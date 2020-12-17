<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Carbon\Carbon;
use App\Movimiento;
use App\DetalleMovimiento;
use App\Espacio;
use App\Producto;
use App\EspacioProducto;

class MovimientoController extends Controller
{
    public function index(Request $request){
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $movimientos = Movimiento::orderBy('id', 'desc')->paginate(10);
        }
        else{
            $movimientos = Movimiento::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(10);
        }
        

        return [
            'pagination' => [
                'total'        => $movimientos->total(),
                'current_page' => $movimientos->currentPage(),
                'per_page'     => $movimientos->perPage(),
                'last_page'    => $movimientos->lastPage(),
                'from'         => $movimientos->firstItem(),
                'to'           => $movimientos->lastItem(),
            ],
            'movimientos' => $movimientos
        ];
    }
}
