<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->paginate(15)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'price' => 'required|numeric|min:0',
            'promo_price' => 'nullable|numeric|min:0|lt:price',
            'stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'quantity_per_carton' => 'required|integer|min:1',
            'is_promo' => 'nullable|boolean',
            'promo_start' => 'nullable|date',
            'promo_end' => 'nullable|date|after_or_equal:promo_start',
        ]);

        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(8);
        $data['is_promo'] = $request->boolean('is_promo');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')
                ->store('products', 'public');
        }

        $product = Product::create($data);

        ActivityLog::log(
            'product_created',
            "Produk '{$product->name}' ditambahkan.",
            $product
        );

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'price' => 'required|numeric|min:0',
            'promo_price' => 'nullable|numeric|min:0|lt:price',
            'stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'quantity_per_carton' => 'required|integer|min:1',
            'is_promo' => 'nullable|boolean',
            'promo_start' => 'nullable|date',
            'promo_end' => 'nullable|date|after_or_equal:promo_start',
        ]);

        $data['is_promo'] = $request->boolean('is_promo');

        $oldImage = $product->image;
        $newImage = null;

        if ($request->hasFile('image')) {
            $newImage = $request->file('image')
                ->store('products', 'public');

            $data['image'] = $newImage;
        }

        try {
            $product->update($data);

            if ($newImage && $oldImage) {
                Storage::disk('public')->delete($oldImage);
            }

            ActivityLog::log(
                'product_updated',
                "Produk '{$product->name}' diperbarui.",
                $product
            );

        } catch (\Throwable $e) {
            if ($newImage) {
                Storage::disk('public')->delete($newImage);
            }

            report($e);

            return back()
                ->with('error', 'Produk gagal diperbarui.')
                ->withInput();
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->orderItems()->exists()) {
            return back()->with(
                'error',
                'Produk tidak dapat dihapus karena sudah memiliki riwayat transaksi.'
            );
        }

        if ($product->goodsReceipts()->exists()) {
            return back()->with(
                'error',
                'Produk tidak dapat dihapus karena memiliki riwayat barang masuk.'
            );
        }

        ActivityLog::log(
            'product_deleted',
            "Produk '{$product->name}' dihapus.",
            $product
        );

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
