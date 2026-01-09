<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BukuStokPinjamanBeras extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'buku_stok_pinjaman_beras';

    protected $fillable = [
        'petani_id',
        'nama_petani',
        'tanggal',
        'jumlah',
        'status',
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
