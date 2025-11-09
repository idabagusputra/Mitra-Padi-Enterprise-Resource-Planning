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
        border-radius: 0.25rem;
        overflow: hidden;
    }

    .input-group-text {
        border-right: none;
        background-color: #fff;
    }

    #petani_search {
        border-left: none;
        border-radius: 0 0.25rem 0.25rem 0;
    }

    #search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background-color: white;
        border: 1px solid #e9ecef;
        border-top: none;
        border-radius: 0 0 0.25rem 0.25rem;
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
        border-bottom: 1px;
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

    .form-control-label {
        padding-left: 0;
        margin-left: 0;
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
<div class="container-fluid mx-0 px-0">
    <div class="card mx-0 px-3">
        <div class="card-header pb-0 px-2">
            <h6 class="mb-0 text-primary">{{ __('Kalkulasi Penggilingan Beras') }}</h6>
        </div>
        <div class="card-body pt-4 mx-2 px-0">
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
                <div class="d-flex">
                    <button
                        class="btn alert bg-gradient-info shadow-info px-4 ms-2 me-2 view-pdf-btn"
                        data-id="{{ $latestGiling->id }}">
                        <i class="bi bi-printer-fill me-2"></i>
                        Print Receipt
                    </button>
 <button
    class="btn alert bg-gradient-secondary shadow-secondary px-4"
    onclick="window.location.href='/daftar-giling'">
    <i class="bi bi-card-list me-2"></i>
    Daftar Giling
</button>
                </div>

            </div>
            @endif


            <form action="{{ route('giling.store') }}" method="POST" role="form text-left">
                @csrf

                <div class="row mb-4">
                    <div class="col-md-8 "> <!-- Diubah dari col-md-6 menjadi col-md-12 -->
                        <div class="form-group">
                            <label for="petani_search" class="form-control-label">{{ __('Pilih Petani') }}</label>
                            <div class="search-container" style="width: 100%;">
                                <div class="form-group d-flex flex-row align-items-center" style="width: 100%;">
                                    <span class="btn btn-outline-primary input-group-text mb-0"
                                        style="border-radius: 0.25rem 0 0 0.25rem;
                   overflow: hidden;
                   border-right: none;
                   height: 40.3333px;
                   display: flex;
                   align-items: center;">
                                        <i class="bi bi-search" aria-hidden="true"></i>
                                    </span>
                                    <input type="text" id="petani_search" class="form-control"
                                        placeholder="Cari petani..."
                                        autocomplete="off"
                                        style="border-radius: 0 0.25rem 0.25rem 0; height: 40.3333px; flex: 1 1 auto; width: 100%; min-width: 0;">
                                    <input type="hidden" id="petani_id" name="petani_id">
                                </div>
                                <div id="search-results" style="display: none;"></div>
                            </div>

                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="form-group">
                            <label for=" created_at" class="form-control-label">{{ __('Tanggal Gabah Masuk') }}</label>
                            <div class="input-group" style="border-radius: 0.25rem 0 0 0.25rem;">
                                <span class="input-group-text" style="border-radius: 0.25rem 0 0 0.25rem;"><i class="bi bi-calendar3"></i></span>
                                <input type="date"
                                    class="form-control @error('created_at') is-invalid @enderror" style=" border-top-right-radius: 0.25rem; border-bottom-right-radius: 0.25rem;"
                                    id=" created_at"
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
                    'giling_kotor' => ['label' => 'Giling Kotor (Kg)'],
                    'pulang' => ['label' => 'Beras Pulang (Kg)'],
                    'pinjam' => ['label' => 'Pinjaman Beras (Kg)'],
                    'jemur' => ['label' => 'Jemur (Karung)'],
                    'harga_jual' => ['label' => 'Harga Beras Laku (Rp)'],
                    'jumlah_konga' => ['label' => 'Jumlah Konga (Karung)'],
                    'harga_konga' => ['label' => 'Harga Konga (Rp)'],
                    'jumlah_menir' => ['label' => 'Jumlah Menir (Kg)'],
                    'harga_menir' => ['label' => 'Harga Menir (Rp)'],
                    ];
                    @endphp
                    @foreach($fields as $field => $data)
                    @php
                    $label = $data['label'];
                    $placeholder = str_contains($label, '(Kg)') ? 'Kilogram'
                    : (str_contains($label, '(Karung)') ? 'Karung'
                    : (str_contains($label, '(Rp)') ? 'Rupiah' : ''));
                    @endphp
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="{{ $field }}" class="form-control-label">{{ __($label) }}</label>
                            <input class="form-control number-format" type="text"
                                name="{{ $field }}"
                                id="{{ $field }}"
                                inputmode="numeric"
                                placeholder="{{ $placeholder }}"
                                required>
                        </div>
                    </div>
                    @endforeach
                </div>


                <h6 class="mb-3 mt-2 text-primary">{{ __('Pengambilan') }}</h6>
                <div id="pengambilans">

                </div>
                <div class="col-md-12 d-flex align-items-end mb-3">
                    <button type="button" class="btn btn-css btn-primary bg-gradient-primary add-pengambilan ">
                        <i class="bi bi-plus-square me-2"></i>
                        <span>Tambah Pengambilan</span>
                    </button>
                </div>

                <!-- Bagian Konstanta -->
                <h6 class="mb-3 mt-4 text-primary">{{ __('Konstanta') }}</h6>
                <div class="row">
                    @php
                    $constants = [
                    'biaya_giling' => ['label' => 'Biaya Giling (%)', 'default' => 9],
                    'biaya_buruh_giling' => ['label' => 'Biaya Buruh Giling (Rp)', 'default' => 80],
                    'biaya_buruh_jemur' => ['label' => 'Biaya Buruh Jemur (Rp)', 'default' => 8000],
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
                                value="{{ number_format(old($field, $data['default']), 0, '.', ',') }}"
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

        // searchInput.addEventListener('input', function() {
        //     const searchTerm = this.value.trim();
        //     if (searchTerm.length > 0) {
        //         fetch(`/search-petani?term=${searchTerm}`)
        //             .then(response => response.json())
        //             .then(data => {
        //                 searchResults.innerHTML = '';
        //                 searchResults.style.display = 'block';
        //                 data.forEach(petani => {
        //                     const div = document.createElement('div');
        //                     div.classList.add('dropdown-item');

        //                     // Buat container untuk nama
        //                     const nameSpan = document.createElement('span');
        //                     nameSpan.style.fontWeight = 'bold';
        //                     nameSpan.style.color = '#cc0c9c';
        //                     nameSpan.textContent = petani.nama;

        //                     // Buat container untuk alamat dan hutang
        //                     const infoSpan = document.createElement('span');
        //                     infoSpan.style.color = '#666';
        //                     infoSpan.textContent = ` - ${petani.alamat} - (Hutang: Rp ${petani.total_hutang.toLocaleString('id-ID')})`;

        //                     // Gabungkan semua elemen
        //                     div.appendChild(nameSpan);
        //                     div.appendChild(infoSpan);

        //                     div.addEventListener('click', function() {
        //                         searchInput.value = petani.nama;
        //                         petaniIdInput.value = petani.id;
        //                         searchResults.style.display = 'none';
        //                     });
        //                     searchResults.appendChild(div);
        //                 });
        //             });
        //     } else {
        //         searchResults.style.display = 'none';
        //     }
        // });

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

                    // Format tanggal
                    let tanggalText = '';
                    if (petani.tanggal_hutang) {
                        const tanggal = new Date(petani.tanggal_hutang);
                        const options = { day: '2-digit', month: 'short', year: 'numeric' };
                        tanggalText = ` - ${tanggal.toLocaleDateString('id-ID', options)}`;
                    }

                    // Buat container untuk alamat, hutang, dan tanggal
                    const infoSpan = document.createElement('span');
                    infoSpan.style.color = '#666';
                    infoSpan.textContent = ` - ${petani.alamat} - (Hutang: Rp ${petani.total_hutang.toLocaleString('id-ID')}${tanggalText})`;

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

            // Format saat input berubah
            input.addEventListener('input', function(e) {
                let value = this.value; // Ambil seluruh input
                this.dataset.rawValue = value;
                formatNumber(this);
            });
        });

        function formatNumber(input) {
            let value = input.value;

            // Menyimpan nilai mentah tanpa format
            input.dataset.rawValue = value;

            // Pisahkan bagian integer dan desimal
            let [integer, decimal] = value.split('.');

            // Hapus semua karakter yang tidak valid dari bagian integer (kecuali angka)
            integer = integer.replace(/[^\d]/g, '');

            // Format bagian integer dengan koma sebagai pemisah ribuan
            if (integer) {
                integer = integer.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            }

            // Gabungkan kembali bagian integer dan desimal jika ada
            if (decimal !== undefined) {
                value = integer + '.' + decimal;
            } else {
                value = integer;
            }

            // Mengatur nilai input field dengan format yang benar
            input.value = value;
        }



        // Modify form submission to send raw values
        document.querySelector('form').addEventListener('submit', function(e) {
            // Tambahkan pengecekan apakah form sedang di-submit
            numberInputs.forEach(input => {
                // Hapus koma dari nilai input
                input.value = input.dataset.rawValue.replace(/,/g, '');
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
                    <input type="text" name="pengambilans[${pengambilanCount}][keterangan]" class="form-control pengambilan keterangan-input w-100" placeholder="Keterangan" list="keterangan-list" value="Karung Konga">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-0">
                    <input type="text" name="pengambilans[${pengambilanCount}][jumlah]" class="form-control number-format pengambilan-w" placeholder="Jumlah" inputmode="numeric" data-raw-value="">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-0">
                    <input type="text" name="pengambilans[${pengambilanCount}][harga]" class="form-control number-format pengambilan-w" placeholder="Harga" inputmode="numeric" data-raw-value="" value="4000">
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
            addDeleteButtonListener(newItem.querySelector('.delete-pengambilan'));
            // Format number inputs
            const numberInputs = document.querySelectorAll('.number-format');

            numberInputs.forEach(input => {
                // Format saat halaman dimuat
                formatNumber(input);

                // Format saat input berubah
                input.addEventListener('input', function(e) {
                    let value = this.value; // Ambil seluruh input
                    this.dataset.rawValue = value;
                    formatNumber(this);
                });
            });

            function formatNumber(input) {
                let value = input.value;

                // Menyimpan nilai mentah tanpa format
                input.dataset.rawValue = value;

                // Pisahkan bagian integer dan desimal
                let [integer, decimal] = value.split('.');

                // Hapus semua karakter yang tidak valid dari bagian integer (kecuali angka)
                integer = integer.replace(/[^\d]/g, '');

                // Format bagian integer dengan koma sebagai pemisah ribuan
                if (integer) {
                    integer = integer.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                }

                // Gabungkan kembali bagian integer dan desimal jika ada
                if (decimal !== undefined) {
                    value = integer + '.' + decimal;
                } else {
                    value = integer;
                }

                // Mengatur nilai input field dengan format yang benar
                input.value = value;
            }

            updateDeleteButtons();
        }

        function formatNumber(input) {
            let value = input.value;

            // Menyimpan nilai mentah tanpa format
            input.dataset.rawValue = value;

            // Pisahkan bagian integer dan desimal
            let [integer, decimal] = value.split('.');

            // Hapus semua karakter yang tidak valid dari bagian integer (kecuali angka)
            integer = integer.replace(/[^\d]/g, '');

            // Format bagian integer dengan koma sebagai pemisah ribuan
            if (integer) {
                integer = integer.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            }

            // Gabungkan kembali bagian integer dan desimal jika ada
            if (decimal !== undefined) {
                value = integer + '.' + decimal;
            } else {
                value = integer;
            }

            // Mengatur nilai input field dengan format yang benar
            input.value = value;
        }


        // // Add print functionality
        // document.getElementById('printPdf').addEventListener('click', function() {
        //     const pdfViewer = document.getElementById('pdfViewer');
        //     const pdfUrl = pdfViewer.getAttribute('src');
        //     const fullPdfUrl = `https://mitrapadi.com/receipts/receipt-${gilingId}.pdf`;

        //     // Bangun URL untuk ESC/POS
        //     const escposUrl = `print://escpos.org/escpos/bt/print?srcTp=uri&srcObj=pdf&numCopies=1&src=${encodeURIComponent(fullPdfUrl)}`;

        //     // Debug
        //     console.log(JSON.stringify({
        //         pdf_url: fullPdfUrl,
        //         print_url: escposUrl
        //     }, null, 2));

        //     // Panggil URL cetak
        //     window.location.href = escposUrl;
        // });

        // function initializeNumberFormatting(inputs) {
        //     inputs.forEach(input => {

        //         // Format saat input berubah
        //         input.addEventListener('input', function(e) {
        //             let value = this.value; // Ambil seluruh input
        //             this.dataset.rawValue = value;
        //             formatNumber(this);
        //         });
        //     });

        // }

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
                    let jumlahInput = item.querySelector(`[name^="pengambilans["][name$="[jumlah]"]`).value.replace(/,/g, ''); // Remove commas and convert to integer
                    let hargaInput = item.querySelector(`[name^="pengambilans["][name$="[harga]"]`).value.replace(/,/g, ''); // Remove commas and convert to integer

                    jumlahInput = parseInt(jumlahInput, 10); // Convert jumlahInput to integer
                    hargaInput = parseInt(hargaInput, 10); // Convert hargaInput to integer

                    if (keterangan !== '' || jumlahInput !== '' || hargaInput !== '') {
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
