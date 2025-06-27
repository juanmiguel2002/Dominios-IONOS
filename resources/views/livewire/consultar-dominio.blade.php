<div>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Detalle del Dominio {{$dominio['name']}}</h1>
        @if ($error)
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                {{ $error }}
            </div>
        @endif

        <div class="mt-6">
            <a href="{{ route('dashboard') }}" >&larr; Volver al listado</a>
        </div>
        @if ($dominio)
            <div class="bg-white shadow p-5 rounded space-y-3">
                <p><strong>Dominio:</strong> {{ $dominio['name'] }}</p>
                <p><strong>Auto Renovación:</strong> {{  $dominio['autoRenew'] ? 'Sí' : 'No'  }}</p>
                <p><strong>Renovación:</strong> {{ \Carbon\Carbon::parse($dominio['expirationDate'])->format('d/m/Y') }}</p>
            </div>
        @endif
    </div>
</div>
