<?php

namespace Tests\Feature;

use App\Filament\Pages\Dashboard;
use App\Models\PstServiceTicket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Pengujian Fitur Workspace Pegawai (Node 6).
 *
 * ID (Konteks Bisnis / Domain BPS):
 * Menguji keterbukaan dasbor kerja terintegrasi petugas PST (/admin atau /workspace),
 * perlindungan hak akses autentikasi, penyajian bento scorecard evaluasi kinerja ELENA,
 * kartu antrean aktif loket, umpan balik pengunjung Statisfy, komposisi layanan konsultasi,
 * jadwal tugas jaga 7 hari, dan notifikasi disposisi kedinasan.
 *
 * EN (Technical Specifications & Contract):
 * Feature test suite for the Employee Workspace (Node 6).
 * Validates route access for /admin and /workspace shortcuts, authentication protection,
 * Livewire Dashboard component mounting, ELENA performance metric bindings, active ticket actions,
 * visitor feedback parsing, and interactive employee workflow methods.
 */
class EmployeeWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ID: Menguji rute /workspace dan /admin/workspace dialihkan ke /admin.
     * EN: Test that /workspace and /admin/workspace routes redirect to /admin.
     */
    public function test_workspace_shortcut_routes_redirect_to_admin_panel(): void
    {
        $response1 = $this->get('/workspace');
        $response1->assertRedirect('/admin');

        $response2 = $this->get('/admin/workspace');
        $response2->assertRedirect('/admin');
    }

    /**
     * ID: Menguji pengguna yang belum login dialihkan ke halaman login Filament.
     * EN: Test that unauthenticated user is redirected to the Filament login page.
     */
    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/admin/login');
    }

    /**
     * ID: Menguji pengguna terautentikasi dapat mengakses halaman dasbor Workspace Pegawai.
     * EN: Test that authenticated user can access the Employee Workspace dashboard page.
     */
    public function test_authenticated_user_can_access_workspace_dashboard(): void
    {
        $user = User::factory()->create([
            'email' => 'hendra.wibowo@bps.go.id',
            'name' => 'Hendra Wibowo, S.Si.',
        ]);

        $response = $this->actingAs($user)->get('/admin');
        $response->assertStatus(200);
        $response->assertSeeText('Selamat Bertugas, Mas Hendra!');
        $response->assertSee('Evaluasi Kinerja ELENA', false);
        $response->assertSee('Umpan Balik Pengunjung PST', false);
        $response->assertSee('Komposisi Layanan Konsultasi', false);
        $response->assertSee('Jadwal Tugas Jaga 7 Hari Ke Depan', false);
        $response->assertSee('Notifikasi & Disposisi', false);
    }

    /**
     * ID: Menguji komponen Livewire Dashboard memuat seluruh kartu Bento dan data reaktif.
     * EN: Test that Livewire Dashboard component properly mounts all Bento cards and reactive data.
     */
    public function test_workspace_dashboard_renders_reactive_data(): void
    {
        $user = User::factory()->create([
            'name' => 'Hendra Wibowo, S.Si.',
        ]);

        PstServiceTicket::factory()->create([
            'ticket_number' => 'A-001',
            'visitor_name' => 'Bappeda Provinsi Jawa Tengah',
            'service_date' => now()->toDateString(),
            'status' => 'waiting',
        ]);

        Livewire::actingAs($user)
            ->test(Dashboard::class)
            ->assertSet('officer.name', 'Hendra Wibowo, S.Si.')
            ->assertSet('activeTicket.ticket_number', 'A-001')
            ->assertSet('activeTicket.visitor_name', 'Bappeda Provinsi Jawa Tengah')
            ->assertSet('elenaPerformance.overall_score', 97.8)
            ->assertSet('visitorFeedback.rating', '5.0 / 5.0 (138 Suara)')
            ->assertSee('A-001')
            ->assertSee('Bappeda Provinsi Jawa Tengah')
            ->assertSee('97.8')
            ->assertSee('Dr. Ratna Widiastuti')
            ->assertSee('Buka Konsol Meja 2')
            ->assertSee('Panggil A-001 Sekarang');
    }

    /**
     * ID: Menguji dasbor menampilkan status kosong (empty state) jika belum ada antrean hari ini.
     * EN: Test dashboard renders empty state when no active queue ticket exists today.
     */
    public function test_workspace_dashboard_renders_empty_state_when_no_active_ticket(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Dashboard::class)
            ->assertSet('activeTicket', [])
            ->assertSee('Belum Ada Antrean Hari Ini - Siap Melayani')
            ->assertSee('Belum Ada Antrean Masuk');
    }

    /**
     * ID: Menguji pemanggilan aksi interaktif pada Workspace Pegawai (panggil antrean, ekspor, disposisi).
     * EN: Test interactive actions on Employee Workspace (call ticket, export calendar, respond disposition).
     */
    public function test_workspace_dashboard_interactive_actions(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Dashboard::class)
            ->call('callActiveTicket')
            ->assertHasNoErrors()
            ->call('exportCalendar')
            ->assertHasNoErrors()
            ->call('respondDisposition', 'PST-2026-042')
            ->assertHasNoErrors()
            ->call('approveFile', 'pristi-01')
            ->assertHasNoErrors()
            ->call('markAllAsRead')
            ->assertHasNoErrors();
    }
}
