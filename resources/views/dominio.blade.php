<x-layouts.app :title="__('Detalle del Dominio ')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <livewire:consultar-dominio :id="$id"/>
    </div>
</x-layouts.app>
