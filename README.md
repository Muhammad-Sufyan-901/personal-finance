# 💰 Finance Tracker: Sistem Kelola Keuangan Pribadi

Aplikasi web responsif dan modern untuk mencatat, mengelola, dan menganalisis arus kas (pemasukan dan pengeluaran) pribadi secara efisien. Proyek ini dibangun dengan menerapkan konsep _Object-Oriented Programming_ (OOP) dan arsitektur _Model-View-Controller_ (MVC) menggunakan framework Laravel untuk memenuhi standar pengembangan perangkat lunak profesional.

---

## ✨ Fitur Utama

- **🔐 Autentikasi Pengguna (Aman & Terenkripsi):**
    - Sistem _Login_ dan _Register_ khusus untuk memisahkan data keuangan antar pengguna.
    - Dilengkapi dengan validasi formulir dan proteksi CSRF.

- **📝 Manajemen Transaksi & Rekapitulasi:**
    - Pencatatan Pemasukan (_Income_) dan Pengeluaran (_Expense_) yang detail (tanggal, nominal, keterangan, **hingga unggah bukti struk transaksi**).
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
- **UI Components:** **daisyUI** (Komponen siap pakai untuk mempercepat _development_)
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

Buka file `.env`, lalu atur bagian _database_ (Pastikan Anda sudah membuat _database_ kosong bernama `personal_finance_db` di MySQL Anda):

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=personal_finance_db
DB_USERNAME=root
DB_PASSWORD=

```

4. **Generate Application Key & Link Storage**

```bash
php artisan key:generate
php artisan storage:link

```

5. **Jalankan Migrasi Database**
   Perintah ini akan membuat skema tabel secara otomatis di _database_ Anda.

```bash
php artisan migrate

```

6. **Jalankan Server Lokal**

```bash
php artisan serve

```

Aplikasi kini dapat diakses melalui browser di alamat: `http://localhost:8000`

---

## 📁 Struktur Folder Utama

Struktur direktori proyek ini mengikuti standar kerangka kerja MVC pada Laravel:

```text
personal-finance/
├── app/
│   ├── Http/
│   │   └── Controllers/    # Controller aplikasi (Logika penanganan request)
│   ├── Models/             # Model ORM (User.php, Transaction.php)
│   └── Services/           # (Opsional) Kelas layanan untuk pemrosesan logika OOP khusus
├── database/
│   └── migrations/         # Skema blueprint tabel database (Cetak biru struktur tabel)
├── public/                 # Entry point aplikasi & tempat penyimpanan aset (CSS, JS, Gambar)
├── resources/
│   └── views/              # Tampilan antarmuka pengguna (UI) menggunakan Laravel Blade
├── routes/
│   └── web.php             # Definisi routing/URL sistem
└── storage/
    └── app/public/         # Tempat penyimpanan unggahan pengguna (contoh: bukti transaksi)

```

---

## 🗄️ Struktur Database

Aplikasi ini menggunakan basis data relasional (MySQL) dengan rancangan arsitektur _One-to-Many_. Berikut adalah struktur tabel utamanya:

### 1. Tabel `users`

Menyimpan data identitas pengguna untuk kebutuhan autentikasi dan kepemilikan data.

| Kolom        | Tipe Data   | Keterangan                                |
| ------------ | ----------- | ----------------------------------------- |
| `id`         | BigInt (PK) | ID unik pengguna                          |
| `name`       | String      | Nama lengkap pengguna                     |
| `email`      | String      | Alamat email (Bersifat unik)              |
| `password`   | String      | Kata sandi (Terenkripsi Bcrypt)           |
| `timestamps` | Timestamp   | Rekaman waktu `created_at` & `updated_at` |

### 2. Tabel `transactions`

Menyimpan rekam jejak arus kas. Terhubung secara relasional dengan tabel `users`.

| Kolom         | Tipe Data     | Keterangan                                                  |
| ------------- | ------------- | ----------------------------------------------------------- |
| `id`          | BigInt (PK)   | ID unik transaksi                                           |
| `user_id`     | BigInt (FK)   | Terhubung ke `id` pada tabel `users` (_Cascade On Delete_)  |
| `type`        | Enum          | Jenis transaksi (`pemasukan`, `pengeluaran`)                |
| `amount`      | Decimal(15,2) | Nominal saldo transaksi                                     |
| `description` | String        | Catatan atau deskripsi transaksi                            |
| `proof`       | String        | Path file bukti transaksi (struk belanja/nota) - _Nullable_ |
| `date`        | Date          | Tanggal terjadinya transaksi                                |
| `timestamps`  | Timestamp     | Rekaman waktu `created_at` & `updated_at`                   |

---

## 🛡️ Keamanan & Privasi Data

- **Password Hashing:** Seluruh kata sandi pengguna dienkripsi secara satu arah (_one-way hashing_) menggunakan algoritma Bcrypt.
- **Session Management:** Menggunakan _session_ Laravel yang terenkripsi untuk mencegah _Session Hijacking_.
- **Data Isolation:** Implementasi relasi _One-to-Many_ memastikan seorang pengguna hanya bisa melihat, mengedit, dan menghapus data transaksinya sendiri.
