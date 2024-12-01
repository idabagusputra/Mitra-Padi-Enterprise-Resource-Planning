<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RekapKredit extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'rekap_kredit';

    protected $fillable = [
        'rekapan_kredit',
    ];
}
