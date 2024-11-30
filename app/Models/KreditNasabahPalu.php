<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KreditNasabahPalu extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kredit_nasabah_palus';
    protected $dates = ['tanggal'];


    protected $fillable = ['nama', 'tanggal', 'keterangan', 'jumlah', 'status'];

    protected $casts = [
        'status' => 'boolean',
        'jumlah' => 'decimal:2',
    ];


    public $timestamps = true;



    // Method untuk menghitung total hutang_plus_bunga untuk kredit yang belum lunas
    public static function calculateTotalKreditNasabahPalu()
    {
        // Mengambil data kredit yang belum lunas (status = 0)
        $kreditsBelumLunas = self::where('status', 0)->get(); // Mengambil kredit yang statusnya 0 (belum lunas)

        // Menghitung total hutang_plus_bunga
        return $kreditsBelumLunas->sum('jumlah');
    }
}
