<h1>Lista de Dominios IONOS</h1>

<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>Dominio</th>
            <th>Fecha de expiración</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($dominios as $dominio)
            <tr class="hover:bg-gray-100">
                <td class="border p-2">{{ $dominio->id }}</td>
                <td class="border p-2">
                    {{ $dominio['name'] }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="2" class="border p-2 text-center text-gray-500">No se encontraron dominios.</td>
            </tr>
        @endforelse
    </tbody>
</table>
