@extends('layouts.admin')
@section('title', 'Rekap Penjualan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">Rekap Hasil Penjualan</h3>
    <a href="{{ route('admin.sales-report.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-success">
        📥 Download Excel (.xlsx)
    </a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label>Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}" required>
            </div>
            <div class="col-md-4">
                <label>Tanggal Akhir</label>
                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}" required>
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary w-100">Filter Data</button>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-start border-primary border-4 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Total Pendapatan (Lunas)</div>
                <div class="fs-3 fw-bold">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-start border-success border-4 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Total Transaksi</div>
                <div class="fs-3 fw-bold">{{ number_format($totalTransactions) }} Transaksi</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-start border-warning border-4 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Total Karton Terjual</div>
                <div class="fs-3 fw-bold">{{ number_format($totalItemsSold) }} Karton</div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white"><strong>Rincian Barang Terjual</strong></div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama Produk</th>
                    <th class="text-center">Total Terjual</th>
                    <th class="text-end">Total Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td class="fw-semibold">{{ $item->product_name }}</td>
                        <td class="text-center"><span class="badge bg-primary">{{ $item->total_qty }} karton</span></td>
                        <td class="text-end">Rp{{ number_format($item->total_sales, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center py-4 text-muted">Tidak ada penjualan pada periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection