LAPORAN BULANAN KEGIATAN MBKM
BULAN OKTOBER TAHUN 2026
Pengembangan Antarmuka Pengguna (UI/UX) dan Rekayasa Frontend Ekosistem Aplikasi Kedinasan (Sistem Lembur TEMPE DELE, SIMANTIK, dan SEMAR PST)
DI BPS Provinsi Jawa Tengah
oleh :
Ardhian Dwi Saputra
2311104040
S1 Rekayasa Perangkat Lunak
Direktorat Kampus Purwokerto
Telkom University
2026

LEMBAR PENGESAHAN
Pengembangan Antarmuka Pengguna (UI/UX) dan Rekayasa Frontend Ekosistem Aplikasi Kedinasan (Sistem Lembur TEMPE DELE, SIMANTIK, dan SEMAR PST)
DI BPS Provinsi Jawa Tengah
Disusun Oleh :
Ardhian Dwi Saputra
2311104040
Disetujui dan disahkan sebagai Laporan Bulanan
Bulan OKTOBER Tahun 2026
MAGANG REKAYASA PERANGKAT LUNAK / ANALISIS SISTEM

Semarang, 31 Oktober 2026
Pembimbing Lapangan
Mahasiswa

Yuli Purwitasari, S.ST
Ardhian Dwi Saputra
NIP. 340050277
NIM. 2311104040

Mengetahui,
Dosen Pembimbing Magang

Faisal Dharma Adhinata, S.Kom., M.Cs
NIP. 22930008

---

## Ringkasan Laporan
Laporan bulanan ini memuat pelaksanaan kegiatan magang MBKM pada periode Oktober 2026 (01 Oktober 2026 - 31 Oktober 2026) di Badan Pusat Statistik (BPS) Provinsi Jawa Tengah. Melanjutkan capaian bulan pertama (September 2026) yang telah berhasil menuntaskan perancangan antarmuka dan visualisasi dasbor platform SWARA JAGAD (Sistem Wawasan Terintegrasi Ekonomi Hijau Jawa Tengah Berbasis Data), kegiatan magang pada bulan kedua difokuskan pada perancangan antarmuka pengguna (UI/UX Design) dan rekayasa frontend pada tiga ekosistem perangkat lunak strategis kedinasan BPS Provinsi Jawa Tengah, yaitu: finalisasi penyerahan SWARA JAGAD serta inisiasi sistem manajemen layanan teknologi informasi (SIMANTIK), penyempurnaan menyeluruh ergonomi antarmuka Sistem Lembur (TEMPE DELE), serta perancangan fondasi antarmuka generasi baru sistem pelayanan terpadu SEMAR PST. Rangkaian aktivitas bulanan tersebut terbagi secara kronologis ke dalam tiga minggu kerja:

Pada Minggu I (01 - 07 Oktober 2026), kegiatan diawali dengan pendampingan teknis antarmuka dan konsolidasi aset visual platform SWARA JAGAD menjelang serah terima resmi (handover) kepada pihak Bappeda Provinsi Jawa Tengah. Selanjutnya, fokus kerja diarahkan pada inisiasi perancangan antarmuka sistem manajemen layanan teknologi informasi internal BPS Jateng (SIMANTIK). Aktivitas mencakup pemetaan alur pengguna (user flow) pelaporan kendala perangkat kerja, penyusunan kerangka kawat (wireframing) dasbor inventaris TI, perancangan formulir tiket kendala ber-stepper progres visual interaktif, penataan tabel aset perangkat keras ber-filter status kondisi, serta standardisasi palet warna resmi BPS yang memenuhi kaidah aksesibilitas (WCAG AA compliant).

Pada Minggu II (08 - 14 Oktober 2026), kegiatan difokuskan pada pembenahan komprehensif tata letak, kenyamanan interaksi, dan ergonomi layar Sistem Lembur (TEMPE DELE) BPS Provinsi Jawa Tengah. Capaian pada pekan ini meliputi perancangan tata letak cetak langsung (direct print) lembar Daftar Hadir lembur (terpisah PNS dan PPPK) dan format ekspor SPKL Excel ber-auto row height, restrukturisasi bilah filter riwayat lembur menjadi dropdown mandiri (bulan dan tahun terpisah), implementasi Collapsible Sidebar desktop ala Canvas LMS ber-animasi transisi halus dan persistensi localStorage, redesain tabel persetujuan Ketua Tim (/ketua-tim/pengajuan) pas 100% desktop tanpa scrollbar horizontal dan ber-kolom sticky solid pada ponsel pintar, perancangan formulir pengajuan lembur (#modalAjukan) menjadi Bounded Modal Container 2-kolom zero-scroll di desktop dengan kanvas tanda tangan digital proporsional, implementasi tabel horizontal swipeable seluler dengan indikator sentuh dan kinetic momentum touch gliding, serta penerapan prinsip desain anti-slop pada modul Manajemen User (/admin/manajemen-user).

Pada Minggu III (15 - 21 Oktober 2026), pengembangan diarahkan pada penyelesaian estetika modal sistem lembur berarsitektur 2-layer backdrop modal murni (@stack('modals')) yang menghilangkan celah garis putih di bawah layar peramban, dilanjutkan dengan kick-off perancangan antarmuka sistem terpadu SEMAR PST (Pelayanan Statistik Terpadu) yang mengonsolidasikan 5 aplikasi layanan lobi BPS Jateng (PST, Statisfy, Pristi, Pengaduan, dan Elena). Aktivitas mencakup audit titik friksi pengalaman pengguna (UX friction) sistem warisan, perumusan Design System berbasis Tailwind CSS dengan palet resmi BPS (Deep Navy, Orange BPS, dan Emerald Green), perancangan antarmuka Kiosk Lobi Sentuh (/kiosk) dengan 4 tombol layanan besar dan modal buku tamu ber-keyboard angka numerik, perancangan tata letak TV Antrian Ruang Tunggu Widescreen 16:9 (/antrian) ber-animasi kartu aktif dan integrasi audio Web Speech API Bahasa Indonesia, perancangan antarmuka Micro-Survey HP 15-Detik (/s/{qr_token}) yang mematuhi aturan sub-1.2 second loading, perancangan antarmuka kerja 1-layar Konsol Operator Meja Loket (/admin/operator-console), serta penyusunan dokumen mandiri Roadmap Mingguan dan 25 Tiket Kanban Backlog Frontend (docs/ROADMAP_DAN_BACKLOG_FRONTEND.md) dengan target akselerasi feature-complete pada 12 Desember 2026.

Selama pelaksanaan kegiatan bulan Oktober, tantangan utama yang dihadapi meliputi penataan tabel transaksi berkolom banyak agar muat pas pada layar laptop tanpa memicu scrollbar horizontal, distorsi kanvas tanda tangan digital pada berbagai variasi resolusi layar, kenyamanan navigasi tabel lebar pada peramban ponsel pintar, serta pemenuhan batas performa muat di bawah 1,2 detik pada antarmuka survei publik di lingkungan lobi kantor yang memiliki fluktuasi sinyal seluler. Seluruh kendala tersebut berhasil diatasi melalui penerapan Collapsible Sidebar, Bounded Modal Container 2-kolom zero-scroll, rekayasa tabel horizontal swipeable kinetik, dan arsitektur frontend ultra-ringan tanpa dependensi pustaka JavaScript yang berat.

---

## Kata Pengantar
Puji syukur penulis panjatkan ke hadirat Tuhan Yang Maha Esa atas limpahan rahmat, taufik, dan karunia-Nya, sehingga penulis dapat menyelesaikan penulisan Laporan Bulanan Magang MBKM Periode Bulan Oktober Tahun 2026 di Badan Pusat Statistik (BPS) Provinsi Jawa Tengah dengan lancar dan tepat waktu.

Laporan bulanan ini disusun sebagai bentuk dokumentasi dan pertanggungjawaban atas penugasan magang yang dilaksanakan, khususnya dalam lingkup perancangan antarmuka pengguna (User Interface/UI) dan rekayasa sisi depan (Frontend Development) pada ekosistem aplikasi kedinasan BPS Provinsi Jawa Tengah (Sistem Lembur TEMPE DELE, SIMANTIK, dan SEMAR PST), sekaligus guna memenuhi persyaratan akademik Program Studi S1 Rekayasa Perangkat Lunak, Telkom University Kampus Purwokerto.

Penulis menyampaikan penghargaan dan terima kasih yang setinggi-tingginya kepada seluruh pihak yang telah memberikan arahan, dukungan, dan kerja sama selama pelaksanaan kegiatan:
1. Tuhan Yang Maha Esa atas kesehatan, kelancaran, dan kemudahan yang senantiasa dianugerahkan.
2. Pimpinan dan jajaran Badan Pusat Statistik (BPS) Provinsi Jawa Tengah atas penerimaan hangat, kepercayaan, dan lingkungan kerja kedinasan yang sangat profesional.
3. Ibu Yuli Purwitasari, S.ST, selaku Pembimbing Lapangan di BPS Provinsi Jawa Tengah atas bimbingan profesional, telaah antarmuka, masukan kebutuhan operasional, dan evaluasi berkelanjutan.
4. Bapak Faisal Dharma Adhinata, S.Kom., M.Cs, selaku Dosen Pembimbing Magang Telkom University Kampus Purwokerto atas pengarahan akademik, masukan metodologi, dan motivasi yang senantiasa diberikan.
5. Rekan partner pengembang, Muhammad Rifqi Al Baqi Ananta, atas kolaborasi solid dan pembagian peran yang sangat sinergis dalam mengintegrasikan antarmuka frontend dengan arsitektur backend di setiap modul aplikasi.
6. Orang tua dan segenap keluarga besar atas doa, pengertian, dan dukungan tanpa henti dalam setiap langkah perjalanan studi penulis.

Penulis menyadari bahwa laporan ini masih memiliki ruang untuk penyempurnaan. Oleh karena itu, masukan dan kritik yang membangun sangat penulis harapkan. Semoga laporan ini dapat memberikan manfaat nyata bagi institusi kampus, BPS Provinsi Jawa Tengah, serta pihak-pihak terkait.

Semarang, 31 Oktober 2026  
Ardhian Dwi Saputra  
NIM. 2311104040  

---

## Daftar Isi
- Ringkasan Laporan
- Kata Pengantar
- Daftar Isi
- BAB I PENDAHULUAN
  - 1.1 Latar Belakang
  - 1.2 Ruang Lingkup
  - 1.3 Tujuan
- BAB II KEGIATAN MAGANG
  - 2.1 Rincian Kegiatan
  - 2.2 Analisa Hasil Kegiatan
  - 2.3 Hambatan dan Upaya untuk Mengatasinya
  - 2.4 Rekomendasi
- BAB III PENUTUP
  - 3.1 Kesimpulan
  - 3.2 Saran
- LAMPIRAN
  - Lampiran 1: Tabel Logbook Harian Resmi (Oktober 2026)
  - Lampiran 2: Dokumentasi Kegiatan Antarmuka Pengguna

---

## BAB I PENDAHULUAN

### 1.1 Latar Belakang
Penyelenggaraan pelayanan publik dan administrasi internal pada lembaga pemerintah memerlukan dukungan perangkat lunak yang tidak hanya andal dalam pemrosesan data, tetapi juga menyajikan antarmuka pengguna (User Interface/UI) yang ergonomis, bersih, dan komunikatif. Pada Badan Pusat Statistik (BPS) Provinsi Jawa Tengah, aplikasi kedinasan digunakan oleh dua kelompok aktor dengan kebutuhan yang spesifik:
1. **Aparatur Sipil Negara (Pegawai dan Pimpinan BPS):** Memerlukan sistem internal yang memfasilitasi administrasi cepat tanpa hambatan tata letak, seperti persetujuan pengajuan lembur yang pas di layar laptop tanpa scrollbar horizontal, pelaporan kendala teknologi informasi yang terstruktur, dan pemantauan data tanpa ornamen visual yang berlebihan (anti-slop).
2. **Masyarakat dan Tamu Kedinasan (Pengunjung Pelayanan Statistik Terpadu):** Memerlukan sistem publik lobi yang mudah dipahami, seperti layar sentuh anjungan Kiosk mandiri dengan tombol besar ramah jari, layar televisi pemanggil antrean yang kontras dan jelas terdengar, serta kuesioner survei kepuasan yang dapat diselesaikan dalam hitungan detik (15-second micro-survey) pada ponsel pintar.

Setelah pada bulan pertama (September 2026) penulis menuntaskan perancangan antarmuka dan visualisasi dasbor platform SWARA JAGAD hingga siap diserahterimakan ke Bappeda Jateng, penugasan magang pada bulan Oktober 2026 difokuskan pada pembenahan dan rekayasa antarmuka tiga sistem kedinasan:
1. **Sistem Lembur (TEMPE DELE):** Menyempurnakan tata letak formulir dan tabel persetujuan berjenjang agar responsif di komputer desktop maupun ponsel pintar.
2. **SIMANTIK:** Menginisiasi perancangan antarmuka sistem manajemen layanan teknologi informasi dan inventaris aset perangkat BPS Jateng.
3. **SEMAR PST:** Menginisiasi perancangan antarmuka generasi baru untuk menyatukan 5 aplikasi pelayanan publik lobi BPS Jateng ke dalam satu arsitektur terintegrasi.

### 1.2 Ruang Lingkup
Ruang lingkup penugasan magang mahasiswa pada periode Oktober 2026 berfokus pada:
1. **Pendampingan Antarmuka SWARA JAGAD & Inisiasi SIMANTIK:**
   - Melakukan finalisasi visual dan konsolidasi aset Swagger UI SWARA JAGAD menjelang serah terima resmi ke Bappeda Jawa Tengah.
   - Menyusun alur pengguna (user flow), kerangka kawat (wireframe), formulir tiket kendala ber-stepper progres, dan tabel aset TI pada sistem SIMANTIK.
2. **Penyempurnaan Ergonomi Antarmuka Sistem Lembur (TEMPE DELE):**
   - Merancang lembar cetak langsung (direct print) Daftar Hadir lembur (PNS dan PPPK) dan dokumen SPKL Excel ber-auto row height.
   - Mengembangkan Collapsible Sidebar desktop ala Canvas LMS ber-animasi halus dan penyimpanan status buka-tutup pada localStorage.
   - Meredesain tabel persetujuan Ketua Tim (/ketua-tim/pengajuan) pas 100% desktop tanpa scrollbar horizontal dan ber-kolom sticky solid pada ponsel pintar.
   - Merestrukturisasi modal pengajuan lembur (#modalAjukan) menjadi Bounded Modal Container 2-kolom zero-scroll di desktop dengan kanvas tanda tangan digital proporsional.
   - Menerapkan tabel horizontal swipeable seluler dengan indikator sentuh dan kinetic momentum touch gliding.
   - Menerapkan prinsip desain anti-slop pada modul Manajemen User (/admin/manajemen-user) dan penataan 2-layer backdrop modal murni (@stack('modals')).
3. **Inisiasi Perancangan Frontend SEMAR PST (Konsolidasi 5 Sistem Layanan BPS Jateng):**
   - Melakukan audit titik friksi pengalaman pengguna (UX friction) pada 5 aplikasi warisan lobi BPS Jateng.
   - Merancang Design System berbasis Tailwind CSS dengan palet warna resmi BPS dan tipografi modern.
   - Merancang tata letak Kiosk Lobi Sentuh (/kiosk), Layar TV Antrian Widescreen 16:9 (/antrian), Micro-Survey HP 15-Detik (/s/{qr_token}), dan Konsol Operator Meja Loket (/admin/operator-console).
   - Menyusun dokumen resmi Roadmap Mingguan dan 25 Tiket Kanban Backlog Frontend (docs/ROADMAP_DAN_BACKLOG_FRONTEND.md).

### 1.3 Tujuan
Tujuan pelaksanaan kegiatan magang pada bulan kedua ini adalah:
1. Memastikan proses serah terima platform SWARA JAGAD berjalan lancar dan menginisiasi rancangan antarmuka SIMANTIK yang terstandarisasi untuk tata kelola layanan TI BPS Jateng.
2. Mengeliminasi seluruh hambatan tata letak, scrollbar ganda, dan ketidaksesuaian antarmuka mobile pada Sistem Lembur TEMPE DELE sehingga proses persetujuan dan pengajuan lembur dapat diselesaikan secara efisien.
3. Menghasilkan arsitektur antarmuka modern, interaktif, dan terstruktur bagi sistem terpadu SEMAR PST, lengkap dengan roadmap dan 25 tiket backlog frontend guna mendukung target penyelesaian proyek sebelum akhir Desember 2026.

---

## BAB II KEGIATAN MAGANG

### 2.1 Rincian Kegiatan
Berikut adalah rekapitulasi pelaksanaan kegiatan magang sisi perancangan antarmuka (UI/UX) dan rekayasa frontend selama bulan Oktober 2026:

#### Tabel 2.1 Rekap Mingguan Kegiatan Magang (Oktober 2026)
| No | Minggu | Kegiatan Utama | Dokumentasi Output |
|:---:|:---|:---|:---|
| 1 | Minggu I (01 - 07 Oktober 2026) | Pendampingan teknis dan finalisasi penyerahan platform SWARA JAGAD ke Bappeda Jateng, serta inisiasi perancangan antarmuka sistem manajemen layanan TI BPS Jateng (SIMANTIK) mencakup user flow, wireframing, formulir tiket kendala ber-stepper visual, dan tabel inventaris perangkat keras. | Tampilan Swagger UI bertema kedinasan, berkas wireframe dasbor SIMANTIK, rancangan UI form tiket kendala TI, dan tabel inventaris perangkat. |
| 2 | Minggu II (08 - 14 Oktober 2026) | Redesain komprehensif Sistem Lembur (TEMPE DELE): Collapsible Sidebar ala Canvas LMS, tabel persetujuan Ketua Tim pas 100% desktop tanpa scrollbar dan sticky column solid di mobile, Bounded Modal Container pengajuan 2-kolom zero-scroll, tabel horizontal swipeable seluler kinetik, dan modul Manajemen User anti-slop. | View Blade Sidebar, lembur.blade.php, ketua-tim/pengajuan.blade.php, admin/manajemen-user.blade.php, format cetak Daftar Hadir, dan template SPKL Excel. |
| 3 | Minggu III (15 - 21 Oktober 2026) | Penataan 2-layer backdrop modal murni (@stack('modals')) Sistem Lembur, audit UX 5 sistem lobi lama, perumusan Design System BPS, perancangan antarmuka Kiosk Sentuh (/kiosk), TV Antrian 16:9 (/antrian), Micro-Survey HP sub-1.2s (/s/{token}), Konsol Operator, dan penyusunan Roadmap Kanban Backlog Frontend (FE-01 s/d FE-25). | Berkas UX Audit, layout Blade SEMAR PST, view Kiosk sentuh, view TV Reverb TTS, view Micro-Survey mobile, dan berkas mandiri docs/ROADMAP_DAN_BACKLOG_FRONTEND.md. |

Aktivitas perancangan antarmuka selama bulan Oktober 2026 berfokus pada transisi yang mulus dari platform analitik makro (SWARA JAGAD) menuju sistem operasional internal (TEMPE DELE dan SIMANTIK) dan sistem pelayanan publik terpadu (SEMAR PST). Penekanan diberikan pada kecepatan buka halaman, eliminasi scrollbar horizontal yang tidak perlu, kenyamanan penandatanganan digital, dan kesiapan operasional lobi fisik.

### 2.2 Analisa Hasil Kegiatan
1. **Keberhasilan Handover & Standarisasi SIMANTIK:** SWARA JAGAD berhasil diserahterimakan dengan dokumentasi Swagger UI yang rapi, sementara rancangan antarmuka SIMANTIK berhasil memetakan kebutuhan digitalisasi layanan kendala TI BPS Jateng dalam format wireframe yang terstruktur.
2. **Efisiensi Kerja pada Sistem Lembur TEMPE DELE:**
   - Redesain tabel persetujuan Ketua Tim berhasil pas 100% pada layar laptop tanpa scrollbar horizontal, menghemat waktu evaluasi data lembur oleh pimpinan tim.
   - Kolom No dan Nama Pegawai sticky solid pada ponsel pintar menjamin identitas pegawai tetap terbaca saat tabel digeser ke samping.
   - Modal pengajuan lembur 2-kolom zero-scroll menghilangkan keharusan menggulir layar ke bawah dan menjaga ukuran kanvas tanda tangan tetap presisi.
   - Tabel horizontal swipeable kinetik memberikan pengalaman bernavigasi di ponsel pintar yang setara dengan aplikasi native mobile.
   - Modul Manajemen User berprinsip anti-slop menghemat ruang layar hingga 40% dan mempertegas segregasi wewenang pejabat penandatangan dan administrator.
3. **Pondasi Antarmuka Terpadu SEMAR PST:** Terwujudnya rancangan antarmuka Kiosk lobi mandiri, TV antrian widescreen ber-audio TTS Bahasa Indonesia, dan form micro-survey mobile yang mematuhi aturan kecepatan buka di bawah 1,2 detik, didukung dengan roadmap dan backlog teknis 25 tiket frontend terukur.

### 2.3 Hambatan dan Upaya untuk Mengatasinya
1. **Hambatan 1 - Keterbatasan Ruang Layar pada Tabel Lebar (Sistem Lembur):** Tabel persetujuan lembur memiliki 10 kolom data yang menyebabkan teks bertabrakan atau terpotong pada laptop beresolusi sedang (1366x768).  
   *Upaya Mengatasinya:* Mengembangkan Collapsible Sidebar desktop ala Canvas LMS agar tabel melebar otomatis saat bilah menu ditutup, merapatkan padding sel (px-1.5 py-2), menyeragamkan tinggi baris (h-16), menyusun header tanggal bertingkat (flex-col), dan memindahkan teks uraian tugas panjang ke dalam modal pop-up interaktif.
2. **Hambatan 2 - Pengalaman Menggeser Tabel yang Kaku di Peramban Ponsel (Sistem Lembur):** Pengguna ponsel pintar kesulitan menggeser tabel yang lebar karena ketiadaan petunjuk visual dan scrollbar bawaan yang kaku.  
   *Upaya Mengatasinya:* Membangun komponen indikator sentuh visual (.table-scroll-hint), tombol navigasi cepat [ < ] dan [ > ], serta menerapkan kinetic momentum touch gliding berbasis JavaScript sentuh 1:1 yang sangat halus tanpa lag.
3. **Hambatan 3 - Distorsi Kanvas Tanda Tangan Digital pada Berbagai Resolusi Layar:** Kanvas tanda tangan digital pada formulir pengajuan lembur kerap mengalami distorsi goresan saat ukuran jendela peramban berubah.  
   *Upaya Mengatasinya:* Menata modal pengajuan ke dalam grid 2-kolom bounded container (max-h-[90vh]) di desktop dengan dimensi kanvas yang terkunci tetap, mempertahankan fallback 1 kolom di ponsel, dan menginisialisasi ulang kalkulasi koordinat kanvas saat modal ditampilkan.
4. **Hambatan 4 - Kebocoran Latar Belakang Modal (Backdrop Layer Bleed):** Lapisan gelap dialog modal tidak menutup layar secara sempurna hingga ke batas bawah peramban, menyisakan garis putih footer yang mengganggu kenyamanan visual.  
   *Upaya Mengatasinya:* Merekayasa arsitektur 2-layer backdrop modal murni menggunakan mekanisme portaling root Blade (@stack('modals')) dan kelas utility fixed inset-0 min-h-screen, sehingga lapisan gelap dan efek buram menutup 100% viewport layar monitor tanpa celah bocor.
5. **Hambatan 5 - Sinyal Seluler Lemah di Ruang Lobi Kantor BPS (SEMAR PST):** Pengunjung yang memindai QR Code di lobi kerap mengalami kesulitan memuat formulir survei akibat koneksi internet seluler yang berfluktuasi.  
   *Upaya Mengatasinya:* Menetapkan aturan ketat Sub-1.2s Public Mobile Performance pada halaman /s/{qr_token}, mengeliminasi dependensi pustaka JavaScript eksternal yang berat, memanfaatkan CSS utilitas standar, dan menyajikan aset ikon dalam format inline SVG berukuran mikro.

### 2.4 Rekomendasi
1. Melakukan uji coba ergonomi secara berkala langsung dengan unit mesin Kiosk fisik di lobi kantor BPS Jateng untuk mengkalibrasi responsivitas sentuhan jari pada layar dan akurasi margin kertas thermal printer 58mm/80mm.
2. Menyiapkan pengujian durasi panjang (endurance testing) pada browser TV ruang tunggu guna memastikan pemutaran animasi kartu antrian dan suara sintesis Web Speech API stabil tanpa penurunan memori (zero memory leak).
3. Melanjutkan kolaborasi teknis yang erat bersama tim backend untuk menghubungkan komponen antarmuka Livewire ke Service API dan WebSocket Reverb sesuai jadwal sprint yang telah ditetapkan.

---

## BAB III PENUTUP

### 3.1 Kesimpulan
Berdasarkan pelaksanaan kegiatan magang MBKM pada bulan Oktober 2026 di Badan Pusat Statistik (BPS) Provinsi Jawa Tengah pada bidang Perancangan Antarmuka Pengguna (UI/UX) dan Rekayasa Frontend, dapat disimpulkan bahwa:
1. Tahap akhir penyerahan platform SWARA JAGAD berhasil didukung secara optimal, dan rancangan awal sistem SIMANTIK berhasil memetakan kebutuhan digitalisasi layanan teknologi informasi internal BPS Jateng.
2. Penyempurnaan antarmuka Sistem Lembur (TEMPE DELE) berhasil mewujudkan pengalaman pengguna yang modern, ergonomis, dan responsif di laptop maupun ponsel pintar (tabel Ketua Tim fit 100% desktop, kolom sticky mobile, modal pengajuan 2-kolom zero-scroll, collapsible sidebar, tabel horizontal swipeable kinetik, dan modul Manajemen User anti-slop).
3. Fondasi antarmuka, Design System, dan rancangan Kiosk, TV, Survei HP, serta Konsol Operator pada sistem terpadu SEMAR PST telah tersusun secara matang melalui dokumen resmi Roadmap Mingguan dan 25 Tiket Kanban Backlog Frontend (docs/ROADMAP_DAN_BACKLOG_FRONTEND.md) dengan target penyelesaian lebih cepat pada 12 Desember 2026.

### 3.2 Saran
1. Mengadakan sesi peninjauan desain (design review) bersama calon pengguna lobi (tamu dan petugas loket PST) untuk menguji kemudahan pemahaman simbol, tombol sentuh kiosk, dan kenyamanan kuesioner survei 15 detik sebelum fase simulasi fisik lobi.
2. Menjaga konsistensi token warna dan panduan komponen desain di seluruh modul agar pengalaman pengguna antar-aplikasi kedinasan BPS Jawa Tengah tetap selaras dan harmonis.

---

## LAMPIRAN 1: TABEL LOGBOOK HARIAN RESMI (OKTOBER 2026)

| No | Hari/Tanggal | Waktu | Lokasi | Pemberi Tugas | Kegiatan | Hasil yang Diperoleh | Kendala |
|:---:|:---|:---:|:---:|:---|:---|:---|:---:|
| 1 | Kamis, 01 Oktober 2026 | 07.30 - 16.00 | WFO | Pembimbing Lapangan | Melakukan pendampingan teknis antarmuka dan konsolidasi aset visual dasbor makro ekonomi hijau SWARA JAGAD menjelang serah terima ke Bappeda Jateng. | Seluruh hierarki visual, kontras warna kartu metrik RPJMD, dan tata letak kartu ringkasan eksekutif berada dalam kondisi optimal dan siap diserahterimakan. | - |
| 2 | Jumat, 02 Oktober 2026 | 07.30 - 16.30 | WFO | Pembimbing Lapangan | Menyelesaikan penyerahan berkas panduan desain SWARA JAGAD dan memulai inisiasi eksplorasi antarmuka modul layanan TI internal BPS Jateng (SIMANTIK). | Berkas desain SWARA JAGAD tuntas diserahterimakan; terpetakannya kebutuhan digitalisasi antarmuka pelaporan kendala perangkat dan monitoring aset TI BPS Jateng. | - |
| 3 | Senin, 05 Oktober 2026 | 07.30 - 16.00 | WFO | Pembimbing Lapangan | Merancang alur pengguna (user flow) dan kerangka kawat (wireframe) antarmuka dasbor utama sistem SIMANTIK. | Tersusunnya kerangka kawat dasbor SIMANTIK, kartu ringkasan tiket kendala aktif, dan blok pemantauan aset perangkat keras kantor BPS Jateng. | Menyelaraskan alur pelaporan kendala agar ringkas tanpa membebani pegawai dengan formulir yang terlalu panjang. |
| 4 | Selasa, 06 Oktober 2026 | 07.30 - 16.00 | WFO | Pembimbing Lapangan | Merancang antarmuka formulir pengajuan tiket bantuan kendala TI ber-stepper visual interaktif dan tabel inventaris perangkat pada sistem SIMANTIK. | Rancangan UI formulir tiket dengan pilihan kategori kendala (jaringan, hardware, software), area upload foto kendala, visual stepper pelacakan status, dan tabel aset ber-filter status. | Merancang indikator status yang jelas agar pegawai dapat memantau penanganan kendala tanpa harus bertanya manual. |
| 5 | Rabu, 07 Oktober 2026 | 07.30 - 16.00 | WFO | Pembimbing Lapangan | Melakukan evaluasi konsistensi token warna dan tipografi resmi BPS pada SIMANTIK serta persiapan perombakan antarmuka Sistem Lembur (TEMPE DELE). | Terwujudnya panduan komponen antarmuka SIMANTIK yang konsisten dengan identitas BPS dan tersusunnya daftar evaluasi antarmuka Sistem Lembur. | Penyelarasan format CSS antarmuka dengan palet warna resmi BPS. |
| 6 | Kamis, 08 Oktober 2026 | 07.30 - 16.00 | WFO | Pembimbing Lapangan | Merancang tata letak lembar cetak langsung (direct print) Daftar Hadir lembur (PNS dan PPPK) dan format ekspor SPKL Excel ber-auto row height pada Sistem Lembur. | Tampilan cetak Daftar Hadir yang presisi pada kertas standar kedinasan, kolom tanda tangan yang rapi, dan dokumen SPKL Excel yang tidak terpotong pada uraian tugas yang panjang. | Penyesuaian margin cetak peramban agar pas 1 halaman penuh tanpa halaman kosong tambahan. |
| 7 | Jumat, 09 Oktober 2026 | 07.30 - 16.30 | WFO | Pembimbing Lapangan | Mengevaluasi dan merestrukturisasi bilah penyaring (filter toolbar) riwayat lembur pegawai: memisahkan dropdown bulan dan tahun serta membersihkan entri tim kerja duplikat. | Tampilan bilah filter yang ringkas dan intuitif, navigasi pencarian riwayat antar-tahun yang mudah, serta eliminasi opsi tim nonaktif yang membingungkan pegawai. | Penataan flexbox toolbar agar rapi dan tidak meluap (overflow) pada layar laptop beresolusi 1366x768. |
| 8 | Senin, 12 Oktober 2026 | 07.30 - 16.00 | WFO | Pembimbing Lapangan | Mengembangkan Collapsible Sidebar desktop ala Canvas LMS, meredesain tabel persetujuan Ketua Tim (/ketua-tim/pengajuan) pas 100% desktop dan berkolom sticky mobile, serta membuat modal pop-up rincian uraian tugas. | Area kerja tabel yang melebar lega 100% saat sidebar ditutup, tab buka-menu mengambang yang elegan, tabel Ketua Tim pas tanpa horizontal scrollbar di desktop, kolom No dan Nama Pegawai sticky solid di mobile, dan modal pop-up uraian tugas yang nyaman dibaca. | Penyelarasan transisi penutupan sidebar 300ms, persistensi localStorage tanpa kedipan layout, dan penataan z-index kolom sticky. |
| 9 | Selasa, 13 Oktober 2026 | 07.30 - 16.00 | WFO | Pembimbing Lapangan | Merestrukturisasi tata letak modal dialog formulir pengajuan lembur (#modalAjukan) menjadi Bounded Modal Container 2-kolom zero-scroll di desktop dengan kanvas tanda tangan digital proporsional. | Terwujudnya modal pengajuan lembur yang fit 100% tanpa scrollbar vertikal di desktop/laptop, komposisi input lapang dan seimbang, header tetap berbadge oranye BPS, footer aksi yang selalu tampak, dan kanvas tanda tangan bebas distorsi. | Penataan responsif grid 2-kolom desktop ke 1-kolom ponsel pintar tanpa merusak ukuran kanvas penandatanganan. |
| 10 | Rabu, 14 Oktober 2026 | 07.30 - 16.00 | WFO | Pembimbing Lapangan | Menerapkan prinsip desain anti-slop pada modul Manajemen User (/admin/manajemen-user): panel eksekutif pejabat penandatangan aktif 2-kolom, modal landscape pemilihan pegawai 2-kolom zero-scroll, dan toolbar filter floating status lembur. | Tata letak Manajemen User yang ringkas, berwibawa, hemat ruang layar hingga 40%, tidak tampak seperti template generik, modal pemilihan pegawai yang cepat tanpa scrollbar, dan toolbar filter status yang rapi. | Penataan stacking context z-index floating dropdown dan penyeimbangan padding panel pejabat penandatangan. |
| 11 | Kamis, 15 Oktober 2026 | 07.30 - 16.00 | WFO | Pembimbing Lapangan | Menyelesaikan penataan 2-layer backdrop modal murni (@stack('modals')) pada Sistem Lembur serta memulai audit pengalaman pengguna (UX) 5 sistem lobi BPS Jateng untuk persiapan SEMAR PST. | Latar gelap modal sistem lembur menutup 100% viewport peramban tanpa celah putih footer; teridentifikasinya titik friksi antrean lobi fisik dan survei kepuasan ganda pada 5 aplikasi warisan BPS Jateng. | Mengeliminasi keterbatasan compositing layer Chromium pada fixed backdrop modal di atas taskbar sistem operasi. |
| 12 | Jumat, 16 Oktober 2026 | 07.30 - 16.30 | WFO | Pembimbing Lapangan | Merancang Design System SEMAR PST (Tailwind tokens BPS), tata letak Kiosk Lobi Sentuh (/kiosk), TV Antrian Ruang Tunggu (/antrian), Micro-Survey 15 Detik (/s/{token}), serta menyusun dokumen Roadmap dan 25 Tiket Kanban Backlog Frontend. | Terbentuknya panduan token visual resmi BPS, desain layar Kiosk dengan tombol layanan besar & keypad numerik buku tamu, desain TV 16:9 ber-audio TTS, desain survei mobile sub-1.2s, serta tersusunnya dokumen mandiri ROADMAP_DAN_BACKLOG_FRONTEND.md. | Menjaga halaman survei mobile sangat ringan di bawah 1,2 detik dengan menghindari dependensi pustaka JavaScript eksternal yang berat. |

---

## LAMPIRAN 2: DOKUMENTASI KEGIATAN ANTARMUKA PENGGUNA

### Bagian A: Sistem Manajemen Layanan TI (SIMANTIK)
- Gambar 1.1: Kerangka kawat (wireframe) antarmuka dasbor manajemen layanan dan inventaris aset TI BPS Provinsi Jawa Tengah.
- Gambar 1.2: Rancangan antarmuka formulir pengajuan tiket bantuan kendala TI ber-stepper visual interaktif.
- Gambar 1.3: Tata letak tabel inventaris perangkat keras TI ber-filter status kondisi dan penanggung jawab perangkat.
- Gambar 1.4: Panduan token palet warna dan tipografi resmi BPS yang diterapkan pada rancangan antarmuka SIMANTIK.

### Bagian B: Sistem Lembur (TEMPE DELE)
- Gambar 2.1: Tampilan Collapsible Sidebar desktop ala Canvas LMS saat terbuka dan tertutup dengan tab mengambang elegan.
- Gambar 2.2: Redesain tabel persetujuan Ketua Tim (/ketua-tim/pengajuan) pas 100% desktop tanpa scrollbar horizontal.
- Gambar 2.3: Tampilan kolom No dan Nama Pegawai sticky solid dengan garis pembatas tegas pada layar ponsel pintar.
- Gambar 2.4: Komponen pop-up modal interaktif untuk melihat rincian utuh uraian kegiatan lembur (#modalDetailUraian).
- Gambar 2.5: Tampilan tombol aksi icon-only (h-7 w-7) modern tanpa label teks pada tabel persetujuan Ketua Tim.
- Gambar 2.6: Header kolom Tanggal bertingkat vertikal (flex-col) dengan tombol dropdown sortir di bawahnya.
- Gambar 2.7: Restrukturisasi dialog modal pengajuan lembur (#modalAjukan) menjadi 2 kolom desktop zero-scroll dengan kanvas tanda tangan proporsional.
- Gambar 2.8: Tampilan tabel yang dapat digeser secara horizontal (horizontal swipeable table) dengan bilah indikator sentuh (.table-scroll-hint) pada smartphone.
- Gambar 2.9: Tampilan navigasi tombol geser cepat [ < ] dan [ > ] serta kinetic momentum touch gliding pada tampilan seluler.
- Gambar 2.10: Penerapan desain anti-slop pada modul Manajemen User (/admin/manajemen-user) dengan panel eksekutif pejabat penandatangan aktif.
- Gambar 2.11: Tampilan modal landscape 2-kolom zero-scroll untuk pemilihan pejabat dan admin pada modul Manajemen User.
- Gambar 2.12: Tampilan bilah toolbar penyaring status lembur mengambang (floating dropdown filter) pada monitoring admin.
- Gambar 2.13: Tampilan arsitektur 2-layer backdrop modal murni (@stack('modals')) yang menutupi 100% layar penuh tanpa celah garis putih footer.
- Gambar 2.14: Lembar pratinjau cetak langsung (direct print) Daftar Hadir lembur terpisah PNS dan PPPK pada kertas standar kedinasan.

### Bagian C: SEMAR PST (Pelayanan Statistik Terpadu)
- Gambar 3.1: Panduan Design System dan token palet warna resmi BPS (Deep Navy, Vibrant Orange, Emerald Green) untuk SEMAR PST.
- Gambar 3.2: Rancangan tata letak antarmuka Kiosk Lobi Sentuh (/kiosk) dengan 4 tombol layanan besar ramah jari.
- Gambar 3.3: Rancangan antarmuka modal Buku Tamu Digital auto-complete dengan on-screen numeric keypad di layar Kiosk.
- Gambar 3.4: Desain template cetak struk termal kertas 58mm/80mm (@media print) yang memuat QR Code dinamis (/s/{qr_token}).
- Gambar 3.5: Rancangan antarmuka TV Antrian Ruang Tunggu Widescreen 16:9 (/antrian) dengan kartu aktif beranimasi dan running text.
- Gambar 3.6: Rancangan antarmuka Micro-Survey HP 15-Detik (/s/{qr_token}) berstandar sub-1.2s mobile loading.
- Gambar 3.7: Rancangan antarmuka Konsol Operator Meja Loket 1-layar kerja (/admin/operator-console).
- Gambar 3.8: Dokumen resmi Roadmap Mingguan dan 25 Tiket Kanban Backlog Frontend (docs/ROADMAP_DAN_BACKLOG_FRONTEND.md).
