<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catatan_konseling', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->unique()->constrained('booking_konseling')->cascadeOnDelete();
            $table->foreignId('konselor_id')->constrained('konselor')->cascadeOnDelete();
            $table->text('catatan_hasil');
            $table->text('rekomendasi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catatan_konseling');
    }
};
