<div>
    <div class="p-5 max-w-7xl mx-auto">
        <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Hosting Web</h1>

        <div class="flex flex-wrap gap-4 mb-6">
            <input wire:model.live.500ms="search" type="text" placeholder="Buscar dominio..."
                class="w-full sm:w-auto flex-1 border border-gray-300 dark:border-gray-600 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
            />

            <select wire:model.live="limit"
                    class="border border-gray-300 dark:border-gray-600 px-3 py-2 rounded-md focus:outline-none dark:bg-gray-800 dark:text-white">
                @foreach ([10, 20, 50, 100] as $size)
                    <option value="{{ $size }}">{{ $size }} resultados</option>
                @endforeach
            </select>

            <select wire:model.live="sortField" class="border px-3 py-2 rounded">
                <option value="created">Ordenar por fecha de creación</option>
                <option value="name">Ordenar por nombre</option>
            </select>

            <button wire:click="resetFiltros" type="button" class="bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600 px-4 py-2 rounded-md transition cursor-pointer">
                Resetear filtros
            </button>
        </div>

        {{-- Tabla --}}
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 dark:border-gray-700 text-sm text-left border-radius-md">
                <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="border border-gray-300 dark:border-gray-700 px-4 py-2">ID</th>
                        <th class="border border-gray-300 dark:border-gray-700 px-4 py-2">Nombre</th>
                        <th class="border border-gray-300 dark:border-gray-700 px-4 py-2">Fecha de creación</th>
                        <th class="border border-gray-300 dark:border-gray-700 px-4 py-2">Estado</th>
                    </tr>
                </thead>
                <tbody class="text-gray-900 dark:text-gray-100">
                    @forelse ($hosting as $key => $host)
                        <tr class="dark:hover:bg-gray-700 hover:bg-gray-100">
                            <td class="border p-2 dark:border-gray-700"> {{$key+1}}</td>
                            <td class="border p-2 dark:border-gray-700">{{$host['name']}}</td>
                            <td class="border p-2 dark:border-gray-700">{{\Carbon\Carbon::parse($host['created'])->format('d/m/Y')}}</td>
                            <td class="border p-2 dark:border-gray-700">
                                @php
                                    $estado = $plesk->estado($host['id']);
                                    $status = strtolower($estado['status']);
                                    $color = match ($status) {
                                        'active' => 'text-green-600',
                                        'suspended', 'inactive' => 'text-red-600',
                                        default => 'text-gray-600',
                                    };
                                @endphp

                                <span class="{{ $color }}">
                                    {{ ucfirst($estado['status']) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="border p-2 text-center text-gray-500">No se encontraron dominios.</td>
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
