@php
    $id = $getId();
    $isDisabled = $isDisabled();
    $statePath = $getStatePath();
    $schedules = $evaluate($getSchedules);
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div class="overflow-hidden rounded-md border border-gray-200 dark:border-gray-800">
        @if (count($schedules) > 0)
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 text-sm dark:divide-gray-800">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr class="text-left text-xs font-semibold uppercase tracking-normal text-gray-500 dark:text-gray-400">
                            <th class="w-12 px-4 py-3">Pilih</th>
                            <th class="px-4 py-3">Hari</th>
                            <th class="px-4 py-3">Jam</th>
                            <th class="px-4 py-3">Konselor</th>
                            <th class="px-4 py-3">Metode</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white dark:divide-gray-800 dark:bg-gray-950">
                        @foreach ($schedules as $schedule)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                                <td class="px-4 py-3">
                                    <x-filament::input.radio
                                        :valid="! $errors->has($statePath)"
                                        :attributes="
                                            (new \Illuminate\View\ComponentAttributeBag)
                                                ->merge([
                                                    'disabled' => $isDisabled,
                                                    'id' => $id . '-' . $schedule['id'],
                                                    'name' => $id,
                                                    'value' => $schedule['id'],
                                                    'wire:loading.attr' => 'disabled',
                                                    $applyStateBindingModifiers('wire:model') => $statePath,
                                                ], escape: false)
                                        "
                                    />
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-950 dark:text-white">
                                    <label for="{{ $id }}-{{ $schedule['id'] }}" class="cursor-pointer">
                                        {{ $schedule['hari'] }}
                                    </label>
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $schedule['jam'] }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $schedule['konselor'] }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $schedule['metode'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-white px-4 py-6 text-sm text-gray-500 dark:bg-gray-950 dark:text-gray-400">
                Belum ada jadwal tersedia untuk metode yang dipilih.
            </div>
        @endif
    </div>
</x-dynamic-component>
