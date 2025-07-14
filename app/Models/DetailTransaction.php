<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaction extends Model
{
    protected $table = 'detail_transactions';

    protected $fillable = ['id_order', 'id_product', 'qty', 'total_harga', 'catatan'];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'id_order');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product');
    }
}
