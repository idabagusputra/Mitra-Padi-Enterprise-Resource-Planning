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
            --primary-color: #6c5ce7;
            --primary-dark: #5549c9;
            --primary-light: #a29bfe;
            --secondary-color: #00cec9;
            --accent-color: #fd79a8;
            --dark-color: #2d3436;
            --light-color: #f7f7f7;
            --success-color: #00b894;
            --warning-color: #fdcb6e;
            --danger-color: #ff7675;
            --card-shadow: 0 10px 30px rgba(108, 92, 231, 0.1);
            --border-radius: 16px;
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, 'Roboto', sans-serif;
        }

        html, body {
            height: 100%;
            width: 100%;
            background: var(--light-color);
            color: var(--dark-color);
            overflow-y: auto; /* Enable vertical scrolling */
            position: relative;
        }

        body {
            display: flex;
            flex-direction: column;
            background: linear-gradient(135deg, #f7f7f7 0%, #e6e9f0 100%);
            min-height: 100%;
        }

        .header {
            background: linear-gradient(120deg, var(--primary-color), var(--primary-dark));
            color: white;
            text-align: center;
            padding: 20px 0;
            border-radius: 0 0 var(--border-radius) var(--border-radius);
            box-shadow: var(--card-shadow);
            margin-bottom: 15px;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: -20px;
            left: -20px;
            width: 140%;
            height: 140%;
            background: radial-gradient(circle, transparent 20%, rgba(255, 255, 255, 0.05) 21%, transparent 22%), radial-gradient(circle, transparent 20%, rgba(255, 255, 255, 0.05) 21%, transparent 22%);
            background-size: 30px 30px;
            background-position: 0 0, 15px 15px;
            transform: rotate(-5deg);
            z-index: 0;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 700;
            margin: 0;
            position: relative;
            z-index: 1;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .calculator-container {
            display: flex;
            flex-direction: column;
            flex: 1;
            padding: 15px 15px 15px;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            overflow-y: visible; /* Ensure content can scroll */
        }

        .table-container {
            flex: 1;
            overflow-y: visible; /* Changed from auto to visible */
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            padding: 10px;
            margin-bottom: 10px;
            position: relative;
            border: 1px solid rgba(108, 92, 231, 0.1);
            max-height: none; /* Remove max-height restriction */
        }

        @media (max-width: 768px) { /* Mobile portrait */
            .table-container {
                margin-top: 5px;
            }
        }

        @media (max-width: 1024px) and (orientation: landscape) { /* Mobile atau tablet dalam mode horizontal */

        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 18px; /* Increased font size for numbers */
        }

        th {
            background: linear-gradient(120deg, var(--primary-color), var(--primary-dark));
            color: white;
            font-weight: 600;
            text-align: center;
            padding: 15px 10px;
            position: sticky;
            top: 0;
            z-index: 10;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 14px;
            border-radius: 10px 10px 0 0;
        }

        th:first-child {
            border-top-left-radius: 10px;
        }

        th:last-child {
            border-top-right-radius: 10px;
        }

        td {
            padding: 15px 10px;
            text-align: center;
            border-bottom: 1px solid rgba(108, 92, 231, 0.1);
            transition: var(--transition);
            font-weight: 400;
        }

        tr:nth-child(even) {
            background-color: rgba(108, 92, 231, 0.03);
        }

        tr:hover:not(.total-row) {
            background-color: rgba(108, 92, 231, 0.08);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(108, 92, 231, 0.1);
        }

        .input-field {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid rgba(108, 92, 231, 0.2);
            border-radius: 12px;
            font-size: 18px; /* Increased font size */
            text-align: center;
            transition: var(--transition);
            background: white;
        }

        .input-field:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.15);
            transform: translateY(-2px);
        }

        .input-field::placeholder {
            color: rgba(45, 52, 54, 0.4);
        }

        .hasil-cell {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 18px; /* Adjusted for consistency */
            text-shadow: 0 1px 0 rgba(0, 0, 0, 0.05);
        }

        .jumlah-cell {
            font-weight: 600;
            color: var(--success-color);
            font-size: 18px; /* Adjusted for consistency */
        }

        .btn {
            padding: 8px 12px; /* Reduced padding */
            border: none;
            border-radius: 10px; /* Smaller radius */
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1); /* Smaller shadow */
        }

        .btn-danger {
            background-color: var(--danger-color);
            color: white;
            font-size: 12px; /* Smaller font */
            min-width: 80px; /* Minimum width */
            max-width: 80px; /* Maximum width to keep button small */
            height: 51.2px; /* Make height 100% */
        }

        td:has(.btn-danger) {
            padding: 0; /* Hilangkan padding pada td yang berisi tombol */
        }


        .btn-danger:hover {
            background-color: #ff5f5f;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(255, 118, 117, 0.3);
        }

        .btn-primary {
            background: linear-gradient(120deg, var(--primary-color), var(--primary-dark));
            color: white;
            width: 100%;
            height: 50px; /* Slightly shorter */
            font-size: 15px; /* Smaller font */
            border-radius: 0 0 var(--border-radius) var(--border-radius);
        }

        .btn-primary:hover {
            background: linear-gradient(120deg, var(--primary-dark), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(108, 92, 231, 0.3);
        }

        .btn i {
            margin-right: 6px;
            font-size: 0.9em;
        }

        .total-row {
            font-weight: 600;
            background: linear-gradient(135deg, rgba(108, 92, 231, 0.05) 0%, rgba(162, 155, 254, 0.1) 100%) !important;
            color: var(--dark-color);
            position: relative;
            box-shadow: 0 -4px 10px rgba(108, 92, 231, 0.05);
        }

        .total-row td {
            padding: 16px 10px;
            font-size: 18px; /* Consistent with other numbers */
            border-top: 2px solid rgba(108, 92, 231, 0.2);
            border-bottom: none;
        }

        .total-label {
            background: linear-gradient(120deg, var(--primary-color), var(--primary-dark));
            color: white;
            font-weight: 600;
            letter-spacing: 1px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
            border-radius: 0 0 10px 10px;
            font-size: 14px; /* Slightly smaller */
        }

        .total-label::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .total-value {
            color: var(--primary-color);
            text-shadow: 1px 1px 0 rgba(255, 255, 255, 0.5);
            font-size: 18px; /* Made consistent with other text */
        }

        .action-bar {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px; /* Added spacing */
        }

        .toggle-container {
            display: flex;
            width: 100%;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--card-shadow);
            margin-top: 0px;
            margin-bottom: 15px; /* Add space at bottom */
            position: sticky;
            bottom: 15px;
            z-index: 20;
        }

        .toggle-btn {
            padding: 14px; /* Slightly smaller */
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            background-color: var(--dark-color);
            color: white;
            flex: 1;
            font-size: 15px; /* Smaller text */
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px; /* Smaller gap */
        }

        .toggle-btn i {
            font-size: 1.1em;
            transition: var(--transition);
        }

        .toggle-btn.active {
            background: linear-gradient(120deg, var(--primary-color), var(--primary-dark));
            color: white;
        }

        .toggle-btn.active i {
            transform: scale(1.2);
        }

        .toggle-btn:hover:not(.active) {
            background-color: #3d3d3d;
            transform: translateY(-2px);
        }

        .toggle-btn:first-child {
            border-radius: var(--border-radius) 0 0 var(--border-radius);
        }

        .toggle-btn:last-child {
            border-radius: 0 var(--border-radius) var(--border-radius) 0;
        }

        .calculator {
            display: none;
            flex-direction: column;
            flex: 1;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .calculator.active {
            display: flex;
            opacity: 1;
            transform: translateY(0);
        }

        #TableSelisih, #sakTableSelisih {
            margin: 0 0 11px 0;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--card-shadow);
            background: white;
            border: 1px solid rgba(108, 92, 231, 0.1);
        }

        #TableSelisih td, #sakTableSelisih td {
            padding: 15px;
        }

        .selisih {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, rgba(108, 92, 231, 0.05) 0%, rgba(162, 155, 254, 0.1) 100%);
            font-size: 18px; /* Made consistent */
        }

        .selisih::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
        }

        /* Floating label for inputs */
        .input-group {
            position: relative;
            width: 100%;
        }

        /* Animation effects */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* For table hover row effect */
        tr {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        /* Optimize the button columns to be narrower */
        table th:last-child,
        table td:last-child {
            width: 80px; /* Fixed width for action column */
            min-width: 80px;
            max-width: 80px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .header {
                padding: 15px 0;
                margin-bottom: 10px;
            }

            .header h1 {
                font-size: 22px;
            }

            th, td {
                padding: 12px 6px; /* Narrower padding */
                font-size: 14px;
            }

            .input-field {
                padding: 10px 8px; /* Smaller padding */
                font-size: 16px; /* Still keep readable size */
            }

            .hasil-cell, .jumlah-cell, .total-value {
                font-size: 16px; /* Consistent font size */
            }

            .total-row td {
                padding: 14px 6px;
            }

            .toggle-btn {
                padding: 12px 8px;
                font-size: 14px;
            }

            .btn-danger {
                padding: 6px 8px;
                font-size: 15px;
                min-width: 50px;
            }

            table th:last-child,
            table td:last-child {
                width: 60px; /* Even smaller on mobile */
                min-width: 60px;
                max-width: 60px;
            }
        }

        @media (max-width: 480px) {
            :root {
                --border-radius: 12px;
            }

            .calculator-container {
                padding: 5px 8px 0px;
            }

            th, td {
                padding: 10px 4px; /* Very small padding */
                font-size: 13px;
            }

            .btn-danger {
                padding: 5px 6px;
                font-size: 11px;
            }

            .btn-danger i {
                margin-right: 2px;
                font-size: 0.8em;
            }

            .input-field {
                padding: 8px 4px;
                font-size: 15px; /* Maintain readability */
                border-radius: 8px;
                border-width: 1px; /* Thinner border */
            }

            .total-row td {
                padding: 12px 4px;
                font-size: 15px;
            }

            .toggle-btn {
                padding: 10px 6px;
                font-size: 13px;
            }

            .selisih {
                font-size: 16px;
            }

            /* Smallest possible button area */
            table th:last-child,
            table td:last-child {
                width: 50px;
                min-width: 50px;
                max-width: 50px;
            }
        }

        /* Additional styles for landscape mode */
        @media (orientation: landscape) {
            .table-container {
                max-height: none; /* Remove any max-height restriction */
            }

            body, html {
                overflow-y: auto !important; /* Force scrolling */
                height: auto !important; /* Allow content to determine height */
                min-height: 100%; /* At least fill the viewport */
            }
        }

        @media (orientation: portrait) {

            .btn-danger {

                height: 37.6px !important;/* Make height 100% */
            }
        }
    </style>
</head>
<body>
    {{-- <header class="header">
        <h1><i class="fas fa-calculator"></i> Kalkulator Beras Modern</h1>
    </header> --}}

    <div class="calculator-container">
        <!-- Kalkulator Jumlah -->
        <div id="jumlahCalculator" class="calculator active">
            <div class="table-container">
                <table id="jumlahTable">
                    <thead style="margin-top: 15px;">
                        <tr>
                            <th>Jumlah (Kg)</th>
                            <th>Harga (Rp)</th>
                            <th>Hasil (Rp)</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input inputmode="decimal" type="text" class="input-field jumlah" oninput="formatJumlah(this); hitungJumlah(this)" onkeydown="handleEnterKeyJumlah(event, this)" placeholder="Masukkan berat"></td>
                            <td><input inputmode="decimal" type="text" class="input-field harga" oninput="formatHarga(this); hitungJumlah(this)" onkeydown="handleEnterKeyJumlah(event, this)" placeholder="Masukkan harga"></td>
                            <td class="hasil hasil-cell">0</td>
                            <td>
                                <button class="btn btn-danger" onclick="hapusBarisJumlah(this)">
                                    <i class="fas fa-trash-alt" style="margin: 0; padding: 0;"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="total-row">
                            <td class="total-value hasil-cell" id="totalJumlah">0</td>
                            <td class="total-value hasil-cell" id="totalRataJumlah">0</td>
                            <td class="total-value hasil-cell" id="totalHasilJumlah">0</td>
                            <td class="total-label">SUM</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <table id="TableSelisih">
                <tr>
                    <td style="width: 50%;">
                        <input inputmode="decimal" type="text" class="input-field dana" oninput="formatHarga(this); hitungSelisih()" onkeydown="handleEnterKeySak(event, this)" placeholder="Jumlah Dana (Rp)">
                    </td>
                    <td class="total-value selisih" style="width: 50%; text-align: center; font-weight: bold;" id="selisih">SELISIH</td>
                </tr>
            </table>

            <div class="action-bar">
                <button class="btn btn-primary" onclick="tambahBarisJumlah()"><i class="fas fa-plus"></i>TAMBAH BARIS</button>
            </div>
        </div>

        <!-- Kalkulator Sak -->
        <div id="sakCalculator" class="calculator">
            <div class="table-container">
                <table id="sakTable">
                    <thead style="margin-top: 15px;">
                        <tr>
                            <th>Jumlah Sak</th>
                            <th>Harga (Rp)</th>
                            <th>Berat (Kg)</th>
                            <th>Hasil (Rp)</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input inputmode="decimal" type="text" class="input-field sak" oninput="formatSak(this); hitungSak(this)" onkeydown="handleEnterKeySak(event, this)" placeholder="Jumlah sak"></td>
                            <td><input inputmode="decimal" type="text" class="input-field harga" oninput="formatHarga(this); hitungSak(this)" onkeydown="handleEnterKeySak(event, this)" placeholder="Harga per kg"></td>
                            <td class="jumlah jumlah-cell">0</td>
                            <td class="hasil hasil-cell">0</td>
                            <td>
                                <button class="btn btn-danger" onclick="hapusBarisSak(this)">
                                    <i class="fas fa-trash-alt" style="margin: 0; padding: 0;"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="total-row">
                            <td class="total-value hasil-cell" id="totalSak">0</td>
                            <td class="total-value hasil-cell" id="totalRataSak">0</td>
                            <td class="total-value hasil-cell" id="totalJumlahSak">0</td>
                            <td class="total-value hasil-cell" id="totalHasilSak">0</td>
                            <td class="total-label">SUM</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <table id="sakTableSelisih">
                <tr>
                    <td style="width: 50%;">
                        <input inputmode="decimal" type="text" class="input-field dana" oninput="formatHarga(this); hitungSelisihSak()" onkeydown="handleEnterKeySak(event, this)" placeholder="Jumlah Dana (Rp)">
                    </td>
                    <td class="total-value selisih" style="width: 50%; text-align: center; font-weight: bold;">SELISIH</td>
                </tr>
            </table>

            <div class="action-bar">
                <button class="btn btn-primary" onclick="tambahBarisSak()"><i class="fas fa-plus"></i>TAMBAH BARIS</button>
            </div>
        </div>

        <!-- Toggle buttons at the bottom -->
        <div class="toggle-container">
            <button class="toggle-btn active" onclick="toggleCalculator('jumlah')">
                <i class="fas fa-weight-hanging"></i> INPUT BERAT
            </button>
            <button class="toggle-btn" onclick="toggleCalculator('sak')">
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

            document.getElementById("totalJumlah").textContent = formatRibuan(totalJumlah.toFixed(0) + " Kg");
            document.getElementById("totalHasilJumlah").textContent = "Rp " + formatRibuan(totalHasil.toFixed(0));
            document.getElementById("totalRataJumlah").textContent = "Rp " + formatRibuan(totalRata.toFixed(0));

            // Panggil fungsi hitungSelisih setelah menghitung total
            hitungSelisih();
        }

        function tambahBarisJumlah() {
            let table = document.getElementById("jumlahTable");
            let row = table.insertRow(table.rows.length - 1);
            row.innerHTML = `
                <td><input inputmode="decimal" type="text" class="input-field jumlah" oninput="formatJumlah(this); hitungJumlah(this)" onkeydown="handleEnterKeyJumlah(event, this)" placeholder="Masukkan berat"></td>
                <td><input inputmode="decimal" type="text" class="input-field harga" oninput="formatHarga(this); hitungJumlah(this)" onkeydown="handleEnterKeyJumlah(event, this)" placeholder="Masukkan harga"></td>
                <td class="hasil hasil-cell">0</td>
                <td>
                                <button class="btn btn-danger" onclick="hapusBarisJumlah(this)">
                                    <i class="fas fa-trash-alt" style="margin: 0; padding: 0;"></i>
                                </button>
                </td>`;



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
            jumlah.textContent = formatRibuan(nilaiJumlah.toFixed(0) + " Kg");

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
            document.getElementById("totalJumlahSak").textContent = formatRibuan(totalJumlah.toFixed(0) + " Kg");
            document.getElementById("totalHasilSak").textContent = "Rp " + formatRibuan(totalHasil.toFixed(0));
            document.getElementById("totalRataSak").textContent = "Rp " + formatRibuan(rataHarga.toFixed(0));

            // Panggil fungsi hitungSelisihSak setelah menghitung total
            hitungSelisihSak();
        }

        function tambahBarisSak() {
            let table = document.getElementById("sakTable");
            let row = table.insertRow(table.rows.length - 1);
            row.innerHTML = `
                <td><input inputmode="decimal" type="text" class="input-field sak" oninput="formatSak(this); hitungSak(this)" onkeydown="handleEnterKeySak(event, this)" placeholder="Jumlah sak"></td>
                <td><input inputmode="decimal" type="text" class="input-field harga" oninput="formatHarga(this); hitungSak(this)" onkeydown="handleEnterKeySak(event, this)" placeholder="Harga per kg"></td>
                <td class="jumlah jumlah-cell">0</td>
                <td class="hasil hasil-cell">0</td>
                <td>
                                <button class="btn btn-danger" onclick="hapusBarisSak(this)">
                                    <i class="fas fa-trash-alt" style="margin: 0; padding: 0;"></i>
                                </button>
                            </td>
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
