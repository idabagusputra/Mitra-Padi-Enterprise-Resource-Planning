<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BukuStokPinjamanKonga extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'buku_stok_pinjaman_konga';

    protected $fillable = [
        'petani_id',
        'nama_petani',
        'tanggal',
        'jumlah',
        'status',
        'buku_stok_konga_menir_id', // Tambahkan ini
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
        'status' => 'boolean',
    ];

    public $timestamps = true;

    // Relationship
    public function petani()
    {
        return $this->belongsTo(Petani::class, 'petani_id', 'id');
    }
}
