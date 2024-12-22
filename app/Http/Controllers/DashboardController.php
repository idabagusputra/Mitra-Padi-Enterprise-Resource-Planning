<?php

namespace App\Http\Controllers;

use App\Models\DaftarGiling;
use App\Models\Petani;
use App\Models\Kredit;
use App\Models\Giling;
use App\Models\Debit;
use App\Models\RekapDana;
use App\Models\RekapKredit;
use App\Models\PembayaranKredit;
use App\Models\KreditNasabahPalu;
use App\Models\KreditTitipanPetani;
use App\Models\UtangKeOperator;
use App\Models\RekapDanaTitipanPetani;
use App\Models\RekapKreditNasabahPalu;
use App\Models\RekapUtangKeOperator;




use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Menghitung total jumlah petani
        $totalPetani = Petani::count();

        $query = Kredit::with('petani');


        $kreditsBelumLunas = Kredit::with('petani')->where('status', false)->get();

        $kreditsNasabahPaluBelumLunas = KreditNasabahPalu::where('status', false)->get();

        $kreditsOperatorBelumLunas = UtangKeOperator::with('petani')->where('status', false)->get();

        $kreditsTitipanPetaniBelumLunas = KreditTitipanPetani::with('petani')->where('status', false)->get();



        // Hitung jumlah total kredit belum lunas
        $totalKreditBelumLunas = $kreditsBelumLunas->sum('jumlah');
        $totalkreditsNasabahPaluBelumLunas = $kreditsNasabahPaluBelumLunas->sum('jumlah');
        $totalkreditsOperatorBelumLunas = $kreditsOperatorBelumLunas->sum('jumlah');
        $totalkreditsTitipanPetaniBelumLunas = $kreditsTitipanPetaniBelumLunas->sum('jumlah');

        // (Opsional) Hitung jumlah petani unik
        $jumlahPetaniBelumLunas = $kreditsBelumLunas->pluck('petani_id')->unique()->count();


        // Mendapatkan bulan saat ini
        $currentMonth = Carbon::now()->format('m');
        $currentYear = Carbon::now()->format('Y');

        // Query untuk mengambil nilai 'beras_bersih' yang memiliki 'created_at' pada bulan sekarang
        $totalBerasBersihBulanIni = DaftarGiling::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->sum('beras_bersih');

        $totalKeseluruhanOngkosGiling = DaftarGiling::sum('ongkos_giling');

        $totalKeseluruhanBulanIniOngkosGiling = DaftarGiling::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->sum('ongkos_giling');

        // // Mendapatkan bulan-bulan dari Januari hingga Desember
        // $months = range(1, 12);

        // // Menginisialisasi array untuk menampung data per bulan
        // $dataBerasBersih = [];
        // $dataOngkosGiling = [];
        // $dataPendapatanTerjual = [];

        // foreach ($months as $month) {
        //     $totalBerasBersih = DaftarGiling::whereMonth('created_at', $month)
        //         ->whereYear('created_at', Carbon::now()->year)
        //         ->sum('beras_bersih');
        //     $dataBerasBersih[] = $totalBerasBersih;

        //     $totalOngkosGiling = DaftarGiling::whereMonth('created_at', $month)
        //         ->whereYear('created_at', Carbon::now()->year)
        //         ->sum('ongkos_giling');
        //     $dataOngkosGiling[] = $totalOngkosGiling;

        //     $totalHargaJual = DaftarGiling::whereMonth('created_at', $month)
        //         ->whereYear('created_at', Carbon::now()->year)
        //         ->sum('harga_jual');
        //     $pendapatanBerasTerjual = $totalOngkosGiling * $totalHargaJual;
        //     $dataPendapatanTerjual[] = $pendapatanBerasTerjual;
        // }

        // Get the last 12 months starting from August 2023
        $months = [];
        $currentDate = Carbon::create(2024, 4, 1);
        $endDate = Carbon::now();

        // Collect the last 12 months
        while (count($months) < 12) {
            $months[] = $currentDate->copy();
            $currentDate->addMonth();
        }

        // Reinitialize arrays for data
        $dataBerasBersih = [];
        $dataOngkosGiling = [];
        $dataPendapatanTerjual = [];

        // Process each month
        foreach ($months as $monthDate) {
            $month = $monthDate->month;
            $year = $monthDate->year;

            // Calculate Beras Bersih for the specific month and year
            $totalBerasBersih = DaftarGiling::where(function ($query) use ($month, $year) {
                $query->whereMonth('created_at', $month)
                    ->whereYear('created_at', $year);
            })->sum('beras_bersih');
            $dataBerasBersih[] = $totalBerasBersih;

            // Hitung Total Ongkos Giling untuk bulan dan tahun spesifik
            $totalOngkosGiling = DaftarGiling::whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->sum('ongkos_giling');
            $dataOngkosGiling[] = $totalOngkosGiling;

            // Hitung Pendapatan Beras Terjual dengan mengalikan harga_jual dan ongkos_giling per baris
            $pendapatanBerasTerjual = DaftarGiling::whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->get()
                ->sum(function ($item) {
                    return $item->harga_jual * $item->ongkos_giling;
                });
            $dataPendapatanTerjual[] = $pendapatanBerasTerjual;

            $pendapatanBerasTerjualTotal = DaftarGiling::get()
                ->sum(function ($item) {
                    return $item->harga_jual * $item->ongkos_giling;
                });

            $pendapatanBerasTerjualTotalPerBulan = DaftarGiling::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->get()
                ->sum(function ($item) {
                    return $item->harga_jual * $item->ongkos_giling;
                });
        }




        // Reinitialize arrays for data
        $dataTotalHutang = [];
        $dataTotalHutangPlusBunga = [];
        $dataPendapatanDariBunga = [];

        // Process each month
        foreach ($months as $monthDate) {
            $month = $monthDate->month;
            $year = $monthDate->year;

            $totalHutang = Kredit::where(function ($query) use ($month, $year) {
                $query->whereMonth('updated_at', $month)
                    ->whereYear('updated_at', $year);
            })
                ->where('status', true)
                ->whereNotIn('petani_id', [187]) // Mengabaikan petani_id dengan nilai 187
                ->whereNull('deleted_at') // Hanya menghitung yang belum dihapus
                ->sum('jumlah');

            $dataTotalHutang[] = $totalHutang;







            $gilings = DB::table('daftar_gilings')
                ->whereNull('deleted_at') // Hanya pilih record yang tidak memiliki soft delete
                ->pluck('id')->toArray(); // Ambil kolom 'id' sebagai array

            $totalHutangPlusBunga = PembayaranKredit::whereIn('giling_id', $gilings)
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->sum('total_hutang');

            // Pastikan nilai yang dihitung tidak null
            $dataTotalHutangPlusBunga[] = $totalHutangPlusBunga ?? 0;




            // Menghitung Pendapatan Dari Bunga
            $pendapatanDariBunga = $totalHutangPlusBunga - $totalHutang;

            if ($pendapatanDariBunga < 0) {
                $pendapatanDariBunga = 0; // Set nilainya menjadi 0 jika negatif
            }

            $dataPendapatanDariBunga[] = $pendapatanDariBunga;

            $positivePendapatanDariBunga = array_filter($dataPendapatanDariBunga, function ($value) {
                return $value >= 0; // Hanya ambil nilai yang >= 0 (positif)
            });

            $sumPendapatanDariBunga = array_sum($positivePendapatanDariBunga);

            $sumTotalHutang = array_sum($dataTotalHutang);
        }

        // Calculate for the current month
        $totalHutangPerbulan = Kredit::where(function ($query) use ($currentMonth, $currentYear) {
            $query->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear);
        })->where('status', true)->sum('jumlah');


        $gilings = DB::table('daftar_gilings')
            ->whereNull('deleted_at') // Hanya pilih record yang tidak memiliki soft delete
            ->pluck('id')->toArray(); // Ambil kolom 'id' sebagai array

        $totalHutangPlusBungaPerbulan = PembayaranKredit::whereIn('giling_id', $gilings)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('total_hutang');






        // Calculate Pendapatan Dari Bunga for the current month
        $pendapatanDariBungaTotalPerbulan = $totalHutangPlusBungaPerbulan - $totalHutangPerbulan;




        // Create month labels for frontend use
        $monthLabels = array_map(function ($monthDate) {
            return $monthDate->format('M Y');
        }, $months);







        // Ambil data dengan relasi 'petani', abaikan petani dengan id 187, dan paginasi 13 entri per halaman
        $gilings = Giling::with(['petani'])
            ->whereDoesntHave('petani', function ($query) {
                $query->where('id', 187);
            })
            ->orderBy('id', 'desc')
            ->paginate(100);

        // Ambil data PembayaranKredit terkait dengan ID Giling terbaru
        $pembayaranKreditsLangsung = PembayaranKredit::with(['giling'])
            ->whereIn('giling_id', $gilings->pluck('id')) // Ambil hanya PembayaranKredit yang memiliki relasi dengan giling ID dari giling terbaru
            ->get();

        $data = [];
        $hutangYangDibayar = 0; // Variabel untuk menghitung total hutang yang sudah dibayar


        // // Proses data untuk tabel
        // foreach ($gilings as $giling) {
        //     $petani = $giling->petani;

        //     // Pastikan ada petani dan kredit
        //     if ($petani && $petani->kredits->isNotEmpty()) {
        //         $pembayaranKredits = $petani->kredits->filter(function ($kredit) use ($giling) {
        //             return $kredit->pKredit_id == $giling->id;
        //         });

        //         $pembayaranKreditsFalse = $petani->kredits;

        //         $pembayaranKreditsTransaksi = $petani->kredits->filter(function ($kredit) use ($giling) {
        //             return $kredit->pKredit_id == $giling->id && $kredit->status == true;
        //         });

        //         // Ambil total hutang yang sudah dibayar terkait dengan giling_id
        //         $hutangYangDibayar = $pembayaranKreditsLangsung->where('giling_id', $giling->id)->pluck('total_hutang')->sum();

        //         // Hitung sisa utang yang belum lunas
        //         $sisaUtang = $pembayaranKreditsFalse->where('status', false)->sum('jumlah');

        //         $status = $sisaUtang > 0 ? false : true;
        //         $sisaUtangFormatted = 'Rp ' . number_format($sisaUtang, 0, ',', '.');

        //         // Tambahkan setiap data giling ke array, terlepas dari kesamaan nama
        //         $data[] = [
        //             'giling_id' => $giling->id, // Tambahkan ID giling
        //             'petani_id' => $petani->id, // Tambahkan ID petani
        //             'petani' => $petani->nama,
        //             'transaksi' => $pembayaranKreditsTransaksi->count(),
        //             'sisa_utang' => $sisaUtangFormatted,
        //             'status' => $status,
        //             'hutangYangDibayar' => 'Rp ' . number_format($hutangYangDibayar, 0, ',', '.'),
        //         ];
        //     }
        // }


        foreach ($gilings as $giling) {
            $petani = $giling->petani;

            // Abaikan petani dengan ID 187
            if ($petani && $petani->id === 187) {
                continue; // Lewati iterasi ini jika petani memiliki ID 187
            }
            // Hapus kondisi ketat pada kredits
            $pembayaranKredits = PembayaranKredit::where('giling_id', $giling->id)->get();

            $hutangYangDibayar = $pembayaranKredits->sum('total_hutang');

            $pembayaranKreditsFalse = $petani->kredits;

            $pembayaranKreditsTransaksi = $petani->kredits->filter(function ($kredit) use ($giling) {
                return $kredit->pKredit_id == $giling->id && $kredit->status == true;
            });

            // Ambil total hutang yang sudah dibayar terkait dengan giling_id
            $hutangYangDibayar = $pembayaranKreditsLangsung->where('giling_id', $giling->id)->pluck('total_hutang')->sum();

            // Hitung sisa utang yang belum lunas
            $sisaUtang = $pembayaranKreditsFalse->where('status', false)->sum('jumlah');

            $status = $sisaUtang > 0 ? false : true;
            $sisaUtangFormatted = 'Rp ' . number_format($sisaUtang, 0, ',', '.');

            $data[] = [
                'giling_id' => $giling->id, // Tambahkan ID giling
                'petani_id' => $petani->id, // Tambahkan ID petani
                'petani' => $petani->nama,
                'transaksi' => $pembayaranKreditsTransaksi->count(),
                'sisa_utang' => $sisaUtangFormatted,
                'status' => $status,
                'hutangYangDibayar' => 'Rp ' . number_format($hutangYangDibayar, 0, ',', '.'),
            ];
        }



        $dataHistory = collect()
            ->merge(Petani::withTrashed()->where('id', '!=', 187)->get()->map(function ($item) {
                $changedAttributes = collect($item->getDirty())->keys();

                if ($item->trashed()) {
                    $item->actionType = 'delete';
                } elseif ($item->updated_at > $item->created_at) {
                    $item->actionType = 'update';
                    $item->changedFields = $changedAttributes->toArray();
                } elseif ($item->wasRecentlyCreated) {
                    $item->actionType = 'create';
                } else {
                    $item->actionType = 'create'; // Default fallback
                }
                return $item;
            }))
            ->merge(Kredit::withTrashed()->where('petani_id', '!=', 187)->get()->map(function ($item) {
                $changedAttributes = collect($item->getDirty())->keys();

                if ($item->trashed()) {
                    $item->actionType = 'delete';
                } elseif ($item->updated_at > $item->created_at) {
                    $item->actionType = 'update';
                    $item->changedFields = $changedAttributes->toArray();
                } elseif ($item->wasRecentlyCreated) {
                    $item->actionType = 'create';
                } else {
                    $item->actionType = 'create'; // Default fallback
                }
                return $item;
            }))

            ->merge(KreditNasabahPalu::withTrashed()->get()->map(function ($item) {
                $changedAttributes = collect($item->getDirty())->keys();

                if ($item->trashed()) {
                    $item->actionType = 'delete';
                } elseif ($item->updated_at > $item->created_at) {
                    $item->actionType = 'update';
                    $item->changedFields = $changedAttributes->toArray();
                } elseif ($item->wasRecentlyCreated) {
                    $item->actionType = 'create';
                } else {
                    $item->actionType = 'create'; // Default fallback
                }
                return $item;
            }))

            ->merge(KreditTitipanPetani::withTrashed()->where('petani_id', '!=', 187)->get()->map(function ($item) {
                $changedAttributes = collect($item->getDirty())->keys();

                if ($item->trashed()) {
                    $item->actionType = 'delete';
                } elseif ($item->updated_at > $item->created_at) {
                    $item->actionType = 'update';
                    $item->changedFields = $changedAttributes->toArray();
                } elseif ($item->wasRecentlyCreated) {
                    $item->actionType = 'create';
                } else {
                    $item->actionType = 'create'; // Default fallback
                }
                return $item;
            }))

            ->merge(UtangKeOperator::withTrashed()->where('petani_id', '!=', 187)->get()->map(function ($item) {
                $changedAttributes = collect($item->getDirty())->keys();

                if ($item->trashed()) {
                    $item->actionType = 'delete';
                } elseif ($item->updated_at > $item->created_at) {
                    $item->actionType = 'update';
                    $item->changedFields = $changedAttributes->toArray();
                } elseif ($item->wasRecentlyCreated) {
                    $item->actionType = 'create';
                } else {
                    $item->actionType = 'create'; // Default fallback
                }
                return $item;
            }))


            ->merge(DaftarGiling::withTrashed()
                ->whereHas('giling', function ($query) {
                    $query->where('petani_id', '!=', 187); // Abaikan entri dengan petani_id = 187
                })
                ->get()
                ->map(function ($item) {
                    $changedAttributes = collect($item->getDirty())->keys();

                    if ($item->trashed()) {
                        $item->actionType = 'delete';
                    } elseif (!empty($changedAttributes->toArray()) && $item->wasChanged()) {
                        $item->actionType = 'update';
                        $item->changedFields = $changedAttributes->toArray();
                    } elseif ($item->wasRecentlyCreated) {
                        $item->actionType = 'create';
                    } else {
                        $item->actionType = 'create'; // Default fallback
                    }
                    return $item;
                }))
            // ->merge(PembayaranKredit::withTrashed()->get()->map(function ($item) {
            //     $changedAttributes = collect($item->getDirty())->keys();

            //     if (!empty($changedAttributes->toArray()) && $item->wasChanged()) {
            //         $item->actionType = 'update';
            //         $item->changedFields = $changedAttributes->toArray();
            //     } elseif ($item->wasRecentlyCreated) {
            //         $item->actionType = 'create';
            //     } else {
            //         $item->actionType = 'create'; // Default fallback
            //     }
            //     return $item;
            // }))
            ->merge(RekapDana::withTrashed()->get()->map(function ($item) {
                $changedAttributes = collect($item->getDirty())->keys();

                if (!empty($changedAttributes->toArray()) && $item->wasChanged()) {
                    $item->actionType = 'update';
                    $item->changedFields = $changedAttributes->toArray();
                } elseif ($item->wasRecentlyCreated) {
                    $item->actionType = 'create';
                } else {
                    $item->actionType = 'create'; // Default fallback
                }
                return $item;
            }))
            ->merge(RekapKredit::withTrashed()->get()->map(function ($item) {
                $changedAttributes = collect($item->getDirty())->keys();

                if (!empty($changedAttributes->toArray()) && $item->wasChanged()) {
                    $item->actionType = 'update';
                    $item->changedFields = $changedAttributes->toArray();
                } elseif ($item->wasRecentlyCreated) {
                    $item->actionType = 'create';
                } else {
                    $item->actionType = 'create'; // Default fallback
                }
                return $item;
            }))

            ->merge(RekapDanaTitipanPetani::withTrashed()->get()->map(function ($item) {
                $changedAttributes = collect($item->getDirty())->keys();

                if ($item->trashed()) {
                    $item->actionType = 'delete';
                } elseif ($item->updated_at > $item->created_at) {
                    $item->actionType = 'update';
                    $item->changedFields = $changedAttributes->toArray();
                } elseif ($item->wasRecentlyCreated) {
                    $item->actionType = 'create';
                } else {
                    $item->actionType = 'create'; // Default fallback
                }
                return $item;
            }))

            ->merge(RekapKreditNasabahPalu::withTrashed()->get()->map(function ($item) {
                $changedAttributes = collect($item->getDirty())->keys();

                if ($item->trashed()) {
                    $item->actionType = 'delete';
                } elseif ($item->updated_at > $item->created_at) {
                    $item->actionType = 'update';
                    $item->changedFields = $changedAttributes->toArray();
                } elseif ($item->wasRecentlyCreated) {
                    $item->actionType = 'create';
                } else {
                    $item->actionType = 'create'; // Default fallback
                }
                return $item;
            }))

            ->merge(RekapUtangKeOperator::withTrashed()->get()->map(function ($item) {
                $changedAttributes = collect($item->getDirty())->keys();

                if ($item->trashed()) {
                    $item->actionType = 'delete';
                } elseif ($item->updated_at > $item->created_at) {
                    $item->actionType = 'update';
                    $item->changedFields = $changedAttributes->toArray();
                } elseif ($item->wasRecentlyCreated) {
                    $item->actionType = 'create';
                } else {
                    $item->actionType = 'create'; // Default fallback
                }
                return $item;
            }))

            ->merge(Debit::withTrashed()->get()->map(function ($item) {
                $changedAttributes = collect($item->getDirty())->keys();

                if ($item->trashed()) {
                    $item->actionType = 'delete';
                } elseif (!empty($changedAttributes->toArray()) && $item->wasChanged()) {
                    $item->actionType = 'update';
                    $item->changedFields = $changedAttributes->toArray();
                } elseif ($item->wasRecentlyCreated) {
                    $item->actionType = 'create';
                } else {
                    $item->actionType = 'create'; // Default fallback
                }
                return $item;
            }))
            ->sortByDesc('updated_at')
            ->take(50);

        $histories = $dataHistory->map(function ($history) {
            if ($history instanceof Petani && isset($history->nama) && isset($history->created_at)) {
                $actionText = match ($history->actionType) {
                    'create' => 'Penambahan',
                    'update' => 'Perbaruan',
                    'delete' => 'Penghapusan',
                    default => 'Aktivitas'
                };

                $additionalInfo = $history->actionType === 'update'
                    ? '' . implode(
                        ', ',
                        $history->changedFields
                    ) . ''
                    : '';

                return [
                    'type' => 'Petani',
                    'description' => $actionText . ' petani: ' . $history->nama,
                    'date' => $history->updated_at->format('d F Y H:i:s'),
                ];
            } elseif ($history instanceof Kredit && isset($history->jumlah, $history->petani, $history->created_at)) {
                $actionText = match ($history->actionType) {
                    'create' => 'Penambahan',
                    'update' => 'Perbaruan',
                    'delete' => 'Penghapusan',
                    default => 'Aktivitas'
                };

                $additionalInfo = $history->actionType === 'update'
                    ? '' . implode(
                        ', ',
                        $history->changedFields
                    ) . ''
                    : '';

                return [
                    'type' => 'Kredit',
                    'description' => $actionText . ' Kredit dengan jumlah Rp ' . number_format($history->jumlah, 0, ',', '.') . ' milik petani: ' . $history->petani->nama,
                    'date' => $history->updated_at->format('d F Y H:i:s'),
                ];
            } elseif ($history instanceof KreditNasabahPalu && isset($history->jumlah, $history->nama, $history->created_at)) {
                $actionText = match ($history->actionType) {
                    'create' => 'Penambahan',
                    'update' => 'Perbaruan',
                    'delete' => 'Penghapusan',
                    default => 'Aktivitas'
                };

                $additionalInfo = $history->actionType === 'update'
                    ? '' . implode(
                        ', ',
                        $history->changedFields
                    ) . ''
                    : '';

                return [
                    'type' => 'Kredit',
                    'description' => $actionText . ' Kredit dengan jumlah Rp ' . number_format($history->jumlah, 0, ',', '.') . ' milik Nasabah Palu: ' . $history->nama,
                    'date' => $history->updated_at->format('d F Y H:i:s'),
                ];
            } elseif ($history instanceof KreditTitipanPetani && isset($history->jumlah, $history->petani, $history->created_at)) {
                $actionText = match ($history->actionType) {
                    'create' => 'Penambahan',
                    'update' => 'Perbaruan',
                    'delete' => 'Penghapusan',
                    default => 'Aktivitas'
                };

                $additionalInfo = $history->actionType === 'update'
                    ? '' . implode(
                        ', ',
                        $history->changedFields
                    ) . ''
                    : '';

                return [
                    'type' => 'KreditPlus',
                    'description' => $actionText . ' Dana Titipan dengan jumlah Rp ' . number_format($history->jumlah, 0, ',', '.') . ' milik petani: ' . $history->petani->nama,
                    'date' => $history->updated_at->format('d F Y H:i:s'),
                ];
            } elseif ($history instanceof UtangKeOperator && isset($history->jumlah, $history->petani, $history->created_at)) {
                $actionText = match ($history->actionType) {
                    'create' => 'Penambahan',
                    'update' => 'Perbaruan',
                    'delete' => 'Penghapusan',
                    default => 'Aktivitas'
                };

                $additionalInfo = $history->actionType === 'update'
                    ? '' . implode(
                        ', ',
                        $history->changedFields
                    ) . ''
                    : '';

                return [
                    'type' => 'KreditPlus',
                    'description' => $actionText . ' Utang ke Operator dengan jumlah Rp ' . number_format($history->jumlah, 0, ',', '.') . ' milik petani: ' . $history->petani->nama,
                    'date' => $history->updated_at->format('d F Y H:i:s'),
                ];
            } elseif (
                $history instanceof Debit && isset($history->jumlah, $history->petani, $history->created_at)
            ) {
                $actionText = match ($history->actionType) {
                    'create' => 'Penambahan',
                    'update' => 'Perbaruan',
                    'delete' => 'Penghapusan',
                    default => 'Aktivitas'
                };

                $additionalInfo = $history->actionType === 'update'
                    ? '' . implode(
                        ', ',
                        $history->changedFields
                    ) . ''
                    : '';

                return [
                    'type' => 'Debit',
                    'description' => $actionText . ' Debit dengan jumlah Rp ' . number_format($history->jumlah, 0, ',', '.') . ' milik petani: ' . $history->petani->nama . $additionalInfo,
                    'date' => $history->updated_at->format('d F Y H:i:s'),
                ];
            } elseif ($history instanceof RekapDana && isset($history->rekapan_dana, $history->created_at)) {
                $actionText = match ($history->actionType) {
                    'create' => 'Pembuatan',
                    'update' => 'Pembuatan',
                    'delete' => 'Penghapusan',
                    default => 'Aktivitas'
                };

                $additionalInfo = $history->actionType === 'update'
                    ? '' . implode(
                        ', ',
                        $history->changedFields
                    ) . ''
                    : '';

                return [
                    'type' => 'RekapDana',
                    'description' => $actionText . ' Rekapan Dana Rp ' . number_format($history->rekapan_dana, 0, ',', '.') . $additionalInfo,
                    'date' => $history->updated_at->format('d F Y H:i:s'),
                ];
            } elseif ($history instanceof RekapDanaTitipanPetani && isset($history->rekapan_dana_titipan_petani, $history->created_at)) {
                $actionText = match ($history->actionType) {
                    'create' => 'Pembuatan',
                    'update' => 'Pembuatan',
                    'delete' => 'Penghapusan',
                    default => 'Aktivitas'
                };

                $additionalInfo = $history->actionType === 'update'
                    ? '' . implode(
                        ', ',
                        $history->changedFields
                    ) . ''
                    : '';

                return [
                    'type' => 'RekapDana',
                    'description' => $actionText . ' Rekapan Dana Titipan Petani Rp ' . number_format($history->rekapan_dana_titipan_petani, 0, ',', '.') . $additionalInfo,
                    'date' => $history->updated_at->format('d F Y H:i:s'),
                ];
            } elseif ($history instanceof RekapKreditNasabahPalu && isset($history->rekapan_kredit_nasabah_palu, $history->created_at)) {
                $actionText = match ($history->actionType) {
                    'create' => 'Pembuatan',
                    'update' => 'Pembuatan',
                    'delete' => 'Penghapusan',
                    default => 'Aktivitas'
                };

                $additionalInfo = $history->actionType === 'update'
                    ? '' . implode(
                        ', ',
                        $history->changedFields
                    ) . ''
                    : '';

                return [
                    'type' => 'RekapDana',
                    'description' => $actionText . ' Rekapan Kredit Nasabah Palu Rp ' . number_format($history->rekapan_kredit_nasabah_palu, 0, ',', '.') . $additionalInfo,
                    'date' => $history->updated_at->format('d F Y H:i:s'),
                ];
            } elseif ($history instanceof RekapUtangKeOperator && isset($history->rekapan_utang_ke_operator, $history->created_at)) {
                $actionText = match ($history->actionType) {
                    'create' => 'Pembuatan',
                    'update' => 'Pembuatan',
                    'delete' => 'Penghapusan',
                    default => 'Aktivitas'
                };

                $additionalInfo = $history->actionType === 'update'
                    ? '' . implode(
                        ', ',
                        $history->changedFields
                    ) . ''
                    : '';

                return [
                    'type' => 'RekapDana',
                    'description' => $actionText . ' Rekapan Utang Ke Operator Rp ' . number_format($history->rekapan_utang_ke_operator, 0, ',', '.') . $additionalInfo,
                    'date' => $history->updated_at->format('d F Y H:i:s'),
                ];
            } elseif ($history instanceof RekapKredit && isset($history->rekapan_kredit, $history->created_at)) {
                $actionText = match ($history->actionType) {
                    'create' => 'Pembuatan',
                    'update' => 'Pembuatan',
                    'delete' => 'Penghapusan',
                    default => 'Aktivitas'
                };

                $additionalInfo = $history->actionType === 'update'
                    ? '' . implode(
                        ', ',
                        $history->changedFields
                    ) . ''
                    : '';

                return [
                    'type' => 'RekapKredit',
                    'description' => $actionText . ' Rekapan Kredit Rp ' . number_format($history->rekapan_kredit, 0, ',', '.') . $additionalInfo,
                    'date' => $history->updated_at->format('d F Y H:i:s'),
                ];
            } elseif ($history instanceof DaftarGiling && isset($history->dana_penerima, $history->petani, $history->created_at)) {
                $actionText = match ($history->actionType) {
                    'create' => 'Pembuatan',
                    'update' => 'Pembaruan',
                    'delete' => 'Penghapusan',
                    default => 'Aktivitas'
                };

                $additionalInfo = $history->actionType === 'update'
                    ? '' . implode(
                        ', ',
                        $history->changedFields
                    ) . ''
                    : '';

                return [
                    'type' => 'DaftarGiling',
                    'description' => $actionText . ' Nota Giling dengan Sisa Dana Rp ' . number_format($history->dana_penerima, 0, ',', '.') . ' milik petani: ' . $history->petani->nama . $additionalInfo,
                    'date' => $history->updated_at->format('d F Y H:i:s'),
                ];
            }
        })->filter()->toArray();





        return view('dashboard', compact('totalkreditsTitipanPetaniBelumLunas', 'totalkreditsOperatorBelumLunas', 'totalkreditsNasabahPaluBelumLunas', 'totalHutangPerbulan', 'sumPendapatanDariBunga', 'sumTotalHutang', 'pendapatanDariBungaTotalPerbulan', 'dataTotalHutang', 'dataTotalHutangPlusBunga', 'dataPendapatanDariBunga', 'histories', 'data', 'totalKeseluruhanBulanIniOngkosGiling', 'pendapatanBerasTerjualTotal', 'pendapatanBerasTerjualTotalPerBulan', 'totalPetani', 'totalKreditBelumLunas', 'jumlahPetaniBelumLunas', 'totalBerasBersih', 'dataOngkosGiling', 'dataBerasBersih', 'dataPendapatanTerjual', 'monthLabels', 'totalBerasBersihBulanIni', 'totalKeseluruhanOngkosGiling'));


        // $currentYear = Carbon::now()->year;

        // // Fetch data for ongkos_giling and total_hutang grouped by month for the current year
        // $monthlyData = DaftarGiling::select(
        //     DB::raw('MONTH(created_at) as month'),
        //     DB::raw('SUM(ongkos_giling) as total_ongkos_giling'),
        //     DB::raw('SUM(total_hutang) as total_hutang')
        // )
        //     ->whereYear('created_at', $currentYear)
        //     ->groupBy('month')
        //     ->get()
        //     ->keyBy('month');

        // // Prepare data for all 12 months
        // $labels = [];
        // $ongkosGilingData = [];
        // $totalHutangData = [];

        // for ($month = 1; $month <= 12; $month++) {
        //     $labels[] = date('M', mktime(0, 0, 0, $month, 1));
        //     $ongkosGilingData[] = $monthlyData->get($month)->total_ongkos_giling ?? 0;
        //     $totalHutangData[] = $monthlyData->get($month)->total_hutang ?? 0;
        // }

        // // Convert data to JSON strings
        // $labelsJson = json_encode($labels);
        // $ongkosGilingDataJson = json_encode($ongkosGilingData);
        // $totalHutangDataJson = json_encode($totalHutangData);

        // // Encode the JSON strings again to ensure they are properly escaped for JavaScript
        // $labelsJson = json_encode($labelsJson);
        // $ongkosGilingDataJson = json_encode($ongkosGilingDataJson);
        // $totalHutangDataJson = json_encode($totalHutangDataJson);

    }
}
