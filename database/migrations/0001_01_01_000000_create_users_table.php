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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nip_baru', 18)->unique()->nullable()->comment('NIP 18 Digit Pegawai BPS');
            $table->string('nip_lama', 9)->nullable()->comment('NIP 9 Digit Lama');
            $table->string('name')->comment('Nama lengkap beserta gelar');
            $table->string('name_without_title')->nullable()->comment('Nama tanpa gelar untuk pencocokan data');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('whatsapp_number', 20)->nullable()->comment('Nomor WhatsApp aktif');
            $table->string('region_code', 10)->nullable()->comment('Kode wilayah / Satker BPS');
            $table->string('unit_code', 20)->nullable()->comment('Kode unit kerja');
            $table->string('unit_name', 100)->nullable()->comment('Nama fungsi / unit kerja');
            $table->string('echelon_id', 10)->nullable()->comment('Tingkat eselon jabatan');
            $table->string('elena_team_code', 20)->nullable()->comment('Kode tim evaluasi Elena');
            $table->string('elena_calc_code', 20)->nullable()->comment('Kode rumus perhitungan Elena');
            $table->boolean('is_active')->default(true)->comment('Status aktif pegawai');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
