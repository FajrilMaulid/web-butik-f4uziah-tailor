<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Tampilkan halaman info pembayaran setelah checkout.
     */
    public function info(Order $order)
    {
        // Pastikan order milik user yang sedang login
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $bankName        = Setting::get('bank_name', 'BCA');
        $bankAccount     = Setting::get('bank_account_number', '1234567890');
        $bankAccountName = Setting::get('bank_account_name', 'F4UZIAH TAILOR');
        $bankName2       = Setting::get('bank_name_2');
        $bankAccount2    = Setting::get('bank_account_number_2');
        $bankAccountName2 = Setting::get('bank_account_name_2');

        $adminWa = Setting::get('admin_whatsapp', '6289601767100');
        $adminWaClean = preg_replace('/[^0-9]/', '', $adminWa);
        if (strpos($adminWaClean, '0') === 0) {
            $adminWaClean = '62' . substr($adminWaClean, 1);
        }
        $waMessage = "Halo Butik F4UZIAHTAILOR, saya sudah melakukan transfer untuk pesanan #ORD-" . str_pad($order->id, 5, '0', STR_PAD_LEFT) . ". Mohon dikonfirmasi. Terima kasih.";
        $waUrl = "https://wa.me/" . $adminWaClean . "?text=" . urlencode($waMessage);

        return view('pages.user.payment-info', compact(
            'order',
            'bankName', 'bankAccount', 'bankAccountName',
            'bankName2', 'bankAccount2', 'bankAccountName2',
            'waUrl'
        ));
    }

    /**
     * Proses upload bukti transfer dari user.
     */
    public function upload(Request $request, Order $order)
    {
        // Pastikan order milik user yang sedang login
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
        ], [
            'payment_proof.required' => 'Silakan pilih foto bukti transfer.',
            'payment_proof.image'    => 'File harus berupa gambar.',
            'payment_proof.max'      => 'Ukuran gambar maksimal 3MB.',
        ]);

        // Hapus bukti lama jika ada
        if ($order->reference_image) {
            Storage::disk('public')->delete($order->reference_image);
        }

        // Simpan bukti baru
        $path = $request->file('payment_proof')->store('payment_proofs', 'public');

        $order->update([
            'reference_image' => $path,
            'payment_status'  => 'uploaded',
            'status'          => 'menunggu',
        ]);

        return redirect()->route('profile')
            ->with('success', 'Bukti transfer berhasil dikirim! Menunggu konfirmasi dari admin.');
    }

    /**
     * Admin: konfirmasi pembayaran lunas.
     */
    public function confirm(Order $order)
    {
        $order->update([
            'payment_status' => 'confirmed',
            'status'         => 'proses',
        ]);

        return back()->with('success', "Pembayaran pesanan #ORD-" . str_pad($order->id, 5, '0', STR_PAD_LEFT) . " berhasil dikonfirmasi. Status pesanan diubah ke Diproses.");
    }

    /**
     * Admin: tolak pembayaran.
     */
    public function reject(Order $order)
    {
        $order->update([
            'payment_status' => 'rejected',
            'status'         => 'batal',
        ]);

        return back()->with('success', "Pembayaran pesanan #ORD-" . str_pad($order->id, 5, '0', STR_PAD_LEFT) . " ditolak. Status pesanan diubah ke Batal.");
    }
}
