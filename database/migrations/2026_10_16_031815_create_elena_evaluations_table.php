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
        Schema::create('elena_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->comment('Pegawai / petugas PST yang dievaluasi');
            $table->unsignedSmallInteger('period_year')->index()->comment('Tahun evaluasi kinerja');
            $table->unsignedTinyInteger('period_month')->index()->comment('Bulan evaluasi kinerja (1-12)');
            $table->unsignedInteger('total_services_count')->default(0)->comment('Volume layanan (COUNT completed dari pst_service_tickets)');
            $table->unsignedInteger('total_surveys_count')->default(0)->comment('Jumlah survei masuk dari statisfy_surveys');
            $table->decimal('raw_satisfaction_avg', 4, 2)->default(0)->comment('Rata-rata kepuasan responden');
            $table->decimal('raw_performance_score', 5, 2)->default(0)->comment('Skor kinerja pelayanan');
            $table->decimal('raw_attendance_score', 5, 2)->default(0)->comment('Skor kehadiran & ketepatan waktu dari pristi_attendances');
            $table->decimal('raw_cooperation_score', 5, 2)->default(0)->comment('Nilai perilaku / kerjasama SKP');
            $table->decimal('raw_innovation_score', 5, 2)->default(0)->comment('Nilai inovatif & kreatif');
            $table->decimal('raw_appearance_score', 5, 2)->default(0)->comment('Nilai kerapian & penampilan');
            $table->unsignedInteger('raw_complaint_count')->default(0)->comment('Jumlah aduan valid pengurang nilai dari public_complaints');
            $table->decimal('weighted_performance', 5, 2)->default(0)->comment('Nilai kinerja tertimbang');
            $table->decimal('weighted_attendance', 5, 2)->default(0)->comment('Nilai kehadiran tertimbang');
            $table->decimal('weighted_cooperation', 5, 2)->default(0)->comment('Nilai kerjasama tertimbang');
            $table->decimal('weighted_innovation', 5, 2)->default(0)->comment('Nilai inovasi tertimbang');
            $table->decimal('weighted_appearance', 5, 2)->default(0)->comment('Nilai penampilan tertimbang');
            $table->decimal('weighted_complaint', 5, 2)->default(0)->comment('Faktor pengurang komplain tertimbang');
            $table->decimal('total_score', 6, 2)->default(0)->comment('Total skor akhir tertimbang');
            $table->decimal('eval_score', 6, 2)->default(0)->comment('Skor evaluasi terstandarisasi');
            $table->unsignedSmallInteger('rank_position')->nullable()->comment('Peringkat juara bulanan');
            $table->boolean('is_locked')->default(false)->comment('Status kunci hasil & rilis pemenang ke portal publik');
            $table->dateTime('locked_at')->nullable()->comment('Waktu penguncian hasil');
            $table->timestamps();

            $table->unique(['user_id', 'period_year', 'period_month'], 'elena_user_period_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elena_evaluations');
    }
};
