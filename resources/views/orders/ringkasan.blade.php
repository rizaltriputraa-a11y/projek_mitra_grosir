@extends('layouts.app')

@section('title', 'Ringkasan Pesanan')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <h2 class="fw-bold text-primary">RINGKASAN PESANAN</h2>
        <p class="text-muted mb-0">Periksa kembali pesanan Anda sebelum melanjutkan.</p>
    </div>

    {{-- Peringatan keamanan --}}
    <div class="alert alert-warning border-start border-4 border-warning">
        <strong>⚠️ Peringatan:</strong>
        Pastikan Anda melakukan transaksi hanya melalui nomor WhatsApp dan informasi pembayaran resmi
        <strong>Mitra Grosir Surabaya</strong>. Jangan melakukan pembayaran kepada pihak lain yang mengatasnamakan toko.
    </div>

    {{-- Data Pelanggan --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Data Pemesan</h5>
        </div>
        <div class="card-body">
            <form id="orderForm" action="{{ route('checkout.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="customer_name" class="form-control" 
                               value="{{ old('customer_name', $user->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nomor WhatsApp <span class="text-danger">*</span></label>
                        <input type="text" name="whatsapp" class="form-control" 
                               value="{{ old('whatsapp', $user->whatsapp) }}" 
                               placeholder="628xxxxxxxxxx" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Alamat Pengiriman <span class="text-danger">*</span></label>
                        <textarea name="address" class="form-control" rows="3" required>{{ old('address', $user->address) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Catatan (opsional)</label>
                        <input type="text" name="notes" class="form-control" value="{{ old('notes') }}" 
                               placeholder="Contoh: Kirim pagi hari">
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Daftar Produk --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Daftar Produk</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th class="text-end">Harga</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $item['product']->image ? asset('storage/' . $item['product']->image) : 'https://via.placeholder.com/60' }}" 
                                             alt="{{ $item['product']->name }}" 
                                             class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                        <div>
                                            <div class="fw-semibold">{{ $item['product']->name }}</div>
                                            <small class="text-muted">{{ $item['product']->unit ?? 'karton' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">Rp{{ number_format($item['price'], 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <span class="badge bg-primary">{{ $item['quantity'] }} karton</span>
                                </td>
                                <td class="text-end fw-semibold">Rp{{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="2" class="text-end fw-bold">Total</td>
                            <td class="text-center fw-bold">{{ $totalQty }} karton</td>
                            <td class="text-end fw-bold text-primary fs-5">Rp{{ number_format($total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    {{-- Tombol Aksi --}}
    <div class="d-flex flex-wrap gap-2 justify-content-end">
        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">
            ← Kembali Belanja
        </a>
        <button type="submit" form="orderForm" class="btn btn-primary btn-lg">
            ✅ Buat Pesanan & Cetak Nota
        </button>
    </div>

    <div class="alert alert-info mt-3 small">
        <strong>Catatan:</strong> Setelah menekan tombol di atas, pesanan akan disimpan dan Anda dapat mengunduh nota PDF.
        Nota tersebut silakan dilampirkan secara manual saat mengirim pesan ke WhatsApp.
    </div>
</div>
@endsection