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

class DaftarGilingController extends Controller
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

        $query = DaftarGiling::with('giling.petani');

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
        $daftarGilings = $query->paginate(100);

        // Pass data to the view, including the alamatList
        return view('laravel-examples.daftar-giling', compact('daftarGilings', 'search', 'sortOrder', 'alamatList'));
    }

    public function findPdf(Request $request)
    {
        $gilingId = $request->input('gilingId');

        // Cari di database untuk R2 URL
        $rekapan = DB::table('daftar_gilings')->where('id', $gilingId)->first();

        if ($rekapan && !empty($rekapan->s3_url)) {
            // Gunakan URL R2 jika tersedia
            return response()->json([
                'pdfPath' => $rekapan->s3_url
            ]);
        }

        // Jika tidak ditemukan URL
        return response()->json([
            'pdfPath' => null
        ], 404);
    }
    // ... (other methods remain the same)

    public function search(Request $request)
    {
        $search = $request->input('term');

        // Query untuk mendapatkan nama petani unik
        $daftarGilings = DaftarGiling::with('giling.petani')
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

        DB::beginTransaction();
        try {

            // Ambil petani_id dari giling
            $petaniId = $giling->petani_id;

            // Ambil semua Kredit dengan status 'true' (lunas) yang terkait dengan petani_id yang sama
            $lunasKredits = Kredit::where('status', true)
                ->where('petani_id', $petaniId)
                ->get();

            // Ambil semua Kredit dengan status 'false' (belum lunas) yang terkait dengan petani_id yang sama
            $belumLunasKredits = Kredit::where('status', false)
                ->where('petani_id', $petaniId)
                ->get();


            if ($belumLunasKredits->isNotEmpty()) {
                // Ambil pKredit_id dari Kredit terakhir dengan status 'true' (lunas)
                $lastPaidKredit = $lunasKredits->last();  // Mengambil data kredit terakhir dalam koleksi

                Log::info('Kredit dengan status lunas terakhir ditemukan dengan pKredit_id: ' . $lastPaidKredit->pKredit_id);

                // Update semua kredit dengan status 'false' (belum lunas) untuk menggunakan pKredit_id dari kredit terakhir yang lunas
                foreach ($belumLunasKredits as $kredit) {
                    $kredit->update([
                        'pKredit_id' => $lastPaidKredit->pKredit_id, // Update dengan pKredit_id dari kredit terakhir yang lunas
                    ]);

                    Log::info('Kredit ID ' . $kredit->id . ' telah diperbarui dengan pKredit ID: ' . $lastPaidKredit->pKredit_id);
                }
            }







            // Ambil semua ID PembayaranKredit berdasarkan Giling ID
            $pembayaranKreditIds = PembayaranKredit::where('giling_id', $giling->id)
                ->pluck('id')
                ->filter();

            if ($pembayaranKreditIds->isEmpty()) {
                Log::info('Tidak ada PembayaranKredit yang terkait dengan Giling ID: ' . $giling->id);
                DB::commit();
                return;
            }

            // Ambil semua Kredit yang terkait dengan PembayaranKredit tersebut
            $kredits = Kredit::whereIn('pKredit_id', $pembayaranKreditIds)->get();

            if ($kredits->isEmpty()) {
                Log::info('Tidak ada Kredit yang terkait dengan PembayaranKredit.');
                DB::commit();
                return;
            }

            // Pisahkan Kredit berdasarkan status
            $newKredits = $kredits->where('status', false);
            $updatedKredits = $kredits->where('status', true);

            // Hapus kredit baru
            foreach ($newKredits as $kredit) {
                $kredit->delete();
                Log::info('Kredit baru dihapus:', ['kredit_id' => $kredit->id]);
            }

            // Revert kredit yang diupdate
            foreach ($updatedKredits as $kredit) {
                Log::info('Mengembalikan Kredit ID: ' . $kredit->id);

                $originalKeterangan = $this->removePaymentInfo($kredit->keterangan);

                $kredit->update([
                    'status' => false,
                    'keterangan' => $originalKeterangan,
                ]);

                // Set updated_at sama dengan created_at
                $kredit->updated_at = $kredit->created_at;
                $kredit->save();

                Log::info('Kredit berhasil dikembalikan:', ['kredit_id' => $kredit->id]);
            }

            DB::commit();
            Log::info('Proses reverse kredit selesai untuk Giling ID: ' . $giling->id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Terjadi kesalahan saat reverse kredit:', ['error' => $e->getMessage()]);
        }
    }



    private function removePaymentInfo($keterangan)
    {
        $patterns = [
            '/\s*\|\s*Terbayar penuh:.*/',
            '/\s*\|\s*Terbayar sebagian:.*/',
            '/\s*\|\s*Terbayar.*/',
            '/\s*\|\s*Dana.*/',
            '/\s*\|\s*Pengambilan.*/',
            '/\s*\|\s*Gabah Masuk.*/',
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

    public function getPdfUrl($gilingId)
    {
        $giling = DB::table('daftar_gilings')->where('id', $gilingId)->first();

        if (!$giling || !$giling->s3_url) {
            return response()->json(['error' => 'PDF URL not found'], 404);
        }

        return response()->json(['pdf_url' => $giling->s3_url], 200);
    }
}
