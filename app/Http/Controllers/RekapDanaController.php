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

class RekapDanaController extends Controller
{
    // CRITICAL: Konfigurasi memory yang lebih agresif
    public function __construct()
    {
        // Set memory limit maksimal
        @ini_set('memory_limit', '1024M'); // Naikkan ke 1GB
        @ini_set('max_execution_time', '600'); // 10 menit

        // Disable query log untuk hemat memory
        DB::connection()->disableQueryLog();
    }

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
     * SUPER OPTIMIZED: Kalkulasi dengan LIMIT untuk mencegah memory overflow
     * ========================================
     */
    private function calculateKreditDataOptimized($modelClass, $now, $relationField = 'petani', $limit = 1000)
    {
        $results = collect();
        $processed = 0;

        Log::info("Processing {$modelClass} with limit {$limit}");

        // Gunakan cursor() untuk lazy loading - lebih hemat memory dari chunk()
        $query = $modelClass::query();

        if ($relationField !== null) {
            $query->with($relationField);
        }

        // TAMBAHKAN LIMIT untuk mencegah data terlalu besar
        $query->orderBy('id', 'desc')->limit($limit);

        foreach ($query->cursor() as $kredit) {
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

            $results->push($kredit);

            $processed++;

            // Force GC setiap 100 records
            if ($processed % 100 === 0) {
                gc_collect_cycles();
            }
        }

        Log::info("Processed {$processed} records for {$modelClass}");

        return $results;
    }

    /**
     * ========================================
     * OPTIMIZED: Prepare data dengan LIMIT
     * ========================================
     */
    private function prepareAllReportData($dataLimit = 500)
    {
        $now = Carbon::now();

        Log::info("Starting data preparation with limit: {$dataLimit}");

        // 1. Kredit Petani - DENGAN LIMIT
        $calculatedKreditPetani = $this->calculateKreditDataOptimized(Kredit::class, $now, 'petani', $dataLimit);
        $sortedKreditPetani = $this->sortKredits($calculatedKreditPetani);
        $groupedKreditPetani = $sortedKreditPetani->groupBy(function ($k) {
            return $k->petani->nama ?? 'Unknown';
        });
        $summaryKreditPetani = $this->calculateSummary($calculatedKreditPetani, 'petani_id');

        Log::info('Kredit Petani processed: ' . $calculatedKreditPetani->count() . ' records');
        unset($calculatedKreditPetani, $sortedKreditPetani);
        gc_collect_cycles();

        // 2. Utang Operator - DENGAN LIMIT
        $calculatedUtangOperator = $this->calculateKreditDataOptimized(UtangKeOperator::class, $now, 'petani', $dataLimit);
        $sortedUtangOperator = $this->sortKredits($calculatedUtangOperator);
        $groupedUtangOperator = $sortedUtangOperator->groupBy(function ($k) {
            return $k->petani->nama ?? 'Unknown';
        });
        $summaryUtangOperator = $this->calculateSummary($calculatedUtangOperator, 'petani_id');

        Log::info('Utang Operator processed: ' . $calculatedUtangOperator->count() . ' records');
        unset($calculatedUtangOperator, $sortedUtangOperator);
        gc_collect_cycles();

        // 3. Titipan Petani - DENGAN LIMIT
        $calculatedTitipanPetani = $this->calculateKreditDataOptimized(KreditTitipanPetani::class, $now, 'petani', $dataLimit);
        $sortedTitipanPetani = $this->sortKredits($calculatedTitipanPetani);
        $groupedTitipanPetani = $sortedTitipanPetani->groupBy(function ($k) {
            return $k->petani->nama ?? 'Unknown';
        });
        $summaryTitipanPetani = $this->calculateSummary($calculatedTitipanPetani, 'petani_id');

        Log::info('Titipan Petani processed: ' . $calculatedTitipanPetani->count() . ' records');
        unset($calculatedTitipanPetani, $sortedTitipanPetani);
        gc_collect_cycles();

        // 4. Nasabah Palu - DENGAN LIMIT
        $calculatedNasabahPalu = $this->calculateKreditDataOptimized(KreditNasabahPalu::class, $now, null, $dataLimit);
        $sortedNasabahPalu = $this->sortKredits($calculatedNasabahPalu);
        $groupedNasabahPalu = $sortedNasabahPalu->groupBy(fn($k) => $k->nama ?? 'Unknown');
        $summaryNasabahPalu = $this->calculateSummary($calculatedNasabahPalu, 'nama');

        Log::info('Nasabah Palu processed: ' . $calculatedNasabahPalu->count() . ' records');
        unset($calculatedNasabahPalu, $sortedNasabahPalu);
        gc_collect_cycles();

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
     * OPTIMIZED: Generate HTML dengan aggressive cleanup
     * ========================================
     */
    private function generateCombinedHtml($rekapDana, $reportData)
    {
        $kelompok1Total = $rekapDana->getKelompok1Total();
        $kelompok2Total = $rekapDana->getKelompok2Total();
        $kelompok3Total = $rekapDana->getKelompok3Total();
        $rekapanDana = $rekapDana->calculateRekapanDana();

        Log::info('Generating HTML sections with aggressive memory cleanup');

        $sections = [];

        // 1. Rekapan Dana
        $sections[] = View::make('rekapanDanaPDF', [
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
        gc_collect_cycles();

        // 2. Kredit Report
        $kreditData = $reportData['kreditPetani'];
        $sections[] = View::make('kreditReport', [
            'groupedKredits' => $kreditData['grouped'],
            'jumlahPetaniBelumLunas' => $kreditData['summary']['belumLunas']['jumlahPetani'],
            'totalKreditBelumLunas' => $kreditData['summary']['belumLunas']['totalKredit'],
            'totalKreditPlusBungaBelumLunas' => $kreditData['summary']['belumLunas']['totalKreditPlusBunga'],
            'jumlahPetaniLunas' => $kreditData['summary']['lunas']['jumlahPetani'],
            'totalKreditLunas' => $kreditData['summary']['lunas']['totalKredit'],
            'totalKreditPlusBungaLunas' => $kreditData['summary']['lunas']['totalKreditPlusBunga'],
        ])->render();
        unset($reportData['kreditPetani'], $kreditData);
        gc_collect_cycles();

        // 3. Utang Operator
        $utangData = $reportData['utangOperator'];
        $sections[] = View::make('utangKeOperatorReport', [
            'groupedKredits' => $utangData['grouped'],
            'jumlahPetaniBelumLunas' => $utangData['summary']['belumLunas']['jumlahPetani'],
            'totalKreditBelumLunas' => $utangData['summary']['belumLunas']['totalKredit'],
            'totalKreditPlusBungaBelumLunas' => $utangData['summary']['belumLunas']['totalKreditPlusBunga'],
            'jumlahPetaniLunas' => $utangData['summary']['lunas']['jumlahPetani'],
            'totalKreditLunas' => $utangData['summary']['lunas']['totalKredit'],
            'totalKreditPlusBungaLunas' => $utangData['summary']['lunas']['totalKreditPlusBunga'],
        ])->render();
        unset($reportData['utangOperator'], $utangData);
        gc_collect_cycles();

        // 4. Titipan Petani
        $titipanData = $reportData['titipanPetani'];
        $sections[] = View::make('danaTitipanPetaniReport', [
            'groupedKredits' => $titipanData['grouped'],
            'jumlahPetaniBelumLunas' => $titipanData['summary']['belumLunas']['jumlahPetani'],
            'totalKreditBelumLunas' => $titipanData['summary']['belumLunas']['totalKredit'],
            'totalKreditPlusBungaBelumLunas' => $titipanData['summary']['belumLunas']['totalKreditPlusBunga'],
            'jumlahPetaniLunas' => $titipanData['summary']['lunas']['jumlahPetani'],
            'totalKreditLunas' => $titipanData['summary']['lunas']['totalKredit'],
            'totalKreditPlusBungaLunas' => $titipanData['summary']['lunas']['totalKreditPlusBunga'],
        ])->render();
        unset($reportData['titipanPetani'], $titipanData);
        gc_collect_cycles();

        // 5. Nasabah Palu
        $nasabahData = $reportData['nasabahPalu'];
        $sections[] = View::make('kreditNasabahPaluReport', [
            'groupedKredits' => $nasabahData['grouped'],
            'jumlahPetaniBelumLunas' => $nasabahData['summary']['belumLunas']['jumlahPetani'],
            'totalKreditBelumLunas' => $nasabahData['summary']['belumLunas']['totalKredit'],
            'totalKreditPlusBungaBelumLunas' => $nasabahData['summary']['belumLunas']['totalKreditPlusBunga'],
            'jumlahPetaniLunas' => $nasabahData['summary']['lunas']['jumlahPetani'],
            'totalKreditLunas' => $nasabahData['summary']['lunas']['totalKredit'],
            'totalKreditPlusBungaLunas' => $nasabahData['summary']['lunas']['totalKreditPlusBunga'],
        ])->render();
        unset($reportData['nasabahPalu'], $nasabahData);
        gc_collect_cycles();

        return $this->wrapWithPageBreaks($sections);
    }

    private function wrapWithPageBreaks(array $htmlSections)
    {
        $combined = '';
        $totalSections = count($htmlSections);

        foreach ($htmlSections as $index => $html) {
            if ($index < $totalSections - 1) {
                $combined .= '<div style="page-break-after: always;">' . $html . '</div>';
            } else {
                $combined .= '<div>' . $html . '</div>';
            }

            // Clear setiap section setelah append
            unset($htmlSections[$index]);

            if ($index % 2 === 0) {
                gc_collect_cycles();
            }
        }

        return $combined;
    }

    /**
     * ========================================
     * MAIN: Generate PDF dengan MAXIMUM OPTIMIZATION
     * ========================================
     */
    public function generatePdfCombined($rekapId)
    {
        try {
            Log::info("Starting PDF generation for rekap ID: {$rekapId}");

            // Clear opcode cache
            if (function_exists('opcache_reset')) {
                opcache_reset();
            }

            $rekapDana = RekapDana::findOrFail($rekapId);

            if (!$rekapDana) {
                Log::error("Rekap Dana not found for ID: {$rekapId}");
                abort(404, 'Data Rekap Dana tidak ditemukan.');
            }

            // STEP 1: Prepare data dengan LIMIT (ubah 500 sesuai kebutuhan)
            Log::info("Preparing report data with LIMIT");
            $reportData = $this->prepareAllReportData(500); // LIMIT 500 records per kategori

            // STEP 2: Simpan summary ke database
            Log::info("Saving summary to database");
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

            // STEP 3: Generate HTML
            Log::info("Generating combined HTML");
            $combinedHtml = $this->generateCombinedHtml($rekapDana, $reportData);

            // Clear reportData dari memory
            unset($reportData);
            gc_collect_cycles();

            $finalHtml = '<!DOCTYPE html><html><head><meta charset="UTF-8"><style>body{font-size:10px;}</style></head><body>'
                . $combinedHtml
                . '</body></html>';

            // STEP 4: Generate PDF dengan SUPER OPTIMIZED settings
            Log::info("Generating PDF with DomPDF - OPTIMIZED MODE");
            $options = new Options();
            $options->set('isRemoteEnabled', false); // Disable remote untuk hemat memory
            $options->set('isHtml5ParserEnabled', false); // Disable HTML5 parser
            $options->set('defaultFont', 'sans-serif');
            $options->set('isFontSubsettingEnabled', false); // Disable subsetting
            $options->set('defaultMediaType', 'print');
            $options->set('dpi', 50); // REDUCE DPI drastis untuk hemat memory
            $options->set('enable_php', false);
            $options->set('enable_javascript', false);
            $options->set('enable_remote', false);
            $options->set('enable_css_float', false);
            $options->set('debugKeepTemp', false);

            $dompdf = new Dompdf($options);
            $dompdf->setPaper('A4', 'portrait');

            // Batasi jumlah page yang di-render
            $dompdf->set_option('isPhpEnabled', false);

            $dompdf->loadHtml($finalHtml);

            // Clear HTML dari memory sebelum render
            unset($combinedHtml, $finalHtml);
            gc_collect_cycles();

            Log::info("Rendering PDF...");
            $dompdf->render();

            $pdfContent = $dompdf->output();

            // Clear dompdf dari memory
            unset($dompdf, $options);
            gc_collect_cycles();

            // STEP 5: Save locally
            Log::info("Saving PDF locally");
            $pdfFileName = 'Rekapan_Dana_' . $rekapDana->id . '_' . date('Y-m-d_H-i-s') . '.pdf';
            $pdfPath = public_path('rekapan_dana');

            if (!file_exists($pdfPath)) {
                mkdir($pdfPath, 0755, true);
            }

            $pdfFullPath = $pdfPath . '/' . $pdfFileName;
            file_put_contents($pdfFullPath, $pdfContent);

            // STEP 6: Upload ke Cloudflare R2
            Log::info("Uploading to Cloudflare R2");
            $r2Client = new S3Client([
                'version' => 'latest',
                'region' => 'auto',
                'endpoint' => 'https://c9961806b72189a4d763edfd8dc0e55f.r2.cloudflarestorage.com',
                'credentials' => [
                    'key' => env('R2_ACCESS_KEY', '2abc6cf8c76a71e84264efef65031933'),
                    'secret' => env('R2_SECRET_KEY', '1aa2ca39d8480cdbf846807ad5a7a1e492e72ee9a947ead03ef5d8ad67dea45d'),
                ],
                'http' => [
                    'timeout' => 300,
                    'connect_timeout' => 30,
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

            // Update semua rekap dengan S3 URL
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

            // STEP 7: Upload ke Google Drive
            Log::info("Uploading to Google Drive");
            try {
                $client = new Client();
                $client->setAuthConfig(storage_path('app/google-drive-credentials.json'));
                $client->addScope(Drive::DRIVE);

                $driveService = new Drive($client);

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
            } catch (\Exception $e) {
                Log::warning("Google Drive upload failed: " . $e->getMessage());
                // Continue execution even if Drive upload fails
            }

            // Clear PDF content dari memory
            unset($pdfContent, $r2Client, $client, $driveService);
            gc_collect_cycles();

            Log::info("PDF generation completed successfully");

            return redirect()->route('rekapDana.index')
                ->with('success', 'Rekapan Dana berhasil dibuat.')
                ->with('newGilingId', $rekapDana->id);
        } catch (\Exception $e) {
            Log::error('PDF Generation failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            Log::error('Memory usage: ' . memory_get_usage(true) . ' bytes');
            Log::error('Memory peak: ' . memory_get_peak_usage(true) . ' bytes');

            return redirect()->route('rekapDana.index')
                ->with('error', 'Gagal membuat PDF: ' . $e->getMessage());
        }
    }

    // Legacy methods tetap dipertahankan
    public function generatePdfRekapDana_4pdf($rekapId)
    {
        return $this->generatePdfCombined($rekapId);
    }

    public function store(Request $request)
    {
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

        return $this->generatePdfCombined($rekapDana->id);
    }

    /**
     * ========================================
     * LEGACY METHODS - Individual PDF Generation
     * ========================================
     */

    public function generatePdf_RekapKredit()
    {
        try {
            $reportData = $this->prepareAllReportData(500);
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

            return $this->generateSinglePdf(
                $html,
                'rekapan_kredit',
                'Rekapan_Kredit',
                function ($totalBelumLunas) {
                    return RekapKredit::create(['rekapan_kredit' => $totalBelumLunas]);
                },
                $kreditData['totalBelumLunas'],
                '1SfsBsgclo-omwnicyM2pN06RGj_7vQ9K',
                'Laporan_Kredit'
            );
        } catch (\Exception $e) {
            Log::error('Kredit PDF Generation failed: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function generatePdf_RekapUtangKeOperator()
    {
        try {
            $reportData = $this->prepareAllReportData(500);
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

            return $this->generateSinglePdf(
                $html,
                'rekapan_utang_ke_operator',
                'Rekapan_Utang_Ke_Operator',
                function ($totalBelumLunas) {
                    return RekapUtangKeOperator::create(['rekapan_utang_ke_operator' => $totalBelumLunas]);
                },
                $utangData['totalBelumLunas'],
                '1stzfcR6OSdpBT0yb13WHFFl4_jsO08la',
                'Laporan_Utang_Ke_Operator'
            );
        } catch (\Exception $e) {
            Log::error('Utang Operator PDF Generation failed: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function generatePdf_RekapDanaTitipanPetani()
    {
        try {
            $reportData = $this->prepareAllReportData(500);
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

            return $this->generateSinglePdf(
                $html,
                'rekapan_dana_titipan_petani',
                'Rekapan_Dana_Titipan_Petani',
                function ($totalBelumLunas) {
                    return RekapDanaTitipanPetani::create(['rekapan_dana_titipan_petani' => $totalBelumLunas]);
                },
                $titipanData['totalBelumLunas'],
                '130_zniBFi1q6Us_F1QWwG5ziHT0gDN_f',
                'Laporan_Dana_Titipan_Petani'
            );
        } catch (\Exception $e) {
            Log::error('Titipan Petani PDF Generation failed: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function generatePdf_RekapKreditNasabahPalu()
    {
        try {
            $reportData = $this->prepareAllReportData(500);
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

            return $this->generateSinglePdf(
                $html,
                'rekapan_kredit_nasabah_palu',
                'Rekapan_Kredit_Nasabah_Palu',
                function ($totalBelumLunas) {
                    return RekapKreditNasabahPalu::create(['rekapan_kredit_nasabah_palu' => $totalBelumLunas]);
                },
                $nasabahData['totalBelumLunas'],
                '1UsDnhEL56lVNDK1F1BW5W5mgbpngM1ft',
                'Laporan_Kredit_Nasabah_Palu_'
            );
        } catch (\Exception $e) {
            Log::error('Nasabah Palu PDF Generation failed: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function generatePdf_RekapDana($rekapId)
    {
        try {
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

            $options = new Options();
            $options->set('isRemoteEnabled', false);
            $options->set('isHtml5ParserEnabled', false);
            $options->set('defaultFont', 'sans-serif');
            $options->set('isFontSubsettingEnabled', false);
            $options->set('defaultMediaType', 'print');
            $options->set('dpi', 50);

            $dompdf = new Dompdf($options);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->loadHtml($htmlContent);
            $dompdf->render();

            return response($dompdf->output())->header('Content-Type', 'application/pdf');
        } catch (\Exception $e) {
            Log::error('Rekap Dana PDF Generation failed: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * ========================================
     * HELPER: Generate single PDF dengan upload
     * ========================================
     */
    private function generateSinglePdf($html, $folderName, $filePrefix, $createRecordCallback, $totalBelumLunas, $driveFolderId, $r2Folder)
    {
        try {
            $rekapDB = $createRecordCallback($totalBelumLunas);

            $options = new Options();
            $options->set('defaultFont', 'Arial');
            $options->set('dpi', 50); // Reduced DPI
            $options->set('isRemoteEnabled', false);
            $options->set('isHtml5ParserEnabled', false);

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

            $pdfContent = $dompdf->output();

            // Clear dompdf
            unset($dompdf, $html);
            gc_collect_cycles();

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
            try {
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
            } catch (\Exception $e) {
                Log::warning("Google Drive upload failed: " . $e->getMessage());
            }

            return response($pdfContent)->header('Content-Type', 'application/pdf');
        } catch (\Exception $e) {
            Log::error('Single PDF Generation or Upload failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
