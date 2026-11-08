<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'whatsapp',
        'address',
        'notes',
        'total',
        'payment_status',
        'order_status',
        'stock_deducted_at',
    ];

    protected $casts = [
        'stock_deducted_at' => 'datetime',
        'total' => 'decimal:2',
    ];

    // Relasi ke pelanggan
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke detail pesanan
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relasi ke pembayaran
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // Membuat nomor pesanan
    // Format: MGS-YYYYMMDD-XXXX
    public static function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $prefix = 'MGS-' . $date . '-';

        $last = self::where('order_number', 'like', $prefix . '%')
            ->orderByDesc('order_number')
            ->first();

        $number = $last
            ? ((int) substr($last->order_number, -4)) + 1
            : 1;

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
