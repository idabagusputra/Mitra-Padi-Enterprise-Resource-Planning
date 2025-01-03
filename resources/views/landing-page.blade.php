<!DOCTYPE html>
<html lang="en">

<style>
    @media (max-width: 768px) {
        .hide-on-mobile {
            display: none;
        }


        body {
            padding-top: 3;

        }

        #about,
        #features,
        #features-2,
        #features-cards,
        #pricing,
        #stats,
        #faq,
        #footer,
        #call-to-action {
            padding-top: 0;
            /* Mengatur padding atas menjadi 0 */
            padding-left: 2rem;
            /* Padding kiri */
            padding-right: 2rem;
            /* Padding kanan */
        }

        #hero {
            padding-left: 2rem;
            /* Padding kiri */
            padding-right: 2rem;
        }

        .hero-content {
            margin-bottom: 0rem !important;

        }

        .play {
            justify-content: center;
        }

        #features {
            padding-top: 0;
            /* Mengatur padding atas menjadi 0 */
            padding-left: 2rem;
            /* Padding kiri */
            padding-right: 2rem;
            /* Padding kanan */
        }

        .heroo {
            margin-top: 6rem;
        }

        .mobile-gearfont {
            font-size: 13px;
        }
    }

    @media (max-width: 1024px) {
        .hide-on-mobile {
            display: none;
        }

        .hero-content {
            margin-bottom: 0rem !important;

        }

        .play {
            justify-content: center;
        }

        #hero,
        #about,
        #features,
        #features-2,
        #features-cards,
        #pricing,
        #stats,
        #faq,
        #call-to-action {
            padding-top: 0;
            /* Mengatur padding atas menjadi 0 */

        }

        #features {
            padding-top: 0;
            /* Mengatur padding atas menjadi 0 */
        }

        .heroo {
            margin-top: 6rem;
        }

    }

    @media (min-width: 1025px) {
        .hide-on-web {
            display: none !important;
        }
    }
</style>

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Mitra Padi</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon-landing.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet">

    <!-- =======================================================
  * Template Name: iLanding
  * Template URL: https://bootstrapmade.com/ilanding-bootstrap-landing-page-template/
  * Updated: Nov 12 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">

    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

            <a href="#" class="logo d-flex align-items-center me-auto me-xl-0">
                <!-- Uncomment the line below if you also wish to use an image logo -->
                <!-- <img src="assets/img/logo.png" alt=""> -->
                <h1 class="sitename">Mitra Padi</h1>
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="#hero" class="active">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#pricing">Pricing</a></li>
                    <!-- <li class="dropdown"><a href="#"><span>Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                        <ul>
                            <li><a href="#">Dropdown 1</a></li>
                            <li class="dropdown"><a href="#"><span>Deep Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                                <ul>
                                    <li><a href="#">Deep Dropdown 1</a></li>
                                    <li><a href="#">Deep Dropdown 2</a></li>
                                    <li><a href="#">Deep Dropdown 3</a></li>
                                    <li><a href="#">Deep Dropdown 4</a></li>
                                    <li><a href="#">Deep Dropdown 5</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Dropdown 2</a></li>
                            <li><a href="#">Dropdown 3</a></li>
                            <li><a href="#">Dropdown 4</a></li>
                        </ul>
                    </li> -->
                    <li><a href="#contact">Contact</a></li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

            <a class="btn-getstarted" href="/login">Login</a>

        </div>
    </header>

    <main class="main">

        <!-- Hero Section -->
        <section id="hero" class="hero section">

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row align-items-center heroo">
                    <div class="col-lg-6">
                        <div class="hero-content" data-aos="fade-up" data-aos-delay="200">
                            <div class="company-badge mb-4 heroo mobile-gearfont">
                                <i class="bi bi-gear-fill me-2"></i>
                                Mengubah Padi Menjadi Beras Berkualitas
                            </div>

                            <h1 class="mb-4">
                                Mitra Padi <br>

                                <span class="accent-text">UD Penggilingan Padi Putra Manuaba</span>
                            </h1>

                            <p class="mb-4 md-5">
                                Pabrik pengolahan padi di Desa Tolai yang mengubah gabah menjadi beras melalui proses pembersihan, pengeringan, penggilingan, dan pengemasan, mendukung distribusi hasil panen petani lokal.
                            </p>

                            <div class="d-flex align-items-center play" style="gap: 30px;">
                                <div class="hero-buttons">
                                    <!-- <a href="/login" class="btn btn-primary me-0 me-sm-2 mx-1">Login</a> -->
                                    <a href="https://youtu.be/OPNMwT99CHY?feature=shared" class=" btn-link  mt-sm-0 glightbox">
                                        <i class="bi bi-play-circle me-1"></i>
                                        Play Video
                                    </a>
                                </div>

                                <div class="hero-buttons">
                                    <!-- <a href="/login" class="btn btn-primary me-0 me-sm-2 mx-1">Login</a> -->
                                    <a href="https://youtu.be/P0U9eqLbkmE?feature=shared" class=" btn-link  mt-sm-0 glightbox">
                                        <i class="bi bi-play-circle me-1"></i>
                                        Play Video
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="hero-image" data-aos="zoom-out" data-aos-delay="300">
                            <img src="assets/img/gilinganTampakAtas3.webp" alt="Hero Image" style="width: 636px; height: 427.6px;" class="heroo img-fluid main-image rounded-4 hide-on-mobile">

                            <!-- <div class="customers-badge">
                                <div class="customer-avatars">
                                    <img src="assets/img/avatar-1.webp" alt="Customer 1" class="avatar">
                                    <img src="assets/img/avatar-2.webp" alt="Customer 2" class="avatar">
                                    <img src="assets/img/avatar-3.webp" alt="Customer 3" class="avatar">
                                    <img src="assets/img/avatar-4.webp" alt="Customer 4" class="avatar">
                                    <img src="assets/img/avatar-5.webp" alt="Customer 5" class="avatar">
                                    <span class="avatar more">12+</span>
                                </div>
                                <p class="mb-0 mt-2">12,000+ lorem ipsum dolor sit amet consectetur adipiscing elit</p>
                            </div> -->
                        </div>
                    </div>
                </div>

                <!-- <div class="row stats-row gy-4 mt-5" data-aos="fade-up" data-aos-delay="500">
                    <div class="col-lg-3 col-md-6">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="bi bi-trophy"></i>
                            </div>
                            <div class="stat-content">
                                <h4>3x Won Awards</h4>
                                <p class="mb-0">Vestibulum ante ipsum</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="bi bi-briefcase"></i>
                            </div>
                            <div class="stat-content">
                                <h4>6.5k Faucibus</h4>
                                <p class="mb-0">Nullam quis ante</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="bi bi-graph-up"></i>
                            </div>
                            <div class="stat-content">
                                <h4>80k Mauris</h4>
                                <p class="mb-0">Etiam sit amet orci</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="bi bi-award"></i>
                            </div>
                            <div class="stat-content">
                                <h4>6x Phasellus</h4>
                                <p class="mb-0">Vestibulum ante ipsum</p>
                            </div>
                        </div>
                    </div>
                </div> -->

            </div>

        </section><!-- /Hero Section -->

        <!-- About Section -->
        <section id="about" class="about section">

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row gy-4 align-items-center justify-content-between">

                    <!-- <div class="col-xl-5" data-aos="fade-up" data-aos-delay="200"> -->
                    <div data-aos="fade-up" data-aos-delay="200">
                        <span class="about-meta">Tentang Kami</span>

                        <h2 class="about-title">UD Penggilingan Padi Putra Manuaba</h2>
                        <img src="assets/img/gilinganTampakAtas.webp" alt="" class="img-fluid mb-3 rounded-4 hide-on-web">
                        <p class="about-description"> Pabrik pengolahan padi yang beroperasi di wilayah Desa Tolai, mengubah padi (gabah) menjadi beras mentah melalui serangkaian proses yang meliputi penerimaan, pembersihan, pengeringan, penggilingan, sortasi, dan pengemasan, dengan tujuan membantu petani setempat dalam mengolah dan mendistribusikan hasil panen mereka ke pasar atau konsumen.</p>

                        <div class="row feature-list-wrapper">
                            <div class="col-md-6">
                                <ul class="feature-list">
                                    <li><i class="bi bi-check-circle-fill"></i> Penerimaan padi dari petani atau supplier</li>
                                    <li><i class="bi bi-check-circle-fill"></i> Pembersihan dan sortasi gabah</li>
                                    <li><i class="bi bi-check-circle-fill"></i> Pengeringan gabah hingga mencapai kadar air yang sesuai</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="feature-list">
                                    <li><i class="bi bi-check-circle-fill"></i> Penggilingan untuk memisahkan kulit padi (sekam) dari beras</li>
                                    <li><i class="bi bi-check-circle-fill"></i> Proses sortasi dan pengemasan beras mentah</li>
                                    <li><i class="bi bi-check-circle-fill"></i> Distribusi beras ke pasar atau konsumen</li>
                                </ul>
                            </div>
                        </div>

                        <div class="info-wrapper">
                            <div class="row gy-4 align-items-center">

                                <div class="profile d-flex align-items-center gap-3">
                                    <!-- Gambar sebagai tautan -->
                                    <a href="https://www.idabagusputra.site" target="_blank" class="text-decoration-none">
                                        <img src="assets/img/about-2.webp" alt="CEO Profile" class="profile-image rounded-circle">
                                    </a>

                                    <!-- Kontainer teks sebagai tautan -->
                                    <div>
                                        <a href="https://www.idabagusputra.site" target="_blank" class="text-decoration-none">
                                            <h4 class="profile-name mb-1">Ida Bagus Putu Putra Manuaba</h4>
                                        </a>
                                        <a href="https://www.idabagusputra.site" target="_blank" class="text-decoration-none">
                                            <p class="profile-position text-muted small">CEO &amp; Founder</p>
                                        </a>
                                    </div>
                                </div>



                                <div class="contact-info d-flex align-items-center gap-3">
                                    <!-- Ikon sebagai tautan -->

                                    <a href="https://wa.me/62811451486" class=" p-2 d-flex align-items-center justify-content-center text-decoration-none" target="_blank">
                                        <i class="bi bi-telephone-fill" style="color: #FC8F54;"></i>
                                    </a>

                                    <!-- Kontainer teks sebagai tautan -->
                                    <div>
                                        <a href="https://wa.me/62811451486" class="text-decoration-none text-dark" target="_blank">
                                            <p class="contact-label ms-2 mb-0 text-muted small">Klik untuk mulai chat di WhatsApp</p>
                                        </a>
                                        <a href="https://wa.me/62811451486" class="text-decoration-none text-dark" target="_blank">
                                            <p class="contact-number ms-2 fw-bold">+62 811-451-486</p>
                                        </a>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>

                    <!-- <div class="col-xl-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="image-wrapper">
                            <div class="images position-relative" data-aos="zoom-out" data-aos-delay="400">
                                <img src="assets/img/about-5.webp" alt="Business Meeting" class="img-fluid main-image rounded-4">
                                <img src="assets/img/about-2.webp" alt="Team Discussion" class="img-fluid small-image rounded-4">
                            </div>
                            <div class="experience-badge floating">
                                <h3>15+ <span>Years</span></h3>
                                <p>Of experience in business service</p>
                            </div>
                        </div>
                    </div> -->
                </div>

            </div>

        </section><!-- /About Section -->

        <!-- Features Section -->
        <section id="features" class="features section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Keunggulan</h2>
                <p>UD Penggilingan Padi Putra Manuaba menghadirkan teknologi dan proses pengolahan padi yang efisien dan berkualitas, mendukung petani lokal dalam menghasilkan beras bermutu tinggi.</p>
            </div><!-- End Section Title -->

            <div class="container">

                <div class="d-flex justify-content-center">

                    <ul class="nav nav-tabs" data-aos="fade-up" data-aos-delay="100">

                        <li class="nav-item">
                            <a class="nav-link active show" data-bs-toggle="tab" data-bs-target="#features-tab-1">
                                <h4>Teknologi</h4>
                            </a>
                        </li><!-- End tab nav item -->

                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" data-bs-target="#features-tab-2">
                                <h4>Kontrol</h4>
                            </a><!-- End tab nav item -->

                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" data-bs-target="#features-tab-3">
                                <h4>Ekonomi</h4>
                            </a>
                        </li><!-- End tab nav item -->
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" data-bs-target="#features-tab-4">
                                <h4>Distribusi</h4>
                            </a>
                        </li><!-- End tab nav item -->
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" data-bs-target="#features-tab-5">
                                <h4>Komitmen</h4>
                            </a>
                        </li><!-- End tab nav item -->

                    </ul>

                </div>

                <div class="tab-content" data-aos="fade-up" data-aos-delay="200">

                    <div class="tab-pane fade active show" id="features-tab-1">
                        <div class="row">
                            <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0 d-flex flex-column justify-content-center">
                                <h3>Teknologi Pengolahan Canggih</h3>
                                <p class="fst-italic">
                                    Teknologi Pengolahan Canggih
                                    Kami menggunakan peralatan modern mutakhir yang mengintegrasikan teknologi terkini untuk memaksimalkan kualitas dan efisiensi setiap tahap pengolahan padi.
                                </p>
                                <ul>
                                    <li><i class="bi bi-check2-all"></i> <span>Mesin pembersih gabah dengan sistem filter multi-tahap.</span></li>
                                    <li><i class="bi bi-check2-all"></i> <span>Alat pengering otomatis dengan kontrol suhu presisi.</span></li>
                                    <li><i class="bi bi-check2-all"></i> <span>Mesin penggilingan berteknologi tinggi untuk hasil giling optimal.</span></li>
                                    <li><i class="bi bi-check2-all"></i> <span>Sistem sortasi elektronik untuk memisahkan beras berkualitas.</span></li>
                                </ul>
                            </div>
                            <div class="col-lg-6 order-1 order-lg-2 text-center">
                                <img src="assets/img/features-illustration-1.webp" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div><!-- End tab content item -->

                    <div class="tab-pane fade" id="features-tab-2">
                        <div class="row">
                            <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0 d-flex flex-column justify-content-center">
                                <h3>Kontrol Kualitas Ketat</h3>
                                <p class="fst-italic">
                                    Kontrol Kualitas Ketat
                                    Komitmen kami terhadap kualitas tercermin dalam serangkaian pemeriksaan menyeluruh yang menjamin setiap butir beras memenuhi standar tertinggi.
                                </p>
                                <ul>
                                    <li><i class="bi bi-check2-all"></i> <span>Pemeriksaan awal kondisi gabah saat penerimaan.</span></li>
                                    <li><i class="bi bi-check2-all"></i> <span>Pengujian kadar air dan kemurnian gabah.</span></li>
                                    <li><i class="bi bi-check2-all"></i> <span>Proses sortasi manual dan otomatis.</span></li>
                                    <li><i class="bi bi-check2-all"></i> <span>Pengecekan akhir sebelum pengemasan.</span></li>
                                    <li><i class="bi bi-check2-all"></i> <span>Standarisasi mutu sesuai ketentuan kesehatan pangan.</span></li>
                                </ul>
                            </div>
                            <div class="col-lg-6 order-1 order-lg-2 text-center">
                                <img src="assets/img/features-illustration-2.webp" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div><!-- End tab content item -->

                    <div class="tab-pane fade" id="features-tab-3">
                        <div class="row">
                            <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0 d-flex flex-column justify-content-center">
                                <h3>Dukungan Ekonomi Petani Lokal</h3>
                                <p class="fst-italic">
                                    Dukungan Ekonomi Petani Lokal Kami tidak sekadar mengolah padi, tetapi membangun ekosistem ekonomi yang mendukung kesejahteraan petani melalui berbagai program pemberdayaan.
                                </p>
                                <ul>
                                    <li><i class="bi bi-check2-all"></i> <span>Harga pembelian gabah yang kompetitif.</span></li>
                                    <li><i class="bi bi-check2-all"></i> <span>Pembayaran tepat waktu.</span></li>
                                    <li><i class="bi bi-check2-all"></i> <span>Bimbingan teknis budidaya padi.</span></li>
                                    <li><i class="bi bi-check2-all"></i> <span>Fasilitasi akses pasar yang lebih luas.</span></li>
                                    <li><i class="bi bi-check2-all"></i> <span>Program kemitraan berkelanjutan.</span></li>
                                </ul>
                            </div>
                            <div class="col-lg-6 order-1 order-lg-2 text-center">
                                <img src="assets/img/features-illustration-3.webp" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div><!-- End tab content item -->

                    <div class="tab-pane fade" id="features-tab-4">
                        <div class="row">
                            <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0 d-flex flex-column justify-content-center">
                                <h3>Sistem Distribusi Efisien</h3>
                                <p class="fst-italic">
                                    Sistem Distribusi Efisien Infrastruktur distribusi canggih kami memastikan beras sampai ke konsumen dalam kondisi prima, tepat waktu, dan dengan kualitas terjamin. </p>
                                <ul>
                                    <li><i class="bi bi-check2-all"></i> <span>Armada distribusi terawat dan terjadwal.</span></li>
                                    <li><i class="bi bi-check2-all"></i> <span>Sistem pelacakan pengiriman modern.</span></li>
                                    <li><i class="bi bi-check2-all"></i> <span>Kemasan produk yang higienis dan aman.</span></li>
                                    <li><i class="bi bi-check2-all"></i> <span>Jangkauan distribusi regional.</span></li>
                                    <li><i class="bi bi-check2-all"></i> <span>Layanan pengiriman tepat waktu.</span></li>
                                </ul>
                            </div>
                            <div class="col-lg-6 order-1 order-lg-2 text-center">
                                <img src="assets/img/features-illustration-3.webp" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div><!-- End tab content item -->

                    <div class="tab-pane fade" id="features-tab-5">
                        <div class="row">
                            <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0 d-flex flex-column justify-content-center">
                                <h3>Komitmen Keberlanjutan dan Inovasi Pengolahan</h3>
                                <p class="fst-italic">
                                    Komitmen Keberlanjutan dan Inovasi Pengolahan Kami mengintegrasikan praktik ramah lingkungan dengan teknologi canggih untuk mendukung keberlanjutan produksi dan lingkungan.
                                </p>
                                <ul>
                                    <li><i class="bi bi-check2-all"></i> <span>Praktik ramah lingkungan dalam pengolahan.</span></li>
                                    <li><i class="bi bi-check2-all"></i> <span>Pengurangan limbah produksi.</span></li>
                                    <li><i class="bi bi-check2-all"></i> <span>Penggunaan energi terbarukan.</span></li>
                                    <li><i class="bi bi-check2-all"></i> <span>Sistem pengering mandiri berteknologi maju.</span></li>
                                    <li><i class="bi bi-check2-all"></i> <span>Pengelolaan air dan energi efisien.</span></li>
                                    <li><i class="bi bi-check2-all"></i> <span>Program daur ulang dan konservasi.</span></li>
                                </ul>
                            </div>
                            <div class="col-lg-6 order-1 order-lg-2 text-center">
                                <img src="assets/img/features-illustration-3.webp" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div><!-- End tab content item -->

                </div>

            </div>

        </section><!-- /Features Section -->

        <!-- Features Cards Section -->
        <section id="features-cards" class="features-cards section">

            <div class="container">

                <div class="row gy-4">
                    <div class="col-xl-3 col-md-6" data-aos="zoom-in" data-aos-delay="100">
                        <div class="feature-box orange">
                            <i class="bi bi-award"></i>
                            <h4>Kualitas Premium</h4>
                            <p>Kami menjamin setiap butir beras yang dihasilkan memenuhi standar kualitas tertinggi melalui proses sortasi dan kontrol ketat</p>
                        </div>
                    </div><!-- End Feature Borx-->

                    <div class="col-xl-3 col-md-6" data-aos="zoom-in" data-aos-delay="200">
                        <div class="feature-box blue">
                            <i class="bi bi-patch-check"></i>
                            <h4>Teknologi Modern</h4>
                            <p>Didukung oleh mesin-mesin pengolahan padi canggih yang memastikan efisiensi dan kualitas tinggi dalam setiap proses produksi</p>
                        </div>
                    </div><!-- End Feature Borx-->

                    <div class="col-xl-3 col-md-6" data-aos="zoom-in" data-aos-delay="300">
                        <div class="feature-box green">
                            <i class="bi bi-sunrise"></i>
                            <h4>Dukung Petani Lokal</h4>
                            <p>Berkomitmen memberdayakan ekonomi petani setempat melalui kemitraan, harga adil, dan akses pasar yang lebih luas</p>
                        </div>
                    </div><!-- End Feature Borx-->

                    <div class="col-xl-3 col-md-6" data-aos="zoom-in" data-aos-delay="400">
                        <div class="feature-box red">
                            <i class="bi bi-shield-check"></i>
                            <h4>Distribusi Terpercaya</h4>
                            <p>Sistem distribusi profesional dengan jaminan ketepatan waktu, kemasan higienis, dan jangkauan pemasaran yang luas</p>
                        </div>
                    </div><!-- End Feature Borx-->
                </div>

            </div>

        </section><!-- /Features Cards Section -->

        <!-- Features 2 Section -->
        <section id="features-2" class="features-2 section">

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row align-items-center">

                    <div class="col-lg-4">

                        <div class="feature-item text-end mb-5" data-aos="fade-right" data-aos-delay="200">
                            <div class="d-flex align-items-center justify-content-end gap-4">
                                <div class="feature-content">
                                    <h3>Use On Any Device</h3>
                                    <p>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; In ac dui quis mi consectetuer lacinia.</p>
                                </div>
                                <div class="feature-icon flex-shrink-0">
                                    <i class="bi bi-display"></i>
                                </div>
                            </div>
                        </div><!-- End .feature-item -->

                        <div class="feature-item text-end mb-5" data-aos="fade-right" data-aos-delay="300">
                            <div class="d-flex align-items-center justify-content-end gap-4">
                                <div class="feature-content">
                                    <h3>Feather Icons</h3>
                                    <p>Phasellus ullamcorper ipsum rutrum nunc nunc nonummy metus vestibulum volutpat sapien arcu sed augue aliquam erat volutpat.</p>
                                </div>
                                <div class="feature-icon flex-shrink-0">
                                    <i class="bi bi-feather"></i>
                                </div>
                            </div>
                        </div><!-- End .feature-item -->

                        <div class="feature-item text-end" data-aos="fade-right" data-aos-delay="400">
                            <div class="d-flex align-items-center justify-content-end gap-4">
                                <div class="feature-content">
                                    <h3>Retina Ready</h3>
                                    <p>Aenean tellus metus bibendum sed posuere ac mattis non nunc vestibulum fringilla purus sit amet fermentum aenean commodo.</p>
                                </div>
                                <div class="feature-icon flex-shrink-0">
                                    <i class="bi bi-eye"></i>
                                </div>
                            </div>
                        </div><!-- End .feature-item -->

                    </div>

                    <div class="col-lg-4" data-aos="zoom-in" data-aos-delay="200">
                        <div class="phone-mockup text-center">
                            <img src="assets/img/phone-app-screen.webp" alt="Phone Mockup" class="img-fluid">
                        </div>
                    </div><!-- End Phone Mockup -->

                    <div class="col-lg-4">

                        <div class="feature-item mb-5" data-aos="fade-left" data-aos-delay="200">
                            <div class="d-flex align-items-center gap-4">
                                <div class="feature-icon flex-shrink-0">
                                    <i class="bi bi-code-square"></i>
                                </div>
                                <div class="feature-content">
                                    <h3>W3c Valid Code</h3>
                                    <p>Donec vitae sapien ut libero venenatis faucibus nullam quis ante etiam sit amet orci eget eros faucibus tincidunt.</p>
                                </div>
                            </div>
                        </div><!-- End .feature-item -->

                        <div class="feature-item mb-5" data-aos="fade-left" data-aos-delay="300">
                            <div class="d-flex align-items-center gap-4">
                                <div class="feature-icon flex-shrink-0">
                                    <i class="bi bi-phone"></i>
                                </div>
                                <div class="feature-content">
                                    <h3>Fully Responsive</h3>
                                    <p>Maecenas tempus tellus eget condimentum rhoncus sem quam semper libero sit amet adipiscing sem neque sed ipsum.</p>
                                </div>
                            </div>
                        </div><!-- End .feature-item -->

                        <div class="feature-item" data-aos="fade-left" data-aos-delay="400">
                            <div class="d-flex align-items-center gap-4">
                                <div class="feature-icon flex-shrink-0">
                                    <i class="bi bi-browser-chrome"></i>
                                </div>
                                <div class="feature-content">
                                    <h3>Browser Compatibility</h3>
                                    <p>Nullam dictum felis eu pede mollis pretium integer tincidunt cras dapibus vivamus elementum semper nisi aenean vulputate.</p>
                                </div>
                            </div>
                        </div><!-- End .feature-item -->

                    </div>
                </div>

            </div>

        </section><!-- /Features 2 Section -->

        <!-- Call To Action Section -->
        <section id="call-to-action" class="call-to-action section">

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row content justify-content-center align-items-center position-relative">
                    <div class="col-lg-8 mx-auto text-center">
                        <h2 class="display-4 mb-4">Maecenas tempus tellus eget condimentum</h2>
                        <p class="mb-4">Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec velit neque, auctor sit amet aliquam vel</p>
                        <a href="#" class="btn btn-cta">Call To Action</a>
                    </div>

                    <!-- Abstract Background Elements -->
                    <div class="shape shape-1">
                        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                            <path d="M47.1,-57.1C59.9,-45.6,68.5,-28.9,71.4,-10.9C74.2,7.1,71.3,26.3,61.5,41.1C51.7,55.9,35,66.2,16.9,69.2C-1.3,72.2,-21,67.8,-36.9,57.9C-52.8,48,-64.9,32.6,-69.1,15.1C-73.3,-2.4,-69.5,-22,-59.4,-37.1C-49.3,-52.2,-32.8,-62.9,-15.7,-64.9C1.5,-67,34.3,-68.5,47.1,-57.1Z" transform="translate(100 100)"></path>
                        </svg>
                    </div>

                    <div class="shape shape-2">
                        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                            <path d="M41.3,-49.1C54.4,-39.3,66.6,-27.2,71.1,-12.1C75.6,3,72.4,20.9,63.3,34.4C54.2,47.9,39.2,56.9,23.2,62.3C7.1,67.7,-10,69.4,-24.8,64.1C-39.7,58.8,-52.3,46.5,-60.1,31.5C-67.9,16.4,-70.9,-1.4,-66.3,-16.6C-61.8,-31.8,-49.7,-44.3,-36.3,-54C-22.9,-63.7,-8.2,-70.6,3.6,-75.1C15.4,-79.6,28.2,-58.9,41.3,-49.1Z" transform="translate(100 100)"></path>
                        </svg>
                    </div>

                    <!-- Dot Pattern Groups -->
                    <div class="dots dots-1">
                        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <pattern id="dot-pattern" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                                <circle cx="2" cy="2" r="2" fill="currentColor"></circle>
                            </pattern>
                            <rect width="100" height="100" fill="url(#dot-pattern)"></rect>
                        </svg>
                    </div>

                    <div class="dots dots-2">
                        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <pattern id="dot-pattern-2" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                                <circle cx="2" cy="2" r="2" fill="currentColor"></circle>
                            </pattern>
                            <rect width="100" height="100" fill="url(#dot-pattern-2)"></rect>
                        </svg>
                    </div>

                    <div class="shape shape-3">
                        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                            <path d="M43.3,-57.1C57.4,-46.5,71.1,-32.6,75.3,-16.2C79.5,0.2,74.2,19.1,65.1,35.3C56,51.5,43.1,65,27.4,71.7C11.7,78.4,-6.8,78.3,-23.9,72.4C-41,66.5,-56.7,54.8,-65.4,39.2C-74.1,23.6,-75.8,4,-71.7,-13.2C-67.6,-30.4,-57.7,-45.2,-44.3,-56.1C-30.9,-67,-15.5,-74,0.7,-74.9C16.8,-75.8,33.7,-70.7,43.3,-57.1Z" transform="translate(100 100)"></path>
                        </svg>
                    </div>
                </div>

            </div>

        </section><!-- /Call To Action Section -->

        <!-- Clients Section -->
        <section id="clients" class="clients section">

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="swiper init-swiper">
                    <script type="application/json" class="swiper-config">
                        {
                            "loop": true,
                            "speed": 600,
                            "autoplay": {
                                "delay": 5000
                            },
                            "slidesPerView": "auto",
                            "pagination": {
                                "el": ".swiper-pagination",
                                "type": "bullets",
                                "clickable": true
                            },
                            "breakpoints": {
                                "320": {
                                    "slidesPerView": 2,
                                    "spaceBetween": 40
                                },
                                "480": {
                                    "slidesPerView": 3,
                                    "spaceBetween": 60
                                },
                                "640": {
                                    "slidesPerView": 4,
                                    "spaceBetween": 80
                                },
                                "992": {
                                    "slidesPerView": 6,
                                    "spaceBetween": 120
                                }
                            }
                        }
                    </script>
                    <div class="swiper-wrapper align-items-center">
                        <div class="swiper-slide"><img src="assets/img/clients/client-1.png" class="img-fluid" alt=""></div>
                        <div class="swiper-slide"><img src="assets/img/clients/client-2.png" class="img-fluid" alt=""></div>
                        <div class="swiper-slide"><img src="assets/img/clients/client-3.png" class="img-fluid" alt=""></div>
                        <div class="swiper-slide"><img src="assets/img/clients/client-4.png" class="img-fluid" alt=""></div>
                        <div class="swiper-slide"><img src="assets/img/clients/client-5.png" class="img-fluid" alt=""></div>
                        <div class="swiper-slide"><img src="assets/img/clients/client-6.png" class="img-fluid" alt=""></div>
                        <div class="swiper-slide"><img src="assets/img/clients/client-7.png" class="img-fluid" alt=""></div>
                        <div class="swiper-slide"><img src="assets/img/clients/client-8.png" class="img-fluid" alt=""></div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>

            </div>

        </section><!-- /Clients Section -->

        <!-- Testimonials Section -->
        <!-- <section id="testimonials" class="testimonials section "> -->
        <section id="testimonials" class="testimonials section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Testimonials</h2>
                <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
            </div><!-- End Section Title -->

            <div class="container">

                <div class="row g-5">

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="testimonial-item">
                            <img src="assets/img/testimonials/testimonials-1.jpg" class="testimonial-img" alt="">
                            <h3>Saul Goodman</h3>
                            <h4>Ceo &amp; Founder</h4>
                            <div class="stars">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                            </div>
                            <p>
                                <i class="bi bi-quote quote-icon-left"></i>
                                <span>Proin iaculis purus consequat sem cure digni ssim donec porttitora entum suscipit rhoncus. Accusantium quam, ultricies eget id, aliquam eget nibh et. Maecen aliquam, risus at semper.</span>
                                <i class="bi bi-quote quote-icon-right"></i>
                            </p>
                        </div>
                    </div><!-- End testimonial item -->

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="testimonial-item">
                            <img src="assets/img/testimonials/testimonials-2.jpg" class="testimonial-img" alt="">
                            <h3>Sara Wilsson</h3>
                            <h4>Designer</h4>
                            <div class="stars">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                            </div>
                            <p>
                                <i class="bi bi-quote quote-icon-left"></i>
                                <span>Export tempor illum tamen malis malis eram quae irure esse labore quem cillum quid cillum eram malis quorum velit fore eram velit sunt aliqua noster fugiat irure amet legam anim culpa.</span>
                                <i class="bi bi-quote quote-icon-right"></i>
                            </p>
                        </div>
                    </div><!-- End testimonial item -->

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="testimonial-item">
                            <img src="assets/img/testimonials/testimonials-3.jpg" class="testimonial-img" alt="">
                            <h3>Jena Karlis</h3>
                            <h4>Store Owner</h4>
                            <div class="stars">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                            </div>
                            <p>
                                <i class="bi bi-quote quote-icon-left"></i>
                                <span>Enim nisi quem export duis labore cillum quae magna enim sint quorum nulla quem veniam duis minim tempor labore quem eram duis noster aute amet eram fore quis sint minim.</span>
                                <i class="bi bi-quote quote-icon-right"></i>
                            </p>
                        </div>
                    </div><!-- End testimonial item -->

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="400">
                        <div class="testimonial-item">
                            <img src="assets/img/testimonials/testimonials-4.jpg" class="testimonial-img" alt="">
                            <h3>Matt Brandon</h3>
                            <h4>Freelancer</h4>
                            <div class="stars">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                            </div>
                            <p>
                                <i class="bi bi-quote quote-icon-left"></i>
                                <span>Fugiat enim eram quae cillum dolore dolor amet nulla culpa multos export minim fugiat minim velit minim dolor enim duis veniam ipsum anim magna sunt elit fore quem dolore labore illum veniam.</span>
                                <i class="bi bi-quote quote-icon-right"></i>
                            </p>
                        </div>
                    </div><!-- End testimonial item -->

                </div>

            </div>

        </section><!-- /Testimonials Section -->

        <!-- Stats Section -->
        <section id="stats" class="stats section">

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row gy-4">

                    <div class="col-lg-3 col-md-6">
                        <div class="stats-item text-center w-100 h-100">
                            <span data-purecounter-start="0" data-purecounter-end="232" data-purecounter-duration="1" class="purecounter"></span>
                            <p>Clients</p>
                        </div>
                    </div><!-- End Stats Item -->

                    <div class="col-lg-3 col-md-6">
                        <div class="stats-item text-center w-100 h-100">
                            <span data-purecounter-start="0" data-purecounter-end="521" data-purecounter-duration="1" class="purecounter"></span>
                            <p>Projects</p>
                        </div>
                    </div><!-- End Stats Item -->

                    <div class="col-lg-3 col-md-6">
                        <div class="stats-item text-center w-100 h-100">
                            <span data-purecounter-start="0" data-purecounter-end="1453" data-purecounter-duration="1" class="purecounter"></span>
                            <p>Hours Of Support</p>
                        </div>
                    </div><!-- End Stats Item -->

                    <div class="col-lg-3 col-md-6">
                        <div class="stats-item text-center w-100 h-100">
                            <span data-purecounter-start="0" data-purecounter-end="32" data-purecounter-duration="1" class="purecounter"></span>
                            <p>Workers</p>
                        </div>
                    </div><!-- End Stats Item -->

                </div>

            </div>

        </section><!-- /Stats Section -->

        <!-- Services Section -->
        <section id="services" class="services section ">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Services</h2>
                <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
            </div><!-- End Section Title -->

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row g-4">

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="service-card d-flex">
                            <div class="icon flex-shrink-0">
                                <i class="bi bi-activity"></i>
                            </div>
                            <div>
                                <h3>Nesciunt Mete</h3>
                                <p>Provident nihil minus qui consequatur non omnis maiores. Eos accusantium minus dolores iure perferendis tempore et consequatur.</p>
                                <a href="service-details.html" class="read-more">Read More <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div><!-- End Service Card -->

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="service-card d-flex">
                            <div class="icon flex-shrink-0">
                                <i class="bi bi-diagram-3"></i>
                            </div>
                            <div>
                                <h3>Eosle Commodi</h3>
                                <p>Ut autem aut autem non a. Sint sint sit facilis nam iusto sint. Libero corrupti neque eum hic non ut nesciunt dolorem.</p>
                                <a href="service-details.html" class="read-more">Read More <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div><!-- End Service Card -->

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="service-card d-flex">
                            <div class="icon flex-shrink-0">
                                <i class="bi bi-easel"></i>
                            </div>
                            <div>
                                <h3>Ledo Markt</h3>
                                <p>Ut excepturi voluptatem nisi sed. Quidem fuga consequatur. Minus ea aut. Vel qui id voluptas adipisci eos earum corrupti.</p>
                                <a href="service-details.html" class="read-more">Read More <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div><!-- End Service Card -->

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="400">
                        <div class="service-card d-flex">
                            <div class="icon flex-shrink-0">
                                <i class="bi bi-clipboard-data"></i>
                            </div>
                            <div>
                                <h3>Asperiores Commodit</h3>
                                <p>Non et temporibus minus omnis sed dolor esse consequatur. Cupiditate sed error ea fuga sit provident adipisci neque.</p>
                                <a href="service-details.html" class="read-more">Read More <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div><!-- End Service Card -->

                </div>

            </div>

        </section><!-- /Services Section -->

        <!-- Pricing Section -->
        <section id="pricing" class="pricing section ">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Pricing</h2>
                <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
            </div><!-- End Section Title -->

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row g-4 justify-content-center">

                    <!-- Basic Plan -->
                    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="pricing-card" style="background-color: #FFECC8">
                            <h3>Basic Plan</h3>
                            <div class="price">
                                <span class="currency">$</span>
                                <span class="amount">9.9</span>
                                <span class="period">/ month</span>
                            </div>
                            <p class="description">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium totam.</p>

                            <h4>Featured Included:</h4>
                            <ul class="features-list">
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    Duis aute irure dolor
                                </li>
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    Excepteur sint occaecat
                                </li>
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    Nemo enim ipsam voluptatem
                                </li>
                            </ul>

                            <a href="#" class="btn btn-primary">
                                Buy Now
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Standard Plan -->
                    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="pricing-card popular">
                            <div class="popular-badge">Most Popular</div>
                            <h3>Standard Plan</h3>
                            <div class="price">
                                <span class="currency">$</span>
                                <span class="amount">19.9</span>
                                <span class="period">/ month</span>
                            </div>
                            <p class="description">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum.</p>

                            <h4>Featured Included:</h4>
                            <ul class="features-list">
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    Lorem ipsum dolor sit amet
                                </li>
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    Consectetur adipiscing elit
                                </li>
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    Sed do eiusmod tempor
                                </li>
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    Ut labore et dolore magna
                                </li>
                            </ul>

                            <a href="#" class="btn btn-light">
                                Buy Now
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Premium Plan -->
                    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                        <div class="pricing-card" style="background-color: #FFECC8">

                            <h3>Premium Plan</h3>
                            <div class="price">
                                <span class="currency">$</span>
                                <span class="amount">39.9</span>
                                <span class="period">/ month</span>
                            </div>
                            <p class="description">Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae.</p>

                            <h4>Featured Included:</h4>
                            <ul class="features-list">
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    Temporibus autem quibusdam
                                </li>
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    Saepe eveniet ut et voluptates
                                </li>
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    Nam libero tempore soluta
                                </li>
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    Cumque nihil impedit quo
                                </li>
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    Maxime placeat facere possimus
                                </li>
                            </ul>

                            <a href="#" class="btn btn-primary">
                                Buy Now
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                </div>

            </div>

        </section><!-- /Pricing Section -->

        <!-- Faq Section -->
        <section class="faq-9 faq section " id="faq">

            <div class="container">
                <div class="row">

                    <div class="col-lg-5" data-aos="fade-up">
                        <h2 class="faq-title">Have a question? Check out the FAQ</h2>
                        <p class="faq-description">Maecenas tempus tellus eget condimentum rhoncus sem quam semper libero sit amet adipiscing sem neque sed ipsum.</p>
                        <div class="faq-arrow d-none d-lg-block" data-aos="fade-up" data-aos-delay="200">
                            <svg class="faq-arrow" width="200" height="211" viewBox="0 0 200 211" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M198.804 194.488C189.279 189.596 179.529 185.52 169.407 182.07L169.384 182.049C169.227 181.994 169.07 181.939 168.912 181.884C166.669 181.139 165.906 184.546 167.669 185.615C174.053 189.473 182.761 191.837 189.146 195.695C156.603 195.912 119.781 196.591 91.266 179.049C62.5221 161.368 48.1094 130.695 56.934 98.891C84.5539 98.7247 112.556 84.0176 129.508 62.667C136.396 53.9724 146.193 35.1448 129.773 30.2717C114.292 25.6624 93.7109 41.8875 83.1971 51.3147C70.1109 63.039 59.63 78.433 54.2039 95.0087C52.1221 94.9842 50.0776 94.8683 48.0703 94.6608C30.1803 92.8027 11.2197 83.6338 5.44902 65.1074C-1.88449 41.5699 14.4994 19.0183 27.9202 1.56641C28.6411 0.625793 27.2862 -0.561638 26.5419 0.358501C13.4588 16.4098 -0.221091 34.5242 0.896608 56.5659C1.8218 74.6941 14.221 87.9401 30.4121 94.2058C37.7076 97.0203 45.3454 98.5003 53.0334 98.8449C47.8679 117.532 49.2961 137.487 60.7729 155.283C87.7615 197.081 139.616 201.147 184.786 201.155L174.332 206.827C172.119 208.033 174.345 211.287 176.537 210.105C182.06 207.125 187.582 204.122 193.084 201.144C193.346 201.147 195.161 199.887 195.423 199.868C197.08 198.548 193.084 201.144 195.528 199.81C196.688 199.192 197.846 198.552 199.006 197.935C200.397 197.167 200.007 195.087 198.804 194.488ZM60.8213 88.0427C67.6894 72.648 78.8538 59.1566 92.1207 49.0388C98.8475 43.9065 106.334 39.2953 114.188 36.1439C117.295 34.8947 120.798 33.6609 124.168 33.635C134.365 33.5511 136.354 42.9911 132.638 51.031C120.47 77.4222 86.8639 93.9837 58.0983 94.9666C58.8971 92.6666 59.783 90.3603 60.8213 88.0427Z" fill="currentColor"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="col-lg-7" data-aos="fade-up" data-aos-delay="300">
                        <div class="faq-container">

                            <div class="faq-item faq-active">
                                <h3>Non consectetur a erat nam at lectus urna duis?</h3>
                                <div class="faq-content">
                                    <p>Feugiat pretium nibh ipsum consequat. Tempus iaculis urna id volutpat lacus laoreet non curabitur gravida. Venenatis lectus magna fringilla urna porttitor rhoncus dolor purus non.</p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div><!-- End Faq item-->

                            <div class="faq-item">
                                <h3>Feugiat scelerisque varius morbi enim nunc faucibus?</h3>
                                <div class="faq-content">
                                    <p>Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id interdum velit laoreet id donec ultrices. Fringilla phasellus faucibus scelerisque eleifend donec pretium. Est pellentesque elit ullamcorper dignissim. Mauris ultrices eros in cursus turpis massa tincidunt dui.</p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div><!-- End Faq item-->

                            <div class="faq-item">
                                <h3>Dolor sit amet consectetur adipiscing elit pellentesque?</h3>
                                <div class="faq-content">
                                    <p>Eleifend mi in nulla posuere sollicitudin aliquam ultrices sagittis orci. Faucibus pulvinar elementum integer enim. Sem nulla pharetra diam sit amet nisl suscipit. Rutrum tellus pellentesque eu tincidunt. Lectus urna duis convallis convallis tellus. Urna molestie at elementum eu facilisis sed odio morbi quis</p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div><!-- End Faq item-->

                            <div class="faq-item">
                                <h3>Ac odio tempor orci dapibus. Aliquam eleifend mi in nulla?</h3>
                                <div class="faq-content">
                                    <p>Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id interdum velit laoreet id donec ultrices. Fringilla phasellus faucibus scelerisque eleifend donec pretium. Est pellentesque elit ullamcorper dignissim. Mauris ultrices eros in cursus turpis massa tincidunt dui.</p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div><!-- End Faq item-->

                            <div class="faq-item">
                                <h3>Tempus quam pellentesque nec nam aliquam sem et tortor?</h3>
                                <div class="faq-content">
                                    <p>Molestie a iaculis at erat pellentesque adipiscing commodo. Dignissim suspendisse in est ante in. Nunc vel risus commodo viverra maecenas accumsan. Sit amet nisl suscipit adipiscing bibendum est. Purus gravida quis blandit turpis cursus in</p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div><!-- End Faq item-->

                            <div class="faq-item">
                                <h3>Perspiciatis quod quo quos nulla quo illum ullam?</h3>
                                <div class="faq-content">
                                    <p>Enim ea facilis quaerat voluptas quidem et dolorem. Quis et consequatur non sed in suscipit sequi. Distinctio ipsam dolore et.</p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div><!-- End Faq item-->

                        </div>
                    </div>

                </div>
            </div>
        </section><!-- /Faq Section -->

        <!-- Call To Action 2 Section -->
        <section id="call-to-action-2" class="call-to-action-2 section dark-background">

            <div class="container">
                <div class="row justify-content-center" data-aos="zoom-in" data-aos-delay="100">
                    <div class="col-xl-10">
                        <div class="text-center">
                            <h3>Call To Action</h3>
                            <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                            <a class="cta-btn" href="#">Call To Action</a>
                        </div>
                    </div>
                </div>
            </div>

        </section><!-- /Call To Action 2 Section -->

        <!-- Contact Section -->
        <section id="contact" class="contact section ">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Contact</h2>
                <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
            </div><!-- End Section Title -->

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row g-4 g-lg-5">
                    <div class="col-lg-5">
                        <div class="info-box" data-aos="fade-up" data-aos-delay="200">
                            <h3>Contact Info</h3>
                            <p>Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Vestibulum ante ipsum primis.</p>

                            <div class="info-item" data-aos="fade-up" data-aos-delay="300">
                                <div class="icon-box">
                                    <i class="bi bi-geo-alt"></i>
                                </div>
                                <div class="content">
                                    <h4>Our Location</h4>
                                    <p>A108 Adam Street</p>
                                    <p>New York, NY 535022</p>
                                </div>
                            </div>

                            <div class="info-item" data-aos="fade-up" data-aos-delay="400">
                                <div class="icon-box">
                                    <i class="bi bi-telephone"></i>
                                </div>
                                <div class="content">
                                    <h4>Phone Number</h4>
                                    <p>+1 5589 55488 55</p>
                                    <p>+1 6678 254445 41</p>
                                </div>
                            </div>

                            <div class="info-item" data-aos="fade-up" data-aos-delay="500">
                                <div class="icon-box">
                                    <i class="bi bi-envelope"></i>
                                </div>
                                <div class="content">
                                    <h4>Email Address</h4>
                                    <p>info@example.com</p>
                                    <p>contact@example.com</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="contact-form" data-aos="fade-up" data-aos-delay="300">
                            <h3>Get In Touch</h3>
                            <p>Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Vestibulum ante ipsum primis.</p>

                            <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="200">
                                <div class="row gy-4">

                                    <div class="col-md-6">
                                        <input type="text" name="name" class="form-control" placeholder="Your Name" required="">
                                    </div>

                                    <div class="col-md-6 ">
                                        <input type="email" class="form-control" name="email" placeholder="Your Email" required="">
                                    </div>

                                    <div class="col-12">
                                        <input type="text" class="form-control" name="subject" placeholder="Subject" required="">
                                    </div>

                                    <div class="col-12">
                                        <textarea class="form-control" name="message" rows="6" placeholder="Message" required=""></textarea>
                                    </div>

                                    <div class="col-12 text-center">
                                        <div class="loading">Loading</div>
                                        <div class="error-message"></div>
                                        <div class="sent-message">Your message has been sent. Thank you!</div>

                                        <button type="submit" class="btn">Send Message</button>
                                    </div>

                                </div>
                            </form>

                        </div>
                    </div>

                </div>

            </div>

        </section><!-- /Contact Section -->

    </main>

    <footer id="footer" class="footer">

        <div class="container footer-top">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6 footer-about">
                    <a href="index.html" class="logo d-flex align-items-center">
                        <span class="sitename">Mitra Padi</span>
                    </a>
                    <div class="footer-contact pt-3">
                        <p>Jl. Trans Sulawesi</p>
                        <p>Dusun Babahan, Desa Tolai 535022</p>
                        <p class="mt-3"><strong>Phone:</strong> <span>+62 811 451 486</span></p>
                        <p><strong>Email:</strong> <span>gp.putramanuaba@gmail.com</span></p>
                    </div>
                    <div class="social-links d-flex mt-4">
                        <a href=""><i class="bi bi-twitter-x"></i></a>
                        <a href=""><i class="bi bi-facebook"></i></a>
                        <a href=""><i class="bi bi-instagram"></i></a>
                        <a href=""><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>

                <!-- <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Useful Links</h4>
                    <ul>
                        <li><a href="#">Home</a></li>
                        <li><a href="#">About us</a></li>
                        <li><a href="#">Services</a></li>
                        <li><a href="#">Terms of service</a></li>
                        <li><a href="#">Privacy policy</a></li>
                    </ul>
                </div> -->

                <!-- <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Our Services</h4>
                    <ul>
                        <li><a href="#">Web Design</a></li>
                        <li><a href="#">Web Development</a></li>
                        <li><a href="#">Product Management</a></li>
                        <li><a href="#">Marketing</a></li>
                        <li><a href="#">Graphic Design</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Hic solutasetp</h4>
                    <ul>
                        <li><a href="#">Molestiae accusamus iure</a></li>
                        <li><a href="#">Excepturi dignissimos</a></li>
                        <li><a href="#">Suscipit distinctio</a></li>
                        <li><a href="#">Dilecta</a></li>
                        <li><a href="#">Sit quas consectetur</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Nobis illum</h4>
                    <ul>
                        <li><a href="#">Ipsam</a></li>
                        <li><a href="#">Laudantium dolorum</a></li>
                        <li><a href="#">Dinera</a></li>
                        <li><a href="#">Trodelas</a></li>
                        <li><a href="#">Flexo</a></li>
                    </ul>
                </div> -->

            </div>
        </div>

        <div class="container copyright text-center mt-4">
            <p>© <span>2024</span> <strong class="px-1 sitename">Ida Bagus Putu Putra Manuaba.</strong> <span>Create. Move. Inspire.</span></p>
            <div class="credits">
                <!-- All the links in the footer should remain intact. -->
                <!-- You can delete the links only if you've purchased the pro version. -->
                <!-- Licensing information: https://bootstrapmade.com/license/ -->
                <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
                Designed by <a href="https://www.idabagusputra.com">Ida Bagus Putu Putra Manuaba</a>
            </div>
        </div>

    </footer>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>

    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>

</body>

</html>