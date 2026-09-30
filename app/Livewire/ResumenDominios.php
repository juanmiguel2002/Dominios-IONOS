<?php

namespace App\Livewire;

use App\Models\Domain;
use Livewire\Component;

class ResumenDominios extends Component
{
    public function render()
    {
        $activos = Domain::where('is_active', true);

        $total = (clone $activos)->count();

        $porVencer = (clone $activos)
            ->whereNotNull('set_to_expire_on')
            ->whereBetween('set_to_expire_on', [now()->startOfDay(), now()->addDays(30)->endOfDay()])
            ->count();

        $enTransferencia = (clone $activos)
            ->where('provisioning_type', 'REGISTRATION_IN_PROGRESS')
            ->count();

        return view('livewire.resumen-dominios', [
            'total' => $total,
            'porVencer' => $porVencer,
            'enTransferencia' => $enTransferencia,
        ]);
    }
}
