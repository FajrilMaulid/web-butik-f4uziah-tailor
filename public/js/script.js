document.addEventListener("DOMContentLoaded", function () {
    // 1. Logika Filter Tombol (Tetap dipertahankan)
    const filterBtns = document.querySelectorAll(".filter-btn");
    filterBtns.forEach((btn) => {
        btn.addEventListener("click", function () {
            filterBtns.forEach((b) => b.classList.remove("active"));
            this.classList.add("active");
        });
    });

    // 2. Logika Efek Blur Samping Murni (Tanpa Mengubah Gerakan/Ukuran Card)
    const catalogContainer = document.querySelector(".catalog");
    const catalogCards = document.querySelectorAll(".card");

    function applyEdgeBlur() {
        const containerRect = catalogContainer.getBoundingClientRect();
        const containerLeft = containerRect.left;
        const containerRight = containerRect.right;

        catalogCards.forEach((card) => {
            const cardRect = card.getBoundingClientRect();
            let distanceToEdge = 0;

            // Hitung seberapa jauh kartu bergeser mendekati atau melewati tepi kiri/kanan layar kontainer
            if (cardRect.right < containerLeft + 120) {
                // Mendekati/keluar ke kiri
                distanceToEdge = containerLeft + 120 - cardRect.right;
            } else if (cardRect.left > containerRight - 120) {
                // Mendekati/keluar ke kanan
                distanceToEdge = cardRect.left - (containerRight - 120);
            }

            // Normalisasi efek: progress akan naik dari 0 ke 1 seiring kartu menuju tepi samping
            let progress = distanceToEdge / 180; // Sensitivitas area jangkauan blur (180px)
            progress = Math.min(progress, 1);

            // Hitung efek blur dan redup secara halus
            const blurValue = progress * 4; // Maksimal blur 4px
            const opacityValue = 1 - progress * 0.4; // Maksimal memudar hingga opacity 0.6

            // Terapkan efek visual murni tanpa merusak tata letak fisik
            card.style.filter = `blur(${blurValue}px)`;
            card.style.opacity = opacityValue;
        });
    }

    // Jalankan efek secara real-time setiap kali kontainer di-scroll
    catalogContainer.addEventListener("scroll", () => {
        window.requestAnimationFrame(applyEdgeBlur);
    });

    // POSISI AWAL SEMULA: Halaman dimulai normal dari kiri (Card 1)
    setTimeout(() => {
        catalogContainer.scrollLeft = 0;
        applyEdgeBlur();
    }, 100);

    window.addEventListener("resize", () => {
        window.requestAnimationFrame(applyEdgeBlur);
    });
});

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
