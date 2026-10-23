<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Etl\EmployeeNameResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

/**
 * ID (Konteks Bisnis / Domain BPS):
 * Pengujian otomatis modul normalisasi nama pegawai BPS lintas sistem legacy (BE-07)
 * untuk memastikan akurasi pembersihan gelar akademik, pemetaan alias/nickname,
 * eksekusi perintah artisan semar:etl-normalize-names, dan kepatuhan Aturan #7.
 *
 * EN (Technical Specifications & Contract):
 * Feature tests covering EmployeeNameResolver title cleaning, nickname & email
 * mapping, NIP resolution, and artisan dry-run safety.
 */
class EtlNormalizeNamesTest extends TestCase
{
    use RefreshDatabase;

    protected EmployeeNameResolver $resolver;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed representative test employees
        User::create([
            'nip_baru' => '198707082009022002',
            'nip_lama' => '340050277',
            'name' => 'Yuli Purwitasari SST',
            'name_without_title' => 'Yuli Purwitasari',
            'email' => 'yuli.purwita@bps.go.id',
            'password' => bcrypt('password'),
            'unit_name' => 'Seksi Diseminasi dan Layanan Statistik',
            'is_active' => true,
        ]);

        User::create([
            'nip_baru' => '197406132006041014',
            'nip_lama' => '340018744',
            'name' => 'Yun Restianto',
            'name_without_title' => 'Yun Restianto',
            'email' => 'nanang@bps.go.id',
            'password' => bcrypt('password'),
            'unit_name' => 'Seksi Diseminasi dan Layanan Statistik',
            'is_active' => true,
        ]);

        User::create([
            'nip_baru' => '198610082009021005',
            'nip_lama' => '340050116',
            'name' => 'Hermawan Prasetyo SST, MT.',
            'name_without_title' => 'Hermawan Prasetyo',
            'email' => 'herpas@bps.go.id',
            'password' => bcrypt('password'),
            'unit_name' => 'Seksi Integrasi Pengolahan Data',
            'is_active' => true,
        ]);

        User::create([
            'nip_baru' => '198805212012121003',
            'nip_lama' => '340056062',
            'name' => 'Dwi Agus Wahyu Riyadi A.Md, S.Kom.',
            'name_without_title' => 'Dwi Agus Wahyu Riyadi',
            'email' => 'dwiagus.wahyu@bps.go.id',
            'password' => bcrypt('password'),
            'unit_name' => 'Seksi Diseminasi dan Layanan Statistik',
            'is_active' => true,
        ]);

        User::create([
            'nip_baru' => '198701042009122002',
            'nip_lama' => '340053222',
            'name' => 'Rizchi Eka Wahyuni S.ST, M.Kom',
            'name_without_title' => 'Rizchi Eka Wahyuni',
            'email' => 'rizchi@bps.go.id',
            'password' => bcrypt('password'),
            'unit_name' => 'Seksi Integrasi Pengolahan Data',
            'is_active' => true,
        ]);

        $this->resolver = new EmployeeNameResolver();
    }

    /**
     * Test title cleaning strips single, compound, and multiple academic degrees.
     */
    public function test_resolver_cleans_academic_titles_properly(): void
    {
        $this->assertEquals('Indah Purnamasari', $this->resolver->cleanAcademicTitle('Indah Purnamasari S.E'));
        $this->assertEquals('Rizchi Eka Wahyuni', $this->resolver->cleanAcademicTitle('Rizchi Eka Wahyuni S.ST, M.Kom'));
        $this->assertEquals('Mufti Ramadhan', $this->resolver->cleanAcademicTitle('Mufti Ramadhan SST.,MT'));
        $this->assertEquals('Hermawan Prasetyo', $this->resolver->cleanAcademicTitle('Hermawan Prasetyo SST, MT.'));
        $this->assertEquals('Dwi Agus Wahyu Riyadi', $this->resolver->cleanAcademicTitle('Dwi Agus Wahyu Riyadi A.Md, S.Kom.'));
        $this->assertEquals('Sentot Bangun Widoyono', $this->resolver->cleanAcademicTitle('Drs. Sentot Bangun Widoyono M.A.'));
    }

    /**
     * Test resolver maps frontline officer nicknames to canonical users.
     */
    public function test_resolver_maps_nicknames_and_usernames_to_users(): void
    {
        // Nickname nanang -> Yun Restianto
        $userNanang = $this->resolver->resolve('nanang');
        $this->assertNotNull($userNanang);
        $this->assertEquals('197406132006041014', $userNanang->nip_baru);

        // Nickname herpas -> Hermawan Prasetyo
        $userHerpas = $this->resolver->resolve('herpas');
        $this->assertNotNull($userHerpas);
        $this->assertEquals('198610082009021005', $userHerpas->nip_baru);

        // Username yuli.purwita -> Yuli Purwitasari
        $userYuli = $this->resolver->resolve('yuli.purwita');
        $this->assertNotNull($userYuli);
        $this->assertEquals('198707082009022002', $userYuli->nip_baru);

        // Username dwiagus.wahyu -> Dwi Agus Wahyu Riyadi
        $userDwi = $this->resolver->resolve('dwiagus.wahyu');
        $this->assertNotNull($userDwi);
        $this->assertEquals('198805212012121003', $userDwi->nip_baru);

        // Username rizchi -> Rizchi Eka Wahyuni
        $userRizchi = $this->resolver->resolve('rizchi');
        $this->assertNotNull($userRizchi);
        $this->assertEquals('198701042009122002', $userRizchi->nip_baru);
    }

    /**
     * Test resolver matches via 18-digit NIP Baru and 9-digit NIP Lama.
     */
    public function test_resolver_matches_via_nip(): void
    {
        $userByNipBaru = $this->resolver->resolve('198707082009022002');
        $this->assertNotNull($userByNipBaru);
        $this->assertEquals('Yuli Purwitasari SST', $userByNipBaru->name);

        $userByNipLama = $this->resolver->resolve('340050277');
        $this->assertNotNull($userByNipLama);
        $this->assertEquals('198707082009022002', $userByNipLama->nip_baru);
    }

    /**
     * Test resolveUserId helper returns integer user ID or null.
     */
    public function test_resolver_returns_user_id_or_null(): void
    {
        $userId = $this->resolver->resolveUserId('nanang');
        $this->assertIsInt($userId);

        $unknownId = $this->resolver->resolveUserId('Pegawai Fiktif Tidak Dikenal');
        $this->assertNull($unknownId);
    }

    /**
     * Test artisan command dry-run execution.
     */
    public function test_etl_normalize_names_command_dry_run(): void
    {
        $this->artisan('semar:etl-normalize-names', ['--dry-run' => true])
            ->assertExitCode(0);

        $logPath = storage_path('logs' . DIRECTORY_SEPARATOR . 'etl_unmapped_names.log');
        $this->assertTrue(File::exists($logPath));
    }
}
