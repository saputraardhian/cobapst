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
        Schema::create('pristi_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->comment('Petugas yang terjadwal jaga');
            $table->foreignId('counter_id')
                ->nullable()
                ->constrained('pst_counters')
                ->nullOnDelete()
                ->comment('Meja loket penugasan');
            $table->date('schedule_date')->index()->comment('Tanggal jadwal jaga');
            $table->string('day_name', 20)->comment('Nama hari (Senin - Jumat)');
            $table->string('shift_name', 50)->comment('Nama sesi shift jaga (Pagi, Siang, Full-Day)');
            $table->foreignId('swapped_with_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('Petugas pengganti jika ada tukar jaga shift');
            $table->text('notes')->nullable()->comment('Catatan jadwal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pristi_schedules');
    }
};
