@extends('layouts.app')

@section('auth')


@if(\Request::is('static-sign-up'))
@include('layouts.navbars.guest.nav')
@yield('content')
@include('layouts.footers.guest.footer')

@elseif (\Request::is('static-sign-in'))
@include('layouts.navbars.guest.nav')
@yield('content')
@include('layouts.footers.guest.footer')

@else
@if (\Request::is('rtl'))
@include('layouts.navbars.auth.sidebar-rtl')
<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg overflow-hidden">
    @include('layouts.navbars.auth.nav-rtl')
    <div class="container-fluid py-4">
        @yield('content')
        @include('layouts.footers.auth.footer')
    </div>
</main>

@elseif (\Request::is('profile'))
@include('layouts.navbars.auth.sidebar')
<div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
    @include('layouts.navbars.auth.nav')
    @yield('content')
</div>

@elseif (\Request::is('virtual-reality'))
@include('layouts.navbars.auth.nav')
<div class="border-radius-xl mt-3 mx-3 position-relative" style="background-image: url('../assets/img/vr-bg.jpg') ; background-size: cover;">
    @include('layouts.navbars.auth.sidebar')
    <main class="main-content mt-1 border-radius-lg">
        @yield('content')
    </main>
</div>
@include('layouts.footers.auth.footer')

@else


@include('layouts.navbars.auth.sidebar')

<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg {{ (Request::is('rtl') ? 'overflow-hidden' : '') }}">
    @include('layouts.navbars.auth.nav')
    <div class="container-fluid py-4">
        @yield('content')
        @include('layouts.footers.auth.footer')
    </div>
</main>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi Perfect Scrollbar untuk sidebar
        const psSidebar = new PerfectScrollbar('.sidenav', {
            suppressScrollX: true, // Menonaktifkan scroll horizontal
        });
    });
</script>
<script>
    const iconNavbarSidenav = document.getElementById("iconNavbarSidenav");
    const iconSidenav = document.getElementById("iconSidenav");
    const sidenav = document.getElementById("sidenav-main");
    let body = document.getElementsByTagName("body")[0];
    let className = "g-sidenav-pinned";

    // Toggle Sidebar Function
    function toggleSidenav() {
        if (body.classList.contains(className)) {
            body.classList.remove(className);
            setTimeout(function() {
                sidenav.classList.remove("bg-white");
            }, 100);
            sidenav.classList.remove("bg-transparent");
        } else {
            body.classList.add(className);
            sidenav.classList.add("bg-white");
            sidenav.classList.remove("bg-transparent");
            iconSidenav.classList.remove("d-none");
        }
    }

    // Toggle Sidenav when clicking on the sidebar icon
    if (iconNavbarSidenav) {
        iconNavbarSidenav.addEventListener("click", toggleSidenav);
    }

    if (iconSidenav) {
        iconSidenav.addEventListener("click", toggleSidenav);
    }

    // Close Sidebar if clicking outside only when sidebar has bg-transparent class
    document.addEventListener('click', function(event) {
        if (!sidenav.contains(event.target) && !iconNavbarSidenav.contains(event.target)) {
            // If the click is outside the sidebar and the sidebar icon
            // Check if the sidebar has bg-transparent class
            if (sidenav.classList.contains("bg-white")) {
                toggleSidenav();
            }
        }
    });
</script>
<!-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi Perfect Scrollbar untuk konten utama
        const psMainContent = new PerfectScrollbar('.main-content', {
            suppressScrollX: true,
        });
    });
</script> -->
@endif

@include('components.fixed-plugin')
@endif



@endsection