@extends('layouts.user_type.auth')

@section('content')

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<style>
    /* Base styles */
    #search-results {
        position: absolute;
        background-color: white;
        border: 1px solid #ddd;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
    }

    #search-results .dropdown-item {
        padding: 10px;
        cursor: pointer;
    }

    #search-results .dropdown-item:hover {
        background-color: #f8f9fa;
    }

    .form-control,
    .btn {
        height: 40px;
    }

    body {
        overflow-x: hidden;
    }

    /* Container styles */
    .d-flex.flex-column.flex-md-row {
        width: 100%;
    }

    /* Dropdown container styles */
    .d-flex.flex-wrap {
        display: flex;
        gap: 1rem;
        width: 100%;
    }

    /* Base select dropdown styles */
    .form-select {
        width: 100%;
    }


    /* Landscape Mode (Desktop/Tablet Horizontal) */
    @media (min-width: 769px) {
        .d-flex.flex-column.flex-md-row {
            align-items: center !important;
            flex-direction: row !important;
        }

        .d-flex.flex-wrap {
            flex-direction: row !important;
            justify-content: flex-end;
            width: auto;
            gap: 1rem;
        }

        h5.mb-3 {
            margin-bottom: 0 !important;
            margin-right: auto;
            margin-inline-start: 0;
        }

        /* Fixed width for all dropdowns in landscape */
        #sort-order,
        #status-filter,
        #alamat-filter {
            width: 151px !important;
        }

        /* Button group styling */
        .btn-group-wrapper {
            display: flex;
            gap: 1rem;
        }

        #search-input,
        #btn-id {
            transition: all 0.5s ease-in-out;
        }

        .card-header {
            margin: 2 !important;
        }


    }

    /* Portrait Mode (Tablet/Mobile Vertical) */
    @media (max-width: 768px) and (orientation: portrait) {
        .card-header {
            padding-bottom: 0 !important;
        }

        .d-flex.flex-column.flex-md-row {
            flex-direction: column !important;
            width: 100% !important;
            gap: 0 !important;
        }

        .d-flex.flex-wrap {
            flex-direction: column;
            width: 100% !important;
            gap: 0 !important;
        }

        h5.mb-3 {
            width: 100%;
        }

        .form-select {
            width: 100%;
            margin-bottom: 1rem;
        }

        .input-group {
            width: 100%;
        }

        .btn {
            height: auto;
        }

        /* Adjust button styling for portrait mode */
        .btn-cetak {
            width: 100% !important;
            height: auto;
            margin-right: 0 !important;



        }


        .btn-baru {
            width: 100% !important;
            height: auto;
            margin-right: 0 !important;
            padding-bottom: 3 !important;

        }

        .form-control,
        .btn {
            height: auto !important;
        }

        /* Styling for dropdown containers */
        .d-flex.flex-wrap>div {
            width: 100% !important;

        }

        /* Base transition untuk semua properti yang akan berubah */
        .d-flex.flex-wrap,
        .d-flex.flex-wrap>div,
        .form-select,
        #sort-order,
        #status-filter,
        #alamat-filter,
        #search-input,
        #btn-id {
            transition: all 0.5s ease-in-out;
        }

        .card-header {
            margin: 0 !important;
        }


        /* .card-header {
            padding: 3 !important;
            padding-bottom: 3 !important;
            margin: 0 !important;
        } */
    }
</style>

<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">


                <div class="card-header pb-3 p-3 mx-2">
                    <form method="GET" action="{{ route('daftar-giling-ryclebin.index') }}">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">

                            <!-- Ida Bagus Putu Putra Manuaba -->

                            <!-- Bagian Label -->
                            <h5 class="mb-3 mb-md-0">Daftar Giling</h5>

                            <!-- Bagian Dropdown -->
                            <div class="d-flex flex-wrap gap-2">
                                <div style="width: 150px;">

                                    <select name="sort" id="sort-order" class="form-select" onchange="this.form.submit()">
                                        <option value="desc" {{ request('sort', 'desc') == 'desc' ? 'selected' : '' }}>Terbaru</option>
                                        <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Terlama</option>
                                    </select>

                                </div>

                                <div style="width: 150px;">

                                    <select name="alamat" id="alamat-filter" class="form-select" onchange="this.form.submit()">
                                        <option value="all">Semua Alamat</option>
                                        <option value="campur" {{ request('alamat') == 'campur' ? 'selected' : '' }}>Campur</option>
                                        @foreach($alamatList as $alamat)
                                        <option value="{{ $alamat }}" {{ request('alamat') == $alamat ? 'selected' : '' }}>{{ $alamat }}</option>
                                        @endforeach
                                    </select>

                                </div>



                            </div>


                        </div>

                        <!-- Bagian Search dan Tombol -->

                        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center w-100">
                            <div class="me-2 w-100" style="position: relative;">
                                <div class="input-group">
                                    <input type="text" id="search-input" name="search" class="form-control" placeholder="Cari nota giling terhapus..." aria-label="Cari daftar kredit" value="{{ request('search') }}" autocomplete="off">
                                    <button class="btn btn-outline-primary mb-0" type="submit" aria-label="Cari">
                                        <i class="bi bi-search" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <div id="search-results" class="dropdown-menu w-100" style="display: none; position: absolute; max-height: 200px; overflow-y: auto; z-index: 1000;">
                                    <!-- Search results will be populated here -->
                                </div>
                            </div>

                            <a href="/giling" class="btn btn-cetak bg-gradient-primary d-flex align-items-center justify-content-center mt-3" style="width: 180px;" id="btn-id">
                                <i class="bi bi-plus-square me-2"></i>
                                <span>Giling Baru</span>
                            </a>

                        </div>


                    </form>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">ID</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Action</th>
                                    <th class="text-uppercase text-primary font-weight-bolder ps-2" style="font-size: 0.85rem;">Petani</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Tanggal</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Sisa Dana</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Total Giling</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Beras Jual</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Harga Jual</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Buruh Giling</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Buruh Jemur</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Jual Konga</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Jual Menir</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Bunga Hutang</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Total Pengambilan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($daftarGilings as $daftarGiling)
                                <tr>
                                    <td class="ps-4">
                                        <p class="text-xs font-weight-bold mb-0">{{ $daftarGiling->id }}</p>
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="btn btn-link text-dark px-2 mb-0 view-pdf-btn" data-id="{{ $daftarGiling->id }}">
                                            <i class="bi bi-eye text-dark me-2" aria-hidden="true"></i>
                                            View
                                        </a>
                                        <!-- <form action="{{ route('daftar-giling.destroy', $daftarGiling->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger text-gradient px-2 mb-0" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                <i class="bi bi-trash3 me-2"></i>
                                                Delete
                                            </button>
                                        </form> -->
                                        <!-- <form action="{{ route('daftar-giling-ryclebin.restore', $daftarGiling->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-link px-2 mb-0" onclick="return confirm('Are you sure you want to restore this item?')" style="color: #1F4529;">
                                                <i class="bi bi-arrow-clockwise me-2" aria-hidden="true" style="color: #059212;"></i>
                                                <span style="color: #059212;">Restore</span>
                                            </button>


                                        </form> -->
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $daftarGiling->giling->petani->nama }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $daftarGiling->created_at->format('Y-m-d') }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">Rp {{ number_format($daftarGiling->dana_penerima, 2, ',', '.') }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ number_format($daftarGiling->giling_kotor, 2, ',', '.') }} Kg</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ number_format($daftarGiling->beras_jual, 2, ',', '.') }} Kg</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">Rp {{ number_format($daftarGiling->harga_jual, 2, ',', '.') }},00</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">Rp {{ number_format($daftarGiling->total_biaya_buruh_giling, 2, ',', '.') }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">Rp {{ number_format($daftarGiling->total_biaya_buruh_jemur, 2, ',', '.') }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">Rp {{ number_format($daftarGiling->dana_jual_konga, 2, ',', '.') }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">Rp {{ number_format($daftarGiling->dana_jual_menir, 2, ',', '.') }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ number_format($daftarGiling->bunga) }}%</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">Rp {{ number_format($daftarGiling->total_pengambilan, 2, ',', '.') }}</p>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Pagination -->
                <div class="d-flex pagination-css justify-content-between align-items-center ps-2 mt-3 mb-3 mx-3">
                    <div>
                        Showing
                        <strong>{{ $daftarGilings->firstItem() }}</strong> to
                        <strong>{{ $daftarGilings->lastItem() }}</strong> of
                        <strong>{{ $daftarGilings->total() }}</strong> entries
                    </div>
                    <div>
                        @if ($daftarGilings->lastPage() > 1)
                        <nav>
                            <ul class="pagination m-0">
                                {{-- Previous Button --}}
                                @if ($daftarGilings->currentPage() > 1)
                                <li class="page-item">
                                    <a class="page-link" href="{{ $daftarGilings->previousPageUrl() }}" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                @endif

                                @php
                                $currentPage = $daftarGilings->currentPage();
                                $lastPage = $daftarGilings->lastPage();
                                @endphp

                                {{-- Always show first page --}}
                                <li class="page-item {{ $currentPage == 1 ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $daftarGilings->url(1) }}">1</a>
                                </li>

                                {{-- Middle pages logic --}}
                                @php
                                $start = max(2, $currentPage - 1);
                                $end = min($lastPage - 1, $currentPage + 1);
                                @endphp

                                @for ($i = $start; $i <= $end; $i++)
                                    <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $daftarGilings->url($i) }}">{{ $i }}</a>
                                    </li>
                                    @endfor

                                    {{-- Always show last page --}}
                                    @if ($lastPage > 1)
                                    <li class="page-item {{ $currentPage == $lastPage ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $daftarGilings->url($lastPage) }}">{{ $lastPage }}</a>
                                    </li>
                                    @endif

                                    {{-- Next Button --}}
                                    @if ($currentPage < $lastPage)
                                        <li class="page-item">
                                        <a class="page-link" href="{{ $daftarGilings->nextPageUrl() }}" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                        </li>
                                        @endif
                            </ul>
                        </nav>
                        @endif
                    </div>
                </div>
            </div>

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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const petaniIdInput = document.getElementById('petani_id');

        // Fungsi untuk setup autocomplete
        // Fungsi untuk setup autocomplete
        function setupAutocomplete(inputId, resultsId, url, onSelectCallback) {
            const input = document.getElementById(inputId);
            const results = document.getElementById(resultsId);

            // Tambahkan styling untuk dropdown
            results.style.cssText = `
        max-height: 300px;
        overflow-y: auto;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: 0 2px 4px #cc0c9c;
    `;

            input.addEventListener('input', function() {
                const searchTerm = this.value.trim();
                if (searchTerm.length > 0) {
                    fetch(`${url}?term=${searchTerm}`)
                        .then(response => response.json())
                        .then(data => {
                            results.innerHTML = '';
                            results.style.display = 'block';

                            data.forEach(item => {
                                const div = document.createElement('div');
                                div.classList.add('dropdown-item');

                                // Buat container untuk nama dan alamat
                                const nameSpan = document.createElement('span');
                                nameSpan.style.fontWeight = 'bold';
                                nameSpan.style.color = '#cc0c9c'; // Menambahkan warna ungu (#890f82)
                                nameSpan.textContent = item.nama;

                                const addressSpan = document.createElement('span');
                                addressSpan.style.color = '#666';
                                addressSpan.style.fontSize = '0.9em';
                                addressSpan.textContent = ` - ${item.alamat}`;

                                // Gabungkan nama dan alamat
                                div.appendChild(nameSpan);
                                div.appendChild(addressSpan);

                                // Styling untuk item dropdown
                                div.style.cssText = `
                            padding: 8px 12px;
                            cursor: pointer;
                            border-bottom: 1px solid #eee;
                        `;

                                // Hover effect
                                div.addEventListener('mouseover', () => {
                                    div.style.backgroundColor = '#f5f5f5';
                                });
                                div.addEventListener('mouseout', () => {
                                    div.style.backgroundColor = 'white';
                                });

                                div.addEventListener('click', function() {
                                    // Update input dengan nama saja
                                    input.value = item.nama;
                                    results.style.display = 'none';
                                    if (onSelectCallback) onSelectCallback(item);
                                });

                                results.appendChild(div);
                            });
                        });
                } else {
                    results.style.display = 'none';
                }
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (e.target !== input && e.target !== results) {
                    results.style.display = 'none';
                }
            });
        }

        // Setup autocomplete for index search
        setupAutocomplete('search-input', 'search-results', '/search-kredit', function(item) {
            document.querySelector('form').submit();
        });

        document.addEventListener('click', function(e) {
            if (e.target !== searchInput && e.target !== searchResults) {
                searchResults.style.display = 'none';
            }
        });

        // Updated event listener for View buttons

        let scrollPosition = 0;

        document.querySelectorAll('.view-pdf-btn').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                scrollPosition = window.pageYOffset;
                const gilingId = this.getAttribute('data-id');
                const pdfPath = `/receipts/receipt-${gilingId}.pdf`;

                // Set src viewer PDF
                document.getElementById('pdfViewer').src = pdfPath;

                // Update modal title
                document.getElementById('pdfModalLabel').textContent = `Receipt #${gilingId}`;

                const modalElement = document.getElementById('pdfModal');
                const pdfModal = new bootstrap.Modal(modalElement);

                // Handle modal events to fix mobile scrolling
                modalElement.addEventListener('shown.bs.modal', function() {
                    document.body.style.overflow = 'hidden';
                });

                modalElement.addEventListener('hidden.bs.modal', function() {
                    document.body.style.overflow = 'auto';
                    document.body.style.position = 'relative';
                    // Reset any inline styles that might affect scrolling
                    window.scrollTo(0, window.scrollY);
                });

                // Add click event listener to close modal when clicking outside
                modalElement.addEventListener('click', function(event) {
                    if (event.target === modalElement) {
                        pdfModal.hide();
                    }
                });

                // Add click event listener for close buttons
                document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(button => {
                    button.addEventListener('click', () => {
                        pdfModal.hide();
                    });
                });

                pdfModal.show();
            });
        });


        // Dalam event hidden.bs.modal
        modalElement.addEventListener('hidden.bs.modal', function() {
            document.body.style.overflow = 'auto';
            document.body.style.position = 'relative';
            document.body.style.top = '';
            window.scrollTo(0, scrollPosition);
        });

        // Event listener for Print button
        document.getElementById('printPdf').addEventListener('click', function() {
            const pdfViewer = document.getElementById('pdfViewer').contentWindow;
            pdfViewer.print();
        });
    });
</script>





@endsection