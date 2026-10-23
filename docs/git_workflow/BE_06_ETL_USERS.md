# [BE-06] ETL Script 1: Deduplikasi Master Pegawai

Dokumentasi alur Git Workflow untuk tiket **BE-06** (Deduplikasi Data Master Pegawai Lintas 5 Basis Data Legacy).

---

## 1. Spesifikasi GitHub Issue

- **Judul Issue:** `[BE-06] ETL Script 1: Deduplikasi Master Pegawai BPS`
- **Labels:** `backend`, `p0-critical`, `migration`
- **Tautan Buat Issue:** [New GitHub Issue](https://github.com/whoNann/semar-pst/issues/new)

### Deskripsi Issue (Salin ke GitHub Issue):
```markdown
### Latar Belakang
Mengembangkan perintah migrasi ETL (`php artisan semar:etl-users`) untuk mengimpor, menormalisasi, dan menggabungkan profil pegawai dari 5 basis data warisan ke tabel tunggal `users`.

### Spesifikasi Teknis
1. Sumber Data Primer:
   - `elena` (`dash_pegawai`, `elena_m_petugas`)
   - `pristi` (`dash_pegawai`)
   - `pengaduan.jateng.pro` (`t_pengaduan_admin`)
   - `statisfy` (`skm_petugas_pst`)
2. Kunci Pencocokan: `nipbaru` (18 digit) sebagai primary matching key.
3. Aturan Kebijakan ETL (Aturan #7):
   - Mendukung opsi `--dry-run` untuk menampilkan ringkasan baris yang dipindai, dicocokkan, dan unmapped sebelum menulis ke database.
   - Menyimpan seluruh anomali/unmapped records ke `storage/logs/etl_unmapped_users.log`.

### Kriteria Selesai (Definition of Done)
- [ ] Command `php artisan semar:etl-users` terimplementasi dengan opsi `--dry-run`.
- [ ] Log audit unmapped tersimpan rapi di `storage/logs/etl_unmapped_users.log`.
- [ ] Automated feature test `EtlUsersTest` lolos 100%.
```

---

## 2. Alur Git & Perintah Terminal

### A. Buat Branch
```bash
git checkout -b feat/BE-06-etl-users
```

### B. Commit Perubahan (Setelah Koding & Test Selesai)
```bash
git add .
git commit -m "feat(etl): implement user deduplication command with dry-run (refs #7)"
```

### C. Push ke GitHub
```bash
git push -u origin feat/BE-06-etl-users
```

---

## 3. Spesifikasi Pull Request (PR)

- **Judul PR:** `feat(etl): Deduplikasi Master Pegawai Lintas Sistem Legacy (BE-06)`
- **Base Branch:** `master` <- **Compare Branch:** `feat/BE-06-etl-users`
- **Tautan 1-Klik Buka PR:** [Compare & Open PR on GitHub](https://github.com/whoNann/semar-pst/compare/master...feat/BE-06-etl-users?expand=1)

### Deskripsi PR (Salin ke GitHub Pull Request):
```markdown
## Ringkasan Perubahan
- Mengimplementasikan command `php artisan semar:etl-users` untuk deduplikasi pegawai BPS.
- Menambahkan opsi `--dry-run` dan sistem audit log di `storage/logs/etl_unmapped_users.log`.
- Menyertakan unit/feature testing untuk memverifikasi proses impor data.

## Checklist Pengujian
- [ ] `php artisan semar:etl-users --dry-run` berjalan sukses.
- [ ] `php artisan test --filter=EtlUsersTest` PASS.

Closes #7
```
