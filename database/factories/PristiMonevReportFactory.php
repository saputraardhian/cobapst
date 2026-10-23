<?php

namespace Database\Factories;

use App\Models\PristiMonevReport;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PristiMonevReport>
 */
class PristiMonevReportFactory extends Factory
{
    protected $model = PristiMonevReport::class;

    public function definition(): array
    {
        return [
            'region_code' => '3374',
            'region_name' => 'BPS Kota Semarang',
            'period_year' => (int) now()->format('Y'),
            'period_type' => 'bulan',
            'period_value' => (int) now()->format('m'),
            'score_adm' => 95.00,
            'score_teknis' => 92.50,
            'score_sdm' => 90.00,
            'score_korupsi' => 100.00,
            'score_prof' => 93.00,
            'score_sarpras' => 88.00,
            'score_sipp' => 94.00,
            'score_pengaduan' => 96.00,
            'score_inovasi' => 89.00,
            'total_score' => 93.05,
            'verified_by_user_id' => User::factory(),
        ];
    }
}
