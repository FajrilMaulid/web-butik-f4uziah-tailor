@extends('layouts.app')

@section('title', 'F4UZIAHTAILOR - Informasi Pembayaran')

@section('content')
<section class="payment-info-section">
    <div class="payment-info-wrapper">

        {{-- Header Sukses --}}
        <div class="payment-success-header">
            <div class="success-icon-ring">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <h1>Pesanan Berhasil Dibuat!</h1>
            <p>Pesanan <strong>#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</strong> telah kami terima.<br>Silakan selesaikan pembayaran untuk memulai proses penjahitan.</p>
        </div>

        {{-- Ringkasan Pesanan --}}
        <div class="payment-order-summary">
            <div class="summary-row">
                <span>Produk</span>
                <strong>{{ $order->product->name ?? 'Produk' }}</strong>
            </div>
            <div class="summary-row">
                <span>Detail</span>
                <strong>{{ $order->notes }}</strong>
            </div>
            <div class="summary-row total-row">
                <span>Total Pembayaran</span>
                <strong class="total-amount">Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
            </div>
        </div>

        {{-- Info Rekening --}}
        <div class="payment-bank-section">
            <h2>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                Transfer ke Rekening Berikut
            </h2>

            <div class="bank-cards-grid">
                {{-- Rekening Utama --}}
                @if($bankAccount)
                <div class="bank-card">
                    <div class="bank-label">Rekening Utama</div>
                    <div class="bank-name">{{ $bankName }}</div>
                    <div class="bank-number" id="rek-utama">{{ $bankAccount }}</div>
                    <div class="bank-holder">a.n. <strong>{{ $bankAccountName }}</strong></div>
                    <button class="btn-copy" onclick="copyToClipboard('{{ $bankAccount }}', this)">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                        Salin Nomor
                    </button>
                </div>
                @endif

                {{-- Rekening Kedua --}}
                @if($bankAccount2)
                <div class="bank-card bank-card-secondary">
                    <div class="bank-label">Rekening Kedua</div>
                    <div class="bank-name">{{ $bankName2 }}</div>
                    <div class="bank-number">{{ $bankAccount2 }}</div>
                    <div class="bank-holder">a.n. <strong>{{ $bankAccountName2 }}</strong></div>
                    <button class="btn-copy" onclick="copyToClipboard('{{ $bankAccount2 }}', this)">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                        Salin Nomor
                    </button>
                </div>
                @endif

                @if(!$bankAccount && !$bankAccount2)
                <div class="bank-card" style="text-align:center; color:#888; padding: 30px 20px;">
                    <p>Info rekening belum diatur oleh admin.</p>
                    <p style="font-size:12px;">Silakan hubungi kami via WhatsApp.</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Langkah Selanjutnya --}}
        <div class="payment-steps">
            <h2>Langkah Selanjutnya</h2>
            <ol class="steps-list">
                <li>
                    <div class="step-num">1</div>
                    <div>
                        <strong>Lakukan Transfer</strong>
                        <p>Transfer sejumlah <strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong> ke salah satu rekening di atas.</p>
                    </div>
                </li>
                <li>
                    <div class="step-num">2</div>
                    <div>
                        <strong>Simpan Bukti Transfer</strong>
                        <p>Screenshot atau foto struk bukti transfer dari aplikasi bank Anda.</p>
                    </div>
                </li>
                <li>
                    <div class="step-num">3</div>
                    <div>
                        <strong>Upload Bukti di Profil Anda</strong>
                        <p>Buka halaman Profil → tab <em>History Pemesanan</em> → temukan pesanan ini → klik <strong>"Upload Bukti Transfer"</strong>.</p>
                    </div>
                </li>
                <li>
                    <div class="step-num">4</div>
                    <div>
                        <strong>Tunggu Konfirmasi Admin</strong>
                        <p>Admin akan memverifikasi pembayaran Anda. Status pesanan akan berubah ke <em>"Sedang Dikerjakan"</em> setelah dikonfirmasi.</p>
                    </div>
                </li>
            </ol>
        </div>

        {{-- Tombol Aksi --}}
        <div class="payment-actions">
            <a href="{{ route('profile') }}" class="btn-go-profile">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                Ke Halaman Profil (Upload Bukti)
            </a>
            <a href="{{ $waUrl }}" target="_blank" class="btn-wa-payment">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M11.999 2C6.477 2 2 6.477 2 12c0 1.89.525 3.66 1.438 5.168L2 22l4.878-1.428A9.959 9.959 0 0 0 12 22c5.523 0 10-4.477 10-10S17.522 2 11.999 2zm.001 18a7.964 7.964 0 0 1-4.073-1.113l-.292-.173-3.022.885.833-3.068-.19-.315A7.96 7.96 0 0 1 4 12c0-4.418 3.582-8 8-8s8 3.582 8 8-3.582 8-8 8z"/></svg>
                Konfirmasi via WhatsApp
            </a>
        </div>

    </div>
</section>

<style>
.payment-info-section {
    min-height: 100vh;
    background-color: var(--bg-krem);
    padding: 60px 5% 80px;
    display: flex;
    justify-content: center;
    align-items: flex-start;
}
.payment-info-wrapper {
    width: 100%;
    max-width: 680px;
    display: flex;
    flex-direction: column;
    gap: 28px;
}

/* Header Sukses */
.payment-success-header {
    text-align: center;
    padding: 40px 30px 35px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    border-top: 4px solid #10b981;
}
.success-icon-ring {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: linear-gradient(135deg, #10b981, #059669);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    color: white;
    box-shadow: 0 8px 24px rgba(16, 185, 129, 0.35);
}
.payment-success-header h1 {
    font-family: 'Lora', serif;
    font-size: 24px;
    color: var(--cokelat-gelap);
    margin-bottom: 10px;
}
.payment-success-header p {
    color: var(--teks-paragraf);
    font-size: 14px;
    line-height: 1.6;
    margin: 0;
}

/* Ringkasan Pesanan */
.payment-order-summary {
    background: white;
    border-radius: 14px;
    padding: 24px 28px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.04);
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
    font-size: 14px;
    color: var(--teks-paragraf);
    padding-bottom: 12px;
    border-bottom: 1px solid #f0e8dc;
}
.summary-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
}
.summary-row strong {
    text-align: right;
    color: var(--teks-gelap);
    max-width: 60%;
}
.total-row {
    padding-top: 8px;
}
.total-amount {
    font-size: 20px !important;
    color: var(--cokelat-utama) !important;
    font-family: 'Lora', serif;
}

/* Rekening Bank */
.payment-bank-section {
    background: white;
    border-radius: 14px;
    padding: 28px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.04);
}
.payment-bank-section h2,
.payment-steps h2 {
    font-family: 'Lora', serif;
    font-size: 17px;
    color: var(--cokelat-gelap);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 12px;
    border-bottom: 2px solid #f0e8dc;
}
.bank-cards-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
}
.bank-card {
    flex: 1 1 220px;
    background: linear-gradient(135deg, var(--cokelat-gelap), #7a5c45);
    color: white;
    border-radius: 14px;
    padding: 22px 24px;
    position: relative;
    box-shadow: 0 6px 20px rgba(101,73,51,0.25);
}
.bank-card-secondary {
    background: linear-gradient(135deg, #4f7c8a, #355f6b);
    box-shadow: 0 6px 20px rgba(79,124,138,0.25);
}
.bank-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    opacity: 0.7;
    margin-bottom: 10px;
}
.bank-name {
    font-family: 'Lora', serif;
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 14px;
}
.bank-number {
    font-size: 22px;
    font-weight: 700;
    letter-spacing: 3px;
    margin-bottom: 6px;
    font-family: 'Courier New', monospace;
}
.bank-holder {
    font-size: 13px;
    opacity: 0.85;
    margin-bottom: 18px;
}
.btn-copy {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,0.2);
    border: 1.5px solid rgba(255,255,255,0.4);
    color: white;
    padding: 7px 14px;
    border-radius: 8px;
    font-size: 12px;
    font-family: 'Nunito', sans-serif;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
}
.btn-copy:hover {
    background: rgba(255,255,255,0.35);
}
.btn-copy.copied {
    background: rgba(255,255,255,0.9);
    color: var(--cokelat-gelap);
}

/* Langkah */
.payment-steps {
    background: white;
    border-radius: 14px;
    padding: 28px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.04);
}
.steps-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 20px;
}
.steps-list li {
    display: flex;
    gap: 16px;
    align-items: flex-start;
}
.step-num {
    flex-shrink: 0;
    width: 32px;
    height: 32px;
    background: var(--cokelat-utama);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 14px;
    margin-top: 2px;
}
.steps-list li div:last-child strong {
    display: block;
    font-size: 14px;
    color: var(--teks-gelap);
    margin-bottom: 4px;
}
.steps-list li div:last-child p {
    font-size: 13px;
    color: var(--teks-paragraf);
    line-height: 1.6;
    margin: 0;
}

/* Tombol Aksi */
.payment-actions {
    display: flex;
    gap: 14px;
    flex-wrap: wrap;
}
.btn-go-profile {
    flex: 1 1 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: var(--cokelat-utama);
    color: white;
    padding: 14px 24px;
    border-radius: 12px;
    font-weight: 700;
    font-family: 'Nunito', sans-serif;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.3s ease;
    text-align: center;
    box-shadow: 0 4px 14px rgba(156, 122, 99, 0.3);
}
.btn-go-profile:hover {
    background: var(--cokelat-gelap);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(133, 100, 76, 0.4);
}
.btn-wa-payment {
    flex: 1 1 180px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: #25d366;
    color: white;
    padding: 14px 24px;
    border-radius: 12px;
    font-weight: 700;
    font-family: 'Nunito', sans-serif;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.3s ease;
    text-align: center;
    box-shadow: 0 4px 14px rgba(37, 211, 102, 0.3);
}
.btn-wa-payment:hover {
    background: #1ebd59;
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 600px) {
    .payment-info-section {
        padding: 30px 5% 60px;
    }
    .payment-success-header {
        padding: 30px 20px;
    }
    .payment-success-header h1 {
        font-size: 20px;
    }
    .bank-number {
        font-size: 18px;
        letter-spacing: 2px;
    }
    .payment-actions {
        flex-direction: column;
    }
    .summary-row strong {
        max-width: 55%;
    }
}
</style>

<script>
function copyToClipboard(text, btn) {
    navigator.clipboard.writeText(text).then(() => {
        const original = btn.innerHTML;
        btn.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg> Tersalin!`;
        btn.classList.add('copied');
        setTimeout(() => {
            btn.innerHTML = original;
            btn.classList.remove('copied');
        }, 2000);
    }).catch(() => {
        // Fallback
        const el = document.createElement('textarea');
        el.value = text;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
    });
}
</script>
@endsection
