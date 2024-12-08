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
                <td>{{ $debits->petani->nama }}</td>
            </tr>
            <tr class="calculation-row">
                <td class="small-text">Nomor Nota</td>
                <td>:</td>
                <td>{{ $debits->id }}</td>
            </tr>
            <tr class="calculation-row">
                <td>Tanggal Nota </td>
                <td>:</td>
                <td>{{ $debits->created_at->addHours(0)->format('d/m/Y') }} ({{ $debits->created_at->addHours(0)->format('H:i:s') }})</td>
            </tr>
        </table>

        <table>
            <div></div>
        </table>



        <table>

            <tr class="bold-border-top">
                <td>DEBIT / TUNAI</td>
                <td></td>
                <td></td>




            </tr>
            <tr class="bold-border-top-top calculation-row">
                <td class="small-text">Jumlah</td>
                <td>:</td>
                <td>{{ number_format($debits->jumlah) }} Kg</td>



            </tr>


        </table>

        <table>
            <div></div>
        </table>


        <table>
            <thead>
                <tr class="bold-border-top">
                    <th>Hutang</th>
                    <th>Jumlah</th>
                    <th>Bunga</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                // Ambil data yang diperlukan
                $filteredKredits = $debits->petani->kredits->where('status', false)->sortBy('tanggal');
                $pembayaranKredit = $debits->pembayaranKredits->first();
                $bungaRate = $pembayaranKredit ? $pembayaranKredit->bunga : 0;
                @endphp

                @forelse ($filteredKredits as $index => $kredit)
                @php
                $lamaBulan = $pembayaranKredit ? $pembayaranKredit->hitungLamaHutangBulan($kredit->tanggal) : 0;
                $bunga = $kredit->jumlah * ($bungaRate / 100) * $lamaBulan;
                $totalHutang = $kredit->jumlah + $bunga;
                @endphp
                <tr class="calculation-row border">
                    <td class="border px-4 py-2">
                        {{ $loop->iteration }}. {{ \Carbon\Carbon::parse($kredit->tanggal)->format('d/m/Y') }}
                    </td>
                    <td class="border px-4 py-2">Rp {{ number_format($kredit->jumlah) }}</td>
                    <td class="border px-4 py-2">{{ $lamaBulan }} Bln ({{ floor($bungaRate) }}%)</td>
                    <td class="border px-4 py-2 font-bold">Rp {{ number_format($totalHutang) }}</td>
                </tr>
                @empty
                <tr class="calculation-row">
                    <td colspan="4" class="text-center py-4">Tidak ada data hutang</td>
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

        <table>
            <div></div>
        </table>

        <div class="footer">
            <img src="{{ asset('footer.png') }}" alt="Putra Manuaba" class="header-logo">
            <div class="header-text">
                <div></div>
            </div>
        </div>


</body>

</html>