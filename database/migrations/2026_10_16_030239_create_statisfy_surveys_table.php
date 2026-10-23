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
        Schema::create('statisfy_surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_ticket_id')
                ->nullable()
                ->unique()
                ->constrained('pst_service_tickets')
                ->nullOnDelete()
                ->comment('Relasi tiket layanan (1 tiket hanya bisa 1 survei)');
            $table->foreignId('rated_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('Petugas loket yang dinilai kepuasannya');
            $table->foreignId('counter_id')
                ->nullable()
                ->constrained('pst_counters')
                ->nullOnDelete()
                ->comment('Loket tempat pelayanan berlangsung');
            $table->date('survey_date')->index()->comment('Tanggal pengisian survei kepuasan');
            $table->string('respondent_name')->nullable()->comment('Nama responden');
            $table->string('respondent_email')->nullable()->comment('Email responden');
            $table->string('respondent_phone', 20)->nullable()->comment('Nomor kontak responden');
            $table->string('service_type', 50)->nullable()->comment('Jenis layanan yang dinilai');
            $table->string('media_channel', 50)->default('offline')->comment('offline, whatsapp, website, email');
            $table->unsignedTinyInteger('rating_score')->comment('Skor kepuasan responden (1-5)');
            $table->text('feedback_comment')->nullable()->comment('Ulasan / saran perbaikan pelayanan');
            $table->string('remote_ip', 45)->nullable()->comment('Alamat IP pengisi survei');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statisfy_surveys');
    }
};
