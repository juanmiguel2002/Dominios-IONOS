<x-layouts.app :title="__('Listado de Usuarios')">
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-6">Listado de Usuarios</h1>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if ($users->isEmpty())
            <div class="text-gray-600">No hay usuarios registrados.</div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300 rounded shadow-sm dark:border-gray-700">
                    <thead class="bg-gray-100 text-left text-sm font-semibold text-gray-700 dark:bg-gray-800  dark:text-gray-200 dark:border-gray-700">
                        <tr class="dark:border-gray-700">
                            <th class="p-3 border-b dark:border-gray-700">ID</th>
                            <th class="p-3 border-b dark:border-gray-700">Nombre</th>
                            <th class="p-3 border-b dark:border-gray-700">Email</th>
                            <th class="p-3 border-b dark:border-gray-700">Fecha de registro</th>
                            <th class="p-3 border-b dark:border-gray-700 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-800 dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 dark:text-white">
                        {{-- Iterar sobre los usuarios --}}
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 dark:border-gray-700">
                                <td class="p-3 border-b dark:border-gray-700">{{ $user->id }}</td>
                                <td class="p-3 border-b dark:border-gray-700">{{ $user->name }}</td>
                                <td class="p-3 border-b dark:border-gray-700">{{ $user->email }}</td>
                                <td class="p-3 border-b dark:border-gray-700">{{ $user->created_at->format('d/m/Y') }}</td>
                                <td class="p-3 border-b text-right dark:border-gray-700">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @endif
    </div>
</x-layouts.app>
