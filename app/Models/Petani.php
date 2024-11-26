<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Petani extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['nama', 'alamat', 'no_telepon'];
    public $timestamps = true;

    public function kredits()
    {
        return $this->hasMany(Kredit::class, 'petani_id');
    }

    public function debit()
    {
        return $this->hasMany(Debit::class, 'petani_id');
    }

    public function giling()
    {
        return $this->hasMany(Giling::class, 'petani_id');
    }

    public function getTotalHutangAttribute()
    {
        $totalHutang = $this->kredits()->where('status', false)->sum('jumlah');
        $totalBunga = $this->giling->sum(function ($giling) {
            return $giling->kalkulasiBunga(config('app.bunga_default', 0));
        });
        return $totalHutang + $totalBunga;
    }
}
