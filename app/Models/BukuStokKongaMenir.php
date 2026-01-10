<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BukuStokKongaMenir extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'buku_stok_konga_menir';

    protected $fillable = [
        'petani_id',
        'nama_petani',
        'giling_id',
        'tanggal',
        'konga_giling',
        'karung_konga',
        'konga_jual',
        'harga_menir',
        'harga_konga',
        'pinjam_konga',
        'kembalikan_konga',
        'menir',
        'menir_jual',
        'global_menir',
        'status', // ⬅️ Tambahkan disini
    ];

    protected $casts = [
        'tanggal' => 'date',
        'konga_giling' => 'decimal:2',
        'konga_jual' => 'decimal:2',
        'pinjam_konga' => 'decimal:2',
        'kembalikan_konga' => 'decimal:2',
        'menir' => 'decimal:2',
        'menir_jual' => 'decimal:2',
        'global_menir' => 'decimal:2',
        'status' => 'integer', // ⬅️ Tambahkan jika mau casting
    ];

    public $timestamps = true;

    public function petani()
    {
        return $this->belongsTo(Petani::class, 'petani_id', 'id');
    }
}
