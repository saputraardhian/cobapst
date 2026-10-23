<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Survei Kepuasan Masyarakat Statisfy (SEMAR PST).
 *
 * ID:
 * Merepresentasikan rekaman pengisian survei kepuasan instan (15 detik) oleh responden
 * pengunjung layanan statistik terpadu BPS. Terikat 1-ke-1 secara unik dengan tiket layanan
 * (`service_ticket_id`), mencatat skor kepuasan (1-6 / bintang), ulasan teks, dan identitas petugas dinilai.
 *
 * EN:
 * Represents instant (15-second) public satisfaction survey submissions (Statisfy).
 * Uniquely bound (1-to-1) to an issued service ticket (`service_ticket_id`), capturing
 * rating scores (1-6 stars), qualitative feedback, and the evaluated officer identity.
 *
 * @property int $id
 * @property int|null $service_ticket_id
 * @property int|null $rated_user_id
 * @property int|null $counter_id
 * @property \Illuminate\Support\Carbon $survey_date
 * @property string|null $respondent_name
 * @property string|null $respondent_email
 * @property string|null $respondent_phone
 * @property string|null $service_type
 * @property string $media_channel
 * @property int $rating_score
 * @property string|null $feedback_comment
 * @property string|null $remote_ip
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class StatisfySurvey extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'statisfy_surveys';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'service_ticket_id',
        'rated_user_id',
        'counter_id',
        'survey_date',
        'respondent_name',
        'respondent_email',
        'respondent_phone',
        'service_type',
        'media_channel',
        'rating_score',
        'feedback_comment',
        'remote_ip',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'survey_date' => 'date',
        'rating_score' => 'integer',
    ];

    /**
     * ID: Tiket layanan PST yang dinilai oleh survei ini.
     * EN: PST service ticket evaluated by this satisfaction survey.
     *
     * @return BelongsTo<PstServiceTicket, StatisfySurvey>
     */
    public function serviceTicket(): BelongsTo
    {
        return $this->belongsTo(PstServiceTicket::class, 'service_ticket_id');
    }

    /**
     * ID: Petugas / operator BPS yang dinilai tingkat pelayanannya.
     * EN: BPS officer whose service performance is rated in this survey.
     *
     * @return BelongsTo<User, StatisfySurvey>
     */
    public function ratedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rated_user_id');
    }

    /**
     * ID: Meja loket fisik tempat pelayanan statistik diberikan.
     * EN: Physical service counter desk where the consultation occurred.
     *
     * @return BelongsTo<PstCounter, StatisfySurvey>
     */
    public function counter(): BelongsTo
    {
        return $this->belongsTo(PstCounter::class, 'counter_id');
    }
}
