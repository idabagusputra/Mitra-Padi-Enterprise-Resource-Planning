<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kredit extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kredits';
    protected $dates = ['tanggal'];


    protected $fillable = ['petani_id', 'pKredit_id', 'tanggal', 'keterangan', 'jumlah', 'status'];

    protected $casts = [
        'status' => 'boolean',
        'jumlah' => 'decimal:2',
    ];


    public $timestamps = true;

    public function getKalkulasiBungaAttribute()
    {
        return $this->pembayaranKredit ? $this->pembayaranKredit->kalkulasiBunga($this->jumlah) : 0;
    }

    public function petani()
    {
        return $this->belongsTo(Petani::class, 'petani_id', 'id');
    }

    public function pembayaranKredit()
    {
        return $this->belongsToMany(PembayaranKredit::class);
    }
}
