<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsReceipt extends Model
{
    protected $fillable = [
        'product_id', 'supplier', 'quantity', 'purchase_price', 'received_date'
    ];

    protected $casts = [
        'received_date' => 'date',
        'purchase_price' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}