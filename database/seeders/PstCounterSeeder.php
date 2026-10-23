<?php

namespace Database\Seeders;

use App\Models\PstCounter;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Seeder Master Meja Loket Pelayanan Statistik Terpadu (PST).
 *
 * ID:
 * Menginisialisasi 5 meja loket pelayanan fisik PST di lobi BPS Provinsi Jawa Tengah:
 * Loket 1 s.d. Loket 5 sesuai standar layout operasional PST BPS Jateng.
 *
 * EN:
 * Seeds the 5 physical PST frontline service counters located in the BPS Central Java lobby
 * according to official facility operational layouts.
 */
class PstCounterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find default operator or first active employee
        $defaultOperator = User::where('email', 'operator.pst@bps.go.id')->first()
            ?? User::where('name', 'like', '%Hendra%')->first()
            ?? User::first();

        $operatorId = $defaultOperator?->id;

        $counters = [
            [
                'counter_number' => 1,
                'counter_name' => 'Loket 1 - Konsultasi Statistik 1',
                'static_qr_slug' => 'loket-1',
                'active_operator_id' => $operatorId,
                'is_open' => true,
                'description' => 'Konsultasi statistik umum, metodologi survei, data kemiskinan, ketenagakerjaan, dan sosial.',
            ],
            [
                'counter_number' => 2,
                'counter_name' => 'Loket 2 - Konsultasi Statistik 2',
                'static_qr_slug' => 'loket-2',
                'active_operator_id' => $operatorId,
                'is_open' => true,
                'description' => 'Konsultasi statistik nerwilis, PDRB, inflasi, ekspor-impor, dan perekonomian regional.',
            ],
            [
                'counter_number' => 3,
                'counter_name' => 'Loket 3 - Perpustakaan Digital & Fisik',
                'static_qr_slug' => 'loket-3',
                'active_operator_id' => null,
                'is_open' => true,
                'description' => 'Akses koleksi publikasi cetak, buku statistik daerah, dan repositori digital BPS.',
            ],
            [
                'counter_number' => 4,
                'counter_name' => 'Loket 4 - Pembelian Data Mikro & Publikasi',
                'static_qr_slug' => 'loket-4',
                'active_operator_id' => null,
                'is_open' => true,
                'description' => 'Layanan permohonan data mikro (raw data), peta digital, dan publikasi berbayar PNBP.',
            ],
            [
                'counter_number' => 5,
                'counter_name' => 'Loket 5 - Rekomendasi Kegiatan Statistik (ROMANIK)',
                'static_qr_slug' => 'loket-5',
                'active_operator_id' => null,
                'is_open' => true,
                'description' => 'Pelayanan rekomendasi kegiatan statistik sektoral dan pembinaan statistik instansi Pemda.',
            ],
        ];

        foreach ($counters as $c) {
            PstCounter::updateOrCreate(
                ['counter_number' => $c['counter_number']],
                $c
            );
        }
    }
}
