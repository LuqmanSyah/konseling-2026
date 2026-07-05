<x-filament-panels::page>
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-semibold tracking-normal text-gray-950 dark:text-white">Layanan Konseling</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Pantau pengajuan dan jadwal konseling Anda.</p>
        </div>

        <x-filament::button
            tag="a"
            :href="\App\Filament\Mahasiswa\Resources\BookingKonselingResource::getUrl('create')"
            icon="heroicon-o-plus-circle"
        >
            Ajukan Konseling
        </x-filament::button>
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
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
                <x-slot name="heading">Jadwal Aktif</x-slot>

                @php($activeBooking = $this->getActiveBooking())

                @if ($activeBooking)
                    <a
                        href="{{ \App\Filament\Mahasiswa\Resources\BookingKonselingResource::getUrl('view', ['record' => $activeBooking]) }}"
                        class="block rounded-md border border-gray-200 p-4 transition hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-800"
                    >
                        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-gray-950 dark:text-white">
                                    {{ $activeBooking->kode_booking }} - {{ $activeBooking->konselor->nama }}
                                </p>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $activeBooking->jadwalKonseling->tanggal->format('d M Y') }}
                                    {{ $activeBooking->jadwalKonseling->jam_mulai->format('H:i') }}-{{ $activeBooking->jadwalKonseling->jam_selesai->format('H:i') }}
                                    · {{ \App\Support\KonselingOptions::metodeKonseling()[$activeBooking->metode] ?? $activeBooking->metode }}
                                </p>
                                @if ($activeBooking->link_meeting)
                                    <p class="mt-2 truncate text-sm text-indigo-600 dark:text-indigo-300">{{ $activeBooking->link_meeting }}</p>
                                @endif
                            </div>
                            <span class="shrink-0 rounded-md bg-indigo-100 px-2 py-1 text-xs font-medium text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">
                                {{ \App\Support\KonselingOptions::bookingStatuses()[$activeBooking->status] ?? $activeBooking->status }}
                            </span>
                        </div>
                    </a>
                @else
                    <p class="py-4 text-sm text-gray-500 dark:text-gray-400">Belum ada jadwal konseling aktif.</p>
                @endif
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
                            <x-filament::icon :icon="$shortcut['icon']" class="h-5 w-5 text-indigo-600" />
                            <span>{{ $shortcut['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </x-filament::section>
        </div>
    </div>

    <x-filament::section>
        <x-slot name="heading">Riwayat Pengajuan Terbaru</x-slot>

        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200 text-sm dark:divide-gray-800">
                <thead>
                    <tr class="text-left text-xs font-semibold uppercase tracking-normal text-gray-500 dark:text-gray-400">
                        <th class="py-3 pr-4">Kode</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Metode</th>
                        <th class="px-4 py-3">Konselor</th>
                        <th class="py-3 pl-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($this->getRecentBookings() as $booking)
                        <tr>
                            <td class="py-3 pr-4 font-medium text-gray-950 dark:text-white">
                                <a href="{{ \App\Filament\Mahasiswa\Resources\BookingKonselingResource::getUrl('view', ['record' => $booking]) }}">
                                    {{ $booking->kode_booking }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $booking->jadwalKonseling->tanggal->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ \App\Support\KonselingOptions::metodeKonseling()[$booking->metode] ?? $booking->metode }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $booking->konselor->nama }}</td>
                            <td class="py-3 pl-4 text-gray-600 dark:text-gray-300">{{ \App\Support\KonselingOptions::bookingStatuses()[$booking->status] ?? $booking->status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-gray-500 dark:text-gray-400">Belum ada pengajuan konseling.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-panels::page>
