<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nota {{ $order->order_number }}</title>
    <style>
        * { font-family: 'DejaVu Sans', sans-serif; }
        body { font-size: 12px; color: #1e293b; }
        .header { background: #1e40af; color: white; padding: 20px; }
        .header h1 { margin: 0; font-size: 22px; }
        .header p { margin: 2px 0; font-size: 11px; opacity: 0.9; }
        .title { text-align: center; margin: 20px 0 10px; }
        .title h2 { color: #1e40af; letter-spacing: 2px; margin: 0; }
        .info-table { width: 100%; margin: 15px 0; }
        .info-table td { vertical-align: top; padding: 3px 0; }
        .info-table .label { width: 130px; color: #64748b; }
        table.items { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table.items th { background: #dbeafe; color: #1e40af; padding: 8px; text-align: left; border-bottom: 2px solid #1e40af; }
        table.items td { padding: 8px; border-bottom: 1px solid #e2e8f0; }
        table.items td.right, table.items th.right { text-align: right; }
        table.items td.center, table.items th.center { text-align: center; }
        .total-row { background: #1e40af; color: white; font-weight: bold; }
        .total-row td { padding: 10px 8px; }
        .footer { margin-top: 30px; font-size: 10px; color: #475569; border-top: 1px dashed #cbd5e1; padding-top: 10px; }
        .badge { display: inline-block; padding: 3px 8px; background: #fef3c7; color: #92400e; border-radius: 4px; font-size: 10px; }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <table width="100%">
            <tr>
                <td>
                    <h1>{{ $settings['store_name'] ?? 'Mitra Grosir Surabaya' }}</h1>
                    <p>{{ $settings['store_address'] ?? 'Surabaya, Jawa Timur' }}</p>
                    <p>WhatsApp: {{ $settings['whatsapp_number'] ?? '-' }}</p>
                </td>
                <td align="right">
                    <div class="badge">NOTA PEMESANAN</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- Judul --}}
    <div class="title">
        <h2>NOTA PEMESANAN</h2>
    </div>

    {{-- Info Order --}}
    <table class="info-table">
        <tr>
            <td class="label">Nomor Pesanan</td>
            <td>: <strong>{{ $order->order_number }}</strong></td>
            <td class="label">Tanggal</td>
            <td>: {{ $order->created_at->format('d F Y, H:i') }} WIB</td>
        </tr>
        <tr>
            <td class="label">Nama Pemesan</td>
            <td>: {{ $order->customer_name }}</td>
            <td class="label">WhatsApp</td>
            <td>: {{ $order->whatsapp }}</td>
        </tr>
        <tr>
            <td class="label">Alamat</td>
            <td colspan="3">: {{ $order->address }}</td>
        </tr>
    </table>

    {{-- Tabel Produk --}}
    <table class="items">
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                <th>Produk</th>
                <th class="right">Harga</th>
                <th class="center">Jumlah</th>
                <th class="right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $i => $item)
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td>{{ $item->product_name }}</td>
                    <td class="right">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="center">{{ $item->quantity }} karton</td>
                    <td class="right">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3" class="right">TOTAL</td>
                <td class="center">{{ $order->items->sum('quantity') }} karton</td>
                <td class="right">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Status --}}
    <p style="margin-top: 15px;">
        <strong>Status Pesanan:</strong> {{ ucwords(str_replace('_', ' ', $order->order_status)) }}<br>
        <strong>Status Pembayaran:</strong> {{ ucwords(str_replace('_', ' ', $order->payment_status)) }}
    </p>

    {{-- Footer --}}
    <div class="footer">
        <p><strong>Nota ini merupakan rincian pesanan sementara dan bukan bukti pembayaran.</strong></p>
        <p>Pesanan akan diproses setelah mendapatkan konfirmasi dari pihak Mitra Grosir Surabaya melalui WhatsApp.</p>
        <p style="margin-top: 10px;">
            Pastikan transaksi hanya melalui WhatsApp resmi Mitra Grosir Surabaya.<br>
            Jangan melakukan pembayaran kepada pihak lain yang mengatasnamakan toko.
        </p>
    </div>

</body>
</html>