<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Kalkulasi Penggilingan Beras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --bg-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --card-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            --border-radius: 12px;
        }

        body {
            background: var(--bg-gradient);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-container {
            padding: 2rem 1rem;
        }

        .form-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            border: none;
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), #1e40af);
            color: white;
            padding: 1.5rem;
            border: none;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
        }

        .card-title i {
            margin-right: 0.5rem;
        }

        .card-body {
            padding: 2rem;
        }

        .section-title {
            color: var(--primary-color);
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e2e8f0;
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

.form-label {
    font-weight: 600; /* Sedikit lebih tegas */
    color: #000; /* Abu gelap lebih lembut dari hitam */
    font-size: 1rem; /* Sedikit lebih kecil dari default agar ringan */
    margin-bottom: 0.5rem;
    display: inline-block;
    letter-spacing: 0.3px;
    transition: color 0.3s ease;
}


        .form-control {
            border-radius: 8px;
            border: 2px solid #e2e8f0;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
        }

        .input-group-text {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 8px 0 0 8px;
            color: var(--secondary-color);
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 8px 8px 0;
        }

        .btn {
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
        }

        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
        }

        .btn-success {
            background: var(--success-color);
            border: none;
        }

        .btn-success:hover {
            background: #059669;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: var(--danger-color);
            border: none;
        }

        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-2px);
        }

        .alert {
            border: none;
            border-radius: 8px;
            padding: 1rem 1.5rem;
        }

        .alert-info {
            background: #dbeafe;
            color: #1e40af;
        }

        /* .pengambilan-item {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        } */

        /* .pengambilan-item:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        } */

        .delete-btn {
            width: 100%;
            height: 100%;
            min-height: 50px;
        }

        .submit-section {
            background: #f8fafc;
            border-radius: var(--border-radius);
            text-align: center;

        }

        .modal-content {
            border-radius: var(--border-radius);
            border: none;
            box-shadow: var(--card-shadow);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-color), #1e40af);
            color: white;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            padding: 0.6rem;
            padding-right: 1rem;
        }

        .modal-title {
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .modal-title i {
            margin-right: 0.5rem;
        }

        .btn-close {
            background: white;
            border-radius: 50%;
            opacity: 0.8;
        }

        .btn-close:hover {
            opacity: 1;
        }

        .iframe-container {
            position: relative;
            width: 100%;
            height: 70vh;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-top: 1.5rem;
        }

        .iframe-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .cut-line {
                        border-top: 1px dashed #000;
                        margin: 10px 0;
                        page-break-after: always;
                    }

        .loading-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 2;
        }
    .modal-footer {
        display: flex; /* aktifkan flex */
        justify-content: space-between; /* biar kiri-kanan */
        align-items: center; /* vertikal rata tengah (opsional) */
        padding: 1.5rem;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
    }

    html, body {
    overflow-x: hidden; /* Hindari scroll horizontal */
    width: 100%;         /* Pastikan body tidak lebih dari viewport */
}


        /* Responsive Design */
        @media (max-width: 768px) {
            .main-container {
                padding: 1rem 0.5rem;
            }

            .card-body {
                padding: 1.5rem;
            }

            /* .pengambilan-item {
                padding: 1rem 0.5rem;
            } */

            .delete-btn {
                margin-top: 1rem;
            }

            .cut-line {
                        border-top: 1px dashed #000;
                        margin: 10px 0;
                        page-break-after: always;
                    }

            .modal-lg {
                max-width: 95%;
            }

            .iframe-container {
                height: 60vh;
            }
        }

        /* Animation */
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
            animation: fadeIn 0.6s ease-out;
        }

        /* Print Styles */
        @media print {
            body {
                background: white !important;
            }

            .no-print {
                display: none !important;
            }
        }

        /* Thermal Print Styles - Tambahkan ini di bagian CSS */
@media print {
    @page {
        size: 80mm 180mm;
        margin: 0;
        padding: 0;
    }

    body {
        background: white !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .no-print {
        display: none !important;
    }
}

/* Hide blob URL in mobile browsers */
.modal-body::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: white;
    z-index: 1;
    pointer-events: none;
}

.iframe-container iframe {
    position: relative;
    z-index: 2;
}
    </style>
</head>
<body>
    <div class="container-fluid main-container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-11">
                <div class="card form-card fade-in">
                    {{-- <div class="card-header">
                        <h1 class="card-title">
                            <i class="bi bi-calculator"></i>
                            Kalkulasi Penggilingan Beras
                        </h1>
                    </div> --}}

                    <div class="card-body">
                        {{-- <div class="alert alert-info d-flex align-items-center" role="alert">
                            <i class="bi bi-info-circle me-2"></i>
                            <div>
                                <strong>Form Temporary:</strong> Data tidak akan disimpan ke database
                            </div>
                        </div> --}}

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
                                               placeholder="" required>
                                    </div>
                                </div>
                                <div class="col-md-6 d-none">
    <div class="form-group">
        <label for="tanggal_nota" class="form-label">Tanggal Nota</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-calendar3"></i>
            </span>
            <input type="date" class="form-control" id="tanggal_nota" name="tanggal_nota" required>
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
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label for="giling_kotor" class="form-label">Giling Kotor (Kg)</label>
                                        <input class="form-control number-format" type="text" name="giling_kotor" id="giling_kotor"
                                               inputmode="numeric" placeholder="" required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label for="pulang" class="form-label">Beras Pulang (Kg)</label>
                                        <input class="form-control number-format" type="text" name="pulang" id="pulang"
                                               inputmode="numeric" placeholder="" >
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label for="pinjam" class="form-label">Pinjaman Beras (Kg)</label>
                                        <input class="form-control number-format" type="text" name="pinjam" id="pinjam"
                                               inputmode="numeric" placeholder="" required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label for="jemur" class="form-label">Jemur (Karung)</label>
                                        <input class="form-control number-format" type="text" name="jemur" id="jemur"
                                               inputmode="numeric" placeholder="" required>
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

                             <!-- Pengambilan -->
                            <div class="section-title">
                                <i class="bi bi-cart-dash"></i>
                                Pengambilan Karung Konga
                            </div>

                            <div id="pengambilans"></div>

                            <div class="submit-section d-flex justify-content-between">
    <button type="button" class="btn btn-success btn-lg mb-4 w-100 add-pengambilan d-none"
        style="background: var(--bg-gradient); border: none; color: white;">
        <i class="bi bi-plus-circle me-2"></i>
        Tambah Pengambilan
    </button>
</div>

                            <!-- Data Produk Sampingan -->
                            <div class="section-title">
                                <i class="bi bi-box-seam"></i>
                                Data Produk Sampingan
                            </div>

                            <div class="row">
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="jumlah_konga" class="form-label">Jumlah Konga (Karung)</label>
                                        <input class="form-control number-format" type="text" name="jumlah_konga" id="jumlah_konga"
                                               inputmode="numeric" placeholder="" required>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="jumlah_menir" class="form-label">Jumlah Menir (Kg)</label>
                                        <input class="form-control number-format" type="text" name="jumlah_menir" id="jumlah_menir"
                                        inputmode="numeric" placeholder="" required>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="harga_konga" class="form-label">Harga Konga (Rp)</label>
                                        <input class="form-control number-format" type="text" name="harga_konga" id="harga_konga"
                                               inputmode="numeric" placeholder="" value="160000" required>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="harga_menir" class="form-label">Harga Menir (Rp)</label>
                                        <input class="form-control number-format" type="text" name="harga_menir" id="harga_menir"
                                               inputmode="numeric" placeholder="" value="5000" required>
                                    </div>
                                </div>
                            </div>


                            <!-- Konstanta -->
                            <div class="section-title">
                                <i class="bi bi-sliders"></i>
                                Biaya Buruh Giling
                            </div>

                            <div class="row">
                                <div class="col-md-3 col-sm-6 d-none">
                                    <div class="form-group">
                                        <label for="biaya_giling" class="form-label">Biaya Giling (%)</label>
                                        <input class="form-control number-format" type="text" name="biaya_giling"
                                               id="biaya_giling" value="9" inputmode="numeric" required>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="biaya_buruh_giling" class="form-label d-none">Biaya Buruh Giling (Rp)</label>
                                        <input class="form-control number-format" type="text" name="biaya_buruh_giling"
                                               id="biaya_buruh_giling" value="80" inputmode="numeric" required>
                                    </div>
                                </div>
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
                            <div class="submit-section d-flex justify-content-between" style="height: 120px;">

    <button type="submit" class="btn btn-success btn-lg w-100" style="background: var(--bg-gradient); border: none; color: white; margin-top: 1rem; margin-bottom: 1rem;">

        <i class="bi  bi-file-earmark-text me-2"></i>
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
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header justify-content-center position-relative">
        <h5 class="modal-title text-center" id="pdfModalLabel">
            {{-- <i class="bi bi-file-earmark-text"></i> --}}
            FORMAT NOTA
        </h5>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set today's date

            const today = new Date().toISOString().split('T')[0];
            document.getElementById('tanggal_nota').value = today;

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
                    <div class="pengambilan-item">
                        <div class="row">
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group" hidden>
                                    <label class="form-label">Keterangan</label>
                                    <input type="text" name="pengambilans[${pengambilanCount}][keterangan]"
                                           class="form-control" placeholder="" value="Karung Konga">
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label class="form-label d-none">Jumlah</label>
                                    <input type="text" name="pengambilans[${pengambilanCount}][jumlah]"
                                           class="form-control number-format" placeholder="Jumlah Karung" inputmode="numeric" data-raw-value="">
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group" hidden>
                                    <label class="form-label">Harga</label>
                                    <input type="text" name="pengambilans[${pengambilanCount}][harga]"
                                           class="form-control number-format" placeholder="Harga" inputmode="numeric" data-raw-value="" value="4,000">
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-6 d-none">
                                <div class="form-group">
                                    <label class="form-label d-none d-md-block">&nbsp;</label>
                                    <button type="button" class="btn btn-danger delete-pengambilan delete-btn d-none">
                                        <i class="bi bi-trash3"></i>
                                        <span class="d-none d-md-inline ms-1">Hapus</span>
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

            addPengambilanItem();

            // Form submission
            document.getElementById('gilingForm').addEventListener('submit', function(e) {
                e.preventDefault();
                generateNota();
            });

            function getRawValue(input) {
                return parseFloat(input.dataset.rawValue?.replace(/,/g, '') || '0') || 0;
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
                const pengambilanItems = document.querySelectorAll('.pengambilan-item');
                let totalPengambilan = 0;
                const pengambilanData = [];

                pengambilanItems.forEach(item => {
                    const keterangan = item.querySelector('[name*="[keterangan]"]').value;
                    const jumlah = getRawValue(item.querySelector('[name*="[jumlah]"]'));
                    const harga = getRawValue(item.querySelector('[name*="[harga]"]'));
                    const subtotal = jumlah * harga;

                    if (keterangan && jumlah && harga) {
                        pengambilanData.push({
                            keterangan: keterangan,
                            jumlah: jumlah,
                            harga: harga,
                            subtotal: subtotal
                        });
                        totalPengambilan += subtotal;
                    }
                });

                calculations.totalPengambilan = totalPengambilan;
                // For this temporary form, we'll assume no existing debts.
                // If you need to implement debt calculation, you'd need a way to input existing debts.
                calculations.totalHutang = 0; // Placeholder for now
                calculations.danaPenerima = calculations.danaKotor - totalPengambilan - calculations.totalHutang;

                // Generate HTML nota
                const notaHtml = generateNotaHtml(formData, calculations, pengambilanData);

                // Create blob and show in modal
                const blob = new Blob([notaHtml], { type: 'text/html' });
                const url = URL.createObjectURL(blob);

                const pdfViewer = document.getElementById('pdfViewer');
                const loadingSpinner = document.querySelector('.loading-spinner');

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
            }

            function generateNotaHtml(formData, calculations, pengambilanData) {
                const formatDate = (dateString) => {
                    const date = new Date(dateString);
                    return date.toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    });
                };

                const formatTime = (dateString) => {
                    const date = new Date(dateString);
                    return date.toLocaleTimeString('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit'
                    });
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

                // Helper to determine decimal places for formatting
                function getDecimalPlaces(value) {
                    const stringValue = String(value);
                    if (stringValue.includes('.')) {
                        return stringValue.split('.')[1].length;
                    }
                    return 0;
                }

                const currentDateTime = new Date();

                return `
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

                         .cut-line {
                        page-break-after: always;
                    }

                        .receipt {
                            width: 80mm; /* Standard thermal printer width */
                            height: 180mm;
                            margin: 0 auto; /* Center the receipt */
                        }

                        .header {
                            text-align: center;
                            margin-bottom: 10px;
                        }

                        .title {
                            font-size: 17pt;
                            font-weight: bold;\
                            margin-bottom: 5px;
                        }
                            .title2 {
                            font-size: 13pt;
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

                        .cut-line {
                        border-top: 1px dashed #000;
                        margin: 10px 0;
                        page-break-after: always;
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
                            font-size: 12pt;
                        }

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

                        @media print {
                @page {
                    size: 80mm 180mm;
                    margin: 0 !important;
                    padding: 0 !important;
                }

                body {
                    margin: 0 !important;
                    padding: 0 !important;
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }
            }

            body {
                font-size: 12px;
                margin: 0;
                line-height: 1.3;
                color: #000;
                background: white;
            }

            .cut-line {
                        border-top: 1px dashed #000;
                        margin: 10px 0;
                        page-break-after: always;
                    }

            .receipt {
                width: 100%;
                max-width: 100%;

            }

            .header {
                text-align: center;
                margin-bottom: 10px;
            }

            .title {
                font-size: 20px;
                font-weight: bold;
                margin-bottom: 3px;
            }

            .title2 {
                font-size: 16px;
                font-weight: bold;
                margin-bottom: 3px;
            }

            .info-item {
                margin-bottom: 2px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 8px;
            }

            th, td {
                padding: 2px 1px;
                text-align: left;
                font-size: 14px;
            }

            .cut-line {
                        page-break-after: always;
                    }

            .calculation-row td {
                border-top: 1px dashed #000;
                border-bottom: 1px dashed #000;
                padding-top: 3px;
                padding-bottom: 3px;
            }

            .calculation-row-top td {
                border-top: 1px dashed #000;
                padding-top: 3px;
                padding-bottom: 3px;
            }

            .total {
                font-size: 13px;
                font-weight: bold;
                border-top: 2px solid #000;
                border-bottom: 2px solid #000;
                margin-bottom: 8px;
            }

            .footer {
                text-align: center;
                margin-top: 10px;
                font-style: italic;
                font-size: 10px;
            }

            .bold-border-top {
                border-top: 2px solid #000;
                font-weight: bold;
            }

            .bold-border-top-top {
                border-top: 2px solid #000;
            }

            .small-text {
                font-size: 14px;
            }

            .bold {
                font-weight: bold;
            }
                    </style>
                </head>
                <body>
                    <div class="receipt">
                        <div class="header-container">
                            <div class="header">
                                <!-- Placeholder for logo, as asset() won't work directly in client-side HTML -->
                                <!-- <img src="{{ asset('logo_gilingan.png') }}" alt="Putra Manuaba" class="header-logo"> -->
                                <div class="header-text">
                                    <div class="title">NOTA GILING SEMENTARA</div>
                                    <div class="title2">GILINGAN PADI PUTRA MANUABA</div>
                                    <div>DUS. BABAHAN, DES. TOLAI, KAB. PARIGI</div>
                                    <div>Telp: 0811-451-486 / 0822-6077-3867</div>
                                </div>
                            </div>
                        </div>

                        <table>
                            <tr class="bold-border-top">
                                <td>Informasi</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="bold-border-top-top calculation-row">
                                <td class="small-text">Nama Petani</td>
                                <td>:</td>
                                <td>${formData.namaPetani}</td>
                            </tr>


                            <tr class="calculation-row">
                                <td>Tanggal Nota </td>
                                <td>:</td>
                                <td>${formatDate(currentDateTime)} (${formatTime(currentDateTime)})</td>
                            </tr>
                        </table>

                        <table>
                            <div></div>
                        </table>

                        <table>
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
                                <td>${formatCurrency(calculations.berasBersih, getDecimalPlaces(calculations.berasBersih))} Kg</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="calculation-row">
                                <td class="small-text">Pulang</td>
                                <td>:</td>
                                <td>${formatCurrency(formData.pulang, getDecimalPlaces(formData.pulang))} Kg</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="calculation-row">
                                <td class="small-text">Beras Jual</td>
                                <td>:</td>
                                <td>${formatCurrency(calculations.berasJual, getDecimalPlaces(calculations.berasJual))} Kg</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="calculation-row">
                                <td class="small-text">Buruh Giling</td>
                                <td>:</td>
                                <td>${formatCurrency(formData.gilingKotor, getDecimalPlaces(formData.gilingKotor))} × Rp ${formatCurrency(formData.biayaBuruhGiling, getDecimalPlaces(formData.biayaBuruhGiling))}</td>
                                <td>=</td>
                                <td class="bold">Rp ${formatCurrency(calculations.buruhGiling, getDecimalPlaces(formData.buruhGiling))}</td>
                            </tr>
                            <tr class="calculation-row">
                                <td class="small-text">Buruh Jemur</td>
                                <td>:</td>
                                <td>${formatCurrency(formData.jemur, getDecimalPlaces(formData.jemur))} × Rp ${formatCurrency(formData.biayaBuruhJemur, getDecimalPlaces(formData.biayaBuruhJemur))}</td>
                                <td>=</td>
                                <td class="bold">Rp ${formatCurrency(calculations.buruhJemur, getDecimalPlaces(formData.buruhJemur))}</td>
                            </tr>
                            <tr class="calculation-row">
                                <td class="small-text">Jual Konga</td>
                                <td>:</td>
                                <td>${formatCurrency(formData.jumlahKonga, getDecimalPlaces(formData.jumlahKonga))} × Rp ${formatCurrency(formData.hargaKonga, getDecimalPlaces(formData.hargaKonga))}</td>
                                <td>=</td>
                                <td class="bold">Rp ${formatCurrency(calculations.danaKonga, getDecimalPlaces(formData.danaKonga))}</td>
                            </tr>
                            <tr class="calculation-row">
                                <td class="small-text">Jual Menir</td>
                                <td>:</td>
                                <td>${formatCurrency(formData.jumlahMenir, getDecimalPlaces(formData.jumlahMenir))} × Rp ${formatCurrency(formData.hargaMenir, getDecimalPlaces(formData.hargaMenir))}</td>
                                <td>=</td>
                                <td class="bold">Rp ${formatCurrency(calculations.danaMenir, getDecimalPlaces(formData.danaMenir))}</td>
                            </tr>
                        </table>

                        <table>
                            <div></div>
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

                        <table>
                            <div></div>
                        </table>



                        <div class="footer">
                            <!-- Placeholder for footer image -->
                            <!-- <img src="{{ asset('footer.png') }}" alt="Putra Manuaba" class="header-logo cut-line"> -->
                            <div class="header-text">
                                <div>TERIMA KASIH TELAH GILING DISINI</div>
                                <div>SUKSES SELALU</div>
                                <div>.</div>
                                <div>.</div>
                                <div>.</div>
                                <div>.</div>
                                <div>.</div>
                                <div>.</div>
                            </div>
                        </div>

                    </div>
                </body>
                </html>
                `;
            }
        });
    </script>
</body>
</html>
