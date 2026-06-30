# Core Aplikasi SIMRS & Rekam Medis Elektronik (RME)

A clean, modular, lightweight, and well-structured Hospital Information System (SIMRS) and Electronic Medical Record (RME) core application built on Laravel 13.

> [!IMPORTANT]
> **Blueprint & Architecture**: Untuk melihat diagram alur (Flowcharts), ERD database, data flow diagram (DFD), dan spesifikasi tabel database lengkap, silakan lihat dokumentasi khusus di [BLUEPRINT.md](BLUEPRINT.md).

## 🚀 Fitur Utama & Spesifikasi Teknis

1. **Framework Laravel 13**:
   - Berbasis PHP 8.3+ standar dengan deklarasi tipe data ketat (*strict types*).
   - Migrasi basis data anonim (*modern anonymous migrations*).
   - Pendekatan pengikatan properti model modern (*attribute-based properties*).
2. **Arsitektur Frontend Ringan & CDN-Based**:
   - Memakai mesin *templating* murni Laravel Blade.
   - Tidak menggunakan kompilasi lokal seperti Node.js, NPM, atau Vite.
   - Seluruh pustaka eksternal menggunakan CDN berkecepatan tinggi:
     - **Bootstrap 5.3** (CSS & JS Bundle) untuk tata letak yang bersih.
     - **Bootstrap Icons** untuk visual indikator menu.
     - **SweetAlert2** untuk notifikasi interaktif yang menarik.
     - **Three.js** untuk grafik visualisasi 3D yang ringan.
3. **Dukungan Progressive Web App (PWA)**:
   - Terintegrasi berkas `manifest.json` dan Service Worker (`sw.js`).
   - Melayani caching offline dasar untuk CDN aset sehingga dapat diinstalasi pada perangkat mobile (Chrome/Safari) via pengujian local IP.
4. **Localization (ID / EN)**:
   - Dukungan multi-bahasa (Bahasa Indonesia & English) yang diatur menggunakan session-based middleware (`SetLocale`).
   - Peralihan bahasa instan melalui tombol dropdown di bilah navigasi.
5. **Aesthetics & Dark Mode**:
   - Estetika desain bertema *"Fresh Material Design"* (bayangan kartu halus, sudut melengkung, warna aksen pastel cerah).
   - Mode gelap bawaan (*Native Dark Mode*) yang sinkron dengan Bootstrap 5.3 (`data-bs-theme="dark"`) dan tersimpan otomatis di `localStorage`.

---

## 📂 Struktur Direktori Kode

Struktur berkas yang dibuat mengikuti standar Laravel 13 sebagai berikut:

```
medic_dashboard/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   ├── LoginController.php       # Controller Masuk & Keluar Akun
│   │   │   │   └── RegisterController.php    # Controller Pendaftaran Akun
│   │   │   ├── IntegrationController.php     # Simulasi API Bridging BPJS & SATUSEHAT
│   │   │   ├── LanguageController.php        # Controller Pengubah Sesi Bahasa
│   │   │   ├── MedicalRecordController.php   # Logika Dasbor & Input SOAP RME
│   │   │   └── PatientController.php         # CRUD Direktori Pasien
│   │   └── Middleware/
│   │       └── SetLocale.php                 # Middleware Pengatur Sesi Locale
│   └── Models/
│       ├── Doctor.php                        # Model Dokter (HasMany MedicalRecords)
│       ├── MedicalRecord.php                 # Model Rekam Medis (BelongsTo Patient & Doctor)
│       ├── Patient.php                       # Model Pasien (HasMany MedicalRecords)
│       └── User.php                          # Model User (+ Kolom Peran 'role')
├── bootstrap/
│   └── app.php                               # Registrasi Middleware SetLocale secara Global
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php           # Modifikasi penambahan kolom role
│   │   ├── 2026_06_30_000001_create_patients_table.php        # Tabel Pasien
│   │   ├── 2026_06_30_000002_create_doctors_table.php         # Tabel Dokter
│   │   └── 2026_06_30_000003_create_medical_records_table.php  # Tabel Rekam Medis RME SOAP
│   └── seeders/
│       └── DatabaseSeeder.php                # Seeder Akun Demo, Dokter, Pasien & Rekam Medis
├── lang/
│   ├── en/
│   │   └── messages.php                      # Kunci terjemahan bahasa Inggris
│   └── id/
│       └── messages.php                      # Kunci terjemahan bahasa Indonesia
├── public/
│   ├── manifest.json                         # Konfigurasi Instalasi PWA
│   └── sw.js                                 # Service Worker Caching & Offline Mode
├── resources/
│   └── views/
│       ├── auth/
│       │   └── login.blade.php               # Halaman Masuk & Daftar Akun (Sliding Transition SPA-style)
│       ├── layouts/
│       │   └── app.blade.php                 # Master Layout Utama (CDN & Toggles)
│       ├── medical-records/
│       │   └── create.blade.php              # Formulir SOAP Catatan RME
│       ├── patients/
│       │   ├── create.blade.php              # Pendaftaran Pasien Baru
│       │   ├── edit.blade.php                # Edit Data Pasien
│       │   └── index.blade.php               # Direktori List Pasien
│       └── dashboard.blade.php               # Monitoring Widget & Three.js Canvas
└── routes/
    └── web.php                               # Router dengan Komentar Bahasa Indonesia
```

---

## 🛠️ Cara Instalasi & Menjalankan Aplikasi

1. **Clone repositori dan masuk ke folder project**:
   ```bash
   cd medic_dashboard
   ```
2. **Instal dependensi PHP**:
   ```bash
   composer install
   ```
3. **Konfigurasi Lingkungan (`.env`)**:
   Salin `.env.example` ke `.env` dan pastikan konfigurasi basis data Anda benar (MySQL/SQLite).
   ```bash
   cp .env.example .env
   ```
4. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```
5. **Jalankan Migrasi & Pengisian Data Awal (Seeding)**:
   ```bash
   php artisan migrate:fresh --seed
   ```
   *Perintah ini akan membuat semua tabel basis data dan mengisi data dokter, pasien, serta beberapa akun demo.*
6. **Jalankan Server Lokal**:
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses melalui browser di alamat [http://localhost:8000](http://localhost:8000).

---

## 📱 Pengujian PWA pada Perangkat Mobile (Smartphone)

Agar PWA dapat diinstalasi pada handphone, server pengembangan lokal harus dapat diakses dalam jaringan Wi-Fi lokal yang sama.

1. **Jalankan Laravel serve dengan membinding IP 0.0.0.0**:
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   ```
2. **Cari IP Lokal Komputer Anda**:
   - Di Windows, jalankan perintah `ipconfig` di Terminal (contoh IP: `192.168.1.15`).
3. **Buka Browser di Smartphone**:
   - Kunjungi `http://192.168.1.15:8000`.
   - **Di Android (Chrome)**: Tap tombol menu titik tiga di kanan atas dan pilih **"Add to Home Screen"** atau **"Install App"**.
   - **Di iOS (Safari)**: Tap tombol **Share** di bar bawah dan pilih **"Add to Home Screen"**.
   - Berkat `manifest.json` dan `sw.js`, aplikasi akan terinstal sebagai aplikasi mandiri (*standalone*) dengan ikon khusus tanpa bingkai browser.

---

## ⚙️ Cara Kerja Sistem Responsif & Simulasi Integrasi

### 1. Tata Letak Responsif (Mobile-First)
- **Sidebar**: Di layar besar (Desktop), sidebar tetap menempel di sisi kiri secara stabil. Di layar kecil (Mobile), sidebar tersembunyi secara default di luar layar kiri (`left: -280px`). Mengeklik ikon menu hamburger di pojok kiri atas navbar akan memicu kelas `.active` via JavaScript untuk menggeser sidebar masuk dengan transisi halus.
- **Tabel Rekam Medis & Pasien**: Menggunakan `table-responsive` pada layar besar untuk kenyamanan mengetik, sedangkan pada layar kecil (lebar < 768px), data akan ditumpuk secara dinamis menjadi kartu-kartu kecil vertikal (*stackable cards*) yang nyaman dibaca pada perangkat mobile.

### 2. Animasi Denyut Jantung Tiga Dimensi (Three.js Pulse Overview)
- Kartu "System Pulse Overview" menampung sebuah kontainer canvas. Saat dasbor dimuat, pustaka Three.js menggambar sebuah sistem koordinat partikel 3D berbentuk gelombang melengkung (*Grid Wave Particles*).
- Ketinggian koordinat Y partikel diperbarui secara dinamis setiap frame menggunakan fungsi matematika trigonometri `Math.sin` dan `Math.cos`, menciptakan visualisasi denyut yang menyerupai elektrokardiogram (EKG).
- Sistem pemantau resizer otomatis mengukur dimensi kontainer secara berkala untuk memperbarui aspek rasio kamera proyeksi sehingga grafik 3D tidak pecah/melar di layar handphone.

### 3. Simulasi Integrasi Bridging BPJS & SATUSEHAT
- Catatan Rekam Medis (RME) yang baru diinput memiliki status sinkronisasi `pending`.
- Ketika staf mengklik tombol **"BPJS"** atau **"SATUSEHAT"** di tabel dasbor, fungsi JavaScript akan melakukan request `fetch` POST ke backend (`/integration/sync-bpjs/{id}` atau `/integration/sync-satusehat/{id}`).
- Di backend, `IntegrationController` memproses pembaruan status data di database, dan mensimulasikan pemrosesan payload dengan mengembalikan JSON sukses (status 200) yang membawa data mock (seperti nomor SEP BPJS acak dan UUID standar FHIR R4).
- Respon sukses ini ditangkap oleh frontend dan ditampilkan dalam bentuk jendela modal detail JSON yang terformat rapi menggunakan **SweetAlert2**.

---

## 🔐 Akun Akses Demo

Anda dapat masuk menggunakan salah satu akun terdaftar berikut:
- **Administrator**: `admin@simrs.com` (Sandi: `password`)
- **Dokter Pemeriksa**: `doctor@simrs.com` (Sandi: `password`)
- **Staff Administrasi**: `staff@simrs.com` (Sandi: `password`)
