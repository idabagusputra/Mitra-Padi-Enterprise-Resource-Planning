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
                                    <button class="btn btn-outline-primary " type="submit" aria-label="Cari">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                                <div id="search-results" class="dropdown-menu w-100" style="display: none; position: absolute; max-height: 200px; overflow-y: auto; z-index: 1000;">
                                    <!-- Search Results Rendered Here -->
                                </div>
                            </div>




                            <!-- Add Debit Button -->
                            <button class="btn btn-potrait bg-gradient-primary d-flex align-items-center justify-content-center mt-md-0" type="button" data-bs-toggle="modal" data-bs-target="#addDebitModal" id="btn-id" style="width: 180px;">
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
                    <div class="form-group">
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
                            <!-- <span class="input-group-text" id="total-hutang">Total Hutang: Rp 0</span> -->
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



@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">


<script>
    input.addEventListener('input', function() {
        console.log('Input event triggered'); // Debug log
        const term = this.value.trim();
        if (term.length > 1) {
            fetch(`{{ route('debit.search') }}?term=${term}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Data received:', data); // Debugging response
                    results.innerHTML = '';
                    results.style.display = 'block';
                    data.forEach(item => {
                        const div = document.createElement('div');
                        div.className = 'dropdown-item';
                        div.textContent = `${item.nama} - ${item.alamat}`;
                        div.addEventListener('click', () => {
                            input.value = item.nama;
                            results.style.display = 'none';
                        });
                        results.appendChild(div);
                    });
                })
                .catch(error => console.error('Error:', error));
        } else {
            results.style.display = 'none';
        }
    });

    // Setup number formatting
    initializeNumberFormatting();

    // Setup autocomplete
    $('#search-input').autocomplete({
        source: function(request, response) {
            console.log('Autocomplete request:', request.term); // Debug log
            $.ajax({
                url: "{{ route('debit.search') }}",
                dataType: 'json',
                data: {
                    term: request.term
                },
                success: function(data) {
                    console.log('Data received:', data); // Debug log
                    response($.map(data, function(item) {
                        return {
                            label: `${item.nama} - ${item.alamat}`,
                            value: item.nama
                        };
                    }));
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', error); // Debug log
                }
            });
        },
        minLength: 2,
        select: function(event, ui) {
            $('#search-input').val(ui.item.value);
            $('form').submit();
        }
    });
</script>
@endpush