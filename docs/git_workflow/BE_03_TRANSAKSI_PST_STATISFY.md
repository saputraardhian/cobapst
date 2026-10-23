# [BE-03] Migration Transaksi PST & Statisfy (5 Tabel)

Dokumentasi alur Git Workflow untuk tiket **BE-03** (Transaksi Layanan PST dan Survei Kepuasan Statisfy AI).

---

## 1. Spesifikasi GitHub Issue

- **Judul Issue:** `[BE-03] Migration Transaksi PST & Statisfy (5 Tabel)`
- **Labels:** `backend`, `p0-critical`, `database`
- **Tautan Buat Issue:** [New GitHub Issue](https://github.com/whoNann/semar-pst/issues/new)

### Deskripsi Issue (Salin ke GitHub Issue):
```markdown
### Latar Belakang
Mengintegrasikan siklus transaksi pengunjung PST, tiket antrian layanan, survei kepuasan 15 detik Statisfy, dan analitik AI eksekutif dari sistem lama `pst` dan `statisfy`.

### Spesifikasi Teknis (5 Tabel)
1. `pst_visitors`: Buku tamu pengunjung PST dengan auto-lookup cepat pada nomor HP (`phone_number`).
2. `pst_visitor_logs`: Riwayat kunjungan buku tamu dan catatan keperluan konsultasi.
3. `pst_service_tickets`: Tiket antrian lobi dengan QR token dinamis, status layanan, dan petugas pelayan.
4. `statisfy_surveys`: Survei kepuasan 15 detik dengan relasi one-to-one ke tiket layanan dan rating skor 1-5.
5. `statisfy_ai_analyses`: Penyimpanan hasil agregasi sentimen dan analitik AI eksekutif BPS.

### Kriteria Selesai (Definition of Done)
- [x] Migrasi 5 tabel transaksi MySQL berhasil dijalankan.
- [x] Model Eloquent dan Factories lengkap dengan bilingual ID/EN DocBlocks.
- [x] Feature test otomatis `PstAndStatisfyTransactionsTest` 100% PASS.
```

---

## 2. Alur Git & Perintah Terminal

### A. Buat Branch
```bash
git checkout -b feat/BE-03-transaksi-pst-statisfy
```

### B. Commit Perubahan
```bash
git add .
git commit -m "feat(database): create pst and statisfy transaction tables and models (BE-03)"
```

### C. Push ke GitHub
```bash
git push -u origin feat/BE-03-transaksi-pst-statisfy
```

---

## 3. Spesifikasi Pull Request (PR)

- **Judul PR:** `feat(pst-statisfy): Transaksi Antrian PST dan Survei Statisfy AI (BE-03)`
- **Base Branch:** `master` <- **Compare Branch:** `feat/BE-03-transaksi-pst-statisfy`
- **Tautan 1-Klik Buka PR:** [Compare & Open PR on GitHub](https://github.com/whoNann/semar-pst/compare/master...feat/BE-03-transaksi-pst-statisfy?expand=1)

### Deskripsi PR (Salin ke GitHub Pull Request):
```markdown
## Ringkasan Perubahan
- Menambahkan skema migrasi untuk `pst_visitors`, `pst_visitor_logs`, `pst_service_tickets`, `statisfy_surveys`, dan `statisfy_ai_analyses`.
- Membangun model Eloquent dan Factories untuk seluruh alur transaksi PST & Statisfy.
- Memastikan relasi cascading dan perlindungan integritas token QR tiket.

## Checklist Pengujian
- [x] `php artisan test --filter=PstAndStatisfyTransactionsTest` PASS (4 tests, 12 assertions).
- [x] Validasi compound FK ke users dan pst_counters berjalan mulus.

Closes #3
```
