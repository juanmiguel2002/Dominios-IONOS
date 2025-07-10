<?php

namespace App\Http\Controllers;

use App\Mail\RenovacionDominio;
use App\Services\IonosService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
        $ionos = new IonosService();
        $dominio = $ionos->obtenerDetallesDominio($id);

        Mail::to('web@ivarscomagenciadepublicidad.com')->send(new RenovacionDominio($dominio['name'], $dominio['expirationDate']));

        return redirect()->back()->with('success', 'Correo enviado correctamente.');
    }
}
