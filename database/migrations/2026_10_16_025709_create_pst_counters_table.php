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
        Schema::create('pst_counters', function (Blueprint $table) {
            $table->id();
            $table->string('counter_name', 50)->comment('Nama loket (contoh: Loket 1 - Pelayanan Terpadu)');
            $table->unsignedTinyInteger('counter_number')->comment('Nomor urut loket');
            $table->string('static_qr_slug', 50)->unique()->comment('Slug QR statis plakat meja (contoh: loket-1)');
            $table->foreignId('active_operator_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('Petugas yang sedang aktif bertugas di loket');
            $table->boolean('is_open')->default(false)->comment('Status ketersediaan loket (buka/tutup)');
            $table->string('description', 255)->nullable()->comment('Keterangan tugas loket');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pst_counters');
    }
};
