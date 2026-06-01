document.addEventListener("DOMContentLoaded", function () {
    // 1. Logika Filter Tombol
    const filterBtns = document.querySelectorAll(".filter-btn");
    filterBtns.forEach((btn) => {
        btn.addEventListener("click", function () {
            filterBtns.forEach((b) => b.classList.remove("active"));
            this.classList.add("active");
        });
    });

    // ==========================================
    // 2. SLIDER KATALOG (Arrow + Dot + Auto-Slide + Drag + Swipe)
    // ==========================================
    const catalogScroll = document.getElementById("catalog-scroll");
    const catalogTrack = document.getElementById("catalog-track");
    const prevBtn = document.getElementById("catalog-prev");
    const nextBtn = document.getElementById("catalog-next");
    const dotsContainer = document.getElementById("catalog-dots");
    const catalogCards = document.querySelectorAll(".card");

    if (catalogScroll && catalogTrack && catalogCards.length > 0) {

    const CARD_WIDTH = 300;
    const CARD_GAP = 25;
    const totalCards = catalogCards.length;
    let currentIndex = 0;
    let isTransitioning = false;

    // ---- Buat Dot Indicators / Progress Bar ----
    function buildDots() {
        if (!dotsContainer) return;
        dotsContainer.innerHTML = "";
        if (totalCards <= 1) return;

        if (totalCards <= 8) {
            dotsContainer.classList.remove('is-progress');
            catalogCards.forEach((_, i) => {
                const dot = document.createElement("button");
                dot.classList.add("catalog-dot");
                dot.setAttribute("aria-label", `Slide ${i + 1}`);
                if (i === 0) dot.classList.add("active");
                dot.addEventListener("click", () => goToIndex(i));
                dotsContainer.appendChild(dot);
            });
        } else {
            dotsContainer.classList.add('is-progress');
            
            const progressTrack = document.createElement("div");
            progressTrack.classList.add("catalog-progress-track");
            
            const progressBar = document.createElement("div");
            progressBar.classList.add("catalog-progress-bar");
            progressBar.id = "catalog-progress-bar";
            progressTrack.appendChild(progressBar);

            const counter = document.createElement("div");
            counter.classList.add("catalog-progress-counter");
            counter.id = "catalog-progress-counter";
            counter.innerText = `1 / ${totalCards}`;

            dotsContainer.appendChild(progressTrack);
            dotsContainer.appendChild(counter);
            
            updateProgress(0);
        }
    }

    function updateDots(index) {
        if (!dotsContainer) return;
        if (totalCards <= 8) {
            const dots = dotsContainer.querySelectorAll(".catalog-dot");
            dots.forEach((d, i) => d.classList.toggle("active", i === index));
        } else {
            updateProgress(index);
        }
    }

    function updateProgress(index) {
        const progressBar = document.getElementById("catalog-progress-bar");
        const counter = document.getElementById("catalog-progress-counter");
        if (progressBar && counter) {
            const thumbWidth = 100 / totalCards;
            progressBar.style.width = `${thumbWidth}%`;
            progressBar.style.transform = `translateX(${index * 100}%)`;
            counter.innerText = `${index + 1} / ${totalCards}`;
        }
    }

    // ---- Hitung scroll target (card ditengahkan di viewport) ----
    function getScrollTarget(index) {
        const card = catalogCards[index];
        if (!card) return 0;
        const cardOffsetLeft = card.offsetLeft;
        const containerWidth = catalogScroll.clientWidth;
        const centered = cardOffsetLeft - (containerWidth / 2) + (CARD_WIDTH / 2);
        return Math.max(0, centered);
    }

    function goToIndex(index, smooth = true) {
        if (isTransitioning && smooth) return;
        index = Math.max(0, Math.min(index, totalCards - 1));
        currentIndex = index;
        const target = getScrollTarget(index);
        catalogScroll.scrollTo({ left: target, behavior: smooth ? "smooth" : "instant" });
        updateDots(index);
        updateArrows();
        if (smooth) {
            isTransitioning = true;
            setTimeout(() => { isTransitioning = false; }, 500);
        }
    }

    function updateArrows() {
        if (!prevBtn || !nextBtn) return;
        prevBtn.classList.toggle("disabled", currentIndex <= 0);
        nextBtn.classList.toggle("disabled", currentIndex >= totalCards - 1);
    }

    // ---- Snap ke card terdekat (dinonaktifkan per permintaan) ----
    // Auto-snap dihapus agar card tidak berpindah sendiri setelah drag/swipe

    // ---- Sync index dari posisi scroll tanpa memindahkan card ----
    function syncIndexFromScroll() {
        const containerCenter = catalogScroll.scrollLeft + catalogScroll.clientWidth / 2;
        let closestIndex = 0;
        let closestDist = Infinity;
        catalogCards.forEach((card, i) => {
            const cardCenter = card.offsetLeft + CARD_WIDTH / 2;
            const dist = Math.abs(cardCenter - containerCenter);
            if (dist < closestDist) {
                closestDist = dist;
                closestIndex = i;
            }
        });
        currentIndex = closestIndex;
        updateDots(currentIndex);
        updateArrows();
    }

    // ---- Arrow button events ----
    if (prevBtn) {
        prevBtn.addEventListener("click", () => {
            goToIndex(currentIndex - 1);
        });
    }
    if (nextBtn) {
        nextBtn.addEventListener("click", () => {
            goToIndex(currentIndex + 1);
        });
    }

    // ==========================================
    // DRAG dengan Mouse (Desktop)
    // ==========================================
    let isDragging = false;
    let dragStartX = 0;
    let dragScrollLeft = 0;
    let hasDragged = false;
    const DRAG_THRESHOLD = 5; // px minimum sebelum dianggap drag

    catalogScroll.addEventListener("mousedown", (e) => {
        // Jangan drag jika klik pada tombol detail
        if (e.target.closest(".btn-detail")) return;
        isDragging = true;
        hasDragged = false;
        dragStartX = e.pageX - catalogScroll.offsetLeft;
        dragScrollLeft = catalogScroll.scrollLeft;
        catalogScroll.classList.add("is-dragging");
        catalogScroll.style.scrollBehavior = "auto";
        e.preventDefault();
    });

    document.addEventListener("mousemove", (e) => {
        if (!isDragging) return;
        const x = e.pageX - catalogScroll.offsetLeft;
        const walk = (x - dragStartX) * 1.5; // faktor kecepatan drag
        if (Math.abs(walk) > DRAG_THRESHOLD) hasDragged = true;
        catalogScroll.scrollLeft = dragScrollLeft - walk;
    });

    document.addEventListener("mouseup", () => {
        if (!isDragging) return;
        isDragging = false;
        catalogScroll.classList.remove("is-dragging");
        catalogScroll.style.scrollBehavior = "smooth";
        // Update index berdasarkan posisi scroll saat ini (tanpa snap)
        syncIndexFromScroll();
    });

    // Jika mouse keluar dari window saat drag
    document.addEventListener("mouseleave", () => {
        if (isDragging) {
            isDragging = false;
            catalogScroll.classList.remove("is-dragging");
            catalogScroll.style.scrollBehavior = "smooth";
            syncIndexFromScroll();
        }
    });

    // ==========================================
    // SWIPE dengan Jari (Mobile / Touch)
    // ==========================================
    let touchStartX = 0;
    let touchStartScrollLeft = 0;
    let isTouching = false;

    catalogScroll.addEventListener("touchstart", (e) => {
        isTouching = true;
        touchStartX = e.touches[0].clientX;
        touchStartScrollLeft = catalogScroll.scrollLeft;
        catalogScroll.style.scrollBehavior = "auto";
    }, { passive: true });

    catalogScroll.addEventListener("touchmove", (e) => {
        if (!isTouching) return;
        const deltaX = touchStartX - e.touches[0].clientX;
        catalogScroll.scrollLeft = touchStartScrollLeft + deltaX;
    }, { passive: true });

    catalogScroll.addEventListener("touchend", () => {
        isTouching = false;
        catalogScroll.style.scrollBehavior = "smooth";
        // Update index tanpa snap otomatis
        syncIndexFromScroll();
    });

    // ==========================================
    // BLUR TEPI + SCROLL TRACKING
    // ==========================================
    function applyEdgeBlur() {
        if (!catalogScroll) return;
        const containerRect = catalogScroll.getBoundingClientRect();
        const containerLeft = containerRect.left;
        const containerRight = containerRect.right;

        // Cek mode responsif / mobile (lebar layar <= 768px)
        const isResponsive = window.innerWidth <= 768;
        const maxBlur = isResponsive ? 1.2 : 4; // Mengurangi blur maksimum di HP dari 4px menjadi 1.2px
        const maxOpacityReduction = isResponsive ? 0.15 : 0.4; // Mengurangi transparansi tepi di HP dari 0.4 menjadi 0.15

        catalogCards.forEach((card) => {
            const cardRect = card.getBoundingClientRect();
            let distanceToEdge = 0;

            if (cardRect.right < containerLeft + 120) {
                distanceToEdge = containerLeft + 120 - cardRect.right;
            } else if (cardRect.left > containerRight - 120) {
                distanceToEdge = cardRect.left - (containerRight - 120);
            }

            let progress = Math.min(distanceToEdge / 180, 1);
            card.style.filter = `blur(${progress * maxBlur}px)`;
            card.style.opacity = 1 - progress * maxOpacityReduction;
        });
    }

    catalogScroll.addEventListener("scroll", () => {
        window.requestAnimationFrame(applyEdgeBlur);
    });

    window.addEventListener("resize", () => {
        window.requestAnimationFrame(applyEdgeBlur);
    });

    // Cegah click event pada card jika habis drag
    catalogTrack.addEventListener("click", (e) => {
        if (hasDragged) {
            e.stopPropagation();
            e.preventDefault();
            hasDragged = false;
        }
    }, true);

    // ---- Inisialisasi ----
    buildDots();
    updateArrows();
    setTimeout(() => {
        catalogScroll.scrollLeft = 0;
        applyEdgeBlur();
    }, 150);
    }

    // ==========================================
    // 3. RESPONSIVE MOBILE NAVBAR (BURGER MENU)
    // ==========================================
    const menuToggle = document.getElementById("menu-toggle");
    const navMenu = document.getElementById("nav-menu");

    if (menuToggle && navMenu) {
        // Create backdrop dynamically
        const backdrop = document.createElement("div");
        backdrop.classList.add("nav-backdrop");
        document.body.appendChild(backdrop);

        function toggleMenu() {
            menuToggle.classList.toggle("active");
            navMenu.classList.toggle("active");
            backdrop.classList.toggle("active");
            
            // Toggle body scroll lock when menu is active
            if (navMenu.classList.contains("active")) {
                document.body.style.overflow = "hidden";
            } else {
                document.body.style.overflow = "";
            }
        }

        menuToggle.addEventListener("click", toggleMenu);
        backdrop.addEventListener("click", toggleMenu);

        // Close menu when clicking on nav links
        const navLinks = navMenu.querySelectorAll(".nav-links a");
        navLinks.forEach(link => {
            link.addEventListener("click", () => {
                if (navMenu.classList.contains("active")) {
                    toggleMenu();
                }
            });
        });
    }

}); // end DOMContentLoaded

// Fitur Auto-Hide Preloader saat Seluruh Aset Selesai Dimuat
window.addEventListener("load", function () {
    const preloader = document.getElementById("preloader");

    // Berikan sedikit jeda buatan (300ms) agar transisinya terasa lembut
    // dan tidak hilang terlalu mendadak jika internet sangat cepat
    setTimeout(() => {
        if (preloader) {
            preloader.classList.add("fade-out");
        }
    }, 300);
});

const transitionLinks = document.querySelectorAll(
    'a[href="/login"], a[href="/register"], .btn-login',
);

transitionLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
        // Cegah browser berpindah halaman secara instan
        e.preventDefault();

        const targetUrl = this.href;
        const preloader = document.getElementById("preloader");

        if (preloader) {
            // Munculkan kembali loading screen dengan menghapus class fade-out
            preloader.classList.remove("fade-out");

            // Tunggu 400 milidetik agar animasi loading muncul sempurna, lalu pindah halaman
            setTimeout(() => {
                window.location.href = targetUrl;
            }, 400);
        } else {
            // Jika preloader tidak ada, langsung pindah halaman normal
            window.location.href = targetUrl;
        }
    });
});

// ==========================================
// LOGIKA POP-UP DETAIL BAJU (Anti-Loncat Sempurna)
// ==========================================
const modalDetail = document.getElementById("modal-detail");
const closeBtn = document.getElementById("close-modal");
const detailButtons = document.querySelectorAll(".btn-detail");

function getScrollbarWidth() {
    return window.innerWidth - document.documentElement.clientWidth;
}

// 1. Membuka Pop-up
detailButtons.forEach((btn) => {
    btn.addEventListener("click", function (e) {
        e.preventDefault();

        // Ambil data dari atribut tombol
        const productId = this.getAttribute('data-id');
        const name = this.getAttribute('data-name');
        const price = this.getAttribute('data-price');
        const desc = this.getAttribute('data-desc');
        const image = this.getAttribute('data-image');

        // Reset state form
        document.getElementById('modal-product-id').value = productId;
        document.getElementById('qty-value').textContent = "1";
        document.getElementById('modal-selected-qty').value = "1";
        const notesInput = document.getElementById('modal-notes-input');
        if (notesInput) notesInput.value = "";
        
        // Reset size selection ke Custom
        const sizeBtns = document.querySelectorAll('.size-btn');
        sizeBtns.forEach(b => b.classList.remove('active'));
        const customBtn = document.querySelector('.size-btn[data-size="Custom"]');
        if(customBtn) customBtn.classList.add('active');
        document.getElementById('modal-selected-size').value = "Custom";

        // Isi konten modal dengan data
        if (name) document.getElementById('modal-title-display').textContent = name;
        if (price) document.getElementById('modal-price-display').textContent = price;
        if (desc) document.getElementById('modal-desc-display').textContent = desc;
        if (image) {
            const imgEl = document.getElementById('modal-image-display');
            imgEl.src = image;
            imgEl.alt = "Foto " + name;
        }

        const scrollbarWidth = getScrollbarWidth();
        // Tahan background agar tidak loncat
        document.body.style.paddingRight = `${scrollbarWidth}px`;

        // KUNCI 1: Beri padding juga ke pop-up agar titik tengahnya tetap absolut
        if (modalDetail) {
            modalDetail.style.paddingRight = `${scrollbarWidth}px`;
            modalDetail.classList.add("active");
        }

        document.body.style.overflow = "hidden";
    });
});

// 2. Fungsi khusus menutup pop-up dengan Delay (Menunggu animasi selesai)
function closeModal() {
    if (modalDetail) {
        modalDetail.classList.remove("active");

        // KUNCI 2: Tunggu 300 milidetik (sesuai waktu transisi memudar di CSS)
        setTimeout(() => {
            document.body.style.overflow = "";
            document.body.style.paddingRight = "0px";
            modalDetail.style.paddingRight = "0px";
        }, 300);
    }
}

// Eksekusi penutupan saat tombol X diklik
if (closeBtn) {
    closeBtn.addEventListener("click", closeModal);
}

// Eksekusi penutupan saat area gelap (overlay) diklik
if (modalDetail) {
    modalDetail.addEventListener("click", function (e) {
        if (e.target === modalDetail) {
            closeModal();
        }
    });
}

// ==========================================
// LOGIKA PILIH UKURAN & JUMLAH (KERANJANG)
// ==========================================
const sizeBtns = document.querySelectorAll('.size-btn');
const inputSize = document.getElementById('modal-selected-size');

sizeBtns.forEach(btn => {
    btn.addEventListener('click', function() {
        // Hapus class active dari semua tombol
        sizeBtns.forEach(b => b.classList.remove('active'));
        // Tambahkan ke tombol yang diklik
        this.classList.add('active');
        // Update input hidden
        inputSize.value = this.getAttribute('data-size');
    });
});

const qtyMinus = document.getElementById('qty-minus');
const qtyPlus = document.getElementById('qty-plus');
const qtyValueDisplay = document.getElementById('qty-value');
const inputQty = document.getElementById('modal-selected-qty');

if (qtyMinus && qtyPlus) {
    qtyMinus.addEventListener('click', function() {
        let currentQty = parseInt(inputQty.value);
        if (currentQty > 1) {
            currentQty--;
            inputQty.value = currentQty;
            qtyValueDisplay.textContent = currentQty;
        }
    });

    qtyPlus.addEventListener('click', function() {
        let currentQty = parseInt(inputQty.value);
        currentQty++;
        inputQty.value = currentQty;
        qtyValueDisplay.textContent = currentQty;
    });
}

// ==========================================
// LOGIKA TABS PROFIL (AKUN & HISTORY)
// ==========================================
function openProfileTab(tabName) {
    // 1. Sembunyikan semua konten tab
    const tabContents = document.querySelectorAll(".profile-tab-content");
    tabContents.forEach((content) => {
        content.classList.remove("active");
    });

    // 2. Hilangkan garis bawah (active) dari semua tombol tab
    const tabButtons = document.querySelectorAll(".tab-btn");
    tabButtons.forEach((btn) => {
        btn.classList.remove("active");
    });

    // 3. Tampilkan tab yang dipilih
    document.getElementById("tab-" + tabName).classList.add("active");

    // 4. Berikan garis bawah pada tombol yang diklik
    document.getElementById("btn-tab-" + tabName).classList.add("active");
}
