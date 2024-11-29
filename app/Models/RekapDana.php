<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapDana extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak menggunakan nama default
    protected $table = 'rekap_dana';

    // Kolom-kolom yang boleh diisi secara massal (fillable)
    protected $fillable = [
        'rekapan_dana',
        'total_kredit', // Menambahkan total_kredit pada fillable
        'nasabah_palu',
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
        'utang_ke_operator',
    ];

    // Anda juga bisa menambahkan metode akses (getter) atau manipulasi data lainnya jika diperlukan.
}
