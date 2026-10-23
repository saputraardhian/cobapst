<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Model Tiket Antrian & Layanan PST (SEMAR PST).
 *
 * ID:
 * Merepresentasikan tiket antrian fisik dan registrasi layanan PST di lobi BPS Jateng.
 * Mengelola siklus antrian (`waiting`, `calling`, `serving`, `completed`, `cancelled`),
 * kode antrian (A/B), token QR dinamis pada struk thermal (`/s/{qr_token}`),
 * penugasan loket, operator yang bertugas, serta rekaman hasil konsultasi statistik.
 *
 * EN:
 * Represents physical queue tickets and service registration within the BPS PST lobby.
 * Governs the queue lifecycle (`waiting`, `calling`, `serving`, `completed`, `cancelled`),
 * queue codes (A/B), dynamic QR tokens on thermal tickets (`/s/{qr_token}`),
 * counter assignments, serving officers, and final statistical consultation reports.
 *
 * @property int $id
 * @property string $ticket_number
 * @property string $queue_code
 * @property int $queue_number
 * @property string $qr_token
 * @property \Illuminate\Support\Carbon $service_date
 * @property string $service_type
 * @property int|null $visitor_id
 * @property string $visitor_name
 * @property bool $is_first_visit
 * @property int|null $counter_id
 * @property int|null $served_by_user_id
 * @property string|null $request_detail
 * @property string|null $service_report
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $called_at
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $finished_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class PstServiceTicket extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'pst_service_tickets';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'ticket_number',
        'queue_code',
        'queue_number',
        'qr_token',
        'service_date',
        'service_type',
        'visitor_id',
        'visitor_name',
        'is_first_visit',
        'counter_id',
        'served_by_user_id',
        'request_detail',
        'service_report',
        'status',
        'called_at',
        'started_at',
        'finished_at',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'service_date' => 'date',
        'queue_number' => 'integer',
        'is_first_visit' => 'boolean',
        'called_at' => 'datetime',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    /**
     * ID: Profil pengunjung pemilik tiket antrian layanan ini.
     * EN: Registered visitor profile owning this service queue ticket.
     *
     * @return BelongsTo<PstVisitor, PstServiceTicket>
     */
    public function visitor(): BelongsTo
    {
        return $this->belongsTo(PstVisitor::class, 'visitor_id');
    }

    /**
     * ID: Meja loket fisik tempat tiket ini dipanggil dan dilayani.
     * EN: Physical service counter desk where this ticket is called and served.
     *
     * @return BelongsTo<PstCounter, PstServiceTicket>
     */
    public function counter(): BelongsTo
    {
        return $this->belongsTo(PstCounter::class, 'counter_id');
    }

    /**
     * ID: Petugas / operator BPS yang melayani tiket ini hingga selesai.
     * EN: BPS operator officer who attended to and completed this service ticket.
     *
     * @return BelongsTo<User, PstServiceTicket>
     */
    public function servedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'served_by_user_id');
    }

    /**
     * ID: Hasil pengisian survei kepuasan masyarakat (Statisfy) untuk tiket ini.
     * EN: Customer satisfaction survey submission (Statisfy) tied to this ticket.
     *
     * @return HasOne<StatisfySurvey>
     */
    public function survey(): HasOne
    {
        return $this->hasOne(StatisfySurvey::class, 'service_ticket_id');
    }
}
