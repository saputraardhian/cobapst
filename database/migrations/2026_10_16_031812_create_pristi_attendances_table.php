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
        Schema::create('pristi_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')
                ->nullable()
                ->constrained('pristi_schedules')
                ->nullOnDelete()
                ->comment('Jadwal shift acuan');
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->comment('Pegawai / petugas yang hadir');
            $table->date('attendance_date')->index()->comment('Tanggal presensi');
            $table->time('check_in_time')->nullable()->comment('Jam masuk');
            $table->time('check_out_time')->nullable()->comment('Jam pulang');
            $table->integer('time_difference')->default(0)->comment('Selisih keterlambatan dalam menit (+: terlambat, 0: tepat waktu)');
            $table->string('source', 50)->default('operator_console')->comment('operator_console, manual, machine_log');
            $table->string('status', 20)->default('present')->index()->comment('present, late, absent, permit, leave');
            $table->text('notes')->nullable()->comment('Keterangan presensi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pristi_attendances');
    }
};
