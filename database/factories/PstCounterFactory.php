<?php

namespace Database\Factories;

use App\Models\PstCounter;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PstCounter>
 */
class PstCounterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $number = 1;
        $counterNum = $number++;

        return [
            'counter_name' => 'Loket ' . $counterNum . ' - Pelayanan Terpadu',
            'counter_number' => $counterNum,
            'static_qr_slug' => 'loket-' . $counterNum,
            'active_operator_id' => null,
            'is_open' => false,
            'description' => 'Loket antrian pelayanan statistik nomor ' . $counterNum,
        ];
    }
}
