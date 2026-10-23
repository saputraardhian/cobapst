# Workflow Log: FE-01 Portal Publik Landing Page - Node 1

## Informasi Pekerjaan
- **ID Tiket:** `FE-01`
- **Modul:** Portal Publik Satu Pintu Layanan Terpadu PST BPS Provinsi Jawa Tengah (Node 1 Design.md)
- **Komponen Livewire:** `App\Livewire\PublicLandingPage` (`/`)
- **Tampilan Blade:** `resources/views/livewire/public-landing-page.blade.php`
- **Layout:** `resources/views/components/layouts/public.blade.php`
- **Waktu Eksekusi:** Oktober 2026

---

## 1. Spesifikasi Teknis & Rancang Bangun (Modern SaaS Bento & Scrollytelling)
1. **Floating Glassmorphic Navbar & Scrollytelling Progress Bar:**
   - Brand logo kelembagaan `semarpst v2.0` (Pelayanan Statistik Terpadu - BPS Provinsi Jawa Tengah).
   - Scrollytelling spy navigation pills (`Layanan PST`, `Live Antrian`, `Petugas Terbaik`, `Kanal Pengaduan`).
   - Badge status operasional lobi dinamis: `Loket Buka (08:00 - 15:30 WIB)`.
   - Tombol CTA `Ambil Tiket` mengarah ke anjungan mandiri `/kiosk`.
   - Progress bar scrollytelling 3px bergradien di puncak jendela browser yang merespons scroll pembaca.
2. **Hero Section (Bento Grid):**
   - **Kolom Kiri:** Badge WBK/WBBM, headline *"Satu Pintu Layanan Data & Konsultasi Statistik Resmi"*, dual action CTA (`Ambil Tiket Antrean Sekarang` & `Cek Status Tiket / Pengaduan`), dan lencana mikro-kepercayaan *Tarif Resmi Rp 0,- / Bebas Biaya*.
   - **Kolom Kanan:** Bento card deep navy *Live Monitor Antrean Real-Time* memuat nomor tiket jumbo aktif (`A-024`), penanda meja tujuan, matriks status 5 loket terpadu, dan tautan ke TV antrean (`/antrian`).
3. **4 Pilar Layanan Terpadu PST (Bento Grid 4 Kolom):**
   - Menampilkan 4 kartu bento layanan berdasar Peraturan BPS No. 2 Tahun 2021:
     1. `Loket 1 - Perpustakaan Statistik` (SLA 15 menit).
     2. `Loket 2 - Konsultasi Statistik` (SLA 30 menit).
     3. `Loket 3 - Pembelian & Data Mikro` (SLA 20 menit).
     4. `Loket 4 - Rekomendasi (ROMANIK)` (SLA 3 hari kerja).
   - Dilengkapi drawer/modal interaktif detail informasi alur dan dokumen prasyarat via Livewire modal (`toggleServiceModal`).
4. **Hall of Fame Petugas PST Teladan (Podium 3D Showcase):**
   - Penghormatan aparatur berprestasi berbasis evaluasi kinerja bulanan riil dari sistem Elena (`elena_evaluations`).
   - Podium juara: Juara 2 Perak (Dina Nuryita), Juara 1 Emas Spotlight (Hendra Wibowo, Skor 99.8), dan Juara 3 Perunggu (Rino Septiani).
5. **Transparansi & Pengaduan (Bento Grid 2 Kolom):**
   - **Form Lacak Status Tiket Layanan & Pengaduan:** Input nomor tiket (`PST-2026-0112`, `A-024`) atau kode pengaduan (`ADU-2026-0042`) secara reaktif dengan visual timeline tahapan penanganan.
   - **Kanal Pengaduan Khusus PST:** Bento card whistleblower perlindungan identitas pelapor dan tautan buat laporan baru.
6. **Gateway Single Sign-On (SSO) SEMARPST:**
   - Banner bento khusus pegawai dan operator lobi untuk akses ke portal internal (`/admin/login`).
7. **Institutional Footer Terpadu (4 Kolom):**
   - Identitas institusi, jam layanan, direktori aplikasi, dan alamat BPS Provinsi Jawa Tengah (Jl. Pahlawan No. 6 Semarang).

---

## 2. Berkas yang Dibuat & Dimodifikasi
- `app/Livewire/PublicLandingPage.php`: Komponen Livewire controller untuk landing page portal publik.
- `resources/views/components/layouts/public.blade.php`: Layout HTML5 modern dengan font Google Plus Jakarta Sans & JetBrains Mono, glassmorphism CSS, dan scrollytelling tracker.
- `resources/views/livewire/public-landing-page.blade.php`: Template antarmuka bento grid lengkap sesuai mockup referensi.
- `routes/web.php`: Pendaftaran rute root `/` mengarah ke `PublicLandingPage::class`.
- `resources/views/welcome.blade.php`: Pengalihan fallback ke `Route::get('/', PublicLandingPage::class)`.
- `tests/Feature/PublicLandingPageTest.php`: Pengujian fitur otomatis untuk landing page, monitor antrean, dan pelacakan tiket/aduan.

---

## 3. Hasil Pengujian & Debugging (Rule #1)
- **Syntax Check:** `php -l` lulus 100% pada seluruh berkas baru.
- **Frontend Build:** `npm run build` sukses (Tailwind CSS v4 & JS terkompilasi optimal).
- **Automated Feature Tests:**
  - `PublicLandingPageTest`: 4 passed (16 assertions).
  - Full Test Suite: **38 passed (200 assertions), 100% PASS**.
