<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalkulator Beras</title>
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js')
                .then(() => console.log("Service Worker Registered"))
                .catch(err => console.log("Service Worker Failed", err));
        }
    </script>
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #f72585;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --success-color: #38b000;
            --warning-color: #ffaa00;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html, body {
            height: 100%;
            width: 100%;
            overflow: hidden;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: white;
            color: var(--dark-color);
            line-height: 1.6;
            display: flex;
            flex-direction: column;
        }

        .table-container {
            flex: 1;
            overflow-y: auto;
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 16px;
        }

        th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 500;
            text-align: center;
            padding: 12px 10px;
            position: sticky;
            top: 0;
            z-index: 10;
            font-size: 16px;
        }

        td {
            padding: 12px 10px;
            text-align: center;
            border-bottom: 1px solid #eaeaea;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tr:hover:not(.total-row) {
            background-color: #e6f7ff;
        }

        .input-field {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            text-align: center;
            transition: border-color 0.2s;
        }

        .input-field:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(67, 97, 238, 0.2);
        }

        .hasil-cell {
            font-weight: 500;
            color: var(--primary-color);
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-danger {
            background-color: #ff5a5f;
            color: white;
            font-size: 14px;
        }

        .btn-danger:hover {
            background-color: #ff4146;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
            width: 100%;
            height: 50px;
            font-size: 16px;
            border-radius: 0;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
        }

        .btn i {
            margin-right: 6px;
        }

        .total-row {
            font-weight: bold;
            background-color: #f0f4ff !important;
            color: var(--dark-color);
        }

        .total-row td {
            padding: 14px 10px;
            font-size: 17px;
            border-top: 2px solid var(--primary-color);
        }

        .total-label {
            background-color: var(--primary-color);
            color: white;
            font-weight: 500;
        }

        .total-value {
            color: var(--primary-color);
        }

        .action-bar {
            width: 100%;
            margin-top: auto;
        }

        @media (max-width: 768px) {
            th, td {
                padding: 10px 8px;
            }

            .input-field {
                padding: 8px;
                font-size: 14px;
            }

            .total-row td {
                padding: 12px 8px;
                font-size: 15px;
            }
        }

        @media (max-width: 480px) {
            th, td {
                padding: 8px 6px;
                font-size: 14px;
            }

            .input-field {
                padding: 6px;
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    <div class="table-container">
        <table id="kalkulatorTable">
            <thead>
                <tr>
                    <th>JUMLAH</th>
                    <th>HARGA</th>
                    <th>HASIL</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input inputmode="decimal" type="text" class="input-field jumlah" oninput="formatJumlah(this); hitung(this)" placeholder="Kg"></td>
                    <td><input inputmode="decimal" type="text" class="input-field harga" oninput="formatHarga(this); hitung(this)" placeholder="Rp"></td>
                    <td class="hasil hasil-cell">0</td>
                    <td><button class="btn btn-danger" onclick="hapusBaris(this)"><i class="fas fa-trash-alt"></i>Hapus</button></td>
                </tr>
                <tr class="total-row">
                    <td class="total-value" id="totalJumlah">0</td>
                    <td class="total-value" id="totalRata">0</td>
                    <td class="total-value" id="totalHasil">0</td>
                    <td class="total-label">TOTAL</td>
                </tr>

            </tbody>
        </table>
    </div>

    <div class="action-bar">
        <button class="btn btn-primary" onclick="tambahBaris()"><i class="fas fa-plus"></i>TAMBAH BARIS</button>
    </div>

    <script>
        function formatRibuan(angka) {
            return angka.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        function formatJumlah(input) {
            let angka = input.value.replace(/,/g, "").replace(/[^\d.]/g, ""); // Hanya angka & titik
            let parts = angka.split('.');
            if (parts.length > 2) angka = parts[0] + '.' + parts.slice(1).join('');
            input.value = angka ? formatRibuan(angka) : "";
        }

        function formatHarga(input) {
            let angka = input.value.replace(/,/g, "").replace(/[^\d]/g, ""); // Hanya angka
            input.value = angka ? "Rp " + formatRibuan(angka) : "";
        }

        function getNumber(input) {
            return parseFloat(input.value.replace(/Rp /g, "").replace(/,/g, "")) || 0;
        }

        function hitung(input) {
            let row = input.closest("tr");
            let jumlah = getNumber(row.querySelector(".jumlah"));
            let harga = getNumber(row.querySelector(".harga"));
            let hasil = row.querySelector(".hasil");

            let nilaiHasil = jumlah * harga;
            hasil.textContent = "Rp " + formatRibuan(nilaiHasil.toFixed(2));

            hitungTotal();
        }

        function hitungTotal() {
            let totalJumlah = 0;
            let totalHasil = 0;

            document.querySelectorAll(".jumlah").forEach(input => {
                totalJumlah += getNumber(input);
            });

            document.querySelectorAll(".hasil").forEach(td => {
                totalHasil += parseFloat(td.textContent.replace(/Rp /g, "").replace(/,/g, "")) || 0;
            });

            let totalRata = totalJumlah ? totalHasil / totalJumlah : 0;

            document.getElementById("totalJumlah").textContent = formatRibuan(totalJumlah.toFixed(2));
            document.getElementById("totalHasil").textContent = "Rp " + formatRibuan(totalHasil.toFixed(2));
            document.getElementById("totalRata").textContent = "Rp " + formatRibuan(totalRata.toFixed(2));
        }

        function tambahBaris() {
            let table = document.getElementById("kalkulatorTable");
            let row = table.insertRow(table.rows.length - 1);
            row.innerHTML = `
                <td><input inputmode="decimal" type="text" class="input-field jumlah" oninput="formatJumlah(this); hitung(this)" placeholder="Kg"></td>
                <td><input inputmode="decimal" type="text" class="input-field harga" oninput="formatHarga(this); hitung(this)" placeholder="Rp"></td>
                <td class="hasil hasil-cell">0</td>
                <td><button class="btn btn-danger" onclick="hapusBaris(this)"><i class="fas fa-trash-alt"></i>Hapus</button></td>
            `;
        }

        function hapusBaris(button) {
            let row = button.closest("tr");
            row.remove();
            hitungTotal();
        }

        // Adjust height on window resize
        window.addEventListener('resize', adjustHeight);

        // Initial height adjustment
        function adjustHeight() {
            let vh = window.innerHeight;
            document.documentElement.style.setProperty('--vh', `${vh}px`);
        }

        adjustHeight();
    </script>
</body>
</html>
