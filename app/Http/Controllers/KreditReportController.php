<?php

namespace App\Http\Controllers;

use App\Models\Kredit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;

class KreditReportController extends Controller
{
    public function generatePdf(Request $request)
    {
        // Tetapkan langsung nilai 'desc' untuk sortOrder
        $sortOrder = 'desc';

        $allKredits = Kredit::with('petani')->get();
        $now = Carbon::now();

        $calculatedKredits = $allKredits->map(function ($kredit) use ($now) {
            $kreditDate = Carbon::parse($kredit->tanggal);
            $selisihBulan = $kreditDate->diffInMonths($now);
            $bunga = $kredit->jumlah * 0.02 * $selisihBulan;
            $hutangPlusBunga = $kredit->jumlah + $bunga;

            $kredit->setAttribute('hutang_plus_bunga', $hutangPlusBunga);
            $kredit->setAttribute('lama_bulan', $selisihBulan);
            $kredit->setAttribute('bunga', $bunga);

            return $kredit;
        });

        // Urutkan data sesuai sortOrder
        $sortedKredits = $calculatedKredits->sortBy(function ($item) {
            return [$item->tanggal, $item->id];
        }, SORT_REGULAR, $sortOrder === 'desc');

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


        // Generate PDF dengan Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Stream PDF ke browser
        // Ubah stream menjadi download
        return $dompdf->stream('Laporan_Kredit_' . date('Y-m-d_H-i-s') . '.pdf', [
            'Attachment' => true,
            'Content-Type' => 'application/pdf'
        ]);
    }
}
