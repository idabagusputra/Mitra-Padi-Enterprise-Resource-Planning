<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $query = Car::query();

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->where('nama_mobil', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan status
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Sorting: Status belum_servis di atas, lalu kelompokkan berdasarkan nama_mobil
        $query->orderByRaw("CASE WHEN status = 'belum_servis' THEN 0 ELSE 1 END")
            ->orderBy('nama_mobil', 'asc')
            ->orderBy('tanggal_servis', 'desc')
            ->orderBy('id', 'desc');

        $cars = $query->get();

        // Kelompokkan berdasarkan status dulu, lalu nama mobil, beri nomor berdasarkan nama mobil
        $groupedCars = collect();

        // Pisahkan berdasarkan status
        $belumServis = $cars->where('status', 'belum_servis')->groupBy('nama_mobil');
        $sudahServis = $cars->where('status', 'sudah_servis')->groupBy('nama_mobil');

        // Hitung total per nama mobil untuk penomoran
        $totalPerNama = $cars->groupBy('nama_mobil')->map->count();

        // Proses belum servis dulu
        foreach ($belumServis as $namaMobil => $group) {
            $counter = $totalPerNama[$namaMobil];
            $sortedGroup = $group->sortBy(['tanggal_servis', 'desc'])->sortBy(['id', 'desc']);
            foreach ($sortedGroup as $car) {
                $car->nomor_urut = $counter;
                $counter--;
                $groupedCars->push($car);
            }
        }

        // Proses sudah servis
        foreach ($sudahServis as $namaMobil => $group) {
            $usedNumbers = $belumServis->get($namaMobil, collect())->count();
            $counter = $totalPerNama[$namaMobil] - $usedNumbers;

            $sortedGroup = $group->sortBy(['tanggal_servis', 'desc'])->sortBy(['id', 'desc']);
            foreach ($sortedGroup as $car) {
                $car->nomor_urut = $counter;
                $counter--;
                $groupedCars->push($car);
            }
        }

        $mobilBelumServis = Car::belumServis()->distinct('nama_mobil')->pluck('nama_mobil');

        return view('servisMobil', compact('cars', 'groupedCars', 'mobilBelumServis'));
    }

    // Menyimpan data mobil baru (data awal)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_mobil' => 'required|string|max:255',
            'tanggal_servis' => 'required|date',
            'kilometer' => 'required|integer|min:0',
            'status' => 'required|in:belum_servis,sudah_servis',
            'filter_oli' => 'boolean',
            'filter_solar' => 'boolean',
            'keterangan' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        $data['filter_oli'] = $request->boolean('filter_oli');
        $data['filter_solar'] = $request->boolean('filter_solar');
        $data['keterangan'] = $request->keterangan;

        $car = Car::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Data mobil berhasil ditambahkan',
            'data' => $car
        ]);
    }

    // Update servis terbaru
    public function updateServis(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_mobil' => 'required|string|exists:cars,nama_mobil',
            'tanggal_servis' => 'required|date',
            'kilometer' => 'required|integer|min:0',
            'filter_oli' => 'boolean',
            'filter_solar' => 'boolean',
            'keterangan' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            return DB::transaction(function () use ($request) {
                $car = Car::where('nama_mobil', $request->nama_mobil)
                    ->where('status', 'belum_servis')
                    ->first();

                if (!$car) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Mobil dengan nama "' . $request->nama_mobil . '" tidak ditemukan atau sudah dalam status servis'
                    ], 404);
                }

                if ($request->kilometer <= $car->kilometer) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Kilometer baru (' . number_format($request->kilometer) . ' km) harus lebih besar dari kilometer sebelumnya (' . number_format($car->kilometer) . ' km)'
                    ], 422);
                }

                // Update data lama menjadi sudah_servis
                $car->update([
                    'status' => 'sudah_servis'
                ]);

                // Buat data baru untuk servis berikutnya
                $newCar = Car::create([
                    'nama_mobil' => $car->nama_mobil,
                    'tanggal_servis' => now()->toDateString(),
                    'kilometer' => $request->kilometer,
                    'filter_oli' => $request->boolean('filter_oli'),
                    'filter_solar' => $request->boolean('filter_solar'),
                    'keterangan' => $request->keterangan,
                    'status' => 'belum_servis'
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data servis berhasil diperbarui. Data servis sebelumnya tersimpan dan data baru telah dibuat.',
                    'data' => [
                        'servis_selesai' => [
                            'id' => $car->id,
                            'nama_mobil' => $car->nama_mobil,
                            'tanggal_servis' => $car->tanggal_servis,
                            'kilometer' => $car->kilometer,
                            'filter_oli' => $newCar->filter_oli,
                            'filter_solar' => $newCar->filter_solar,
                            'keterangan' => $newCar->keterangan,
                            'status' => $car->status
                        ],
                        'servis_baru' => [
                            'id' => $newCar->id,
                            'nama_mobil' => $newCar->nama_mobil,
                            'tanggal_servis' => $newCar->tanggal_servis,
                            'kilometer' => $newCar->kilometer,
                            'filter_oli' => $car->filter_oli,
                            'filter_solar' => $car->filter_solar,
                            'keterangan' => $car->keterangan,
                            'status' => $newCar->status
                        ]
                    ]
                ]);
            });
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data servis. Silakan coba lagi.',
                'error_detail' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    // Menampilkan detail mobil
    public function show($id)
    {
        $car = Car::findOrFail($id);
        return response()->json($car);
    }

    // Update data mobil
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_mobil' => 'required|string|max:255',
            'tanggal_servis' => 'required|date',
            'kilometer' => 'required|integer|min:0',
            'status' => 'required|in:belum_servis,sudah_servis',
            'filter_oli' => 'sometimes|boolean',
            'filter_solar' => 'sometimes|boolean',
            'keterangan' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $car = Car::findOrFail($id);

        $data = [
            'nama_mobil' => $request->nama_mobil,
            'tanggal_servis' => $request->tanggal_servis,
            'kilometer' => $request->kilometer,
            'status' => $request->status,
            'filter_oli' => $request->boolean('filter_oli', false),
            'filter_solar' => $request->boolean('filter_solar', false),
            'keterangan' => $request->keterangan
        ];

        $car->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Data mobil berhasil diperbarui',
            'data' => $car
        ]);
    }

    // Hapus data mobil
    public function destroy($id)
    {
        $car = Car::findOrFail($id);
        $car->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data mobil berhasil dihapus'
        ]);
    }

    // Reset status mobil menjadi belum servis
    public function resetStatus($nama_mobil)
    {
        $car = Car::where('nama_mobil', $nama_mobil)->where('status', 'sudah_servis')->first();

        if (!$car) {
            return response()->json([
                'success' => false,
                'message' => 'Mobil dengan nama tersebut tidak ditemukan atau sudah dalam status belum servis'
            ], 404);
        }

        $car->update(['status' => 'belum_servis']);

        return response()->json([
            'success' => true,
            'message' => 'Status mobil berhasil direset ke belum servis'
        ]);
    }

    // Search functionality
    public function search(Request $request)
    {
        $term = $request->get('term');

        $cars = Car::where('nama_mobil', 'LIKE', '%' . $term . '%')
            ->select('id', 'nama_mobil', 'status', 'tanggal_servis', 'kilometer', 'filter_oli', 'filter_solar', 'keterangan')
            ->limit(10)
            ->get();

        return response()->json($cars);
    }

    // Autocomplete API endpoint
    public function autocomplete(Request $request)
    {
        $term = $request->get('term');

        $cars = Car::where('nama_mobil', 'LIKE', '%' . $term . '%')
            ->select('nama_mobil', 'status', 'filter_oli', 'filter_solar')
            ->distinct('nama_mobil')
            ->limit(10)
            ->get()
            ->map(function ($car) {
                return [
                    'nama' => $car->nama_mobil,
                    'status' => $car->status == 'belum_servis' ? 'Belum Servis' : 'Sudah Servis',
                    'filter_oli' => $car->filter_oli ? 'Sudah Ganti' : 'Belum Ganti',
                    'filter_solar' => $car->filter_solar ? 'Sudah Ganti' : 'Belum Ganti'
                ];
            });

        return response()->json($cars);
    }

    // Generate laporan servis
    public function generateReport(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $status = $request->get('status');

        $query = Car::query();

        if ($startDate) {
            $query->where('tanggal_servis', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('tanggal_servis', '<=', $endDate);
        }

        if ($status && $status != 'all') {
            $query->where('status', $status);
        }

        $cars = $query->orderBy('tanggal_servis', 'desc')->get();

        return view('cars.report', compact('cars', 'startDate', 'endDate', 'status'));
    }

    // Download laporan dalam format PDF
    public function downloadReport(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil didownload'
        ]);
    }

    // Find PDF laporan
    public function findPdf(Request $request)
    {
        return response()->json([
            'success' => true,
            'pdf_url' => 'path/to/pdf/file.pdf'
        ]);
    }
}
