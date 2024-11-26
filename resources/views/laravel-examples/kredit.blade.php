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
            margin-bottom: 0 !important;

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
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4 mx-4">
                        <div class="card-header pb-3 p-3 mx-2" id="btn-id">
                            <form method="GET" action="{{ route('kredit.index') }}">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">

                                    <!-- Bagian Label -->
                                    <h5 class="mb-3 mb-md-0">Manajemen Kredit</h5>

                                    <!-- Bagian Dropdown -->
                                    <div class="d-flex flex-wrap gap-2">
                                        <div style="width: 150px;">

                                            <select name="sort" id="sort-order" class="form-select" onchange="this.form.submit()">
                                                <option value="desc" {{ request('sort', 'desc') == 'desc' ? 'selected' : '' }}>Terbaru</option>
                                                <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Terlama</option>
                                            </select>

                                        </div>

                                        <div style="width: 150px;">

                                            <select name="status" id="status-filter" class="form-select" onchange="this.form.submit()">
                                                <option value="">Semua Status</option>
                                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Lunas</option>
                                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Belum Lunas</option>
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
                                    <div class="me-2 w-100" style="position: relative;" id="btn-id">
                                        <div class="input-group">
                                            <input type="text" id="search-input" name="search" class="form-control" placeholder="Cari kredit/hutang..." aria-label="Cari daftar kredit" value="{{ request('search') }}" autocomplete="off">
                                            <button class="btn btn-outline-primary mb-0" type="submit" aria-label="Cari">
                                                <i class="bi bi-search" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                        <div id="search-results" class="dropdown-menu w-100" style="display: none; position: absolute; max-height: 200px; overflow-y: auto; z-index: 1000;">
                                            <!-- Search results will be populated here -->
                                        </div>
                                    </div>

                                    <form method="GET" action="{{ route('laporan.kredit') }}">

                                        <a href="{{ route('laporan.kredit') }}" class="btn btn-cetak bg-gradient-primary d-flex align-items-center justify-content-center mt-3 me-2" id="btn-id" style="width: 233px;">
                                            <i class="bi bi-printer me-2"></i>
                                            <span>CETAK</span>
                                        </a>

                                    </form>


                                    <button class="btn btn-baru bg-gradient-primary d-flex align-items-center justify-content-center mt-3" type="button" id="btn-id" data-bs-toggle="modal" data-bs-target="#addKreditModal" style="width: 233px;">
                                        <i class="bi bi-plus-square me-2"></i>
                                        <span>Kredit Baru</span>
                                    </button>



                                </div>


                            </form>


                        </div>




                        <div class="card-body px-0 pt-0 pb-2">


                            <div class="mx-4 pb-0">
                                <h6 class="margin-atas text-uppercase text-primary font-weight-bolder">Ringkasan Kredit Belum Lunas</h6>
                            </div>
                            <div class="card-body px-0 pt-0 pb-2">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0">
                                        <tbody>
                                            <tr>
                                                <td class="text-start ps-4" style="border-top: none;">
                                                    <p class="text-sm mb-0">Jumlah Petani Belum Lunas</p>
                                                </td>
                                                <td class="text-end pe-4" style="border-top: none;">
                                                    <p class="text-sm font-weight-bold mb-0">{{ $jumlahPetaniBelumLunas }} Orang</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-start ps-4" style="border-top: none;">
                                                    <p class="text-sm mb-0">Total Kredit Belum Lunas</p>
                                                </td>
                                                <td class="text-end pe-4" style="border-top: none;">
                                                    <p class="text-sm font-weight-bold mb-0">Rp {{ number_format($totalKreditBelumLunas, 2, ',', '.') }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-start ps-4" style="border-top: none;">
                                                    <p class="text-sm mb-0">Total Bunga Kredit</p>
                                                </td>
                                                <td class="text-end pe-4" style="border-top: none;">
                                                    <p class="text-sm font-weight-bold mb-0">Rp {{ number_format($totalKreditPlusBungaBelumLunas-$totalKreditBelumLunas, 2, ',', '.') }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-start ps-4" style="border-top: none;">
                                                    <p class="text-sm mb-0">Total Kredit Dengan Bunga Belum Lunas</p>
                                                </td>
                                                <td class="text-end pe-4" style="border-top: none;">
                                                    <p class="text-sm font-weight-bold mb-0">Rp {{ number_format($totalKreditPlusBungaBelumLunas, 2, ',', '.') }}</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="table-responsive p-0">

                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">ID</th>
                                            <th class="text-uppercase text-primary font-weight-bolder ps-2" style="font-size: 0.85rem;">Petani</th>
                                            <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Alamat</th>
                                            <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Tanggal</th>
                                            <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Jumlah</th>
                                            <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Hutang + Bunga</th>
                                            <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Status</th>
                                            <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Keterangan</th>
                                            <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Action</th>
                                        </tr>
                                    </thead>



                                    @foreach($kredits as $kredit)
                                    <tbody>
                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ $kredit->id }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $kredit->petani->nama }}</p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ $kredit->petani->alamat }}</p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ $kredit->tanggal }}</p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">Rp {{ number_format($kredit->jumlah, 2, ',', '.') }}</p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">
                                                Rp {{ number_format($kredit->hutang_plus_bunga, 2, ',', '.') }} | {{ number_format($kredit->lama_bulan) }} Bulan
                                            </p>
                                            <small style="font-size: 0.7rem; color: #999;">
                                                Bunga: Rp {{ number_format($kredit->bunga, 2, ',', '.') }}
                                            </small>
                                        </td>
                                        <td class="text-center"><span class="badge badge-sm bg-gradient-{{ $kredit->status ? 'success' : 'warning' }}">{{ $kredit->status ? 'Lunas' : 'Belum Lunas' }}</span></td>
                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ $kredit->keterangan }}</p>
                                        </td>

                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <a href="#" class="btn btn-link text-dark px-2 mb-0" data-bs-toggle="modal" data-bs-target="#editKreditModal{{ $kredit->id }}">
                                                    <i class="bi bi-pencil-square text-dark me-2" aria-hidden="true"></i>
                                                    Edit
                                                </a>
                                                <form action="{{ route('kredit.destroy', $kredit->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link text-danger px-2 mb-0" onclick="return confirm('Are you sure you want to delete this item?')">
                                                        <i class="bi bi-trash3 text-danger me-2" aria-hidden="true"></i>
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tbody>
                                    @endforeach






                                    <!-- Edit Kredit Modal -->
                                    @foreach($kredits as $kredit)

                                    <div class="modal fade" id="editKreditModal{{ $kredit->id }}" tabindex="-1" role="dialog" aria-labelledby="editKreditModalLabel{{ $kredit->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editKreditModalLabel{{ $kredit->id }}">Edit Kredit</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form id="editKreditForm{{ $kredit->id }}" action="{{ route('kredit.update', $kredit->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <!-- ... (form fields) ... -->
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="petani_id">Petani</label>
                                                            <select class="form-control" id="petani_id" name="petani_id" required>
                                                                @foreach($petanis as $petani)
                                                                <option value="{{ $petani->id }}" {{ $kredit->petani_id == $petani->id ? 'selected' : '' }}>{{ $petani->nama }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="tanggal">Tanggal</label>
                                                            <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ date('Y-m-d', strtotime($kredit->tanggal)) }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="jumlah">Jumlah</label>
                                                            <input type="number" class="form-control" id="jumlah" name="jumlah" step="0.01" value="{{ $kredit->jumlah }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="keterangan">Keterangan</label>
                                                            <textarea class="form-control" id="keterangan" name="keterangan" required>{{ $kredit->keterangan }}</textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="status">Status</label>
                                                            <select class="form-control" id="status" name="status" required>
                                                                <option value="0" {{ $kredit->status == 0 ? 'selected' : '' }}>Belum Lunas</option>
                                                                <option value="1" {{ $kredit->status == 1 ? 'selected' : '' }}>Lunas</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn bg-gradient-primary">Save changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach

                                    <!-- Add Kredit Modal -->
                                    <div class="modal fade" id="addKreditModal" tabindex="-1" role="dialog" aria-labelledby="addKreditModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="addKreditModalLabel">Add New Kredit</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('kredit.store') }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="petani_search">Petani</label>
                                                            <div class="position-relative">
                                                                <input type="text" class="form-control" id="petani_search" placeholder="Search for a petani..." autocomplete="off" required>
                                                                <input type="hidden" id="petani_id" name="petani_id" required>
                                                                <div id="petani_search_results" class="dropdown-menu w-100" style="display: none; position: absolute; max-height: 200px; overflow-y: auto; z-index: 1000;"></div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="tanggal">Tanggal</label>
                                                            <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ date('Y-m-d') }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="jumlah">Jumlah</label>
                                                            <input type="number" class="form-control" id="jumlah" name="jumlah" step="0.01" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="keterangan">Keterangan</label>
                                                            <textarea class="form-control" id="keterangan" name="keterangan" required></textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="status">Status</label>
                                                            <select class="form-control" id="status" name="status" required>
                                                                <option value="0">Belum Lunas</option>
                                                                <option value="1">Lunas</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn bg-gradient-primary">Save changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </table>

                            </div>
                            <!-- Pagination -->
                            <div class="d-flex pagination-css justify-content-between align-items-center ps-2 mt-3 mb-3 mx-3">
                                <div>
                                    Showing
                                    <strong>{{ $kredits->firstItem() }}</strong> to
                                    <strong>{{ $kredits->lastItem() }}</strong> of
                                    <strong>{{ $kredits->total() }}</strong> entries
                                </div>
                                <div>
                                    @if ($kredits->lastPage() > 1)
                                    <nav>
                                        <ul class="pagination m-0">
                                            {{-- Previous Button --}}
                                            @if ($kredits->currentPage() > 1)
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $kredits->previousPageUrl() }}" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            @endif

                                            @php
                                            $currentPage = $kredits->currentPage();
                                            $lastPage = $kredits->lastPage();
                                            @endphp

                                            {{-- Always show first page --}}
                                            <li class="page-item {{ $currentPage == 1 ? 'active' : '' }}">
                                                <a class="page-link" href="{{ $kredits->url(1) }}">1</a>
                                            </li>

                                            {{-- Middle pages logic --}}
                                            @php
                                            $start = max(2, $currentPage - 1);
                                            $end = min($lastPage - 1, $currentPage + 1);
                                            @endphp

                                            @for ($i = $start; $i <= $end; $i++)
                                                <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                                                <a class="page-link" href="{{ $kredits->url($i) }}">{{ $i }}</a>
                                                </li>
                                                @endfor

                                                {{-- Always show last page --}}
                                                @if ($lastPage > 1)
                                                <li class="page-item {{ $currentPage == $lastPage ? 'active' : '' }}">
                                                    <a class="page-link" href="{{ $kredits->url($lastPage) }}">{{ $lastPage }}</a>
                                                </li>
                                                @endif

                                                {{-- Next Button --}}
                                                @if ($currentPage < $lastPage)
                                                    <li class="page-item">
                                                    <a class="page-link" href="{{ $kredits->nextPageUrl() }}" aria-label="Next">
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

                // Setup autocomplete for modal petani search
                setupAutocomplete('petani_search', 'petani_search_results', '/search-petani', function(petani) {
                    if (petaniIdInput) {
                        petaniIdInput.value = petani.id;
                        input.value = `${petani.nama} - ${petani.alamat}`; // Update input to show both name and address
                        console.log('Petani selected:', petani.nama, 'Alamat:', petani.alamat, 'ID:', petani.id);
                    } else {
                        console.error('petaniIdInput not found');
                    }
                });

                // Handle form submission for editing kredit
                document.querySelectorAll('form[id^="editKreditForm"]').forEach(form => {
                    form.addEventListener('submit', function(event) {
                        event.preventDefault();
                        const formData = new FormData(form);

                        fetch(form.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    console.log('Kredit updated successfully');
                                    location.reload();
                                } else {
                                    console.error('Error updating kredit:', data);
                                    alert('Error updating kredit: ' + (data.message || JSON.stringify(data)));
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('An error occurred while updating kredit: ' + error);
                            });
                    });
                });

                // Handle kredit deletion
                document.querySelectorAll('form[data-delete-kredit]').forEach(form => {
                    form.addEventListener('submit', function(event) {
                        event.preventDefault();
                        const kreditId = this.getAttribute('data-delete-kredit');
                        const deleteUrl = this.action;

                        if (confirm('Are you sure you want to delete this kredit?')) {
                            fetch(deleteUrl, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                    },
                                    body: JSON.stringify({
                                        _method: 'DELETE'
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        console.log('Kredit deleted successfully');
                                        location.reload();
                                    } else {
                                        console.error('Error deleting kredit:', data);
                                        alert('Error deleting kredit: ' + (data.message || JSON.stringify(data)));
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('An error occurred while deleting kredit: ' + error);
                                });
                        }
                    });
                });

                // Handle form submission for adding new kredit
                // Handle form submission for adding new kredit
                const addKreditForm = document.querySelector('#addKreditModal form');
                const addKreditModal = document.getElementById('addKreditModal');

                if (addKreditForm && addKreditModal) {
                    let isSubmitting = false; // Flag untuk mencegah multiple submission

                    addKreditForm.addEventListener('submit', function(event) {
                        event.preventDefault();

                        // Cek jika sedang dalam proses submit
                        if (isSubmitting) {
                            return;
                        }

                        if (!petaniIdInput || !petaniIdInput.value) {
                            alert('Silakan pilih petani sebelum menyimpan.');
                            return;
                        }

                        // Ambil tombol submit
                        const submitButton = this.querySelector('button[type="submit"]');

                        // Set flag dan nonaktifkan tombol
                        isSubmitting = true;
                        if (submitButton) {
                            submitButton.disabled = true;
                        }

                        const formData = new FormData(this);
                        // Ensure petani_id is added to formData
                        formData.set('petani_id', petaniIdInput.value);
                        console.log('Petani ID before send:', petaniIdInput.value);
                        console.log('Form data before send:', Object.fromEntries(formData));

                        fetch(this.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    console.log('New kredit added successfully');
                                    // Reset form fields
                                    addKreditForm.reset();
                                    // Clear petani search input and reset petani_id
                                    const petaniSearchInput = document.getElementById('petani_search');
                                    if (petaniSearchInput) {
                                        petaniSearchInput.value = '';
                                    }
                                    if (petaniIdInput) {
                                        petaniIdInput.value = '';
                                    }
                                    // Close the modal
                                    const modal = bootstrap.Modal.getInstance(addKreditModal);
                                    if (modal) {
                                        modal.hide();
                                    }
                                    // Reload the page
                                    location.reload();
                                } else {
                                    console.error('Error adding new kredit:', data);
                                    alert('Error adding new kredit: ' + (data.message || JSON.stringify(data)));
                                    // Reset flag dan aktifkan tombol kembali jika error
                                    isSubmitting = false;
                                    if (submitButton) {
                                        submitButton.disabled = false;
                                    }
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('An error occurred while adding new kredit: ' + error);
                                // Reset flag dan aktifkan tombol kembali jika error
                                isSubmitting = false;
                                if (submitButton) {
                                    submitButton.disabled = false;
                                }
                            });
                    });
                } else {
                    console.error('Add Kredit form not found');
                }
            });
        </script>




        @endsection