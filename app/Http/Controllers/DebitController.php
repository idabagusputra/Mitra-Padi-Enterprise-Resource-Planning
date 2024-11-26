<?php

namespace App\Http\Controllers;

use App\Models\Debit;
use App\Models\Kredit;
use App\Models\Petani;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;



class DebitController extends Controller
{
    public function index(Request $request)
    {
        $query = Debit::with('petani');

        $search = $request->input('search');

        // Apply filters
        if ($search) {
            $query->whereHas('petani', function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%');
            });
        }

        $sort = $request->input('sort', 'desc');

        $query->orderBy('tanggal', $sort) // Urutkan berdasarkan tanggal
            ->orderBy('id', $sort); // Urutkan berdasarkan id untuk menangani data dengan tanggal yang sama


        $debits = $query->paginate(20);

        $petanisWithOutstandingKredits = Petani::whereHas('kredits', function ($query) {
            $query->where('status', false);
        })->with(['kredits' => function ($query) {
            $query->where('status', false);
        }])->get()->map(function ($petani) {
            $petani->total_hutang = $petani->kredits->sum('jumlah');
            return $petani;
        });

        return view('laravel-examples/debit', compact('debits', 'petanisWithOutstandingKredits'));
    }

    public function searchPetani(Request $request)
    {
        $term = $request->input('term');
        $petanis = Petani::where('nama', 'LIKE', "%{$term}%")
            ->orWhere('alamat', 'LIKE', "%{$term}%")
            ->get(['id', 'nama', 'alamat']);

        return response()->json($petanis);
    }

    public function search(Request $request)
    {
        $term = $request->query('term');

        $petanis = Petani::where('nama', 'LIKE', "%{$term}%")
            ->orWhere('alamat', 'LIKE', "%{$term}%")
            ->select('id', 'nama', 'alamat')
            ->get();

        return response()->json($petanis);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'petani_id' => 'required|exists:petanis,id',
            'tanggal' => 'required|date',
            'jumlah' => 'required|numeric|min:0',
            'bunga' => 'required|numeric|min:0|max:100',
            'keterangan' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $debit = Debit::create($validatedData);
            $debit->processPayment();

            DB::commit();
            return redirect()->back()->with('success', 'Debit entry created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error creating debit entry: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        return DB::transaction(function () use ($id) {
            try {
                // Dapatkan data Debit yang ingin dihapus
                $debit = Debit::with('petani.kredits')->findOrFail($id);

                // Reverse perubahan pada kredit
                $this->reversePaymentChanges($debit);

                // Soft delete pada Debit
                $debit->delete();

                return redirect()->route('debit.index')
                    ->with('success', 'Debit berhasil dihapus (soft delete) dan status kredit dikembalikan.');
            } catch (\Exception $e) {

                throw $e;
            }
        });
    }

    private function reversePaymentChanges(Debit $debit)
    {
        Log::info('Memulai proses reverse pembayaran untuk Debit ID: ' . $debit->id);

        // Hapus kredit baru yang dibuat setelah pembayaran debit
        $newKredits = Kredit::where('petani_id', $debit->petani_id)
            ->where('created_at', '>=', $debit->created_at)
            ->get();

        foreach ($newKredits as $kredit) {
            Log::info('Menghapus Kredit baru:', ['kredit_id' => $kredit->id]);
            $kredit->forceDelete();
        }

        // Ambil semua kredit yang diupdate saat pembayaran
        $updatedKredits = Kredit::where('petani_id', $debit->petani_id)
            ->where('updated_at', '>=', $debit->created_at)
            ->where('status', true)
            ->get();

        Log::info('Jumlah kredit yang akan direset: ' . $updatedKredits->count());

        foreach ($updatedKredits as $kredit) {
            Log::info('Mereset Kredit ID: ' . $kredit->id);

            // Hapus informasi pembayaran dari keterangan
            $originalKeterangan = $this->removePaymentInfo($kredit->keterangan);

            $success = $kredit->update([
                'status' => false,
                'keterangan' => $originalKeterangan
            ]);

            if ($success) {
                Log::info('Kredit berhasil direset:', $kredit->toArray());
            } else {
                Log::error('Gagal mereset Kredit ID: ' . $kredit->id);
            }
        }

        Log::info('Proses reverse pembayaran selesai untuk Debit ID: ' . $debit->id);
    }

    private function removePaymentInfo($keterangan)
    {
        // Hapus semua informasi pembayaran yang ditambahkan saat proses pembayaran
        $patterns = [
            '/\s*\|\s*Terbayar Penuh.*/',
            '/\s*\|\s*Terbayar Sebagian.*/',
            '/\s*\|\s*Debit:.*/',
            '/\s*\|\s*Sisa Hutang:.*/',
            '/\s*\|\s*Durasi:.*/'
        ];

        $cleanKeterangan = $keterangan;
        foreach ($patterns as $pattern) {
            $cleanKeterangan = preg_replace($pattern, '', $cleanKeterangan);
        }

        return trim($cleanKeterangan);
    }


    public function getTotalHutang($petaniId)
    {
        try {
            $petani = Petani::findOrFail($petaniId);
            $totalHutang = $petani->kredits()->where('status', false)->sum('jumlah');
            return response()->json(['total_hutang' => $totalHutang]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching total hutang: ' . $e->getMessage()], 500);
        }
    }
}
