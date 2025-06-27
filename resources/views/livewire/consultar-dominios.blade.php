<div>
    <div class="p-5">
        <h1 class="text-xxl font-bold mb-4">Dominios IONOS</h1>

        @if ($error)
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">{{ $error }}</div>
        @endif

        <div class="flex flex-wrap gap-4 mb-4">
            <input wire:model.live.500ms="search"
                type="text"
                placeholder="Buscar dominio..."
                class="border px-3 py-2 rounded w-full sm:w-auto"
            />

            <select wire:model.live="limit" class="border px-3 py-2 rounded">
                @foreach ([10, 25, 50, 100] as $size)
                    <option value="{{ $size }}">{{ $size }} resultados</option>
                @endforeach
            </select>

            <select wire:model.live="sort" class="border px-3 py-2 rounded">
                <option value="asc">Ordenar por renovación ↑</option>
                <option value="desc">Ordenar por renovación ↓</option>
            </select>

            <button wire:click="resetFiltros" type="button"
                class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300" >
                Resetear filtros
            </button>
        </div>

        <table class="table-auto w-full border-collapse border border-gray-300">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border p-2 text-left">Dominio</th>
                    <th class="border p-2 text-left">TlD</th>
                    <th class="border p-2 text-left">Fecha de expiración</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dominios as $dominio)
                    <tr class="hover:bg-gray-100">
                        <td class="border p-2" ><a href="{{ route('dominios.show', ['id' => $dominio['id']]) }}" >
                            {{ $dominio['name'] }}</td>
                        <td class="border p-2">
                            {{ $dominio['tld'] }}
                        <td class="border p-2">
                            {{ \Carbon\Carbon::parse($dominio['status']['provisioningStatus']['setToRenewOn'])->format('d/m/Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="border p-2 text-center text-gray-500">No se encontraron dominios.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $dominios->links() }}
    </div>
</div>
