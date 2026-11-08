<?php

namespace App\Http\Controllers;

use App\Models\Setting;

class WhatsAppController extends Controller
{
    public function contact()
    {
        $whatsapp = Setting::get('whatsapp_number', '6281234567890');
        return view('contact', compact('whatsapp'));
    }

    public static function link(string $message = null): string
    {
        $number = Setting::get('whatsapp_number', '6281234567890');
        $number = preg_replace('/[^0-9]/', '', $number);
        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        }
        $url = "https://wa.me/{$number}";
        if ($message) {
            $url .= '?text=' . urlencode($message);
        }
        return $url;
    }
}