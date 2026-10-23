<?php

namespace Database\Factories;

use App\Models\ElenaEvaluation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ElenaEvaluation>
 */
class ElenaEvaluationFactory extends Factory
{
    protected $model = ElenaEvaluation::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'period_year' => (int) now()->format('Y'),
            'period_month' => (int) now()->format('m'),
            'total_services_count' => 38,
            'total_surveys_count' => 35,
            'raw_satisfaction_avg' => 4.85,
            'raw_performance_score' => 92.00,
            'raw_attendance_score' => 98.00,
            'raw_cooperation_score' => 90.00,
            'raw_innovation_score' => 88.00,
            'raw_appearance_score' => 95.00,
            'raw_complaint_count' => 0,
            'weighted_performance' => 27.60,
            'weighted_attendance' => 19.60,
            'weighted_cooperation' => 18.00,
            'weighted_innovation' => 13.20,
            'weighted_appearance' => 14.25,
            'weighted_complaint' => 0.00,
            'total_score' => 92.65,
            'eval_score' => 92.65,
            'rank_position' => 1,
            'is_locked' => true,
            'locked_at' => now(),
        ];
    }
}
