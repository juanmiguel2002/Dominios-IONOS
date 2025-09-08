<?php

namespace App\Livewire;

use App\Services\PleskService;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class DominiosPlesk extends Component
{
    use WithPagination;

    public $search = '';
    public $limit = 20;
    public $sortDirection = 'desc'; // 'asc' o 'desc'
    public $sortField = 'created'; // 'name' o 'created'
    public $error = null;
    public $page = 1;

    protected $queryString = [
        'search' => ['except' => ''],
        'limit' => ['except' => 25],
        'sortDirection' => ['except' => 'desc'],
        'page' => ['except' => 1],
    ];

    public function updating($property)
    {
        if (in_array($property, ['search', 'limit', 'sortDirection', 'sortField'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $plesk = new PleskService();
        $hosting = $plesk->obtenerHosting();

        if ($this->search) {
            $hosting = $hosting->filter(fn($d) => str_contains(strtolower($d['name']), strtolower($this->search)));
        }

        // Ordenar por campo seleccionado
        $hosting = $hosting->sortBy(function ($d) {
            if ($this->sortField === 'name') {
                return strtolower($d['name']);
            }
            return $d['created'] ?? null;
        }, SORT_REGULAR, $this->sortDirection === 'asc');

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

        return view('livewire.dominios-plesk', ['hosting' => $paginator, 'plesk' => $plesk, ]);
    }

    public function resetFiltros()
    {
        $this->reset(['search', 'limit', 'sortField', 'page', 'sortDirection', 'sortField']);
    }
}
