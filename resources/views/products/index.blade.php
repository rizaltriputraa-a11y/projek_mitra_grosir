@extends('layouts.app')
@section('title', 'Produk - Mitra Grosir Surabaya')

@section('content')
<div class="container py-4">
    <h1 class="section-title">Katalog Produk</h1>

    <form method="GET" class="filter-box mb-4">
        <div class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control"
                       placeholder="Cari produk..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="sort" class="form-select">
                    <option value="latest" @selected(request('sort') == 'latest')>Terbaru</option>
                    <option value="price_low" @selected(request('sort') == 'price_low')>Harga Termurah</option>
                    <option value="price_high" @selected(request('sort') == 'price_high')>Harga Tertinggi</option>
                    <option value="name" @selected(request('sort') == 'name')>Nama A-Z</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" name="promo" value="1"
                           id="promoFilter" @checked(request('promo'))>
                    <label class="form-check-label" for="promoFilter">Promo</label>
                </div>
                <button class="btn btn-primary flex-grow-1">
                    <i class="bi bi-search"></i> Filter
                </button>
            </div>
        </div>
    </form>

    <div class="row">
        @forelse($products as $product)
            <x-product-card :product="$product" />
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-search fs-1 text-muted"></i>
                <p class="mt-3">Tidak ada produk yang cocok dengan pencarian.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">Reset Filter</a>
            </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $products->links() }}</div>
</div>
@endsection