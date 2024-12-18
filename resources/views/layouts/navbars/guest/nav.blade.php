<style>
    .brand-hover::before {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background-color: #4CAF50;
        transition: width 0.3s ease;
    }

    .brand-hover:hover::before {
        width: 100%;
    }

    .text-gradient {
        background: linear-gradient(to right, #4CAF50, #2196F3);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        transition: all 0.3s ease;
    }

    .brand-hover:hover .text-gradient {
        background: linear-gradient(to right, #2196F3, #4CAF50);
    }
</style>
<!-- Navbar -->
<nav class="navbar mx-4 navbar-expand-lg position-absolute top-0 z-index-3 my-3 {{ (Request::is('static-sign-up') ? 'w-100 shadow-none  navbar-transparent mt-4' : 'blur blur-rounded shadow py-2 start-0 end-0 mx4') }}">
    <div class="container-fluid justify-content-center {{ (Request::is('static-sign-up') ? 'container' : 'container-fluid') }}">
        <a class="navbar-brand font-weight-bold text-center text-sm-start mx-0 px-2 ps-2 position-relative brand-hover ms-lg-0 {{ (Request::is('static-sign-up') ? 'text-white' : '') }}" href="{{ url('dashboard') }}">
            <span class="d-block text-nowrap text-center text-sm-start" style="letter-spacing: 0.5px; transition: all 0.3s ease;">
                <b>Penggilingan Padi Putra Manuaba</b>
            </span>
        </a>
        <!--<button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">-->
        <!--    <span class="navbar-toggler-icon mt-2">-->
        <!--        <span class="navbar-toggler-bar bar1"></span>-->
        <!--        <span class="navbar-toggler-bar bar2"></span>-->
        <!--        <span class="navbar-toggler-bar bar3"></span>-->
        <!--    </span>-->
        <!--</button>-->
        <div class="collapse navbar-collapse" id="navigation">
            <ul class="navbar-nav mx-auto">
                @if (auth()->user())
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center me-2 active" aria-current="page" href="{{ url('dashboard') }}">
                        <i class="fa fa-chart-pie opacity-6 me-1 {{ (Request::is('static-sign-up') ? '' : 'text-dark') }}"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-2" href="{{ url('profile') }}">
                        <i class="fa fa-user opacity-6 me-1 {{ (Request::is('static-sign-up') ? '' : 'text-dark') }}"></i>
                        Profile
                    </a>
                </li>
                @endif
                <!-- <li class="nav-item">
                    <a class="nav-link me-2" href="{{ auth()->user() ? url('static-sign-up') : url('register') }}">
                        <i class="fas fa-user-circle opacity-6 me-1 {{ (Request::is('static-sign-up') ? '' : 'text-dark') }}"></i>
                        Sign Up
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-2" href="{{ auth()->user() ? url('static-sign-in') : url('login') }}">
                        <i class="fas fa-key opacity-6 me-1 {{ (Request::is('static-sign-up') ? '' : 'text-dark') }}"></i>
                        Sign In
                    </a>
                </li> -->
            </ul>
            <ul class="navbar-nav d-lg-block d-none">
                <li class="nav-item">
                    <a href="" target="" class="btn btn-sm btn-round mb-0 me-1 bg-gradient-{{ (Request::is('static-sign-up') ? 'light' : 'dark') }}">Mitra Padi</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- End Navbar -->