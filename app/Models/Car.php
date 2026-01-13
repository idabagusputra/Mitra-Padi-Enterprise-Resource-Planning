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
        'filter_oli',
        'filter_solar',
        'keterangan'
    ];

    protected $casts = [
        'tanggal_servis' => 'date',
        'filter_oli' => 'boolean',
        'filter_solar' => 'boolean'
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

    // Scope untuk mobil dengan filter solar yang sudah diganti
    public function scopeFilterSolarSudahGanti($query)
    {
        return $query->where('filter_solar', true);
    }

    // Scope untuk mobil dengan filter solar yang belum diganti
    public function scopeFilterSolarBelumGanti($query)
    {
        return $query->where('filter_solar', false);
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

    // Accessor untuk status filter solar dalam format readable
    public function getFilterSolarStatusAttribute()
    {
        return $this->filter_solar ? 'Sudah Ganti' : 'Belum Ganti';
    }

    // Method untuk cek apakah mobil perlu ganti filter oli (contoh: setiap 10.000 km)
    public function isFilterOliPerluGanti($kmTerakhirGanti = 0, $intervalKm = 10000)
    {
        return ($this->kilometer - $kmTerakhirGanti) >= $intervalKm;
    }

    // Method untuk cek apakah mobil perlu ganti filter solar
    public function isFilterSolarPerluGanti($kmTerakhirGanti = 0, $intervalKm = 10000)
    {
        return ($this->kilometer - $kmTerakhirGanti) >= $intervalKm;
    }
}
