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
        $user = auth()->user();
        //dd($user->email);
        $envio = Mail::to($user->email, 'Admin dominio')->send(new RenovacionDominio($nombre, $this->dominio['expirationDate']));
        //dd($envio);
        // Verificar si el envío fue exitoso
        if (!$envio) {
            session()->flash('error', 'Error al enviar la notificación: ');
            return;
        }

        // Despachar evento de éxito
        session()->flash('success', 'Notificación enviada correctamente');
    }

    public function render()
    {
        return view('livewire.consultar-dominio');
    }
}
