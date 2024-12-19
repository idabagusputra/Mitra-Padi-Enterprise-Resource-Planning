@extends('layouts.user_type.auth')

@section('content')

<style>
    /* .timeline-block::after,
    .timeline-content::after,
    .timeline-step::after {
        content: none;
    }

    .timeline-block::after,
    .timeline-content::after,
    .timeline-step::after,
    .timeline-step>span::after {
        content: none;
    } */

    @media (max-width: 576px) {
        .container .row {
            display: flex;
            flex-wrap: wrap;
            /* Izinkan wrapping pada baris */
            gap: 1rem;
            /* Ruang antar kolom */
            align-items: flex-start;
        }

        .container .col-3 {
            flex: 1 1 calc(50% - 1rem);
            /* Setengah lebar dan kurangi margin untuk ruang antar kolom */
            padding: 0;
            margin-bottom: 0;
            border-radius: 0.5rem;
            background-color: #fff;
        }

        .container .d-flex {
            display: flex;
            align-items: center;
        }

        .container .icon {
            font-size: 12px;
            margin-right: 10px;
        }

        .container p.text-xs,
        .container h4 {
            text-align: left;
            margin: 0;
        }

        .col-lg-7.mt-0 {
            padding-top: 24px !important;
            /* Sesuaikan ukuran padding/margin yang diinginkan */
        }

        .row {
            padding-top: 0 !important;
            margin-top: 0 !important;
            margin-bottom: 0px !important;
            /* Sesuaikan ukuran padding/margin yang diinginkan untuk horizontal */
        }

        .col-lg-5.mb-lg-0 {
            margin-bottom: 24px !important;
            /* Sesuaikan ukuran padding/margin yang diinginkan */
        }

        /* .timeline-block::after,
        .timeline-content::after,
        .timeline-step::after {
            content: none;
        }

        .timeline-block::after,
        .timeline-content::after,
        .timeline-step::after,
        .timeline-step>span::after {
            content: none;
        } */

        .mobile2 {
            margin-top: 24px !important;
        }

        .mobile {
            margin-top: 24px !important;
            margin-bottom: 24px !important;
        }
    }

    /* Landscape Mode (Desktop/Tablet Horizontal) */
    @media (max-width: 1920px) and (orientation: landscape) {
        .horizontal {
            padding-top: 0 !important;
            margin-top: 0 !important;
            /* Sesuaikan ukuran padding/margin yang diinginkan untuk horizontal */
        }

        /* .timeline-block::after,
        .timeline-content::after,
        .timeline-step::after {
            content: none;
        }

        .timeline-block::after,
        .timeline-content::after,
        .timeline-step::after,
        .timeline-step>span::after {
            content: none;
        } */

        .row {
            padding-top: 0 !important;
            margin-top: 0 !important;
            margin-bottom: 0px !important;
            /* Sesuaikan ukuran padding/margin yang diinginkan untuk horizontal */
        }

        .mobile2 {
            margin-bottom: 24px !important;
        }

        .mobile {

            margin-bottom: 24px !important;
        }
    }

    .number-fit {
        font-size: 1rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        width: 100%;
    }

    .text-fit {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        width: 100%;
    }
</style>

<div class="row mb-4">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold text-fit">Total Petani</p>
                            <h5 class="font-weight-bolder mb-0 number-fit">
                                {{ $totalPetani }}
                                <span class="text-success text-sm font-weight-bolder">Orang</span>
                            </h5>

                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                            <i class="ni bi bi-person-lines-fill text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold text-fit">Total Petani Berutang</p>
                            <h5 class="font-weight-bolder mb-0 number-fit">
                                {{ $jumlahPetaniBelumLunas }}
                                <span class="text-success text-sm font-weight-bolder">Orang</span>
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                            <i class="bi bi-person-fill-x text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold text-fit">Total Kredit Petani</p>
                            <h5 class="font-weight-bolder mb-0 number-fit">
                                <!-- Rp {{ number_format($totalKreditBelumLunas, 2, ',', '.') }} -->
                                Rp {{ number_format($totalKreditBelumLunas) }}
                                <span class="text-success text-sm font-weight-bolder">
                                    @if ($totalKreditBelumLunas >= 1_000_000_000)
                                    Miliar
                                    @elseif ($totalKreditBelumLunas >= 1_000_000)
                                    Juta
                                    @elseif ($totalKreditBelumLunas >= 1_000)
                                    Ribu
                                    @else
                                    Tidak ada label khusus
                                    @endif
                                </span>
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                            <i class="bi bi-cash-coin text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold text-fit">Beras Petani Terjual Bulan Ini</p>
                            <h5 class="font-weight-bolder mb-0 number-fit">
                                {{ number_format($totalBerasBersihBulanIni, 2, '.', ',') }}
                                <span class="text-success text-sm font-weight-bolder">Kg</span>
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                            <i class="bi bi-cart-check-fill text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<!-- <div class="row mt-4">
    <div class="col-lg-7 mb-lg-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="d-flex flex-column h-100">
                            <p class="mb-1 pt-2 text-bold">Built by developers</p>
                            <h5 class="font-weight-bolder">Soft UI Dashboard</h5>
                            <p class="mb-5">From colors, cards, typography to complex elements, you will find the full documentation.</p>
                            <a class="text-body text-sm font-weight-bold mb-0 icon-move-right mt-auto" href="javascript:;">
                                Read More
                                <i class="fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-5 ms-auto text-center mt-5 mt-lg-0">
                        <div class="bg-gradient-primary border-radius-lg h-100">
                            <img src="../assets/img/shapes/waves-white.svg" class="position-absolute h-100 w-50 top-0 d-lg-block d-none" alt="waves">
                            <div class="position-relative d-flex align-items-center justify-content-center h-100">
                                <img class="w-100 position-relative z-index-2 pt-4" src="../assets/img/illustrations/rocket-white.png" alt="rocket">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card h-100 p-3">
            <div class="overflow-hidden position-relative border-radius-lg bg-cover h-100" style="background-image: url('../assets/img/ivancik.jpg');">
                <span class="mask bg-gradient-dark"></span>
                <div class="card-body position-relative z-index-1 d-flex flex-column h-100 p-3">
                    <h5 class="text-white font-weight-bolder mb-4 pt-2">Work with the rockets</h5>
                    <p class="text-white">Wealth creation is an evolutionarily recent positive-sum game. It is all about who take the opportunity first.</p>
                    <a class="text-white text-sm font-weight-bold mb-0 icon-move-right mt-auto" href="javascript:;">
                        Read More
                        <i class="fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div> -->



<div class="row mt-4 horizontal">


    <div class=" col-lg-6 mt-0 mobile2">
        <div class="card z-index-2">
            <div class="card-header pb-0 mb-0">
                <h6 class="ms-0 mt-2 mb-0">Grafik Laporan Hasil Giling (Kg)</h6>
                <p class="ms-0 text-sm mb-0 pb-1"> <span class="font-weight-bolder">Per Bulan</span> </p>
                <!-- <p class="text-sm">
                    <i class="fa fa-arrow-up text-success"></i>
                    <span class="font-weight-bold">4% more</span> in 2021
                </p> -->
            </div>
            <div class="card-body p-3  pt-0">
                <div class="chart">
                    <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>


    <div class="col-lg-6 mb-lg-0 mobile">
        <div class="card z-index-2">
            <div class="card-body p-3">
                <h6 class="ms-0 mt-2 mb-0">Grafik Laporan Pendapatan (Rp)</h6>
                <p class="text-sm ms-0"> <span class="font-weight-bolder">Per Bulan</span> </p>
                <div class="bg-gradient-dark border-radius-lg py-3 pe-1 mb-3">
                    <div class="chart">
                        <canvas id="chart-bars" class="chart-canvas" height="115"></canvas>
                    </div>
                </div>
                <div class="container border-radius-lg justify-content-between">
                    <div class="row justify-content-between">
                        <div class="col-3 py-3 ps-0 mobile">
                            <div class="d-flex mb-2">
                                <div class="icon icon-shape bi bi-building-fill-check icon-xxs shadow border-radius-sm bg-gradient-primary text-center me-2 d-flex align-items-center justify-content-center" style="color: white; font-size: 10px;">


                                </div>
                                <p class="text-xs mt-1 mb-0 font-weight-bold">Total Ongkos-G</p>
                            </div>
                            <h4 class="font-weight-bolder text-lg">{{ number_format($totalKeseluruhanOngkosGiling, 2) }} Kg</h4>
                            <!-- <div class="progress w-75">
                                <div class="progress-bar bg-dark w-60" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                            </div> -->
                        </div>
                        <div class="col-3 py-3 ps-0 mobile">
                            <div class="d-flex mb-2">
                                <div class="icon icon-shape bi bi-building-fill-down icon-xxs shadow border-radius-sm bg-gradient-info text-center me-2 d-flex align-items-center justify-content-center" style="color: white; font-size: 10px;">

                                </div>
                                <p class="text-xs mt-1 mb-0 font-weight-bold">Ongkos-G Bln Ini</p>
                            </div>
                            <h4 class="font-weight-bolder text-lg">{{ number_format($totalKeseluruhanBulanIniOngkosGiling, 2) }} Kg</h4>
                            <!-- <div class="progress w-75">
                                <div class="progress-bar bg-dark w-90" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                            </div> -->
                        </div>


                        <div class="col-3 py-3 ps-0 mobile">
                            <div class="d-flex mb-2">
                                <div class="icon icon-shape bi bi-currency-dollar icon-xxs shadow border-radius-sm bg-gradient-warning text-center me-2 d-flex align-items-center justify-content-center" style="color: white; font-size: 10px;">

                                </div>
                                <p class="text-xs mt-1 mb-0 font-weight-bold">Total Pendapatan</p>
                            </div>
                            <h4 class="font-weight-bolder text-lg">Rp {{ number_format($pendapatanBerasTerjualTotal, 2) }}</h4>
                            <!-- <div class="progress w-75">
                                <div class="progress-bar bg-dark w-30" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                            </div> -->
                        </div>


                        <div class="col-3 py-3 ps-0 mobile">
                            <div class="d-flex mb-2">
                                <div class="icon icon-shape bi bi-cash-coin icon-xxs shadow border-radius-sm bg-gradient-danger text-center me-2 d-flex align-items-center justify-content-center" style="color: white; font-size: 10px;">

                                </div>
                                <p class="text-xs mt-1 mb-0 font-weight-bold">Total-P Bln Ini</p>
                            </div>
                            <h4 class="font-weight-bolder text-lg">Rp {{ number_format($pendapatanBerasTerjualTotalPerBulan, 2) }}</h4>

                            <!-- <div class="progress w-75">
                                <div class="progress-bar bg-dark w-50" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div> -->
                        </div>



                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row my-4">
    <div class="col-lg-8 col-md-6 mb-md-0 mb-4">
        <div class="card">
            <div class="card-header pb-0">
                <div class="row">
                    <div class="col-lg-6 col-12">
                        <h6>Update Utang Lunas</h6>
                        <p class="text-sm ms-0"> <span class="font-weight-bolder">Terbaru</span> </p>
                    </div>
                    <div class="col-lg-6 col-5 my-auto text-end">
                        <div class="dropdown float-lg-end pe-4">
                            <a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-ellipsis-v text-secondary"></i>
                            </a>
                            <ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable">
                                <li><a class="dropdown-item border-radius-md" href="javascript:;">Action</a></li>
                                <li><a class="dropdown-item border-radius-md" href="javascript:;">Another action</a></li>
                                <li><a class="dropdown-item border-radius-md" href="javascript:;">Something else here</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>

                                <th class="text-uppercase text-primary font-weight-bolder ps-4" style="font-size: 0.85rem;">Petani</th>
                                <th class="text-uppercase text-primary font-weight-bolder ps-2" style="font-size: 0.85rem;">Jumlah Utang</th>
                                <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Sisa Utang</th>
                                <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Transaksi</th>
                                <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                            <tr>

                                <td class="ps-4">{{ $item['petani'] }}</td>
                                <td>{{ $item['hutangYangDibayar'] }}</td>
                                <td class="text-center">{{ $item['sisa_utang'] }}</td>
                                <td class="text-center"> {{ $item['transaksi'] }}</td>
                                <td class="text-center">
                                    <span class="badge badge-sm bg-gradient-{{ $item['status'] ? 'success' : 'warning' }}">
                                        {{ $item['status'] ? 'Lunas' : 'Belum Lunas' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>


    <div class="col-lg-4 col-md-6">
        <div class="card h-100">
            <div class="card-header pb-0">
                <h6>History</h6>
                <p class="text-sm ms-0"> <span class="font-weight-bolder">Terbaru</span> </p>
            </div>
            <div class="card-body p-3">
                <div class="timeline timeline-one-side">

                    <!-- @foreach($histories as $history)
                    <div class="timeline-block">
                        <span class="timeline-step">
                            @if(isset($history['type']))
                            @switch($history['type'])
                            @case('Petani')
                            <i class="bi bi-person-fill-add text-info text-gradient"></i>
                            @break
                            @case('Kredit')
                            <i class="bi bi-currency-dollar text-danger text-gradient"></i>
                            @break
                            @case('Debit')
                            <i class="bi bi-currency-dollar text-success text-gradient"></i>
                            @break
                            @case('RekapDana')
                            <i class="bi bi-file-earmark-check-fill text-warning text-gradient"></i>
                            @break
                            @case('RekapKredit')
                            <i class="bi bi-file-earmark-check-fill text-warning text-gradient"></i>
                            @break
                            @case('DaftarGiling')
                            <i class="bi bi-cart-plus-fill text-success text-gradient"></i>
                            @break
                            @case('PembayaranKredit')
                            <i class="ni ni-credit-card text-warning text-gradient"></i>
                            @break
                            @endswitch
                            @endif
                        </span>
                        <div class="timeline-content">
                            @if(isset($history['description']) && isset($history['date']))
                            <h6 class="text-dark text-sm font-weight-bold mb-0">{{ $history['description'] }}</h6>
                            <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">{{ $history['date'] }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach -->


                    @foreach($histories as $history)
                    <div class="timeline-block mb-3">
                        <span class="timeline-step">
                            @if(isset($history['type']))
                            @switch($history['type'])
                            @case('Petani')
                            <i class="bi bi-person-fill-add text-info text-gradient"></i>
                            @break
                            @case('Kredit')
                            <i class="bi bi-currency-dollar text-danger text-gradient"></i>
                            @break
                            @case('Debit')
                            <i class="bi bi-currency-dollar text-success text-gradient"></i>
                            @break
                            @case('RekapDana')
                            <i class="bi bi-file-earmark-check-fill text-warning text-gradient"></i>
                            @break
                            @case('RekapKredit')
                            <i class="bi bi-file-earmark-check-fill text-warning text-gradient"></i>
                            @break
                            @case('DaftarGiling')
                            <i class="bi bi-cart-plus-fill text-success text-gradient"></i>
                            @break
                            @case('PembayaranKredit')
                            <i class="ni ni-credit-card text-warning text-gradient"></i>
                            @break
                            @endswitch
                            @endif
                        </span>
                        <div class="timeline-content">
                            @if(isset($history['description']) && isset($history['date']))
                            @php
                            $descriptionParts = explode('petani: ', $history['description'], 2);
                            $beforePetani = $descriptionParts[0];
                            $remainingText = isset($descriptionParts[1]) ? $descriptionParts[1] : '';
                            $firstWord = explode(' ', $beforePetani, 2)[0]; // Ambil kata pertama dari sebelum 'petani:'
                            $nameParts = explode(': ', $remainingText, 2);
                            $farmerName = isset($nameParts[0]) ? $nameParts[0] : '';
                            $remainingDetails = isset($nameParts[1]) ? $nameParts[1] : '';
                            @endphp
                            <h6 class="text-dark text-sm font-weight-bold mb-0">
                                <strong>{{ $firstWord }}</strong>
                                @if($farmerName)
                                {{ str_replace($firstWord, '', $beforePetani) }} petani: <strong>{{ $farmerName }}</strong>
                                <span style="font-weight: normal;">{{ $remainingDetails }}</span>
                                @else
                                {{ str_replace($firstWord, '', $beforePetani) }}<strong>{{ $farmerName }}</strong>
                                @endif
                            </h6>

                            <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">{{ $history['date'] }}</p>
                            @endif
                        </div>






                    </div>
                    @endforeach



                </div>
            </div>
        </div>
    </div>






</div>

@endsection
@push('dashboard')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<canvas id="ctx2"></canvas>
<script>
    window.onload = function() {

        var dataBerasBersih = <?php echo json_encode($dataBerasBersih); ?>;
        var dataPendapatanTerjual = <?php echo json_encode($dataPendapatanTerjual); ?>;
        var dataOngkosGiling = <?php echo json_encode($dataOngkosGiling); ?>;
        var monthLabels = <?php echo json_encode($monthLabels); ?>;




        var ctx = document.getElementById("chart-bars").getContext("2d");

        new Chart(ctx, {
            type: "bar",
            data: {
                labels: monthLabels,
                datasets: [{
                    label: "Hasil Penjualan (Rp)",
                    tension: 0.4,
                    borderWidth: 0,
                    borderRadius: 4,
                    borderSkipped: false,
                    backgroundColor: "#fff",
                    data: dataPendapatanTerjual,
                    maxBarThickness: 6
                }, ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                        },
                        ticks: {
                            suggestedMin: 0,
                            suggestedMax: 500,
                            beginAtZero: true,
                            padding: 15,
                            font: {
                                size: 14,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                            color: "#fff"
                        },
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false
                        },
                        ticks: {
                            display: false
                        },
                    },
                },
            },
        });


        var ctx2 = document.getElementById("chart-line").getContext("2d");

        var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);

        gradientStroke1.addColorStop(1, 'rgba(203,12,159,0.2)');
        gradientStroke1.addColorStop(0.2, 'rgba(72,72,176,0.0)');
        gradientStroke1.addColorStop(0, 'rgba(203,12,159,0)'); //purple colors

        var gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);

        gradientStroke2.addColorStop(1, 'rgba(20,23,39,0.2)');
        gradientStroke2.addColorStop(0.2, 'rgba(72,72,176,0.0)');
        gradientStroke2.addColorStop(0, 'rgba(20,23,39,0)'); //purple colors




        new Chart(ctx2, {
            type: "line",
            data: {
                labels: monthLabels, // Menambahkan label bulan mulai dari Oktober hingga Januari
                datasets: [{
                        label: "Beras Bersih Petani (Kg)",
                        tension: 0.4,
                        borderWidth: 0,
                        pointRadius: 0,
                        borderColor: "#cb0c9f",
                        borderWidth: 3,
                        backgroundColor: gradientStroke1,
                        fill: true,
                        data: dataBerasBersih,
                        maxBarThickness: 6

                    },
                    {
                        label: "Ongkos Gilingan (Kg)",
                        tension: 0.4,
                        borderWidth: 0,
                        pointRadius: 0,
                        borderColor: "#3A416F",
                        borderWidth: 3,
                        backgroundColor: gradientStroke2,
                        fill: true,
                        data: dataOngkosGiling,
                        maxBarThickness: 6
                    },
                    // {
                    //     label: "Pendapatan Beras ",
                    //     tension: 0.4,
                    //     borderWidth: 0,
                    //     pointRadius: 0,
                    //     borderColor: "#3A416F",
                    //     borderWidth: 3,
                    //     backgroundColor: gradientStroke2,
                    //     fill: true,
                    //     data: dataPendapatanTerjual,
                    //     maxBarThickness: 6
                    // },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            padding: 10,
                            color: '#b2b9bf',
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            color: '#b2b9bf',
                            padding: 20,
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                },
            },
        });
    }
</script>
@endpush