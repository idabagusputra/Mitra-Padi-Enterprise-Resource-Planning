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

        .header {
            background-color: var(--primary-color);
            color: white;
            text-align: center;
            padding: 10px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .header h1 {
            font-size: 20px;
            font-weight: 500;
        }

        .calculator-container {
            display: flex;
            flex-direction: column;
            flex: 1;
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

        .jumlah-cell {
            font-weight: 500;
            color: var(--success-color);
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
        }

        .toggle-container {
            display: flex;
            width: 100%;
        }

        .toggle-btn {
            padding: 12px 16px;
            border: none;
            border-radius: 0;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            background-color: var(--secondary-color);
            color: white;
            flex: 1;
            font-size: 16px;
        }

        .toggle-btn.active {
            background-color: var(--primary-color);
        }

        .toggle-btn:hover:not(.active) {
            background-color: #4a41d5;
        }

        .calculator {
            display: none;
            flex-direction: column;
            flex: 1;
        }

        .calculator.active {
            display: flex;
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

            .toggle-btn {
                padding: 10px 12px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

    <div class="calculator-container">
        <!-- Kalkulator Jumlah -->
        <div id="jumlahCalculator" class="calculator active">
            <div class="table-container">
                <table id="jumlahTable">
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
                            <td><input inputmode="decimal" type="text" class="input-field jumlah" oninput="formatJumlah(this); hitungJumlah(this)" onkeydown="handleEnterKeyJumlah(event, this)" placeholder="Kg"></td>
                            <td><input inputmode="decimal" type="text" class="input-field harga" oninput="formatHarga(this); hitungJumlah(this)" onkeydown="handleEnterKeyJumlah(event, this)" placeholder="Rp"></td>
                            <td class="hasil hasil-cell">0</td>
                            <td><button class="btn btn-danger" onclick="hapusBarisJumlah(this)"><i class="fas fa-trash-alt"></i>Hapus</button></td>
                        </tr>
                        <tr class="total-row">
                            <td class="total-value" id="totalJumlah">0</td>
                            <td class="total-value" id="totalRataJumlah">0</td>
                            <td class="total-value" id="totalHasilJumlah">0</td>
                            <td class="total-label">TOTAL</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <table id="TableSelisih" class="table-auto total-row w-full border-collapse border border-gray-300 mb-2">
                <tr>
                    <td style="width: 40%;">
                        <input inputmode="decimal" type="text" class="input-field dana" oninput="formatHarga(this); hitungSelisih()" onkeydown="handleEnterKeySak(event, this)" placeholder="Jumlah Dana (Rp)">
                    </td>
                    <td class="total-value selisih" style="width: 40%; text-align: center; font-weight: bold;" id="selisih">SELISIH</td>
                    {{-- <td class="total-label" style="width: 16.65%;">SELISIH</td> --}}
                </tr>
            </table>


            {{-- <div class="action-bar">
                <button class="btn btn-primary" onclick="tambahBarisJumlah()"><i class="fas fa-plus"></i>TAMBAH BARIS</button>
            </div> --}}
        </div>

        <!-- Kalkulator Sak -->
        <div id="sakCalculator" class="calculator">
            <div class="table-container">
                <table id="sakTable">
                    <thead>
                        <tr>
                            <th>SAK</th>
                            <th>HARGA</th>
                            <th>JUMLAH</th>
                            <th>HASIL</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input inputmode="decimal" type="text" class="input-field sak" oninput="formatSak(this); hitungSak(this)" onkeydown="handleEnterKeySak(event, this)" placeholder="Sak"></td>
                            <td><input inputmode="decimal" type="text" class="input-field harga" oninput="formatHarga(this); hitungSak(this)" onkeydown="handleEnterKeySak(event, this)" placeholder="Rp"></td>
                            <td class="jumlah jumlah-cell">0</td>
                            <td class="hasil hasil-cell">0</td>
                            <td><button class="btn btn-danger" onclick="hapusBarisSak(this)"><i class="fas fa-trash-alt"></i>Hapus</button></td>
                        </tr>
                        <tr class="total-row">
                            <td class="total-value" id="totalSak">0</td>
                            <td class="total-value" id="totalRataSak">0</td>
                            <td class="total-value" id="totalJumlahSak">0</td>
                            <td class="total-value" id="totalHasilSak">0</td>
                            <td class="total-label">TOTAL</td>
                        </tr>
                    </tbody>
                </table>
            </div>

           <table id="sakTableSelisih" class="table-auto total-row w-full border-collapse border border-gray-300 mb-2">
                <tr>
                    <td style="width: 50%;">
                        <input inputmode="decimal" type="text" class="input-field dana" oninput="formatHarga(this); hitungSelisihSak()" onkeydown="handleEnterKeySak(event, this)" placeholder="Jumlah Dana (Rp)">
                    </td>
                    <td class="total-value selisih" style="width: 50%; text-align: center; font-weight: bold;">SELISIH</td>
                    {{-- <td class="total-label" style="width: 16.65%;">SELISIH</td> --}}
                </tr>
            </table>

            {{-- <div class="action-bar">
                <button class="btn btn-primary" onclick="tambahBarisSak()"><i class="fas fa-plus"></i>TAMBAH BARIS</button>
            </div> --}}
        </div>

        <!-- Toggle buttons at the bottom -->
        <div class="toggle-container">
            <button class="toggle-btn btn-primary active " onclick="toggleCalculator('jumlah')">
                <i class="fas fa-balance-scale"></i> INPUT BERAT
            </button>
            <button class="toggle-btn btn-primary" onclick="toggleCalculator('sak')">
                <i class="fas fa-box"></i> INPUT SAK
            </button>
        </div>
    </div>

    <script>
        const KG_PER_SAK = 50; // Konstanta: 1 Sak = 50 Kg

        function hitungSelisih() {
            // Ambil nilai Jumlah Dana
            let jumlahDana = getNumber(document.querySelector('.dana'));

            // Ambil nilai totalHasilJumlah
            let totalHasilJumlah = parseFloat(document.getElementById('totalHasilJumlah').textContent.replace(/Rp /g, "").replace(/,/g, "")) || 0;

            // Hitung selisih
            let selisih = jumlahDana - totalHasilJumlah;

            // Perbarui nilai pada elemen dengan id selisih
            document.getElementById('selisih').textContent = "Rp " + formatRibuan(selisih.toFixed(0));
        }

        function hitungSelisihSak() {
            // Ambil nilai Jumlah Dana
            let jumlahDana = getNumber(document.querySelector('#sakTableSelisih .dana'));

            // Ambil nilai totalHasilSak
            let totalHasilSak = parseFloat(document.getElementById('totalHasilSak').textContent.replace(/Rp /g, "").replace(/,/g, "")) || 0;

            // Hitung selisih
            let selisih = jumlahDana - totalHasilSak;

            // Perbarui nilai pada elemen dengan id selisih di tabel Sak
            document.querySelector('#sakTableSelisih .selisih').textContent = "Rp " + formatRibuan(selisih.toFixed(0));
        }

        function toggleCalculator(type) {
            // Update button states
            document.querySelectorAll('.toggle-btn').forEach(btn => btn.classList.remove('active'));
            event.currentTarget.classList.add('active');

            // Hide all calculators
            document.querySelectorAll('.calculator').forEach(calc => calc.classList.remove('active'));

            // Show selected calculator
            if (type === 'jumlah') {
                document.getElementById('jumlahCalculator').classList.add('active');
            } else if (type === 'sak') {
                document.getElementById('sakCalculator').classList.add('active');
            }
        }

        function formatRibuan(angka) {
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        // FUNCTIONS FOR JUMLAH CALCULATOR
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

        function hitungJumlah(input) {
            let row = input.closest("tr");
            let jumlah = getNumber(row.querySelector(".jumlah"));
            let harga = getNumber(row.querySelector(".harga"));
            let hasil = row.querySelector(".hasil");

            let nilaiHasil = jumlah * harga;
            hasil.textContent = "Rp " + formatRibuan(nilaiHasil.toFixed(0));

            hitungTotalJumlah();
        }

        function hitungTotalJumlah() {
            let totalJumlah = 0;
            let totalHasil = 0;

            document.querySelectorAll("#jumlahTable .jumlah").forEach(input => {
                totalJumlah += getNumber(input);
            });

            document.querySelectorAll("#jumlahTable .hasil").forEach(td => {
                totalHasil += parseFloat(td.textContent.replace(/Rp /g, "").replace(/,/g, "")) || 0;
            });

            let totalRata = totalJumlah ? totalHasil / totalJumlah : 0;

            document.getElementById("totalJumlah").textContent = formatRibuan(totalJumlah.toFixed(0));
            document.getElementById("totalHasilJumlah").textContent = "Rp " + formatRibuan(totalHasil.toFixed(0));
            document.getElementById("totalRataJumlah").textContent = "Rp " + formatRibuan(totalRata.toFixed(0));

            // Panggil fungsi hitungSelisih setelah menghitung total
            hitungSelisih();
        }

        function tambahBarisJumlah() {
            let table = document.getElementById("jumlahTable");
            let row = table.insertRow(table.rows.length - 1);
            row.innerHTML = `
                <td><input inputmode="decimal" type="text" class="input-field jumlah" oninput="formatJumlah(this); hitungJumlah(this)" onkeydown="handleEnterKeyJumlah(event, this)" placeholder="Kg"></td>
                <td><input inputmode="decimal" type="text" class="input-field harga" oninput="formatHarga(this); hitungJumlah(this)" onkeydown="handleEnterKeyJumlah(event, this)" placeholder="Rp"></td>
                <td class="hasil hasil-cell">0</td>
                <td><button class="btn btn-danger" onclick="hapusBarisJumlah(this)"><i class="fas fa-trash-alt"></i>Hapus</button></td>
            `;

            // Focus on the new jumlah input
            setTimeout(() => {
                row.querySelector('.jumlah').focus();
            }, 100);
        }

        function hapusBarisJumlah(button) {
            let row = button.closest("tr");
            row.remove();
            hitungTotalJumlah();
        }

        function handleEnterKeyJumlah(event, input) {
            if (event.key === 'Enter') {
                event.preventDefault();

                const row = input.closest('tr');

                // If this is jumlah input, move to harga input
                if (input.classList.contains('jumlah')) {
                    row.querySelector('.harga').focus();
                }
                // If this is harga input
                else if (input.classList.contains('harga')) {
                    // Is this the last row before total?
                    const allRows = Array.from(document.querySelectorAll('#jumlahTable tr:not(.total-row)'));
                    const currentRowIndex = allRows.indexOf(row);

                    // If it's the last row, add a new row
                    if (currentRowIndex === allRows.length - 1) {
                        tambahBarisJumlah();
                    } else {
                        // If not, move to the jumlah input of the next row
                        allRows[currentRowIndex + 1].querySelector('.jumlah').focus();
                    }
                }
            }
        }

        // FUNCTIONS FOR SAK CALCULATOR
        function formatSak(input) {
            let angka = input.value.replace(/,/g, "").replace(/[^\d.]/g, ""); // Hanya angka & titik
            let parts = angka.split('.');
            if (parts.length > 2) angka = parts[0] + '.' + parts.slice(1).join('');
            input.value = angka ? formatRibuan(angka) : "";
        }

        function hitungSak(input) {
            let row = input.closest("tr");
            let sak = getNumber(row.querySelector(".sak"));
            let harga = getNumber(row.querySelector(".harga"));
            let jumlah = row.querySelector(".jumlah");
            let hasil = row.querySelector(".hasil");

            // Jumlah = Sak × 50
            let nilaiJumlah = sak * KG_PER_SAK;
            jumlah.textContent = formatRibuan(nilaiJumlah.toFixed(0));

            // Hasil = Jumlah × Harga
            let nilaiHasil = nilaiJumlah * harga;
            hasil.textContent = "Rp " + formatRibuan(nilaiHasil.toFixed(0));

            hitungTotalSak();
        }

        function hitungTotalSak() {
            let totalSak = 0;
            let totalJumlah = 0;
            let totalHasil = 0;
            let totalHarga = 0;
            let countHarga = 0;

            document.querySelectorAll("#sakTable .sak").forEach(input => {
                totalSak += getNumber(input);
            });

            document.querySelectorAll("#sakTable .harga").forEach(input => {
                let nilai = getNumber(input);
                if (nilai > 0) {
                    totalHarga += nilai;
                    countHarga++;
                }
            });

            document.querySelectorAll("#sakTable .jumlah").forEach(td => {
                if (td.textContent !== "0") {
                    totalJumlah += parseFloat(td.textContent.replace(/,/g, "")) || 0;
                }
            });

            document.querySelectorAll("#sakTable .hasil").forEach(td => {
                if (td.textContent !== "0") {
                    totalHasil += parseFloat(td.textContent.replace(/Rp /g, "").replace(/,/g, "")) || 0;
                }
            });

            // Harga rata-rata per kg
            let rataHarga = countHarga ? totalHarga / countHarga : 0;

            document.getElementById("totalSak").textContent = formatRibuan(totalSak.toFixed(0));
            document.getElementById("totalJumlahSak").textContent = formatRibuan(totalJumlah.toFixed(0));
            document.getElementById("totalHasilSak").textContent = "Rp " + formatRibuan(totalHasil.toFixed(0));
            document.getElementById("totalRataSak").textContent = "Rp " + formatRibuan(rataHarga.toFixed(0));

            // Panggil fungsi hitungSelisihSak setelah menghitung total
            hitungSelisihSak();
        }

        function tambahBarisSak() {
            let table = document.getElementById("sakTable");
            let row = table.insertRow(table.rows.length - 1);
            row.innerHTML = `
                <td><input inputmode="decimal" type="text" class="input-field sak" oninput="formatSak(this); hitungSak(this)" onkeydown="handleEnterKeySak(event, this)" placeholder="Sak"></td>
                <td><input inputmode="decimal" type="text" class="input-field harga" oninput="formatHarga(this); hitungSak(this)" onkeydown="handleEnterKeySak(event, this)" placeholder="Rp"></td>
                <td class="jumlah jumlah-cell">0</td>
                <td class="hasil hasil-cell">0</td>
                <td><button class="btn btn-danger" onclick="hapusBarisSak(this)"><i class="fas fa-trash-alt"></i>Hapus</button></td>
            `;

            // Focus on the new sak input
            setTimeout(() => {
                row.querySelector('.sak').focus();
            }, 100);
        }

        function hapusBarisSak(button) {
            let row = button.closest("tr");
            row.remove();
            hitungTotalSak();
        }

        function handleEnterKeySak(event, input) {
            if (event.key === 'Enter') {
                event.preventDefault();

                const row = input.closest('tr');

                // If this is sak input, move to harga input
                if (input.classList.contains('sak')) {
                    row.querySelector('.harga').focus();
                }
                // If this is harga input
                else if (input.classList.contains('harga')) {
                    // Is this the last row before total?
                    const allRows = Array.from(document.querySelectorAll('#sakTable tr:not(.total-row)'));
                    const currentRowIndex = allRows.indexOf(row);

                    // If it's the last row, add a new row
                    if (currentRowIndex === allRows.length - 1) {
                        tambahBarisSak();
                    } else {
                        // If not, move to the sak input of the next row
                        allRows[currentRowIndex + 1].querySelector('.sak').focus();
                    }
                }
            }
        }

        // Window resizing
        window.addEventListener('resize', adjustHeight);

        function adjustHeight() {
            let vh = window.innerHeight;
            document.documentElement.style.setProperty('--vh', `${vh}px`);
        }

        adjustHeight();
    </script>
</body>
</html>
