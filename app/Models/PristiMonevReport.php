<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Laporan Monitoring & Evaluasi Pristi Kab/Kota (SEMAR PST).
 *
 * ID:
 * Menyimpan rekapitulasi penilaian kinerja pelayanan statistik terpadu di 35 BPS Kabupaten/Kota
 * se-Jawa Tengah berdasarkan 9 dimensi penilaian baku (Administrasi, Teknis, SDM, Anti Korupsi,
 * Profesionalitas, Sarpras, SIPP, Penanganan Pengaduan, dan Inovasi Pelayanan).
 * Mendukung filter agregasi periodik: bulanan, triwulanan, dan semesteran.
 *
 * EN:
 * Stores monitoring and evaluation scores for PST services across 35 Regency/City BPS offices
 * in Central Java based on 9 official evaluation dimensions.
 * Supports periodic aggregation scopes (monthly, quarterly, and semester) with official verification audits.
 *
 * @property int $id
 * @property string $region_code
 * @property string $region_name
 * @property int $period_year
 * @property string $period_type
 * @property int $period_value
 * @property string $score_adm
 * @property string $score_teknis
 * @property string $score_sdm
 * @property string $score_korupsi
 * @property string $score_prof
 * @property string $score_sarpras
 * @property string $score_sipp
 * @property string $score_pengaduan
 * @property string $score_inovasi
 * @property string $total_score
 * @property int|null $verified_by_user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class PristiMonevReport extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'pristi_monev_reports';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'region_code',
        'region_name',
        'period_year',
        'period_type',
        'period_value',
        'score_adm',
        'score_teknis',
        'score_sdm',
        'score_korupsi',
        'score_prof',
        'score_sarpras',
        'score_sipp',
        'score_pengaduan',
        'score_inovasi',
        'total_score',
        'verified_by_user_id',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'period_year' => 'integer',
        'period_value' => 'integer',
        'score_adm' => 'decimal:2',
        'score_teknis' => 'decimal:2',
        'score_sdm' => 'decimal:2',
        'score_korupsi' => 'decimal:2',
        'score_prof' => 'decimal:2',
        'score_sarpras' => 'decimal:2',
        'score_sipp' => 'decimal:2',
        'score_pengaduan' => 'decimal:2',
        'score_inovasi' => 'decimal:2',
        'total_score' => 'decimal:2',
    ];

    /**
     * ID: Pejabat / verifikator provinsi yang mengesahkan laporan monev kab/kota ini.
     * EN: Provincial verifier officer who audited and approved this regional monev report.
     *
     * @return BelongsTo<User, PristiMonevReport>
     */
    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by_user_id');
    }
}
