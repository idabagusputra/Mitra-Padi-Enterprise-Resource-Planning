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




                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">ID</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Action</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Rekapan Dana</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Tanggal Pembuatan</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rekapDanas as $rekapDana)
                                <tr>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0 ">{{ $rekapDana->id }}</p>
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="btn btn-link text-dark mb-0 view-pdf-btn-daftarRekapanDana" data-id="{{ $rekapDana->id }}">
                                            <i class="bi bi-eye text-dark me-2" aria-hidden="true"></i>
                                            View
                                        </a>
                                        <!-- <form action="{{ route('daftar-giling.destroy', $rekapDana->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger text-gradient px-2 mb-0" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                <i class="bi bi-trash3 me-2"></i>
                                                Delete
                                            </button>
                                        </form> -->
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0 ">Rp. {{ number_format($rekapDana->rekapan_dana, 2, ',', '.') }}</p>
                                    </td>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0 text-center">
                                            Tanggal: {{ $rekapDana->created_at->format('d-m-Y') }} | Waktu: {{ $rekapDana->created_at->format('H:i:s') }}
                                        </p>
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
                        <strong>{{ $rekapDanas->firstItem() }}</strong> to
                        <strong>{{ $rekapDanas->lastItem() }}</strong> of
                        <strong>{{ $rekapDanas->total() }}</strong> entries
                    </div>
                    <div>
                        @if ($rekapDanas->lastPage() > 1)
                        <nav>
                            <ul class="pagination m-0">
                                {{-- Previous Button --}}
                                @if ($rekapDanas->currentPage() > 1)
                                <li class="page-item">
                                    <a class="page-link" href="{{ $rekapDanas->previousPageUrl() }}" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                @endif

                                @php
                                $currentPage = $rekapDanas->currentPage();
                                $lastPage = $rekapDanas->lastPage();
                                @endphp

                                {{-- Always show first page --}}
                                <li class="page-item {{ $currentPage == 1 ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $rekapDanas->url(1) }}">1</a>
                                </li>

                                {{-- Middle pages logic --}}
                                @php
                                $start = max(2, $currentPage - 1);
                                $end = min($lastPage - 1, $currentPage + 1);
                                @endphp

                                @for ($i = $start; $i <= $end; $i++)
                                    <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $rekapDanas->url($i) }}">{{ $i }}</a>
                                    </li>
                                    @endfor

                                    {{-- Always show last page --}}
                                    @if ($lastPage > 1)
                                    <li class="page-item {{ $currentPage == $lastPage ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $rekapDanas->url($lastPage) }}">{{ $lastPage }}</a>
                                    </li>
                                    @endif

                                    {{-- Next Button --}}
                                    @if ($currentPage < $lastPage)
                                        <li class="page-item">
                                        <a class="page-link" href="{{ $rekapDanas->nextPageUrl() }}" aria-label="Next">
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

<!-- Modal PDF -->
<div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pdfModalLabel">Receipt #</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe id="pdfViewer" class="pdf-viewer" frameborder="0"></iframe>
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

        // Event listener untuk notifikasi
        const notificationLinks = document.querySelectorAll('.view-pdf-btn-daftarRekapanDana');
        notificationLinks.forEach(function(link) {
            link.addEventListener('click', async function(e) {
                e.preventDefault();
                e.stopPropagation();

                const gilingId = this.getAttribute('data-id');
                console.log('Opening PDF for Giling ID:', gilingId);

                if (!gilingId) {
                    console.error('No giling ID found');
                    return;
                }


                // Folder path di direktori public
                const folderPath = '/rekapan_dana';

                try {
                    // Cari file dengan fetch API atau AJAX
                    const response = await fetch(`/find-pdf-dana?gilingId=${gilingId}`);
                    const data = await response.json();

                    if (data.pdfPath) {

                        // Ekstrak nama file dari path
                        const fileName = data.pdfPath.split('/').pop();
                        const cleanFileName = fileName.substring(0, fileName.lastIndexOf('_'));

                        // Ekstrak ID dan tanggal
                        const parts = cleanFileName.split('_');
                        const id = parts[parts.length - 2];
                        const date = parts[parts.length - 1];

                        // Set src viewer PDF
                        const pdfViewer = document.getElementById('pdfViewer');
                        pdfViewer.src = data.pdfPath;

                        // Update modal title dengan format baru
                        document.getElementById('pdfModalLabel').textContent = `Rekapan Dana ${id} (${date})`;

                        // Tampilkan modal dengan opsi backdrop yang dimodifikasi
                        const pdfModal = new bootstrap.Modal(document.getElementById('pdfModal'), {
                            backdrop: 'static',
                            keyboard: false,
                        });
                        pdfModal.show();
                    } else {
                        console.error('No PDF file found');
                        // Tambahkan notifikasi error untuk pengguna
                    }
                } catch (error) {
                    console.error('Error finding PDF:', error);
                }

                // Close dropdown menu after clicking
                const dropdownMenu = this.closest('.dropdown-menu');
                if (dropdownMenu) {
                    const dropdownToggle = document.querySelector('[data-bs-toggle="dropdown"]');
                    if (dropdownToggle) {
                        const dropdown = bootstrap.Dropdown.getInstance(dropdownToggle);
                        if (dropdown) dropdown.hide();
                    }
                }
            });
        });




        // Event listener untuk tombol Close dan Print
        const modal = document.getElementById('pdfModal');
        if (modal) {
            // Event saat modal ditutup
            modal.addEventListener('hidden.bs.modal', function() {
                // Reset backdrop opacity
                document.body.classList.remove('modal-open');
                const backdrops = document.getElementsByClassName('modal-backdrop');
                while (backdrops.length > 0) {
                    backdrops[0].parentNode.removeChild(backdrops[0]);
                }
            });

            // Event listener untuk tombol Print
            const printButton = document.getElementById('printPdf');
            if (printButton) {
                printButton.addEventListener('click', function() {
                    const pdfViewer = document.getElementById('pdfViewer');
                    if (pdfViewer && pdfViewer.contentWindow) {
                        pdfViewer.contentWindow.print();
                    }
                });
            }
        }

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


    });
</script>





@endsection