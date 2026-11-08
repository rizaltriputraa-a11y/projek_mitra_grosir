@props(['product'])

<div class="col-6 col-md-4 col-lg-3 mb-4">
    <div class="product-card h-100">
        <div class="product-image-wrap">
            @if($product->isActivePromo())
                <span class="badge-promo">PROMO</span>
            @endif
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
            @else
                <div class="placeholder-img"><i class="bi bi-image"></i></div>
            @endif
        </div>
        <div class="product-body">
            <small class="text-muted">{{ $product->category->name ?? '-' }}</small>
            <h6 class="product-name">{{ $product->name }}</h6>

            @if($product->isActivePromo())
                <div class="price-wrap">
                    <span class="price-old">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    <span class="price-new">Rp {{ number_format($product->promo_price, 0, ',', '.') }}</span>
                </div>
            @else
                <div class="price-wrap">
                    <span class="price-normal">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                </div>
            @endif

            <div class="small text-muted">/ {{ $product->unit }}</div>
            <div class="small mb-2">
                Stok:
                @if($product->stock > 0)
                    <span class="text-success fw-bold">{{ $product->stock }}</span>
                @else
                    <span class="text-danger fw-bold">Habis</span>
                @endif
            </div>

            <div class="d-grid gap-1">
                <a href="{{ route('products.show', $product->slug) }}" class="btn btn-sm btn-outline-primary">
                    Lihat Detail
                </a>
                @if($product->stock > 0)
                    <form method="POST" action="{{ route('cart.add', $product->id) }}">
                        @csrf
                        <input type="hidden" name="quantity" value="1">
                        <button class="btn btn-sm btn-primary w-100">
                            <i class="bi bi-cart-plus"></i> Keranjang
                        </button>
                    </form>
                @else
                    <button class="btn btn-sm btn-secondary" disabled>Stok Habis</button>
                @endif
            </div>
        </div>
    </div>
</div>