<?php

namespace App\Http\Controllers;

use App\Mail\RenovacionDominio;
use App\Services\IonosService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class DominioController extends Controller
{
    //
    public function index(Request $request)
    {

        $ionos = new IonosService();
        $dominio = $ionos->obtenerDetallesDominio($request->id);
        $contacto = $ionos->obtenerContactoDominio($request->id);
        return view('dominio', ['id' => $request->id, 'dominio' => $dominio, 'contacto' => $contacto]);
    }

    public function enviarEmail($id)
    {
        try {
            $ionos = new IonosService();
            $dominio = $ionos->obtenerDetallesDominio($id);

            if (!$dominio || empty($dominio['name']) || empty($dominio['expirationDate'])) {
                return redirect()->back()->with('error', 'No se pudo obtener la información del dominio.');
            }

            $nombre = $dominio['name'];
            $fecha = Carbon::parse($dominio['expirationDate']);
            $diasRestantes = now()->diffInDays($fecha, false); // puede ser negativo

            Mail::to('info@ivarscom.com')->cc('web@ivarscomagenciadepublicidad.com')
                ->send(new RenovacionDominio($nombre, $fecha, $diasRestantes));

            Log::info("Correo enviado para el dominio: {$nombre}");

            return redirect()->back()->with('success', "Correo enviado para el dominio {$nombre}.");

        } catch (\Throwable $e) {
            Log::error("Error al enviar correo para dominio ID {$id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Hubo un error al enviar el correo.');
        }
    }
}
