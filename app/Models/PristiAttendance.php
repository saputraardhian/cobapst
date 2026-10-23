<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Presensi Jaga Loket Pristi (SEMAR PST).
 *
 * ID:
 * Merepresentasikan pencatatan kehadiran riil petugas di loket pelayanan PST.
 * Menyimpan jam check-in, jam check-out, selisih waktu keterlambatan (`time_difference` dalam menit)
 * terhadap jadwal shift resmi, sumber presensi (`operator_console`), serta status kehadiran
 * yang menjadi parameter penalti skor kehadiran pada evaluasi kinerja Elena.
 *
 * EN:
 * Represents actual attendance logs for duty officers at PST service counters.
 * Tracks check-in and check-out timestamps, late arrival minutes (`time_difference`)
 * against scheduled shifts, recording sources (`operator_console`), and attendance statuses
 * that feed directly into monthly Elena evaluation penalty calculations.
 *
 * @property int $id
 * @property int|null $schedule_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon $attendance_date
 * @property string|null $check_in_time
 * @property string|null $check_out_time
 * @property int $time_difference
 * @property string $source
 * @property string $status
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class PristiAttendance extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'pristi_attendances';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'schedule_id',
        'user_id',
        'attendance_date',
        'check_in_time',
        'check_out_time',
        'time_difference',
        'source',
        'status',
        'notes',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'attendance_date' => 'date',
        'time_difference' => 'integer',
    ];

    /**
     * ID: Jadwal shift acuan tempat presensi kehadiran ini dicatatkan.
     * EN: Shift schedule reference against which this attendance is validated.
     *
     * @return BelongsTo<PristiSchedule, PristiAttendance>
     */
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(PristiSchedule::class, 'schedule_id');
    }

    /**
     * ID: Pegawai / petugas BPS pemilik catatan presensi kehadiran ini.
     * EN: BPS officer personnel owning this attendance record.
     *
     * @return BelongsTo<User, PristiAttendance>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
