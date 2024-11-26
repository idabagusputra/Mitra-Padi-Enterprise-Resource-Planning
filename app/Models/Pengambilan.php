<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengambilan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['giling_id', 'keterangan', 'jumlah', 'harga'];

    public function giling()
    {
        return $this->belongsTo(Giling::class);
    }
}