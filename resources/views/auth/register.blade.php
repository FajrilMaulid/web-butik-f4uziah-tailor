<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - F4UZIAHTAILOR</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div id="preloader">
        <div class="loader-content">
            <div class="spinner"></div>
            <p class="loader-text">F4UZIAHTAILOR</p>
        </div>
    </div>

    <div class="auth-page">
        <a href="/" class="btn-back-home">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Kembali ke Beranda
        </a>
        <div class="auth-card">
            <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=800&auto=format&fit=crop" alt="Boutique" class="auth-img">

            <div class="auth-content">
                <h2>Register Butik</h2>

                <form action="{{ route('register.process') }}" method="POST">
                    @csrf
                    <div class="input-group">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <input type="text" name="name" placeholder="Masukkan Username....." required value="{{ old('name') }}">
                    </div>

                    <div class="input-group">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        <input type="email" name="email" placeholder="Masukkan Email....." required value="{{ old('email') }}">
                    </div>

                    <div class="input-group" style="flex-direction: column; align-items: flex-start; padding: 0;">
                        <div style="display: flex; align-items: center; width: 100%; padding: 0 15px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 12px; flex-shrink: 0;"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>
                            <input
                                type="tel"
                                name="phone_number"
                                id="phone_number"
                                placeholder="Nomer Ponsel (cth: 08123456789)"
                                required
                                minlength="9"
                                maxlength="15"
                                pattern="[0-9+\-\s]{9,15}"
                                value="{{ old('phone_number') }}"
                                oninput="validatePhone(this)"
                                style="width: 100%; padding: 15px 0; border: none; background: transparent; outline: none; font-family: 'Nunito', sans-serif; font-size: 14px; color: var(--teks-gelap);"
                            >
                        </div>
                        <div id="phone-counter" style="font-size: 11px; color: #aaa; padding: 2px 15px 6px; width: 100%; box-sizing: border-box; text-align: right;">0 / 15</div>
                        @error('phone_number')
                            <span style="font-size: 11px; color: #ff6b6b; padding: 0 15px 8px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="input-group">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 18v3c0 .6.4 1 1 1h4v-3h3v-3h2l1.4-1.4a6.5 6.5 0 1 0-4-4Z"></path><circle cx="16.5" cy="7.5" r=".5" fill="currentColor"></circle></svg>
                        <input type="password" name="password" placeholder="Masukkan Kata Sandi....." required>
                    </div>

                    <button type="submit" class="btn-auth">Daftar</button>
                </form>

                <a href="/login" class="auth-link">Kembali</a>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/script.js') }}"></script>
    <script>
        function validatePhone(input) {
            // Hanya izinkan karakter angka, +, -, spasi
            input.value = input.value.replace(/[^0-9+\-\s]/g, '');

            const len = input.value.replace(/[^0-9]/g, '').length;
            const counter = document.getElementById('phone-counter');

            if (counter) {
                counter.textContent = input.value.length + ' / 15';
                if (len < 9) {
                    counter.style.color = '#ff6b6b';
                    counter.textContent = input.value.length + ' / 15 (min. 9 angka)';
                } else if (input.value.length >= 15) {
                    counter.style.color = '#ff6b6b';
                    counter.textContent = '15 / 15 (maks)';
                } else {
                    counter.style.color = '#4caf50';
                }
            }
        }

        // Inisialisasi counter jika ada value awal (old input)
        document.addEventListener('DOMContentLoaded', function() {
            const phoneInput = document.getElementById('phone_number');
            if (phoneInput && phoneInput.value) validatePhone(phoneInput);
        });
    </script>
</body>
</html>
