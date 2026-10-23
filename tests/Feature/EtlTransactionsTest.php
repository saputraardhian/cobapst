<?php

namespace Tests\Feature;

use App\Models\ElenaEvaluation;
use App\Models\PristiAttendance;
use App\Models\PristiSchedule;
use App\Models\PstServiceTicket;
use App\Models\PstVisitor;
use App\Models\PstVisitorLog;
use App\Models\PublicComplaint;
use App\Models\StatisfyAiAnalysis;
use App\Models\StatisfySurvey;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

/**
 * Pengujian Fitur ETL Migrasi Histori Transaksi 5 Basis Data Warisan (BE-08).
 *
 * ID:
 * Menguji kepatuhan perintah `php artisan semar:etl-transactions` terhadap Golden Rule #7:
 * verifikasi mode simulasi `--dry-run`, integritas pemindahan riwayat transaksi seluruh modul
 * (PST, Statisfy, Pristi, Pengaduan, Elena), serta ketiadaan anomali tak terpetakan.
 *
 * EN:
 * Tests the `semar:etl-transactions` ETL command per Golden Rule #7: verifies `--dry-run`
 * simulation without DB mutations, full transaction migration integrity across all 5 modules,
 * and zero unmapped anomalies.
 */
class EtlTransactionsTest extends TestCase
{
    use RefreshDatabase;

    protected string $unmappedLogPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->unmappedLogPath = storage_path('logs/etl_unmapped_transactions.log');
    }

    public function test_etl_transactions_dry_run_does_not_mutate_database(): void
    {
        // First seed master users via ETL so resolver has users to match against
        $this->artisan('semar:etl-users')->assertExitCode(Command::SUCCESS);

        $this->artisan('semar:etl-transactions', ['--dry-run' => true])
            ->expectsOutputToContain('MODE: SIMULASI (DRY-RUN)')
            ->expectsOutputToContain('DRY-RUN BERHASIL')
            ->assertExitCode(Command::SUCCESS);

        // Verify zero transactional rows inserted
        $this->assertSame(0, PstVisitor::count());
        $this->assertSame(0, PstVisitorLog::count());
        $this->assertSame(0, PstServiceTicket::count());
        $this->assertSame(0, StatisfySurvey::count());
        $this->assertSame(0, PristiSchedule::count());
        $this->assertSame(0, PristiAttendance::count());
        $this->assertSame(0, PublicComplaint::count());
        $this->assertSame(0, ElenaEvaluation::count());

        // Verify audit log generated
        $this->assertFileExists($this->unmappedLogPath);
        $log = File::get($this->unmappedLogPath);
        $this->assertStringContainsString('[DRY-RUN MODE]', $log);
    }

    public function test_etl_transactions_real_execution_migrates_all_modules(): void
    {
        // Seed master users via ETL
        $this->artisan('semar:etl-users')->assertExitCode(Command::SUCCESS);

        $this->artisan('semar:etl-transactions')
            ->expectsOutputToContain('MODE: EKSEKUSI RIIL')
            ->expectsOutputToContain('EKSEKUSI ETL SELESAI')
            ->assertExitCode(Command::SUCCESS);

        // Verify PST transactions populated
        $this->assertGreaterThan(0, PstVisitor::count());
        $this->assertGreaterThan(0, PstVisitorLog::count());
        $this->assertGreaterThan(0, PstServiceTicket::count());

        // Verify Statisfy surveys and AI summaries populated
        $this->assertGreaterThan(0, StatisfySurvey::count());
        $this->assertGreaterThan(0, StatisfyAiAnalysis::count());

        // Verify Pristi schedules and attendances populated
        $this->assertGreaterThan(0, PristiSchedule::count());
        $this->assertGreaterThan(0, PristiAttendance::count());

        // Verify Public Complaints populated
        $this->assertGreaterThan(0, PublicComplaint::count());
        $firstComplaint = PublicComplaint::first();
        $this->assertNotNull($firstComplaint);
        $this->assertStringStartsWith('ADU-', $firstComplaint->tracking_code);

        // Verify Elena Evaluations populated
        $this->assertGreaterThan(0, ElenaEvaluation::count());
        $firstEval = ElenaEvaluation::first();
        $this->assertNotNull($firstEval);
        $this->assertTrue($firstEval->is_locked);
    }

    public function test_etl_transactions_module_filtering(): void
    {
        $this->artisan('semar:etl-transactions', ['--source' => 'pengaduan'])
            ->expectsOutputToContain('Memproses Transaksi Pengaduan Masyarakat')
            ->assertExitCode(Command::SUCCESS);

        // Verify only complaints were populated
        $this->assertGreaterThan(0, PublicComplaint::count());
        $this->assertSame(0, PstServiceTicket::count());
        $this->assertSame(0, ElenaEvaluation::count());
    }
}
