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
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Rekapan Kredit</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Tanggal Pembuatan</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rekapanKredits as $rekapanKredit)
                                <tr>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0 ">{{ $rekapanKredit->id }}</p>
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="btn btn-link text-dark mb-0 view-pdf-btn-daftarRekapanDana" data-id="{{ $rekapanKredit->id }}">
                                            <i class="bi bi-eye text-dark me-2" aria-hidden="true"></i>
                                            View
                                        </a>
                                        <!-- <form action="{{ route('daftar-giling.destroy', $rekapanKredit->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger text-gradient px-2 mb-0" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                <i class="bi bi-trash3 me-2"></i>
                                                Delete
                                            </button>
                                        </form> -->
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0 ">Rp. {{ number_format($rekapanKredit->rekapan_kredit, 2, ',', '.') }}</p>
                                    </td>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0 text-center">
                                            Tanggal: {{ $rekapanKredit->created_at->format('d-m-Y') }} | Waktu: {{ $rekapanKredit->created_at->format('H:i:s') }}
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
                        <strong>{{ $rekapanKredits->firstItem() }}</strong> to
                        <strong>{{ $rekapanKredits->lastItem() }}</strong> of
                        <strong>{{ $rekapanKredits->total() }}</strong> entries
                    </div>
                    <div>
                        @if ($rekapanKredits->lastPage() > 1)
                        <nav>
                            <ul class="pagination m-0">
                                {{-- Previous Button --}}
                                @if ($rekapanKredits->currentPage() > 1)
                                <li class="page-item">
                                    <a class="page-link" href="{{ $rekapanKredits->previousPageUrl() }}" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                @endif

                                @php
                                $currentPage = $rekapanKredits->currentPage();
                                $lastPage = $rekapanKredits->lastPage();
                                @endphp

                                {{-- Always show first page --}}
                                <li class="page-item {{ $currentPage == 1 ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $rekapanKredits->url(1) }}">1</a>
                                </li>

                                {{-- Middle pages logic --}}
                                @php
                                $start = max(2, $currentPage - 1);
                                $end = min($lastPage - 1, $currentPage + 1);
                                @endphp

                                @for ($i = $start; $i <= $end; $i++)
                                    <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $rekapanKredits->url($i) }}">{{ $i }}</a>
                                    </li>
                                    @endfor

                                    {{-- Always show last page --}}
                                    @if ($lastPage > 1)
                                    <li class="page-item {{ $currentPage == $lastPage ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $rekapanKredits->url($lastPage) }}">{{ $lastPage }}</a>
                                    </li>
                                    @endif

                                    {{-- Next Button --}}
                                    @if ($currentPage < $lastPage)
                                        <li class="page-item">
                                        <a class="page-link" href="{{ $rekapanKredits->nextPageUrl() }}" aria-label="Next">
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
                <iframe id="pdfViewer" style="display: none;" src="URL_PDF_ANDA"></iframe>
                <button id="printPdf" class="btn btn-primary">Print</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.14.305/pdf.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/printjs/1.6.0/print.min.js"></script>
<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
<link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css">
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const notificationLinks = document.querySelectorAll('.view-pdf-btn-daftarRekapanDana');

        notificationLinks.forEach(function(link) {
            link.addEventListener('click', async function(e) {
                e.preventDefault();
                e.stopPropagation();

                const gilingId = this.getAttribute('data-id');

                if (!gilingId) {
                    console.error('No giling ID found');
                    return;
                }

                try {
                    // Fetch URL from backend
                    const response = await fetch(`/find-pdf-kredit?gilingId=${gilingId}`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        credentials: 'include',
                    });

                    if (!response.ok) {
                        console.error('Error fetching PDF:', response.statusText);
                        alert('File PDF tidak ditemukan.');
                        return;
                    }

                    const data = await response.json(); // Ambil JSON respons

                    if (data.pdfPath) { // Pastikan ada URL PDF yang valid
                        const pdfPath = data.pdfPath;

                        // Tampilkan PDF di modal
                        const pdfModal = new bootstrap.Modal(document.getElementById('pdfModal'), {
                            backdrop: 'static',
                            keyboard: false,
                        });

                        const pdfViewer = document.getElementById('pdfViewer');
                        pdfViewer.src = pdfPath; // Set URL PDF ke iframe

                        // Update modal title
                        const modalLabel = document.getElementById('pdfModalLabel');
                        modalLabel.textContent = `Rekapan Dana #${gilingId}`;

                        // Show the modal
                        pdfModal.show();

                        // Handle modal events to fix mobile scrolling
                        const modalElement = document.getElementById('pdfModal');
                        modalElement.addEventListener('shown.bs.modal', function() {
                            document.body.style.overflow = 'hidden';
                        });

                        modalElement.addEventListener('hidden.bs.modal', function() {
                            document.body.style.overflow = 'auto';
                            document.body.style.position = 'relative';
                            // Reset any inline styles that might affect scrolling
                            window.scrollTo(0, window.scrollY);
                        });

                    } else {
                        console.error('No valid PDF path found.');
                        alert('File PDF tidak ditemukan.');
                    }

                } catch (error) {
                    console.error('Error fetching PDF:', error);
                    alert('Terjadi kesalahan saat mencari file PDF.');
                }

                // Tutup dropdown setelah klik
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

        document.getElementById('printPdf').addEventListener('click', async function() {
            const pdfViewer = document.getElementById('pdfViewer');

            if (pdfViewer) {
                try {
                    // Ambil URL PDF dari src iframe
                    const pdfUrl = pdfViewer.src;

                    // Ambil PDF sebagai Blob
                    const response = await fetch(pdfUrl, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/pdf',
                        },
                    });

                    if (!response.ok) {
                        throw new Error('Gagal mengambil file PDF.');
                    }

                    const pdfBlob = await response.blob();

                    // Buat Object URL dari Blob
                    const blobUrl = URL.createObjectURL(pdfBlob);

                    // Buat iframe tersembunyi untuk mencetak
                    const hiddenIframe = document.createElement('iframe');
                    hiddenIframe.style.display = 'none';
                    hiddenIframe.src = blobUrl;

                    // Tunggu iframe selesai dimuat
                    hiddenIframe.onload = () => {
                        try {
                            hiddenIframe.contentWindow.focus(); // Fokus pada iframe
                            hiddenIframe.contentWindow.print(); // Panggil dialog cetak

                            // // Hapus Object URL dan iframe setelah selesai
                            // setTimeout(() => {
                            //     URL.revokeObjectURL(blobUrl); // Hapus Object URL
                            //     document.body.removeChild(hiddenIframe);
                            // }, 5000);
                        } catch (printError) {
                            console.error('Gagal mencetak dokumen:', printError);
                            alert('Terjadi kesalahan saat mencetak dokumen.');
                        }
                    };

                    document.body.appendChild(hiddenIframe);

                } catch (error) {
                    console.error('Gagal mencetak dokumen:', error);
                    alert('Terjadi kesalahan saat mencetak dokumen.');
                }
            } else {
                alert('Viewer PDF tidak ditemukan.');
            }
        });













        // document.getElementById('printPdf').addEventListener('click', function() {
        //     const pdfViewer = document.getElementById('pdfViewer');
        //     if (pdfViewer) {
        //         const pdfUrl = pdfViewer.src;

        //         fetch(pdfUrl)
        //             .then(response => response.blob())
        //             .then(blob => {
        //                 const blobUrl = URL.createObjectURL(blob);

        //                 // Deteksi apakah perangkat adalah iOS atau Android
        //                 const isIOS = /iPhone|iPad|iPod/i.test(navigator.userAgent);
        //                 const isAndroid = /Android/i.test(navigator.userAgent);

        //                 if (isIOS || isAndroid) {
        //                     // Untuk iOS dan Android, buka file di tab baru
        //                     const newTab = window.open(blobUrl, '_blank');
        //                     if (!newTab) {
        //                         alert('Harap izinkan popup untuk mencetak PDF.');
        //                     }
        //                 } else {
        //                     // Untuk browser desktop
        //                     const hiddenIframe = document.createElement('iframe');
        //                     hiddenIframe.style.display = 'none';
        //                     hiddenIframe.src = blobUrl;

        //                     hiddenIframe.onload = () => {
        //                         hiddenIframe.contentWindow.print();
        //                     };

        //                     document.body.appendChild(hiddenIframe);

        //                     // Bersihkan iframe setelah selesai
        //                     setTimeout(() => {
        //                         document.body.removeChild(hiddenIframe);
        //                         URL.revokeObjectURL(blobUrl);
        //                     }, 5000);
        //                 }
        //             })
        //             .catch(error => {
        //                 console.error('Gagal memproses PDF:', error);
        //                 alert('Terjadi kesalahan saat memproses PDF.');
        //             });
        //     } else {
        //         alert('PDF viewer tidak ditemukan.');
        //     }
        // });





        //ini conlose lengkap
        // document.getElementById('printPdf').addEventListener('click', function() {
        //     const pdfViewer = document.getElementById('pdfViewer');
        //     if (pdfViewer) {
        //         console.log('PDF Source:', pdfViewer.src); // Log URL PDF

        //         // Tambahkan header untuk debug
        //         fetch(pdfViewer.src, {
        //                 method: 'HEAD', // Cek header response
        //                 mode: 'cors', // Aktifkan CORS
        //                 headers: {
        //                     'Accept': 'application/pdf'
        //                 }
        //             })
        //             .then(response => {
        //                 console.log('Fetch Response:', response);

        //                 // Periksa status dan header
        //                 console.log('Status:', response.status);
        //                 console.log('Headers:', Object.fromEntries(response.headers));
        //             })
        //             .catch(error => {
        //                 console.error('Fetch Error:', error);
        //             });

        //         try {
        //             const tempIframe = document.createElement('iframe');
        //             tempIframe.style.position = 'fixed';
        //             tempIframe.style.top = '-9999px';
        //             tempIframe.style.width = '1px';
        //             tempIframe.style.height = '1px';

        //             tempIframe.src = pdfViewer.src;

        //             // Log event iframe
        //             tempIframe.onload = () => {
        //                 console.log('Iframe loaded successfully');

        //                 try {
        //                     const iframeWindow = tempIframe.contentWindow;
        //                     console.log('Iframe Window:', iframeWindow);

        //                     // Tambahkan pengecekan eksplisit
        //                     if (iframeWindow && typeof iframeWindow.print === 'function') {
        //                         iframeWindow.focus();
        //                         iframeWindow.print();
        //                     } else {
        //                         console.error('Print function not available');
        //                         alert('Browser tidak mendukung pencetakan PDF langsung.');
        //                     }

        //                     // Hapus iframe
        //                     setTimeout(() => {
        //                         document.body.removeChild(tempIframe);
        //                     }, 1000);
        //                 } catch (printError) {
        //                     console.error('Print Error Details:', {
        //                         name: printError.name,
        //                         message: printError.message,
        //                         stack: printError.stack
        //                     });
        //                     alert('Gagal mencetak. Lihat konsol untuk detail.');
        //                 }
        //             };

        //             // Event error iframe
        //             tempIframe.onerror = (error) => {
        //                 console.error('Iframe Load Error:', error);
        //                 alert('Gagal memuat PDF di iframe.');
        //             };

        //             document.body.appendChild(tempIframe);
        //         } catch (error) {
        //             console.error('Proses Pencetakan Error:', {
        //                 name: error.name,
        //                 message: error.message,
        //                 stack: error.stack
        //             });
        //             alert('Terjadi kesalahan saat memproses PDF.');
        //         }
        //     } else {
        //         alert('PDF viewer tidak ditemukan.');
        //     }
        // });

        // document.getElementById('printPdf').addEventListener('click', function() {
        //     const pdfViewer = document.getElementById('pdfViewer');
        //     if (pdfViewer) {
        //         console.log('PDF Source:', pdfViewer.src); // Log URL PDF

        //         // Tambahkan header untuk debug
        //         fetch(pdfViewer.src, {
        //                 method: 'HEAD', // Cek header response
        //                 mode: 'cors', // Aktifkan CORS
        //                 headers: {
        //                     'Accept': 'application/pdf'
        //                 }
        //             })
        //             .then(response => {
        //                 console.log('Fetch Response:', response);

        //                 // Periksa status dan header
        //                 console.log('Status:', response.status);
        //                 console.log('Headers:', Object.fromEntries(response.headers));
        //             })
        //             .catch(error => {
        //                 console.error('Fetch Error:', error);
        //             });

        //         try {
        //             const tempIframe = document.createElement('iframe');
        //             tempIframe.style.position = 'fixed';
        //             tempIframe.style.top = '-9999px';
        //             tempIframe.style.width = '1px';
        //             tempIframe.style.height = '1px';

        //             tempIframe.src = pdfViewer.src;

        //             // Log event iframe
        //             tempIframe.onload = () => {
        //                 console.log('Iframe loaded successfully');

        //                 try {
        //                     const iframeWindow = tempIframe.contentWindow;
        //                     console.log('Iframe Window:', iframeWindow);

        //                     // Tambahkan pengecekan eksplisit
        //                     if (iframeWindow && typeof iframeWindow.print === 'function') {
        //                         iframeWindow.focus();
        //                         iframeWindow.print();
        //                     } else {
        //                         console.error('Print function not available');
        //                         alert('Browser tidak mendukung pencetakan PDF langsung.');
        //                     }

        //                     // Hapus iframe
        //                     setTimeout(() => {
        //                         document.body.removeChild(tempIframe);
        //                     }, 1000);
        //                 } catch (printError) {
        //                     console.error('Print Error Details:', {
        //                         name: printError.name,
        //                         message: printError.message,
        //                         stack: printError.stack
        //                     });
        //                     alert('Gagal mencetak. Lihat konsol untuk detail.');
        //                 }
        //             };

        //             // Event error iframe
        //             tempIframe.onerror = (error) => {
        //                 console.error('Iframe Load Error:', error);
        //                 alert('Gagal memuat PDF di iframe.');
        //             };

        //             document.body.appendChild(tempIframe);
        //         } catch (error) {
        //             console.error('Proses Pencetakan Error:', {
        //                 name: error.name,
        //                 message: error.message,
        //                 stack: error.stack
        //             });
        //             alert('Terjadi kesalahan saat memproses PDF.');
        //         }
        //     } else {
        //         alert('PDF viewer tidak ditemukan.');
        //     }
        // });

        // document.getElementById('printPdf').addEventListener('click', function() {
        //     const pdfViewer = document.getElementById('pdfViewer');
        //     if (pdfViewer) {
        //         const iframeWindow = pdfViewer.contentWindow;
        //         if (iframeWindow) {
        //             try {
        //                 iframeWindow.print();
        //             } catch (error) {
        //                 console.error('Gagal mencetak PDF:', error);
        //                 alert('Browser tidak mengizinkan pencetakan langsung dari iframe.');
        //             }
        //         } else {
        //             alert('Tidak dapat mengakses contentWindow dari PDF viewer.');
        //         }
        //     } else {
        //         alert('PDF viewer tidak ditemukan.');
        //     }
        // });

        //INI API

        // document.getElementById('printPdf').addEventListener('click', function() {
        //     const pdfViewer = document.getElementById('pdfViewer');
        //     if (pdfViewer) {
        //         try {
        //             // Ambil URL PDF dari iframe
        //             const pdfUrl = pdfViewer.src;

        //             // Buat elemen iframe tersembunyi
        //             const hiddenIframe = document.createElement('iframe');
        //             hiddenIframe.src = pdfUrl;
        //             hiddenIframe.style.display = 'none';

        //             // Tambahkan ke body
        //             document.body.appendChild(hiddenIframe);

        //             // Tunggu iframe dimuat
        //             hiddenIframe.onload = () => {
        //                 // Coba gunakan print API
        //                 hiddenIframe.contentWindow.print();

        //                 // Hapus iframe setelah selesai
        //                 setTimeout(() => {
        //                     document.body.removeChild(hiddenIframe);
        //                 }, 500);
        //             };
        //         } catch (error) {
        //             console.error('Gagal memproses PDF:', error);
        //             alert('Terjadi kesalahan saat memproses PDF.');
        //         }
        //     } else {
        //         alert('PDF viewer tidak ditemukan.');
        //     }
        // });







        // // Event listener untuk tombol Close dan Print
        // const modal = document.getElementById('pdfModal');
        // if (modal) {
        //     // Event saat modal ditutup
        //     modal.addEventListener('hidden.bs.modal', function() {
        //         // Reset backdrop opacity
        //         document.body.classList.remove('modal-open');
        //         const backdrops = document.getElementsByClassName('modal-backdrop');
        //         while (backdrops.length > 0) {
        //             backdrops[0].parentNode.removeChild(backdrops[0]);
        //         }
        //     });

        //     // Event listener untuk tombol Print
        //     const printButton = document.getElementById('printPdf');
        //     if (printButton) {
        //         printButton.addEventListener('click', function() {
        //             const pdfViewer = document.getElementById('pdfViewer');
        //             if (pdfViewer && pdfViewer.contentWindow) {
        //                 pdfViewer.contentWindow.print();
        //             }
        //         });
        //     }
        // }

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