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
        Schema::create('public_complaints', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_code', 30)->unique()->index()->comment('Kode lacak pengaduan (ADU-YYYY-XXXX)');
            $table->string('reporter_name')->comment('Nama pelapor');
            $table->string('reporter_email')->nullable()->comment('Email pelapor');
            $table->string('reporter_phone', 20)->nullable()->comment('Nomor kontak WhatsApp pelapor');
            $table->string('complaint_category', 50)->comment('Kategori: pelayanan, sarana_prasarana, petugas, pungutan_liar, lainnya');
            $table->text('complaint_content')->comment('Isi uraian aduan / keluhan');
            $table->text('public_feedback')->nullable()->comment('Tanggapan / balasan resmi untuk pelapor');
            $table->text('internal_follow_up')->nullable()->comment('Catatan tindak lanjut internal BPS');
            $table->foreignId('related_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('Petugas terkait jika aduan berkaitan dengan oknum tertentu');
            $table->boolean('is_valid_penalty')->default(false)->comment('Flag aduan valid sebagai variabel penalti modul Elena');
            $table->string('status', 20)->default('received')->index()->comment('received, investigating, resolved, rejected');
            $table->string('remote_ip', 45)->nullable()->comment('Alamat IP pengirim aduan');
            $table->dateTime('resolved_at')->nullable()->comment('Waktu penyelesaian aduan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('public_complaints');
    }
};
