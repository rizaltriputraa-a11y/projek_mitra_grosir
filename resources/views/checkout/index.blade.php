@extends('layouts.app')
@section('title', 'Checkout')

@section('content')
<div class="container py-4">
    <h1 class="section-title">Checkout</h1>

    <div class="alert alert-warning">
        <i class="bi bi-shield-exclamation"></i>
        Pastikan seluruh data pesanan dan pembayaran sudah benar sebelum melanjutkan.
        Lakukan pembayaran <strong>hanya melalui QRIS resmi</strong> yang tampil di website.
        Jangan melakukan pembayaran kepada pihak lain di luar informasi resmi Mitra Grosir Surabaya.
    </div>

    <form method="POST" action="{{ route('checkout.store') }}">
        @csrf
        <div class="row">
            <div class="col-lg-7">
                <div class="card shadow-sm mb-3">
                    <div class="card-header"><strong>Data Penerima</strong></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Penerima <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror"
                                   value="{{ old('customer_name', auth()->user()->name) }}" required>
                            @error('customer_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nomor WhatsApp <span class="text-danger">*</span></label>
                            <input type="text" name="whatsapp" class="form-control @error('whatsapp') is-invalid @enderror"
                                   value="{{ old('whatsapp', auth()->user()->whatsapp) }}" required>
                            @error('whatsapp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea name="address" rows="3" class="form-control @error('address') is-invalid @enderror" required>{{ old('address', auth()->user()->address) }}</textarea>
                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan (opsional)</label>
                            <textarea name="notes" rows="2" class="form-control">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-header"><strong>Ringkasan Pesanan</strong></div>
                    <div class="card-body">
                        @foreach($cart as $item)
                            <div class="d-flex justify-content-between small mb-1">
                                <span>{{ $item['name'] }} × {{ $item['quantity'] }}</span>
                                <span>Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total</strong>
                            <strong class="text-primary fs-5">Rp {{ number_format($total, 0, ',', '.') }}</strong>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mt-3">
                            <i class="bi bi-check-circle"></i> LANJUT KE PEMBAYARAN
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection