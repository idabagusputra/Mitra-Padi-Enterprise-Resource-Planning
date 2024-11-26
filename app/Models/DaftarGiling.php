<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DaftarGiling extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'harga_jual',
        'giling_id',
        'giling_kotor',
        'biaya_giling',
        'ongkos_giling',
        'beras_bersih',
        'beras_jual',
        'total_hutang',
        'total_pengambilan',
        'pulang',
        'pinjam',
        'dana_jual_beras',
        'dana_penerima',
        'biaya_buruh_giling',
        'total_biaya_buruh_giling',
        'jemur',
        'biaya_buruh_jemur',
        'total_biaya_buruh_jemur',
        'jumlah_konga',
        'harga_konga',
        'dana_jual_konga',
        'jumlah_menir',
        'harga_menir',
        'dana_jual_menir',
        'bunga'
    ];


    // Relasi melalui model Giling untuk mendapatkan Petani
    public function petani()
    {
        return $this->giling->petani();
    }

    public function giling()
    {
        return $this->belongsTo(Giling::class)->withTrashed();
    }

    // Relasi ke pengambilans melalui giling
    public function pengambilans()
    {
        return $this->giling->pengambilans();
    }
}
