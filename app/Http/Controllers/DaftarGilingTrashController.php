<?php

namespace App\Http\Controllers;

use App\Models\DaftarGiling;
use App\Models\Kredit;
use App\Models\Petani;
use App\Models\PembayaranKredit;
use App\Models\Giling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB; // Tambahkan ini
use Illuminate\Support\Facades\Log; // Tambahkan ini

class DaftarGilingTrashController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve all petanis for use in the view
        $petanis = Petani::all();

        // Get unique alamat list for the filter dropdown
        $alamatList = $petanis->pluck('alamat')->unique()->filter()->values();

        $search = $request->input('search');
        $alamatFilter = $request->input('alamat');
        $sortOrder = $request->input('sort', 'desc');


        $query = DaftarGiling::onlyTrashed()->with('giling.petani');

        // Apply search filter
        if ($search) {
            $query->whereHas('giling.petani', function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%');
            });
        }

        // Handle filtering by alamat
        if ($request->has('alamat')) {
            if ($alamatFilter === 'campur') {
                $query->whereHas('giling.petani', function ($q) {
                    $q->whereNotIn('alamat', [
                        'Penebel',
                        'Palesari',
                        'Sangeh Sari',
                        'Gigit Sari',
                        'Wanaprasta',
                        'Sibang',
                        'Sausu',
                        'Bali Indah',
                        'Candra Buana',
                        'Taman Sari',
                        'Sukasada',
                        'Purwo Sari',
                        'Karyawan',
                    ]);
                });
            } elseif ($alamatFilter !== 'all') {
                $query->whereHas('giling.petani', function ($q) use ($alamatFilter) {
                    $q->where('alamat', $alamatFilter);
                });
            }
        }

        // Apply sorting
        $query->orderBy('created_at', $sortOrder);

        // Get the data, even if no results, don't cause error
        $daftarGilings = $query->paginate(20);

        // Pass data to the view, including the alamatList
        return view('laravel-examples.daftar-gilingTrash', compact('daftarGilings', 'search', 'sortOrder', 'alamatList'));
    }


    // ... (other methods remain the same)

    public function search(Request $request)
    {
        $search = $request->input('term');

        // Query untuk mendapatkan nama petani unik
        $daftarGilings = DaftarGiling::with('daftar-gilingTrash.petani')
            ->whereHas('giling.petani', function ($query) use ($search) {
                $query->where('nama', 'like', '%' . $search . '%');
            })
            ->get()
            ->unique('giling.petani.nama')  // Mengambil nama petani yang unik berdasarkan nama

            ->values(); // Untuk me-reset index agar response JSON rapi

        return response()->json($daftarGilings);
    }


    public function show($id)
    {
        $daftarGiling = DaftarGiling::with('giling')->findOrFail($id);
        return view('daftar-giling.show', compact('daftarGiling'));
    }

    public function create()
    {
        return view('daftar-giling.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'giling_id' => 'required|exists:gilings,id',
            'giling_kotor' => 'required|numeric',
            'biaya_giling' => 'required|numeric',
            'ongkos_giling' => 'required|numeric',
            'beras_bersih' => 'required|numeric',
            'total_hutang' => 'required|numeric',
            'total_pengambilan' => 'required|numeric',
            'pulang' => 'required|numeric',
            'pinjam' => 'required|numeric',
            'dana_jual_beras' => 'required|numeric',
            'dana_penerima' => 'required|numeric',
            'biaya_buruh_giling' => 'required|numeric',
            'total_biaya_buruh_giling' => 'required|numeric',
            'jemur' => 'required|numeric',
            'biaya_buruh_jemur' => 'required|numeric',
            'total_biaya_buruh_jemur' => 'required|numeric',
            'jumlah_konga' => 'required|numeric',
            'harga_konga' => 'required|numeric',
            'dana_jual_konga' => 'required|numeric',
            'jumlah_menir' => 'required|numeric',
            'harga_menir' => 'required|numeric',
            'dana_jual_menir' => 'required|numeric',
            'bunga' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $daftarGiling = DaftarGiling::create($validator->validated());



        // Redirect ke halaman yang sama dengan meneruskan ID baru
        return redirect()->route('giling.create')
            ->with('success', 'Daftar Giling berhasil dibuat.')
            ->with('newGilingId', $daftarGiling->id);


        return redirect()->route('daftar-giling.show', $daftarGiling->id)->with('success', 'Daftar Giling berhasil dibuat.');
    }

    public function edit($id)
    {
        $daftarGiling = DaftarGiling::findOrFail($id);
        return view('daftar-giling.edit', compact('daftarGiling'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'giling_id' => 'required|exists:gilings,id',
            'giling_kotor' => 'required|numeric',
            'biaya_giling' => 'required|numeric',
            'ongkos_giling' => 'required|numeric',
            'beras_bersih' => 'required|numeric',
            'total_hutang' => 'required|numeric',
            'total_pengambilan' => 'required|numeric',
            'pulang' => 'required|numeric',
            'pinjam' => 'required|numeric',
            'dana_jual_beras' => 'required|numeric',
            'dana_penerima' => 'required|numeric',
            'biaya_buruh_giling' => 'required|numeric',
            'total_biaya_buruh_giling' => 'required|numeric',
            'jemur' => 'required|numeric',
            'biaya_buruh_jemur' => 'required|numeric',
            'total_biaya_buruh_jemur' => 'required|numeric',
            'jumlah_konga' => 'required|numeric',
            'harga_konga' => 'required|numeric',
            'dana_jual_konga' => 'required|numeric',
            'jumlah_menir' => 'required|numeric',
            'harga_menir' => 'required|numeric',
            'dana_jual_menir' => 'required|numeric',
            'bunga' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $daftarGiling = DaftarGiling::findOrFail($id);
        $daftarGiling->update($validator->validated());

        $validatedData = $validator->validated();

        // Buat Giling dan data terkait
        $giling = Giling::create($validatedData);

        // Proses kredits baru dan perhitungan kredit setelah giling
        $this->processKredits($giling, $validatedData);


        return redirect()->route('daftar-giling.show', $daftarGiling->id)->with('success', 'Daftar Giling berhasil diperbarui.');
    }

    public function restore($id)
    {
        try {
            // Cari data yang soft deleted
            $kredit = DaftarGiling::onlyTrashed()->findOrFail($id);

            // Restore data
            $kredit->restore();

            // Redirect ke fungsi index untuk menampilkan halaman dengan data terbaru
            return redirect()->route('daftar-giling-ryclebin.index')->with('success', 'Kredit berhasil dikembalikan');
        } catch (\Exception $e) {
            Log::error('Error restoring kredit: ' . $e->getMessage());

            // Redirect ke fungsi index meskipun terjadi error, agar halaman tetap tampil
            return redirect()->route('daftar-giling-ryclebin.index')->with('error', 'Terjadi kesalahan saat mengembalikan kredit');
        }
    }

    public function destroy($id)
    {
        return DB::transaction(function () use ($id) {
            try {
                // Dapatkan data Giling dan DaftarGiling yang ingin dihapus
                $daftarGiling = DaftarGiling::with('giling.pembayaranKredits.kredits')->findOrFail($id);
                $giling = $daftarGiling->giling;

                if ($giling) {
                    // Reverse perubahan pada kredit hanya jika giling ditemukan
                    $this->reverseKreditChanges($giling);

                    // Soft delete pada Giling dan DaftarGiling
                    $giling->delete();
                    $daftarGiling->delete();

                    return redirect()->route('daftar-giling.index')->with('success', 'Daftar Giling berhasil dihapus (soft delete) dan status kredit dikembalikan.');
                }

                return redirect()->route('daftar-giling.index')->with('error', 'Data Giling tidak ditemukan.');
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                Log::error('Giling atau Daftar Giling tidak ditemukan: ' . $e->getMessage());
                return redirect()->route('daftar-giling.index')->with('error', 'Data tidak ditemukan.');
            } catch (\Exception $e) {
                Log::error('Error soft deleting Daftar Giling: ' . $e->getMessage());
                return redirect()->route('daftar-giling.index')->with('error', 'Terjadi kesalahan saat menghapus data. Silakan coba lagi.');
            }
        });
    }

    private function reverseKreditChanges(Giling $giling)
    {
        Log::info('Memulai proses reverse kredit untuk Giling ID: ' . $giling->id);

        // Hapus kredit baru yang dihasilkan dari giling
        $newKredits = Kredit::where('petani_id', $giling->petani_id)
            ->where('created_at', '>=', $giling->updated_at)
            ->get();

        foreach ($newKredits as $kredit) {
            $kredit->forceDelete();
            Log::info('Kredit baru dihapus:', ['kredit_id' => $kredit->id]);
        }

        // Ambil semua kredit yang terkait dengan petani ini dan diupdate saat atau setelah giling
        $updatedKredits = Kredit::where('petani_id', $giling->petani_id)
            ->where('updated_at', '>=', $giling->updated_at)
            ->where('status', true)
            ->get();

        Log::info('Jumlah kredit yang akan diupdate: ' . $updatedKredits->count());

        foreach ($updatedKredits as $kredit) {
            Log::info('Updating Kredit ID: ' . $kredit->id . ' dari status true ke false');

            $originalKeterangan = $this->removePaymentInfo($kredit->keterangan);

            $success = $kredit->update([
                'status' => false,
                'keterangan' => $originalKeterangan
            ]);

            if ($success) {
                Log::info('Kredit berhasil diupdate:', $kredit->toArray());
            } else {
                Log::error('Gagal mengupdate Kredit ID: ' . $kredit->id);
            }
        }

        Log::info('Proses reverse kredit selesai untuk Giling ID: ' . $giling->id);
    }

    private function removePaymentInfo($keterangan)
    {
        $patterns = [
            '/\s*\|\s*Terbayar penuh:.*/',
            '/\s*\|\s*Terbayar sebagian:.*/',
            '/\s*\|\s*Durasi:.*/',
            '/\s*\|\s*Menjadi Hutang Baru:.*/'
        ];

        return preg_replace($patterns, '', $keterangan);
    }




    private function deleteRelatedRecords(Giling $giling)
    {
        // Delete related PembayaranKredit records
        PembayaranKredit::where('giling_id', $giling->id)->delete();

        // Delete related Pengambilan records
        $giling->pengambilans()->delete();

        // Delete the Giling record
        $giling->delete();
    }
}
