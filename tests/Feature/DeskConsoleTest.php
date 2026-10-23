<?php

namespace Tests\Feature;

use App\Filament\Pages\DeskConsole;
use App\Models\PstServiceTicket;
use App\Models\PstVisitor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Pengujian Fitur Konsol Meja Operator (Node 4).
 *
 * ID (Konteks Bisnis / Domain BPS):
 * Menguji keterbukaan rute meja kerja digital loket PST (/admin/konsol-meja),
 * perlindungan hak akses autentikasi, penyajian 4 kartu metrik operasional cepat,
 * spotlight antrean aktif A-024, stopwatch SLA layanan 30 menit, formulir pelayanan tamu terpadu,
 * panel sisip antrean prioritas B-010, pratinjau bukti layanan resmi, dan pemicu survei Statisfy.
 *
 * EN (Technical Specifications & Contract):
 * Feature test suite for the Operator Desk Console (Node 4).
 * Validates route access, authentication guards, Livewire component mounting,
 * active queue ticket A-024 rendering, SLA stopwatch bindings, zero-reload visitor registration,
 * priority queue injection, and automated Statisfy survey triggers.
 */
class DeskConsoleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ID: Menguji rute /konsol-meja dan /operator dialihkan ke /admin/konsol-meja.
     * EN: Test that /konsol-meja and /operator shortcut routes redirect to /admin/konsol-meja.
     */
    public function test_desk_console_shortcut_routes_redirect_to_admin_panel(): void
    {
        $response1 = $this->get('/konsol-meja');
        $response1->assertRedirect('/admin/konsol-meja');

        $response2 = $this->get('/operator');
        $response2->assertRedirect('/admin/konsol-meja');
    }

    /**
     * ID: Menguji pengguna yang belum login dialihkan ke halaman login Filament.
     * EN: Test that unauthenticated user is redirected to the Filament login page.
     */
    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get('/admin/konsol-meja');
        $response->assertRedirect('/admin/login');
    }

    /**
     * ID: Menguji pengguna terautentikasi dapat mengakses halaman Konsol Meja Operator.
     * EN: Test that authenticated user can access the Desk Console page.
     */
    public function test_authenticated_user_can_access_desk_console(): void
    {
        $user = User::factory()->create([
            'email' => 'operator.meja2@bps.go.id',
            'name' => 'Hendra Wibowo, S.Si.',
        ]);

        $response = $this->actingAs($user)->get('/admin/konsol-meja');
        $response->assertStatus(200);
        $response->assertSeeText('Konsol Meja Operator (Meja 2)');
        $response->assertSee('A-024', false);
        $response->assertSee('Stopwatch Durasi Layanan', false);
        $response->assertSee('Formulir Pelayanan Tamu Terpadu', false);
        $response->assertSee('Panel Sisip Antrean Prioritas', false);
    }

    /**
     * ID: Menguji komponen Livewire DeskConsole memuat data operasional dan tiket aktif A-024.
     * EN: Test that Livewire DeskConsole component mounts reactive datasets properly.
     */
    public function test_desk_console_renders_reactive_data(): void
    {
        $user = User::factory()->create([
            'name' => 'Hendra Wibowo, S.Si.',
        ]);

        Livewire::actingAs($user)
            ->test(DeskConsole::class)
            ->assertSet('activeTicket.ticket_number', 'A-024')
            ->assertSet('activeTicket.visitor_name', 'Budi Santoso, S.E.')
            ->assertSet('guestForm.phone', '')
            ->assertSet('guestForm.name', '')
            ->assertSet('guestForm.requested_datasets', [])
            ->assertSee('A-024')
            ->assertSee('Formulir Pelayanan Tamu Terpadu')
            ->assertSee('Formulir Siap Diisi');
    }

    /**
     * ID: Menguji aksi interaktif operator (panggil lobi, recall suara, sisip prioritas, simpan formulir, dan selesai survei).
     * EN: Test interactive operator actions (call lobby, recall sound, insert priority, save form, and complete survey).
     */
    public function test_desk_console_interactive_actions(): void
    {
        $user = User::factory()->create([
            'name' => 'Hendra Wibowo, S.Si.',
        ]);

        // Siapkan pengunjung di database
        PstVisitor::factory()->create([
            'phone_number' => '081234567890',
            'name' => 'Budi Santoso, S.E.',
            'institution' => 'Universitas Diponegoro',
        ]);

        Livewire::actingAs($user)
            ->test(DeskConsole::class)
            ->call('callLobby')
            ->assertHasNoErrors()
            ->call('recallLobby')
            ->assertHasNoErrors()
            ->call('startServing')
            ->assertHasNoErrors()
            ->set('guestForm.phone', '081234567890')
            ->call('searchVisitorByPhone')
            ->assertSet('guestForm.name', 'Budi Santoso, S.E.')
            ->assertSet('isVisitorRegistered', true)
            ->set('newDatasetInput', 'Publikasi Daerah Dalam Angka 2026')
            ->call('addDataset')
            ->assertHasNoErrors()
            ->assertSee('Publikasi Daerah Dalam Angka 2026')
            ->call('removeDataset', 0)
            ->assertHasNoErrors()
            ->call('saveAndPrintConsultation')
            ->assertHasNoErrors()
            ->set('priorityForm.category', 'Disabilitas')
            ->set('priorityForm.identifier', 'Ahmad Ridwan')
            ->set('priorityForm.urgency', 'Klarifikasi Angka Kemiskinan Difabel')
            ->call('insertPriorityQueue')
            ->assertHasNoErrors()
            ->assertSet('nextPriorityNumber', 'B-011')
            ->assertSee('Ahmad Ridwan (Disabilitas)')
            ->call('completeAndSendStatisfy')
            ->assertHasNoErrors()
            ->call('downloadPdfReceipt')
            ->assertHasNoErrors();
    }
}
