<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Pengaduan Masyarakat (SEMAR PST).
 *
 * ID:
 * Merepresentasikan saluran aspirasi dan pengaduan publik terkait layanan statistik BPS Jateng.
 * Dilengkapi kode pelacakan unik publik (`tracking_code` dengan format `ADU-YYYY-XXXX`),
 * tindak lanjut investigasi internal, respon publik, serta flag penalti kinerja (`is_valid_penalty`)
 * yang secara otomatis memotong komponen skor pengaduan pada evaluasi bulanan Elena.
 *
 * EN:
 * Represents public grievances, whistleblowing, and feedback for BPS Central Java services.
 * Features a unique public tracking identifier (`tracking_code` formatted as `ADU-YYYY-XXXX`),
 * internal investigation audit trails, public responses, and performance penalty flags (`is_valid_penalty`)
 * that feed into Elena monthly evaluation deductions.
 *
 * @property int $id
 * @property string $tracking_code
 * @property string $reporter_name
 * @property string|null $reporter_email
 * @property string|null $reporter_phone
 * @property string $complaint_category
 * @property string $complaint_content
 * @property string|null $public_feedback
 * @property string|null $internal_follow_up
 * @property int|null $related_user_id
 * @property bool $is_valid_penalty
 * @property string $status
 * @property string|null $remote_ip
 * @property \Illuminate\Support\Carbon|null $resolved_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class PublicComplaint extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'public_complaints';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'tracking_code',
        'reporter_name',
        'reporter_email',
        'reporter_phone',
        'complaint_category',
        'complaint_content',
        'public_feedback',
        'internal_follow_up',
        'related_user_id',
        'is_valid_penalty',
        'status',
        'remote_ip',
        'resolved_at',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'is_valid_penalty' => 'boolean',
        'resolved_at' => 'datetime',
    ];

    /**
     * ID: Petugas BPS yang diadukan atau terkait dalam materi aduan publik ini.
     * EN: BPS personnel implicated in or assigned to this public complaint.
     *
     * @return BelongsTo<User, PublicComplaint>
     */
    public function relatedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'related_user_id');
    }
}
