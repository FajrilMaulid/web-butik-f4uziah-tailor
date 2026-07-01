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

        // Mengelompokkan pesanan berdasarkan payment_code.
        // Jika payment_code bernilai null (data lama), kelompokkan secara individu berdasarkan order ID.
        $groupedOrders = $orders->groupBy(function ($order) {
            return $order->payment_code ?? 'individual-' . $order->id;
        });

        return view('pages.user.profile', compact('groupedOrders'));
    }

    // Proses memperbarui data akun user
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi input form
        $request->validate([
            'name'         => 'required|string|max:255',
            'phone_number' => ['required', 'string', 'min:9', 'max:15', 'regex:/^[0-9+\-\s]+$/'],
            'email'        => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password'     => 'nullable|string|min:6',
            'avatar'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address'      => 'nullable|string',
        ], [
            'phone_number.min'   => 'Nomor telepon minimal 9 digit.',
            'phone_number.max'   => 'Nomor telepon maksimal 15 digit.',
            'phone_number.regex' => 'Nomor telepon hanya boleh mengandung angka.',
        ]);


        // Update data dasar
        $user->name = $request->name;
        $user->phone_number = $request->phone_number;
        $user->email = $request->email;
        $user->address = $request->address;

        // Upload avatar
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama
            if ($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            // Simpan avatar baru
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        // Cek user mengisi kolom password baru
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Simpan ke database
        $user->save();

        // Kembali ke halaman profil
        return back()->with('success', 'Profil Anda berhasil diperbarui!');
    }
}
