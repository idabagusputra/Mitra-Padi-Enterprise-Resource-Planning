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

        $unpaidKredits = $giling->petani->kredits->where('status', false);
        $now = Carbon::now();
        foreach ($unpaidKredits as $kredit) {
            $tanggal = Carbon::parse($kredit->tanggal);
            $kredit->lama_bulan = $tanggal->diffInMonths($now);
        }

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'sans-serif');
        $options->set('isFontSubsettingEnabled', true);
        $options->set('defaultMediaType', 'print');
        $options->set('dpi', 96);
        $options->set('debugKeepTemp', true);

        $htmlContent = view('receipt.thermal', compact('giling', 'daftarGiling', 'unpaidKredits'))->render();

        $defaultCss = '
        <style>
            @page { margin: 0mm 3mm 3mm 3mm; }
            body {
                font-family: sans-serif;
                margin: 0;
                font-size: 10pt;
                line-height: 1.3;
            }
            * { box-sizing: border-box; }
            table { width: 100%; }
            .text-center { text-align: center; }
            .text-right { text-align: right; }
            .font-bold { font-weight: bold; }
        </style>
    ';

        $htmlContent = $defaultCss . $htmlContent;

        $width = 86 * 2.83465; // lebar tetap dalam points

        // ── PASS 1: ukur tinggi konten ──────────────────────────────
        $dompdf = new Dompdf($options);
        $dompdf->setPaper([0, 0, $width, 99999]);
        $dompdf->loadHtml($htmlContent);
        $dompdf->render();

        $canvas     = $dompdf->getCanvas();
        $pageHeight = $canvas->get_height();

        // Coba get_cpdf() dulu, fallback ke Reflection
        if (method_exists($canvas, 'get_cpdf')) {
            $cpdf     = $canvas->get_cpdf();
            $currentY = $cpdf->y;
        } else {
            // Fallback: akses property $_pdf via Reflection
            $reflection = new \ReflectionClass($canvas);
            $prop       = $reflection->getProperty('_pdf');
            $prop->setAccessible(true);
            $cpdf       = $prop->getValue($canvas);
            $currentY   = $cpdf->y;
        }

        $contentHeight = ($pageHeight - $currentY) + 20;

        // ── PASS 2: render dengan tinggi dinamis ────────────────────
        $dompdf2 = new Dompdf($options);
        $dompdf2->setPaper([0, 0, $width, $contentHeight]);
        $dompdf2->loadHtml($htmlContent);
        $dompdf2->render();

        $pdfContent = $dompdf2->output();

        // ── Simpan ke disk ──────────────────────────────────────────
        $pdfFileName = 'receipt-' . $giling->id . '.pdf';
        $pdfPath     = public_path('receipts');

        if (!file_exists($pdfPath)) {
            mkdir($pdfPath, 0755, true);
        }

        $pdfFullPath = $pdfPath . '/' . $pdfFileName;
        file_put_contents($pdfFullPath, $pdfContent);

        try {
            // ── Upload ke Cloudflare R2 ─────────────────────────────
            $r2Client = new S3Client([
                'version'     => 'latest',
                'region'      => 'auto',
                'endpoint'    => 'https://c9961806b72189a4d763edfd8dc0e55f.r2.cloudflarestorage.com',
                'credentials' => [
                    'key'    => '2abc6cf8c76a71e84264efef65031933',
                    'secret' => '1aa2ca39d8480cdbf846807ad5a7a1e492e72ee9a947ead03ef5d8ad67dea45d',
                ]
            ]);

            $r2FileName = 'Nota_Giling/' . $giling->id . '_Nota_Giling_' . date('Y-m-d_H-i-s') . '.pdf';

            $r2Client->putObject([
                'Bucket'      => 'mitra-padi',
                'Body'        => $pdfContent,
                'Key'         => $r2FileName,
                'ContentType' => 'application/pdf',
                'ACL'         => 'public-read'
            ]);

            $r2Url = "https://pub-b2576acededb43e08e7292257cd6a4c8.r2.dev/{$r2FileName}";
            $daftarGiling->s3_url = $r2Url;
            $daftarGiling->save();

            // ── Upload ke Google Drive ──────────────────────────────
            $client = new Client();
            $client->setAuthConfig(storage_path('app/google-drive-credentials.json'));
            $client->addScope(Drive::DRIVE);

            $driveService = new Drive($client);

            $folderCheck = $driveService->files->get('124X5hrQB-fxqMk66zAY8Cp-CFyysSOME', ['fields' => 'id,name']);
            Log::info('Folder found: ' . $folderCheck->getName());

            $fileMetadata = new Drive\DriveFile([
                'name'    => $pdfFileName,
                'parents' => ['124X5hrQB-fxqMk66zAY8Cp-CFyysSOME']
            ]);

            $file = $driveService->files->create($fileMetadata, [
                'data'       => $pdfContent,
                'mimeType'   => 'application/pdf',
                'uploadType' => 'multipart',
                'fields'     => 'id,webViewLink'
            ]);

            return [
                'pdf_path'      => $pdfFullPath,
                'file_id'       => $file->id,
                'web_view_link' => $file->webViewLink
            ];
        } catch (\Exception $e) {
            Log::error('Upload failed: ' . $e->getMessage());
            throw $e;
        }
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
