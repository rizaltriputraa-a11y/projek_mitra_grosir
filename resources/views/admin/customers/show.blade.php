@extends('layouts.admin')
@section('title', 'Detail Customer')

@section('content')
<a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
    <i class="bi bi-arrow-left"></i> Kembali
</a>

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <p><strong>Nama:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>WhatsApp:</strong> {{ $user->whatsapp }}</p>
        <p><strong>Alamat:</strong> {{ $user->address }}</p>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header"><strong>Riwayat Pesanan</strong></div>
    <div class="card-body table-responsive">
        <table class="table">
            <thead><tr><th>No. Pesanan</th><th>Total</th><th>Status</th><th>Tanggal</th><th></th></tr></thead>
            <tbody>
                @foreach($orders as $o)
                    <tr>
                        <td>{{ $o->order_number }}</td>
                        <td>Rp {{ number_format($o->total, 0, ',', '.') }}</td>
                        <td><span class="badge bg-info">{{ $o->order_status }}</span></td>
                        <td>{{ $o->created_at->format('d M Y') }}</td>
                        <td><a href="{{ route('admin.orders.show', $o) }}" class="btn btn-sm btn-primary">Detail</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection