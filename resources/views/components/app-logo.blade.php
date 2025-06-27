<div class="flex aspect-square size-8 items-center justify-center rounded-md  text-accent-foreground">

    <span class="flex h-9 w-9 mb-1 items-center justify-center rounded-md">
        {{-- Logo para modo claro --}}
        <img src="{{ asset('storage/icon.png') }}" alt="logo claro" class="block dark:hidden w-full h-full object-contain" />

        {{-- Logo para modo oscuro --}}
        <img src="{{ asset('storage/icon-blanco.png') }}" alt="logo oscuro" class="hidden dark:block w-full h-full object-contain" />
    </span>

</div>
<div class="ms-1 grid flex-1 text-start text-sm">
    <span class="mb-0.5 truncate leading-tight font-semibold">Ivarscom</span>
</div>
