<nav class="navbar">
    <div class="logo">F4UZIAHTAILOR</div>
    <div class="nav-right">
        <ul class="nav-links">
            <li><a href="/#hero">Beranda</a></li>
            <li><a href="/#search">Katalog</a></li>
            <li><a href="/#tentang">Tentang</a></li>
            <li><a href="/#kontak">Kontak</a></li>
        </ul>
        @guest
        <a href="{{ route('login') }}" class="btn-login">Login</a>
        @endguest

        @auth
            <div class="user-actions">
                <a href="/cart" class="icon-btn" title="Keranjang">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                </a>

                <a href="/profile" class="icon-btn" title="Profil Saya" style="{{ Auth::user()->avatar ? 'width: 42px; height: 42px; border-radius: 8px; overflow: hidden; display: flex; align-items: center; justify-content: center; border: 2px solid var(--cokelat-utama); padding: 0;' : '' }}">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    @endif
                </a>
            </div>
        @endauth
    </div>
</nav>
