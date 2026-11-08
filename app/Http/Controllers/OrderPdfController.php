<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class OrderPdfController extends Controller
{
    public function download($orderNumber)
    {
        $user = Auth::user();

        if (!$user) {
            abort(401, 'Silakan login terlebih dahulu.');
        }

        $order = Order::with('items.product')
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        // Admin boleh mengunduh semua nota
        // Pelanggan hanya boleh mengunduh nota miliknya
        if (!$user->isAdmin() && $order->user_id != $user->id) {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
        }

        $settings = Setting::pluck('value', 'key')->toArray();

        $pdf = Pdf::loadView('orders.pdf.nota', [
            'order' => $order,
            'settings' => $settings,
        ])->setPaper('a4', 'portrait');

        $filename = 'Nota-' . $order->order_number . '.pdf';

        return $pdf->download($filename);
    }
}
