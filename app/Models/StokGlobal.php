<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokGlobal extends Model
{
    protected $table = 'stok_global';

    protected $fillable = [
        'stok_beras',
        'stok_konga',
        'stok_menir',
    ];
}
