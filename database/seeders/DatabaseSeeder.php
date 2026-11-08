<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================
        // 1. ADMIN — pakai updateOrCreate agar tidak duplikat
        // ============================================
        User::updateOrCreate(
            ['email' => 'admin@mitragrosir.com'],
            [
                'name'     => 'Admin Mitra Grosir',
                'password' => Hash::make('password'), // ganti setelah login pertama
                'role'     => 'admin',
                'whatsapp' => '6285174461840',
                'address'  => 'Surabaya, Jawa Timur',
            ]
        );

        // ============================================
        // 2. CUSTOMER CONTOH
        // ============================================
        User::updateOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name'     => 'Customer Contoh',
                'password' => Hash::make('password'),
                'role'     => 'user',
                'whatsapp' => '6281234567891',
                'address'  => 'Jl. Contoh No. 1, Surabaya',
            ]
        );

        // ============================================
        // 3. KATEGORI
        // ============================================
        $categories = [
            ['name' => 'Air Mineral',       'description' => 'Berbagai merek air mineral'],
            ['name' => 'Teh',               'description' => 'Minuman teh kemasan'],
            ['name' => 'Minuman Bersoda',   'description' => 'Minuman bersoda berbagai merek'],
            ['name' => 'Minuman Isotonik',  'description' => 'Minuman pengganti ion tubuh'],
            ['name' => 'Jus',               'description' => 'Minuman jus kemasan'],
            ['name' => 'Kopi',              'description' => 'Minuman kopi kemasan'],
            ['name' => 'Minuman Lainnya',   'description' => 'Berbagai minuman lainnya'],
        ];

        foreach ($categories as $c) {
            Category::updateOrCreate(
                ['slug' => Str::slug($c['name'])],
                [
                    'name'        => $c['name'],
                    'description' => $c['description'],
                ]
            );
        }

        // ============================================
        // 4. PRODUK CONTOH
        // ============================================
        $products = [
            ['Aqua 600ml',              'Air Mineral',      50000, 45000, 100, 24],
            ['Le Minerale 600ml',       'Air Mineral',      52000, null,  80,  24],
            ['Teh Botol Sosro 350ml',   'Teh',              45000, 40000, 60,  24],
            ['Pocari Sweat 500ml',      'Minuman Isotonik', 75000, null,  40,  24],
            ['Coca Cola 390ml',         'Minuman Bersoda',  60000, 55000, 50,  24],
            ['Sprite 390ml',            'Minuman Bersoda',  58000, null,  45,  24],
            ['Fanta 390ml',             'Minuman Bersoda',  58000, null,  45,  24],
            ['Buavita Jus 250ml',       'Jus',              65000, 60000, 30,  24],
            ['Kopi Kapal Api Sachet',   'Kopi',             35000, null,  200, 50],
        ];

        foreach ($products as $p) {
            $cat = Category::where('name', $p[1])->first();
            if (!$cat) continue;

            Product::updateOrCreate(
                ['name' => $p[0]],
                [
                    'category_id'         => $cat->id,
                    'slug'                => Str::slug($p[0]) . '-' . uniqid(),
                    'description'         => 'Produk ' . $p[0] . ' dijual per karton.',
                    'price'               => $p[2],
                    'promo_price'         => $p[3],
                    'stock'               => $p[4],
                    'unit'                => 'karton',
                    'quantity_per_carton' => $p[5],
                    'is_promo'            => !is_null($p[3]),
                    'promo_start'         => !is_null($p[3]) ? now()->subDays(2) : null,
                    'promo_end'           => !is_null($p[3]) ? now()->addDays(14) : null,
                ]
            );
        }

        // ============================================
        // 5. SETTINGS DEFAULT — pakai set() hanya jika belum ada
        //    (jangan timpa setting yang sudah diubah admin)
        // ============================================
        $defaults = [
            'whatsapp_number'   => '6285174461840',
            'store_name'        => 'Mitra Grosir Surabaya',
            'store_address'     => 'Surabaya, Jawa Timur',
            'store_description' => 'Pusat penjualan berbagai macam minuman secara grosir.',
            'store_hours'       => 'Senin - Sabtu, 08.00 - 17.00',
        ];

        foreach ($defaults as $key => $value) {
            if (!Setting::where('key', $key)->exists()) {
                Setting::set($key, $value);
            }
        }
    }
}