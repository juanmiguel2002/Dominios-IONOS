<div>
    <div class="p-5 max-w-7xl mx-auto">

        {{-- Cabecera --}}
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Dominios IONOS</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Gestiona y revisa el estado de tus dominios.</p>
            </div>
            <span class="inline-flex items-center gap-1.5 rounded-full bg-zinc-100 dark:bg-zinc-800 px-3 py-1 text-sm font-medium text-zinc-600 dark:text-zinc-300">
                <span class="h-2 w-2 rounded-full bg-indigo-500"></span>
                {{ $dominios->total() }} {{ \Illuminate\Support\Str::plural('dominio', $dominios->total()) }}
            </span>
        </div>

        {{-- Error --}}
        @if ($error)
            <div class="flex items-start gap-3 rounded-lg border border-red-200 bg-red-50 dark:border-red-900/50 dark:bg-red-950/40 p-4 mb-6 text-red-700 dark:text-red-300">
                <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                </svg>
                <span class="text-sm">{{ $error }}</span>
            </div>
        @endif

        {{-- Filtros --}}
        <div class="flex flex-wrap items-center gap-3 mb-6 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4">
            <div class="relative flex-1 min-w-[200px]">
                <svg class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3M11 19a8 8 0 1 1 0-16 8 8 0 0 1 0 16z" />
                </svg>
                <input wire:model.live.500ms="search" type="text" placeholder="Buscar dominio..."
                    class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 pl-9 pr-3 py-2 text-sm text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
            </div>

            <select wire:model.live="limit"
                class="rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-2 text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @foreach ([10, 25, 50, 100] as $size)
                    <option value="{{ $size }}">{{ $size }} resultados</option>
                @endforeach
            </select>

            <select wire:model.live="sortField"
                class="rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-2 text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="renovacion">Ordenar por renovación</option>
                <option value="name">Ordenar por nombre</option>
            </select>

            <select wire:model.live="sortDirection"
                class="rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-2 text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="asc">Ascendente ↑</option>
                <option value="desc">Descendente ↓</option>
            </select>

            <button wire:click="estadoDominio" type="button"
                class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold border transition cursor-pointer
                    {{ $estado
                        ? 'bg-amber-500 border-amber-500 text-white hover:bg-amber-600'
                        : 'bg-white dark:bg-zinc-800 border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-zinc-200 hover:bg-zinc-50 dark:hover:bg-zinc-700' }}">
                {{ $estado ? 'Ver todos' : 'Ver solo transfiriendo' }}
            </button>

            <button wire:click="resetFiltros" type="button"
                class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 0 0 4.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 0 1-15.357-2m15.357 2H15" />
                </svg>
                Resetear
            </button>
        </div>

        {{-- Tabla --}}
        <div class="relative overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900">
            {{-- Overlay de carga --}}
            <div wire:loading.flex wire:target="search,limit,sortField,sortDirection,estado,previousPage,nextPage,gotoPage"
                class="absolute inset-0 z-10 items-center justify-center bg-white/60 dark:bg-zinc-900/60 backdrop-blur-sm">
                <svg class="w-6 h-6 animate-spin text-indigo-500" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.4 0 0 5.4 0 12h4z"></path>
                </svg>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/80 text-xs uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Dominio</th>
                            <th class="px-4 py-3 font-semibold">TLD</th>
                            <th class="px-4 py-3 font-semibold">Fecha de renovación</th>
                            <th class="px-4 py-3 font-semibold">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800 text-zinc-800 dark:text-zinc-100">
                        @forelse ($dominios as $dominio)
                            @php
                                $provisioning = $dominio['provisioningStatus'] ?? [];
                                $estadoTransferencia = $provisioning['type'] ?? null;
                                $expira = $provisioning['setToExpireOn'] ?? null;
                                $renueva = $provisioning['setToRenewOn'] ?? null;
                                $creado = $provisioning['createdDate'] ?? null;
                                $enTransferencia = $estadoTransferencia === 'REGISTRATION_IN_PROGRESS';

                                if ($enTransferencia && $creado) {
                                    $fechaMostrar = \Carbon\Carbon::parse($creado);
                                    $textoFecha = 'Transferencia iniciada';
                                } elseif ($expira) {
                                    $fechaMostrar = \Carbon\Carbon::parse($expira);
                                    $textoFecha = 'Expira';
                                } elseif ($renueva) {
                                    $fechaMostrar = \Carbon\Carbon::parse($renueva);
                                    $textoFecha = 'Renueva';
                                } else {
                                    $fechaMostrar = null;
                                    $textoFecha = null;
                                }

                                $diasRestantes = ($fechaMostrar && !$enTransferencia)
                                    ? (int) round(now()->diffInDays($fechaMostrar, false))
                                    : null;
                                $porVencer = !$estado && $diasRestantes !== null && $diasRestantes <= 30;
                            @endphp

                            <tr class="transition hover:bg-zinc-50 dark:hover:bg-zinc-800/60 {{ $porVencer ? 'bg-red-50/70 dark:bg-red-950/20' : '' }}">
                                <td class="px-4 py-3 {{ $porVencer ? 'border-l-2 border-red-500' : 'border-l-2 border-transparent' }}">
                                    @if (!$estado)
                                        <a href="{{ route('dominios.show', ['id' => $dominio['id']]) }}"
                                           class="font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                                            {{ $dominio['name'] }}
                                        </a>
                                    @else
                                        <span class="font-medium">{{ $dominio['name'] }}</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center rounded-md bg-zinc-100 dark:bg-zinc-800 px-2 py-0.5 text-xs font-medium text-zinc-600 dark:text-zinc-300">
                                        {{ $dominio['tld'] }}
                                    </span>
                                </td>

                                <td class="px-4 py-3">
                                    @if ($fechaMostrar)
                                        <div class="flex items-center gap-2">
                                            <span class="text-zinc-700 dark:text-zinc-200">
                                                {{ $textoFecha }} el {{ $fechaMostrar->format('d/m/Y') }}
                                            </span>
                                            @if ($porVencer)
                                                <span title="Vence en {{ $diasRestantes }} días"
                                                    class="inline-flex items-center gap-1 rounded-full bg-red-100 dark:bg-red-900/40 px-2 py-0.5 text-xs font-semibold text-red-700 dark:text-red-300">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M5.07 19h13.86c1.1 0 1.68-1.27 1.06-2.13L13.06 4.87a1.25 1.25 0 0 0-2.12 0L4.01 16.87c-.62.86-.04 2.13 1.06 2.13z" />
                                                    </svg>
                                                    {{ $diasRestantes >= 0 ? "{$diasRestantes} d" : 'Vencido' }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-zinc-400 dark:text-zinc-600">Sin fecha de renovación</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3">
                                    @if ($enTransferencia)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 dark:bg-amber-900/40 px-2.5 py-1 text-xs font-semibold text-amber-700 dark:text-amber-300">
                                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                            Transfiriendo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-green-100 dark:bg-green-900/40 px-2.5 py-1 text-xs font-semibold text-green-700 dark:text-green-300">
                                            <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                            Activo
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2 text-zinc-400 dark:text-zinc-500">
                                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0zM3.6 9h16.8M3.6 15h16.8M12 3a15 15 0 0 1 0 18M12 3a15 15 0 0 0 0 18" />
                                        </svg>
                                        <span class="text-sm font-medium">No se encontraron dominios.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Paginación --}}
        <div class="mt-6">
            {{ $dominios->links() }}
        </div>
    </div>
</div>
