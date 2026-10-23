<?php

namespace Tests\Feature;

use App\Models\ElenaEvaluation;
use App\Models\PristiAttendance;
use App\Models\PristiMonevReport;
use App\Models\PristiSchedule;
use App\Models\PstCounter;
use App\Models\PstServiceTicket;
use App\Models\PstVisitor;
use App\Models\PublicComplaint;
use App\Models\StatisfySurvey;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Pengujian Fitur RBAC Multi-Role & Policies (BE-05).
 *
 * ID:
 * Menguji integritas 6 peran kedinasan BPS Jateng (super_admin, operator_pst, admin_pristi,
 * admin_pengaduan, admin_elena, eksekutif), verifikasi hak akses granular pada setiap model,
 * serta memastikan kepatuhan pembagian kewenangan (segregation of duties).
 *
 * EN:
 * Tests the integrity of 6 official BPS roles, verifies model policy gate authorizations,
 * and asserts strict segregation of duties across modules.
 */
class RbacAndPoliciesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_all_six_official_roles_and_default_users_exist(): void
    {
        $expectedRoles = [
            'super_admin',
            'operator_pst',
            'admin_pristi',
            'admin_pengaduan',
            'admin_elena',
            'eksekutif',
        ];

        foreach ($expectedRoles as $roleName) {
            $this->assertDatabaseHas('roles', ['name' => $roleName]);
        }

        $this->assertDatabaseHas('users', ['email' => 'superadmin@bps.go.id']);
        $this->assertDatabaseHas('users', ['email' => 'operator.pst@bps.go.id']);
        $this->assertDatabaseHas('users', ['email' => 'admin.pristi@bps.go.id']);
        $this->assertDatabaseHas('users', ['email' => 'admin.pengaduan@bps.go.id']);
        $this->assertDatabaseHas('users', ['email' => 'admin.elena@bps.go.id']);
        $this->assertDatabaseHas('users', ['email' => 'eksekutif@bps.go.id']);

        $superAdmin = User::where('email', 'superadmin@bps.go.id')->first();
        $this->assertTrue($superAdmin->hasRole('super_admin'));
    }

    public function test_super_admin_has_full_bypass_across_policies(): void
    {
        $superAdmin = User::where('email', 'superadmin@bps.go.id')->first();

        $this->assertTrue(Gate::forUser($superAdmin)->allows('viewAny', User::class));
        $this->assertTrue(Gate::forUser($superAdmin)->allows('delete', $superAdmin));
        $this->assertTrue(Gate::forUser($superAdmin)->allows('create', PstServiceTicket::class));
        $this->assertTrue(Gate::forUser($superAdmin)->allows('delete', new ElenaEvaluation()));
    }

    public function test_operator_pst_can_handle_tickets_but_cannot_delete_or_manage_elena(): void
    {
        $operator = User::where('email', 'operator.pst@bps.go.id')->first();

        // Allowed actions
        $this->assertTrue(Gate::forUser($operator)->allows('viewAny', PstServiceTicket::class));
        $this->assertTrue(Gate::forUser($operator)->allows('create', PstServiceTicket::class));
        $this->assertTrue(Gate::forUser($operator)->allows('viewAny', PstVisitor::class));
        $this->assertTrue(Gate::forUser($operator)->allows('create', PristiAttendance::class));

        // Forbidden actions
        $this->assertFalse(Gate::forUser($operator)->allows('delete', new PstServiceTicket()));
        $this->assertFalse(Gate::forUser($operator)->allows('create', ElenaEvaluation::class));
        $this->assertFalse(Gate::forUser($operator)->allows('delete', new ElenaEvaluation()));
        $this->assertFalse(Gate::forUser($operator)->allows('create', User::class));
    }

    public function test_admin_pristi_can_manage_schedules_and_monev_but_cannot_delete_users(): void
    {
        $adminPristi = User::where('email', 'admin.pristi@bps.go.id')->first();

        // Allowed actions
        $this->assertTrue(Gate::forUser($adminPristi)->allows('create', PristiSchedule::class));
        $this->assertTrue(Gate::forUser($adminPristi)->allows('update', new PristiSchedule()));
        $this->assertTrue(Gate::forUser($adminPristi)->allows('create', PristiMonevReport::class));
        $this->assertTrue(Gate::forUser($adminPristi)->allows('viewAny', User::class));

        // Forbidden actions
        $this->assertFalse(Gate::forUser($adminPristi)->allows('delete', new User()));
        $this->assertFalse(Gate::forUser($adminPristi)->allows('create', ElenaEvaluation::class));
        $this->assertFalse(Gate::forUser($adminPristi)->allows('create', PublicComplaint::class));
    }

    public function test_admin_pengaduan_can_manage_complaints_but_cannot_modify_counters(): void
    {
        $adminPengaduan = User::where('email', 'admin.pengaduan@bps.go.id')->first();

        // Allowed actions
        $this->assertTrue(Gate::forUser($adminPengaduan)->allows('viewAny', PublicComplaint::class));
        $this->assertTrue(Gate::forUser($adminPengaduan)->allows('update', new PublicComplaint()));

        // Forbidden actions
        $this->assertFalse(Gate::forUser($adminPengaduan)->allows('create', PstCounter::class));
        $this->assertFalse(Gate::forUser($adminPengaduan)->allows('delete', new PstCounter()));
        $this->assertFalse(Gate::forUser($adminPengaduan)->allows('delete', new User()));
    }

    public function test_admin_elena_can_manage_evaluations_but_cannot_delete_tickets(): void
    {
        $adminElena = User::where('email', 'admin.elena@bps.go.id')->first();

        // Allowed actions
        $this->assertTrue(Gate::forUser($adminElena)->allows('create', ElenaEvaluation::class));
        $this->assertTrue(Gate::forUser($adminElena)->allows('update', new ElenaEvaluation()));
        $this->assertTrue(Gate::forUser($adminElena)->allows('viewAny', PstServiceTicket::class));
        $this->assertTrue(Gate::forUser($adminElena)->allows('viewAny', StatisfySurvey::class));

        // Forbidden actions
        $this->assertFalse(Gate::forUser($adminElena)->allows('delete', new PstServiceTicket()));
        $this->assertFalse(Gate::forUser($adminElena)->allows('create', PstServiceTicket::class));
        $this->assertFalse(Gate::forUser($adminElena)->allows('delete', new User()));
    }

    public function test_eksekutif_has_read_only_privileges_and_cannot_mutate_data(): void
    {
        $eksekutif = User::where('email', 'eksekutif@bps.go.id')->first();

        // Read-only / viewAny across modules allowed
        $this->assertTrue(Gate::forUser($eksekutif)->allows('viewAny', User::class));
        $this->assertTrue(Gate::forUser($eksekutif)->allows('viewAny', PstCounter::class));
        $this->assertTrue(Gate::forUser($eksekutif)->allows('viewAny', PstServiceTicket::class));
        $this->assertTrue(Gate::forUser($eksekutif)->allows('viewAny', StatisfySurvey::class));
        $this->assertTrue(Gate::forUser($eksekutif)->allows('viewAny', PristiMonevReport::class));
        $this->assertTrue(Gate::forUser($eksekutif)->allows('viewAny', ElenaEvaluation::class));

        // Mutations strictly forbidden
        $this->assertFalse(Gate::forUser($eksekutif)->allows('create', User::class));
        $this->assertFalse(Gate::forUser($eksekutif)->allows('create', PstServiceTicket::class));
        $this->assertFalse(Gate::forUser($eksekutif)->allows('update', new PristiSchedule()));
        $this->assertFalse(Gate::forUser($eksekutif)->allows('delete', new PublicComplaint()));
        $this->assertFalse(Gate::forUser($eksekutif)->allows('delete', new ElenaEvaluation()));
    }
}
