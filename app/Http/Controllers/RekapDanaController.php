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
use setasign\Fpdi\Fpdi;
use Illuminate\Http\Response;
use FPDF;

class RekapDanaController extends Controller
{

    // Method untuk menampilkan halaman form input dan data total_kredit_plus_bunga
    public function index()
    {
        // Menghitung total_kredit_plus_bunga dengan memanggil method di model Kredit
        $totalKreditPetani = Kredit::calculateTotalKredit();

        // Menghitung total_kredit_plus_bunga dengan memanggil method di model Kredit
        $totalKreditNasabahPalu = KreditNasabahPalu::calculateTotalKreditNasabahPalu();

        $totalUtangKeOperator = UtangKeOperator::calculateTotalUtangKeOperator();

        $totalKreditTitipanPetani = KreditTitipanPetani::calculateTotalKreditTitipanPetani();

        // Mengirim data ke view 'rekap-dana.blade.php'
        return view('rekap-dana', compact('totalKreditTitipanPetani', 'totalKreditPetani', 'totalKreditNasabahPalu', 'totalUtangKeOperator'));
    }

    public function findPdf(Request $request)
    {
        $gilingId = $request->input('gilingId');
        $folderPath = public_path('rekapan_dana');

        // Cari file yang sesuai pola
        $matchingFiles = glob("{$folderPath}/Rekapan_Dana_{$gilingId}_*.pdf");

        if (!empty($matchingFiles)) {
            // Ambil file pertama yang cocok
            $pdfPath = str_replace(public_path(), '', $matchingFiles[0]);
            return response()->json([
                'pdfPath' => $pdfPath
            ]);
        }

        return response()->json([
            'pdfPath' => null
        ]);
    }

    public function indexDaftar()
    {
        // Ambil 1 data terbaru berdasarkan id terbesar
        $rekapDanaTerbaru = RekapDana::orderBy('id', 'desc')->first(); // Mengambil 1 data terbaru

        // Mengambil data dari tabel 'rekap_dana' dengan pagination 20 per halaman, diurutkan berdasarkan 'id' terbaru
        $rekapDanas = RekapDana::orderBy('id', 'desc')->paginate(20);

        // Mengirim data terbaru dan data lainnya ke view
        return view(
            'daftar-rekapan-dana',
            compact('rekapDanas', 'rekapDanaTerbaru')
        );
    }

    public function generatePdf_RekapKredit()
    {
        // Tetapkan langsung nilai 'desc' untuk sortOrder
        $sortOrder = 'desc';

        $allKredits = Kredit::with('petani')->get();
        $now = Carbon::now()->subDays(3)->startOfDay();
        $calculatedKredits = $allKredits->map(function ($kredit) use ($now) {
            $kreditDate = Carbon::parse($kredit->tanggal);

            // Cek apakah tanggal created_at dan updated_at sama (tanpa waktu)
            if ($kredit->status === true) {
                // Jika statusnya true, hitung selisih bulan menggunakan now
                $now = Carbon::now()->subDays(3)->startOfDay(); // Dapatkan waktu sekarang
                $diffInMonthsUpdate = $kreditDate->diffInMonths($kredit->updated_at); // Menghitung selisih bulan
                // Lakukan sesuatu dengan $diffInMonthsUpdate jika diperlukan
                // Jika diffInMonthsUpdate bernilai negatif, set nilainya menjadi 0
                if ($diffInMonthsUpdate < 0) {
                    $diffInMonthsUpdate = 0;
                }
            } else {
                // Hitung selisih bulan menggunakan updated_at
                $diffInMonthsUpdate = $kreditDate->diffInMonths($now);

                // Jika diffInMonthsUpdate bernilai negatif, set nilainya menjadi 0
                if ($diffInMonthsUpdate < 0) {
                    $diffInMonthsUpdate = 0;
                }
            }


            // Ensure the difference is floored
            // $selisihBulan = floor($diffInMonthsUpdate);
            $selisihBulan = ceil($diffInMonthsUpdate * 10) / 10;
            // $selisihBulan = $diffInMonthsUpdate;

            // Calculate bunga menggunakan selisih bulan
            $bunga = $kredit->jumlah * 0.02 * $selisihBulan;

            // Calculate hutang plus bunga
            $hutangPlusBunga = $kredit->jumlah + $bunga;


            $diffInMonthsUpdate = $kreditDate->diffInMonths($kredit->update_at);

            // Cek apakah tanggal created_at dan updated_at sama
            if ($kredit->created_at->eq($kredit->updated_at)) {
                $diffInMonthsUpdate = 0;
            }

            // Pastikan perbedaan bulan menjadi negatif dan dibulatkan ke bawah
            // $selisihBulanUpdate = floor($diffInMonthsUpdate);
            $selisihBulanUpdate = ceil($diffInMonthsUpdate * 10) / 10;
            // $selisihBulanUpdate = $diffInMonthsUpdate;

            // Hitung bunga menggunakan perbedaan bulan yang negatif
            $bungaUpdate = $kredit->jumlah * 0.02 * $selisihBulanUpdate;

            // Hitung hutang ditambah bunga
            $hutangPlusBungaUpdate = $kredit->jumlah + $bungaUpdate;



            $kredit->setAttribute('hutang_plus_bunga', ($hutangPlusBunga)); // Round down
            $kredit->setAttribute('hutang_plus_bunga_update', ($hutangPlusBungaUpdate)); // Round down
            $kredit->setAttribute('lama_bulan', $selisihBulan); // Use negative difference in months
            $kredit->setAttribute('lama_bulan_update', $selisihBulanUpdate); // Use negative difference in months
            $kredit->setAttribute('bunga', floor($bunga)); // Round down the bunga
            $kredit->setAttribute('bunga_update', floor($bungaUpdate)); // Round down the bunga

            return $kredit;
        });

        $sortedKredits = $calculatedKredits->sortBy(
            function ($item) {
                return [
                    $item->status ? 0 : 1,  // Status false (0) di atas, true (1) di bawah
                    $item->tanggal,
                    $item->id
                ];
            },
            SORT_REGULAR,
            $sortOrder === 'desc'
        );

        // Kelompokkan kredit berdasarkan nama petani
        $groupedByPetani = $sortedKredits->groupBy(function ($kredit) {
            return $kredit->petani->nama; // Asumsi 'nama' adalah kolom di model Petani
        });

        // Hitung ringkasan data
        $kreditsBelumLunas = $calculatedKredits->where('status', 0);
        $jumlahPetaniBelumLunas = $kreditsBelumLunas->pluck('petani_id')->unique()->count();
        $totalKreditBelumLunas = $kreditsBelumLunas->sum('jumlah');
        $totalKreditPlusBungaBelumLunas = $kreditsBelumLunas->sum('hutang_plus_bunga');

        // Hitung ringkasan data
        $kreditsLunas = $calculatedKredits->where('status', 1);
        $jumlahPetaniLunas = $kreditsLunas->pluck('petani_id')->unique()->count();
        $totalKreditLunas = $kreditsLunas->sum('jumlah');
        $totalKreditPlusBungaLunas = $kreditsLunas->sum('hutang_plus_bunga');

        // Render HTML menggunakan Blade
        $html = View::make('kreditReport', [
            'groupedKredits' => $groupedByPetani, // Mengirimkan data yang sudah dikelompokkan
            'jumlahPetaniBelumLunas' => $jumlahPetaniBelumLunas,
            'totalKreditBelumLunas' => $totalKreditBelumLunas,
            'totalKreditPlusBungaBelumLunas' => $totalKreditPlusBungaBelumLunas,
            // Menambahkan data untuk yang sudah lunas
            'jumlahPetaniLunas' => $jumlahPetaniLunas,
            'totalKreditLunas' => $totalKreditLunas,
            'totalKreditPlusBungaLunas' => $totalKreditPlusBungaLunas
        ])->render();

        // Buat data baru di database
        $rekapKreditDB = RekapKredit::create([
            'rekapan_kredit' => $totalKreditBelumLunas,
        ]);

        // Generate PDF dengan Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Define the PDF file name using only the 'id' from the $rekapDana object
        $pdfFileName = 'Rekapan_Kredit_' . $rekapKreditDB->id . '_' . date('Y-m-d_H-i-s') . '.pdf';


        $pdfPath = public_path('rekapan_kredit');


        // Ensure directory exists
        if (!file_exists($pdfPath)) {
            mkdir($pdfPath, 0755, true);
        }

        $pdfFullPath = $pdfPath . '/' . $pdfFileName;



        try {

            // Generate the PDF content
            $pdfContent = $dompdf->output();

            // Save the PDF to the server
            file_put_contents($pdfFullPath,  $pdfContent);

            // Cloudflare R2 Upload
            $r2Client = new S3Client([
                'version' => 'latest',
                'region' => 'auto',
                'endpoint' => 'https://c9961806b72189a4d763edfd8dc0e55f.r2.cloudflarestorage.com',
                'credentials' => [
                    'key' => '2abc6cf8c76a71e84264efef65031933',
                    'secret' => '1aa2ca39d8480cdbf846807ad5a7a1e492e72ee9a947ead03ef5d8ad67dea45d',
                ]
            ]);

            $r2FileName = 'Laporan_Kredit/Rekapan_Kredit_' . $rekapKreditDB->id . '_' . date('Y-m-d_H-i-s') . '.pdf';

            $r2Upload = $r2Client->putObject([
                'Bucket' => 'mitra-padi', // Nama bucket Anda
                'Body' => $pdfContent,
                'Key' => $r2FileName,
                'ContentType' => 'application/pdf',
                'ContentDisposition' => 'inline', // Tambahkan header Content-Disposition
                'ACL' => 'public-read'
            ]);


            // Dapatkan URL publik R2
            $r2Url = "https://pub-b2576acededb43e08e7292257cd6a4c8.r2.dev/{$r2FileName}";

            // Menyimpan URL Cloudinary ke database
            $rekapKreditDB->s3_url = $r2Url;
            $rekapKreditDB->save();

            // Set up Google Drive client
            $client = new Client();
            $client->setAuthConfig(storage_path('app/google-drive-credentials.json'));
            $client->addScope(Drive::DRIVE);

            $driveService = new Drive($client);

            // Check folder existence (example folder ID)
            try {
                $folderCheck = $driveService->files->get('1SfsBsgclo-omwnicyM2pN06RGj_7vQ9K', [
                    'fields' => 'id,name'
                ]);
                Log::info('Folder found: ' . $folderCheck->getName());
            } catch (\Exception $e) {
                Log::error('Failed to access folder: ' . $e->getMessage());
                throw new \Exception('Folder cannot be accessed');
            }

            // Prepare file metadata
            $fileMetadata = new Drive\DriveFile([
                'name' => $pdfFileName,
                'parents' => ['1SfsBsgclo-omwnicyM2pN06RGj_7vQ9K']
            ]);

            // Upload file to Google Drive
            $file = $driveService->files->create($fileMetadata, [
                'data' => $pdfContent,
                'mimeType' => 'application/pdf',
                'uploadType' => 'multipart',
                'fields' => 'id,webViewLink'
            ]);

            return response($pdfContent)
                ->header('Content-Type', 'application/pdf');
        } catch (\Exception $e) {
            Log::error('PDF Generation or Upload failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function generatePdf_RekapUtangKeOperator()
    {
        // Tetapkan langsung nilai 'desc' untuk sortOrder
        $sortOrder = 'desc';

        $allKredits = UtangKeOperator::with('petani')->get();
        $now = Carbon::now()->subDays(3)->startOfDay();
        $calculatedKredits = $allKredits->map(function ($kredit) use ($now) {
            $kreditDate = Carbon::parse($kredit->tanggal);

            // Cek apakah tanggal created_at dan updated_at sama (tanpa waktu)
            if ($kredit->status === true) {
                // Jika statusnya true, hitung selisih bulan menggunakan now
                $now = Carbon::now()->subDays(3)->startOfDay(); // Dapatkan waktu sekarang
                $diffInMonthsUpdate = $kreditDate->diffInMonths($kredit->updated_at); // Menghitung selisih bulan
                // Lakukan sesuatu dengan $diffInMonthsUpdate jika diperlukan
                // Jika diffInMonthsUpdate bernilai negatif, set nilainya menjadi 0
                if ($diffInMonthsUpdate < 0) {
                    $diffInMonthsUpdate = 0;
                }
            } else {
                // Hitung selisih bulan menggunakan updated_at
                $diffInMonthsUpdate = $kreditDate->diffInMonths($now);

                // Jika diffInMonthsUpdate bernilai negatif, set nilainya menjadi 0
                if ($diffInMonthsUpdate < 0) {
                    $diffInMonthsUpdate = 0;
                }
            }


            // Ensure the difference is floored
            // $selisihBulan = floor($diffInMonthsUpdate);
            $selisihBulan = ceil($diffInMonthsUpdate * 10) / 10;
            // $selisihBulan = $diffInMonthsUpdate;

            // Calculate bunga menggunakan selisih bulan
            $bunga = $kredit->jumlah * 0.02 * $selisihBulan;

            // Calculate hutang plus bunga
            $hutangPlusBunga = $kredit->jumlah + $bunga;


            $diffInMonthsUpdate = $kreditDate->diffInMonths($kredit->update_at);

            // Cek apakah tanggal created_at dan updated_at sama
            if ($kredit->created_at->eq($kredit->updated_at)) {
                $diffInMonthsUpdate = 0;
            }

            // Pastikan perbedaan bulan menjadi negatif dan dibulatkan ke bawah
            // $selisihBulanUpdate = floor($diffInMonthsUpdate);
            $selisihBulanUpdate = ceil($diffInMonthsUpdate * 10) / 10;
            // $selisihBulanUpdate = $diffInMonthsUpdate;

            // Hitung bunga menggunakan perbedaan bulan yang negatif
            $bungaUpdate = $kredit->jumlah * 0.02 * $selisihBulanUpdate;

            // Hitung hutang ditambah bunga
            $hutangPlusBungaUpdate = $kredit->jumlah + $bungaUpdate;



            $kredit->setAttribute('hutang_plus_bunga', ($hutangPlusBunga)); // Round down
            $kredit->setAttribute('hutang_plus_bunga_update', ($hutangPlusBungaUpdate)); // Round down
            $kredit->setAttribute('lama_bulan', $selisihBulan); // Use negative difference in months
            $kredit->setAttribute('lama_bulan_update', $selisihBulanUpdate); // Use negative difference in months
            $kredit->setAttribute('bunga', floor($bunga)); // Round down the bunga
            $kredit->setAttribute('bunga_update', floor($bungaUpdate)); // Round down the bunga

            return $kredit;
        });

        $sortedKredits = $calculatedKredits->sortBy(
            function ($item) {
                return [
                    $item->status ? 0 : 1,  // Status false (0) di atas, true (1) di bawah
                    $item->tanggal,
                    $item->id
                ];
            },
            SORT_REGULAR,
            $sortOrder === 'desc'
        );

        // Kelompokkan kredit berdasarkan nama petani
        $groupedByPetani = $sortedKredits->groupBy(function ($kredit) {
            return $kredit->petani->nama; // Asumsi 'nama' adalah kolom di model Petani
        });

        // Hitung ringkasan data
        $kreditsBelumLunas = $calculatedKredits->where('status', 0);
        $jumlahPetaniBelumLunas = $kreditsBelumLunas->pluck('petani_id')->unique()->count();
        $totalKreditBelumLunas = $kreditsBelumLunas->sum('jumlah');
        $totalKreditPlusBungaBelumLunas = $kreditsBelumLunas->sum('hutang_plus_bunga');

        // Hitung ringkasan data
        $kreditsLunas = $calculatedKredits->where('status', 1);
        $jumlahPetaniLunas = $kreditsLunas->pluck('petani_id')->unique()->count();
        $totalKreditLunas = $kreditsLunas->sum('jumlah');
        $totalKreditPlusBungaLunas = $kreditsLunas->sum('hutang_plus_bunga');

        // Render HTML menggunakan Blade
        $html = View::make('utangKeOperatorReport', [
            'groupedKredits' => $groupedByPetani, // Mengirimkan data yang sudah dikelompokkan
            'jumlahPetaniBelumLunas' => $jumlahPetaniBelumLunas,
            'totalKreditBelumLunas' => $totalKreditBelumLunas,
            'totalKreditPlusBungaBelumLunas' => $totalKreditPlusBungaBelumLunas,
            // Menambahkan data untuk yang sudah lunas
            'jumlahPetaniLunas' => $jumlahPetaniLunas,
            'totalKreditLunas' => $totalKreditLunas,
            'totalKreditPlusBungaLunas' => $totalKreditPlusBungaLunas
        ])->render();

        // Buat data baru di database
        $rekapKreditDB = RekapUtangKeOperator::create([
            'rekapan_utang_ke_operator' => $totalKreditBelumLunas,
        ]);

        // Generate PDF dengan Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Define the PDF file name using only the 'id' from the $rekapDana object
        $pdfFileName = 'Rekapan_Utang_Ke_Operator_' . $rekapKreditDB->id . '_' . date('Y-m-d_H-i-s') . '.pdf';


        $pdfPath = public_path('rekapan_utang_ke_operator');


        // Ensure directory exists
        if (!file_exists($pdfPath)) {
            mkdir($pdfPath, 0755, true);
        }

        $pdfFullPath = $pdfPath . '/' . $pdfFileName;



        try {

            // Generate the PDF content
            $pdfContent = $dompdf->output();

            // Save the PDF to the server
            file_put_contents($pdfFullPath,  $pdfContent);

            // Cloudflare R2 Upload
            $r2Client = new S3Client([
                'version' => 'latest',
                'region' => 'auto',
                'endpoint' => 'https://c9961806b72189a4d763edfd8dc0e55f.r2.cloudflarestorage.com',
                'credentials' => [
                    'key' => '2abc6cf8c76a71e84264efef65031933',
                    'secret' => '1aa2ca39d8480cdbf846807ad5a7a1e492e72ee9a947ead03ef5d8ad67dea45d',
                ]
            ]);

            $r2FileName = 'Laporan_Utang_Ke_Operator/Rekapan_Utang_Ke_Operator_' . $rekapKreditDB->id . '_' . date('Y-m-d_H-i-s') . '.pdf';

            $r2Upload = $r2Client->putObject([
                'Bucket' => 'mitra-padi', // Nama bucket Anda
                'Body' => $pdfContent,
                'Key' => $r2FileName,
                'ContentType' => 'application/pdf',
                'ContentDisposition' => 'inline', // Tambahkan header Content-Disposition
                'ACL' => 'public-read'
            ]);


            // Dapatkan URL publik R2
            $r2Url = "https://pub-b2576acededb43e08e7292257cd6a4c8.r2.dev/{$r2FileName}";

            // Menyimpan URL Cloudinary ke database
            $rekapKreditDB->s3_url = $r2Url;
            $rekapKreditDB->save();

            // Set up Google Drive client
            $client = new Client();
            $client->setAuthConfig(storage_path('app/google-drive-credentials.json'));
            $client->addScope(Drive::DRIVE);

            $driveService = new Drive($client);

            // Check folder existence (example folder ID)
            try {
                $folderCheck = $driveService->files->get('1stzfcR6OSdpBT0yb13WHFFl4_jsO08la', [
                    'fields' => 'id,name'
                ]);
                Log::info('Folder found: ' . $folderCheck->getName());
            } catch (\Exception $e) {
                Log::error('Failed to access folder: ' . $e->getMessage());
                throw new \Exception('Folder cannot be accessed');
            }

            // Prepare file metadata
            $fileMetadata = new Drive\DriveFile([
                'name' => $pdfFileName,
                'parents' => ['1stzfcR6OSdpBT0yb13WHFFl4_jsO08la']
            ]);

            // Upload file to Google Drive
            $file = $driveService->files->create($fileMetadata, [
                'data' => $pdfContent,
                'mimeType' => 'application/pdf',
                'uploadType' => 'multipart',
                'fields' => 'id,webViewLink'
            ]);

            return response($pdfContent)
                ->header('Content-Type', 'application/pdf');
        } catch (\Exception $e) {
            Log::error('PDF Generation or Upload failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function generatePdf_RekapDanaTitipanPetani()
    {
        // Tetapkan langsung nilai 'desc' untuk sortOrder
        $sortOrder = 'desc';

        $allKredits = KreditTitipanPetani::with('petani')->get();
        $now = Carbon::now()->subDays(3)->startOfDay();
        $calculatedKredits = $allKredits->map(function ($kredit) use ($now) {
            $kreditDate = Carbon::parse($kredit->tanggal);

            // Cek apakah tanggal created_at dan updated_at sama (tanpa waktu)
            if ($kredit->status === true) {
                // Jika statusnya true, hitung selisih bulan menggunakan now
                $now = Carbon::now()->subDays(3)->startOfDay(); // Dapatkan waktu sekarang
                $diffInMonthsUpdate = $kreditDate->diffInMonths($kredit->updated_at); // Menghitung selisih bulan
                // Lakukan sesuatu dengan $diffInMonthsUpdate jika diperlukan
                // Jika diffInMonthsUpdate bernilai negatif, set nilainya menjadi 0
                if ($diffInMonthsUpdate < 0) {
                    $diffInMonthsUpdate = 0;
                }
            } else {
                // Hitung selisih bulan menggunakan updated_at
                $diffInMonthsUpdate = $kreditDate->diffInMonths($now);

                // Jika diffInMonthsUpdate bernilai negatif, set nilainya menjadi 0
                if ($diffInMonthsUpdate < 0) {
                    $diffInMonthsUpdate = 0;
                }
            }


            // Ensure the difference is floored
            // $selisihBulan = floor($diffInMonthsUpdate);
            $selisihBulan = ceil($diffInMonthsUpdate * 10) / 10;
            // $selisihBulan = $diffInMonthsUpdate;

            // Calculate bunga menggunakan selisih bulan
            $bunga = $kredit->jumlah * 0.02 * $selisihBulan;

            // Calculate hutang plus bunga
            $hutangPlusBunga = $kredit->jumlah + $bunga;


            $diffInMonthsUpdate = $kreditDate->diffInMonths($kredit->update_at);

            // Cek apakah tanggal created_at dan updated_at sama
            if ($kredit->created_at->eq($kredit->updated_at)) {
                $diffInMonthsUpdate = 0;
            }

            // Pastikan perbedaan bulan menjadi negatif dan dibulatkan ke bawah
            // $selisihBulanUpdate = floor($diffInMonthsUpdate);
            $selisihBulanUpdate = ceil($diffInMonthsUpdate * 10) / 10;
            // $selisihBulanUpdate = $diffInMonthsUpdate;

            // Hitung bunga menggunakan perbedaan bulan yang negatif
            $bungaUpdate = $kredit->jumlah * 0.02 * $selisihBulanUpdate;

            // Hitung hutang ditambah bunga
            $hutangPlusBungaUpdate = $kredit->jumlah + $bungaUpdate;



            $kredit->setAttribute('hutang_plus_bunga', ($hutangPlusBunga)); // Round down
            $kredit->setAttribute('hutang_plus_bunga_update', ($hutangPlusBungaUpdate)); // Round down
            $kredit->setAttribute('lama_bulan', $selisihBulan); // Use negative difference in months
            $kredit->setAttribute('lama_bulan_update', $selisihBulanUpdate); // Use negative difference in months
            $kredit->setAttribute('bunga', floor($bunga)); // Round down the bunga
            $kredit->setAttribute('bunga_update', floor($bungaUpdate)); // Round down the bunga

            return $kredit;
        });

        $sortedKredits = $calculatedKredits->sortBy(
            function ($item) {
                return [
                    $item->status ? 0 : 1,  // Status false (0) di atas, true (1) di bawah
                    $item->tanggal,
                    $item->id
                ];
            },
            SORT_REGULAR,
            $sortOrder === 'desc'
        );

        // Kelompokkan kredit berdasarkan nama petani
        $groupedByPetani = $sortedKredits->groupBy(function ($kredit) {
            return $kredit->petani->nama; // Asumsi 'nama' adalah kolom di model Petani
        });

        // Hitung ringkasan data
        $kreditsBelumLunas = $calculatedKredits->where('status', 0);
        $jumlahPetaniBelumLunas = $kreditsBelumLunas->pluck('petani_id')->unique()->count();
        $totalKreditBelumLunas = $kreditsBelumLunas->sum('jumlah');
        $totalKreditPlusBungaBelumLunas = $kreditsBelumLunas->sum('hutang_plus_bunga');

        // Hitung ringkasan data
        $kreditsLunas = $calculatedKredits->where('status', 1);
        $jumlahPetaniLunas = $kreditsLunas->pluck('petani_id')->unique()->count();
        $totalKreditLunas = $kreditsLunas->sum('jumlah');
        $totalKreditPlusBungaLunas = $kreditsLunas->sum('hutang_plus_bunga');

        // Render HTML menggunakan Blade
        $html = View::make('danaTitipanPetaniReport', [
            'groupedKredits' => $groupedByPetani, // Mengirimkan data yang sudah dikelompokkan
            'jumlahPetaniBelumLunas' => $jumlahPetaniBelumLunas,
            'totalKreditBelumLunas' => $totalKreditBelumLunas,
            'totalKreditPlusBungaBelumLunas' => $totalKreditPlusBungaBelumLunas,
            // Menambahkan data untuk yang sudah lunas
            'jumlahPetaniLunas' => $jumlahPetaniLunas,
            'totalKreditLunas' => $totalKreditLunas,
            'totalKreditPlusBungaLunas' => $totalKreditPlusBungaLunas
        ])->render();

        // Buat data baru di database
        $rekapKreditDB = RekapDanaTitipanPetani::create([
            'rekapan_dana_titipan_petani' => $totalKreditBelumLunas,
        ]);

        // Generate PDF dengan Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Define the PDF file name using only the 'id' from the $rekapDana object
        $pdfFileName = 'Rekapan_Dana_Titipan_Petani_' . $rekapKreditDB->id . '_' . date('Y-m-d_H-i-s') . '.pdf';


        $pdfPath = public_path('rekapan_dana_titipan_petani');


        // Ensure directory exists
        if (!file_exists($pdfPath)) {
            mkdir($pdfPath, 0755, true);
        }

        $pdfFullPath = $pdfPath . '/' . $pdfFileName;



        try {

            // Generate the PDF content
            $pdfContent = $dompdf->output();

            // Save the PDF to the server
            file_put_contents($pdfFullPath,  $pdfContent);

            // Cloudflare R2 Upload
            $r2Client = new S3Client([
                'version' => 'latest',
                'region' => 'auto',
                'endpoint' => 'https://c9961806b72189a4d763edfd8dc0e55f.r2.cloudflarestorage.com',
                'credentials' => [
                    'key' => '2abc6cf8c76a71e84264efef65031933',
                    'secret' => '1aa2ca39d8480cdbf846807ad5a7a1e492e72ee9a947ead03ef5d8ad67dea45d',
                ]
            ]);

            $r2FileName = 'Laporan_Dana_Titipan_Petani/Rekapan_Dana_Titipan_Petani_' . $rekapKreditDB->id . '_' . date('Y-m-d_H-i-s') . '.pdf';

            $r2Upload = $r2Client->putObject([
                'Bucket' => 'mitra-padi', // Nama bucket Anda
                'Body' => $pdfContent,
                'Key' => $r2FileName,
                'ContentType' => 'application/pdf',
                'ContentDisposition' => 'inline', // Tambahkan header Content-Disposition
                'ACL' => 'public-read'
            ]);


            // Dapatkan URL publik R2
            $r2Url = "https://pub-b2576acededb43e08e7292257cd6a4c8.r2.dev/{$r2FileName}";

            // Menyimpan URL Cloudinary ke database
            $rekapKreditDB->s3_url = $r2Url;
            $rekapKreditDB->save();

            // Set up Google Drive client
            $client = new Client();
            $client->setAuthConfig(storage_path('app/google-drive-credentials.json'));
            $client->addScope(Drive::DRIVE);

            $driveService = new Drive($client);

            // Check folder existence (example folder ID)
            try {
                $folderCheck = $driveService->files->get('130_zniBFi1q6Us_F1QWwG5ziHT0gDN_f', [
                    'fields' => 'id,name'
                ]);
                Log::info('Folder found: ' . $folderCheck->getName());
            } catch (\Exception $e) {
                Log::error('Failed to access folder: ' . $e->getMessage());
                throw new \Exception('Folder cannot be accessed');
            }

            // Prepare file metadata
            $fileMetadata = new Drive\DriveFile([
                'name' => $pdfFileName,
                'parents' => ['130_zniBFi1q6Us_F1QWwG5ziHT0gDN_f']
            ]);

            // Upload file to Google Drive
            $file = $driveService->files->create($fileMetadata, [
                'data' => $pdfContent,
                'mimeType' => 'application/pdf',
                'uploadType' => 'multipart',
                'fields' => 'id,webViewLink'
            ]);

            return response($pdfContent)
                ->header('Content-Type', 'application/pdf');
        } catch (\Exception $e) {
            Log::error('PDF Generation or Upload failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function generatePdf_RekapKreditNasabahPalu()
    {
        // Tetapkan langsung nilai 'desc' untuk sortOrder
        $sortOrder = 'desc';

        $allKredits = KreditNasabahPalu::get();
        $now = Carbon::now()->subDays(3)->startOfDay();
        $calculatedKredits = $allKredits->map(function ($kredit) use ($now) {
            $kreditDate = Carbon::parse($kredit->tanggal);

            // Cek apakah tanggal created_at dan updated_at sama (tanpa waktu)
            if ($kredit->status === true) {
                // Jika statusnya true, hitung selisih bulan menggunakan now
                $now = Carbon::now()->subDays(3)->startOfDay(); // Dapatkan waktu sekarang
                $diffInMonthsUpdate = $kreditDate->diffInMonths($kredit->updated_at); // Menghitung selisih bulan
                // Lakukan sesuatu dengan $diffInMonthsUpdate jika diperlukan
                // Jika diffInMonthsUpdate bernilai negatif, set nilainya menjadi 0
                if ($diffInMonthsUpdate < 0) {
                    $diffInMonthsUpdate = 0;
                }
            } else {
                // Hitung selisih bulan menggunakan updated_at
                $diffInMonthsUpdate = $kreditDate->diffInMonths($now);

                // Jika diffInMonthsUpdate bernilai negatif, set nilainya menjadi 0
                if ($diffInMonthsUpdate < 0) {
                    $diffInMonthsUpdate = 0;
                }
            }


            // Ensure the difference is floored
            // $selisihBulan = floor($diffInMonthsUpdate);
            $selisihBulan = ceil($diffInMonthsUpdate * 10) / 10;
            // $selisihBulan = $diffInMonthsUpdate;

            // Calculate bunga menggunakan selisih bulan
            $bunga = $kredit->jumlah * 0.02 * $selisihBulan;

            // Calculate hutang plus bunga
            $hutangPlusBunga = $kredit->jumlah + $bunga;


            $diffInMonthsUpdate = $kreditDate->diffInMonths($kredit->update_at);

            // Cek apakah tanggal created_at dan updated_at sama
            if ($kredit->created_at->eq($kredit->updated_at)) {
                $diffInMonthsUpdate = 0;
            }

            // Pastikan perbedaan bulan menjadi negatif dan dibulatkan ke bawah
            // $selisihBulanUpdate = floor($diffInMonthsUpdate);
            $selisihBulanUpdate = ceil($diffInMonthsUpdate * 10) / 10;
            // $selisihBulanUpdate = $diffInMonthsUpdate;

            // Hitung bunga menggunakan perbedaan bulan yang negatif
            $bungaUpdate = $kredit->jumlah * 0.02 * $selisihBulanUpdate;

            // Hitung hutang ditambah bunga
            $hutangPlusBungaUpdate = $kredit->jumlah + $bungaUpdate;



            $kredit->setAttribute('hutang_plus_bunga', ($hutangPlusBunga)); // Round down
            $kredit->setAttribute('hutang_plus_bunga_update', ($hutangPlusBungaUpdate)); // Round down
            $kredit->setAttribute('lama_bulan', $selisihBulan); // Use negative difference in months
            $kredit->setAttribute('lama_bulan_update', $selisihBulanUpdate); // Use negative difference in months
            $kredit->setAttribute('bunga', floor($bunga)); // Round down the bunga
            $kredit->setAttribute('bunga_update', floor($bungaUpdate)); // Round down the bunga

            return $kredit;
        });

        $sortedKredits = $calculatedKredits->sortBy(
            function ($item) {
                return [
                    $item->status ? 0 : 1,  // Status false (0) di atas, true (1) di bawah
                    $item->tanggal,
                    $item->id
                ];
            },
            SORT_REGULAR,
            $sortOrder === 'desc'
        );

        // Kelompokkan kredit berdasarkan nama petani
        $groupedByPetani = $sortedKredits->groupBy(function ($kredit) {
            return $kredit->nama; // Asumsi 'nama' adalah kolom di model Petani
        });

        // Hitung ringkasan data
        $kreditsBelumLunas = $calculatedKredits->where('status', 0);
        // Assuming 'nama' is a column in the Kredits model and you want to count unique names
        $jumlahPetaniBelumLunas = $kreditsBelumLunas->pluck('nama')->unique()->count();
        $totalKreditBelumLunas = $kreditsBelumLunas->sum('jumlah');
        $totalKreditPlusBungaBelumLunas = $kreditsBelumLunas->sum('hutang_plus_bunga');

        // Hitung ringkasan data
        $kreditsLunas = $calculatedKredits->where('status', 1);
        $jumlahPetaniLunas = $kreditsLunas->pluck('petani_id')->unique()->count();
        $totalKreditLunas = $kreditsLunas->sum('jumlah');
        $totalKreditPlusBungaLunas = $kreditsLunas->sum('hutang_plus_bunga');

        // Render HTML menggunakan Blade
        $html = View::make('kreditNasabahPaluReport', [
            'groupedKredits' => $groupedByPetani, // Mengirimkan data yang sudah dikelompokkan
            'jumlahPetaniBelumLunas' => $jumlahPetaniBelumLunas,
            'totalKreditBelumLunas' => $totalKreditBelumLunas,
            'totalKreditPlusBungaBelumLunas' => $totalKreditPlusBungaBelumLunas,
            // Menambahkan data untuk yang sudah lunas
            'jumlahPetaniLunas' => $jumlahPetaniLunas,
            'totalKreditLunas' => $totalKreditLunas,
            'totalKreditPlusBungaLunas' => $totalKreditPlusBungaLunas
        ])->render();

        // Buat data baru di database
        $rekapKreditDB = RekapKreditNasabahPalu::create([
            'rekapan_kredit_nasabah_palu' => $totalKreditBelumLunas,
        ]);

        // Generate PDF dengan Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Define the PDF file name using only the 'id' from the $rekapDana object
        $pdfFileName = 'Rekapan_Kredit_Nasabah_Palu_' . $rekapKreditDB->id . '_' . date('Y-m-d_H-i-s') . '.pdf';


        $pdfPath = public_path('rekapan_kredit_nasabah_palu');


        // Ensure directory exists
        if (!file_exists($pdfPath)) {
            mkdir($pdfPath, 0755, true);
        }

        $pdfFullPath = $pdfPath . '/' . $pdfFileName;



        try {

            // Generate the PDF content
            $pdfContent = $dompdf->output();

            // Save the PDF to the server
            file_put_contents($pdfFullPath,  $pdfContent);

            // Cloudflare R2 Upload
            $r2Client = new S3Client([
                'version' => 'latest',
                'region' => 'auto',
                'endpoint' => 'https://c9961806b72189a4d763edfd8dc0e55f.r2.cloudflarestorage.com',
                'credentials' => [
                    'key' => '2abc6cf8c76a71e84264efef65031933',
                    'secret' => '1aa2ca39d8480cdbf846807ad5a7a1e492e72ee9a947ead03ef5d8ad67dea45d',
                ]
            ]);

            $r2FileName = 'Laporan_Kredit_Nasabah_Palu_/Rekapan_Kredit_Nasabah_Palu_' . $rekapKreditDB->id . '_' . date('Y-m-d_H-i-s') . '.pdf';

            $r2Upload = $r2Client->putObject([
                'Bucket' => 'mitra-padi', // Nama bucket Anda
                'Body' => $pdfContent,
                'Key' => $r2FileName,
                'ContentType' => 'application/pdf',
                'ContentDisposition' => 'inline', // Tambahkan header Content-Disposition
                'ACL' => 'public-read'
            ]);


            // Dapatkan URL publik R2
            $r2Url = "https://pub-b2576acededb43e08e7292257cd6a4c8.r2.dev/{$r2FileName}";

            // Menyimpan URL Cloudinary ke database
            $rekapKreditDB->s3_url = $r2Url;
            $rekapKreditDB->save();

            // Set up Google Drive client
            $client = new Client();
            $client->setAuthConfig(storage_path('app/google-drive-credentials.json'));
            $client->addScope(Drive::DRIVE);

            $driveService = new Drive($client);

            // Check folder existence (example folder ID)
            try {
                $folderCheck = $driveService->files->get('1UsDnhEL56lVNDK1F1BW5W5mgbpngM1ft', [
                    'fields' => 'id,name'
                ]);
                Log::info('Folder found: ' . $folderCheck->getName());
            } catch (\Exception $e) {
                Log::error('Failed to access folder: ' . $e->getMessage());
                throw new \Exception('Folder cannot be accessed');
            }

            // Prepare file metadata
            $fileMetadata = new Drive\DriveFile([
                'name' => $pdfFileName,
                'parents' => ['1UsDnhEL56lVNDK1F1BW5W5mgbpngM1ft']
            ]);

            // Upload file to Google Drive
            $file = $driveService->files->create($fileMetadata, [
                'data' => $pdfContent,
                'mimeType' => 'application/pdf',
                'uploadType' => 'multipart',
                'fields' => 'id,webViewLink'
            ]);

            return response($pdfContent)
                ->header('Content-Type', 'application/pdf');
        } catch (\Exception $e) {
            Log::error('PDF Generation or Upload failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function generatePdf_RekapDana($rekapId)
    {
        $rekapDana = RekapDana::findOrFail($rekapId);  // Find the RekapDana by ID

        if (!$rekapDana) {
            Log::error("Rekap Dana not found for ID: {$rekapId}");
            abort(404, 'Data Rekap Dana tidak ditemukan.');
        }

        // Fetch totals for each kelompok
        $kelompok1Total = $rekapDana->getKelompok1Total();
        $kelompok2Total = $rekapDana->getKelompok2Total();
        $kelompok3Total = $rekapDana->getKelompok3Total();


        // Calculate 'rekapan_dana'
        $rekapanDana = $rekapDana->calculateRekapanDana();

        // Setup Dompdf options
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'sans-serif');
        $options->set('isFontSubsettingEnabled', true);
        $options->set('defaultMediaType', 'print');
        $options->set('dpi', 96);
        $dompdf = new Dompdf($options);

        // Gunakan ukuran kertas A4 standar dengan margin 2 mm di setiap sisi
        // Konversi 2 mm ke dalam satuan point (1 mm = 2.83464567 point)
        $marginInPoints = 2 * 2.83464567;
        $dompdf->setPaper('A4', 'portrait', [
            'margin-top' => $marginInPoints,
            'margin-right' => $marginInPoints,
            'margin-bottom' => $marginInPoints,
            'margin-left' => $marginInPoints
        ]);


        // Ambil HTML untuk konten PDF
        $htmlContent = view('rekapanDanaPDF', [
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


        // Add default CSS to ensure the layout is correct
        $defaultCss = '
        <style>
            @page {
                margin: 0mm 6mm 6mm 6mm;
            }
            body {
                font-family: sans-serif;
                margin: 0;
                font-size: 10pt;
                line-height: 1;
            }
            * {
                box-sizing: border-box;
            }
            table {
                width: 100%;
            }
            .text-center {
                text-align: center;
            }
            .text-right {
                text-align: right;
            }
            .font-bold {
                font-weight: bold;
            }
            .total {
                font-weight: bold;
                text-align: right;
            }
        </style>
    ';

        // Combine the CSS and HTML content
        $htmlContent = $defaultCss . $htmlContent;


        $dompdf->loadHtml($htmlContent);

        // Render the PDF (first pass)
        $dompdf->render();

        // Define the PDF file name using only the 'id' from the $rekapDana object
        $pdfFileName = 'Rekapan_Dana_' . $rekapDana->id . '_' . date('Y-m-d_H-i-s') . '.pdf';



        $pdfPath = public_path('rekapan_dana');


        // Ensure directory exists
        if (!file_exists($pdfPath)) {
            mkdir($pdfPath, 0755, true);
        }

        $pdfFullPath = $pdfPath . '/' . $pdfFileName;

        try {

            // Generate the PDF content
            $pdfContent = $dompdf->output();

            // Return details for further use
            // return [
            //     'pdf_path' => $pdfFullPath, // Server-side PDF path
            //     'file_id' => $file->id,      // Google Drive file ID
            //     'web_view_link' => $file->webViewLink // Google Drive link
            // ];

            // // Redirect ke URL /daftar-rekapan-dana tanpa data query
            // return redirect('/daftar-rekapan-dana');

            return response($pdfContent)
                ->header('Content-Type', 'application/pdf');
        } catch (\Exception $e) {
            Log::error('PDF Generation or Upload failed: ' . $e->getMessage());
            throw $e;
        }
    }



    public function generatePdfRekapDana_4pdf($rekapId)
    {
        $rekapDana = RekapDana::findOrFail($rekapId);  // Find the RekapDana by ID

        if (!$rekapDana) {
            Log::error("Rekap Dana not found for ID: {$rekapId}");
            abort(404, 'Data Rekap Dana tidak ditemukan.');
        }

        // Fetch totals for each kelompok
        $kelompok1Total = $rekapDana->getKelompok1Total();
        $kelompok2Total = $rekapDana->getKelompok2Total();
        $kelompok3Total = $rekapDana->getKelompok3Total();


        // Calculate 'rekapan_dana'
        $rekapanDana = $rekapDana->calculateRekapanDana();

        // Setup Dompdf options
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'sans-serif');
        $options->set('isFontSubsettingEnabled', true);
        $options->set('defaultMediaType', 'print');
        $options->set('dpi', 96);
        $dompdf = new Dompdf($options);

        // Gunakan ukuran kertas A4 standar dengan margin 2 mm di setiap sisi
        // Konversi 2 mm ke dalam satuan point (1 mm = 2.83464567 point)
        $marginInPoints = 2 * 2.83464567;
        $dompdf->setPaper('A4', 'portrait', [
            'margin-top' => $marginInPoints,
            'margin-right' => $marginInPoints,
            'margin-bottom' => $marginInPoints,
            'margin-left' => $marginInPoints
        ]);


        // Ambil HTML untuk konten PDF
        $htmlContent = view('rekapanDanaPDF', [
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


        // Add default CSS to ensure the layout is correct
        $defaultCss = '
        <style>
            @page {
                margin: 0mm 6mm 6mm 6mm;
            }
            body {
                font-family: sans-serif;
                margin: 0;
                font-size: 10pt;
                line-height: 1;
            }
            * {
                box-sizing: border-box;
            }
            table {
                width: 100%;
            }
            .text-center {
                text-align: center;
            }
            .text-right {
                text-align: right;
            }
            .font-bold {
                font-weight: bold;
            }
            .total {
                font-weight: bold;
                text-align: right;
            }
        </style>
    ';

        // Combine the CSS and HTML content
        $htmlContent = $defaultCss . $htmlContent;


        $dompdf->loadHtml($htmlContent);

        // Render the PDF (first pass)
        $dompdf->render();

        // Define the PDF file name using only the 'id' from the $rekapDana object
        $pdfFileName = 'Rekapan_Dana_' . $rekapDana->id . '_' . date('Y-m-d_H-i-s') . '.pdf';



        $pdfPath = public_path('rekapan_dana');


        // Ensure directory exists
        if (!file_exists($pdfPath)) {
            mkdir($pdfPath, 0755, true);
        }

        $pdfFullPath = $pdfPath . '/' . $pdfFileName;



        try {
            // Ambil konten dari fungsi lainnya
            $pdfContent1 = $this->generatePdf_RekapDana($rekapId); // Panggil fungsi pertama
            $pdfContent2 = $this->generatePdf_RekapKredit(); // Panggil fungsi pertama
            $pdfContent3 = $this->generatePdf_RekapUtangKeOperator(); // Panggil fungsi kedua
            $pdfContent4 = $this->generatePdf_RekapDanaTitipanPetani(); // Panggil fungsi ketiga
            $pdfContent5 = $this->generatePdf_RekapKreditNasabahPalu(); // Panggil fungsi ketiga

            // Buat file sementara untuk masing-masing PDF
            $tempFile1 = tempnam(sys_get_temp_dir(), 'pdf1_');
            $tempFile2 = tempnam(sys_get_temp_dir(), 'pdf2_');
            $tempFile3 = tempnam(sys_get_temp_dir(), 'pdf3_');
            $tempFile4 = tempnam(sys_get_temp_dir(), 'pdf4_');
            $tempFile5 = tempnam(sys_get_temp_dir(), 'pdf5_');

            // Tulis konten PDF ke file sementara
            file_put_contents($tempFile1, $pdfContent1); // RekapDana pertama (paling atas)
            file_put_contents($tempFile2, $pdfContent2); // RekapKredit
            file_put_contents($tempFile3, $pdfContent3); // RekapUtangKeOperator
            file_put_contents($tempFile4, $pdfContent4); // RekapDanaTitipanPetani
            file_put_contents($tempFile5, $pdfContent5); // RekapDanaTitipanPetani

            // Inisialisasi FPDI untuk menggabungkan PDF
            $pdf = new Fpdi();

            // Gabungkan PDF pertama (RekapDana) yang diletakkan di atas
            $pageCount1 = $pdf->setSourceFile($tempFile1);
            for ($i = 1; $i <= $pageCount1; $i++) {
                $tpl = $pdf->importPage($i);
                $pdf->AddPage();
                $pdf->useTemplate($tpl);
            }

            // Gabungkan PDF kedua (RekapKredit)
            $pageCount2 = $pdf->setSourceFile($tempFile2);
            for ($i = 1; $i <= $pageCount2; $i++) {
                $tpl = $pdf->importPage($i);
                $pdf->AddPage();
                $pdf->useTemplate($tpl);
            }

            // Gabungkan PDF ketiga (RekapUtangKeOperator)
            $pageCount3 = $pdf->setSourceFile($tempFile3);
            for ($i = 1; $i <= $pageCount3; $i++) {
                $tpl = $pdf->importPage($i);
                $pdf->AddPage();
                $pdf->useTemplate($tpl);
            }

            // Gabungkan PDF keempat (RekapDanaTitipanPetani)
            $pageCount4 = $pdf->setSourceFile($tempFile4);
            for ($i = 1; $i <= $pageCount4; $i++) {
                $tpl = $pdf->importPage($i);
                $pdf->AddPage();
                $pdf->useTemplate($tpl);
            }

            $pageCount5 = $pdf->setSourceFile($tempFile5);
            for ($i = 1; $i <= $pageCount5; $i++) {
                $tpl = $pdf->importPage($i);
                $pdf->AddPage();
                $pdf->useTemplate($tpl);
            }

            // Bersihkan file sementara
            unlink($tempFile1);
            unlink($tempFile2);
            unlink($tempFile3);
            unlink($tempFile4);
            unlink($tempFile5);



            // Simpan file PDF gabungan ke path yang diinginkan secara lokal
            $pdf->Output($pdfFullPath, 'F');

            // Konversi PDF menjadi string
            $pdfContent = $pdf->Output('S'); // 'S' untuk mendapatkan konten PDF sebagai string


            // // Generate the PDF content
            // $pdfContent = $mergedPdfContent;

            // // Save the PDF to the server
            // file_put_contents($pdfFullPath, $pdfContent);

            // Cloudflare R2 Upload
            $r2Client = new S3Client([
                'version' => 'latest',
                'region' => 'auto',
                'endpoint' => 'https://c9961806b72189a4d763edfd8dc0e55f.r2.cloudflarestorage.com',
                'credentials' => [
                    'key' => '2abc6cf8c76a71e84264efef65031933',
                    'secret' => '1aa2ca39d8480cdbf846807ad5a7a1e492e72ee9a947ead03ef5d8ad67dea45d',
                ]
            ]);

            $r2FileName = 'Laporan_Dana/Rekapan_Dana_' . $rekapDana->id . '_' . date('Y-m-d_H-i-s') . '.pdf';

            $r2Upload = $r2Client->putObject([
                'Bucket' => 'mitra-padi', // Nama bucket Anda
                'Body' => $pdfContent,
                'Key' => $r2FileName,
                'ContentType' => 'application/pdf',
                'ContentDisposition' => 'inline', // Tambahkan header Content-Disposition
                'ACL' => 'public-read'
            ]);


            // Dapatkan URL publik R2
            $r2Url = "https://pub-b2576acededb43e08e7292257cd6a4c8.r2.dev/{$r2FileName}";

            // Menyimpan URL Cloudinary ke database
            $rekapDana->s3_url = $r2Url;
            $rekapDana->save();

            // Set up Google Drive client
            $client = new Client();
            $client->setAuthConfig(storage_path('app/google-drive-credentials.json'));
            $client->addScope(Drive::DRIVE);

            $driveService = new Drive($client);

            // Check folder existence (example folder ID)
            try {
                $folderCheck = $driveService->files->get('104G4glHVz6jE1iqk0-f5s0sN-pU0THpv', [
                    'fields' => 'id,name'
                ]);
                Log::info('Folder found: ' . $folderCheck->getName());
            } catch (\Exception $e) {
                Log::error('Failed to access folder: ' . $e->getMessage());
                throw new \Exception('Folder cannot be accessed');
            }

            // Prepare file metadata
            $fileMetadata = new Drive\DriveFile([
                'name' => $pdfFileName . '.pdf',
                'parents' => ['104G4glHVz6jE1iqk0-f5s0sN-pU0THpv']
            ]);

            // Upload file to Google Drive
            $file = $driveService->files->create($fileMetadata, [
                'data' => $pdfContent,
                'mimeType' => 'application/pdf',
                'uploadType' => 'multipart',
                'fields' => 'id,webViewLink'
            ]);

            // Return details for further use
            // return [
            //     'pdf_path' => $pdfFullPath, // Server-side PDF path
            //     'file_id' => $file->id,      // Google Drive file ID
            //     'web_view_link' => $file->webViewLink // Google Drive link
            // ];

            // // Redirect ke URL /daftar-rekapan-dana tanpa data query
            // return redirect('/daftar-rekapan-dana');

            return redirect()->route('rekapDana.index')
                ->with('success', 'Rekapan Dana berhasil dibuat.')
                ->with('newGilingId', $rekapDana->id);
        } catch (\Exception $e) {
            Log::error('PDF Generation or Upload failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            // Kelompok 1
            'bri' => 'required|numeric',
            'bni' => 'required|numeric',
            'tunai' => 'required|numeric',
            'mama' => 'required|numeric',

            // Kelompok 2
            'stok_beras_jumlah' => 'required|numeric',
            'beras_terpinjam_jumlah' => 'required|numeric',
            'ongkos_jemur_jumlah' => 'required|numeric',
            'stok_beras_harga' => 'required|numeric',
            'beras_terpinjam_harga' => 'required|numeric',
            'ongkos_jemur_harga' => 'required|numeric',

            // Kelompok 3
            'pinjaman_bank' => 'required|numeric',
            // 'titipan_petani' => 'required|numeric',
            'utang_beras' => 'required|numeric',
            // 'utang_ke_operator' => 'required|numeric',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Menghitung total_kredit_plus_bunga dengan memanggil method di model Kredit
        $totalKreditPetani = Kredit::calculateTotalKredit();

        // Menghitung total_kredit_plus_bunga dengan memanggil method di model Kredit
        $totalKreditNasabahPalu = KreditNasabahPalu::calculateTotalKreditNasabahPalu();


        $totalUtangKeOperator = UtangKeOperator::calculateTotalUtangKeOperator();


        $totalKreditTitipanPetani = KreditTitipanPetani::calculateTotalKreditTitipanPetani();




        // Ambil data dari request
        $data = $request->all();

        // Menghitung total untuk Kelompok 2
        $stokBerasTotal = $data['stok_beras_jumlah'] * $data['stok_beras_harga'];
        $ongkosJemurTotal = $data['ongkos_jemur_jumlah'] * $data['ongkos_jemur_harga'];
        $berasTerpinjamTotal = $data['beras_terpinjam_jumlah'] * $data['beras_terpinjam_harga'];

        // Menghitung nilai rekapan_dana = Kelompok 1 + Kelompok 2 (total) - Kelompok 3
        $kelompok1Total = $data['bri'] + $data['bni'] + $data['tunai'] + $data['mama'];
        $kelompok2Total = $stokBerasTotal + $ongkosJemurTotal + $berasTerpinjamTotal;
        $kelompok3Total = $data['pinjaman_bank'] + $totalKreditTitipanPetani + $data['utang_beras'] + $totalUtangKeOperator;

        $rekapanDana = $kelompok1Total + $kelompok2Total + $totalKreditNasabahPalu + $totalKreditPetani - $kelompok3Total;

        // Menambahkan nilai total_kredit dan rekapan_dana sebelum disimpan
        $data['total_kredit'] = $totalKreditPetani;
        $data['titipan_petani'] = $totalKreditTitipanPetani;
        $data['utang_ke_operator'] = $totalUtangKeOperator;
        $data['nasabah_palu'] = $totalKreditNasabahPalu;
        $data['stok_beras_total'] = $stokBerasTotal;
        $data['ongkos_jemur_total'] = $ongkosJemurTotal;
        $data['beras_terpinjam_total'] = $berasTerpinjamTotal;
        $data['rekapan_dana'] = $rekapanDana;


        // Menghitung nilai rekapan_dana = Kelompok 1 + Kelompok 2 (total) - Kelompok 3
        $viewKelompok1Total = $data['bri'] + $data['bni'] + $data['tunai'] + $data['mama'] + $totalKreditPetani + $totalKreditNasabahPalu;
        $viewKelompok2Total = $stokBerasTotal + $ongkosJemurTotal + $berasTerpinjamTotal;
        $viewKelompok3Total = $data['pinjaman_bank'] + $data['titipan_petani'] + $data['utang_beras'] + $data['utang_ke_operator'];

        // Simpan data ke dalam tabel rekap_dana
        $rekapDana = RekapDana::create($data); // Store the new record and assign it to the $rekapDana variable

        // Call the generatePdfRekapDana method after storing the record
        $this->generatePdfRekapDana_4pdf($rekapDana->id); // Pass the id of the newly created record to generate the PDF

        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'Data berhasil disimpan.'
        // ], 201);

        return redirect()->route('rekapDana.index')
            ->with('success', 'Rekapan Dana berhasil dibuat.')
            ->with('newGilingId', $rekapDana->id);
    }
}
