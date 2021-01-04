<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Movimiento;
use App\DetalleMovimiento;
use App\Espacio;
use App\Producto;
use App\EspacioProducto;

class MovimientoController extends Controller
{
    public function index(Request $request){
        // if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $movimientos = Movimiento::with('movimientodetalle')->orderBy('id', 'desc')->paginate(10);
        }
        else{
            $movimientos = Movimiento::with('movimientodetalle')->where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(10);
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

    public function obtenerEspacio(Request $request){
        // if (!$request->ajax()) return redirect('/');
        $espacio = Espacio::where('nombre','like','%'. $request->nombre . '%')->orderBy('id','desc')->get();

        return ['espacio'=>$espacio];
    }

    public function obtenerNoRecibo(Request $request){
        // if (!$request->ajax()) return redirect('/');

        $no_recibo = Movimiento::latest('no_recibo')
                            ->select('no_recibo')->first();
        
        // $id_ventas = Venta::latest('id')
        // ->select('id')->first();

        return ['noventa' => $no_recibo];
    }

    public function store(Request $request){
        // if (!$request->ajax()) return redirect('/');

        $idA=$request->idEspacioA;
        $idB=$request->idEspacioB;
        $no_recibo = $request->no_recibo;
        $arrayD = $request->data;
        $obs = $request->observaciones;
        $fecha = Carbon::now('America/Guatemala');
        // return $arrayD;
        try{

            DB::beginTransaction();

            $movimiento = new Movimiento();
            $movimiento->idespacio_1=$idA;
            $movimiento->idespacio_2=$idB;
            $movimiento->idusuario="1";
            $movimiento->no_recibo=$no_recibo;
            $movimiento->observaciones=$obs;
            $movimiento->fecha=$fecha;
            $movimiento->estado="1";
            $movimiento->save();

            foreach($arrayD as $dt){
                $detmov = new DetalleMovimiento();
                $detmov->idmovimiento = $movimiento->id;
                $detmov->idproducto = $dt['idproducto'];
                $detmov->cantidad = $dt['cantidad'];
                $detmov->save();
            }
            foreach($arrayD as $dt){
                // Resta el stock deseado del espacio 1

                $qP = EspacioProducto::findOrFail($dt['idEP']);
                $qP->stock = $qP->stock - $dt['cantidad'];
                $qP->save();
            }
            foreach($arrayD as $dt){
                // Suma o crea stock en el espacio 2
                   $movpr = EspacioProducto::where('idespacio','=',$idB)
                                            ->where('idproducto','=',$dt['idproducto'])
                                            ->first();
                    if(!$movpr){
                        $espacioproducto = new EspacioProducto();
                        $espacioproducto->idespacio = $idB;
                        $espacioproducto->idproducto = $dt['idproducto'];
                        $espacioproducto->stock = $dt['cantidad'];
                        $espacioproducto->save();
                    }
                    else{
                        $espacioproducto = EspacioProducto::findOrFail($movpr->id);
                        $espacioproducto->stock = $espacioproducto->stock + $dt['cantidad'];
                        $espacioproducto->save();
                    }
            }


        } catch(Exception $e) {
            DB::rollBack();
        }
    }
}
