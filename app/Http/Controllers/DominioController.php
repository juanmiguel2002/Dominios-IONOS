<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DominioController extends Controller
{
    //
    public function index(Request $request)
    {
        return view('dominio', ['id' => $request->id]);
    }
}
