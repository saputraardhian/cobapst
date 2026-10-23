<?php

namespace Tests\Feature;

use App\Filament\Pages\Auth\Login;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Pengujian Fitur Otentikasi Masuk SSO Pegawai BPS (SEMAR PST).
 *
 * ID:
 * Menguji halaman masuk SSO pegawai BPS (/admin/login):
 * 1. Halaman render sukses (status 200) dengan elemen antarmuka sesuai desain.
 * 2. Autentikasi berhasil menggunakan email dinas penuh (@bps.go.id).
 * 3. Autentikasi berhasil menggunakan username prefix (misal: 'ardhian.ds').
 * 4. Autentikasi berhasil menggunakan NIP 18-digit.
 * 5. Penolakan autentikasi saat kredensial salah dengan pesan validasi yang jelas.
 *
 * EN:
 * Tests BPS Personnel SSO authentication (/admin/login):
 * 1. Login page renders HTTP 200 with all required design elements.
 * 2. Successful authentication via full email address.
 * 3. Successful authentication via username prefix (e.g. 'ardhian.ds').
 * 4. Successful authentication via 18-digit NIP.
 * 5. Validation failure rejection on invalid credentials.
 */
class SsoLoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_sso_login_page_renders_successfully(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
        $response->assertSee('Login SSO Pegawai');
        $response->assertSee('PRISTELA');
        $response->assertSee('Username atau Email Dinas');
        $response->assertSee('Kata Sandi SSO');
        $response->assertSee('Masuk Sistem');
        $response->assertSee('TLS 1.3');
        $response->assertSee('Portal Publik');
    }

    public function test_sso_login_succeeds_with_full_email(): void
    {
        Livewire::test(Login::class)
            ->set('data.login', 'ardhian.ds@bps.go.id')
            ->set('data.password', 'password')
            ->call('authenticate')
            ->assertHasNoErrors();

        $this->assertAuthenticated();
    }

    public function test_sso_login_succeeds_with_username_prefix(): void
    {
        Livewire::test(Login::class)
            ->set('data.login', 'ardhian.ds')
            ->set('data.password', 'password')
            ->call('authenticate')
            ->assertHasNoErrors();

        $this->assertAuthenticated();
    }

    public function test_sso_login_succeeds_with_nip_baru(): void
    {
        Livewire::test(Login::class)
            ->set('data.login', '199508152019031001')
            ->set('data.password', 'password')
            ->call('authenticate')
            ->assertHasNoErrors();

        $this->assertAuthenticated();
    }

    public function test_sso_login_fails_with_invalid_credentials(): void
    {
        Livewire::test(Login::class)
            ->set('data.login', 'ardhian.ds')
            ->set('data.password', 'wrong-password')
            ->call('authenticate')
            ->assertHasErrors(['data.login']);

        $this->assertGuest();
    }

    public function test_sso_login_bypass_succeeds(): void
    {
        Livewire::test(Login::class)
            ->call('bypassLogin', 'super_admin')
            ->assertHasNoErrors();

        $this->assertAuthenticated();
    }
}
