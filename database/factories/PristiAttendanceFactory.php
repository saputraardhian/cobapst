<?php

namespace Database\Factories;

use App\Models\PristiAttendance;
use App\Models\PristiSchedule;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PristiAttendance>
 */
class PristiAttendanceFactory extends Factory
{
    protected $model = PristiAttendance::class;

    public function definition(): array
    {
        return [
            'schedule_id' => PristiSchedule::factory(),
            'user_id' => User::factory(),
            'attendance_date' => now()->toDateString(),
            'check_in_time' => '07:55:00',
            'check_out_time' => '16:05:00',
            'time_difference' => 0,
            'source' => 'operator_console',
            'status' => 'present',
            'notes' => 'Presensi hadir tepat waktu via Konsol Meja Loket',
        ];
    }
}
