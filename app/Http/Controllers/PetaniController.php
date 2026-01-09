<?php

namespace App\Http\Controllers;

use App\Models\Petani;
use App\Models\BukuStokBeras;
use App\Models\BukuStokKongaMenir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PetaniController extends Controller
{
    public function index(Request $request)
    {
        $query = Petani::query();

        // Daftar alamat yang akan digunakan
        $alamatList = [
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
        ];

        // Handle search functionality
        if ($request->has('search')) {
            $query->where('nama', 'like', '%' . $request->input('search') . '%');
        }

        // Handle sorting by ID
        if ($request->has('sort')) {
            $sortOrder = $request->input('sort') === 'asc' ? 'asc' : 'desc';
            $query->orderBy('id', $sortOrder);
        } else {
            $query->orderBy('id', 'desc'); // Default sort order
        }

        // Handle filtering by alamat
        if ($request->has('alamat')) {
            if ($request->input('alamat') === 'campur') {
                $query->whereNotIn('alamat', $alamatList);
            } elseif ($request->input('alamat') !== 'all') {
                $query->where('alamat', $request->input('alamat'));
            }
        }


        // Fetch paginated results
        $petanis = $query->paginate(100); // Adjust pagination as needed

        return view('laravel-examples.petani', compact('petanis', 'alamatList'));
    }

    public function create()
    {
        return view('laravel-examples.petani');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telepon' => 'nullable|string|max:20', // Menambahkan 'nullable' untuk mengizinkan nilai null
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Transformasi nama dan alamat untuk menjadikan huruf awal setiap kata kapital
        $validatedData = $validator->validated();
        $validatedData['nama'] = ucwords(strtolower($validatedData['nama']));
        $validatedData['alamat'] = ucwords(strtolower($validatedData['alamat']));

        Petani::create($validatedData);

        return redirect()->back()->with('success', 'Petani berhasil ditambahkan.');
    }


    public function show($id)
    {
        $petani = Petani::findOrFail($id);
        return view('laravel-examples.petani', compact('petani'));
    }

    public function edit($id)
    {
        $petani = Petani::findOrFail($id);
        return view('laravel-examples.petani', compact('petani'));
    }

    public function update(Request $request, $id)
    {
        $petani = Petani::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telepon' => 'nullable|string|max:20',  // Menambahkan 'nullable' untuk mengizinkan nilai null
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $petani->update($validator->validated());

        return redirect()->back()->with('success', 'Petani berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $petani = Petani::findOrFail($id);
        $petani->delete();

        return redirect()->back()->with('success', 'Petani berhasil dihapus.');
    }


    // PetaniController.php
    public function search(Request $request)
    {
        $query = $request->get('query');
        $petanis = Petani::where('nama', 'like', "%{$query}%")->limit(10)->get();

        return response()->json($petanis);
    }
    public function searchDebit(Request $request)
    {
        $term = $request->get('term');  // Get the search term

        // Search Petani based on the 'nama' field
        $petani = Petani::where('nama', 'like', '%' . $term . '%')->get();

        // Return the data as JSON
        return response()->json($petani);
    }

    public function searchPetaniDebit(Request $request)
    {
        $term = $request->input('term');



        $petanis = Petani::where('nama', 'LIKE', "%{$term}%")
            ->orWhere('alamat', 'LIKE', "%{$term}%")
            ->select('id', 'nama', 'alamat')
            ->limit(10)
            ->get();

        return response()->json($petanis);
    }

    public function stokTerakhir($id)
    {
        // Ambil beras belum lunas pertama
        $beras = BukuStokBeras::where('petani_id', $id)
            ->where('status', 0) // 0 = belum lunas
            ->orderBy('tanggal', 'asc')
            ->first();

        // Ambil data jemur konga+menir sesuai ID & belum lunas
        $kongaMenir = BukuStokKongaMenir::where('petani_id', $id)
            ->where('status', 0)
            ->orderBy('tanggal', 'asc')
            ->get();

        // Hitung total konga (jemur - jual tapi hanya yg belum lunas)
        $totalKonga = $kongaMenir->sum(function ($item) {
            return max(($item->pinjam_konga - $item->kembalikan_konga), 0);
        });

        // Hitung total menir (menir - menir_jual status belum lunas)
        $totalMenir = $kongaMenir->sum(function ($item) {
            return max(($item->menir - $item->menir_jual), 0);
        });

        // SUM karung_konga seluruh data (tidak tergantung status)
        $totalKarungKonga = BukuStokKongaMenir::where('petani_id', $id)
            ->sum('karung_konga');

        // SUM jual konga secara keseluruhan
        $totalKongaJual = BukuStokKongaMenir::where('petani_id', $id)
            ->sum('konga_jual');

        // SUM jual menir secara keseluruhan
        $totalMenirJual = BukuStokKongaMenir::where('petani_id', $id)
            ->sum('menir_jual');

        return response()->json([
            'beras' => $beras,
            'konga_menir' => [
                'total_konga' => $totalKongaJual,
                'total_menir' => $totalMenirJual,
                'total_karung_konga' => $totalKarungKonga,
                'total_konga_jual' => $totalKongaJual,
                'total_menir_jual' => $totalMenirJual,
            ],
        ]);
    }
}
