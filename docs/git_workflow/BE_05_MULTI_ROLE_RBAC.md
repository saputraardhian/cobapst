# [BE-05] Konfigurasi SSO & Multi-Role RBAC

Dokumentasi alur Git Workflow untuk tiket **BE-05** (Konfigurasi 6 Peran Resmi BPS & Filament Shield Policies).

---

## 1. Spesifikasi GitHub Issue

- **Judul Issue:** `[BE-05] Konfigurasi SSO & Multi-Role RBAC 6 Peran Resmi BPS`
- **Labels:** `backend`, `p0-critical`, `security`
- **Tautan Buat Issue:** [New GitHub Issue](https://github.com/whoNann/semar-pst/issues/new)

### Deskripsi Issue (Salin ke GitHub Issue):
```markdown
### Latar Belakang
Membangun sistem otorisasi multi-peran dan SSO kedinasan untuk menjamin keamanan akses dan segregasi tugas (segregation of duties) sesuai tata kelola operasional BPS Provinsi Jawa Tengah.

### Spesifikasi Teknis
1. 6 Peran Resmi Kedinasan BPS:
   - `super_admin`: Bypass penuh via Gate::before ke seluruh modul.
   - `operator_pst`: CRUD tiket layanan & buku tamu, dilarang mengubah evaluasi Elena.
   - `admin_pristi`: CRUD jadwal jaga loket & monev 9 dimensi kab/kota.
   - `admin_pengaduan`: CRUD tindak lanjut aduan masyarakat.
   - `admin_elena`: CRUD penilaian kinerja SDM, view-only tiket/survei/aduan.
   - `eksekutif`: Akses baca global (viewAny & view) dasbor eksekutif pimpinan.
2. Filament Shield Policies: 13 file policy di app/Policies/ untuk seluruh 12 modul data.
3. Seeder Akun Uji Coba: 6 akun bawaan terverifikasi di DatabaseSeeder.

### Kriteria Selesai (Definition of Done)
- [x] Seeder hak akses `RoleAndPermissionSeeder` berhasil dieksekusi.
- [x] 13 file Policy terpasang dan auto-discover oleh Laravel 12.
- [x] Feature test otomatis `RbacAndPoliciesTest` 100% PASS.
- [x] Full test suite (20 tests, 96 assertions 100% PASS).
```

---

## 2. Alur Git & Perintah Terminal

### A. Buat Branch
```bash
git checkout -b feat/BE-05-multi-role-rbac
```

### B. Commit Perubahan
```bash
git add .
git commit -m "feat(auth): implement 6 official BPS roles, policies, and RBAC seeder (BE-05)"
```

### C. Push ke GitHub
```bash
git push -u origin feat/BE-05-multi-role-rbac
```

---

## 3. Spesifikasi Pull Request (PR)

- **Judul PR:** `feat(auth): Konfigurasi SSO & Multi-Role RBAC 6 Peran Resmi BPS (BE-05)`
- **Base Branch:** `master` <- **Compare Branch:** `feat/BE-05-multi-role-rbac`
- **Tautan 1-Klik Buka PR:** [Compare & Open PR on GitHub](https://github.com/whoNann/semar-pst/compare/master...feat/BE-05-multi-role-rbac?expand=1)

### Deskripsi PR (Salin ke GitHub Pull Request):
```markdown
## Ringkasan Perubahan
- Mengonfigurasi Spatie Laravel Permission dan Filament Shield.
- Mengimplementasikan 6 peran resmi kedinasan BPS dan seeder hak akses granular 12 modul data inti.
- Membangun 13 kelas policy di `app/Policies/` dengan kepatuhan segregasi tugas yang ketat.
- Mendaftarkan bypass Super Admin di `AppServiceProvider` via `Gate::before`.

## Checklist Pengujian
- [x] `php artisan test --filter=RbacAndPoliciesTest` PASS (7 tests, 55 assertions).
- [x] Full application test suite PASS (20 tests, 96 assertions 100% PASS).

Closes #5
```
