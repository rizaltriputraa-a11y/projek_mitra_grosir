@extends('layouts.app')
@section('title', 'Kontak')

@section('content')
<div class="container py-4">
    <h1 class="section-title">Hubungi Kami</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            @php
                $wa = \App\Models\Setting::get('whatsapp_number', '6285174461840');
                $clean = preg_replace('/[^0-9]/', '', $wa);
                if (str_starts_with($clean, '0')) $clean = '62' . substr($clean, 1);
            @endphp
            <p><strong>WhatsApp:</strong> {{ $wa }}</p>
            <a href="https://wa.me/{{ $clean }}?text={{ urlencode('Halo Mitra Grosir Surabaya.') }}"
               target="_blank" class="btn btn-success">
                <i class="bi bi-whatsapp"></i> Chat via WhatsApp
            </a>
        </div>
    </div>
</div>
@endsection