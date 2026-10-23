<?php

namespace App\Console\Commands;

use App\Services\Etl\EmployeeNameResolver;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * ID (Konteks Bisnis / Domain BPS):
 * Perintah Artisan untuk memverifikasi dan mengaudit pemetaan normalisasi nama pegawai,
 * username, dan alias petugas loket dari seluruh sistem warisan (PST, Elena, Pristi, Statisfy)
 * ke tabel pengguna terpusat users.id sesuai Aturan Kebijakan ETL (Aturan #7).
 *
 * EN (Technical Specifications & Contract):
 * Artisan console command that normalizes legacy employee and officer names across
 * legacy SQL dumps, auditing mapping precision, resolving aliases, and logging anomalies.
 */
class EtlNormalizeNamesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'semar:etl-normalize-names
                            {--dry-run : Run resolution without modifying database records}
                            {--source= : Specific data source to inspect (pst, elena, pristi, statisfy, all)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Audit and normalize employee and officer names from legacy databases to users.id (BE-07)';

    /**
     * Path to legacy SQL database dumps.
     */
    protected string $pstSqlPath;
    protected string $elenaSqlPath;
    protected string $pristiSqlPath;
    protected string $statisfySqlPath;

    /**
     * Dedicated log path for unmapped names.
     */
    protected string $unmappedLogPath;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();

        $baseDir = is_dir(base_path('../PST')) ? base_path('../PST') : dirname(base_path());
        $this->pstSqlPath = $baseDir . DIRECTORY_SEPARATOR . 'pst' . DIRECTORY_SEPARATOR . 'pst_db.sql';
        $this->elenaSqlPath = $baseDir . DIRECTORY_SEPARATOR . 'elena' . DIRECTORY_SEPARATOR . 'jatengwe_apps.sql';
        $this->pristiSqlPath = $baseDir . DIRECTORY_SEPARATOR . 'pristi' . DIRECTORY_SEPARATOR . 'pristi_db_new (2).sql';
        $this->statisfySqlPath = $baseDir . DIRECTORY_SEPARATOR . 'statisfy' . DIRECTORY_SEPARATOR . 'statisfy.sql';

        $this->unmappedLogPath = storage_path('logs' . DIRECTORY_SEPARATOR . 'etl_unmapped_names.log');
    }

    /**
     * Execute the console command.
     */
    public function handle(EmployeeNameResolver $resolver): int
    {
        $isDryRun = (bool) $this->option('dry-run');
        $targetSource = strtolower((string) ($this->option('source') ?: 'all'));

        $this->line('===========================================================');
        $this->line('  SEMAR PST - Normalisasi Nama Pegawai ke NIP (BE-07)');
        $this->line('===========================================================');

        if ($isDryRun) {
            $this->warn('********************************************************************');
            $this->warn('*      MODE: SIMULASI (DRY-RUN). Tidak ada perubahan data.         *');
            $this->warn('********************************************************************');
        } else {
            $this->info('MODE: AUDIT & RESOLUSI AKTIF.');
        }

        $this->line('');

        // Prepare unmapped audit log header
        $logDir = dirname($this->unmappedLogPath);
        if (!File::isDirectory($logDir)) {
            File::makeDirectory($logDir, 0755, true);
        }

        $modeLabel = $isDryRun ? 'DRY-RUN SIMULATION' : 'REAL AUDIT';
        File::put($this->unmappedLogPath, "=== SEMAR PST ETL UNMAPPED NAMES AUDIT LOG [{$modeLabel}] - " . now()->toIso8601String() . " ===\n");

        $overallScanned = 0;
        $overallMapped = 0;
        $overallUnmapped = 0;
        $summaryTable = [];

        // 1. PST - pst_layanan.created_by & pst_register.created_by
        if (in_array($targetSource, ['all', 'pst'])) {
            $this->info('1. Memindai data username / author dari PST (pst_db.sql)...');
            $pstCreatedBy = $this->scanPstCreatedBy();
            $result = $this->evaluateUniqueNames($pstCreatedBy, 'pst:created_by', $resolver);
            $summaryTable[] = [
                'Sumber Data' => 'PST (pst_layanan & pst_register)',
                'Kolom' => 'created_by',
                'Unik' => count($pstCreatedBy),
                'Cocok' => $result['mapped'],
                'Anomali' => $result['unmapped'],
                'Akurasi' => $result['rate'] . '%',
            ];
            $overallScanned += count($pstCreatedBy);
            $overallMapped += $result['mapped'];
            $overallUnmapped += $result['unmapped'];
        }

        // 2. Elena - elena_t_lk.nama_pegawai & elena_m_petugas
        if (in_array($targetSource, ['all', 'elena'])) {
            $this->info('2. Memindai data nama pegawai dari Elena (jatengwe_apps.sql)...');
            $elenaNames = $this->scanElenaNames();
            $result = $this->evaluateUniqueNames($elenaNames, 'elena:nama_pegawai', $resolver);
            $summaryTable[] = [
                'Sumber Data' => 'Elena (elena_t_lk & petugas)',
                'Kolom' => 'nama_pegawai',
                'Unik' => count($elenaNames),
                'Cocok' => $result['mapped'],
                'Anomali' => $result['unmapped'],
                'Akurasi' => $result['rate'] . '%',
            ];
            $overallScanned += count($elenaNames);
            $overallMapped += $result['mapped'];
            $overallUnmapped += $result['unmapped'];
        }

        // 3. Pristi - presensi_m_jadwal.nama_petugas
        if (in_array($targetSource, ['all', 'pristi'])) {
            $this->info('3. Memindai data jadwal presensi dari Pristi (pristi_db_new.sql)...');
            $pristiNames = $this->scanPristiNames();
            $result = $this->evaluateUniqueNames($pristiNames, 'pristi:nama_petugas', $resolver);
            $summaryTable[] = [
                'Sumber Data' => 'Pristi (presensi_m_jadwal)',
                'Kolom' => 'nama_petugas',
                'Unik' => count($pristiNames),
                'Cocok' => $result['mapped'],
                'Anomali' => $result['unmapped'],
                'Akurasi' => $result['rate'] . '%',
            ];
            $overallScanned += count($pristiNames);
            $overallMapped += $result['mapped'];
            $overallUnmapped += $result['unmapped'];
        }

        // 4. Statisfy - skm_kepuasan.nama_pegawai
        if (in_array($targetSource, ['all', 'statisfy'])) {
            $this->info('4. Memindai data nama petugas survei dari Statisfy (statisfy.sql)...');
            $statisfyNames = $this->scanStatisfyNames();
            $result = $this->evaluateUniqueNames($statisfyNames, 'statisfy:skm_kepuasan', $resolver);
            $summaryTable[] = [
                'Sumber Data' => 'Statisfy (skm_kepuasan)',
                'Kolom' => 'nama_pegawai',
                'Unik' => count($statisfyNames),
                'Cocok' => $result['mapped'],
                'Anomali' => $result['unmapped'],
                'Akurasi' => $result['rate'] . '%',
            ];
            $overallScanned += count($statisfyNames);
            $overallMapped += $result['mapped'];
            $overallUnmapped += $result['unmapped'];
        }

        $this->line('');
        $this->table(
            ['Sumber Data', 'Kolom', 'Unik', 'Cocok', 'Anomali', 'Akurasi'],
            $summaryTable
        );

        $totalRate = $overallScanned > 0 ? round(($overallMapped / $overallScanned) * 100, 2) : 100;
        $this->info("TOTAL KESELURUHAN: {$overallMapped} dari {$overallScanned} string unik berhasil dipetakan ({$totalRate}%).");
        $this->info("Audit log unmapped tersimpan di: {$this->unmappedLogPath}");

        return Command::SUCCESS;
    }

    /**
     * Evaluates a set of raw name strings against EmployeeNameResolver.
     *
     * @param array<string, int> $uniqueNames
     * @return array{mapped: int, unmapped: int, rate: float}
     */
    protected function evaluateUniqueNames(array $uniqueNames, string $sourceTag, EmployeeNameResolver $resolver): array
    {
        $mapped = 0;
        $unmapped = 0;

        foreach ($uniqueNames as $rawName => $frequency) {
            $user = $resolver->resolve($rawName);
            if ($user !== null) {
                $mapped++;
            } else {
                $unmapped++;
                $this->logUnmapped($sourceTag, $rawName, "Tidak dapat dipetakan ke users (Frekuensi kemunculan: {$frequency})");
            }
        }

        $rate = count($uniqueNames) > 0 ? round(($mapped / count($uniqueNames)) * 100, 2) : 100;

        return [
            'mapped' => $mapped,
            'unmapped' => $unmapped,
            'rate' => $rate,
        ];
    }

    /**
     * Scans distinct created_by values from PST tables.
     *
     * @return array<string, int>
     */
    protected function scanPstCreatedBy(): array
    {
        $values = [];
        $callback = function (array $row) use (&$values) {
            $cb = trim($row['created_by'] ?? '');
            if ($cb !== '' && $cb !== 'NULL') {
                $values[$cb] = ($values[$cb] ?? 0) + 1;
            }
        };

        $this->streamSqlTable($this->pstSqlPath, 'pst_layanan', $callback);
        $this->streamSqlTable($this->pstSqlPath, 'pst_register', $callback);

        return $values;
    }

    /**
     * Scans distinct employee names from Elena tables.
     *
     * @return array<string, int>
     */
    protected function scanElenaNames(): array
    {
        $values = [];
        $callback = function (array $row) use (&$values) {
            $name = trim($row['nama_pegawai'] ?? '');
            if ($name !== '' && $name !== 'NULL') {
                $values[$name] = ($values[$name] ?? 0) + 1;
            }
        };

        $this->streamSqlTable($this->elenaSqlPath, 'elena_t_lk', $callback);
        $this->streamSqlTable($this->elenaSqlPath, 'elena_m_petugas', $callback);

        return $values;
    }

    /**
     * Scans distinct officer names from Pristi tables.
     *
     * @return array<string, int>
     */
    protected function scanPristiNames(): array
    {
        $values = [];
        $callback = function (array $row) use (&$values) {
            $name = trim($row['nama_petugas'] ?? '');
            if ($name !== '' && $name !== 'NULL') {
                $values[$name] = ($values[$name] ?? 0) + 1;
            }
        };

        $this->streamSqlTable($this->pristiSqlPath, 'presensi_m_jadwal', $callback);

        return $values;
    }

    /**
     * Scans distinct officer names from Statisfy survey ratings.
     *
     * @return array<string, int>
     */
    protected function scanStatisfyNames(): array
    {
        $values = [];
        $callback = function (array $row) use (&$values) {
            foreach (['nama_pegawai', 'nama_pegawai2', 'nama_pegawai3'] as $col) {
                $name = trim($row[$col] ?? '');
                if ($name !== '' && $name !== '-' && $name !== 'NULL') {
                    $values[$name] = ($values[$name] ?? 0) + 1;
                }
            }
        };

        $this->streamSqlTable($this->statisfySqlPath, 'skm_kepuasan', $callback);

        return $values;
    }

    /**
     * Fast, memory-efficient SQL table streaming parser.
     */
    protected function streamSqlTable(string $filePath, string $targetTable, callable $rowCallback): void
    {
        if (!file_exists($filePath)) {
            return;
        }

        $f = fopen($filePath, 'r');
        $columns = [];
        $inTargetTable = false;
        $buffer = '';

        while (($line = fgets($f)) !== false) {
            if (preg_match('/CREATE TABLE [`\']?' . preg_quote($targetTable, '/') . '[`\']?/i', $line)) {
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

            if (preg_match('/INSERT INTO [`\']?' . preg_quote($targetTable, '/') . '[`\']?\s*(?:\(([^)]+)\))?\s*VALUES/i', $line, $matches)) {
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

                    $rowCallback($assoc);
                }
            }
        }

        fclose($f);
    }

    /**
     * Appends an unmapped name anomaly to audit log.
     */
    protected function logUnmapped(string $sourceTag, string $rawName, string $reason): void
    {
        $timestamp = now()->toDateTimeString();
        $line = "[{$timestamp}] [SOURCE: {$sourceTag}] [REASON: {$reason}] [NAME: \"{$rawName}\"]\n";
        File::append($this->unmappedLogPath, $line);
    }
}
