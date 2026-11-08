<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    /**
     * Halaman ringkasan pesanan dari keranjang.
     */
    public function review(WhatsAppService $wa)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('products.index')
                ->with('error', 'Keranjang Anda masih kosong.');
        }

        $items = [];
        $total = 0;
        $totalQty = 0;

        foreach ($cart as $productId => $row) {
            $product = Product::find($productId);

            if (!$product) {
                continue;
            }

            $qty = (int) ($row['quantity'] ?? 0);

            if ($qty < 1) {
                continue;
            }

            $price = $product->finalPrice();
            $subtotal = $price * $qty;

            $items[] = [
                'product' => $product,
                'price' => $price,
                'quantity' => $qty,
                'subtotal' => $subtotal,
            ];

            $total += $subtotal;
            $totalQty += $qty;
        }

        if (empty($items)) {
            return redirect()->route('products.index')
                ->with('error', 'Produk dalam keranjang tidak tersedia.');
        }

        return view('orders.ringkasan', [
            'items' => $items,
            'total' => $total,
            'totalQty' => $totalQty,
            'user' => auth()->user(),
            'waNumber' => $wa->getNumber(),
        ]);
    }

    /**
     * Membuat pesanan dari keranjang.
     * Stok langsung dikurangi ketika pesanan berhasil dibuat.
     */
    public function store(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('products.index')
                ->with('error', 'Keranjang Anda masih kosong.');
        }

        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:20',
            'address' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        try {
            $order = DB::transaction(function () use ($cart, $data) {

                $items = [];
                $total = 0;

                // Urutkan ID produk agar penguncian lebih konsisten.
                ksort($cart);

                foreach ($cart as $productId => $row) {

                    $qty = filter_var(
                        $row['quantity'] ?? null,
                        FILTER_VALIDATE_INT
                    );

                    if ($qty === false || $qty < 1) {
                        throw ValidationException::withMessages([
                            'cart' => 'Jumlah produk dalam keranjang tidak valid.',
                        ]);
                    }

                    // Kunci produk selama transaksi berlangsung.
                    $product = Product::whereKey($productId)
                        ->lockForUpdate()
                        ->first();

                    if (!$product) {
                        throw ValidationException::withMessages([
                            'cart' => 'Salah satu produk tidak ditemukan.',
                        ]);
                    }

                    if ($product->stock < $qty) {
                        throw ValidationException::withMessages([
                            'cart' => "Stok {$product->name} tidak mencukupi. Stok tersedia: {$product->stock}.",
                        ]);
                    }

                    $price = $product->finalPrice();
                    $subtotal = $price * $qty;

                    $total += $subtotal;

                    $items[] = [
                        'product' => $product,
                        'quantity' => $qty,
                        'price' => $price,
                        'subtotal' => $subtotal,
                    ];
                }

                if (empty($items)) {
                    throw ValidationException::withMessages([
                        'cart' => 'Tidak ada produk yang dapat dipesan.',
                    ]);
                }

                // Buat pesanan.
                $order = Order::create([
                    'order_number' => Order::generateOrderNumber(),
                    'user_id' => auth()->id(),
                    'customer_name' => $data['customer_name'],
                    'whatsapp' => $data['whatsapp'],
                    'address' => $data['address'],
                    'notes' => $data['notes'] ?? null,
                    'total' => $total,
                    'payment_status' => 'belum_dibayar',
                    'order_status' => 'menunggu_konfirmasi',
                    'stock_deducted_at' => now(),
                ]);

                // Simpan detail pesanan dan kurangi stok.
                foreach ($items as $item) {

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product']->id,
                        'product_name' => $item['product']->name,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['subtotal'],
                    ]);

                    $item['product']->decrement(
                        'stock',
                        $item['quantity']
                    );
                }

                ActivityLog::log(
                    'order_created',
                    "Order {$order->order_number} dibuat oleh " . auth()->user()->name,
                    $order
                );

                return $order;
            });

            // Keranjang dikosongkan setelah transaksi berhasil.
            session()->forget('cart');

            return redirect()->route('orders.show', $order->order_number)
                ->with('success', 'Pesanan berhasil dibuat. Stok produk telah diperbarui. Silakan unduh nota dan lanjut ke WhatsApp.');

        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            return back()->with(
                'error',
                'Pesanan gagal dibuat. Silakan coba kembali.'
            )->withInput();
        }
    }

    /**
     * Membeli satu produk secara langsung.
     */
    public function buyNow(Request $request, $id, WhatsAppService $wa)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $qty = (int) $data['quantity'];

        try {
            $order = DB::transaction(function () use ($id, $qty) {

                // Kunci produk sebelum memeriksa stok.
                $product = Product::whereKey($id)
                    ->lockForUpdate()
                    ->first();

                if (!$product) {
                    throw ValidationException::withMessages([
                        'product' => 'Produk tidak ditemukan.',
                    ]);
                }

                if ($product->stock < $qty) {
                    throw ValidationException::withMessages([
                        'quantity' => 'Stok produk tidak mencukupi. Stok tersedia: ' . $product->stock,
                    ]);
                }

                $user = auth()->user();
                $price = $product->finalPrice();
                $subtotal = $price * $qty;

                $order = Order::create([
                    'order_number' => Order::generateOrderNumber(),
                    'user_id' => $user->id,
                    'customer_name' => $user->name,
                    'whatsapp' => $user->whatsapp ?? '-',
                    'address' => $user->address ?? '-',
                    'notes' => null,
                    'total' => $subtotal,
                    'payment_status' => 'belum_dibayar',
                    'order_status' => 'menunggu_konfirmasi',
                    'stock_deducted_at' => now(),
                ]);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $qty,
                    'price' => $price,
                    'subtotal' => $subtotal,
                ]);

                // Kurangi stok setelah pesanan berhasil disimpan.
                $product->decrement('stock', $qty);

                ActivityLog::log(
                    'order_created',
                    "Order {$order->order_number} (beli langsung) oleh {$user->name}",
                    $order
                );

                return $order;
            });

            // Arahkan pelanggan ke WhatsApp setelah transaksi berhasil.
            $message = $wa->pesanSatuProduk($order->fresh('items'));

            return redirect()->away($wa->link($message));

        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            return back()->with(
                'error',
                'Pesanan gagal dibuat. Silakan coba kembali.'
            );
        }
    }
}