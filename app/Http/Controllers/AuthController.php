<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // --- Bagian Login ---
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Cek kredensial ke database
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // Arahkan ke halaman utama/dashboard setelah berhasil login
            return redirect()->intended('/dashboard');
        }

        // 3. Jika gagal, kembalikan ke form dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // --- Bagian Register ---
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // 1. Validasi data pendaftaran
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:8', 'confirmed'], // butuh input password_confirmation di form
        ]);

        // 2. Simpan user baru ke database (Password otomatis di-hash oleh model User Laravel default, atau kita hash manual)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3. Langsung loginkan user yang baru mendaftar
        Auth::login($user);

        return redirect('/dashboard');
    }

    // --- Bagian Logout ---
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}