<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

/**
 * Command ETL Migrasi & Deduplikasi Master Pegawai BPS (BE-06).
 *
 * ID:
 * Perintah migrasi Extract-Transform-Load (ETL) untuk mengekstraksi, menormalisasi, dan
 * mendeduplikasi master data pegawai dari 5 basis data warisan (Elena, Pristi, Pengaduan,
 * dan Statisfy) ke dalam tabel tunggal `users`. Mendukung opsi simulasi `--dry-run` serta
 * audit log untuk data yang anomali/tidak terpetakan sesuai Aturan #7.
 *
 * EN:
 * ETL migration command to extract, normalize, and deduplicate master personnel data from
 * 5 legacy database SQL dumps (Elena, Pristi, Pengaduan, and Statisfy) into the unified
 * `users` table. Supports `--dry-run` simulation mode and logs unmapped records to a
 * dedicated audit log file per Golden Rule #7.
 */
class EtlMasterUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'semar:etl-users
                            {--dry-run : Simulate extraction and deduplication without altering the database}
                            {--path= : Custom base directory containing legacy project folders}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deduplicate and import master BPS employees from legacy systems into users table';

    /**
     * Dedicated log file path for unmapped records (Golden Rule #7).
     */
    protected string $unmappedLogPath;

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $isDryRun = (bool) $this->option('dry-run');
        $this->unmappedLogPath = storage_path('logs/etl_unmapped_users.log');

        $this->info('===========================================================');
        $this->info('  SEMAR PST - ETL Master Pegawai BPS (BE-06)');
        $this->info('===========================================================');

        if ($isDryRun) {
            $this->warn('MODE: DRY-RUN (Simulasi aktif, tidak ada perubahan basis data).');
        } else {
            $this->alert('MODE: EKSEKUSI RIIL (Data akan disimpan ke tabel users).');
        }

        // Initialize unmapped log file
        $this->initUnmappedLog($isDryRun);

        // Determine base path
        $baseDir = $this->option('path') ?: (is_dir(base_path('../PST')) ? base_path('../PST') : dirname(base_path()));

        $pengaduanPath = $baseDir . DIRECTORY_SEPARATOR . 'pengaduan' . DIRECTORY_SEPARATOR . 'pengaduan_jateng.sql';
        if (!file_exists($pengaduanPath)) {
            $pengaduanPath = $baseDir . DIRECTORY_SEPARATOR . 'pengaduan.jateng.pro' . DIRECTORY_SEPARATOR . 'pengaduan_jateng.sql';
        }
        if (!file_exists($pengaduanPath)) {
            $pengaduanPath = $baseDir . DIRECTORY_SEPARATOR . 'pengaduan_jateng.sql';
        }

        $sources = [
            'elena' => $baseDir . DIRECTORY_SEPARATOR . 'elena' . DIRECTORY_SEPARATOR . 'jatengwe_apps.sql',
            'pristi' => $baseDir . DIRECTORY_SEPARATOR . 'pristi' . DIRECTORY_SEPARATOR . 'pristi_db_new (2).sql',
            'pengaduan' => $pengaduanPath,
            'statisfy' => $baseDir . DIRECTORY_SEPARATOR . 'statisfy' . DIRECTORY_SEPARATOR . 'statisfy.sql',
        ];

        // Validate source file existence
        foreach ($sources as $sys => $filePath) {
            if (!file_exists($filePath)) {
                $this->error("Berkas SQL untuk $sys tidak ditemukan: $filePath");
                return Command::FAILURE;
            }
        }

        $this->line('');
        $this->info('1. Membaca data master dash_pegawai dari Elena...');
        $elenaPegawai = $this->extractInsertRows($sources['elena'], 'dash_pegawai');
        $this->line("   - Terbaca {$elenaPegawai['count']} baris.");

        $this->info('2. Membaca data master dash_pegawai dari Pristi...');
        $pristiPegawai = $this->extractInsertRows($sources['pristi'], 'dash_pegawai');
        $this->line("   - Terbaca {$pristiPegawai['count']} baris.");

        $this->info('3. Membaca data tim dari elena_m_petugas...');
        $elenaPetugas = $this->extractInsertRows($sources['elena'], 'elena_m_petugas');
        $this->line("   - Terbaca {$elenaPetugas['count']} baris.");

        $this->info('4. Membaca data nama tanpa gelar dari skm_petugas_pst (Statisfy)...');
        $statisfyPetugas = $this->extractInsertRows($sources['statisfy'], 'skm_petugas_pst');
        $this->line("   - Terbaca {$statisfyPetugas['count']} baris.");

        $this->info('5. Membaca data admin dari t_pengaduan_admin (Pengaduan)...');
        $pengaduanAdmin = $this->extractInsertRows($sources['pengaduan'], 't_pengaduan_admin');
        $this->line("   - Terbaca {$pengaduanAdmin['count']} baris.");

        $this->line('');
        $this->info('6. Menjalankan deduplikasi dan pengayaan atribut pegawai...');

        // Map employees keyed by nipbaru (18 digits)
        $masterEmployees = [];
        $unmappedCount = 0;
        $pristiNewCount = 0;
        $pristiMergedCount = 0;
        $statisfyNewCount = 0;

        // Process Elena dash_pegawai
        foreach ($elenaPegawai['rows'] as $row) {
            $nipBaru = trim($row['nipbaru'] ?? '');
            if (empty($nipBaru) || strlen($nipBaru) < 9) {
                $this->logUnmapped('elena:dash_pegawai', $row, 'NIP Baru kosong atau tidak valid');
                $unmappedCount++;
                continue;
            }

            $email = trim($row['email'] ?? '');
            if (empty($email)) {
                $email = "pegawai.{$nipBaru}@bps.go.id";
            }

            $masterEmployees[$nipBaru] = [
                'nip_baru' => $nipBaru,
                'nip_lama' => trim($row['niplama'] ?? null) ?: null,
                'name' => trim($row['nama'] ?? 'Pegawai BPS'),
                'name_without_title' => null,
                'email' => strtolower($email),
                'whatsapp_number' => trim($row['no_wa'] ?? null) ?: null,
                'region_code' => trim($row['id_wilayah'] ?? null) ?: null,
                'unit_code' => trim($row['id_unitkerja'] ?? null) ?: null,
                'unit_name' => trim($row['unitkerja'] ?? null) ?: null,
                'echelon_id' => trim($row['id_eselon'] ?? null) ?: null,
                'elena_team_code' => null,
                'elena_calc_code' => null,
                'is_active' => (bool) ($row['is_aktif'] ?? true),
                'source' => 'elena',
            ];
        }

        // Process Pristi dash_pegawai (Deduplicate & Merge)
        foreach ($pristiPegawai['rows'] as $row) {
            $nipBaru = trim($row['nipbaru'] ?? '');
            if (empty($nipBaru) || strlen($nipBaru) < 9) {
                $this->logUnmapped('pristi:dash_pegawai', $row, 'NIP Baru kosong atau tidak valid');
                $unmappedCount++;
                continue;
            }

            if (isset($masterEmployees[$nipBaru])) {
                // Enrich existing employee if attributes missing
                $pristiMergedCount++;
                if (empty($masterEmployees[$nipBaru]['whatsapp_number']) && !empty($row['no_wa'])) {
                    $masterEmployees[$nipBaru]['whatsapp_number'] = trim($row['no_wa']);
                }
                if (empty($masterEmployees[$nipBaru]['unit_name']) && !empty($row['unitkerja'])) {
                    $masterEmployees[$nipBaru]['unit_name'] = trim($row['unitkerja']);
                }
            } else {
                // New employee found only in Pristi
                $pristiNewCount++;
                $email = trim($row['email'] ?? '');
                if (empty($email)) {
                    $email = "pegawai.{$nipBaru}@bps.go.id";
                }

                $masterEmployees[$nipBaru] = [
                    'nip_baru' => $nipBaru,
                    'nip_lama' => trim($row['niplama'] ?? null) ?: null,
                    'name' => trim($row['nama'] ?? 'Pegawai BPS'),
                    'name_without_title' => null,
                    'email' => strtolower($email),
                    'whatsapp_number' => trim($row['no_wa'] ?? null) ?: null,
                    'region_code' => trim($row['id_wilayah'] ?? null) ?: null,
                    'unit_code' => trim($row['id_unitkerja'] ?? null) ?: null,
                    'unit_name' => trim($row['unitkerja'] ?? null) ?: null,
                    'echelon_id' => trim($row['id_eselon'] ?? null) ?: null,
                    'elena_team_code' => null,
                    'elena_calc_code' => null,
                    'is_active' => (bool) ($row['is_aktif'] ?? true),
                    'source' => 'pristi',
                ];
            }
        }

        // 7. Enrich and import active PST officers from Statisfy (skm_petugas_pst)
        $statisfyEnrichedCount = 0;
        foreach ($statisfyPetugas['rows'] as $row) {
            $nipBaru = trim($row['nip_baru'] ?? '');
            $nipLama = trim($row['nip'] ?? '');
            $namaTanpaGelar = trim($row['nama_tanpagelar'] ?? '');
            $nama = trim($row['nama'] ?? '');

            if (!empty($nipBaru) && strlen($nipBaru) === 18) {
                if (isset($masterEmployees[$nipBaru])) {
                    $masterEmployees[$nipBaru]['name_without_title'] = $namaTanpaGelar ?: null;
                    if (empty($masterEmployees[$nipBaru]['nip_lama']) && !empty($nipLama)) {
                        $masterEmployees[$nipBaru]['nip_lama'] = $nipLama;
                    }
                    $statisfyEnrichedCount++;
                } else {
                    // New active officer from Statisfy
                    $statisfyNewCount++;
                    $masterEmployees[$nipBaru] = [
                        'nip_baru' => $nipBaru,
                        'nip_lama' => $nipLama ?: null,
                        'name' => $nama ?: 'Petugas PST',
                        'name_without_title' => $namaTanpaGelar ?: null,
                        'email' => "petugas.{$nipBaru}@bps.go.id",
                        'whatsapp_number' => null,
                        'region_code' => '3300',
                        'unit_code' => '33000',
                        'unit_name' => trim($row['tim'] ?? 'Pelayanan Statistik Terpadu'),
                        'echelon_id' => null,
                        'elena_team_code' => null,
                        'elena_calc_code' => null,
                        'is_active' => true,
                        'source' => 'statisfy',
                    ];
                }
            } else {
                $this->logUnmapped('statisfy:skm_petugas_pst', $row, 'NIP Baru kosong atau tidak valid (18 digit required)');
                $unmappedCount++;
            }
        }

        // 8. Enrich with Elena Petugas (elena_m_petugas) for team & calc codes
        $elenaEnrichedCount = 0;
        $nameLookup = [];
        foreach ($masterEmployees as $nip => $emp) {
            $cleanName = $this->normalizeName($emp['name']);
            $nameLookup[$cleanName] = $nip;
        }

        foreach ($elenaPetugas['rows'] as $row) {
            $namaPetugas = trim($row['nama_pegawai'] ?? '');
            $cleanName = $this->normalizeName($namaPetugas);

            $matchedNip = $nameLookup[$cleanName] ?? null;

            if (!$matchedNip) {
                foreach ($nameLookup as $empName => $empNip) {
                    if (str_contains($empName, $cleanName) || str_contains($cleanName, $empName)) {
                        $matchedNip = $empNip;
                        break;
                    }
                }
            }

            if ($matchedNip && isset($masterEmployees[$matchedNip])) {
                $masterEmployees[$matchedNip]['elena_team_code'] = trim($row['kode_tim'] ?? null);
                $masterEmployees[$matchedNip]['elena_calc_code'] = trim($row['kodehitung'] ?? null);
                $elenaEnrichedCount++;
            } else {
                $this->logUnmapped('elena:elena_m_petugas', $row, "Nama petugas '{$namaPetugas}' tidak cocok dengan master pegawai");
                $unmappedCount++;
            }
        }

        // 9. Enrich with Pengaduan Admin (t_pengaduan_admin)
        $pengaduanEnrichedCount = 0;
        foreach ($pengaduanAdmin['rows'] as $row) {
            $nip = trim($row['nip'] ?? '');
            if (!empty($nip) && isset($masterEmployees[$nip])) {
                $pengaduanEnrichedCount++;
            } else {
                // Try searching by NIP Lama
                $found = false;
                foreach ($masterEmployees as $empNip => $emp) {
                    if ($emp['nip_lama'] === $nip) {
                        $pengaduanEnrichedCount++;
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $this->logUnmapped('pengaduan:t_pengaduan_admin', $row, "NIP admin '{$nip}' tidak terdaftar di master pegawai");
                    $unmappedCount++;
                }
            }
        }

        // Display Summary Table
        $this->line('');
        $this->table(
            ['Metrik ETL', 'Nilai'],
            [
                ['Pegawai Dipindai dari Elena (dash_pegawai)', $elenaPegawai['count']],
                ['Pegawai Dipindai dari Pristi (dash_pegawai)', $pristiPegawai['count']],
                ['Pegawai Pristi Berhasil Dimerge (Deduplikasi)', $pristiMergedCount],
                ['Pegawai Baru Khusus Pristi', $pristiNewCount],
                ['Pegawai Baru Khusus Statisfy (Petugas PST)', $statisfyNewCount],
                ['Pegawai Diperkaya Atribut Statisfy (nama_tanpagelar)', $statisfyEnrichedCount],
                ['Pegawai Diperkaya Atribut Elena (kode_tim & kodehitung)', $elenaEnrichedCount],
                ['Admin Pengaduan Cocok Terverifikasi', $pengaduanEnrichedCount],
                ['Total Baris Anomali/Unmapped Dicatat ke Log', $unmappedCount],
                ['Total Master Pegawai Unik Siap Dimuat', count($masterEmployees)],
            ]
        );

        // 10. Database Persist / Dry-Run Output
        if ($isDryRun) {
            $this->info("DRY-RUN BERHASIL: " . count($masterEmployees) . " pegawai siap dimuat ke tabel users.");
            $this->info("Audit log unmapped tersimpan di: {$this->unmappedLogPath}");
            return Command::SUCCESS;
        }

        $this->info('Memulai penyimpanan ke tabel users menggunakan DB::transaction()...');

        $defaultPassword = Hash::make('password');
        $insertedCount = 0;
        $updatedCount = 0;

        DB::transaction(function () use ($masterEmployees, $defaultPassword, &$insertedCount, &$updatedCount) {
            $bar = $this->output->createProgressBar(count($masterEmployees));
            $bar->start();

            foreach ($masterEmployees as $emp) {
                $existing = User::where('nip_baru', $emp['nip_baru'])
                    ->orWhere('email', $emp['email'])
                    ->first();

                if ($existing) {
                    // Update master attributes while preserving existing roles & password
                    $existing->update([
                        'nip_baru' => $emp['nip_baru'],
                        'nip_lama' => $emp['nip_lama'] ?: $existing->nip_lama,
                        'name' => $emp['name'] ?: $existing->name,
                        'name_without_title' => $emp['name_without_title'] ?: $existing->name_without_title,
                        'whatsapp_number' => $emp['whatsapp_number'] ?: $existing->whatsapp_number,
                        'region_code' => $emp['region_code'] ?: $existing->region_code,
                        'unit_code' => $emp['unit_code'] ?: $existing->unit_code,
                        'unit_name' => $emp['unit_name'] ?: $existing->unit_name,
                        'echelon_id' => $emp['echelon_id'] ?: $existing->echelon_id,
                        'elena_team_code' => $emp['elena_team_code'] ?: $existing->elena_team_code,
                        'elena_calc_code' => $emp['elena_calc_code'] ?: $existing->elena_calc_code,
                        'is_active' => $emp['is_active'],
                    ]);
                    $updatedCount++;
                } else {
                    User::create([
                        'nip_baru' => $emp['nip_baru'],
                        'nip_lama' => $emp['nip_lama'],
                        'name' => $emp['name'],
                        'name_without_title' => $emp['name_without_title'],
                        'email' => $emp['email'],
                        'password' => $defaultPassword,
                        'whatsapp_number' => $emp['whatsapp_number'],
                        'region_code' => $emp['region_code'],
                        'unit_code' => $emp['unit_code'],
                        'unit_name' => $emp['unit_name'],
                        'echelon_id' => $emp['echelon_id'],
                        'elena_team_code' => $emp['elena_team_code'],
                        'elena_calc_code' => $emp['elena_calc_code'],
                        'is_active' => $emp['is_active'],
                    ]);
                    $insertedCount++;
                }
                $bar->advance();
            }

            $bar->finish();
            $this->line('');
        });

        $this->info("EKSEKUSI ETL SELESAI: {$insertedCount} pegawai baru ditambahkan, {$updatedCount} pegawai diperbarui.");
        $this->info("Audit log unmapped tersimpan di: {$this->unmappedLogPath}");

        return Command::SUCCESS;
    }

    /**
     * Fast line-by-line SQL INSERT stream parser without PCRE limits.
     *
     * @return array{count: int, rows: array<int, array<string, mixed>>}
     */
    protected function extractInsertRows(string $filePath, string $tableName): array
    {
        if (!file_exists($filePath)) {
            return ['count' => 0, 'rows' => []];
        }

        $f = fopen($filePath, 'r');
        $rows = [];
        $columns = [];
        $inTargetTable = false;
        $buffer = '';

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

        return [
            'count' => count($rows),
            'rows' => $rows,
        ];
    }

    /**
     * Normalizes person names by removing academic titles and lowercasing for fuzzy matching.
     */
    protected function normalizeName(?string $name): string
    {
        if (empty($name)) {
            return '';
        }

        $clean = strtolower($name);
        $titles = [
            'dr.', 'dra.', 'drs.', 'ir.', 'prof.', 's.st.', 'sst', 's.tr.stat.', 's.stat.', 's.si.', 'ssi',
            's.kom.', 'skom', 's.e.', 'se', 'm.si.', 'msi', 'm.m.', 'mm', 'm.kom.', 'mkom', 'm.a.', 'ma',
            'm.sc.', 'msc', 's.h.', 'sh', 's.sos.', 'ssos', 'a.md.', 'amd',
        ];

        foreach ($titles as $t) {
            $clean = str_replace($t, '', $clean);
        }

        $clean = preg_replace('/[^a-z0-9]/', '', $clean);

        return trim($clean);
    }

    /**
     * Initializes or resets the unmapped records audit log.
     */
    protected function initUnmappedLog(bool $isDryRun): void
    {
        $dir = dirname($this->unmappedLogPath);
        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        $modeHeader = $isDryRun ? '[DRY-RUN MODE]' : '[REAL EXECUTION MODE]';
        $timestamp = now()->toIso8601String();
        $header = "=== SEMAR PST ETL UNMAPPED USERS AUDIT LOG {$modeHeader} - {$timestamp} ===\n";

        File::put($this->unmappedLogPath, $header);
    }

    /**
     * Logs unmapped legacy records to the dedicated audit log.
     *
     * @param array<string, mixed> $payload
     */
    protected function logUnmapped(string $source, array $payload, string $reason): void
    {
        $timestamp = now()->toDateTimeString();
        $json = json_encode($payload, JSON_UNESCAPED_SLASHES);
        $entry = "[{$timestamp}] [SOURCE: {$source}] [REASON: {$reason}] [DATA: {$json}]\n";

        File::append($this->unmappedLogPath, $entry);
    }
}
