<div>
    <div class="p-5 max-w-7xl mx-auto">
        <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Hosting Web</h1>
        {{-- Tabla --}}
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 dark:border-gray-700 text-sm text-left ">
                <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="border border-gray-300 dark:border-gray-700 px-4 py-2">Nombre</th>
                        <th class="border border-gray-300 dark:border-gray-700 px-4 py-2">Fecha de creación</th>
                        <th class="border border-gray-300 dark:border-gray-700 px-4 py-2">Estado</th>
                    </tr>
                </thead>
                <tbody class="text-gray-900 dark:text-gray-100">
                    @forelse ($hosting as $host)
                        <tr class="dark:hover:bg-gray-700 hover:bg-gray-100">
                            <td class="border p-2 dark:border-gray-700">{{$host['name']}}</td>
                            <td class="border p-2 dark:border-gray-700">{{$host['created']}}</td>
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
        </div>
    </div>
</div>
