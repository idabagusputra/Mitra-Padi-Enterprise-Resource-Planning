<?php

namespace App\Http\Controllers;

use App\Models\DaftarGiling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $currentYear = Carbon::now()->year;

        // Fetch data for ongkos_giling and total_hutang grouped by month for the current year
        $monthlyData = DaftarGiling::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(ongkos_giling) as total_ongkos_giling'),
            DB::raw('SUM(total_hutang) as total_hutang')
        )
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        // Prepare data for all 12 months
        $labels = [];
        $ongkosGilingData = [];
        $totalHutangData = [];

        for ($month = 1; $month <= 12; $month++) {
            $labels[] = date('M', mktime(0, 0, 0, $month, 1));
            $ongkosGilingData[] = $monthlyData->get($month)->total_ongkos_giling ?? 0;
            $totalHutangData[] = $monthlyData->get($month)->total_hutang ?? 0;
        }

        // Convert data to JSON strings
        $labelsJson = json_encode($labels);
        $ongkosGilingDataJson = json_encode($ongkosGilingData);
        $totalHutangDataJson = json_encode($totalHutangData);

        // Encode the JSON strings again to ensure they are properly escaped for JavaScript
        $labelsJson = json_encode($labelsJson);
        $ongkosGilingDataJson = json_encode($ongkosGilingDataJson);
        $totalHutangDataJson = json_encode($totalHutangDataJson);

        return view('dashboard', compact('labelsJson', 'ongkosGilingDataJson', 'totalHutangDataJson'));
    }
}
