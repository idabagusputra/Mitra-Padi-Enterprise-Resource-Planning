<?php

namespace App\Http\Controllers;

use App\Models\Giling;
use App\Models\DaftarGiling;
use App\Models\PembayaranKredit;
use App\Models\Kredit;
use App\Models\Petani;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class GilingController extends Controller
{
    public function index()
    {
        $gilings = Giling::with(['petani', 'pembayaranKredits.kredits'])->get();

        $petanis = Petani::with(['kredits' => function ($query) {
            $query->where('status', false);
        }])->get();


        $latestGiling = DaftarGiling::latest()->first();

        if (!$latestGiling) {
            Log::error('Latest giling not found');
        } else {
            Log::info('Latest giling ID: ' . $latestGiling->id);
        }

        return view('laravel-examples.giling', compact('gilings', 'petanis', 'latestGiling'));
    }

    public function searchPetani(Request $request)
    {
        $search = $request->input('term');

        $petanis = Petani::with(['kredits' => function ($query) {
            $query->where('status', false);
        }])
            ->where('nama', 'like', "%{$search}%")
            ->get()
            ->map(function ($petani) {
                $totalHutang = $petani->kredits->sum('jumlah');
                return [
                    'id' => $petani->id,
                    'nama' => $petani->nama,
                    'alamat' => $petani->alamat,
                    'total_hutang' => $totalHutang
                ];
            });

        return response()->json($petanis);
    }

    public function create()
    {
        $petanis = Petani::with(['kredits' => function ($query) {
            $query->where('status', false);
        }])->get();
        return view('giling.create', compact('petanis'));
    }

    public function store(Request $request)
    {
        return DB::transaction(function () use ($request) {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'created_at' => 'required|date', // Menjamin bahwa input adalah tanggal yang valid
                'petani_id' => 'required|exists:petanis,id',
                'giling_kotor' => 'required|numeric',
                'biaya_giling' => 'required|numeric',
                'pulang' => 'required|numeric',
                'pinjam' => 'required|numeric',
                'biaya_buruh_giling' => 'required|numeric',
                'biaya_buruh_jemur' => 'required|numeric',
                'jemur' => 'required|numeric',
                'jumlah_konga' => 'required|numeric',
                'harga_konga' => 'required|numeric',
                'jumlah_menir' => 'required|numeric',
                'harga_menir' => 'required|numeric',
                'harga_jual' => 'required|numeric',
                'bunga' => 'required|numeric|min:0|max:100',
                'pengambilans' => 'nullable|array',
                'pengambilans.*.keterangan' => 'required|string',
                'pengambilans.*.keterangan_custom' => 'required_if:pengambilans.*.keterangan,custom',
                'pengambilans.*.jumlah' => 'required|integer',
                'pengambilans.*.harga' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $validatedData = $validator->validated();

            // Buat Giling dan Pengambilan
            $giling = Giling::create($validatedData);
            // Create Pengambilan entries if provided
            // Handle pengambilans
            if (!empty($validatedData['pengambilans']) && is_array($validatedData['pengambilans'])) {
                foreach ($validatedData['pengambilans'] as $pengambilanData) {
                    if (!empty($pengambilanData['keterangan']) || !empty($pengambilanData['jumlah']) || !empty($pengambilanData['harga'])) {
                        $giling->pengambilans()->create($pengambilanData);
                    }
                }
            }




            $petani = Petani::findOrFail($request->petani_id);
            $totalHutang = $petani->kredits()->where('status', false)->sum('jumlah');
            $bungaRate = $validatedData['bunga'] / 100;
            $totalHutangDenganBunga = $totalHutang * (1 + $bungaRate);

            // Hitung total dana dan total pengambilan
            $totalPengambilan = $giling->calculateTotalPengambilan();
            $dana = $giling->calculateDana();
            $sisaDana = $dana - $totalPengambilan;


            $pembayaranKredit = PembayaranKredit::create([
                'created_at' => $validatedData['created_at'],
                'giling_id' => $giling->id,
                'total_hutang' => $totalHutangDenganBunga,
                'dana_terbayar' => min($dana, $totalHutangDenganBunga),
                'bunga' => $validatedData['bunga']
            ]);


            // Log the total hutang including the total bunga
            $bungaInput = $validatedData['bunga'];

            // Use the new kalkulasiBunga method
            $totalBunga = $giling->kalkulasiBunga($bungaInput);

            $hutangDenganPlusTotalBunga = $totalHutang + $totalBunga;
            Log::info('Total bunga: ' . $totalBunga);
            Log::info('Total hutang setelah ditambahkan bunga: ' . $hutangDenganPlusTotalBunga);

            // Fetch unpaid kredits before generating PDF
            $unpaidKredits = $giling->petani->kredits()->where('status', false)->get();




            $daftarGiling = DaftarGiling::create([
                'giling_id' => $giling->id,
                'giling_kotor' => $validatedData['giling_kotor'],
                'biaya_giling' => $validatedData['biaya_giling'],
                'ongkos_giling' => $validatedData['giling_kotor'] * $validatedData['biaya_giling'] / 100,
                'beras_bersih' => $validatedData['giling_kotor'] - ($validatedData['giling_kotor'] * $validatedData['biaya_giling'] / 100) - $validatedData['pinjam'],
                'beras_jual' => $berasJual = $validatedData['giling_kotor'] - ($validatedData['giling_kotor'] * $validatedData['biaya_giling'] / 100) - $validatedData['pinjam'] - $validatedData['pulang'],
                'total_hutang' => $hutangDenganPlusTotalBunga,
                'total_pengambilan' => $totalPengambilan,
                'pulang' => $validatedData['pulang'],
                'pinjam' => $validatedData['pinjam'],
                'harga_jual' => $validatedData['harga_jual'],
                'dana_jual_beras' => ($berasJual * $validatedData['harga_jual']),
                'dana_penerima' => $dana - $totalPengambilan - $hutangDenganPlusTotalBunga,
                'biaya_buruh_giling' => $validatedData['biaya_buruh_giling'],
                'total_biaya_buruh_giling' => $validatedData['biaya_buruh_giling'] * $validatedData['giling_kotor'],
                'jemur' => $validatedData['jemur'],
                'biaya_buruh_jemur' => $validatedData['biaya_buruh_jemur'],
                'total_biaya_buruh_jemur' => $validatedData['biaya_buruh_jemur'] * $validatedData['jemur'],
                'jumlah_konga' => $validatedData['jumlah_konga'],
                'harga_konga' => $validatedData['harga_konga'],
                'dana_jual_konga' => $validatedData['jumlah_konga'] *  $validatedData['harga_konga'],
                'jumlah_menir' => $validatedData['jumlah_menir'],
                'harga_menir' => $validatedData['harga_menir'],
                'dana_jual_menir' => $validatedData['jumlah_menir'] * $validatedData['harga_menir'],
                'bunga' => $validatedData['bunga']
            ]);


            // Generate the PDF before updating kredit status
            $receiptController = new ReceiptController();
            $pdfPath = $receiptController->generatePdf($daftarGiling->id, $unpaidKredits);

            // Check if the file exists
            if (!file_exists($pdfPath)) {
                Log::error("PDF file not found: " . $pdfPath);
                return back()->with('error', 'PDF file could not be generated. Please try again.');
            }


            if ($sisaDana < 0) {
                $kredits = $petani->kredits()->where('status', false)->get();
                $newHutang = abs($sisaDana);

                foreach ($kredits as $kredit) {
                    $kredit->update([
                        'status' => true,
                        'keterangan' => "Terbayar | Menjadi Hutang Baru: Rp " . number_format($newHutang, 2)
                    ]);
                }

                Kredit::create([
                    'petani_id' => $petani->id,
                    'pKredit_id' => $pembayaranKredit->id,
                    'tanggal' => now(),
                    'keterangan' => 'Dana tidak mencukupi! ' .
                        '| Dana: ' . number_format($dana, 2) .
                        '| Total Hutang: ' . number_format($hutangDenganPlusTotalBunga, 2) .
                        '| Total Pengambilan: ' . number_format($totalPengambilan, 2),
                    'jumlah' => $newHutang,
                    'status' => false
                ]);

                $pembayaranKredit->update([
                    'total_hutang' => $hutangDenganPlusTotalBunga,
                    'dana_terbayar' => $dana,
                ]);
            } elseif ($sisaDana > 0) {
                $kredits = $petani->kredits()->where('status', false)->orderBy('tanggal')->get();
                $remainingSisaDana = $sisaDana;

                foreach ($kredits as $kredit) {
                    $totalLamaBulan = $pembayaranKredit->hitungLamaHutangBulan($kredit->tanggal);
                    $hutangDenganBunga = $kredit->jumlah * (1 + $bungaRate * $totalLamaBulan);

                    if ($remainingSisaDana >= $hutangDenganBunga) {
                        $kredit->update([
                            'status' => true,
                            'keterangan' => $kredit->keterangan . " | Terbayar penuh: Rp " . number_format($hutangDenganBunga, 2) .
                                ' | Durasi: ' . number_format($totalLamaBulan, 2) . ' Bulan'
                        ]);
                        $remainingSisaDana -= $hutangDenganBunga;
                    } else {
                        $terbayar = $remainingSisaDana;
                        $sisaHutang = $hutangDenganBunga - $terbayar;

                        $kredit->update([
                            'status' => true,
                            'keterangan' => $kredit->keterangan . " | Terbayar sebagian: Rp " . number_format($terbayar, 2) .
                                ' | Sisa hutang: Rp ' . number_format($sisaHutang, 2) .
                                ' | Durasi: ' . number_format($totalLamaBulan, 2) . ' Bulan'
                        ]);

                        Kredit::create([
                            'petani_id' => $petani->id,
                            'pKredit_id' => $pembayaranKredit->id,
                            'tanggal' => now(),
                            'keterangan' => 'Sisa hutang dari pembayaran sebagian | Sisa Dana : ' . number_format($dana, 2) .
                                ', Total Hutang: ' . number_format($hutangDenganPlusTotalBunga, 2) .
                                ', Total Pengambilan: ' . number_format($totalPengambilan, 2),
                            'jumlah' => $sisaHutang,
                            'status' => false
                        ]);

                        $remainingSisaDana = 0;
                        break;
                    }
                }

                $pembayaranKredit->update([
                    'total_hutang' => $hutangDenganPlusTotalBunga,
                    'dana_terbayar' => $dana - $remainingSisaDana,
                ]);
            } else {
                $pembayaranKredit->update([
                    'total_hutang' => $hutangDenganPlusTotalBunga,
                    'dana_terbayar' => $dana,
                ]);

                $petani->kredits()->where('status', false)->update([
                    'status' => true,
                    'keterangan' => DB::raw("CONCAT(keterangan, ' | Terbayar penuh: Rp ', jumlah)")
                ]);
            }



            return redirect()->route('giling.index')
                ->with('success', 'Data giling berhasil disimpan 👍🏻')
                ->with('giling_id', $daftarGiling->id); // This will store it in the session


            // // Return a download response
            // return response()->download($pdfPath, 'receipt-' . $daftarGiling->id . '.pdf', [
            //     'Content-Type' => 'application/pdf',
            // ])->deleteFileAfterSend(false);



            // Hubungkan pembayaran kredit dengan kredit terkait
            $pembayaranKredit->kredits()->attach($petani->kredits()->pluck('id')->toArray());

            return redirect()->route('giling.index')->with('success', 'Data giling berhasil disimpan.');
        });
    }

    public function show($id)
    {
        $giling = Giling::with(['petani', 'pembayaranKredits.kredits'])->findOrFail($id);
        return view('giling.show', compact('giling'));
    }

    public function edit($id)
    {
        $giling = Giling::findOrFail($id);
        $petanis = Petani::all();
        return view('giling.edit', compact('giling', 'petanis'));
    }

    private function formatGilingData($giling)
    {
        return [
            'id' => $giling->id,
            'giling_kotor' => $giling->giling_kotor,
            'biaya_giling' => $giling->biaya_giling,
            'pulang' => $giling->pulang,
            'pinjam' => $giling->pinjam,
            'biaya_buruh_giling' => $giling->biaya_buruh_giling,
            'biaya_buruh_jemur' => $giling->biaya_buruh_jemur,
            'jemur' => $giling->jemur,
            'jumlah_konga' => $giling->jumlah_konga,
            'harga_konga' => $giling->harga_konga,
            'jumlah_menir' => $giling->jumlah_menir,
            'harga_menir' => $giling->harga_menir,
            'harga_jual' => $giling->harga_jual,
            'petani' => $giling->petani ? [
                'id' => $giling->petani->id,
                'nama' => $giling->petani->nama,
                'alamat' => $giling->petani->alamat,
                'no_telepon' => $giling->petani->no_telepon,
            ] : null,
            'pembayaran_kredits' => $giling->pembayaranKredits->map(function ($pembayaran) {
                return [
                    'id' => $pembayaran->id,
                    'bunga' => $pembayaran->bunga,
                    'kredit' => $pembayaran->kredits->map(function ($kredit) {
                        return [
                            'id' => $kredit->id,
                            'jumlah' => $kredit->jumlah,
                            'tanggal' => $kredit->tanggal,
                            'keterangan' => $kredit->keterangan,
                            'status' => $kredit->status,
                        ];
                    }),
                ];
            }),
            'biaya_giling_kg' => $giling->calculateBiayaGiling(),
            'beras_bersih' => $giling->calculateBerasBersih(),
            'beras_jual' => $giling->calculateBerasJual(),
            'buruh_giling' => $giling->calculateBuruhGiling(),
            'buruh_jemur' => $giling->calculateBuruhJemur(),
            'jual_konga' => $giling->calculateJualKonga(),
            'jual_menir' => $giling->calculateJualMenir(),
            'hutang' => $giling->calculateHutang(),
            'dana' => $giling->calculateDana(),
            'created_at' => $giling->created_at,
            'updated_at' => $giling->updated_at,
        ];
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'giling_kotor' => 'sometimes|required|integer',
            'biaya_giling' => 'sometimes|required|integer',
            'pulang' => 'sometimes|required|integer',
            'pinjam' => 'sometimes|required|integer',
            'biaya_buruh_giling' => 'sometimes|required|integer',
            'biaya_buruh_jemur' => 'sometimes|required|integer',
            'jumlah_konga' => 'sometimes|required|integer',
            'harga_konga' => 'sometimes|required|integer',
            'jumlah_menir' => 'sometimes|required|integer',
            'harga_menir' => 'sometimes|required|integer',
            'jemur' => 'sometimes|required|integer',
            'harga_jual' => 'sometimes|required|integer', // Menambahkan validasi untuk harga_jual
        ]);

        try {
            $giling = Giling::findOrFail($id);
            $giling->update($request->all());


            return response()->json($this->formatGilingData($giling));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $giling = Giling::findOrFail($id);
            $giling->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
