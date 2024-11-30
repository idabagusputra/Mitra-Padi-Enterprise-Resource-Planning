@extends('layouts.user_type.auth')

<style>
    .readonly-transparent {
        background-color: transparent !important;
        /* Membuat latar belakang tetap transparan */
        border: 1px solid #ced4da;
        /* Memastikan border tetap sesuai */
        color: inherit;
        /* Memastikan warna teks tetap sesuai */
        pointer-events: none;
        /* Mencegah interaksi dengan pointer */
    }

    .btn-css {

        /* width: 23.28% !important; */
        /* width: 170.867 !important; */
        width: 200 !important;
        min-height: 59.2px;

    }
</style>

@section('content')
<div class="container-fluid">
    <div class="card">
        <!-- <div class="card-header pb-0 px-3">
            <h6 class="mb-0 text-primary">{{ __('Keuangan') }}</h6>
        </div> -->
        <div class="card-body pt-4 p-3">
            @if ($errors->any())
            <div class="mt-3 alert alert-primary alert-dismissible fade show" role="alert">
                <span class="alert-text text-white">{{ $errors->first() }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <i class="bi bi-x-circle" aria-hidden="true"></i>
                </button>
            </div>
            @endif

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show m-0" role="alert">
                <span class="alert-text text-white">
                    {{ session('success') }}
                </span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <i class="bi bi-x-circle" aria-hidden="true"></i>
                </button>
            </div>
            @endif

            <form action="{{ route('rekapdana.store') }}" method="POST" role="form text-left">
                @csrf

                <h6 class="mb-3 text-primary">{{ __('( + ) Dana Tersebar') }}</h6>
                <div class="row">
                    <!-- Field Total Kredit -->
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="total_kredit" class="form-control-label">Total Kredit Petani</label>
                            <input class="form-control readonly-transparent" type="text"
                                value="Rp. {{ number_format($totalKreditPetani, 2, ',', '.') }}"
                                readonly>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="total_kredit" class="form-control-label">Total Kredit Nasabah Palu</label>
                            <input class="form-control readonly-transparent" type="text"
                                value="Rp. {{ number_format($totalKreditNasabahPalu, 2, ',', '.') }}"
                                readonly>
                        </div>
                    </div>

                    @php
                    $kelompok1 = [
                    'bri' => ['label' => 'B R I'],
                    'tunai' => ['label' => 'Tunai'],
                    'mama' => ['label' => 'Mama'],
                    'bni' => ['label' => 'B N I'],
                    ];
                    @endphp

                    @foreach($kelompok1 as $field => $data)
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="{{ $field }}" class="form-control-label">{{ __($data['label']) }}</label>
                            <input class="form-control number-format" type="text"
                                name="{{ $field }}"
                                id="{{ $field }}"
                                inputmode="numeric"
                                required>
                        </div>
                    </div>
                    @endforeach
                </div>



                <h6 class="mb-3 mt-4 text-primary">{{ __('( + ) Kalkulasi Jumlah dan Harga') }}</h6>
                <div class="row">
                    @php
                    $kelompok2 = [
                    'stok_beras_jumlah' => ['label' => 'Jumlah Stok Beras'],
                    'beras_terpinjam_jumlah' => ['label' => 'Jumlah Beras Terpinjam'],
                    'ongkos_jemur_jumlah' => ['label' => 'Jumlah Ongkos Jemur'],
                    'stok_beras_harga' => ['label' => 'Harga Stok Beras'],
                    'beras_terpinjam_harga' => ['label' => 'Harga Beras Terpinjam'],
                    'ongkos_jemur_harga' => ['label' => 'Harga Ongkos Jemur']
                    ];
                    @endphp
                    @foreach($kelompok2 as $field => $data)
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="{{ $field }}" class="form-control-label">{{ __($data['label']) }}</label>
                            <input class="form-control number-format" type="text"
                                name="{{ $field }}"
                                id="{{ $field }}"
                                inputmode="numeric"
                                required>
                        </div>
                    </div>
                    @endforeach
                </div>

                <h6 class="mb-3 mt-4 text-primary">{{ __('( - ) Pinjaman dan Utang') }}</h6>
                <div class="row">
                    @php
                    $kelompok3 = [
                    'pinjaman_bank' => ['label' => 'Pinjaman Bank'],
                    'titipan_petani' => ['label' => 'Titipan Petani'],
                    'utang_beras' => ['label' => 'Utang Beras'],
                    'utang_ke_operator' => ['label' => 'Utang ke Operator']
                    ];
                    @endphp
                    @foreach($kelompok3 as $field => $data)
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label for="{{ $field }}" class="form-control-label">{{ __($data['label']) }}</label>
                            <input class="form-control number-format" type="text"
                                name="{{ $field }}"
                                id="{{ $field }}"
                                inputmode="numeric"
                                required>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-end mt-2">
                    <button type="submit" class="btn btn-css bg-gradient-primary btn-md submit-button">{{ 'Simpan Rekapan Dana' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Number formatting script from the original blade (you can copy the exact script from the previous template)
        const numberInputs = document.querySelectorAll('.number-format');

        numberInputs.forEach(input => {
            // Format saat halaman dimuat
            formatNumber(input);

            // Mencegah input selain angka
            input.addEventListener('keypress', function(e) {
                if (!/[\d]/.test(e.key) &&
                    e.key !== 'Backspace' &&
                    e.key !== 'Delete' &&
                    e.key !== 'ArrowLeft' &&
                    e.key !== 'ArrowRight' &&
                    e.key !== 'Tab') {
                    e.preventDefault();
                }
            });

            // Mencegah paste konten selain angka
            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const pastedText = (e.clipboardData || window.clipboardData).getData('text');
                if (/^\d+$/.test(pastedText)) {
                    const value = this.value.replace(/\D/g, '');
                    this.dataset.rawValue = value;
                    formatNumber(this);
                }
            });

            // Format saat input berubah
            input.addEventListener('input', function(e) {
                let value = this.value.replace(/\D/g, '');
                this.dataset.rawValue = value;
                formatNumber(this);
            });

            // Saat fokus, tampilkan nilai asli
            input.addEventListener('focus', function(e) {
                this.value = this.dataset.rawValue;
                // Posisikan kursor di akhir
                const length = this.value.length;
                this.setSelectionRange(length, length);
            });

            // Saat blur, format ulang
            input.addEventListener('blur', function(e) {
                formatNumber(this);
            });
        });

        function formatNumber(input) {
            let value = input.value.replace(/\D/g, '');
            input.dataset.rawValue = value;

            if (value !== '') {
                value = parseInt(value);
                input.value = value.toLocaleString('id-ID');
            }
        }

        // Form submission handling to send raw values
        document.querySelector('form').addEventListener('submit', function(e) {
            numberInputs.forEach(input => {
                input.value = input.dataset.rawValue;
            });
        });
    });
</script>

@endsection