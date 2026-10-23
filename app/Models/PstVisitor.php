<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Master Pengunjung Buku Tamu PST (SEMAR PST).
 *
 * ID:
 * Merepresentasikan profil buku tamu digital pengunjung Pelayanan Statistik Terpadu (PST).
 * Menggunakan indeks nomor ponsel unik (`phone_number`) sebagai dasar auto-lookup cepat
 * saat pengunjung datang kembali ke lobi BPS Provinsi Jawa Tengah.
 *
 * EN:
 * Represents master digital guestbook visitor profiles for the integrated PST service.
 * Utilizes a unique telephone index (`phone_number`) for instantaneous auto-lookup
 * during returning visits at the BPS lobby kiosk.
 *
 * @property int $id
 * @property string $phone_number
 * @property string $name
 * @property string|null $email
 * @property string|null $institution
 * @property string|null $address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class PstVisitor extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'pst_visitors';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'phone_number',
        'name',
        'email',
        'institution',
        'address',
    ];

    /**
     * ID: Riwayat log seluruh kunjungan buku tamu oleh pengunjung ini.
     * EN: Complete chronological guestbook visit logs recorded for this visitor.
     *
     * @return HasMany<PstVisitorLog>
     */
    public function visitorLogs(): HasMany
    {
        return $this->hasMany(PstVisitorLog::class, 'visitor_id');
    }

    /**
     * ID: Seluruh tiket antrian layanan PST yang pernah diterbitkan untuk pengunjung ini.
     * EN: All PST service queue tickets issued and associated with this visitor.
     *
     * @return HasMany<PstServiceTicket>
     */
    public function serviceTickets(): HasMany
    {
        return $this->hasMany(PstServiceTicket::class, 'visitor_id');
    }
}
