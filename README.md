# SIPA - Sistem Informasi Peminjaman Alat 🛠️

SIPA adalah platform berbasis web yang dirancang untuk mengelola peminjaman dan pengembalian alat secara efisien. Proyek ini dikembangkan sebagai tugas Uji Kompetensi Keahlian (UKK/Ujikom) Rekayasa Perangkat Lunak dan telah mendapatkan predikat Sangat Kompeten.

## 🚀 Fitur Utama

*   **Manajemen Stok Real-time**: Stok alat akan berkurang secara otomatis ketika status peminjaman berubah menjadi "Ongoing" (setelah pengambilan fisik), memastikan data inventaris tetap akurat.
*   **Pelacakan Keterlambatan Otomatis**: Dilengkapi dengan sistem pendeteksi keterlambatan otomatis menggunakan Artisan Command dan Scheduler.
*   **Dashboard Interaktif**: Memantau jumlah alat tersedia, alat dipinjam, dan status peminjaman secara cepat.
*   **Laporan Terpadu**: Sistem pelaporan peminjaman dan pencatatan log aktivitas otomatis yang memudahkan admin dalam melakukan audit.
*   **Otorisasi Multi-Peran**: Sistem hak akses yang membedakan fitur untuk Admin (Master Data), Petugas (Verifikasi), dan Peminjam (Pengajuan).

---

## 🏗️ Arsitektur Sistem & Alur Kerja

### 1. Flowchart Sistem
Sistem ini mengakomodasi siklus secara *end-to-end*: mulai dari fase pengajuan (booking), verifikasi persetujuan oleh petugas, serah terima fisik (otomasi potong stok), hingga fase pengembalian barang.

![Flowchart Alur Peminjaman]<img width="5388" height="8192" alt="Aset Peminjaman dan-2026-04-05-015222" src="https://github.com/user-attachments/assets/128eccba-9ace-4c86-8533-ce1e49743305" />

### 2. Conceptual Data Model (ERD)
Pemodelan data konseptual (Notasi Chen) yang memetakan entitas utama beserta atribut dasarnya. Pusat alur data berada pada entitas `loans` yang mengikat data pengguna (`users`) dan detail aset (`assets`).

![ERD Conceptual SIPA]<img width="764" height="452" alt="ERD Bulet drawio (3)" src="https://github.com/user-attachments/assets/fa409352-0b3b-4af0-8521-47508e39b9f1" />

### 3. Physical Data Model (Class Diagram)
Rancangan struktur basis data yang berpusat pada relasi transaksional. Menampilkan relasi *One-to-Many* (seperti `Users` ke `Loans`) dan *One-to-One* (seperti `Loans` ke `Returns`).

![Class Diagram SIPA]<img width="1348" height="1060" alt="Peminjaman-Beta drawio" src="https://github.com/user-attachments/assets/fc59075b-8e4f-47dd-a8d1-c17386a71788" />

---

## 📸 Dokumentasi Antarmuka (Screenshots)

Berikut adalah tampilan antarmuka dari fitur-fitur inti SIPA:

### Autentikasi & Dasbor Peminjam
Proses *login* dengan *Role-Based Access Control* dan dasbor pemantauan riwayat peminjaman pengguna.

![Halaman Login & Dashboard]<img width="1366" height="768" alt="Screenshot from 2026-05-05 15-23-46" src="https://github.com/user-attachments/assets/d8fb7898-3532-4b14-ab8c-4e34d5f0f14e" />

### Manajemen Aset (Admin)
Halaman bagi Admin untuk mengelola Master Data, menambah aset baru, dan memantau ketersediaan stok.

![Halaman Manajemen Aset]<img width="1366" height="768" alt="Screenshot from 2026-05-05 15-25-38" src="https://github.com/user-attachments/assets/be3b1a43-1a99-4888-9057-8e472d860344" />

### Pengajuan & Validasi Stok (Peminjam)
Halaman pengajuan barang. Sistem dilengkapi validasi penolakan otomatis jika kuantitas yang dipinjam melebihi sisa stok yang tersedia.

![Form Pengajuan Peminjaman]<img width="1366" height="768" alt="Screenshot from 2026-05-05 15-28-32" src="https://github.com/user-attachments/assets/b5f6ab88-1453-4ef6-b696-338a172152a9" />

### Verifikasi & Serah Terima (Petugas)
Halaman bagi petugas untuk melakukan *Approve/Reject* serta memproses serah terima barang yang memicu *trigger* pengurangan stok di *database*[cite: 1].

![Proses Verifikasi Petugas]<img width="1366" height="768" alt="Screenshot from 2026-05-05 15-30-37" src="https://github.com/user-attachments/assets/31dce28a-a47d-4b9c-a971-573e4b760d8c" />

### Proses Pengembalian & Log Aktivitas
Halaman penyelesaian transaksi pengembalian barang dan dasbor riwayat aktivitas (*Activity Logs*) untuk kebutuhan audit.

![Proses Pengembalian]<img width="1366" height="768" alt="Screenshot from 2026-05-05 15-31-51" src="https://github.com/user-attachments/assets/949b992e-eb0c-4087-b97d-40259fc95b5f" /> <img width="1366" height="768" alt="Screenshot from 2026-05-05 15-32-17" src="https://github.com/user-attachments/assets/5faad05a-1acf-4844-a745-21d4508b7f5a" />

---

## 🛠️ Teknologi yang Digunakan

*   **Framework:** Laravel 11
*   **Frontend Interactivity:** Livewire 3 & Alpine.js
*   **Styling:** Tailwind CSS
*   **Database:** MySQL / MariaDB
*   **Environment:** Linux Mint (Development)

---

## 💻 Cara Menjalankan di Lokal (Docker Environment)

Aplikasi ini menggunakan lingkungan berbasis container. Pastikan **Docker** dan **Docker Compose** sudah terpasang dan berjalan di perangkatmu.

**1. Clone Repositori**

```bash
git clone [https://github.com/FathanEmHa/peminjaman-beta.git](https://github.com/FathanEmHa/peminjaman-beta.git)
cd sipa
```

**2. Konfigurasi Environment**
Salin file .env.example menjadi .env.

```bash
cp .env.example .env
```

(Penting: Pastikan konfigurasi koneksi database di .env sudah disesuaikan dengan nama service database di konfigurasi Docker kamu, misalnya DB_HOST=mysql atau DB_HOST=db)

**3. Build & Jalankan Container**
Jalankan semua service Docker (aplikasi dan database) di background.
(Catatan: Jika kamu menggunakan Laravel Sail, ganti docker compose dengan ./vendor/bin/sail)

```bash
docker compose up -d
```

**4. Instalasi Dependensi & Setup Aplikasi**
Jalankan perintah berikut untuk menginstal library dan mengatur database di dalam container aplikasi (asumsi nama service Laravel kamu adalah app):

```bash
# Install dependency PHP & Node.js
docker compose exec app composer install
docker compose exec app npm install

# Generate Application Key
docker compose exec app php artisan key:generate

# Migrasi Database & Seeder
docker compose exec app php artisan migrate --seed
```

**5. Menjalankan Asset & Scheduler (Background Task)**
Untuk mengompilasi aset frontend (Livewire/Alpine.js/Tailwind) dan menjalankan pengecekan otomatis untuk status overdue:

Buka dua terminal baru, lalu jalankan:

Terminal 1 (Frontend Vite):

```bash
docker compose exec app npm run dev
```

Terminal 2 (Scheduler Keterlambatan Otomatis):

```bash
docker compose exec app php artisan schedule:work
```

**💡 Sedikit Catatan:**
Kalau di file `docker-compose.yml` kamu nama *service* PHP/Laravel-nya bukan `app` (misalnya `web`, `laravel.test`, atau `php`), pastikan kata `app` pada *command* `docker compose exec app ...` di atas diganti sesuai dengan nama *service* milikmu, ya.
