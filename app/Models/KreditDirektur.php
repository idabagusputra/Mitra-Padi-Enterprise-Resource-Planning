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

    public static function calculateTotalKreditDirektur()
    {
        // Mengambil data kredit yang belum lunas (status = 0)
        $kreditsBelumLunas = self::where('status', 0)->get(); // Mengambil kredit yang statusnya 0 (belum lunas)

        // Menghitung total hutang_plus_bunga
        return $kreditsBelumLunas->sum('jumlah');
    }
}
