<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Debit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['petani_id', 'jumlah', 'bunga', 'keterangan', 'tanggal'];

    protected $dates = ['tanggal'];

    protected $casts = [
        'tanggal' => 'datetime', // Ensure 'tanggal' is a Carbon date
    ];

    public function petani()
    {
        return $this->belongsTo(Petani::class, 'petani_id', 'id');
    }

    public function calculateTotalHutangDenganBunga()
    {
        if (!$this->petani) {
            Log::error("Petani not found for Debit ID: {$this->id}");
            return 0;
        }

        $paymentDate = $this->tanggal ? Carbon::parse($this->tanggal) : Carbon::now();
        $kredits = $this->petani->kredits()->where('status', false)->get();

        if ($kredits->isEmpty()) {
            Log::error("No outstanding Kredit found for Petani ID: {$this->petani_id}");
            return 0;
        }

        $totalHutang = 0;
        $totalBunga = 0;

        foreach ($kredits as $kredit) {
            $totalHutang += $kredit->jumlah;

            $creditDate = Carbon::parse($kredit->tanggal);
            $debtDurationMonths = $creditDate->diffInMonths($paymentDate);

            Log::info("Kredit: {$kredit->jumlah}, Tanggal: {$kredit->tanggal}, Durasi Hutang: {$debtDurationMonths} bulan");

            if ($debtDurationMonths > 0) {
                $monthlyInterest = $kredit->jumlah * ($this->bunga / 100);
                $totalBungaForKredit = $monthlyInterest * $debtDurationMonths;
                $totalBunga += $totalBungaForKredit;

                Log::info("Bunga Bulanan: {$monthlyInterest}, Total Bunga untuk Kredit: {$totalBungaForKredit}");
            }
        }

        $totalHutangDenganBunga = $totalHutang + $totalBunga;

        Log::info("Total Hutang: " . number_format($totalHutang, 2) . ", Total Bunga: " . number_format($totalBunga, 2) . ", Total Hutang dengan Bunga: " . number_format($totalHutangDenganBunga, 2) . " untuk Debit ID: {$this->id}");

        return $totalHutangDenganBunga;
    }

    public function processPayment()
    {
        DB::beginTransaction();
        try {
            // Assume that you have calculated totalHutangDenganBunga already
            $totalHutangDenganBunga = $this->calculateTotalHutangDenganBunga();
            $paymentDate = $this->tanggal ? Carbon::parse($this->tanggal) : Carbon::now();
            $kredits = $this->petani->kredits()->where('status', false)->get();
            // Check if there are outstanding credits
            if ($kredits->isNotEmpty()) {
                // Get the first outstanding credit (if you are certain there is only one)
                $kredit = $kredits->first();
                $creditDate = Carbon::parse($kredit->tanggal);
                $debtDurationMonths = $creditDate->diffInMonths($paymentDate);
                if ($this->jumlah >= $totalHutangDenganBunga) {
                    // Pay off the credit fully
                    foreach ($kredits as $kredit) {
                        $kredit->status = true;
                        $kredit->keterangan = $kredit->keterangan . ' | Terbayar Penuh | Debit: Rp ' . number_format($totalHutangDenganBunga, 2);
                        $kredit->save();
                    }
                    $this->keterangan .= " | Terbayar Penuh | Durasi: " . floor($debtDurationMonths) . " bulan";
                } else {
                    // Pay off partially
                    $sisaHutang = $totalHutangDenganBunga - $this->jumlah;
                    foreach ($kredits as $kredit) {
                        $kredit->status = true;
                        $kredit->keterangan = $kredit->keterangan . ' | Terbayar Sebagian | Debit: Rp ' . number_format($this->jumlah, 2) .
                            ' | Sisa Hutang: Rp ' . number_format($sisaHutang, 2);
                        $kredit->save();
                    }
                    $this->keterangan .= ' | Terbayar Sebagian | Kredit: Rp ' . number_format($totalHutangDenganBunga, 2) .
                        ' | Sisa Hutang: Rp ' . number_format($sisaHutang, 2);
                    // Create a new Kredit entry for the remaining debt
                    Kredit::create([
                        'petani_id' => $this->petani_id,
                        'tanggal' => $this->tanggal,
                        'jumlah' => $sisaHutang,
                        'keterangan' => 'Terbayar Sebagian | Rp ' . number_format($this->jumlah, 2) .
                            ' (Debit) - Rp ' . number_format($totalHutangDenganBunga, 2) .
                            ' (Kredit)',
                    ]);
                }
            } else {
                // Handle case where there are no outstanding credits
                Log::warning("No outstanding credits found for Petani ID: {$this->petani_id}");
            }
            $this->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error processing payment: " . $e->getMessage());
            throw $e;
        }
    }
}
