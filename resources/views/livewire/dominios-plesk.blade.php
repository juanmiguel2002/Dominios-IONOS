<div>
    <div class="p-5 max-w-7xl mx-auto">
        <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Hosting Web</h1>

        {{-- Filtros --}}
        <div class="flex flex-wrap gap-4 mb-6 items-center">

            {{-- Búsqueda --}}
            <input wire:model.live.500ms="search" type="text" placeholder="Buscar dominio..."
                class="w-full sm:w-auto flex-1 border border-gray-300 dark:border-gray-600 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
            />

            {{-- Limit --}}
            <select wire:model.live="limit"
                    class="border border-gray-300 dark:border-gray-600 px-3 py-2 rounded-md focus:outline-none dark:bg-gray-800 dark:text-white">
                @foreach ([10, 20, 50, 100] as $size)
                    <option value="{{ $size }}">{{ $size }} resultados</option>
                @endforeach
            </select>

            {{-- Ordenar --}}
            <select wire:model.live="sortField" class="border px-3 py-2 rounded dark:bg-gray-800 dark:text-white">
                <option value="created">Ordenar por fecha de creación</option>
                <option value="name">Ordenar por nombre</option>
            </select>

            {{-- Selector de servidor --}}
            <select wire:model.live="server" class="border px-3 py-2 rounded dark:bg-gray-800 dark:text-white">
                @foreach(config('services.plesk.servers') as $key => $srv)
                    <option value="{{ $key }}">{{ $srv['name'] }}</option>
                @endforeach
            </select>

            {{-- Estado del servidor --}}
            <span class="px-3 py-1 rounded-full text-white text-sm
                {{ $estadoServidor === 'online' ? 'bg-green-600' : 'bg-red-600' }}">
                {{ ucfirst($estadoServidor) }}
            </span>

            {{-- Reset filtros --}}
            <button wire:click="resetFiltros" type="button"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600 px-4 py-2 rounded-md transition cursor-pointer">
                Resetear filtros
            </button>
        </div>

        {{-- Tabla de dominios --}}
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 dark:border-gray-700 text-sm text-left border-radius-md">
                <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="border border-gray-300 dark:border-gray-700 px-4 py-2">#</th>
                        <th class="border border-gray-300 dark:border-gray-700 px-4 py-2 cursor-pointer"
                            wire:click="$set('sortField', 'name')">
                            Nombre
                            @if($sortField === 'name')
                                @if($sortDirection === 'asc') ▲ @else ▼ @endif
                            @endif
                        </th>
                        <th class="border border-gray-300 dark:border-gray-700 px-4 py-2 cursor-pointer"
                            wire:click="$set('sortField', 'created')">
                            Fecha de creación
                            @if($sortField === 'created')
                                @if($sortDirection === 'asc') ▲ @else ▼ @endif
                            @endif
                        </th>
                        <th class="border border-gray-300 dark:border-gray-700 px-4 py-2">Estado</th>
                        <th class="border border-gray-300 dark:border-gray-700 px-4 py-2">Servidor</th>
                    </tr>
                </thead>
                <tbody class="text-gray-900 dark:text-gray-100">
                    @forelse ($hosting as $key => $host)
                        <tr class="dark:hover:bg-gray-700 hover:bg-gray-100">
                            <td class="border p-2 dark:border-gray-700">{{ ($hosting->currentPage() - 1) * $limit + $key + 1 }}</td>
                            <td class="border p-2 dark:border-gray-700">{{ $host['name'] ?? 'Sin nombre' }}</td>
                            <td class="border p-2 dark:border-gray-700">
                                {{ isset($host['created']) ? \Carbon\Carbon::parse($host['created'])->format('d/m/Y') : '-' }}
                            </td>
                            <td class="border p-2 dark:border-gray-700">
                                @php
                                    $estado = app(\App\Services\PleskService::class, ['serverKey' => $server])->estado($host['id']);
                                    $status = strtolower($estado['status'] ?? 'unknown');
                                    $color = match ($status) {
                                        'active' => 'text-green-600',
                                        'inactive', 'suspended' => 'text-red-600',
                                        default => 'text-gray-600',
                                    };
                                @endphp
                                <span class="{{ $color }}">{{ ucfirst($estado['status'] ?? 'Desconocido') }}</span>
                            </td>
                            <td class="border p-2 dark:border-gray-700">{{ config("services.plesk.servers.{$server}.name") }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="border p-2 text-center text-gray-500">No se encontraron dominios.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        <div class="mt-6">
            {{ $hosting->links() }}
        </div>
    </div>
</div>
