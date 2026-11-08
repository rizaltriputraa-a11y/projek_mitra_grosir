<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user');

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                    ->orWhere('customer_name', 'like', '%' . $request->search . '%')
                    ->orWhere('whatsapp', 'like', '%' . $request->search . '%');
            });
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'user']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'order_status' => 'required|in:menunggu_konfirmasi,dikonfirmasi,menunggu_pembayaran,sudah_dibayar,diproses,selesai,dibatalkan',
        ]);

        $newStatus = $request->order_status;

        try {
            DB::transaction(function () use ($order, $newStatus) {

                $order = Order::whereKey($order->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $oldStatus = $order->order_status;

                if ($oldStatus === $newStatus) {
                    return;
                }

                if ($oldStatus === 'dibatalkan') {
                    throw ValidationException::withMessages([
                        'order_status' => 'Pesanan yang sudah dibatalkan tidak dapat diaktifkan kembali.',
                    ]);
                }

                if ($oldStatus === 'selesai' && $newStatus === 'dibatalkan') {
                    throw ValidationException::withMessages([
                        'order_status' => 'Pesanan yang sudah selesai tidak dapat dibatalkan.',
                    ]);
                }

                // Jika pesanan dibatalkan, kembalikan stok.
                if ($newStatus === 'dibatalkan') {

                    if ($order->stock_deducted_at !== null) {

                        $items = $order->items()
                            ->orderBy('product_id')
                            ->get();

                        foreach ($items as $item) {

                            if (!$item->product_id) {
                                continue;
                            }

                            $product = Product::whereKey($item->product_id)
                                ->lockForUpdate()
                                ->first();

                            if ($product) {
                                $product->increment('stock', $item->quantity);
                            }
                        }

                        // Tandai stok sudah dikembalikan.
                        $order->stock_deducted_at = null;
                    }
                }

                // Sinkronisasi status pembayaran.
                if ($newStatus === 'sudah_dibayar') {
                    $order->payment_status = 'sudah_dibayar';
                }

                if ($newStatus === 'dibatalkan') {
                    $order->payment_status = 'dibatalkan';
                }

                $order->order_status = $newStatus;
                $order->save();

                ActivityLog::log(
                    'order_status_updated',
                    "Status {$order->order_number} diubah dari {$oldStatus} ke {$newStatus}.",
                    $order
                );
            });

            return back()->with(
                'success',
                'Status pesanan berhasil diperbarui.'
            );

        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            return back()->with(
                'error',
                'Status pesanan gagal diperbarui. Silakan coba kembali.'
            );
        }
    }
}
