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
        }

        h5.mb-3 {
            margin-bottom: 0 !important;
            margin-right: auto;
            margin-inline-start: 0;
        }

        /* Fixed width for dropdowns in landscape */
        #sort-order,
        #alamat-filter {
            width: 151px !important;
        }

        /* Container for dropdowns */
        #responsiveDiv {
            display: flex !important;
            flex-direction: row !important;
        }

        /* Reset width for dropdown containers */
        #responsiveDiv>div {
            width: auto !important;
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
        }

        .input-group {
            width: 100%;
        }

        .btn {

            height: auto;
        }

        .btn-potrait {
            width: 100% !important;
            height: auto;
            margin-top: 0;
        }

        .form-control,
        .btn {
            height: auto !important;
        }

        #responsiveDiv {
            width: 100%;
        }

        #responsiveDiv>div {
            width: 100% !important;
            margin-bottom: 1rem;
        }

        .card-header {
            margin: 0 !important;
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

    }
</style>

<div>
    <div>
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-3 p-3 mx-2">
                    <form method="GET" action="{{ route('petani.index') }}">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">

                            <!-- Bagian Label -->
                            <!-- Bagian Label -->
                            <!-- Bagian Label -->
                            <h5 class=" mb-3 mb-md-0">Manajemen Petani</h5>

                            <!-- Bagian Dropdown -->
                            <div class="d-flex flex-wrap gap-sm-2 gap-lg-3 gap-xl-4" id="responsiveDiv">
                                <div style="width: 100%;">

                                    <select name="sort" id="sort-order" class="form-select" onchange="this.form.submit()">
                                        <option value="desc" {{ request('sort', 'desc') == 'desc' ? 'selected' : '' }}>Terbaru</option>
                                        <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Terlama</option>
                                    </select>

                                </div>


                                <div style="width: 100%;">

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
                                    <input type="text" id="search-input" name="search" class="form-control" placeholder="Cari petani..." aria-label="Cari daftar petani" value="{{ request('search') }}" autocomplete="off">
                                    <button class="btn btn-outline-primary mb-0" type="submit" aria-label="Cari">
                                        <i class="bi bi-search" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <div id="search-results" class="dropdown-menu w-100" style="display: none; position: absolute; max-height: 200px; overflow-y: auto; z-index: 1000;">
                                    <!-- Search results will be populated here -->
                                </div>
                            </div>

                            <button class="btn btn-potrait bg-gradient-primary d-flex align-items-center justify-content-center mt-3" id="btn-id" type="button" data-bs-toggle="modal" data-bs-target="#addPetaniModal" style="width: 180px;">
                                <i class="bi bi-plus-square me-2"></i>
                                <span>Petani Baru</span>
                            </button>
                        </div>


                    </form>
                </div>


                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">ID</th>
                                    <th class="text-uppercase text-primary font-weight-bolder ps-2" style="font-size: 0.85rem;">Nama</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Alamat</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">No Telepon</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Total Hutang</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($petanis as $petani)
                                <tr>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $petani->id }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $petani->nama }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $petani->alamat }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $petani->no_telepon }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">Rp {{ number_format($petani->total_hutang, 0, ',', '.') }},00</p>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <a href="#" class="btn btn-link text-dark px-2 mb-0" data-bs-toggle="modal" data-bs-target="#editPetaniModal{{ $petani->id }}">
                                                <i class="bi bi-pencil-square text-dark me-2" aria-hidden="true"></i>
                                                Edit
                                            </a>
                                            <!-- <form action="{{ route('petani.destroy', $petani->id) }}" method="POST" data-delete-petani="{{ $petani->id }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger px-2 mb-0">
                                                    <i class="bi bi-trash3 text-danger me-2" aria-hidden="true"></i>
                                                    Delete
                                                </button>
                                            </form> -->
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div class="d-flex pagination-css justify-content-between align-items-center ps-2 mt-3 mb-3 mx-3">
                        <div>
                            Showing
                            <strong>{{ $petanis->firstItem() }}</strong> to
                            <strong>{{ $petanis->lastItem() }}</strong> of
                            <strong>{{ $petanis->total() }}</strong> entries
                        </div>
                        <div>
                            @if ($petanis->lastPage() > 1)
                            <nav>
                                <ul class="pagination m-0">
                                    {{-- Previous Button --}}
                                    @if ($petanis->currentPage() > 1)
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $petanis->previousPageUrl() }}" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    @endif

                                    @php
                                    $currentPage = $petanis->currentPage();
                                    $lastPage = $petanis->lastPage();
                                    @endphp

                                    {{-- Always show first page --}}
                                    <li class="page-item {{ $currentPage == 1 ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $petanis->url(1) }}">1</a>
                                    </li>

                                    {{-- Middle pages logic --}}
                                    @php
                                    $start = max(2, $currentPage - 1);
                                    $end = min($lastPage - 1, $currentPage + 1);
                                    @endphp

                                    @for ($i = $start; $i <= $end; $i++)
                                        <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $petanis->url($i) }}">{{ $i }}</a>
                                        </li>
                                        @endfor

                                        {{-- Always show last page --}}
                                        @if ($lastPage > 1)
                                        <li class="page-item {{ $currentPage == $lastPage ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $petanis->url($lastPage) }}">{{ $lastPage }}</a>
                                        </li>
                                        @endif

                                        {{-- Next Button --}}
                                        @if ($currentPage < $lastPage)
                                            <li class="page-item">
                                            <a class="page-link" href="{{ $petanis->nextPageUrl() }}" aria-label="Next">
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

<!-- Add Petani Modal -->
<div class="modal fade" id="addPetaniModal" tabindex="-1" role="dialog" aria-labelledby="addPetaniModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPetaniModalLabel">Add New Petani</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('petani.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" required>
                    </div>
                    <div class="form-group">
                        <label for="no_telepon">No Telepon</label>
                        <input type="text" class="form-control" id="no_telepon" name="no_telepon" inputmode="numeric" required>
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

<!-- Edit Petani Modal -->
@foreach($petanis as $petani)
<div class="modal fade" id="editPetaniModal{{ $petani->id }}" tabindex="-1" role="dialog" aria-labelledby="editPetaniModalLabel{{ $petani->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header justify-content-center">
                <h5 class="modal-title" id="editPetaniModalLabel{{ $petani->id }}">Edit Petani</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editPetaniForm{{ $petani->id }}" action="{{ route('petani.update', $petani->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ $petani->nama }}" required>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" value="{{ $petani->alamat }}" required>
                    </div>
                    <div class="form-group">
                        <label for="no_telepon">No Telepon</label>
                        <input type="text" class="form-control" id="no_telepon" name="no_telepon" value="{{ $petani->no_telepon }}" required>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const messageDiv = document.getElementById('message');

        // Show message function
        function showMessage(message, type = 'success') {
            if (messageDiv) {
                messageDiv.textContent = message;
                messageDiv.className = `alert alert-${type}`; // Bootstrap alert classes
                messageDiv.style.display = 'block';
                setTimeout(() => {
                    messageDiv.style.display = 'none';
                }, 3000); // Hide message after 3 seconds
            } else {
                console.error("messageDiv is null.");
            }
        }

        // Delete Petani
        document.querySelectorAll('form[data-delete-petani]').forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                const petaniId = this.getAttribute('data-delete-petani');

                if (confirm('Are you sure you want to delete this petani?')) {
                    this.submit(); // Submit the form directly
                }
            });
        });

        // Add New Petani
        const addPetaniForm = document.querySelector('#addPetaniModal form');

        if (addPetaniForm) {
            let isSubmitting = false;

            addPetaniForm.addEventListener('submit', function(event) {
                event.preventDefault();

                // Cek jika sedang dalam proses submit
                if (isSubmitting) {
                    return;
                }

                // Ambil tombol submit
                const submitButton = this.querySelector('button[type="submit"]');

                // Set flag dan nonaktifkan tombol
                isSubmitting = true;
                if (submitButton) {
                    submitButton.disabled = true;
                }

                try {
                    this.submit(); // Submit form langsung
                } catch (error) {
                    // Jika terjadi error, kembalikan status
                    isSubmitting = false;
                    if (submitButton) {
                        submitButton.disabled = false;
                    }
                    console.error('Error submitting form:', error);
                }
            });
        } else {
            console.error('Add Petani form not found');
        }

        // Edit Petani
        document.querySelectorAll('form[id^="editPetaniForm"]').forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                this.submit(); // Submit the form directly
            });
        });

        // Search functionality
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

        // Close the dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!searchInput.contains(event.target) && !searchResults.contains(event.target)) {
                searchResults.style.display = 'none';
            }
        });

        // function adjustWidth() {
        //     var div = document.getElementById('responsiveDiv');
        //     if (window.innerWidth > window.innerHeight) {
        //         // Landscape
        //         div.style.width = '150px';
        //         div.style.flexDirection = 'row'; // Mengatur flex menjadi baris
        //     } else {
        //         // Potrait
        //         div.style.width = '100%';
        //         div.style.flexDirection = 'column'; // Mengatur flex menjadi kolom
        //     }
        // }

        // // Panggil fungsi pertama kali saat halaman dimuat
        // window.onload = adjustWidth;

        // // Panggil fungsi setiap kali ukuran jendela berubah
        // window.onresize = adjustWidth;
    });
</script>





@endsection