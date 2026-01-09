<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenjualanBeras extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'penjualan_beras';

    protected $fillable = [
        'keterangan',
        'tanggal',
        'jumlah_beras',
        'harga',
    ];


    protected $casts = [
        'tanggal' => 'date',
        'jumlah_beras' => 'decimal:2',
        'harga' => 'decimal:2',
    ];


    public $timestamps = true;
}
