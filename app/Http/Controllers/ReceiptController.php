<?php

namespace App\Http\Controllers;

use App\Models\DaftarGiling;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;

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
        // $options->set('debugCss', true);

        $dompdf = new Dompdf($options);

        // Convert mm to points (1mm = 2.83465 points)
        $width = 86 * 2.83465;
        $height = 400 * 2.83465;

        // Set custom paper size
        $dompdf->setPaper(array(0, 0, $width, $height));

        // Get HTML content using existing view
        $htmlContent = view('receipt.thermal', compact('giling', 'daftarGiling', 'unpaidKredits'))->render();

        // Add default CSS untuk memastikan tampilan sesuai
        $defaultCss = '
            <style>
                @page {
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

        // Load HTML ke DomPDF
        $dompdf->loadHtml($htmlContent);

        // Render PDF
        $dompdf->render();

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
            Log::info("PDF generated successfully: {$pdfFullPath}");
        } catch (\Exception $e) {
            Log::error("PDF generation failed: " . $e->getMessage());
            throw $e;
        }

        return $pdfFullPath;
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
            $daftarGiling = DaftarGiling::findOrFail($id);
            $pdfFullPath = $this->generatePdf($daftarGiling->id);

            if (!file_exists($pdfFullPath)) {
                throw new \Exception("PDF file not found");
            }

            return response()->file($pdfFullPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="receipt-' . $daftarGiling->id . '.pdf"',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
                'X-Content-Type-Options' => 'nosniff'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in printPdf: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to generate PDF'], 500);
        }
    }
}
