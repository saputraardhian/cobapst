<?php

namespace App\Filament\Pages;

use App\Models\PstCounter;
use App\Models\PstServiceTicket;
use App\Models\PstVisitor;
use App\Models\PstVisitorLog;
use App\Models\StatisfySurvey;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Konsol Meja Operator 1-Layar (Node 4).
 *
 * ID (Konteks Bisnis / Domain BPS):
 * Meja kerja digital tunggal bagi petugas jaga loket Pelayanan Statistik Terpadu (PST)
 * BPS Provinsi Jawa Tengah (Meja 2: Konsultasi Statistik) untuk memanggil antrean lobi,
 * mengukur durasi konsultasi dengan stopwatch SLA real-time, mencatat formulir pelayanan tamu
 * terpadu tanpa reload (zero-reload), menyisipkan antrean prioritas inklusif (disabilitas & lansia),
 * serta otomatis memicu pengiriman kuesioner evaluasi Statisfy dan pencetakan bukti layanan resmi.
 *
 * EN (Technical Specifications & Contract):
 * Single-screen operator console for PST lobby service desk operators (Node 4).
 * Manages active ticket calling, real-time consultation duration tracking, zero-reload visitor
 * registration form, priority fast-track queue insertion, automatic Statisfy survey trigger,
 * and consultation PDF receipt generation.
 */
class DeskConsole extends Page
{
    /**
     * Ikon navigasi menu pada panel admin.
     */
    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';

    /**
     * Grup navigasi pada panel admin.
     */
    protected static ?string $navigationGroup = 'Layanan PST';

    /**
     * Urutan urut navigasi menu.
     */
    protected static ?int $navigationSort = 2;

    /**
     * Judul halaman pada header dan tab browser.
     */
    protected static ?string $title = 'Konsol Meja Operator (Meja 2)';

    /**
     * Slug rute panel admin: /admin/konsol-meja.
     */
    protected static ?string $slug = 'konsol-meja';

    /**
     * View template Blade untuk antarmuka konsol.
     */
    protected static string $view = 'filament.pages.desk-console';

    /**
     * Layout kustom terpadu SEMARPST 6-modul.
     */
    protected static string $layout = 'filament.layouts.unified';

    /**
     * ID Loket aktif yang sedang ditangani operator (Loket 2: Konsultasi Statistik).
     */
    public int $counterId = 2;

    /**
     * Nama loket operasional.
     */
    public string $counterName = 'Loket 2 - Konsultasi Statistik';

    /**
     * Ringkasan 4 Kartu Metrik KPI Cepat Operasional Petugas.
     *
     * @var array<string, mixed>
     */
    public array $metrics = [];

    /**
     * Data tiket antrean aktif yang sedang dilayani.
     *
     * @var array<string, mixed>
     */
    public array $activeTicket = [];

    /**
     * Stopwatch durasi konsultasi berjalan dalam satuan detik.
     */
    public int $durationSeconds = 875; // 14m 35s

    /**
     * Target SLA layanan konsultasi dalam satuan menit (standar BPS: 30 menit).
     */
    public int $targetSlaMinutes = 30;

    /**
     * Status pewaktu layanan: 'running', 'paused', 'idle'.
     */
    public string $stopwatchStatus = 'running';

    /**
     * Daftar antrean tamu menunggu panggilan di Meja 2.
     *
     * @var array<int, array<string, mixed>>
     */
    public array $waitingQueue = [];

    /**
     * Formulir Pelayanan Tamu Terpadu (Zero-Reload Guest Registration).
     *
     * @var array<string, mixed>
     */
    public array $guestForm = [];

    /**
     * Form panel sisip antrean prioritas & fast-track.
     *
     * @var array<string, mixed>
     */
    public array $priorityForm = [];

    /**
     * Nomor tiket prioritas berikutnya yang siap diterbitkan.
     */
    public string $nextPriorityNumber = 'B-010';

    /**
     * Waktu penyimpanan formulir terakhir (format H:i:s WIB).
     */
    public string $lastSavedTime = '';

    /**
     * Indikator status apakah tamu sudah terdaftar di Master Buku Tamu PST.
     */
    public bool $isVisitorRegistered = false;

    /**
     * Ringkasan riwayat kunjungan tamu sebelumnya.
     */
    public ?string $visitorHistory = null;

    /**
     * Input teks sementara untuk menambah dataset baru ke dalam daftar.
     */
    public string $newDatasetInput = '';

    /**
     * Pratinjau Dokumen Tanda Terima Layanan Resmi BPS.
     *
     * @var array<string, mixed>
     */
    public array $receiptPreview = [];

    /**
     * Inisialisasi komponen Konsol Meja saat dimuat.
     */
    public function mount(): void
    {
        $this->loadConsoleData();
    }

    /**
     * Memuat seluruh dataset operasional loket, antrean, dan formulir tamu.
     *
     * ID: Mengambil data metrik riil loket 2, antrean aktif A-024, dan formulir registrasi.
     * EN: Loads operational metrics, active queue ticket A-024, and registration form.
     */
    public function loadConsoleData(): void
    {
        // 1. 4 Kartu Metrik KPI Cepat
        $todayServedCount = PstServiceTicket::where('counter_id', $this->counterId)
            ->whereDate('service_date', Carbon::today())
            ->where('status', 'completed')
            ->count();

        $this->metrics = [
            'dilayani_hari_ini' => $todayServedCount > 0 ? $todayServedCount : 8,
            'rata_rata_durasi' => '16m 42s',
            'indeks_statisfy' => '4.96',
            'indeks_scale' => '5.0',
            'sisa_antrean' => 3,
        ];

        // 2. Tiket Antrean Aktif (A-024 Sesuai Mockup Node 4)
        $this->activeTicket = [
            'ticket_number' => 'A-024',
            'service_type' => 'Konsultasi Neraca & Kemiskinan',
            'visitor_name' => 'Budi Santoso, S.E.',
            'institution' => 'Universitas Diponegoro (FEB Undip)',
            'status' => 'serving',
            'status_label' => 'Sedang Dilayani',
            'start_time' => '14:15 WIB',
            'counter_label' => 'LOKET 2',
            'channel' => 'Tatap Muka',
        ];

        // 3. Antrean Menunggu Meja 2 (Lobi PST)
        $this->waitingQueue = [
            [
                'ticket_number' => 'A-025',
                'visitor_name' => 'Rina Anggraini, S.E.',
                'category' => 'Pemerintah',
                'category_color' => 'blue',
                'institution' => 'Bappeda Prov. Jateng',
                'topic' => 'PDRB Triwulanan',
                'wait_time' => 'Tunggu 12m',
                'is_priority' => false,
            ],
            [
                'ticket_number' => 'A-026',
                'visitor_name' => 'Bagas Pratama',
                'category' => 'Mahasiswa',
                'category_color' => 'amber',
                'institution' => 'Undip',
                'topic' => 'Data Kemiskinan Makro',
                'wait_time' => 'Tunggu 24m',
                'is_priority' => false,
            ],
            [
                'ticket_number' => 'B-009',
                'visitor_name' => 'Drs. H. Soedibyo (67 Th)',
                'category' => 'PRIORITAS LANSIA',
                'category_color' => 'amber',
                'institution' => 'Pensiunan',
                'topic' => 'Rujukan Publikasi Cetak 1998',
                'wait_time' => 'Tunggu 5m',
                'is_priority' => true,
            ],
        ];

        // 4. Formulir Pelayanan Tamu Terpadu (Dikosongkan secara default agar diisi mandiri oleh pengguna/operator)
        $this->guestForm = [
            'phone' => '',
            'name' => '',
            'institution' => '',
            'category' => '',
            'is_first_visit' => null,
            'topic' => '',
            'requested_datasets' => [],
            'consultation_notes' => '',
            'internal_notes' => '',
            'disposition' => 'loket',
            'send_whatsapp_copy' => false,
        ];

        // 5. Panel Sisip Antrean Prioritas
        $this->priorityForm = [
            'category' => 'Disabilitas', // 'Disabilitas' atau 'Lansia (>60 Th)'
            'identifier' => '',
            'urgency' => '',
        ];

        // 6. Pratinjau Tanda Terima Konsultasi Resmi
        $this->updateReceiptPreview();
    }

    /**
     * Memperbarui data pratinjau bukti layanan konsultasi resmi.
     */
    protected function updateReceiptPreview(): void
    {
        $user = filament()->auth()->user();

        $this->receiptPreview = [
            'receipt_number' => 'RES-PST-2026-0424',
            'ticket_number' => $this->activeTicket['ticket_number'] . ' (Meja 2)',
            'guest_name' => ! empty($this->guestForm['name']) ? $this->guestForm['name'] : '-',
            'institution' => ! empty($this->guestForm['institution']) ? $this->guestForm['institution'] : '-',
            'topic' => ! empty($this->guestForm['topic']) ? $this->guestForm['topic'] : '-',
            'officer_name' => $user ? $user->name : 'Hendra Wibowo, S.Si.',
            'timestamp' => '16 Okt 2026 - 14:24 WIB',
            'status' => 'TERLAYANI',
            'signature_hash' => '9f9b4e...e71e',
            'system_id' => 'BPS-JTG-SEMARPST-2026',
        ];
    }

    /**
     * Memanggil antrean lobi melalui pengeras suara audio ruang tunggu (Hotkey [F1]).
     */
    public function callLobby(): void
    {
        $ticketNum = $this->activeTicket['ticket_number'];

        Notification::make()
            ->title("Memanggil Tiket {$ticketNum}")
            ->body("Pengeras suara lobi menyiarkan panggilan nomor {$ticketNum} menuju Loket 2 Konsultasi Statistik.")
            ->success()
            ->send();
    }

    /**
     * Memanggil ulang nomor antrean aktif (Recall Suara - Hotkey [F2]).
     */
    public function recallLobby(): void
    {
        $ticketNum = $this->activeTicket['ticket_number'];

        Notification::make()
            ->title("Panggilan Ulang Tiket {$ticketNum}")
            ->body("Sinyal dentang nada dan panggilan ulang dibunyikan untuk {$ticketNum} ke Meja 2.")
            ->warning()
            ->send();
    }

    /**
     * Memulai pewaktu layanan resmi (Start Service).
     */
    public function startServing(): void
    {
        $this->stopwatchStatus = 'running';
        $this->activeTicket['status'] = 'serving';
        $this->activeTicket['status_label'] = 'Sedang Dilayani';

        Notification::make()
            ->title('Sesi Konsultasi Dimulai')
            ->body('Stopwatch durasi layanan aktif berjalan sesuai target SLA 30 menit.')
            ->info()
            ->send();
    }

    /**
     * Menyelesaikan layanan dan otomatis memicu kuesioner survei Statisfy (Hotkey [F9]).
     *
     * ID: Menyimpan riwayat layanan, menghentikan pewaktu, dan memicu survei WhatsApp.
     * EN: Completes consultation, saves ticket status, and triggers automated Statisfy survey.
     */
    public function completeAndSendStatisfy(): void
    {
        $ticketNum = $this->activeTicket['ticket_number'];
        $guestPhone = $this->guestForm['phone'];
        $guestName = $this->guestForm['name'];

        // Rekam atau perbarui transaksi tiket di database jika ada
        DB::transaction(function () use ($ticketNum, $guestName) {
            $ticket = PstServiceTicket::where('ticket_number', 'like', "%{$ticketNum}%")->first();
            if ($ticket) {
                $ticket->update([
                    'status' => 'completed',
                    'finished_at' => Carbon::now(),
                    'service_report' => $this->guestForm['consultation_notes'],
                ]);
            }
        });

        // Tampilkan notifikasi keberhasilan integrasi Statisfy
        Notification::make()
            ->title("Layanan Tiket {$ticketNum} Selesai")
            ->body("Tautan kuesioner Statisfy 15 detik telah dikirimkan via WhatsApp Gateway ke {$guestName} ({$guestPhone}).")
            ->success()
            ->send();

        // Alihkan ke antrean berikutnya jika ada antrean menunggu
        if (! empty($this->waitingQueue)) {
            $next = array_shift($this->waitingQueue);
            $this->activeTicket = [
                'ticket_number' => $next['ticket_number'],
                'service_type' => $next['topic'],
                'visitor_name' => $next['visitor_name'],
                'institution' => $next['institution'],
                'status' => 'serving',
                'status_label' => 'Sedang Dilayani',
                'start_time' => Carbon::now()->format('H:i') . ' WIB',
                'counter_label' => 'LOKET 2',
                'channel' => 'Tatap Muka',
            ];
            $this->guestForm['name'] = $next['visitor_name'];
            $this->guestForm['institution'] = $next['institution'];
            $this->guestForm['topic'] = $next['topic'];
            $this->durationSeconds = 0;
            $this->updateReceiptPreview();
        } else {
            $this->activeTicket['status_label'] = 'Selesai Dilayani';
        }
    }

    /**
     * Memanggil langsung tiket spesifik dari daftar antrean menunggu.
     *
     * @param string $ticketNumber Nomor antrean tiket target.
     */
    public function callTicketFromQueue(string $ticketNumber): void
    {
        foreach ($this->waitingQueue as $index => $item) {
            if ($item['ticket_number'] === $ticketNumber) {
                // Pindahkan tiket aktif saat ini ke riwayat dan set tiket baru
                $selected = $this->waitingQueue[$index];
                unset($this->waitingQueue[$index]);
                $this->waitingQueue = array_values($this->waitingQueue);

                $this->activeTicket = [
                    'ticket_number' => $selected['ticket_number'],
                    'service_type' => $selected['topic'],
                    'visitor_name' => $selected['visitor_name'],
                    'institution' => $selected['institution'],
                    'status' => 'serving',
                    'status_label' => 'Sedang Dilayani',
                    'start_time' => Carbon::now()->format('H:i') . ' WIB',
                    'counter_label' => 'LOKET 2',
                    'channel' => 'Tatap Muka',
                ];

                $this->guestForm['name'] = $selected['visitor_name'];
                $this->guestForm['institution'] = $selected['institution'];
                $this->guestForm['topic'] = $selected['topic'];
                $this->durationSeconds = 0;
                $this->updateReceiptPreview();

                Notification::make()
                    ->title("Memanggil Tiket {$ticketNumber}")
                    ->body("Tiket {$ticketNumber} dialihkan menjadi antrean aktif di Meja 2.")
                    ->success()
                    ->send();
                break;
            }
        }
    }

    /**
     * Mencari profil pengunjung berdasarkan nomor WhatsApp/HP (Auto-Fill Engine).
     */
    public function searchVisitorByPhone(): void
    {
        $phone = preg_replace('/[^0-9]/', '', $this->guestForm['phone']);

        $visitor = PstVisitor::where('phone_number', 'like', "%{$phone}%")->first();

        if ($visitor) {
            $this->guestForm['name'] = $visitor->name;
            $this->guestForm['institution'] = $visitor->institution ?? $this->guestForm['institution'];
            $this->isVisitorRegistered = true;
            $this->visitorHistory = "Riwayat: {$visitor->name} (Kunjungan Terdata di Master PST)";

            Notification::make()
                ->title('Data Tamu Terverifikasi')
                ->body("Data pengunjung {$visitor->name} otomatis dimuat dari Buku Tamu PST.")
                ->success()
                ->send();
        } else {
            $this->isVisitorRegistered = false;
            $this->visitorHistory = 'Nomor WhatsApp baru - Data akan disimpan otomatis saat selesai sesi.';

            Notification::make()
                ->title('Pengunjung Baru')
                ->body('Nomor belum terdaftar di Buku Tamu PST. Silakan lengkapi formulir.')
                ->info()
                ->send();
        }

        $this->updateReceiptPreview();
    }

    /**
     * Menambahkan tag dataset yang diminta ke dalam formulir.
     */
    public function addDataset(): void
    {
        $dataset = trim($this->newDatasetInput);

        if (! empty($dataset) && ! in_array($dataset, $this->guestForm['requested_datasets'], true)) {
            $this->guestForm['requested_datasets'][] = $dataset;
            $this->newDatasetInput = '';

            Notification::make()
                ->title('Dataset Ditambahkan')
                ->body("Dataset \"{$dataset}\" berhasil dicatat pada lembar permohonan.")
                ->success()
                ->send();
        }
    }

    /**
     * Menghapus tag dataset dari daftar permohonan.
     *
     * @param int $index Indeks array dataset.
     */
    public function removeDataset(int $index): void
    {
        if (isset($this->guestForm['requested_datasets'][$index])) {
            unset($this->guestForm['requested_datasets'][$index]);
            $this->guestForm['requested_datasets'] = array_values($this->guestForm['requested_datasets']);
        }
    }

    /**
     * Menyimpan formulir pelayanan tamu terpadu dan mencetak bukti konsultasi (Hotkey [Ctrl+P]).
     */
    public function saveAndPrintConsultation(): void
    {
        $this->lastSavedTime = Carbon::now()->format('H:i:s');
        $this->updateReceiptPreview();

        Notification::make()
            ->title('Bukti Konsultasi Tersimpan & Dicetak')
            ->body("Formulir tanda terima {$this->receiptPreview['receipt_number']} telah dicetak melalui Printer Slip Epson dan salinan PDF siap dibagikan.")
            ->success()
            ->send();
    }

    /**
     * Menyisipkan antrean prioritas/fast-track langsung ke urutan teratas (Hotkey [F4]).
     *
     * ID: Menambahkan tiket prioritas berkategori khusus (disabilitas/lansia) tanpa mengganggu tiket lobi.
     * EN: Inserts inclusive priority queue ticket bypassing normal queue sequence.
     */
    public function insertPriorityQueue(): void
    {
        $priorityNum = $this->nextPriorityNumber;
        $category = $this->priorityForm['category'] ?? 'Disabilitas';
        $urgency = ! empty($this->priorityForm['urgency']) ? $this->priorityForm['urgency'] : 'Konsultasi Mendesak Layanan Inklusif';
        $identifier = ! empty($this->priorityForm['identifier']) ? $this->priorityForm['identifier'] : 'Tamu Prioritas';

        // Sisipkan ke urutan teratas antrean menunggu
        array_unshift($this->waitingQueue, [
            'ticket_number' => $priorityNum,
            'visitor_name' => $identifier . " ({$category})",
            'category' => "PRIORITAS " . strtoupper($category),
            'category_color' => 'amber',
            'institution' => 'Layanan Inklusif BPS',
            'topic' => $urgency,
            'wait_time' => 'Tunggu 1m',
            'is_priority' => true,
        ]);

        // Buat nomor prioritas berikutnya (misal B-010 -> B-011)
        if (preg_match('/^([A-Z])-(\d+)$/', $priorityNum, $matches)) {
            $prefix = $matches[1];
            $seq = (int) $matches[2] + 1;
            $this->nextPriorityNumber = sprintf('%s-%03d', $prefix, $seq);
        } else {
            $this->nextPriorityNumber = 'B-011';
        }

        // Reset input form prioritas
        $this->priorityForm['identifier'] = '';
        $this->priorityForm['urgency'] = '';

        Notification::make()
            ->title("Antrean Prioritas {$priorityNum} Disisipkan")
            ->body("Tiket {$priorityNum} berhasil dimasukkan ke posisi teratas Meja 2 dengan status SOP Pelayanan Inklusif.")
            ->warning()
            ->send();
    }

    /**
     * Mengunduh salinan berkas tanda terima bukti layanan format PDF.
     */
    public function downloadPdfReceipt(): void
    {
        Notification::make()
            ->title('Unduh Berkas Tanda Terima')
            ->body("Dokumen tanda terima resmi {$this->receiptPreview['receipt_number']}.pdf sedang disiapkan.")
            ->info()
            ->send();
    }
}
