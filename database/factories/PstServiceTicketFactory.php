<?php

namespace Database\Factories;

use App\Models\PstCounter;
use App\Models\PstServiceTicket;
use App\Models\PstVisitor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<PstServiceTicket>
 */
class PstServiceTicketFactory extends Factory
{
    protected $model = PstServiceTicket::class;

    public function definition(): array
    {
        static $number = 1;
        $queueNum = $number++;

        return [
            'ticket_number' => sprintf('A-%03d', $queueNum),
            'queue_code' => 'A',
            'queue_number' => $queueNum,
            'qr_token' => Str::random(32),
            'service_date' => now()->toDateString(),
            'service_type' => fake()->randomElement(['perpustakaan', 'konsultasi', 'pembelian', 'rekomendasi']),
            'visitor_id' => PstVisitor::factory(),
            'visitor_name' => fake()->name(),
            'is_first_visit' => false,
            'counter_id' => PstCounter::factory(),
            'served_by_user_id' => User::factory(),
            'request_detail' => 'Permintaan data inflasi dan pertumbuhan ekonomi BPS Jawa Tengah',
            'service_report' => 'Layanan telah selesai diberikan melalui konsultasi tatap muka',
            'status' => 'completed',
            'called_at' => now()->subMinutes(20),
            'started_at' => now()->subMinutes(18),
            'finished_at' => now(),
        ];
    }
}
