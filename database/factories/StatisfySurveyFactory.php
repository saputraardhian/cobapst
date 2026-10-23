<?php

namespace Database\Factories;

use App\Models\PstCounter;
use App\Models\PstServiceTicket;
use App\Models\StatisfySurvey;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StatisfySurvey>
 */
class StatisfySurveyFactory extends Factory
{
    protected $model = StatisfySurvey::class;

    public function definition(): array
    {
        return [
            'service_ticket_id' => PstServiceTicket::factory(),
            'rated_user_id' => User::factory(),
            'counter_id' => PstCounter::factory(),
            'survey_date' => now()->toDateString(),
            'respondent_name' => fake()->name(),
            'respondent_email' => fake()->safeEmail(),
            'respondent_phone' => fake()->numerify('08##########'),
            'service_type' => 'konsultasi',
            'media_channel' => 'offline',
            'rating_score' => fake()->numberBetween(4, 5),
            'feedback_comment' => 'Pelayanan ramah, cepat, dan data yang disajikan sangat lengkap.',
            'remote_ip' => '127.0.0.1',
        ];
    }
}
