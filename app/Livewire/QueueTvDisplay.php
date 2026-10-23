<?php

namespace App\Livewire;

use App\Models\PstCounter;
use App\Models\PstServiceTicket;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

/**
 * Komponen Livewire Layar TV Display Antrean Ruang Tunggu PST (Node 3).
 *
 * ID:
 * Menampilkan antarmuka monitor TV ruang tunggu 23 inci bebas scroll (zero-scroll)
 * beresolusi Full HD di lobi Pelayanan Statistik Terpadu (PST) BPS Provinsi Jawa Tengah.
 * Menyajikan visualisasi nomor tiket yang sedang dipanggil/dilayani, matriks status
 * 5 meja loket operasional, statistik harian, teks maklumat berjalan (running marquee),
 * serta memicu audio chime ding-dong dan sintesis suara (TTS) Bahasa Indonesia.
 *
 * EN:
 * Livewire component for the PST Lobby 23-inch TV Queue Display Monitor (Node 3).
 * Renders a zero-scroll Full HD waiting area interface at BPS Central Java Province.
 * Displays active calling/serving ticket spotlight, operational matrix of 5 service
 * counters, daily queue KPIs, institutional running marquee announcements,
 * and emits Web Audio chime ding-dong triggers alongside Indonesian TTS voice calls.
 */
#[Layout('components.layouts.tv')]
#[Title('Display Antrean TV - BPS Provinsi Jawa Tengah')]
class QueueTvDisplay extends Component
{
    /**
     * ID: Token pembanding panggilan tiket terakhir untuk mencegah pengulangan suara pada polling yang sama.
     * EN: Comparison token for the last called ticket to prevent duplicate audio triggers on identical polls.
     */
    public ?string $lastCallSignature = null;

    /**
     * ID: Pesan status audio untuk umpan balik operator.
     * EN: Audio status message for operator feedback.
     */
    public string $audioStatus = 'Siap';

    /**
     * ID: Inisialisasi awal komponen layar TV.
     * EN: Mount initial state of the TV display component.
     *
     * @return void
     */
    public function mount(): void
    {
        $activeTicket = $this->getActiveTicket();
        if ($activeTicket) {
            $this->lastCallSignature = $this->buildCallSignature($activeTicket);
        }
    }

    /**
     * ID: Polling berkala yang dipanggil oleh wire:poll untuk sinkronisasi data riil antrean.
     * EN: Periodic polling method invoked by wire:poll to synchronize real-time queue data.
     *
     * @return void
     */
    public function refreshQueue(): void
    {
        $activeTicket = $this->getActiveTicket();

        if ($activeTicket) {
            $currentSignature = $this->buildCallSignature($activeTicket);

            // ID: Jika ada panggilan baru atau perubahan tiket berstatus 'calling', kirim sinyal audio ke browser
            // EN: If a new call or ticket transition to 'calling' occurs, dispatch an audio call event
            if ($activeTicket->status === 'calling' && $currentSignature !== $this->lastCallSignature) {
                $this->lastCallSignature = $currentSignature;

                $this->dispatch('queue-called', [
                    'ticketCode' => $this->getDisplayQueueCode($activeTicket),
                    'ticketNumber' => $activeTicket->ticket_number,
                    'counterName' => $activeTicket->counter?->counter_name ?? 'Loket Layanan PST',
                    'counterNumber' => $activeTicket->counter?->counter_number ?? 1,
                    'serviceType' => $activeTicket->service_type,
                    'visitorName' => $activeTicket->visitor_name,
                ]);
            }
        }
    }

    /**
     * ID: Simulasi pemicu panggilan untuk kebutuhan uji operasional dan demonstrasi audio.
     * EN: Trigger a simulated call for operational testing and audio demonstration purposes.
     *
     * @param int $counterNumber
     * @return void
     */
    public function triggerTestCall(int $counterNumber = 1): void
    {
        $counter = PstCounter::where('counter_number', $counterNumber)->first()
            ?? PstCounter::first();

        $activeTicket = $this->getActiveTicket();
        $ticketCode = $this->getDisplayQueueCode($activeTicket);
        $ticketNumber = $activeTicket?->ticket_number ?? 'A-024';
        $counterName = $counter?->counter_name ?? 'Loket 1 - Konsultasi Statistik';

        $this->dispatch('queue-called', [
            'ticketCode' => $ticketCode,
            'ticketNumber' => $ticketNumber,
            'counterName' => $counterName,
            'counterNumber' => $counter?->counter_number ?? 1,
            'serviceType' => 'Konsultasi Statistik',
            'visitorName' => $activeTicket?->visitor_name ?? 'Tamu Pelayanan',
        ]);
    }

    /**
     * ID: Mengambil tiket aktif utama yang sedang dipanggil atau dilayani di lobi.
     * EN: Retrieve the primary active ticket currently being called or served in the lobby.
     *
     * @return PstServiceTicket|null
     */
    public function getActiveTicket(): ?PstServiceTicket
    {
        // 1. Prioritaskan tiket berstatus 'calling' (sedang dipanggil)
        $callingTicket = PstServiceTicket::with(['counter.activeOperator', 'servedBy', 'visitor'])
            ->where('status', 'calling')
            ->latest('called_at')
            ->first();

        if ($callingTicket) {
            return $callingTicket;
        }

        // 2. Jika tidak ada yang calling, ambil tiket berstatus 'serving' (sedang dilayani)
        $servingTicket = PstServiceTicket::with(['counter.activeOperator', 'servedBy', 'visitor'])
            ->where('status', 'serving')
            ->latest('started_at')
            ->first();

        if ($servingTicket) {
            return $servingTicket;
        }

        // 3. Fallback: ambil tiket terbaru di sistem untuk visualisasi standby
        return PstServiceTicket::with(['counter.activeOperator', 'servedBy', 'visitor'])
            ->latest('id')
            ->first();
    }

    /**
     * ID: Format kode antrean ringkas untuk tampilan jumbo TV (misal: A-003).
     * EN: Formats concise queue code for TV jumbo display (e.g., A-003).
     *
     * @param PstServiceTicket|null $ticket
     * @return string
     */
    public function getDisplayQueueCode(?PstServiceTicket $ticket): string
    {
        if (!$ticket) {
            return 'A-001';
        }

        // 1. Jika queue_code sudah berformat lengkap seperti "A-003", gunakan langsung
        if (!empty($ticket->queue_code) && preg_match('/^[A-Za-z]-\d+$/', $ticket->queue_code)) {
            return strtoupper($ticket->queue_code);
        }

        // 2. Jika queue_code adalah huruf tunggal dan memiliki nomor urut
        if (!empty($ticket->queue_code) && !empty($ticket->queue_number)) {
            $letter = strtoupper(substr($ticket->queue_code, 0, 1));
            return sprintf('%s-%03d', $letter, (int) $ticket->queue_number);
        }

        // 3. Ekstrak huruf antrean dan nomor urut dari format nomor tiket lama (contoh: PST-20210917-A-003)
        if (preg_match('/([A-Za-z])-?0*(\d+)$/', $ticket->ticket_number, $matches)) {
            return sprintf('%s-%03d', strtoupper($matches[1]), (int) $matches[2]);
        }

        return $ticket->ticket_number;
    }

    /**
     * ID: Mengambil daftar 5 meja loket beserta tiket aktif yang sedang dilayani di masing-masing meja.
     * EN: Retrieve the 5 service counters alongside active tickets being served at each counter.
     *
     * @return Collection<int, PstCounter>
     */
    public function getCounters(): Collection
    {
        $counters = PstCounter::with('activeOperator')
            ->orderBy('counter_number')
            ->get();

        // ID: Tarik tiket yang sedang dipanggil/dilayani per loket secara eager untuk mencegah N+1
        // EN: Fetch active tickets currently called or serving per counter to prevent N+1 query overhead
        $activeTickets = PstServiceTicket::whereIn('status', ['calling', 'serving'])
            ->whereNotNull('counter_id')
            ->latest('id')
            ->get()
            ->keyBy('counter_id');

        foreach ($counters as $counter) {
            $counter->setAttribute('current_ticket', $activeTickets->get($counter->id));
        }

        return $counters;
    }

    /**
     * ID: Menghitung metrik KPI antrean operasional hari ini.
     * EN: Calculate daily operational queue KPI metrics.
     *
     * @return array{total: int, serving: int, waiting: int, completed: int}
     */
    public function getQueueStats(): array
    {
        // ID: Gunakan tanggal transaksi paling mutakhir di basis data untuk konsistensi data demo
        // EN: Leverage the latest transaction date in the database for consistent demo data presentation
        $targetDate = PstServiceTicket::max('service_date');
        $queryDate = $targetDate ? Carbon::parse($targetDate)->toDateString() : today()->toDateString();

        $baseQuery = PstServiceTicket::whereDate('service_date', $queryDate);

        return [
            'total' => (clone $baseQuery)->count(),
            'serving' => (clone $baseQuery)->whereIn('status', ['calling', 'serving'])->count(),
            'waiting' => (clone $baseQuery)->where('status', 'waiting')->count(),
            'completed' => (clone $baseQuery)->where('status', 'completed')->count(),
        ];
    }

    /**
     * ID: Membuat signature unik untuk sebuah panggilan tiket.
     * EN: Generate a unique signature string for a ticket call event.
     *
     * @param PstServiceTicket $ticket
     * @return string
     */
    protected function buildCallSignature(PstServiceTicket $ticket): string
    {
        $timestamp = $ticket->called_at?->timestamp ?? $ticket->updated_at?->timestamp ?? 0;
        return "{$ticket->id}-{$ticket->status}-{$timestamp}";
    }

    /**
     * ID: Render tampilan antarmuka display TV antrean.
     * EN: Render the TV queue display monitor view.
     *
     * @return View
     */
    public function render(): View
    {
        return view('livewire.queue-tv-display', [
            'activeTicket' => $this->getActiveTicket(),
            'counters' => $this->getCounters(),
            'stats' => $this->getQueueStats(),
        ]);
    }
}
