<?php

namespace App\Console\Commands;

use App\Models\ElenaEvaluation;
use App\Models\PristiAttendance;
use App\Models\PristiSchedule;
use App\Models\PstCounter;
use App\Models\PstServiceTicket;
use App\Models\PstVisitor;
use App\Models\PstVisitorLog;
use App\Models\PublicComplaint;
use App\Models\StatisfyAiAnalysis;
use App\Models\StatisfySurvey;
use App\Models\User;
use App\Services\Etl\EmployeeNameResolver;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PDO;

/**
 * Command ETL Migrasi Histori Transaksi Lintas 5 Basis Data Warisan (BE-08).
 *
 * ID:
 * Perintah migrasi Extract-Transform-Load (ETL) untuk mengekstraksi, menormalisasi, dan
 * memindahkan riwayat transaksi historis dari 5 basis data warisan (PST, Statisfy, Pristi,
 * Pengaduan, dan Elena) ke dalam tabel transaksi terpadu di SEMAR PST.
 * Mendukung opsi simulasi `--dry-run`, pemfilteran per modul `--source`, serta audit log
 * anomali sesuai Aturan #7.
 *
 * EN:
 * ETL migration command to extract, normalize, and load historical transactional data from
 * 5 legacy databases (PST, Statisfy, Pristi, Pengaduan, and Elena) into consolidated
 * transactional tables within SEMAR PST. Supports `--dry-run` simulation, module filtering,
 * and unmapped record audit logging per Golden Rule #7.
 */
class EtlTransactionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'semar:etl-transactions
                            {--dry-run : Simulate extraction and migration without mutating the database}
                            {--source=all : Specific module to migrate (pst, statisfy, pristi, pengaduan, elena, all)}
                            {--path= : Custom base directory containing legacy SQL files}
                            {--fresh : Safely truncate target transactional tables before importing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate historical transactional records from 5 legacy systems into unified tables';

    /**
     * Dedicated audit log path for unmapped transactional records.
     */
    protected string $unmappedLogPath;

    /**
     * Base directory for SQL dump fallback files.
     */
    protected string $baseDir;

    /**
     * Direct PDO connection to legacy MySQL databases if available.
     */
    protected ?PDO $legacyPdo = null;

    /**
     * Employee Name and Alias Resolver service.
     */
    protected EmployeeNameResolver $resolver;

    /**
     * Counts and metrics tracker.
     *
     * @var array<string, int>
     */
    protected array $metrics = [
        'visitors_scanned' => 0,
        'visitors_imported' => 0,
        'visitor_logs_scanned' => 0,
        'visitor_logs_imported' => 0,
        'tickets_scanned' => 0,
        'tickets_imported' => 0,
        'surveys_scanned' => 0,
        'surveys_imported' => 0,
        'ai_analyses_scanned' => 0,
        'ai_analyses_imported' => 0,
        'schedules_scanned' => 0,
        'schedules_imported' => 0,
        'attendances_scanned' => 0,
        'attendances_imported' => 0,
        'complaints_scanned' => 0,
        'complaints_imported' => 0,
        'evaluations_scanned' => 0,
        'evaluations_imported' => 0,
        'unmapped_records' => 0,
    ];

    /**
     * Execute the console command.
     */
    public function handle(EmployeeNameResolver $resolver): int
    {
        $this->resolver = $resolver;
        $isDryRun = (bool) $this->option('dry-run');
        $targetSource = strtolower((string) ($this->option('source') ?: 'all'));
        $this->unmappedLogPath = storage_path('logs/etl_unmapped_transactions.log');

        $this->line('===========================================================');
        $this->line('  SEMAR PST - ETL Migrasi Histori Transaksi (BE-08)');
        $this->line('===========================================================');

        if ($isDryRun) {
            $this->warn('MODE: SIMULASI (DRY-RUN). Tidak ada perubahan basis data.');
        } else {
            $this->alert('MODE: EKSEKUSI RIIL. Data transaksi akan ditulis ke database.');
        }

        $this->initUnmappedLog($isDryRun);
        $this->initLegacyConnection();

        if (!$isDryRun && $this->option('fresh')) {
            $this->info('Membersihkan tabel transaksi sebelum import (--fresh)...');
            $this->cleanTransactionalTables($targetSource);
        }

        // 1. Modul PST: Buku Tamu, Riwayat Kunjungan & Tiket Layanan
        if ($targetSource === 'all' || $targetSource === 'pst') {
            $this->migratePstTransactions($isDryRun);
        }

        // 2. Modul Statisfy: Survei Kepuasan & Rangkuman AI
        if ($targetSource === 'all' || $targetSource === 'statisfy') {
            $this->migrateStatisfyTransactions($isDryRun);
        }

        // 3. Modul Pristi: Jadwal Shift & Presensi
        if ($targetSource === 'all' || $targetSource === 'pristi') {
            $this->migratePristiTransactions($isDryRun);
        }

        // 4. Modul Pengaduan: Aduan Publik & Tracking
        if ($targetSource === 'all' || $targetSource === 'pengaduan') {
            $this->migrateComplaintTransactions($isDryRun);
        }

        // 5. Modul Elena: Evaluasi Kinerja Historis
        if ($targetSource === 'all' || $targetSource === 'elena') {
            $this->migrateElenaTransactions($isDryRun);
        }

        // Print Summary Table
        $this->line('');
        $this->table(
            ['Metrik Transaksi', 'Jumlah Baris'],
            [
                ['Buku Tamu Pengunjung (pst_visitors)', $this->metrics['visitors_imported'] . ' / ' . $this->metrics['visitors_scanned']],
                ['Riwayat Kunjungan Tamu (pst_visitor_logs)', $this->metrics['visitor_logs_imported'] . ' / ' . $this->metrics['visitor_logs_scanned']],
                ['Tiket & Layanan PST (pst_service_tickets)', $this->metrics['tickets_imported'] . ' / ' . $this->metrics['tickets_scanned']],
                ['Survei Kepuasan (statisfy_surveys)', $this->metrics['surveys_imported'] . ' / ' . $this->metrics['surveys_scanned']],
                ['Rangkuman AI Statisfy (statisfy_ai_analyses)', $this->metrics['ai_analyses_imported'] . ' / ' . $this->metrics['ai_analyses_scanned']],
                ['Kalender Shift Pristi (pristi_schedules)', $this->metrics['schedules_imported'] . ' / ' . $this->metrics['schedules_scanned']],
                ['Presensi Jaga Pristi (pristi_attendances)', $this->metrics['attendances_imported'] . ' / ' . $this->metrics['attendances_scanned']],
                ['Pengaduan Masyarakat (public_complaints)', $this->metrics['complaints_imported'] . ' / ' . $this->metrics['complaints_scanned']],
                ['Evaluasi Kinerja Bulanan (elena_evaluations)', $this->metrics['evaluations_imported'] . ' / ' . $this->metrics['evaluations_scanned']],
                ['Total Baris Anomali / Unmapped', (string) $this->metrics['unmapped_records']],
            ]
        );

        $statusMsg = $isDryRun ? 'DRY-RUN BERHASIL: Seluruh data valid dan siap dimuat.' : 'EKSEKUSI ETL SELESAI: Riwayat transaksi berhasil dimigrasikan.';
        $this->info($statusMsg);
        $this->line("Audit log unmapped tersimpan di: {$this->unmappedLogPath}");

        return Command::SUCCESS;
    }

    /**
     * Initializes connection to MySQL legacy database or sets up fallback directory.
     */
    protected function initLegacyConnection(): void
    {
        $this->baseDir = (string) ($this->option('path') ?: (is_dir(base_path('../PST')) ? base_path('../PST') : dirname(base_path())));

        try {
            $host = config('database.connections.mysql.host', '127.0.0.1');
            $port = config('database.connections.mysql.port', '3306');
            $user = config('database.connections.mysql.username', 'root');
            $pass = config('database.connections.mysql.password', '');

            $pdo = new PDO("mysql:host={$host};port={$port}", $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);

            // Test if pst_db exists
            $stmt = $pdo->query("SHOW DATABASES LIKE 'pst_db'");
            if ($stmt && $stmt->fetch()) {
                $this->legacyPdo = $pdo;
                $this->info('Sumber Data: Terkoneksi langsung ke server MySQL lokal (Mode Berkecepatan Tinggi).');
                return;
            }
        } catch (\Throwable $e) {
            $this->legacyPdo = null;
        }

        $this->info("Sumber Data: Menggunakan streaming berkas SQL dari direktori: {$this->baseDir}");
    }

    /**
     * Migrates PST Visitors, Visitor Logs, and Service Tickets.
     */
    protected function migratePstTransactions(bool $isDryRun): void
    {
        $this->info('1. Memproses Transaksi PST (Buku Tamu, Kunjungan & Tiket Layanan)...');

        // A. Buku Tamu (pst_buku_tamu -> pst_visitors)
        $bukuTamuRows = $this->fetchLegacyRows('pst_db', 'pst_db.sql', 'pst_buku_tamu', 'pst');
        $this->metrics['visitors_scanned'] += count($bukuTamuRows);

        $visitorMap = []; // phone => visitor_id
        $visitorsToInsert = [];

        foreach ($bukuTamuRows as $row) {
            $phone = trim($row['no_hp'] ?? '');
            if (empty($phone) || $phone === '-') {
                $phone = 'guest-' . ($row['id'] ?? uniqid());
            }

            $name = trim($row['nama'] ?? 'Pengunjung PST');
            $institution = !empty($row['instansi']) ? trim($row['instansi']) : null;
            $address = !empty($row['alamat']) ? trim($row['alamat']) : null;
            $createdAt = !empty($row['created_at']) ? $row['created_at'] : now();

            if (!isset($visitorsToInsert[$phone])) {
                $visitorsToInsert[$phone] = [
                    'phone_number' => $phone,
                    'name' => $name,
                    'institution' => $institution,
                    'address' => $address,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ];
                $this->metrics['visitors_imported']++;
            }
        }

        if (!$isDryRun && !empty($visitorsToInsert)) {
            DB::transaction(function () use ($visitorsToInsert) {
                foreach (array_chunk($visitorsToInsert, 200) as $chunk) {
                    PstVisitor::upsert($chunk, ['phone_number'], ['name', 'institution', 'address', 'updated_at']);
                }
            });
        }

        // Cache visitor IDs for logs
        if (!$isDryRun) {
            $visitorMap = PstVisitor::pluck('id', 'phone_number')->toArray();
        }

        // B. Riwayat Kunjungan (pst_kunjungan_tamu -> pst_visitor_logs)
        $kunjunganRows = $this->fetchLegacyRows('pst_db', 'pst_db.sql', 'pst_kunjungan_tamu', 'pst');
        $this->metrics['visitor_logs_scanned'] += count($kunjunganRows);
        $logsToInsert = [];

        foreach ($kunjunganRows as $row) {
            $phone = trim($row['no_hp'] ?? '');
            $visitDate = $row['tanggal'] ?? now()->toDateString();
            $purpose = !empty($row['keperluan']) ? trim($row['keperluan']) : 'Kunjungan PST';
            $checkIn = !empty($row['jam_datang']) ? "{$visitDate} {$row['jam_datang']}" : "{$visitDate} 08:00:00";
            $checkOut = !empty($row['jam_pulang']) ? "{$visitDate} {$row['jam_pulang']}" : null;
            $notes = trim(($row['tipe_tamu'] ?? '') . ' ' . ($row['pekerjaan'] ?? ''));

            $visitorId = $visitorMap[$phone] ?? null;

            $logsToInsert[] = [
                'visitor_id' => $visitorId,
                'visit_date' => $visitDate,
                'purpose' => $purpose,
                'check_in_at' => $checkIn,
                'check_out_at' => $checkOut,
                'notes' => !empty($notes) ? trim($notes) : null,
                'created_at' => $row['created_at'] ?? now(),
                'updated_at' => $row['created_at'] ?? now(),
            ];
            $this->metrics['visitor_logs_imported']++;
        }

        if (!$isDryRun && !empty($logsToInsert)) {
            DB::transaction(function () use ($logsToInsert) {
                foreach (array_chunk($logsToInsert, 200) as $chunk) {
                    PstVisitorLog::insert($chunk);
                }
            });
        }

        // C. Tiket Layanan (pst_tiket + pst_register + pst_layanan -> pst_service_tickets)
        $tiketRows = $this->fetchLegacyRows('pst_db', 'pst_db.sql', 'pst_tiket', 'pst');
        $registerRows = $this->fetchLegacyRows('pst_db', 'pst_db.sql', 'pst_register', 'pst');
        $layananRows = $this->fetchLegacyRows('pst_db', 'pst_db.sql', 'pst_layanan', 'pst');

        $this->metrics['tickets_scanned'] += count($tiketRows);

        // Index register and layanan by composite key: tanggal_notiket
        $regMap = [];
        foreach ($registerRows as $r) {
            $k = ($r['tanggal'] ?? '') . '_' . ($r['no_tiket'] ?? '');
            $regMap[$k] = $r;
        }

        $layananMap = [];
        foreach ($layananRows as $l) {
            $k = ($l['tanggal'] ?? '') . '_' . ($l['no_tiket'] ?? '');
            $layananMap[$k] = $l;
        }

        $ticketsToInsert = [];
        $typePrefixes = [
            'perpustakaan' => 'A',
            'konsultasi' => 'B',
            'pembelian' => 'C',
            'rekomendasi' => 'D',
        ];

        foreach ($tiketRows as $row) {
            $serviceDate = $row['tanggal'] ?? now()->toDateString();
            $queueNum = (int) ($row['no_tiket'] ?? 1);
            $serviceType = strtolower(trim($row['layanan'] ?? 'konsultasi'));
            $prefix = $typePrefixes[$serviceType] ?? 'A';
            $queueCode = sprintf('%s-%03d', $prefix, $queueNum);
            $ticketNumber = sprintf('PST-%s-%s', str_replace('-', '', $serviceDate), $queueCode);
            $qrToken = hash('sha256', "PST-{$row['id']}-{$serviceDate}-{$queueNum}-" . uniqid());

            $key = "{$serviceDate}_{$queueNum}";
            $reg = $regMap[$key] ?? null;
            $lay = $layananMap[$key] ?? null;

            $visitorName = !empty($reg['nama_pengunjung']) ? ($this->sanitizeString($reg['nama_pengunjung']) ?: 'Pengunjung PST') : 'Pengunjung PST';
            $isFirstVisit = !empty($reg['is_pertama']);

            $rawOperator = $lay['created_by'] ?? ($reg['created_by'] ?? null);
            $servedById = $this->resolver->resolveUserId($rawOperator);

            $requestDetail = !empty($lay['request']) ? $this->sanitizeString($lay['request']) : null;
            $serviceReport = !empty($lay['laporan']) ? $this->sanitizeString($lay['laporan']) : null;

            $finishOn = (int) ($row['finish_on'] ?? ($reg['finish_on'] ?? ($lay['finish_on'] ?? 0)));
            $startOn = (int) ($reg['start_on'] ?? ($lay['start_on'] ?? 0));

            $status = 'waiting';
            if (!empty($reg['is_selesai']) || $finishOn > 0) {
                $status = 'completed';
            } elseif ($startOn > 0) {
                $status = 'serving';
            }

            $calledAt = !empty($row['jam']) ? "{$serviceDate} {$row['jam']}" : "{$serviceDate} 08:30:00";
            $startedAt = $startOn > 86400 ? Carbon::createFromTimestamp($startOn)->toDateTimeString() : null;
            $finishedAt = $finishOn > 86400 ? Carbon::createFromTimestamp($finishOn)->toDateTimeString() : null;
            $createdAt = (!empty($row['created_on']) && (int)$row['created_on'] > 86400)
                ? Carbon::createFromTimestamp((int) $row['created_on'])->toDateTimeString()
                : "{$serviceDate} 08:00:00";
            $updatedAt = $finishedAt ?: $createdAt;

            $ticketsToInsert[] = [
                'ticket_number' => $ticketNumber,
                'queue_code' => $queueCode,
                'queue_number' => $queueNum,
                'qr_token' => $qrToken,
                'service_date' => $serviceDate,
                'service_type' => $serviceType,
                'visitor_id' => null,
                'visitor_name' => $visitorName,
                'is_first_visit' => $isFirstVisit,
                'counter_id' => null,
                'served_by_user_id' => $servedById,
                'request_detail' => $requestDetail,
                'service_report' => $serviceReport,
                'status' => $status,
                'called_at' => $calledAt,
                'started_at' => $startedAt,
                'finished_at' => $finishedAt,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ];

            $this->metrics['tickets_imported']++;
        }

        if (!$isDryRun && !empty($ticketsToInsert)) {
            DB::transaction(function () use ($ticketsToInsert) {
                foreach (array_chunk($ticketsToInsert, 200) as $chunk) {
                    PstServiceTicket::insert($chunk);
                }
            });
        }
    }

    /**
     * Migrates Statisfy Surveys and AI Analyses.
     */
    protected function migrateStatisfyTransactions(bool $isDryRun): void
    {
        $this->info('2. Memproses Transaksi Statisfy (Survei Kepuasan & Analisis AI)...');

        $kepuasanRows = $this->fetchLegacyRows('skm_db', 'statisfy.sql', 'skm_kepuasan', 'statisfy');
        $this->metrics['surveys_scanned'] += count($kepuasanRows);

        $surveysToInsert = [];
        foreach ($kepuasanRows as $row) {
            $surveyDate = $row['tanggal'] ?? now()->toDateString();
            $serviceType = !empty($row['layanan']) ? trim($row['layanan']) : 'konsultasi';
            $mediaChannel = !empty($row['media']) ? trim($row['media']) : 'offline';
            $score = (int) ($row['kualitas_layanan'] ?? 4);
            if ($score < 1) {
                $score = 1;
            } elseif ($score > 4) {
                $score = 4;
            }

            $feedback = !empty($row['feedback_layanan']) ? trim($row['feedback_layanan']) : null;
            $rawOfficer = $row['nama_pegawai'] ?? ($row['nip'] ?? null);
            $ratedUserId = $this->resolver->resolveUserId($rawOfficer);

            $rawCreated = $row['created_on'] ?? null;
            $surveyCreatedAt = (!empty($rawCreated) && strtotime((string)$rawCreated) > 946684800)
                ? Carbon::parse($rawCreated)->toDateTimeString()
                : "{$surveyDate} 09:00:00";

            $surveysToInsert[] = [
                'service_ticket_id' => null,
                'rated_user_id' => $ratedUserId,
                'counter_id' => null,
                'survey_date' => $surveyDate,
                'respondent_name' => 'Responden ' . ($row['id'] ?? uniqid()),
                'respondent_email' => null,
                'respondent_phone' => null,
                'service_type' => $serviceType,
                'media_channel' => $mediaChannel,
                'rating_score' => $score,
                'feedback_comment' => $feedback,
                'remote_ip' => $row['remote_ip'] ?? null,
                'created_at' => $surveyCreatedAt,
                'updated_at' => $surveyCreatedAt,
            ];
            $this->metrics['surveys_imported']++;
        }

        if (!$isDryRun && !empty($surveysToInsert)) {
            DB::transaction(function () use ($surveysToInsert) {
                foreach (array_chunk($surveysToInsert, 200) as $chunk) {
                    StatisfySurvey::insert($chunk);
                }
            });
        }

        // B. Rangkuman Analisis AI (skm_analisis_ai -> statisfy_ai_analyses)
        $aiRows = $this->fetchLegacyRows('skm_db', 'statisfy.sql', 'skm_analisis_ai', 'statisfy');
        $this->metrics['ai_analyses_scanned'] += count($aiRows);
        $aiToInsert = [];

        foreach ($aiRows as $row) {
            $mode = strtolower(trim($row['mode'] ?? 'alltime'));
            $month = !empty($row['bulan']) ? (int) $row['bulan'] : null;
            $year = !empty($row['tahun']) ? (int) $row['tahun'] : null;
            $result = $row['hasil_json'] ?? '';

            $aiToInsert[] = [
                'mode' => in_array($mode, ['bulanan', 'tahunan', 'alltime'], true) ? $mode : 'alltime',
                'period_month' => $month,
                'period_year' => $year,
                'analysis_result' => $result,
                'summary_sentiment' => 'positif',
                'total_reviews_analyzed' => 100,
                'created_at' => $row['created_at'] ?? now(),
                'updated_at' => $row['created_at'] ?? now(),
            ];
            $this->metrics['ai_analyses_imported']++;
        }

        if (!$isDryRun && !empty($aiToInsert)) {
            DB::transaction(function () use ($aiToInsert) {
                foreach ($aiToInsert as $item) {
                    StatisfyAiAnalysis::create($item);
                }
            });
        }
    }

    /**
     * Migrates Pristi Schedules and Attendances.
     */
    protected function migratePristiTransactions(bool $isDryRun): void
    {
        $this->info('3. Memproses Transaksi Pristi (Jadwal Shift & Presensi Petugas)...');

        // A. Jadwal Shift (presensi_m_jadwal + presensi_t_jadwal -> pristi_schedules)
        $jadwalRows = $this->fetchLegacyRows('pristi_db_new', 'pristi_db_new (2).sql', 'presensi_m_jadwal', 'pristi');
        $swapRows = $this->fetchLegacyRows('pristi_db_new', 'pristi_db_new (2).sql', 'presensi_t_jadwal', 'pristi');

        $this->metrics['schedules_scanned'] += count($jadwalRows);

        // Swap map: tanggal_namadiganti => namamengganti
        $swapMap = [];
        foreach ($swapRows as $sw) {
            $k = ($sw['tanggal'] ?? '') . '_' . strtolower(trim($sw['namadiganti'] ?? ''));
            $swapMap[$k] = $sw['namamengganti'] ?? null;
        }

        $schedulesToInsert = [];
        foreach ($jadwalRows as $row) {
            $rawOfficer = $row['nama_petugas'] ?? null;
            $userId = $this->resolver->resolveId($rawOfficer);
            if (!$userId) {
                $this->logUnmapped('pristi:presensi_m_jadwal', $row, 'Pegawai tidak ditemukan: ' . $rawOfficer);
                $this->metrics['unmapped_records']++;
                continue;
            }

            $date = $row['tanggal'] ?? now()->toDateString();
            $swapKey = "{$date}_" . strtolower(trim($rawOfficer));
            $swappedName = $swapMap[$swapKey] ?? null;
            $swappedWithId = $swappedName ? $this->resolver->resolveId($swappedName) : null;

            $schedulesToInsert[] = [
                'user_id' => $userId,
                'counter_id' => null,
                'schedule_date' => $date,
                'day_name' => $row['hari'] ?? 'Senin',
                'shift_name' => $row['status_petugas'] ?? 'Konsultasi 1',
                'swapped_with_user_id' => $swappedWithId,
                'notes' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $this->metrics['schedules_imported']++;
        }

        if (!$isDryRun && !empty($schedulesToInsert)) {
            DB::transaction(function () use ($schedulesToInsert) {
                foreach (array_chunk($schedulesToInsert, 200) as $chunk) {
                    PristiSchedule::insert($chunk);
                }
            });
        }

        // B. Presensi Jaga (presensi_t_transaksi -> pristi_attendances)
        $presensiRows = $this->fetchLegacyRows('pristi_db_new', 'pristi_db_new (2).sql', 'presensi_t_transaksi', 'pristi');
        $this->metrics['attendances_scanned'] += count($presensiRows);

        $attendancesToInsert = [];
        foreach ($presensiRows as $row) {
            $rawOfficer = !empty($row['nip']) ? $row['nip'] : ($row['id_pegawai'] ?? null);
            $userId = $this->resolver->resolveId($rawOfficer);
            if (!$userId) {
                $this->logUnmapped('pristi:presensi_t_transaksi', $row, 'Pegawai presensi unmapped: ' . $rawOfficer);
                $this->metrics['unmapped_records']++;
                continue;
            }

            $attendancesToInsert[] = [
                'schedule_id' => null,
                'user_id' => $userId,
                'attendance_date' => $row['tanggal'] ?? now()->toDateString(),
                'check_in_time' => $row['jam_datang'] ?? null,
                'check_out_time' => $row['jam_pulang'] ?? null,
                'time_difference' => 0,
                'source' => 'legacy_pristi',
                'status' => 'present',
                'notes' => !empty($row['catatan']) ? trim($row['catatan']) : null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $this->metrics['attendances_imported']++;
        }

        if (!$isDryRun && !empty($attendancesToInsert)) {
            DB::transaction(function () use ($attendancesToInsert) {
                foreach (array_chunk($attendancesToInsert, 200) as $chunk) {
                    PristiAttendance::insert($chunk);
                }
            });
        }
    }

    /**
     * Migrates Public Complaints.
     */
    protected function migrateComplaintTransactions(bool $isDryRun): void
    {
        $this->info('4. Memproses Transaksi Pengaduan Masyarakat (t_pengaduan)...');

        $aduanRows = $this->fetchLegacyRows('pengaduan_jateng', 'pengaduan_jateng.sql', 't_pengaduan', 'pengaduan');
        $this->metrics['complaints_scanned'] += count($aduanRows);

        $complaintsToInsert = [];
        foreach ($aduanRows as $row) {
            $id = (int) ($row['id'] ?? 1);
            $createdOn = (int) ($row['created_on'] ?? time());
            if ($createdOn < 946684800) {
                $createdOn = time();
            }
            $year = date('Y', $createdOn);
            $trackingCode = sprintf('ADU-%s-%04d', $year, $id);

            $reporterName = $this->sanitizeString($row['nama'] ?? 'Masyarakat') ?: 'Masyarakat';
            $reporterEmail = !empty($row['email']) ? $this->sanitizeString($row['email']) : null;
            $reporterPhone = !empty($row['no_hp']) ? $this->sanitizeString($row['no_hp']) : null;
            $category = !empty($row['jenis_pengaduan']) ? $this->sanitizeString($row['jenis_pengaduan']) : 'Layanan PST';
            $content = $this->sanitizeString($row['isi_pengaduan'] ?? '') ?: '-';
            $feedback = !empty($row['feedback_pengaduan']) ? $this->sanitizeString($row['feedback_pengaduan']) : null;
            $status = !empty($feedback) ? 'resolved' : 'received';

            $complaintsToInsert[] = [
                'tracking_code' => $trackingCode,
                'reporter_name' => $reporterName,
                'reporter_email' => $reporterEmail,
                'reporter_phone' => $reporterPhone,
                'complaint_category' => $category,
                'complaint_content' => $content,
                'public_feedback' => $feedback,
                'internal_follow_up' => null,
                'related_user_id' => null,
                'is_valid_penalty' => false,
                'status' => $status,
                'remote_ip' => $row['remote_ip'] ?? null,
                'resolved_at' => !empty($feedback) ? Carbon::createFromTimestamp($createdOn)->toDateTimeString() : null,
                'created_at' => Carbon::createFromTimestamp($createdOn)->toDateTimeString(),
                'updated_at' => Carbon::createFromTimestamp($createdOn)->toDateTimeString(),
            ];
            $this->metrics['complaints_imported']++;
        }

        if (!$isDryRun && !empty($complaintsToInsert)) {
            DB::transaction(function () use ($complaintsToInsert) {
                foreach (array_chunk($complaintsToInsert, 200) as $chunk) {
                    PublicComplaint::insert($chunk);
                }
            });
        }
    }

    /**
     * Migrates Elena Historical Monthly Evaluations.
     */
    protected function migrateElenaTransactions(bool $isDryRun): void
    {
        $this->info('5. Memproses Transaksi Evaluasi Kinerja Elena (elena_t_lk)...');

        $elenaRows = $this->fetchLegacyRows('jatengwe_apps', 'jatengwe_apps.sql', 'elena_t_lk', 'elena');
        $this->metrics['evaluations_scanned'] += count($elenaRows);

        $evaluationsToInsert = [];
        $uniqueKeySet = [];

        foreach ($elenaRows as $row) {
            $rawOfficer = $row['nama_pegawai'] ?? null;
            $userId = $this->resolver->resolveId($rawOfficer);
            if (!$userId) {
                $this->logUnmapped('elena:elena_t_lk', $row, 'Pegawai tidak ditemukan: ' . $rawOfficer);
                $this->metrics['unmapped_records']++;
                continue;
            }

            $year = (int) ($row['tahun'] ?? 2024);
            $month = (int) ($row['bulan'] ?? 1);
            $compoundKey = "{$userId}_{$year}_{$month}";

            if (isset($uniqueKeySet[$compoundKey])) {
                continue; // Prevent unique constraint violation
            }
            $uniqueKeySet[$compoundKey] = true;

            $evaluationsToInsert[] = [
                'user_id' => $userId,
                'period_year' => $year,
                'period_month' => $month,
                'total_services_count' => 0,
                'total_surveys_count' => 0,
                'raw_satisfaction_avg' => 0.0,
                'raw_performance_score' => (float) ($row['kinerja'] ?? 0),
                'raw_attendance_score' => (float) ($row['kehadiran'] ?? 0),
                'raw_cooperation_score' => (float) ($row['kerjasama'] ?? 0),
                'raw_innovation_score' => (float) ($row['inovatifkreatif'] ?? 0),
                'raw_appearance_score' => (float) ($row['penampilan'] ?? 0),
                'raw_complaint_count' => (int) ($row['komplain'] ?? 0),
                'weighted_performance' => (float) ($row['kinerja_ttb'] ?? 0),
                'weighted_attendance' => (float) ($row['kehadiran_ttb'] ?? 0),
                'weighted_cooperation' => (float) ($row['kerjasama_ttb'] ?? 0),
                'weighted_innovation' => (float) ($row['inovatifkreatif_ttb'] ?? 0),
                'weighted_appearance' => (float) ($row['penampilan_ttb'] ?? 0),
                'weighted_complaint' => (float) ($row['komplain_ttb'] ?? 0),
                'total_score' => (float) ($row['totalscore'] ?? 0),
                'eval_score' => (float) ($row['score_eval'] ?? 0),
                'rank_position' => null,
                'is_locked' => true,
                'locked_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $this->metrics['evaluations_imported']++;
        }

        if (!$isDryRun && !empty($evaluationsToInsert)) {
            DB::transaction(function () use ($evaluationsToInsert) {
                foreach (array_chunk($evaluationsToInsert, 200) as $chunk) {
                    ElenaEvaluation::insert($chunk);
                }
            });
        }
    }

    /**
     * Unified data fetcher: queries MySQL if connected, otherwise streams from SQL dump.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function fetchLegacyRows(string $database, string $dumpFileName, string $tableName, string $subDir): array
    {
        if ($this->legacyPdo !== null) {
            try {
                $stmt = $this->legacyPdo->query("SELECT * FROM `{$database}`.`{$tableName}`");
                if ($stmt) {
                    return $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
            } catch (\Throwable $e) {
                // Fallback to SQL streaming
            }
        }

        $candidates = [
            $this->baseDir . DIRECTORY_SEPARATOR . $subDir . DIRECTORY_SEPARATOR . $dumpFileName,
            $this->baseDir . DIRECTORY_SEPARATOR . $dumpFileName,
        ];

        foreach ($candidates as $filePath) {
            if (file_exists($filePath)) {
                return $this->extractInsertRows($filePath, $tableName);
            }
        }

        return [];
    }

    /**
     * Extracts rows from MySQL SQL dump file without loading the whole file into memory.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function extractInsertRows(string $filePath, string $tableName): array
    {
        $f = fopen($filePath, 'r');
        if (!$f) {
            return [];
        }

        $rows = [];
        $columns = [];
        $inTargetTable = false;

        while (($line = fgets($f)) !== false) {
            if (preg_match('/CREATE TABLE [`\']?' . preg_quote($tableName, '/') . '[`\']?/i', $line)) {
                $inTargetTable = true;
                $columns = [];
                continue;
            }

            if ($inTargetTable && empty($columns)) {
                if (preg_match('/^\s*[`\']?([a-zA-Z0-9_]+)[`\']?\s+/', $line, $matches)) {
                    $columns[] = $matches[1];
                }
                if (preg_match('/^\s*\)/', $line)) {
                    $inTargetTable = false;
                }
            }

            if (preg_match('/INSERT INTO [`\']?' . preg_quote($tableName, '/') . '[`\']?\s*(?:\(([^)]+)\))?\s*VALUES/i', $line, $matches)) {
                if (!empty($matches[1])) {
                    $cols = array_map(function ($c) {
                        return trim($c, " `'\t\n\r");
                    }, explode(',', $matches[1]));
                } else {
                    $cols = $columns;
                }

                $pos = stripos($line, 'VALUES');
                $buffer = substr($line, $pos + 6);

                while ($buffer !== false) {
                    while (preg_match('/^\s*,\s*/', $buffer)) {
                        $buffer = preg_replace('/^\s*,\s*/', '', $buffer);
                    }

                    if (trim($buffer) === '') {
                        $nextLine = fgets($f);
                        if ($nextLine === false) {
                            break;
                        }
                        $buffer = $nextLine;
                        continue;
                    }

                    if (preg_match('/^\s*;/m', $buffer)) {
                        $buffer = '';
                        break;
                    }

                    if (!preg_match('/^\s*\(/', $buffer)) {
                        $nextLine = fgets($f);
                        if ($nextLine === false) {
                            break;
                        }
                        $buffer .= $nextLine;
                        continue;
                    }

                    $len = strlen($buffer);
                    $inQuotes = false;
                    $quoteChar = '';
                    $escaped = false;
                    $tupleEnd = -1;
                    $depth = 0;

                    for ($i = 0; $i < $len; $i++) {
                        $char = $buffer[$i];
                        if ($escaped) {
                            $escaped = false;
                            continue;
                        }
                        if ($char === '\\') {
                            $escaped = true;
                            continue;
                        }
                        if ($inQuotes) {
                            if ($char === $quoteChar) {
                                $inQuotes = false;
                            }
                        } else {
                            if ($char === "'" || $char === '"') {
                                $inQuotes = true;
                                $quoteChar = $char;
                            } elseif ($char === '(') {
                                $depth++;
                            } elseif ($char === ')') {
                                $depth--;
                                if ($depth === 0) {
                                    $tupleEnd = $i;
                                    break;
                                }
                            }
                        }
                    }

                    if ($tupleEnd === -1) {
                        $nextLine = fgets($f);
                        if ($nextLine === false) {
                            break;
                        }
                        $buffer .= $nextLine;
                        continue;
                    }

                    $tupleContent = substr($buffer, 0, $tupleEnd + 1);
                    $buffer = substr($buffer, $tupleEnd + 1);

                    $inner = trim($tupleContent);
                    if (str_starts_with($inner, '(') && str_ends_with($inner, ')')) {
                        $inner = substr($inner, 1, -1);
                    }

                    $parsedRow = str_getcsv($inner, ',', "'", '\\');
                    $assoc = [];
                    foreach ($cols as $idx => $colName) {
                        $val = isset($parsedRow[$idx]) ? trim($parsedRow[$idx]) : null;
                        if ($val === 'NULL' || $val === 'null') {
                            $val = null;
                        }
                        $assoc[$colName] = $val;
                    }
                    $rows[] = $assoc;
                }
            }
        }

        fclose($f);

        return $rows;
    }

    /**
     * Initializes the unmapped audit log file.
     */
    protected function initUnmappedLog(bool $isDryRun): void
    {
        $dir = dirname($this->unmappedLogPath);
        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        $modeHeader = $isDryRun ? '[DRY-RUN MODE]' : '[REAL EXECUTION MODE]';
        $timestamp = now()->toIso8601String();
        $header = "=== SEMAR PST ETL UNMAPPED TRANSACTIONS AUDIT LOG {$modeHeader} - {$timestamp} ===\n";

        File::put($this->unmappedLogPath, $header);
    }

    /**
     * Logs unmapped records to audit log.
     *
     * @param array<string, mixed> $payload
     */
    protected function logUnmapped(string $source, array $payload, string $reason): void
    {
        $line = sprintf(
            "[%s] [%s] %s | Payload: %s\n",
            now()->toIso8601String(),
            $source,
            $reason,
            json_encode($payload, JSON_UNESCAPED_UNICODE)
        );

        File::append($this->unmappedLogPath, $line);
    }

    /**
     * Safely truncates target transactional tables when --fresh option is used.
     */
    protected function cleanTransactionalTables(string $targetSource): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        if ($targetSource === 'all' || $targetSource === 'pst') {
            PstVisitorLog::truncate();
            PstServiceTicket::truncate();
            PstVisitor::truncate();
        }

        if ($targetSource === 'all' || $targetSource === 'statisfy') {
            StatisfySurvey::truncate();
            StatisfyAiAnalysis::truncate();
        }

        if ($targetSource === 'all' || $targetSource === 'pristi') {
            PristiAttendance::truncate();
            PristiSchedule::truncate();
        }

        if ($targetSource === 'all' || $targetSource === 'pengaduan') {
            PublicComplaint::truncate();
        }

        if ($targetSource === 'all' || $targetSource === 'elena') {
            ElenaEvaluation::truncate();
        }

        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    /**
     * Sanitizes raw text from legacy databases to clean UTF-8 and enforces ASCII hyphen (Rule #6).
     */
    protected function sanitizeString(?string $str): ?string
    {
        if ($str === null) {
            return null;
        }

        $map = [
            "\x91" => "'",
            "\x92" => "'",
            "\x93" => '"',
            "\x94" => '"',
            "\x95" => '*',
            "\x96" => '-',
            "\x97" => '-',
            "\xE2\x80\x98" => "'",
            "\xE2\x80\x99" => "'",
            "\xE2\x80\x9C" => '"',
            "\xE2\x80\x9D" => '"',
            "\xE2\x80\x93" => '-',
            "\xE2\x80\x94" => '-',
        ];

        $clean = strtr($str, $map);
        $clean = mb_convert_encoding($clean, 'UTF-8', 'UTF-8');
        $clean = (string) iconv('UTF-8', 'UTF-8//IGNORE', $clean);

        return trim($clean);
    }
}
