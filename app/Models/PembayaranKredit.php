<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PembayaranKredit extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_kredits';

    protected $fillable = ['giling_id', 'total_hutang', 'dana_terbayar', 'bunga', 'created_at'];

    public function giling()
    {
        return $this->belongsTo(Giling::class);
    }

    public function kredits()
    {
        return $this->belongsToMany(Kredit::class);
    }


    public function kalkulasiBunga($gilingId)
    {
        // Retrieve the current Giling record to get the interest rate
        $giling = Giling::findOrFail($gilingId);
        $bungaRate = $giling->bunga / 100; // Convert percentage to decimal

        // Get all related Kredit records for this Petani that are still active (status false)
        $kredits = Kredit::where('petani_id', $giling->petani_id) // Adjust if needed based on your relationship
            ->where('status', false)
            ->get();

        $totalBunga = 0;

        Log::info('Calculating Hutang for Giling ID: ' . $gilingId);
        Log::info('Kredit records count: ' . $kredits->count());

        foreach ($kredits as $kredit) {
            // Calculate the duration in months
            $lamaBulan = $this->hitungLamaHutangBulan($kredit->tanggal);

            // Calculate the bunga for this kredit
            $bunga = $kredit->jumlah * $bungaRate * $lamaBulan;

            Log::info('Kredit ID: ' . $kredit->id . ', Jumlah: ' . number_format($kredit->jumlah, 2) . ', Bunga: ' . number_format($bunga, 2));

            // Accumulate the total bunga
            $totalBunga += $bunga;
        }

        Log::info('Total bunga yang dihitung', ['totalBunga' => number_format($totalBunga, 2)]);

        return $totalBunga;
    }


    // public function hitungLamaHutangBulan($tanggalKredit)
    // {
    //     $tanggalPembayaran = $this->created_at ?? Carbon::now();

    //     if (!$tanggalKredit instanceof Carbon) {
    //         $tanggalKredit = Carbon::parse($tanggalKredit);
    //     }

    //     // Hitung selisih bulan dan lakukan pembulatan kebawah
    //     return floor($tanggalKredit->diffInMonths($tanggalPembayaran)); // Pembulatan kebawah
    // }

    public function hitungLamaHutangBulan($tanggalKredit)
    {
        $tanggalPembayaran = $this->created_at ?? Carbon::now();
        if (!$tanggalKredit instanceof Carbon) {
            $tanggalKredit = Carbon::parse($tanggalKredit);
        }

        // Hitung selisih bulan tanpa pembulatan dulu
        $selisihBulan = $tanggalKredit->diffInMonths($tanggalPembayaran);

        // Jika tanggal kredit lebih besar dari tanggal pembayaran
        if ($tanggalKredit > $tanggalPembayaran) {
            return 0; // Kembalikan 0 jika hasilnya akan minus
        }

        // Jika tidak minus, lakukan pembulatan kebawah seperti biasa
        return floor($selisihBulan);
    }
}
