@extends('layouts.admin')
@section('title', 'Pencatatan Barang Datang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">Pencatatan Barang Datang</h3>
    <a href="{{ route('admin.goods-receipts.create') }}" class="btn btn-primary">+ Catat Barang Datang</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Tanggal Datang</th>
                    <th>Nama Barang</th>
                    <th>Supplier</th>
                    <th class="text-center">Jumlah Datang</th>
                    <th class="text-end">Harga Beli (Total)</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($receipts as $r)
                    <tr>
                        <td>{{ $r->received_date->format('d/m/Y') }}</td>
                        <td class="fw-semibold">{{ $r->product->name ?? 'Produk Dihapus' }}</td>
                        <td>{{ $r->supplier }}</td>
                        <td class="text-center"><span class="badge bg-success">+ {{ $r->quantity }} karton</span></td>
                        <td class="text-end">Rp{{ number_format($r->purchase_price, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.goods-receipts.edit', $r->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('admin.goods-receipts.destroy', $r->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus catatan ini? Stok akan dikurangi kembali.')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada barang datang yang dicatat.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">{{ $receipts->links() }}</div>
</div>
@endsection