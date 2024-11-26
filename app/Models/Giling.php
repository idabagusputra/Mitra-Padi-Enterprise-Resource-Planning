<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class Giling extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'created_at',
        'petani_id',
        'giling_kotor',
        'biaya_giling',
        'pulang',
        'pinjam',
        'harga_jual',
        'biaya_buruh_giling',
        'biaya_buruh_jemur',
        'jemur',
        'jumlah_konga',
        'harga_konga',
        'jumlah_menir',
        'harga_menir',
        'harga_jual'
    ];

    public function petani()
    {
        return $this->belongsTo(Petani::class, 'petani_id', 'id');
    }

    public function pembayaranKredits()
    {
        return $this->hasMany(PembayaranKredit::class);
    }

    public function pengambilans()
    {
        return $this->hasMany(Pengambilan::class);
    }

    public function daftarGiling()
    {
        return $this->hasMany(DaftarGiling::class);
    }

    public function calculateBiayaGiling()
    {
        $result = $this->giling_kotor * ($this->biaya_giling / 100);
        Log::info("calculateBiayaGiling for Giling ID {$this->id}: {$result}");
        return $result;
    }

    public function calculateBerasBersih()
    {
        $result = $this->giling_kotor - $this->calculateBiayaGiling() - $this->pinjam;
        Log::info("calculateBerasBersih for Giling ID {$this->id}: {$result}");
        return $result;
    }

    public function calculateBerasJual()
    {
        $result = ($this->calculateBerasBersih() - $this->pulang) * $this->harga_jual;
        Log::info("calculateBerasJual for Giling ID {$this->id}: {$result}");
        return $result;
    }

    public function calculateBuruhGiling()
    {
        $result = $this->giling_kotor * $this->biaya_buruh_giling;
        Log::info("calculateBuruhGiling for Giling ID {$this->id}: {$result}");
        return $result;
    }

    public function calculateBuruhJemur()
    {
        $result = $this->jemur * $this->biaya_buruh_jemur;
        Log::info("calculateBuruhJemur for Giling ID {$this->id}: {$result}");
        return $result;
    }

    public function calculateJualKonga()
    {
        $result = $this->jumlah_konga * $this->harga_konga;
        Log::info("calculateJualKonga for Giling ID {$this->id}: {$result}");
        return $result;
    }

    public function calculateJualMenir()
    {
        $result = $this->jumlah_menir * $this->harga_menir;
        Log::info("calculateJualMenir for Giling ID {$this->id}: {$result}");
        return $result;
    }

    public function calculateHutang()
    {
        Log::info("Calculating Hutang for Giling ID: {$this->id}");

        if (!$this->petani) {
            Log::info("No Petani associated with Giling ID {$this->id}. Returning 0.");
            return 0;
        }

        $kredits = $this->petani->kredits()->where('status', false)->get();
        Log::info("Kredit records count for Giling ID {$this->id}: {$kredits->count()}");

        $totalHutang = 0;
        foreach ($kredits as $kredit) {
            Log::info("Kredit ID: {$kredit->id}, Jumlah: {$kredit->jumlah}");
            $totalHutang += $kredit->jumlah;
        }

        Log::info("Calculated Total Hutang for Giling ID {$this->id}: {$totalHutang}");
        return $totalHutang;
    }

    public function kalkulasiBunga($bungaInput)
    {
        Log::info("Calculating Bunga for Giling ID: {$this->id}, Bunga Input: {$bungaInput}");
        $paymentDate = $this->created_at ? Carbon::parse($this->created_at) : Carbon::now();
        $totalBunga = 0;
        $credits = Kredit::where('petani_id', $this->petani_id)
            ->where('status', false)
            ->get();

        foreach ($credits as $credit) {
            $creditDate = Carbon::parse($credit->tanggal);
            $debtDuration = $creditDate->diffInMonths($paymentDate);
            $debtDurationMonths = floor($debtDuration);

            Log::info("Credit: {$credit->jumlah}, Date: {$credit->tanggal}, Debt Duration: {$debtDurationMonths} months");

            if ($debtDurationMonths > 0) {
                $monthlyInterest = $credit->jumlah * ($bungaInput / 100);
                $totalBungaForCredit = $monthlyInterest * $debtDurationMonths;
                $totalBunga += $totalBungaForCredit;

                Log::info("Monthly Interest: {$monthlyInterest}, Total Interest for Credit: {$totalBungaForCredit}");
            }
        }

        Log::info("Total Interest for Giling ID {$this->id}: {$totalBunga}");
        return $totalBunga;
    }


    public function calculateTotalPengambilan()
    {
        Log::info("Calculating Total Pengambilan for Giling ID: {$this->id}");
        $pengambilans = $this->pengambilans;
        Log::info("Pengambilans count: {$pengambilans->count()}");

        $total = $pengambilans->sum(function ($pengambilan) {
            $subtotal = $pengambilan->jumlah * $pengambilan->harga;
            Log::info("Pengambilan ID: {$pengambilan->id}, Jumlah: {$pengambilan->jumlah}, Harga: {$pengambilan->harga}, Subtotal: {$subtotal}");
            return $subtotal;
        });

        Log::info("Calculated Total Pengambilan for Giling ID {$this->id}: {$total}");
        return $total;
    }

    public function calculateDana()
    {
        Log::info("Calculating Dana for Giling ID: {$this->id}");
        $hasil = $this->calculateBerasJual()
            - $this->calculateBuruhGiling()
            - $this->calculateBuruhJemur()
            + $this->calculateJualKonga()
            + $this->calculateJualMenir();

        Log::info("Calculated Dana for Giling ID {$this->id}: {$hasil}");
        return $hasil;
    }

    public function calculateTotalHutangDenganBunga($bungaRate)
    {
        // Calculate the total debt from the related kredits
        $totalHutang = $this->petani->kredits()->where('status', false)->sum('jumlah');

        // Calculate total debt with interest
        return $totalHutang * (1 + $bungaRate / 100);
    }


    public function getAllCalculations()
    {
        Log::info("Getting All Calculations for Giling ID: {$this->id}");
        $hutang = $this->calculateHutang();
        $dana = $this->calculateDana();
        $totalPengambilan = $this->calculateTotalPengambilan();

        $calculations = [
            'biaya_giling_kg' => $this->calculateBiayaGiling(),
            'beras_bersih' => $this->calculateBerasBersih(),
            'beras_jual' => $this->calculateBerasJual(),
            'buruh_giling' => $this->calculateBuruhGiling(),
            'buruh_jemur' => $this->calculateBuruhJemur(),
            'jual_konga' => $this->calculateJualKonga(),
            'jual_menir' => $this->calculateJualMenir(),
            'hutang' => $hutang,
            // 'total_pengambilan' => $totalPengambilan,
            'dana' => $dana,
            // 'dana_bersih' => $dana - $hutang - $totalPengambilan,
        ];

        Log::info("All Calculations for Giling ID {$this->id}:", $calculations);
        return $calculations;
    }
}
