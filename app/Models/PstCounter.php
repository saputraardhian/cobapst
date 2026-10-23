<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Meja Loket Layanan PST (SEMAR PST).
 *
 * ID:
 * Merepresentasikan meja fisik pelayanan statistik terpadu di lobi BPS Provinsi Jawa Tengah.
 * Mengelola status buka/tutup loket, penugasan operator yang sedang bertugas aktif, slug QR code
 * statis plakat meja (`/loket/{slug}/survei`), serta keterikatan tiket dan survei.
 *
 * EN:
 * Represents physical PST service counter desks stationed at the BPS lobby.
 * Manages operational status (open/closed), active assigned operator personnel, static desk
 * plaque QR slugs (`/loket/{slug}/survei`), and linked tickets and customer feedback surveys.
 *
 * @property int $id
 * @property string $counter_name
 * @property int $counter_number
 * @property string $static_qr_slug
 * @property int|null $active_operator_id
 * @property bool $is_open
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class PstCounter extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'pst_counters';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'counter_name',
        'counter_number',
        'static_qr_slug',
        'active_operator_id',
        'is_open',
        'description',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'counter_number' => 'integer',
        'is_open' => 'boolean',
    ];

    /**
     * ID: Petugas/operator BPS yang sedang aktif bertugas di loket ini.
     * EN: Active BPS operator personnel currently stationed at this counter desk.
     *
     * @return BelongsTo<User, PstCounter>
     */
    public function activeOperator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'active_operator_id');
    }

    /**
     * ID: Seluruh tiket antrian layanan yang dilayani pada meja loket ini.
     * EN: All service tickets processed and served at this counter desk.
     *
     * @return HasMany<PstServiceTicket>
     */
    public function serviceTickets(): HasMany
    {
        return $this->hasMany(PstServiceTicket::class, 'counter_id');
    }

    /**
     * ID: Riwayat survei kepuasan pengunjung yang tercatat pada meja loket ini.
     * EN: Visitor satisfaction surveys submitted and linked to this counter desk.
     *
     * @return HasMany<StatisfySurvey>
     */
    public function surveys(): HasMany
    {
        return $this->hasMany(StatisfySurvey::class, 'counter_id');
    }
}
