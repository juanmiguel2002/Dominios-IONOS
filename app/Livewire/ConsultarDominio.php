<?php

namespace App\Livewire;

use App\Mail\RenovacionDominio;
use App\Services\IonosService;
use Illuminate\Support\Facades\Mail;
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

    public function enviarNotificacion()
    {

        if (!$this->dominio) {
            session()->flash('error', 'Dominio no encontrado');
            return;
        }

        $nombre = $this->dominio['name'];
        $email = $this->contacto['email'] ?? null;
        if (!$email) {
            session()->flash('error', 'No se encontró un email de contacto para el dominio');
            return;
        }
        Mail::to($email)->send(new RenovacionDominio($nombre, $this->dominio['expirationDate']));

        // Despachar evento de éxito
        session()->flash('success', 'Notificación enviada correctamente');
    }
}
