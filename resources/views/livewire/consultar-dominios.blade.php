<div>
    <div class="p-5 max-w-7xl mx-auto">
        <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Dominios IONOS</h1>

        {{-- Error --}}
        @if ($error)
            <div class="bg-red-100 text-red-700 p-3 rounded mb-6">
                {{ $error }}
            </div>
        @endif

        {{-- Filtros --}}
        <div class="flex flex-wrap gap-4 mb-6">
            <input wire:model.live.500ms="search" type="text" placeholder="Buscar dominio..."
                class="w-full sm:w-auto flex-1 border border-gray-300 dark:border-gray-600 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
            />

            <select wire:model.live="limit"
                    class="border border-gray-300 dark:border-gray-600 px-3 py-2 rounded-md focus:outline-none dark:bg-gray-800 dark:text-white">
                @foreach ([10, 25, 50, 100] as $size)
                    <option value="{{ $size }}">{{ $size }} resultados</option>
                @endforeach
            </select>

            <select wire:model.live="sort"
                    class="border border-gray-300 dark:border-gray-600 px-3 py-2 rounded-md focus:outline-none dark:bg-gray-800 dark:text-white">
                <option value="asc">Ordenar por renovación ↑</option>
                <option value="desc">Ordenar por renovación ↓</option>
            </select>

            <select wire:model.live="sortN"
                    class="border border-gray-300 dark:border-gray-600 px-3 py-2 rounded-md focus:outline-none dark:bg-gray-800 dark:text-white">
                <option value="desc">Ordenar por nombre ↓</option>
                <option value="asc">Ordenar por nombre ↑</option>
            </select>

            <button wire:click="resetFiltros" type="button" class="bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600 px-4 py-2 rounded-md transition">
                Resetear filtros
            </button>
        </div>

        {{-- Tabla --}}
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 dark:border-gray-700 text-sm text-left ">
                <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="border border-gray-300 dark:border-gray-700 px-4 py-2 cursor-pointer" wire:model.live="sort">Dominio</th>
                        <th class="border border-gray-300 dark:border-gray-700 px-4 py-2">TLD</th>
                        <th class="border border-gray-300 dark:border-gray-700 px-4 py-2">Fecha de renovación</th>
                    </tr>
                </thead>
                <tbody class="text-gray-900 dark:text-gray-100">
                    @forelse ($dominios as $dominio)
                        @php
                        $fechaRenovacion = \Carbon\Carbon::parse($dominio['provisioningStatus']['setToRenewOn']);
                        $diasRestantes = now()->diffInDays($fechaRenovacion, false);
                        $filaAlerta = $diasRestantes <= 30 ? 'bg-red-50 dark:bg-red-900/30 hover:text-red-200!important' : '';
                    @endphp

                    <tr class=" dark:hover:bg-gray-700 transition {{ $filaAlerta }}">
                            {{-- Dominio --}}
                            <td class="border border-gray-300 dark:border-gray-700 px-4 py-2">
                                <a href="{{ route('dominios.show', ['id' => $dominio['id']]) }}"
                                class=" dark:text-white-400 hover:text-balance">
                                    {{ $dominio['name'] }}
                                </a>
                            </td>
                            <td class="border border-gray-300 dark:border-gray-700 px-4 py-2">
                                {{ $dominio['tld'] }}
                            </td>
                            <td class="border border-gray-300 dark:border-gray-700 px-4 py-2 flex items-center gap-2">
                                <span>{{ $fechaRenovacion->format('d/m/Y') }}</span>

                                @if ($diasRestantes <= 30)
                                    <span title="Este dominio vence en {{ $diasRestantes }} días"
                                        class="text-red-600 dark:text-red-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M5.07 19h13.86c1.1 0 1.68-1.27 1.06-2.13L13.06 4.87a1.25 1.25 0 00-2.12 0L4.01 16.87c-.62.86-.04 2.13 1.06 2.13z" />
                                        </svg>
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="border px-4 py-3 text-center text-gray-500 dark:text-gray-400">
                                No se encontraron dominios.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        <div class="mt-6">
            {{ $dominios->links() }}
        </div>
    </div>

</div>
