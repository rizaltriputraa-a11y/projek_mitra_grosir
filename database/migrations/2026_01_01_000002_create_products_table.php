<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Relasi kategori, kategori tidak bisa dihapus jika masih digunakan
            $table->foreignId('category_id')
                ->constrained()
                ->restrictOnDelete();

            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();

            // Harga produk
            $table->decimal('price', 12, 2);
            $table->decimal('promo_price', 12, 2)->nullable();

            // Stok tidak boleh negatif
            $table->unsignedInteger('stock')->default(0);

            $table->string('unit')->default('karton');
            $table->unsignedInteger('quantity_per_carton')->default(24);

            // Informasi promo
            $table->boolean('is_promo')->default(false);
            $table->date('promo_start')->nullable();
            $table->date('promo_end')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
