# 💸 MoneyTrack (Mini-ERP System)

Selamat datang di **MoneyTrack**, sebuah aplikasi manajemen keuangan pribadi & bisnis skala kecil (Mini-ERP) yang dirancang untuk menjadi asisten finansial andalan Anda. 

Aplikasi ini dibuat dengan antarmuka yang sangat modern, ramah pengguna, dan dibekali dengan tingkat keamanan data *(SaaS Multi-Tenancy)* kelas profesional.

---

## ✨ Fitur Utama (Features)

Aplikasi ini bukan sekadar pencatat uang biasa, melainkan memiliki perlindungan cerdas di dalamnya:

1. **Dashboard Cerdas & Analitik:** 
   Melihat ringkasan total Pemasukan, Pengeluaran, dan Saldo Utama Anda secara *Real-Time* dengan tampilan grafik (UI) yang elegan.
2. **Sistem Banyak Akun (Multi-Tenancy):** 
   Setiap orang bisa mendaftar dan memiliki akunnya sendiri. Data Anda **100% aman dan terisolasi** dari pengguna lain meskipun berada di database yang sama.
3. **Pawang Saldo (Financial Integrity):** 
   Aplikasi ini sangat ketat. Anda **tidak bisa** melakukan pengeluaran yang membuat saldo Anda menjadi minus. Sistem akan memblokir transaksi fiktif tersebut untuk mencegah "uang gaib".
4. **Perencana Impian (Dream Planner):** 
   Punya target beli Laptop, Motor, atau Biaya Nikah? Buat *Dream Planner* Anda, sisihkan uang dari saldo utama Anda ke dalam celengan khusus ini, lalu pantau persentase progresnya hingga 100%!
5. **Tong Sampah Pintar (Soft Deletes):** 
   Menghapus data transaksi karena tidak sengaja? Jangan panik, data Anda tidak benar-benar hilang melainkan masuk ke "Tong Sampah" dan bisa dikembalikan lagi kapan saja.

---

## 🛠️ Teknologi yang Digunakan

Proyek ini dibangun menggunakan teknologi web modern:
- **Framework Utama:** [Laravel 11](https://laravel.com/) (PHP)
- **Desain & UI:** [Tailwind CSS](https://tailwindcss.com/)
- **Database:** MySQL / SQLite
- **Font & Ikon:** Google Fonts (Inter) & Heroicons

---

## 🚀 Panduan Instalasi (Untuk Developer / Profesional)

Jika Anda ingin menjalankan aplikasi ini di komputer lokal Anda (menggunakan Laragon / XAMPP), ikuti langkah-langkah mudah berikut:

### Persyaratan Sistem:
- **PHP 8.2** atau yang lebih baru.
- Composer terinstal.
- Node.js & NPM terinstal (opsional, untuk *compile* CSS jika ingin dimodifikasi).

### Langkah-langkah:

1. **Buka Terminal / Command Prompt**, lalu masuk ke folder project ini.
2. **Install Dependensi PHP:**
   ```bash
   composer install
   ```
3. **Siapkan File Konfigurasi Lingkungan:**
   - Duplikat file `.env.example` dan ubah namanya menjadi `.env`
   - Buka file `.env` dan atur koneksi database Anda (biasanya `DB_DATABASE=mini-erp`, `DB_USERNAME=root`, `DB_PASSWORD=`).
4. **Generate App Key:**
   ```bash
   php artisan key:generate
   ```
5. **Migrasi Database & Data Awal:**
   Jalankan perintah ini untuk membangun tabel database beserta akun bawaan (Admin):
   ```bash
   php artisan migrate --seed
   ```
6. **Jalankan Aplikasi:**
   Jika tidak menggunakan server seperti Laragon, jalankan:
   ```bash
   php artisan serve
   ```
   Lalu buka browser Anda di `http://127.0.0.1:8000`.

---

## 🔑 Akses Akun (Login)
Setelah aplikasi berhasil dijalankan, Anda dapat mengklik tombol **"Daftar sekarang"** di halaman Login untuk membuat akun Anda sendiri dari awal dengan lembaran yang bersih (Saldo Rp 0).

*(Jika Anda mengunduh project ini beserta database bawaannya, Anda mungkin sudah memiliki akun demo, namun disarankan untuk membuat akun baru demi keamanan data Anda sendiri).*

---

## 🌐 Catatan Untuk *Hosting* (Cpanel / Hostinger)

Aplikasi ini sudah dikonfigurasi agar ramah-hosting *(Hosting-Friendly)*:
- Sistem **Otomatis memaksa jalur HTTPS** (bebas dari error *Mixed Content*).
- Asset gambar (seperti logo) sudah diposisikan agar bisa langsung dibaca oleh *server* tanpa memerlukan perintah rumit seperti `storage:link`.
- Pastikan saja opsi versi PHP di *Hosting* Anda sudah disetel minimal ke **PHP 8.2**.

---
