@extends('layouts.app')
@section('title', 'Mitra Grosir Surabaya - Belanja Minuman Grosir')

@section('content')
<section class="hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="hero-title">MITRA GROSIR SURABAYA</h1>
                <p class="hero-subtitle">Belanja Minuman Grosir Lebih Mudah dan Terpercaya</p>
                <p class="hero-desc">Berbagai pilihan minuman dengan harga grosir per karton.</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg px-4">
                        <i class="bi bi-bag"></i> Belanja Sekarang
                    </a>
                    <a href="{{ route('products.promo') }}" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-tag"></i> Lihat Promo
                    </a>
                </div>
            </div>
            <div class="col-lg-5 text-center">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="hero-logo img-fluid">
            </div>
        </div>
    </div>
</section>

{{-- Kategori --}}
<section class="py-5">
    <div class="container">
        <h2 class="section-title">Kategori Produk</h2>
        <div class="row g-3">
            @foreach($categories as $cat)
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('products.index', ['category' => $cat->id]) }}" class="category-card">
                        <i class="bi bi-cup-straw fs-1"></i>
                        <h6 class="mt-2 mb-0">{{ $cat->name }}</h6>
                        <small class="text-muted">{{ $cat->products_count }} produk</small>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Promo --}}
@if($promoProducts->count() > 0)
<section class="py-5 bg-light-blue">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title mb-0">PROMO TERBARU</h2>
            <a href="{{ route('products.promo') }}" class="btn btn-outline-primary btn-sm">Lihat Semua</a>
        </div>
        <div class="row">
            @foreach($promoProducts as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Produk Terbaru --}}
<section class="py-5">
    <div class="container">
        <h2 class="section-title">Produk Terbaru</h2>
        <div class="row">
            @forelse($latestProducts as $product)
                <x-product-card :product="$product" />
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-box-seam fs-1 text-muted"></i>
                    <p class="mt-2">Tidak ada produk yang tersedia.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- Keunggulan --}}
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="section-title text-center">Keunggulan Mitra Grosir</h2>
        <div class="row g-4 mt-2">
            @php
                $features = [
                    ['Harga Grosir', 'Harga khusus untuk pembelian per karton.', 'bi-tags'],
                    ['Produk Beragam', 'Berbagai merek minuman tersedia.', 'bi-grid'],
                    ['Pemesanan Mudah', 'Checkout cepat dan mudah.', 'bi-cart-check'],
                    ['Transaksi Terstruktur', 'Invoice dan verifikasi jelas.', 'bi-shield-check'],
                    ['Informasi Jelas', 'Detail produk lengkap.', 'bi-info-circle'],
                ];
            @endphp
            @foreach($features as $f)
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <i class="bi {{ $f[2] }} fs-2 text-primary"></i>
                        <h6 class="mt-3">{{ $f[0] }}</h6>
                        <p class="text-muted mb-0 small">{{ $f[1] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Cara Belanja --}}
<section class="py-5">
    <div class="container">
        <h2 class="section-title text-center">Cara Belanja</h2>
        <div class="row g-3 mt-3">
            @php
                $steps = [
                    'Pilih Produk', 'Masukkan ke Keranjang', 'Checkout',
                    'Bayar melalui QRIS', 'Upload Bukti Pembayaran',
                    'Pesanan Diproses', 'Pesanan Dikirim'
                ];
            @endphp
            @foreach($steps as $i => $step)
                <div class="col-6 col-md-3 col-lg">
                    <div class="step-card text-center">
                        <div class="step-number">{{ $i + 1 }}</div>
                        <p class="small mb-0">{{ $step }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA WhatsApp --}}
<section class="py-5 bg-primary text-white text-center">
    <div class="container">
        <h3>Butuh Bantuan?</h3>
        <p>Hubungi kami langsung melalui WhatsApp</p>
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('whatsapp_number', '6285174461840')) }}"
           target="_blank" class="btn btn-light btn-lg">
            <i class="bi bi-whatsapp text-success"></i> Chat Sekarang
        </a>
    </div>
</section>
@endsection