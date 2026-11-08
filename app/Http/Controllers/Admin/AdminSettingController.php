<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    public function index()
    {
        $whatsapp = Setting::get('whatsapp_number', '085174461840');
        $storeName = Setting::get('store_name', 'Mitra Grosir Surabaya');
        $storeAddress = Setting::get('store_address', 'Surabaya, Jawa Timur');
        $storeDescription = Setting::get(
            'store_description',
            'Pusat penjualan berbagai macam minuman secara grosir.'
        );
        $storeHours = Setting::get(
            'store_hours',
            'Senin - Sabtu, 08.00 - 17.00'
        );

        return view('admin.settings.index', compact(
            'whatsapp',
            'storeName',
            'storeAddress',
            'storeDescription',
            'storeHours'
        ));
    }

    public function updateWhatsapp(Request $request)
    {
        $data = $request->validate([
            'whatsapp_number' => [
                'required',
                'string',
                'max:20',
                'regex:/^[0-9+\-\s]+$/',
            ],
        ]);

        Setting::set('whatsapp_number', $data['whatsapp_number']);

        ActivityLog::log(
            'whatsapp_updated',
            'Nomor WhatsApp toko diperbarui.'
        );

        return back()->with(
            'success',
            'Nomor WhatsApp berhasil diperbarui.'
        );
    }

    public function updateStore(Request $request)
    {
        $data = $request->validate([
            'store_name' => 'required|string|max:100',
            'store_address' => 'required|string|max:255',
            'store_description' => 'nullable|string',
            'store_hours' => 'nullable|string|max:100',
        ]);

        Setting::set('store_name', $data['store_name']);
        Setting::set('store_address', $data['store_address']);
        Setting::set('store_description', $data['store_description'] ?? '');
        Setting::set('store_hours', $data['store_hours'] ?? '');

        ActivityLog::log(
            'store_updated',
            'Informasi toko diperbarui.'
        );

        return back()->with(
            'success',
            'Informasi toko berhasil diperbarui.'
        );
    }
}
