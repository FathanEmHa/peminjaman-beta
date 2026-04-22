SIPA - Sistem Informasi Peminjaman Alat 🛠️
SIPA adalah platform berbasis web yang dirancang untuk mengelola peminjaman dan pengembalian alat secara efisien. Proyek ini dikembangkan sebagai tugas Uji Kompetensi Keahlian (UKK/Ujikom) Rekayasa Perangkat Lunak dan telah mendapatkan predikat Sangat Kompeten.

🚀 Fitur Utama
Manajemen Stok Real-time: Stok alat akan berkurang secara otomatis ketika status peminjaman berubah menjadi "Ongoing" (setelah pengambilan fisik), memastikan data inventaris tetap akurat.

Pelacakan Keterlambatan Otomatis: Dilengkapi dengan sistem pendeteksi keterlambatan otomatis menggunakan Artisan Command dan Scheduler.

Dashboard Interaktif: Memantau jumlah alat tersedia, alat dipinjam, dan status peminjaman secara cepat.

Laporan Terpadu: Sistem pelaporan peminjaman yang memudahkan admin dalam melakukan audit alat.

🛠️ Teknologi yang Digunakan
Framework: Laravel 11

Frontend Interactivity: Livewire 3 & Alpine.js

Styling: Tailwind CSS

Database: MySQL

Environment: Linux Mint (Development)

💻 Cara Menjalankan di Lokal
Pastikan kamu sudah menginstall PHP (>= 8.2), Composer, dan Node.js di perangkatmu.

Clone Repositori

Bash
git clone https://github.com/FathanEmHa/peminjaman-beta.git
cd sipa
Instalasi Dependensi

Bash
composer install
npm install
Konfigurasi Environment
Salin file .env.example menjadi .env dan atur koneksi databasemu.

Bash
cp .env.example .env
php artisan key:generate
Migrasi Database & Seeder

Bash
php artisan migrate --seed
Menjalankan Scheduler (Otomatisasi Status)
Untuk menjalankan pengecekan status overdue secara lokal:

Bash
php artisan schedule:work
Jalankan Aplikasi
Buka dua terminal:

Terminal 1: php artisan serve

Terminal 2: npm run dev

📈 Pengembangan (On Progress)
Aplikasi ini masih dalam tahap pengembangan aktif untuk meningkatkan fungsionalitasnya:

[ ] Deployment: Persiapan migrasi ke VPS dengan dedicated domain.

[ ] Notifikasi: Integrasi pengingat pengembalian alat melalui email/WhatsApp.

[ ] UI/UX Enhancement: Refinement pada antarmuka pengguna agar lebih intuitif.

🤝 Kontribusi
Proyek ini terbuka untuk diskusi dan pengembangan lebih lanjut. Jika ada saran atau ditemukan bug, silakan buka issue atau hubungi saya.
