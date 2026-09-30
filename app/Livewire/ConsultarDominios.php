<?php

namespace App\Livewire;

use App\Models\Domain;
use Livewire\Component;
use Livewire\WithPagination;

class ConsultarDominios extends Component
{
    use WithPagination;

    public $search = '';

    public $limit = 25;

    public $sortDirection = 'desc'; // 'asc' o 'desc'

    public $sortField = 'renovacion'; // 'name' o 'renovacion'

    public $estado = false; // true = solo dominios en transferencia

    protected $queryString = [
        'search' => ['except' => ''],
        'limit' => ['except' => 25],
        'sortDirection' => ['except' => 'desc'],
        'sortField' => ['except' => 'renovacion'],
        'page' => ['except' => 1],
        'estado' => ['except' => false],
    ];

    public function updating($property)
    {
        if (in_array($property, ['search', 'limit', 'sortDirection', 'sortField', 'estado'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $orderColumn = $this->sortField === 'name' ? 'name' : 'set_to_expire_on';

        $dominios = Domain::query()
            ->where('is_active', true)
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->estado, fn ($q) => $q->where('provisioning_type', 'REGISTRATION_IN_PROGRESS'))
            ->orderBy($orderColumn, $this->sortDirection === 'asc' ? 'asc' : 'desc')
            ->paginate($this->limit);

        return view('livewire.consultar-dominios', ['dominios' => $dominios]);
    }

    public function resetFiltros()
    {
        $this->reset(['search', 'limit', 'sortField', 'sortDirection', 'estado']);
        $this->resetPage();
    }

    public function estadoDominio()
    {
        $this->estado = ! $this->estado;
    }
}
