<?php

namespace App\Http\Controllers;

use App\Models\DaftarGiling;
use Google\Client;
use Google\Service\Drive;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\DB;
use Aws\S3\S3Client;

class ReceiptController extends Controller
{
    public function generatePdf($gilingId)
    {
        $daftarGiling = DaftarGiling::findOrFail($gilingId);
        $giling = $daftarGiling->giling()->with(['petani', 'pengambilans', 'petani.kredits' => function ($query) {
            $query->where('status', false);
        }])->first();

        if (!$giling) {
            Log::error("Giling not found for DaftarGiling ID: {$gilingId}");
            abort(404, 'Data Giling tidak ditemukan.');
        }

        // Get unpaid kredits
        $unpaidKredits = $giling->petani->kredits->where('status', false);

        // Calculate lama_bulan for each kredit
        $now = Carbon::now();
        foreach ($unpaidKredits as $kredit) {
            $tanggal = Carbon::parse($kredit->tanggal);
            $kredit->lama_bulan = $tanggal->diffInMonths($now);
        }

        // Setup DomPDF dengan konfigurasi khusus
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'sans-serif');
        $options->set('isFontSubsettingEnabled', true);
        $options->set('defaultMediaType', 'print');
        $options->set('dpi', 96);
        $options->set('debugKeepTemp', true);

        // Convert mm to points (1mm = 2.83465 points)
        $widthMm = 86;
        $width = $widthMm * 2.83465;

        // Get HTML content using existing view
        $htmlContent = view('receipt.thermal', compact('giling', 'daftarGiling', 'unpaidKredits'))->render();

        // Add default CSS dengan @page size auto untuk flexible height
        $defaultCss = '
        <style>
            @page {
                size: ' . $widthMm . 'mm auto;
                margin: 0mm 3mm 3mm 3mm;
            }
            body {
                font-family: sans-serif;
                margin: 0;
                font-size: 10pt;
                line-height: 1.3;
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
        </style>
    ';

        // Combine CSS with HTML content
        $htmlContent = $defaultCss . $htmlContent;

        // Create DomPDF instance
        $dompdf = new Dompdf($options);

        // Set custom paper size - PENTING: gunakan large height untuk auto sizing
        $dompdf->setPaper(array(0, 0, $width, 10000));

        // Load HTML
        $dompdf->loadHtml($htmlContent);

        // Render PDF
        $dompdf->render();

        // KUNCI: Setelah render, extract actual used height dan re-render dengan ukuran tepat
        $pages = $dompdf->getPages();

        if (!empty($pages)) {
            // Render ulang dengan ukuran yang sebenarnya digunakan
            $dompdf = new Dompdf($options);

            // Estimasi tinggi berdasarkan jumlah pages
            // 1 page = content fit dalam ~300-800pt tergantung konten
            // Multiple pages = each page ~792pt (standar)
            $estimatedHeight = $this->getEstimatedHeight(count($pages));

            $dompdf->setPaper(array(0, 0, $width, $estimatedHeight));
            $dompdf->loadHtml($htmlContent);
            $dompdf->render();
        }

        // Define PDF path
        $pdfFileName = 'receipt-' . $giling->id . '.pdf';
        $pdfPath = public_path('receipts');

        // Ensure directory exists
        if (!file_exists($pdfPath)) {
            mkdir($pdfPath, 0755, true);
        }

        $pdfFullPath = $pdfPath . '/' . $pdfFileName;

        try {
            // Save PDF to file
            file_put_contents($pdfFullPath, $dompdf->output());
            $pdfContent = $dompdf->output();

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

            $r2FileName = 'Nota_Giling/' . $giling->id . '_Nota_Giling_' . date('Y-m-d_H-i-s') . '.pdf';

            $r2Upload = $r2Client->putObject([
                'Bucket' => 'mitra-padi',
                'Body' => $pdfContent,
                'Key' => $r2FileName,
                'ContentType' => 'application/pdf',
                'ACL' => 'public-read'
            ]);

            $r2Url = "https://pub-b2576acededb43e08e7292257cd6a4c8.r2.dev/{$r2FileName}";

            $daftarGiling->s3_url = $r2Url;
            $daftarGiling->save();

            // Set up Google Drive client
            $client = new Client();
            $client->setAuthConfig(storage_path('app/google-drive-credentials.json'));
            $client->addScope(Drive::DRIVE);

            $driveService = new Drive($client);

            // Check folder access
            try {
                $folderCheck = $driveService->files->get('124X5hrQB-fxqMk66zAY8Cp-CFyysSOME', [
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
                'parents' => ['124X5hrQB-fxqMk66zAY8Cp-CFyysSOME']
            ]);

            // Upload file to Google Drive
            $file = $driveService->files->create($fileMetadata, [
                'data' => $dompdf->output(),
                'mimeType' => 'application/pdf',
                'uploadType' => 'multipart',
                'fields' => 'id,webViewLink'
            ]);

            return [
                'pdf_path' => $pdfFullPath,
                'file_id' => $file->id,
                'web_view_link' => $file->webViewLink
            ];
        } catch (\Exception $e) {
            Log::error('Upload failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Estimasi tinggi berdasarkan jumlah pages yang dirender
     *
     * @param int $numPages Jumlah pages dari render pertama
     * @return float Tinggi dalam points
     */
    private function getEstimatedHeight($numPages)
    {
        // Untuk receipt thermal 86mm:
        // - 1 page = konten singkat, ~400-600pt
        // - 2+ pages = ada konten panjang

        if ($numPages <= 1) {
            // Single page - gunakan tinggi minimal yang cukup
            return 600 * 2.83465; // ~150mm
        }

        if ($numPages === 2) {
            return 800 * 2.83465; // ~280mm
        }

        // 3+ pages - scale accordingly
        return (400 + ($numPages - 1) * 300) * 2.83465;
    }

    public function printLatest()
    {
        try {
            $latestGiling = DaftarGiling::latest()->first();

            if (!$latestGiling) {
                return redirect()->back()->withErrors(['error' => 'Data giling tidak ditemukan.']);
            }

            $pdfFullPath = $this->generatePdf($latestGiling->id);

            return response()->file($pdfFullPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="receipt-' . $latestGiling->id . '.pdf"',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ]);
        } catch (\Exception $e) {
            Log::error("Error in printLatest: " . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Gagal generate receipt.']);
        }
    }

    public function printPdf($id)
    {
        try {
            // Find DaftarGiling record by ID
            $daftarGiling = DaftarGiling::findOrFail($id);

            // Generate the PDF and get the file path
            $result = $this->generatePdf($daftarGiling->id); // Assuming generatePdf returns the file path
            $pdfFullPath = $result['pdf_path']; // Retrieve the generated PDF file path from the result

            // Check if the generated PDF exists
            if (!file_exists($pdfFullPath)) {
                throw new \Exception("PDF file not found");
            }

            // Return the PDF file as an inline response
            return response()->file($pdfFullPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="receipt-' . $daftarGiling->id . '.pdf"',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
                'X-Content-Type-Options' => 'nosniff'
            ]);
        } catch (\Exception $e) {
            // Log the error and return a JSON error response
            Log::error('Error in printPdf: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to generate PDF'], 500);
        }
    }
}
