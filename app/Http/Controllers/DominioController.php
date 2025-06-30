<?php

namespace App\Http\Controllers;

use App\Services\IonosService;
use Illuminate\Http\Request;

class DominioController extends Controller
{
    //

    public function index(Request $request, IonosService $ionosService)
    {
        return view('dominio', ['id' => $request->id]);
    }
}
