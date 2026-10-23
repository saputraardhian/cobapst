<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Matriks Evaluasi Kinerja Petugas Elena (SEMAR PST).
 *
 * ID:
 * Menyimpan rekapitulasi penilaian kinerja bulanan petugas pelayanan statistik terpadu BPS Jateng.
 * Menggabungkan metrik kuantitatif agregat (volume layanan PST, survei kepuasan Statisfy, kehadiran Pristi,
 * penalti pengaduan) dengan penilaian kualitatif perilaku kerja (kerjasama, inovasi, penampilan).
 * Menerapkan pembobotan formula tertimbang (`_ttb`) sesuai kode hitung Elena, penentuan peringkat,
 * penguncian hasil penilaian (`is_locked`), serta pencetakan piagam penghargaan FPDF.
 *
 * EN:
 * Represents monthly performance evaluation matrix records for BPS PST duty officers.
 * Consolidates automated quantitative metrics (service volume, survey satisfaction, attendance rates,
 * complaint deductions) with qualitative behavioral scores (teamwork, innovation, professional grooming).
 * Computes weighted formula scores (`_ttb`), ranking positions, period audit locking (`is_locked`),
 * and authentic FPDF certificate generation.
 *
 * @property int $id
 * @property int $user_id
 * @property int $period_year
 * @property int $period_month
 * @property int $total_services_count
 * @property int $total_surveys_count
 * @property string $raw_satisfaction_avg
 * @property string $raw_performance_score
 * @property string $raw_attendance_score
 * @property string $raw_cooperation_score
 * @property string $raw_innovation_score
 * @property string $raw_appearance_score
 * @property int $raw_complaint_count
 * @property string $weighted_performance
 * @property string $weighted_attendance
 * @property string $weighted_cooperation
 * @property string $weighted_innovation
 * @property string $weighted_appearance
 * @property string $weighted_complaint
 * @property string $total_score
 * @property string $eval_score
 * @property int|null $rank_position
 * @property bool $is_locked
 * @property \Illuminate\Support\Carbon|null $locked_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class ElenaEvaluation extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'elena_evaluations';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'period_year',
        'period_month',
        'total_services_count',
        'total_surveys_count',
        'raw_satisfaction_avg',
        'raw_performance_score',
        'raw_attendance_score',
        'raw_cooperation_score',
        'raw_innovation_score',
        'raw_appearance_score',
        'raw_complaint_count',
        'weighted_performance',
        'weighted_attendance',
        'weighted_cooperation',
        'weighted_innovation',
        'weighted_appearance',
        'weighted_complaint',
        'total_score',
        'eval_score',
        'rank_position',
        'is_locked',
        'locked_at',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'period_year' => 'integer',
        'period_month' => 'integer',
        'total_services_count' => 'integer',
        'total_surveys_count' => 'integer',
        'raw_satisfaction_avg' => 'decimal:2',
        'raw_performance_score' => 'decimal:2',
        'raw_attendance_score' => 'decimal:2',
        'raw_cooperation_score' => 'decimal:2',
        'raw_innovation_score' => 'decimal:2',
        'raw_appearance_score' => 'decimal:2',
        'raw_complaint_count' => 'integer',
        'weighted_performance' => 'decimal:2',
        'weighted_attendance' => 'decimal:2',
        'weighted_cooperation' => 'decimal:2',
        'weighted_innovation' => 'decimal:2',
        'weighted_appearance' => 'decimal:2',
        'weighted_complaint' => 'decimal:2',
        'total_score' => 'decimal:2',
        'eval_score' => 'decimal:2',
        'rank_position' => 'integer',
        'is_locked' => 'boolean',
        'locked_at' => 'datetime',
    ];

    /**
     * ID: Pegawai / petugas BPS yang dievaluasi kinerjanya pada periode ini.
     * EN: BPS officer personnel whose performance is evaluated for this period.
     *
     * @return BelongsTo<User, ElenaEvaluation>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
