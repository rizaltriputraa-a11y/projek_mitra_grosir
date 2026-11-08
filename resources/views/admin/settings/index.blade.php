@extends('layouts.admin')
@section('title', 'Pengaturan')

@section('content')
<div class="row g-3">
    {{-- Nomor WhatsApp --}}
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-primary text-white">
                <strong>Nomor WhatsApp Toko</strong>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.settings.whatsapp') }}">
                    @csrf
                    <label class="form-label">Nomor WhatsApp</label>
                    <input type="text" name="whatsapp_number" class="form-control mb-2"
                           value="{{ $whatsapp }}" placeholder="6285174461840" required>
                    <small class="text-muted d-block mb-3">
                        Format: <code>6285174461840</code> (tanpa +, tanpa spasi, tanpa strip).
                        Nomor ini dipakai untuk semua tombol WhatsApp.
                    </small>
                    <button class="btn btn-primary w-100">Simpan Nomor WA</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Jam Operasional --}}
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-info text-white">
                <strong>Jam Operasional</strong>
            </div>
            <div class="card-body">
                <p class="mb-2">Jam operasional ditampilkan di halaman kontak & footer.</p>
                <p class="text-muted small">
                    Contoh: <em>Senin - Sabtu, 08.00 - 17.00</em>
                </p>
                <p class="mb-0"><strong>Jam saat ini:</strong> {{ $storeHours }}</p>
            </div>
        </div>
    </div>

    {{-- Informasi Toko --}}
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <strong>Informasi Toko</strong>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.settings.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Toko</label>
                            <input type="text" name="store_name" class="form-control"
                                   value="{{ $storeName }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jam Operasional</label>
                            <input type="text" name="store_hours" class="form-control"
                                   value="{{ $storeHours }}" placeholder="Senin - Sabtu, 08.00 - 17.00">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Alamat Toko</label>
                            <textarea name="store_address" class="form-control" rows="2" required>{{ $storeAddress }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Deskripsi Singkat</label>
                            <textarea name="store_description" class="form-control" rows="3">{{ $storeDescription }}</textarea>
                        </div>
                    </div>
                    <button class="btn btn-success mt-3">Simpan Informasi Toko</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Info Keamanan --}}
    <div class="col-12">
        <div class="alert alert-warning">
            <strong>⚠️ Catatan Keamanan:</strong>
            Website ini <strong>tidak</strong> memproses pembayaran otomatis. Semua pembayaran dilakukan melalui
            komunikasi WhatsApp. Jangan pernah meminta pelanggan transfer ke rekening pribadi tanpa konfirmasi resmi.
        </div>
    </div>
</div>
@endsection