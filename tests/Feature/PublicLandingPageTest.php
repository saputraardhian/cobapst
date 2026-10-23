<?php

namespace Tests\Feature;

use App\Livewire\PublicLandingPage;
use App\Models\PstCounter;
use App\Models\PstServiceTicket;
use App\Models\PublicComplaint;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Pengujian Fitur Portal Layanan Publik Terpadu BPS Jateng (Node 1).
 *
 * ID:
 * Menguji keterbukaan halaman depan portal publik (/), pemuatan komponen Livewire,
 * penampilan widget antrean lobi real-time, 4 pilar layanan, Hall of Fame petugas teladan,
 * serta fungsi pelacakan interaktif tiket antrean dan pengaduan publik.
 *
 * EN:
 * Feature test suite for BPS Central Java Integrated Public Service Portal (Node 1).
 * Tests route accessibility for (/), Livewire component mounting, real-time lobby queue widget,
 * 4 service pillars, Hall of Fame showcase, and interactive ticket/complaint tracking.
 */
class PublicLandingPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ID: Menguji rute beranda publik dapat diakses dengan sukses.
     * EN: Test that home route is accessible successfully.
     */
    public function test_public_landing_page_can_be_rendered(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSeeLivewire(PublicLandingPage::class);
        $response->assertSee('PRISTELA');
        $response->assertSee('Satu Pintu Layanan Data & Konsultasi Statistik Resmi', false);
        $response->assertSee('4 Pilar Layanan Terpadu PST');
        $response->assertSee('Hall of Fame Petugas PST Teladan');
    }

    /**
     * ID: Menguji widget antrean menampilkan loket dan tiket aktif.
     * EN: Test queue widget renders counters and active ticket.
     */
    public function test_public_landing_page_renders_live_queue_and_counters(): void
    {
        $operator = User::factory()->create([
            'name' => 'Hendra Wibowo, S.Si.',
        ]);

        $counter = PstCounter::factory()->create([
            'counter_name' => 'Loket 2 - Konsultasi Statistik',
            'counter_number' => 2,
            'is_open' => true,
            'active_operator_id' => $operator->id,
        ]);

        PstServiceTicket::factory()->create([
            'ticket_number' => 'A-024',
            'queue_code' => 'A',
            'queue_number' => 24,
            'status' => 'calling',
            'counter_id' => $counter->id,
            'served_by_user_id' => $operator->id,
            'service_date' => today()->toDateString(),
        ]);

        Livewire::test(PublicLandingPage::class)
            ->assertStatus(200)
            ->assertSee('A-024')
            ->assertSee('LIVE MONITOR ANTREAN')
            ->assertSee('Hendra Wibowo, S.Si.');
    }

    /**
     * ID: Menguji pelacakan kode pengaduan masyarakat.
     * EN: Test tracking public complaint tracking code.
     */
    public function test_public_landing_page_tracks_complaint(): void
    {
        PublicComplaint::factory()->create([
            'tracking_code' => 'ADU-2026-0042',
            'complaint_category' => 'Pelayanan Loket Konsultasi',
            'status' => 'follow_up',
            'complaint_content' => 'Permintaan percepatan rekomendasi statistik',
        ]);

        Livewire::test(PublicLandingPage::class)
            ->call('trackTicket', 'ADU-2026-0042')
            ->assertSee('ADU-2026-0042')
            ->assertSee('Pengaduan Masyarakat')
            ->assertSee('Tindak Lanjut Lapangan');
    }

    /**
     * ID: Menguji pelacakan nomor tiket antrean layanan PST.
     * EN: Test tracking PST queue service ticket number.
     */
    public function test_public_landing_page_tracks_service_ticket(): void
    {
        PstServiceTicket::factory()->create([
            'ticket_number' => 'PST-2026-0112',
            'service_type' => 'konsultasi',
            'status' => 'completed',
            'request_detail' => 'Konsultasi PDRB Kabupaten Semarang',
        ]);

        Livewire::test(PublicLandingPage::class)
            ->call('trackTicket', 'PST-2026-0112')
            ->assertSee('PST-2026-0112')
            ->assertSee('Tiket Layanan PST')
            ->assertSee('Layanan Selesai Diberikan');
    }
}
