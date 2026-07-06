<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('jadwal_konseling') || ! Schema::hasColumn('jadwal_konseling', 'tanggal')) {
            return;
        }

        if (! Schema::hasColumn('jadwal_konseling', 'hari')) {
            Schema::table('jadwal_konseling', function (Blueprint $table): void {
                $table->string('hari')->nullable()->after('konselor_id')->index();
            });
        }

        $days = [
            1 => 'senin',
            2 => 'selasa',
            3 => 'rabu',
            4 => 'kamis',
            5 => 'jumat',
            6 => 'sabtu',
            7 => 'minggu',
        ];

        DB::table('jadwal_konseling')
            ->select(['id', 'tanggal'])
            ->orderBy('id')
            ->get()
            ->each(function (object $jadwal) use ($days): void {
                DB::table('jadwal_konseling')
                    ->where('id', $jadwal->id)
                    ->update([
                        'hari' => $days[Carbon::parse($jadwal->tanggal)->dayOfWeekIso],
                    ]);
            });

        Schema::table('jadwal_konseling', function (Blueprint $table): void {
            $table->string('hari')->nullable(false)->change();
            $table->dropIndex('jadwal_konseling_konselor_waktu_index');
            $table->dropColumn('tanggal');
            $table->index(['konselor_id', 'hari', 'jam_mulai', 'jam_selesai'], 'jadwal_konseling_konselor_waktu_index');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('jadwal_konseling') || Schema::hasColumn('jadwal_konseling', 'tanggal')) {
            return;
        }

        Schema::table('jadwal_konseling', function (Blueprint $table): void {
            $table->date('tanggal')->nullable()->after('konselor_id')->index();
            $table->dropIndex('jadwal_konseling_konselor_waktu_index');
            $table->index(['konselor_id', 'tanggal', 'jam_mulai', 'jam_selesai'], 'jadwal_konseling_konselor_waktu_index');
        });
    }
};
