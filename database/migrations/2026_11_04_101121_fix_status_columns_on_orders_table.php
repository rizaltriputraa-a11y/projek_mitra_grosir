<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE orders MODIFY payment_status VARCHAR(50) NOT NULL DEFAULT 'belum_dibayar'");
            DB::statement("ALTER TABLE orders MODIFY order_status VARCHAR(50) NOT NULL DEFAULT 'menunggu_konfirmasi'");
        }
    }

    public function down(): void
    {
        // Tidak ada perubahan rollback.
    }
};