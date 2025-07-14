<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_jenis',
        'id_merk',
        'nama',
        'status',
        'harga_beli',
        'harga_jual',
        'qty',
        'foto',
    ];

    public function jenis()
    {
        return $this->belongsTo(Jenis::class, 'id_jenis');
    }

    public function merk()
    {
        return $this->belongsTo(Merk::class, 'id_merk');
    }
}
