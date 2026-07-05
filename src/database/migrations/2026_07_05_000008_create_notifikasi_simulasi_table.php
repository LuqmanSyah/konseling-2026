<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi_simulasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('booking_konseling')->cascadeOnDelete();
            $table->foreignId('penerima_id')->constrained('users')->cascadeOnDelete();
            $table->string('jenis')->index();
            $table->text('pesan');
            $table->string('channel')->default('sistem')->index();
            $table->string('status')->default('tercatat')->index();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi_simulasi');
    }
};
