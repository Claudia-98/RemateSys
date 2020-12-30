<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pruebas;

class PruebasController extends Controller
{
    public function store(Request $request){

        $idEspacio = $request->idE;
        $idProducto = $request->idP;
        $newStock = $request->stock;

         return $EProducto = Pruebas::where('idespacio','=',$idEspacio)->where('idproducto','=',$idProducto)->first();
    }
}
