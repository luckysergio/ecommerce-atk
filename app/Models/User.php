<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['email', 'password', 'id_role'];

    protected $hidden = ['password'];

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }

    public function karyawan()
    {
        return $this->hasOne(Karyawan::class, 'id_user');
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id_user');
    }
}

