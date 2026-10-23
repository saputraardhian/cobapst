<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Jadwal Tugas Jaga Loket Pristi (SEMAR PST).
 *
 * ID:
 * Merepresentasikan penugasan shift petugas pelayanan statistik terpadu di meja loket
 * BPS Provinsi Jawa Tengah. Mendukung pencatatan jadwal harian, penugasan loket, serta
 * mekanisme pertukaran shift jaga (swap) antar-pegawai secara akuntabel.
 *
 * EN:
 * Represents operator desk duty scheduling within the integrated SEMAR PST system.
 * Manages daily shift assignments, counter allocations, and official shift-swap
 * workflows between BPS personnel with full relational integrity.
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $counter_id
 * @property \Illuminate\Support\Carbon $schedule_date
 * @property string $day_name
 * @property string $shift_name
 * @property int|null $swapped_with_user_id
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class PristiSchedule extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'pristi_schedules';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'counter_id',
        'schedule_date',
        'day_name',
        'shift_name',
        'swapped_with_user_id',
        'notes',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'schedule_date' => 'date',
    ];

    /**
     * ID: Petugas BPS yang ditugaskan dalam jadwal shift loket ini.
     * EN: Primary BPS officer assigned to this scheduled counter shift.
     *
     * @return BelongsTo<User, PristiSchedule>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * ID: Meja loket fisik pelayanan PST tempat petugas bertugas.
     * EN: Physical PST service counter desk where the officer is deployed.
     *
     * @return BelongsTo<PstCounter, PristiSchedule>
     */
    public function counter(): BelongsTo
    {
        return $this->belongsTo(PstCounter::class, 'counter_id');
    }

    /**
     * ID: Petugas pengganti apabila terjadi pertukaran jadwal jaga resmi.
     * EN: Replacement BPS officer in case of an authorized shift swap.
     *
     * @return BelongsTo<User, PristiSchedule>
     */
    public function swappedWith(): BelongsTo
    {
        return $this->belongsTo(User::class, 'swapped_with_user_id');
    }

    /**
     * ID: Riwayat presensi riil (check-in/check-out) yang terikat pada jadwal ini.
     * EN: Actual attendance records (check-in/check-out) linked to this schedule.
     *
     * @return HasMany<PristiAttendance>
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(PristiAttendance::class, 'schedule_id');
    }
}
