<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    // Membuat slug otomatis ketika kategori ditambahkan
    protected static function booted(): void
    {
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name)
                    . '-' . Str::random(8);
            }
        });
    }

    // Relasi kategori dengan produk
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
