<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jenis extends Model
{
    use HasFactory;

    protected $fillable = ['nama'];

    public function products()
    {
        return $this->hasMany(Product::class, 'id_jenis');
    }
}
