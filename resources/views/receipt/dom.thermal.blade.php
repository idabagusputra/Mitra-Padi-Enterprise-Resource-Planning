<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            font-family: sans-serif;
            font-size: 8pt;
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
            font-size: 12pt;
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
            font-size: 10pt;
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
            font-size: 7pt;
        }

        /* New class for bold text */
        .bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="receipt">
        <div class="header">
            <div class="title">GILINGAN PADI PUTRA MANUABA</div>
            <div>DUSUN BABAHAN / LINGGA SARI TOLAI</div>
        </div>

        <table>
            <tr class="bold-border-top">
                <td>Informasi</td>
                <td></td>
                <td></td>
            <tr>
            <tr class="bold-border-top-top calculation-row">
                <td>Tanggal</td>
                <td>:</td>
                <td>{{ $giling->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            <tr class="calculation-row">
                <td class="small-text">Nama Petani</td>
                <td>:</td>
                <td>{{ $giling->petani->nama }}</td>
            </tr>
            <tr class="calculation-row">
                <td class="small-text">Giling ID</td>
                <td>:</td>
                <td>{{ $giling->id }}</td>
            </tr>
        </table>
        <table>
            <tr class="bold-border-top">
                <td>Kalkulasi</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr class="bold-border-top-top calculation-row">
                <td class="small-text"> Giling Kotor</td>
                <td>:</td>
                <td>{{ number_format($giling->giling_kotor) }} Kg</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr class="calculation-row">
                <td class="small-text">Ongkos Giling</td>
                <td>:</td>
                <td>{{ number_format($giling->giling_kotor, 2) }} × {{ number_format($giling->biaya_giling, 2) }}%</td>
                <td>=</td>
                <td>{{ number_format($giling->calculateBiayaGiling(), 2) }} Kg</td>
                <td></td>
                <td></td>

            </tr>
            <tr class="calculation-row">
                <td class="small-text">Pinjam</td>
                <td>:</td>
                <td>{{ number_format($giling->pinjam) }} Kg</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr class="calculation-row">
                <td class="small-text">Pulang</td>
                <td>:</td>
                <td>{{ number_format($giling->pulang) }} Kg</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr class="calculation-row">
                <td class="small-text">Beras Bersih</td>
                <td>:</td>
                <td>{{ number_format($giling->calculateBerasBersih(), 2) }} Kg</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>

            </tr>
            <tr class="calculation-row">
                <td class="small-text">Beras Jual</td>
                <td>:</td>
                <td>{{ number_format($giling->calculateBerasJual() / $giling->harga_jual, 2) }} × Rp {{ number_format($giling->harga_jual, 2) }}</td>
                <td>=</td>
                <td class="bold">Rp {{ number_format($giling->calculateBerasJual(), 2) }}</td>
                <td></td>
                <td></td>

            </tr>
            <tr class="calculation-row">
                <td class="small-text">Buruh Giling</td>
                <td>:</td>
                <td>{{ number_format($giling->giling_kotor, 2) }} × Rp {{ number_format($giling->biaya_buruh_giling, 2) }}</td>
                <td>=</td>
                <td class="bold">Rp {{ number_format($giling->calculateBuruhGiling(), 2) }}</td>
                <td></td>
                <td></td>

            </tr>
            <tr class="calculation-row">
                <td class="small-text">Buruh Jemur</td>
                <td>:</td>
                <td>{{ number_format($giling->jemur, 2) }} × Rp {{ number_format($giling->biaya_buruh_jemur, 2) }}</td>
                <td>=</td>
                <td class="bold">Rp {{ number_format($giling->calculateBuruhJemur(), 2) }}</td>
                <td></td>
                <td></td>

            </tr>
            <tr class="calculation-row">
                <td class="small-text">Jual Konga</td>
                <td>:</td>
                <td>{{ number_format($giling->jumlah_konga) }} × Rp {{ number_format($giling->harga_konga, 2) }}</td>
                <td>=</td>
                <td class="bold">Rp {{ number_format($giling->calculateJualKonga(), 2) }}</td>
                <td></td>
                <td></td>

            </tr>
            <tr class="calculation-row">
                <td class="small-text">Jual Menir</td>
                <td>:</td>
                <td>{{ number_format($giling->jumlah_menir) }} × Rp {{ number_format($giling->harga_menir, 2) }}</td>
                <td>=</td>
                <td class="bold">Rp {{ number_format($giling->calculateJualMenir(), 2) }}</td>
                <td></td>
                <td></td>

            </tr>
        </table>

        <table>
            <tr class="bold-border-top">
                <th>Ambil</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Total</th>
            </tr>
            @if($giling->pengambilans->isNotEmpty())
            @foreach($giling->pengambilans as $index => $pengambilan)
            <tr class="calculation-row">
                <td>{{ $index + 1 }}. {{ $pengambilan->keterangan }}</td>
                <td>{{ number_format($pengambilan->jumlah) }}</td>
                <td>Rp {{ number_format($pengambilan->harga, 2) }}</td>
                <td class="bold">Rp {{ number_format($pengambilan->jumlah * $pengambilan->harga, 2) }}</td>
            </tr>
            @endforeach
            @else
            <tr class="calculation-row">
                <td colspan="4">Tidak ada data pengambilan</td>
            </tr>
            @endif
        </table>

        <table>
            <tr class="bold-border-top">
                <th>Hutang</th>
                <th>Jumlah</th>
                <th>Bunga</th>
                <th>Total</th>
            </tr>
            @if($giling->petani->kredits->where('status', false)->isNotEmpty())
            @php
            $pembayaranKredit = $giling->pembayaranKredits->first();
            $bungaRate = $pembayaranKredit ? $pembayaranKredit->bunga : 0;
            @endphp
            @foreach($giling->petani->kredits->where('status', false) as $index => $kredit)
            @php
            $lamaBulan = $pembayaranKredit ? $pembayaranKredit->hitungLamaHutangBulan($kredit->tanggal) : 0;
            $bunga = $kredit->jumlah * ($bungaRate / 100) * $lamaBulan;
            $totalHutang = $kredit->jumlah + $bunga;
            @endphp
            <tr class="calculation-row">
                <td>{{ $index + 1 }}. {{ \Carbon\Carbon::parse($kredit->tanggal)->format('d/m/Y') }}</td>
                <td>Rp {{ number_format($kredit->jumlah, 2) }}</td>
                <td>{{ $lamaBulan }} Bln ({{ floor($bungaRate) }}%)</td>
                <td class="bold">Rp {{ number_format($totalHutang, 2) }}</td>
            </tr>
            @endforeach
            @else
            <tr class="calculation-row">
                <td colspan="4">Tidak ada data hutang</td>
            </tr>
            @endif
        </table>

        <table>
            @foreach($giling->daftarGiling as $index => $daftar)

            <!-- <tr class="total">
                <td>Dana Giling</td>
                <td>:</td>
                <td>Rp {{ number_format($daftar->dana_jual_beras - $daftar->total_pengambilan , 2) }}</td>
            </tr> -->
            <tr class="total">
                <td>Total Hutang</td>
                <td>:</td>
                <td>Rp {{ number_format($daftar->total_hutang, 2) }}</td>
            </tr>
            <!-- <tr class="total">
                <td>Total Pengambilan</td>
                <td>:</td>
                <td>Rp {{ number_format($giling->calculateTotalPengambilan(), 2) }}</td>
            </tr> -->

            <tr class="total">
                <td>Sisa Dana</td>
                <td>:</td>
                <td>Rp {{ number_format($daftar->dana_penerima, 2) }}</td>
            </tr>
            @endforeach
        </table>

        <div class="footer">
            Terima kasih giling disini, sukses selalu!
        </div>

</body>

</html>