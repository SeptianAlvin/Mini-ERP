# MoneyTrack (Mini-ERP System)

MoneyTrack adalah web app sederhana bergaya Mini-ERP yang saya kembangkan untuk manajemen keuangan pribadi dan UMKM. Idenya adalah punya satu tempat yang aman buat mencatat arus kas, tapi dengan *rules* keuangan yang ketat supaya datanya valid dan nggak berantakan.

Aplikasi ini sudah mendukung banyak *user* sekaligus dalam satu database, di-desain responsif, dan siap langsung di-deploy ke production.

---

## 🔥 Key Features

Aplikasi ini nggak cuma sekadar aplikasi CRUD biasa. Saya nerapin beberapa *business logic* yang lumayan ketat di *backend*:

### 1. Real-time Analytics Dashboard
Semua metrik finansial utama (Pemasukan, Pengeluaran, Saldo Netto) langsung dikalkulasi dan divisualisasikan secara *real-time*. UI-nya dibuat se-bersih mungkin biar enak dilihat.

### 2. Multi-Tenant Data Isolation
Sistem ini menggunakan arsitektur *Multi-Tenancy* lewat `Global Scopes` bawaan Laravel. Artinya, setiap *user* yang register akan punya "ruang kerja" sendiri. Eksekusi *query* ke database dijamin terisolasi 100%, jadi nggak mungkin ada *bug* data pengguna A nyasar ke pengguna B.

### 3. Strict Financial Integrity (Zero-Balance Enforcement)
Ini fitur keamanan finansial aplikasinya. Saya bikin *validation layer* khusus di mana sistem akan me-reject secara otomatis (baik saat *create*, *update*, atau *delete*) jika aksi tersebut berpotensi bikin Saldo Utama jadi minus / di bawah 0. Jadi, nggak ada lagi cerita "uang fiktif".

### 4. Goal-Oriented Savings (Dream Planner)
Fitur *sinking fund* untuk misahin alokasi dana khusus (misal beli laptop atau biaya nikah). Dana yang dialokasiin ke target ini bakal dikunci dari Saldo Utama. Kalau targetnya udah 100%, uangnya bisa di-withdraw kembali ke Saldo Utama sebagai dana cair.

### 5. Safe Data Recovery (Soft Deletes)
Saya implementasiin *Soft Deletes* di tabel transaksi. Kalau *user* salah hapus data pengeluaran/pemasukan, datanya nggak langsung di-drop dari database (disimpan di Trash). Kalau di-restore, sistem akan ngecek ulang apakah *balance*-nya mencukupi sebelum mengembalikan data tersebut.

---

## 🛠️ Tech Stack

- **Backend:** Laravel 11 (PHP 8.2+)
- **Frontend:** Blade Templating + Tailwind CSS
- **Database:** MySQL / MariaDB

---

## 🚀 Cara Install di Local

Buat yang mau *clone* dan coba *running* di local (pake Laragon atau XAMPP), *setup*-nya gampang banget:

1. Clone repo ini dan masuk ke foldernya via terminal.
2. Install semua *dependencies*:
   ```bash
   composer install
   ```
3. Copy `.env.example` jadi `.env` dan atur konfigurasi DB kalian:
   ```env
   DB_DATABASE=mini-erp
   DB_USERNAME=root
   DB_PASSWORD=
   ```
4. Generate key bawaan Laravel:
   ```bash
   php artisan key:generate
   ```
5. Migrate database:
   ```bash
   php artisan migrate
   ```
6. Tinggal *running*:
   ```bash
   php artisan serve
   ```
   Buka di browser `http://127.0.0.1:8000`.

---

## 🔑 Login

Kalau baru pertama kali jalanin, database pasti kosong. Langsung aja klik **Daftar sekarang** di halaman Login buat bikin akun baru. Begitu register, otomatis dapet *workspace* bersih dengan saldo awal Rp 0.

---

## 🌐 Notes untuk Deployment (Shared Hosting)

Project ini udah saya *tweak* biar *hosting-friendly*, terutama buat *environment* yang maksa pakai SSL kayak Hostinger:

- **Forced HTTPS:** Udah ada *script* di `AppServiceProvider` yang otomatis nge-force *scheme* ke HTTPS kalau status `.env` ada di `production`. Jadi *bye-bye* error *Mixed Content*.
- **Asset Routing:** File statis (logo dll) udah diamankan tanpa perlu repot *setup* `storage:link`.
- Cuma pastiin aja PHP version di cPanel/hPanel kalian udah di-set ke **PHP 8.2**.
