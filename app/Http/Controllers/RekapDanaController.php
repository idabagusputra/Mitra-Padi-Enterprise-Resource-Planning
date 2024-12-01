<?php

namespace App\Http\Controllers;

use App\Models\Kredit;
use App\Models\KreditNasabahPalu;
use App\Models\RekapDana;
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

class RekapDanaController extends Controller
{

    // Method untuk menampilkan halaman form input dan data total_kredit_plus_bunga
    public function index()
    {
        // Menghitung total_kredit_plus_bunga dengan memanggil method di model Kredit
        $totalKreditPetani = Kredit::calculateTotalKredit();

        // Menghitung total_kredit_plus_bunga dengan memanggil method di model Kredit
        $totalKreditNasabahPalu = KreditNasabahPalu::calculateTotalKreditNasabahPalu();

        // Mengirim data ke view 'rekap-dana.blade.php'
        return view('rekap-dana', compact('totalKreditPetani', 'totalKreditNasabahPalu'));
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
        // Mengambil data dari tabel 'rekap_dana' dengan pagination 20 per halaman, diurutkan berdasarkan 'id' terbaru
        $rekapDanas = RekapDana::orderBy('id', 'desc')->paginate(20);

        // Mengirim data ke view 'daftar-rekapan-dana'
        return view(
            'daftar-rekapan-dana',
            compact('rekapDanas')
        );
    }



    public function generatePdfRekapDana($rekapId)
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
            // Save the PDF to the server
            file_put_contents($pdfFullPath, $dompdf->output());

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
                'data' => $dompdf->output(),
                'mimeType' => 'application/pdf',
                'uploadType' => 'multipart',
                'fields' => 'id,webViewLink'
            ]);

            // Return details for further use
            return view('daftar-rekapan-dana', [
                'pdf_path' => $pdfFullPath,
                'file_id' => $file->id,
                'web_view_link' => $file->webViewLink
            ]);
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
            'titipan_petani' => 'required|numeric',
            'utang_beras' => 'required|numeric',
            'utang_ke_operator' => 'required|numeric',
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


        // Ambil data dari request
        $data = $request->all();

        // Menghitung total untuk Kelompok 2
        $stokBerasTotal = $data['stok_beras_jumlah'] * $data['stok_beras_harga'];
        $ongkosJemurTotal = $data['ongkos_jemur_jumlah'] * $data['ongkos_jemur_harga'];
        $berasTerpinjamTotal = $data['beras_terpinjam_jumlah'] * $data['beras_terpinjam_harga'];

        // Menghitung nilai rekapan_dana = Kelompok 1 + Kelompok 2 (total) - Kelompok 3
        $kelompok1Total = $data['bri'] + $data['bni'] + $data['tunai'] + $data['mama'];
        $kelompok2Total = $stokBerasTotal + $ongkosJemurTotal + $berasTerpinjamTotal;
        $kelompok3Total = $data['pinjaman_bank'] + $data['titipan_petani'] + $data['utang_beras'] + $data['utang_ke_operator'];

        $rekapanDana = $kelompok1Total + $kelompok2Total + $totalKreditNasabahPalu + $totalKreditPetani - $kelompok3Total;

        // Menambahkan nilai total_kredit dan rekapan_dana sebelum disimpan
        $data['total_kredit'] = $totalKreditPetani;
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
        $this->generatePdfRekapDana($rekapDana->id); // Pass the id of the newly created record to generate the PDF

        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'Data berhasil disimpan.'
        // ], 201);

        return redirect()->route('rekapDana.index');
    }
}
