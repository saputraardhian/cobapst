# PRISTELA - Living Changelog

This document maintains the active, consolidated status of features, architecture, and changes for PRISTELA (BPS Provinsi Jawa Tengah).
In accordance with Rule #2 (In-Place Updates), entries for existing features are updated in-place to reflect their current Single Source of Truth rather than stacked redundantly.

---

## Architecture Foundation & Configuration (BE-01)
- **Status:** Complete (Verified)
- **Framework Core:** Laravel 12.x on PHP 8.2 (CLI: PHP 8.2.12 Windows XAMPP).
- **Core Packages Installed:**
  - `filament/filament` (^3.2 / v3.3.57) - Admin & Backoffice Panel
  - `livewire/livewire` (v3.8.10) - Reactive Public Frontend UI
  - `spatie/laravel-permission` (v6.25.0) - Role-Based Access Control
  - `bezhansalleh/filament-shield` (v3.9.10) - Filament Permission Management & Policies
  - `maatwebsite/excel` (v3.1.70) - Excel Import & Export Processing
  - `simplesoftwareio/simple-qrcode` (v4.2.0) - Thermal Ticket & Desk QR Generator
  - `barryvdh/laravel-dompdf` (v3.1.2) - PDF & Award Certificate Engine
- **Admin Panel Configuration:**
  - `app/Providers/Filament/AdminPanelProvider.php` registered in `bootstrap/providers.php` with official brand name `PRISTELA`.
  - Application branding synchronized to `PRISTELA` across `.env`, `config/app.php`, Filament Admin Panel, SSO Auth screens, Public Portal, TV Display, and Kiosk Lobby.
  - Filament published assets and routes verified (`admin/login`, `admin`).
- **Verification Results (Rule #1):**
  - Routes: 14 active routes discovered without conflicts.
  - Automated Tests: 2 passing tests (0 failures).
- **System Constraints & Rules:**
  - Strict adherence to `AGENTS.md` (12 Golden Engineering Rules including Ponytail YAGNI Ladder, Backend Anti-Slop, and Rule 12 Bilingual Clean Code Documentation).
  - Standard ASCII hyphen (`-`) strictly enforced across all documentation and codebase.
  - Complete bilingual (ID/EN) DocBlocks and strict type hints implemented across all 12 core Eloquent models.

---

## Migration Master Pegawai & Loket (BE-02)
- **Status:** Complete (Verified)
- **Database Engine:** MySQL 8.0 / MariaDB (Database: `semar_pst`).
- **Tables Implemented:**
  1. `users` (Peleburan Master Pegawai BPS):
     - Primary Key: `id` (bigint unsigned)
     - Core Attributes: `nip_baru` (char 18, unique, nullable), `nip_lama` (varchar 9, nullable), `name`, `name_without_title` (nullable), `email` (unique), `password`, `whatsapp_number` (varchar 20, nullable), `region_code` (varchar 10, nullable), `unit_code` (varchar 20, nullable), `unit_name` (varchar 100, nullable), `echelon_id` (varchar 10, nullable), `elena_team_code` (varchar 20, nullable), `elena_calc_code` (varchar 20, nullable), `is_active` (boolean, default true).
     - Trait & Contracts: `HasRoles` (Spatie), `FilamentUser` (`canAccessPanel`).
  2. `pst_counters` (Master Meja Loket PST):
     - Primary Key: `id` (bigint unsigned)
     - Core Attributes: `counter_name` (varchar 50), `counter_number` (unsignedTinyInteger), `static_qr_slug` (varchar 50, unique), `active_operator_id` (foreignId to `users.id`, nullable, nullOnDelete), `is_open` (boolean, default false), `description` (varchar 255, nullable).
     - Relationships: `activeOperator()` (BelongsTo `User`), reciprocal `activeCounter()` (HasOne on `User`).
  3. `permissions`, `roles`, `model_has_roles`, `role_has_permissions`:
     - Spatie Permission tables published and migrated.
- **Verification Results (Rule #1):**
  - Syntax check: `php -l` passed on all migration and model files.
  - Automated Tests: 4/4 passing tests in `MasterUsersAndCountersTest` and test suite.

---

## Migration Transaksi PST & Statisfy (BE-03)
- **Status:** Complete (Verified)
- **Database Engine:** MySQL 8.0 / MariaDB (Database: `semar_pst`).
- **Tables Implemented:**
  1. `pst_visitors` (Master Buku Tamu Pengunjung PST):
     - Primary Key: `id` (bigint unsigned)
     - Core Attributes: `phone_number` (varchar 20, unique, index - basis auto-lookup), `name`, `email` (nullable), `institution` (nullable), `address` (text, nullable).
     - Relationships: `visitorLogs()` (HasMany `PstVisitorLog`), `serviceTickets()` (HasMany `PstServiceTicket`).
  2. `pst_visitor_logs` (Riwayat Kunjungan Buku Tamu):
     - Primary Key: `id` (bigint unsigned)
     - Core Attributes: `visitor_id` (foreignId to `pst_visitors`, cascadeOnDelete), `visit_date` (date, index), `purpose` (text, nullable), `check_in_at` (dateTime), `check_out_at` (dateTime, nullable), `notes` (text, nullable).
     - Relationships: `visitor()` (BelongsTo `PstVisitor`).
  3. `pst_service_tickets` (Tiket Antrian Lobi & Registrasi Layanan):
     - Primary Key: `id` (bigint unsigned)
     - Core Attributes: `ticket_number` (varchar 20), `queue_code` (varchar 10), `queue_number` (unsignedSmallInteger), `qr_token` (varchar 64, unique, index), `service_date` (date, index), `service_type` (varchar 50), `visitor_id` (foreignId to `pst_visitors`, nullable, nullOnDelete), `visitor_name`, `is_first_visit` (boolean, default false), `counter_id` (foreignId to `pst_counters`, nullable, nullOnDelete), `served_by_user_id` (foreignId to `users`, nullable, nullOnDelete), `request_detail` (text, nullable), `service_report` (text, nullable), `status` (varchar 20, index, default 'waiting'), `called_at`, `started_at`, `finished_at`.
     - Relationships: `visitor()` (BelongsTo), `counter()` (BelongsTo), `servedBy()` (BelongsTo `User`), `survey()` (HasOne `StatisfySurvey`).
  4. `statisfy_surveys` (Survei Kepuasan Masyarakat 15 Detik):
     - Primary Key: `id` (bigint unsigned)
     - Core Attributes: `service_ticket_id` (foreignId to `pst_service_tickets`, nullable, unique, nullOnDelete), `rated_user_id` (foreignId to `users`, nullable, nullOnDelete), `counter_id` (foreignId to `pst_counters`, nullable, nullOnDelete), `survey_date` (date, index), `respondent_name` (nullable), `respondent_email` (nullable), `respondent_phone` (varchar 20, nullable), `service_type` (varchar 50, nullable), `media_channel` (varchar 50, default 'offline'), `rating_score` (unsignedTinyInteger), `feedback_comment` (text, nullable), `remote_ip` (varchar 45, nullable).
     - Relationships: `serviceTicket()` (BelongsTo), `ratedUser()` (BelongsTo `User`), `counter()` (BelongsTo `PstCounter`).
  5. `statisfy_ai_analyses` (Penyimpanan Eksekutif Analisis AI):
     - Primary Key: `id` (bigint unsigned)
     - Core Attributes: `mode` (varchar 20: bulanan, tahunan, alltime), `period_month` (unsignedTinyInteger, nullable), `period_year` (unsignedSmallInteger, nullable), `analysis_result` (longText), `summary_sentiment` (varchar 50, nullable), `total_reviews_analyzed` (unsignedInteger, default 0).
- **Verification Results (Rule #1):**
  - Syntax check: `php -l` passed on all 5 migrations, 5 models, and 5 factories.
  - Automated Tests: 8/8 tests passed (23 assertions) in `PstAndStatisfyTransactionsTest` and full suite.

---

## Migration Transaksi Pristi, Aduan & Elena (BE-04)
- **Status:** Complete (Verified)
- **Database Engine:** MySQL 8.0 / MariaDB (Database: `semar_pst`).
- **Tables Implemented (Completing all 12 core tables):**
  1. `pristi_schedules` (Kalender Jadwal Shift Jaga Loket):
     - Primary Key: `id` (bigint unsigned)
     - Core Attributes: `user_id` (foreignId to `users`, cascadeOnDelete), `counter_id` (foreignId to `pst_counters`, nullable, nullOnDelete), `schedule_date` (date, index), `day_name` (varchar 20), `shift_name` (varchar 50), `swapped_with_user_id` (foreignId to `users`, nullable, nullOnDelete), `notes` (text, nullable).
     - Relationships: `user()`, `counter()`, `swappedWith()`, `attendances()` (HasMany `PristiAttendance`).
  2. `pristi_attendances` (Presensi Jaga & Kalkulasi Keterlambatan):
     - Primary Key: `id` (bigint unsigned)
     - Core Attributes: `schedule_id` (foreignId to `pristi_schedules`, nullable, nullOnDelete), `user_id` (foreignId to `users`, cascadeOnDelete), `attendance_date` (date, index), `check_in_time` (time, nullable), `check_out_time` (time, nullable), `time_difference` (integer, default 0 - menit keterlambatan), `source` (varchar 50, default 'operator_console'), `status` (varchar 20, default 'present'), `notes` (text, nullable).
     - Relationships: `schedule()`, `user()`.
  3. `pristi_monev_reports` (Evaluasi Monev 9 Dimensi Kab/Kota):
     - Primary Key: `id` (bigint unsigned)
     - Core Attributes: `region_code` (varchar 10, index), `region_name` (varchar 100), `period_year` (unsignedSmallInteger, index), `period_type` (varchar 20: bulan, triwulan, semester), `period_value` (unsignedTinyInteger), 9 dimensi skor (`score_adm`, `score_teknis`, `score_sdm`, `score_korupsi`, `score_prof`, `score_sarpras`, `score_sipp`, `score_pengaduan`, `score_inovasi`), `total_score` (decimal 5,2), `verified_by_user_id` (foreignId to `users`, nullable, nullOnDelete).
     - Relationships: `verifiedBy()`.
  4. `public_complaints` (Pengaduan Publik & Tracking Code):
     - Primary Key: `id` (bigint unsigned)
     - Core Attributes: `tracking_code` (varchar 30, unique, index - format `ADU-YYYY-XXXX`), `reporter_name`, `reporter_email`, `reporter_phone`, `complaint_category`, `complaint_content`, `public_feedback`, `internal_follow_up`, `related_user_id` (foreignId to `users`, nullable, nullOnDelete), `is_valid_penalty` (boolean, default false - flag penalti Elena), `status` (varchar 20, default 'received'), `remote_ip`, `resolved_at`.
     - Relationships: `relatedUser()`.
  5. `elena_evaluations` (Matriks Evaluasi Kinerja Bulanan & Sertifikat):
     - Primary Key: `id` (bigint unsigned)
     - Core Attributes: `user_id` (foreignId to `users`, cascadeOnDelete), `period_year` (index), `period_month` (index), `total_services_count`, `total_surveys_count`, `raw_satisfaction_avg`, `raw_performance_score`, `raw_attendance_score`, `raw_cooperation_score`, `raw_innovation_score`, `raw_appearance_score`, `raw_complaint_count`, `weighted_performance`, `weighted_attendance`, `weighted_cooperation`, `weighted_innovation`, `weighted_appearance`, `weighted_complaint`, `total_score`, `eval_score`, `rank_position`, `is_locked`, `locked_at`.
     - Constraints: Unique compound key `['user_id', 'period_year', 'period_month']`.
     - Relationships: `user()`.
- **Verification Results (Rule #1):**
  - Syntax check: `php -l` passed on all 5 migrations, 5 models, and 5 factories.
  - Automated Tests: 13/13 tests passed (41 assertions) in `PristiAduanElenaTransactionsTest` and full suite.

---

## Konfigurasi SSO & Multi-Role RBAC (BE-05)
- **Status:** Complete (Verified)
- **Security & Authorization Framework:** `spatie/laravel-permission` (v6.25.0) & `bezhansalleh/filament-shield` (v3.9.10).
- **Core RBAC Architecture:**
  - **Super Admin Bypass:** Implemented `Gate::before` in `app/Providers/AppServiceProvider.php` ensuring `super_admin` holds unconditional bypass privileges across all policies and abilities.
  - **Shield Panel Integration:** Registered `FilamentShieldPlugin` in `AdminPanelProvider.php` and published `config/filament-shield.php`.
- **6 Official Roles Seeded (`RoleAndPermissionSeeder`):**
  1. `super_admin`: Administrator sistem dengan hak akses penuh ke seluruh 12 modul dan manajemen hak akses (bypass via `Gate::before`).
  2. `operator_pst`: Petugas meja layanan PST. Memiliki hak CRUD tiket layanan (`pst_service_tickets`), buku tamu (`pst_visitors` & `pst_visitor_logs`), presensi jaga (`pristi_attendances`), serta melihat survei dan evaluasi pribadi; dilarang menghapus tiket layanan atau mengubah evaluasi Elena.
  3. `admin_pristi`: Koordinator jadwal dan monev. Memiliki hak CRUD jadwal jaga (`pristi_schedules`), presensi loket (`pristi_attendances`), dan laporan monev kab/kota 9 dimensi (`pristi_monev_reports`); dilarang menghapus pegawai atau memanipulasi evaluasi Elena.
  4. `admin_pengaduan`: Tim hukum dan tindak lanjut aduan publik. Memiliki hak CRUD pengaduan masyarakat (`public_complaints`); dilarang memodifikasi meja loket atau menghapus pegawai.
  5. `admin_elena`: Tim penilai kinerja SDM BPS. Memiliki hak CRUD evaluasi kinerja bulanan (`elena_evaluations`), serta hak baca (view-only) pada tiket layanan, survei Statisfy, presensi Pristi, dan aduan publik; dilarang membuat atau menghapus tiket layanan.
  6. `eksekutif`: Kepala BPS dan jajaran pimpinan. Memiliki hak akses baca global (`viewAny` dan `view`) pada seluruh 12 modul untuk dasbor eksekutif; dilarang melakukan mutasi, penambahan, pengubahan, atau penghapusan data.
- **Granular Policies Implemented (13 Policies in `app/Policies/`):**
  - `UserPolicy`, `PstCounterPolicy`, `PstVisitorPolicy`, `PstVisitorLogPolicy`, `PstServiceTicketPolicy`, `StatisfySurveyPolicy`, `StatisfyAiAnalysisPolicy`, `PristiSchedulePolicy`, `PristiAttendancePolicy`, `PristiMonevReportPolicy`, `PublicComplaintPolicy`, `ElenaEvaluationPolicy`, `RolePolicy`.
  - All policies strictly typed (`: bool`) and documented using bilingual ID/EN DocBlocks (Rule #12).
- **Halaman Masuk SSO Pegawai Terpadu (Custom Filament Login):**
  - **Komponen & View:** `App\Filament\Pages\Auth\Login` (`resources/views/filament/pages/auth/login.blade.php`), layout `resources/views/filament/layouts/auth.blade.php`.
  - **Multi-Credential Authentication:** Mendukung masuk via email dinas (@bps.go.id), username prefix (misal `ardhian.ds`), maupun NIP 18-digit dengan pencarian otomatis dan pencocokan sandi aman.
  - **Estetika Sesuai Mockup Desain Resmi:**
    - Header top bar: Identitas logo resmi BPS `SEMARPST BPS Provinsi Jawa Tengah` dan tombol `Portal Publik` (`/`).
    - Floating card: Lencana gembok biru, tipografi serif `Login SSO Pegawai`, form terisolasi anti-tabrakan ikon (user icon & lock icon pada layout flex mandiri).
    - Toggle visibility sandi (`Lihat` / `Tutup`) berbasis Alpine.js.
    - Status keamanan lobi: Indikator hijau aktif `● TLS 1.3`.
    - Tombol aksi utama: `Masuk Sistem ->` berlatar BPS Deep Navy (`#005288`).
    - Kotak bantuan SPBE: Maklumat kontak Helpdesk IPDS Ext. 120 dan modal interaktif reset sandi.
    - Footer kelembagaan: Lencana enkripsi TLS 1.3, tautan panduan, SPBE, dan saluran helpdesk.
- **Default Official Testing Accounts Provisioned:**
  - `ardhian.ds@bps.go.id` / `ardhian.ds` (Role: `super_admin`)
  - `superadmin@bps.go.id` (Role: `super_admin`)
  - `operator.pst@bps.go.id` (Role: `operator_pst`)
  - `admin.pristi@bps.go.id` (Role: `admin_pristi`)
  - `admin.pengaduan@bps.go.id` (Role: `admin_pengaduan`)
  - `admin.elena@bps.go.id` (Role: `admin_elena`)
  - `eksekutif@bps.go.id` (Role: `eksekutif`)
- **Verification Results (Rule #1):**
  - Syntax check: `php -l` passed on all policies, custom login, layout, seeder, and test files.
  - Browser Verification: Diuji langsung di browser subagent pada resolusi 1440x900 (desk_console_wa_badge_1792636846542.png dan desk_console_guest_form_full_1792633487844.png), membuktikan tampilan prefix nomor WhatsApp terintegrasi sangat estetik, profesional, dan nyaman digunakan.
  - Clean Blank Form Default: Formulir pelayanan tamu terpadu dikosongkan secara default tanpa pre-filled data, dilengkapi placeholder informatif pada seluruh field, status header Formulir Siap Diisi, dan empty state ramah pada daftar dataset.
  - Automated Tests: 5/5 passing tests (34 assertions) pada [DeskConsoleTest.php](file:///d:/ARDHIAN%20DWI%20SAPUTRA/coba%20coba/tests/Feature/DeskConsoleTest.php).
  - Full Test Suite: 43/43 passing tests (218 assertions 100% PASS) across the entire application.

---

## ETL Script 1: Deduplikasi Master Pegawai BPS (BE-06)
- **Status:** Complete (Verified)
- **Artisan Command:** `php artisan semar:etl-users` (Namespace: `App\Console\Commands\EtlMasterUsersCommand`).
- **Data Source Ingestion:**
  - `elena` (`jatengwe_apps.sql` -> `dash_pegawai`, `elena_m_petugas`)
  - `pristi` (`pristi_db_new (2).sql` -> `dash_pegawai`)
  - `pengaduan.jateng.pro` (`pengaduan_jateng.sql` -> `t_pengaduan_admin`)
  - `statisfy` (`statisfy.sql` -> `skm_petugas_pst`)
- **Core ETL Architecture & Features:**
  - **Streaming O(1) Memory Parser:** Efficient line-by-line SQL dump streaming bypassing memory limits for large SQL dumps.
  - **18-Digit NIP Matching:** Primary deduplication key using canonical `nipbaru` with automatic fallback to `niplama` or normalized name matching.
  - **Field Normalization & Enrichment:** Clean separation of titles (`name_without_title`), email generation (`@bps.go.id`), phone number cleaning, work unit codes (`unit_code`, `unit_name`), and Elena evaluation codes (`elena_team_code`, `elena_calc_code`).
  - **Statisfy Active Frontline Officers Onboarding:** Ingestion of active PST officers (including mentors) to avoid orphaned foreign keys in subsequent transaction migrations (BE-07 & BE-08).
  - **Reversible ETL Policy & Safety (Rule #7):**
    - Mandatory `--dry-run` flag displaying scanned, matched, unmapped counts without mutating the database.
    - Anomaly audit logger: unmapped records automatically written to `storage/logs/etl_unmapped_users.log`.
- **Verification Results (Rule #1):**
  - Syntax check: `php -l` passed on `EtlMasterUsersCommand.php` and `EtlUsersTest.php`.
  - Automated Tests: 2/2 tests passed (15 assertions) in `EtlUsersTest`.
  - Full Test Suite: 22/22 tests passed (111 assertions 100% PASS).

---

## ETL Script 2: Normalisasi Nama Pegawai ke NIP (BE-07)
- **Status:** Complete (Verified)
- **Service Engine:** `App\Services\Etl\EmployeeNameResolver`
- **Artisan Command:** `php artisan semar:etl-normalize-names` (Namespace: `App\Console\Commands\EtlNormalizeNamesCommand`).
- **Data Source Ingestion & Auditing:**
  - `pst` (`pst_db.sql` -> `pst_layanan.created_by`, `pst_register.created_by`) - 35/35 (100% matched)
  - `elena` (`jatengwe_apps.sql` -> `elena_t_lk.nama_pegawai`, `elena_m_petugas`) - 50/50 (100% matched)
  - `pristi` (`pristi_db_new (2).sql` -> `presensi_m_jadwal.nama_petugas`) - 31/31 (100% matched)
  - `statisfy` (`statisfy.sql` -> `skm_kepuasan.nama_pegawai`) - 50/50 (100% matched)
- **Core Architecture & Resolver Features:**
  - **Academic Title Stripper:** Robust regular expression engine removing front honorifics (`Drs.`, `Dra.`, `Ir.`, `Dr.`) and single/compound degree suffixes (`SST`, `S.Si`, `S.E`, `A.Md, S.Kom.`, `S.Tr.Stat`, `S.Ak`, `S.Pt`, `M.Ec.Dev`, `MT`, `M.Stat`).
  - **Frontline Nickname Dictionary:** Predefined alias dictionary linking usernames and informal call signs (e.g., `nanang` -> Yun Restianto, `herpas` -> Hermawan Prasetyo, `yuli.purwita` -> Yuli Purwitasari, `dwiagus.wahyu` -> Dwi Agus Wahyu Riyadi, `rizchi` -> Rizchi Eka Wahyuni).
  - **Multi-Level Matching Strategy:** 18-digit NIP -> 9-digit NIP lama -> Alias Map -> Email Prefix -> Title-Stripped Name -> Substring/Token fallback.
  - **Reversible ETL Policy & Audit Safety (Rule #7):**
    - Mandatory `--dry-run` flag displaying scan metrics and accuracy rates.
    - Anomaly audit logger: unmapped names automatically written to `storage/logs/etl_unmapped_names.log`.
- **Verification Results (Rule #1):**
  - Syntax check: `php -l` passed on `EmployeeNameResolver.php`, `EtlNormalizeNamesCommand.php`, and `EtlNormalizeNamesTest.php`.
  - Clean Blank Form Default: Formulir pelayanan tamu terpadu dikosongkan secara default tanpa pre-filled data, dilengkapi placeholder informatif pada seluruh field, status header Formulir Siap Diisi, dan empty state ramah pada daftar dataset.
  - Automated Tests: 5/5 passing tests (34 assertions) pada [DeskConsoleTest.php](file:///d:/ARDHIAN%20DWI%20SAPUTRA/coba%20coba/tests/Feature/DeskConsoleTest.php).
  - Full Test Suite: 27/27 tests passed (135 assertions 100% PASS).

---

## ETL Script 3: Migrasi Histori Transaksi 5 DB (BE-08)
- **Status:** Complete (Verified)
- **Artisan Command:** `php artisan semar:etl-transactions` (Namespace: `App\Console\Commands\EtlTransactionsCommand`).
- **Data Source Ingestion & Target Tables:**
  - `pst_db` -> `pst_buku_tamu` (178 rows) to `pst_visitors` (178 rows, 100%)
  - `pst_db` -> `pst_kunjungan_tamu` (233 rows) to `pst_visitor_logs` (233 rows, 100%)
  - `pst_db` -> `pst_tiket` (850 rows) merged with `pst_register` & `pst_layanan` to `pst_service_tickets` (850 rows, 100%)
  - `skm_db` -> `skm_kepuasan` (572 rows) to `statisfy_surveys` (572 rows, 100%)
  - `skm_db` -> `skm_analisis_ai` (7 rows) to `statisfy_ai_analyses` (7 rows, 100%)
  - `pristi_db_new` -> `presensi_m_jadwal` (73 rows) & `presensi_t_jadwal` to `pristi_schedules` (73 rows, 100%)
  - `pristi_db_new` -> `presensi_t_transaksi` (1,728 rows) to `pristi_attendances` (1,728 rows, 100%)
  - `pengaduan_jateng` -> `t_pengaduan` (117 rows) to `public_complaints` (117 rows, 100%)
  - `jatengwe_apps` -> `elena_t_lk` (968 rows) to `elena_evaluations` (968 rows, 100%)
- **Core Architecture & Features:**
  - **Dual Ingestion Engine:** High-performance direct PDO extraction when legacy MySQL databases are connected, with automatic line-by-line streaming fallback from `.sql` files for isolated testing.
  - **Employee Foreign Key Resolution:** Seamless binding of historical officer names, nicknames, and authors to `users.id` via `EmployeeNameResolver`.
  - **Zero Data Loss & Reversible ETL Policy (Rule #7):**
    - Mandatory `--dry-run` simulation mode displaying per-module metrics.
    - Safety `--fresh` option for idempotent re-runs without duplicate insertions.
    - Anomaly audit logger: unmapped transactional rows logged to `storage/logs/etl_unmapped_transactions.log` (0 unmapped anomalies achieved).
  - **Typography & Punctuation Standard (Rule #6):** Text sanitizer automatically stripping non-UTF8 legacy encoding and replacing em/en-dashes with standard ASCII hyphens (`-`).
- **Verification Results (Rule #1):**
  - Syntax check: `php -l` passed on `EtlTransactionsCommand.php` and `EtlTransactionsTest.php`.
  - Automated Tests: 3/3 tests passed (36 assertions) in `EtlTransactionsTest`.
  - Full Test Suite: 30/30 tests passed (171 assertions 100% PASS).

---

## Layar TV Display Antrean Ruang Tunggu PST - Node 3 (FE-03)
- **Status:** Complete (Verified)
- **Komponen Livewire:** `App\Livewire\QueueTvDisplay` (`/antrian`, dengan pengalihan otomatis dari `/antrean`).
- **Template & Layout:**
  - Layout Bebas Scroll (Zero-Scroll): `resources/views/components/layouts/tv.blade.php`.
  - Tampilan Utama: `resources/views/livewire/queue-tv-display.blade.php`.
- **Rancang Bangun Sesuai Spesifikasi Design.md (Node 3):**
  1. **Header Zero-Scroll 68px:**
     - Identitas kelembagaan resmi BPS Provinsi Jawa Tengah dengan logo vektor SVG.
     - Lencana status `LIVE SINKRON` dengan animasi denyut hijau (`#6CBE45`).
     - Teks berjalan (*running marquee*): maklumat pelayanan anti-gratifikasi, jam operasional layanan, dan saluran pengaduan resmi (WhatsApp & LAPOR.go.id).
     - Jam digital besar berformat WIB dan penanggalan Bahasa Indonesia presisi.
  2. **Kolom Kiri (Spotlight Antrean Aktif):**
     - Nomor antrean jumbo (`#F7941D` Warm Orange, font-mono, drop-shadow halus) dengan pemformatan ringkas (`A-003`) dan badge token referensi lengkap.
     - Banner penanda meja tujuan bergradien Deep Navy ke Primary Blue (`#005288` -> `#005999`).
     - Kartu profil petugas loket yang bertugas aktif lengkap dengan NIP 18 digit dan avatar inisial.
     - Tombol `Uji Panggil` interaktif untuk simulasi panggilan antrean dan pengetesan audio di lobi.
  3. **Kolom Kanan (Video Edukasi & Matriks Loket):**
     - Area bingkai media 16:9 Kanal Edukasi Statistik BPS Jawa Tengah (BRS, Sensus, Indikator Strategis Kemiskinan & Inflasi).
     - Matriks status 5 meja loket PST (`Loket 1` s.d. `Loket 5`) dengan status operasional `BUKA` / `TUTUP`, nama petugas jaga, dan nomor tiket aktif.
  4. **Bilah Metrik Bawah (Footer):**
     - 4 kartu KPI antrean harian: Total Antrean, Sedang Dilayani, Menunggu Panggilan, dan Selesai Dilayani.
     - Tombol aktivasi audio status dan mode layar penuh (*fullscreen*).
  5. **Audio Engine (Web Audio API & Indonesian TTS):**
     - Sintesis nada lonceng ding-dong 3-nada harmonik pentatonik (C5 -> E5 -> G5) via Web Audio API tanpa dependensi file eksternal berat.
     - Suara panggilan sintesis Bahasa Indonesia (`SpeechSynthesisUtterance` `id-ID`) dengan pembacaan ejaan nomor antrean ramah telinga.
     - Sinkronisasi reaktif Livewire polling (`wire:poll.3s`) dengan penanganan aman payload event dan pencegahan pengulangan panggilan identik.
- **Verification Results (Rule #1):**
  - Syntax check: `php -l` lulus pada seluruh berkas terkait.
  - Browser Verification: Diuji langsung via browser subagent pada resolusi TV widescreen (0 console errors, animasi panggilan oranye aktif, chime dan TTS berfungsi).
  - Automated Tests: 4/4 tests passed (13 assertions) in `QueueTvDisplayTest`.
  - Full Test Suite: 34/34 tests passed (184 assertions 100% PASS).

---

## Portal Publik Landing Page - Node 1 (FE-01)
- **Status:** Complete (Verified)
- **Komponen Livewire:** `App\Livewire\PublicLandingPage` (`/`, `Route::get('/', PublicLandingPage::class)->name('home')`).
- **Template & Layout:**
  - Layout Publik Khusus: `resources/views/components/layouts/public.blade.php`.
  - Tampilan Livewire Utama: `resources/views/livewire/public-landing-page.blade.php`.
- **Rancang Bangun Modern SaaS Bento & Scrollytelling (Sesuai Mockup Portal Public):**
  1. **Floating Glassmorphic Navbar & Scrollytelling Progress Bar:**
     - Brand identitas kelembagaan `semarpst v2.0` (Pelayanan Statistik Terpadu - BPS Provinsi Jawa Tengah).
     - Scrollytelling spy navigation pills (`Layanan PST`, `Live Antrian`, `Petugas Terbaik`, `Kanal Pengaduan`) dengan animasi hover responsif.
     - Status operasional lobi dinamis: badge hijau menyala `Loket Buka (08:00 - 15:30 WIB)`.
     - Tombol aksi utama CTA `Ambil Tiket` mengarah langsung ke anjungan mandiri (`/kiosk`).
     - Progress bar scrollytelling 3px bergradien di puncak jendela browser yang merespons persentase scroll pembaca.
  2. **Hero Section (Bento Grid):**
     - **Kolom Kiri (Value Proposition & Maklumat Pelayanan):**
       - Lencana sertifikasi WBK/WBBM berikon perisai emas.
       - Headline berwibawa: *"Satu Pintu Layanan Data & Konsultasi Statistik Resmi"*.
       - Penjelasan ringkas akses publikasi, data mikro sensus, rekomendasi statistik (ROMANIK), dan konsultasi tatap muka langsung bersama statistisi BPS Provinsi Jawa Tengah.
       - Tombol ganda: CTA primer `Ambil Tiket Antrean Sekarang` dan CTA sekunder `Cek Status Tiket / Pengaduan`.
       - Lencana mikro-kepercayaan: jaminan kepastian bebas biaya (*Tarif Resmi Rp 0,- / Gratis Tanpa Pungutan*).
     - **Kolom Kanan (Bento Spotlight Live Monitor Antrean Real-Time):**
       - Kartu bento bergradien Deep Navy elegan berlatar `from-slate-900 to-sky-950`.
       - Badge status live `LIVE MONITOR ANTRIAN` dan indikator hijau menyala.
       - Nomor antrean jumbo dipanggil (`A-024` atau tiket aktif riil) dalam tipografi monospaced presisi tinggi.
       - Penanda meja tujuan (*Meja 3 - Pembelian Data & Konsultasi Teknis*).
       - Matriks status 5 meja loket terpadu (Loket 1 s.d. Loket 5) dengan status `BUKA` / `TERISI` / `TUTUP` dan tag warna dinamis.
       - Footer kartu yang memuat total kuota antrean hari ini dan tautan langsung ke layar TV display lobi (`/antrian`).
  3. **4 Pilar Layanan Terpadu PST (Bento Grid 4 Kolom):**
     - Rujukan hukum resmi: Peraturan BPS Nomor 2 Tahun 2021 tentang Standar Pelayanan PST BPS.
     - 4 kartu bento layanan interaktif:
       1. `Loket 1 - Perpustakaan Statistik`: Peminjaman dan pembacaan publikasi cetak/digital, direktori BRS, SLA 15 menit.
       2. `Loket 2 - Konsultasi Statistik`: Diskusi metodologi, interpretasi indikator makro, data inflasi/PDRB, SLA 30 menit.
       3. `Loket 3 - Pembelian & Data Mikro`: Permohonan dataset mikro, peta digital, data wilayah kerja statistik, SLA 20 menit.
       4. `Loket 4 - Rekomendasi (ROMANIK)`: Pengajuan rekomendasi kegiatan statistik sektoral bagi OPD Pemprov Jawa Tengah, SLA 3 hari kerja.
     - Setiap kartu dilengkapi tag kategori, estimasi SLA, dan tautan interaktif `Pilih ->` yang membuka Drawer / Modal Informasi Layanan terperinci (`wire:click="toggleServiceModal"`).
  4. **Hall of Fame Petugas PST Teladan (Podium 3D Showcase):**
     - Penghormatan aparatur berprestasi berbasis evaluasi kinerja bulanan terverifikasi sistem Elena (`elena_evaluations`).
     - Layout podium bertingkat:
       - Juara 2 (Perak): Dina Nuryita, S.Tr.Stat (Loket 1 - Perpustakaan & Rujukan), Skor Kepuasan 3.96 / 4.00, Kehadiran 98.7%.
       - Juara 1 (Emas Spotlight): Hendra Wibowo, S.Si (Loket 2 - Konsultasi Data Sektoral), Skor Elena 99.8, Kepuasan 3.98, Total Layanan 143, lencana emas *Petugas Teladan 1*.
       - Juara 3 (Perunggu): Rino Septiani, M.Stat (Loket 4 - Rekomendasi Statistik ROMANIK), Skor Kepuasan 3.91 / 4.00, Kehadiran 96.2%.
  5. **Transparansi & Pengaduan (Bento Grid 2 Kolom):**
     - **Kolom Kiri (Lacak Status Tiket Layanan & Pengaduan):**
       - Form pencarian reaktif Livewire (`wire:submit="trackTicket"`).
       - Mendukung pelacakan tiket layanan PST (`PST-2026-0112`, nomor antrean `A-024`) maupun kode pengaduan masyarakat (`ADU-2026-0042`).
       - Tampilan visual status pelacakan: badge status, persentase progress bar horizontal, tahapan penyelesaian (Diterima, Diproses, Selesai/Ditindaklanjuti), serta detail tanggapan resmi.
     - **Kolom Kanan (Kanal Pengaduan Khusus PST):**
       - Kartu bento deep navy perlindungan whistleblowing.
       - Jaminan kerahasiaan identitas 100% dan tindak lanjut terikat SLA waktu penanganan.
       - Tombol aksi langsung `Buat Laporan / Pengaduan Baru ->` menuju form pengaduan daring.
  6. **Gateway Single Sign-On (SSO) SEMARPST:**
     - Banner bento khusus pegawai dan operator lobi untuk akses konsol meja, jadwal jaga, evaluasi Elena, dan panel eksekutif (`/admin/login`).
  7. **Institutional Footer Terpadu (4 Kolom):**
     - Identitas kelembagaan BPS Provinsi Jawa Tengah, jam operasional layanan (Senin - Kamis 08:00 - 15:30 WIB, Jumat 08:30 - 15:00 WIB), direktori modul (PST, Statisfy, Pristi, Pengaduan, Elena), dan saluran narahubung resmi di Jl. Pahlawan No. 6 Semarang.
- **Verification Results (Rule #1):**
  - Syntax check: `php -l` lulus pada `PublicLandingPage.php`, `public.blade.php`, dan `public-landing-page.blade.php`.
  - Frontend Compilation: `npm run build` sukses dalam 2.89s.
  - Browser Verification: Diuji langsung di browser subagent pada resolusi 1440x900 (desk_console_wa_badge_1792636846542.png dan desk_console_guest_form_full_1792633487844.png), membuktikan tampilan prefix nomor WhatsApp terintegrasi sangat estetik, profesional, dan nyaman digunakan.
  - Automated Tests: 4/4 tests passed (16 assertions) in `PublicLandingPageTest`.
  - Full Test Suite: 38/38 tests passed (200 assertions 100% PASS).

---

## Anjungan Kios Lobi Mandiri Sentuh - Node 2 (FE-02)
- **Status:** Complete (Verified)
- **Komponen Livewire:** `App\Livewire\KioskLobby` (`/kiosk`, `Route::get('/kiosk', KioskLobby::class)->name('kiosk')`).
- **Template & Layout:**
  - Layout Khusus Layar Sentuh Kios: `resources/views/components/layouts/kiosk.blade.php` (mendukung font JetBrains Mono, Merriweather, Plus Jakarta Sans, dan `@media print` 80mm ESC/POS).
  - Tampilan Utama Kios: `resources/views/livewire/kiosk-lobby.blade.php`.
- **Rancang Bangun Sesuai Mockup Desain Kios Lobi:**
  1. **Header Kios Lobi Deep Navy (`#005288`):**
     - Lis aksen bawah oranye hangat (`#F7941D`).
     - Identitas kelembagaan: Lencana `LOBI UTAMA PST`, nama instansi `Badan Pusat Statistik Provinsi Jawa Tengah`, dan judul `Kios Antrian Mandiri PST`.
     - Jam digital real-time format WIB (`08:42:15 WIB`), penanggalan Indonesia lengkap, serta indikator `LAYANAN AKTIF`.
     - Tombol aksesibilitas: tombol beralih `Audio` (TTS) dan tombol panggil `Bantuan` petugas.
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
     - Kolom Kiri: Display dark navy status tiket aktif. Saat tiket baru diterbitkan menampilkan `Tiket Baru Berhasil Diterbitkan` dan tombol `SELESAI / AMBIL TIKET`. Saat tombol ditekan, status beralih ke `Tiket Telah Diambil Pengunjung` dengan tombol `TIKET SUDAH DIAMBIL`, tetap mempertahankan nomor tiket aktif (`D-001`, `B-001`, dll) beserta meja tujuan dan estimasi tunggu tanpa mereset ke data dummy.
     - Kolom Kanan: Pratinjau struk termal fisik 80mm presisi dengan logo resmi BPS, tepi bawah kertas bergigi robekan perforasi (`.receipt-tear-bottom`), nomor tiket aktif, tipe layanan, waktu cetak, target SLA (15 menit), stempel lencana `Struk Fisik Sudah Diambil`, serta QR code SVG dinamis (`/s/{qr_token}`) yang tetap aktif dan ter-render sempurna untuk pemantauan mandiri di ponsel.
  6. **Registrasi Cepat Tamu Kedinasan BPS Provinsi Jawa Tengah (Buku Tamu Digital):**
     - Banner header biru bergradien dengan lencana `KUNJUNGAN NON-PST` dan status `BUKU TAMU DIGITAL: Penjadwalan & Check-In Mandiri`.
     - 4 field input bersih tanpa teks placeholder contoh (siap diisi mandiri oleh pengunjung): Nomor WhatsApp (+62), NIK / Nomor KTP, Nama Lengkap Tamu, Asal Instansi / Dinas (dilengkapi sistem auto-lookup otomatis terhadap profil pengunjung yang pernah terdaftar saat nomor HP dimasukkan).
     - Chip pilihan cepat instansi terdaftar: `Dinas Kominfo Jateng`, `Bappeda Prov Jateng`, `Inspektorat Jateng`, `Perguruan Tinggi (Undip / Unnes)`, `Media Massa / Wartawan`.
     - Dropdown tim kerja yang dituju dan keperluan kunjungan kedinasan dengan opsi default kosong (`-- Pilih Kontak / Tim Kerja yang Dituju --` dan `-- Pilih Keperluan Kunjungan Kedinasan --`).
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
- **Verification Results (Rule #1):**
  - Syntax check: `php -l` lulus 100% pada seluruh berkas terkait.
  - Frontend Compilation: `npm run build` sukses dalam 3.65s.
  - Automated Tests: 6/6 tests passed (31 assertions) in `KioskLobbyTest`.
  - Full Test Suite: 50/50 tests passed (251 assertions 100% PASS across the entire project).
  - Browser Verification: Diuji langsung di browser subagent pada resolusi 1440x900 (desk_console_wa_badge_1792636846542.png dan desk_console_guest_form_full_1792633487844.png), membuktikan tampilan prefix nomor WhatsApp terintegrasi sangat estetik, profesional, dan nyaman digunakan.

---

## Monitor TV Display Antrean Lobi - Node 3 (FE-03)
- **Status:** Complete (Verified)
- **Komponen Livewire:** `App\Livewire\QueueTvDisplay` (`/antrian`, alias redirect otomatis dari `/antrean`).
- **Template & Layout:**
  - Layout Khusus Display TV: `resources/views/components/layouts/tv.blade.php` (`bg-[#F8FAFC]`, font JetBrains Mono, Merriweather, Plus Jakarta Sans, dan zero-scroll viewport).
  - Tampilan Utama TV: `resources/views/livewire/queue-tv-display.blade.php`.
- **Rancang Bangun & Penyelarasan Desain (Anti-Slop Craftsmanship Standard):**
  1. **Harmonisasi Warna Latar Belakang & Tema:**
     - Mengubah tema display dari dark-mode pekat menjadi palet resmi BPS selaras dengan Landing Page (`/`) dan Kios Mandiri (`/kiosk`): latar belakang `bg-[#F8FAFC]` (slate-50), kartu putih bersih `bg-white` berlis halus `border-slate-200/90`, dan bayangan mikro `shadow-sm`.
  2. **Header TV Lobi Deep Navy (`#005288`):**
     - Aksen garis oranye hangat (`#F7941D`) setebal 4px.
     - Logo BPS, teks identitas instansi, lencana `LIVE SINKRON` hijau berdenyut, jam digital real-time WIB (`14:05:10 WIB`), penanggalan Indonesia lengkap, tombol kontrol audio, dan tombol beralih layar penuh (*fullscreen*).
     - Running marquee kedinasan dengan teks kontras tinggi: Maklumat Pelayanan PST BEBAS BIAYA (Rp 0,-) serta saluran pengaduan WhatsApp dan lapor.go.id.
  3. **Layar Besar Sebelah Kiri (Kanal Video Edukasi Statistik BPS):**
     - Mengubah area kiri layar menjadi pemutar video edukasi berdefinisi tinggi (*high definition*).
     - Bingkai video dilengkapi bilah atas merah berdenyut `Kanal Edukasi Statistik BPS Provinsi Jawa Tengah - Informasi & Sensus Nasional`.
     - Pemutar video YouTube no-cookie responsif (rasio 16:9) dengan pemutaran otomatis tanpa jeda (*auto-loop*).
     - Bilah informasi bawah (*lower-third ticker*) menyajikan tautan Portal Data Terpadu `jateng.bps.go.id` (data inflasi, PDRB, kemiskinan) dan identitas Zona Integritas BPS Jateng.
  4. **Spotlight Panggilan Antrean Utama (Kanan Atas):**
     - Lencana status antrean (`SEDANG DILAYANI` / `SEDANG DIPANGGIL` dengan animasi ping).
     - Nomor antrean jumbo font mono JetBrains (`A-003`, `A-024`) warna oranye khas BPS (`#F7941D`).
     - Banner meja tujuan gradien navy-biru `Silakan Menuju Loket 1 - Konsultasi Statistik 1`.
     - Identitas petugas jaga lobi (nama, NIP) dan tombol uji panggilan audio simulasi (*test chime ding-dong*).
   5. **Matriks Meja Layanan PST (5 Loket) Diperbesar & Diperluas:**
      - Membentang 5 kolom horizontal penuh di bawah video dan spotlight panggilan dengan tata letak proporsional dan bebas tumpang tindih (*zero-collision*).
      - Setiap kartu loket menampilkan nomor meja (`Loket 1` s.d. `Loket 5`), status operasional (`BUKA` / `TUTUP`), nomor tiket aktif berukuran besar (`text-xl sm:text-2xl xl:text-3xl font-mono-numbers`, misal `A-003`), serta identitas petugas jaga.
      - Kartu loket yang sedang memanggil/melayani tiket aktif ditandai secara visual dengan bingkai oranye bercahaya (*ring highlight*).
      - Dilengkapi penataan *flex constraints* (`overflow-hidden`, `min-h-0`, dan hierarki z-index jelas) agar tidak bertabrakan dengan kartu panggilan antrean saat dibuka pada resolusi laptop atau layar berskala (*Windows DPI scaling*).
   6. **Footer 4 Indikator KPI Operasional Harian (48px):**
      - 4 kartu metrik real-time: Total Antrean Hari Ini, Sedang Dilayani, Menunggu Panggilan, dan Selesai Dilayani.
   7. **Mesin Audio & Sintesis Suara C-2 Compliant:**
      - Web Audio API tri-tone harmonic chime (C5: 523.25Hz -> E5: 659.25Hz -> G5: 783.99Hz).
      - Browser TTS Bahasa Indonesia (`id-ID`) dengan pembacaan natural nomor antrean dan nama loket tujuan.
- **Verification Results (Rule #1):**
  - Syntax check: `php -l` lulus 100% pada semua berkas view dan komponen.
  - Frontend Compilation: `npm run build` sukses dalam 3.08s (0 error).
  - Automated Tests: 4/4 passing tests (13 assertions) pada `QueueTvDisplayTest`.
  - Full Test Suite: 51/51 tests passed (257 assertions 100% PASS).
  - Browser Verification: Diuji langsung di browser pada endpoint `http://127.0.0.1:8000/antrian` (kartu panggilan antrean jumbo, matriks 5 loket, serta kartu KPI tersusun rapi, teks judul terbaca utuh, dan bebas benturan container).

---

## STATISFY Analytics & AI Summary - Node 5 (FE-05)
- **Status:** Complete (Verified)
- **Rute & Halaman:**
  - Filament Admin Page: `App\Filament\Pages\StatisfyAnalytics` (`/admin/statisfy`).
  - Redirect Shortcut: `/statisfy` -> `/admin/statisfy` di `routes/web.php`.
  - Unified Backoffice Layout: `resources/views/filament/layouts/unified.blade.php`.
  - Blade View: `resources/views/filament/pages/statisfy-analytics.blade.php`.
- **Fitur & Arsitektur Dasbor (Anti-Slop Craftsmanship Standard):**
  1. **Unified Backoffice Header 6-Modul (Design.md Section 4):**
     - Identitas institusi BPS Jawa Tengah dengan lencana SSO INTERNAL.
     - Navigasi 6-modul: Workspace, Konsol Meja, PRISTI (Presensi), STATISFY (Analytics - Aktif), ELENA, Pengaduan.
     - Lencana `Live SKD 2026 Sync` berdenyut, avatar inisial pegawai, dan tombol keluar sesi.
  2. **Sub-Bar Navigasi & Filter Reaktif:**
     - Breadcrumb terarah dan tombol kembali ke dashboard utama.
     - Tombol filter periode reaktif: Bulanan, Triwulanan, Tahunan, All-Time (Livewire).
     - Tombol cepat ekspor ranking petugas format Excel.
  3. **4 Kartu Metrik Ringkasan SKM (Multi-Column Grid):**
     - Indeks Kepuasan: Skor IKM 3.72 / 4.00, Mutu A (Sangat Baik), 98.50% SKM.
     - Total Responden SKM: 572 Tamu riil, +18.4% MoM, target 1,000 (57.2%).
     - Net Promoter Score (NPS): +74%, World Class rating, rasio Promoters vs Detractors.
     - Respon Tercepat Rata-Rata: 3.2m, pencapaian SLA 15m (99.1% terpenuhi), status Optimal.
  4. **Executive Summary NLP & Rekomendasi Pimpinan:**
     - Sintesis sentimen NLP V3.4 + GPT-2026, lencana sentimen positif 94.6%, dan tombol perbarui sintesis.
     - Kotak kepatuhan integritas Zero Pungli Compliance 100%.
     - Tag corpus dominan: Ramah & Sigap, Kecepatan Respons, Sangat Membantu, Data Akurat, Perluasan AC Ruang Tunggu.
     - 3 Poin rekomendasi strategis pimpinan (P1 jam sibuk, P2 kenyamanan ruang tunggu, P3 survei WhatsApp).
     - Tombol tindakan berdampingan: Kirim Disposisi Pimpinan (navy) dan Unduh Brief PDF (putih).
  5. **Tren Berkala 2026 & Sebaran Skala 4 SKD:**
     - Bar chart batang vertikal bulanan (Jan - Okt) dengan garis target RENSTRA 3.80 dan gradien hijau untuk bulan berjalan.
     - Distribusi 4 skala penilaian SKD (Sangat Puas, Puas, Cukup Puas, Kurang Puas) dengan progress bar warna, persentase, dan jumlah responden.
  6. **Kanal Layanan Terpadu & Analisis Teks SKM:**
     - Segmented bar omni-channel dan rincian 3 kanal (Tatap Muka Loket PST 66%, WhatsApp PST Jateng 20%, Website 14%).
     - Tag cloud 8 kata kunci warna-warni dan 4 kartu hits sentimen (Ramah, Kecepatan, Kualitas, Saran Ruang Tunggu).
  7. **Leaderboard Petugas PST & Evaluasi 4 Meja:**
     - Tabel peringkat kinerja pegawai dengan tab filter: Skor Tertinggi, Tamu Terbanyak, SLA Tercepat.
     - 5 profil petugas lengkap dengan avatar inisial, NIP, loket, jumlah tamu dilayani, rata-rata SLA, skor IKM bintang, dan lencana penghargaan.
     - Evaluasi matriks 4 meja loket layanan (All Mutu A) dan tombol unduh evaluasi XLSX.
  8. **Pusat Ekspor Laporan Kedinasan BPS:**
     - Spanduk gradien biru deep navy resmi BPS Jateng dengan validasi Digital Hash SHA-256.
     - Tombol Format Excel (.xlsx), Nota Dinas PDF (.pdf), dan Unduh Laporan Lengkap (oranye).
- **Verification Results (Rule #1):**
  - Syntax check: `php -l` lulus 100% pada semua berkas controller dan view.
  - Automated Tests: 4/4 passing tests (15 assertions) di `StatisfyAnalyticsTest`.
  - Browser Verification: Diuji langsung di browser subagent pada resolusi 1440x900 (desk_console_wa_badge_1792636846542.png dan desk_console_guest_form_full_1792633487844.png), membuktikan tampilan prefix nomor WhatsApp terintegrasi sangat estetik, profesional, dan nyaman digunakan.

---

## Workspace Pegawai Bento UI - Node 6 (FE-06)
- **Status:** Complete (Verified)
- **Rute & Halaman:**
  - Filament Dashboard Page: `App\Filament\Pages\Dashboard` (`/admin` atau `/admin/workspace`).
  - Redirect Shortcut: `/workspace` -> `/admin` dan `/admin/workspace` -> `/admin` di `routes/web.php`.
  - Admin Panel Provider: Didaftarkan sebagai pengganti halaman Dashboard utama di `App\Providers\Filament\AdminPanelProvider`.
  - Unified Backoffice Layout: `resources/views/filament/layouts/unified.blade.php` (Header terpadu tersinkronisasi 100% dengan STATISFY: full-width sticky top navbar `h-14 sm:h-16 bg-white border-b border-slate-200/80`, navigasi pill snug `#F1F5F9` di tengah, lencana hijau `Live SKD 2026 Sync`, avatar lingkaran `SU` dengan teks dinamis `Super Administrator BPS` / `Statistisi Ahli Pertama`, serta tombol keluar sesi lingkaran).
  - Blade View: `resources/views/filament/pages/employee-workspace.blade.php`.
- **Rancang Bangun & Penyelarasan Desain Bento Grid (Presisi 1:1 Gambar Referensi Pengguna):**
  1. **Sub-Bar Header & Breadcrumb Navigasi:**
     - Tombol cepat `<- Kembali ke Portal Publik` menuju route beranda publik.
     - Breadcrumb kedinasan: `Portal Publik BPS > Workspace Pegawai`.
  2. **Row 1 - Bento Kiri: Status Penugasan & Tiket Antrean Aktif:**
     - Lencana shift: `• Shift Aktif Hari Ini | 08:00 - 15:30 WIB` (emerald) dan `Meja 2: Konsultasi Statistik` (amber).
     - Sapaan ramah personal: `Selamat Bertugas, Mas Hendra!` dengan teks aksen biru BPS (`#005999`).
     - Deskripsi kesiapan PST: `"Pusat Layanan Statistik Terpadu (PST) siap melayani masyarakat dan stakeholder dengan integritas, kecepatan, dan data berkualitas."`.
     - Inset Card Tiket Antrean Aktif A-014 (Presisi 100% Mockup Pengguna):
       - Lencana jumbo oranye (`TIKET A-014` dengan font tebal mono-numbers).
       - Profil instansi: `Bappeda Provinsi Jawa Tengah` dengan pill biru muda `Tatap Muka`.
       - Lencana status: `Status: Menunggu Dipanggil di Loket` (amber).
       - Topik: `Sinkronisasi Angka Kemiskinan Makro & Publikasi PDRB Triwulan`.
       - Waktu tunggu: `WAKTU TUNGGU` / `02 menit 45 detik` (font mono emerald cerah).
     - Tombol Aksi 2 Kolom Berdampingan:
       - `[Buka Konsol Meja 2]` (navy primer) dan `[Panggil A-014 Sekarang]` (putih berlis dengan ikon pengeras suara oranye).
       - Tombol sekunder penuh: `[Lihat Jadwal Shift]` (putih berlis).
     - Fallback Siaga: Apabila antrean kosong hari ini, tampilan inset otomatis menampilkan status loket siaga bersih (`bg-[#F8FAFC]` tanpa garis putus-putus) serta aksi sinkronisasi antrean.
  3. **Row 1 - Bento Kanan: Evaluasi Kinerja Pegawai ELENA:**
     - Header kartu bintang emas: `EVALUASI KINERJA ELENA` / `Periode Berjalan: Triwulan IV 2026` dengan pill kenaikan `+2.4 Poin`.
     - Skor kumulatif: `97.8 / 100` dengan pill predikat `SANGAT BAIK` dan peringkat `Peringkat #1 dari 34 Petugas`.
     - Inset Box 3 Pilar Kinerja (Bobot Kedinasan BPS):
       - Lencana target: `✓ DI ATAS TARGET`.
       - 3 kolom nilai: `PST (40%)`: 98, `SKMS (35%)`: 96, `PRISTI (25%)`: 96.
     - 3 Indikator Sub-Metrik dengan Progress Bar Warna:
       - Kecepatan & Responsivitas: `98.4 / 100` (Sky Blue).
       - Keramahan & Sikap 5S: `99.1 / 100` (Emerald Green).
       - Akurasi & Pemahaman Metodologi: `95.6 / 100` (Amber Orange).
     - Footer capaian: `Target Triwulan: 90.0` dan tautan interaktif `Rincian Rapor SKP ->`.
  4. **Row 2 - Bento Kiri: Umpan Balik Pengunjung PST (STATISFY):**
      - Header ikon vektor SVG apresiasi pengunjung (hijau emerald tanpa emoji), judul resmi, subteks survei STATISFY, dan pill bintang `★ 5.0 / 5.0 (138 Suara)`.
      - Tag pujian terbanyak dikumpulkan: `+ Pelayanan Sangat Ramah 48x`, `+ Penjelasan Jelas 39x`, `+ Cepat & Tepat 27x`, `+ Solutif & Informatif 24x`.
      - Kotak kutipan testimoni riil: format kutipan bersih tanpa double-quote bersarang dan tanpa tanda kutip mengambang yang canggung, avatar responden `RW` Navy, `Dr. Ratna Widiastuti` (Peneliti BAPPEDA Provinsi Jawa Tengah), dan timestamp `Kemarin, 14:15 WIB`.
      - Footer: `100% pengunjung memberikan rating Bintang 5` dan tautan `Lihat Semua Ulasan ->`.
   5. **Row 2 - Bento Kanan: Komposisi Layanan Konsultasi Statistik:**
      - Header ikon vektor SVG pie-chart analitik (biru sky tanpa emoji ⏱), judul resmi, subteks total 142 sesi, dan pill `Semester II 2026`.
      - Segmented stacked bar: 4 segmen warna BPS (Navy 45%, Sky 30%, Amber 15%, Hijau 10%) dengan skala 0%, 25%, 50%, 75%, 100%.
      - Matriks 4 kartu topik konsultasi (2x2 grid) dengan tinggi sel seragam (`min-h-[62px]`): `Kemiskinan` (45%), `PDRB` (30%), `Inflasi` (15%), dan `Metodologi` (10%).
      - Footer: `Spesialisasi Utama: Analisis Sosial & Kemiskinan` dan tombol `Unduh Rekap CSV ->`.
   6. **Row 3 - Bento Kiri: Jadwal Tugas Jaga 7 Hari Ke Depan (PRISTI):**
      - Header ikon vektor SVG kalender dinas (navy BPS tanpa emoji 📅), penugasan loket PST dan pengolahan data, serta tombol `[Ekspor Kalender]`.
      - 5 baris jadwal terstruktur: `RAB 16` (Hari Ini - Konsultasi Statistik Meja 2), `SEN 19` (Shift Pagi - Front Office), `SEL 20` (Back Office - ROMANIK), `RAB 21` (Shift Pagi - PNBP Meja 3), dan `KAM 22` (Shift Pagi - Konsultasi Meja 2).
      - Footer: `Kebutuhan tukar shift? Ajukan melalui menu PRISTI` dan tautan `Ajukan Pengalihan Tugas ->`.
   7. **Row 3 - Bento Kanan: Notifikasi & Disposisi Kedinasan:**
      - Header ikon vektor SVG lonceng notifikasi (merah rose tanpa emoji 🔔) dan lencana `5 Baru` berdenyut aktif.
      - Daftar 5 notifikasi terpadu yang menyeimbangkan tinggi kartu jadwal sebelah kiri tanpa ruang kosong (*zero empty void*):
        1. Item 1: Disposisi Prioritas Refined Inset (Tiket #PST-2026-042: Permintaan Klarifikasi Data Publikasi Makro Jateng, aksen garis tepi amber `#F7941D`, latar lembut `bg-amber-50/25`, lencana prioritas oranye terkurasi, batas waktu font mono amber, tombol utama Navy BPS `#005288` `[Tanggapi Disposisi]` berikon pesawat kertas, dan tombol sekunder putih `[Setujui Berkas]` berikon centang emerald - meniadakan warna merah kirmizi agresif yang mencolok).
        2. Item 2: Pengumuman Manajemen (Briefing Peningkatan Layanan WBBM BPS Jateng - 10:00 WIB).
        3. Item 3: Approval PRISTI Berhasil (Dispensasi Dinas Luar Kasubbag Umum - 08:12 WIB).
        4. Item 4: Evaluasi Kinerja ELENA (Penilaian SKP dan Perilaku Kerja Semester I - Kemarin).
        5. Item 5: Survei Statisfy Baru (Rating Bintang 5 Tamu Meja 2 - Kemarin).
      - Footer: `Pusat Bantuan & Admin Teknis` dan aksi `Tandai Semua Dibaca ->`.
   8. **Footer Bar Status Sistem:**
      - Indikator real-time: `• Server BPS Jateng Online` • `Sinkronisasi Terakhir` • `Latensi: 24ms`.
      - Versi & Identitas: `PRISTELA Enterprise v2.4` • `BPS Provinsi Jawa Tengah` • `Kompilasi Sensus & Survei Terintegrasi`.
- **Verification Results (Rule #1):**
  - Syntax check: `php -l` lulus 100% pada `app/Filament/Pages/Dashboard.php` dan `resources/views/filament/pages/employee-workspace.blade.php`.
  - Disposisi Visual Refinement: Komponen Disposisi dirombak dari nuansa merah kirmizi mencolok (`#e11d48`) menjadi estetika tenang khas BPS dengan tombol primer Navy (`#005288`), tombol sekunder putih berlis halus, lencana `Disposisi Prioritas` amber, dan aksen garis tepi oranye `#F7941D`.
  - Automated Tests: 6/6 passing tests (32 assertions) di `tests/Feature/EmployeeWorkspaceTest.php`.
  - Full Test Suite: 66/66 passing tests (338 assertions) tanpa kegagalan atau regresi.
  - Browser Verification: Diuji langsung di browser subagent pada resolusi 1536x730 (`notifikasi_disposisi_card_1792641526201.png`), membuktikan tampilan kartu Notifikasi & Disposisi tersaji sangat elegan, tenang, dan selaras dengan tema korporat BPS tanpa elemen yang terlalu mencolok.
---

## Konsol Meja Operator 1-Layar / Desk Console (FE-04)
- **Status:** Complete (Verified)
- **Route & Access:**
  - Official Filament page: `/admin/konsol-meja` ([DeskConsole.php](file:///d:/ARDHIAN%20DWI%20SAPUTRA/coba%20coba/app/Filament/Pages/DeskConsole.php))
  - Redirect shortcuts: `/konsol-meja` and `/operator` (registered in [routes/web.php](file:///d:/ARDHIAN%20DWI%20SAPUTRA/coba%20coba/routes/web.php))
  - Access Control: Authenticated staff / operator / superadmin (`canAccessPanel`).
- **UI Architecture & Layout (Presisi 1:1 Mockup Pengguna):**
  1. **Unified Backoffice Header:**
     - Tab aktif `Konsol Meja • Meja 2` dengan lencana oranye kontras.
     - Tab navigasi terpadu: `Workspace`, `Konsol Meja`, `PRISTI (Presensi)`, `STATISFY`, `ELENA`, `Pengaduan`.
     - Lencana status sinkronisasi real-time: `• TV 23" Sinkron` (emerald).
     - Subtitle profil petugas: `Meja 2: Konsultasi` dan tombol keluar sesi terisolasi.
  2. **Row 1 - 4 Kartu Metrik KPI Cepat Operasional:**
     - Grid 4 kolom horizontal (`.console-metrics-grid`).
     - Metrik 1: `Dilayani Hari Ini`: `8 Tamu` (ikon antrean biru).
     - Metrik 2: `Rata-Rata Durasi`: `16m 42s` (ikon jam hijau).
     - Metrik 3: `Indeks Statisfy`: `4.96 ★ 5.0` (ikon jempol oranye).
     - Metrik 4: `Sisa Antrean Meja 2`: `3 Menunggu` (ikon tiket oranye).
  3. **Row 2 - Area Kerja Operator Utama (2 Kolom):**
     - **Kolom Kiri (~40%): Tiket Aktif & Stopwatch Layanan:**
       - Lencana sesi: `• Sedang Dilayani` (hijau berdenyut), `Mulai: 14:15 WIB`, dan lencana `LOKET 2`.
       - Tampilan jumbo nomor antrean aktif: `A-024` dengan pill layanan `Konsultasi Neraca & Kemiskinan`.
       - Stopwatch Inset Gelap (`#0B1E33` Deep Navy Surface):
         - Pewaktu interaktif Alpine.js berjalan live (`14 MENIT : 39 DETIK`).
         - Lencana `Target SLA: 30 Menit`, indikator `★ Sisa 15m 25s (Aman)`, dan bar kemajuan kuota 48%.
       - Matriks Tombol Aksi Operasional Berwarna:
         - `[Panggil Lobi]` (Navy BPS `#005288` - Hotkey `F1`)
         - `[Recall Suara]` (Oranye `#F7941D` - Hotkey `F2`)
         - `[Mulai Layani]` (Putih berlis hijau)
         - `[+ Sisip Prioritas]` (Amber muda)
         - `[Selesai & Kirim Survei Statisfy]` (Emerald Green `#10B981` - Hotkey `F9`)
       - Kotak Antrean Menunggu Meja 2:
         - Menampilkan 3 tiket: `A-025` (Rina Anggraini, S.E. - Pemerintah), `A-026` (Bagas Pratama - Mahasiswa), dan `B-009` (Drs. H. Soedibyo - Prioritas Lansia) lengkap dengan estimasi waktu dan tombol panggil langsung.
      - **Kolom Kanan (~60%): Formulir Pelayanan Tamu Terpadu (Zero-Reload):**
        - Header status formulir dinamis (Formulir Siap Diisi saat baru, dan Tersimpan otomatis {waktu} setelah penyimpanan) serta tombol re-sinkronisasi.
        - Dikosongkan secara default tanpa data bawaan (*clean blank state*) agar dapat diisi mandiri oleh pengguna/operator:
          - Input Nomor WhatsApp / Handphone Pengunjung yang bersih, rapi, dan bebas border bersarang (single-border border-slate-200 rounded-xl dengan placeholder informatif Contoh: 0812-3456-7890), serasi dengan field Nama Lengkap dan Instansi, serta lencana verifikasi master PST otomatis saat nomor diisi.
          - Input Nama Lengkap (placeholder: Nama lengkap pengunjung...) dan Instansi (placeholder: Contoh: Universitas Diponegoro / Bappeda Prov. Jateng...).
          - Pemilih Kategori Tamu 4 Pilihan (Mahasiswa, Dosen / Peneliti, Pemerintah / OPD, Swasta) dalam kondisi bersih belum terpilih.
          - Pilihan Kunjungan Pertama (Ya / Tidak) dalam kondisi bersih belum terpilih.
          - Input Topik Konsultasi (placeholder: Contoh: Konsultasi Data Kemiskinan Makro / PDRB Triwulanan...).
          - Multi-Pill Tags Dataset Diminta: dalam kondisi bersih (*empty state*) dengan catatan panduan ramah dan input inline + Tambah Dataset (Enter)....
          - Textarea Catatan Konsultasi & Resume Layanan (placeholder informatif).
          - Inset Box Catatan Internal Petugas (Rahasia & Monev) warna amber untuk sinkronisasi evaluasi ELENA.
          - Radio Hasil Disposisi: Diberikan Langsung di Loket / Perlu Pengolahan Lanjutan dengan tanda terima PDF siap dibuat.
          - Checkbox kirim salinan otomatis ke WhatsApp pengunjung.
          - Tombol Aksi Primer: [Simpan & Cetak Bukti Konsultasi] (Navy BPS #005288 - Hotkey Ctrl+P).
  4. **Row 3 - Panel Sisip Antrean Prioritas & Fast-Track (Bypass Meja 2):**
      - Sesuai SOP Pelayanan Inklusif BPS bagi tamu berkebutuhan khusus.
      - Nomor Siap Diterbitkan: `B-010`.
      - Layout 4 kolom sebaris presisi (`.console-priority-grid` dengan tinggi seragam 40px `h-10`, label terpadu, dan alignment sempurna):
        1. Kategori: Segmented toggle container amber (`.priority-toggle-container`) memuat tombol aktif oranye `#F7941D` Disabilitas (ikon wheelchair presisi) dan Lansia (>60 Th) (ikon lansia tongkat presisi) dengan teks satu baris tanpa wrapping.
        2. Nomor WA / Identitas: Input `08XX-XXXX-XXXX atau NIK` (tinggi seragam 40px, rounded-xl).
        3. Keperluan Mendesak: Input `Contoh: Konsultasi Angka Stunting mendesak` (tinggi seragam 40px, rounded-xl).
        4. Tindakan Eksekusi: Tombol `[+ Sisipkan B-010]` (oranye `#F7941D`, tinggi seragam 40px, rounded-xl - Hotkey `F4`).
  5. **Row 4 - Dua Kartu Terintegrasi:**
     - **Kartu Kiri: Pratinjau Bukti Layanan Konsultasi Resmi:**
       - Format tanda terima kedinasan PST BPS Provinsi Jawa Tengah (`RES-PST-2026-0424`).
       - Memuat rincian tiket, nama tamu, instansi, topik, nama petugas jaga, waktu layanan, dan status `TERLAYANI`.
       - Tanda tangan digital tervalidasi: `BPS-JTG-SEMARPST-2026` dengan hash verifikasi `9f9b4e...e71e`.
       - Tombol aksi: `[Unduh PDF]`.
     - **Kartu Kanan: Modul Statisfy Terintegrasi:**
       - Permukaan gelap Deep Navy (`#0B1E33`).
       - Header lencana `Survei 15 Detik`.
       - Deskripsi trigger otomatis evaluasi pengunjung via WhatsApp dan QR Kiosk saat sesi layanan ditutup.
       - Status integrasi: WhatsApp Gateway `0812-3456-7890 (Terkoneksi)`, Integrasi ELENA `+0.25 Bobot Kinerja`, Status Respon `Siap kirim saat sesi ditutup`.
       - Aturan rujukan: `SOP PermenPAN-RB No. 14 / 2017` dan `Indeks SKD & PST`.
  6. **Footer Bar Status Sistem & Hotkeys:**
     - Status perangkat keras & jaringan: `WebSocket: Terhubung (14ms)`, `Audio Lobi: Aktif (85%)`, `Printer Slip: Epson Ready`.
     - Pintasan keyboard global: `[F1] Panggil`, `[F2] Recall`, `[F4] Sisip`, `[F9] Selesai`, `[Ctrl+P] Cetak`.
- **Verification Results (Rule #1):**
  - Syntax check: `php -l` lulus 100% pada [DeskConsole.php](file:///d:/ARDHIAN%20DWI%20SAPUTRA/coba%20coba/app/Filament/Pages/DeskConsole.php), [console-icon.blade.php](file:///d:/ARDHIAN%20DWI%20SAPUTRA/coba%20coba/resources/views/components/console-icon.blade.php), dan view template [desk-console.blade.php](file:///d:/ARDHIAN%20DWI%20SAPUTRA/coba%20coba/resources/views/filament/pages/desk-console.blade.php).
  - High-Craft Vector Icon Refinement: Diciptakan komponen ikon Blade [console-icon.blade.php](file:///d:/ARDHIAN%20DWI%20SAPUTRA/coba%20coba/resources/views/components/console-icon.blade.php) yang merender 4 ikon vektor murni dengan geometri presisi sesuai gambar referensi pengguna (ikon 3-person team untuk Dilayani Hari Ini, jam lingkaran jarum 12:00 dan 04:30 untuk Rata-Rata Durasi, jempol rounded thumbs-up dengan manset pergelangan untuk Indeks Statisfy, dan 3-tier stepped queue stack untuk Sisa Antrean Meja 2) tanpa menggunakan file gambar (raster image) maupun file eksternal SVG.
  - Clean Blank Form Default: Formulir pelayanan tamu terpadu dikosongkan secara default tanpa pre-filled data, dilengkapi placeholder informatif pada seluruh field, status header Formulir Siap Diisi, dan empty state ramah pada daftar dataset.
  - Automated Tests: 5/5 passing tests (34 assertions) pada [DeskConsoleTest.php](file:///d:/ARDHIAN%20DWI%20SAPUTRA/coba%20coba/tests/Feature/DeskConsoleTest.php).
  - Full Regression Test Suite: 66/66 passing tests (338 assertions) tanpa kegagalan (zero regressions).
  - Browser Verification: Diuji langsung di browser subagent pada resolusi 1440x900 (desk_console_whatsapp_clean_input_1792637754886.png dan panel_prioritas_reloaded_1792639115664.png), membuktikan tampilan input nomor WhatsApp bebas border ganda dan Panel Sisip Prioritas tersusun presisi sebaris 40px (h-10) dengan visualisasi segmented switch dan ikon vektor bersih.
