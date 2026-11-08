@extends('layouts.admin')
@section('title', 'Daftar Pesanan')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <strong>Daftar Pesanan</strong>
        <span class="badge bg-light text-dark">{{ $orders->total() }} pesanan</span>
    </div>
    <div class="card-body">

        {{-- Filter --}}
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">-- Semua Status --</option>
                    <option value="menunggu_konfirmasi" {{ request('status')=='menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                    <option value="dikonfirmasi" {{ request('status')=='dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
                    <option value="menunggu_pembayaran" {{ request('status')=='menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="sudah_dibayar" {{ request('status')=='sudah_dibayar' ? 'selected' : '' }}>Sudah Dibayar</option>
                    <option value="diproses" {{ request('status')=='diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai" {{ request('status')=='selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ request('status')=='dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div class="col-md-5">
                <input type="text" name="search" class="form-control"
                       placeholder="Cari nomor pesanan / nama / WA..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100">Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>

        {{-- Tabel --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle">
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
                    @forelse($orders as $order)
                        <tr>
                            <td>
                                <code>{{ $order->order_number }}</code>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $order->customer_name }}</div>
                                <small class="text-muted">{{ $order->whatsapp }}</small>
                            </td>
                            <td class="fw-semibold">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                            <td>
                                @php
                                    $colors = [
                                        'menunggu_konfirmasi' => 'warning',
                                        'dikonfirmasi'        => 'info',
                                        'menunggu_pembayaran' => 'warning',
                                        'sudah_dibayar'       => 'primary',
                                        'diproses'            => 'info',
                                        'selesai'             => 'success',
                                        'dibatalkan'          => 'danger',
                                    ];
                                    $color = $colors[$order->order_status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }}">
                                    {{ ucwords(str_replace('_', ' ', $order->order_status)) }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada pesanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $orders->links() }}
    </div>
</div>
@endsection