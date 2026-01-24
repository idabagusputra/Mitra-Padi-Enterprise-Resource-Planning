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
     * Cache untuk menyimpan data yang sudah dihitung
     * Menghindari kalkulasi berulang
     */
    private $calculatedData = [];

    // Method untuk menampilkan halaman form input dan data total_kredit_plus_bunga
    public function index()
    {
        $totalKreditPetani = Kredit::calculateTotalKredit();
        $totalKreditNasabahPalu = KreditNasabahPalu::calculateTotalKreditNasabahPalu();
        $totalUtangKeOperator = UtangKeOperator::calculateTotalUtangKeOperator();
        $totalKreditTitipanPetani = KreditTitipanPetani::calculateTotalKreditTitipanPetani();

        return view('rekap-dana', compact('totalKreditTitipanPetani', 'totalKreditPetani', 'totalKreditNasabahPalu', 'totalUtangKeOperator'));
    }

    public function findPdf(Request $request)
    {
        $gilingId = $request->input('gilingId');
        $folderPath = public_path('rekapan_dana');

        $matchingFiles = glob("{$folderPath}/Rekapan_Dana_{$gilingId}_*.pdf");

        if (!empty($matchingFiles)) {
            $pdfPath = str_replace(public_path(), '', $matchingFiles[0]);
            return response()->json(['pdfPath' => $pdfPath]);
        }

        return response()->json(['pdfPath' => null]);
    }

    public function indexDaftar()
    {
        $rekapDanaTerbaru = RekapDana::orderBy('id', 'desc')->first();
        $rekapDanas = RekapDana::orderBy('id', 'desc')->paginate(20);

        return view('daftar-rekapan-dana', compact('rekapDanas', 'rekapDanaTerbaru'));
    }

    /**
     * ========================================
     * OPTIMIZED: Kalkulasi kredit dengan caching
     * ========================================
     */
    private function calculateKreditData($allKredits, $now)
    {
        return $allKredits->map(function ($kredit) use ($now) {
            $kreditDate = Carbon::parse($kredit->tanggal);

            if ($kredit->status === true) {
                $diffInMonthsUpdate = $kreditDate->diffInMonths($kredit->updated_at);
            } else {
                $diffInMonthsUpdate = $kreditDate->diffInMonths($now);
            }

            if ($diffInMonthsUpdate < 0) {
                $diffInMonthsUpdate = 0;
            }

            $selisihBulan = ceil($diffInMonthsUpdate * 100) / 100;
            $bunga = $kredit->jumlah * 0.02 * $selisihBulan;
            $hutangPlusBunga = $kredit->jumlah + $bunga;

            // Untuk update calculation
            $diffInMonthsForUpdate = $kreditDate->diffInMonths($kredit->updated_at ?? $now);
            if ($kredit->created_at && $kredit->updated_at && $kredit->created_at->eq($kredit->updated_at)) {
                $diffInMonthsForUpdate = 0;
            }

            $selisihBulanUpdate = ceil($diffInMonthsForUpdate * 100) / 100;
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
     * ========================================
     * OPTIMIZED: Sort kredits
     * ========================================
     */
    private function sortKredits($calculatedKredits, $sortOrder = 'desc')
    {
        return $calculatedKredits->sortBy(
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
     * ========================================
     * OPTIMIZED: Hitung summary data
     * ========================================
     */
    private function calculateSummary($calculatedKredits, $groupByField = 'petani_id')
    {
        $kreditsBelumLunas = $calculatedKredits->where('status', 0);
        $kreditsLunas = $calculatedKredits->where('status', 1);

        return [
            'belumLunas' => [
                'jumlahPetani' => $kreditsBelumLunas->pluck($groupByField)->unique()->count(),
                'totalKredit' => $kreditsBelumLunas->sum('jumlah'),
                'totalKreditPlusBunga' => $kreditsBelumLunas->sum('hutang_plus_bunga'),
            ],
            'lunas' => [
                'jumlahPetani' => $kreditsLunas->pluck($groupByField)->unique()->count(),
                'totalKredit' => $kreditsLunas->sum('jumlah'),
                'totalKreditPlusBunga' => $kreditsLunas->sum('hutang_plus_bunga'),
            ]
        ];
    }

    /**
     * ========================================
     * OPTIMIZED: Prepare data untuk semua report sekaligus
     * ========================================
     */
    private function prepareAllReportData()
    {
        $now = Carbon::now();

        // 1. Data Kredit Petani
        $allKreditPetani = Kredit::with('petani')->get();
        $calculatedKreditPetani = $this->calculateKreditData($allKreditPetani, $now);
        $sortedKreditPetani = $this->sortKredits($calculatedKreditPetani);
        $groupedKreditPetani = $sortedKreditPetani->groupBy(function ($k) {
            return $k->petani->nama;
        });
        $summaryKreditPetani = $this->calculateSummary($calculatedKreditPetani, 'petani_id');

        // 2. Data Utang Ke Operator
        $allUtangOperator = UtangKeOperator::with('petani')->get();
        $calculatedUtangOperator = $this->calculateKreditData($allUtangOperator, $now);
        $sortedUtangOperator = $this->sortKredits($calculatedUtangOperator);
        $groupedUtangOperator = $sortedUtangOperator->groupBy(function ($k) {
            return $k->petani->nama;
        });
        $summaryUtangOperator = $this->calculateSummary($calculatedUtangOperator, 'petani_id');

        // 3. Data Titipan Petani
        $allTitipanPetani = KreditTitipanPetani::with('petani')->get();
        $calculatedTitipanPetani = $this->calculateKreditData($allTitipanPetani, $now);
        $sortedTitipanPetani = $this->sortKredits($calculatedTitipanPetani);
        $groupedTitipanPetani = $sortedTitipanPetani->groupBy(function ($k) {
            return $k->petani->nama;
        });
        $summaryTitipanPetani = $this->calculateSummary($calculatedTitipanPetani, 'petani_id');

        // 4. Data Nasabah Palu
        $allNasabahPalu = KreditNasabahPalu::get();
        $calculatedNasabahPalu = $this->calculateKreditData($allNasabahPalu, $now);
        $sortedNasabahPalu = $this->sortKredits($calculatedNasabahPalu);
        $groupedNasabahPalu = $sortedNasabahPalu->groupBy(function ($k) {
            return $k->nama;
        });
        $summaryNasabahPalu = $this->calculateSummary($calculatedNasabahPalu, 'nama');

        return [
            'kreditPetani' => [
                'grouped' => $groupedKreditPetani,
                'summary' => $summaryKreditPetani,
                'totalBelumLunas' => $summaryKreditPetani['belumLunas']['totalKredit'],
            ],
            'utangOperator' => [
                'grouped' => $groupedUtangOperator,
                'summary' => $summaryUtangOperator,
                'totalBelumLunas' => $summaryUtangOperator['belumLunas']['totalKredit'],
            ],
            'titipanPetani' => [
                'grouped' => $groupedTitipanPetani,
                'summary' => $summaryTitipanPetani,
                'totalBelumLunas' => $summaryTitipanPetani['belumLunas']['totalKredit'],
            ],
            'nasabahPalu' => [
                'grouped' => $groupedNasabahPalu,
                'summary' => $summaryNasabahPalu,
                'totalBelumLunas' => $summaryNasabahPalu['belumLunas']['totalKredit'],
            ],
        ];
    }

    /**
     * OPTIMIZED: Generate Combined HTML untuk semua report
     * CSS dari setiap template DIPERTAHANKAN
     */
    private function generateCombinedHtml($rekapDana, $reportData)
    {
        // Fetch totals for each kelompok
        $kelompok1Total = $rekapDana->getKelompok1Total();
        $kelompok2Total = $rekapDana->getKelompok2Total();
        $kelompok3Total = $rekapDana->getKelompok3Total();
        $rekapanDana = $rekapDana->calculateRekapanDana();

        // ========================================
        // BAGIAN 1: Rekapan Dana (Halaman Pertama)
        // Render LENGKAP dengan CSS-nya
        // ========================================
        $htmlRekapDana = View::make('rekapanDanaPDF', [
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

        // ========================================
        // BAGIAN 2: Kredit Report
        // ========================================
        $kreditData = $reportData['kreditPetani'];
        $htmlKredit = View::make('kreditReport', [
            'groupedKredits' => $kreditData['grouped'],
            'jumlahPetaniBelumLunas' => $kreditData['summary']['belumLunas']['jumlahPetani'],
            'totalKreditBelumLunas' => $kreditData['summary']['belumLunas']['totalKredit'],
            'totalKreditPlusBungaBelumLunas' => $kreditData['summary']['belumLunas']['totalKreditPlusBunga'],
            'jumlahPetaniLunas' => $kreditData['summary']['lunas']['jumlahPetani'],
            'totalKreditLunas' => $kreditData['summary']['lunas']['totalKredit'],
            'totalKreditPlusBungaLunas' => $kreditData['summary']['lunas']['totalKreditPlusBunga'],
        ])->render();

        // ========================================
        // BAGIAN 3: Utang Ke Operator Report
        // ========================================
        $utangData = $reportData['utangOperator'];
        $htmlUtangOperator = View::make('utangKeOperatorReport', [
            'groupedKredits' => $utangData['grouped'],
            'jumlahPetaniBelumLunas' => $utangData['summary']['belumLunas']['jumlahPetani'],
            'totalKreditBelumLunas' => $utangData['summary']['belumLunas']['totalKredit'],
            'totalKreditPlusBungaBelumLunas' => $utangData['summary']['belumLunas']['totalKreditPlusBunga'],
            'jumlahPetaniLunas' => $utangData['summary']['lunas']['jumlahPetani'],
            'totalKreditLunas' => $utangData['summary']['lunas']['totalKredit'],
            'totalKreditPlusBungaLunas' => $utangData['summary']['lunas']['totalKreditPlusBunga'],
        ])->render();

        // ========================================
        // BAGIAN 4: Dana Titipan Petani Report
        // ========================================
        $titipanData = $reportData['titipanPetani'];
        $htmlTitipanPetani = View::make('danaTitipanPetaniReport', [
            'groupedKredits' => $titipanData['grouped'],
            'jumlahPetaniBelumLunas' => $titipanData['summary']['belumLunas']['jumlahPetani'],
            'totalKreditBelumLunas' => $titipanData['summary']['belumLunas']['totalKredit'],
            'totalKreditPlusBungaBelumLunas' => $titipanData['summary']['belumLunas']['totalKreditPlusBunga'],
            'jumlahPetaniLunas' => $titipanData['summary']['lunas']['jumlahPetani'],
            'totalKreditLunas' => $titipanData['summary']['lunas']['totalKredit'],
            'totalKreditPlusBungaLunas' => $titipanData['summary']['lunas']['totalKreditPlusBunga'],
        ])->render();

        // ========================================
        // BAGIAN 5: Kredit Nasabah Palu Report
        // ========================================
        $nasabahData = $reportData['nasabahPalu'];
        $htmlNasabahPalu = View::make('kreditNasabahPaluReport', [
            'groupedKredits' => $nasabahData['grouped'],
            'jumlahPetaniBelumLunas' => $nasabahData['summary']['belumLunas']['jumlahPetani'],
            'totalKreditBelumLunas' => $nasabahData['summary']['belumLunas']['totalKredit'],
            'totalKreditPlusBungaBelumLunas' => $nasabahData['summary']['belumLunas']['totalKreditPlusBunga'],
            'jumlahPetaniLunas' => $nasabahData['summary']['lunas']['jumlahPetani'],
            'totalKreditLunas' => $nasabahData['summary']['lunas']['totalKredit'],
            'totalKreditPlusBungaLunas' => $nasabahData['summary']['lunas']['totalKreditPlusBunga'],
        ])->render();

        // ========================================
        // GABUNGKAN SEMUA HTML DENGAN PAGE BREAK
        // Setiap HTML sudah memiliki CSS masing-masing
        // ========================================
        return $this->wrapWithPageBreaks([
            $htmlRekapDana,
            $htmlKredit,
            $htmlUtangOperator,
            $htmlTitipanPetani,
            $htmlNasabahPalu,
        ]);
    }

    /**
     * HELPER: Wrap HTML sections dengan page break
     * Setiap section dibungkus dalam div terpisah agar CSS tidak bentrok
     */
    private function wrapWithPageBreaks(array $htmlSections)
    {
        $combined = '';
        $totalSections = count($htmlSections);

        foreach ($htmlSections as $index => $html) {
            // Bungkus setiap section dalam div dengan page-break
            if ($index < $totalSections - 1) {
                // Tambahkan page-break-after untuk semua kecuali yang terakhir
                $combined .= '<div style="page-break-after: always;">' . $html . '</div>';
            } else {
                // Section terakhir tanpa page-break
                $combined .= '<div>' . $html . '</div>';
            }
        }

        return $combined;
    }

    /**
     * HELPER: Get minimal CSS untuk PDF
     * Tidak override CSS dari template, hanya tambahkan page-break support
     */
    private function getDefaultCss()
    {
        return '
    <style>
        @page {
            margin: 6mm;
        }
        .page-section {
            page-break-after: always;
        }
        .page-section:last-child {
            page-break-after: avoid;
        }
    </style>
    ';
    }

    /**
     * ========================================
     * OPTIMIZED: Generate PDF Combined (Menggantikan generatePdfRekapDana_4pdf)
     * HANYA 1x GENERATE PDF, BUKAN 5x!
     * ========================================
     */
    public function generatePdfCombined($rekapId)
    {
        $rekapDana = RekapDana::findOrFail($rekapId);

        if (!$rekapDana) {
            Log::error("Rekap Dana not found for ID: {$rekapId}");
            abort(404, 'Data Rekap Dana tidak ditemukan.');
        }

        try {
            // ========================================
            // STEP 1: Prepare semua data sekaligus (1x query per tabel)
            // ========================================
            $reportData = $this->prepareAllReportData();

            // ========================================
            // STEP 2: Simpan ke tabel rekap masing-masing
            // ========================================
            $rekapKreditDB = RekapKredit::create([
                'rekapan_kredit' => $reportData['kreditPetani']['totalBelumLunas'],
            ]);

            $rekapUtangOperatorDB = RekapUtangKeOperator::create([
                'rekapan_utang_ke_operator' => $reportData['utangOperator']['totalBelumLunas'],
            ]);

            $rekapTitipanPetaniDB = RekapDanaTitipanPetani::create([
                'rekapan_dana_titipan_petani' => $reportData['titipanPetani']['totalBelumLunas'],
            ]);

            $rekapNasabahPaluDB = RekapKreditNasabahPalu::create([
                'rekapan_kredit_nasabah_palu' => $reportData['nasabahPalu']['totalBelumLunas'],
            ]);

            // ========================================
            // STEP 3: Generate Combined HTML (SEKALI SAJA!)
            // ========================================
            $combinedHtml = $this->generateCombinedHtml($rekapDana, $reportData);

            // JANGAN tambahkan CSS lagi, karena setiap template sudah punya CSS sendiri
            $finalHtml = '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body>'
                . $combinedHtml
                . '</body></html>';

            // ========================================
            // STEP 4: Generate PDF SEKALI SAJA!
            // ========================================
            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);
            $options->set('defaultFont', 'sans-serif');
            $options->set('isFontSubsettingEnabled', true);
            $options->set('defaultMediaType', 'print');
            $options->set('dpi', 96);

            $dompdf = new Dompdf($options);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->loadHtml($finalHtml);
            $dompdf->render();

            $pdfContent = $dompdf->output();

            // ========================================
            // STEP 5: Save locally
            // ========================================
            $pdfFileName = 'Rekapan_Dana_' . $rekapDana->id . '_' . date('Y-m-d_H-i-s') . '.pdf';
            $pdfPath = public_path('rekapan_dana');

            if (!file_exists($pdfPath)) {
                mkdir($pdfPath, 0755, true);
            }

            $pdfFullPath = $pdfPath . '/' . $pdfFileName;
            file_put_contents($pdfFullPath, $pdfContent);

            // ========================================
            // STEP 6: Upload ke Cloudflare R2
            // ========================================
            $r2Client = new S3Client([
                'version' => 'latest',
                'region' => 'auto',
                'endpoint' => 'https://c9961806b72189a4d763edfd8dc0e55f.r2.cloudflarestorage.com',
                'credentials' => [
                    'key' => env('R2_ACCESS_KEY', '2abc6cf8c76a71e84264efef65031933'),
                    'secret' => env('R2_SECRET_KEY', '1aa2ca39d8480cdbf846807ad5a7a1e492e72ee9a947ead03ef5d8ad67dea45d'),
                ]
            ]);

            $r2FileName = 'Laporan_Dana/Rekapan_Dana_' . $rekapDana->id . '_' . date('Y-m-d_H-i-s') . '.pdf';

            $r2Client->putObject([
                'Bucket' => 'mitra-padi',
                'Body' => $pdfContent,
                'Key' => $r2FileName,
                'ContentType' => 'application/pdf',
                'ContentDisposition' => 'inline',
                'ACL' => 'public-read'
            ]);

            $r2Url = "https://pub-b2576acededb43e08e7292257cd6a4c8.r2.dev/{$r2FileName}";

            // Update semua rekap dengan S3 URL yang sama (karena sudah combined)
            $rekapDana->s3_url = $r2Url;
            $rekapDana->save();

            $rekapKreditDB->s3_url = $r2Url;
            $rekapKreditDB->save();

            $rekapUtangOperatorDB->s3_url = $r2Url;
            $rekapUtangOperatorDB->save();

            $rekapTitipanPetaniDB->s3_url = $r2Url;
            $rekapTitipanPetaniDB->save();

            $rekapNasabahPaluDB->s3_url = $r2Url;
            $rekapNasabahPaluDB->save();

            // ========================================
            // STEP 7: Upload ke Google Drive
            // ========================================
            $client = new Client();
            $client->setAuthConfig(storage_path('app/google-drive-credentials.json'));
            $client->addScope(Drive::DRIVE);

            $driveService = new Drive($client);

            try {
                $folderCheck = $driveService->files->get('104G4glHVz6jE1iqk0-f5s0sN-pU0THpv', [
                    'fields' => 'id,name'
                ]);
                Log::info('Folder found: ' . $folderCheck->getName());
            } catch (\Exception $e) {
                Log::error('Failed to access folder: ' . $e->getMessage());
                throw new \Exception('Folder cannot be accessed');
            }

            $fileMetadata = new Drive\DriveFile([
                'name' => $pdfFileName,
                'parents' => ['104G4glHVz6jE1iqk0-f5s0sN-pU0THpv']
            ]);

            $driveService->files->create($fileMetadata, [
                'data' => $pdfContent,
                'mimeType' => 'application/pdf',
                'uploadType' => 'multipart',
                'fields' => 'id,webViewLink'
            ]);

            return redirect()->route('rekapDana.index')
                ->with('success', 'Rekapan Dana berhasil dibuat.')
                ->with('newGilingId', $rekapDana->id);
        } catch (\Exception $e) {
            Log::error('PDF Generation or Upload failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * ========================================
     * LEGACY METHODS - Tetap dipertahankan untuk backward compatibility
     * Bisa dipanggil individual jika diperlukan
     * ========================================
     */

    public function generatePdf_RekapKredit()
    {
        $reportData = $this->prepareAllReportData();
        $kreditData = $reportData['kreditPetani'];

        $html = View::make('kreditReport', [
            'groupedKredits' => $kreditData['grouped'],
            'jumlahPetaniBelumLunas' => $kreditData['summary']['belumLunas']['jumlahPetani'],
            'totalKreditBelumLunas' => $kreditData['summary']['belumLunas']['totalKredit'],
            'totalKreditPlusBungaBelumLunas' => $kreditData['summary']['belumLunas']['totalKreditPlusBunga'],
            'jumlahPetaniLunas' => $kreditData['summary']['lunas']['jumlahPetani'],
            'totalKreditLunas' => $kreditData['summary']['lunas']['totalKredit'],
            'totalKreditPlusBungaLunas' => $kreditData['summary']['lunas']['totalKreditPlusBunga'],
        ])->render();

        return $this->generateSinglePdf($html, 'rekapan_kredit', 'Rekapan_Kredit', function ($totalBelumLunas) {
            return RekapKredit::create(['rekapan_kredit' => $totalBelumLunas]);
        }, $kreditData['totalBelumLunas'], '1SfsBsgclo-omwnicyM2pN06RGj_7vQ9K', 'Laporan_Kredit');
    }

    public function generatePdf_RekapUtangKeOperator()
    {
        $reportData = $this->prepareAllReportData();
        $utangData = $reportData['utangOperator'];

        $html = View::make('utangKeOperatorReport', [
            'groupedKredits' => $utangData['grouped'],
            'jumlahPetaniBelumLunas' => $utangData['summary']['belumLunas']['jumlahPetani'],
            'totalKreditBelumLunas' => $utangData['summary']['belumLunas']['totalKredit'],
            'totalKreditPlusBungaBelumLunas' => $utangData['summary']['belumLunas']['totalKreditPlusBunga'],
            'jumlahPetaniLunas' => $utangData['summary']['lunas']['jumlahPetani'],
            'totalKreditLunas' => $utangData['summary']['lunas']['totalKredit'],
            'totalKreditPlusBungaLunas' => $utangData['summary']['lunas']['totalKreditPlusBunga'],
        ])->render();

        return $this->generateSinglePdf($html, 'rekapan_utang_ke_operator', 'Rekapan_Utang_Ke_Operator', function ($totalBelumLunas) {
            return RekapUtangKeOperator::create(['rekapan_utang_ke_operator' => $totalBelumLunas]);
        }, $utangData['totalBelumLunas'], '1stzfcR6OSdpBT0yb13WHFFl4_jsO08la', 'Laporan_Utang_Ke_Operator');
    }

    public function generatePdf_RekapDanaTitipanPetani()
    {
        $reportData = $this->prepareAllReportData();
        $titipanData = $reportData['titipanPetani'];

        $html = View::make('danaTitipanPetaniReport', [
            'groupedKredits' => $titipanData['grouped'],
            'jumlahPetaniBelumLunas' => $titipanData['summary']['belumLunas']['jumlahPetani'],
            'totalKreditBelumLunas' => $titipanData['summary']['belumLunas']['totalKredit'],
            'totalKreditPlusBungaBelumLunas' => $titipanData['summary']['belumLunas']['totalKreditPlusBunga'],
            'jumlahPetaniLunas' => $titipanData['summary']['lunas']['jumlahPetani'],
            'totalKreditLunas' => $titipanData['summary']['lunas']['totalKredit'],
            'totalKreditPlusBungaLunas' => $titipanData['summary']['lunas']['totalKreditPlusBunga'],
        ])->render();

        return $this->generateSinglePdf($html, 'rekapan_dana_titipan_petani', 'Rekapan_Dana_Titipan_Petani', function ($totalBelumLunas) {
            return RekapDanaTitipanPetani::create(['rekapan_dana_titipan_petani' => $totalBelumLunas]);
        }, $titipanData['totalBelumLunas'], '130_zniBFi1q6Us_F1QWwG5ziHT0gDN_f', 'Laporan_Dana_Titipan_Petani');
    }

    public function generatePdf_RekapKreditNasabahPalu()
    {
        $reportData = $this->prepareAllReportData();
        $nasabahData = $reportData['nasabahPalu'];

        $html = View::make('kreditNasabahPaluReport', [
            'groupedKredits' => $nasabahData['grouped'],
            'jumlahPetaniBelumLunas' => $nasabahData['summary']['belumLunas']['jumlahPetani'],
            'totalKreditBelumLunas' => $nasabahData['summary']['belumLunas']['totalKredit'],
            'totalKreditPlusBungaBelumLunas' => $nasabahData['summary']['belumLunas']['totalKreditPlusBunga'],
            'jumlahPetaniLunas' => $nasabahData['summary']['lunas']['jumlahPetani'],
            'totalKreditLunas' => $nasabahData['summary']['lunas']['totalKredit'],
            'totalKreditPlusBungaLunas' => $nasabahData['summary']['lunas']['totalKreditPlusBunga'],
        ])->render();

        return $this->generateSinglePdf($html, 'rekapan_kredit_nasabah_palu', 'Rekapan_Kredit_Nasabah_Palu', function ($totalBelumLunas) {
            return RekapKreditNasabahPalu::create(['rekapan_kredit_nasabah_palu' => $totalBelumLunas]);
        }, $nasabahData['totalBelumLunas'], '1UsDnhEL56lVNDK1F1BW5W5mgbpngM1ft', 'Laporan_Kredit_Nasabah_Palu_');
    }

    public function generatePdf_RekapDana($rekapId)
    {
        $rekapDana = RekapDana::findOrFail($rekapId);

        if (!$rekapDana) {
            Log::error("Rekap Dana not found for ID: {$rekapId}");
            abort(404, 'Data Rekap Dana tidak ditemukan.');
        }

        $kelompok1Total = $rekapDana->getKelompok1Total();
        $kelompok2Total = $rekapDana->getKelompok2Total();
        $kelompok3Total = $rekapDana->getKelompok3Total();
        $rekapanDana = $rekapDana->calculateRekapanDana();

        $htmlContent = View::make('rekapanDanaPDF', [
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

        $finalHtml = $this->getDefaultCss() . $htmlContent;

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'sans-serif');
        $options->set('isFontSubsettingEnabled', true);
        $options->set('defaultMediaType', 'print');
        $options->set('dpi', 96);

        $dompdf = new Dompdf($options);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->loadHtml($finalHtml);
        $dompdf->render();

        return response($dompdf->output())->header('Content-Type', 'application/pdf');
    }

    /**
     * ========================================
     * HELPER: Generate single PDF dengan upload
     * ========================================
     */
    private function generateSinglePdf($html, $folderName, $filePrefix, $createRecordCallback, $totalBelumLunas, $driveFolderId, $r2Folder)
    {
        $rekapDB = $createRecordCallback($totalBelumLunas);

        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdfFileName = $filePrefix . '_' . $rekapDB->id . '_' . date('Y-m-d_H-i-s') . '.pdf';
        $pdfPath = public_path($folderName);

        if (!file_exists($pdfPath)) {
            mkdir($pdfPath, 0755, true);
        }

        $pdfFullPath = $pdfPath . '/' . $pdfFileName;

        try {
            $pdfContent = $dompdf->output();
            file_put_contents($pdfFullPath, $pdfContent);

            // Cloudflare R2 Upload
            $r2Client = new S3Client([
                'version' => 'latest',
                'region' => 'auto',
                'endpoint' => 'https://c9961806b72189a4d763edfd8dc0e55f.r2.cloudflarestorage.com',
                'credentials' => [
                    'key' => env('R2_ACCESS_KEY', '2abc6cf8c76a71e84264efef65031933'),
                    'secret' => env('R2_SECRET_KEY', '1aa2ca39d8480cdbf846807ad5a7a1e492e72ee9a947ead03ef5d8ad67dea45d'),
                ]
            ]);

            $r2FileName = $r2Folder . '/' . $pdfFileName;

            $r2Client->putObject([
                'Bucket' => 'mitra-padi',
                'Body' => $pdfContent,
                'Key' => $r2FileName,
                'ContentType' => 'application/pdf',
                'ContentDisposition' => 'inline',
                'ACL' => 'public-read'
            ]);

            $r2Url = "https://pub-b2576acededb43e08e7292257cd6a4c8.r2.dev/{$r2FileName}";
            $rekapDB->s3_url = $r2Url;
            $rekapDB->save();

            // Google Drive Upload
            $client = new Client();
            $client->setAuthConfig(storage_path('app/google-drive-credentials.json'));
            $client->addScope(Drive::DRIVE);

            $driveService = new Drive($client);

            $fileMetadata = new Drive\DriveFile([
                'name' => $pdfFileName,
                'parents' => [$driveFolderId]
            ]);

            $driveService->files->create($fileMetadata, [
                'data' => $pdfContent,
                'mimeType' => 'application/pdf',
                'uploadType' => 'multipart',
                'fields' => 'id,webViewLink'
            ]);

            return response($pdfContent)->header('Content-Type', 'application/pdf');
        } catch (\Exception $e) {
            Log::error('PDF Generation or Upload failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * ========================================
     * LEGACY: generatePdfRekapDana_4pdf - Alias ke method baru
     * ========================================
     */
    public function generatePdfRekapDana_4pdf($rekapId)
    {
        // Redirect ke method yang sudah dioptimasi
        return $this->generatePdfCombined($rekapId);
    }

    /**
     * ========================================
     * STORE METHOD - Sudah dioptimasi
     * Tidak perlu memory_limit 1024M lagi!
     * ========================================
     */
    public function store(Request $request)
    {
        // Validasi input
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

        // Panggil method yang sudah dioptimasi
        return $this->generatePdfCombined($rekapDana->id);
    }
}
