<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Nota Giling Sementara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- html2canvas for PNG export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        /* ============================================
           ROOT VARIABLES - Matching buku-gilingan theme
        ============================================ */
        :root {
            --primary-gradient: linear-gradient(135deg, #cb0c9f 0%, #e91e8c 100%);
            --primary-color: #cb0c9f;
            --primary-light: rgba(203, 12, 159, 0.1);
            --primary-lighter: rgba(203, 12, 159, 0.04);
            --secondary-color: #8392ab;
            --success-gradient: linear-gradient(135deg, #17ad37 0%, #98ec2d 100%);
            --success-color: #17ad37;
            --warning-gradient: linear-gradient(135deg, #f5365c 0%, #f56036 100%);
            --warning-color: #f5365c;
            --info-gradient: linear-gradient(135deg, #2152ff 0%, #21d4fd 100%);
            --info-color: #2152ff;
            --text-dark: #344767;
            --text-muted: #8392ab;
            --border-color: #e9ecef;
            --bg-light: #f8f9fa;
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --border-radius: 16px;
            --border-radius-sm: 12px;
            --border-radius-xs: 8px;
        }

        /* ============================================
           BASE STYLES
        ============================================ */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: var(--bg-light);
            min-height: 100vh;
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, sans-serif;
            color: var(--text-dark);
            overflow-x: hidden;
        }

        html, body {
            width: 100%;
        }

        /* ============================================
           MAIN CONTAINER
        ============================================ */
        .main-container {
            padding: 1.5rem 1rem;
            max-width: 900px;
            margin: 0 auto;
        }

        /* ============================================
           CARD STYLES - Matching buku-gilingan
        ============================================ */
        .form-card {
            background: #ffffff;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            border: none;
            overflow: hidden;
        }

        .card-header-custom {
            background: var(--primary-gradient);
            color: white;
            padding: 1.25rem 1.5rem;
            border: none;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .card-title .icon-wrapper {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-title i {
            font-size: 1.25rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* ============================================
           SECTION TITLE - Matching buku-gilingan tabs
        ============================================ */
        .section-title {
            color: var(--primary-color);
            font-size: 0.9rem;
            font-weight: 700;
            margin-bottom: 1.25rem;
            padding: 0.75rem 1rem;
            background: var(--primary-lighter);
            border-radius: var(--border-radius-xs);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border-left: 4px solid var(--primary-color);
        }

        .section-title i {
            font-size: 1rem;
        }

        /* ============================================
           FORM STYLES
        ============================================ */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            display: block;
            letter-spacing: 0.3px;
        }

        .form-control {
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius-xs);
            padding: 0.625rem 0.875rem;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            background: #ffffff;
            color: var(--text-dark);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px var(--primary-light);
            outline: none;
        }

        .form-control::placeholder {
            color: #adb5bd;
        }
#pulang::placeholder {
    color: var(--primary-color) !important;
    opacity: 1;
    font-weight: 500;
}

        .input-group-text {
            background: var(--bg-light);
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius-xs) 0 0 var(--border-radius-xs);
            color: var(--secondary-color);
            border-right: none;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 var(--border-radius-xs) var(--border-radius-xs) 0;
        }

        /* ============================================
           BUTTONS - Matching buku-gilingan
        ============================================ */
        .btn {
            border-radius: var(--border-radius-xs);
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-primary-gradient {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 4px 15px rgba(203, 12, 159, 0.3);
        }

        .btn-primary-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(203, 12, 159, 0.4);
            color: white;
        }

        .btn-success-gradient {
            background: var(--success-gradient);
            color: white;
            box-shadow: 0 4px 15px rgba(23, 173, 55, 0.3);
        }

        .btn-success-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(23, 173, 55, 0.4);
            color: white;
        }

        .btn-info-gradient {
            background: var(--info-gradient);
            color: white;
            box-shadow: 0 4px 15px rgba(33, 82, 255, 0.3);
        }

        .btn-info-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(33, 82, 255, 0.4);
            color: white;
        }

        .btn-danger-gradient {
            background: var(--warning-gradient);
            color: white;
            box-shadow: 0 4px 15px rgba(245, 54, 92, 0.3);
        }

        .btn-danger-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(245, 54, 92, 0.4);
            color: white;
        }

        .btn-secondary-outline {
            background: transparent;
            color: var(--secondary-color);
            border: 2px solid var(--border-color);
        }

        .btn-secondary-outline:hover {
            background: var(--bg-light);
            color: var(--text-dark);
        }

        /* ============================================
           PENGAMBILAN SECTION
        ============================================ */
        .pengambilan-item {
            background: var(--bg-light);
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius-sm);
            padding: 1rem;
            margin-bottom: 0.75rem;
            transition: all 0.3s ease;
        }

        .pengambilan-item:hover {
            border-color: var(--primary-color);
            box-shadow: 0 4px 12px var(--primary-light);
        }

        .delete-btn {
            width: 100%;
            height: 100%;
            min-height: 42px;
        }

        /* ============================================
           SUBMIT SECTION
        ============================================ */
        .submit-section {
            padding: 1rem;
            background: var(--bg-light);
            border-radius: var(--border-radius-sm);
            margin-top: 1rem;
        }

        .btn-submit {
            background: var(--primary-gradient);
            border: none;
            color: white;
            padding: 0.875rem 2rem;
            font-weight: 600;
            font-size: 0.9rem;
            border-radius: var(--border-radius-xs);
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(203, 12, 159, 0.3);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(203, 12, 159, 0.4);
            color: white;
        }

        /* ============================================
           MODAL STYLES - Matching theme
        ============================================ */
        .modal-content {
            border-radius: var(--border-radius);
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .modal-header {
            background: var(--primary-gradient);
            color: white;
            padding: 1rem 1.5rem;
            border: none;
        }

        .modal-title {
            font-weight: 700;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modal-title i {
            font-size: 1.1rem;
        }

        .btn-close {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            opacity: 1;
            padding: 0.5rem;
            transition: all 0.2s ease;
        }

        .btn-close:hover {
            transform: scale(1.1);
            opacity: 1;
        }

        .modal-body {
            padding: 1.5rem;
            background: var(--bg-light);
        }

        .iframe-container {
            position: relative;
            width: 100%;
            height: 65vh;
            border-radius: var(--border-radius-sm);
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            background: white;
        }

        .iframe-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .loading-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 2;
            text-align: center;
        }

        .loading-spinner .spinner-border {
            color: var(--primary-color);
            width: 3rem;
            height: 3rem;
        }

        .loading-spinner p {
            color: var(--text-muted);
            margin-top: 0.75rem;
            font-size: 0.875rem;
        }

        .modal-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
            background: #ffffff;
            border-top: 1px solid var(--border-color);
            gap: 1rem;
        }

        .modal-footer .btn {
            flex: 1;
            max-width: 200px;
        }

        @media (max-width: 576px) {
            .modal-footer {

            }
            .modal-footer .btn {
                max-width: 100%;
                width: 100%;
            }
        }

        /* ============================================
           ALERT STYLES
        ============================================ */
        .alert-info-custom {
            background: linear-gradient(135deg, rgba(33, 82, 255, 0.1) 0%, rgba(33, 212, 253, 0.1) 100%);
            border: none;
            border-radius: var(--border-radius-xs);
            color: var(--info-color);
            padding: 1rem 1.25rem;
            border-left: 4px solid var(--info-color);
        }

        .alert-info-custom i {
            font-size: 1.1rem;
        }

        /* ============================================
           ROW GRID IMPROVEMENTS
        ============================================ */
        .row {
            --bs-gutter-x: 1rem;
        }

        /* ============================================
           ANIMATIONS
        ============================================ */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        /* ============================================
           RESPONSIVE STYLES
        ============================================ */
        @media (max-width: 768px) {
            .main-container {
                padding: 1rem 0.75rem;
            }

            .card-body {
                padding: 1.25rem;
            }

            .section-title {
                font-size: 0.85rem;
                padding: 0.625rem 0.875rem;
            }

            .form-label {
                font-size: 0.8rem;
            }

            .modal-lg {
                max-width: 95%;
                margin: 1rem auto;
            }

            .iframe-container {
                height: 55vh;
            }
        }

        @media (max-width: 576px) {
            .card-title {
                font-size: 1.1rem;
            }

            .card-title .icon-wrapper {
                width: 36px;
                height: 36px;
            }

            .card-title i {
                font-size: 1rem;
            }
        }

        /* ============================================
           PRINT STYLES
        ============================================ */
        @media print {
            body {
                background: white !important;
            }

            .no-print {
                display: none !important;
            }
        }

        /* ============================================
           SCROLLBAR STYLING
        ============================================ */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-light);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-gradient);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-color);
        }

        /* ============================================
           NOTA PREVIEW CONTAINER (for PNG export)
        ============================================ */
        #nota-preview-container {
            position: fixed;
            left: -9999px;
            top: 0;
            background: white;
            z-index: -1;
        }

        /* ============================================
           DOWNLOAD PROGRESS
        ============================================ */
        .download-progress {
            display: none;
            text-align: center;
            padding: 2rem;
        }

        .download-progress.active {
            display: block;
        }

        .download-progress .spinner-border {
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <div class="container-fluid main-container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card form-card fade-in">
                    <!-- Card Header -->
                    <div class="card-header-custom">
                        <h1 class="card-title">
                            <div class="icon-wrapper">
                                <i class="bi bi-receipt-cutoff"></i>
                            </div>
                            <div>
                                <span>Nota Giling Sementara</span>
                                <div style="font-size: 0.75rem; font-weight: 400; opacity: 0.9; margin-top: 2px;">
                                    Penggilingan Padi Putra Manuaba
                                </div>
                            </div>
                        </h1>
                    </div>

                    <div class="card-body">
                        <form id="gilingForm">
                            <!-- Informasi Petani -->
                            <div class="section-title">
                                <i class="bi bi-person-circle"></i>
                                Informasi Petani
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama_petani" class="form-label">Nama Petani</label>
                                        <input type="text" id="nama_petani" name="nama_petani" class="form-control"
                                               placeholder="Masukkan nama petani..." required>
                                    </div>
                                </div>
                                <div class="col-md-6 d-none">
                                    <div class="form-group">
                                        <label for="tanggal_nota" class="form-label">Tanggal Nota</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-calendar3"></i>
                                            </span>
                                            <input type="date" class="form-control" id="tanggal_nota" name="tanggal_nota" >
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Data Penggilingan -->
                            <div class="section-title">
                                <i class="bi bi-gear-fill"></i>
                                Data Penggilingan
                            </div>

                            <div class="row">
                                <div class="col-md-3 col-6">
                                    <div class="form-group">
                                        <label for="giling_kotor" class="form-label">Giling Kotor (Kg)</label>
                                        <input class="form-control number-format" type="text" name="giling_kotor" id="giling_kotor"
                                               inputmode="numeric" placeholder="0" required>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="form-group">
                                        <label for="pulang" class="form-label">Beras Pulang (Kg)</label>
                                        <input class="form-control number-format" type="text" name="pulang" id="pulang"
                                               inputmode="numeric" placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="form-group">
                                        <label for="pinjam" class="form-label">Pinjaman Beras (Kg)</label>
                                        <input class="form-control number-format" type="text" name="pinjam" id="pinjam"
                                               inputmode="numeric" placeholder="0" required>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="form-group">
                                        <label for="jemur" class="form-label">Jemur (Karung)</label>
                                        <input class="form-control number-format" type="text" name="jemur" id="jemur"
                                               inputmode="numeric" placeholder="0" required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6" style="display: none;">
                                    <div class="form-group">
                                        <label for="harga_jual" class="form-label">Harga Beras Laku (Rp)</label>
                                        <input class="form-control number-format" type="text" name="harga_jual" id="harga_jual"
                                               inputmode="numeric" placeholder="" value="0" required>
                                    </div>
                                </div>
                            </div>

                            {{-- <!-- Pengambilan -->
                            <div class="section-title">
                                <i class="bi bi-cart-dash"></i>
                                Pengambilan Karung Konga
                            </div>

                            <div id="pengambilans"></div>

                            <div class="submit-section d-flex justify-content-between d-none">
                                <button type="button" class="btn btn-success-gradient w-100 add-pengambilan">
                                    <i class="bi bi-plus-circle me-2"></i>
                                    Tambah Pengambilan
                                </button>
                            </div> --}}

                            <!-- Data Produk Sampingan -->
                            <div class="section-title">
                                <i class="bi bi-box-seam"></i>
                                Penjualan Konga dan Menir
                            </div>

                            <div class="row">
                                <div class="col-md-3 col-6">
                                    <div class="form-group">
                                        <label for="jumlah_konga" class="form-label">Jumlah Konga (Karung)</label>
                                        <input class="form-control number-format" type="text" name="jumlah_konga" id="jumlah_konga"
                                               inputmode="numeric" placeholder="0" required>
                                    </div>
                                </div>

                                <div class="col-md-3 col-6">
                                    <div class="form-group">
                                        <label for="harga_konga" class="form-label">Harga Konga (Rp)</label>
                                        <input class="form-control number-format" type="text" name="harga_konga" id="harga_konga"
                                               inputmode="numeric" placeholder="" value="250,000" required>
                                    </div>
                                </div>


                                <div class="col-md-3 col-6">
                                    <div class="form-group">
                                        <label for="jumlah_menir" class="form-label">Jumlah Menir (Kg)</label>
                                        <input class="form-control number-format" type="text" name="jumlah_menir" id="jumlah_menir"
                                               inputmode="numeric" placeholder="0" required>
                                    </div>
                                </div>

                                <div class="col-md-3 col-6">
                                    <div class="form-group">
                                        <label for="harga_menir" class="form-label">Harga Menir (Rp)</label>
                                        <input class="form-control number-format" type="text" name="harga_menir" id="harga_menir"
                                               inputmode="numeric" placeholder="" value="4,000" required>
                                    </div>
                                </div>
                            </div>

                            {{-- <!-- Konstanta -->
                            <div class="section-title">
                                <i class="bi bi-sliders"></i>
                                Biaya Buruh Giling
                            </div> --}}


                            <div class="row">
    <div class="col-6">
        <div class="section-title">
            <i class="bi bi-cart-dash"></i>
            Pengambilan
        </div>
    </div>
    <div class="col-6 text-end">
        <div class="section-title">
            <i class="bi bi-sliders"></i>
            Buruh Giling
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div id="pengambilans"></div>
    </div>
    <div class="col-6">
        <div class="form-group mb-0">
            <label class="form-label d-none">Biaya Buruh Giling (Rp)</label>
            <input class="form-control number-format" type="text" name="biaya_buruh_giling"
                   id="biaya_buruh_giling" value="80" inputmode="numeric" required>
        </div>
    </div>
    <div class="submit-section d-flex justify-content-between d-none">
        <button type="button" class="btn btn-success-gradient w-100 add-pengambilan">
            <i class="bi bi-plus-circle me-2"></i>
            Tambah Pengambilan
        </button>
    </div>
</div>


                            <div class="row">
                                <div class="col-md-3 col-sm-6 d-none">
                                    <div class="form-group">
                                        <label for="biaya_giling" class="form-label">Biaya Giling (%)</label>
                                        <input class="form-control number-format" type="text" name="biaya_giling"
                                               id="biaya_giling" value="9" inputmode="numeric" required>
                                    </div>
                                </div>
                                {{-- <div class="col-md-3 col-6">
                                    <div class="form-group">
                                        <label for="biaya_buruh_giling" class="form-label d-none">Biaya Buruh Giling (Rp)</label>
                                        <input class="form-control number-format" type="text" name="biaya_buruh_giling"
                                               id="biaya_buruh_giling" value="80" inputmode="numeric" required>
                                    </div>
                                </div> --}}
                                <div class="col-md-3 col-sm-6 d-none">
                                    <div class="form-group">
                                        <label for="biaya_buruh_jemur" class="form-label">Biaya Buruh Jemur (Rp)</label>
                                        <input class="form-control number-format" type="text" name="biaya_buruh_jemur"
                                               id="biaya_buruh_jemur" value="8,000" inputmode="numeric" required>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6" style="display: none;">
                                    <div class="form-group">
                                        <label for="bunga" class="form-label">Bunga (%)</label>
                                        <input class="form-control number-format" type="text" name="bunga"
                                               id="bunga" value="2" inputmode="numeric" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Section -->
                            <div class=" mt-4 mb-2">
                                <button type="submit" class="btn btn-submit">
                                    <i class="bi bi-file-earmark-text"></i>
                                    BUAT NOTA SEMENTARA
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for displaying nota -->
    <div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pdfModalLabel">
                        <i class="bi bi-file-earmark-text"></i>
                        Preview Nota Sementara
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="iframe-container">
                        <div class="loading-spinner">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p>Memuat nota...</p>
                        </div>
                        <iframe id="pdfViewer" src="" style="display: none;"></iframe>
                    </div>
                    <!-- Download Progress -->
                    <div class="download-progress" id="downloadProgress">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 text-muted">Sedang membuat gambar...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    {{-- <button type="button" class="btn btn-secondary-outline" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>
                        Tutup
                    </button> --}}
                    <button type="button" class="btn btn-info-gradient" id="downloadPng">
                        <i class="bi bi-download me-1"></i>
                        Simpan
                    </button>
                    <button type="button" class="btn btn-primary-gradient" id="printPdf">
                        <i class="bi bi-printer me-1"></i>
                        Cetak
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden container for PNG rendering -->
    <div id="nota-preview-container"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set today's date
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('tanggal_nota').value = today;

            // Store nota HTML globally for PNG export
            let currentNotaHtml = '';
            let currentFormData = null;

            // Format number inputs
            const numberInputs = document.querySelectorAll('.number-format');
            numberInputs.forEach(input => {
                formatNumber(input);
                input.addEventListener('input', function(e) {
                    let value = this.value;
                    this.dataset.rawValue = value;
                    formatNumber(this);
                });
            });

            function formatNumber(input) {
                let value = input.value;
                input.dataset.rawValue = value;

                let [integer, decimal] = value.split('.');
                integer = integer.replace(/[^\d]/g, '');

                if (integer) {
                    integer = integer.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                }

                if (decimal !== undefined) {
                    value = integer + '.' + decimal;
                } else {
                    value = integer;
                }

                input.value = value;
            }

            // Pengambilan functionality
            let pengambilanCount = -1;
            const pengambilansContainer = document.getElementById('pengambilans');
            const addPengambilanBtn = document.querySelector('.add-pengambilan');

            function addDeleteButtonListener(button) {
                button.addEventListener('click', function() {
                    this.closest('.pengambilan-item').remove();
                    updateDeleteButtons();
                    if (document.querySelectorAll('.pengambilan-item').length === 0) {
                        pengambilanCount = -1;
                    }
                });
            }

            function updateDeleteButtons() {
                const deleteButtons = document.querySelectorAll('.delete-pengambilan');
                deleteButtons.forEach(button => {
                    button.style.display = 'block';
                });
            }

            function addPengambilanItem() {
                pengambilanCount++;
                const newPengambilan = `
                    <div class="">
                        <div class="row align-items-end">
                            <div class="col-md-4 col-sm-6" hidden>
                                <div class="form-group mb-0">
                                    <label class="form-label">Keterangan</label>
                                    <input type="text" name="pengambilans[${pengambilanCount}][keterangan]"
                                           class="form-control" placeholder="" value="Karung Konga">
                                </div>
                            </div>
                            <div class="">
                                <div class="form-group mb-0">
                                    <label class="form-label d-none">Jumlah</label>
                                    <input type="text" name="pengambilans[${pengambilanCount}][jumlah]"
                                           class="form-control number-format" placeholder="Jumlah Karung Konga" inputmode="numeric" data-raw-value="">
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6" hidden>
                                <div class="form-group mb-0">
                                    <label class="form-label">Harga</label>
                                    <input type="text" name="pengambilans[${pengambilanCount}][harga]"
                                           class="form-control number-format" placeholder="Harga" inputmode="numeric" data-raw-value="" value="4,000">
                                </div>
                            </div>
                            <div class="col-md-2 col-4 d-none">
                                <div class="form-group mb-0">
                                    <button type="button" class="btn btn-danger-gradient delete-pengambilan delete-btn d-none">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                pengambilansContainer.insertAdjacentHTML('beforeend', newPengambilan);

                const newItem = pengambilansContainer.lastElementChild;
                addDeleteButtonListener(newItem.querySelector('.delete-pengambilan'));

                // Initialize number formatting for new inputs
                const newInputs = newItem.querySelectorAll('.number-format');
                newInputs.forEach(input => {
                    formatNumber(input);
                    input.addEventListener('input', function(e) {
                        let value = this.value;
                        this.dataset.rawValue = value;
                        formatNumber(this);
                    });
                });

                updateDeleteButtons();
            }

            addPengambilanBtn.addEventListener('click', addPengambilanItem);

            // Add initial pengambilan item
            addPengambilanItem();

            // Form submission
            document.getElementById('gilingForm').addEventListener('submit', function(e) {
                e.preventDefault();
                generateNota();
            });

            function getRawValue(input) {
                return parseFloat(input.dataset.rawValue?.replace(/,/g, '') || input.value?.replace(/,/g, '') || '0') || 0;
            }

            function formatCurrency(number, decimalPlaces = 0) {
                return new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: decimalPlaces,
                    maximumFractionDigits: decimalPlaces
                }).format(number);
            }

            function capitalizeWords(str) {
                return str.replace(/\b\w/g, char => char.toUpperCase());
            }

            function getDecimalPlaces(value) {
                const stringValue = String(value);
                if (stringValue.includes('.')) {
                    return stringValue.split('.')[1].length;
                }
                return 0;
            }

            function generateNota() {
                // Get form values
                const formData = {
                    namaPetani: capitalizeWords(document.getElementById('nama_petani').value.trim()),
                    tanggalNota: document.getElementById('tanggal_nota').value,
                    gilingKotor: getRawValue(document.getElementById('giling_kotor')),
                    pulang: getRawValue(document.getElementById('pulang')) || 0,
                    pinjam: getRawValue(document.getElementById('pinjam')),
                    jemur: getRawValue(document.getElementById('jemur')),
                    hargaJual: getRawValue(document.getElementById('harga_jual')),
                    jumlahKonga: getRawValue(document.getElementById('jumlah_konga')),
                    hargaKonga: getRawValue(document.getElementById('harga_konga')),
                    jumlahMenir: getRawValue(document.getElementById('jumlah_menir')),
                    hargaMenir: getRawValue(document.getElementById('harga_menir')),
                    biayaGiling: getRawValue(document.getElementById('biaya_giling')),
                    biayaBuruhGiling: getRawValue(document.getElementById('biaya_buruh_giling')),
                    biayaBuruhJemur: getRawValue(document.getElementById('biaya_buruh_jemur')),
                    bunga: getRawValue(document.getElementById('bunga'))
                };

                currentFormData = formData;

                // Calculations
                const calculations = {
                    ongkosGiling: formData.gilingKotor * formData.biayaGiling / 100,
                    berasBersih: formData.gilingKotor - (formData.gilingKotor * formData.biayaGiling / 100) - formData.pinjam,
                    berasJual: (formData.gilingKotor - (formData.gilingKotor * formData.biayaGiling / 100) - formData.pinjam) - formData.pulang,
                    danaBeras: ((formData.gilingKotor - (formData.gilingKotor * formData.biayaGiling / 100) - formData.pinjam) - formData.pulang) * formData.hargaJual,
                    danaKonga: formData.jumlahKonga * formData.hargaKonga,
                    danaMenir: formData.jumlahMenir * formData.hargaMenir,
                    buruhGiling: formData.biayaBuruhGiling * formData.gilingKotor,
                    buruhJemur: formData.biayaBuruhJemur * formData.jemur
                };

                calculations.totalPendapatan = calculations.danaBeras + calculations.danaKonga + calculations.danaMenir;
                calculations.totalBiaya = calculations.buruhGiling + calculations.buruhJemur;
                calculations.danaKotor = calculations.totalPendapatan - calculations.totalBiaya;

                // Calculate pengambilan
                // Calculate pengambilan
const pengambilanItems = document.querySelectorAll('#pengambilans > div');
let totalPengambilan = 0;
const pengambilanData = [];

pengambilanItems.forEach(item => {
    const keteranganInput = item.querySelector('[name*="[keterangan]"]');
    const jumlahInput = item.querySelector('[name*="[jumlah]"]');
    const hargaInput = item.querySelector('[name*="[harga]"]');

    if (keteranganInput && jumlahInput && hargaInput) {
        const keterangan = keteranganInput.value;
        const jumlah = getRawValue(jumlahInput);
        const harga = getRawValue(hargaInput);

        console.log('Debug Pengambilan:', { keterangan, jumlah, harga }); // untuk debugging

        if (keterangan && jumlah > 0) {
            const subtotal = jumlah * harga;
            pengambilanData.push({
                keterangan: keterangan,
                jumlah: jumlah,
                harga: harga,
                subtotal: subtotal
            });
            totalPengambilan += subtotal;
        }
    }
});

console.log('Total Pengambilan Data:', pengambilanData); // untuk debugging

                calculations.totalPengambilan = totalPengambilan;
                calculations.totalHutang = 0;
                calculations.danaPenerima = calculations.danaKotor - totalPengambilan - calculations.totalHutang;

                // Generate HTML nota
                const notaHtml = generateNotaHtml(formData, calculations, pengambilanData);
                currentNotaHtml = notaHtml;

                // Create blob and show in modal
                const blob = new Blob([notaHtml], { type: 'text/html' });
                const url = URL.createObjectURL(blob);

                const pdfViewer = document.getElementById('pdfViewer');
                const loadingSpinner = document.querySelector('.loading-spinner');

                // Reset states
                loadingSpinner.style.display = 'block';
                pdfViewer.style.display = 'none';
                document.getElementById('downloadProgress').classList.remove('active');

                // Show modal
                const modal = new bootstrap.Modal(document.getElementById('pdfModal'));
                modal.show();

                // Load nota in iframe
                pdfViewer.onload = function() {
                    loadingSpinner.style.display = 'none';
                    pdfViewer.style.display = 'block';
                };

                pdfViewer.src = url;

                // Print functionality
                document.getElementById('printPdf').onclick = function() {
                    pdfViewer.contentWindow.print();
                };

                // Download PNG functionality
                document.getElementById('downloadPng').onclick = function() {
                    downloadAsPng(formData, calculations, pengambilanData);
                };
            }

            // Download as PNG function
            async function downloadAsPng(formData, calculations, pengambilanData) {
                const downloadProgress = document.getElementById('downloadProgress');
                const iframeContainer = document.querySelector('.iframe-container');

                // Show progress
                downloadProgress.classList.add('active');
                iframeContainer.style.display = 'none';

                try {
                    // Create a temporary container for rendering
                    const container = document.getElementById('nota-preview-container');
                    container.innerHTML = generateNotaHtmlForPng(formData, calculations, pengambilanData);
                    container.style.left = '0';
                    container.style.position = 'absolute';
                    container.style.zIndex = '-1';
                    container.style.opacity = '0';

                    // Wait for content to render
                    await new Promise(resolve => setTimeout(resolve, 500));

                    const notaElement = container.querySelector('.receipt');

                    // Use html2canvas to capture
                    const canvas = await html2canvas(notaElement, {
                        scale: 3, // High resolution
                        useCORS: true,
                        allowTaint: true,
                        backgroundColor: '#ffffff',
                        width: 302, // 80mm at 96 DPI
                        windowWidth: 302
                    });

                    // Convert to PNG and download
                    const link = document.createElement('a');
                    const timestamp = new Date().toISOString().slice(0, 10);
                    const sanitizedName = formData.namaPetani.replace(/[^a-zA-Z0-9]/g, '_');
                    link.download = `Nota_${sanitizedName}_${timestamp}.png`;
                    link.href = canvas.toDataURL('image/png', 1.0);
                    link.click();

                    // Reset container
                    container.style.left = '-9999px';
                    container.innerHTML = '';

                } catch (error) {
                    console.error('Error generating PNG:', error);
                    alert('Gagal membuat gambar PNG. Silakan coba lagi.');
                } finally {
                    // Hide progress and show iframe
                    downloadProgress.classList.remove('active');
                    iframeContainer.style.display = 'block';
                }
            }

//             // Download as PNG function INI YANG JPG
// async function downloadAsPng(formData, calculations, pengambilanData) {
//     const downloadProgress = document.getElementById('downloadProgress');
//     const iframeContainer = document.querySelector('.iframe-container');
//     // Show progress
//     downloadProgress.classList.add('active');
//     iframeContainer.style.display = 'none';
//     try {
//         // Create a temporary container for rendering
//         const container = document.getElementById('nota-preview-container');
//         container.innerHTML = generateNotaHtmlForPng(formData, calculations, pengambilanData);
//         container.style.left = '0';
//         container.style.position = 'absolute';
//         container.style.zIndex = '-1';
//         container.style.opacity = '0';
//         // Wait for content to render
//         await new Promise(resolve => setTimeout(resolve, 500));
//         const notaElement = container.querySelector('.receipt');
//         // Use html2canvas to capture
//         const canvas = await html2canvas(notaElement, {
//             scale: 3, // High resolution
//             useCORS: true,
//             allowTaint: true,
//             backgroundColor: '#ffffff',
//             width: 302, // 80mm at 96 DPI
//             windowWidth: 302
//         });
//         // Convert to JPG and download
//         const link = document.createElement('a');
//         const timestamp = new Date().toISOString().slice(0, 10);
//         const sanitizedName = formData.namaPetani.replace(/[^a-zA-Z0-9]/g, '_');
//         link.download = `Nota_${sanitizedName}_${timestamp}.jpg`;
//         link.href = canvas.toDataURL('image/jpeg', 1.0);
//         link.click();
//         // Reset container
//         container.style.left = '-9999px';
//         container.innerHTML = '';
//     } catch (error) {
//         console.error('Error generating JPG:', error);
//         alert('Gagal membuat gambar JPG. Silakan coba lagi.');
//     } finally {
//         // Hide progress and show iframe
//         downloadProgress.classList.remove('active');
//         iframeContainer.style.display = 'block';
//     }
// }

            function generateNotaHtmlForPng(formData, calculations, pengambilanData) {
                const currentDateTime = new Date();

                const formatDatee = (dateString) => {
                    const date = new Date(dateString);
                    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    const months = [
                        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                    ];
                    const dayName = days[date.getDay()];
                    const day = date.getDate();
                    const monthName = months[date.getMonth()];
                    const year = date.getFullYear();
                    return `${dayName}, ${day} ${monthName} ${year}`;
                };

                const formatTime = (dateString) => {
                    const date = new Date(dateString);
                    const hours = String(date.getHours()).padStart(2, '0');
                    const minutes = String(date.getMinutes()).padStart(2, '0');
                    const seconds = String(date.getSeconds()).padStart(2, '0');
                    return `${hours}:${minutes}:${seconds}`;
                };

                let pengambilanRows = '';
                if (pengambilanData.length > 0) {
                    pengambilanData.forEach((item, index) => {
                        pengambilanRows += `
                            <tr class="calculation-row">
                                <td>${item.keterangan}</td>
                                <td>${formatCurrency(item.jumlah, getDecimalPlaces(item.jumlah))}</td>
                                <td>Rp ${formatCurrency(item.harga, getDecimalPlaces(item.harga))}</td>
                                <td class="bold">Rp ${formatCurrency(item.subtotal, getDecimalPlaces(item.subtotal))}</td>
                            </tr>
                        `;
                    });
                } else {
                    pengambilanRows = `
                        <tr class="calculation-row">
                            <td colspan="4">Tidak ada data pengambilan</td>
                        </tr>
                    `;
                }

                return `
                    <div class="receipt" style="width: 302px; padding: 15px; font-family: 'Segoe UI', Arial, sans-serif; font-size: 11px; background: white; color: #000;">
                        <style>
                            .receipt * { box-sizing: border-box; }
                            .receipt table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
                            .receipt th, .receipt td { padding: 4px 2px; text-align: left; font-size: 11px; }
                            .receipt .header { text-align: center; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 2px dashed #333; }
                            .receipt .title { font-size: 14px; font-weight: bold; margin-bottom: 4px; }
                            .receipt .title2 { font-size: 12px; font-weight: bold; margin-bottom: 2px; }
                            .receipt .calculation-row td { border-top: 1px dashed #999; border-bottom: 1px dashed #999; padding: 5px 2px; }
                            .receipt .bold { font-weight: bold; }
                            .receipt .bold-border-top { border-top: 2px solid #000; font-weight: bold; }
                            .receipt .bold-border-top-top td { border-top: 2px solid #000; }
                            .receipt .footer { text-align: center; margin-top: 15px; padding-top: 10px; border-top: 2px dashed #333; font-style: italic; font-size: 10px; }
                            .receipt .small-text { font-size: 11px; }
                        </style>

                        <div class="header">
                            <div class="title">NOTA GILING SEMENTARA</div>
                            <div class="title2">GILINGAN PADI PUTRA MANUABA</div>
                            <div style="font-size: 10px;">DUS. BABAHAN, DES. TOLAI, KAB. PARIGI</div>
                            <div style="font-size: 10px;">Telp: 0811-451-486 / 0822-6077-3867</div>
                        </div>

                        <table>
                            <tr class="bold-border-top">
                                <td colspan="3"><strong>Informasi</strong></td>
                            </tr>
                            <tr class="bold-border-top-top calculation-row">
                                <td class="small-text">Nama Petani</td>
                                <td>:</td>
                                <td>${formData.namaPetani}</td>
                            </tr>
                            <tr class="calculation-row">
                                <td>Tanggal Nota</td>
                                <td>:</td>
                                <td>${formatDatee(currentDateTime)}</td>
                            </tr>
                            <tr class="calculation-row">
                                <td>Waktu Nota</td>
                                <td>:</td>
                                <td>${formatTime(currentDateTime)}</td>
                            </tr>
                        </table>

                        <table>
                            <tr class="bold-border-top">
                                <td colspan="5"><strong>Kalkulasi</strong></td>
                            </tr>
                            <tr class="bold-border-top-top calculation-row">
                                <td class="small-text">Giling Kotor</td>
                                <td>:</td>
                                <td>${formatCurrency(formData.gilingKotor, getDecimalPlaces(formData.gilingKotor))} Kg</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="calculation-row">
                                <td class="small-text">Ongkos Giling</td>
                                <td>:</td>
                                <td>${formatCurrency(formData.gilingKotor, getDecimalPlaces(formData.gilingKotor))} × ${formatCurrency(formData.biayaGiling, getDecimalPlaces(formData.biayaGiling))}%</td>
                                <td>=</td>
                                <td>${formatCurrency(calculations.ongkosGiling, 2)} Kg</td>
                            </tr>
                            <tr class="calculation-row">
                                <td class="small-text">Pinjam</td>
                                <td>:</td>
                                <td>${formatCurrency(formData.pinjam, getDecimalPlaces(formData.pinjam))} Kg</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="calculation-row">
    <td class="small-text">Beras Bersih</td>
    <td>:</td>
    <td>${calculations.berasBersih % 1 === 0 ? formatCurrency(calculations.berasBersih, 0) : formatCurrency(calculations.berasBersih, 2)} Kg</td>
    <td></td>
    <td></td>
</tr>
<tr class="calculation-row">
    <td class="small-text">Pulang</td>
    <td>:</td>
    <td>${formData.pulang % 1 === 0 ? formatCurrency(formData.pulang, 0) : formatCurrency(formData.pulang, 2)} Kg</td>
    <td></td>
    <td></td>
</tr>
<tr class="calculation-row">
    <td class="small-text">Beras Jual</td>
    <td>:</td>
    <td>${calculations.berasJual % 1 === 0 ? formatCurrency(calculations.berasJual, 0) : formatCurrency(calculations.berasJual, 2)} Kg</td>
    <td></td>
    <td></td>
</tr>
                            <tr class="calculation-row">
                                <td class="small-text">Buruh Giling</td>
                                <td>:</td>
                                <td>${formatCurrency(formData.gilingKotor, getDecimalPlaces(formData.gilingKotor))} × Rp ${formatCurrency(formData.biayaBuruhGiling, getDecimalPlaces(formData.biayaBuruhGiling))}</td>
                                <td>=</td>
                                <td class="bold">Rp ${formatCurrency(calculations.buruhGiling, getDecimalPlaces(calculations.buruhGiling))}</td>
                            </tr>
                            <tr class="calculation-row">
                                <td class="small-text">Buruh Jemur</td>
                                <td>:</td>
                                <td>${formatCurrency(formData.jemur, getDecimalPlaces(formData.jemur))} × Rp ${formatCurrency(formData.biayaBuruhJemur, getDecimalPlaces(formData.biayaBuruhJemur))}</td>
                                <td>=</td>
                                <td class="bold">Rp ${formatCurrency(calculations.buruhJemur, getDecimalPlaces(calculations.buruhJemur))}</td>
                            </tr>
                            <tr class="calculation-row">
                                <td class="small-text">Jual Konga</td>
                                <td>:</td>
                                <td>${formatCurrency(formData.jumlahKonga, getDecimalPlaces(formData.jumlahKonga))} × Rp ${formatCurrency(formData.hargaKonga, getDecimalPlaces(formData.hargaKonga))}</td>
                                <td>=</td>
                                <td class="bold">Rp ${formatCurrency(calculations.danaKonga, getDecimalPlaces(calculations.danaKonga))}</td>
                            </tr>
                            <tr class="calculation-row">
                                <td class="small-text">Jual Menir</td>
                                <td>:</td>
                                <td>${formatCurrency(formData.jumlahMenir, getDecimalPlaces(formData.jumlahMenir))} × Rp ${formatCurrency(formData.hargaMenir, getDecimalPlaces(formData.hargaMenir))}</td>
                                <td>=</td>
                                <td class="bold">Rp ${formatCurrency(calculations.danaMenir, getDecimalPlaces(calculations.danaMenir))}</td>
                            </tr>
                        </table>

                        <table>
                            <tr class="bold-border-top">
                                <th>Pengambilan</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Total</th>
                            </tr>
                            ${pengambilanRows}
                        </table>

                        <div class="footer">
                            <div>TERIMA KASIH TELAH GILING DISINI</div>
                            <div>SUKSES SELALU</div>
                        </div>
                    </div>
                `;
            }

            function generateNotaHtml(formData, calculations, pengambilanData) {
                const currentDateTime = new Date();

                const formatDatee = (dateString) => {
                    const date = new Date(dateString);
                    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    const months = [
                        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                    ];
                    const dayName = days[date.getDay()];
                    const day = date.getDate();
                    const monthName = months[date.getMonth()];
                    const year = date.getFullYear();
                    return `${dayName}, ${day} ${monthName} ${year}`;
                };

                const formatTime = (dateString) => {
                    const date = new Date(dateString);
                    const hours = String(date.getHours()).padStart(2, '0');
                    const minutes = String(date.getMinutes()).padStart(2, '0');
                    const seconds = String(date.getSeconds()).padStart(2, '0');
                    return `${hours}:${minutes}:${seconds}`;
                };

                let pengambilanRows = '';
                if (pengambilanData.length > 0) {
                    pengambilanData.forEach((item, index) => {
                        pengambilanRows += `
                            <tr class="calculation-row">
                                <td>${item.keterangan}</td>
                                <td>${formatCurrency(item.jumlah, getDecimalPlaces(item.jumlah))}</td>
                                <td>Rp ${formatCurrency(item.harga, getDecimalPlaces(item.harga))}</td>
                                <td class="bold">Rp ${formatCurrency(item.subtotal, getDecimalPlaces(item.subtotal))}</td>
                            </tr>
                        `;
                    });
                } else {
                    pengambilanRows = `
                        <tr class="calculation-row">
                            <td colspan="4">Tidak ada data pengambilan</td>
                        </tr>
                    `;
                }

                return `
                <!DOCTYPE html>
                <html>
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                    <style>
                        * {
                            margin: 0;
                            padding: 0;
                            box-sizing: border-box;
                        }

                        body {
                            font-family: 'Segoe UI', Arial, sans-serif;
                            font-size: 11px;
                            margin: 0;
                            padding: 0;
                            background: white;
                            color: #000;
                            line-height: 1.4;
                        }

                        .receipt {
                            width: 80mm;
                            max-width: 80mm;
                            margin: 0 auto;
                            padding: 10px;
                            background: white;
                        }

                        .header {
                            text-align: center;
                            margin-bottom: 12px;
                            padding-bottom: 10px;
                            border-bottom: 2px dashed #333;
                        }

                        .title {
                            font-size: 15px;
                            font-weight: bold;
                            margin-bottom: 4px;
                            letter-spacing: 0.5px;
                        }

                        .title2 {
                            font-size: 12px;
                            font-weight: bold;
                            margin-bottom: 3px;
                        }

                        .header div {
                            margin-bottom: 2px;
                        }

                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-bottom: 10px;
                        }

                        th, td {
                            padding: 4px 2px;
                            text-align: left;
                            font-size: 11px;
                            vertical-align: top;
                        }

                        td:nth-child(1) { width: 32%; }
                        td:nth-child(2) { width: 5%; text-align: center; }

                        .calculation-row td {
                            border-top: 1px dashed #999;
                            border-bottom: 1px dashed #999;
                            padding-top: 5px;
                            padding-bottom: 5px;
                        }

                        .calculation-row-top td {
                            border-top: 1px dashed #999;
                            padding-top: 5px;
                            padding-bottom: 5px;
                        }

                        .bold {
                            font-weight: bold;
                        }

                        .bold-border-top {
                            border-top: 2px solid #000;
                            font-weight: bold;
                        }

                        .bold-border-top td {
                            padding-top: 8px;
                            font-weight: bold;
                        }

                        .bold-border-top-top td {
                            border-top: 1px solid #000;
                        }

                        .small-text {
                            font-size: 11px;
                        }

                        .footer {
                            text-align: center;
                            margin-top: 15px;
                            padding-top: 10px;
                            border-top: 2px dashed #333;
                            font-style: italic;
                            font-size: 10px;
                        }

                        .footer div {
                            margin-bottom: 3px;
                        }

                        .spacer {
                            height: 20px;
                        }

                        @media print {
                            @page {
                                size: 80mm auto;
                                margin: 0;
                            }

                            body {
                                margin: 0;
                                padding: 0;
                                -webkit-print-color-adjust: exact;
                                print-color-adjust: exact;
                            }

                            .receipt {
                                width: 100%;
                                max-width: 100%;
                            }
                        }
                    </style>
                </head>
                <body>
                    <div class="receipt">
                        <div class="header">
                            <div class="title">NOTA GILING SEMENTARA</div>
                            <div class="title2">GILINGAN PADI PUTRA MANUABA</div>
                            <div>DUS. BABAHAN, DES. TOLAI, KAB. PARIGI</div>
                            <div>Telp: 0811-451-486 / 0822-6077-3867</div>
                        </div>

                        <table>
                            <tr class="bold-border-top">
                                <td colspan="3"><strong>Informasi</strong></td>
                            </tr>
                            <tr class="bold-border-top-top calculation-row">
                                <td class="small-text">Nama Petani</td>
                                <td>:</td>
                                <td>${formData.namaPetani}</td>
                            </tr>
                            <tr class="calculation-row">
                                <td>Tanggal Nota</td>
                                <td>:</td>
                                <td>${formatDatee(currentDateTime)}</td>
                            </tr>
                            <tr class="calculation-row">
                                <td>Waktu Nota</td>
                                <td>:</td>
                                <td>${formatTime(currentDateTime)}</td>
                            </tr>
                        </table>

                        <table>
                            <tr class="bold-border-top">
                                <td colspan="5"><strong>Kalkulasi</strong></td>
                            </tr>
                            <tr class="bold-border-top-top calculation-row">
                                <td class="small-text">Giling Kotor</td>
                                <td>:</td>
                                <td>${formatCurrency(formData.gilingKotor, getDecimalPlaces(formData.gilingKotor))} Kg</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="calculation-row">
                                <td class="small-text">Ongkos Giling</td>
                                <td>:</td>
                                <td>${formatCurrency(formData.gilingKotor, getDecimalPlaces(formData.gilingKotor))} × ${formatCurrency(formData.biayaGiling, getDecimalPlaces(formData.biayaGiling))}%</td>
                                <td>=</td>
                                <td>${formatCurrency(calculations.ongkosGiling, 2)} Kg</td>
                            </tr>
                            <tr class="calculation-row">
                                <td class="small-text">Pinjam</td>
                                <td>:</td>
                                <td>${formatCurrency(formData.pinjam, getDecimalPlaces(formData.pinjam))} Kg</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="calculation-row">
    <td class="small-text">Beras Bersih</td>
    <td>:</td>
    <td>${calculations.berasBersih % 1 === 0 ? formatCurrency(calculations.berasBersih, 0) : formatCurrency(calculations.berasBersih, 2)} Kg</td>
    <td></td>
    <td></td>
</tr>
<tr class="calculation-row">
    <td class="small-text">Pulang</td>
    <td>:</td>
    <td>${formData.pulang % 1 === 0 ? formatCurrency(formData.pulang, 0) : formatCurrency(formData.pulang, 2)} Kg</td>
    <td></td>
    <td></td>
</tr>
<tr class="calculation-row">
    <td class="small-text">Beras Jual</td>
    <td>:</td>
    <td>${calculations.berasJual % 1 === 0 ? formatCurrency(calculations.berasJual, 0) : formatCurrency(calculations.berasJual, 2)} Kg</td>
    <td></td>
    <td></td>
</tr>
                            <tr class="calculation-row">
                                <td class="small-text">Buruh Giling</td>
                                <td>:</td>
                                <td>${formatCurrency(formData.gilingKotor, getDecimalPlaces(formData.gilingKotor))} × Rp ${formatCurrency(formData.biayaBuruhGiling, getDecimalPlaces(formData.biayaBuruhGiling))}</td>
                                <td>=</td>
                                <td class="bold">Rp ${formatCurrency(calculations.buruhGiling, getDecimalPlaces(calculations.buruhGiling))}</td>
                            </tr>
                            <tr class="calculation-row">
                                <td class="small-text">Buruh Jemur</td>
                                <td>:</td>
                                <td>${formatCurrency(formData.jemur, getDecimalPlaces(formData.jemur))} × Rp ${formatCurrency(formData.biayaBuruhJemur, getDecimalPlaces(formData.biayaBuruhJemur))}</td>
                                <td>=</td>
                                <td class="bold">Rp ${formatCurrency(calculations.buruhJemur, getDecimalPlaces(calculations.buruhJemur))}</td>
                            </tr>
                            <tr class="calculation-row">
                                <td class="small-text">Jual Konga</td>
                                <td>:</td>
                                <td>${formatCurrency(formData.jumlahKonga, getDecimalPlaces(formData.jumlahKonga))} × Rp ${formatCurrency(formData.hargaKonga, getDecimalPlaces(formData.hargaKonga))}</td>
                                <td>=</td>
                                <td class="bold">Rp ${formatCurrency(calculations.danaKonga, getDecimalPlaces(calculations.danaKonga))}</td>
                            </tr>
                            <tr class="calculation-row">
                                <td class="small-text">Jual Menir</td>
                                <td>:</td>
                                <td>${formatCurrency(formData.jumlahMenir, getDecimalPlaces(formData.jumlahMenir))} × Rp ${formatCurrency(formData.hargaMenir, getDecimalPlaces(formData.hargaMenir))}</td>
                                <td>=</td>
                                <td class="bold">Rp ${formatCurrency(calculations.danaMenir, getDecimalPlaces(calculations.danaMenir))}</td>
                            </tr>
                        </table>

                        <table>
                            <tr class="bold-border-top">
                                <th>Pengambilan</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Total</th>
                            </tr>
                            ${pengambilanRows}
                        </table>

                        <div class="footer">
                            <div>TERIMA KASIH TELAH GILING DISINI</div>
                            <div>SUKSES SELALU</div>
                            <div class="spacer"></div>
                        </div>
                    </div>
                </body>
                </html>
                `;
            }

            // Update placeholder on giling_kotor input
            document.getElementById('giling_kotor').addEventListener('input', function() {
                let gilingKotor = parseFloat(this.value.replace(/,/g, '')) || 0;
                let hasilPerhitungan = gilingKotor - (gilingKotor * 0.09);

                let formatted = hasilPerhitungan.toLocaleString('id-ID', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 2
                });

                document.getElementById('pulang').placeholder = formatted;
            });

            // Reset form on load
            document.getElementById('gilingForm').reset();

            // Re-apply default values after reset
            document.getElementById('biaya_giling').value = '9';
            document.getElementById('biaya_buruh_giling').value = '80';
            document.getElementById('biaya_buruh_jemur').value = '8,000';
            document.getElementById('harga_konga').value = '250,000';
            document.getElementById('harga_menir').value = '4,000';
            document.getElementById('bunga').value = '2';
            document.getElementById('harga_jual').value = '0';
        });
    </script>
</body>
</html>
