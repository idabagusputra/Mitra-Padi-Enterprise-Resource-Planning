<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenjualanKongaMenir extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'penjualan_konga_menir';

    protected $fillable = [
        'keterangan',
        'tanggal',
        'jumlah_konga',
        'jumlah_menir',
        'harga',
    ];


    protected $casts = [
        'tanggal' => 'date',
        'jumlah_konga' => 'decimal:2',
        'jumlah_menir' => 'decimal:2',
        'harga' => 'decimal:2',
    ];


    public $timestamps = true;
}
