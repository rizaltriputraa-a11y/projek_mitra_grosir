<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:100',
            'category' => 'nullable|integer|exists:categories,id',
            'sort' => 'nullable|in:latest,price_low,price_high,name',
        ]);

        $query = Product::with('category');

        // Pencarian produk
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter kategori
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter produk promo
        if ($request->filled('promo')) {
            $query->promo();
        }

        // Filter produk yang masih tersedia
        if ($request->filled('stock')) {
            $query->where('stock', '>', 0);
        }

        // Pengurutan produk
        $sort = $request->input('sort', 'latest');

        match ($sort) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'name' => $query->orderBy('name', 'asc'),
            default => $query->latest(),
        };

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function show(string $slug)
    {
        $product = Product::with('category')
            ->where('slug', $slug)
            ->firstOrFail();

        $related = Product::with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'related'));
    }

    public function promo()
    {
        $products = Product::with('category')
            ->promo()
            ->paginate(12);

        return view('products.promo', compact('products'));
    }
}
