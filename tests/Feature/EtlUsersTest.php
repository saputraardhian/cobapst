<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

/**
 * Pengujian Fitur ETL Deduplikasi Master Pegawai BPS (BE-06).
 *
 * ID:
 * Menguji kepatuhan perintah `php artisan semar:etl-users` terhadap Golden Rule #7:
 * verifikasi mode simulasi `--dry-run`, integritas deduplikasi master pegawai, pengayaan
 * atribut dari sistem legacy, serta pencatatan audit log unmapped.
 *
 * EN:
 * Tests the `semar:etl-users` ETL command per Golden Rule #7: verifies `--dry-run`
 * simulation without DB mutation, master employee deduplication integrity, attribute
 * enrichment from legacy dumps, and unmapped audit log generation.
 */
class EtlUsersTest extends TestCase
{
    use RefreshDatabase;

    protected string $unmappedLogPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->unmappedLogPath = storage_path('logs/etl_unmapped_users.log');
    }

    public function test_etl_command_dry_run_does_not_mutate_database(): void
    {
        // Initial user count (default seeder or clean state)
        $initialCount = User::count();

        $this->artisan('semar:etl-users', ['--dry-run' => true])
            ->expectsOutputToContain('MODE: DRY-RUN')
            ->expectsOutputToContain('DRY-RUN BERHASIL')
            ->assertExitCode(Command::SUCCESS);

        // Assert database was NOT modified
        $this->assertSame($initialCount, User::count());

        // Assert audit log was generated
        $this->assertFileExists($this->unmappedLogPath);
        $logContent = File::get($this->unmappedLogPath);
        $this->assertStringContainsString('[DRY-RUN MODE]', $logContent);
    }

    public function test_etl_command_imports_and_deduplicates_employees_in_real_execution(): void
    {
        $this->artisan('semar:etl-users')
            ->expectsOutputToContain('MODE: EKSEKUSI RIIL')
            ->expectsOutputToContain('EKSEKUSI ETL SELESAI')
            ->assertExitCode(Command::SUCCESS);

        // Assert master users were populated (> 1000 records)
        $totalUsers = User::count();
        $this->assertGreaterThan(1000, $totalUsers);

        // Assert Elena employee imported
        $this->assertDatabaseHas('users', [
            'nip_baru' => '196109041983021001',
            'name' => 'Sentot Bangun Widoyono M.A.',
        ]);

        // Assert Statisfy officer imported and enriched with name_without_title
        $this->assertDatabaseHas('users', [
            'nip_baru' => '200108252024121005',
            'name_without_title' => 'Muhammad Hanief',
        ]);

        // Assert Pristi specific employee imported
        $this->assertDatabaseHas('users', [
            'nip_baru' => '199704032018021001',
        ]);

        // Assert unmapped audit log in real execution mode
        $this->assertFileExists($this->unmappedLogPath);
        $logContent = File::get($this->unmappedLogPath);
        $this->assertStringContainsString('[REAL EXECUTION MODE]', $logContent);
    }
}
