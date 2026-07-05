<div class="grid gap-2">
    @forelse ($items as $label => $count)
        <div class="flex items-center justify-between gap-3 text-sm">
            <span class="truncate text-gray-600 dark:text-gray-300">{{ $label }}</span>
            <span class="font-semibold text-gray-950 dark:text-white">{{ $count }}</span>
        </div>
    @empty
        <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada data.</p>
    @endforelse
</div>
