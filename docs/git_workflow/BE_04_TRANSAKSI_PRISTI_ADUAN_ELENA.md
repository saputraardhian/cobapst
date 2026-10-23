# [BE-04] Migration Transaksi Pristi, Aduan & Elena (5 Tabel)

Dokumentasi alur Git Workflow untuk tiket **BE-04** (Transaksi Jadwal Jaga Loket, Pengaduan Publik, dan Evaluasi Elena).

---

## 1. Spesifikasi GitHub Issue

- **Judul Issue:** `[BE-04] Migration Transaksi Pristi, Aduan & Elena (5 Tabel)`
- **Labels:** `backend`, `p0-critical`, `database`
- **Tautan Buat Issue:** [New GitHub Issue](https://github.com/whoNann/semar-pst/issues/new)

### Deskripsi Issue (Salin ke GitHub Issue):
```markdown
### Latar Belakang
Mengintegrasikan transaksi jadwal loket & presensi (Pristi), aduan masyarakat (Pengaduan Jateng), serta evaluasi kinerja bulanan petugas (Elena) melengkapi total 12 tabel database inti SEMAR PST.

### Spesifikasi Teknis (5 Tabel)
1. `pristi_schedules`: Kalender shift jaga loket PST dengan fitur tukar shift (`swapped_with_user_id`).
2. `pristi_attendances`: Presensi petugas lobi, pencatatan waktu aktual, dan kalkulasi keterlambatan.
3. `pristi_monev_reports`: Laporan evaluasi monev 9 dimensi pelayanan kab/kota se-Jawa Tengah.
4. `public_complaints`: Registrasi aduan masyarakat dengan tracking code unik format `ADU-YYYY-XXXX` dan flag penalti Elena.
5. `elena_evaluations`: Matriks evaluasi kinerja bulanan petugas loket berbobot 6 dimensi dan sertifikat.

### Kriteria Selesai (Definition of Done)
- [x] Migrasi 5 tabel operasional MySQL berhasil dijalankan.
- [x] 12 model Eloquent inti memiliki relasi valid dan bilingual ID/EN DocBlocks.
- [x] Feature test otomatis `PristiAduanElenaTransactionsTest` 100% PASS.
```

---

## 2. Alur Git & Perintah Terminal

### A. Buat Branch
```bash
git checkout -b feat/BE-04-transaksi-pristi-aduan-elena
```

### B. Commit Perubahan
```bash
git add .
git commit -m "feat(database): create pristi, public complaints, and elena evaluation tables and models (BE-04)"
```

### C. Push ke GitHub
```bash
git push -u origin feat/BE-04-transaksi-pristi-aduan-elena
```

---

## 3. Spesifikasi Pull Request (PR)

- **Judul PR:** `feat(pristi-aduan-elena): Transaksi Pristi, Aduan Publik, dan Elena (BE-04)`
- **Base Branch:** `master` <- **Compare Branch:** `feat/BE-04-transaksi-pristi-aduan-elena`
- **Tautan 1-Klik Buka PR:** [Compare & Open PR on GitHub](https://github.com/whoNann/semar-pst/compare/master...feat/BE-04-transaksi-pristi-aduan-elena?expand=1)

### Deskripsi PR (Salin ke GitHub Pull Request):
```markdown
## Ringkasan Perubahan
- Menuntaskan 12 tabel basis data inti dengan penambahan `pristi_schedules`, `pristi_attendances`, `pristi_monev_reports`, `public_complaints`, dan `elena_evaluations`.
- Membangun model Eloquent dan Factories lengkap dengan validasi compound key unik periode Elena.

## Checklist Pengujian
- [x] `php artisan test --filter=PristiAduanElenaTransactionsTest` PASS (5 tests, 18 assertions).
- [x] Full test suite (13 tests, 41 assertions 100% PASS).

Closes #4
```
