<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'whatsapp' => 'required|string|max:20',
            'address' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Pengguna yang mendaftar selalu menjadi pelanggan
        $data['role'] = 'user';

        $user = User::create($data);

        Auth::login($user);

        // Memperbarui session setelah login
        $request->session()->regenerate();

        return redirect()->route('home')
            ->with('success', 'Registrasi berhasil. Selamat datang!');
    }
}
