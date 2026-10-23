<?php

namespace Tests\Feature;

use App\Models\PstCounter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterUsersAndCountersTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_user_with_bps_master_fields(): void
    {
        $user = User::factory()->create([
            'nip_baru' => '199501012022031002',
            'nip_lama' => '340056789',
            'name' => 'Budi Santoso, S.Tr.Stat.',
            'name_without_title' => 'Budi Santoso',
            'email' => 'budi@bps.go.id',
            'whatsapp_number' => '081234567890',
            'region_code' => '3300',
            'unit_code' => '33001',
            'unit_name' => 'Fungsi Nerwilis',
            'echelon_id' => 'IV',
            'elena_team_code' => 'TIM-PROD',
            'elena_calc_code' => 'RUMUS-A',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('users', [
            'nip_baru' => '199501012022031002',
            'name_without_title' => 'Budi Santoso',
            'region_code' => '3300',
        ]);
        $this->assertTrue($user->canAccessPanel(new \Filament\Panel()));
    }

    public function test_can_create_pst_counter_and_associate_active_operator(): void
    {
        $operator = User::factory()->create([
            'name' => 'Siti Rahmawati',
        ]);

        $counter = PstCounter::factory()->create([
            'counter_name' => 'Loket 1 - Layanan Statistik',
            'counter_number' => 1,
            'static_qr_slug' => 'loket-1',
            'active_operator_id' => $operator->id,
            'is_open' => true,
        ]);

        $this->assertDatabaseHas('pst_counters', [
            'counter_number' => 1,
            'static_qr_slug' => 'loket-1',
            'active_operator_id' => $operator->id,
            'is_open' => true,
        ]);

        $this->assertEquals($operator->id, $counter->activeOperator->id);
        $this->assertEquals($counter->id, $operator->activeCounter->id);
    }
}
