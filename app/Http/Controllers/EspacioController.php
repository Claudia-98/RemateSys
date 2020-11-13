<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Espacio;
use App\EspacioProducto;

class EspacioController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $espacios = Espacio::orderBy('id', 'desc')->paginate(3);
        }
        else{
            $espacios = Espacio::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(3);
        }
        

        return [
            'pagination' => [
                'total'        => $espacios->total(),
                'current_page' => $espacios->currentPage(),
                'per_page'     => $espacios->perPage(),
                'last_page'    => $espacios->lastPage(),
                'from'         => $espacios->firstItem(),
                'to'           => $espacios->lastItem(),
            ],
            'espacios' => $espacios
        ];
    } 
    public function indexP(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $espaciop = EspacioProducto::orderBy('id', 'desc')->paginate(3);
        }
        else{
            $espaciop = EspacioProducto::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(3);
        }
        

        return [
            'pagination' => [
                'total'        => $espaciop->total(),
                'current_page' => $espaciop->currentPage(),
                'per_page'     => $espaciop->perPage(),
                'last_page'    => $espaciop->lastPage(),
                'from'         => $espaciop->firstItem(),
                'to'           => $espaciop->lastItem(),
            ],
            'espaciop' => $espaciop
        ];
    } 


    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $espacio = new Espacio();
        $espacio->nombre = $request->nombre;
        $espacio->direccion = $request->direccion;
        $espacio->telefono = $request->telefono;
        $espacio->estado = '1';
        $espacio->save();
    }
    public function storeP(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $espaciopr = new EspacioProducto();
        $espaciopr->idespacio = $request->idespacio;
        $espaciopr->idproducto = $request->idproducto;
        $espaciopr->stock = $request->stock;
        $espaciopr->estado = '1';
        $espaciopr->save();
    }


    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $espacio = Espacio::findOrFail($request->id);
        $espacio->nombre = $request->nombre;
        $espacio->direccion = $request->direccion;
        $espacio->telefono = $request->telefono;
        $espacio->estado = '1';
        $espacio->save();
    }
    public function updateP(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $espaciopr = EspacioProducto::findOrFail($request->id);
        $espaciopr->idespacio = $request->idespacio;
        $espaciopr->idproducto = $request->idproducto;
        $espaciopr->stock = $request->stock;
        $espaciopr->estado = '1';
        $espaciopr->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $espacio = Espacio::findOrFail($request->id);
        $espacio->estado = '0';
        $espacio->save();
    }
    public function desactivarP(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $espaciopr = EspacioProducto::findOrFail($request->id);
        $espaciopr->estado = '0';
        $espaciopr->save();
    }


    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $espacio = Espacio::findOrFail($request->id);
        $espacio->estado = '1';
        $espacio->save();
    }
    public function activarP(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $espaciopr = EspacioProducto::findOrFail($request->id);
        $espaciopr->estado = '1';
        $espaciopr->save();
    }
}
