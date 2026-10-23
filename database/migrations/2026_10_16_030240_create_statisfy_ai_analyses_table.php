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
        Schema::create('statisfy_ai_analyses', function (Blueprint $table) {
            $table->id();
            $table->string('mode', 20)->comment('Mode periode: bulanan, tahunan, alltime');
            $table->unsignedTinyInteger('period_month')->nullable()->comment('Bulan periode (1-12)');
            $table->unsignedSmallInteger('period_year')->nullable()->comment('Tahun periode');
            $table->longText('analysis_result')->comment('Hasil teks analisis AI (JSON / Ringkasan Naratif)');
            $table->string('summary_sentiment', 50)->nullable()->comment('Sentimen dominan: positif, netral, negatif');
            $table->unsignedInteger('total_reviews_analyzed')->default(0)->comment('Jumlah ulasan yang dianalisis');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statisfy_ai_analyses');
    }
};
