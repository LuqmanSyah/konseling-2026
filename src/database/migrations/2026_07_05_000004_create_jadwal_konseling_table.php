<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_konseling', function (Blueprint $table) {
            $table->id();
            $table->foreignId('konselor_id')->constrained('konselor')->cascadeOnDelete();
            $table->string('hari')->index();
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('metode')->index();
            $table->string('status')->default('tersedia')->index();
            $table->timestamps();

            $table->index(['konselor_id', 'hari', 'jam_mulai', 'jam_selesai'], 'jadwal_konseling_konselor_waktu_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_konseling');
    }
};
