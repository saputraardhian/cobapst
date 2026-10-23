<?php

namespace Tests\Feature;

use App\Livewire\KioskLobby;
use App\Models\PstCounter;
use App\Models\PstServiceTicket;
use App\Models\PstVisitor;
use App\Models\PstVisitorLog;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Pengujian Fitur Anjungan Kios Lobi Mandiri Sentuh (Node 2).
 *
 * ID:
 * Menguji keterbukaan rute /kiosk, penampilan antarmuka layar sentuh 1440x900,
 * penerbitan tiket antrean secara atomik (A/B/C/D), simulasi cetak struk termal ESC/POS,
 * registrasi buku tamu kedinasan, auto-lookup profil pengunjung, serta check-out kepulangan tamu.
 *
 * EN:
 * Feature test suite for the Self-Service Touchscreen Kiosk Lobby (Node 2).
 * Tests route availability for /kiosk, 1440x900 touch interface layout rendering,
 * atomic queue ticket issuance (A/B/C/D), realistic ESC/POS thermal receipt simulation,
 * guestbook registration, phone number auto-lookup, and guest check-out workflows.
 */
class KioskLobbyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ID: Menguji rute /kiosk dapat diakses dan merender komponen KioskLobby dengan benar.
     * EN: Test that /kiosk route is accessible and renders the KioskLobby component properly.
     */
    public function test_kiosk_lobby_route_is_accessible_and_renders_correctly(): void
    {
        $response = $this->get('/kiosk');
        $response->assertStatus(200);
        $response->assertSeeLivewire(KioskLobby::class);

        Livewire::test(KioskLobby::class)
            ->assertStatus(200)
            ->assertSee('Kios Antrian Mandiri PST')
            ->assertSee('LOBI UTAMA PST')
            ->assertSee('AMBIL TIKET ANTREAN PST')
            ->assertSee('BUKU TAMU KEDINASAN')
            ->assertSee('CHECK-OUT KUNJUNGAN')
            ->assertSee('Perpustakaan', false)
            ->assertSee('Konsultasi Statistik', false)
            ->assertSee('Pembelian Data Mikro', false)
            ->assertSee('Rekomendasi (ROMANIK)')
            ->assertSee('Simulasi Cetak Struk Fisik');
    }

    /**
     * ID: Menguji penerbitan tiket antrean secara atomik menghasilkan nomor tiket dan event audio.
     * EN: Test atomic ticket issuance generates sequential ticket number and audio event.
     */
    public function test_kiosk_issue_ticket_creates_atomic_ticket_and_dispatches_event(): void
    {
        PstCounter::factory()->create([
            'counter_number' => 2,
            'counter_name' => 'Loket 2 - Konsultasi Statistik',
            'is_open' => true,
        ]);

        $component = Livewire::test(KioskLobby::class)
            ->call('issueTicket', 'B')
            ->assertDispatched('ticket-issued');

        $this->assertDatabaseHas('pst_service_tickets', [
            'ticket_number' => 'B-001',
            'queue_code' => 'B',
            'queue_number' => 1,
            'service_type' => 'konsultasi',
            'status' => 'waiting',
        ]);

        $ticket = PstServiceTicket::where('ticket_number', 'B-001')->first();
        $this->assertNotNull($ticket);
        $this->assertNotNull($ticket->qr_token);

        // Uji penerbitan nomor berikutnya (increment atomik)
        $component->call('issueTicket', 'B');

        $this->assertDatabaseHas('pst_service_tickets', [
            'ticket_number' => 'B-002',
            'queue_code' => 'B',
            'queue_number' => 2,
        ]);
    }

    /**
     * ID: Menguji registrasi buku tamu kedinasan menyimpan profil tamu dan log kunjungan.
     * EN: Test guestbook registration stores visitor profile and visit log.
     */
    public function test_kiosk_register_guest_creates_visitor_and_log(): void
    {
        Livewire::test(KioskLobby::class)
            ->set('guestPhone', '081234567890')
            ->set('guestNik', '3374011203950003')
            ->set('guestName', 'Ir. Bambang Trihatmojo, M.Si')
            ->set('guestInstitution', 'Dinas Komunikasi & Informatika Jateng')
            ->set('guestTargetUnit', 'Tim IPDS (Integrasi Pengolahan & Diseminasi Statistik)')
            ->set('guestPurpose', 'Koordinasi Satu Data Indonesia & Interoperabilitas API Statistik')
            ->call('registerGuest')
            ->assertDispatched('guest-registered')
            ->assertSet('showSuccessPassModal', true);

        $this->assertDatabaseHas('pst_visitors', [
            'name' => 'Ir. Bambang Trihatmojo, M.Si',
            'phone_number' => '081234567890',
            'institution' => 'Dinas Komunikasi & Informatika Jateng',
        ]);

        $this->assertDatabaseHas('pst_visitor_logs', [
            'notes' => 'NIK: 3374011203950003',
        ]);
    }

    /**
     * ID: Menguji auto-lookup profil pengunjung terdaftar saat nomor HP diinput.
     * EN: Test registered visitor auto-lookup when phone number is typed.
     */
    public function test_kiosk_auto_lookup_by_phone(): void
    {
        PstVisitor::create([
            'name' => 'Dr. Hendra Gunawan',
            'phone_number' => '081299887766',
            'institution' => 'Bappeda Prov Jateng',
        ]);

        Livewire::test(KioskLobby::class)
            ->set('guestPhone', '081299887766')
            ->assertSet('guestName', 'Dr. Hendra Gunawan')
            ->assertSet('guestInstitution', 'Bappeda Prov Jateng')
            ->assertSet('isKnownVisitor', true);
    }

    /**
     * ID: Menguji proses check-out tamu menyelesaikan kunjungan dan mencatat waktu keluar.
     * EN: Test visitor check-out process completes visit and timestamps departure.
     */
    public function test_kiosk_checkout_visitor_updates_checkout_time(): void
    {
        $visitor = PstVisitor::create([
            'name' => 'Siti Aminah, S.Kom',
            'phone_number' => '085711223344',
            'institution' => 'Inspektorat Jateng',
        ]);

        $log = PstVisitorLog::create([
            'visitor_id' => $visitor->id,
            'visit_date' => Carbon::today(),
            'purpose' => 'Koordinasi Evaluasi Kinerja',
            'check_in_at' => Carbon::now()->subHours(2),
            'check_out_at' => null,
        ]);

        Livewire::test(KioskLobby::class)
            ->call('checkoutVisitor', $log->id)
            ->assertDispatched('guest-checked-out');

        $this->assertNotNull($log->fresh()->check_out_at);
    }

    /**
     * ID: Menguji fungsi pemanggilan bantuan petugas lobi dan tombol peralihan audio.
     * EN: Test officer help calling function and audio toggle switch.
     */
    public function test_kiosk_call_officer_and_toggle_audio(): void
    {
        Livewire::test(KioskLobby::class)
            ->call('callOfficer')
            ->assertSet('showHelpModal', true)
            ->assertDispatched('officer-called')
            ->call('toggleAudio')
            ->assertSet('audioEnabled', false)
            ->call('toggleAudio')
            ->assertSet('audioEnabled', true);
    }

    /**
     * ID: Menguji tombol selesai ambil tiket menandai tiket telah diambil dan mempertahankan data tiket beserta QR code.
     * EN: Test that finishing ticket retrieval marks ticket as taken while preserving ticket details and QR code.
     */
    public function test_kiosk_clear_printed_ticket_marks_ticket_taken_and_preserves_ticket_details(): void
    {
        PstCounter::factory()->create([
            'counter_number' => 1,
            'counter_name' => 'Loket 1 - Perpustakaan',
            'is_open' => true,
        ]);

        Livewire::test(KioskLobby::class)
            ->call('issueTicket', 'A')
            ->assertSet('ticketTaken', false)
            ->assertSee('A-001')
            ->call('clearPrintedTicket')
            ->assertSet('ticketTaken', true)
            ->assertDispatched('ticket-taken')
            ->assertSee('TIKET SUDAH DIAMBIL')
            ->assertSee('A-001');
    }
}
