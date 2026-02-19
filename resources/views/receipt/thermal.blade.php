<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            font-family: sans-serif;
            font-size: 10pt;
            margin: 0;
            padding: 0;
        }

        .receipt {
            width: 80mm;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .title {
            font-size: 17pt;
            font-weight: bold;
        }

        .info-item {
            margin-bottom: 3px;
        }

        .info {
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th,
        td {
            padding: 5px 2px;
            text-align: left;
        }

        .calculation-row td {
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .calculation-row-top td {
            border-top: 1px dashed #000;
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .total {
            font-size: 14pt;
            font-weight: bold;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            margin-bottom: 10px;
        }

        .footer {
            text-align: center;
            margin-top: 10px;
            font-style: italic;
        }

        .bold-border-top {
            border-top: 2px solid #000;

            font-weight: bold;
        }

        .bold-border-top-top {
            border-top: 2px solid #000;


        }

        .bold-border-bottom {
            border-bottom: 2px solid #000;
        }

        .small-text {
            font-size: 10pt;
        }

        /* New class for bold text */
        .bold {
            font-weight: bold;
        }

        .subbold {
            font-size: 11pt;
        }

        .header img {
            max-width: 100%;
            height: auto;
        }

        .footer img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>

<body>
    <div class="receipt">
        <div class="header-container">
            <div class="header">
                <img src="{{ asset('logo_gilingan.png') }}" alt="Putra Manuaba" class="header-logo">
                <div class="header-text">

                    <div></div>
                </div>
            </div>
        </div>

        <table>
            <tr class="bold-border-top">
                <td>Informasi</td>
                <td></td>
                <td></td>
            <tr>
            <tr class="bold-border-top-top calculation-row">
                <td class="small-text">Nama Petani</td>
                <td>:</td>
                <td>{{ $giling->petani->nama }}</td>
            </tr>
            <tr class="calculation-row">
                <td class="small-text">Nomor Nota</td>
                <td>:</td>
                <td>{{ $giling->id }}</td>
            </tr>
            <tr class="calculation-row">
                <td>Tanggal Gabah </td>
                <td>:</td>
                <td>{{ $giling->created_at->addHours(0)->format('d/m/Y') }}</td>
            </tr>
            <tr class="calculation-row">
                <td>Tanggal Nota </td>
                <td>:</td>
                <td>{{ $daftarGiling->created_at->addHours(0)->format('d/m/Y') }} ({{ $daftarGiling->created_at->addHours(0)->format('H:i:s') }})</td>
            </tr>
        </table>

        <table>
            <div></div>
        </table>






        <table>


            @php
            // Fungsi untuk menentukan jumlah desimal pada angka
            function getDecimalPlaces($value) {
            $value = (string)$value;
            if (strpos($value, '.') !== false) {
            return strlen(substr(strrchr($value, '.'), 1));
            }
            return 0; // Tidak ada desimal
            }

            // Mendapatkan jumlah desimal untuk kedua nilai
            $decimalPlacesGilingKotor = getDecimalPlaces($giling->giling_kotor);
            $decimalPlacesBerasJual = getDecimalPlaces($giling->calculateBerasBersih() - $giling->pulang);
            $decimalPlacesBerasBersih = getDecimalPlaces($giling->calculateBerasBersih());
            $decimalPlacesKonga = getDecimalPlaces($giling->jumlah_konga);
            $decimalPlacesMenir = getDecimalPlaces($giling->jumlah_menir);
            $decimalPlacesBuruhJemur = getDecimalPlaces($giling->jemur);



            @endphp



            <tr class="bold-border-top">
                <td>Kalkulasi</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>


            </tr>
            <tr class="bold-border-top-top calculation-row">
                <td class="small-text"> Giling Kotor</td>
                <td>:</td>
                <td>{{ number_format($giling->giling_kotor, $decimalPlacesGilingKotor) }} Kg</td>
                <td></td>
                <td></td>


            </tr>
            <tr class="calculation-row">
                <td class="small-text">Ongkos Giling</td>
                <td>:</td>
                <td>{{ number_format($giling->giling_kotor, $decimalPlacesGilingKotor) }} × {{ number_format($giling->biaya_giling) }}%</td>
                <td>=</td>
                <td>{{ number_format($giling->calculateBiayaGiling(), 2) }} Kg</td>



            </tr>
            <tr class="calculation-row">
                <td class="small-text">Pinjam</td>
                <td>:</td>
                <td>{{ number_format($giling->pinjam) }} Kg</td>
                <td></td>
                <td></td>


            </tr>
            <tr class="calculation-row">
                <td class="small-text">Beras Bersih</td>
                <td>:</td>
                <td>{{ number_format($giling->calculateBerasBersih(), $decimalPlacesBerasBersih) }} Kg</td>
                <td></td>
                <td></td>



            </tr>

            <tr class="calculation-row">
                <td class="small-text">Pulang</td>
                <td>:</td>
                <td>{{ number_format($giling->pulang) }} Kg</td>
                <td></td>
                <td></td>


            </tr>

            <tr class="calculation-row">
                <td class="small-text">Beras Jual</td>
                <td>:</td>
                <td>{{ number_format($giling->calculateBerasBersih() - $giling->pulang, $decimalPlacesBerasJual) }} Kg × Rp {{ number_format($giling->harga_jual) }}</td>
                <td>=</td>
                <td class="bold">Rp {{ number_format($giling->calculateBerasJual()) }}</td>



            </tr>
            <tr class="calculation-row">
                <td class="small-text">Buruh Giling</td>
                <td>:</td>
                <td>{{ number_format($giling->giling_kotor, $decimalPlacesGilingKotor) }} × Rp {{ number_format($giling->biaya_buruh_giling) }}</td>
                <td>=</td>
                <td class="bold">Rp {{ number_format($giling->calculateBuruhGiling()) }}</td>



            </tr>
            <tr class="calculation-row">
                <td class="small-text">Buruh Jemur</td>
                <td>:</td>
                <td>{{ number_format($giling->jemur,  $decimalPlacesBuruhJemur) }} × Rp {{ number_format($giling->biaya_buruh_jemur) }}</td>
                <td>=</td>
                <td class="bold">Rp {{ number_format($giling->calculateBuruhJemur()) }}</td>



            </tr>
            <tr class="calculation-row">
                <td class="small-text">Jual Konga</td>
                <td>:</td>
                <td>{{ number_format($giling->jumlah_konga, $decimalPlacesKonga) }} × Rp {{ number_format($giling->harga_konga) }}</td>
                <td>=</td>
                <td class="bold">Rp {{ number_format($giling->calculateJualKonga()) }}</td>



            </tr>
            <tr class="calculation-row">
                <td class="small-text">Jual Menir</td>
                <td>:</td>
                <td>{{ number_format($giling->jumlah_menir, $decimalPlacesMenir)}} × Rp {{ number_format($giling->harga_menir) }}</td>
                <td>=</td>
                <td class="bold">Rp {{ number_format($giling->calculateJualMenir()) }}</td>



            </tr>
        </table>

        <table>
            <div></div>
        </table>

        <table>
            <tr class="bold-border-top">
                <th>Ambil</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Total</th>
            </tr>

            @php
            if (!function_exists('getDecimalPlacesP')) {
            function getDecimalPlacesP($value) {
            if (is_numeric($value)) {
            return strpos($value, '.') !== false ? strlen(substr(strrchr($value, '.'), 1)) : 0;
            }
            return 0; // Tidak ada desimal
            }
            }
            @endphp

            @if($giling->pengambilans->isNotEmpty())



            @foreach($giling->pengambilans as $index => $pengambilan)

            @php
            $decimalPlacesPJumlah = getDecimalPlacesP($pengambilan->jumlah);
            $decimalPlacesPHarga = getDecimalPlacesP($pengambilan->harga);
            $decimalPlacesPTHarga = getDecimalPlacesP($pengambilan->jumlah * $pengambilan->harga);
            @endphp

            <tr class="calculation-row">
                <td>{{ $index + 1 }}. {{ $pengambilan->keterangan }}</td>
                <td>{{ number_format($pengambilan->jumlah, $decimalPlacesPJumlah) }}</td>
                <td>Rp {{ number_format($pengambilan->harga, $decimalPlacesPHarga) }}</td>
                <td class="bold">Rp {{ number_format($pengambilan->jumlah * $pengambilan->harga, $decimalPlacesPTHarga) }}</td>
            </tr>
            @endforeach
            @else
<tr class="calculation-row">
    <td colspan="4" style="text-align: justify; text-align-last: justify;">TIDAK ADA PENGAMBILAN (KARUNG PRIBADI)</td>
</tr>
@endif
        </table>

        <table>
            <div></div>
        </table>

        <table>
            <thead>
                <tr class="bold-border-top">
                    <th>Utang</th>
                    <th>Jumlah</th>
                    <th>Bunga</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                // Ambil data yang diperlukan
                $filteredKredits = $giling->petani->kredits->where('status', false)->sortBy('tanggal');
                $pembayaranKredit = $giling->pembayaranKredits->first();
                $bungaRate = $pembayaranKredit ? $pembayaranKredit->bunga : 0;
                @endphp

                @forelse ($filteredKredits as $index => $kredit)
                @php
                $lamaBulan = $pembayaranKredit ? $pembayaranKredit->hitungLamaHutangBulan($kredit->tanggal) : 0;
                $bunga = $kredit->jumlah * ($bungaRate / 100) * $lamaBulan;
                $totalHutang = $kredit->jumlah + $bunga;
                @endphp
                <tr class="calculation-row">
                    <td>{{ $loop->iteration }}. {{ \Carbon\Carbon::parse($kredit->tanggal)->format('d/m/Y') }}</td>
                    <td>Rp {{ number_format($kredit->jumlah) }}</td>
                    <td>{{ $lamaBulan }} Bln ({{ floor($bungaRate) }}%)</td>
                    <td class="bold">Rp {{ number_format($totalHutang) }}</td>
                </tr>

                @if($loop->last && $bungaRate > 0)

<tr class="calculation-row">
    <td colspan="4" style="text-align: justify; text-align-last: justify;">BUNGA TERHITUNG DARI TANGGAL UTANG SAMPAI TANGGAL GABAH MASUK: <strong>{{ $giling->created_at->addHours(0)->format('d - m - Y') }}</strong></td>
</tr>
    @endif

                @empty
                <tr class="calculation-row">
                    <td colspan="4">Tidak ada data hutang</td>
                </tr>
                @endforelse
            </tbody>
        </table>


        <table>
            <div></div>
        </table>

        <table>
            @foreach($giling->daftarGiling as $index => $daftar)

            <!-- <tr class="total">
                <td>Dana Giling</td>
                <td>:</td>
                <td>Rp {{ number_format($daftar->dana_jual_beras - $daftar->total_pengambilan , 2) }}</td>
            </tr> -->

            <tr class="total">
                <td>Total Ambil</td>
                <td>:</td>
                <td>Rp {{ number_format($daftar->total_pengambilan) }}</td>
            </tr>

            <tr class="total">
                <td>Total Hutang</td>
                <td>:</td>
                <td>Rp {{ number_format($daftar->total_hutang) }}</td>
            </tr>

            <!-- <tr class="total">
                <td>Total Pengambilan</td>
                <td>:</td>
                <td>Rp {{ number_format($giling->calculateTotalPengambilan(), 2) }}</td>
            </tr> -->


            <tr class="total">
                <td>Sisa Dana</td>
                <td>:</td>
                <td>Rp {{ number_format($daftar->dana_penerima) }}</td>
            </tr>
            @endforeach
        </table>

        <table>
            <div></div>
        </table>

        <div class="footer">
            <img src="{{ asset('footer.png') }}" alt="Putra Manuaba" class="header-logo">
            <div class="header-text">
                <div></div>
            </div>
        </div>

        <!-- Titik-titik di kiri dan kanan sebanyak 4 baris -->
        <table style="width: 100%; border-collapse: collapse; margin: 5px 0;">
            <tr>
                <td style="width: 50%; text-align: left; padding: 1px 0;">.</td>
                <td style="width: 50%; text-align: right; padding: 1px 0;">.</td>
            </tr>
            <tr>
                <td style="width: 50%; text-align: left; padding: 1px 0;">.</td>
                <td style="width: 50%; text-align: right; padding: 1px 0;">.</td>
            </tr>
            <tr>
                <td style="width: 50%; text-align: left; padding: 1px 0;">.</td>
                <td style="width: 50%; text-align: right; padding: 1px 0;">.</td>
            </tr>
            <tr>
                <td style="width: 50%; text-align: left; padding: 1px 0;">.</td>
                <td style="width: 50%; text-align: right; padding: 1px 0;">.</td>
            </tr>
        </table>


</body>

</html>
