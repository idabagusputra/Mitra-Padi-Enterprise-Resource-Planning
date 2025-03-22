<?php

namespace App\Http\Controllers;

use App\Models\Debit;
use App\Models\Kredit;
use App\Models\UtangKeOperator;
use App\Models\Petani;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Aws\S3\S3Client;
use Dompdf\Dompdf;
use Google\Client;
use Dompdf\Options;
use Google\Service\Drive;

class DebitController extends Controller
{
    public function index(Request $request)
    {
        $query = Debit::with('petani');

        $search = $request->input('search');

        // Apply filters
        if ($search) {
            $query->whereHas('petani', function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%');
            });
        }

        $sort = $request->input('sort', 'desc');

        $query->orderBy('tanggal', $sort) // Urutkan berdasarkan tanggal
            ->orderBy('id', $sort); // Urutkan berdasarkan id untuk menangani data dengan tanggal yang sama


        $debits = $query->paginate(20);

        $petanisWithOutstandingKredits = Petani::whereHas('kredits', function ($query) {
            $query->where('status', false);
        })->with(['kredits' => function ($query) {
            $query->where('status', false);
        }])->get()->map(function ($petani) {
            $petani->total_hutang = $petani->kredits->sum('jumlah');
            return $petani;
        });

        return view('laravel-examples/debit', compact('debits', 'petanisWithOutstandingKredits'));
    }

    public function searchPetani(Request $request)
    {
        $term = $request->input('term');
        $petanis = Petani::where('nama', 'LIKE', "%{$term}%")
            ->orWhere('alamat', 'LIKE', "%{$term}%")
            ->get(['id', 'nama', 'alamat']);

        return response()->json($petanis);
    }

    public function search(Request $request)
    {
        $term = $request->query('term');

        $petanis = Petani::where('nama', 'LIKE', "%{$term}%")
            ->orWhere('alamat', 'LIKE', "%{$term}%")
            ->select('id', 'nama', 'alamat')
            ->get();

        return response()->json($petanis);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'petani_id' => 'required|exists:petanis,id',
                'tanggal' => 'required|date',
                'jumlah' => 'required|numeric|min:0',
                'bunga' => 'required|numeric|min:0|max:100',
                'keterangan' => 'required|string',
            ]);

            DB::beginTransaction();

            // Transformasi keterangan untuk menjadikan huruf awal setiap kata kapital
            $validatedData['keterangan'] = ucwords(strtolower($validatedData['keterangan']));

            $debit = Debit::create($validatedData);
            $debit->processPayment();

            DB::commit();

            // Return a proper JSON response
            return response()->json([
                'success' => true,
                'message' => 'Debit entry created successfully.',
                'data' => $debit
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating debit entry: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        return DB::transaction(function () use ($id) {
            try {
                // Dapatkan data Debit yang ingin dihapus
                $debit = Debit::with('petani.kredits')->findOrFail($id);

                // Soft delete pada Debit
                $debit->delete();

                // Reverse perubahan pada kredit
                $this->reversePaymentChanges($debit);

                return redirect()->route('debit.index')
                    ->with('success', 'Debit berhasil dihapus (soft delete) dan status kredit dikembalikan.');
            } catch (\Exception $e) {

                throw $e;
            }
        });
    }

    // public function generatePdf($id)
    // {
    //     $debits = Debit::with('petani.kredits')->findOrFail($id);

    //     $kredits = Kredit::where('debit_id', $id)
    //         ->where('status', false)
    //         ->orderBy('tanggal', 'dsc')
    //         ->firstOrFail();

    //     $nama_petani = $debits->petani->nama;

    //     if (!$id) {
    //         Log::error("Giling not found for DaftarGiling ID: {$id}");
    //         abort(404, 'Data Debit tidak ditemukan.');
    //     }


    //     // Calculate lama_bulan for each kredit
    //     $now = Carbon::now()->subDay()->startOfDay();
    //     foreach ($kredits as $kredit) {
    //         $tanggal = Carbon::parse($kredit->tanggal);
    //         $kredit->lama_bulan = $tanggal->diffInMonths($now);
    //     }

    //     // Setup DomPDF dengan konfigurasi khusus
    //     $options = new Options();
    //     $options->set('isRemoteEnabled', true);
    //     $options->set('isHtml5ParserEnabled', true);
    //     $options->set('defaultFont', 'sans-serif');
    //     $options->set('isFontSubsettingEnabled', true);
    //     $options->set('defaultMediaType', 'print');
    //     $options->set('dpi', 96);
    //     $options->set('debugKeepTemp', true);
    //     // $options->set('debugCss', true);

    //     $dompdf = new Dompdf($options);

    //     // Convert mm to points (1mm = 2.83465 points)
    //     $width = 86 * 2.83465;
    //     $height = 400 * 2.83465;

    //     // Set custom paper size
    //     $dompdf->setPaper(array(0, 0, $width, $height));

    //     // Get HTML content using existing view
    //     $htmlContent = view('receipt.debit-thermal', compact('debits', 'nama_petani', 'kredits'))->render();

    //     // Add default CSS untuk memastikan tampilan sesuai
    //     $defaultCss = '
    //         <style>
    //             @page {
    //                 margin: 0mm 3mm 3mm 3mm;

    //             }
    //             body {
    //                 font-family: sans-serif;
    //                 margin: 0;

    //                 font-size: 10pt;
    //                 line-height: 1.3;
    //             }
    //             * {
    //                 box-sizing: border-box;
    //             }
    //             table {
    //                 width: 100%;
    //             }
    //             .text-center {
    //                 text-align: center;
    //             }
    //             .text-right {
    //                 text-align: right;
    //             }
    //             .font-bold {
    //                 font-weight: bold;
    //             }
    //         </style>
    //     ';

    //     // Combine CSS with HTML content
    //     $htmlContent = $defaultCss . $htmlContent;

    //     // Load HTML ke DomPDF
    //     $dompdf->loadHtml($htmlContent);

    //     // Render PDF
    //     $dompdf->render();

    //     // Define PDF path
    //     // $pdfFileName = $giling->id . '_' . 'Nota_Giling_' . date('Y-m-d_H-i-s') . '.pdf';
    //     $pdfFileName = 'Nota-Debit' . $debits->id . '.pdf';
    //     $pdfPath = public_path('receipts');

    //     // Ensure directory exists
    //     if (!file_exists($pdfPath)) {
    //         mkdir($pdfPath, 0755, true);
    //     }

    //     $pdfFullPath = $pdfPath . '/' . $pdfFileName;

    //     try {
    //         // Save PDF to file
    //         file_put_contents($pdfFullPath, $dompdf->output());
    //         // Generate the PDF content
    //         $pdfContent = $dompdf->output();

    //         // Cloudflare R2 Upload
    //         $r2Client = new S3Client([
    //             'version' => 'latest',
    //             'region' => 'auto',
    //             'endpoint' => 'https://c9961806b72189a4d763edfd8dc0e55f.r2.cloudflarestorage.com',
    //             'credentials' => [
    //                 'key' => '2abc6cf8c76a71e84264efef65031933',
    //                 'secret' => '1aa2ca39d8480cdbf846807ad5a7a1e492e72ee9a947ead03ef5d8ad67dea45d',
    //             ]
    //         ]);

    //         $r2FileName = 'Nota_Debit/' . $debits->id . '_Nota_Giling_' . date('Y-m-d_H-i-s') . '.pdf';

    //         $r2Upload = $r2Client->putObject([
    //             'Bucket' => 'mitra-padi', // Nama bucket Anda
    //             'Body' => $pdfContent,
    //             'Key' => $r2FileName,
    //             'ContentType' => 'application/pdf',
    //             'ACL' => 'public-read'
    //         ]);

    //         // Dapatkan URL publik R2
    //         $r2Url = "https://pub-b2576acededb43e08e7292257cd6a4c8.r2.dev/{$r2FileName}";

    //         // Menyimpan URL Cloudinary ke database
    //         $debits->s3_url = $r2Url;
    //         $debits->save();


    //         // Set up Google Drive client
    //         $client = new Client();
    //         $client->setAuthConfig(storage_path('app/google-drive-credentials.json'));
    //         $client->addScope(Drive::DRIVE);

    //         $driveService = new Drive($client);

    //         // Check folder access
    //         try {
    //             $folderCheck = $driveService->files->get('124X5hrQB-fxqMk66zAY8Cp-CFyysSOME', [
    //                 'fields' => 'id,name'
    //             ]);
    //             Log::info('Folder found: ' . $folderCheck->getName());
    //         } catch (\Exception $e) {
    //             Log::error('Failed to access folder: ' . $e->getMessage());
    //             throw new \Exception('Folder cannot be accessed');
    //         }

    //         // Prepare file metadata
    //         $fileMetadata = new Drive\DriveFile([
    //             'name' => $pdfFileName,
    //             'parents' => ['124X5hrQB-fxqMk66zAY8Cp-CFyysSOME']
    //         ]);

    //         // Upload file to Google Drive
    //         $file = $driveService->files->create($fileMetadata, [
    //             'data' => $dompdf->output(),
    //             'mimeType' => 'application/pdf',
    //             'uploadType' => 'multipart',
    //             'fields' => 'id,webViewLink'
    //         ]);

    //         // Return file path along with Drive details for further use
    //         return [
    //             'pdf_path' => $pdfFullPath, // PDF file path
    //             'file_id' => $file->id,      // Google Drive file ID
    //             'web_view_link' => $file->webViewLink // Google Drive file view link
    //         ];
    //     } catch (\Exception $e) {
    //         Log::error('Upload failed: ' . $e->getMessage());
    //         throw $e;
    //     }
    // }

    // private function reversePaymentChanges(Debit $debit)
    // {
    //     Log::info('Memulai proses reverse pembayaran untuk Debit ID: ' . $debit->id);

    //     // Hapus kredit baru yang dibuat setelah pembayaran debit
    //     $newKredits = Kredit::where('petani_id', $debit->petani_id)
    //         ->where('created_at', '>=', $debit->created_at)
    //         ->get();

    //     foreach ($newKredits as $kredit) {
    //         Log::info('Menghapus Kredit baru:', ['kredit_id' => $kredit->id]);
    //         $kredit->forceDelete();
    //     }

    //     // Ambil semua kredit yang diupdate saat pembayaran
    //     $updatedKredits = Kredit::where('petani_id', $debit->petani_id)
    //         ->where('updated_at', '>=', $debit->created_at)
    //         ->where('status', true)
    //         ->get();

    //     Log::info('Jumlah kredit yang akan direset: ' . $updatedKredits->count());

    //     foreach ($updatedKredits as $kredit) {
    //         Log::info('Mereset Kredit ID: ' . $kredit->id);

    //         // Hapus informasi pembayaran dari keterangan
    //         $originalKeterangan = $this->removePaymentInfo($kredit->keterangan);

    //         $success = $kredit->update([
    //             'status' => false,
    //             'keterangan' => $originalKeterangan
    //         ]);

    //         if ($success) {
    //             Log::info('Kredit berhasil direset:', $kredit->toArray());
    //         } else {
    //             Log::error('Gagal mereset Kredit ID: ' . $kredit->id);
    //         }
    //     }

    //     Log::info('Proses reverse pembayaran selesai untuk Debit ID: ' . $debit->id);
    // }


    private function reversePaymentChanges(Debit $debit)
    {

        Log::info('Memulai proses reverse pembayaran untuk Debit ID: ' . $debit->id);

        // Ambil semua kredit yang terkait dengan debit ini
        $relatedKredits = Kredit::where('debit_id', $debit->id)->get();

        $relatedUtangKeOperators = UtangKeOperator::where('debit_id', $debit->id)->get();

        $petaniId = $debit->petani_id;

        // Ambil semua kredit yang terkait dengan petani_id dan memenuhi kondisi
        $relatedTrueKredits = Kredit::where('petani_id', $petaniId)
            ->where('status', true)
            ->whereNull('deleted_at')
            ->get();

        // Tentukan debit_id terbaru
        $latestDebitId = $relatedTrueKredits->max('debit_id');

        // Ambil kredit dengan debit_id yang lebih lama (sebelumnya)
        $previousKredits = $relatedTrueKredits->where('debit_id', '<', $latestDebitId);

        // Ambil semua debit_id dari previousKredits dan pastikan hanya ada satu nilai unik
        $debitPreviousIds = $previousKredits->pluck('debit_id')->unique();

        // Jika Anda hanya ingin mengambil satu debit_id pertama (dari yang lebih lama)
        $oneDebitPreviousId = $debitPreviousIds->first();

        // Dapatkan data Debit terakhir
        $lastDebit = Debit::with('petani.kredits')->orderBy('id', 'desc')->first();



        Log::info('Jumlah kredit yang akan direset: ' . $relatedKredits->count());

        foreach ($relatedKredits as $kredit) {
            Log::info('Memproses Kredit ID: ' . $kredit->id);

            // Jika status kredit adalah 0 (false) dan memiliki debit_id yang sama
            if ($kredit->status === false) {
                if (is_null($kredit->p_debit_id) || $kredit->debit_id < $kredit->p_debit_id) {
                    Log::info('Soft delete Kredit ID: ' . $kredit->id);
                    $kredit->delete(); // Soft delete
                } else {
                    // Hapus informasi pembayaran dari keterangan
                    $originalKeterangan = $this->removePaymentInfo($kredit->keterangan);

                    $kreditTanggal = $kredit->tanggal ? Carbon::parse($kredit->tanggal) : null;

                    $success = $kredit->update([
                        'status' => false,
                        'keterangan' => $originalKeterangan,
                        'debit_id' => $lastDebit?->id, // Hapus referensi ke debit
                        'updated_at' => $kreditTanggal,
                    ]);

                    if ($success) {
                        Log::info('Kredit berhasil direset:', $kredit->toArray());
                    } else {
                        Log::error('Gagal mereset Kredit ID: ' . $kredit->id);
                    }
                }
            }
            // Jika status kredit adalah 1 (true)
            else {
                Log::info('Mereset Kredit ID: ' . $kredit->id);

                // Hapus informasi pembayaran dari keterangan
                $originalKeterangan = $this->removePaymentInfo($kredit->keterangan);

                $kreditTanggal = $kredit->tanggal ? Carbon::parse($kredit->tanggal) : null;

                $success = $kredit->update([
                    'status' => false,
                    'keterangan' => $originalKeterangan,
                    'debit_id' => $lastDebit?->id, // Hapus referensi ke debit
                    'updated_at' => $kreditTanggal,
                ]);

                if ($success) {
                    Log::info('Kredit berhasil direset:', $kredit->toArray());
                } else {
                    Log::error('Gagal mereset Kredit ID: ' . $kredit->id);
                }
            }
        }

        foreach ($relatedUtangKeOperators as $kredit) {
            $kredit->delete(); // Soft delete
        }

        Log::info('Proses reverse pembayaran selesai untuk Debit ID: ' . $debit->id);
    }

    private function removePaymentInfo($keterangan)
    {
        // Hapus semua informasi pembayaran yang ditambahkan saat proses pembayaran
        $patterns = [
            '/\s*\|\s*Terbayar Penuh.*/',
            '/\s*\|\s*Terbayar Sebagian.*/',
            '/\s*\|\s*Debit:.*/',
            '/\s*\|\s*Sisa Hutang:.*/',
            '/\s*\|\s*Durasi:.*/'
        ];

        $cleanKeterangan = $keterangan;
        foreach ($patterns as $pattern) {
            $cleanKeterangan = preg_replace($pattern, '', $cleanKeterangan);
        }

        return trim($cleanKeterangan);
    }


    public function getTotalHutang($petaniId)
    {
        try {
            $petani = Petani::findOrFail($petaniId);
            $totalHutang = $petani->kredits()->where('status', false)->sum('jumlah');
            return response()->json(['total_hutang' => $totalHutang]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching total hutang: ' . $e->getMessage()], 500);
        }
    }
}
