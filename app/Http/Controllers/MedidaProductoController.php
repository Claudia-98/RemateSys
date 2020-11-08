<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\MedidaProducto;

class MedidaProductoController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $medidas = MedidaProducto::orderBy('id', 'desc')->paginate(3);
        }
        else{
            $medidas = MedidaProducto::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(3);
        }
        

        return [
            'pagination' => [
                'total'        => $medidas->total(),
                'current_page' => $medidas->currentPage(),
                'per_page'     => $medidas->perPage(),
                'last_page'    => $medidas->lastPage(),
                'from'         => $medidas->firstItem(),
                'to'           => $medidas->lastItem(),
            ],
            'medidas' => $medidas
        ];
    } 

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $medida = new MedidaProducto();
        $medida->medida = $request->medida;
        $medida->estado = '1';
        $medida->save();
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $medida = MedidaProducto::findOrFail($request->id);
        $medida->medida = $request->medida;
        $medida->estado = '1';
        $medida->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $medida = MedidaProducto::findOrFail($request->id);
        $medida->estado = '0';
        $medida->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $medida = MedidaProducto::findOrFail($request->id);
        $medida->estado = '1';
        $medida->save();
    }
}
