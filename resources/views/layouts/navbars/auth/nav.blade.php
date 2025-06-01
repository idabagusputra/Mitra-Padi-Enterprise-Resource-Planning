<style>
    /* Tambahkan style ini di bagian atas nav.blade.php */

    /* Style untuk backdrop modal */
    .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.3) !important;
        /* Sesuaikan opacity backdrop */
    }

    /* Style untuk modal dialog */
    .modal .modal-dialog {
        max-width: 450px;
    }

    .modal .modal-content {
        border-radius: 0.5rem;
        border: none;
    }

    .modal .modal-header {
        padding: 1rem;
        border-bottom: 1px solid #dee2e6;
    }

    .modal .modal-body {
        position: relative;
        padding: 15px;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0 !important;
    }

    .modal .modal-footer {
        padding: 1rem;
        border-top: 1px solid #dee2e6;
    }

    /* Style untuk PDF viewer */
    .pdf-viewer {
        width: 100%;
        height: 500px;
        border: none;
    }

    @media (max-width: 767.98px) {
    .centered-modal {
        margin-left: auto !important;
        margin-right: auto !important;
    }
}


</style>

<!-- Navbar -->
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
    <div class="container-fluid py-1 px-0">

        <nav aria-label="breadcrumb">
            <ol class="ps-0 breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Mitra-Padi</a></li>
                <li class="breadcrumb-item text-sm text-dark active text-capitalize" aria-current="page">{{ str_replace('-', ' ', Request::path()) }}</li>
            </ol>
            <h6 class="ps-0 font-weight-bolder mb-0 text-capitalize">{{ str_replace('-', ' ', Request::path()) }}</h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4 d-flex justify-content-end" id="navbar">
            <!-- <div class="nav-item d-flex align-self-end">
                <a href="https://www.creative-tim.com/product/soft-ui-dashboard-laravel" target="_blank" class="btn btn-primary active mb-0 text-white" role="button" aria-pressed="true">
                    Download
                </a>
            </div> -->
            <!-- <div class="ms-md-3 pe-md-3 d-flex align-items-center">
                <div class="input-group">
                    <span class="input-group-text text-body"><i class="bi bi-search" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" placeholder="Type here...">
                </div>
            </div> -->
            <ul class="navbar-nav  justify-content-end">
                <!-- <li class="nav-item d-flex align-items-center">
                    <a href="{{ url('/logout')}}" class="nav-link text-body font-weight-bold px-0">
                        <i class="fa fa-user me-sm-1"></i>
                        <span class="d-sm-inline d-none">Sign Out</span>
                    </a>
                </li> -->


                <li class="ps-3 nav-item dropdown d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell cursor-pointer"></i>
                    </a>
                    <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                        <!-- <li class="mb-2">
                            <a class="dropdown-item border-radius-md" href="javascript:;">
                                <div class="d-flex py-1">
                                    <div class="my-auto">
                                        <img src="../assets/img/team-2.jpg" class="avatar avatar-sm  me-3 ">
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            <span class="font-weight-bold">New message</span> from Laur
                                        </h6>
                                        <p class="text-xs text-secondary mb-0">
                                            <i class="fa fa-clock me-1"></i>
                                            13 minutes ago
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="mb-2">
                            <a class="dropdown-item border-radius-md" href="javascript:;">
                                <div class="d-flex py-1">
                                    <div class="my-auto">
                                        <img src="../assets/img/small-logos/logo-spotify.svg" class="avatar avatar-sm bg-gradient-dark  me-3 ">
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            <span class="font-weight-bold">New album</span> by Travis Scott
                                        </h6>
                                        <p class="text-xs text-secondary mb-0">
                                            <i class="fa fa-clock me-1"></i>
                                            1 day
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li> -->

                        @if(isset($latestNotifications) && $latestNotifications->count() > 0)
                        @foreach($latestNotifications as $notification)
                        <li class="mb-2">
                            <a class="dropdown-item border-radius-md view-pdf-btn-nav"
                                href="#"
                                data-id="{{ $notification['id'] }}"
                                data-bs-toggle="modal"
                                data-bs-target="#pdfModal">
                                <div class="d-flex py-1">
                                    <div class="avatar avatar-sm bg-gradient-secondary me-3 my-auto">
                                        <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <div class="icon icon-shape bi bi-gear-fill icon-xxs text-center ms-3 me-3 d-flex align-items-center justify-content-center" style="color: white; font-size: 15px;">
                                        </svg>
                                    </div>




                                </div>
                                <div class="d-flex flex-column justify-content-center">
                                    <h6 class="text-sm font-weight-normal mb-1">
                                        <span class="font-weight-bold">Giling Baru</span> dari {{ $notification['petani_nama'] }}
                                    </h6>
                                    <p class="text-xs text-secondary mb-0">
                                        <i class="fa fa-clock me-1"></i>
                                        {{ $notification['created_at']->diffForHumans() }}
                                    </p>
                                </div>
        </div>
        </a>
        </li>
        @endforeach
        @else
        <li class="mb-2">
            <div class="dropdown-item border-radius-md">
                <div class="d-flex py-1">
                    <div class="d-flex flex-column justify-content-center">
                        <h6 class="text-sm font-weight-normal mb-1">
                            Tidak ada data giling terbaru
                        </h6>
                    </div>
                </div>
            </div>
        </li>
        @endif
        <!-- <li>
                            <a class="dropdown-item border-radius-md" href="javascript:;">
                                <div class="d-flex py-1">
                                    <div class="avatar avatar-sm bg-gradient-secondary  me-3  my-auto">
                                        <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <title>credit-card</title>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                                    <g transform="translate(1716.000000, 291.000000)">
                                                        <g transform="translate(453.000000, 454.000000)">
                                                            <path class="color-background" d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z" opacity="0.593633743"></path>
                                                            <path class="color-background" d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z"></path>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            Payment successfully completed
                                        </h6>
                                        <p class="text-xs text-secondary mb-0">
                                            <i class="fa fa-clock me-1"></i>
                                            2 days
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li> -->
        </ul>
        </li>

        <li class="pe-0 nav-item ps-4 d-xl-none d-flex align-items-center">
            <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                </div>
            </a>
        </li>

        <!-- <li class="nav-item px-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0">
                        <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
                    </a>
                </li> -->
        </ul>
    </div>
    </div>
</nav>

<!-- Modal PDF -->
<!-- Modal PDF -->
<div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered centered-modal"> <!-- Tambahkan modal-dialog-centered di sini -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pdfModalLabel">Receipt #</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe id="pdfViewer" class="pdf-viewer" frameborder="0"></iframe>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <div>
                    <button id="printPdf" class="btn btn-primary me-2">
                        <i class="bi bi-printer-fill me-1"></i> print
                    </button>
                    <button id="sharePdf" class="btn btn-info me-2">
                        <i class="bi bi-floppy-fill me-1"></i> Save
                    </button>
                    {{-- <button id="whatsappSharePdf" class="btn btn-success me-2">
                        <i class="bi bi-whatsapp me-1"></i> WhatsApp
                    </button> --}}
                </div>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-square-fill me-1"></i> close
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Tambahkan Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    // Initialize PDF.js worker
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    document.addEventListener('DOMContentLoaded', function() {
        // Event listener untuk notifikasi
        const notificationLinks = document.querySelectorAll('.view-pdf-btn-nav');

        notificationLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const gilingId = this.getAttribute('data-id');
                console.log('Opening PDF for Giling ID:', gilingId);

                if (!gilingId) {
                    console.error('No giling ID found');
                    return;
                }

                const pdfPath = `/receipts/receipt-${gilingId}.pdf`;

                // Set src viewer PDF
                const pdfViewer = document.getElementById('pdfViewer');
                pdfViewer.src = pdfPath;

                // Update modal title
                document.getElementById('pdfModalLabel').textContent = `Receipt #${gilingId}`;

                // Tampilkan modal dengan opsi backdrop yang dimodifikasi
                const pdfModal = new bootstrap.Modal(document.getElementById('pdfModal'), {
                    backdrop: 'static',
                    keyboard: false
                });
                pdfModal.show();

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

        // Function to convert PDF to JPG without using canvas element
        async function convertPdfToJpg(pdfUrl) {
            try {
                // Load PDF
                const loadingTask = pdfjsLib.getDocument(pdfUrl);
                const pdf = await loadingTask.promise;

                // Get first page
                const page = await pdf.getPage(1);

                // Set scale for better resolution
                const scale = 4;
                const viewport = page.getViewport({ scale });

                // Create an off-screen canvas
                const canvas = document.createElement("canvas");
                const context = canvas.getContext("2d");
                canvas.width = viewport.width;
                canvas.height = viewport.height;

                // Render PDF page to the canvas
                const renderContext = {
                    canvasContext: context,
                    viewport: viewport
                };
                await page.render(renderContext).promise;

                // Convert canvas to Blob (JPG)
                return new Promise((resolve) => {
                    canvas.toBlob((blob) => {
                        resolve(blob);
                    }, "image/jpeg", 0.95);
                });

            } catch (error) {
                console.error("Error converting PDF to JPG:", error);
                throw error;
            }
        }

        // Function to download the file
        function downloadBlob(blob, fileName) {
            const url = URL.createObjectURL(blob);
            const link = document.createElement("a");
            link.href = url;
            link.download = fileName;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
        }

        // Add this inside the existing DOMContentLoaded event listener

        // // Event listener untuk tombol WhatsApp Share
        // const whatsappShareButton = document.getElementById("whatsappSharePdf");

        // if (whatsappShareButton) {
        //     whatsappShareButton.addEventListener("click", async function () {
        //         try {
        //             // Show loading state
        //             whatsappShareButton.disabled = true;
        //             whatsappShareButton.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Preparing...';

        //             // Ambil nomor kuitansi dari modal title
        //             const receiptNumber = document.getElementById("pdfModalLabel").textContent.split("#")[1];
        //             const fileName = `receipt-${receiptNumber}.jpg`;

        //             // URL gambar di server
        //             const imageUrl = `${window.location.origin}/receipts_jpg/${fileName}`;

        //             // Fetch gambar sebagai blob
        //             const response = await fetch(imageUrl);
        //             const imageBlob = await response.blob();
        //             const imageFile = new File([imageBlob], fileName, { type: "image/jpeg" });

        //             // Cek apakah Web Share API didukung
        //             if (navigator.canShare && navigator.canShare({ files: [imageFile] })) {
        //                 await navigator.share({
        //                     title: `Receipt #${receiptNumber}`,
        //                     files: [imageFile]
        //                 });
        //             } else {
        //                 alert("Web Share API tidak didukung di perangkat ini.");
        //             }
        //         } catch (error) {
        //             console.error("Error in WhatsApp share process:", error);
        //             alert("Failed to prepare receipt for sharing. Please try again.");
        //         } finally {
        //             // Reset button state
        //             whatsappShareButton.disabled = false;
        //             whatsappShareButton.innerHTML = '<i class="bi bi-whatsapp me-1"></i> WhatsApp';
        //         }
        //     });
        // }



        // // Fallback WhatsApp sharing method
        // function fallbackWhatsAppShare(file, receiptNumber) {
        //     // Create object URL for the file
        //     const fileUrl = URL.createObjectURL(file);

        //     // Construct WhatsApp share URL
        //     // Note: This method works on mobile devices
        //     const whatsappUrl = `https://wa.me/?text=Receipt%20%23${receiptNumber}&file=${encodeURIComponent(fileUrl)}`;

        //     // Open WhatsApp
        //     window.open(whatsappUrl, '_blank');
        // }

        // Event listener untuk tombol Share
        const shareButton = document.getElementById("sharePdf");
        if (shareButton) {
            shareButton.addEventListener("click", async function () {
                try {
                    // Show loading state
                    shareButton.disabled = true;
                    shareButton.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Converting...';

                    const pdfViewer = document.getElementById("pdfViewer");
                    const pdfUrl = pdfViewer.src;

                    // Convert PDF to JPG
                    const jpgBlob = await convertPdfToJpg(pdfUrl);

                    // Get receipt number from modal title
                    const receiptNumber = document.getElementById("pdfModalLabel").textContent.split("#")[1];
                    const fileName = `receipt-${receiptNumber}.jpg`;

                    // Download the JPG
                    downloadBlob(jpgBlob, fileName);

                } catch (error) {
                    console.error("Error in share process:", error);
                    alert("Failed to convert PDF to JPG. Please try again.");
                } finally {
                    // Reset button state
                    shareButton.disabled = false;
                    shareButton.innerHTML = '<i class="bi bi-floppy-fill me-1"></i> SAVED';
                }
            });
        }


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

        // Get the sidenav, icon, and backdrop elements
        const sidenav = document.getElementById('sidenav-main');
        const iconSidenav = document.getElementById('iconSidenav');
        const sidenavCollapse = document.getElementById('sidenav-collapse-main');
        const backdrop = document.getElementById('backdrop');

        // Variable to track whether the sidenav is open
        let isOpen = false;

        // Function to toggle the sidenav (open and close)
        function toggleSidenav() {
            if (sidenavCollapse.classList.contains('show')) {
                // Close the sidenav
                sidenavCollapse.classList.remove('show');
                backdrop.classList.add('d-none'); // Hide the backdrop
                isOpen = false;
            } else {
                // Open the sidenav
                sidenavCollapse.classList.add('show');
                backdrop.classList.remove('d-none'); // Show the backdrop
                isOpen = true;
            }
        }

        // Function to close the sidenav and simulate a click on the icon
        function closeSidenav() {
            if (isOpen) {
                // Automatically toggle the sidenav by simulating a click on the icon
                iconSidenav.click();
                isOpen = false;
            }
        }

        // Event listener for the close icon
        iconSidenav.addEventListener('click', toggleSidenav);

        // Event listener for clicking anywhere outside the sidenav (when it's open)
        document.addEventListener('click', function(event) {
            // Only close the sidenav if it's open and the click is outside of it
            if (isOpen && !sidenav.contains(event.target) && !iconSidenav.contains(event.target)) {
                closeSidenav();
            }
        });

        // Prevent click events inside the sidenav from propagating (so the sidebar doesn't close when clicking inside)
        sidenav.addEventListener('click', function(event) {
            event.stopPropagation();
        });
    });
</script>
<!-- End Navbar -->
