<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Nomor pesanan harus unik
            $table->string('order_number')->unique();

            // Relasi pelanggan
            // Pesanan tidak ikut terhapus jika akun pelanggan dihapus
            $table->foreignId('user_id')
                ->constrained()
                ->restrictOnDelete();

            $table->string('customer_name');
            $table->string('whatsapp');
            $table->text('address');
            $table->text('notes')->nullable();

            // Total transaksi
            $table->decimal('total', 12, 2);

            // Status pembayaran
            $table->enum('payment_status', [
                'belum_dibayar',
                'menunggu_verifikasi',
                'sudah_dibayar',
                'ditolak',
                'dibatalkan'
            ])->default('belum_dibayar');

            // Status pesanan
            $table->enum('order_status', [
                'menunggu_konfirmasi',
                'dikonfirmasi',
                'menunggu_pembayaran',
                'sudah_dibayar',
                'diproses',
                'selesai',
                'dibatalkan'
            ])->default('menunggu_konfirmasi');

            

            $table->timestamps();

            // Index untuk membantu pencarian laporan penjualan
            $table->index(['payment_status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
