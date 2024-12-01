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

class KreditReportController extends Controller
{

    public function index()
    {
        // Menggunakan paginate() untuk mengambil 10 data per halaman
        $rekapanKredits = RekapKredit::paginate(20); // Ambil 10 data per halaman

        // Mengirim data ke view 'daftar-rekapan-kredit.blade.php'
        return view('daftar-rekapan-kredit', compact('rekapanKredits'));
    }

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

        // Urutkan data sesuai sortOrder
        // $sortedKredits = $calculatedKredits->sortBy(function ($item) {
        //     return [$item->tanggal, $item->id];
        // }, SORT_REGULAR, $sortOrder === 'desc');

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
            // Save the PDF to the server
            file_put_contents($pdfFullPath, $dompdf->output());

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
                'data' => $dompdf->output(),
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

            return redirect()->route('rekapKredit.index')
                ->with('success', 'Rekapan Kredit berhasil dibuat.')
                ->with('newGilingId', $rekapKreditDB->id);
        } catch (\Exception $e) {
            Log::error('PDF Generation or Upload failed: ' . $e->getMessage());
            throw $e;
        }


        // Stream PDF ke browser
        // Ubah stream menjadi download
        return $dompdf->stream('Laporan_Kredit_' . date('Y-m-d_H-i-s') . '.pdf', [
            'Attachment' => true,
            'Content-Type' => 'application/pdf'
        ]);
    }
}
