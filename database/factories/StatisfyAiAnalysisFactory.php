<?php

namespace Database\Factories;

use App\Models\StatisfyAiAnalysis;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StatisfyAiAnalysis>
 */
class StatisfyAiAnalysisFactory extends Factory
{
    protected $model = StatisfyAiAnalysis::class;

    public function definition(): array
    {
        return [
            'mode' => 'bulanan',
            'period_month' => (int) now()->format('m'),
            'period_year' => (int) now()->format('Y'),
            'analysis_result' => json_encode([
                'overall_summary' => 'Tingkat kepuasan pengunjung bulan ini sangat tinggi dengan sentimen dominan positif.',
                'highlights' => ['Pelayanan cepat', 'Petugas ramah', 'Data mudah diakses'],
                'areas_to_improve' => ['Koneksi internet ruang tunggu'],
            ]),
            'summary_sentiment' => 'positif',
            'total_reviews_analyzed' => 45,
        ];
    }
}
