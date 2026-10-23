<?php

namespace Database\Factories;

use App\Models\PstVisitor;
use App\Models\PstVisitorLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PstVisitorLog>
 */
class PstVisitorLogFactory extends Factory
{
    protected $model = PstVisitorLog::class;

    public function definition(): array
    {
        return [
            'visitor_id' => PstVisitor::factory(),
            'visit_date' => now()->toDateString(),
            'purpose' => 'Konsultasi data statistik indikator strategis',
            'check_in_at' => now(),
            'check_out_at' => now()->addMinutes(30),
            'notes' => 'Kunjungan reguler ke lobi PST',
        ];
    }
}
