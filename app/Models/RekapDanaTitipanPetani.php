<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RekapDanaTitipanPetani extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'rekap_dana_titipan_petani';

    protected $fillable = [
        'rekapan_dana_titipan_petani',
    ];
}
