<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\WhatsAppService;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show($orderNumber)
    {
        $order = Order::with(['items', 'payment'])
            ->where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }

    public function redirectWhatsApp($orderNumber, WhatsAppService $wa)
    {
        $order = Order::with('items')
            ->where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $message = $order->items->count() === 1
            ? $wa->pesanSatuProduk($order)
            : $wa->pesanMultiProduk($order);

        return redirect()->away($wa->link($message));
    }
}