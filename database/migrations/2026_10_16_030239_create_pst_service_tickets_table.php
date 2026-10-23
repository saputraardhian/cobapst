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
        Schema::create('pst_service_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number', 20)->comment('Nomor tampilan tiket antrian (contoh: A-001)');
            $table->string('queue_code', 10)->comment('Kode alfabet antrian (A, B, C, D)');
            $table->unsignedSmallInteger('queue_number')->comment('Nomor urut harian antrian');
            $table->string('qr_token', 64)->unique()->index()->comment('Token hash unik untuk link survei QR');
            $table->date('service_date')->index()->comment('Tanggal operasional pelayanan');
            $table->string('service_type', 50)->comment('Kategori: perpustakaan, konsultasi, pembelian, rekomendasi');
            $table->foreignId('visitor_id')
                ->nullable()
                ->constrained('pst_visitors')
                ->nullOnDelete()
                ->comment('Relasi ke data profil pengunjung');
            $table->string('visitor_name')->comment('Nama pengunjung saat tiket dibuat');
            $table->boolean('is_first_visit')->default(false)->comment('Apakah baru pertama kali berkunjung');
            $table->foreignId('counter_id')
                ->nullable()
                ->constrained('pst_counters')
                ->nullOnDelete()
                ->comment('Loket yang melayani tiket');
            $table->foreignId('served_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('Petugas / operator yang melayani');
            $table->text('request_detail')->nullable()->comment('Rincian data / layanan yang diminta');
            $table->text('service_report')->nullable()->comment('Laporan penyelesaian layanan oleh petugas');
            $table->string('status', 20)->default('waiting')->index()->comment('waiting, called, serving, completed, skipped');
            $table->dateTime('called_at')->nullable()->comment('Waktu pertama kali dipanggil');
            $table->dateTime('started_at')->nullable()->comment('Waktu mulai dilayani di meja loket');
            $table->dateTime('finished_at')->nullable()->comment('Waktu layanan selesai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pst_service_tickets');
    }
};
