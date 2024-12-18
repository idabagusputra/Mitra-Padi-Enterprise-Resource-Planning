<?php

namespace App\Http\Controllers;

use App\Models\DaftarGiling;
use App\Models\Petani;
use App\Models\Kredit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Menghitung total jumlah petani
        $totalPetani = Petani::count();

        $query = Kredit::with('petani');


        $kreditsBelumLunas = Kredit::with('petani')->where('status', false)->get();

        // Hitung jumlah total kredit belum lunas
        $totalKreditBelumLunas = $kreditsBelumLunas->sum('jumlah');

        // (Opsional) Hitung jumlah petani unik
        $jumlahPetaniBelumLunas = $kreditsBelumLunas->pluck('petani_id')->unique()->count();


        // Mendapatkan bulan saat ini
        $currentMonth = Carbon::now()->format('m');
        $currentYear = Carbon::now()->format('Y');

        // Query untuk mengambil nilai 'beras_bersih' yang memiliki 'created_at' pada bulan sekarang
        $totalBerasBersihBulanIni = DaftarGiling::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->sum('beras_bersih');

        $totalKeseluruhanOngkosGiling = DaftarGiling::sum('ongkos_giling');

        $totalKeseluruhanBulanIniOngkosGiling = DaftarGiling::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->sum('ongkos_giling');

        // // Mendapatkan bulan-bulan dari Januari hingga Desember
        // $months = range(1, 12);

        // // Menginisialisasi array untuk menampung data per bulan
        // $dataBerasBersih = [];
        // $dataOngkosGiling = [];
        // $dataPendapatanTerjual = [];

        // foreach ($months as $month) {
        //     $totalBerasBersih = DaftarGiling::whereMonth('created_at', $month)
        //         ->whereYear('created_at', Carbon::now()->year)
        //         ->sum('beras_bersih');
        //     $dataBerasBersih[] = $totalBerasBersih;

        //     $totalOngkosGiling = DaftarGiling::whereMonth('created_at', $month)
        //         ->whereYear('created_at', Carbon::now()->year)
        //         ->sum('ongkos_giling');
        //     $dataOngkosGiling[] = $totalOngkosGiling;

        //     $totalHargaJual = DaftarGiling::whereMonth('created_at', $month)
        //         ->whereYear('created_at', Carbon::now()->year)
        //         ->sum('harga_jual');
        //     $pendapatanBerasTerjual = $totalOngkosGiling * $totalHargaJual;
        //     $dataPendapatanTerjual[] = $pendapatanBerasTerjual;
        // }

        // Get the last 12 months starting from August 2023
        $months = [];
        $currentDate = Carbon::create(2024, 11, 1);
        $endDate = Carbon::now();

        // Collect the last 12 months
        while (count($months) < 6) {
            $months[] = $currentDate->copy();
            $currentDate->addMonth();
        }

        // Reinitialize arrays for data
        $dataBerasBersih = [];
        $dataOngkosGiling = [];
        $dataPendapatanTerjual = [];

        // Process each month
        foreach ($months as $monthDate) {
            $month = $monthDate->month;
            $year = $monthDate->year;

            // Calculate Beras Bersih for the specific month and year
            $totalBerasBersih = DaftarGiling::where(function ($query) use ($month, $year) {
                $query->whereMonth('created_at', $month)
                    ->whereYear('created_at', $year);
            })->sum('beras_bersih');
            $dataBerasBersih[] = $totalBerasBersih;

            // Hitung Total Ongkos Giling untuk bulan dan tahun spesifik
            $totalOngkosGiling = DaftarGiling::whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->sum('ongkos_giling');
            $dataOngkosGiling[] = $totalOngkosGiling;

            // Hitung Pendapatan Beras Terjual dengan mengalikan harga_jual dan ongkos_giling per baris
            $pendapatanBerasTerjual = DaftarGiling::whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->get()
                ->sum(function ($item) {
                    return $item->harga_jual * $item->ongkos_giling;
                });
            $dataPendapatanTerjual[] = $pendapatanBerasTerjual;

            $pendapatanBerasTerjualTotal = DaftarGiling::get()
                ->sum(function ($item) {
                    return $item->harga_jual * $item->ongkos_giling;
                });

            $pendapatanBerasTerjualTotalPerBulan = DaftarGiling::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->get()
                ->sum(function ($item) {
                    return $item->harga_jual * $item->ongkos_giling;
                });
        }

        // Create month labels for frontend use
        $monthLabels = array_map(function ($monthDate) {
            return $monthDate->format('M Y');
        }, $months);

        return view('dashboard', compact('totalKeseluruhanBulanIniOngkosGiling', 'pendapatanBerasTerjualTotal', 'pendapatanBerasTerjualTotalPerBulan', 'totalPetani', 'totalKreditBelumLunas', 'jumlahPetaniBelumLunas', 'totalBerasBersih', 'dataOngkosGiling', 'dataBerasBersih', 'dataPendapatanTerjual', 'monthLabels', 'totalBerasBersihBulanIni', 'totalKeseluruhanOngkosGiling'));


        // $currentYear = Carbon::now()->year;

        // // Fetch data for ongkos_giling and total_hutang grouped by month for the current year
        // $monthlyData = DaftarGiling::select(
        //     DB::raw('MONTH(created_at) as month'),
        //     DB::raw('SUM(ongkos_giling) as total_ongkos_giling'),
        //     DB::raw('SUM(total_hutang) as total_hutang')
        // )
        //     ->whereYear('created_at', $currentYear)
        //     ->groupBy('month')
        //     ->get()
        //     ->keyBy('month');

        // // Prepare data for all 12 months
        // $labels = [];
        // $ongkosGilingData = [];
        // $totalHutangData = [];

        // for ($month = 1; $month <= 12; $month++) {
        //     $labels[] = date('M', mktime(0, 0, 0, $month, 1));
        //     $ongkosGilingData[] = $monthlyData->get($month)->total_ongkos_giling ?? 0;
        //     $totalHutangData[] = $monthlyData->get($month)->total_hutang ?? 0;
        // }

        // // Convert data to JSON strings
        // $labelsJson = json_encode($labels);
        // $ongkosGilingDataJson = json_encode($ongkosGilingData);
        // $totalHutangDataJson = json_encode($totalHutangData);

        // // Encode the JSON strings again to ensure they are properly escaped for JavaScript
        // $labelsJson = json_encode($labelsJson);
        // $ongkosGilingDataJson = json_encode($ongkosGilingDataJson);
        // $totalHutangDataJson = json_encode($totalHutangDataJson);

    }
}
