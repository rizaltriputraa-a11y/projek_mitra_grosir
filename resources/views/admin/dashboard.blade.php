@extends('layouts.admin')
@section('title', 'Dashboard Admin')

@section('content')
<h3 class="fw-bold mb-4">Dashboard Admin</h3>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-start border-primary border-4 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Total Produk</div>
                <div class="fs-3 fw-bold">{{ $stats['total_products'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-start border-warning border-4 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Produk Promo</div>
                <div class="fs-3 fw-bold">{{ $stats['promo_products'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-start border-info border-4 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Total Pesanan</div>
                <div class="fs-3 fw-bold">{{ $stats['total_orders'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-start border-success border-4 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Total Pelanggan</div>
                <div class="fs-3 fw-bold">{{ $stats['total_customers'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-start border-danger border-4 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Pesanan Baru</div>
                <div class="fs-3 fw-bold">{{ $stats['new_orders'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-start border-secondary border-4 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Sudah Dibayar</div>
                <div class="fs-3 fw-bold">{{ $stats['paid_orders'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-start border-dark border-4 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Pesanan Selesai</div>
                <div class="fs-3 fw-bold">{{ $stats['completed_orders'] }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between">
        <strong>Pesanan Terbaru</strong>
        <a href="{{ route('admin.orders.index') }}" class="text-white small">Lihat semua →</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No. Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr>
                            <td><code>{{ $order->order_number }}</code></td>
                            <td>{{ $order->customer_name }}</td>
                            <td>Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ ucwords(str_replace('_', ' ', $order->order_status)) }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-3">Belum ada pesanan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection