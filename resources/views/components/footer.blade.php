<footer class="bg-primary text-white mt-5 py-4">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h5 class="fw-bold">MITRA GROSIR SURABAYA</h5>
                <p class="small mb-2">
                    {{ \App\Models\Setting::get('store_description', 'Pusat penjualan berbagai macam minuman secara grosir dengan pemesanan yang mudah dan praktis.') }}
                </p>
                @php
                    $waFooter = \App\Models\Setting::get('whatsapp_number', '6285174461840');
                    $waFooter = preg_replace('/[^0-9]/', '', $waFooter);
                    if (str_starts_with($waFooter, '0')) $waFooter = '62' . substr($waFooter, 1);
                @endphp
                <a href="https://wa.me/{{ $waFooter }}" target="_blank" class="btn btn-light btn-sm text-primary">
                    <i class="bi bi-whatsapp"></i> Chat WhatsApp
                </a>
            </div>
            <div class="col-md-4">
                <h6 class="fw-bold">Menu</h6>
                <ul class="list-unstyled small">
                    <li><a href="{{ route('home') }}" class="text-white text-decoration-none">Home</a></li>
                    <li><a href="{{ route('products.index') }}" class="text-white text-decoration-none">Produk</a></li>
                    <li><a href="{{ route('products.promo') }}" class="text-white text-decoration-none">Promo</a></li>
                    <li><a href="{{ route('contact') }}" class="text-white text-decoration-none">Kontak</a></li>
                    @guest
                        <li><a href="{{ route('login') }}" class="text-white text-decoration-none">Login</a></li>
                    @endguest
                </ul>
            </div>
            <div class="col-md-4">
                <h6 class="fw-bold">Kontak</h6>
                <p class="small mb-1">
                    <i class="bi bi-whatsapp"></i> {{ \App\Models\Setting::get('whatsapp_number', '6285174461840') }}
                </p>
                <p class="small mb-1">
                    <i class="bi bi-geo-alt"></i> {{ \App\Models\Setting::get('store_address', 'Surabaya, Jawa Timur') }}
                </p>
                <p class="small mb-0">
                    <i class="bi bi-clock"></i> {{ \App\Models\Setting::get('store_hours', 'Senin - Sabtu, 08.00 - 17.00') }}
                </p>
            </div>
        </div>
        <hr class="border-light my-3">
        <div class="text-center small">
            &copy; {{ date('Y') }} Mitra Grosir Surabaya. Semua hak dilindungi.
        </div>
    </div>
</footer>