# [BE-07] ETL Script 2: Normalisasi Nama Pegawai ke NIP

Dokumentasi alur Git Workflow untuk tiket **BE-07** (Normalisasi & Resolusi Nama Pegawai, Username, dan Gelar Akademik ke `users.id`).

---

## 1. Spesifikasi GitHub Issue

- **Judul Issue:** `[BE-07] ETL Script 2: Normalisasi Nama Pegawai ke NIP`
- **Labels:** `backend`, `p0-critical`, `migration`
- **Tautan Buat Issue:** [New GitHub Issue](https://github.com/whoNann/semar-pst/issues/new)

### Deskripsi Issue (Salin ke GitHub Issue):
```markdown
### Latar Belakang
Pada 4 sistem lama (`elena`, `pst`, `pristi`, `statisfy`), pencatatan identitas operator atau pegawai yang bertugas tidak menggunakan Foreign Key terpusat, melainkan string bebas:
- `elena`: kolom `nama_pegawai` (varchar 50) dengan/tanpa variasi gelar akademik (contoh: "Indah Purnamasari S.E", "Dwi Agus Wahyu Riyadi A.Md, S.Kom.").
- `pst`: kolom `created_by` (varchar 64) berisi username / email prefix / nickname (contoh: "nanang", "yuli.purwita", "indahpur", "herpas", "anargi").
- `pristi`: kolom `nama_petugas` di jadwal presensi.
- `statisfy`: kolom `nama_pegawai` di `skm_kepuasan`.

Diperlukan service resolver dan artisan command (`php artisan semar:etl-normalize-names`) untuk memetakan string nama/username tersebut ke `users.id` agar transaksi historis tidak yatim (*orphan*).

### Spesifikasi Teknis
1. **Engine Pemetaan Cerdas (EmployeeNameResolver):**
   - Pencocokan Langsung via NIP (18 digit) / NIP Lama (9 digit).
   - Pencocokan Username / Email Prefix (`users.email` atau `skm_petugas_pst.nick`).
   - Normalisasi Nama Bersih (pembersihan gelar akademik otomatis seperti SST, S.Si, S.E, A.Md, M.Ec.Dev, MT, M.Stat).
   - Kamus Alias Eksplisit (e.g. `nanang` -> Yun Restianto, `herpas` -> Hermawan Prasetyo).
2. **Kebijakan ETL & Audit Safety (Golden Rule #7):**
   - Opsi `--dry-run` untuk menampilkan ringkasan resolusi nama dan persentase keberhasilan tanpa mutasi.
   - Pencatatan seluruh nama yang tidak terpetakan (*unmapped anomalies*) ke `storage/logs/etl_unmapped_names.log`.
3. **Automated Feature Test:**
   - Memverifikasi ketepatan pemetaan 100% pada sampel nama kritis dan alias username.

### Kriteria Selesai (Definition of Done)
- [ ] Service `EmployeeNameResolver` terimplementasi dengan algoritma pembersihan gelar dan kamus alias.
- [ ] Command `php artisan semar:etl-normalize-names` terimplementasi dengan dukungan `--dry-run`.
- [ ] File log audit unmapped `storage/logs/etl_unmapped_names.log` terintegrasi.
- [ ] Feature test `EtlNormalizeNamesTest` lolos 100%.
```

---

## 2. Alur Git & Perintah Terminal

### A. Buat Branch
```bash
git checkout -b feat/BE-07-etl-normalization
```

### B. Commit Perubahan (Setelah Koding & Test Selesai)
```bash
git add .
git commit -m "feat(etl): implement employee name and nickname resolver with dry-run (refs #14)"
```

### C. Push ke GitHub
```bash
git push -u origin feat/BE-07-etl-normalization
```

---

## 3. Spesifikasi Pull Request (PR)

- **Judul PR:** `feat(etl): Normalisasi Nama Pegawai dan Alias Username ke NIP (BE-07)`
- **Base Branch:** `master` <- **Compare Branch:** `feat/BE-07-etl-normalization`
- **Tautan 1-Klik Buka PR:** [Compare & Open PR on GitHub](https://github.com/whoNann/semar-pst/compare/master...feat/BE-07-etl-normalization?expand=1)

### Deskripsi PR (Salin ke GitHub Pull Request):
```markdown
## Ringkasan Perubahan
- Mengimplementasikan `App\Services\Etl\EmployeeNameResolver` untuk normalisasi nama bergelar, username, dan alias petugas loket ke `users.id`.
- Mengimplementasikan command `php artisan semar:etl-normalize-names` dengan dukungan `--dry-run`.
- Menyertakan pencatatan audit unmapped ke `storage/logs/etl_unmapped_names.log`.
- Menambahkan test feature `EtlNormalizeNamesTest`.

## Checklist Pengujian
- [ ] `php artisan semar:etl-normalize-names --dry-run` berjalan sukses.
- [ ] `php artisan test --filter=EtlNormalizeNamesTest` PASS.

Closes #14
```
