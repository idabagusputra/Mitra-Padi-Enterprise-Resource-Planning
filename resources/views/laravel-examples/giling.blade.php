@extends('layouts.user_type.auth')

@section('content')


<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<style>
    body {
        overflow-x: hidden;
    }

    .search-container {
        position: relative;
    }

    .input-group {
        border-radius: 0.375rem;
        overflow: hidden;
    }

    .input-group-text {
        border-right: none;
        background-color: #fff;
    }

    #petani_search {
        border-left: none;
        border-radius: 0 0.375rem 0.375rem 0;
    }

    #search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background-color: white;
        border: 1px solid #e9ecef;
        border-top: none;
        border-radius: 0 0 0.375rem 0.375rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        width: 100%;
    }

    #search-results .dropdown-item {
        padding: 0.5rem 1rem;
        border-bottom: 1px solid #e9ecef;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    #search-results .dropdown-item:last-child {
        border-bottom: none;
    }

    #search-results .dropdown-item:hover {
        background-color: #f8f9fa;
    }

    #search-results .petani-name {
        font-weight: bold;
        color: #344767;
    }

    #search-results .petani-debt {
        font-size: 0.875em;
        color: #67748e;
    }

    /* Styling untuk modal dan area PDF */
    .modal .modal-dialog {
        max-width: 450px;
    }

    .modal-body {
        position: relative;
        padding: 15px;
    }

    .pdf-viewer {
        width: 100%;
        height: 600px;
        border: none;
    }

    .btn-cetak-receipt {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        transition-duration: 0.4s;
        cursor: pointer;
        border-radius: 5px;
    }

    .btn-cetak-receipt:hover {
        background-color: #45a049;
        color: white;
    }

    .form-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        box-sizing: border-box;
    }

    .form-row {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 15px;
        align-items: center;
    }

    .form-group {
        flex: 1;
        margin-right: 10px;
        min-width: 200px;
    }

    .form-group:last-child {
        margin-right: 0;
    }

    label {
        display: block;
        margin-bottom: 5px;
    }

    input[type="text"],
    input[type="number"],
    select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .btn-add {
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .btn-add:hover {
        background-color: #45a049;
    }

    @media (max-width: 768px) {
        .form-group {
            flex-basis: 100%;
            margin-right: 0;
            margin-bottom: 10px;
        }
    }

    .view-pdf-btn {
        min-height: 59.2;
        /* Sesuaikan dengan tinggi alert */
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .submit-button {
        min-height: 59.2px;
    }

    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
        border-width: 0.2em;
    }

    /* Landscape Mode (Desktop/Tablet Horizontal) */
    @media (min-width: 769px) {
        .pengambilan-w {

            width: 80% !important;

        }

        .konstanta-css {

            width: 80% !important;

        }
    }

    /* Portrait Mode (Tablet/Mobile Vertical) */
    @media (max-width: 768px) and (orientation: portrait) {
        .pengambilan-item {

            gap: 1rem !important
        }

        .pengambilan {
            align-items: center !important;
            flex-direction: column !important;
            width: 100% !important;
            gap: 1rem !important
        }

        .pengambilan-w {
            align-items: center !important;
            flex-direction: column !important;
            width: 100% !important;
            gap: 1rem !important
        }

        .konstanta-css {

            width: 100% !important;

        }

        .btn-css {

            width: 100% !important;

        }

    }
</style>

<!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css"> -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> -->
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script> -->




@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header pb-0 px-3">
            <h6 class="mb-0 text-primary">{{ __('Kalkulasi Penggilingan Beras') }}</h6>
        </div>
        <div class="card-body pt-4 p-3">
            @if ($errors->any())
            <div class="mt-3 alert alert-primary alert-dismissible fade show" role="alert">
                <span class="alert-text text-white">{{ $errors->first() }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <i class="bi bi-x-circle" aria-hidden="true"></i>
                </button>
            </div>
            @endif

            @if(session('success'))
            <div class="d-flex justify-content-between align-items-center mt-0 mb-4">
                <div class="alert alert-success alert-dismissible fade show m-0 view-pdf-btn" id="alert-success" role="alert">
                    <span class="alert-text text-white">
                        {{ session('success') }}
                    </span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <i class="bi bi-x-circle" aria-hidden="true"></i>
                    </button>
                </div>
                <button class="btn alert bg-gradient-info shadow-info px-4 m-0 view-pdf-btn" data-id="{{ $latestGiling->id }}">
                    <i class="bi bi-printer-fill me-2"></i>
                    Print Receipt
                </button>
            </div>
            @endif


            <form action="{{ route('giling.store') }}" method="POST" role="form text-left">
                @csrf

                <div class="row mb-4">
                    <div class="col-md-8 "> <!-- Diubah dari col-md-6 menjadi col-md-12 -->
                        <div class="form-group">
                            <label for="petani_search" class="form-control-label">{{ __('Pilih Petani') }}</label>
                            <div class="search-container ">
                                <div class="input-group">

                                    <span class="btn btn-outline-primary input-group-text mb-0" type="" aria-label="Cari">
                                        <i class="bi bi-search" aria-hidden="true"></i>
                                    </span>
                                    <input type="text" id="petani_search" class="form-control" placeholder="Cari petani..." autocomplete="off">
                                    <input type="hidden" id="petani_id" name="petani_id">
                                </div>
                                <div id="search-results" style="display: none;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="created_at" class="form-control-label">{{ __('Tanggal Gabah Masuk') }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                <input type="date"
                                    class="form-control @error('created_at') is-invalid @enderror"
                                    id="created_at"
                                    name="created_at"
                                    value="{{ now('Asia/Jakarta')->format('Y-m-d') }}"
                                    required>
                            </div>
                            @error('created_at')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                </div>




                <!-- Bagian Data Penggilingan -->
                <h6 class="mb-3 text-primary">{{ __('Data Penggilingan') }}</h6>
                <div class="row">
                    @php
                    $fields = [
                    'giling_kotor' => ['label' => 'Giling Kotor (kg)'],
                    'pulang' => ['label' => 'Beras Pulang (kg)'],
                    'pinjam' => ['label' => 'Pinjaman Beras (kg)'],
                    'jemur' => ['label' => 'Jemur (kg)'],
                    'jumlah_konga' => ['label' => 'Jumlah Konga'],
                    'harga_konga' => ['label' => 'Harga Konga'],
                    'harga_jual' => ['label' => 'Harga Beras Laku'],
                    'jumlah_menir' => ['label' => 'Jumlah Menir'],
                    'harga_menir' => ['label' => 'Harga Menir'],
                    ];
                    @endphp
                    @foreach($fields as $field => $data)
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="{{ $field }}" class="form-control-label">{{ __($data['label']) }}</label>
                            <input class="form-control number-format" type="text"
                                name="{{ $field }}"
                                id="{{ $field }}"
                                inputmode="numeric"
                                required>
                        </div>
                    </div>
                    @endforeach
                </div>

                <h6 class="mb-3 mt-4 text-primary">{{ __('Pengambilan') }}</h6>
                <div id="pengambilans">

                </div>
                <div class="col-md-12 d-flex align-items-end mb-3">
                    <button type="button" class="btn btn-css btn-primary add-pengambilan ">
                        <i class="bi bi-plus-square me-2"></i>
                        <span>Tambah Pengambilan</span>
                    </button>
                </div>

                <!-- Bagian Konstanta -->
                <h6 class="mb-3 mt-4 text-primary">{{ __('Konstanta') }}</h6>
                <div class="row">
                    @php
                    $constants = [
                    'biaya_giling' => ['label' => 'Biaya Giling', 'default' => 9],
                    'biaya_buruh_giling' => ['label' => 'Biaya Buruh Giling', 'default' => 70],
                    'biaya_buruh_jemur' => ['label' => 'Biaya Buruh Jemur', 'default' => 7000],
                    'bunga' => ['label' => 'Bunga (%)', 'default' => 2],
                    ];
                    @endphp
                    @foreach($constants as $field => $data)
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label for="{{ $field }}" class="form-control-label ">{{ __($data['label']) }}</label>
                            <input class="form-control number-format konstanta-css" type="text"
                                name="{{ $field }}"
                                id="{{ $field }}"
                                value="{{ number_format(old($field, $data['default']), 0, ',', '.') }}"
                                data-raw-value="{{ old($field, $data['default']) }}"
                                inputmode="numeric"
                                required>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-end mt-2">
                    <button type="submit" class="btn btn-css bg-gradient-primary btn-md submit-button">{{ 'Simpan Nota Giling' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pdfModalLabel">Receipt #</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe id="pdfViewer" style="width: 100%; height: 500px;" frameborder="0"></iframe>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button id="printPdf" class="btn btn-primary">Print</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>




<!-- Tambahkan CSS Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<!-- Tambahkan JavaScript Flatpickr -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('petani_search');
        const searchResults = document.getElementById('search-results');
        const petaniIdInput = document.getElementById('petani_id');

        // Tambahkan styling untuk dropdown
        searchResults.style.cssText = `
    max-height: 300px;
    overflow-y: auto;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-shadow: 0 2px 4px #cc0c9c;
`;

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.trim();
            if (searchTerm.length > 0) {
                fetch(`/search-petani?term=${searchTerm}`)
                    .then(response => response.json())
                    .then(data => {
                        searchResults.innerHTML = '';
                        searchResults.style.display = 'block';
                        data.forEach(petani => {
                            const div = document.createElement('div');
                            div.classList.add('dropdown-item');

                            // Buat container untuk nama
                            const nameSpan = document.createElement('span');
                            nameSpan.style.fontWeight = 'bold';
                            nameSpan.style.color = '#cc0c9c';
                            nameSpan.textContent = petani.nama;

                            // Buat container untuk alamat dan hutang
                            const infoSpan = document.createElement('span');
                            infoSpan.style.color = '#666';
                            infoSpan.textContent = ` - ${petani.alamat} - (Hutang: Rp ${petani.total_hutang.toLocaleString('id-ID')})`;

                            // Gabungkan semua elemen
                            div.appendChild(nameSpan);
                            div.appendChild(infoSpan);

                            div.addEventListener('click', function() {
                                searchInput.value = petani.nama;
                                petaniIdInput.value = petani.id;
                                searchResults.style.display = 'none';
                            });
                            searchResults.appendChild(div);
                        });
                    });
            } else {
                searchResults.style.display = 'none';
            }
        });

        document.addEventListener('click', function(e) {
            if (e.target !== searchInput && e.target !== searchResults) {
                searchResults.style.display = 'none';
            }
        });




        // Format number inputs
        const numberInputs = document.querySelectorAll('.number-format');

        numberInputs.forEach(input => {
            // Format saat halaman dimuat
            formatNumber(input);

            // Mencegah input selain angka
            input.addEventListener('keypress', function(e) {
                // Mengizinkan hanya angka dan tombol kontrol
                if (!/[\d]/.test(e.key) &&
                    e.key !== 'Backspace' &&
                    e.key !== 'Delete' &&
                    e.key !== 'ArrowLeft' &&
                    e.key !== 'ArrowRight' &&
                    e.key !== 'Tab') {
                    e.preventDefault();
                }
            });

            // Mencegah paste konten selain angka
            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const pastedText = (e.clipboardData || window.clipboardData).getData('text');
                if (/^\d+$/.test(pastedText)) {
                    const value = this.value.replace(/\D/g, '');
                    this.dataset.rawValue = value;
                    formatNumber(this);
                }
            });

            // Format saat input berubah
            input.addEventListener('input', function(e) {
                let value = this.value.replace(/\D/g, '');
                this.dataset.rawValue = value;
                formatNumber(this);
            });

            // Saat fokus, tampilkan nilai asli
            input.addEventListener('focus', function(e) {
                this.value = this.dataset.rawValue;
                // Posisikan kursor di akhir
                const length = this.value.length;
                this.setSelectionRange(length, length);
            });

            // Saat blur, format ulang
            input.addEventListener('blur', function(e) {
                formatNumber(this);
            });
        });

        function formatNumber(input) {
            let value = input.value.replace(/\D/g, '');
            input.dataset.rawValue = value;

            if (value !== '') {
                value = parseInt(value);
                // Khusus untuk field bunga, tambahkan 2 desimal
                if (input.id === 'bunga') {
                    // input.value = value.toLocaleString('id-ID', {
                    //     minimumFractionDigits: 2,
                    //     maximumFractionDigits: 2
                    // });
                } else {
                    input.value = value.toLocaleString('id-ID');
                }
            }
        }

        // Modify form submission to send raw values
        document.querySelector('form').addEventListener('submit', function(e) {
            // Tambahkan pengecekan apakah form sedang di-submit
            numberInputs.forEach(input => {
                input.value = input.dataset.rawValue;
            });
            if (this.getAttribute('data-submitting') === 'true') {
                e.preventDefault();
                return;
            }

            // Set flag bahwa form sedang disubmit
            this.setAttribute('data-submitting', 'true');
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            // Disable button dan tampilkan loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
        <div class="d-flex align-items-center">
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            <span>Menyimpan...</span>
        </div>
    `;
        });




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
            <div class="pengambilan-item row mb-2">
            <div class="col-md-4">
                <div class="form-group mb-0">
                    <input type="text" name="pengambilans[${pengambilanCount}][keterangan]" class="form-control pengambilan keterangan-input w-100" placeholder="Keterangan" list="keterangan-list">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-0">
                    <input type="text" name="pengambilans[${pengambilanCount}][jumlah]" class="form-control number-format pengambilan-w" placeholder="Jumlah" inputmode="numeric" data-raw-value="">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-0">
                    <input type="text" name="pengambilans[${pengambilanCount}][harga]" class="form-control number-format pengambilan-w" placeholder="Harga" inputmode="numeric" data-raw-value="">
                </div>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn pengambilan btn-danger delete-pengambilan w-100 bi">
                <i class="bi bi-trash3-fill me-2"></i>
                        <span>DEL</span>
                        </button>
            </div>
        </div>
    `;
            pengambilansContainer.insertAdjacentHTML('beforeend', newPengambilan);

            // Initialize number formatting for new inputs
            const newItem = pengambilansContainer.lastElementChild;
            initializeNumberFormatting(newItem.querySelectorAll('.number-format'));

            addDeleteButtonListener(newItem.querySelector('.delete-pengambilan'));
            updateDeleteButtons();
        }

        function formatNumber(input) {
            if (!input.dataset.rawValue) {
                input.dataset.rawValue = input.value.replace(/\D/g, '');
            }
            const value = input.dataset.rawValue;
            if (value === '') {
                input.value = '';
                return;
            }
            input.value = Number(value).toLocaleString('id-ID');
        }

        function initializeNumberFormatting(inputs) {
            inputs.forEach(input => {
                // Format saat halaman dimuat
                formatNumber(input);

                // Mencegah input selain angka
                input.addEventListener('keypress', function(e) {
                    if (!/[\d]/.test(e.key) &&
                        e.key !== 'Backspace' &&
                        e.key !== 'Delete' &&
                        e.key !== 'ArrowLeft' &&
                        e.key !== 'ArrowRight' &&
                        e.key !== 'Tab') {
                        e.preventDefault();
                    }
                });

                // Mencegah paste konten selain angka
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pastedText = (e.clipboardData || window.clipboardData).getData('text');
                    if (/^\d+$/.test(pastedText)) {
                        this.dataset.rawValue = pastedText;
                        formatNumber(this);
                    }
                });

                // Format saat input berubah
                input.addEventListener('input', function(e) {
                    let value = this.value.replace(/\D/g, '');
                    this.dataset.rawValue = value;
                    formatNumber(this);
                });

                // Saat fokus, tampilkan nilai asli
                input.addEventListener('focus', function(e) {
                    this.value = this.dataset.rawValue || '';
                    // Posisikan kursor di akhir
                    const length = this.value.length;
                    this.setSelectionRange(length, length);
                });

                // Saat blur, format ulang
                input.addEventListener('blur', function(e) {
                    formatNumber(this);
                });
            });
        }

        // Initialize existing number inputs when page loads
        document.addEventListener('DOMContentLoaded', function() {
            const existingInputs = document.querySelectorAll('.pengambilan-item .number-format');
            initializeNumberFormatting(existingInputs);
        });

        addPengambilanBtn.addEventListener('click', addPengambilanItem);

        // Form submission handling
        document.querySelector('form').addEventListener('submit', function(e) {
            const pengambilans = document.querySelectorAll('.pengambilan-item');

            if (pengambilans.length === 0) {
                const pengambilansInput = document.createElement('input');
                pengambilansInput.type = 'hidden';
                pengambilansInput.name = 'pengambilans';
                pengambilansInput.value = '';
                this.appendChild(pengambilansInput);
            } else {
                let allEmpty = true;
                pengambilans.forEach((item, index) => {
                    const keterangan = item.querySelector(`[name^="pengambilans["][name$="[keterangan]"]`).value;
                    const jumlahInput = item.querySelector(`[name^="pengambilans["][name$="[jumlah]"]`);
                    const hargaInput = item.querySelector(`[name^="pengambilans["][name$="[harga]"]`);

                    // Convert raw values to integers before submission
                    if (jumlahInput.dataset.rawValue) {
                        jumlahInput.value = parseInt(jumlahInput.dataset.rawValue, 10);
                    }
                    if (hargaInput.dataset.rawValue) {
                        hargaInput.value = parseInt(hargaInput.dataset.rawValue, 10);
                    }

                    if (keterangan !== '' || jumlahInput.value !== '' || hargaInput.value !== '') {
                        allEmpty = false;
                    }
                });

                if (allEmpty) {
                    pengambilans.forEach(item => item.remove());
                    const pengambilansInput = document.createElement('input');
                    pengambilansInput.type = 'hidden';
                    pengambilansInput.name = 'pengambilans';
                    pengambilansInput.value = '';
                    this.appendChild(pengambilansInput);
                }
            }
        });




        var pdfModal = new bootstrap.Modal(document.getElementById('pdfModal'));

        // Function to show PDF modal
        function showPdfModal(gilingId) {
            const pdfPath = `/receipts/receipt-${gilingId}.pdf`;
            document.getElementById('pdfViewer').src = pdfPath;
            document.getElementById('pdfModalLabel').textContent = `Receipt #${gilingId}`;
            pdfModal.show();
        }

        // Event listener for View buttons
        document.querySelectorAll('.view-pdf-btn').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const gilingId = this.getAttribute('data-id');
                showPdfModal(gilingId);
            });
        });

        // Show modal automatically if success message is present
        @if(session('success'))
        const latestGilingId = "{{ $latestGiling->id }}";
        showPdfModal(latestGilingId);
        @endif

        // Event listener for Print button
        document.getElementById('printPdf').addEventListener('click', function() {
            const pdfViewer = document.getElementById('pdfViewer').contentWindow;
            pdfViewer.print();
        });
    });
</script>

@endsection