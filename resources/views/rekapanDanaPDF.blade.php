<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Dana</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1;
            color: #333;
            font-size: 9pt;
            max-width: 21cm;
            margin: 0 auto;
        }

        .export-info {
            text-align: right;
            margin-bottom: 5mm;
            font-size: 8pt;
            color: #666;
        }

        h1 {
            text-align: center;
            font-size: 16pt;
            margin-top: 6mm;
            margin-bottom: 5mm;
            border-bottom: 1px solid #333;
            padding-bottom: 3mm;
        }

        .section {
            margin-bottom: 5mm;
        }

        .section-title {
            font-size: 15pt;
            font-weight: bold;
            margin-bottom: 2mm;
            color: #555;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 3mm;
            table-layout: fixed;
            font-size: 13pt;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 2mm;
            text-align: right;
            vertical-align: middle;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }

        table tr td:first-child,
        table tr th:first-child {
            text-align: left;
        }

        .total td {
            font-weight: bold;
            background-color: #e0e0e0;
        }

        .result {
            font-size: 10pt;
            text-align: right !important;
        }

        .footer {
            margin-top: 5mm;
            text-align: center;
            font-size: 11pt;
            color: #666;
        }

        table {
            page-break-inside: avoid;
        }

        .section {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>
    <div class="container">


        <h1>REKAPAN DANA</h1>

        <div class="section">
            <div class="section-title">(+) Dana Tersebar</div>
            <table>
                <tr>
                    <th>Item</th>
                    <th>Jumlah</th>
                </tr>
                <tr>
                    <td>BRI</td>
                    <td>Rp. {{ number_format($bri, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>BNI</td>
                    <td>Rp. {{ number_format($bni, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Tunai</td>
                    <td>Rp. {{ number_format($tunai, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Mama</td>
                    <td>Rp. {{ number_format($mama, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Total Kredit Petani</td>
                    <td>Rp. {{ number_format($total_kredit, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Total Kredit Nasabah Palu</td>
                    <td>Rp. {{ number_format($nasabah_palu, 2, ',', '.') }}</td>
                </tr>
                <tr class="total">
                    <td>Total Dana Tersebar</td>
                    <td>Rp. {{ number_format($kelompok1Total, 2, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">(+) Kalkulasi Jumlah dan Harga</div>
            <table>
                <tr>
                    <th>Item</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Total</th>
                </tr>
                <tr>
                    <td>Stok Beras</td>
                    <td>{{ $stok_beras_jumlah }}</td>
                    <td>Rp. {{ number_format($stok_beras_harga, 2, ',', '.') }}</td>
                    <td>Rp. {{ number_format($stok_beras_total, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Ongkos Jemur</td>
                    <td>{{ $ongkos_jemur_jumlah }}</td>
                    <td>Rp. {{ number_format($ongkos_jemur_harga, 2, ',', '.') }}</td>
                    <td>Rp. {{ number_format($ongkos_jemur_total, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Beras Terpinjam</td>
                    <td>{{ $beras_terpinjam_jumlah }}</td>
                    <td>Rp. {{ number_format($beras_terpinjam_harga, 2, ',', '.') }}</td>
                    <td>Rp. {{ number_format($beras_terpinjam_total, 2, ',', '.') }}</td>
                </tr>
                <tr class="total">
                    <td>Total Kalkulasi</td>
                    <td colspan="3">Rp. {{ number_format($kelompok2Total, 2, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">(-) Pinjaman dan Utang</div>
            <table>
                <tr>
                    <th>Item</th>
                    <th>Jumlah</th>
                </tr>
                <tr>
                    <td>Pinjaman Bank</td>
                    <td>Rp. {{ number_format($pinjaman_bank, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Titipan Petani</td>
                    <td>Rp. {{ number_format($titipan_petani, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Utang Beras</td>
                    <td>Rp. {{ number_format($utang_beras, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Utang ke Operator</td>
                    <td>Rp. {{ number_format($utang_ke_operator, 2, ',', '.') }}</td>
                </tr>
                <tr class="total">
                    <td>Total Pinjaman dan Utang</td>
                    <td>Rp. {{ number_format($kelompok3Total, 2, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Kalkulasi Rekapan Dana</div>
            <table>
                <tr>
                    <td>Total Dana Tersebar</td>
                    <td>Rp. {{ number_format($viewKelompok1Total, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Total Kalkulasi Jumlah dan Harga</td>
                    <td>Rp. {{ number_format($viewKelompok2Total, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Total Pinjaman dan Utang</td>
                    <td>Rp. {{ number_format($viewKelompok3Total, 2, ',', '.') }}</td>
                </tr>
                <tr class="total">
                    <td>Rekapan Dana</td>
                    <td>Rp. {{ number_format($rekapan_dana, 2, ',', '.') }}</td>
                </tr>
            </table>
        </div>

    </div>

    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::parse($created_at)->locale('id')->isoFormat('dddd, D MMMM YYYY - HH:mm:ss') }}
    </div>
</body>

</html>