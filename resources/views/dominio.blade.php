<x-layouts.app :title="__('Detalle del Dominio')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="p-6 max-w-4xl mx-auto w-full">

            {{-- Volver --}}
            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center gap-1.5 text-sm text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200 transition mb-4">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Volver al listado
            </a>

            {{-- Cabecera --}}
            <div class="flex flex-wrap items-start justify-between gap-3 mb-6">
                <div>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Detalle del dominio</p>
                    <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $dominio['name'] }}</h1>
                </div>
                @if ($dominio)
                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold
                        {{ ($dominio['autoRenew'] ?? false)
                            ? 'bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300'
                            : 'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300' }}">
                        <span class="h-1.5 w-1.5 rounded-full {{ ($dominio['autoRenew'] ?? false) ? 'bg-green-500' : 'bg-red-500' }}"></span>
                        Auto renovación {{ ($dominio['autoRenew'] ?? false) ? 'activada' : 'desactivada' }}
                    </span>
                @endif
            </div>

            {{-- Flash --}}
            @if (session('error'))
                <div class="flex items-start gap-3 rounded-lg border border-red-200 bg-red-50 dark:border-red-900/50 dark:bg-red-950/40 p-4 mb-6 text-red-700 dark:text-red-300">
                    <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                    </svg>
                    <span class="text-sm">{{ session('error') }}</span>
                </div>
            @endif

            @if (session('success'))
                <div class="flex items-start gap-3 rounded-lg border border-green-200 bg-green-50 dark:border-green-900/50 dark:bg-green-950/40 p-4 mb-6 text-green-700 dark:text-green-300">
                    <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                    </svg>
                    <span class="text-sm">{{ session('success') }}</span>
                </div>
            @endif

            @if ($dominio)
                @php
                    $expira = $dominio['expirationDate'] ?? null;
                    $fechaExpira = $expira ? \Carbon\Carbon::parse($expira) : null;
                    $diasRestantes = $fechaExpira ? (int) round(now()->diffInDays($fechaExpira, false)) : null;
                    $porVencer = $diasRestantes !== null && $diasRestantes <= 30;

                    // Dirección compuesta de forma segura (evita índices inexistentes).
                    $calle = data_get($contacto, 'postalInfo.address.streets.0');
                    $cp = data_get($contacto, 'postalInfo.address.postalCode');
                    $ciudad = data_get($contacto, 'postalInfo.address.city');
                    $direccion = collect([$calle, trim(($cp ?? '') . ' ' . ($ciudad ?? ''))])
                        ->filter(fn ($p) => filled(trim((string) $p)))
                        ->implode(', ');
                @endphp

                <div class="grid gap-4 md:grid-cols-2">
                    {{-- Información del dominio --}}
                    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-5">
                        <h2 class="flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-4">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0zM3.6 9h16.8M3.6 15h16.8M12 3a15 15 0 0 1 0 18M12 3a15 15 0 0 0 0 18" />
                            </svg>
                            Información del dominio
                        </h2>
                        <dl class="divide-y divide-zinc-100 dark:divide-zinc-800 text-sm">
                            <div class="flex justify-between gap-4 py-2">
                                <dt class="text-zinc-500 dark:text-zinc-400">Dominio</dt>
                                <dd>
                                    <a href="https://{{ $dominio['name'] }}" target="_blank" rel="noopener noreferrer"
                                       class="font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                                        {{ $dominio['name'] }} ↗
                                    </a>
                                </dd>
                            </div>
                            <div class="flex justify-between gap-4 py-2">
                                <dt class="text-zinc-500 dark:text-zinc-400">TLD</dt>
                                <dd>
                                    <span class="inline-flex items-center rounded-md bg-zinc-100 dark:bg-zinc-800 px-2 py-0.5 text-xs font-medium text-zinc-600 dark:text-zinc-300">
                                        {{ $dominio['tld'] ?? 'N/A' }}
                                    </span>
                                </dd>
                            </div>
                            <div class="flex justify-between gap-4 py-2">
                                <dt class="text-zinc-500 dark:text-zinc-400">Auto renovación</dt>
                                <dd class="font-medium {{ ($dominio['autoRenew'] ?? false) ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ ($dominio['autoRenew'] ?? false) ? 'Sí' : 'No' }}
                                </dd>
                            </div>
                            <div class="flex justify-between gap-4 py-2 items-center">
                                <dt class="text-zinc-500 dark:text-zinc-400">Fecha de renovación</dt>
                                <dd class="flex items-center gap-2 text-zinc-800 dark:text-zinc-100">
                                    {{ $fechaExpira ? $fechaExpira->format('d/m/Y') : 'N/A' }}
                                    @if ($porVencer)
                                        <span class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900/40 px-2 py-0.5 text-xs font-semibold text-red-700 dark:text-red-300">
                                            {{ $diasRestantes >= 0 ? "{$diasRestantes} d" : 'Vencido' }}
                                        </span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Información de contacto --}}
                    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-5">
                        <h2 class="flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-4">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 1 0-8 0 4 4 0 0 0 8 0zm0 0v1.5a2.5 2.5 0 0 0 5 0V12a9 9 0 1 0-9 9m4.5-1.2a8.9 8.9 0 0 1-4.5 1.2" />
                            </svg>
                            Información de contacto
                        </h2>
                        <dl class="divide-y divide-zinc-100 dark:divide-zinc-800 text-sm">
                            <div class="flex justify-between gap-4 py-2">
                                <dt class="text-zinc-500 dark:text-zinc-400">Nombre</dt>
                                <dd class="text-zinc-800 dark:text-zinc-100 text-right">{{ data_get($contacto, 'postalInfo.name', 'N/A') }}</dd>
                            </div>
                            <div class="flex justify-between gap-4 py-2">
                                <dt class="text-zinc-500 dark:text-zinc-400">Empresa</dt>
                                <dd class="text-zinc-800 dark:text-zinc-100 text-right">{{ data_get($contacto, 'postalInfo.organization', 'N/A') }}</dd>
                            </div>
                            <div class="flex justify-between gap-4 py-2">
                                <dt class="text-zinc-500 dark:text-zinc-400">Email</dt>
                                <dd class="text-right">
                                    @if ($email = data_get($contacto, 'email'))
                                        <a href="mailto:{{ $email }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ $email }}</a>
                                    @else
                                        <span class="text-zinc-800 dark:text-zinc-100">N/A</span>
                                    @endif
                                </dd>
                            </div>
                            <div class="flex justify-between gap-4 py-2">
                                <dt class="text-zinc-500 dark:text-zinc-400">Teléfono</dt>
                                <dd class="text-zinc-800 dark:text-zinc-100 text-right">{{ data_get($contacto, 'voice', 'N/A') }}</dd>
                            </div>
                            <div class="flex justify-between gap-4 py-2">
                                <dt class="text-zinc-500 dark:text-zinc-400">Dirección</dt>
                                <dd class="text-zinc-800 dark:text-zinc-100 text-right">{{ $direccion !== '' ? $direccion : 'N/A' }}</dd>
                            </div>
                            <div class="flex justify-between gap-4 py-2">
                                <dt class="text-zinc-500 dark:text-zinc-400">País</dt>
                                <dd class="text-zinc-800 dark:text-zinc-100 text-right">{{ data_get($contacto, 'country', 'ES') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                {{-- Acciones --}}
                <div class="flex flex-col sm:flex-row gap-3 mt-4">
                    <form action="{{ route('enviar', ['id' => $id]) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 transition cursor-pointer">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 0 0 2.22 0L21 8M5 19h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2z" />
                            </svg>
                            Enviar correo de renovación
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
