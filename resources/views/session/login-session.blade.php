@extends('layouts.user_type.guest')

<style>
    /* Gradien untuk teks */
    .text-gradient-new {
        background: linear-gradient(45deg, #ff3c7e, #a900d9);
        /* Sesuaikan warna gradien di sini */
        -webkit-background-clip: text;
        color: transparent;
    }

    /* Gradien untuk tombol */
    .btn-gradient {
        background: linear-gradient(45deg, #ff3c7e, #a900d9);
        /* Sesuaikan warna gradien di sini */
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: bold;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        transition: transform 0.2s;
    }

    /* Efek hover pada tombol */
    .btn-gradient:hover {
        transform: scale(1.05);
    }
</style>

@section('content')

<main class="main-content  mt-0">
    <section>
        <div class="page-header min-vh-75">
            <div class="container">
                <div class="row">
                    <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
                        <div class="card card-plain mt-8">
                            <div class="card-header pb-0 text-left bg-transparent">
                                <h3 class="font-weight-bolder text-gradient-new">Welcome back</h3>
                                <p class="mb-0">Silakan masuk terlebih dahulu sebelum melanjutkan<br></p>
                                <!-- <p class="mb-0">OR Sign in with these credentials:</p>
                  <p class="mb-0">Email <b>admin@softui.com</b></p>
                  <p class="mb-0">Password <b>secret</b></p> -->
                            </div>
                            <div class="card-body">
                                <form role="form" method="POST" action="/session">
                                    @csrf
                                    <label>Email</label>
                                    <div class="mb-3">
                                        <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="putra.manuaba@penggilingan.padi.com" aria-label="Email" aria-describedby="email-addon">
                                        @error('email')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <label>Password</label>
                                    <div class="mb-3">
                                        <input type="password" class="form-control" name="password" id="password" placeholder="Password" value="" aria-label="Password" aria-describedby="password-addon">
                                        @error('password')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="rememberMe" checked="">
                                        <label class="form-check-label" for="rememberMe">Remember me</label>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-gradient text-white w-100 mt-4 mb-0">Sign in</button>
                                    </div>
                                </form>
                            </div>
                            <!-- <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                <small class="text-muted">Forgot you password? Reset you password
                                    <a href="/login/forgot-password" class="text-info text-gradient font-weight-bold">here</a>
                                </small>
                                <p class="mb-4 text-sm mx-auto">
                                    Don't have an account?
                                    <a href="register" class="text-info text-gradient font-weight-bold">Sign up</a>
                                </p>
                            </div> -->
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
                            <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image:url('../assets/img/curved-images/curved6.jpg')"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

@endsection