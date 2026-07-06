<x-filament-panels::page>
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($this->getStats() as $stat)
            <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $stat['label'] }}</p>
                        <p class="mt-2 text-3xl font-semibold tracking-normal text-gray-950 dark:text-white">{{ $stat['value'] }}</p>
                    </div>
                    <x-filament::icon :icon="$stat['icon']" class="h-9 w-9 {{ $stat['color'] }}" />
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid gap-6 xl:grid-cols-3">
        <div class="xl:col-span-2">
            <x-filament::section>
                <x-slot name="heading">Pengajuan Menunggu Verifikasi</x-slot>

                <div class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse ($this->getPendingBookings() as $booking)
                        <a
                            href="{{ \App\Filament\Bkts\Resources\BookingKonselingResource::getUrl('view', ['record' => $booking]) }}"
                            class="flex items-center justify-between gap-4 py-3"
                        >
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-gray-950 dark:text-white">{{ $booking->kode_booking }}</p>
                                <p class="truncate text-sm text-gray-500 dark:text-gray-400">{{ $booking->mahasiswa->nama }} - {{ \App\Support\KonselingOptions::kategoriKonseling()[$booking->kategori] ?? $booking->kategori }}</p>
                            </div>
                            <span class="shrink-0 rounded-md bg-amber-100 px-2 py-1 text-xs font-medium text-amber-700 dark:bg-amber-500/10 dark:text-amber-300">
                                Diajukan
                            </span>
                        </a>
                    @empty
                        <p class="py-4 text-sm text-gray-500 dark:text-gray-400">Tidak ada pengajuan yang menunggu verifikasi.</p>
                    @endforelse
                </div>
            </x-filament::section>
        </div>

        <div>
            <x-filament::section>
                <x-slot name="heading">Akses Cepat</x-slot>

                <div class="grid gap-2">
                    @foreach ($this->getShortcuts() as $shortcut)
                        <a
                            href="{{ $shortcut['url'] }}"
                            class="flex items-center gap-3 rounded-md border border-gray-200 px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-800 dark:text-gray-200 dark:hover:bg-gray-800"
                        >
                            <x-filament::icon :icon="$shortcut['icon']" class="h-5 w-5 text-sky-600" />
                            <span>{{ $shortcut['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </x-filament::section>
        </div>
    </div>

    <x-filament::section>
        <x-slot name="heading">Jadwal Mingguan</x-slot>

        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200 text-sm dark:divide-gray-800">
                <thead>
                    <tr class="text-left text-xs font-semibold uppercase tracking-normal text-gray-500 dark:text-gray-400">
                        <th class="py-3 pr-4">Hari</th>
                        <th class="px-4 py-3">Waktu</th>
                        <th class="px-4 py-3">Konselor</th>
                        <th class="px-4 py-3">Metode</th>
                        <th class="py-3 pl-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($this->getUpcomingSchedules() as $jadwal)
                        <tr>
                            <td class="py-3 pr-4 font-medium text-gray-950 dark:text-white">{{ \App\Support\KonselingOptions::hariDalamMinggu()[$jadwal->hari] ?? $jadwal->hari }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $jadwal->jam_mulai->format('H:i') }} - {{ $jadwal->jam_selesai->format('H:i') }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $jadwal->konselor->nama }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ \App\Support\KonselingOptions::metodeKonseling()[$jadwal->metode] ?? $jadwal->metode }}</td>
                            <td class="py-3 pl-4 text-gray-600 dark:text-gray-300">{{ \App\Support\KonselingOptions::jadwalStatuses()[$jadwal->status] ?? $jadwal->status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-gray-500 dark:text-gray-400">Belum ada jadwal mingguan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-panels::page>
