<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        foreach ($cart as $id => $item) {
            $product = Product::find($id);

            if (!$product || !$product->isInStock()) {
                unset($cart[$id]);
                continue;
            }

            // Sesuaikan jumlah dengan stok terbaru
            $quantity = min($item['quantity'], $product->stock);

            $cart[$id] = [
                'name' => $product->name,
                'price' => $product->finalPrice(),
                'quantity' => $quantity,
                'image' => $product->image,
                'slug' => $product->slug,
                'stock' => $product->stock,
            ];
        }

        session()->put('cart', $cart);

        return view('cart.index', compact('cart'));
    }

    public function add(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'sometimes|required|integer|min:1',
        ]);

        $product = Product::findOrFail($id);

        if (!$product->isInStock()) {
            return back()->with('error', 'Produk sedang tidak tersedia.');
        }

        $qty = (int) $request->input('quantity', 1);

        $cart = session()->get('cart', []);

        $newQty = ($cart[$id]['quantity'] ?? 0) + $qty;

        if ($newQty > $product->stock) {
            return back()->with('error', 'Stok produk tidak mencukupi.');
        }

        $cart[$id] = [
            'name' => $product->name,
            'price' => $product->finalPrice(),
            'quantity' => $newQty,
            'image' => $product->image,
            'slug' => $product->slug,
            'stock' => $product->stock,
        ];

        session()->put('cart', $cart);

        return back()->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (!isset($cart[$id])) {
            return back();
        }

        $product = Product::find($id);

        if (!$product || !$product->isInStock()) {
            unset($cart[$id]);
            session()->put('cart', $cart);

            return back()->with('error', 'Produk sudah tidak tersedia.');
        }

        $qty = (int) $request->input('quantity');

        if ($qty > $product->stock) {
            return back()->with('error', 'Stok produk tidak mencukupi.');
        }

        $cart[$id] = [
            'name' => $product->name,
            'price' => $product->finalPrice(),
            'quantity' => $qty,
            'image' => $product->image,
            'slug' => $product->slug,
            'stock' => $product->stock,
        ];

        session()->put('cart', $cart);

        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);

        unset($cart[$id]);

        session()->put('cart', $cart);

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    public function clear()
    {
        session()->forget('cart');

        return back()->with('success', 'Keranjang dikosongkan.');
    }
}