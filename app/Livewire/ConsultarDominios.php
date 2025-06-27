<?php

namespace App\Livewire;

use App\Services\IonosService;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class ConsultarDominios extends Component
{

    use WithPagination;

    public $search = '';
    public $limit = 25;
    public $sort = 'asc';
    public $error = null;
    public $page = 1;

    protected $queryString = [
        'search' => ['except' => ''],
        'limit' => ['except' => 25],
        'sort' => ['except' => 'asc'],
        'page' => ['except' => 1],
    ];

    public function updating($property)
    {
        if (in_array($property, ['search', 'limit', 'sort'])) {
            $this->resetPage();
        }
    }

    public function resetFiltros()
    {
        $this->reset(['search', 'limit', 'sort', 'page']);
    }

    public function render(IonosService $ionos)
    {
        try {
            $domains = $ionos->obtenerDominios();
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return view('livewire.consultar-dominios', ['dominios' => collect()]);
        }

        // Buscar por nombre
        if ($this->search) {
            $domains = $domains->filter(fn($d) => str_contains(strtolower($d['name']), strtolower($this->search)));
        }

        // Ordenar por fecha de renovación
        $domains = $domains->sortBy(fn($d) =>
            $d['status']['provisioningStatus']['setToRenewOn'] ?? null,
            SORT_REGULAR,
            $this->sort === 'desc'
        );

        // Paginar manualmente
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $items = $domains->slice(($currentPage - 1) * $this->limit, $this->limit)->values();
        $paginator = new LengthAwarePaginator(
            $items,
            $domains->count(),
            $this->limit,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('livewire.consultar-dominios', ['dominios' => $paginator]);
    }

    private function paginateCollection($items, $perPage)
    {
        $page = $this->page ?? 1;
        $items = $items->values(); // reset keys

        return new LengthAwarePaginator(
            $items->forPage($page, $perPage),
            $items->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }
}
