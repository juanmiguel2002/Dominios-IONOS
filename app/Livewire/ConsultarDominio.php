<?php

namespace App\Livewire;

use App\Services\IonosService;
use Livewire\Component;

class ConsultarDominio extends Component
{

    public $id; // el nombre del dominio
    public $dominio;
    public $error;

    public function mount($id, IonosService $ionos)
    {
        $this->id = $id;

        try {
            $this->dominio = $ionos->obtenerDetallesDominio($id);
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }
    }


    public function render()
    {
        return view('livewire.consultar-dominio');
    }
}
