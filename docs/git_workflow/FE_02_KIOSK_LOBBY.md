# Workflow Log: FE-02 Kios Lobi Mandiri Sentuh - Node 2

## Informasi Pekerjaan
- **ID Tiket:** `FE-02`
- **Modul:** Anjungan Kios Lobi Mandiri Sentuh (Node 2 Design.md & Mockup Kios Lobby)
- **Komponen Livewire:** `App\Livewire\KioskLobby` (`/kiosk`)
- **Tampilan Blade:** `resources/views/livewire/kiosk-lobby.blade.php`
- **Layout:** `resources/views/components/layouts/kiosk.blade.php`
- **Waktu Eksekusi:** Oktober 2026

---

## 1. Spesifikasi Teknis & Rancang Bangun (Touchscreen Kiosk 1440x900)
1. **Header Kios Lobi Deep Navy (`#005288`):**
   - Garis aksen bawah oranye hangat (`#F7941D`).
   - Identitas kelembagaan: Lencana `LOBI UTAMA PST`, nama instansi `Badan Pusat Statistik Provinsi Jawa Tengah`, dan judul `Kios Antrian Mandiri PST`.
   - Jam digital real-time format WIB (`08:42:15 WIB`), penanggalan Indonesia lengkap, serta indikator hijau aktif `LAYANAN AKTIF`.
   - Tombol aksesibilitas: tombol beralih `Audio` (TTS) dan tombol panggil `Bantuan` petugas lobi.
2. **3 Mode Operasional Sentuh Besar:**
   - Tab 1: `AMBIL TIKET ANTREAN PST` (Badge: `4 PILAR` - Mahasiswa, Peneliti, Pimpinan, Publik Umum).
   - Tab 2: `BUKU TAMU KEDINASAN` (Badge: `NON-PST` - Rapat, Audiensi, Koordinasi Antar-Sektor).
   - Tab 3: `CHECK-OUT KUNJUNGAN` (Selesai Layanan & Kembalikan Kartu Tamu).
3. **Notice Banner & Kesiapan Hardware:**
   - Banner petunjuk pengambilan tiket terpadu.
   - Lencana status hijau siap pakai: `Kertas Struk: Siap`.
4. **4 Pilar Layanan Terpadu PST (Grid 4 Kartu Sentuh):**
   - Rujukan Peraturan BPS Nomor 2 Tahun 2021: Lencana hijau `Seluruh Layanan GRATIS (Tanpa Pungutan)`.
   - Kartu 1: `LOKET 1` / `KODE A` - `Perpustakaan & Ruang Baca` (baca publikasi, koleksi softcopy, katalog sensus; tombol `AMBIL A >`).
   - Kartu 2: `FAVORIT` / `LOKET 2 & 3` / `KODE B` - `Konsultasi Statistik & Data` (metodologi, indikator makro inflasi/kemiskinan/PDRB; tombol `AMBIL B >`).
   - Kartu 3: `LOKET 3` / `KODE C` - `Pembelian Data Mikro & Peta` (data mentah sensus/survei, peta wilayah kerja statistik, PNBP resmi; tombol `AMBIL C >`).
   - Kartu 4: `SOP TERIKAT` / `KODE D` - `Rekomendasi (ROMANIK)` (rekomendasi statistik sektoral OPD Pemprov Jateng Satu Data Indonesia; tombol `AMBIL D >`).
   - Setiap kartu menyajikan pemantauan antrean menunggu dan perkiraan estimasi waktu tunggu secara real-time.
5. **Simulasi Cetak Struk Fisik (Thermal Receipt ESC/POS 80mm):**
   - Kolom Kiri: Display dark navy kartu tiket berhasil diterbitkan, nomor tiket jumbo monospaced (`B-001`), penanda meja tujuan, estimasi tunggu, tombol `CETAK ULANG STRUK` dan `SELESAI / AMBIL TIKET`.
   - Kolom Kanan: Pratinjau struk termal fisik 80mm presisi dengan logo resmi BPS, tepi bawah kertas bergigi robekan perforasi (`.receipt-tear-bottom`), nomor tiket, tipe layanan, waktu cetak, target SLA (15 menit), QR code SVG dinamis (`/s/{qr_token}`) untuk pemantauan mandiri di ponsel, dan pesan maklumat pelayanan.
6. **Registrasi Cepat Tamu Kedinasan BPS Provinsi Jawa Tengah (Buku Tamu Digital):**
   - Banner header biru bergradien dengan lencana `KUNJUNGAN NON-PST` dan status `BUKU TAMU DIGITAL: Penjadwalan & Check-In Mandiri`.
   - 4 field input: Nomor WhatsApp (+62) dengan auto-lookup otomatis terhadap profil pengunjung yang pernah berkunjung, NIK / Nomor KTP, Nama Lengkap Tamu, Asal Instansi / Dinas.
   - Chip pilihan cepat instansi terdaftar: `Dinas Kominfo Jateng`, `Bappeda Prov Jateng`, `Inspektorat Jateng`, `Perguruan Tinggi (Undip / Unnes)`, `Media Massa / Wartawan`.
   - Dropdown tim kerja yang dituju dan keperluan kunjungan kedinasan.
   - Kepatuhan standar keamanan ISO 27001 dan tombol submit `Simpan Buku Tamu & Terbitkan Visitor Pass`.
7. **Terminal Footer Dark Navy (`#0B132B`):**
   - Lencana oranye `KEBIJAKAN ZERO-RUPIAH` sesuai PP Nomor 28 Tahun 2024 tentang PNBP BPS RI (seluruh layanan Rp 0,-).
   - Indikator status perangkat keras: `Status Kertas: Aman (80/80mm)`, `Koneksi LAN: 1 Gbps (Laten 2ms)`, `Scanner KTP: Aktif & Siap`, `Dispenser Card: Ready`.
   - Identitas mesin: `ID TERMINAL: KIOSK_LOBBY_01` (SEMARPST OS KIOSK HARDWARE v2.4) dan tombol aksi cepat `Panggil Petugas Lobi`.
8. **Audio Engine & Modal Interaktif:**
   - Web Audio API harmonic chime (C5 -> E5 -> G5) dan sintesis suara Bahasa Indonesia (`SpeechSynthesisUtterance` `id-ID`).
   - Modal bantuan petugas lobi (`showHelpModal`).
   - Modal pratinjau visitor pass tamu (`showSuccessPassModal`).
   - Drawer modal check-out pengunjung aktif hari ini (`pst_visitor_logs`).

---

## 2. Berkas yang Dibuat & Dimodifikasi
- `app/Livewire/KioskLobby.php`: Komponen Livewire controller untuk anjungan kios mandiri.
- `resources/views/components/layouts/kiosk.blade.php`: Layout HTML5 touchscreen 1440x900 dengan font Google Plus Jakarta Sans & JetBrains Mono, style perforasi struk thermal, dan CSS print 80mm ESC/POS.
- `resources/views/livewire/kiosk-lobby.blade.php`: Template antarmuka sentuh lengkap sesuai mockup referensi Kios Lobi.
- `routes/web.php`: Pendaftaran rute `/kiosk` mengarah ke `KioskLobby::class`.
- `tests/Feature/KioskLobbyTest.php`: Pengujian fitur otomatis untuk anjungan kios, transaksi tiket atomik, buku tamu, auto-lookup, dan check-out.

---

## 3. Hasil Pengujian & Debugging (Rule #1)
- **Syntax Check:** `php -l` lulus 100% pada seluruh berkas baru.
- **Frontend Build:** `npm run build` sukses dalam 3.65s (Tailwind CSS v4 & JS terkompilasi optimal).
- **Automated Feature Tests:**
  - `KioskLobbyTest`: 6 passed (31 assertions).
  - Full Test Suite: **50 passed (251 assertions), 100% PASS**.
- **Browser Subagent Visual Verification:**
  - Diuji pada Chrome 1440x900 resolution (viewport kios mandiri resmi).
  - 0 error pada browser console.
  - Alur pengambilan tiket Kode B teruji atomik dengan penerbitan nomor `B-001`, kalkulasi estimasi antrean, dan render SVG QR code dinamis.
  - Dialog modal panggil bantuan petugas lobi (`Bantuan`) dan penutupannya teruji responsif.
