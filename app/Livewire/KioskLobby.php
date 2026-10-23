<?php

namespace App\Livewire;

use App\Models\PstCounter;
use App\Models\PstServiceTicket;
use App\Models\PstVisitor;
use App\Models\PstVisitorLog;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/**
 * Komponen Anjungan Kios Lobi Mandiri Sentuh (SEMAR PST - Node 2).
 *
 * ID:
 * Mengelola antarmuka layar sentuh anjungan mandiri lobi PST BPS Provinsi Jawa Tengah (1440x900px).
 * Memfasilitasi pengambilan nomor tiket antrean 4 pilar layanan terpadu secara atomik (A/B/C/D),
 * simulasi cetak struk termal ESC/POS (80mm) dengan QR token dinamis, registrasi buku tamu kedinasan,
 * auto-lookup profil pengunjung terdaftar, serta pencatatan check-out kepulangan tamu.
 *
 * EN:
 * Touchscreen Self-Service Kiosk Lobby Component (SEMAR PST - Node 2).
 * Operates the 1440x900 touch kiosk in the BPS Central Java PST lobby: facilitates atomic queue
 * ticket dispensing across the 4 service pillars (A/B/C/D), realistic 80mm ESC/POS thermal receipt
 * printing simulation with dynamic QR tokens, guestbook registration, auto-lookup, and guest check-out.
 */
#[Layout('components.layouts.kiosk')]
#[Title('Kios Antrian Mandiri PST - BPS Provinsi Jawa Tengah')]
class KioskLobby extends Component
{
    /**
     * Tab mode operasional aktif: 'pst_ticket', 'guestbook', 'checkout'.
     */
    public string $activeTab = 'pst_ticket';

    /**
     * Tiket yang baru saja diterbitkan (untuk simulasi struk termal).
     */
    public ?PstServiceTicket $activeTicket = null;

    /**
     * Penanda status apakah tiket fisik telah diambil oleh pengunjung.
     */
    public bool $ticketTaken = false;

    /**
     * String SVG representasi QR code tiket aktif.
     */
    public ?string $qrCodeSvg = null;

    /**
     * Pengaturan panduan suara audio (TTS).
     */
    public bool $audioEnabled = true;

    /**
     * Modal dialog status bantuan petugas lobi.
     */
    public bool $showHelpModal = false;

    /**
     * Modal dialog pratinjau visitor pass tamu.
     */
    public bool $showSuccessPassModal = false;

    /**
     * Data visitor pass tamu yang baru terdaftar.
     *
     * @var array<string, mixed>|null
     */
    public ?array $activeVisitorPass = null;

    /**
     * Input form buku tamu digital.
     */
    public string $guestPhone = '';
    public string $guestNik = '';
    public string $guestName = '';
    public string $guestInstitution = '';
    public string $guestTargetUnit = '';
    public string $guestPurpose = '';
    public bool $isKnownVisitor = false;

    /**
     * Pencarian cepat pada tab check-out tamu.
     */
    public string $searchCheckout = '';

    /**
     * Inisialisasi awal komponen Kiosk.
     */
    public function mount(): void
    {
        // Cari tiket terakhir yang dibuat hari ini jika ada untuk demo display struk
        $lastTicket = PstServiceTicket::whereDate('service_date', Carbon::today())
            ->latest('id')
            ->first();

        if ($lastTicket) {
            $this->activeTicket = $lastTicket;
            $this->generateQrSvg($lastTicket->qr_token);
            $this->ticketTaken = true;
        } else {
            $this->generateQrSvg('demo-kiosk-sample');
        }
    }

    /**
     * Penerbitan tiket antrean baru secara atomik dan bebas race-condition.
     */
    public function issueTicket(string $code): void
    {
        $code = strtoupper(trim($code));

        if (! in_array($code, ['A', 'B', 'C', 'D'])) {
            return;
        }

        $serviceType = match ($code) {
            'A' => 'perpustakaan',
            'B' => 'konsultasi',
            'C' => 'pembelian',
            'D' => 'rekomendasi',
        };

        $counterNumber = match ($code) {
            'A' => 1,
            'B' => 2,
            'C' => 3,
            'D' => 4,
        };

        $today = Carbon::today();

        // Transaksi atomik dengan pessimistic locking (Rule #11)
        $ticket = DB::transaction(function () use ($code, $serviceType, $counterNumber, $today) {
            $lastQueueNumber = PstServiceTicket::whereDate('service_date', $today)
                ->where('queue_code', $code)
                ->lockForUpdate()
                ->max('queue_number') ?? 0;

            $nextQueueNumber = $lastQueueNumber + 1;
            $ticketNumber = sprintf('%s-%03d', $code, $nextQueueNumber);
            $qrToken = bin2hex(random_bytes(16));

            $counter = PstCounter::where('counter_number', $counterNumber)->first();

            return PstServiceTicket::create([
                'ticket_number' => $ticketNumber,
                'queue_code' => $code,
                'queue_number' => $nextQueueNumber,
                'qr_token' => $qrToken,
                'service_date' => $today,
                'service_type' => $serviceType,
                'visitor_name' => 'Pengunjung Mandiri Lobi',
                'counter_id' => $counter?->id,
                'status' => 'waiting',
            ]);
        });

        $this->activeTicket = $ticket;
        $this->ticketTaken = false;
        $this->generateQrSvg($ticket->qr_token);

        // Dispatch event audio TTS dan printer termal ke browser
        $speechText = "Nomor antrian {$ticket->ticket_number}, silakan ambil tiket fisik Anda.";
        $this->dispatch('ticket-issued', [
            'ticket_number' => $ticket->ticket_number,
            'speech' => $speechText,
            'audio' => $this->audioEnabled,
        ]);
    }

    /**
     * Generate representasi SVG untuk QR code tiket.
     */
    protected function generateQrSvg(?string $qrToken): void
    {
        try {
            $token = $qrToken ?: 'demo-kiosk-sample';
            $url = url("/s/{$token}");
            $this->qrCodeSvg = (string) QrCode::size(120)->generate($url);
        } catch (\Throwable $e) {
            $this->qrCodeSvg = null;
        }
    }

    /**
     * Cetak ulang struk tiket fisik (trigger browser print dialog).
     */
    public function reprintTicket(): void
    {
        $this->dispatch('reprint-ticket');
    }

    /**
     * Tandai tiket fisik telah diambil oleh pengunjung dan simpan tampilan tiket aktif.
     */
    public function clearPrintedTicket(): void
    {
        $this->ticketTaken = true;

        $this->dispatch('ticket-taken', [
            'message' => 'Tiket fisik berhasil diambil. Silakan menuju ruang tunggu.',
            'audio' => $this->audioEnabled,
        ]);
    }

    /**
     * Auto-lookup otomatis profil pengunjung saat nomor HP diketik.
     */
    public function updatedGuestPhone(string $value): void
    {
        $cleanPhone = preg_replace('/[^0-9]/', '', $value);

        if (strlen($cleanPhone) >= 9) {
            $visitor = PstVisitor::where('phone_number', 'like', "%{$cleanPhone}%")->first();

            if ($visitor) {
                $this->guestName = $visitor->name;
                $this->guestInstitution = $visitor->institution ?? '';
                $this->isKnownVisitor = true;
                return;
            }
        }

        $this->isKnownVisitor = false;
    }

    /**
     * Tombol jalan pintas pengisian asal instansi tamu (Quick Fill).
     */
    public function quickFillInstitution(string $institution): void
    {
        $this->guestInstitution = $institution;
    }

    /**
     * Registrasi dan penyimpanan data buku tamu kedinasan.
     */
    public function registerGuest(): void
    {
        $this->validate([
            'guestPhone' => ['required', 'string', 'min:8'],
            'guestName' => ['required', 'string', 'min:3'],
            'guestInstitution' => ['required', 'string'],
            'guestTargetUnit' => ['required', 'string'],
            'guestPurpose' => ['required', 'string'],
        ], [
            'guestPhone.required' => 'Nomor WhatsApp / HP wajib diisi.',
            'guestName.required' => 'Nama lengkap tamu wajib diisi.',
            'guestInstitution.required' => 'Asal instansi / dinas wajib diisi.',
            'guestTargetUnit.required' => 'Kontak / tim kerja tujuan wajib dipilih.',
            'guestPurpose.required' => 'Keperluan kunjungan kedinasan wajib dipilih.',
        ]);

        $visitor = DB::transaction(function () {
            $cleanPhone = preg_replace('/[^0-9]/', '', $this->guestPhone);

            $visitor = PstVisitor::updateOrCreate(
                ['phone_number' => $cleanPhone],
                [
                    'name' => $this->guestName,
                    'institution' => $this->guestInstitution,
                ]
            );

            PstVisitorLog::create([
                'visitor_id' => $visitor->id,
                'visit_date' => Carbon::today(),
                'purpose' => "{$this->guestTargetUnit} - {$this->guestPurpose}",
                'check_in_at' => Carbon::now(),
                'notes' => $this->guestNik ? "NIK: {$this->guestNik}" : null,
            ]);

            return $visitor;
        });

        $this->activeVisitorPass = [
            'name' => $visitor->name,
            'institution' => $visitor->institution,
            'phone' => $visitor->phone_number,
            'time' => Carbon::now()->format('H:i'),
            'target' => $this->guestTargetUnit,
            'pass_code' => 'VISIT-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 5)),
        ];

        $this->showSuccessPassModal = true;

        $speechText = "Buku tamu atas nama {$visitor->name} berhasil disimpan. Selamat datang di BPS Provinsi Jawa Tengah.";
        $this->dispatch('guest-registered', [
            'speech' => $speechText,
            'audio' => $this->audioEnabled,
        ]);

        // Reset form
        $this->reset(['guestPhone', 'guestNik', 'guestName', 'guestInstitution', 'guestTargetUnit', 'guestPurpose', 'isKnownVisitor']);
    }

    /**
     * Penyelesaian kunjungan tamu (Check-Out).
     */
    public function checkoutVisitor(int $logId): void
    {
        $log = PstVisitorLog::find($logId);

        if ($log && is_null($log->check_out_at)) {
            $log->update(['check_out_at' => Carbon::now()]);

            $this->dispatch('guest-checked-out', [
                'message' => 'Check-out berhasil. Terima kasih atas kunjungan Anda.',
                'audio' => $this->audioEnabled,
            ]);
        }
    }

    /**
     * Panggil bantuan petugas lobi ke anjungan Kios.
     */
    public function callOfficer(): void
    {
        $this->showHelpModal = true;
        $this->dispatch('officer-called');
    }

    /**
     * Beralih status panduan audio suara.
     */
    public function toggleAudio(): void
    {
        $this->audioEnabled = ! $this->audioEnabled;
    }

    /**
     * Mendapatkan jumlah antrean menunggu per kode layanan.
     *
     * @return array<string, int>
     */
    public function getQueueCountsProperty(): array
    {
        $today = Carbon::today();

        return [
            'A' => PstServiceTicket::whereDate('service_date', $today)->where('queue_code', 'A')->where('status', 'waiting')->count(),
            'B' => PstServiceTicket::whereDate('service_date', $today)->where('queue_code', 'B')->where('status', 'waiting')->count(),
            'C' => PstServiceTicket::whereDate('service_date', $today)->where('queue_code', 'C')->where('status', 'waiting')->count(),
            'D' => PstServiceTicket::whereDate('service_date', $today)->where('queue_code', 'D')->where('status', 'waiting')->count(),
        ];
    }

    /**
     * Mendapatkan daftar tamu yang sedang berkunjung hari ini (belum check-out).
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, PstVisitorLog>
     */
    public function getActiveVisitorsProperty()
    {
        $query = PstVisitorLog::with('visitor')
            ->whereDate('visit_date', Carbon::today())
            ->whereNull('check_out_at')
            ->latest('check_in_at');

        if ($this->searchCheckout) {
            $query->whereHas('visitor', function ($q) {
                $q->where('name', 'like', "%{$this->searchCheckout}%")
                  ->orWhere('phone_number', 'like', "%{$this->searchCheckout}%")
                  ->orWhere('institution', 'like', "%{$this->searchCheckout}%");
            });
        }

        return $query->take(15)->get();
    }

    /**
     * Render antarmuka anjungan kios.
     */
    public function render(): View
    {
        return view('livewire.kiosk-lobby');
    }
}
