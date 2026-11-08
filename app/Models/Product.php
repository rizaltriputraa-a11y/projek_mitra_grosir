<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'image',
        'price',
        'promo_price',
        'stock',
        'unit',
        'quantity_per_carton',
        'is_promo',
        'promo_start',
        'promo_end',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'promo_price' => 'decimal:2',
        'stock' => 'integer',
        'quantity_per_carton' => 'integer',
        'is_promo' => 'boolean',
        'promo_start' => 'date',
        'promo_end' => 'date',
    ];

    // Membuat slug otomatis ketika produk ditambahkan
    protected static function booted(): void
    {
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name)
                    . '-' . Str::random(8);
            }
        });
    }

    // Relasi kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi detail pesanan
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relasi barang masuk
    public function goodsReceipts()
    {
        return $this->hasMany(GoodsReceipt::class);
    }

    // Mengecek apakah promo sedang aktif
    public function isActivePromo(): bool
    {
        if (!$this->is_promo || $this->promo_price === null) {
            return false;
        }

        $today = Carbon::today();

        if ($this->promo_start && $today->lt($this->promo_start)) {
            return false;
        }

        if ($this->promo_end && $today->gt($this->promo_end)) {
            return false;
        }

        return true;
    }

    // Mengambil harga akhir produk
    public function finalPrice(): float
    {
        if ($this->isActivePromo()) {
            return (float) $this->promo_price;
        }

        return (float) $this->price;
    }

    // Mengecek ketersediaan stok
    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    // Menampilkan produk yang sedang promo
    public function scopePromo($query)
    {
        return $query->where('is_promo', true)
            ->whereNotNull('promo_price')
            ->where(function ($q) {
                $q->whereNull('promo_start')
                    ->orWhereDate('promo_start', '<=', Carbon::today());
            })
            ->where(function ($q) {
                $q->whereNull('promo_end')
                    ->orWhereDate('promo_end', '>=', Carbon::today());
            });
    }
}
