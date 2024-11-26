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
            margin-top: 0 !important;
        }



        .d-flex.flex-wrap {
            flex-direction: row !important;
            justify-content: flex-end;

            width: 100% !important;
        }

        h5.mb-3 {
            margin-bottom: 0 !important;
            margin-right: auto;
            margin-inline-start: 0;
            width: 100% !important;
        }

        /* Fixed width for dropdowns in landscape */
        #sort-order,
        #alamat-filter {
            width: 151px !important;
            transition: all 0.5s ease-in-out !important;

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


        /* Base transition untuk semua properti yang akan berubah */
        .d-flex.flex-wrap,
        .d-flex.flex-wrap>div,
        .form-select,
        #sort-order,
        #status-filter,
        #alamat-filter,
        #search-input,
        #btn-id {
            transition: all 0.5s ease-in-out !important;
        }


        .card-header {
            margin: 2 !important;
        }
    }

    /* Portrait Mode (Tablet/Mobile Vertical) */
    @media (max-width: 768px) and (orientation: portrait) {


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

        /* Fixed width for dropdowns in landscape */
        #sort-order,
        #alamat-filter {
            width: 100% !important;

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
            height: 40px !important;
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
            padding-bottom: 0 !important;
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
            transition: all 0.5s ease-in-out !important;
        }


    }
</style>

<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-3 p-3 mx-2">
                    <!-- Header Title and Dropdown Filters -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                        <!-- Title Section -->
                        <h5 class="mb-3 mb-md-0">Manajemen Debit</h5>

                        <!-- Filters and Action Button -->
                        <div class="d-flex flex-wrap" id="btn-id">
                            <!-- Sort Dropdown -->
                            <div>
                                <form method="GET" action="{{ route('debit.index') }}" class="d-flex flex-column flex-md-row align-items-start align-items-md-center " id="btn-id">
                                    <select name="sort" id="sort-order" class="form-select" onchange="this.form.submit()">
                                        <option value="desc" {{ request('sort', 'desc') == 'desc' ? 'selected' : '' }}>Terbaru</option>
                                        <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Terlama</option>
                                    </select>
                                </form>
                            </div>


                        </div>
                    </div>

                    <!-- Search Form and Add Button -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mt-3">
                        <form method="GET" action="{{ route('debit.index') }}" class="d-flex flex-column flex-md-row align-items-start align-items-md-center w-100">
                            <!-- Search Input -->
                            <div class="me-2 w-100" style="position: relative;">
                                <div class="input-group">
                                    <input type="text" id="search-input" name="search" class="form-control" placeholder="Cari debit/tunai..." aria-label="Cari daftar debit" value="{{ request('search') }}" autocomplete="off">
                                    <button class="btn btn-outline-primary mb-0" type="submit" aria-label="Cari">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                                <div id="search-results" class="dropdown-menu w-100" style="display: none; position: absolute; max-height: 200px; overflow-y: auto; z-index: 1000;">
                                    <!-- Search Results Rendered Here -->
                                </div>
                            </div>







                            <!-- Add Debit Button -->
                            <button class="btn btn-potrait bg-gradient-primary d-flex align-items-center justify-content-center mt-3 md-0" type="button" data-bs-toggle="modal" data-bs-target="#addDebitModal" id="btn-id" style="width: 180px;">
                                <i class="bi bi-pencil-square me-2"></i>
                                <span>New Debit</span>
                            </button>
                        </form>
                    </div>
                </div>


                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center">ID</th>
                                    <th class="text-uppercase text-primary font-weight-bolder ps-2">Petani</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center">Tanggal</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center">Jumlah</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center">Bunga</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center">Keterangan</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($debits as $debit)
                                <tr>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $debit->id }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $debit->petani->nama }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $debit->tanggal->format('d-m-Y') }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">Rp {{ number_format($debit->jumlah, 2, ',', '.') }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $debit->bunga }}%</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $debit->keterangan }}</p>
                                    </td>
                                    <td class="text-center">
                                        <!--<a href="#" class="btn btn-link text-dark px-2 mb-0" data-bs-toggle="modal" data-bs-target="#editDebitModal{{ $debit->id }}">-->
                                        <!--    <i class="bi bi-pencil-square text-dark me-2" aria-hidden="true"></i>-->
                                        <!--    Edit-->
                                        <!--</a>-->
                                        <form action="{{ route('debit.destroy', $debit->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger px-2 mb-0" onclick="return confirm('Are you sure you want to delete this item?')">
                                                <i class="bi bi-trash3 text-danger me-2" aria-hidden="true"></i>
                                                Delete
                                            </button>
                                        </form>
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
                            <strong>{{ $debits->firstItem() }}</strong> to
                            <strong>{{ $debits->lastItem() }}</strong> of
                            <strong>{{ $debits->total() }}</strong> entries
                        </div>
                        <div>
                            @if ($debits->lastPage() > 1)
                            <nav>
                                <ul class="pagination m-0">
                                    {{-- Previous Button --}}
                                    @if ($debits->currentPage() > 1)
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $debits->previousPageUrl() }}" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    @endif

                                    @php
                                    $currentPage = $debits->currentPage();
                                    $lastPage = $debits->lastPage();
                                    @endphp

                                    {{-- Always show first page --}}
                                    <li class="page-item {{ $currentPage == 1 ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $debits->url(1) }}">1</a>
                                    </li>

                                    {{-- Middle pages logic --}}
                                    @php
                                    $start = max(2, $currentPage - 1);
                                    $end = min($lastPage - 1, $currentPage + 1);
                                    @endphp

                                    @for ($i = $start; $i <= $end; $i++)
                                        <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $debits->url($i) }}">{{ $i }}</a>
                                        </li>
                                        @endfor

                                        {{-- Always show last page --}}
                                        @if ($lastPage > 1)
                                        <li class="page-item {{ $currentPage == $lastPage ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $debits->url($lastPage) }}">{{ $lastPage }}</a>
                                        </li>
                                        @endif

                                        {{-- Next Button --}}
                                        @if ($currentPage < $lastPage)
                                            <li class="page-item">
                                            <a class="page-link" href="{{ $debits->nextPageUrl() }}" aria-label="Next">
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

<!-- Add Debit Modal -->
<div class="modal fade" id="addDebitModal" tabindex="-1" role="dialog" aria-labelledby="addDebitModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDebitModalLabel">Add New Debit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addDebitForm" action="{{ route('debit.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- <div class="form-group">
                        <label for="petani_id">Petani</label>
                        <div class="input-group">
                            <select class="form-control" id="petani_id" name="petani_id" required onchange="updateTotalHutang(this)">
                                <option value="">Select Petani</option>
                                @foreach($petanisWithOutstandingKredits as $petani)
                                <option value="{{ $petani->id }}" data-total-hutang="{{ $petani->total_hutang }}">
                                    {{ $petani->nama }}
                                </option>
                                @endforeach
                            </select>

                        </div>
                    </div> -->
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
                        <input type="text" class="form-control number-format" id="jumlah" name="jumlah" inputmode="numeric" required>
                    </div>
                    <div class="form-group">
                        <label for="bunga">Bunga (%)</label>
                        <input type="text" class="form-control number-format" id="bunga" name="bunga" inputmode="numeric" required>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" required></textarea>
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