<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Menampilkan halaman Register
    public function showRegister()
    {
        return view('auth.register');
    }

    // Menampilkan halaman Login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses Pendaftaran User Baru
    public function register(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:15',
            'password'     => 'required|string|min:6',
        ]);

        // 2. Simpan User ke Database
        $user = User::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'phone_number' => $request->phone_number,
            'password'     => Hash::make($request->password),
            'role'         => 'user', // Default register adalah user biasa
        ]);

        // 3. Login otomatis setelah daftar
        Auth::login($user);

        // 4. Redirect ke Beranda
        return redirect('/')->with('success', 'Akun berhasil dibuat!');
    }

    // Proses Login
    public function login(Request $request)
    {
        // 1. Validasi Input
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Coba Autentikasi
        // Argumen kedua 'true' digunakan jika checkbox "Ingat Saya" dicentang
        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();

            // 3. Pengecekan Role
            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->intended('/');
        }

        // 4. Jika gagal
        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah keluar.');
    }
}
