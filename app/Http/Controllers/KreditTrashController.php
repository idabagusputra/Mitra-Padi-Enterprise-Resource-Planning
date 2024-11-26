<?php

namespace App\Http\Controllers;

use App\Models\Kredit;
use App\Models\Petani;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\KreditReportController;

class KreditTrashController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $statusFilter = $request->input('status');
        $alamatFilter = $request->input('alamat');
        $sortOrder = $request->input('sort', 'desc'); // Default ke 'desc'
        $showDeleted = $request->input('show_deleted'); // Tambahan untuk filter deleted_at

        $query = Kredit::onlyTrashed()->with('petani');


        // Filter hanya data yang terhapus jika show_deleted=true
        if ($showDeleted === 'true') {
            $query->onlyTrashed();
        }

        // Apply filters
        if ($search) {
            $query->whereHas('petani', function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%');
            });
        }

        // Handle filtering by alamat
        if ($request->has('alamat')) {
            if ($alamatFilter === 'campur') {
                $query->whereHas('petani', function ($q) {
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
                    ]); // Filter petani dengan alamat berbeda dari daftar nilai
                });
            } elseif ($alamatFilter !== 'all') {
                $query->whereHas('petani', function ($q) use ($alamatFilter) {
                    $q->where('alamat', $alamatFilter);
                });
            }
        }

        if ($statusFilter !== null) {
            $query->where('status', $statusFilter);
        }



        // Get all matching kredits without pagination
        $allKredits = $query->get();

        // Calculate additional values and prepare data
        $now = Carbon::now();
        $calculatedKredits = $allKredits->map(function ($kredit) use ($now) {
            $kreditDate = Carbon::parse($kredit->tanggal);
            // Calculate the difference in months
            $diffInMonths = $kreditDate->diffInMonths($now);
            // Ensure the difference is negative and floored
            $selisihBulan = floor($diffInMonths);
            // Calculate bunga using the negative difference in months
            $bunga = $kredit->jumlah * 0.02 * $selisihBulan;
            // Calculate hutang plus bunga
            $hutangPlusBunga = $kredit->jumlah + $bunga;
            // Set attributes with the updated calculations
            $kredit->setAttribute('hutang_plus_bunga', ($hutangPlusBunga)); // Round down
            $kredit->setAttribute('lama_bulan', $selisihBulan); // Use negative difference in months
            $kredit->setAttribute('bunga', floor($bunga)); // Round down the bunga
            Log::info("Kredit ID: {$kredit->id}, Jumlah: {$kredit->jumlah}, Lama Bulan: {$selisihBulan}, Bunga: {$bunga}, Total: {$kredit->hutang_plus_bunga}");
            return $kredit;
        });

        // Sort the collection
        $sortedKredits = $calculatedKredits->sortBy(function ($item) {
            return [
                $item->deleted_at, // Prioritas utama
                $item->tanggal,    // Prioritas kedua
                $item->id          // Prioritas ketiga
            ];
        }, SORT_REGULAR, $sortOrder === 'desc');


        $kreditsBelumLunas = $calculatedKredits->where('status', 0);

        // Calculate summary data
        $jumlahPetaniBelumLunas = $kreditsBelumLunas->pluck('petani_id')->unique()->count();
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

        // Retrieve all petanis for use in the view
        $petanis = Petani::all();

        // Get unique alamat list for the filter dropdown
        $alamatList = $petanis->pluck('alamat')->unique()->filter()->values();

        return view('laravel-examples/kreditTrash', [
            'kredits' => $paginator,
            'petanis' => $petanis,
            'search' => $search,
            'statusFilter' => $statusFilter,
            'sortOrder' => $sortOrder,
            'jumlahPetaniBelumLunas' => $jumlahPetaniBelumLunas,
            'totalKreditBelumLunas' => $totalKreditBelumLunas,
            'totalKreditPlusBungaBelumLunas' => $totalKreditPlusBungaBelumLunas,
            'alamatList' => $alamatList
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
            $validator = Validator::make($request->all(), [
                'petani_id' => 'required|exists:petanis,id',
                'tanggal' => 'required|date_format:Y-m-d',
                'keterangan' => 'required|string',
                'jumlah' => 'required|numeric',
                'status' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $kredit = Kredit::create($validator->validated());

            return response()->json(['success' => true, 'message' => 'Kredit berhasil ditambahkan', 'data' => $kredit]);
        } catch (\Exception $e) {
            Log::error('Error creating kredit: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menambahkan kredit'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $kredit = Kredit::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'petani_id' => 'required|exists:petanis,id',
                'tanggal' => 'required|date_format:Y-m-d',
                'keterangan' => 'required|string',
                'jumlah' => 'required|numeric',
                'status' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $kredit->update($validator->validated());

            return response()->json(['success' => true, 'message' => 'Kredit berhasil diperbaharui']);
        } catch (\Exception $e) {
            Log::error('Error updating kredit: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat memperbarui kredit'], 500);
        }
    }

    public function restore($id)
    {
        try {
            // Cari data yang soft deleted
            $kredit = Kredit::onlyTrashed()->findOrFail($id);

            // Restore data
            $kredit->restore();

            // Redirect ke fungsi index untuk menampilkan halaman dengan data terbaru
            return redirect()->route('kredit-ryclebin.index')->with('success', 'Kredit berhasil dikembalikan');
        } catch (\Exception $e) {
            Log::error('Error restoring kredit: ' . $e->getMessage());

            // Redirect ke fungsi index meskipun terjadi error, agar halaman tetap tampil
            return redirect()->route('kredit-ryclebin.index')->with('error', 'Terjadi kesalahan saat mengembalikan kredit');
        }
    }





    public function show($id)
    {
        $kredit = Kredit::with('petani')->findOrFail($id);
        return view('kredit.show', compact('kredit'));
    }

    public function destroy($id)
    {
        $kredit = Kredit::findOrFail($id);
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
