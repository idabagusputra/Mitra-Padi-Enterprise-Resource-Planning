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
                                value="Rp. {{ number_format($totalKreditPetani, 2, '.', ',') }}"
                                readonly>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="total_kredit" class="form-control-label">Total Kredit Nasabah Palu</label>
                            <input class="form-control readonly-transparent" type="text"
                                value="Rp. {{ number_format($totalKreditNasabahPalu, 2, '.', ',') }}"
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
                    'ongkos_jemur_jumlah' => ['label' => 'Jumlah Gabah Belum Giling'],
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
        // Format number inputs
        const numberInputs = document.querySelectorAll('.number-format');

        numberInputs.forEach(input => {
            // Format saat halaman dimuat
            formatNumber(input);

            // Format saat input berubah
            input.addEventListener('input', function(e) {
                let value = this.value; // Ambil seluruh input
                this.dataset.rawValue = value;
                formatNumber(this);
            });
        });

        function formatNumber(input) {
            let value = input.value;

            // Menyimpan nilai mentah tanpa format
            input.dataset.rawValue = value;

            // Pisahkan bagian integer dan desimal
            let [integer, decimal] = value.split('.');

            // Hapus semua karakter yang tidak valid dari bagian integer (kecuali angka)
            integer = integer.replace(/[^\d]/g, '');

            // Format bagian integer dengan koma sebagai pemisah ribuan
            if (integer) {
                integer = integer.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            }

            // Gabungkan kembali bagian integer dan desimal jika ada
            if (decimal !== undefined) {
                value = integer + '.' + decimal;
            } else {
                value = integer;
            }

            // Mengatur nilai input field dengan format yang benar
            input.value = value;
        }

        // Form submission handling to send raw values
        document.querySelector('form').addEventListener('submit', function(e) {
            // List of fields to remove commas and convert to numeric
            const fieldsToClean = [
                'bri',
                'bni',
                'tunai',
                'mama',
                'stok_beras_jumlah',
                'stok_beras_harga',
                'stok_beras_total',
                'ongkos_jemur_jumlah',
                'ongkos_jemur_harga',
                'ongkos_jemur_total',
                'beras_terpinjam_jumlah',
                'beras_terpinjam_harga',
                'beras_terpinjam_total',
                'pinjaman_bank',
                'titipan_petani',
                'utang_beras',
                'utang_ke_operator'
            ];

            // Process each input before submission
            fieldsToClean.forEach(fieldName => {
                const input = document.querySelector(`[name="${fieldName}"]`);
                if (input) {
                    // Remove all commas
                    let cleanValue = input.value.replace(/,/g, '');

                    // Convert to a valid numeric value
                    cleanValue = parseFloat(cleanValue) || '0';

                    // Set the cleaned value back to the input as the raw value
                    input.dataset.rawValue = cleanValue.toString();
                }
            });

            // Set all number inputs to their raw values for submission
            numberInputs.forEach(input => {
                input.value = input.dataset.rawValue;
            });

            if (this.getAttribute('data-submitting') === 'true') {
                e.preventDefault();
                return;
            }

            // Set flag bahwa form sedang disubmit
            this.setAttribute('data-submitting', 'true');
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            // Disable button dan tampilkan loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
        <div class="d-flex align-items-center">
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            <span>Menyimpan...</span>
        </div>
    `;
        });


    });
</script>

@endsection