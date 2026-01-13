@extends('layouts.user_type.auth')

@section('content')


<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

<style>
/* Styling untuk checkbox filter yang lebih visual - Layout Vertikal */
.filter-checkbox-wrapper {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    width: 100%;
}

.filter-checkbox-item {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
    padding: 0.75rem 1rem;
    border: 2px solid #dee2e6;
    border-radius: 8px;
    transition: all 0.3s ease;
    background: white;
    width: 100%;
    min-height: 50px;
}

.filter-checkbox-item:hover {
    border-color: #adb5bd;
    background: #f8f9fa;
}

.filter-checkbox-item input[type="checkbox"] {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

.filter-checkbox-left {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    flex: 1;
}

.filter-checkbox-item .checkbox-icon {
    width: 24px;
    height: 24px;
    min-width: 24px;
    border: 2px solid #ced4da;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    transition: all 0.3s ease;
}

.filter-checkbox-item .checkbox-label {
    font-weight: 600;
    font-size: 14px;
    color: #495057;
    user-select: none;
    transition: all 0.3s ease;
}

/* Badge status untuk menunjukkan status */
.filter-checkbox-item .status-badge {
    font-size: 12px;
    padding: 6px 16px;
    border-radius: 6px;
    font-weight: 600;
    transition: all 0.3s ease;
    white-space: nowrap;
    min-width: 100px;
    text-align: center;
}

/* Status BELUM GANTI (Default/Unchecked) */
.filter-checkbox-item .status-badge {
    background: #e9ecef;
    color: #6c757d;
    border: 1px solid #dee2e6;
}

/* ===== FILTER OLI - HIJAU ===== */
.filter-oli-item input[type="checkbox"]:checked ~ .filter-checkbox-left .checkbox-icon {
    background: #10b981;
    border-color: #10b981;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.25);
}

.filter-oli-item input[type="checkbox"]:checked ~ .filter-checkbox-left .checkbox-icon::after {
    content: "✓";
    color: white;
    font-weight: bold;
    font-size: 16px;
}

.filter-oli-item input[type="checkbox"]:checked ~ .filter-checkbox-left .checkbox-label {
    color: #10b981;
    font-weight: 700;
}

.filter-oli-item input[type="checkbox"]:checked ~ .status-badge {
    background: #10b981;
    color: white;
    border: 1px solid #10b981;
    box-shadow: 0 2px 6px rgba(16, 185, 129, 0.2);
}

.filter-oli-item:has(input[type="checkbox"]:checked) {
    border-color: #10b981;
    background: #f0fdf4;
}

/* ===== FILTER SOLAR - BIRU ===== */
.filter-solar-item input[type="checkbox"]:checked ~ .filter-checkbox-left .checkbox-icon {
    background: #3b82f6;
    border-color: #3b82f6;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.25);
}

.filter-solar-item input[type="checkbox"]:checked ~ .filter-checkbox-left .checkbox-icon::after {
    content: "✓";
    color: white;
    font-weight: bold;
    font-size: 16px;
}

.filter-solar-item input[type="checkbox"]:checked ~ .filter-checkbox-left .checkbox-label {
    color: #3b82f6;
    font-weight: 700;
}

.filter-solar-item input[type="checkbox"]:checked ~ .status-badge {
    background: #3b82f6;
    color: white;
    border: 1px solid #3b82f6;
    box-shadow: 0 2px 6px rgba(59, 130, 246, 0.2);
}

.filter-solar-item:has(input[type="checkbox"]:checked) {
    border-color: #3b82f6;
    background: #eff6ff;
}

/* Responsif untuk mobile */
@media (max-width: 576px) {
    .filter-checkbox-item {
        padding: 0.65rem 0.875rem;
        min-height: 46px;
    }

    .filter-checkbox-item .checkbox-label {
        font-size: 13px;
    }

    .filter-checkbox-item .status-badge {
        font-size: 11px;
        padding: 5px 12px;
        min-width: 90px;
    }
}






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
            /* transition: all 0.5s ease-in-out; */
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
            <div class="card mb-4 ">
                <div class="card-header pb-3 p-3 mx-2">
                     <!-- Bagian Label -->
                            <!-- Bagian Label -->
                            <!-- Bagian Label -->
                            <h5 class=" mb-3 mb-md-0">Manajemen Servis Kendaraan</h5>
                    <form method="GET" action="{{ route('cars.index') }}">

                       <!-- Bagian Search dan Tombol -->
                        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center w-100">
                            <div class="me-2 w-100" style="position: relative;">
                                <div class="input-group">
                                    <input type="text" id="search-input" name="search" class="form-control" placeholder="Cari mobil..." aria-label="Cari daftar mobil" value="{{ request('search') }}" autocomplete="off">
                                    <button class="btn btn-outline-primary mb-0" type="submit" aria-label="Cari">
                                        <i class="bi bi-search" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <div id="search-results" class="dropdown-menu w-100" style="display: none; position: absolute; max-height: 200px; overflow-y: auto; z-index: 1000;">
                                    <!-- Search results will be populated here -->
                                </div>
                            </div>

                            <button class="btn btn-potrait bg-gradient-primary d-flex align-items-center justify-content-center mt-3" id="btn-id" type="button" data-bs-toggle="modal" data-bs-target="#addCarModal" style="width: 180px;">
                                <i class="bi bi-plus-square me-2"></i>
                                <span>Mobil Baru</span>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                         <table class="table align-items-center mb-0">
            <thead>
                <tr>
                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">No</th>
                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Nama Mobil</th>
                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Tanggal Servis</th>
                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Kilometer</th>
                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Status</th>
                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Filter Oli</th>
                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Filter Solar</th>
<th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Keterangan</th>
                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $currentGroup = '';
                    $showGroupHeader = false;
                @endphp

                @foreach($groupedCars as $car)
                    @php
                        $groupKey = $car->nama_mobil . '_' . $car->status;
                        if ($currentGroup !== $groupKey) {
                            $currentGroup = $groupKey;
                            $showGroupHeader = true;
                        } else {
                            $showGroupHeader = false;
                        }
                    @endphp

                       {{-- <tr class="{{ $showGroupHeader ? 'border-top-2 border-primary' : '' }}">
                        <td class="text-center">
                            <p class="text-xs font-weight-bold mb-0">{{ $car->nomor_urut }}</p>
                        </td>
                        <td class="text-center {{ $showGroupHeader ? 'font-weight-bolder' : '' }}">
                            <p class="text-xs {{ $showGroupHeader ? 'font-weight-bolder text-primary' : 'font-weight-bold' }} mb-0">
                                {{ $car->nama_mobil }}
                            </p>
                        </td> --}}

                    <tr class="{{ $showGroupHeader ? '' : '' }}">
                        <td class="text-center">
                            <p class="text-xs font-weight-bold mb-0">{{ $car->nomor_urut }}</p>
                        </td>
                        <td class="text-center {{ $showGroupHeader ? 'font-weight-bolder' : '' }}">
                            <p class="text-xs {{ $showGroupHeader ? 'font-weight-bolder text-primary' : 'font-weight-bold' }} mb-0">
                                {{ $car->nama_mobil }}
                            </p>
                        </td>
                        <td class="text-center">
                            <p class="text-xs font-weight-bold mb-0">{{ $car->tanggal_servis_formatted }}</p>
                        </td>
                        <td class="text-center">
                            <p class="text-xs font-weight-bold mb-0">{{ number_format($car->kilometer, 0, ',', '.') }} KM</p>
                        </td>
                        <td class="text-center">
                            @if($car->status == 'belum_servis')
                                <span class="badge badge-sm bg-gradient-warning">Belum Servis</span>
                            @else
                                <span class="badge badge-sm bg-gradient-success">Sudah Servis</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($car->filter_oli)
                                <span class="badge badge-sm bg-gradient-success">Sudah Ganti</span>
                            @else
                                <span class="badge badge-sm bg-gradient-secondary">Belum Ganti</span>
                            @endif
                        </td>

                        <td class="text-center">
    @if($car->filter_solar)
        <span class="badge badge-sm bg-gradient-info">Sudah Ganti</span>
    @else
        <span class="badge badge-sm bg-gradient-secondary">Belum Ganti</span>
    @endif
</td>
<td class="text-center">
    <p class="text-xs font-weight-bold mb-0" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $car->keterangan }}">
        {{ $car->keterangan ?? '-' }}
    </p>
</td>

                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center">
                                <a href="#" class="btn btn-link text-dark px-2 mb-0" data-bs-toggle="modal" data-bs-target="#editCarModal{{ $car->id }}">
                                    <i class="bi bi-pencil-square text-dark me-2" aria-hidden="true"></i>
                                    Edit
                                </a>
                                @if($car->status == 'belum_servis')
                                <button class="btn btn-link text-primary px-2 mb-0" onclick="openServisModal('{{ $car->nama_mobil }}', {{ $car->kilometer }})">
                                    <i class="bi bi-gear text-primary me-2" aria-hidden="true"></i>
                                    Servis
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Car Modal -->
<div class="modal fade" id="addCarModal" tabindex="-1" role="dialog" aria-labelledby="addCarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCarModalLabel">Tambah Mobil Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addCarForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_mobil">Nama Mobil</label>
                        <input type="text" class="form-control" id="nama_mobil" name="nama_mobil" required>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_servis">Tanggal Servis</label>
                        <input type="date" class="form-control" id="tanggal_servis" name="tanggal_servis" required>
                    </div>
                    <div class="form-group">
                        <label for="kilometer">Kilometer</label>
                        <input type="number" class="form-control" id="kilometer" name="kilometer" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="belum_servis">Belum Servis</option>
                            <option value="sudah_servis">Sudah Servis</option>
                        </select>
                    </div>
                    <div class="form-group">
    <label class="form-label fw-bold mb-3">Filter</label>
    <div class="filter-checkbox-wrapper">
        <!-- Filter Oli -->
        <label class="filter-checkbox-item filter-oli-item" style="margin-left: 0 !important; margin-right: 0 !important;">
            <input type="checkbox" id="filter_oli" name="filter_oli" value="1">
            <div class="filter-checkbox-left">
                <span class="checkbox-icon"></span>
                <span class="checkbox-label">Filter Oli</span>
            </div>
            <span class="status-badge" id="oliStatus">Belum Ganti</span>
        </label>

        <!-- Filter Solar -->
        <label class="filter-checkbox-item filter-solar-item" style="margin-left: 0 !important; margin-right: 0 !important;">
            <input type="checkbox" id="filter_solar" name="filter_solar" value="1">
            <div class="filter-checkbox-left">
                <span class="checkbox-icon"></span>
                <span class="checkbox-label">Filter Solar</span>
            </div>
            <span class="status-badge" id="solarStatus">Belum Ganti</span>
        </label>
    </div>
</div>
<div class="form-group">
    <label for="keterangan">Keterangan</label>
    <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Masukkan keterangan (opsional)"></textarea>
</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn bg-gradient-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Car Modal -->
@foreach($cars as $car)
<div class="modal fade" id="editCarModal{{ $car->id }}" tabindex="-1" role="dialog" aria-labelledby="editCarModalLabel{{ $car->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header justify-content-center">
                <h5 class="modal-title" id="editCarModalLabel{{ $car->id }}">Edit Mobil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="editCarForm" data-car-id="{{ $car->id }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_mobil">Nama Mobil</label>
                        <input type="text" class="form-control" name="nama_mobil" value="{{ $car->nama_mobil }}" required>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_servis">Tanggal Servis</label>
                        <input type="date" class="form-control" name="tanggal_servis" value="{{ $car->tanggal_servis->format('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="kilometer">Kilometer</label>
                        <input type="number" class="form-control" name="kilometer" value="{{ $car->kilometer }}" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" name="status" required>
                            <option value="belum_servis" {{ $car->status == 'belum_servis' ? 'selected' : '' }}>Belum Servis</option>
                            <option value="sudah_servis" {{ $car->status == 'sudah_servis' ? 'selected' : '' }}>Sudah Servis</option>
                        </select>
                    </div>
                  <div class="form-group">
    <label class="form-label fw-bold mb-3">Filter</label>
    <div class="filter-checkbox-wrapper">
        <!-- Filter Oli -->
        <label class="filter-checkbox-item filter-oli-item" style="margin-left: 0 !important; margin-right: 0 !important;">
            <input type="checkbox" id="filter_oli_{{ $car->id }}" name="filter_oli" value="1" {{ $car->filter_oli ? 'checked' : '' }}>
            <div class="filter-checkbox-left">
                <span class="checkbox-icon"></span>
                <span class="checkbox-label">Filter Oli</span>
            </div>
            <span class="status-badge" id="oliStatus">{{ $car->filter_oli ? 'Sudah Ganti' : 'Belum Ganti' }}</span>
        </label>

        <!-- Filter Solar -->
        <label class="filter-checkbox-item filter-solar-item" style="margin-left: 0 !important; margin-right: 0 !important;">
            <input type="checkbox" id="filter_solar_{{ $car->id }}" name="filter_solar" value="1" {{ $car->filter_solar ? 'checked' : '' }}>
            <div class="filter-checkbox-left">
                <span class="checkbox-icon"></span>
                <span class="checkbox-label">Filter Solar</span>
            </div>
            <span class="status-badge" id="solarStatus">{{ $car->filter_solar ? 'Sudah Ganti' : 'Belum Ganti' }}</span>
        </label>
    </div>
</div>
<div class="form-group">
    <label for="keterangan_{{ $car->id }}">Keterangan</label>
    <textarea class="form-control" id="keterangan_{{ $car->id }}" name="keterangan" rows="3" placeholder="Masukkan keterangan (opsional)">{{ $car->keterangan }}</textarea>
</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn bg-gradient-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- Servis Terbaru Modal -->
<div class="modal fade" id="servisModal" tabindex="-1" role="dialog" aria-labelledby="servisModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="servisModalLabel">Servis Terbaru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="servisForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="servis_nama_mobil">Nama Mobil</label>
                        <select class="form-control" id="servis_nama_mobil" name="nama_mobil" required>
                            <option value="">Pilih Mobil</option>
                            @foreach($mobilBelumServis as $namaMobil)
                            <option value="{{ $namaMobil }}">{{ $namaMobil }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="servis_tanggal">Tanggal Servis</label>
                        <input type="date" class="form-control" id="servis_tanggal" name="tanggal_servis" required>
                    </div>
                    <div class="form-group">
                        <label for="servis_kilometer">Kilometer</label>
                        <input type="number" class="form-control" id="servis_kilometer" name="kilometer" min="0" required>
                        <small class="text-muted">Kilometer harus lebih besar dari: <span id="km-minimum">0</span> km</small>
                    </div>
                 <div class="form-group">
    <label class="form-label fw-bold mb-3">Filter</label>
    <div class="filter-checkbox-wrapper">
        <!-- Filter Oli -->
        <label class="filter-checkbox-item filter-oli-item" style="margin-left: 0 !important; margin-right: 0 !important;">
            <input type="checkbox" id="servis_filter_oli" name="filter_oli" value="1">
            <div class="filter-checkbox-left">
                <span class="checkbox-icon"></span>
                <span class="checkbox-label">Filter Oli</span>
            </div>
            <span class="status-badge" id="oliStatus">Belum Ganti</span>
        </label>

        <!-- Filter Solar -->
        <label class="filter-checkbox-item filter-solar-item" style="margin-left: 0 !important; margin-right: 0 !important;">
            <input type="checkbox" id="servis_filter_solar" name="filter_solar" value="1">
            <div class="filter-checkbox-left">
                <span class="checkbox-icon"></span>
                <span class="checkbox-label">Filter Solar</span>
            </div>
            <span class="status-badge" id="solarStatus">Belum Ganti</span>
        </label>
    </div>
</div>
<div class="form-group">
    <label for="servis_keterangan">Keterangan</label>
    <textarea class="form-control" id="servis_keterangan" name="keterangan" rows="3" placeholder="Masukkan keterangan (opsional)"></textarea>
</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn bg-gradient-primary">Update Servis</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
// Add New Car
// document.getElementById('filter_oli').addEventListener('change', function () {
//     const badge = document.getElementById('oliStatus');
//     badge.textContent = this.checked ? 'Sudah Ganti' : 'Belum Ganti';
// });

// document.getElementById('filter_solar').addEventListener('change', function () {
//     const badge = document.getElementById('solarStatus');
//     badge.textContent = this.checked ? 'Sudah Ganti' : 'Belum Ganti';
// });

document.querySelectorAll('.filter-checkbox-item input[type="checkbox"]').forEach(checkbox => {
    const badge = checkbox.closest('.filter-checkbox-item')
                          .querySelector('.status-badge');

    if (!badge) return;

    const updateBadge = () => {
        badge.textContent = checkbox.checked ? 'Sudah Ganti' : 'Belum Ganti';
    };

    // set kondisi awal (penting untuk edit & blade)
    updateBadge();

    // update saat klik
    checkbox.addEventListener('change', updateBadge);
});






const addCarForm = document.getElementById('addCarForm');
if (addCarForm) {
    addCarForm.addEventListener('submit', function(event) {
        event.preventDefault();

        // Ambil data form secara manual
const data = {
    nama_mobil: document.getElementById('nama_mobil').value,
    tanggal_servis: document.getElementById('tanggal_servis').value,
    kilometer: document.getElementById('kilometer').value,
    status: document.getElementById('status').value,
    filter_oli: document.getElementById('filter_oli').checked,
    filter_solar: document.getElementById('filter_solar').checked,
    keterangan: document.getElementById('keterangan').value
};

        console.log('Data yang dikirim:', data); // Debug

        fetch('/api/cars', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            console.log('Response:', result); // Debug
            if (result.success) {
                // alert('Mobil berhasil ditambahkan!');
                location.reload();
            } else {
                alert('Error: ' + result.message);
                if (result.errors) {
                    console.log('Validation errors:', result.errors);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menambah mobil: ' + error.message);
        });
    });
}

        // Edit Car
        document.querySelectorAll('.editCarForm').forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                const carId = this.getAttribute('data-car-id');
                const formData = new FormData(this);
                const data = {};

                // Handle semua field
                for (let [key, value] of formData.entries()) {
                    data[key] = value;
                }

                // Khusus untuk checkbox filter_oli
                const checkboxFilterOli = this.querySelector('input[name="filter_oli"]');
                data.filter_oli = checkboxFilterOli ? checkboxFilterOli.checked : false;

                const checkboxFilterSolar = this.querySelector('input[name="filter_solar"]');
data.filter_solar = checkboxFilterSolar ? checkboxFilterSolar.checked : false;
data.keterangan = this.querySelector('textarea[name="keterangan"]').value;


                fetch(`/api/cars/${carId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        // alert('Data mobil berhasil diperbarui!');
                        location.reload();
                    } else {
                        alert('Error: ' + result.message);
                        console.log(result.errors);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memperbarui data mobil');
                });
            });
        });

        // Servis Terbaru
        const servisForm = document.getElementById('servisForm');
        if (servisForm) {
            servisForm.addEventListener('submit', function(event) {
                event.preventDefault();

                const formData = new FormData(this);
                const data = {};

                // Handle semua field
                for (let [key, value] of formData.entries()) {
                    data[key] = value;
                }

                // Khusus untuk checkbox filter_oli
                data.filter_oli = document.getElementById('servis_filter_oli').checked;
                data.filter_solar = document.getElementById('servis_filter_solar').checked;
data.keterangan = document.getElementById('servis_keterangan').value;

                fetch('/api/cars/servis', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + result.message);
                        console.log(result.errors);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memperbarui servis');
                });
            });
        }

        // Set tanggal hari ini sebagai default
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('tanggal_servis').value = today;
        document.getElementById('servis_tanggal').value = today;
    });

    // Function to open servis modal with pre-filled data
function openServisModal(namaMobil, currentKm) {
    const selectElement = document.getElementById('servis_nama_mobil');
    selectElement.value = namaMobil;

    document.getElementById('km-minimum').textContent = new Intl.NumberFormat('id-ID').format(currentKm);
    document.getElementById('servis_kilometer').min = currentKm + 1;

    // Reset checkbox filter oli dan solar
    document.getElementById('servis_filter_oli').checked = false;
    document.getElementById('servis_filter_solar').checked = false;
    document.getElementById('servis_keterangan').value = '';

    const modal = new bootstrap.Modal(document.getElementById('servisModal'));
    modal.show();
}
</script>






@endsection
