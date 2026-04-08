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
            $kredit->lama_bulan = Carbon::parse($kredit->tanggal)->diffInMonths($now);
        }

        // ── Setup DomPDF ────────────────────────────────────────────
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'sans-serif');
        $options->set('isFontSubsettingEnabled', true);
        $options->set('defaultMediaType', 'print');
        $options->set('dpi', 96);

        $widthMm = 86;
        $widthPt = $widthMm * 2.83465; // 243.78pt

        $htmlContent = view('receipt.thermal', compact('giling', 'daftarGiling', 'unpaidKredits'))->render();

        $defaultCss = '
    <style>
        @page { margin: 0mm 3mm 3mm 3mm; }
        body  { font-family: sans-serif; margin: 0; font-size: 10pt; line-height: 1.3; }
        *     { box-sizing: border-box; }
        table { width: 100%; }
        .text-center { text-align: center; }
        .text-right  { text-align: right;  }
        .font-bold   { font-weight: bold;  }
    </style>
';

        $fullHtml = $defaultCss . $htmlContent;

        // ── RENDER PERTAMA: height sangat besar ──────────────────────
        $dompdf = new Dompdf($options);
        $dompdf->setPaper([0, 0, $widthPt, 99999]);
        $dompdf->loadHtml($fullHtml);
        $dompdf->render();

        // ── AMBIL POSISI Y ELEMEN TERAKHIR dari CPDF ─────────────────
        // DomPDF koordinat: Y=0 ada di BAWAH (system PDF)
        // Elemen pertama ditulis di Y tinggi, elemen terakhir di Y rendah
        $cpdf    = $dompdf->getCanvas()->get_cpdf();
        $minY    = PHP_INT_MAX;

        foreach ($cpdf->objects as $obj) {
            if (!isset($obj['t']) || $obj['t'] !== 'contents' || empty($obj['c'])) {
                continue;
            }
            // Cari semua perintah "text position" (Td, TD, Tm) dan "draw" (re = rectangle)
            // Format: x y Td  atau  x y w h re
            if (preg_match_all('/([\d.]+)\s+([\d.]+)\s+Td/', $obj['c'], $tdMatches)) {
                foreach ($tdMatches[2] as $y) {
                    $minY = min($minY, (float)$y);
                }
            }
            if (preg_match_all('/([\d.]+)\s+([\d.]+)\s+([\d.]+)\s+([\d.]+)\s+re/', $obj['c'], $reMatches)) {
                foreach ($reMatches[2] as $y) {
                    $minY = min($minY, (float)$y);
                }
            }
        }

        // Konversi: tinggi konten = 99999 - minY (karena Y terbalik di PDF)
        // Tambah margin bawah 10pt (~3.5mm)
        if ($minY === PHP_INT_MAX) {
            $finalHeight = 400 * 2.83465; // fallback
        } else {
            $contentUsed = 99999 - $minY;
            $finalHeight = $contentUsed + 20; // 20pt padding bawah
        }

        Log::info("PDF Height - minY: {$minY}, contentUsed: " . (99999 - $minY) . ", finalHeight: {$finalHeight}");

        // ── RENDER KEDUA: ukuran tepat ───────────────────────────────
        $dompdf = new Dompdf($options);
        $dompdf->setPaper([0, 0, $widthPt, $finalHeight]);
        $dompdf->loadHtml($fullHtml);
        $dompdf->render();

        // ── LANGKAH 2: Ukur tinggi konten aktual ────────────────────
        $canvas      = $dompdf->getCanvas();
        $contentHeight = $canvas->get_height(); // dalam points

        // Tambahkan sedikit padding bawah agar konten tidak kepotong
        $paddingPt   = 10; // ~3.5mm
        $finalHeight = $contentHeight + $paddingPt;

        // ── LANGKAH 3: Render ulang dengan ukuran yang tepat ────────
        $dompdf = new Dompdf($options);
        $dompdf->setPaper([0, 0, $widthPt, $finalHeight]);
        $dompdf->loadHtml($htmlContent);
        $dompdf->render();

        // ── Simpan file ─────────────────────────────────────────────
        $pdfFileName = 'receipt-' . $giling->id . '.pdf';
        $pdfPath     = public_path('receipts');

        if (!file_exists($pdfPath)) {
            mkdir($pdfPath, 0755, true);
        }

        $pdfFullPath = $pdfPath . '/' . $pdfFileName;
        $pdfContent  = $dompdf->output();

        try {
            file_put_contents($pdfFullPath, $pdfContent);

            // ── Upload ke Cloudflare R2 ──────────────────────────────
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
                'ACL'         => 'public-read',
            ]);

            $r2Url = "https://pub-b2576acededb43e08e7292257cd6a4c8.r2.dev/{$r2FileName}";
            $daftarGiling->s3_url = $r2Url;
            $daftarGiling->save();

            // ── Upload ke Google Drive ───────────────────────────────
            $client = new Client();
            $client->setAuthConfig(storage_path('app/google-drive-credentials.json'));
            $client->addScope(Drive::DRIVE);
            $driveService = new Drive($client);

            $folderCheck = $driveService->files->get('124X5hrQB-fxqMk66zAY8Cp-CFyysSOME', ['fields' => 'id,name']);
            Log::info('Folder found: ' . $folderCheck->getName());

            $fileMetadata = new Drive\DriveFile([
                'name'    => $pdfFileName,
                'parents' => ['124X5hrQB-fxqMk66zAY8Cp-CFyysSOME'],
            ]);

            $file = $driveService->files->create($fileMetadata, [
                'data'       => $pdfContent,
                'mimeType'   => 'application/pdf',
                'uploadType' => 'multipart',
                'fields'     => 'id,webViewLink',
            ]);

            return [
                'pdf_path'      => $pdfFullPath,
                'file_id'       => $file->id,
                'web_view_link' => $file->webViewLink,
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
