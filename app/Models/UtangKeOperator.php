<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UtangKeOperator extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'utang_ke_operators';
    protected $dates = ['tanggal'];


    protected $fillable = ['petani_id', 'nama', 'tanggal', 'keterangan', 'jumlah', 'status', 'debit_id', 'updated_at'];

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

    // Method untuk menghitung total hutang_plus_bunga untuk kredit yang belum lunas
    public static function calculateTotalUtangKeOperator()
    {
        // Mengambil data kredit yang belum lunas (status = 0)
        $kreditsBelumLunas = self::where('status', 0)->get(); // Mengambil kredit yang statusnya 0 (belum lunas)

        // Menghitung total hutang_plus_bunga
        return $kreditsBelumLunas->sum('jumlah');
    }
}
