<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Penyimpanan Hasil Analisis AI Statisfy (SEMAR PST).
 *
 * ID:
 * Menyimpan hasil agregasi dan ekstraksi wawasan cerdas (AI sentiment analysis & summary)
 * dari seluruh feedback ulasan teks survei kepuasan pengunjung PST. Mendukung mode analisis
 * bulanan, tahunan, maupun kumulatif untuk pelaporan dashboard pimpinan/eksekutif.
 *
 * EN:
 * Stores aggregate AI sentiment analysis and qualitative insights extracted from visitor
 * satisfaction feedback. Supports monthly, yearly, and cumulative scopes for executive dashboard reporting.
 *
 * @property int $id
 * @property string $mode
 * @property int|null $period_month
 * @property int|null $period_year
 * @property string $analysis_result
 * @property string|null $summary_sentiment
 * @property int $total_reviews_analyzed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class StatisfyAiAnalysis extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'statisfy_ai_analyses';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'mode',
        'period_month',
        'period_year',
        'analysis_result',
        'summary_sentiment',
        'total_reviews_analyzed',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'period_month' => 'integer',
        'period_year' => 'integer',
        'total_reviews_analyzed' => 'integer',
    ];
}
