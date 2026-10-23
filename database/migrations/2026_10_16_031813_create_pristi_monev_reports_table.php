<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pristi_monev_reports', function (Blueprint $table) {
            $table->id();
            $table->string('region_code', 10)->index()->comment('Kode Kabupaten / Kota BPS se-Jateng');
            $table->string('region_name', 100)->comment('Nama BPS Kabupaten / Kota');
            $table->unsignedSmallInteger('period_year')->index()->comment('Tahun periode monev');
            $table->string('period_type', 20)->comment('Jenis periode: bulan, triwulan, semester');
            $table->unsignedTinyInteger('period_value')->comment('Nilai periode (1-12 bulan, 1-4 triwulan, 1-2 semester)');
            $table->decimal('score_adm', 5, 2)->default(0)->comment('Dimensi Pelayanan Administrasi');
            $table->decimal('score_teknis', 5, 2)->default(0)->comment('Dimensi Pelayanan Teknis');
            $table->decimal('score_sdm', 5, 2)->default(0)->comment('Dimensi Sumber Daya Manusia');
            $table->decimal('score_korupsi', 5, 2)->default(0)->comment('Dimensi Persepsi Anti Korupsi');
            $table->decimal('score_prof', 5, 2)->default(0)->comment('Dimensi Profesionalisme');
            $table->decimal('score_sarpras', 5, 2)->default(0)->comment('Dimensi Sarana & Prasarana');
            $table->decimal('score_sipp', 5, 2)->default(0)->comment('Dimensi Kepatuhan SIPP');
            $table->decimal('score_pengaduan', 5, 2)->default(0)->comment('Dimensi Pengelolaan Pengaduan');
            $table->decimal('score_inovasi', 5, 2)->default(0)->comment('Dimensi Inovasi Pelayanan');
            $table->decimal('total_score', 5, 2)->default(0)->comment('Akumulasi rata-rata total skor 9 dimensi');
            $table->foreignId('verified_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('Petugas / Admin Provinsi yang memverifikasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pristi_monev_reports');
    }
};
