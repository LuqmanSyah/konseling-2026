<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_konseling', function (Blueprint $table) {
            $table->id();
            $table->string('kode_booking')->unique();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->foreignId('jadwal_id')->constrained('jadwal_konseling')->cascadeOnDelete();
            $table->foreignId('konselor_id')->constrained('konselor')->cascadeOnDelete();
            $table->string('kategori')->index();
            $table->string('metode')->index();
            $table->text('keluhan_awal');
            $table->string('status')->default('diajukan')->index();
            $table->string('link_meeting')->nullable();
            $table->text('alasan_pembatalan')->nullable();
            $table->timestamps();

            $table->index(['mahasiswa_id', 'status']);
            $table->index(['konselor_id', 'status']);
            $table->index(['jadwal_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_konseling');
    }
};
