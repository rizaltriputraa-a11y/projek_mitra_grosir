<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class AdminCustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role', 'user')
            ->withCount('orders')
            ->latest()
            ->paginate(15);

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $user)
    {
        // Pastikan akun yang dibuka adalah pelanggan
        if ($user->role !== 'user') {
            abort(404);
        }

        $orders = $user->orders()
            ->with('items')
            ->latest()
            ->paginate(10);

        return view('admin.customers.show', compact('user', 'orders'));
    }
}
