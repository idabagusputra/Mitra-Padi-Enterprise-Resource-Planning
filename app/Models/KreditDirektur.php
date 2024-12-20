<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KreditDirektur extends Model
{
    use HasFactory, SoftDeletes;


    protected $table = 'kredit_direkturs';
    protected $fillable = [
        'debit_id',
        'nama',
        'tanggal',
        'jumlah',
        'keterangan',
        'status',
    ];
}
