<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Merk extends Model
{
    use HasFactory;

    protected $fillable = ['nama'];

    protected $table = 'merks';

    public function products()
    {
        return $this->hasMany(Product::class, 'id_merk');
    }
}
