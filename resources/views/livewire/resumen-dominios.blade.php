<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    {{-- Total dominios --}}
    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-5">
        <div class="flex items-center justify-between">
            <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Dominios activos</p>
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-300">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0zM3.6 9h16.8M3.6 15h16.8M12 3a15 15 0 0 1 0 18M12 3a15 15 0 0 0 0 18" />
                </svg>
            </span>
        </div>
        <p class="mt-3 text-3xl font-bold text-zinc-900 dark:text-white">{{ $total }}</p>
    </div>

    {{-- Próximos a vencer --}}
    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-5">
        <div class="flex items-center justify-between">
            <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Próximos a vencer (30 d)</p>
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-300">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M5.07 19h13.86c1.1 0 1.68-1.27 1.06-2.13L13.06 4.87a1.25 1.25 0 0 0-2.12 0L4.01 16.87c-.62.86-.04 2.13 1.06 2.13z" />
                </svg>
            </span>
        </div>
        <p class="mt-3 text-3xl font-bold {{ $porVencer > 0 ? 'text-red-600 dark:text-red-400' : 'text-zinc-900 dark:text-white' }}">{{ $porVencer }}</p>
    </div>

    {{-- En transferencia --}}
    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-5">
        <div class="flex items-center justify-between">
            <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">En transferencia</p>
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-300">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 3h5v5M21 3l-7 7M8 21H3v-5M3 21l7-7" />
                </svg>
            </span>
        </div>
        <p class="mt-3 text-3xl font-bold {{ $enTransferencia > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-zinc-900 dark:text-white' }}">{{ $enTransferencia }}</p>
    </div>
</div>
