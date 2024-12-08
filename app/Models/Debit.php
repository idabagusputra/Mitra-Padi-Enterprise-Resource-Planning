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

    protected $fillable = ['petani_id', 'jumlah', 'bunga', 'keterangan', 'tanggal', 'updated_at'];

    protected $dates = ['tanggal'];

    protected $casts = [
        'tanggal' => 'datetime', // Ensure 'tanggal' is a Carbon date
    ];

    public function petani()
    {
        return $this->belongsTo(Petani::class, 'petani_id', 'id');
    }

    public function debitStatusTrueTerakhir()
    {
        // Mengambil semua kredit dengan status false
        $kredits = $this->petani->kredits()->where('status', true)->get();

        // Mengambil debit_id terakhir dari koleksi kredits
        $lastDebitId = $kredits->last()?->debit_id;

        // Jika lastDebitId ditemukan, kembalikan data debit, jika tidak, kembalikan null
        $lastDebit = $lastDebitId ? Debit::find($lastDebitId) : null;

        return $lastDebit;
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
            // Urutkan kredit berdasarkan durasi/tanggal tertua
            $kredits = $this->petani->kredits()
                ->where('status', false)
                ->orderBy('tanggal', 'asc')
                ->get();

            if ($kredits->isEmpty()) {
                Log::warning("No outstanding credits found for Petani ID: {$this->petani_id}");
                return false;
            }

            $remainingPayment = $this->jumlah;

            foreach ($kredits as $kredit) {
                // Hitung bunga untuk kredit ini
                $creditDate = Carbon::parse($kredit->tanggal);
                $paymentDate = $this->tanggal ? Carbon::parse($this->tanggal) : Carbon::now();
                $debtDurationMonths = $creditDate->diffInMonths($paymentDate);

                $monthlyInterest = $kredit->jumlah * ($this->bunga / 100);
                $totalBungaForKredit = $monthlyInterest * $debtDurationMonths;
                $totalKreditDenganBunga = $kredit->jumlah + $totalBungaForKredit;

                // Mengambil data debits terakhir untuk petani yang bersangkutan
                $lastDebit = Debit::orderBy('created_at', 'desc')->first();

                // Jika sisa pembayaran cukup untuk melunasi kredit ini
                if ($remainingPayment >= $totalKreditDenganBunga) {
                    $remainingPayment -= $totalKreditDenganBunga;

                    // Tandai kredit sebagai lunas
                    $kredit->status = true;
                    $kredit->debit_id = $lastDebit->id;
                    $kredit->keterangan .= " | Terbayar Penuh | Debit: Rp. " . number_format($remainingPayment);
                    $kredit->updated_at = $paymentDate;
                    $kredit->save();

                    Log::info("Kredit ID {$kredit->id} fully paid. Remaining payment: " . number_format($remainingPayment, 2));

                    // Lanjutkan ke kredit berikutnya
                    continue;
                }

                // Jika sisa pembayaran tidak cukup melunasi kredit
                if ($remainingPayment > 0) {
                    // Kurangi total hutang dengan pembayaran
                    $sisaHutang = $totalKreditDenganBunga - $remainingPayment;

                    // Update kredit saat ini
                    $kredit->status = true;
                    $kredit->debit_id = $lastDebit->id;
                    $kredit->keterangan .= " | Terbayar Sebagian | Dibayar: Rp " . number_format($remainingPayment, 2) .
                        " | Sisa Hutang: Rp " . number_format($sisaHutang, 2);
                    $kredit->updated_at = $paymentDate;
                    $kredit->save();

                    // Buat kredit baru untuk sisa hutang
                    $newKredit = Kredit::create([
                        'debit_id' => $this->id,
                        'petani_id' => $this->petani_id,
                        'tanggal' => $paymentDate,
                        'jumlah' => $sisaHutang,
                        'keterangan' => 'Sisa Hutang dari Kredit Sebelumnya'
                    ]);

                    Log::info("Partial payment for Kredit ID {$kredit->id}. New Kredit created with ID {$newKredit->id}");

                    // Habiskan sisa pembayaran
                    $remainingPayment = 0;
                    break;
                }
            }

            // Update debit
            $this->keterangan .= " | Proses Pembayaran Sebagian";
            $this->save();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error in dynamic payment processing: " . $e->getMessage());
            throw $e;
        }
    }

    // public function processPayment()
    // {
    //     DB::beginTransaction();
    //     try {
    //         // Assume that you have calculated totalHutangDenganBunga already
    //         $totalHutangDenganBunga = $this->calculateTotalHutangDenganBunga();
    //         $paymentDate = $this->tanggal ? Carbon::parse($this->tanggal) : Carbon::now();
    //         $kredits = $this->petani->kredits()->where('status', false)->get();

    //         // Mengambil data debits terakhir untuk petani yang bersangkutan
    //         $lastDebit = Debit::orderBy('created_at', 'desc')->first();

    //         // Check if there are outstanding credits
    //         if ($kredits->isNotEmpty()) {
    //             // Get the first outstanding credit (if you are certain there is only one)
    //             $kredit = $kredits->first();
    //             $creditDate = Carbon::parse($kredit->tanggal);
    //             $debtDurationMonths = $creditDate->diffInMonths($paymentDate);
    //             if ($this->jumlah >= $totalHutangDenganBunga) {
    //                 // Pay off the credit fully
    //                 foreach ($kredits as $kredit) {
    //                     $kredit->status = true;
    //                     $kredit->keterangan = $kredit->keterangan . ' | Terbayar Penuh | Debit: Rp ' . number_format($totalHutangDenganBunga, 2);
    //                     $kredit->save();
    //                 }
    //                 $this->keterangan .= " | Terbayar Penuh | Durasi: " . floor($debtDurationMonths) . " bulan";
    //             } else {
    //                 // Pay off partially
    //                 $sisaHutang = $totalHutangDenganBunga - $this->jumlah;
    //                 foreach ($kredits as $kredit) {
    //                     $kredit->status = true;
    //                     $kredit->keterangan = $kredit->keterangan . ' | Terbayar Sebagian | Debit: Rp ' . number_format($this->jumlah, 2) .
    //                         ' | Sisa Hutang: Rp ' . number_format($sisaHutang, 2);
    //                     $kredit->updated_at = $creditDate;
    //                     $kredit->debit_id = $lastDebit->id;
    //                     $kredit->save();
    //                 }
    //                 $this->keterangan .= ' | Terbayar Sebagian | Kredit: Rp ' . number_format($totalHutangDenganBunga, 2) .
    //                     ' | Sisa Hutang: Rp ' . number_format($sisaHutang, 2);
    //                 // Create a new Kredit entry for the remaining debt

    //                 Kredit::create([
    //                     'debit_id' => $lastDebit->id,
    //                     'petani_id' => $this->petani_id,
    //                     'tanggal' => $this->tanggal,
    //                     'jumlah' => $sisaHutang,
    //                     'keterangan' => 'Terbayar Sebagian | Rp ' . number_format($this->jumlah, 2) .
    //                         ' (Debit) - Rp ' . number_format($totalHutangDenganBunga, 2) .
    //                         ' (Kredit)',
    //                 ]);
    //             }
    //         } else {
    //             // Handle case where there are no outstanding credits
    //             Log::warning("No outstanding credits found for Petani ID: {$this->petani_id}");
    //         }
    //         $this->save();
    //         DB::commit();
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error("Error processing payment: " . $e->getMessage());
    //         throw $e;
    //     }
    // }


}
