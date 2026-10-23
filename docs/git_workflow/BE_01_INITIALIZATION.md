# [BE-01] Inisialisasi Project & Package Utama

Dokumentasi alur Git Workflow untuk tiket **BE-01** (Inisialisasi Project SEMAR PST).

---

## 1. Spesifikasi GitHub Issue

- **Judul Issue:** `[BE-01] Inisialisasi Project & Package Utama SEMAR PST`
- **Labels:** `backend`, `p0-critical`, `setup`
- **Tautan Buat Issue:** [New GitHub Issue](https://github.com/whoNann/semar-pst/issues/new)

### Deskripsi Issue (Salin ke GitHub Issue):
```markdown
### Latar Belakang
SEMAR PST (Pelayanan Statistik Terpadu) BPS Provinsi Jawa Tengah adalah sistem monolith terintegrasi yang menyatukan 5 aplikasi warisan (PST, Statisfy, Pristi, Pengaduan, dan Elena). Diperlukan inisialisasi fondasi arsitektur Laravel 12 modern dan instalasi package inti.

### Spesifikasi Teknis
- Framework: Laravel 12.x pada runtime PHP 8.2 (Windows XAMPP).
- Admin Panel: FilamentPHP v3 (`filament/filament`).
- Reactive UI: Livewire 3 (`livewire/livewire`).
- Autorisasi: Spatie Laravel Permission & Filament Shield.
- Tooling: Maatwebsite Excel, Simple QRCode, DomPDF.

### Kriteria Selesai (Definition of Done)
- [x] Repositori Laravel 12 berhasil diinstal dan terkonfigurasi.
- [x] AdminPanelProvider Filament v3 terdaftar di bootstrap/providers.php.
- [x] Routing bawaan aktif tanpa konflik (`php artisan route:list`).
- [x] Automated test dasar lolos 100% (`php artisan test`).
```

---

## 2. Alur Git & Perintah Terminal

### A. Buat Branch
```bash
git checkout -b feat/BE-01-project-init
```

### B. Commit Perubahan
```bash
git add .
git commit -m "feat(core): initialize Laravel 12 with Filament v3, Livewire, Shield, and core tools (BE-01)"
```

### C. Push ke GitHub
```bash
git push -u origin feat/BE-01-project-init
```

---

## 3. Spesifikasi Pull Request (PR)

- **Judul PR:** `feat(core): Inisialisasi Project & Package Utama SEMAR PST (BE-01)`
- **Base Branch:** `master` <- **Compare Branch:** `feat/BE-01-project-init`
- **Tautan 1-Klik Buka PR:** [Compare & Open PR on GitHub](https://github.com/whoNann/semar-pst/compare/master...feat/BE-01-project-init?expand=1)

### Deskripsi PR (Salin ke GitHub Pull Request):
```markdown
## Ringkasan Perubahan
- Menginisiasi project monolith Laravel 12 untuk SEMAR PST BPS Provinsi Jawa Tengah.
- Menginstal FilamentPHP v3, Livewire 3, Spatie Permission, Filament Shield, Laravel Excel, Simple QRCode, dan DomPDF.
- Mengonfigurasi `AdminPanelProvider` dan mempublikasikan aset Filament.

## Checklist Pengujian
- [x] Syntax check PHP 8.2 lolos tanpa galat.
- [x] `php artisan test` 100% PASS (2 tests, 2 assertions).
- [x] Panel admin dapat diakses di `/admin`.

Closes #1
```
