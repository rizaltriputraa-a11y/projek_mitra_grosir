@extends('layouts.app')
@section('title', 'Detail Pesanan')

@section('content')
<div class="container py-4">
    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <h1 class="section-title">Pesanan {{ $order->order_number }}</h1>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-3">
                <div class="card-header"><strong>Produk</strong></div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr><th>Produk</th><th>Qty</th><th>Harga</th><th>Subtotal</th></tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr><th colspan="3" class="text-end">Total</th>
                                <th>Rp {{ number_format($order->total, 0, ',', '.') }}</th></tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header"><strong>Data Pengiriman</strong></div>
                <div class="card-body">
                    <p class="mb-1"><strong>Nama:</strong> {{ $order->customer_name }}</p>
                    <p class="mb-1"><strong>WhatsApp:</strong> {{ $order->whatsapp }}</p>
                    <p class="mb-1"><strong>Alamat:</strong> {{ $order->address }}</p>
                    @if($order->notes)<p class="mb-1"><strong>Catatan:</strong> {{ $order->notes }}</p>@endif
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header"><strong>Status</strong></div>
                <div class="card-body">
                    <p><strong>Pembayaran:</strong><br>
                        <span class="badge bg-info">{{ $order->payment_status }}</span>
                    </p>
                    <p><strong>Pesanan:</strong><br>
                        <span class="badge bg-secondary">{{ $order->order_status }}</span>
                    </p>
                    <p><strong>Tanggal:</strong><br>{{ $order->created_at->format('d M Y H:i') }}</p>

                    @if($order->payment_status === 'Menunggu Pembayaran' || $order->payment_status === 'Pembayaran Ditolak')
                        <a href="{{ route('payment.show', $order->order_number) }}" class="btn btn-primary w-100">
                            <i class="bi bi-credit-card"></i> Bayar Sekarang
                        </a>
                    @endif

                    @php
                        $wa = \App\Models\Setting::get('whatsapp_number', '6285174461840');
                        $wa = preg_replace('/[^0-9]/', '', $wa);
                        if (str_starts_with($wa, '0')) $wa = '62' . substr($wa, 1);
                        $msg = "Halo Mitra Grosir Surabaya, saya ingin menanyakan pesanan {$order->order_number}.";
                    @endphp
                    <a href="https://wa.me/{{ $wa }}?text={{ urlencode($msg) }}" target="_blank"
                       class="btn btn-outline-success w-100 mt-2">
                        <i class="bi bi-whatsapp"></i> Tanya via WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection