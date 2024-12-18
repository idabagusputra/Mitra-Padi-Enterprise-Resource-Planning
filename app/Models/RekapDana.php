<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RekapDana extends Model
{
    use HasFactory, SoftDeletes;

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

    // Method to calculate Kelompok 1 total
    public function getKelompok1Total()
    {
        return $this->bri + $this->bni + $this->tunai + $this->mama + $this->total_kredit + $this->nasabah_palu;
    }

    // Method to calculate Kelompok 2 total
    public function getKelompok2Total()
    {
        return $this->stok_beras_total + $this->ongkos_jemur_total + $this->beras_terpinjam_total;
    }

    // Method to calculate Kelompok 3 total
    public function getKelompok3Total()
    {
        return $this->pinjaman_bank + $this->titipan_petani + $this->utang_beras + $this->utang_ke_operator;
    }

    // Method to calculate the final 'rekapan_dana'
    public function calculateRekapanDana()
    {
        $kelompok1Total = $this->getKelompok1Total();
        $kelompok2Total = $this->getKelompok2Total();
        $kelompok3Total = $this->getKelompok3Total();

        // Calculate rekapan_dana
        return $kelompok1Total + $kelompok2Total - $kelompok3Total;
    }
}
