<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Karyawan extends Model
{
    use HasFactory;

    protected $fillable = ['id_user', 'nama', 'no_hp','alamat'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
