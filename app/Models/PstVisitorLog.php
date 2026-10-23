<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Log Kunjungan Buku Tamu PST (SEMAR PST).
 *
 * ID:
 * Merepresentasikan pencatatan histori setiap sesi kedatangan pengunjung di lobi BPS Jateng.
 * Mencatat tanggal kunjungan, jam check-in kedatangan, keperluan/tujuan konsultasi statistik,
 * serta jam kepulangan (check-out) pengunjung.
 *
 * EN:
 * Represents historical lobby visit session logs for BPS Central Java guestbook records.
 * Tracks specific visit dates, check-in timestamps, stated consultation purposes,
 * and departure (check-out) timestamps.
 *
 * @property int $id
 * @property int $visitor_id
 * @property \Illuminate\Support\Carbon $visit_date
 * @property string|null $purpose
 * @property \Illuminate\Support\Carbon $check_in_at
 * @property \Illuminate\Support\Carbon|null $check_out_at
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class PstVisitorLog extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'pst_visitor_logs';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'visitor_id',
        'visit_date',
        'purpose',
        'check_in_at',
        'check_out_at',
        'notes',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'visit_date' => 'date',
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime',
    ];

    /**
     * ID: Data induk master pengunjung terkait log kunjungan ini.
     * EN: Master visitor profile associated with this specific visit session log.
     *
     * @return BelongsTo<PstVisitor, PstVisitorLog>
     */
    public function visitor(): BelongsTo
    {
        return $this->belongsTo(PstVisitor::class, 'visitor_id');
    }
}
