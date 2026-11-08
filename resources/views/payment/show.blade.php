@extends('layouts.app')
@section('title', 'Pembayaran QRIS')

@section('content')
<div class="container py-4">
    <h1 class="section-title">Pembayaran QRIS</h1>

    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i>
        Pastikan Anda melakukan pembayaran hanya melalui metode pembayaran resmi yang tersedia di website
        Mitra Grosir Surabaya. Jangan melakukan pembayaran kepada pihak lain di luar informasi pembayaran
        resmi yang ditampilkan pada website.
    </div>

    <div class="row">
        <div class="col-lg-5">
            <div class="card shadow-sm mb-3">
                <div class="card-header"><strong>Detail Pesanan</strong></div>
                <div class="card-body">
                    <p class="mb-1"><strong>No. Pesanan:</strong> {{ $order->order_number }}</p>
                    <p class="mb-1"><strong>Total:</strong>
                        <span class="text-primary fs-5">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </p>
                    <p class="mb-1"><strong>Status Pembayaran:</strong>
                        <span class="badge bg-{{ $order->payment_status === 'Pembayaran Berhasil' ? 'success' : ($order->payment_status === 'Pembayaran Ditolak' ? 'danger' : 'warning') }}">
                            {{ $order->payment_status }}
                        </span>
                    </p>
                    <p class="mb-0"><strong>Status Pesanan:</strong>
                        <span class="badge bg-secondary">{{ $order->order_status }}</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header"><strong>QRIS Mitra Grosir</strong></div>
                <div class="card-body text-center">
                    @if($qrisImage)
                        <img src="{{ asset('storage/' . $qrisImage) }}" alt="QRIS" class="img-fluid mb-3" style="max-width: 320px;">
                    @else
                        <div class="alert alert-warning">QRIS belum tersedia. Silakan hubungi admin.</div>
                    @endif
                    <p class="small text-muted">Scan QRIS di atas untuk melakukan pembayaran.</p>

                    @if($order->payment_status === 'Menunggu Pembayaran' || $order->payment_status === 'Pembayaran Ditolak')
                        <hr>
                        <h6>Upload Bukti Pembayaran</h6>
                        @if($order->payment && $order->payment->rejection_reason)
                            <div class="alert alert-danger small">
                                Alasan penolakan: {{ $order->payment->rejection_reason }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('payment.upload', $order->order_number) }}" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="proof_image" class="form-control mb-2" accept="image/*" required>
                            <button class="btn btn-primary w-100">
                                <i class="bi bi-upload"></i> Upload Bukti
                            </button>
                        </form>
                    @elseif($order->payment_status === 'Menunggu Verifikasi')
                        <div class="alert alert-warning">Bukti pembayaran sedang diverifikasi oleh admin.</div>
                        @if($order->payment && $order->payment->proof_image)
                            <img src="{{ asset('storage/' . $order->payment->proof_image) }}" class="img-fluid rounded" style="max-width: 220px;">
                        @endif
                    @elseif($order->payment_status === 'Pembayaran Berhasil')
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i> Pembayaran berhasil diverifikasi.
                        </div>
                    @endif

                    @php
                        $wa = \App\Models\Setting::get('whatsapp_number', '6285174461840');
                        $wa = preg_replace('/[^0-9]/', '', $wa);
                        if (str_starts_with($wa, '0')) $wa = '62' . substr($wa, 1);
                        $msg = "Halo Mitra Grosir Surabaya, saya ingin menanyakan pesanan {$order->order_number}.";
                    @endphp
                    <a href="https://wa.me/{{ $wa }}?text={{ urlencode($msg) }}" target="_blank"
                       class="btn btn-outline-success w-100 mt-2">
                        <i class="bi bi-whatsapp"></i> Tanya via WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection