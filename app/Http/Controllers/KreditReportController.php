<?php

namespace App\Http\Controllers;

use App\Models\Kredit;
use App\Models\RekapKredit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Log;
use Google\Client;
use Google\Service\Drive;
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Illuminate\Support\Facades\DB;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;



class KreditReportController extends Controller
{

    public function index()
    {
        // Ambil 1 data terbaru berdasarkan id terbesar
        $rekapanKreditTerbaru = RekapKredit::orderBy('id', 'desc')->first(); // Mengambil 1 data terbaru

        // Menggunakan paginate() untuk mengambil 20 data per halaman
        $rekapanKredits = RekapKredit::orderBy('id', 'desc')->paginate(20);

        // Mengirim data terbaru dan data lainnya ke view
        return view('daftar-rekapan-kredit', compact('rekapanKredits', 'rekapanKreditTerbaru'));
    }

    // public function findPdf(Request $request)
    // {
    //     $gilingId = $request->input('gilingId');

    //     // Cari di database untuk R2 URL
    //     $rekapan = DB::table('rekap_kredit')->where('id', $gilingId)->first();

    //     if ($rekapan && !empty($rekapan->s3_url)) {


    //         return response()->json([
    //             'pdfPath' => $rekapan->s3_url
    //         ], 200, [
    //             'Access-Control-Allow-Origin' => '*', // Izinkan semua origin
    //             'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS',
    //             'Access-Control-Allow-Headers' => 'Content-Type, Authorization'
    //         ]);
    //     }

    //     // Jika tidak ditemukan URL
    //     return response()->json([
    //         'pdfPath' => null
    //     ], 404)->header('Access-Control-Allow-Origin', '*'); // Izinkan semua origin
    // }

    public function findPdf(Request $request)
    {
        $gilingId = $request->input('gilingId');
        $folderPath = public_path('rekapan_kredit');

        // Cari file yang sesuai pola
        $matchingFiles = glob("{$folderPath}/Rekapan_Kredit_{$gilingId}_*.pdf");

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





    public function generatePdf(Request $request)
    {
        // Tetapkan langsung nilai 'desc' untuk sortOrder
        $sortOrder = 'desc';

        $allKredits = Kredit::with('petani')->get();
        $now = Carbon::now();
        $calculatedKredits = $allKredits->map(function ($kredit) use ($now) {
            $kreditDate = Carbon::parse($kredit->tanggal);

            // Cek apakah tanggal created_at dan updated_at sama (tanpa waktu)
            if ($kredit->created_at->toDateString() === $kredit->updated_at->toDateString()) {
                // Jika sama, hitung selisih bulan menggunakan now
                $diffInMonthsUpdate = $kreditDate->diffInMonths($now);
            } else {
                // Hitung selisih bulan menggunakan updated_at
                $diffInMonthsUpdate = $kreditDate->diffInMonths($kredit->updated_at);

                // Jika diffInMonthsUpdate bernilai negatif, set nilainya menjadi 0
                if ($diffInMonthsUpdate < 0) {
                    $diffInMonthsUpdate = 0;
                }
            }
            $selisihBulan = $kreditDate->diffInMonths($now);
            $bunga = $kredit->jumlah * 0.02 * $selisihBulan;
            $hutangPlusBunga = $kredit->jumlah + $bunga;

            $kredit->setAttribute('hutang_plus_bunga', $hutangPlusBunga);
            $kredit->setAttribute('lama_bulan', $selisihBulan);
            $kredit->setAttribute('bunga', $bunga);

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

            return redirect()->route('rekapKredit.index')
                ->with('success', 'Rekapan Kredit berhasil dibuat.')
                ->with('newGilingId', $rekapKreditDB->id);
        } catch (\Exception $e) {
            Log::error('PDF Generation or Upload failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
