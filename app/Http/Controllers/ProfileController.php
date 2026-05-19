<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // Menampilkan halaman profil beserta tab history
    public function index()
    {
        $orders = \App\Models\Order::with('product')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
        return view('pages.user.profile', compact('orders'));
    }

    // Proses memperbarui data akun user
    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi input form
        $request->validate([
            'name'         => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'email'        => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password'     => 'nullable|string|min:6',
            'avatar'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 2. Update data dasar
        $user->name = $request->name;
        $user->phone_number = $request->phone_number;
        $user->email = $request->email;

        // 3. Handle upload avatar jika ada
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada
            if ($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            // Simpan avatar baru
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        // 4. Cek apakah user mengisi kolom password baru
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // 5. Simpan ke database
        $user->save();

        // 6. Kembali ke halaman profil dengan pesan sukses
        return back()->with('success', 'Profil Anda berhasil diperbarui!');
    }
}
