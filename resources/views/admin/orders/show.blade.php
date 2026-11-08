@extends('layouts.admin')
@section('title', 'Detail Pesanan')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">← Kembali</a>
</div>

<div class="row g-3">
    {{-- Info Pesanan --}}
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white"><strong>Info Pesanan</strong></div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr><th width="140">No. Pesanan</th><td><code>{{ $order->order_number }}</code></td></tr>
                    <tr><th>Tanggal</th><td>{{ $order->created_at->format('d F Y, H:i') }} WIB</td></tr>
                    <tr><th>Nama</th><td>{{ $order->customer_name }}</td></tr>
                    <tr><th>WhatsApp</th>
                        <td>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->whatsapp) }}" target="_blank">
                                {{ $order->whatsapp }}
                            </a>
                        </td>
                    </tr>
                    <tr><th>Alamat</th><td>{{ $order->address }}</td></tr>
                    @if($order->notes)
                        <tr><th>Catatan</th><td>{{ $order->notes }}</td></tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    {{-- Update Status --}}
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white"><strong>Update Status</strong></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}">
                    @csrf
                    @method('PATCH')
                    <label class="form-label">Status Pesanan</label>
                    <select name="order_status" class="form-select mb-2">
                        @php
                            $statuses = [
                                'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
                                'dikonfirmasi'        => 'Dikonfirmasi',
                                'menunggu_pembayaran' => 'Menunggu Pembayaran',
                                'sudah_dibayar'       => 'Sudah Dibayar',
                                'diproses'            => 'Diproses',
                                'selesai'             => 'Selesai',
                                'dibatalkan'          => 'Dibatalkan',
                            ];
                        @endphp
                        @foreach($statuses as $val => $label)
                            <option value="{{ $val }}" {{ $order->order_status == $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <button class="btn btn-success w-100">Simpan Status</button>
                </form>

                <hr>
                <a href="{{ route('orders.whatsapp', $order->order_number) }}" 
                   target="_blank" class="btn btn-outline-success w-100">
                    💬 Hubungi Pelanggan via WhatsApp
                </a>
            </div>
        </div>
    </div>

    {{-- Daftar Produk --}}
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white"><strong>Daftar Produk</strong></div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th class="text-end">Harga</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td class="text-end">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="text-center">{{ $item->quantity }} karton</td>
                                <td class="text-end">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        <tr class="table-primary">
                            <td colspan="3" class="text-end fw-bold">TOTAL</td>
                            <td class="text-end fw-bold">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection