<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;

class WhatsAppService
{
    public function getNumber(): string
{
    $number = Setting::where('key', 'whatsapp_number')->value('value')
        ?? '085174461840';

    $number = preg_replace('/[^0-9]/', '', $number);

    if (str_starts_with($number, '0')) {
        $number = '62' . substr($number, 1);
    }

    return $number;
}

    public function link(string $message): string
    {
        return 'https://wa.me/' . $this->getNumber()
            . '?text=' . urlencode($message);
    }

    public function pesanSatuProduk(Order $order): string
    {
        $item = $order->items->first();

        if (!$item) {
            return $this->pesanTanyaPesanan($order->order_number);
        }

        $harga = number_format($item->price, 0, ',', '.');
        $subtotal = number_format($item->subtotal, 0, ',', '.');
        $total = number_format($order->total, 0, ',', '.');

        return "Halo Mitra Grosir Surabaya.\n\n"
            . "Saya ingin memesan produk:\n\n"
            . "Nomor Pesanan: {$order->order_number}\n"
            . "Nama: {$order->customer_name}\n"
            . "Produk: {$item->product_name}\n"
            . "Jumlah: {$item->quantity} karton\n"
            . "Harga per karton: Rp{$harga}\n"
            . "Subtotal: Rp{$subtotal}\n"
            . "Alamat: {$order->address}\n"
            . "Total: Rp{$total}\n\n"
            . "Mohon konfirmasi pesanan saya.\n\n"
            . "Terima kasih.";
    }

    public function pesanMultiProduk(Order $order): string
    {
        $total = number_format($order->total, 0, ',', '.');
        $totalItems = $order->items->count();
        $totalQty = $order->items->sum('quantity');

        return "Halo Mitra Grosir Surabaya.\n\n"
            . "Saya ingin melakukan pemesanan produk:\n\n"
            . "Nomor Pesanan: {$order->order_number}\n"
            . "Nama: {$order->customer_name}\n"
            . "Total Jenis Produk: {$totalItems}\n"
            . "Total Pembelian: {$totalQty} karton\n"
            . "Total Pesanan: Rp{$total}\n\n"
            . "Saya akan melampirkan nota pemesanan dalam pesan ini.\n\n"
            . "Mohon konfirmasi pesanan saya.\n\n"
            . "Terima kasih.";
    }

    public function pesanTanyaProduk(?string $productName = null): string
    {
        if ($productName) {
            return "Halo Mitra Grosir Surabaya, saya ingin bertanya mengenai produk {$productName}.";
        }

        return "Halo Mitra Grosir Surabaya, saya ingin bertanya mengenai produk yang tersedia.";
    }

    public function pesanTanyaPesanan(string $orderNumber): string
    {
        return "Halo Mitra Grosir Surabaya, saya ingin menanyakan pesanan dengan nomor {$orderNumber}.";
    }
}
