<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">Filter Laporan</x-slot>

        <div class="grid gap-4 md:grid-cols-3">
            <label class="grid gap-2">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Tanggal Mulai</span>
                <x-filament::input.wrapper>
                    <x-filament::input type="date" wire:model.live="startDate" />
                </x-filament::input.wrapper>
            </label>

            <label class="grid gap-2">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Tanggal Selesai</span>
                <x-filament::input.wrapper>
                    <x-filament::input type="date" wire:model.live="endDate" />
                </x-filament::input.wrapper>
            </label>

            <label class="grid gap-2">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Status</span>
                <x-filament::input.wrapper>
                    <x-filament::input.select wire:model.live="status">
                        <option value="">Semua Status</option>
                        @foreach ($this->getStatusOptions() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </label>
        </div>
    </x-filament::section>

    <div class="grid gap-4 lg:grid-cols-4">
        <x-filament::section>
            <x-slot name="heading">Per Status</x-slot>
            @include('filament.bkts.pages.partials.summary-list', ['items' => $this->getStatusSummary()])
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Per Kategori</x-slot>
            @include('filament.bkts.pages.partials.summary-list', ['items' => $this->getCategorySummary()])
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Per Metode</x-slot>
            @include('filament.bkts.pages.partials.summary-list', ['items' => $this->getMethodSummary()])
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Per Konselor</x-slot>
            @include('filament.bkts.pages.partials.summary-list', ['items' => $this->getCounselorSummary()])
        </x-filament::section>
    </div>

    <x-filament::section>
        <x-slot name="heading">Data Booking Konseling</x-slot>

        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200 text-sm dark:divide-gray-800">
                <thead>
                    <tr class="text-left text-xs font-semibold uppercase tracking-normal text-gray-500 dark:text-gray-400">
                        <th class="py-3 pr-4">Kode</th>
                        <th class="px-4 py-3">Mahasiswa</th>
                        <th class="px-4 py-3">Konselor</th>
                        <th class="px-4 py-3">Jadwal</th>
                        <th class="px-4 py-3">Kategori</th>
                        <th class="px-4 py-3">Metode</th>
                        <th class="py-3 pl-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($this->getRows() as $booking)
                        <tr>
                            <td class="py-3 pr-4 font-medium text-gray-950 dark:text-white">{{ $booking->kode_booking }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $booking->mahasiswa->nama }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $booking->konselor->nama }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ \App\Support\KonselingOptions::hariDalamMinggu()[$booking->jadwalKonseling->hari] ?? $booking->jadwalKonseling->hari }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ \App\Support\KonselingOptions::kategoriKonseling()[$booking->kategori] ?? $booking->kategori }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ \App\Support\KonselingOptions::metodeKonseling()[$booking->metode] ?? $booking->metode }}</td>
                            <td class="py-3 pl-4 text-gray-600 dark:text-gray-300">{{ \App\Support\KonselingOptions::bookingStatuses()[$booking->status] ?? $booking->status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-4 text-gray-500 dark:text-gray-400">Tidak ada data booking pada filter ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-panels::page>
