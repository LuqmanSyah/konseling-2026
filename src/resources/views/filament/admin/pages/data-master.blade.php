<x-filament-panels::page>
    <div class="grid gap-6 md:grid-cols-2">
        @foreach ($this->getMasterData() as $group => $items)
            <x-filament::section>
                <x-slot name="heading">
                    {{ $group }}
                </x-slot>

                <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">
                                    Kode
                                </th>
                                <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">
                                    Label
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900">
                            @foreach ($items as $value => $label)
                                <tr>
                                    <td class="px-4 py-3 font-mono text-xs text-gray-600 dark:text-gray-300">
                                        {{ $value }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">
                                        {{ $label }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-filament::section>
        @endforeach
    </div>
</x-filament-panels::page>
