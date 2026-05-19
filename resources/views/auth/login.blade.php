<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - F4UZIAHTAILOR</title>
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
                <h2>Login Butik</h2>

                <form action="{{ route('login.process') }}" method="POST">
                    @csrf

                    <div class="input-group">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        <input type="email" name="email" placeholder="Masukkan Email....." required value="{{ old('email') }}">
                    </div>

                    <div class="input-group">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 18v3c0 .6.4 1 1 1h4v-3h3v-3h2l1.4-1.4a6.5 6.5 0 1 0-4-4Z"></path><circle cx="16.5" cy="7.5" r=".5" fill="currentColor"></circle></svg>
                        <input type="password" name="password" placeholder="Masukkan Kata Sandi....." required>
                    </div>
                    @error('email')
                        <p style="color: #ffcccc; font-size: 12px; margin-bottom: 10px;">{{ $message }}</p>
                    @enderror

                    <div class="auth-options">
                        <label><input type="checkbox" name="remember"> Ingat Saya</label>
                        <a href="#">Lupa Password?</a>
                    </div>

                    <button type="submit" class="btn-auth">Masuk</button>
                </form>

                <a href="/register" class="auth-link">Buat Akun</a>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>
