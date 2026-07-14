# SIPA - Sistem Informasi Peminjaman Alat 🛠️

SIPA adalah platform berbasis web yang dirancang untuk mengelola peminjaman dan pengembalian alat secara efisien. Proyek ini dikembangkan sebagai tugas Uji Kompetensi Keahlian (UKK/Ujikom) Rekayasa Perangkat Lunak dan telah mendapatkan predikat Sangat Kompeten.

## 🔗 Live Demo

Coba langsung aplikasinya di sini 👉 **[loan.tansys.my.id](https://loan.tansys.my.id)**

## 💡 Masalah & Solusi yang Ditawarkan

Aplikasi ini dibangun untuk menyelesaikan berbagai kendala operasional yang sering terjadi pada manajemen inventaris fisik/sekolah:

**Masalah yang Sering Terjadi**:

* **Pencatatan Konvensional**: Menggunakan kertas atau spreadsheet manual sering menyebabkan selisih data stok dan riwayat yang hilang.
* **Kondisi Barang yang Diperdebatkan**: Sering terjadi konflik mengenai kondisi fisik alat (rusak/cacat) antara petugas dan peminjam karena tidak ada bukti saat serah terima.
* **Keterlambatan Tanpa Pengawasan**: Tidak ada sistem yang melacak batas waktu peminjaman, sehingga alat sering menumpuk di peminjam tanpa status yang jelas.

**Solusi dari SIPA**:

* **Otomasi & Digitalisasi Penuh**: Semua siklus peminjaman tercatat dalam basis data. Sistem akan mengunci stok saat pengajuan (mencegah double-booking) dan memotong stok secara riil saat alat diserahkan.
* **Bukti Visual (Photo Before/After)**: Mewajibkan dokumentasi foto kondisi barang saat serah terima (ongoing) dan saat dikembalikan (returned), sehingga pertanggungjawaban fisik menjadi jelas.
* **Pendeteksi Overdue Otomatis**: Menggunakan sistem cron job / scheduler yang berjalan di latar belakang untuk secara otomatis mengubah status transaksi menjadi overdue dan menghitung denda keterlambatan secara presisi.

## 🚀 Fitur Utama

*   **Manajemen Stok Real-time**: Stok alat akan berkurang secara otomatis ketika status peminjaman berubah menjadi "Ongoing" (setelah pengambilan fisik), memastikan data inventaris tetap akurat.
*   **Pelacakan Keterlambatan Otomatis**: Dilengkapi dengan sistem pendeteksi keterlambatan otomatis menggunakan Artisan Command dan Scheduler.
*   **Dashboard Interaktif**: Memantau jumlah alat tersedia, alat dipinjam, dan status peminjaman secara cepat.
*   **Laporan Terpadu**: Sistem pelaporan peminjaman dan pencatatan log aktivitas otomatis yang memudahkan admin dalam melakukan audit.
*   **Otorisasi Multi-Peran**: Sistem hak akses yang membedakan fitur untuk Admin (Master Data), Petugas (Verifikasi), dan Peminjam (Pengajuan).
*   **Desain Responsif**: Tampilan menyesuaikan otomatis di berbagai ukuran layar (desktop, tablet, hingga mobile), jadi Admin, Petugas, maupun Peminjam tetap nyaman mengakses sistem lewat perangkat apa pun.
*   **Tampilan Responsif**: Antarmuka menyesuaikan dari desktop hingga mobile, termasuk sidebar navigasi yang berubah jadi overlay/drawer di layar kecil, sehingga tetap nyaman diakses dari perangkat apa pun.

---

## 🏗️ Arsitektur Sistem & Alur Kerja

### 1. Flowchart Sistem
Sistem ini mengakomodasi siklus secara *end-to-end*: mulai dari fase pengajuan (booking), verifikasi persetujuan oleh petugas, serah terima fisik (otomasi potong stok), hingga fase pengembalian barang.

<img width="5388" height="8192" alt="Flowchart Alur Peminjaman" src="https://github.com/user-attachments/assets/128eccba-9ace-4c86-8533-ce1e49743305" />

### 2. Conceptual Data Model (ERD)
Pemodelan data konseptual (Notasi Chen) yang memetakan entitas utama beserta atribut dasarnya. Pusat alur data berada pada entitas `loans` yang mengikat data pengguna (`users`) dan detail aset (`assets`).

<img width="764" height="452" alt="ERD Conceptual SIPA" src="https://github.com/user-attachments/assets/fa409352-0b3b-4af0-8521-47508e39b9f1" />

### 3. Physical Data Model (Class Diagram)
Rancangan struktur basis data yang berpusat pada relasi transaksional. Menampilkan relasi *One-to-Many* (seperti `Users` ke `Loans`) dan *One-to-One* (seperti `Loans` ke `Returns`).

<img width="1348" height="1060" alt="Class Diagram SIPA" src="https://github.com/user-attachments/assets/fc59075b-8e4f-47dd-a8d1-c17386a71788" />

---

## 📸 Dokumentasi Antarmuka (Screenshots)

Berikut adalah tampilan antarmuka dari fitur-fitur inti SIPA. Klik gambar mana pun untuk langsung mencoba live demo-nya.

### Autentikasi & Dasbor Peminjam
Proses *login* dengan *Role-Based Access Control* dan dasbor pemantauan riwayat peminjaman pengguna.

<a href="https://loan.tansys.my.id" target="_blank">
  <img width="1366" height="768" alt="Halaman Login & Dashboard" src="https://github.com/user-attachments/assets/d8fb7898-3532-4b14-ab8c-4e34d5f0f14e" />
</a>

### Manajemen Aset (Admin)
Halaman bagi Admin untuk mengelola Master Data, menambah aset baru, dan memantau ketersediaan stok.

<a href="https://loan.tansys.my.id" target="_blank">
  <img width="1366" height="768" alt="Halaman Manajemen Aset" src="https://github.com/user-attachments/assets/be3b1a43-1a99-4888-9057-8e472d860344" />
</a>

### Pengajuan & Validasi Stok (Peminjam)
Halaman pengajuan barang. Sistem dilengkapi validasi penolakan otomatis jika kuantitas yang dipinjam melebihi sisa stok yang tersedia.

<a href="https://loan.tansys.my.id" target="_blank">
  <img width="1366" height="768" alt="Form Pengajuan Peminjaman" src="https://github.com/user-attachments/assets/b5f6ab88-1453-4ef6-b696-338a172152a9" />
</a>

### Verifikasi & Serah Terima (Petugas)
Halaman bagi petugas untuk melakukan *Approve/Reject* serta memproses serah terima barang yang memicu *trigger* pengurangan stok di *database*.

<a href="https://loan.tansys.my.id" target="_blank">
  <img width="1366" height="768" alt="Proses Verifikasi Petugas" src="https://github.com/user-attachments/assets/31dce28a-a47d-4b9c-a971-573e4b760d8c" />
</a>

### Proses Pengembalian & Log Aktivitas
Halaman penyelesaian transaksi pengembalian barang dan dasbor riwayat aktivitas (*Activity Logs*) untuk kebutuhan audit.

<a href="https://loan.tansys.my.id" target="_blank">
  <img width="1366" height="768" alt="Proses Pengembalian" src="https://github.com/user-attachments/assets/949b992e-eb0c-4087-b97d-40259fc95b5f" />
</a>
<a href="https://loan.tansys.my.id" target="_blank">
  <img width="1366" height="768" alt="Log Aktivitas" src="https://github.com/user-attachments/assets/5faad05a-1acf-4844-a745-21d4508b7f5a" />
</a>

---

## ⚠️ Kendala Selama Proses Deployment

Proses deploy SIPA (baik lewat VPS maupun kombinasi Docker + reverse proxy) sempat ketemu beberapa masalah teknis. Dicatat di sini sebagai referensi kalau nemu kasus serupa:

1. **Mixed Content Error (HTTP/HTTPS)**
   Aplikasi berjalan di belakang reverse proxy (Nginx Proxy Manager) dengan SSL termination di level proxy, tapi Laravel tetap generate URL asset dengan skema `http://` meskipun user akses lewat `https://`.
   **Solusi:** Konfigurasi `trustProxies` di `bootstrap/app.php` (Laravel 11) supaya Laravel percaya header `X-Forwarded-Proto` dari proxy dan generate URL dengan skema yang benar.

2. **`APP_KEY` Hilang/Berubah Tiap Container Di-rebuild**
   Setiap kali image di-rebuild, `APP_KEY` ikut ke-generate ulang, akibatnya semua data terenkripsi (session, cookie, dsb) jadi invalid.
   **Solusi:** Pastikan `.env` disimpan di volume persisten (bukan ikut ter-bake ke dalam image), jadi `APP_KEY` cukup di-generate sekali di awal dan tidak berubah setiap rebuild.

3. **Perubahan `env_file` Tidak Ter-apply Walau Container Sudah Restart**
   Ganti variable di `.env`/`env_file`, tapi container tetap membaca konfigurasi lama meskipun sudah `docker compose restart`.
   **Solusi:** Gunakan `docker compose up -d --force-recreate` supaya container benar-benar dibuat ulang dan membaca environment variable terbaru.

4. **VPS Mendadak Down**
   Sempat pakai VPS pihak ketiga untuk hosting, tapi service-nya tiba-tiba mati/tidak bisa diakses, sehingga environment kerja (database, storage foto, dsb) ikut hilang.
   **Solusi:** Pivot ke skema hosting gratis yang lebih tahan lama: kombinasi **Render** (compute/app hosting) + **Cloudflare R2** (penyimpanan foto before/after), supaya tidak bergantung ke satu VPS saja.

5. **Permission Error di `storage/`**
   Error `Permission denied` pada `storage/logs` atau `storage/framework`, terutama saat pindah environment (lokal → Docker, atau ganti user yang menjalankan proses).
   **Solusi:** Pastikan ownership `storage/` dan `bootstrap/cache/` sesuai user yang menjalankan proses PHP (`www-data` untuk web server, atau user container), lalu `chmod -R 775`.

---

## 🛠️ Teknologi yang Digunakan

*   **Framework:** Laravel 11
*   **Frontend Interactivity:** Livewire 3 & Alpine.js
*   **Styling:** Tailwind CSS
*   **Database:** MySQL / MariaDB
*   **Environment:** Linux Mint (Development)

---

## 💻 Cara Menjalankan Secara Lokal

Aplikasi ini adalah proyek Laravel standar. Environment production sebenarnya dijalankan di atas Docker + reverse proxy + tunnel, tapi untuk development lokal cukup jalankan seperti aplikasi Laravel pada umumnya — yang penting **database**, **queue worker**, dan **scheduler** jalan berbarengan.

**1. Clone Repositori**

```bash
git clone https://github.com/FathanEmHa/peminjaman-beta.git
cd peminjaman-beta
```

**2. Install Dependency**

```bash
composer install
npm install
```

**3. Konfigurasi Environment**

```bash
cp .env.example .env
php artisan key:generate
```

Sesuaikan kredensial database (`DB_*`) di `.env` dengan environment kamu (lokal, Docker, atau managed database dari hosting provider).

**4. Migrasi Database & Seeder**

```bash
php artisan migrate --seed
```

**5. Storage Link** (untuk fitur upload foto before/after)

```bash
php artisan storage:link
```

**6. Compile Asset Frontend**

```bash
npm run dev    # mode development (watch)
# atau
npm run build  # build sekali untuk production
```

**7. Jalankan Aplikasi**

```bash
php artisan serve
```

**8. Jalankan Queue Worker & Scheduler** (buka 2 terminal terpisah, wajib jalan biar fitur background seperti log & deteksi overdue berfungsi)

```bash
# Terminal 1 — Queue Worker
php artisan queue:work

# Terminal 2 — Scheduler (deteksi keterlambatan otomatis)
php artisan schedule:work
```

> 💡 **Catatan untuk production:** `queue:work` dan `schedule:work` di atas cocok untuk development. Untuk production, sebaiknya `queue:work` di-manage pakai Supervisor (auto-restart kalau crash), dan `schedule:work` diganti dengan entry cron `* * * * * php artisan schedule:run` di server.

---

## ⚙️ Contoh `.env.example`

```env
APP_NAME="SIPA - Sistem Informasi Peminjaman Alat"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_TIMEZONE=Asia/Jakarta
APP_URL=http://localhost

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
PHP_CLI_SERVER_WORKERS=4
BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

# Sesuaikan dengan environment kamu:
# - Lokal: DB_HOST=127.0.0.1
# - Docker: DB_HOST=nama-service-db-di-compose (misal: db / mysql)
# - Managed DB (Railway/Render/dsb): host & credential didapat dari dashboard provider
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sipa
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
CACHE_STORE=database
```

> 💡 Sebelumnya `.env.example` menyertakan komentar `# dari Railway MySQL plugin` — dihapus karena bikin bingung kalau ada orang lain yang clone dan gak pakai Railway. Sekarang komentarnya generic, tinggal disesuaikan mau deploy ke mana.