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
        Schema::create('pst_visitor_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visitor_id')
                ->constrained('pst_visitors')
                ->cascadeOnDelete()
                ->comment('Relasi ke master pengunjung');
            $table->date('visit_date')->index()->comment('Tanggal kunjungan');
            $table->text('purpose')->nullable()->comment('Keperluan / tujuan kedatangan');
            $table->dateTime('check_in_at')->comment('Waktu kedatangan di lobi');
            $table->dateTime('check_out_at')->nullable()->comment('Waktu selesai / pulang');
            $table->text('notes')->nullable()->comment('Catatan tambahan kunjungan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pst_visitor_logs');
    }
};
