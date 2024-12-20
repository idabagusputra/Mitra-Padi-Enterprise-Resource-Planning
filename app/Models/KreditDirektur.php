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
        'nama',
        'debit_id',
        'tanggal',
        'keterangan',
        'jumlah',
        'status',
    ];


    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
        'status' => 'boolean',
    ];


    public function debit()
    {
        return $this->belongsTo(Debit::class, 'debit_id');
    }
}
