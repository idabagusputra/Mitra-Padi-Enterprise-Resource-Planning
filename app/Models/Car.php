<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_mobil',
        'tanggal_servis',
        'kilometer',
        'status'
    ];

    protected $casts = [
        'tanggal_servis' => 'date'
    ];

    // Scope untuk mobil yang belum servis
    public function scopeBelumServis($query)
    {
        return $query->where('status', 'belum_servis');
    }

    // Scope untuk mobil yang sudah servis
    public function scopeSudahServis($query)
    {
        return $query->where('status', 'sudah_servis');
    }

    // Accessor untuk format tanggal Indonesia
    public function getTanggalServisFormattedAttribute()
    {
        return $this->tanggal_servis->format('d/m/Y');
    }

    // Method untuk menghitung hari sejak servis terakhir
    public function getHariSejakServisAttribute()
    {
        return $this->tanggal_servis->diffInDays(Carbon::now());
    }
}
