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
        Schema::create('pst_visitors', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number', 20)->unique()->index()->comment('Nomor WhatsApp / HP pengunjung');
            $table->string('name')->comment('Nama lengkap pengunjung');
            $table->string('email')->nullable()->comment('Alamat email pengunjung');
            $table->string('institution')->nullable()->comment('Asal instansi / universitas / umum');
            $table->text('address')->nullable()->comment('Alamat domisili pengunjung');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pst_visitors');
    }
};
