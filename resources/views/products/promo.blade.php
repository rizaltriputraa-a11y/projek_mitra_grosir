@extends('layouts.app')
@section('title', 'Promo - Mitra Grosir Surabaya')

@section('content')
<div class="container py-4">
    <h1 class="section-title"><i class="bi bi-tag-fill text-danger"></i> PROMO</h1>
    <p class="text-muted">Daftar produk yang sedang promo</p>

    <div class="row">
        @forelse($products as $product)
            <x-product-card :product="$product" />
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-tag fs-1 text-muted"></i>
                <p class="mt-3">Saat ini belum ada promo.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">Mulai Belanja</a>
            </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $products->links() }}</div>
</div>
@endsection