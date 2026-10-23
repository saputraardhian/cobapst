# DOKUMEN SPESIFIKASI DESAIN SISTEM (DESIGN.MD)
## SEMARPST - Super-App Layanan Publik & Evaluasi Kinerja PST
**Instansi:** Badan Pusat Statistik (BPS) Provinsi Jawa Tengah (Jl. Pahlawan No. 6, Semarang)  
**Ruang Lingkup:** Konsolidasi 5 Sistem Layanan (`pst`, `statisfy`, `pristi`, `pengaduan`, `elena`)  
**Fondasi Teknologi UI:** Tailwind CSS + Blade Layouts + Livewire 3 + Alpine.js  
**Status Dokumen:** Living Specification (Diselaraskan dengan 12 Node Desain Kanvas)  
**Versi:** 2.0 (Oktober 2026)  

---

## 1. Filosofi Desain & Standar Rekayasa Antarmuka

Antarmuka sistem SEMARPST dikembangkan dengan memadukan wibawa identitas instansi pemerintah terpercaya (Badan Pusat Statistik) dan keanggunan produk perangkat lunak modern setara Linear, Stripe, Raycast, dan GitHub. Desain antarmuka berorientasi pada utilitas tinggi (*utility-first*), keringkasan informasi (*information density*), dan efisiensi alur kerja operasional ASN tanpa beban kognitif yang tidak perlu.

### 1.1. Prinsip Anti-Slop (Kejujuran & Ketepatan Visual)
1. **Eliminasi AI Tropes & Visual Gimmick:**
   - Dilarang menggunakan gradien neon ungu/violet pekat, bola pendar (*floating glow blur*), dan ikon bintang pendar fiktif (*sparkle/star*).
   - Menghilangkan ornamen efek kaca buram (*frosted glassmorphism*) berlebih yang menurunkan keterbacaan teks angka dan metrik resmi.
   - Peniadaan animasi radar berkedip (*animate-ping*) pada elemen dekoratif murni. Animasi denyut (*pulse*) hanya diizinkan pada indikator jaringan/sinkronisasi data riil.
2. **Kerapian Micro-Craft & Hierarki Tipografi:**
   - Seluruh batas kontainer menggunakan garis tepi presisi 1 piksel (*crisp 1px border*) dengan kontras halus (`border-slate-200/80` pada mode terang dan `dark:border-slate-800` pada mode gelap).
   - Menggunakan bayangan alami lembut (*subtle natural elevation* seperti `shadow-xs` dan `shadow-sm`), bukan bayangan tebal berwarna yang mengaburkan batas elemen.
   - Tipografi menggunakan skala proporsional yang jelas dengan pembagian peran yang tegas antara judul formal dan angka tabular.
3. **Realisme & Integritas Konten:**
   - Seluruh teks antarmuka menggunakan bahasa kedinasan resmi Republik Indonesia sesuai KBBI (misalnya ejaan baku `antrean`, bukan `antrian`).
   - Meniadakan teks pengisi generik (*lorem ipsum* atau jargon kosong). Setiap tabel, kartu ringkasan, dan modul memuat metadata riil BPS Jawa Tengah (NIP 18 digit, 6 tim teknis, nomor tiket antrean, dan indeks kepuasan).

### 1.2. Prinsip Ponytail (Kesederhanaan Radikal & YAGNI)
- **Zero Redundancy:** Menghilangkan duplikasi bilah navigasi modul yang memakan ruang vertikal layar. Setiap informasi operasional (seperti status shift atau loket aktif) hanya diletakkan pada satu titik jangkar utama (*single primary anchor*).
- **Struktur Grid Terstruktur vs. Floating Card:** Mengutamakan struktur bento grid yang teratur dan tabel data padat dibandingkan kartu melayang renggang di tengah layar.
- **Contract-Driven Consistency:** Antarmuka disinkronkan secara ketat dengan skema 12 tabel database backend (`users`, `pst_services`, `pst_counters`, `pst_visitors`, `pst_queues`, `pst_guest_books`, `skm_surveys`, `pristi_schedules`, `pristi_attendances`, `complaints`, `elena_evaluations`, `elena_certificates`).

---

## 2. Sistem Token Desain & Palet Warna Resmi BPS

Sistem pewarnaan SEMARPST mengadopsi palet warna resmi Badan Pusat Statistik dengan rasio kontras tinggi yang memenuhi standar aksesibilitas WCAG 2.1 AA di kedua mode tampilan (terang dan gelap).

### 2.1. Token Palet Warna Inti (Official BPS Tokens)

| Nama Token | Nilai Hex | Utilitas Tailwind CSS | Peran & Penggunaan Khusus |
| :--- | :--- | :--- | :--- |
| **BPS Deep Navy** | `#005288` | `bg-[#005288]`<br>`text-[#005288]`<br>`border-[#005288]` | Header tabel utama, brand logo SEMARPST, kartu pimpinan eksekutif, identitas institusi. |
| **BPS Primary Blue** | `#005999` | `bg-[#005999]`<br>`text-[#005999]`<br>`border-[#005999]` | Tombol aksi primer, kapsul modul aktif, highlight modul STATISFY, header Kios lobi. |
| **BPS Vibrant Green** | `#6CBE45` | `bg-[#6CBE45]`<br>`text-[#6CBE45]`<br>`border-[#6CBE45]` | Status tepat waktu, lencana online aktif, tombol selesai layanan, aksen modul PRISTI. |
| **BPS Warm Orange** | `#F7941D` | `bg-[#F7941D]`<br>`text-[#F7941D]`<br>`border-[#F7941D]` | Modul PST (Konsol Loket), nomor tiket aktif antrean, status panggilan meja layanan. |
| **BPS Accent Yellow** | `#FDE047` | `bg-[#FDE047]`<br>`text-[#FDE047]` | Kotak skor capaian ELENA, lencana penanda khusus, highlight teks penting pada struk/kios. |
| **Pristi Cyan Action** | `#00B4F0` | `bg-[#00B4F0]`<br>`text-[#00B4F0]` | Tombol pemicu jepretan kamera webcam `[Ambil Foto]` pada modul presensi biometrik. |
| **Canvas Background** | `#F4F5FA` | `bg-[#F4F5FA]`<br>`dark:bg-slate-950` | Latar belakang dasar kanvas kerja, dasbor bento, dan halaman portal publik. |
| **Surface Neutral** | `#FFFFFF` | `bg-white`<br>`dark:bg-slate-900` | Latar kartu bento, panel formulir, modal dialog, dan bilah header mengambang. |
| **Track Sub-Surface** | `#F3F5FB` | `bg-[#F3F5FB]`<br>`dark:bg-slate-800/80` | Trek navigasi modul, tab filter segmentasi, kontainer nomor tiket TV, latar input form. |
| **TV Dark Backdrop** | `#0F172A` | `bg-[#0F172A]` | Latar belakang khusus layar TV display antrean 23 inci untuk visibilitas jarak jauh. |

### 2.2. Kontrak Dual-Theme (Terang & Gelap)
Setiap komponen antarmuka dirancang *light-first* dan wajib dipasangkan secara eksplisit dengan kelas varian `dark:`.
- **Latar Belakang:** `bg-[#F4F5FA] dark:bg-slate-950`
- **Permukaan Kartu/Panel:** `bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800`
- **Teks Primer:** `text-slate-900 dark:text-white`
- **Teks Sekunder/Label:** `text-slate-500 dark:text-slate-400`
- **Garis Pembatas (Divider):** `border-slate-100 dark:border-slate-800`

---

## 3. Skala Tipografi, Bentuk & Spacing

### 3.1. Tipografi Sistem
- **Headings & Identitas Brand:** Menggunakan font berkarakter serif tegas (`font-serif font-extrabold tracking-tight`, bergaya Fraunces/Merriweather) untuk menghadirkan ketegasan hukum dan otoritas data resmi negara.
- **Teks UI, Navigasi & Formulir:** Menggunakan font sans-serif modern (`font-sans font-medium / font-semibold / font-bold`, setara Plus Jakarta Sans atau Inter) untuk kemudahan pembacaan kilat.
- **Data Angka, Kode Tiket & Stopwatch:** Menggunakan font proporsional berjarak tetap (`font-mono font-bold / font-black`) untuk nomor antrean (`A-024`), token struk (`#PST-8921`), NIP 18 digit, dan pencatat waktu layanan.

### 3.2. Skala Sudut Lengkung (Border Radius System)
Komponen SEMARPST menggunakan radius sudut yang proporsional sesuai hierarki skala fisik:
- **Header Card & Floating Navbar:** `rounded-2xl` (16px) dengan `shadow-sm border border-slate-200/80 dark:border-slate-800`.
- **Bento Grid Cards & Panel Konten Utama:** `rounded-3xl` (24px) dengan padding proporsional (`p-5` hingga `p-8`).
- **Tombol Aksi, Input Field & Tab Filter:** `rounded-xl` (12px) dengan transisi mulus dan fokus aktif yang tegas.
- **Micro-Badges & Status Tags:** `rounded-md` atau `rounded-lg` (6px hingga 8px) dengan teks huruf tebal berukuran kecil (`text-[10px]` atau `text-xs`).
- **Aturan Pembatasan `rounded-full`:** Bentuk kapsul penuh (`rounded-full`) hanya diizinkan untuk lencana status mikro (misalnya titik hijau aktif) dan tombol avatar pengguna. Dilarang menggunakan `rounded-full` pada kartu data besar atau sel tabel.

---

## 4. Arsitektur Komponen Header Terpadu (Zero-Overflow Architecture)

Salah satu capaian rancang bangun krusial pada SEMARPST adalah penyelesaian tuntas masalah pemotongan teks (*flex clipping bug*) pada resolusi laptop standar (1024px hingga 1366px).

### 4.1. Struktur 3-Seksi Header Bebas Luapan (Single-Row Unified Navbar)
Header aplikasi internal menggabungkan 3 fungsi dalam satu baris ramping (~56px) tanpa mendorong elemen keluar batas layar:
1. **Seksi Kiri (Identitas Instansi - `flex-shrink-0`):**
   - Ikon logo geometris BPS Jateng berlatar biru Navy (`bg-[#005999] rounded-xl w-9 h-9 sm:w-10 sm:h-10 text-white`).
   - Teks judul sistem `SEMARPST` (`font-extrabold text-base sm:text-lg`).
   - Lencana status `SSO INTERNAL` warna hijau daun (`bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 border border-emerald-200 text-[10px] font-extrabold`).
   - Sub-judul instansi `BPS Provinsi Jawa Tengah` (`text-slate-500 dark:text-slate-400 text-[11px] sm:text-xs`).
2. **Seksi Tengah (Trek Navigasi 6-Modul Responsif - `flex-1 min-w-0 max-w-2xl`):**
   - Dibungkus dalam kontainer elastis `flex-1 min-w-0 max-w-2xl mx-1 sm:mx-2 overflow-hidden flex items-center`.
   - Bilah navigasi menggunakan tag `<nav>` dengan kelas `w-full flex items-center justify-start bg-slate-100/90 dark:bg-slate-800/80 p-1.5 rounded-xl gap-1 overflow-x-auto scroll-smooth flex-nowrap scrollbar-thin`.
   - Menggunakan perataan rata kiri `justify-start` (bukan `justify-center`) agar item pertama tidak terdorong ke koordinat negatif saat ruang menyempit.
   - Terdiri dari 6 modul navigasi internal:
     - **Workspace** (Dasbor Bento Pegawai)
     - **Konsol Meja** (Konsol Meja Layanan Operator PST)
     - **PRISTI** (Presensi & Jadwal Pegawai)
     - **STATISFY** (Analitik & AI Summary Kepuasan)
     - **ELENA** (Matriks Evaluasi Kinerja & Piagam)
     - **Pengaduan** (Portal & Backoffice Pengaduan Terpadu)
   - Modul yang sedang aktif ditandai dengan latar biru resmi BPS (`bg-[#005999] text-white font-bold`), titik indikator, dan bayangan halus. Modul tidak aktif memiliki status `hover:bg-white dark:hover:bg-slate-700/80 text-slate-600 dark:text-slate-400`.
3. **Seksi Kanan (Sesi & Identitas Pegawai - `flex-shrink-0`):**
   - Lencana status sinkronisasi operasional (misalnya `PRISTI: 07:28 WIB` atau `186/186 Pegawai Terdata`).
   - Garis pembatas vertikal tipis (`h-6 w-px bg-slate-200 dark:bg-slate-700`).
   - Avatar inisial pegawai (`w-9 h-9 rounded-xl bg-[#005999] text-white font-bold text-xs`) disertai nama lengkap dan jabatan struktural.
   - Tombol kedinasan **Keluar** dengan ikon panah keluar SVG (`bg-slate-100 dark:bg-slate-800 hover:bg-rose-50 dark:hover:bg-rose-950/50 hover:text-rose-600 text-xs font-bold px-3 py-2 rounded-xl transition`).

### 4.2. Bilah Sub-Header Mandiri (Independent Action Sub-Bar)
Tombol **Kembali ke Dashboard Utama** tidak diletakkan berdesakan di dalam kapsul header utama, melainkan ditempatkan pada baris mandiri di bawah kartu header (`w-full mb-3 flex flex-wrap items-center justify-between gap-3 px-1`):
- **Sisi Kiri:** Tombol kembali berlatar putih bersih (`bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-200 text-xs font-bold px-4 py-2 rounded-xl shadow-xs inline-flex items-center gap-2`).
- **Sisi Kanan:** Jejak navigasi rekam jejak (*breadcrumb*) yang subtil (`Admin Internal > Nama Modul Spesifik`).

---

## 5. Rincian Spesifikasi 12 Node Desain Antarmuka

Seluruh 12 layar utama sistem SEMARPST telah selesai dirancang dengan kepatuhan 100% pada aturan anti-slop, dual-theme, dan responsivitas.

### 5.1. Node 1: Landing Page Publik & Portal Layanan (`/`)
- **ID Node Kanvas:** `ffa1563c-39ec-4d82-8018-0ca7cfcea70a`
- **Preset Perangkat:** Desktop (1440x900 piksel), responsif ponsel & tablet
- **Tujuan Antarmuka:** Gerbang satu pintu bagi masyarakat, mahasiswa, peneliti, dan instansi untuk mengakses layanan data statistik BPS Jawa Tengah.
- **Komponen Utama:**
  - Header navigasi publik mengambang dengan status lobi aktif (`Loket Buka 08:00 - 15:30 WIB`).
  - Hero Section 2-kolom: Banner keterbukaan informasi publik Zona Integritas (WBK/WBBM) dan Widget Pantauan Antrean Real-Time terintegrasi WebSocket.
  - Direktori 5 Layanan Utama PST BPS Jateng (Konsultasi Statistik, Data Mikro & Peta Digital, Perpustakaan Fisik, Pembelian Publikasi, Rekomendasi Kegiatan Statistik / ROMANIK).
  - Profil Meja Pelayanan PST Aktif dengan foto, nama petugas, dan status operasional loket.
  - Showcase Hall of Fame Petugas Teladan Bulanan (integrasi penilaian modul ELENA).
  - Footer kelembagaan 3-kolom: Alamat Jl. Pahlawan No. 6 Semarang, kontak pengaduan WhatsApp/Email, dan maklumat bebas biaya (Rp 0).

### 5.2. Node 2: Kios Lobi Sentuh Mandiri (`/kiosk`)
- **ID Node Kanvas:** `6bb5a995-5d9d-459f-8977-2e618598e9b7`
- **Preset Perangkat:** Desktop Kiosk Widescreen (1440x900 piksel, Touch-Optimized)
- **Tujuan Antarmuka:** Layar anjungan mandiri di lobi depan PST bagi pengunjung untuk mengambil tiket antrean fisik dan mengisi buku tamu kedinasan.
- **Komponen Utama:**
  - Header kelembagaan BPS dengan aksen garis oranye hangat (`border-[#F7941D]`), jam digital presisi, dan tombol aksesibilitas audio/bantuan petugas.
  - 3 Tab Mode Operasional Sentuh:
    1. **Ambil Tiket Antrean PST** (4 tombol layanan besar ramah jemari: Konsultasi Statistik, Data Mikro & Publikasi, Perpustakaan, Rekomendasi Kegiatan Statistik).
    2. **Buku Tamu Kedinasan Non-PST** (Kunjungan audiensi dinas kantor BPS Jateng).
    3. **Check-Out Kunjungan** (Penyelesaian kunjungan dan pengembalian kartu tamu).
  - Papan Papan Panduan Visual Sentuh ramah difabel dengan kontras tinggi.
  - Dukungan integrasi cetak struk termal otomatis kertas 58mm/80mm memuat QR token dinamis (`/s/{qr_token}`).

### 5.3. Node 3: Monitor TV Antrian 23 Inci (`/antrian`)
- **ID Node Kanvas:** `1893e6cf-6fdf-4a48-ad25-392497d33d87`
- **Preset Perangkat:** Desktop Widescreen TV 16:9 (1440x900 piksel, Zero-Scroll 68px Header)
- **Tujuan Antarmuka:** Display ruang tunggu publik resolusi Full HD untuk memandu alur antrean secara visual dan audio tanpa interaksi keyboard/mouse.
- **Komponen Utama:**
  - Header Zero-Scroll 68px memuat jam digital besar, indikator status online hijau, dan teks berjalan (*running marquee*) maklumat pelayanan anti-gratifikasi dan saluran aduan.
  - Layout Terbagi 2 Kolom Seimbang:
    - **Kolom Kiri (Spotlight Antrean Aktif):** Kartu nomor tiket jumbo (`A-024`), penanda meja tujuan (`LOKET 2 KONSULTASI STATISTIK`), kartu identitas petugas jaga (`Hendra Wibowo, S.Si.`), dan notifikasi suara TTS selesai.
    - **Kolom Kanan (Video Edukasi & Rekap Meja):** Area pemutar video edukasi data sensus/survei BPS Jateng berdampingan dengan matriks status 5 meja loket PST (Meja 1 s.d. Meja 5).

### 5.4. Node 4: Konsol Meja Operator 1-Layar (`/admin/konsol-meja`)
- **ID Node Kanvas:** `2ec065f7-a2ac-48a0-9962-c5edc5f527c6`
- **Preset Perangkat:** Desktop (1440x900 piksel, 1-Screen Workflow)
- **Tujuan Antarmuka:** Meja kerja digital tunggal bagi petugas jaga loket PST untuk mengelola panggilan tamu, mencatat layanan, dan mengukur durasi konsultasi.
- **Komponen Utama:**
  - Header terpadu bebas luapan dengan penanda loket aktif (`Meja 2 Konsultasi`) dan badge status TV 23" Sinkron.
  - 4 Kartu Metrik Cepat Operasional Petugas (Tamu Dilayani Hari Ini, Rata-Rata Durasi SLA, Indeks Kepuasan Petugas, Sisa Antrean Menunggu).
  - Panel Kiri (Panggilan & Pengatur Waktu Layanan): Tombol aksi sentuh besar (`[Panggil Berikutnya]`, `[Panggil Ulang]`, `[Lewati]`), kotak nomor antrean aktif, dan Stopwatch Layanan real-time dengan satuan eksplisit (`14 Menit : 35 Detik`).
  - Panel Kanan (Formulir Pencatatan Layanan Cepat): Identitas tamu otomatis dari nomor tiket/buku tamu, pemilihan kategori data statistik yang diminta, catatan tindak lanjut, dan tombol primer `[Selesai Layanan & Buka Survei HP]`.

### 5.5. Node 5: Admin Statisfy Analytics & AI Summary (`/admin/statisfy`)
- **ID Node Kanvas:** `7b56c201-56e2-4367-a388-02218359f0a1`
- **Preset Perangkat:** Desktop (1440x900 piksel)
- **Tujuan Antarmuka:** Dasbor eksekutif pengolahan hasil Survei Kepuasan Masyarakat (SKD/SKM) dengan rangkuman sentimen otomatis berbasis pemrosesan bahasa alami (NLP/AI).
- **Komponen Utama:**
  - Header terpadu dengan lencana `Live SKD 2026 Sync` dan bilah filter waktu (Bulanan, Triwulanan, Tahunan, All-Time) serta tombol ekspor ranking pegawai.
  - 4 Kartu Ringkasan Indeks Kepuasan (Skor IKM BPS Jateng, Persentase Puas, SLA Rata-rata Layanan, dan Rasio Zero Pungli 100%).
  - Panel Sintesis Eksekutif Minimalis & Elegan: Berbasis permukaan netral bersih (`bg-white dark:bg-slate-900`) bebas pendar neon, menyajikan analisis sentimen positif/netral/kritis, matriks kata kunci paling sering dicari, dan rekomendasi manajerial pimpinan berbobot kedinasan.
  - Tabel Rincian Ulasan Responden Lengkap dengan filter rating dan tanggal.

### 5.6. Node 6: Workspace Pegawai Bento UI (`/admin/workspace`)
- **ID Node Kanvas:** `b7195db9-a841-456f-8f01-99517a2454a3`
- **Preset Perangkat:** Desktop Bento Grid (1440x900 piksel)
- **Tujuan Antarmuka:** Beranda dasbor kerja personal bagi setiap pegawai BPS Provinsi Jawa Tengah lintas tim (Bagian Umum, Sosial, Produksi, Distribusi, Nerwilis, IPDS).
- **Komponen Utama:**
  - Header 1-baris ramping dengan navigasi modul scroll horizontal presisi.
  - Hero Bento Card (Span 7 Kolom): Sapaan ramah personal pegawai (`Selamat Bertugas, Mas Hendra!`), NIP resmi, jadwal shift aktif hari ini di Meja 2 Konsultasi, dan tiket antrean aktif yang ditangani (`Tiket A-014`).
  - Kartu Komposisi Konsultasi Statistik dengan warna tematik khas: Kemiskinan (`#005288`), PDRB (`#0284C7`), Inflasi (`#F7941D`), dan Metodologi (`#6CBE45`).
  - Kartu Ringkasan Kinerja Pribadi ELENA (Skor Kinerja Berjalan & Peringkat Sementara di Tim IPDS).
  - Kartu Presensi Biometrik PRISTI Hari Ini (Jam masuk tercatat, status geofence kantor Jl. Pahlawan No. 6 Semarang, dan tombol cepat presensi).

### 5.7. Node 7: Modul PRISTI Presensi Webcam (`/admin/presensi`)
- **ID Node Kanvas:** `9519d4fe-6e6d-455a-836b-2598951d178f`
- **Preset Perangkat:** Desktop (1440x900 piksel)
- **Tujuan Antarmuka:** Modul presensi biometrik wajah dan geofencing mandiri bagi seluruh 186 pegawai BPS Provinsi Jawa Tengah (baik shift lobi PST maupun staf internal kantor).
- **Komponen Utama:**
  - Header modul bernuansa biru resmi instansi dengan lencana kepatuhan Perka BPS No. 4/2024.
  - Panel Informasi Pegawai Terautentikasi SSO (Foto profil, nama lengkap, NIP 18 digit, dan tim kerja).
  - Bingkai Pratinjau Webcam Interaktif dengan kotak deteksi wajah AI (*face bounding box*), indikator status geofence aktif di zona kantor Jl. Pahlawan No. 6 Semarang, dan tombol aksi biru muda Pristi `[Ambil Foto]`.
  - Panel Hasil Jepretan & Verifikasi Koordinat GPS sebelum menekan tombol kirim presensi resmi.

### 5.8. Node 8: Modul PRISTI Rekap Presensi & Jadwal Pegawai (`/admin/schedules`, `/admin/rekap-presensi`)
- **ID Node Kanvas:** `831c35f7-8487-4711-a4d7-cbdc6516417e`
- **Preset Perangkat:** Desktop (1440x900 piksel)
- **Tujuan Antarmuka:** Manajemen jadwal shift piket meja PST dan rekap kehadiran harian seluruh 186 pegawai BPS Jawa Tengah.
- **Komponen Utama:**
  - Header terpadu dengan lencana status `186/186 Pegawai Terdata` dan sub-bar tombol kembali ke dasbor utama.
  - 4 Kartu KPI Kepatuhan Presensi Provinsi (Tingkat Kepatuhan 96.8%, Pegawai Hadir Tepat Waktu, Pegawai Dinas Luar, dan Tingkat Kelulusan AI Liveness).
  - Matriks Kalender 5 Hari Kerja Rotasi Meja Loket PST (Senin s.d. Jumat untuk 5 meja pelayanan).
  - Tabel Rekapitulasi Presensi Lengkap per Tim Kerja internal (Bagian Umum, Sosial, Produksi, Distribusi, Nerwilis, IPDS) dengan fitur pencarian dan tombol ekspor Excel resmi.

### 5.9. Node 9: Portal & Backoffice Pengaduan (`/pengaduan`, `/admin/complaints`)
- **ID Node Kanvas:** `3cfd0ce5-a76f-4b2a-a518-d43aef441d68`
- **Preset Perangkat:** Desktop (1440x900 piksel)
- **Tujuan Antarmuka:** Portal pelaporan keluhan masyarakat sekaligus meja verifikasi tindak lanjut bagi Tim Pengawasan Internal (SPI) BPS Jawa Tengah.
- **Komponen Utama:**
  - Header terpadu dengan lencana SLA respons cepat (`SLA Respons: <= 1x24 Jam`) dan sub-bar navigasi kembali.
  - 4 Kartu Status SLA Pengaduan (Total Aduan Masuk, Dalam Proses Triage, Selesai Ditindaklanjuti, Rasio Kepuasan Tindak Lanjut 98.2%).
  - Panel Kiri: Formulir Pengaduan Masyarakat dengan opsi anonimitas terjamin, pilihan kategori aduan, dan unggah berkas bukti pendukung.
  - Panel Kanan: Meja Kerja Verifikator Pengaduan (Triage Desk), pelacakan progres tiket aduan berformat `ADU-2026-0042`, serta integrasi dropdown penautan sanksi ke sistem penilaian ELENA jika terbukti pelanggaran layanan.

### 5.10. Node 10: Modul ELENA Matriks Evaluasi & Piagam (`/admin/elena-evaluation`)
- **ID Node Kanvas:** `1f163670-b45f-43ba-a47a-92e474d60272`
- **Preset Perangkat:** Desktop (1440x900 piksel)
- **Tujuan Antarmuka:** Sistem evaluasi berkala kinerja petugas PST BPS Jawa Tengah, penentuan pegawai teladan, dan pencetakan piagam penghargaan berformat PDF resmi.
- **Komponen Utama:**
  - Header terpadu dengan lencana sinkronisasi otomatis dan bilah aksi evaluasi (Tarik Skor Terkini, Kunci Hasil Evaluasi, dan Cetak Piagam Penghargaan PDF).
  - Bilah Informasi Bobot Penilaian ELENA (Kepuasan Pengunjung 40%, Kerapian Kehadiran PRISTI 30%, Kepatuhan SLA Konsol Loket 20%, Penilaian Tim Penilai SDM 10%).
  - Tabel Matriks Evaluasi Pegawai Komprehensif: Menampilkan NIP, nama petugas, skor otomatis antar-modul, input skor kualitatif manajerial, nilai akhir terbobot, dan peringkat bulanan.
  - Modal Pratinjau Piagam Penghargaan Petugas PST Teladan berstandar layout resmi BPS Provinsi Jawa Tengah.

### 5.11. Node 11: Halaman Login SSO Pegawai (`/admin/login`)
- **ID Node Kanvas:** `832617cc-0527-4103-ae9c-c0e89ccd1c3a`
- **Preset Perangkat:** Desktop (1440x900 piksel, Single Centered Card)
- **Tujuan Antarmuka:** Gerbang autentikasi tunggal bagi seluruh aparatur BPS Jawa Tengah untuk mengakses area backoffice sistem SEMARPST.
- **Komponen Utama:**
  - Bilah header minimalis dengan logo BPS Jateng dan tautan kembali ke Portal Publik.
  - Kartu Pahlawan Tunggal Terpusat (`max-w-[420px]`):
    - Ikon kunci kedinasan berwibawa berlatar biru lembut.
    - Judul formal `Login SSO Pegawai`.
    - Input Kredensial Fleksibel: Label **Username atau Email Dinas** dengan placeholder informatif (menerima input username singkat `ardhian.ds` maupun email lengkap `@bps.go.id` tanpa badge suffix kaku).
    - Input Kata Sandi SSO dengan tombol toggle visibilitas (*Lihat*).
    - Kotak centang *Ingat sesi di perangkat ini* berstatus keamanan TLS 1.3.
    - Tombol primer `[Masuk Sistem]` berlatar BPS Primary Blue (`#005999`).
  - Bilah Informasi Bantuan & Keamanan Akun IPDS (menggantikan tombol WebSSO pihak ketiga yang redundan): Menampilkan catatan integrasi direktori akun resmi BPS dan tautan cepat ke Helpdesk IPDS Ext. 120.
  - Footer kelembagaan 1-baris bersih memuat hak cipta resmi BPS Provinsi Jawa Tengah.

### 5.12. Node 12: Micro-Survey Statisfy HP 15 Detik (`/s/{qr_token}`)
- **ID Node Kanvas:** `7f497109-8312-46a2-bda0-5ed56a9a6a13`
- **Preset Perangkat:** Mobile Smartphone (375x812 piksel, One-Hand Touch Ergonomics)
- **Tujuan Antarmuka:** Formulir survei kepuasan kilat berbasis pemindaian QR Code (di struk tiket atau plakat meja) yang dirancang untuk diisi pengunjung dalam waktu kurang dari 15 detik tanpa mengetik teks (*Zero Typing*).
- **Komponen Utama:**
  - Header seluler ringkas berlatar BPS Deep Navy (`#005288`) dengan lencana keamanan SSL 256-bit dan identitas instansi.
  - Kartu Kunjungan Terkunci Otomatis: Menampilkan nomor tiket pengunjung (`NO. A-024`), foto dan nama petugas yang melayani (`Hendra Wibowo, S.Si.`), nama meja loket, serta waktu layanan.
  - Pertanyaan Utama SKD 4 Skala Reaksi Resmi BPS:
    1. **Sangat Puas** (Nilai 4, Ikon Bintang/Emotikon BPS Hijau `#6CBE45`)
    2. **Puas** (Nilai 3, Biru Langit)
    3. **Cukup Puas** (Nilai 2, Oranye Lembut)
    4. **Kurang Puas** (Nilai 1, Abu-abu Netral)
  - Pilihan Chip Kategori Layanan Cepat ramah jempol.
  - Kartu Penutup Apresiasi Kedinasan Bersih: Berfokus pada maklumat integritas anti-gratifikasi resmi BPS Jateng (tanpa kupon undian souvenir yang tidak relevan).

---

## 6. Standar Aksesibilitas, Responsivitas & Performa

### 6.1. Kontrak Responsivitas Multi-Perangkat
Sistem SEMARPST dirancang untuk berjalan pada 4 spektrum perangkat keras yang berbeda di lingkungan kantor BPS Provinsi Jawa Tengah:
1. **Layar Sentuh Anjungan Kios (1080p/720p Touchscreen):** Target sentuh tombol minimum 48x48 piksel dengan feedback visual instan saat ditekan (`active:scale-[0.99]`).
2. **Display Televisi Ruang Tunggu (1080p Widescreen 16:9 Non-Interactive):** Desain bebas scroll (*zero-scroll*) dengan ukuran tipografi besar yang dapat dibaca jelas dari jarak pandang 5 meter.
3. **Komputer Kerja Pegawai & Laptop Petugas (Resolusi 1280x800 hingga 1920x1080):** Kerapian bento grid dan navigasi modul yang tidak pernah terpotong pada batas resolusi horizontal.
4. **Smartphone Pengunjung (Layar 360px hingga 428px):** Performa ultra-ringan dengan bobot halaman di bawah 150 KB untuk menjamin waktu buka sub-1.2 detik pada jaringan seluler lobi.

### 6.2. Standar Integritas Kode & Styling
- **Utility-First Tanpa CSS Inline:** Seluruh styling dibangun murni menggunakan kelas utilitas Tailwind CSS. Dilarang keras menulis atribut `style=""`, tag `<style>`, skrip `<script>`, atau tag `<link>` ke CSS eksternal di dalam komponen antarmuka.
- **Standar Tanda Hubung Baku (Rule #6 AGENTS.md):** Seluruh label teks, judul modul, dokumentasi, dan metadata wajib menggunakan tanda hubung standar ASCII (`-`). Penggunaan em-dash (`\u2014`) dan en-dash (`\u2013`) dilarang secara ketat.

---

## 7. Kesimpulan & Panduan Implementasi Frontend

Dokumen `design.md` ini menjadi acuan tunggal (*single source of truth*) bagi tim rekayasa frontend dan backend dalam menerjemahkan rancangan antarmuka kanvas menjadi komponen Blade/Livewire pada proyek Laravel SEMARPST. Setiap penambahan fitur baru di masa mendatang wajib merujuk pada token warna resmi BPS, struktur header bebas luapan, dan filosofi anti-slop yang telah dibakukan dalam dokumen ini.
