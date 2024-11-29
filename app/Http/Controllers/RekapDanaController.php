<?php

namespace App\Http\Controllers;

use App\Models\Kredit;
use App\Models\RekapDana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class RekapDanaController extends Controller
{

    // Method untuk menampilkan halaman form input dan data total_kredit_plus_bunga
    public function index()
    {
        // Menghitung total kredit plus bunga menggunakan method dari model Kredit
        $totalKredit = Kredit::calculateTotalKredit();

        // Mengirim data ke view 'rekap-dana.blade.php'
        return view('rekap-dana', compact('totalKredit'));
    }

    public function indexDaftar()
    {
        // Mengambil data dari tabel 'rekap_dana' dengan pagination 20 per halaman
        $rekapDanas = RekapDana::paginate(20);

        // Mengirim data ke view 'daftar-rekapan-dana'
        return view('daftar-rekapan-dana', compact('rekapDanas'));
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

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            // Kelompok 1
            'nasabah_palu' => 'required|numeric',
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
        $totalKredit = Kredit::calculateTotalKredit();

        // Ambil data dari request
        $data = $request->all();

        // Menghitung total untuk Kelompok 2
        $stokBerasTotal = $data['stok_beras_jumlah'] * $data['stok_beras_harga'];
        $ongkosJemurTotal = $data['ongkos_jemur_jumlah'] * $data['ongkos_jemur_harga'];
        $berasTerpinjamTotal = $data['beras_terpinjam_jumlah'] * $data['beras_terpinjam_harga'];

        // Menghitung nilai rekapan_dana = Kelompok 1 + Kelompok 2 (total) - Kelompok 3
        $kelompok1Total = $data['nasabah_palu'] + $data['bri'] + $data['bni'] + $data['tunai'] + $data['mama'];
        $kelompok2Total = $stokBerasTotal + $ongkosJemurTotal + $berasTerpinjamTotal;
        $kelompok3Total = $data['pinjaman_bank'] + $data['titipan_petani'] + $data['utang_beras'] + $data['utang_ke_operator'];

        $rekapanDana = $kelompok1Total + $kelompok2Total - $kelompok3Total - $totalKredit;

        // Menambahkan nilai total_kredit dan rekapan_dana sebelum disimpan
        $data['total_kredit'] = $totalKredit;
        $data['stok_beras_total'] = $stokBerasTotal;
        $data['ongkos_jemur_total'] = $ongkosJemurTotal;
        $data['beras_terpinjam_total'] = $berasTerpinjamTotal;
        $data['rekapan_dana'] = $rekapanDana;

        try {
            // Simpan data ke dalam tabel rekap_dana
            RekapDana::create($data); // Pastikan mass assignment diatur di model
            // return response()->json([
            //     'status' => 'success',
            //     'message' => 'Data berhasil disimpan.'
            // ], 201);
            return redirect()->route('rekapDana.index');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }
}
