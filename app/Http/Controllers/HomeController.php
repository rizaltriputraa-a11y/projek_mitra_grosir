<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $promoProducts = Product::with('category')->promo()->take(4)->get();
        $latestProducts = Product::with('category')->latest()->take(8)->get();
        $categories = Category::withCount('products')
            ->orderBy('name')
            ->get();

        return view('home', compact('promoProducts', 'latestProducts', 'categories'));
    }
}