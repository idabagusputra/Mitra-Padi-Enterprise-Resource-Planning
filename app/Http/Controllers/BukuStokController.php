<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Petani;
use App\Models\BukuStokBeras;
use App\Models\BukuStokKongaMenir;
use App\Models\BukuStokPinjamanBeras;
use App\Models\BukuStokPinjamanKonga;
use App\Models\PenjualanBeras;
use App\Models\PenjualanKongaMenir;
use App\Models\StokGlobal;

class BukuStokController extends Controller
{
    /* =====================================================
     |  HELPER STOK GLOBAL (SINGLE ROW)
     ===================================================== */
    private function stokGlobal()
    {
        return StokGlobal::firstOrCreate(
            ['id' => 1],
            ['stok_beras' => 0, 'stok_konga' => 0, 'stok_menir' => 0]
        );
    }

    /* =====================================================
     |  INDEX
     ===================================================== */
    public function index()
    {
        return view('laravel-examples/buku-gilingan', [
            // Buku Stok Beras - urut tanggal DESC, id DESC
            'bukuStokBeras' => BukuStokBeras::with('petani')
                ->orderByDesc('tanggal')
                ->orderByDesc('id')
                ->get(),

            // Pinjaman Beras - urut status ASC (belum lunas dulu), tanggal DESC, id DESC
            'pinjamanBeras' => BukuStokPinjamanBeras::with('petani')
                ->orderBy('status', 'asc')
                ->orderByDesc('tanggal')
                ->orderByDesc('id')
                ->get(),

            // Pinjaman Konga - urut status ASC (belum lunas dulu), tanggal DESC, id DESC
            'pinjamanKonga' => BukuStokPinjamanKonga::with('petani')
                ->orderBy('status', 'asc')
                ->orderByDesc('tanggal')
                ->orderByDesc('id')
                ->get(),

            // Buku Stok Konga & Menir - urut tanggal DESC, id DESC
            'bukuStokKongaMenir' => BukuStokKongaMenir::with('petani')
                ->orderByDesc('tanggal')
                ->orderByDesc('id')
                ->get(),

            // Penjualan Beras - urut tanggal DESC, id DESC
            'penjualanBeras' => PenjualanBeras::orderByDesc('tanggal')
                ->orderByDesc('id')
                ->get(),

            // Penjualan Konga & Menir - urut tanggal DESC, id DESC
            'penjualanKongaMenir' => PenjualanKongaMenir::orderByDesc('tanggal')
                ->orderByDesc('id')
                ->get(),

            // Stok Global
            'stokGlobal' => $this->stokGlobal(),
        ]);
    }

    /* =====================================================
     |  PINJAMAN BERAS
     ===================================================== */
    public function storePinjamanBeras(Request $request)
    {
        $request->validate([
            'rows' => 'required|array|min:1',
            'rows.*.petani_id' => 'required|exists:petanis,id',
            'rows.*.tanggal'   => 'required|date',
            'rows.*.jumlah'    => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();

        try {
            foreach ($request->rows as $row) {

                $petani = Petani::find($row['petani_id']);
                $jumlah = (float) $row['jumlah'];

                BukuStokPinjamanBeras::create([
                    'petani_id'   => $petani->id,
                    'nama_petani' => $petani->nama,
                    'tanggal'     => $row['tanggal'],
                    'jumlah'      => $jumlah,
                    'status'      => 0,
                ]);

                StokGlobal::where('id', 1)
                    ->lockForUpdate()
                    ->update([
                        'stok_beras' => DB::raw("stok_beras - {$jumlah}")
                    ]);
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }



    public function updatePinjamanBeras(Request $request, $id)
    {
        $pinjaman = BukuStokPinjamanBeras::findOrFail($id);

        if ($pinjaman->status == 1) {
            return response()->json(['message' => 'Pinjaman sudah dipakai'], 422);
        }

        $request->validate([
            'tanggal' => 'required|date',
            'jumlah'  => 'required|numeric|min:0.01',
        ]);

        $pinjaman->update($request->only('tanggal', 'jumlah'));

        return response()->json(['success' => true]);
    }

    public function destroyPinjamanBeras($id)
    {
        DB::beginTransaction();

        try {
            $pinjaman = BukuStokPinjamanBeras::findOrFail($id);

            if ($pinjaman->status == 1) {
                return redirect()->back()
                    ->with('error', 'Pinjaman sudah dipakai, tidak bisa dihapus');
            }

            $jumlah = (float) $pinjaman->jumlah;

            // 🔥 KEMBALIKAN STOK GLOBAL BERAS
            StokGlobal::where('id', 1)
                ->lockForUpdate()
                ->update([
                    'stok_beras' => DB::raw("stok_beras + {$jumlah}")
                ]);

            $pinjaman->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Pinjaman dihapus & stok dikembalikan');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    /* =====================================================
     |  BUKU STOK BERAS
     ===================================================== */
    public function storeBukuBeras(Request $request)
    {
        $request->validate([
            'rows' => 'required|array|min:1',
            'rows.*.petani_id'    => 'required|exists:petanis,id',
            'rows.*.tanggal'      => 'required|date',
            'rows.*.giling_kotor' => 'required|numeric|min:0',
            'rows.*.beras_pulang' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            foreach ($request->rows as $row) {

                $petani = Petani::find($row['petani_id']);

                $pinjamanQuery = BukuStokPinjamanBeras::where('petani_id', $petani->id)
                    ->where('status', 0);

                $pinjaman = (float) $pinjamanQuery->sum('jumlah');

                $jemur = (float) $row['jemur'];
                $kotor = (float) $row['giling_kotor'];
                $pulang = (float) $row['beras_pulang'];

                $ongkos = round($kotor * 0.09, 2);
                $bersih = max($kotor - $ongkos - $pinjaman, 0);
                $jual   = max($bersih - $pulang, 0);
                $jualK  = round($jual + $ongkos + $pinjaman, 2);

                $buku = BukuStokBeras::create([
                    'petani_id'      => $petani->id,
                    'nama_petani'    => $petani->nama,
                    'tanggal'        => $row['tanggal'],
                    'jemur'          => $jemur,
                    'giling_kotor'   => $kotor,
                    'ongkos'         => $ongkos,
                    'pinjaman_beras' => $pinjaman,
                    'beras_bersih'   => $bersih,
                    'beras_pulang'   => $pulang,
                    'jual'           => $jual,
                    'jual_kotor'     => $jualK,
                    'status'      => $row['status'],
                ]);

                $pinjamanQuery->update([
                    'status' => 1,
                    'buku_stok_beras_id' => $buku->id,
                ]);

                $this->stokGlobal()->increment('stok_beras', $jualK);
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function updateBukuBeras(Request $request, $id)
    {
        DB::transaction(function () use ($request, $id) {

            $buku = BukuStokBeras::findOrFail($id);

            // rollback stok lama
            $this->stokGlobal()->decrement('stok_beras', $buku->jual_kotor);

            // rollback pinjaman lama
            BukuStokPinjamanBeras::where('buku_stok_beras_id', $buku->id)
                ->update(['status' => 0, 'buku_stok_beras_id' => null]);

            $pinjamanQuery = BukuStokPinjamanBeras::where('petani_id', $buku->petani_id)
                ->where('status', 0);

            $pinjaman = (float) $pinjamanQuery->sum('jumlah');

            $ongkos = round($request->giling_kotor * 0.09, 2);
            $bersih = max($request->giling_kotor - $ongkos - $pinjaman, 0);
            $jual   = max($bersih - $request->beras_pulang, 0);
            $jualK  = round($jual + $ongkos +  $pinjaman, 2);

            $buku->update([
                'tanggal'        => $request->tanggal,
                'giling_kotor'   => $request->giling_kotor,
                'ongkos'         => $ongkos,
                'pinjaman_beras' => $pinjaman,
                'beras_bersih'   => $bersih,
                'beras_pulang'   => $request->beras_pulang,
                'jual'           => $jual,
                'jual_kotor'     => $jualK,
            ]);

            // 🔥 TAMBAHAN INI
            if ($pinjaman > 0) {
                $this->stokGlobal()
                    ->lockForUpdate()
                    ->increment('stok_beras', $pinjaman);
            }

            $pinjamanQuery->update([
                'status' => 1,
                'buku_stok_beras_id' => $buku->id,
            ]);

            // stok global +
            $this->stokGlobal()->increment('stok_beras', $jualK);
        });

        return response()->json(['success' => true]);
    }

    public function destroyBukuBeras($id)
    {
        DB::transaction(function () use ($id) {

            $buku = BukuStokBeras::findOrFail($id);

            // rollback stok
            $this->stokGlobal()->decrement('stok_beras', $buku->jual_kotor);

            // rollback pinjaman
            BukuStokPinjamanBeras::where('buku_stok_beras_id', $buku->id)
                ->update(['status' => 0, 'buku_stok_beras_id' => null]);

            $buku->delete();
        });

        return redirect()->back()->with('success', 'Buku stok beras dihapus');
    }

    /* =====================================================
     |  PENJUALAN BERAS
     ===================================================== */
    public function storePenjualanBeras(Request $request)
    {
        $request->validate([
            'rows' => 'required|array|min:1',
            'rows.*.keterangan'   => 'required|string|max:255',
            'rows.*.tanggal'      => 'required|date',
            'rows.*.jumlah_beras' => 'required|numeric|min:0',
            'rows.*.harga'        => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            foreach ($request->rows as $row) {

                $jual = PenjualanBeras::create([
                    'keterangan'   => $row['keterangan'],
                    'tanggal'      => $row['tanggal'],
                    'jumlah_beras' => (float) $row['jumlah_beras'],
                    'harga'        => isset($row['harga']) ? (float)$row['harga'] : 0,
                ]);

                $this->stokGlobal()->decrement('stok_beras', $jual->jumlah_beras);
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function destroyPenjualanBeras($id)
    {
        DB::transaction(function () use ($id) {

            $jual = PenjualanBeras::findOrFail($id);

            $this->stokGlobal()->increment('stok_beras', $jual->jumlah_beras);

            $jual->delete();
        });

        return redirect()->back()->with('success', 'Penjualan beras dihapus');
    }

    /* =====================================================
 |  PINJAMAN KONGA
 ===================================================== */
    public function storePinjamanKonga(Request $request)
    {
        $request->validate([
            'rows' => 'required|array|min:1',
            'rows.*.petani_id' => 'required|exists:petanis,id',
            'rows.*.tanggal'   => 'required|date',
            'rows.*.jumlah'    => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();

        try {
            foreach ($request->rows as $row) {

                $petani = Petani::find($row['petani_id']);
                $jumlah = (float) $row['jumlah'];

                BukuStokPinjamanKonga::create([
                    'petani_id'   => $petani->id,
                    'nama_petani' => $petani->nama,
                    'tanggal'     => $row['tanggal'],
                    'jumlah'      => $jumlah,
                    'status'      => 0,
                ]);

                // Kurangi stok konga saat dipinjam
                StokGlobal::where('id', 1)
                    ->lockForUpdate()
                    ->update([
                        'stok_konga' => DB::raw("stok_konga - {$jumlah}")
                    ]);
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroyPinjamanKonga($id)
    {
        DB::beginTransaction();

        try {
            $pinjaman = BukuStokPinjamanKonga::findOrFail($id);

            if ($pinjaman->status == 1) {
                return redirect()->back()
                    ->with('error', 'Pinjaman sudah dipakai, tidak bisa dihapus');
            }

            $jumlah = (float) $pinjaman->jumlah;

            // Kembalikan stok konga
            StokGlobal::where('id', 1)
                ->lockForUpdate()
                ->update([
                    'stok_konga' => DB::raw("stok_konga + {$jumlah}")
                ]);

            $pinjaman->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Pinjaman dihapus & stok dikembalikan');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /* =====================================================
 |  BUKU STOK KONGA MENIR
 ===================================================== */
    public function storeBukuKongaMenir(Request $request)
    {
        $request->validate([
            'rows' => 'required|array|min:1',
            'rows.*.petani_id'        => 'required|exists:petanis,id',
            'rows.*.tanggal'          => 'required|date',
            'rows.*.konga_giling'     => 'nullable|numeric|min:0',
            'rows.*.konga_jual'       => 'nullable|numeric|min:0',
            'rows.*.kembalikan_konga' => 'nullable|numeric|min:0',
            'rows.*.menir'            => 'nullable|numeric|min:0',
            'rows.*.menir_jual'       => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $stokGlobal = $this->stokGlobal();

            foreach ($request->rows as $row) {

                $petani = Petani::find($row['petani_id']);

                // Hitung pinjaman konga untuk petani ini
                $pinjamanQuery = BukuStokPinjamanKonga::where('petani_id', $petani->id)
                    ->where('status', 0);

                $pinjamanKonga = (float) $pinjamanQuery->sum('jumlah');

                $kongaGiling = (float) ($row['konga_giling'] ?? 0);
                $karungKonga  = (float) ($row['karung_konga'] ?? 0);
                $kongaJual = (float) ($row['konga_jual'] ?? 0);
                $kembalikanKonga = (float) ($row['kembalikan_konga'] ?? 0);
                $menir = (float) ($row['menir'] ?? 0);
                $menirJual = (float) ($row['menir_jual'] ?? 0);

                $buku = BukuStokKongaMenir::create([
                    'petani_id'         => $petani->id,
                    'nama_petani'       => $petani->nama,
                    'tanggal'           => $row['tanggal'],
                    'konga_giling'      => $kongaGiling,
                    'karung_konga'      => $karungKonga,
                    'konga_jual'        => $kongaJual,
                    'pinjam_konga'      => $pinjamanKonga,
                    'kembalikan_konga'  => $kembalikanKonga,
                    'menir'             => $menir,
                    'menir_jual'        => $menirJual,
                    'global_menir'      => $stokGlobal->stok_menir,
                    'status'            => 0, // ⬅️ Default pertama kali masuk
                ]);


                // Update stok global: tambah konga_jual dan menir_jual
                $stokGlobal->increment('stok_konga', $kongaJual);
                $stokGlobal->increment('stok_menir', $menirJual);

                // Jika ada pengembalian konga, update status pinjaman
                if ($pinjamanKonga > 0) {
                    $pinjamanQuery->update([
                        'status' => 1,
                        'buku_stok_konga_menir_id' => $buku->id,
                    ]);

                    // Kembalikan stok konga yang dipinjam
                    $stokGlobal->increment('stok_konga', $pinjamanKonga);
                }
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroyBukuKongaMenir($id)
    {
        DB::beginTransaction();

        try {
            $buku = BukuStokKongaMenir::findOrFail($id);
            $stokGlobal = $this->stokGlobal();

            // Kurangi stok global konga dan menir
            $stokGlobal->decrement('stok_konga', $buku->konga_jual);
            $stokGlobal->decrement('stok_menir', $buku->menir_jual);

            // Jika ada pinjaman konga yang sudah dikembalikan, rollback
            if ($buku->pinjam_konga > 0) {
                BukuStokPinjamanKonga::where('buku_stok_konga_menir_id', $buku->id)
                    ->update([
                        'status' => 0,
                        'buku_stok_konga_menir_id' => null
                    ]);

                // Kurangi kembali stok konga yang dikembalikan
                $stokGlobal->decrement('stok_konga', $buku->pinjam_konga);
            }

            $buku->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Buku stok konga menir dihapus');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    /* =====================================================
 |  PENJUALAN KONGA MENIR
 ===================================================== */
    public function storePenjualanKongaMenir(Request $request)
    {
        $request->validate([
            'rows' => 'required|array|min:1',
            'rows.*.keterangan'   => 'required|string|max:255',
            'rows.*.tanggal'      => 'required|date',
            'rows.*.jumlah_konga' => 'nullable|numeric|min:0',
            'rows.*.jumlah_menir' => 'nullable|numeric|min:0',
            'rows.*.harga'        => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $stokGlobal = $this->stokGlobal();

            foreach ($request->rows as $row) {

                $jumlahKonga = (float) ($row['jumlah_konga'] ?? 0);
                $jumlahMenir = (float) ($row['jumlah_menir'] ?? 0);

                $jual = PenjualanKongaMenir::create([
                    'keterangan'   => $row['keterangan'],
                    'tanggal'      => $row['tanggal'],
                    'jumlah_konga' => $jumlahKonga,
                    'jumlah_menir' => $jumlahMenir,
                    'harga'        => isset($row['harga']) ? (float)$row['harga'] : 0,
                ]);

                // Kurangi stok global
                $stokGlobal->decrement('stok_konga', $jumlahKonga);
                $stokGlobal->decrement('stok_menir', $jumlahMenir);
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroyPenjualanKongaMenir($id)
    {
        DB::beginTransaction();

        try {
            $jual = PenjualanKongaMenir::findOrFail($id);
            $stokGlobal = $this->stokGlobal();

            // Kembalikan stok
            $stokGlobal->increment('stok_konga', $jual->jumlah_konga);
            $stokGlobal->increment('stok_menir', $jual->jumlah_menir);

            $jual->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Penjualan konga menir dihapus');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }



    /**
     * Update Stok Global Manual
     */
    public function updateStokGlobal(Request $request)
    {
        $request->validate([
            'type' => 'required|in:beras,konga,menir',
            'value' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $stokGlobal = $this->stokGlobal();

            // Determine field name
            $field = 'stok_' . $request->type;

            // Update stok
            $stokGlobal->update([
                $field => $request->value
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Stok berhasil diperbarui'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }



    public function searchPetaniStok(Request $request)
    {
        $term = $request->get('term', '');

        $petanis = Petani::where('nama', 'LIKE', "%{$term}%")
            ->orWhere('alamat', 'LIKE', "%{$term}%")
            ->limit(10)
            ->get();

        // Ambil sum semua pinjaman yang belum lunas (status = 0)
        $result = $petanis->map(function ($petani) {
            // Sum semua pinjaman beras yang belum lunas
            $pinjamanBeras = BukuStokPinjamanBeras::where('petani_id', $petani->id)
                ->where('status', 0)
                ->sum('jumlah');

            // Sum semua pinjaman konga yang belum lunas
            $pinjamanKonga = BukuStokPinjamanKonga::where('petani_id', $petani->id)
                ->where('status', 0)
                ->sum('jumlah');

            return [
                'id' => $petani->id,
                'nama' => $petani->nama,
                'alamat' => $petani->alamat,
                'pinjaman_beras' => (float) $pinjamanBeras,
                'pinjaman_konga' => (float) $pinjamanKonga,
            ];
        });


        return response()->json($result);
    }
}
