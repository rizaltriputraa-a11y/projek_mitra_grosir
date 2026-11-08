@extends('layouts.app')
@section('title', $product->name)

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produk</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-md-5">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded shadow-sm" alt="{{ $product->name }}">
            @else
                <div class="placeholder-img-lg"><i class="bi bi-image"></i></div>
            @endif
        </div>
        <div class="col-md-7">
            <small class="text-muted">{{ $product->category->name }}</small>
            <h1 class="h3">{{ $product->name }}</h1>

            @if($product->isActivePromo())
                <div class="mb-3">
                    <span class="badge bg-danger">PROMO</span>
                    <div class="mt-2">
                        <span class="price-old fs-5">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        <span class="price-new fs-3">Rp {{ number_format($product->promo_price, 0, ',', '.') }}</span>
                        <small class="text-muted">/ {{ $product->unit }}</small>
                    </div>
                </div>
            @else
                <div class="mb-3">
                    <span class="price-new fs-3">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    <small class="text-muted">/ {{ $product->unit }}</small>
                </div>
            @endif

            <p><strong>Stok:</strong>
                @if($product->stock > 0)
                    <span class="text-success">{{ $product->stock }} {{ $product->unit }}</span>
                @else
                    <span class="text-danger">STOK HABIS</span>
                @endif
            </p>

            <p><strong>Isi per karton:</strong> {{ $product->quantity_per_carton }} pcs</p>

            <div class="mb-3">
                <h6>Deskripsi:</h6>
                <p>{!! nl2br(e($product->description ?? 'Tidak ada deskripsi.')) !!}</p>
            </div>

            @if($product->stock > 0)
                @auth
                    {{-- ============ USER LOGIN ============ --}}

                    {{-- Form Beli Sekarang via WhatsApp --}}
                    <form method="POST" action="{{ route('checkout.buyNow', $product->id) }}" class="row g-2 align-items-end mb-3">
                        @csrf
                        <div class="col-auto">
                            <label class="form-label">Jumlah</label>
                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                                   class="form-control" style="width: 100px">
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-success">
                                <i class="bi bi-whatsapp"></i> Beli Sekarang via WhatsApp
                            </button>
                        </div>
                    </form>

                    {{-- Form Tambah ke Keranjang --}}
                    <form method="POST" action="{{ route('cart.add', $product->id) }}" class="row g-2 align-items-end mb-3">
                        @csrf
                        <div class="col-auto">
                            <label class="form-label">Jumlah</label>
                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                                   class="form-control" style="width: 100px">
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-primary">
                                <i class="bi bi-cart-plus"></i> Tambah ke Keranjang
                            </button>
                        </div>
                    </form>

                @else
                    {{-- ============ GUEST ============ --}}
                    <div class="alert alert-info mb-3">
                        Silakan <a href="{{ route('login') }}" class="fw-bold">login</a> terlebih dahulu untuk melakukan pemesanan produk.
                    </div>

                    <button class="btn btn-secondary mb-2" disabled>
                        <i class="bi bi-cart-plus"></i> Tambah ke Keranjang
                    </button>
                    <button class="btn btn-success mb-2" disabled>
                        <i class="bi bi-whatsapp"></i> Beli Sekarang
                    </button>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary">
                        <i class="bi bi-box-arrow-in-right"></i> Login untuk Memesan
                    </a>
                @endauth
            @else
                <button class="btn btn-secondary" disabled>Stok Habis</button>
            @endif

            {{-- Tombol Chat via WhatsApp (semua orang boleh) --}}
            @php
                $wa = \App\Models\Setting::get('whatsapp_number', '6285174461840');
                $wa = preg_replace('/[^0-9]/', '', $wa);
                if (str_starts_with($wa, '0')) $wa = '62' . substr($wa, 1);
                $msg = "Halo Mitra Grosir Surabaya, saya ingin bertanya mengenai produk {$product->name}.";
            @endphp
            <a href="https://wa.me/{{ $wa }}?text={{ urlencode($msg) }}"
               target="_blank" class="btn btn-outline-success">
                <i class="bi bi-whatsapp"></i> Chat via WhatsApp
            </a>
        </div>
    </div>

    @if($related->count())
        <hr class="my-5">
        <h3 class="section-title">Produk Terkait</h3>
        <div class="row">
            @foreach($related as $p)
                <x-product-card :product="$p" />
            @endforeach
        </div>
    @endif
</div>
@endsection