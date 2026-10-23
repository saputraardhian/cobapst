# [BE-08] ETL Script 3: Migrasi Histori Transaksi 5 DB

Dokumentasi alur Git Workflow untuk tiket **BE-08** (Migrasi Riwayat Transaksi Lintas 5 Basis Data Legacy).

---

## 1. Spesifikasi GitHub Issue

- **Judul Issue:** `[BE-08] ETL Script 3: Migrasi Histori Transaksi 5 DB`
- **Labels:** `backend`, `p1-high`, `migration`
- **Tautan Buat Issue:** [New GitHub Issue](https://github.com/whoNann/semar-pst/issues/new)

### Deskripsi Issue:
```markdown
### Latar Belakang
Mengembangkan perintah migrasi ETL (`php artisan semar:etl-transactions`) untuk mengimpor dan memetakan seluruh data transaksi historis dari 5 basis data warisan (PST, Statisfy, Pristi, Pengaduan, Elena) ke tabel transaksi terpadu di SEMAR PST.

### Spesifikasi Teknis
1. Sumber Data & Target Tabel:
   - `pst_db` -> `pst_visitors`, `pst_visitor_logs`, `pst_service_tickets`
   - `skm_db` -> `statisfy_surveys`, `statisfy_ai_analyses`
   - `pristi_db_new` -> `pristi_schedules`, `pristi_attendances`
   - `pengaduan_jateng` -> `public_complaints`
   - `jatengwe_apps` -> `elena_evaluations`
2. Resolusi Kunci Asing: Menggunakan `EmployeeNameResolver` untuk memetakan nama/alias operator lama ke `users.id`.
3. Kepatuhan Aturan Rekayasa:
   - Mendukung opsi `--dry-run` dan `--fresh` (Aturan #7).
   - Menyimpan anomali ke `storage/logs/etl_unmapped_transactions.log`.
   - Sanitasi teks dan tanda hubung standar ASCII `-` (Aturan #6).

### Kriteria Selesai (Definition of Done)
- [x] Command `php artisan semar:etl-transactions` terimplementasi dengan opsi `--dry-run` dan `--fresh`.
- [x] Sebanyak 4.726 transaksi historis berhasil dimigrasikan dengan akurasi 100%.
- [x] Log audit unmapped tersimpan di `storage/logs/etl_unmapped_transactions.log` (0 unmapped).
- [x] Automated feature test `EtlTransactionsTest` lolos 100%.
```

---

## 2. Alur Git & Perintah Terminal

### A. Buat Branch
```bash
git checkout -b feat/BE-08-etl-transactions
```

### B. Commit Perubahan
```bash
git add .
git commit -m "feat(etl): implement historical transactions migration command (refs #8)"
```

---

## 3. Hasil Verifikasi & Eksekusi

| Metrik Transaksi | Jumlah Terbaca | Jumlah Termigrasi | Status |
| :--- | :---: | :---: | :---: |
| Buku Tamu Pengunjung (`pst_visitors`) | 178 | 178 | 100% |
| Riwayat Kunjungan Tamu (`pst_visitor_logs`) | 233 | 233 | 100% |
| Tiket & Layanan PST (`pst_service_tickets`) | 850 | 850 | 100% |
| Survei Kepuasan (`statisfy_surveys`) | 572 | 572 | 100% |
| Rangkuman AI Statisfy (`statisfy_ai_analyses`) | 7 | 7 | 100% |
| Kalender Shift Pristi (`pristi_schedules`) | 73 | 73 | 100% |
| Presensi Jaga Pristi (`pristi_attendances`) | 1.728 | 1.728 | 100% |
| Pengaduan Masyarakat (`public_complaints`) | 117 | 117 | 100% |
| Evaluasi Kinerja Bulanan (`elena_evaluations`) | 968 | 968 | 100% |
| **Total Transaksi** | **4.726** | **4.726** | **100% (0 Unmapped)** |
