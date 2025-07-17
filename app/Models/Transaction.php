<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['id_customer', 'status', 'total_harga','bukti_pembayaran'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }

    public function detailTransactions()
    {
        return $this->hasMany(DetailTransaction::class, 'id_order');
    }
}
