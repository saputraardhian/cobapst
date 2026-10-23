# LAPORAN BULANAN KEGIATAN MAGANG MBKM
BULAN: Oktober TAHUN: 2026  

Nama Program: Magang Mandiri MBKM  
Judul Topik Kegiatan: Konsolidasi dan Pengembangan Ekosistem Perangkat Lunak Terintegrasi (SWARA JAGAD, Sistem Lembur TEMPE DELE, dan SEMAR PST)  
Lokasi: Badan Pusat Statistik (BPS) Provinsi Jawa Tengah  

Disusun Oleh:  
- Nama: Muhammad Rifqi Al Baqi Ananta  
- NIM: [Isi NIM Mahasiswa]  
- Program Studi: S1 Rekayasa Perangkat Lunak  
- Direktorat Kampus Purwokerto  
- Telkom University  
- Tahun: 2026  

---

## LEMBAR PENGESAHAN

Judul Topik Kegiatan: Konsolidasi dan Pengembangan Ekosistem Perangkat Lunak Terintegrasi (SWARA JAGAD, Sistem Lembur TEMPE DELE, dan SEMAR PST)  
Lokasi: Badan Pusat Statistik (BPS) Provinsi Jawa Tengah  
Disusun Oleh: Muhammad Rifqi Al Baqi Ananta / [Isi NIM Mahasiswa]  

Disetujui dan disahkan sebagai Laporan Bulanan  
Bulan Oktober Tahun 2026  
Program Magang MBKM Mandiri  

| Semarang, 31 Oktober 2026 | |
|:---|:---|
| Pembimbing Lapangan | Mahasiswa |
| Badan Pusat Statistik Provinsi Jawa Tengah | Telkom University Kampus Purwokerto |
| | |
| | |
| Yuli Purwitasari, S.S.T. | Muhammad Rifqi Al Baqi Ananta |
| NIP. [NIP Pembimbing] | NIM. [NIM Mahasiswa] |

| Mengetahui, |
|:---|
| Dosen Pembimbing Magang |
| Telkom University Kampus Purwokerto |
| |
| |
| Dr. Faisal Dharma Adhinata, S.Kom., M.Cs. |
| NIP. [NIP Dosen Pembimbing] |

---

## RINGKASAN LAPORAN
Laporan bulanan ini merangkum pelaksanaan kegiatan magang MBKM periode Oktober 2026 di Badan Pusat Statistik (BPS) Provinsi Jawa Tengah yang mencakup tiga fase strategis rekayasa perangkat lunak kedinasan:

1. **Minggu Pertama (01 - 07 Oktober 2026):** Penyelesaian menyeluruh (*finalization & handover milestone*) platform SWARA JAGAD (Sistem Wawasan Terintegrasi Ekonomi Hijau Jawa Tengah Berbasis Data) sebelum diserahterimakan secara resmi kepada Badan Perencanaan Pembangunan Daerah (Bappeda) Provinsi Jawa Tengah. Kegiatan mencakup penguatan arsitektur sinkronisasi data via modul `SatuDataService` dengan mekanisme *resilient caching*, telaah 11 dataset resmi Satu Data Jawa Tengah (SDJT) lintas Organisasi Perangkat Daerah (OPD), integrasi modul Dimensi 1 (Ekonomi Sirkular) mencakup IKPS, Peta Sampah 35 Kab/Kota, dan direktori UMKM/Koperasi Hijau, pembangunan arsitektur REST API v1 berbasis OpenAPI Swagger (`/api/documentation`), serta suite pengujian otomatis PHPUnit dengan tingkat kelulusan 100%.

2. **Minggu Kedua (08 - 14 Oktober 2026):** Penyempurnaan alur transaksi dan standarisasi antarmuka Sistem Lembur (TEMPE DELE) BPS Provinsi Jawa Tengah. Fokus kegiatan meliputi implementasi fitur koreksi penolakan bertingkat Ketua Tim dan persetujuan langsung Kabag Umum, ekspor SPKL Excel ber-auto row height, cetak langsung (*direct print*) Daftar Hadir (terpisah PNS dan PPPK) berbasis jam riil basis data presensi BOS (`t_presensi`), audit skema basis data MySQL, perancangan antarmuka *collapsible sidebar* ala Canvas LMS, standarisasi tabel *horizontal swipeable* ramah seluler ber-indikator sentuh dan *kinetic momentum touch gliding*, dialog modal formulir pengajuan 2-kolom *zero-scroll*, perombakan tata letak Manajemen User berprinsip *anti-slop*, perbaikan *2-layer backdrop modal* murni (`@stack('modals')`), serta penyusunan instrumen pengujian fungsional *Black Box Testing Suite* (70 kasus uji lulus 100%).

3. **Minggu Ketiga (15 - 21 Oktober 2026):** Inisiasi arsitektur dan perancangan fondasi sistem SEMAR PST (Pelayanan Statistik Terpadu) BPS Provinsi Jawa Tengah. Inisiatif strategis ini bertujuan mengonsolidasikan 5 aplikasi layanan dan manajemen kerja terpisah (*legacy systems*: PST, Statisfy, Pristi, Pengaduan, dan Elena) ke dalam satu platform *monolith* modern terintegrasi berbasis Laravel 12, FilamentPHP v3, Livewire 3, Tailwind CSS, dan MySQL 8. Kegiatan diawali dengan audit skema 5 basis data warisan, penyusunan dokumen spesifikasi kebutuhan sistem (`PRD fix.md`) dan *Roadmap Kanban Backlog* (35 tiket terukur). Dilanjutkan dengan instalasi ekosistem backend (`BE-01`), perumusan 12 Golden Engineering Rules (`AGENTS.md`), perancangan skema migrasi MySQL, Model Eloquent, Factory, dan pengujian otomatis untuk Master Pegawai BPS dan Meja Loket (`BE-02`), Transaksi Antrian PST dan Survei Statisfy AI (`BE-03`), Transaksi Jadwal/Presensi Pristi, Pengaduan Publik ber-tracking code, dan Evaluasi Kinerja Bulanan Elena (`BE-04`), serta konfigurasi SSO dan Multi-Role RBAC 6 Peran Resmi BPS beserta Filament Shield Policies (`BE-05`) dengan hasil pengujian otomatis 100% lulus (20 pengujian, 96 assertions).

---

## KATA PENGANTAR
Puji syukur ke hadirat Tuhan Yang Maha Esa atas rahmat dan karunia-Nya, sehingga Laporan Bulanan Magang MBKM Periode Oktober 2026 di Badan Pusat Statistik (BPS) Provinsi Jawa Tengah ini dapat diselesaikan dengan baik. Laporan ini disusun sebagai bentuk pertanggungjawaban pelaksanaan kegiatan magang sekaligus memenuhi persyaratan kurikulum akademik Program Studi S1 Rekayasa Perangkat Lunak, Telkom University Kampus Purwokerto.

Penulis menyampaikan terima kasih yang tulus kepada pihak-pihak yang telah memberikan bimbingan dan dukungan:
1. Pimpinan dan segenap jajaran BPS Provinsi Jawa Tengah atas kesempatan berharga yang diberikan dalam pelaksanaan magang MBKM.
2. Ibu Yuli Purwitasari, S.S.T., dan Bapak Muhammad Hanief, S.Tr.Stat., selaku Pembimbing Lapangan dan Mentor di BPS Provinsi Jawa Tengah atas arahan, bimbingan teknis, dan diskusi produktif selama pengembangan sistem.
3. Bapak Dr. Faisal Dharma Adhinata, S.Kom., M.Cs., selaku Dosen Pembimbing Magang atas bimbingan dan arahan akademik yang senantiasa diberikan.
4. Rekan pengembang, Ardhian Dwi Saputra, serta staf BPS Provinsi Jawa Tengah atas kerja sama dan kolaborasi solid selama kegiatan.
5. Keluarga dan rekan-rekan mahasiswa atas doa, perhatian, dan dukungan moril yang tiada henti.

Semarang, 31 Oktober 2026  
Penulis,  

Muhammad Rifqi Al Baqi Ananta  

---

## DAFTAR ISI
- Ringkasan Laporan
- Kata Pengantar
- Daftar Isi
- BAB I Pendahuluan
  - 1.1 Latar Belakang
  - 1.2 Ruang Lingkup
  - 1.3 Tujuan
- BAB II Kegiatan Magang
  - 2.1 Rincian Kegiatan
    - Rekapitulasi Mingguan (Minggu I: 01 - 07 Oktober 2026)
    - Rekapitulasi Mingguan (Minggu II: 08 - 14 Oktober 2026)
    - Rekapitulasi Mingguan (Minggu III: 15 - 21 Oktober 2026)
  - 2.2 Analisa Hasil Kegiatan
  - 2.3 Hambatan dan Upaya untuk Mengatasinya
  - 2.4 Rekomendasi
- BAB III Penutup
  - 3.1 Kesimpulan
  - 3.2 Saran
- Lampiran 1: Tabel Logbook Harian Resmi
  - Minggu I (01 - 07 Oktober 2026)
  - Minggu II (08 - 14 Oktober 2026)
  - Minggu III (15 - 21 Oktober 2026)
- Lampiran 2: Dokumentasi Kegiatan
  - Minggu I (SWARA JAGAD)
  - Minggu II (Sistem Lembur TEMPE DELE)
  - Minggu III (SEMAR PST - Konsolidasi 5 Sistem BPS Jateng)

---

## BAB I PENDAHULUAN

### 1.1 Latar Belakang
Pelaksanaan program Magang Merdeka Belajar Kampus Merdeka (MBKM) Mandiri di Badan Pusat Statistik (BPS) Provinsi Jawa Tengah merupakan wujud nyata integrasi kurikulum pendidikan tinggi program studi S1 Rekayasa Perangkat Lunak dengan kebutuhan transformasi digital sektor publik. Memasuki bulan Oktober 2026, dinamika pengembangan perangkat lunak mencakup tiga fase krusial:

1. Penyelesaian akhir dan serah terima (*handover*) platform analitik makro SWARA JAGAD (Sistem Wawasan Terintegrasi Ekonomi Hijau Jawa Tengah Berbasis Data) kepada Badan Perencanaan Pembangunan Daerah (Bappeda) Provinsi Jawa Tengah untuk memantau indikator RPJMD dan Ekonomi Hijau 2025-2045.
2. Penguatan teknis dan standarisasi antarmuka operasional pada Sistem Lembur (TEMPE DELE) guna menjamin kepatuhan administrasi keuangan, akurasi integrasi presensi riil, dan kenyamanan pengguna lintas perangkat.
3. Inisiasi dan rekayasa platform generasi baru **SEMAR PST** (Sistem Elektronik Manajemen dan Pelayanan Statistik Terpadu) yang mengonsolidasikan 5 aplikasi operasional terpisah (*PST*, *Statisfy*, *Pristi*, *Pengaduan*, dan *Elena*) ke dalam satu kesatuan arsitektur *monolith* terpadu guna mengatasi masalah fragmentasi basis data, redundansi input pegawai, dan kendala sinkronisasi data antar-sistem.

### 1.2 Ruang Lingkup
Ruang lingkup kegiatan pada periode ini meliputi:
1. Finalisasi integrasi API Satu Data Jawa Tengah (SDJT), dokumentasi REST API OpenAPI Swagger, serta *handover* repositori SWARA JAGAD ke Bappeda Jateng.
2. Penyempurnaan alur bisnis persetujuan berjenjang, integrasi data presensi riil BOS, standarisasi dokumen SPKL/Daftar Hadir, perombakan antarmuka responsif *mobile-first*, dan jaminan mutu *Black Box Testing* pada Sistem Lembur TEMPE DELE.
3. Audit skema 5 basis data warisan BPS Jateng (`jatengwe_apps.sql`, `statisfy.sql`, `pristi_db_new.sql`, `pengaduan_jateng.sql`, dan `elena`).
4. Penyusunan dokumen *Product Requirement Document* (`PRD fix.md`), pemetaan 35 tiket *backlog* (`roadmap dan backlog.md`), dan perumusan 10 Golden Engineering Rules (`AGENTS.md`) untuk proyek SEMAR PST.
5. Inisialisasi arsitektur Laravel 12 monolith dengan FilamentPHP v3, Livewire 3, Tailwind CSS, Spatie Permission, dan Filament Shield (`BE-01`).
6. Perancangan skema migrasi MySQL, Model Eloquent, relasi *foreign key*, dan pengujian otomatis untuk 12 tabel basis data inti SEMAR PST (`BE-02`, `BE-03`, dan `BE-04`).

### 1.3 Tujuan
Tujuan pelaksanaan kegiatan pada periode ini adalah:
1. Menuntaskan dan menyerahkan sistem SWARA JAGAD dalam kondisi teruji 100% dan terdokumentasi lengkap.
2. Meningkatkan efisiensi proses bisnis, akurasi presensi, dan estetika antarmuka Sistem Lembur TEMPE DELE sesuai standar kedinasan BPS.
3. Membangun fondasi arsitektur perangkat lunak yang kokoh, modular, dan terukur bagi sistem SEMAR PST guna menyatukan seluruh siklus layanan statistik dan evaluasi kinerja pegawai BPS Provinsi Jawa Tengah dalam satu platform terintegrasi.
4. Memastikan seluruh kode sumber mematuhi kaidah rekayasa perangkat lunak modern, performa tinggi, dan standar mutu bebas regresi.

---

## BAB II KEGIATAN MAGANG

### 2.1 Rincian Kegiatan

#### Rekapitulasi Mingguan: Minggu I (01 - 07 Oktober 2026)
- **Fokus Utama:** Finalisasi Integrasi Satu Data Jateng, REST API OpenAPI Swagger, dan Handover Platform SWARA JAGAD.
- **Uraian Aktivitas:**
  1. Mengembangkan arsitektur sinkronisasi dataset terpusat Satu Data Jawa Tengah (SDJT) serta menerapkan sistem penyimpanan sementara tangguh (*resilient caching*) pada peladen aplikasi.
  2. Melakukan telaah teknis dan pemetaan mendalam terhadap 11 dataset resmi Satu Data Jawa Tengah lintas OPD untuk seluruh dimensi ekonomi hijau pada sistem SWARA JAGAD.
  3. Mengintegrasikan dataset resmi Satu Data Jateng ke seluruh modul Dimensi 1 (Ekonomi Sirkular): Indeks Kinerja Pengelolaan Sampah (IKPS), Timbulan Sampah, Peta Sampah 35 Kabupaten/Kota, serta Direktori UMKM dan Koperasi Hijau.
  4. Mengintegrasikan dataset Green Industry (Dataset ID 640 Disperindag), membangun antarmuka pemrograman aplikasi (REST API v1) berbasis OpenAPI (Swagger), serta menyusun suite pengujian otomatis (`ApiV1EndpointsTest`).
  5. Melakukan konsolidasi akhir, pembersihan artefak dokumentasi non-esensial, finalisasi penyerahan (*handover*) platform SWARA JAGAD ke pihak Bappeda Provinsi Jawa Tengah, serta sinkronisasi repositori kode sumber.

#### Rekapitulasi Mingguan: Minggu II (08 - 14 Oktober 2026)
- **Fokus Utama:** Penyempurnaan Alur Transaksi, Fleksibilitas Persetujuan Bertingkat, dan Format Dokumen Resmi Sistem Lembur (TEMPE DELE) BPS Provinsi Jawa Tengah.
- **Uraian Aktivitas:**
  1. Mengimplementasikan fitur koreksi penolakan pengajuan lembur oleh Ketua Tim pada status `menunggu_kabag` serta penyesuaian kolom jam disetujui bersyarat ketersediaan data presensi riil (`has_presensi`).
  2. Menyempurnakan alur kerja Kabag Umum agar dapat langsung memproses persetujuan pengajuan lembur berstatus `pending` untuk pegawai di bawah Tim Bagian Umum.
  3. Mengintegrasikan mekanisme tinggi baris dinamis (*auto row height*) pada dokumen SPKL Excel (XLSX) untuk mengakomodasi teks uraian kegiatan lembur yang panjang tanpa terpotong.
  4. Mengembangkan fitur cetak langsung (*direct browser print*) pada menu Daftar Hadir (terpisah PNS dan PPPK) serta menyelaraskan jam kepulangan murni bersumber dari basis data presensi riil BOS (`t_presensi`).
  5. Melakukan restrukturisasi filter riwayat lembur pegawai: memisahkan dropdown bulan dan dropdown tahun mandiri (*Semua Tahun* default) serta mengeliminasi duplikasi nama tim nonaktif via penyaringan status aktif (`where status = aktif`) dan `distinct()`.
  6. Melakukan redesain komprehensif tabel persetujuan pengajuan lembur Ketua Tim (`/ketua-tim/pengajuan`): mengoptimalkan tata letak desktop pas 100% tanpa gulir horizontal, menerapkan kolom *sticky* (No dan Nama Pegawai) pada layar ponsel pintar, menyeragamkan tinggi baris (`h-16`) dengan `line-clamp-2`, serta menyediakan modal pop-up interaktif detail uraian kegiatan.
  7. Merancang tata kelola antarmuka modern *anti-slop* pada modul Manajemen User, dialog modal *bounded container* 2-kolom *zero-scroll*, standarisasi tabel *horizontal swipeable* seluler dengan indikator sentuh dan *kinetic momentum touch gliding*, perbaikan hierarki *2-layer backdrop modal* murni (`@stack('modals')`), serta penyusunan instrumen pengujian fungsional *Black Box Testing Suite* (70 kasus uji 100% PASS).

#### Rekapitulasi Mingguan: Minggu III (15 - 21 Oktober 2026)
- **Fokus Utama:** Inisiasi, Analisis 5 Sistem Legacy, Perancangan Arsitektur 12 Tabel, dan Fondasi Monolith SEMAR PST (BPS Provinsi Jawa Tengah).
- **Uraian Aktivitas:**
  1. Melakukan telaah teknis mendalam dan audit skema dari 5 basis data aplikasi operasional terpisah BPS Provinsi Jawa Tengah (`pst_db.sql`, `statisfy.sql`, `pristi_db_new.sql`, `pengaduan_jateng.sql`, dan `jatengwe_apps.sql` untuk Elena) guna memetakan redundansi struktur, format identitas pegawai (NIP 9 digit vs NIP 18 digit), serta siklus hidup transaksi layanan.
  2. Menyusun dokumen spesifikasi kebutuhan sistem terpadu (`PRD fix.md`) dan merumuskan *Kanban Backlog* 35 tiket terukur dalam 5 sprint pengembangan yang dituangkan pada `roadmap dan backlog.md`.
  3. Merumuskan dan menetapkan 10 Golden Engineering Rules (`AGENTS.md`) sebagai panduan tata kelola rekayasa perangkat lunak tim pengembang (mencakup verifikasi pasca-perubahan otomatis, *living documentation* non-stacking, commit atomik, kontrol lingkup ketat, dokumentasi Swagger komprehensif, standarisasi tanda hubung ASCII hyphen `-`, kebijakan ETL non-destruktif, tata nama dwibahasa, kompatibilitas PHP 8.2, dan performa antarmuka seluler sub-1.2 detik).
  4. Menginisialisasi repositori proyek *monolith* modern Laravel 12 pada direktori `semar-pst`, mengintegrasikan FilamentPHP v3 (`AdminPanelProvider`), Livewire 3, Tailwind CSS, Spatie Laravel Permission, Filament Shield, Simple QR Code, Maatwebsite Excel, dan DomPDF (`BE-01`).
  5. Merancang skema migrasi MySQL, Model Eloquent, dan *relationships* untuk Master Users (peleburan identitas pegawai BPS dari 5 tabel terpisah menjadi satu skema komprehensif dengan atribut `nip_baru`, `nip_lama`, `region_code`, `unit_code`, `elena_team_code`, `elena_calc_code`) dan Meja Loket `pst_counters` ber-slug QR statis (`BE-02`).
  6. Merancang skema migrasi MySQL dan Model Eloquent untuk klaster transaksi PST dan Statisfy AI: `pst_visitors` (master buku tamu berbasis indeks nomor telepon untuk auto-lookup), `pst_visitor_logs` (riwayat kunjungan lobi), `pst_service_tickets` (tiket antrean loket terpadu dengan token QR dinamis dan siklus status layanan), `statisfy_surveys` (survei kepuasan 15 detik terikat 1 tiket), serta `statisfy_ai_analyses` (penyimpanan agregat sentimen AI eksekutif) (`BE-03`).
  7. Merancang skema migrasi MySQL dan Model Eloquent untuk klaster transaksi Pristi, Pengaduan, dan Elena: `pristi_schedules` (kalender jadwal tugas loket & pertukaran shift), `pristi_attendances` (presensi terpadu konsol loket dan kalkulasi keterlambatan), `pristi_monev_reports` (laporan monev 9 dimensi kab/kota), `public_complaints` (registrasi aduan masyarakat dengan tracking code otomatis `ADU-YYYY-XXXX` dan flag penalti kinerja), serta `elena_evaluations` (matriks penilaian bulanan berformula tertimbang dan compound unique constraint periode) (`BE-04`).
  8. Mengembangkan rangkaian pengujian otomatis Feature & Unit Test Suite (`MasterUsersAndCountersTest`, `PstAndStatisfyTransactionsTest`, `PristiAduanElenaTransactionsTest`) dengan 13 test cases dan 41 assertions yang terverifikasi lulus 100% (PASS).

### 2.2 Analisa Hasil Kegiatan
Pekerjaan yang diselesaikan sepanjang periode Oktober 2026 berhasil mencapai target fungsional yang ditetapkan:
1. **Keandalan Akses Data (SWARA JAGAD):** Pola *resilient caching* pada `SatuDataService` menjamin sistem tetap beroperasi normal saat peladen API eksternal mengalami gangguan jaringan atau kuota kueri habis.
2. **Keterhubungan Data Riil (SWARA JAGAD):** Modul Dimensi 1 terhubung secara *live* dengan dataset resmi DLHK, Disperindag, dan Dinkop UKM Jawa Tengah.
3. **Standarisasi Interoperabilitas (SWARA JAGAD):** Endpoint REST API v1 (`/api/v1`) terdokumentasi lengkap pada Swagger UI interaktif sesuai standar OpenAPI 3.0.
4. **Validasi Kualitas Mutu (SWARA JAGAD):** Pengujian otomatis berbasis PHPUnit (`ApiV1EndpointsTest` dan `GreenIndustryGroupingTest`) berhasil lolos dengan tingkat kelulusan 100%.
5. **Fleksibilitas Alur Persetujuan Bertingkat (Sistem Lembur):** Ketua Tim dapat mengoreksi keputusan menjadi ditolak sebelum verifikasi akhir pimpinan, dan Kabag Umum dapat menyetujui langsung pengajuan internal timnya tanpa hambatan validasi.
6. **Akurasi Dokumen & Presensi (Sistem Lembur):** Rekapitulasi SPKL Excel kini memiliki keterbacaan tinggi dengan tinggi baris adaptif, dan lembar daftar hadir menampilkan jam kepulangan riil presensi pegawai secara presisi.
7. **Fondasi Terpadu SEMAR PST (SEMAR PST):** Terwujudnya arsitektur *monolith* modern yang menyatukan 5 domain aplikasi operasional BPS Provinsi Jawa Tengah ke dalam satu kesatuan sistem terpusat, mengeliminasi duplikasi data dan kerumitan sinkronisasi manual.
8. **Jaminan Integritas Skema Relasional 12 Tabel (SEMAR PST):** Seluruh 12 tabel basis data inti telah termigrasi dengan sukses pada MySQL `semar_pst` dengan integritas *foreign key* yang kokoh, dilengkapi model Eloquent berelasi dua arah, serta lolos pengujian otomatis dengan 41 assertions valid (100% PASS).

### 2.3 Hambatan dan Upaya untuk Mengatasinya
1. **Hambatan 1 - Variasi Struktur Data Mentah SDJT:** Struktur atribut data mentah dari portal Satu Data Jateng memiliki penamaan kolom yang berbeda antar-OPD penyedia.  
   *Upaya Mengatasinya:* Mengembangkan lapisan transformasi data (*normalizer*) di controller untuk memetakan nama atribut mentah ke dalam model data standar antarmuka.
2. **Hambatan 2 - Fluktuasi Latensi Portal Publik:** Respon peladen API publik terkadang mengalami jeda waktu (*timeout*) pada jam kerja sibuk.  
   *Upaya Mengatasinya:* Mengimplementasikan penyimpanan sementara data di server (*cache storage*) dengan mekanisme nilai cadangan lokal (*graceful fallback*) menggunakan berkas JSON terstruktur.
3. **Hambatan 3 - Kerapihan Teks Antarmuka:** Terdapat istilah teknis internal dan kode dataset yang sebelumnya muncul pada tampilan publik.  
   *Upaya Mengatasinya:* Mengeliminasi seluruh teks teknis internal dan menggantinya dengan label formal statistik yang ramah bagi pimpinan dan masyarakat umum.
4. **Hambatan 4 - Heterogenitas Skema & Format Data 5 Sistem Warisan:** Basis data 5 sistem warisan memiliki format identitas pegawai yang tidak seragam (sebagian menggunakan NIP lama 9 digit, sebagian teks nama bebas tanpa gelar, sebagian NIP baru 18 digit).  
   *Upaya Mengatasinya:* Merancang skema tabel `users` konsolidatif yang mengakomodasi atribut `nip_baru` (18 digit, unik), `nip_lama` (9 digit), `name`, `name_without_title`, serta kode tim dan kode hitung Elena, serta menyiapkan algoritma normalisasi bertingkat pada skrip ETL Sprint 1.
5. **Hambatan 5 - Sekuensial Eksekusi Migrasi Foreign Key MySQL:** Pembuatan berkas migrasi secara serentak dalam detik yang sama menyebabkan kesamaan cap waktu (*timestamp*), sehingga tabel anak dieksekusi sebelum tabel induk dan memicu galat MySQL Foreign Key Constraint Error 150.  
   *Upaya Mengatasinya:* Melakukan penataan ulang cap waktu berkas migrasi secara sekuensial dan terurut (`031811` s/d `031815`) serta membersihkan sisa tabel sementara sebelum mengeksekusi migrasi ulang secara bersih.

### 2.4 Rekomendasi
1. Melakukan transfer pengetahuan berkala kepada tim teknis Bappeda terkait pemeliharaan *resilient cache* dan integrasi API SWARA JAGAD.
2. Menjaga kepatuhan pelaporan presensi BOS tepat waktu oleh unit kepegawaian agar proses verifikasi lembur bulanan berjalan lancar tanpa data anomali.
3. Memprioritaskan penyelesaian sub-modul ETL (`BE-06` s/d `BE-08`) pada SEMAR PST untuk memindahkan seluruh data transaksi historis dari 5 database lama sebelum memasuki fase pengembangan antarmuka antrian real-time loket (Sprint 2).
4. Menerapkan pengujian beban (*load test*) koneksi WebSocket Reverb pada konsol loket dan display TV lobi sebelum simulasi fisik di kantor BPS Jateng.

---

## BAB III PENUTUP

### 3.1 Kesimpulan
Pelaksanaan kegiatan magang MBKM di Badan Pusat Statistik (BPS) Provinsi Jawa Tengah selama bulan Oktober 2026 telah berjalan dengan sangat efektif dan mencapai seluruh target yang ditetapkan. Tiga tonggak capaian penting berhasil diselesaikan:
1. Penyerahan platform SWARA JAGAD yang andal, terdokumentasi OpenAPI Swagger, dan teruji 100% kepada pihak Bappeda Provinsi Jawa Tengah.
2. Penyempurnaan alur bisnis persetujuan, validasi presensi riil BOS, antarmuka responsif ramah seluler berstandar *anti-slop*, dan verifikasi mutu *Black Box Testing* pada Sistem Lembur TEMPE DELE.
3. Pembentukan arsitektur *monolith* modern terpadu, perumusan 12 Golden Engineering Rules (termasuk Backend Anti-Slop & Ponytail YAGNI Ladder), perancangan dan verifikasi tuntas 12 tabel basis data inti, serta implementasi sistem otorisasi Multi-Role RBAC 6 Peran Resmi BPS (super_admin, operator_pst, admin_pristi, admin_pengaduan, admin_elena, eksekutif) dan Filament Shield Policies pada SEMAR PST yang teruji 100% bebas regresi (20 tests, 96 assertions).

### 3.2 Saran
1. Mengadakan sesi simulasi langsung bersama petugas operasional lobi dan operator loket PST guna menguji kenyamanan alur antrian dan survei kepuasan 15 detik pada implementasi antarmuka SEMAR PST mendatang.
2. Melakukan sinkronisasi data pegawai secara berkala dengan basis data kepegawaian BPS RI agar master data `users` tetap mutakhir secara berkelanjutan.

---

## LAMPIRAN 1: TABEL LOGBOOK HARIAN RESMI

### Minggu I (01 - 07 Oktober 2026) - Finalisasi SWARA JAGAD
| No | Hari/Tanggal | Waktu | Lokasi | Pemberi Tugas | Kegiatan | Hasil yang Diperoleh | Kendala | Tautan Bukti |
|:---:|:---|:---:|:---:|:---|:---|:---|:---:|:---|
| 1 | Kamis, 01 Oktober 2026 | 07.30 - 16.00 | WFO | Pembimbing Lapangan | Mengembangkan arsitektur sinkronisasi dataset terpusat Satu Data Jawa Tengah (SDJT) serta menerapkan sistem penyimpanan sementara tangguh (resilient caching) pada peladen aplikasi. | Terbentuknya modul sinkronisasi otomatis data indikator makro yang tahan terhadap fluktuasi jaringan, mampu membaca API provinsi secara dinamis, dan memiliki mekanisme cadangan lokal (fallback storage). | Fluktuasi latensi respon peladen API eksternal yang diatasi dengan strategi cache-first with asynchronous background sync. | Berkas Modul `SatuDataService` & Repositori Proyek |
| 2 | Jumat, 02 Oktober 2026 | 07.30 - 16.30 | WFO | Pembimbing Lapangan | Melakukan telaah teknis dan pemetaan mendalam terhadap 11 dataset resmi Satu Data Jawa Tengah lintas OPD untuk seluruh dimensi ekonomi hijau pada sistem SWARA JAGAD. | Tersusunnya dokumen panduan teknis PEMETAAN_DATASET_SDJT_PER_DIMENSI.md yang memuat identifikasi ID dataset, OPD penyedia (DLHK, Disperindag, Dinkop UKM, ESDM, Dislutkan, DPMPTSP), jumlah data, dan integrasi modul antarmuka. | Variasi skema kolom data mentah antar-OPD yang memerlukan normalisasi atribut agar selaras dengan kebutuhan antarmuka sistem. | Dokumen Teknis `PEMETAAN_DATASET_SDJT_PER_DIMENSI.md` |
| 3 | Senin, 05 Oktober 2026 | 07.30 - 16.00 | WFO | Pembimbing Lapangan | Mengintegrasikan dataset resmi Satu Data Jateng ke seluruh modul Dimensi 1 (Ekonomi Sirkular): Indeks Kinerja Pengelolaan Sampah (IKPS), Timbulan Sampah, Peta Sampah 35 Kab/Kota, serta Direktori UMKM dan Koperasi Hijau. | Modul IKPS dan Timbulan Sampah terhubung data real DLHK (ID 473 & 518), peta sampah menampilkan agregat akurat 35 kab/kota, direktori UMKM/Koperasi Hijau terintegrasi data resmi (ID 645 & 650) lengkap dengan filter interaktif dan ekspor spreadsheet multi-sheet. | Penyesuaian tata letak kartu metrik dan pembersihan istilah teknis internal agar tampilan antarmuka ramah pengguna (user-friendly). | Modul Dimensi 1 (Ekonomi Sirkular) & Berkas Ekspor Data |
| 4 | Selasa, 06 Oktober 2026 | 07.30 - 16.00 | WFO | Pembimbing Lapangan | Mengintegrasikan dataset Green Industry (Dataset ID 640 Disperindag), membangun antarmuka pemrograman aplikasi (REST API v1) berbasis OpenAPI (Swagger), serta menyusun suite pengujian otomatis (ApiV1EndpointsTest). | Peta Green Industry terhubung data real dengan geocoding presisi tingkat desa/kecamatan; antarmuka Swagger UI interaktif aktif di /api/documentation dengan dokumentasi seluruh parameter query; seluruh suite pengujian otomatis lolos 100% (PASS). | Penyelarasan format anotasi OpenAPI attributes pada controller Laravel yang disesuaikan dengan spesifikasi OpenAPI 3.0. | Antarmuka Swagger OpenAPI `/api/documentation` & Berkas Pengujian PHPUnit |
| 5 | Rabu, 07 Oktober 2026 | 07.30 - 16.00 | WFO | Pembimbing Lapangan | Melakukan konsolidasi akhir, pembersihan artefak dokumentasi non-esensial, finalisasi penyerahan (handover) platform SWARA JAGAD ke pihak Bappeda Provinsi Jawa Tengah, serta sinkronisasi repositori kode sumber. | Repositori proyek berada dalam kondisi bersih dan stabil, seluruh branch kerja terintegrasi ke branch main, dokumen pemetaan dataset dan API siap pakai diserahterimakan, serta tercapainya seluruh target luaran sistem magang BPS Jateng. | - | Berkas Repositori Final GitHub & Paket Handover Platform Bappeda |

### Minggu II (08 - 14 Oktober 2026) - Lanjutan Sistem Lembur (TEMPE DELE)
| No | Hari/Tanggal | Waktu | Lokasi | Pemberi Tugas | Kegiatan | Hasil yang Diperoleh | Kendala | Tautan Bukti |
|:---:|:---|:---:|:---:|:---|:---|:---|:---:|:---|
| 6 | Kamis, 08 Oktober 2026 | 07.30 - 16.00 | WFO | Pembimbing Lapangan | Melakukan penyesuaian alur koreksi persetujuan Ketua Tim (menjadi ditolak pada tahap menunggu_kabag), approval langsung status pending oleh Kabag Umum, auto row height dokumen SPKL Excel, dan integrasi jam pulang presensi riil BOS pada Daftar Hadir. | Terwujudnya fleksibilitas koreksi penolakan tahap 1, terbukanya aksi approval pending Kabag Umum, ekspor SPKL rapi tanpa teks terpotong, dan sinkronisasi jam pulang murni dari t_presensi. | Penyesuaian format pencocokan NIP lama (9 digit) dan NIP baru (18 digit) pada subquery SQL presensi kepulangan. | Berkas Controller Pengajuan, View Blade, dan Export Excel Sistem Lembur |
| 7 | Jumat, 09 Oktober 2026 | 07.30 - 16.30 | WFO | Pembimbing Lapangan | Melakukan audit arsitektur skema basis data lembur (t_transaksi & t_presensi), meneliti asal mula pengisian jam disetujui Kabag Umum, memperluas subquery SQL presensi multi-format NIP (9 dan 18 digit), serta mengamankan pembaruan DOM AJAX agar kolom jam disetujui hanya tampil saat presensi telah diimpor. | Dokumentasi lengkap alur basis data MySQL, eliminasi bug pembaruan DOM real-time yang memunculkan jam prematur sebelum presensi ada, dan kompatibilitas 100% pembacaan presensi BOS lintas format identitas pegawai. | Data presensi bulan berjalan belum diimpor oleh admin operasional sehingga query correlated subquery perlu ditangani secara defensif. | Skrip Audit Skema Basis Data, Controller, dan View Pengajuan Lembur |
| 8 | Senin, 12 Oktober 2026 | 07.30 - 16.00 | WFO | Pembimbing Lapangan | Melakukan audit teknis komprehensif spreadsheet Rekap Presensi BOS, mengamankan batas hari (daysInMonth), menambahkan opsi target tahun unggah presensi admin, merestrukturisasi filter lembur (pemisahan bulan & tahun mandiri serta eliminasi duplikasi tim nonaktif), mengembalikan lebar alami tabel pengajuan lembur (min-w-[1280px]), mengimplementasikan fitur Collapsible Sidebar (buka-tutup drawer desktop) dengan floating drawer toggle tab ala Canvas LMS dan persistensi state di localStorage, mengkalibrasi proporsi lebar 10 kolom tabel 100% fit di layar laptop/desktop, menyeragamkan tinggi seluruh baris tabel (h-16 / 64px) dengan perataan align-middle & pembatasan line-clamp-2 konsisten, membangun komponen pop-up modal interaktif untuk melihat rincian utuh Uraian Kegiatan lembur, menyempurnakan tata letak responsif seluler (mobile layout reflow): restrukturisasi toolbar filter ke grid 2 kolom simetris pada smartphone, isolasi batas lebar minimum tabel 1020px anti-tabrakan kolom jam dan uraian, sinkronisasi indikator geser horizontal real-time (0%-100%), inisialisasi default cerdas mode Timeline di layar ponsel, melakukan redesain komprehensif tabel persetujuan pengajuan lembur Ketua Tim (/ketua-tim/pengajuan): mengoptimalkan tata letak desktop pas 100% tanpa gulir horizontal menggunakan colgroup proporsional, menyederhanakan header tanggal, menerapkan kolom No (left-0) dan Nama Pegawai (left-10) sticky solid pada peramban ponsel pintar (mobile) bergaris batas vertikal dan bayangan elevasi halus tanpa kebocoran teks, menyeragamkan tinggi baris (h-16 / 64px) dengan line-clamp-2, mengimplementasikan modal pop-up interaktif rincian uraian tugas dan riwayat keputusan, menyinkronkan pembaruan atribut baris data secara real-time pasca penyimpanan AJAX, memodernisasi seluruh tombol pada kolom Aksi (Proses, Koreksi, dan Aksi) menjadi tombol icon-only (h-7 w-7 / h-8 w-8) tanpa label teks pada modul Ketua Tim (/ketua-tim/pengajuan) dan Administrator (/admin/lembur), merebalancing distribusi colgroup tabel Ketua Tim untuk mengeliminasi overflow pemotongan badge 'Menunggu Kabag' dan mewujudkan jarak sela bebas ke tombol pensil aksi, mengkalibrasi layout tabel persetujuan Ketua Tim menjadi fit 100% desktop tanpa gulir horizontal (lg:min-w-full), merapatkan padding sel (px-1 / px-1.5 py-2) guna mengeliminasi jarak renggang kosong antar-kolom, menstandarisasi lencana jam disetujui hijau text-[10.5px] px-1.5 py-0.5 bebas tabrakan dan proporsional, menata bertingkat vertikal (flex-col) header kolom Tanggal (label Tanggal di baris atas dan dropdown sortir [Terbaru v] di bawahnya) guna mengeliminasi tabrakan horizontal dengan header Jam Diajukan, serta merestrukturisasi tata letak dialog modal formulir pengajuan lembur (#modalAjukan) menjadi 2 kolom paralel desktop (md:grid-cols-2, max-w-3xl lg:max-w-4xl) zero-scroll yang diselaraskan secara presisi atas-bawah menggunakan flex justify-between h-full, memperlebar ruang sela input (py-2.5, gap-x-7 gap-y-4), dan mempertahankan fallback responsif 1 kolom pada ponsel cerdas. | Validasi 100% akurasi ekstraksi presensi, terwujudnya filter multi-tahun fleksibel, eliminasi nama tim dobel, area tabel pengajuan lembur yang melebar 100% secara lega saat sidebar ditutup, tab mengambang melengkung elegan dengan tooltip 'Buka Menu', transisi mulus bebas kedipan (zero layout flicker), seluruh baris tabel memiliki ritme tinggi yang seragam (64px), uraian tugas panjang dapat dibaca utuh dengan nyaman melalui pop-up modal, toolbar filter mobile tersusun rapi simetris, eliminasi 100% tabrakan teks jam pada tabel seluler, otomatisasi tampilan kartu Timeline di ponsel, terwujudnya tabel persetujuan Ketua Tim yang pas 100% di desktop tanpa horizontal scrollbar dan sticky column solid di mobile, tata letak kolom aksi yang sangat bersih, ringkas, dan modern ala SaaS tanpa teks label, keterbacaan sempurna lencana status panjang tanpa bertabrakan dengan tombol tindakan, terwujudnya tampilan tabel Ketua Tim yang fit 100% rapat, rapi, dan proporsional tanpa horizontal scrollbar (scrollWidth === clientWidth: 1045px), header kolom Tanggal yang rapi berjarak lega (> 45px) bebas dari tabrakan ke kolom Jam Diajukan, serta terwujudnya dialog modal Ajukan Lembur 2-kolom yang fit 100% tanpa scrollbar vertikal di desktop/laptop dengan alinyemen atas-bawah presisi sejajar serta komposisi input yang lapang dan simetris. | Menyelaraskan animasi penutupan flexbox (margin-left: -16rem) dengan transisi 300ms, evaluasi synchronous localStorage pada head untuk mencegah loncatan layout, pembatasan teks multiline agar tidak meluap keluar batas sel tabel, penataan breakpoint reflow mobile sesuai prinsip antislop layout, penataan tombol icon-only h-7 w-7 terpusat simetris lengkap dengan atribut tooltip title yang informatif, penyeimbangan rasio colgroup presisi 100% dan ukuran lencana kompak text-[10.5px] agar tabel fit di desktop tanpa scrollbar dan tanpa tabrakan horizontal, penyelarasan pembungkus flex-col dan vertical-align header kolom bertingkat, serta penataan responsif grid 2-kolom yang mempertahankan ukuran kanvas tanda tangan tanpa distorsi goresan garis tanda tangan. | Skrip Audit Excel, LemburController, resources/views/partials/sidebar.blade.php, resources/views/lembur.blade.php, resources/views/ketua-tim/lembur.blade.php, resources/views/admin/lembur.blade.php, resources/views/ketua-tim/pengajuan.blade.php, resources/views/admin/pengajuan.blade.php, resources/views/layouts/app.blade.php, dan DOKUMENTASI_PERUBAHAN_ALUR_LEMBUR.md |
| 9 | Selasa, 13 Oktober 2026 | 07.30 - 16.00 | WFO | Pembimbing Lapangan | Menyusun instrumen pengujian perangkat lunak formal Black Box Testing Suite (PENGUJIAN_BLACKBOX.md) mencakup 70 kasus uji melintasi 8 modul (Autentikasi/RBAC SSO & Dev-Login, Pegawai, Ketua Tim, Kabag Umum, Admin, Presensi BOS, Dokumen Kedinasan, dan Manajemen User). | Terwujudnya dokumen baku jaminan mutu perangkat lunak (QA / UAT) dengan tingkat kelulusan 100% (70/70 PASS), menjadi landasan verifikasi fungsional dan laporan resmi magang MBKM. | Merancang variasi kasus uji nilai batas (boundary value) dan transisi status antrean persetujuan bertingkat agar mencakup skenario riil di kantor BPS. | Dokumen `PENGUJIAN_BLACKBOX.md` & Repositori Sistem Lembur |
| 10 | Rabu, 14 Oktober 2026 | 07.30 - 16.00 | WFO | Pembimbing Lapangan | Mengimplementasikan validasi durasi lembur (minimal 1 jam dan kelipatan 30 menit), mengunci pengajuan hanya 1 transaksi lembur per hari per pegawai, menangani lembur lintas hari (melewati tengah malam) dengan jam akhir presensi di hari berikutnya, validasi ketat pembubuhan tanda tangan (inspeksi kanvas goresan tinta piksel non-kosong minimal 300 piksel hitam via ekstensi PHP GD), proteksi pengeditan pengajuan lembur yang hanya diizinkan pada status pending dan menunggu_kabag (tim bagian umum), merekayasa antarmuka mode linimasa (timeline) 3-kolom modern dengan garis vertikal berhias ikon status interaktif, memberikan fleksibilitas koreksi bagi Ketua Tim untuk menolak pengajuan lembur yang telah disetujuinya selama belum diproses Kabag Umum, menyelesaikan bug layar pemuat beku (loading overlay hang) saat penolakan pengajuan, memodernisasi tata letak modal dialog formulir pengajuan lembur (#modalAjukan) menjadi Bounded Modal Container (max-h-[90vh]) dengan Fixed Header berbadge oranye BPS, Scrollable Body internal, Pinned Footer dengan tombol Batal dan Kirim Pengajuan yang selalu tampak di layar, serta penyelarasan penataan tombol Hapus Tanda Tangan dan kartu estimasi durasi berbadge warna dinamis, mengimplementasikan pencarian data komprehensif pada tabel Persetujuan Kabag Umum (/kabag-umum/pengajuan) via AJAX instan tanpa kedipan (0ms latency), membatasi tombol "Lihat semua" pada kartu dashboard agar tetap berdimensi proporsional horizontal, menstandarisasi format catatan transaksi dibatalkan oleh Admin pada tabel, linimasa, dan modal detail di seluruh role, menstandarisasi antarmuka pengajuan lembur pribadi bagi Ketua Tim dan Kabag Umum menggunakan view mandiri lembur.blade.php lengkap dengan filter waktu multi-tahun, memperbaiki filter status Dibatalkan pada monitoring lembur admin (/admin/lembur), menambahkan page header terstruktur dan tombol kembali/tutup pada halaman Riwayat Presensi Admin (/admin/riwayat_presensi), memperbaiki false-positive validasi uraian kegiatan saat persetujuan pengajuan lembur tanpa data presensi melalui normalisasi canonical CRLF/LF, merancang custom floating dropdown filter status pada toolbar monitoring admin, meredesain modal pemilihan pegawai pada menu Manajemen User (/admin/manajemen-user) menjadi antarmuka landscape 2-kolom interaktif zero-scroll (preview pegawai di kiri dan pencarian di kanan), serta menerapkan prinsip desain anti-slop pada Manajemen User dengan panel eksekutif terpadu pejabat penandatangan aktif (Kabag Umum & PPK) dan diferensiasi peran Admin Lembur vs Super Administrator. | Tercegahnya manipulasi jam lembur, kepatuhan 100% pada aturan SPJ/uang makan lembur pemerintah, hasil pencarian akurat mencakup seluruh data tanpa reload, efisiensi ruang vertikal layar, tersedianya dialog modal suksesi pejabat dan admin yang tampil fit 100% tanpa memicu scrollbar vertikal pada peramban laptop/desktop, serta terwujudnya tata letak halaman Manajemen User yang berwibawa, bersih, ringkas, hemat ruang layar hingga 40%, tidak tampak seperti template AI generik (anti-slop), dan menyajikan kejelasan wewenang struktural secara tegas. | Penanganan sampling piksel GD di bawah 2 milidetik, penataan flexbox dan whitespace-nowrap tombol dashboard, pembersihan prefix teks sistem yang kotor, normalisasi line-break Windows CRLF vs Linux LF, serta penataan stacking context Tailwind v4. | Berkas Controller, View Blade Lembur, Manajemen User, PENGUJIAN_BLACKBOX.md, dan PANDUAN_DEPLOY_SERVER.md |

### Minggu III (15 - 21 Oktober 2026) - Inisiasi dan Fondasi Arsitektur SEMAR PST (BPS Provinsi Jawa Tengah)
| No | Hari/Tanggal | Waktu | Lokasi | Pemberi Tugas | Kegiatan | Hasil yang Diperoleh | Kendala | Tautan Bukti |
|:---:|:---|:---:|:---:|:---|:---|:---|:---:|:---|
| 11 | Kamis, 15 Oktober 2026 | 07.30 - 16.00 | WFO | Pembimbing Lapangan | Penyelesaian finalisasi antarmuka dan interaktivitas Sistem Lembur (TEMPE DELE) serta Kick-off Pengembangan Sistem Terpadu SEMAR PST BPS Provinsi Jawa Tengah: melakukan penataan 2-layer backdrop modal murni (@stack('modals')) untuk mengeliminasi keterbatasan compositing layer Chromium dan garis putih footer, optimasi scrolling tabel mobile (kinetic momentum touch gliding & tombol panah cepat [ < ] dan [ > ]), penyusunan dataset simulasi persetujuan berjenjang Tim SID pegawai Herry Kusmaiwanto S.Si periode 01 s.d. 14 Oktober 2026; sekaligus memulai analisis teknis arsitektur 5 basis data warisan (pst, statisfy, pristi, pengaduan, elena), perumusan dokumen spesifikasi kebutuhan sistem (PRD fix.md), perancangan Roadmap Kanban Backlog 35 tiket terukur, dan penetapan 10 Golden Engineering Rules (AGENTS.md). | Tampilan dialog modal sistem lembur berlatar gelap slate merata 100% penuh viewport tanpa celah bocor, navigasi tabel smartphone ultra-smooth tanpa lag, kesiapan dataset uji coba simulasi alur lembur, tersusunnya dokumen PRD Final dan Roadmap Backlog SEMAR PST, serta ditetapkannya standar baku tata kelola rekayasa perangkat lunak kedinasan BPS yang ketat. | Variasi skema kolom dan inkonsistensi representasi NIP pegawai lintas 5 basis data lama (NIP 9 digit, teks nama tanpa gelar, dan NIP 18 digit) yang dianalisis untuk pemetaan deduplikasi master user terpadu. | Dokumen `PRD fix.md`, `roadmap dan backlog.md`, `AGENTS.md`, dan Repositori Sistem Lembur |
| 12 | Jumat, 16 Oktober 2026 | 07.30 - 16.30 | WFO | Pembimbing Lapangan | Menginisiasi arsitektur monolith Laravel 12 SEMAR PST (BE-01); mengimplementasikan migrasi skema 12 tabel basis data inti, model Eloquent, serta otomasi pengujian (BE-02 s.d. BE-04); standarisasi dokumentasi dwibahasa (ID/EN DocBlocks) dan clean code pada 12 model Eloquent; perumusan 12 Golden Engineering Rules di AGENTS.md (Backend Anti-Slop & Ponytail YAGNI Ladder); mengonfigurasi SSO & Multi-Role RBAC 6 peran resmi kedinasan BPS (super_admin, operator_pst, admin_pristi, admin_pengaduan, admin_elena, eksekutif), seeding hak akses permissions 12 modul, akun uji coba terverifikasi, dan Filament Shield Policies (BE-05); serta penulisan Feature Test Suite komprehensif (20 tests, 96 assertions 100% PASS). | Terwujudnya repositori monolith modern SEMAR PST yang siap pakai, eliminasi fragmentasi 5 sistem lama ke dalam 12 tabel MySQL semar_pst yang terstruktur rapi, relasi foreign key 100% valid tanpa galat, penerapan standar dokumentasi kode dwibahasa yang seragam di seluruh model, standarisasi tata kelola rekayasa perangkat lunak kedinasan BPS, tersedianya sistem otorisasi multi-role dan segregasi tugas (segregation of duties) yang ketat pada 6 peran BPS, terpasangnya Filament Shield Policies di seluruh 12 modul, serta verifikasi mutu kode otomatis dengan hasil 100% lulus (20 tests, 96 assertions tanpa regresi). | Penanganan dependensi PHP 8.2 runtime lokal XAMPP, resolusi benturan timestamp migrasi beruntun pada foreign key MySQL, dan normalisasi penamaan atribut dwibahasa (Domain BPS Bahasa Indonesia & Arsitektur Bahasa Inggris). | Berkas repositori semar-pst, database/migrations, app/Models, app/Policies, database/seeders/RoleAndPermissionSeeder.php, tests/Feature, AGENTS.md, dan CHANGELOG.md |

---

## LAMPIRAN 2: DOKUMENTASI KEGIATAN

### Minggu I (SWARA JAGAD)
- Gambar 1.1: Berkas modul layanan integrasi data SatuDataService dan mekanisme resilient caching.
- Gambar 1.2: Dokumen panduan teknis PEMETAAN_DATASET_SDJT_PER_DIMENSI.md.
- Gambar 1.3: Tangkapan layar antarmuka modul Dimensi 1 (IKPS, Peta Sampah, dan Direktori UMKM/Koperasi Hijau).
- Gambar 1.4: Tampilan visual peta interaktif Green Industry dengan geocoding presisi dan kartu metrik analitik.
- Gambar 1.5: Antarmuka dokumentasi REST API v1 berbasis Swagger OpenAPI (/api/documentation).
- Gambar 1.6: Hasil eksekusi pengujian otomatis fitur perangkat lunak PHPUnit Feature Test Suite (100% PASS).

### Minggu II (Sistem Lembur TEMPE DELE)
- Gambar 2.1: Tampilan modal koreksi Ketua Tim dengan opsi penolakan aktif pada transaksi berstatus menunggu_kabag.
- Gambar 2.2: Tombol aksi Setujui pada tabel Kabag Umum untuk memproses langsung pengajuan status pending anggota Tim Bagian Umum.
- Gambar 2.3: Lembar cetak langsung Daftar Hadir (PNS & PPPK) dengan jam pulang presensi riil serta berkas SPKL Excel ber-auto row height.
- Gambar 2.4: Hasil investigasi relasi tabel t_transaksi & t_presensi dan proteksi penayangan jam disetujui berbasis has_presensi.
- Gambar 2.5: Hasil audit ekstraksi berkas Rekap Presensi 9 September.xlsx dan antarmuka modal upload presensi ber-dropdown target tahun.
- Gambar 2.6: Dokumen instrumen pengujian fungsional perangkat lunak Black Box Testing Suite (70 test cases 100% PASS).
- Gambar 2.7: Validasi durasi lembur, penguncian 1 lembur/hari, lembur lintas hari, inspeksi piksel tanda tangan, dan proteksi edit non-pending.
- Gambar 2.8: Antarmuka linimasa 3-kolom modern, fleksibilitas koreksi penolakan internal Ketua Tim, dan penanganan bug layar pemuat beku (loading overlay fix).
- Gambar 2.9: Tata letak modal Ajukan Lembur terstandarisasi (bounded container max-h-[90vh], fixed header, scrollable body, dan pinned footer).
- Gambar 2.10: Redesign tabel persetujuan pengajuan Ketua Tim (/ketua-tim/pengajuan): Layout Desktop pas 100%, Sticky Column No & Nama Pegawai di Mobile, serta Modal Pop-up Rincian Uraian Tugas.
- Gambar 2.11: Tampilan tombol aksi icon-only tanpa teks label pada tabel dan linimasa Ketua Tim serta tabel monitoring Admin.
- Gambar 2.12: Kalibrasi tabel persetujuan pengajuan Ketua Tim (/ketua-tim/pengajuan) fit 100% desktop rapat, rapi, anti-scrollbar horizontal, dan bebas tabrakan elemen.
- Gambar 2.13: Header kolom Tanggal bertingkat vertikal (flex-col) dengan dropdown sortir di bawahnya pada tabel Ketua Tim.
- Gambar 2.14: Restrukturisasi tata letak modal Ajukan Lembur menjadi 2 kolom desktop zero-scroll (Pegawai, Ketua Tim, Admin).
- Gambar 2.15: Antarmuka pencarian menyeluruh ultra-smooth (0ms latency tanpa reload) dan baris paginasi 3 bagian pada halaman Persetujuan Kabag Umum (/kabag-umum/pengajuan).
- Gambar 2.16: Standarisasi dimensi dan responsivitas tombol "Lihat semua" pada kartu dashboard lintas peran, resolusi layar laptop/desktop, dan status sidebar terbuka/tertutup.
- Gambar 2.17: Izin pengeditan mandiri pengajuan lembur bagi anggota Tim Bagian Umum pada status menunggu_kabag sebelum diproses Kabag Umum.
- Gambar 2.18: Standarisasi ketebalan font teks uraian kegiatan linimasa dan judul modal detail dari bold menjadi semi-bold di seluruh role.
- Gambar 2.19: Standarisasi format catatan transaksi dibatalkan oleh Admin (label khusus 'Admin:' dan pembersihan prefix teks teknis [Dibatalkan Admin]) pada tabel, linimasa, dan modal detail di seluruh role.
- Gambar 2.20: Standarisasi antarmuka pengajuan lembur pribadi Ketua Tim dan Kepala Bagian Umum menggunakan view modern lembur.blade.php lengkap dengan filter multi-tahun, modal ajukan 2-kolom zero-scroll, dan mode linimasa.
- Gambar 2.21: Tampilan tabel monitoring lembur Admin (/admin/lembur) pada tab filter Dibatalkan yang secara presisi menampilkan data transaksi berstatus dibatalkan dengan label catatan Admin yang bersih.
- Gambar 2.22: Tampilan halaman Riwayat Presensi Admin (/admin/riwayat_presensi) dengan page header terstruktur, tombol Kembali ke Presensi, tombol Tutup (x), dan kartu tabel riwayat berkas spreadsheet.
- Gambar 2.23: Alur persetujuan pengajuan lembur tanpa data presensi yang berhasil diproses secara mulus oleh approver (Ketua Tim, Kabag Umum, Admin) tanpa memicu false-positive error validasi uraian kegiatan.
- Gambar 2.24: Antarmuka floating dropdown status filter pada toolbar monitoring lembur admin (/admin/lembur) di samping bilah pencarian tim lengkap dengan titik warna, hitungan real-time, dan highlight status aktif.
- Gambar 2.25: Antarmuka modal landscape 2-kolom zero-scroll untuk pemilihan pejabat dan admin pada modul Manajemen User (/admin/manajemen-user) lengkap dengan kartu pratinjau pegawai terpilih di kolom kiri dan pencarian instan di kolom kanan.
- Gambar 2.26: Penerapan antarmuka anti-slop pada modul Manajemen User (/admin/manajemen-user) dengan panel eksekutif terpadu 2-kolom pejabat aktif (Kabag Umum & PPK), laci arsip riwayat, serta diferensiasi peran Admin Lembur dan Super Administrator.
- Gambar 2.27: Penempatan tombol aksi '+ Tambah Admin' (oranye amber BPS #faa938) dan '+ Tambah Superadmin' (merah tegas bg-red-600) di luar/atas kartu tabel masing-masing (Admin Lembur dan Super Administrator) menjaga bingkai tabel murni rounded-2xl dan kontras teks tinggi.
- Gambar 2.28: Tampilan modal penetapan Super Administrator dan penugasan Admin Lembur berdesain formal kedinasan BPS, tipografi ramah mata, dan kartu peringatan keamanan slate-rose anti-slop.
- Gambar 2.29: Standarisasi menyeluruh dialog konfirmasi, validasi pembubuhan tanda tangan digital, peringatan unggah presensi, sinkronisasi pegawai, dan notifikasi status ke dalam bahasa formal kedinasan BPS pada seluruh modul aplikasi.
- Gambar 2.30: Tampilan bilah sisi (sidebar) responsif mobile/tablet yang tajam (crisp), tidak buram, dan interaktif di depan backdrop overlay pasca penataan ulang z-index.
- Gambar 2.31: Tampilan dialog modal Penetapan Super Administrator dan Pengalihan Admin Lembur berarsitektur Bounded Modal Container (max-h-[90vh]) dengan pinned action footer yang selalu tampil utuh dan interaktif pada layar laptop beresolusi pendek (< 650px).
- Gambar 2.32: Tampilan tabel yang dapat digeser secara horizontal (horizontal swipeable table) dengan bilah indikator geser sentuh interaktif (.table-scroll-hint) pada tampilan mobile di seluruh modul aplikasi tanpa mengubah tata letak desktop sama sekali.
- Gambar 2.33: Tampilan pop-up modal interaktif Detail Uraian Tugas (#modalDetailUraian) dan tombol aksi icon-only pada halaman Persetujuan Lembur Kabag Umum (/kabag-umum/pengajuan), penutupan backdrop overlay mobile sidebar tanpa efek blur, serta alur persetujuan bertingkat Tim SID atas pegawai Herry Kusmaiwanto S.Si.
- Gambar 2.34: Tampilan dialog modal Penetapan Super Administrator dan modal pop-up lainnya dengan latar belakang gelap (backdrop overlay) yang menutupi 100% tinggi layar penuh hingga batas bawah peramban tanpa celah strip putih footer pasca restrukturisasi flexbox stacking context.
- Gambar 2.35: Tampilan dialog modal Penetapan Super Administrator dan Penugasan Admin Lembur berarsitektur 2-layer backdrop murni dan portaling root @stack('modals') yang menyajikan lapisan buram (backdrop blur) dan rona gelap slate yang merata 100% dari sudut atas hingga sudut terbawah monitor tanpa celah teks tabel bocor di atas bilah tugas OS.
- Gambar 2.36: Tampilan pergeseran tabel mobile yang sangat halus (ultra-smooth) dilengkapi tombol panah sentuh cepat [ < ] dan [ > ], indikator persentase interaktif, penyeretan rel 1:1 tanpa lag, kinetic momentum touch gliding, serta eliminasi scrollbar bawah kaku tanpa mengubah tampilan desktop sama sekali.

### Minggu III (SEMAR PST - Konsolidasi 5 Sistem BPS Jateng)
- Gambar 3.1: Dokumen Spesifikasi Kebutuhan Sistem (PRD fix.md) dan Roadmap Backlog Arsitektur SEMAR PST.
- Gambar 3.2: Standar Tata Kelola Rekayasa Perangkat Lunak 10 Golden Engineering Rules (AGENTS.md).
- Gambar 3.3: Struktur Proyek Laravel 12 Monolith SEMAR PST dengan Integrasi FilamentPHP v3 Admin Panel.
- Gambar 3.4: Skema Relasi Basis Data Terpadu 12 Tabel (Users, Loket, Buku Tamu, Tiket, Survei, Pristi, Aduan, Elena).
- Gambar 3.5: Migrasi Database MySQL semar_pst Berhasil Dijalankan (Zero Error Foreign Key).
- Gambar 3.6: Berkas Model Eloquent dan Skema Migrasi Transaksi Pristi, Pengaduan, dan Elena (BE-04).
- Gambar 3.7: Berkas Seeder Hak Akses Multi-Role (RoleAndPermissionSeeder) dan Filament Shield Policies untuk 6 Peran Resmi BPS (BE-05).
- Gambar 3.8: Hasil Eksekusi Pengujian Otomatis Feature & Unit Test Suite SEMAR PST (20 Tests, 96 Assertions 100% PASS).
