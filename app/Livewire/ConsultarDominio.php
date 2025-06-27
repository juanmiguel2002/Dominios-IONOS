<?php

namespace App\Livewire;

use App\Services\IonosService;
use Livewire\Component;

class ConsultarDominio extends Component
{

    public $id; // el nombre del dominio
    public $dominio;
    public $error;
    public $contacto;

    public function mount($id, IonosService $ionos)
    {
        $this->id = $id;

        try {
            $this->dominio = $ionos->obtenerDetallesDominio($id);
            $this->contacto = $ionos->obtenerContactoDominio($id);
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }
    }

    public function mostrarContacto()
    {
        $this->dispatch('mostrarContacto', $this->id);
    }

    public function render()
    {
        return view('livewire.consultar-dominio');
    }
}
