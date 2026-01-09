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
        'status', 
        'filter_oli'
    ];

    protected $casts = [
        'tanggal_servis' => 'date',
        'filter_oli' => 'boolean'  // Tambahan cast untuk filter_oli
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

    // Scope untuk mobil dengan filter oli yang sudah diganti
    public function scopeFilterOliSudahGanti($query)
    {
        return $query->where('filter_oli', true);
    }

    // Scope untuk mobil dengan filter oli yang belum diganti
    public function scopeFilterOliBelumGanti($query)
    {
        return $query->where('filter_oli', false);
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

    // Accessor untuk status filter oli dalam format readable
    public function getFilterOliStatusAttribute()
    {
        return $this->filter_oli ? 'Sudah Ganti' : 'Belum Ganti';
    }

    // Method untuk cek apakah mobil perlu ganti filter oli (contoh: setiap 10.000 km)
    public function isFilterOliPerluGanti($kmTerakhirGanti = 0, $intervalKm = 10000)
    {
        return ($this->kilometer - $kmTerakhirGanti) >= $intervalKm;
    }
}