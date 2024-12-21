<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RekapUtangKeOperator extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'rekap_utang_ke_operator';

    protected $fillable = [
        'rekapan_utang_ke_operator',
    ];
}
