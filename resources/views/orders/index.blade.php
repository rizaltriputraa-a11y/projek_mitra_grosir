@extends('layouts.app')
@section('title', 'Pesanan Saya')

@section('content')
<div class="container py-4">
    <h1 class="section-title">Pesanan Saya</h1>

    @forelse($orders as $order)
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <small class="text-muted">No. Pesanan</small>
                        <h6 class="mb-0">{{ $order->order_number }}</h6>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Tanggal</small>
                        <div>{{ $order->created_at->format('d M Y H:i') }}</div>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Total</small>
                        <div class="fw-bold text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-md-3 text-md-end">
                        <span class="badge bg-info">{{ $order->payment_status }}</span>
                        <span class="badge bg-secondary">{{ $order->order_status }}</span>
                        <div class="mt-2">
                            <a href="{{ route('orders.show', $order->order_number) }}" class="btn btn-sm btn-primary">Detail</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <i class="bi bi-bag-x fs-1 text-muted"></i>
            <p class="mt-3">Anda belum memiliki pesanan.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">Mulai Belanja</a>
        </div>
    @endforelse

    {{ $orders->links() }}
</div>
@endsection