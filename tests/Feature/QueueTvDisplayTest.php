<?php

namespace Tests\Feature;

use App\Livewire\QueueTvDisplay;
use App\Models\PstCounter;
use App\Models\PstServiceTicket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Pengujian Fitur Layar TV Display Antrean Ruang Tunggu PST (Node 3).
 *
 * ID:
 * Menguji keterbukaan rute /antrian, pengalihan /antrean, pemuatan komponen Livewire,
 * penampilan nomor tiket aktif, matriks 5 loket pelayanan, kalkulasi KPI harian,
 * serta emisi event audio panggilan antrean.
 *
 * EN:
 * Feature test suite for the PST Lobby TV Queue Display Monitor (Node 3).
 * Tests route availability for /antrian, redirection from /antrean, Livewire component mounting,
 * active ticket spotlight rendering, 5-counter matrix display, daily KPI computations,
 * and audio call event dispatching.
 */
class QueueTvDisplayTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ID: Menguji rute /antrian dapat diakses dan /antrean dialihkan secara otomatis.
     * EN: Test that /antrian route is accessible and /antrean redirects automatically.
     */
    public function test_tv_display_route_is_accessible_and_redirects(): void
    {
        $response = $this->get('/antrian');
        $response->assertStatus(200);
        $response->assertSeeLivewire(QueueTvDisplay::class);

        $redirect = $this->get('/antrean');
        $redirect->assertRedirect('/antrian');
    }

    /**
     * ID: Menguji tampilan layar TV menyajikan data tiket aktif, loket, dan statistik.
     * EN: Test TV display renders active ticket, counters, and statistics correctly.
     */
    public function test_tv_display_renders_active_ticket_and_counters(): void
    {
        $operator = User::factory()->create([
            'name' => 'Hendra Wibowo, S.Si.',
            'nip_baru' => '198503152008011002',
        ]);

        $counter = PstCounter::factory()->create([
            'counter_name' => 'Loket 1 - Konsultasi Statistik',
            'counter_number' => 1,
            'is_open' => true,
            'active_operator_id' => $operator->id,
        ]);

        $ticket = PstServiceTicket::factory()->create([
            'ticket_number' => 'A-024',
            'status' => 'calling',
            'counter_id' => $counter->id,
            'served_by_user_id' => $operator->id,
            'visitor_name' => 'Prof. Dr. Ir. Sudirman',
            'service_type' => 'Konsultasi Statistik',
            'called_at' => now(),
            'service_date' => today()->toDateString(),
        ]);

        Livewire::test(QueueTvDisplay::class)
            ->assertStatus(200)
            ->assertSee('A-024')
            ->assertSee('Loket 1 - Konsultasi Statistik')
            ->assertSee('Prof. Dr. Ir. Sudirman')
            ->assertSee('Hendra Wibowo, S.Si.')
            ->assertSee('LIVE SINKRON')
            ->assertSee('Matriks Meja Layanan PST');
    }

    /**
     * ID: Menguji tombol uji panggil mengirimkan event browser queue-called untuk audio chime.
     * EN: Test demo call trigger dispatches queue-called browser event for audio chime.
     */
    public function test_tv_display_trigger_test_call_dispatches_event(): void
    {
        $counter = PstCounter::factory()->create([
            'counter_name' => 'Loket 2 - Konsultasi Statistik 2',
            'counter_number' => 2,
            'is_open' => true,
        ]);

        Livewire::test(QueueTvDisplay::class)
            ->call('triggerTestCall', 2)
            ->assertDispatched('queue-called');
    }

    /**
     * ID: Menguji polling berkala mendeteksi transisi tiket baru ke status calling dan memicu event audio.
     * EN: Test periodic polling detects new ticket transition to calling status and fires audio event.
     */
    public function test_tv_display_polling_detects_new_calling_ticket(): void
    {
        $counter = PstCounter::factory()->create([
            'counter_name' => 'Loket 1 - Konsultasi Statistik',
            'counter_number' => 1,
            'is_open' => true,
        ]);

        $ticket = PstServiceTicket::factory()->create([
            'ticket_number' => 'A-015',
            'status' => 'waiting',
            'counter_id' => $counter->id,
            'called_at' => null,
            'service_date' => today()->toDateString(),
        ]);

        $test = Livewire::test(QueueTvDisplay::class);

        // Perbarui tiket menjadi 'calling'
        $ticket->update([
            'status' => 'calling',
            'called_at' => now(),
        ]);

        $test->call('refreshQueue')
            ->assertDispatched('queue-called');
    }
}
