<?php

namespace Database\Factories;

use App\Models\PstCounter;
use App\Models\PristiSchedule;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PristiSchedule>
 */
class PristiScheduleFactory extends Factory
{
    protected $model = PristiSchedule::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'counter_id' => PstCounter::factory(),
            'schedule_date' => now()->toDateString(),
            'day_name' => 'Senin',
            'shift_name' => 'Pagi (08.00 - 12.00)',
            'swapped_with_user_id' => null,
            'notes' => 'Jadwal shift reguler loket PST',
        ];
    }
}
