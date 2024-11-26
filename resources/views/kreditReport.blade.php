<!DOCTYPE html>
<html>

<head>
    <title>Laporan Kredit</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            table-layout: auto;
            /* Automatically adjust column widths based on content */
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            padding: 8px;
            text-align: center;
        }

        td {
            padding: 4px;
            word-wrap: break-word;
            /* Ensure long text breaks to new lines if necessary */
        }



        .summary {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div style="display: flex; justify-content: space-between; width: 100%; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0; display: flex; justify-content: space-between; width: 100%;">
            <span>Laporan Kredit / Hutang Petani </span>
            <span style="text-align: right; padding-left: 182px;">{{ date('d F Y - H:i:s') }}</span>
        </h2>
    </div>




    <div class="summary">
        <table>
            <tr>
                <td style="text-align: left;">Jumlah Petani Belum Lunas <span style="float: right;">{{ $jumlahPetaniBelumLunas }} Orang</span></td>
            </tr>
            <tr>
                <td style="text-align: left;">Total Kredit Belum Lunas <span style="float: right;">Rp {{ number_format($totalKreditBelumLunas, 2, ',', '.') }}</span></td>
            </tr>
            <tr>
                <td style="text-align: left;">Total Bunga Kredit <span style="float: right;">Rp {{ number_format($totalKreditPlusBungaBelumLunas-$totalKreditBelumLunas, 2, ',', '.') }}</span></td>
            </tr>
            <tr>
                <td style="text-align: left;">Total Kredit Dengan Bunga Belum Lunas <span style="float: right;">Rp {{ number_format($totalKreditPlusBungaBelumLunas, 2, ',', '.') }}</span></td>
            </tr>

        </table>
    </div>



    <div class="summary">
        <table>

            <tr>
                <td style="text-align: left;">Jumlah Petani Sudah Lunas <span style="float: right;">{{ $jumlahPetaniLunas }} Orang</span></td>
            </tr>
            <tr>
                <td style="text-align: left;">Total Kredit Lunas <span style="float: right;">Rp {{ number_format($totalKreditLunas, 2, ',', '.') }}</span></td>
            </tr>
            <tr>
                <td style="text-align: left;">Total Bunga Kredit <span style="float: right;">Rp {{ number_format($totalKreditPlusBungaLunas-$totalKreditLunas, 2, ',', '.') }}</span></td>
            </tr>
            <tr>
                <td style="text-align: left;">Total Kredit Dengan Bunga Lunas <span style="float: right;">Rp {{ number_format($totalKreditPlusBungaLunas, 2, ',', '.') }}</span></td>
            </tr>
        </table>
    </div>



    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Petani</th>
                <th>Alamat</th>
                <th>Tanggal</th>
                <th>Jumlah</th>
                <th>Hutang + Bunga</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <!-- Loop untuk setiap grup petani -->
            @foreach ($groupedKredits as $petaniName => $kredits)
            @foreach($kredits as $kredit)
            <tr>
                <td style="text-align: center;">{{ $kredit->id }}</td>
                <td style="text-align: left; padding-left: 8px;">{{ $kredit->petani->nama }}</td>
                <td style="text-align: center;">{{ $kredit->petani->alamat }}</td>
                <td style="text-align: center;">{{ $kredit->tanggal }}</td>
                <td style="text-align: right; padding-right: 8px;">Rp {{ number_format($kredit->jumlah, 2, ',', '.') }}</td>
                <td style="text-align: right; padding-right: 8px;">
                    Rp {{ number_format($kredit->hutang_plus_bunga, 2, ',', '.') }}
                    <br>
                    <small>({{ number_format($kredit->lama_bulan) }} Bulan, Bunga: Rp {{ number_format($kredit->bunga, 2, ',', '.') }})</small>
                </td>
                <td style="text-align: center;">{{ $kredit->status ? 'Lunas' : 'Belum Lunas' }}</td>
            </tr>
            @endforeach
            @endforeach
        </tbody>
    </table>
</body>

</html>