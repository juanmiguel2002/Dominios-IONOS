<?php

namespace App\Livewire;

use App\Services\IonosService;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;

class ConsultarDominios extends Component
{

    use \Livewire\WithPagination;

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
        $paginated = $this->paginateCollection($domains, $this->limit);

        return view('livewire.consultar-dominios', ['dominios' => $paginated]);
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
