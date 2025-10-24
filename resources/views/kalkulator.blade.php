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

        .table-container2 {
    background: white;
    border-top: 1px solid rgba(108, 92, 231, 0.1);
    border-left: 1px solid rgba(108, 92, 231, 0.1);
    border-right: 1px solid rgba(108, 92, 231, 0.1);

    border-bottom: none; /* Tidak ada border bawah */

    border-top-left-radius: var(--border-radius);
    border-top-right-radius: var(--border-radius);
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;

    box-shadow: var(--card-shadow);
    padding: 10px;
    max-height: none;
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

        .total-label-TTL {
            background: linear-gradient(120deg, var(--primary-color), var(--primary-dark));
            color: white;
            font-weight: 600;
            letter-spacing: 1px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
            border-radius: 0 0 0 0;
            font-size: 14px; /* Slightly smaller */
        }

        .total-label-TTL::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            animation: shimmer 2s infinite;
        }

         .total-label-selisih {
            background: linear-gradient(120deg, var(--primary-color), var(--primary-dark));
            color: white;
            font-weight: 600;
            letter-spacing: 1px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
            border-radius: 10px 10px 0 0;
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
        .total-label-selisih::after {
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


         /* Styles for the modal */
    .modal-dialog-centered {
        display: flex;
        align-items: center;
        min-height: calc(100% - 1rem);
    }

    .modal-body {
        position: relative;
        padding: 0; /* Remove padding to make iframe fill */
    }

    .iframe-container {
        position: relative;
        width: 100%;
        padding-top: 141.42%; /* Aspect ratio for A4 portrait (297/210 * 100%) */
        overflow: hidden;
        background-color: #f0f0f0; /* Placeholder background */
        border-radius: var(--border-radius);
    }

    .iframe-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
    }

    .loading-spinner {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1;
    }

    /* Adjust modal size for better viewing of the nota */
    @media (min-width: 992px) {
        .modal-lg {
            --bs-modal-width: 800px; /* Adjust as needed for better view */
        }
    }

    /* Print button style */
    .btn-print {
        background: linear-gradient(120deg, var(--secondary-color), #00a8a4);
        color: white;
        width: 100%;
        height: 50px;
        font-size: 15px;
        border-radius: 0 0 var(--border-radius) var(--border-radius);

    }

    .btn-print:hover {
        background: linear-gradient(120deg, #00a8a4, var(--secondary-color));
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 206, 201, 0.3);
    }

.btn-save {
    background: linear-gradient(120deg, #2c3e50, #4b79a1);
    color: white;
    width: 100%;
    height: 50px;
    font-size: 15px;
    border-radius: 0 0 var(--border-radius) var(--border-radius);
}

.btn-save:hover {
    background: linear-gradient(120deg, #4b79a1, #2c3e50);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(75, 121, 161, 0.4);
}


    /* Specific styles for 80mm receipt */
    @page {
        size: 80mm auto; /* Set width to 80mm, height auto */
        margin: 0; /* Remove default margins */
    }

    .receipt-80mm {
        width: 80mm;
        padding: 5mm; /* Small padding inside the receipt */
        font-family: 'Segoe UI', sans-serif;
        font-size: 10px; /* Smaller font for receipts */
        line-height: 1.4;
        color: #000; /* Ensure black text for printing */
    }

    .receipt-80mm h2, .receipt-80mm h3, .receipt-80mm h4 {
        text-align: center;
        margin-bottom: 2mm;
        font-size: 12px;
    }

    .receipt-80mm .header-info, .receipt-80mm .footer-info {
        text-align: center;
        margin-bottom: 3mm;
    }

    .receipt-80mm table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 3mm;
    }

    .receipt-80mm th, .receipt-80mm td {
        border-bottom: 1px dashed #ccc;
        padding: 1mm 0;
        text-align: left;
    }

    .receipt-80mm th {
        font-weight: bold;
    }

    .receipt-80mm .text-right {
        text-align: right;
    }

    .receipt-80mm .total-section {
        border-top: 1px dashed #000;
        padding-top: 2mm;
        margin-top: 2mm;
    }

    .receipt-80mm .total-row td {
        border-bottom: none;
        font-weight: bold;
    }

    .receipt-80mm .thank-you {
        text-align: center;
        margin-top: 5mm;
        font-size: 11px;
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
                    <thead>
                        <tr>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Hasil</th>
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

<div class="table-container2">
            <table id="TableSelisih">
                <thead>
                    <tr>
                        <th>Jumlah Dana</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input inputmode="decimal" type="text" class="input-field dana" oninput="formatHarga(this); hitungSelisih()" onkeydown="handleEnterKeyDana(event, this)" placeholder="Jumlah Dana (Rp)"></td>
                        <td>
                            <button class="btn btn-danger" onclick="hapusBarisDana(this)">
                                <i class="fas fa-trash-alt" style="margin: 0; padding: 0;"></i>
                            </button>
                        </td>
                    </tr>

                    <tr class="total-row">
                        <td class="total-value hasil-cell" id="totalDana">0</td>
                        <td class="total-label-selisih">MSK</td>
                    </tr>

                    <tr class="total-row">
    <td class="total-value hasil-cell">
        <span id="totalHarga">Rp 0</span>
    </td>
    <td class="total-label-TTL">TTL</td>
</tr>

                    <tr class="total-row">
                        <td class="total-value hasil-cell" id="totalSelisih">0</td>
                        <td class="total-label">SSH</td>
                    </tr>

                </tbody>
            </table>
</div>

            <div class="action-bar">
    <button class="btn btn-primary" onclick="tambahBarisJumlah()">
        <i class="fas fa-plus"></i> TAMBAH BARIS
    </button>


        <button class="btn btn-print half" onclick="showNotaModal('jumlah')">
            <i class="fas fa-print"></i> CETAK NOTA
        </button>
        <button class="btn btn-save half" onclick="saveNotaAsJPG('jumlah')"
        {{-- style="width: 178px; text-align: center;"> --}}
        style="text-align: center;">
        <i class="fas fa-download"></i> SAVE GAMBAR
    </button>

</div>


        </div>

        <!-- Kalkulator Sak -->
        <div id="sakCalculator" class="calculator">
            <div class="table-container">
                <table id="sakTable">
                    <thead>
                        <tr>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Berat</th>
                            <th>Hasil</th>
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

<div class="table-container2">
            <table id="sakTableSelisih">
                <thead>
                    <tr>
                        <th>Jumlah Dana</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input inputmode="decimal" type="text" class="input-field dana" oninput="formatHarga(this); hitungSelisihSak()" onkeydown="handleEnterKeyDanaSak(event, this)" placeholder="Jumlah Dana (Rp)"></td>
                        <td>
                            <button class="btn btn-danger" onclick="hapusBarisDanaSak(this)">
                                <i class="fas fa-trash-alt" style="margin: 0; padding: 0;"></i>
                            </button>
                        </td>
                    </tr>
                    <tr class="total-row">
                        <td class="total-value hasil-cell" id="totalDanaSak">0</td>
                        <td class="total-label-selisih">MSK</td>
                    </tr>

                    <tr class="total-row">
    <td class="total-value hasil-cell">
        <span id="totalHargaSak">Rp 0</span>
    </td>
    <td class="total-label-TTL">TTL</td>
</tr>

                    <tr class="total-row">
                        <td class="total-value hasil-cell" id="totalSelisihSak">0</td>
                        <td class="total-label">SSH</td>
                    </tr>
                </tbody>
            </table>
</div>

            <div class="action-bar">
    <button class="btn btn-primary" onclick="tambahBarisSak()">
        <i class="fas fa-plus"></i> TAMBAH BARIS
    </button>

    <button class="btn btn-print" onclick="showNotaModal('sak')">
        <i class="fas fa-print"></i> CETAK NOTA
    </button>

      <button class="btn btn-save half" onclick="saveNotaAsJPG('sak')"
        {{-- style="width: 178px; text-align: center;"> --}}
        style=" text-align: center;">
        <i class="fas fa-download"></i> SAVE GAMBAR
    </button>
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

    <!-- Modal for displaying nota -->
    <div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header justify-content-center position-relative">
                    <h5 class="modal-title text-center" id="pdfModalLabel">
                        FORMAT NOTA
                    </h5>
                </div>
                <div class="modal-body px-4">
                    <div class="iframe-container">
                        <div class="loading-spinner text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Memuat nota...</p>
                        </div>
                        <iframe id="pdfViewer" src="" style="display: none;"></iframe>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>
                        Tutup
                    </button>
                    <button type="button" class="btn btn-primary" id="printPdf">
                        <i class="bi bi-printer me-2"></i>
                        Cetak Nota
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (Popper.js and Bootstrap JS) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>




       <script>

        // Fungsi untuk recalculate semua nilai saat page load
function recalculateAllOnLoad() {
    // Recalculate untuk Jumlah Calculator
    document.querySelectorAll("#jumlahTable tbody tr:not(.total-row)").forEach(row => {
        const jumlahInput = row.querySelector(".jumlah");
        const hargaInput = row.querySelector(".harga");
        if (jumlahInput && hargaInput) {
            if (jumlahInput.value || hargaInput.value) {
                hitungJumlah(jumlahInput);
            }
        }
    });

    // Recalculate Dana untuk Jumlah
    document.querySelectorAll('#TableSelisih .dana').forEach(input => {
        if (input.value) {
            hitungSelisih();
        }
    });

    // Recalculate untuk Sak Calculator
    document.querySelectorAll("#sakTable tbody tr:not(.total-row)").forEach(row => {
        const sakInput = row.querySelector(".sak");
        const hargaInput = row.querySelector(".harga");
        if (sakInput && hargaInput) {
            if (sakInput.value || hargaInput.value) {
                hitungSak(sakInput);
            }
        }
    });

    // Recalculate Dana untuk Sak
    document.querySelectorAll('#sakTableSelisih .dana').forEach(input => {
        if (input.value) {
            hitungSelisihSak();
        }
    });
}


// Fungsi untuk menyimpan data ke localStorage
function saveToLocalStorage() {
    // Simpan data Jumlah Calculator
    const jumlahData = [];
    document.querySelectorAll("#jumlahTable tbody tr:not(.total-row)").forEach(row => {
        const jumlah = row.querySelector(".jumlah").value;
        const harga = row.querySelector(".harga").value;
        if (jumlah || harga) {
            jumlahData.push({ jumlah, harga });
        }
    });
    localStorage.setItem('jumlahData', JSON.stringify(jumlahData));

    // Simpan data Dana Jumlah
    const danaJumlahData = [];
    document.querySelectorAll('#TableSelisih tbody tr:not(.total-row)').forEach(row => {
        const dana = row.querySelector(".dana").value;
        if (dana) {
            danaJumlahData.push({ dana });
        }
    });
    localStorage.setItem('danaJumlahData', JSON.stringify(danaJumlahData));

    // Simpan data Sak Calculator
    const sakData = [];
    document.querySelectorAll("#sakTable tbody tr:not(.total-row)").forEach(row => {
        const sak = row.querySelector(".sak").value;
        const harga = row.querySelector(".harga").value;
        if (sak || harga) {
            sakData.push({ sak, harga });
        }
    });
    localStorage.setItem('sakData', JSON.stringify(sakData));

    // Simpan data Dana Sak
    const danaSakData = [];
    document.querySelectorAll('#sakTableSelisih tbody tr:not(.total-row)').forEach(row => {
        const dana = row.querySelector(".dana").value;
        if (dana) {
            danaSakData.push({ dana });
        }
    });
    localStorage.setItem('danaSakData', JSON.stringify(danaSakData));
}

// Fungsi untuk restore data dari localStorage
function restoreFromLocalStorage() {
    // Restore Jumlah Calculator
    const jumlahData = JSON.parse(localStorage.getItem('jumlahData') || '[]');
    if (jumlahData.length > 0) {
        const jumlahTable = document.getElementById("jumlahTable");
        const jumlahTbody = jumlahTable.querySelector('tbody');

        // Hapus baris pertama yang kosong
        const firstRow = jumlahTbody.querySelector('tr:not(.total-row)');
        if (firstRow) firstRow.remove();

        jumlahData.forEach(data => {
            const row = jumlahTable.insertRow(jumlahTable.rows.length - 1);
            row.innerHTML = `
                <td><input inputmode="decimal" type="text" class="input-field jumlah" oninput="formatJumlah(this); hitungJumlah(this); saveToLocalStorage()" onkeydown="handleEnterKeyJumlah(event, this)" placeholder="Masukkan berat" value="${data.jumlah}"></td>
                <td><input inputmode="decimal" type="text" class="input-field harga" oninput="formatHarga(this); hitungJumlah(this); saveToLocalStorage()" onkeydown="handleEnterKeyJumlah(event, this)" placeholder="Masukkan harga" value="${data.harga}"></td>
                <td class="hasil hasil-cell">0</td>
                <td>
                    <button class="btn btn-danger" onclick="hapusBarisJumlah(this)">
                        <i class="fas fa-trash-alt" style="margin: 0; padding: 0;"></i>
                    </button>
                </td>`;
        });
    }

    // Restore Dana Jumlah
    const danaJumlahData = JSON.parse(localStorage.getItem('danaJumlahData') || '[]');
    if (danaJumlahData.length > 0) {
        const danaTable = document.getElementById("TableSelisih");
        const danaTbody = danaTable.querySelector('tbody');

        // Hapus baris pertama yang kosong
        const firstDanaRow = danaTbody.querySelector('tr:not(.total-row)');
        if (firstDanaRow) firstDanaRow.remove();

        danaJumlahData.forEach(data => {
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td><input inputmode="decimal" type="text" class="input-field dana" oninput="formatHarga(this); hitungSelisih(); saveToLocalStorage()" onkeydown="handleEnterKeyDana(event, this)" placeholder="Jumlah Dana (Rp)" value="${data.dana}"></td>
                <td>
                    <button class="btn btn-danger" onclick="hapusBarisDana(this)">
                        <i class="fas fa-trash-alt" style="margin: 0; padding: 0;"></i>
                    </button>
                </td>`;
            const totalRows = danaTbody.querySelectorAll('.total-row');
            danaTbody.insertBefore(newRow, totalRows[0]);
        });
    }

    // Restore Sak Calculator
    const sakData = JSON.parse(localStorage.getItem('sakData') || '[]');
    if (sakData.length > 0) {
        const sakTable = document.getElementById("sakTable");
        const sakTbody = sakTable.querySelector('tbody');

        // Hapus baris pertama yang kosong
        const firstSakRow = sakTbody.querySelector('tr:not(.total-row)');
        if (firstSakRow) firstSakRow.remove();

        sakData.forEach(data => {
            const row = sakTable.insertRow(sakTable.rows.length - 1);
            row.innerHTML = `
                <td><input inputmode="decimal" type="text" class="input-field sak" oninput="formatSak(this); hitungSak(this); saveToLocalStorage()" onkeydown="handleEnterKeySak(event, this)" placeholder="Jumlah sak" value="${data.sak}"></td>
                <td><input inputmode="decimal" type="text" class="input-field harga" oninput="formatHarga(this); hitungSak(this); saveToLocalStorage()" onkeydown="handleEnterKeySak(event, this)" placeholder="Harga per kg" value="${data.harga}"></td>
                <td class="jumlah jumlah-cell">0</td>
                <td class="hasil hasil-cell">0</td>
                <td>
                    <button class="btn btn-danger" onclick="hapusBarisSak(this)">
                        <i class="fas fa-trash-alt" style="margin: 0; padding: 0;"></i>
                    </button>
                </td>`;
        });
    }

    // Restore Dana Sak
    const danaSakData = JSON.parse(localStorage.getItem('danaSakData') || '[]');
    if (danaSakData.length > 0) {
        const danaSakTable = document.getElementById("sakTableSelisih");
        const danaSakTbody = danaSakTable.querySelector('tbody');

        // Hapus baris pertama yang kosong
        const firstDanaSakRow = danaSakTbody.querySelector('tr:not(.total-row)');
        if (firstDanaSakRow) firstDanaSakRow.remove();

        danaSakData.forEach(data => {
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td><input inputmode="decimal" type="text" class="input-field dana" oninput="formatHarga(this); hitungSelisihSak(); saveToLocalStorage()" onkeydown="handleEnterKeyDanaSak(event, this)" placeholder="Jumlah Dana (Rp)" value="${data.dana}"></td>
                <td>
                    <button class="btn btn-danger" onclick="hapusBarisDanaSak(this)">
                        <i class="fas fa-trash-alt" style="margin: 0; padding: 0;"></i>
                    </button>
                </td>`;
            const totalRows = danaSakTbody.querySelectorAll('.total-row');
            danaSakTbody.insertBefore(newRow, totalRows[0]);
        });
    }
}

// Update fungsi tambahBarisJumlah
function tambahBarisJumlah() {
    let table = document.getElementById("jumlahTable");
    let row = table.insertRow(table.rows.length - 1);
    row.innerHTML = `
        <td><input inputmode="decimal" type="text" class="input-field jumlah" oninput="formatJumlah(this); hitungJumlah(this); saveToLocalStorage()" onkeydown="handleEnterKeyJumlah(event, this)" placeholder="Masukkan berat"></td>
        <td><input inputmode="decimal" type="text" class="input-field harga" oninput="formatHarga(this); hitungJumlah(this); saveToLocalStorage()" onkeydown="handleEnterKeyJumlah(event, this)" placeholder="Masukkan harga"></td>
        <td class="hasil hasil-cell">0</td>
        <td>
            <button class="btn btn-danger" onclick="hapusBarisJumlah(this)">
                <i class="fas fa-trash-alt" style="margin: 0; padding: 0;"></i>
            </button>
        </td>`;
    setTimeout(() => {
        row.querySelector('.jumlah').focus();
    }, 100);
    saveToLocalStorage();
}

// Update fungsi hapusBarisJumlah
function hapusBarisJumlah(button) {
    let row = button.closest("tr");
    row.remove();
    hitungTotalJumlah();
    saveToLocalStorage();
}

// Update fungsi tambahBarisSak
function tambahBarisSak() {
    let table = document.getElementById("sakTable");
    let row = table.insertRow(table.rows.length - 1);
    row.innerHTML = `
        <td><input inputmode="decimal" type="text" class="input-field sak" oninput="formatSak(this); hitungSak(this); saveToLocalStorage()" onkeydown="handleEnterKeySak(event, this)" placeholder="Jumlah sak"></td>
        <td><input inputmode="decimal" type="text" class="input-field harga" oninput="formatHarga(this); hitungSak(this); saveToLocalStorage()" onkeydown="handleEnterKeySak(event, this)" placeholder="Harga per kg"></td>
        <td class="jumlah jumlah-cell">0</td>
        <td class="hasil hasil-cell">0</td>
        <td>
            <button class="btn btn-danger" onclick="hapusBarisSak(this)">
                <i class="fas fa-trash-alt" style="margin: 0; padding: 0;"></i>
            </button>
        </td>`;
    setTimeout(() => {
        row.querySelector('.sak').focus();
    }, 100);
    saveToLocalStorage();
}

// Update fungsi hapusBarisSak
function hapusBarisSak(button) {
    let row = button.closest("tr");
    row.remove();
    hitungTotalSak();
    saveToLocalStorage();
}

// Update fungsi hapusBarisDana
function hapusBarisDana(button) {
    let row = button.closest("tr");
    let inputRows = document.querySelectorAll('#TableSelisih tr:not(.total-row)');
    if (inputRows.length > 1) {
        row.remove();
    } else {
        row.querySelector('.dana').value = '';
    }
    hitungSelisih();
    saveToLocalStorage();
}

// Update fungsi hapusBarisDanaSak
function hapusBarisDanaSak(button) {
    let row = button.closest("tr");
    let inputRows = document.querySelectorAll('#sakTableSelisih tr:not(.total-row)');
    if (inputRows.length > 1) {
        row.remove();
    } else {
        row.querySelector('.dana').value = '';
    }
    hitungSelisihSak();
    saveToLocalStorage();
}

        const KG_PER_SAK = 50; // Konstanta: 1 Sak = 50 Kg

        function formatRibuan(angka) {
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        function getNumber(input) {
            return parseFloat(input.value.replace(/Rp /g, "").replace(/,/g, "")) || 0;
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
                if (input.classList.contains('jumlah')) {
                    row.querySelector('.harga').focus();
                } else if (input.classList.contains('harga')) {
                    const allRows = Array.from(document.querySelectorAll('#jumlahTable tr:not(.total-row)'));
                    const currentRowIndex = allRows.indexOf(row);
                    if (currentRowIndex === allRows.length - 1) {
                        tambahBarisJumlah();
                    } else {
                        allRows[currentRowIndex + 1].querySelector('.jumlah').focus();
                    }
                }
            }
        }

        // FUNCTIONS FOR SELISIH (JUMLAH CALCULATOR)
        function hitungSelisih() {
            let totalDana = 0;
            document.querySelectorAll('#TableSelisih .dana').forEach(input => {
                totalDana += getNumber(input);
            });
            let totalHasilJumlah = parseFloat(document.getElementById('totalHasilJumlah').textContent.replace(/Rp /g, "").replace(/,/g, "")) || 0;
            let totalSelisih = totalDana - totalHasilJumlah;
            let totalHarga = totalHasilJumlah;
            document.getElementById('totalDana').textContent = "Rp " + formatRibuan(totalDana.toFixed(0));
            document.getElementById('totalHarga').textContent = "Rp " + formatRibuan(totalHarga.toFixed(0));
            document.getElementById('totalSelisih').textContent = "Rp " + formatRibuan(totalSelisih.toFixed(0));
        }

        function tambahBarisDana() {
            let table = document.getElementById("TableSelisih");
            let tbody = table.querySelector('tbody');
            let newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td><input inputmode="decimal" type="text" class="input-field dana" oninput="formatHarga(this); hitungSelisih()" onkeydown="handleEnterKeyDana(event, this)" placeholder="Jumlah Dana (Rp)"></td>
                <td>
                    <button class="btn btn-danger" onclick="hapusBarisDana(this)">
                        <i class="fas fa-trash-alt" style="margin: 0; padding: 0;"></i>
                    </button>
                </td>`;
            let totalRows = tbody.querySelectorAll('.total-row');
            tbody.insertBefore(newRow, totalRows[0]);
            setTimeout(() => {
                newRow.querySelector('.dana').focus();
            }, 100);
        }

        function hapusBarisDana(button) {
            let row = button.closest("tr");
            let inputRows = document.querySelectorAll('#TableSelisih tr:not(.total-row)');
            if (inputRows.length > 1) {
                row.remove();
            } else {
                row.querySelector('.dana').value = '';
            }
            hitungSelisih();
        }

        function handleEnterKeyDana(event, input) {
            if (event.key === 'Enter') {
                event.preventDefault();
                const inputRows = Array.from(document.querySelectorAll('#TableSelisih tr:not(.total-row)'));
                const currentRow = input.closest('tr');
                const currentRowIndex = inputRows.indexOf(currentRow);
                if (currentRowIndex === inputRows.length - 1) {
                    tambahBarisDana();
                } else {
                    inputRows[currentRowIndex + 1].querySelector('.dana').focus();
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

            let nilaiJumlah = sak * KG_PER_SAK;
            jumlah.textContent = formatRibuan(nilaiJumlah.toFixed(0));

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

            let rataHarga = countHarga ? totalHarga / countHarga : 0;

            document.getElementById("totalSak").textContent = formatRibuan(totalSak.toFixed(0));
            document.getElementById("totalJumlahSak").textContent = formatRibuan(totalJumlah.toFixed(0) + " Kg");
            document.getElementById("totalHasilSak").textContent = "Rp " + formatRibuan(totalHasil.toFixed(0));
            document.getElementById("totalRataSak").textContent = "Rp " + formatRibuan(rataHarga.toFixed(0));

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
                </td>`;
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
                if (input.classList.contains('sak')) {
                    row.querySelector('.harga').focus();
                } else if (input.classList.contains('harga')) {
                    const allRows = Array.from(document.querySelectorAll('#sakTable tr:not(.total-row)'));
                    const currentRowIndex = allRows.indexOf(row);
                    if (currentRowIndex === allRows.length - 1) {
                        tambahBarisSak();
                    } else {
                        allRows[currentRowIndex + 1].querySelector('.sak').focus();
                    }
                }
            }
        }

        // FUNCTIONS FOR SELISIH (SAK CALCULATOR)
        function hitungSelisihSak() {
            let totalDana = 0;
            document.querySelectorAll('#sakTableSelisih .dana').forEach(input => {
                totalDana += getNumber(input);
            });
            let totalHasilSak = parseFloat(document.getElementById('totalHasilSak').textContent.replace(/Rp /g, "").replace(/,/g, "")) || 0;
            let totalSelisih = totalDana - totalHasilSak;
            let totalHargaSak = totalHasilSak;
            document.getElementById('totalHargaSak').textContent = "Rp " + formatRibuan(totalHargaSak.toFixed(0));
            document.getElementById('totalDanaSak').textContent = "Rp " + formatRibuan(totalDana.toFixed(0));
            document.getElementById('totalSelisihSak').textContent = "Rp " + formatRibuan(totalSelisih.toFixed(0));
        }

        function tambahBarisDanaSak() {
            let table = document.getElementById("sakTableSelisih");
            let tbody = table.querySelector('tbody');
            let newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td><input inputmode="decimal" type="text" class="input-field dana" oninput="formatHarga(this); hitungSelisihSak()" onkeydown="handleEnterKeyDanaSak(event, this)" placeholder="Jumlah Dana (Rp)"></td>
                <td>
                    <button class="btn btn-danger" onclick="hapusBarisDanaSak(this)">
                        <i class="fas fa-trash-alt" style="margin: 0; padding: 0;"></i>
                    </button>
                </td>`;
            let totalRows = tbody.querySelectorAll('.total-row');
            tbody.insertBefore(newRow, totalRows[0]);
            setTimeout(() => {
                newRow.querySelector('.dana').focus();
            }, 100);
        }

        function hapusBarisDanaSak(button) {
            let row = button.closest("tr");
            let inputRows = document.querySelectorAll('#sakTableSelisih tr:not(.total-row)');
            if (inputRows.length > 1) {
                row.remove();
            } else {
                row.querySelector('.dana').value = '';
            }
            hitungSelisihSak();
        }

        function handleEnterKeyDanaSak(event, input) {
            if (event.key === 'Enter') {
                event.preventDefault();
                const inputRows = Array.from(document.querySelectorAll('#sakTableSelisih tr:not(.total-row)'));
                const currentRow = input.closest('tr');
                const currentRowIndex = inputRows.indexOf(currentRow);
                if (currentRowIndex === inputRows.length - 1) {
                    tambahBarisDanaSak();
                } else {
                    inputRows[currentRowIndex + 1].querySelector('.dana').focus();
                }
            }
        }

        function toggleCalculator(type) {
            document.querySelectorAll('.toggle-btn').forEach(btn => btn.classList.remove('active'));
            event.currentTarget.classList.add('active');
            document.querySelectorAll('.calculator').forEach(calc => calc.classList.remove('active'));
            if (type === 'jumlah') {
                document.getElementById('jumlahCalculator').classList.add('active');
            } else if (type === 'sak') {
                document.getElementById('sakCalculator').classList.add('active');
            }
        }

       // NOTA GENERATION AND MODAL FUNCTIONS
function generateNotaHTML(calculatorType) {
   let notaHTML = `
<!DOCTYPE html>
<html>
<head>
    <title>Nota Pembelian Beras</title>
    <style>
        body {
            width: 80mm;
            margin: 0;
            padding: 4mm;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.2;
            color: #000;
            background: white;
        }

        .header-text {
            text-align: center;
            margin-bottom: 4mm;
            padding-bottom: 3mm;
            border-bottom: 1px solid #000;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 3mm;
            letter-spacing: 1px;
        }

        .title2 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 3mm;
            letter-spacing: 0.5px;
        }

        .header-text div:not(.title):not(.title2) {
            font-size: 12px;
            margin-bottom: 1mm;
        }

        .header-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            font-size: 14px;
            padding: 2mm 0;
            margin-bottom: 3mm;
        }

        .header-info .tanggal {
            margin: 0;
            text-align: left;
font-size: 12px;
        }

        .header-foot {
            margin: 0;
            text-align: right;
font-size: 10px;
        }

                .header-info .waktu {
            margin: 0;
            text-align: right;
font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4mm;
        }

        th {
            font-weight: bold;
            font-size: 14px;
            padding: 2mm 1mm;
            border-bottom: 1px solid #000;
            border-top: 1px solid #000;
            text-align: center;
        }

        td {
            padding: 2mm 1mm;
            border-bottom: 1px dashed #000;

            font-size: 14px;
            vertical-align: middle;
        }

        th:first-child, td:first-child {
            width: 25%;
            text-align: left;
            font-weight: normal;
        }

        th:nth-child(2), td:nth-child(2) {
            width: 30%;
            text-align: center;
            font-weight: normal;
        }

        th:last-child, td:last-child {
            width: 45%;
            text-align: right;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
            font-weight: bold;
        }

        .total-section {
            border-top: 1px solid #000;
            padding-top: 3mm;
            margin-top: 3mm;

        }

        .total-section table {
            margin-bottom: 0;
        }

        .total-row td {
            border-bottom: none;
            font-weight: bold;
            font-size: 14px;
            padding: 1.5mm 1mm;
        }

        .total-row td:first-child {
    width: 29%;
    text-align: left;
}

.total-row td:nth-child(2) {
    width: 21%;
    text-align: left;
}

.total-row td:last-child {
    width: 50%;
    text-align: right;
    font-size: 14px;
}


        .footer-info {
            text-align: center;
            margin-top: 3mm;

             border-top: 1px solid #000;
        }

        .footer-info p {
padding-top: 6mm;
        }

        .thank-you {
            font-size: 14px;
            font-weight: bold;
            margin-top: 2mm;
        }

        .separator {
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        /* Responsive adjustments for better readability */
        @media print {
            body {
                width: 80mm;
                font-size: 14px;
            }

            .title {
                font-size: 17px;
            }

            .title2 {
                font-size: 15px;
            }

            .header-text div:not(.title):not(.title2) {
                font-size: 11px;
            }
        }
    </style>
</head>
<body>
    <div class="header-text">
        <div class="title">NOTA PEMBELIAN BERAS</div>
        <div class="title2">GILINGAN PADI PUTRA MANUABA</div>
        <div>DUS. BABAHAN, DES. TOLAI, KAB. PARIGI</div>
        <div>Telp: 0811-451-486 / 0822-6077-3867</div>
    </div>

   <div class="header-info">
    <p class="tanggal">Tanggal: ${new Date().toLocaleDateString('id-ID')}</p>

<p class="waktu">Waktu: ${new Date().getHours().toString().padStart(2, '0')}:${new Date().getMinutes().toString().padStart(2, '0')}:${new Date().getSeconds().toString().padStart(2, '0')}</p>


    </div>

    <table>
        <thead>
            <tr>
    <th class="text-right" style="font-weight: bold;">JUMLAH</th>
    <th class="text-right" style="font-weight: bold;">HARGA</th>
    <th class="text-right" style="font-weight: bold;">TOTAL</th>
</tr>
        </thead>
        <tbody>
            `;

            let items = [];
            let totalHargaBeras = 0;
            let totalDanaDibayar = 0;
            let selisih = 0;

            if (calculatorType === 'jumlah') {
                document.querySelectorAll("#jumlahTable tbody tr:not(.total-row)").forEach(row => {
                    const jumlah = row.querySelector(".jumlah").value;
                    const harga = row.querySelector(".harga").value;
                    const hasil = row.querySelector(".hasil").textContent;
                    if (getNumber(row.querySelector(".jumlah")) > 0 && getNumber(row.querySelector(".harga")) > 0) {
                        items.push({
                            desc: `${jumlah}`,
                            harga: harga,
                            total: hasil
                        });
                    }
                });
                totalHargaBeras = parseFloat(document.getElementById('totalHasilJumlah').textContent.replace(/Rp /g, "").replace(/,/g, "")) || 0;
                totalDanaDibayar = parseFloat(document.getElementById('totalDana').textContent.replace(/Rp /g, "").replace(/,/g, "")) || 0;
                selisih = parseFloat(document.getElementById('totalSelisih').textContent.replace(/Rp /g, "").replace(/,/g, "")) || 0;

            } else if (calculatorType === 'sak') {
                document.querySelectorAll("#sakTable tbody tr:not(.total-row)").forEach(row => {
                    const sak = row.querySelector(".sak").value;
                    const harga = row.querySelector(".harga").value;
                    const berat = row.querySelector(".jumlah").textContent;
                    const hasil = row.querySelector(".hasil").textContent;
                    if (getNumber(row.querySelector(".sak")) > 0 && getNumber(row.querySelector(".harga")) > 0) {
                        items.push({
                            desc: `${berat} (${sak} Sak)`,
                            harga: harga,
                            total: hasil
                        });
                    }
                });
                totalHargaBeras = parseFloat(document.getElementById('totalHasilSak').textContent.replace(/Rp /g, "").replace(/,/g, "")) || 0;
                totalDanaDibayar = parseFloat(document.getElementById('totalDanaSak').textContent.replace(/Rp /g, "").replace(/,/g, "")) || 0;
                selisih = parseFloat(document.getElementById('totalSelisihSak').textContent.replace(/Rp /g, "").replace(/,/g, "")) || 0;
            }
items.forEach(item => {
// Modifikasi untuk memisahkan berat dan sak menjadi 2 baris
const formatDesc = (desc) => {
    // Memisahkan berat dan sak dari format "berat (sak Sak)"
    const match = desc.match(/^(.+?)\s+\((.+?)\)$/);
    if (match) {
        const berat = match[1];
        const sak = match[2];
        return `${berat}<br><span style="font-size: 8px;">${sak}</span>`;
    }
    return desc;
};

notaHTML += `
    <tr>
        <td style="font-weight: bold;">${item.desc.replace(/^(.+?)\s+\((.+?)\)$/, '$1<br><span style="font-size: 12px;">$2</span>')}</td>
        <td class="text-right" style="font-weight: bold;">${item.harga}</td>
        <td class="text-right" style="font-weight: bold;">${item.total}</td>
    </tr>
`;
});


           notaHTML += `
        </tbody>
    </table>
    <div class="total-section">
        <table>
            <tr class="total-row">
                <td class="text-left">Total</td>
                <td class="text-left">:</td>
                <td class="text-right">Rp ${formatRibuan(totalHargaBeras.toFixed(0))}</td>
            </tr>
            <tr class="total-row">
                <td class="text-left">Terbayar</td>
                <td class="text-left">:</td>
                <td class="text-right">Rp ${formatRibuan(totalDanaDibayar.toFixed(0))}</td>
            </tr>
            <tr class="total-row">
                <td class="text-left">Sisa</td>
                <td class="text-left">:</td>
                <td class="text-right">Rp ${formatRibuan(selisih.toFixed(0))}</td>
            </tr>
        </table>
    </div>
    <div class="footer-info">
        <p class="thank-you">Terima Kasih Atas Kunjungan Anda!</p>
    </div>

     <div class="header-foot">
    <div class="waktu" style="display: flex; justify-content: space-between;">
        <div style="text-transform: none !important; font-weight: normal !important;">.</div>
        <div style="text-transform: none !important; font-weight: normal !important;">.</div>
    </div>
    <div class="waktu" style="display: flex; justify-content: space-between;">
        <div style="text-transform: none !important; font-weight: normal !important;">.</div>
        <div style="text-transform: none !important; font-weight: normal !important;">.</div>
    </div>
    <div class="waktu" style="display: flex; justify-content: space-between;">
        <div style="text-transform: none !important; font-weight: normal !important;">.</div>
        <div style="text-transform: none !important; font-weight: normal !important;">.</div>
    </div>
    <div class="waktu" style="display: flex; justify-content: space-between;">
        <div style="text-transform: none !important; font-weight: normal !important;">.</div>
        <div style="text-transform: none !important; font-weight: normal !important;">.</div>
    </div>
    <div class="waktu" style="display: flex; justify-content: space-between;">
        <div style="text-transform: none !important; font-weight: normal !important;">.</div>
        <div style="text-transform: none !important; font-weight: normal !important;">.</div>
    </div>
</div>


</body>


</html>
`;
            return notaHTML;
        }


function showNotaModal(calculatorType) {
    const notaContent = generateNotaHTML(calculatorType);

    const iframe = document.createElement('iframe');
    iframe.style.position = 'absolute';
    iframe.style.left = '-9999px';
    iframe.style.top = '-9999px';
    iframe.style.width = '0px';
    iframe.style.height = '0px';
    iframe.style.border = 'none';

    document.body.appendChild(iframe);

    const printHTML = `
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                @page {
                    size: 80mm auto;
                    margin: 0 !important;
                }
                * { box-sizing: border-box !important; }
                body {
                    margin: 0 !important;
                    padding: 10px 8px 10px 8px !important; /* 👈 Tambahkan padding keliling */
                    width: 80mm !important;
                    max-width: 80mm !important;
                    background: white;
                }
                @media print {
                    @page { size: 80mm auto; margin: 0 !important; }
                    body {
                        margin: 0 !important;
                        padding: 10px 8px 10px 8px !important; /* padding tetap di mode print */
                    }
                }
            </style>
        </head>
        <body>
            ${notaContent}
        </body>
        </html>
    `;

    iframe.contentDocument.open();
    iframe.contentDocument.write(printHTML);
    iframe.contentDocument.close();

    iframe.onload = async function() {
        const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
        const iframeBody = iframeDoc.body;

        // Tunggu render sedikit
        await new Promise(resolve => setTimeout(resolve, 300));

        // Konversi ke canvas
        const canvas = await html2canvas(iframeBody, {
            scale: 2, // gunakan scale tinggi untuk hasil tajam
            useCORS: true,
            backgroundColor: '#fff'
        });

        const imgData = canvas.toDataURL('image/png');

        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF({
            orientation: 'portrait',
            unit: 'px',
            format: [canvas.width, canvas.height]
        });

        pdf.addImage(imgData, 'PNG', 0, 0, canvas.width, canvas.height);

        // Buat blob untuk print
        const blob = pdf.output('blob');
        const pdfUrl = URL.createObjectURL(blob);

        const printFrame = document.createElement('iframe');
        printFrame.style.position = 'fixed';
        printFrame.style.right = '0';
        printFrame.style.bottom = '0';
        printFrame.style.width = '0';
        printFrame.style.height = '0';
        printFrame.style.border = 'none';
        printFrame.src = pdfUrl;
        document.body.appendChild(printFrame);

        printFrame.onload = function() {
            printFrame.contentWindow.focus();
            printFrame.contentWindow.print();

            // bersihkan setelah print
            setTimeout(() => {
                URL.revokeObjectURL(pdfUrl);
                document.body.removeChild(printFrame);
                document.body.removeChild(iframe);
            }, 2000);
        };
    };
}

// async function showNotaModal(calculatorType) {
//     const notaContent = generateNotaHTML(calculatorType);

//     // --- buat iframe tersembunyi ---
//     const iframe = document.createElement('iframe');
//     iframe.style.position = 'absolute';
//     iframe.style.left = '-9999px';
//     iframe.style.top = '-9999px';
//     iframe.style.width = '0';
//     iframe.style.height = '0';
//     iframe.style.border = 'none';
//     document.body.appendChild(iframe);

//     const html = `
//         <!DOCTYPE html>
//         <html>
//         <head>
//             <meta charset="UTF-8">
//             <style>
//                 @page { size: 80mm auto; margin: 0; }
//                 * { box-sizing: border-box; }
//                 body {
//                     margin: 0;
//                     padding: 12px 10px;
//                     width: 80mm;
//                     max-width: 80mm;
//                     background: white;
//                     font-family: "Arial", sans-serif;
//                     -webkit-print-color-adjust: exact !important;
//                 }
//             </style>
//         </head>
//         <body>${notaContent}</body>
//         </html>
//     `;

//     iframe.contentDocument.open();
//     iframe.contentDocument.write(html);
//     iframe.contentDocument.close();

//     iframe.onload = async function () {
//         const iframeBody = iframe.contentDocument.body;

//         // Tunggu render selesai
//         await new Promise((r) => setTimeout(r, 300));

//         // === 🔍 Render tajam dengan html2canvas ===
//         const scale = window.devicePixelRatio * 4; // bisa 3–6 tergantung tajam yang diinginkan
//         const canvas = await html2canvas(iframeBody, {
//             scale: scale,
//             useCORS: true,
//             backgroundColor: '#fff',
//             logging: false,
//         });

//         // Pastikan hasilnya dalam ukuran mm yang benar
//         const imgData = canvas.toDataURL('image/png', 1.0); // kualitas 100%

//         // === Buat PDF resolusi tinggi ===
//         const { jsPDF } = window.jspdf;
//         const pdfWidth = 80; // mm
//         const pxPerMm = canvas.width / pdfWidth;
//         const pdfHeight = canvas.height / pxPerMm;

//         const pdf = new jsPDF({
//             orientation: 'portrait',
//             unit: 'mm',
//             format: [pdfWidth, pdfHeight],
//         });

//         pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight, '', 'FAST');

//         // === Print otomatis ===
//         const blob = pdf.output('blob');
//         const pdfUrl = URL.createObjectURL(blob);

//         const printFrame = document.createElement('iframe');
//         printFrame.style.position = 'fixed';
//         printFrame.style.right = '0';
//         printFrame.style.bottom = '0';
//         printFrame.style.width = '0';
//         printFrame.style.height = '0';
//         printFrame.src = pdfUrl;
//         document.body.appendChild(printFrame);

//         printFrame.onload = function () {
//             printFrame.contentWindow.focus();
//             printFrame.contentWindow.print();

//             setTimeout(() => {
//                 URL.revokeObjectURL(pdfUrl);
//                 document.body.removeChild(printFrame);
//                 document.body.removeChild(iframe);
//             }, 2000);
//         };
//     };
// }


async function saveNotaAsJPG(calculatorType) {
    const notaContent = generateNotaHTML(calculatorType);

    // Buat iframe tersembunyi
    const iframe = document.createElement('iframe');
    iframe.style.position = 'absolute';
    iframe.style.left = '-9999px';
    iframe.style.top = '-9999px';
    iframe.style.width = '400px'; // beri ukuran agar browser render penuh
    iframe.style.height = 'auto';
    iframe.style.border = 'none';
    document.body.appendChild(iframe);

    const html = `
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                @page { size: 80mm auto; margin: 0; }
                * { box-sizing: border-box; }
                body {
                    margin: 0;
                    padding: 12px 10px;
                    width: 80mm;
                    max-width: 80mm;
                    background: white;
                    font-family: "Arial", sans-serif;
                    -webkit-print-color-adjust: exact !important;
                    transform: scale(2); /* render dua kali lebih besar */
                    transform-origin: top left;
                }
            </style>
        </head>
        <body>${notaContent}</body>
        </html>
    `;

    iframe.contentDocument.open();
    iframe.contentDocument.write(html);
    iframe.contentDocument.close();

    iframe.onload = async function () {
        const iframeBody = iframe.contentDocument.body;

        // Pastikan font dan gambar sudah ter-load
        await new Promise(resolve => setTimeout(resolve, 600));

        // Render dengan resolusi tinggi
        const canvas = await html2canvas(iframeBody, {
            scale: 6, // scale tinggi = tajam
            useCORS: true,
            backgroundColor: '#ffffff',
            windowWidth: iframeBody.scrollWidth * 2,
            windowHeight: iframeBody.scrollHeight * 2
        });

        // Konversi ke JPG tajam
        const imgData = canvas.toDataURL('image/jpeg', 1.0);

        // Buat link download
        const link = document.createElement('a');
        link.href = imgData;
        link.download = `nota_${new Date().toISOString().slice(0,19).replace(/[:T]/g,'-')}.jpg`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        // Bersihkan
        document.body.removeChild(iframe);
    };
}




function showNotaModalAndroid(calculatorType) {
    const notaContent = generateNotaHTML(calculatorType);

    const printHTML = `
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                @page {
                    size: 80mm auto;
                    margin: 0 !important;
                }
                * {
                    box-sizing: border-box !important;
                }
                body {
                    margin: 0 !important;
                    padding: 0 !important;
                    width: 80mm !important;
                    max-width: 80mm !important;
                }
                @media print {
                    @page {
                        size: 80mm auto;
                        margin: 0 !important;
                    }
                    body {
                        margin: 0 !important;
                        padding: 0 !important;
                    }
                }
            </style>
        </head>
        <body>
            ${notaContent}
        </body>
        </html>
    `;

    const printWindow = window.open('', '_blank');

    if (printWindow) {
        printWindow.document.open();
        printWindow.document.write(printHTML);
        printWindow.document.close();

        printWindow.onload = function() {
            printWindow.focus();
            printWindow.print();

            printWindow.onafterprint = function() {
                printWindow.close();
            };
        };
    } else {
        alert('Tidak dapat membuka dialog print. Pastikan pop-up diizinkan.');
    }
}

function printNotaDirect(calculatorType) {
    const notaContent = generateNotaHTML(calculatorType);

    const printStyles = `
        @page {
            size: 80mm auto;
            margin: 0 !important;
        }
        @media print {
            body {
                margin: 0 !important;
                padding: 0 !important;
                width: 80mm !important;
                max-width: 80mm !important;
            }
        }
    `;

    const style = document.createElement('style');
    style.innerHTML = printStyles;
    document.head.appendChild(style);

    const printDiv = document.createElement('div');
    printDiv.innerHTML = notaContent;
    document.body.appendChild(printDiv);

    window.print();

    // setTimeout(() => {
    //     document.body.removeChild(printDiv);
    //     document.head.removeChild(style);
    // }, 1000);
}


        // Window resizing
        window.addEventListener('resize', adjustHeight);

        function adjustHeight() {
            let vh = window.innerHeight;
            document.documentElement.style.setProperty('--vh', `${vh}px`);
        }

        adjustHeight();

        // Initial calculations on load
        document.addEventListener('DOMContentLoaded', () => {
            // Restore data dari localStorage
    restoreFromLocalStorage();

    // Tunggu sebentar untuk memastikan browser sudah restore nilai
    setTimeout(() => {
        recalculateAllOnLoad();
    }, 100);
            hitungTotalJumlah();
            hitungSelisih();
            hitungTotalSak();
            hitungSelisihSak();
        });
    </script>
</body>
</html>
