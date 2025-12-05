<?php

namespace App\Livewire;

use App\Services\PleskService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;

class DominiosPlesk extends Component
{
    use WithPagination;

    public $search = '';
    public $limit = 20;
    public $sortDirection = 'desc'; // asc | desc
    public $sortField = 'created'; // name | created
    public $error = null;
    public $server = 'server1'; // Servidor seleccionado
    public $page = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'limit' => ['except' => 20],
        'sortDirection' => ['except' => 'desc'],
        'sortField' => ['except' => 'created'],
        'server' => ['except' => 'server1'],
        'page' => ['except' => 1],
    ];

    public function updating($property)
    {
        if (in_array($property, ['search', 'limit', 'sortDirection', 'sortField', 'server'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        try {
            $plesk = new PleskService($this->server);
            $hosting = $plesk->obtenerHosting();
        } catch (\Throwable $e) {
            $this->error = "No se pudo conectar al servidor seleccionado.";
            $hosting = collect([]);
        }

        // Filtro por búsqueda
        if ($this->search) {
            $hosting = $hosting->filter(
                fn($d) => str_contains(
                    strtolower($d['name'] ?? ''),
                    strtolower($this->search)
                )
            );
        }

        // Ordenamiento
        $hosting = $hosting->sortBy(
            fn($d) => $this->sortField === 'name'
                ? strtolower($d['name'] ?? '')
                : ($d['created'] ?? null),
            SORT_REGULAR,
            $this->sortDirection === 'asc'
        );

        // Paginación manual
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $items = $hosting->slice(($currentPage - 1) * $this->limit, $this->limit)->values();
        $paginator = new LengthAwarePaginator(
            $items,
            $hosting->count(),
            $this->limit,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('livewire.dominios-plesk', [
            'hosting' => $paginator,
            'plesk' => $plesk,
            'estadoServidor' => $plesk->estadoServidor(),
        ]);
    }

    public function resetFiltros()
    {
        $this->reset([
            'search',
            'limit',
            'sortField',
            'sortDirection',
            'page',
        ]);
    }
}
