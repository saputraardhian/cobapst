# [BE-02] Migration Master Pegawai (`users`) & Loket (`pst_counters`)

Dokumentasi alur Git Workflow untuk tiket **BE-02** (Peleburan Master Pegawai BPS dan Meja Loket PST).

---

## 1. Spesifikasi GitHub Issue

- **Judul Issue:** `[BE-02] Migration Master Pegawai (users) & Loket (pst_counters)`
- **Labels:** `backend`, `p0-critical`, `database`
- **Tautan Buat Issue:** [New GitHub Issue](https://github.com/whoNann/semar-pst/issues/new)

### Deskripsi Issue (Salin ke GitHub Issue):
```markdown
### Latar Belakang
Mengintegrasikan master pegawai yang sebelumnya terpisah di tabel legacy `dash_pegawai` (Elena & Pristi), `elena_m_petugas`, dan `skm_petugas_pst` ke dalam tabel tunggal `users`. Serta membuat master meja loket `pst_counters` dengan relasi operator aktif.

### Spesifikasi Teknis
1. Skema `users`: NIP Baru (18 digit, unique), NIP Lama (9 digit), Nama Lengkap, Nama Tanpa Gelar, No WA, Unit Kerja, Kode Tim Elena, Kode Hitung, Is Aktif.
2. Skema `pst_counters`: Nama Meja, Nomor Meja, Slug QR Statis, Active Operator ID (FK ke users), Status Buka Meja.
3. Model Eloquent: `User` (HasRoles, FilamentUser), `PstCounter` dengan relasi `activeOperator()`.

### Kriteria Selesai (Definition of Done)
- [x] Migration MySQL `users` dan `pst_counters` berhasil dijalankan.
- [x] Model Eloquent dilengkapi bilingual ID/EN DocBlocks dan strict typing.
- [x] Feature test otomatis `MasterUsersAndCountersTest` 100% PASS.
```

---

## 2. Alur Git & Perintah Terminal

### A. Buat Branch
```bash
git checkout -b feat/BE-02-master-users-counters
```

### B. Commit Perubahan
```bash
git add .
git commit -m "feat(database): create master users and pst_counters tables with MySQL config (BE-02)"
```

### C. Push ke GitHub
```bash
git push -u origin feat/BE-02-master-users-counters
```

---

## 3. Spesifikasi Pull Request (PR)

- **Judul PR:** `feat(database): Master Pegawai dan Loket PST (BE-02)`
- **Base Branch:** `master` <- **Compare Branch:** `feat/BE-02-master-users-counters`
- **Tautan 1-Klik Buka PR:** [Compare & Open PR on GitHub](https://github.com/whoNann/semar-pst/compare/master...feat/BE-02-master-users-counters?expand=1)

### Deskripsi PR (Salin ke GitHub Pull Request):
```markdown
## Ringkasan Perubahan
- Menambahkan skema migrasi terpadu untuk `users` dan `pst_counters`.
- Membangun model Eloquent `User` dan `PstCounter` beserta relasi bolak-balik operator aktif.
- Menambahkan Model Factories untuk pengujian otomatis.

## Checklist Pengujian
- [x] `php artisan test --filter=MasterUsersAndCountersTest` PASS (2 tests, 9 assertions).
- [x] Integritas referensial foreign key MySQL teruji tanpa galat.

Closes #2
```
