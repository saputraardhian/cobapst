<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Seeder Hak Akses Multi-Role & SSO Kedinasan (SEMAR PST).
 *
 * ID:
 * Menginisialisasi 6 peran resmi kedinasan BPS Provinsi Jawa Tengah (super_admin, operator_pst,
 * admin_pristi, admin_pengaduan, admin_elena, eksekutif), mendaftarkan hak akses (permissions)
 * untuk 12 modul data inti, serta menyediakan akun uji coba resmi per peran.
 *
 * EN:
 * Seeds official BPS multi-role RBAC structures (super_admin, operator_pst, admin_pristi,
 * admin_pengaduan, admin_elena, eksekutif), registers granular permissions across all 12 core
 * entity modules, and provisions verified default role testing accounts.
 */
class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Core Modules / Resources
        $modules = [
            'user',
            'pst_counter',
            'pst_visitor',
            'pst_visitor_log',
            'pst_service_ticket',
            'statisfy_survey',
            'statisfy_ai_analysis',
            'pristi_schedule',
            'pristi_attendance',
            'pristi_monev_report',
            'public_complaint',
            'elena_evaluation',
        ];

        // Standard CRUD action verbs
        $actions = [
            'view_any',
            'view',
            'create',
            'update',
            'delete',
        ];

        // Create all permissions
        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => "{$action}_{$module}",
                    'guard_name' => 'web',
                ]);
            }
        }

        // Additional administrative and Shield permissions
        Permission::firstOrCreate(['name' => 'view_any_role', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'view_role', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create_role', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'update_role', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete_role', 'guard_name' => 'web']);

        // 2. Define the 6 Official Roles
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $operatorPst = Role::firstOrCreate(['name' => 'operator_pst', 'guard_name' => 'web']);
        $adminPristi = Role::firstOrCreate(['name' => 'admin_pristi', 'guard_name' => 'web']);
        $adminPengaduan = Role::firstOrCreate(['name' => 'admin_pengaduan', 'guard_name' => 'web']);
        $adminElena = Role::firstOrCreate(['name' => 'admin_elena', 'guard_name' => 'web']);
        $eksekutif = Role::firstOrCreate(['name' => 'eksekutif', 'guard_name' => 'web']);

        // 3. Assign Permissions per Role

        // Role: super_admin gets all permissions
        $superAdmin->syncPermissions(Permission::all());

        // Role: operator_pst (Petugas Layanan PST)
        $operatorPst->syncPermissions([
            'view_any_pst_counter', 'view_pst_counter',
            'view_any_pst_visitor', 'view_pst_visitor', 'create_pst_visitor', 'update_pst_visitor',
            'view_any_pst_visitor_log', 'view_pst_visitor_log', 'create_pst_visitor_log', 'update_pst_visitor_log',
            'view_any_pst_service_ticket', 'view_pst_service_ticket', 'create_pst_service_ticket', 'update_pst_service_ticket',
            'view_any_statisfy_survey', 'view_statisfy_survey',
            'view_any_pristi_schedule', 'view_pristi_schedule', 'update_pristi_schedule',
            'view_any_pristi_attendance', 'view_pristi_attendance', 'create_pristi_attendance',
            'view_any_elena_evaluation', 'view_elena_evaluation',
        ]);

        // Role: admin_pristi (Pengelola Jadwal Shift & Laporan Monev Kab/Kota)
        $adminPristi->syncPermissions([
            'view_any_user', 'view_user',
            'view_any_pst_counter', 'view_pst_counter',
            'view_any_pristi_schedule', 'view_pristi_schedule', 'create_pristi_schedule', 'update_pristi_schedule', 'delete_pristi_schedule',
            'view_any_pristi_attendance', 'view_pristi_attendance', 'create_pristi_attendance', 'update_pristi_attendance', 'delete_pristi_attendance',
            'view_any_pristi_monev_report', 'view_pristi_monev_report', 'create_pristi_monev_report', 'update_pristi_monev_report', 'delete_pristi_monev_report',
        ]);

        // Role: admin_pengaduan (Tim Pengelola Pengaduan Publik & Whistleblowing)
        $adminPengaduan->syncPermissions([
            'view_any_user', 'view_user',
            'view_any_public_complaint', 'view_public_complaint', 'create_public_complaint', 'update_public_complaint', 'delete_public_complaint',
        ]);

        // Role: admin_elena (Tim Penilai SDM & Evaluasi Kinerja Petugas)
        $adminElena->syncPermissions([
            'view_any_user', 'view_user',
            'view_any_pst_service_ticket', 'view_pst_service_ticket',
            'view_any_statisfy_survey', 'view_statisfy_survey',
            'view_any_pristi_attendance', 'view_pristi_attendance',
            'view_any_public_complaint', 'view_public_complaint',
            'view_any_elena_evaluation', 'view_elena_evaluation', 'create_elena_evaluation', 'update_elena_evaluation', 'delete_elena_evaluation',
        ]);

        // Role: eksekutif (Pimpinan / Kepala BPS - View Only & Executive Dashboard)
        $eksekutif->syncPermissions([
            'view_any_user', 'view_user',
            'view_any_pst_counter', 'view_pst_counter',
            'view_any_pst_visitor', 'view_pst_visitor',
            'view_any_pst_visitor_log', 'view_pst_visitor_log',
            'view_any_pst_service_ticket', 'view_pst_service_ticket',
            'view_any_statisfy_survey', 'view_statisfy_survey',
            'view_any_statisfy_ai_analysis', 'view_statisfy_ai_analysis',
            'view_any_pristi_schedule', 'view_pristi_schedule',
            'view_any_pristi_attendance', 'view_pristi_attendance',
            'view_any_pristi_monev_report', 'view_pristi_monev_report',
            'view_any_public_complaint', 'view_public_complaint',
            'view_any_elena_evaluation', 'view_elena_evaluation',
        ]);

        // 4. Provision Default Official Testing Accounts
        $defaultUsers = [
            [
                'nip_baru' => '199001012015011001',
                'nip_lama' => '330000001',
                'name' => 'Super Administrator BPS',
                'name_without_title' => 'Super Administrator BPS',
                'email' => 'superadmin@bps.go.id',
                'unit_code' => '33000',
                'unit_name' => 'Bagian Umum',
                'role' => 'super_admin',
            ],
            [
                'nip_baru' => '199202022016021002',
                'nip_lama' => '330000002',
                'name' => 'Operator Loket PST',
                'name_without_title' => 'Operator Loket PST',
                'email' => 'operator.pst@bps.go.id',
                'unit_code' => '33000',
                'unit_name' => 'Pelayanan Statistik Terpadu',
                'role' => 'operator_pst',
            ],
            [
                'nip_baru' => '199303032017031003',
                'nip_lama' => '330000003',
                'name' => 'Pengelola Jadwal Pristi',
                'name_without_title' => 'Pengelola Jadwal Pristi',
                'email' => 'admin.pristi@bps.go.id',
                'unit_code' => '33000',
                'unit_name' => 'Tim Diseminasi Statistik',
                'role' => 'admin_pristi',
            ],
            [
                'nip_baru' => '199404042018041004',
                'nip_lama' => '330000004',
                'name' => 'Admin Pengaduan Masyarakat',
                'name_without_title' => 'Admin Pengaduan Masyarakat',
                'email' => 'admin.pengaduan@bps.go.id',
                'unit_code' => '33000',
                'unit_name' => 'Tim Hukum dan Pengaduan',
                'role' => 'admin_pengaduan',
            ],
            [
                'nip_baru' => '199505052019051005',
                'nip_lama' => '330000005',
                'name' => 'Tim Penilai Kinerja Elena',
                'name_without_title' => 'Tim Penilai Kinerja Elena',
                'email' => 'admin.elena@bps.go.id',
                'unit_code' => '33000',
                'unit_name' => 'Tim SDM dan Organisasi',
                'role' => 'admin_elena',
            ],
            [
                'nip_baru' => '198006062002061006',
                'nip_lama' => '330000006',
                'name' => 'Pimpinan BPS Jawa Tengah',
                'name_without_title' => 'Pimpinan BPS Jawa Tengah',
                'email' => 'eksekutif@bps.go.id',
                'unit_code' => '33000',
                'unit_name' => 'Pimpinan / Kepala BPS',
                'role' => 'eksekutif',
            ],
            [
                'nip_baru' => '199508152019031001',
                'nip_lama' => '330015081',
                'name' => 'Ardhian Dwi Saputra',
                'name_without_title' => 'Ardhian Dwi Saputra',
                'email' => 'ardhian.ds@bps.go.id',
                'unit_code' => '33000',
                'unit_name' => 'Tim Integrasi Pengolahan & Diseminasi Statistik',
                'role' => 'super_admin',
            ],
        ];

        foreach ($defaultUsers as $item) {
            $user = User::firstOrCreate(
                ['email' => $item['email']],
                [
                    'nip_baru' => $item['nip_baru'],
                    'nip_lama' => $item['nip_lama'],
                    'name' => $item['name'],
                    'name_without_title' => $item['name_without_title'],
                    'password' => Hash::make('password'),
                    'unit_code' => $item['unit_code'],
                    'unit_name' => $item['unit_name'],
                    'is_active' => true,
                ]
            );

            $user->syncRoles([$item['role']]);
        }
    }
}
