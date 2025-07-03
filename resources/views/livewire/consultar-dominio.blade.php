<div>
    <div class="p-6 max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">
            Detalle del Dominio: <span class=" dark:text-white-400">{{ $dominio['name'] }}</span>
        </h1>

        @if (session('error'))
            <div class="alert alert-danger bg-red-100 text-red-700 p-4 rounded-md border border-red-300 mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success bg-green-100 text-green-700 p-4 rounded-md border border-green-300 mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-6">
            <a href="{{ route('dashboard') }}" class="text-sm dark:text-blue-400">
                &larr; Volver al listado
            </a>
        </div>

        @if ($dominio)
            <div class="bg-white dark:bg-gray-900 shadow-md border border-gray-200 dark:border-gray-700 p-6 rounded-lg space-y-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Información del Dominio</h2>
                    <ul class="mt-2 space-y-1 text-gray-600 dark:text-gray-300">
                        <li><strong>Dominio:</strong> <a href="https://{{ $dominio['name'] }}" target="_blank" rel="noopener noreferrer" class="hover:text-blue-700">{{ $dominio['name'] }}</a></li>
                        <li><strong>TLD:</strong> {{ $dominio['tld'] ?? 'N/A' }}</li>
                        <li><strong>Auto Renovación:</strong>
                            <span class="{{ $dominio['autoRenew'] ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $dominio['autoRenew'] ? 'Sí' : 'No' }}
                            </span>
                        </li>
                        <li><strong>Fecha de Renovación:</strong> {{ \Carbon\Carbon::parse($dominio['expirationDate'])->format('d/m/Y') }}</li>
                    </ul>
                </div>
                <hr>
                <div>
                    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Información de contacto</h2>
                    <ul class="mt-2 space-y-2 text-sm text-gray-700 dark:text-gray-200">
                        <li><strong>Nombre:</strong> {{ $contacto['postalInfo']['name'] ?? 'N/A' }}</li>
                        <li><strong>Empresa:</strong> {{ $contacto['postalInfo']['organization'] ?? 'N/A' }}</li>
                        <li><strong>Email:</strong> {{ $contacto['email'] ?? 'N/A' }}</li>
                        <li><strong>Teléfono:</strong> {{ $contacto['voice'] ?? 'N/A' }}</li>
                        <li><strong>Dirección:</strong> {{ $contacto['postalInfo']['address']['streets'][0].", ". $contacto['postalInfo']['address']['postalCode'] . " ". $contacto['postalInfo']['address']['city'] ?? 'N/A' }}</li>
                        <li><strong>País:</strong> {{ $contacto['country'] ?? 'ES' }}</li>
                    </ul>

                    </div>
                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">

                    <!-- Botón para enviar aviso -->
                    <a wire:click="enviarNotificacion" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded transition cursor-pointer">
                        Enviar aviso de renovación
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
