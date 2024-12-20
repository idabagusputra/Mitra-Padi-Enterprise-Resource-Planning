<?php

namespace App\Http\Controllers;


use App\Models\KreditDirektur;
use App\Models\Petani;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\KreditReportController;

class KreditDirekturController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $statusFilter = $request->input('status');
        $alamatFilter = $request->input('alamat');
        $sortOrder = $request->input('sort', 'desc');

        $query = KreditDirektur::all();

        // // Apply filters
        // if ($search) {
        //     $query->whereHas('petani', function ($q) use ($search) {
        //         $q->where('nama', 'like', '%' . $search . '%');
        //     });
        // }

        // // Handle filtering by alamat
        // if ($request->has('alamat')) {
        //     if ($alamatFilter === 'campur') {
        //         $query->whereHas('petani', function ($q) {
        //             $q->whereNotIn('alamat', [
        //                 'Penebel',
        //                 'Palesari',
        //                 'Sangeh Sari',
        //                 'Gigit Sari',
        //                 'Wanaprasta',
        //                 'Sibang',
        //                 'Sausu',
        //                 'Bali Indah',
        //                 'Candra Buana',
        //                 'Taman Sari',
        //                 'Sukasada',
        //                 'Purwo Sari',
        //                 'Karyawan',
        //             ]);
        //         });
        //     } elseif ($alamatFilter !== 'all') {
        //         $query->whereHas('petani', function ($q) use ($alamatFilter) {
        //             $q->where('alamat', $alamatFilter);
        //         });
        //     }
        // }

        // if ($statusFilter !== null) {
        //     $query->where('status', $statusFilter);
        // }

        // Get all matching kredits without pagination
        $allKredits = $query;

        // Calculate additional values and prepare data
        $now = Carbon::now();
        $calculatedKredits = $allKredits->map(function ($kredit) use ($now) {
            $kreditDate = Carbon::parse($kredit->tanggal);

            // Cek apakah tanggal created_at dan updated_at sama (tanpa waktu)
            // Pastikan $kredit->status dan $kreditDate adalah objek yang valid
            if ($kredit->status === true) {
                // Jika statusnya true, hitung selisih bulan menggunakan now
                $now = Carbon::now(); // Dapatkan waktu sekarang
                $diffInMonthsUpdate = $kreditDate->diffInMonths($kredit->updated_at); // Menghitung selisih bulan
                // Lakukan sesuatu dengan $diffInMonthsUpdate jika diperlukan
            } else {
                // Hitung selisih bulan menggunakan updated_at
                $diffInMonthsUpdate = $kreditDate->diffInMonths($now);

                // Jika diffInMonthsUpdate bernilai negatif, set nilainya menjadi 0
                if ($diffInMonthsUpdate < 0) {
                    $diffInMonthsUpdate = 0;
                }
            }

            // Ensure the difference is floored
            $selisihBulan = floor($diffInMonthsUpdate);

            // Calculate bunga menggunakan selisih bulan
            $bunga = $kredit->jumlah * 0.02 * $selisihBulan;

            // Calculate hutang plus bunga
            $hutangPlusBunga = $kredit->jumlah + $bunga;



            $diffInMonthsUpdate = $kreditDate->diffInMonths($kredit->update_at);

            // Cek apakah tanggal created_at dan updated_at sama
            if ($kredit->created_at->eq($kredit->updated_at)) {
                $diffInMonthsUpdate = 0;
            }

            // Pastikan perbedaan bulan menjadi negatif dan dibulatkan ke bawah
            $selisihBulanUpdate = floor($diffInMonthsUpdate);

            // Hitung bunga menggunakan perbedaan bulan yang negatif
            $bungaUpdate = $kredit->jumlah * 0.02 * $selisihBulanUpdate;

            // Hitung hutang ditambah bunga
            $hutangPlusBungaUpdate = $kredit->jumlah + $bungaUpdate;



            $kredit->setAttribute('hutang_plus_bunga', ($hutangPlusBunga)); // Round down
            $kredit->setAttribute('hutang_plus_bunga_update', ($hutangPlusBungaUpdate)); // Round down
            $kredit->setAttribute('lama_bulan', $selisihBulan); // Use negative difference in months
            $kredit->setAttribute('lama_bulan_update', $selisihBulanUpdate); // Use negative difference in months
            $kredit->setAttribute('bunga', floor($bunga)); // Round down the bunga
            $kredit->setAttribute('bunga_update', floor($bungaUpdate)); // Round down the bunga
            Log::info("Kredit ID: {$kredit->id}, Jumlah: {$kredit->jumlah}, Lama Bulan: {$selisihBulan}, Bunga: {$bunga}, Total: {$kredit->hutang_plus_bunga}");
            return $kredit;
        });

        $sortedKredits = $calculatedKredits->sortBy(function ($item) {
            return [
                $item->status ? 0 : 1,  // Status false (0) di atas, true (1) di bawah
                $item->tanggal,
                $item->id
            ];
        }, SORT_REGULAR, $sortOrder === 'desc');

        // Ambil data kredit dengan status belum lunas (status = 0)
        $kreditsBelumLunas = $calculatedKredits->where('status', 0);

        // Hitung jumlah petani yang belum lunas, berdasarkan kolom 'nama' yang unik (case-insensitive)
        $jumlahPetaniBelumLunas = $kreditsBelumLunas->pluck('nama')
            ->map(function ($nama) {
                return strtolower($nama); // Ubah semua nama menjadi huruf kecil
            })
            ->unique()
            ->count();


        $totalKreditBelumLunas = $kreditsBelumLunas->sum('jumlah');
        $totalKreditPlusBungaBelumLunas = $kreditsBelumLunas->sum('hutang_plus_bunga');



        // Manually paginate the collection
        $page = $request->input('page', 1);
        $perPage = 20;
        $paginatedKredits = $sortedKredits->forPage($page, $perPage);

        // Create a custom paginator
        $paginator = new LengthAwarePaginator(
            $paginatedKredits,
            $sortedKredits->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );


        return view('kredit-direktur', [
            'kredits' => $paginator,
            'search' => $search,
            'statusFilter' => $statusFilter,
            'sortOrder' => $sortOrder,
            'jumlahPetaniBelumLunas' => $jumlahPetaniBelumLunas,
            'totalKreditBelumLunas' => $totalKreditBelumLunas,
            'totalKreditPlusBungaBelumLunas' => $totalKreditPlusBungaBelumLunas,
        ]);
    }



    public function downloadLaporanKredit(Request $request)
    {
        // Membuat instance KreditReportController
        $kreditReportController = new KreditReportController();

        // Memanggil fungsi generatePdf dari KreditReportController
        return $kreditReportController->generatePdf($request);
    }


    public function searchPetani(Request $request)
    {
        $search = $request->input('term');

        $petanis = Petani::where('nama', 'like', '%' . $search . '%')
            ->select('id', 'nama')
            ->limit(10)
            ->get();

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
        try {
            // Ambil semua data dari request
            $input = $request->all();

            // Hapus koma dari nilai jumlah jika ada
            if (isset($input['jumlah'])) {
                $input['jumlah'] = str_replace(',', '', $input['jumlah']);
            }

            // Validasi data setelah penghapusan koma
            $validator = Validator::make($input, [
                'debit_id' => 'nullable|int',
                'nama' => 'required|string',
                'tanggal' => 'required|date_format:Y-m-d',
                'keterangan' => 'required|string',
                'jumlah' => 'required|numeric',
                'status' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            // Ambil data yang sudah divalidasi
            $validatedData = $validator->validated();

            // Konversi tanggal ke format timestamp
            $timestamp = Carbon::createFromFormat('Y-m-d', $validatedData['tanggal'])->toDateTimeString();

            // Simpan data ke dalam database
            KreditDirektur::create($validatedData);

            return redirect()->back()->with('success', 'Kredit berhasil dimasukan');
        } catch (\Exception $e) {
            Log::error('Error creating kredit: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menambahkan kredit'], 500);
        }
    }





    public function update(Request $request, $id)
    {
        try {
            // Temukan data kredit berdasarkan ID
            $kredit = KreditDirektur::findOrFail($id);

            // Ambil semua data dari request
            $input = $request->all();

            // Hapus koma dari nilai jumlah jika ada
            if (isset($input['jumlah'])) {
                $input['jumlah'] = str_replace(',', '', $input['jumlah']);
            }

            // Validasi data setelah penghapusan koma
            $validator = Validator::make($input, [
                'nama' => 'required|string',
                'tanggal' => 'required|date_format:Y-m-d',
                'keterangan' => 'required|string',
                'jumlah' => 'required|numeric',
                'status' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            // Ambil data yang sudah divalidasi
            $validatedData = $validator->validated();

            // Konversi tanggal ke format timestamp
            $timestamp = Carbon::createFromFormat('Y-m-d', $validatedData['tanggal'])->toDateTimeString();

            // Perbarui data kredit
            $kredit->fill($validatedData);
            // Atur `updated_at` secara manual jika diperlukan
            $kredit->updated_at = $timestamp;

            // Simpan perubahan ke database
            $kredit->save();

            return redirect()->back()->with('success', 'Kredit berhasil diedit');
        } catch (\Exception $e) {
            Log::error('Error updating kredit: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat memperbarui kredit'], 500);
        }
    }




    public function show($id)
    {
        $kredit = KreditDirektur::with('petani')->findOrFail($id);
        return view('kredit.show', compact('kredit'));
    }

    public function destroy($id)
    {
        $kredit = KreditDirektur::findOrFail($id);
        $kredit->delete();
        return redirect()->back()->with('success', 'Kredit berhasil dihapus');
    }



    // public function search(Request $request)
    // {
    //     $query = $request->input('query');
    //     $kredits = Kredit::with('petani')
    //         ->whereHas('petani', function ($q) use ($query) {
    //             $q->where('nama', 'like', "%{$query}%");
    //         })
    //         ->orWhere('jumlah', 'like', "%{$query}%")
    //         ->orWhere('tanggal', 'like', "%{$query}%")
    //         ->get();

    //     return response()->json($kredits);
    // }

    public function autocomplete(Request $request)
    {
        $query = $request->get('query');
        $petanis = Petani::where('nama', 'LIKE', "%{$query}%")->get();
        return response()->json($petanis);
    }
}
