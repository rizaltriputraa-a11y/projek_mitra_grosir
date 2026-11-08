@extends('layouts.app')
@section('title', 'Keranjang Belanja')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4">Keranjang Belanja</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(count($cart) > 0)
        <div class="card shadow-sm mb-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th class="text-end">Harga</th>
                                <th class="text-center" style="width: 150px;">Jumlah</th>
                                <th class="text-end">Subtotal</th>
                                <th class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @foreach($cart as $id => $item)
                                @php 
                                    $subtotal = $item['price'] * $item['quantity']; 
                                    $total += $subtotal;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            @if(isset($item['image']))
                                                <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary rounded text-white d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                    <i class="bi bi-image"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-semibold">{{ $item['name'] }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">Rp{{ number_format($item['price'], 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('cart.update', $id) }}" method="POST" class="d-flex justify-content-center gap-1">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['stock'] ?? 99 }}" class="form-control form-control-sm text-center" style="width: 70px;">
                                            <button type="submit" class="btn btn-sm btn-outline-primary" title="Update">↻</button>
                                        </form>
                                    </td>
                                    <td class="text-end fw-semibold">Rp{{ number_format($subtotal, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('cart.remove', $id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus" onclick="return confirm('Hapus produk ini dari keranjang?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 fw-bold">Total: <span class="text-primary">Rp{{ number_format($total, 0, ',', '.') }}</span></h5>
                <div class="d-flex gap-2">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Lanjut Belanja</a>
                    
                    {{-- INI BAGIAN YANG DIUBAH: route('checkout.index') menjadi route('checkout.review') --}}
                    <a href="{{ route('checkout.review') }}" class="btn btn-primary">Lanjut ke Ringkasan →</a>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <div class="display-1 text-muted mb-3"><i class="bi bi-cart-x"></i></div>
            <h4 class="text-muted">Keranjang Anda masih kosong.</h4>
            <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">Mulai Belanja</a>
        </div>
    @endif
</div>
@endsection