<?php

namespace Database\Factories;

use App\Models\PublicComplaint;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PublicComplaint>
 */
class PublicComplaintFactory extends Factory
{
    protected $model = PublicComplaint::class;

    public function definition(): array
    {
        static $number = 1;
        $seq = $number++;

        return [
            'tracking_code' => sprintf('ADU-%s-%04d', now()->format('Y'), $seq),
            'reporter_name' => fake()->name(),
            'reporter_email' => fake()->safeEmail(),
            'reporter_phone' => fake()->numerify('08##########'),
            'complaint_category' => 'pelayanan',
            'complaint_content' => 'Konsultasi data statistik memerlukan waktu respon yang lebih cepat.',
            'public_feedback' => 'Terima kasih atas masukannya, kami telah meningkatkan kecepatan akses data.',
            'internal_follow_up' => 'Telah didisposisikan ke Tim Nerwilis untuk optimalisasi alur layanan.',
            'related_user_id' => null,
            'is_valid_penalty' => false,
            'status' => 'resolved',
            'remote_ip' => '127.0.0.1',
            'resolved_at' => now(),
        ];
    }
}
