# 🧵 Butik & Tailor F4uziah Tailor (Web-Based Management System)

Aplikasi Web **Butik & Tailor F4uziah Tailor** adalah sistem informasi manajemen butik dan pemesanan jahitan berbasis web yang dirancang khusus untuk mempermudah operasional butik serta memberikan pengalaman belanja dan pemesanan baju custom yang premium bagi pelanggan.

Aplikasi ini dibangun menggunakan framework **Laravel 13** yang mutakhir, dikombinasikan dengan performa kilat **Vite 8** dan keindahan antarmuka utility-first **Tailwind CSS v4.0.0**.

---

## ✨ Fitur Utama

### 🛍️ Untuk Pelanggan (Customer Side)
*   **Autentikasi & Registrasi**: Pendaftaran akun baru dan login terproteksi middleware demi keamanan data pelanggan.
*   **Katalog Busana Interaktif**: Telusuri berbagai koleksi busana butik secara interaktif yang dilengkapi dengan fitur **Pencarian Produk** serta **Filter Kategori**.
*   **Keranjang Belanja Premium**:
    *   Menambahkan pakaian pilihan ke dalam keranjang.
    *   **Custom Size**: Opsi penentuan ukuran baju custom langsung saat menambahkan produk ke keranjang.
    *   Mengatur jumlah kuantitas (*quantity*) pembelian.
*   **Checkout Fleksibel**: Mengirimkan pesanan dari keranjang belanja secara langsung ke database dengan status awal `menunggu` untuk ditinjau oleh Admin.
*   **Manajemen Profil Mandiri**:
    *   Memperbarui nama, email, nomor telepon, dan password.
    *   Upload foto profil (*avatar*) dengan sistem manajemen penyimpanan file yang rapi.
    *   **Riwayat Pesanan (Order History)**: Memantau status pesanan pakaian secara real-time langsung dari halaman profil.

### 👔 Untuk Pengelola (Admin Side)
*   **Dashboard Statistik**: Grafik ringkas untuk memantau data operasional seperti jumlah pelanggan, total produk, kategori, dan pesanan yang masuk.
*   **Manajemen Kategori (CRUD)**: Pengelolaan klasifikasi pakaian (misal: Gamis, Kemeja, Kebaya, Jas, dll).
*   **Manajemen Produk (CRUD)**: Mengelola katalog pakaian lengkap dengan nama, harga, deskripsi, kategori, dan unggah gambar produk berkualitas tinggi.
*   **Manajemen Pengguna (CRUD)**: Memantau dan mengelola akun pelanggan yang terdaftar pada sistem.
*   **Manajemen Pesanan / Orders (CRUD)**: 
    *   Menerima dan memantau pesanan masuk secara real-time.
    *   Memperbarui status pesanan (e.g., *menunggu*, *diproses*, *selesai*, *dibatalkan*).
    *   Membaca catatan kustomisasi ukuran baju pelanggan.

---

## 🛠️ Teknologi & Tools

| Teknologi | Versi | Peran dalam Proyek |
| :--- | :--- | :--- |
| **PHP** | `^8.3` | Bahasa pemrograman backend utama |
| **Laravel Framework** | `^13.8` | Framework backend tangguh dengan MVC arsitektur |
| **Tailwind CSS** | `^4.0.0` | Framework styling modern untuk tampilan premium |
| **Vite** | `^8.0.0` | Build tool super cepat untuk kompilasi asset frontend |
| **Concurrently** | `^9.0.1` | Pengelola proses multi-tasking lokal |

---

## 🚀 Panduan Instalasi dan Setup

Pastikan perangkat Anda sudah terinstal **PHP >= 8.3**, **Composer**, dan **Node.js** sebelum memulai.

### ⚡ Cara Cepat (Satu Perintah Setup)
Proyek ini telah dikonfigurasi dengan script otomasi instalasi di `composer.json`. Jalankan perintah berikut di terminal root proyek Anda:

```bash
composer run setup
```

> [!NOTE]
> Perintah di atas akan otomatis menginstal dependensi PHP (Composer), menyalin konfigurasi `.env`, men-generate App Key, menjalankan migrasi database, menginstal dependensi npm, dan melakukan build aset frontend.

---

### 🪵 Cara Manual (Langkah-demi-Langkah)

Jika ingin melakukan instalasi secara manual, ikuti langkah-langkah di bawah ini:

1.  **Clone / Buka Direktori Proyek**
    Buka folder proyek ini di terminal Anda.

2.  **Salin File Konfigurasi Environment**
    ```bash
    cp .env.example .env
    ```

3.  **Sesuaikan Konfigurasi Database**
    Buka file `.env` yang baru dibuat dan sesuaikan konfigurasi koneksi database Anda (misalnya menggunakan MySQL):
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=db_f4uziah_tailor
    DB_USERNAME=root
    DB_PASSWORD=
    ```

4.  **Instal Dependensi PHP (Composer)**
    ```bash
    composer install
    ```

5.  **Generate Application Key**
    ```bash
    php artisan key:generate
    ```

6.  **Migrasi & Seed Database**
    Jalankan migrasi tabel beserta pengisian data bawaan (seeder):
    ```bash
    php artisan migrate:fresh --seed
    ```

7.  **Instal Dependensi Frontend & Compile Asset**
    ```bash
    npm install
    npm run build
    ```

---

## 🔑 Akun Pengujian Bawaan (Default Accounts)

Database Seeder telah mengonfigurasi dua akun dengan peran (*role*) berbeda untuk kebutuhan simulasi & pengujian:

| Role | Email | Password | Kegunaan |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@tailor.com` | `password123` | Mengakses dashboard admin dan melakukan manajemen data (CRUD) |
| **User** | `fajril@gmail.com` | `password123` | Melakukan simulasi belanja, kustomisasi ukuran baju, dan checkout |

---

## 💻 Menjalankan Aplikasi di Lingkungan Lokal

### ⚡ Cara Cepat (Dev Server All-in-One)
Anda tidak perlu membuka banyak tab terminal untuk menjalankan server local dan build tool secara terpisah. Cukup jalankan perintah kustom berikut di terminal root proyek Anda:

```bash
composer run dev
```

> [!TIP]
> Perintah kustom ini memanfaatkan package `concurrently` untuk meluncurkan:
> 1.  Server PHP Laravel (`php artisan serve`)
> 2.  Queue Listener (`php artisan queue:listen`)
> 3.  Laravel Pail Log Viewer (`php artisan pail`)
> 4.  Vite Dev Server (`npm run dev`)
> 
> Semuanya berjalan secara simultan dalam satu jendela terminal!

### 🪵 Cara Standar
Jika ingin menjalankannya secara manual di terminal terpisah:
*   Terminal 1 (Server Laravel):
    ```bash
    php artisan serve
    ```
*   Terminal 2 (Compiler Frontend):
    ```bash
    npm run dev
    ```

---

## 📂 Struktur Folder Penting

*   `app/Http/Controllers/` : Pengendali logika bisnis aplikasi (Registrasi, Profil, Admin Panel).
*   `app/Models/` : Representasi tabel database Eloquent (User, Product, Category, Order, Measurement).
*   `database/seeders/` : Data awal bawaan untuk pengujian (Produk, Kategori, Order, Akun Bawaan).
*   `resources/views/pages/` : File visual antarmuka pengguna berbasis Blade Templating yang indah.
    *   `resources/views/pages/user/` : Halaman Beranda, Keranjang, dan Profil Pelanggan.
    *   `resources/views/pages/admin/` : Panel Dashboard, Kategori, Produk, Pesanan, dan Manajemen Pengguna.
*   `routes/web.php` : Rute navigasi web lengkap yang dilindungi middleware otentikasi dan pengecekan role admin.

---

## 🆕 Pembaruan & Fitur Baru (Terbaru)

Dalam sesi pembaruan terbaru, beberapa penyempurnaan krusial telah diterapkan guna memperkaya performa, keamanan, dan fungsionalitas sistem:

### 1. ⚙️ Kustomisasi Info Kontak & Footer (Admin Side)
Admin kini memiliki kendali penuh atas informasi kontak butik serta seluruh tata letak **Footer** website secara dinamis melalui halaman **Admin Panel -> Pengaturan Butik**:
*   **Info Kontak**: Alamat, jam operasional, alamat email, dan link embed Google Maps dapat dikustomisasi secara real-time tanpa menyentuh kode.
*   **Tata Letak Footer**: Deskripsi singkat, nomor telepon tambahan footer, username Instagram, dan teks hak cipta (*copyright*) dapat disesuaikan secara dinamis.
*   **Tautan Sosial Media Aktif**: Username Instagram yang dimasukkan oleh admin akan otomatis tersemat di bagian footer sebagai link aktif (*clickable link*) ke profil Instagram butik dengan efek hover warna cokelat utama khas yang premium.

### 2. 🔍 Fitur Pencarian Data Admin (Search Bar)
Untuk mempercepat pencarian data bagi administrator, fitur **Pencarian Real-time** terintegrasi telah ditambahkan pada 4 modul utama di sisi admin:
*   **Kelola Kategori**: Cari kategori baju berdasarkan nama kategori.
*   **Kelola Produk**: Cari baju berdasarkan nama produk atau isi deskripsi.
*   **Kelola Transaksi**: Cari pesanan berdasarkan nama pembeli, catatan kustom ukuran, atau nama produk.
*   **Kelola Pengguna**: Cari akun pelanggan berdasarkan nama, nomor telepon, atau email.
*   *Dilengkapi dengan retensi pencarian berbasis query-string saat berpindah halaman (pagination).*

### 3. 🛡️ Validasi Keamanan Nomor Telepon
Untuk mencegah pendaftaran akun asal yang berlebihan atau pembaruan profil yang tidak valid:
*   Pembatasan panjang nomor telepon diterapkan secara ketat dengan batas minimal **9 angka** dan maksimal **15 angka**.
*   Sistem validasi Regex memastikan input nomor telepon hanya menerima karakter angka, simbol `+`, tanda hubung `-`, dan spasi.
*   Disertai dengan fitur visual **JS Character Counter** dinamis pada form registrasi yang akan memandu pengguna secara real-time.

### 4. 📱 Penyempurnaan Tampilan Responsif (Mobile View)
*   **Hero Responsive CSS**: Menata ulang posisi hero banner di perangkat mobile. Gambar hero kini diposisikan secara seimbang di atas tombol *Call-to-Action (CTA)*, bukan disembunyikan seluruhnya ataupun ditaruh di bawah tombol yang dapat merusak alur visual pembeli.
*   **Hapus Garis Tombol Profil**: Menghilangkan ornamen garis di dalam tombol simpan perubahan pada menu profil user untuk memberikan tampilan yang lebih minimalis dan elegan.
*   **Reduksi Efek Blur Tepi HP**: Pada layar desktop, efek blur tepi `4px` dan reduksi opacity pada katalog carousel memberikan nuansa kedalaman visual. Namun pada mode responsif (lebar layar `<= 768px`), efek blur tersebut dikurangi secara dinamis menjadi maksimal **`1.2px`** dan opacity dipertahankan tinggi (maksimal reduksi `0.15`) agar katalog baju samping tetap cerah, tajam, dan mudah teridentifikasi oleh pengguna seluler.

### 🗄️ Penyempurnaan Seeder Database (`SettingSeeder`)
*   Seluruh parameter pengaturan dinamis, teks banner, profil tentang butik, hingga data footer di atas kini telah terintegrasi di dalam **`SettingSeeder.php`** dan terdaftar di **`DatabaseSeeder.php`**.
*   Data pesanan dummy di **`OrderSeeder.php`** kini berhasil ter-seed secara sempurna karena urutan eksekusi seeder telah dikoreksi agar data user dibuat terlebih dahulu sebelum relasi order dipanggil.
