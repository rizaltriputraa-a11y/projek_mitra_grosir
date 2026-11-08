@extends('layouts.admin')
@section('title', 'Catat Barang Datang')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.goods-receipts.index') }}" class="btn btn-sm btn-outline-secondary">← Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white"><strong>Form Barang Datang</strong></div>
    <div class="card-body">
        <form action="{{ route('admin.goods-receipts.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label>Pilih Produk</label>
                    <select name="product_id" class="form-select @error('product_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Produk --</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}" {{ old('product_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->name }} (Stok saat ini: {{ $p->stock }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label>Nama Supplier</label>
                    <input type="text" name="supplier" class="form-control @error('supplier') is-invalid @enderror" value="{{ old('supplier') }}" required>
                    @error('supplier')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label>Jumlah Datang (karton)</label>
                    <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" min="1" value="{{ old('quantity') }}" required>
                    @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label>Harga Beli Total (Rp)</label>
                    <input type="number" name="purchase_price" class="form-control @error('purchase_price') is-invalid @enderror" min="0" value="{{ old('purchase_price') }}" required>
                    @error('purchase_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label>Tanggal Kedatangan</label>
                    <input type="date" name="received_date" class="form-control @error('received_date') is-invalid @enderror" value="{{ old('received_date', date('Y-m-d')) }}" required>
                    @error('received_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <button class="btn btn-primary mt-3 w-100">Simpan & Tambah Stok</button>
        </form>
    </div>
</div>
@endsection