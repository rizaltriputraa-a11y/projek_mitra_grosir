<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),

            'promo_products' => Product::promo()->count(),

            'total_categories' => Category::count(),

            'total_orders' => Order::count(),

            'new_orders' => Order::where(
                'order_status',
                'menunggu_konfirmasi'
            )->count(),

            'paid_orders' => Order::where(
                'payment_status',
                'sudah_dibayar'
            )
                ->where('order_status', '!=', 'dibatalkan')
                ->count(),

            'completed_orders' => Order::where(
                'order_status',
                'selesai'
            )->count(),

            'total_customers' => User::where(
                'role',
                'user'
            )->count(),
        ];

        $recentOrders = Order::with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentOrders'
        ));
    }
}
