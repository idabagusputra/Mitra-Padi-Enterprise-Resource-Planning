<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BukuStokBeras extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'buku_stok_beras';

    protected $fillable = [
        'petani_id',
        'nama_petani',
        'giling_id',
        'jemur',
        'tanggal',
        'giling_kotor',
        'ongkos',
        'pinjaman_beras',
        'beras_bersih',
        'beras_pulang',
        'jual',
        'jual_kotor',
        'global_beras',
        'status',
        'keterangan_operator_gajian',
        'harga',
    ];



    protected $casts = [
        'tanggal' => 'date',
        'giling_kotor' => 'decimal:2',
        'ongkos' => 'decimal:2',
        'beras_bersih' => 'decimal:2',
        'beras_pulang' => 'decimal:2',
        'jual' => 'decimal:2',
        'jual_kotor' => 'decimal:2',
        'global_beras' => 'decimal:2',
        'harga' => 'decimal:2',
        'status' => 'boolean',
    ];

    public $timestamps = true;

    // Relationship
    public function petani()
    {
        return $this->belongsTo(Petani::class, 'petani_id', 'id');
    }
}
