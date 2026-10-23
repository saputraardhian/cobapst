<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * Model Master Pengguna & Pegawai BPS (SEMAR PST).
 *
 * ID:
 * Model terpusat data pegawai BPS Provinsi Jawa Tengah hasil konsolidasi 5 sistem legacy.
 * Menyimpan identitas kepegawaian resmi (NIP 18 digit, NIP lama 9 digit), penugasan tim kerja,
 * kode unit kerja, kode formula perhitungan evaluasi Elena, serta hak akses multi-role (RBAC).
 *
 * EN:
 * Centralized User and BPS Personnel model consolidating master records from 5 legacy applications.
 * Maintains official BPS personnel identities (18-digit NIP, 9-digit legacy NIP), team allocations,
 * work unit hierarchies, Elena calculation codes, and multi-role RBAC authorization.
 *
 * @property int $id
 * @property string|null $nip_baru
 * @property string|null $nip_lama
 * @property string $name
 * @property string|null $name_without_title
 * @property string $email
 * @property string $password
 * @property string|null $whatsapp_number
 * @property string|null $region_code
 * @property string|null $unit_code
 * @property string|null $unit_name
 * @property string|null $echelon_id
 * @property string|null $elena_team_code
 * @property string|null $elena_calc_code
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'nip_baru',
        'nip_lama',
        'name',
        'name_without_title',
        'email',
        'password',
        'whatsapp_number',
        'region_code',
        'unit_code',
        'unit_name',
        'echelon_id',
        'elena_team_code',
        'elena_calc_code',
        'is_active',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * ID: Menentukan apakah pengguna diizinkan mengakses panel admin Filament.
     * EN: Determine if the user is authorized to access the Filament admin panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return (bool) $this->is_active;
    }

    /**
     * ID: Meja loket aktif tempat pegawai bertugas sebagai operator saat ini.
     * EN: Active service counter where this officer is currently deployed as operator.
     *
     * @return HasOne<PstCounter>
     */
    public function activeCounter(): HasOne
    {
        return $this->hasOne(PstCounter::class, 'active_operator_id');
    }

    /**
     * ID: Seluruh tiket layanan PST yang diselesaikan oleh petugas ini.
     * EN: All PST service tickets handled and fulfilled by this officer.
     *
     * @return HasMany<PstServiceTicket>
     */
    public function servedTickets(): HasMany
    {
        return $this->hasMany(PstServiceTicket::class, 'served_by_user_id');
    }

    /**
     * ID: Hasil survei kepuasan masyarakat yang menilai kinerja petugas ini.
     * EN: Public satisfaction surveys evaluating this officer's service quality.
     *
     * @return HasMany<StatisfySurvey>
     */
    public function receivedSurveys(): HasMany
    {
        return $this->hasMany(StatisfySurvey::class, 'rated_user_id');
    }

    /**
     * ID: Seluruh jadwal tugas jaga shift loket yang ditetapkan untuk pegawai ini.
     * EN: All scheduled service counter shifts assigned to this personnel.
     *
     * @return HasMany<PristiSchedule>
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(PristiSchedule::class, 'user_id');
    }

    /**
     * ID: Riwayat log presensi riil kehadiran jaga loket pegawai ini.
     * EN: Actual attendance and check-in logs recorded for this personnel.
     *
     * @return HasMany<PristiAttendance>
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(PristiAttendance::class, 'user_id');
    }

    /**
     * ID: Pengaduan publik masyarakat yang ditujukan kepada petugas ini.
     * EN: Public complaints filed against or handled by this personnel.
     *
     * @return HasMany<PublicComplaint>
     */
    public function complaints(): HasMany
    {
        return $this->hasMany(PublicComplaint::class, 'related_user_id');
    }

    /**
     * ID: Rekapitulasi evaluasi penilaian kinerja bulanan Elena untuk pegawai ini.
     * EN: Monthly Elena performance evaluation records compiled for this personnel.
     *
     * @return HasMany<ElenaEvaluation>
     */
    public function evaluations(): HasMany
    {
        return $this->hasMany(ElenaEvaluation::class, 'user_id');
    }
}
