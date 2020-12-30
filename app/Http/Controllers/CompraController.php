<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Compra;
use App\Persona;
class CompraController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $compra = Compra::orderBy('id', 'desc')->paginate(3);
        }
        else{
            $compra = Compra::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(3);
        }
        

        return [
            'pagination' => [
                'total'        => $compra->total(),
                'current_page' => $compra->currentPage(),
                'per_page'     => $compra->perPage(),
                'last_page'    => $compra->lastPage(),
                'from'         => $compra->firstItem(),
                'to'           => $compra->lastItem(),
            ],
            'compra' => $compra
        ];
    } 
    public function obtenerPersona(Request $request){
        if (!$request->ajax()) return redirect('/');

        $persona = Persona::where('nombre','like','%'. $request->nombre . '%')
                                ->orderBy('nombre', 'desc')
                                ->get();
        return ['persona'=>$persona];
    }
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $compra = new Compra();
        $compra->fecha= $request->fecha;
        $compra->total = $request->total;
        $compra->observaciones = $request->observaciones;
        $compra->idpersona = $request->idpersona;
        $compra->estado = '1';
        $compra->idusuario = '1';
        $compra->save();
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $persona = Compra::findOrFail($request->id);
        $compra->fecha= $request->fecha;
        $compra->total = $request->total;
        $compra->observaciones = $request->observaciones;
        $compra->idpersona = $request->idpersona;
        $compra->estado = '1';
        $compra->idusuario = '1';
        $compra->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $compra = Compra::findOrFail($request->id);
        $compra->estado = '0';
        $compra->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $compra = Compra::findOrFail($request->id);
        $compra->estado = '1';
        $compra->save();
    }

    public function ultimaPersona(Request $request){

        if (!$request->ajax()) return redirect('/');
        $persona = Persona:: where('estado',1)
                    ->orderBy('id','desc')->first();
        return ['persona'=>$persona];
    }
}           
