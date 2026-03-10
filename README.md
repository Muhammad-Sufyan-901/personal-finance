# 💰 Finance Tracker: Sistem Kelola Keuangan Pribadi

Aplikasi web responsif dan modern untuk mencatat, mengelola, dan menganalisis arus kas (pemasukan dan pengeluaran) pribadi secara efisien. Proyek ini dibangun dengan menerapkan konsep _Object-Oriented Programming_ (OOP) dan arsitektur _Model-View-Controller_ (MVC) menggunakan framework Laravel untuk memenuhi standar pengembangan perangkat lunak profesional.

---

## ✨ Fitur Utama

- **🔐 Autentikasi Pengguna (Aman & Terenkripsi):**
    - Sistem _Login_ dan _Register_ khusus untuk memisahkan data keuangan antar pengguna.
    - Dilengkapi dengan validasi formulir dan proteksi CSRF.

- **📝 Manajemen Transaksi & Rekapitulasi:**
    - Pencatatan Pemasukan (_Income_) dan Pengeluaran (_Expense_) yang detail (tanggal, nominal, dan keterangan).
    - _Dashboard_ interaktif yang menampilkan kalkulasi otomatis Total Saldo, Total Pemasukan, dan Total Pengeluaran.

- **🔍 Filter & Pencarian Cerdas:**
    - Pengguna dapat memfilter riwayat transaksi berdasarkan jenis transaksi atau rentang waktu tertentu, memudahkan pelacakan arus kas.

- **📥 Ekspor Data (CSV):**
    - Fitur pengunduhan laporan keuangan (_Export to CSV_). Data dapat langsung dibuka dan dianalisis lebih lanjut menggunakan Microsoft Excel atau Google Sheets.

---

## 🛠️ Tech Stack & Library

Aplikasi ini dibangun menggunakan teknologi modern untuk memastikan performa, keamanan, dan keindahan antarmuka:

- **Backend:** PHP 8.x (Laravel Framework)
- **Frontend:** HTML5, Laravel Blade, **Tailwind CSS** (via CDN)
- **Date Formatter:** **Carbon** (Untuk memformat tanggal)
- **Database:** MySQL
- **Arsitektur:** Model-View-Controller (MVC)

---

## 🚀 Panduan Instalasi (Local Development)

Ikuti langkah-langkah di bawah ini untuk menjalankan aplikasi di mesin lokal Anda:

### Prasyarat

- PHP >= 8.1
- Composer terinstal
- MySQL / MariaDB (XAMPP/Laragon) terinstal

### Langkah-langkah

1. **Clone Repositori**

```bash
git clone https://github.com/Muhammad-Sufyan-901/personal-finance.git
cd personal-finance
```

2. **Instal Dependensi PHP**

```bash
composer install

```

3. **Konfigurasi Environment**
   Duplikat file konfigurasi bawaan dan sesuaikan kredensial _database_ Anda.

```bash
cp .env.example .env

```

Buka file `.env`, lalu atur bagian _database_ (Pastikan Anda sudah membuat _database_ kosong bernama `personal_finance_db` di phpMyAdmin):

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=personal_finance_db
DB_USERNAME=root
DB_PASSWORD=

```

4. **Generate Application Key**

```bash
php artisan key:generate

```

5. **Jalankan Migrasi Database**
   Perintah ini akan membuat struktur tabel `users` dan `transactions` secara otomatis.

```bash
php artisan migrate

```

6. **Jalankan Server Lokal**

```bash
php artisan serve

```

Aplikasi kini dapat diakses melalui browser di alamat: `http://localhost:8000`

---

## 🛡️ Keamanan & Privasi Data

- **Password Hashing:** Seluruh kata sandi pengguna dienkripsi secara satu arah (_one-way hashing_) menggunakan algoritma Bcrypt.
- **Session Management:** Menggunakan _session_ Laravel yang terenkripsi untuk mencegah _Session Hijacking_.
- **Data Isolation:** Implementasi relasi _One-to-Many_ memastikan seorang pengguna hanya bisa melihat dan memodifikasi data transaksinya sendiri.
