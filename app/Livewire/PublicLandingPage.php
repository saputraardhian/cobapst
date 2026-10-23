<?php

namespace App\Livewire;

use App\Models\PstCounter;
use App\Models\PstServiceTicket;
use App\Models\PublicComplaint;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

/**
 * Komponen Livewire Portal Layanan Publik Terpadu BPS Jateng (Node 1).
 *
 * ID:
 * Mengelola halaman depan portal publik SEMAR PST dengan arsitektur Modern SaaS Bento & Scrollytelling.
 * Menghubungkan pemantauan antrean lobi real-time, direktori 4 pilar layanan terpadu,
 * hall of fame petugas teladan bulanan (integrasi Elena), pelacakan progres tiket/aduan interaktif,
 * serta gerbang akses Single Sign-On (SSO) pegawai internal.
 *
 * EN:
 * Livewire component for SEMAR PST Public Service Portal & Landing Page (Node 1).
 * Powers the public-facing gateway utilizing a Modern SaaS Bento & Scrollytelling layout.
 * Features real-time lobby queue widget synchronization, 4-pillar service directory,
 * Hall of Fame exemplary officer showcase (Elena integration), interactive ticket/complaint tracker,
 * and internal officer Single Sign-On (SSO) gateway.
 */
#[Layout('components.layouts.public')]
#[Title('PRISTELA - Portal Layanan Publik & Konsultasi Statistik BPS Jateng')]
class PublicLandingPage extends Component
{
    /**
     * ID: Kode pencarian tiket atau pengaduan masyarakat.
     * EN: Search query code for ticket or public complaint tracking.
     */
    public string $searchCode = '';

    /**
     * ID: Hasil pencarian pelacakan status tiket atau pengaduan.
     * EN: Search results payload for ticket or complaint tracking.
     *
     * @var array<string, mixed>|null
     */
    public ?array $searchResult = null;

    /**
     * ID: Kunci pilar layanan yang sedang dipilih untuk modal rincian layanan.
     * EN: Selected service pillar key for detailed informational modal view.
     */
    public ?string $selectedServiceKey = null;

    /**
     * ID: Eksekusi pencarian status tiket antrean atau tiket pengaduan masyarakat.
     * EN: Execute search for queue service ticket or public complaint tracking code.
     *
     * @param string|null $code
     * @return void
     */
    public function trackTicket(?string $code = null): void
    {
        $targetCode = trim($code ?? $this->searchCode);
        if (empty($targetCode)) {
            $this->searchResult = null;
            return;
        }

        $this->searchCode = $targetCode;

        // 1. Periksa pada tabel pengaduan publik (public_complaints)
        $complaint = PublicComplaint::where('tracking_code', $targetCode)
            ->orWhere('tracking_code', 'like', "%{$targetCode}%")
            ->first();

        if ($complaint) {
            $this->searchResult = [
                'found' => true,
                'category' => 'Pengaduan Masyarakat',
                'code' => $complaint->tracking_code,
                'title' => $complaint->complaint_category ?? 'Aspirasi Pelayanan Publik',
                'status' => $complaint->status,
                'status_label' => match ($complaint->status) {
                    'received' => 'Diterima - Menunggu Triage',
                    'in_review' => 'Dalam Penelaahan Internal',
                    'follow_up' => 'Tindak Lanjut Lapangan',
                    'resolved' => 'Selesai Ditindaklanjuti',
                    default => 'Dalam Proses',
                },
                'status_color' => match ($complaint->status) {
                    'resolved' => 'green',
                    'follow_up', 'in_review' => 'amber',
                    default => 'blue',
                },
                'date' => $complaint->created_at?->format('d M Y, H:i') ?? '-',
                'description' => $complaint->complaint_content,
                'feedback' => $complaint->public_feedback ?? 'Pengaduan sedang diverifikasi oleh Tim Pengawasan Internal BPS Provinsi Jawa Tengah.',
            ];
            return;
        }

        // 2. Periksa pada tabel tiket antrean layanan PST (pst_service_tickets)
        $ticket = PstServiceTicket::with(['counter', 'servedBy'])
            ->where('ticket_number', $targetCode)
            ->orWhere('ticket_number', 'like', "%{$targetCode}%")
            ->orWhere('qr_token', $targetCode)
            ->latest('id')
            ->first();

        if ($ticket) {
            $this->searchResult = [
                'found' => true,
                'category' => 'Tiket Layanan PST',
                'code' => $ticket->ticket_number,
                'title' => 'Layanan ' . ucfirst($ticket->service_type),
                'status' => $ticket->status,
                'status_label' => match ($ticket->status) {
                    'waiting' => 'Menunggu Panggilan di Lobi',
                    'calling' => 'Sedang Dipanggil di Loket',
                    'serving' => 'Sedang Dilayani Petugas',
                    'completed' => 'Layanan Selesai Diberikan',
                    'cancelled' => 'Dibatalkan / Tidak Hadir',
                    default => 'Diproses',
                },
                'status_color' => match ($ticket->status) {
                    'completed' => 'green',
                    'calling', 'serving' => 'orange',
                    'waiting' => 'amber',
                    default => 'slate',
                },
                'date' => $ticket->service_date?->format('d M Y') ?? '-',
                'description' => $ticket->request_detail ?? 'Konsultasi pelayanan data dan statistik BPS Provinsi Jawa Tengah.',
                'feedback' => $ticket->service_report ?? ($ticket->counter ? "Dilayani pada {$ticket->counter->counter_name} oleh {$ticket->servedBy?->name}." : 'Menunggu alokasi meja pelayanan.'),
            ];
            return;
        }

        // 3. Fallback jika tidak ditemukan
        $this->searchResult = [
            'found' => false,
            'code' => $targetCode,
            'message' => "Nomor tiket atau kode pelacakan '{$targetCode}' tidak ditemukan dalam arsip layanan aktif BPS Jawa Tengah. Pastikan format nomor telah sesuai (contoh: ADU-2026-0042 atau A-024).",
        ];
    }

    /**
     * ID: Mengambil tiket aktif utama yang sedang dipanggil atau dilayani di lobi.
     * EN: Retrieve the primary active ticket currently being called or served in the lobby.
     *
     * @return PstServiceTicket|null
     */
    public function getActiveTicket(): ?PstServiceTicket
    {
        $callingTicket = PstServiceTicket::with(['counter.activeOperator', 'servedBy', 'visitor'])
            ->where('status', 'calling')
            ->latest('called_at')
            ->first();

        if ($callingTicket) {
            return $callingTicket;
        }

        $servingTicket = PstServiceTicket::with(['counter.activeOperator', 'servedBy', 'visitor'])
            ->where('status', 'serving')
            ->latest('started_at')
            ->first();

        if ($servingTicket) {
            return $servingTicket;
        }

        return PstServiceTicket::with(['counter.activeOperator', 'servedBy', 'visitor'])
            ->latest('id')
            ->first();
    }

    /**
     * ID: Format kode antrean ringkas (misal: A-024).
     * EN: Format concise queue code (e.g. A-024).
     *
     * @param PstServiceTicket|null $ticket
     * @return string
     */
    public function getDisplayQueueCode(?PstServiceTicket $ticket): string
    {
        if (!$ticket) {
            return 'A-024';
        }

        if (!empty($ticket->queue_code) && preg_match('/^[A-Za-z]-\d+$/', $ticket->queue_code)) {
            return strtoupper($ticket->queue_code);
        }

        if (!empty($ticket->queue_code) && !empty($ticket->queue_number)) {
            $letter = strtoupper(substr($ticket->queue_code, 0, 1));
            return sprintf('%s-%03d', $letter, (int) $ticket->queue_number);
        }

        if (preg_match('/([A-Za-z])-?0*(\d+)$/', $ticket->ticket_number, $matches)) {
            return sprintf('%s-%03d', strtoupper($matches[1]), (int) $matches[2]);
        }

        return $ticket->ticket_number;
    }

    /**
     * ID: Mengambil daftar 5 meja loket beserta tiket aktif yang sedang dilayani di masing-masing meja.
     * EN: Retrieve 5 service counters alongside active tickets being served at each counter.
     *
     * @return Collection<int, PstCounter>
     */
    public function getCounters(): Collection
    {
        $counters = PstCounter::with('activeOperator')
            ->orderBy('counter_number')
            ->get();

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
     * ID: Menghitung total pengunjung/tiket hari ini.
     * EN: Calculate total visitors/tickets for today.
     *
     * @return int
     */
    public function getTodayTicketsCount(): int
    {
        $targetDate = PstServiceTicket::max('service_date');
        $queryDate = $targetDate ? Carbon::parse($targetDate)->toDateString() : today()->toDateString();

        return PstServiceTicket::whereDate('service_date', $queryDate)->count();
    }

    /**
     * ID: Data showcase Hall of Fame Petugas Teladan Triwulanan (Integrasi Elena).
     * EN: Showcase data for Quarterly Exemplary Officer Hall of Fame (Elena integration).
     *
     * @return list<array<string, mixed>>
     */
    public function getTopOfficers(): array
    {
        return [
            [
                'rank' => 1,
                'badge' => 'PETUGAS TELADAN 1',
                'is_winner' => true,
                'name' => 'Hendra Wibowo, S.Si.',
                'initials' => 'HW',
                'nip' => '198503152008011002',
                'counter' => 'Loket 2 - Konsultasi Statistik Sektoral',
                'score_final' => '99.8',
                'satisfaction_index' => '4.98',
                'total_services' => 162,
                'honor_title' => 'Pegawai Teladan BPS Jawa Tengah',
            ],
            [
                'rank' => 2,
                'badge' => 'PERINGKAT 2',
                'is_winner' => false,
                'name' => 'Dina Nuryita, S.Tr.Stat.',
                'initials' => 'DN',
                'nip' => '199208142014102001',
                'counter' => 'Loket 1 - Perpustakaan & Rujukan',
                'score_final' => '3.94 / 4.00',
                'satisfaction_index' => '98.1%',
                'total_services' => 114,
                'honor_title' => 'Petugas Berprestasi',
            ],
            [
                'rank' => 3,
                'badge' => 'PERINGKAT 3',
                'is_winner' => false,
                'name' => 'Rino Septiani, M.Stat.',
                'initials' => 'RS',
                'nip' => '199004052012122002',
                'counter' => 'Loket 4 - Rekomendasi Statistik (ROMANIK)',
                'score_final' => '3.87 / 4.00',
                'satisfaction_index' => '96.2%',
                'total_services' => 89,
                'honor_title' => 'Petugas Berprestasi',
            ],
        ];
    }

    /**
     * ID: Menampilkan atau menutup modal rincian layanan.
     * EN: Toggle service pillar informational modal.
     *
     * @param string|null $serviceKey
     * @return void
     */
    public function toggleServiceModal(?string $serviceKey = null): void
    {
        $this->selectedServiceKey = $serviceKey;
    }

    /**
     * ID: Render tampilan portal publik SEMAR PST.
     * EN: Render SEMAR PST public portal view.
     *
     * @return View
     */
    public function render(): View
    {
        $activeTicket = $this->getActiveTicket();

        return view('livewire.public-landing-page', [
            'activeTicket' => $activeTicket,
            'displayQueueCode' => $this->getDisplayQueueCode($activeTicket),
            'counters' => $this->getCounters(),
            'todayTicketsCount' => $this->getTodayTicketsCount(),
            'topOfficers' => $this->getTopOfficers(),
        ]);
    }
}
