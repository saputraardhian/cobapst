<?php

namespace Tests\Feature;

use App\Filament\Pages\StatisfyAnalytics;
use App\Models\StatisfySurvey;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Pengujian Fitur STATISFY Analytics & AI Summary (Node 5).
 *
 * ID:
 * Menguji keterbukaan rute /admin/statisfy, pengalihan /statisfy, rendering komponen Filament,
 * kalkulasi metrik IKM, pemfilteran periode (bulanan/triwulanan/tahunan/all-time),
 * leaderboard performa petugas, dan aksi interaktif pimpinan.
 *
 * EN:
 * Feature test suite for STATISFY Analytics & AI Summary (Node 5).
 * Tests route availability for /admin/statisfy, shortcut redirection from /statisfy, Filament page mounting,
 * satisfaction metric computations, period filtering, officer performance leaderboard, and executive actions.
 */
class StatisfyAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ID: Menguji rute /statisfy dialihkan ke /admin/statisfy.
     * EN: Test that /statisfy route redirects to /admin/statisfy.
     */
    public function test_statisfy_shortcut_redirects_to_admin_panel(): void
    {
        $response = $this->get('/statisfy');
        $response->assertRedirect('/admin/statisfy');
    }

    /**
     * ID: Menguji pengguna terautentikasi dapat mengakses halaman analitik Statisfy.
     * EN: Test that authenticated user can access the Statisfy analytics page.
     */
    public function test_authenticated_user_can_access_statisfy_page(): void
    {
        $user = User::factory()->create([
            'email' => 'analis@bps.go.id',
            'name' => 'Joko Hendarto, S.Stat.',
        ]);

        $response = $this->actingAs($user)->get('/admin/statisfy');
        $response->assertStatus(200);
        $response->assertSee('STATISFY Analytics & AI Summary', false);
        $response->assertSee('Executive Summary Sentimen', false);
    }

    /**
     * ID: Menguji komponen Livewire StatisfyAnalytics memuat metrik IKM dan distribusi.
     * EN: Test that StatisfyAnalytics Livewire component loads IKM metrics and distributions.
     */
    public function test_statisfy_analytics_renders_metrics_and_distributions(): void
    {
        $user = User::factory()->create();

        // Buat sampel data survei kepuasan
        StatisfySurvey::create([
            'rating_score' => 4,
            'media_channel' => 'tatap_muka',
            'survey_date' => now()->toDateString(),
        ]);
        StatisfySurvey::create([
            'rating_score' => 4,
            'media_channel' => 'tatap_muka',
            'survey_date' => now()->toDateString(),
        ]);
        StatisfySurvey::create([
            'rating_score' => 3,
            'media_channel' => 'whatsapp',
            'survey_date' => now()->toDateString(),
        ]);

        Livewire::actingAs($user)
            ->test(StatisfyAnalytics::class)
            ->assertSet('selectedPeriod', 'monthly')
            ->assertSee('Indeks Kepuasan')
            ->assertSee('Total Responden SKM')
            ->assertSee('Net Promoter Score')
            ->assertSee('Respon Tercepat Rata-Rata')
            ->assertSee('Sebaran 4 Skala Penilaian')
            ->assertSee('Leaderboard IKM Petugas PST Jateng');
    }

    /**
     * ID: Menguji filter periode waktu dan tab leaderboard berfungsi secara reaktif.
     * EN: Test that period filter and leaderboard tab filter work reactively.
     */
    public function test_statisfy_analytics_reactive_filters(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(StatisfyAnalytics::class)
            ->call('setPeriod', 'yearly')
            ->assertSet('selectedPeriod', 'yearly')
            ->call('setLeaderboardTab', 'total')
            ->assertSet('leaderboardTab', 'total')
            ->call('refreshSynthesis')
            ->assertHasNoErrors();
    }
}
