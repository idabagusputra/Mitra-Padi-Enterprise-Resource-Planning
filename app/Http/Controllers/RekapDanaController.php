<?php

namespace App\Http\Controllers;

use App\Models\Kredit;
use App\Models\KreditNasabahPalu;
use App\Models\KreditTitipanPetani;
use App\Models\UtangKeOperator;
use App\Models\RekapUtangKeOperator;
use App\Models\RekapKredit;
use App\Models\RekapDana;
use App\Models\RekapDanaTitipanPetani;
use App\Models\RekapKreditNasabahPalu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Google\Client;
use Google\Service\Drive;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Aws\S3\S3Client;
use Illuminate\Http\Response;

class RekapDanaController extends Controller
{
    /**
     * Konfigurasi R2 - dipindahkan ke method terpisah untuk reusability
     */
    private function getR2Client(): S3Client
    {
        return new S3Client([
            'version' => 'latest',
            'region' => 'auto',
            'endpoint' => 'https://c9961806b72189a4d763edfd8dc0e55f.r2.cloudflarestorage.com',
            'credentials' => [
                'key' => env('R2_ACCESS_KEY', '2abc6cf8c76a71e84264efef65031933'),
                'secret' => env('R2_SECRET_KEY', '1aa2ca39d8480cdbf846807ad5a7a1e492e72ee9a947ead03ef5d8ad67dea45d'),
            ]
        ]);
    }

    /**
     * Konfigurasi Google Drive - dipindahkan ke method terpisah
     */
    private function getDriveService(): Drive
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google-drive-credentials.json'));
        $client->addScope(Drive::DRIVE);
        return new Drive($client);
    }

    /**
     * Method untuk menampilkan halaman form input dan data total_kredit_plus_bunga
     */
    public function index()
    {
        $totalKreditPetani = Kredit::calculateTotalKredit();
        $totalKreditNasabahPalu = KreditNasabahPalu::calculateTotalKreditNasabahPalu();
        $totalUtangKeOperator = UtangKeOperator::calculateTotalUtangKeOperator();
        $totalKreditTitipanPetani = KreditTitipanPetani::calculateTotalKreditTitipanPetani();

        return view('rekap-dana', compact(
            'totalKreditTitipanPetani',
            'totalKreditPetani',
            'totalKreditNasabahPalu',
            'totalUtangKeOperator'
        ));
    }

    public function findPdf(Request $request)
    {
        $gilingId = $request->input('gilingId');
        $folderPath = public_path('rekapan_dana');
        $matchingFiles = glob("{$folderPath}/Rekapan_Dana_{$gilingId}_*.pdf");

        return response()->json([
            'pdfPath' => !empty($matchingFiles) ? str_replace(public_path(), '', $matchingFiles[0]) : null
        ]);
    }

    public function indexDaftar()
    {
        $rekapDanaTerbaru = RekapDana::orderBy('id', 'desc')->first();
        $rekapDanas = RekapDana::orderBy('id', 'desc')->paginate(20);

        return view('daftar-rekapan-dana', compact('rekapDanas', 'rekapDanaTerbaru'));
    }

    /**
     * OPTIMASI UTAMA: Method untuk menghitung kredit dengan bunga
     * Dipindahkan ke method terpisah untuk menghindari duplikasi kode
     */
    private function calculateKreditWithBunga($kredits, $now = null)
    {
        $now = $now ?? Carbon::now();

        return $kredits->map(function ($kredit) use ($now) {
            $kreditDate = Carbon::parse($kredit->tanggal);

            // Hitung selisih bulan berdasarkan status
            if ($kredit->status === true) {
                $diffInMonths = $kreditDate->diffInMonths($kredit->updated_at);
            } else {
                $diffInMonths = $kreditDate->diffInMonths($now);
            }

            $diffInMonths = max(0, $diffInMonths);
            $selisihBulan = ceil($diffInMonths * 100) / 100;

            // Calculate bunga dan hutang
            $bunga = $kredit->jumlah * 0.02 * $selisihBulan;
            $hutangPlusBunga = $kredit->jumlah + $bunga;

            // Hitung untuk update
            $diffInMonthsUpdate = $kreditDate->diffInMonths($kredit->updated_at ?? $now);
            if ($kredit->created_at && $kredit->updated_at && $kredit->created_at->eq($kredit->updated_at)) {
                $diffInMonthsUpdate = 0;
            }

            $selisihBulanUpdate = ceil($diffInMonthsUpdate * 100) / 100;
            $bungaUpdate = $kredit->jumlah * 0.02 * $selisihBulanUpdate;
            $hutangPlusBungaUpdate = $kredit->jumlah + $bungaUpdate;

            $kredit->setAttribute('hutang_plus_bunga', $hutangPlusBunga);
            $kredit->setAttribute('hutang_plus_bunga_update', $hutangPlusBungaUpdate);
            $kredit->setAttribute('lama_bulan', $selisihBulan);
            $kredit->setAttribute('lama_bulan_update', $selisihBulanUpdate);
            $kredit->setAttribute('bunga', floor($bunga));
            $kredit->setAttribute('bunga_update', floor($bungaUpdate));

            return $kredit;
        });
    }

    /**
     * OPTIMASI: Method untuk sorting kredit
     */
    private function sortKredits($kredits, $sortOrder = 'desc')
    {
        return $kredits->sortBy(
            function ($item) {
                return [
                    $item->status ? 0 : 1,
                    $item->tanggal,
                    $item->id
                ];
            },
            SORT_REGULAR,
            $sortOrder === 'desc'
        );
    }

    /**
     * OPTIMASI: Method untuk menghitung summary kredit
     */
    private function calculateKreditSummary($calculatedKredits, $groupByField = 'petani_id')
    {
        $kreditsBelumLunas = $calculatedKredits->where('status', 0);
        $kreditsLunas = $calculatedKredits->where('status', 1);

        return [
            'belum_lunas' => [
                'jumlah_petani' => $kreditsBelumLunas->pluck($groupByField)->unique()->count(),
                'total_kredit' => $kreditsBelumLunas->sum('jumlah'),
                'total_plus_bunga' => $kreditsBelumLunas->sum('hutang_plus_bunga'),
            ],
            'lunas' => [
                'jumlah_petani' => $kreditsLunas->pluck($groupByField)->unique()->count(),
                'total_kredit' => $kreditsLunas->sum('jumlah'),
                'total_plus_bunga' => $kreditsLunas->sum('hutang_plus_bunga'),
            ]
        ];
    }

    /**
     * OPTIMASI: Generate HTML untuk Rekap Kredit (tanpa render PDF)
     */
    private function generateHtml_RekapKredit(&$totalKreditBelumLunas)
    {
        $allKredits = Kredit::with('petani')->get();
        $calculatedKredits = $this->calculateKreditWithBunga($allKredits);
        $sortedKredits = $this->sortKredits($calculatedKredits);

        $groupedByPetani = $sortedKredits->groupBy(fn($kredit) => $kredit->petani->nama);
        $summary = $this->calculateKreditSummary($calculatedKredits);

        $totalKreditBelumLunas = $summary['belum_lunas']['total_kredit'];

        return View::make('kreditReport', [
            'groupedKredits' => $groupedByPetani,
            'jumlahPetaniBelumLunas' => $summary['belum_lunas']['jumlah_petani'],
            'totalKreditBelumLunas' => $summary['belum_lunas']['total_kredit'],
            'totalKreditPlusBungaBelumLunas' => $summary['belum_lunas']['total_plus_bunga'],
            'jumlahPetaniLunas' => $summary['lunas']['jumlah_petani'],
            'totalKreditLunas' => $summary['lunas']['total_kredit'],
            'totalKreditPlusBungaLunas' => $summary['lunas']['total_plus_bunga']
        ])->render();
    }

    /**
     * OPTIMASI: Generate HTML untuk Utang Ke Operator (tanpa render PDF)
     */
    private function generateHtml_RekapUtangKeOperator(&$totalKreditBelumLunas)
    {
        $allKredits = UtangKeOperator::with('petani')->get();
        $calculatedKredits = $this->calculateKreditWithBunga($allKredits);
        $sortedKredits = $this->sortKredits($calculatedKredits);

        $groupedByPetani = $sortedKredits->groupBy(fn($kredit) => $kredit->petani->nama);
        $summary = $this->calculateKreditSummary($calculatedKredits);

        $totalKreditBelumLunas = $summary['belum_lunas']['total_kredit'];

        return View::make('utangKeOperatorReport', [
            'groupedKredits' => $groupedByPetani,
            'jumlahPetaniBelumLunas' => $summary['belum_lunas']['jumlah_petani'],
            'totalKreditBelumLunas' => $summary['belum_lunas']['total_kredit'],
            'totalKreditPlusBungaBelumLunas' => $summary['belum_lunas']['total_plus_bunga'],
            'jumlahPetaniLunas' => $summary['lunas']['jumlah_petani'],
            'totalKreditLunas' => $summary['lunas']['total_kredit'],
            'totalKreditPlusBungaLunas' => $summary['lunas']['total_plus_bunga']
        ])->render();
    }

    /**
     * OPTIMASI: Generate HTML untuk Dana Titipan Petani (tanpa render PDF)
     */
    private function generateHtml_RekapDanaTitipanPetani(&$totalKreditBelumLunas)
    {
        $allKredits = KreditTitipanPetani::with('petani')->get();
        $calculatedKredits = $this->calculateKreditWithBunga($allKredits);
        $sortedKredits = $this->sortKredits($calculatedKredits);

        $groupedByPetani = $sortedKredits->groupBy(fn($kredit) => $kredit->petani->nama);
        $summary = $this->calculateKreditSummary($calculatedKredits);

        $totalKreditBelumLunas = $summary['belum_lunas']['total_kredit'];

        return View::make('danaTitipanPetaniReport', [
            'groupedKredits' => $groupedByPetani,
            'jumlahPetaniBelumLunas' => $summary['belum_lunas']['jumlah_petani'],
            'totalKreditBelumLunas' => $summary['belum_lunas']['total_kredit'],
            'totalKreditPlusBungaBelumLunas' => $summary['belum_lunas']['total_plus_bunga'],
            'jumlahPetaniLunas' => $summary['lunas']['jumlah_petani'],
            'totalKreditLunas' => $summary['lunas']['total_kredit'],
            'totalKreditPlusBungaLunas' => $summary['lunas']['total_plus_bunga']
        ])->render();
    }

    /**
     * OPTIMASI: Generate HTML untuk Kredit Nasabah Palu (tanpa render PDF)
     */
    private function generateHtml_RekapKreditNasabahPalu(&$totalKreditBelumLunas)
    {
        $allKredits = KreditNasabahPalu::get();
        $calculatedKredits = $this->calculateKreditWithBunga($allKredits);
        $sortedKredits = $this->sortKredits($calculatedKredits);

        $groupedByPetani = $sortedKredits->groupBy(fn($kredit) => $kredit->nama);

        $kreditsBelumLunas = $calculatedKredits->where('status', 0);
        $kreditsLunas = $calculatedKredits->where('status', 1);

        $totalKreditBelumLunas = $kreditsBelumLunas->sum('jumlah');

        return View::make('kreditNasabahPaluReport', [
            'groupedKredits' => $groupedByPetani,
            'jumlahPetaniBelumLunas' => $kreditsBelumLunas->pluck('nama')->unique()->count(),
            'totalKreditBelumLunas' => $totalKreditBelumLunas,
            'totalKreditPlusBungaBelumLunas' => $kreditsBelumLunas->sum('hutang_plus_bunga'),
            'jumlahPetaniLunas' => $kreditsLunas->pluck('petani_id')->unique()->count(),
            'totalKreditLunas' => $kreditsLunas->sum('jumlah'),
            'totalKreditPlusBungaLunas' => $kreditsLunas->sum('hutang_plus_bunga')
        ])->render();
    }

    /**
     * OPTIMASI: Generate HTML untuk Rekap Dana (tanpa render PDF)
     */
    private function generateHtml_RekapDana($rekapDana)
    {
        $kelompok1Total = $rekapDana->getKelompok1Total();
        $kelompok2Total = $rekapDana->getKelompok2Total();
        $kelompok3Total = $rekapDana->getKelompok3Total();
        $rekapanDana = $rekapDana->calculateRekapanDana();

        return View::make('rekapanDanaPDF', [
            'created_at' => $rekapDana->created_at,
            'bri' => $rekapDana->bri,
            'bni' => $rekapDana->bni,
            'tunai' => $rekapDana->tunai,
            'mama' => $rekapDana->mama,
            'total_kredit' => $rekapDana->total_kredit,
            'nasabah_palu' => $rekapDana->nasabah_palu,
            'stok_beras_jumlah' => $rekapDana->stok_beras_jumlah,
            'stok_beras_harga' => $rekapDana->stok_beras_harga,
            'stok_beras_total' => $rekapDana->stok_beras_total,
            'ongkos_jemur_jumlah' => $rekapDana->ongkos_jemur_jumlah,
            'ongkos_jemur_harga' => $rekapDana->ongkos_jemur_harga,
            'ongkos_jemur_total' => $rekapDana->ongkos_jemur_total,
            'beras_terpinjam_jumlah' => $rekapDana->beras_terpinjam_jumlah,
            'beras_terpinjam_harga' => $rekapDana->beras_terpinjam_harga,
            'beras_terpinjam_total' => $rekapDana->beras_terpinjam_total,
            'pinjaman_bank' => $rekapDana->pinjaman_bank,
            'titipan_petani' => $rekapDana->titipan_petani,
            'utang_beras' => $rekapDana->utang_beras,
            'utang_ke_operator' => $rekapDana->utang_ke_operator,
            'kelompok1Total' => $kelompok1Total,
            'kelompok2Total' => $kelompok2Total,
            'kelompok3Total' => $kelompok3Total,
            'rekapan_dana' => $rekapanDana,
            'viewKelompok1Total' => $kelompok1Total,
            'viewKelompok2Total' => $kelompok2Total,
            'viewKelompok3Total' => $kelompok3Total,
        ])->render();
    }

    /**
     * OPTIMASI: Upload ke R2 dengan error handling yang lebih baik
     */
    private function uploadToR2($pdfContent, $fileName, $folder)
    {
        try {
            $r2Client = $this->getR2Client();
            $r2FileName = "{$folder}/{$fileName}";

            $r2Client->putObject([
                'Bucket' => 'mitra-padi',
                'Body' => $pdfContent,
                'Key' => $r2FileName,
                'ContentType' => 'application/pdf',
                'ContentDisposition' => 'inline',
                'ACL' => 'public-read'
            ]);

            return "https://pub-b2576acededb43e08e7292257cd6a4c8.r2.dev/{$r2FileName}";
        } catch (\Exception $e) {
            Log::error('R2 Upload failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * OPTIMASI: Upload ke Google Drive dengan error handling yang lebih baik
     */
    private function uploadToGoogleDrive($pdfContent, $fileName, $folderId)
    {
        try {
            $driveService = $this->getDriveService();

            // Verify folder access
            $driveService->files->get($folderId, ['fields' => 'id,name']);

            $fileMetadata = new Drive\DriveFile([
                'name' => $fileName,
                'parents' => [$folderId]
            ]);

            $file = $driveService->files->create($fileMetadata, [
                'data' => $pdfContent,
                'mimeType' => 'application/pdf',
                'uploadType' => 'multipart',
                'fields' => 'id,webViewLink'
            ]);

            return $file;
        } catch (\Exception $e) {
            Log::error('Google Drive Upload failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * OPTIMASI UTAMA: Generate PDF gabungan dengan satu kali render
     * Menggabungkan semua HTML menjadi satu dokumen dengan page-break
     */
    public function generatePdfRekapDana_Optimized($rekapId)
    {
        $rekapDana = RekapDana::findOrFail($rekapId);

        if (!$rekapDana) {
            Log::error("Rekap Dana not found for ID: {$rekapId}");
            abort(404, 'Data Rekap Dana tidak ditemukan.');
        }

        // Variables untuk menyimpan total
        $totalKredit = 0;
        $totalUtangOperator = 0;
        $totalTitipanPetani = 0;
        $totalNasabahPalu = 0;

        // Generate semua HTML sekaligus
        $htmlRekapDana = $this->generateHtml_RekapDana($rekapDana);
        $htmlRekapKredit = $this->generateHtml_RekapKredit($totalKredit);
        $htmlUtangOperator = $this->generateHtml_RekapUtangKeOperator($totalUtangOperator);
        $htmlTitipanPetani = $this->generateHtml_RekapDanaTitipanPetani($totalTitipanPetani);
        $htmlNasabahPalu = $this->generateHtml_RekapKreditNasabahPalu($totalNasabahPalu);

        // Simpan ke database rekap masing-masing
        $rekapKreditDB = RekapKredit::create(['rekapan_kredit' => $totalKredit]);
        $rekapUtangDB = RekapUtangKeOperator::create(['rekapan_utang_ke_operator' => $totalUtangOperator]);
        $rekapTitipanDB = RekapDanaTitipanPetani::create(['rekapan_dana_titipan_petani' => $totalTitipanPetani]);
        $rekapNasabahDB = RekapKreditNasabahPalu::create(['rekapan_kredit_nasabah_palu' => $totalNasabahPalu]);

        // CSS global untuk semua halaman
        $globalCss = '
        <style>
            @page {
                margin: 6mm;
            }
            body {
                font-family: sans-serif;
                margin: 0;
                font-size: 10pt;
                line-height: 1.2;
            }
            * {
                box-sizing: border-box;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            .text-center { text-align: center; }
            .text-right { text-align: right; }
            .font-bold { font-weight: bold; }
            .total { font-weight: bold; text-align: right; }

            /* Page break untuk memisahkan setiap laporan */
            .page-break {
                page-break-after: always;
                page-break-inside: avoid;
            }

            /* Hapus page break di halaman terakhir */
            .page-break:last-child {
                page-break-after: auto;
            }
        </style>';

        // Gabungkan semua HTML dengan page-break
        $combinedHtml = $globalCss . '
            <div class="page-break">' . $htmlRekapDana . '</div>
            <div class="page-break">' . $htmlRekapKredit . '</div>
            <div class="page-break">' . $htmlUtangOperator . '</div>
            <div class="page-break">' . $htmlTitipanPetani . '</div>
            <div>' . $htmlNasabahPalu . '</div>
        ';

        // Setup Dompdf - HANYA SATU KALI
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'sans-serif');
        $options->set('isFontSubsettingEnabled', true);
        $options->set('defaultMediaType', 'print');
        $options->set('dpi', 96);

        // Optimasi untuk Domainesia shared hosting
        $options->set('isPhpEnabled', false); // Disable PHP dalam PDF untuk keamanan
        $options->set('debugKeepTemp', false); // Jangan simpan file temp

        $dompdf = new Dompdf($options);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->loadHtml($combinedHtml);

        // Render PDF - HANYA SATU KALI
        $dompdf->render();
        $pdfContent = $dompdf->output();

        // Bersihkan memory
        unset($combinedHtml, $htmlRekapDana, $htmlRekapKredit, $htmlUtangOperator, $htmlTitipanPetani, $htmlNasabahPalu);
        gc_collect_cycles();

        $timestamp = date('Y-m-d_H-i-s');
        $pdfFileName = "Rekapan_Dana_{$rekapDana->id}_{$timestamp}.pdf";

        // Simpan file lokal
        $pdfPath = public_path('rekapan_dana');
        if (!file_exists($pdfPath)) {
            mkdir($pdfPath, 0755, true);
        }
        file_put_contents($pdfPath . '/' . $pdfFileName, $pdfContent);

        try {
            // Upload ke R2 - HANYA SATU KALI untuk PDF gabungan
            $r2Url = $this->uploadToR2($pdfContent, $pdfFileName, 'Laporan_Dana');
            if ($r2Url) {
                $rekapDana->s3_url = $r2Url;
                $rekapDana->save();

                // Update juga untuk rekap lainnya dengan URL yang sama atau buat URL terpisah jika diperlukan
                $rekapKreditDB->s3_url = $r2Url;
                $rekapKreditDB->save();

                $rekapUtangDB->s3_url = $r2Url;
                $rekapUtangDB->save();

                $rekapTitipanDB->s3_url = $r2Url;
                $rekapTitipanDB->save();

                $rekapNasabahDB->s3_url = $r2Url;
                $rekapNasabahDB->save();
            }

            // Upload ke Google Drive - HANYA SATU KALI
            $this->uploadToGoogleDrive($pdfContent, $pdfFileName . '.pdf', '104G4glHVz6jE1iqk0-f5s0sN-pU0THpv');

            // Bersihkan memory
            unset($pdfContent);
            gc_collect_cycles();

            return redirect()->route('rekapDana.index')
                ->with('success', 'Rekapan Dana berhasil dibuat.')
                ->with('newGilingId', $rekapDana->id);
        } catch (\Exception $e) {
            Log::error('PDF Generation or Upload failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Method lama untuk backward compatibility - generate PDF terpisah
     * Gunakan ini jika Anda masih membutuhkan PDF individual
     */
    public function generatePdf_RekapKredit()
    {
        $totalKredit = 0;
        $html = $this->generateHtml_RekapKredit($totalKredit);

        $rekapKreditDB = RekapKredit::create(['rekapan_kredit' => $totalKredit]);

        return $this->renderAndUploadSinglePdf(
            $html,
            "Rekapan_Kredit_{$rekapKreditDB->id}_" . date('Y-m-d_H-i-s') . ".pdf",
            'rekapan_kredit',
            'Laporan_Kredit',
            '1SfsBsgclo-omwnicyM2pN06RGj_7vQ9K',
            $rekapKreditDB
        );
    }

    public function generatePdf_RekapUtangKeOperator()
    {
        $totalUtang = 0;
        $html = $this->generateHtml_RekapUtangKeOperator($totalUtang);

        $rekapDB = RekapUtangKeOperator::create(['rekapan_utang_ke_operator' => $totalUtang]);

        return $this->renderAndUploadSinglePdf(
            $html,
            "Rekapan_Utang_Ke_Operator_{$rekapDB->id}_" . date('Y-m-d_H-i-s') . ".pdf",
            'rekapan_utang_ke_operator',
            'Laporan_Utang_Ke_Operator',
            '1stzfcR6OSdpBT0yb13WHFFl4_jsO08la',
            $rekapDB
        );
    }

    public function generatePdf_RekapDanaTitipanPetani()
    {
        $totalTitipan = 0;
        $html = $this->generateHtml_RekapDanaTitipanPetani($totalTitipan);

        $rekapDB = RekapDanaTitipanPetani::create(['rekapan_dana_titipan_petani' => $totalTitipan]);

        return $this->renderAndUploadSinglePdf(
            $html,
            "Rekapan_Dana_Titipan_Petani_{$rekapDB->id}_" . date('Y-m-d_H-i-s') . ".pdf",
            'rekapan_dana_titipan_petani',
            'Laporan_Dana_Titipan_Petani',
            '130_zniBFi1q6Us_F1QWwG5ziHT0gDN_f',
            $rekapDB
        );
    }

    public function generatePdf_RekapKreditNasabahPalu()
    {
        $totalNasabah = 0;
        $html = $this->generateHtml_RekapKreditNasabahPalu($totalNasabah);

        $rekapDB = RekapKreditNasabahPalu::create(['rekapan_kredit_nasabah_palu' => $totalNasabah]);

        return $this->renderAndUploadSinglePdf(
            $html,
            "Rekapan_Kredit_Nasabah_Palu_{$rekapDB->id}_" . date('Y-m-d_H-i-s') . ".pdf",
            'rekapan_kredit_nasabah_palu',
            'Laporan_Kredit_Nasabah_Palu_',
            '1UsDnhEL56lVNDK1F1BW5W5mgbpngM1ft',
            $rekapDB
        );
    }

    public function generatePdf_RekapDana($rekapId)
    {
        $rekapDana = RekapDana::findOrFail($rekapId);
        $html = $this->generateHtml_RekapDana($rekapDana);

        $defaultCss = '
        <style>
            @page { margin: 0mm 6mm 6mm 6mm; }
            body { font-family: sans-serif; margin: 0; font-size: 10pt; line-height: 1; }
            * { box-sizing: border-box; }
            table { width: 100%; }
            .text-center { text-align: center; }
            .text-right { text-align: right; }
            .font-bold { font-weight: bold; }
            .total { font-weight: bold; text-align: right; }
        </style>';

        $html = $defaultCss . $html;

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'sans-serif');
        $options->set('isFontSubsettingEnabled', true);
        $options->set('defaultMediaType', 'print');
        $options->set('dpi', 96);

        $dompdf = new Dompdf($options);
        $marginInPoints = 2 * 2.83464567;
        $dompdf->setPaper('A4', 'portrait', [
            'margin-top' => $marginInPoints,
            'margin-right' => $marginInPoints,
            'margin-bottom' => $marginInPoints,
            'margin-left' => $marginInPoints
        ]);

        $dompdf->loadHtml($html);
        $dompdf->render();

        return $dompdf->output();
    }

    /**
     * Helper method untuk render dan upload single PDF
     */
    private function renderAndUploadSinglePdf($html, $fileName, $localFolder, $r2Folder, $driveFolderId, $dbModel)
    {
        $defaultCss = '
        <style>
            @page { margin: 6mm; }
            body { font-family: sans-serif; margin: 0; font-size: 10pt; line-height: 1.2; }
            * { box-sizing: border-box; }
            table { width: 100%; border-collapse: collapse; }
            .text-center { text-align: center; }
            .text-right { text-align: right; }
            .font-bold { font-weight: bold; }
            .total { font-weight: bold; text-align: right; }
        </style>';

        $html = $defaultCss . $html;

        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isPhpEnabled', false);
        $options->set('debugKeepTemp', false);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdfContent = $dompdf->output();

        // Simpan lokal
        $pdfPath = public_path($localFolder);
        if (!file_exists($pdfPath)) {
            mkdir($pdfPath, 0755, true);
        }
        file_put_contents($pdfPath . '/' . $fileName, $pdfContent);

        try {
            // Upload R2
            $r2Url = $this->uploadToR2($pdfContent, $fileName, $r2Folder);
            if ($r2Url) {
                $dbModel->s3_url = $r2Url;
                $dbModel->save();
            }

            // Upload Google Drive
            $this->uploadToGoogleDrive($pdfContent, $fileName, $driveFolderId);

            return $pdfContent;
        } catch (\Exception $e) {
            Log::error('PDF Generation or Upload failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Store method - OPTIMIZED
     */
    public function store(Request $request)
    {
        // Optimasi untuk Domainesia
        ini_set('max_execution_time', 300); // 5 menit maksimal
        ini_set('memory_limit', '256M'); // Cukup untuk kebanyakan kasus

        $validator = Validator::make($request->all(), [
            'bri' => 'required|numeric',
            'bni' => 'required|numeric',
            'tunai' => 'required|numeric',
            'mama' => 'required|numeric',
            'stok_beras_jumlah' => 'required|numeric',
            'beras_terpinjam_jumlah' => 'required|numeric',
            'ongkos_jemur_jumlah' => 'required|numeric',
            'stok_beras_harga' => 'required|numeric',
            'beras_terpinjam_harga' => 'required|numeric',
            'ongkos_jemur_harga' => 'required|numeric',
            'pinjaman_bank' => 'required|numeric',
            'utang_beras' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $totalKreditPetani = Kredit::calculateTotalKredit();
        $totalKreditNasabahPalu = KreditNasabahPalu::calculateTotalKreditNasabahPalu();
        $totalUtangKeOperator = UtangKeOperator::calculateTotalUtangKeOperator();
        $totalKreditTitipanPetani = KreditTitipanPetani::calculateTotalKreditTitipanPetani();

        $data = $request->all();

        $stokBerasTotal = $data['stok_beras_jumlah'] * $data['stok_beras_harga'];
        $ongkosJemurTotal = $data['ongkos_jemur_jumlah'] * $data['ongkos_jemur_harga'];
        $berasTerpinjamTotal = $data['beras_terpinjam_jumlah'] * $data['beras_terpinjam_harga'];

        $kelompok1Total = $data['bri'] + $data['bni'] + $data['tunai'] + $data['mama'];
        $kelompok2Total = $stokBerasTotal + $ongkosJemurTotal + $berasTerpinjamTotal;
        $kelompok3Total = $data['pinjaman_bank'] + $totalKreditTitipanPetani + $data['utang_beras'] + $totalUtangKeOperator;

        $rekapanDana = $kelompok1Total + $kelompok2Total + $totalKreditNasabahPalu + $totalKreditPetani - $kelompok3Total;

        $data['total_kredit'] = $totalKreditPetani;
        $data['titipan_petani'] = $totalKreditTitipanPetani;
        $data['utang_ke_operator'] = $totalUtangKeOperator;
        $data['nasabah_palu'] = $totalKreditNasabahPalu;
        $data['stok_beras_total'] = $stokBerasTotal;
        $data['ongkos_jemur_total'] = $ongkosJemurTotal;
        $data['beras_terpinjam_total'] = $berasTerpinjamTotal;
        $data['rekapan_dana'] = $rekapanDana;

        $rekapDana = RekapDana::create($data);

        // Gunakan method yang sudah dioptimasi
        return $this->generatePdfRekapDana_Optimized($rekapDana->id);
    }
}
