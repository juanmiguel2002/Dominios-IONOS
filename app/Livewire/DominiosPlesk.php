<?php

namespace App\Livewire;

use App\Services\IonosService;
use App\Services\PleskService;
use Livewire\Component;

class DominiosPlesk extends Component
{

    public function render()
    {
        $plesk = new PleskService();
        $hosting = $plesk->obtenerHosting();
        $ionos = new IonosService();
        $dominios = $ionos->obtenerDominios(false);
        
        return view('livewire.dominios-plesk', ['hosting' => $hosting, 'plesk' => $plesk, 'dominios' => $dominios]);
    }
}
