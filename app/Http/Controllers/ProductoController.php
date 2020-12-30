<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\MedidaProducto;
use App\Categoria;
use App\Persona;
use App\EspacioProducto;
use App\Producto;
use Image;

class ProductoController extends Controller
{
    public function listarProducto(Request $request){
        // if (!$request->ajax()) return redirect('/');
        $busc = $request->buscar;
        $cri = $request->criterio;
        // return $cri;
        if($busc==null){
            // $producto = Producto::with('tipo','medida','proveedor')->paginate(4);
            $producto = EspacioProducto::with('producto.medida','espacio')
                                    ->where('idespacio','like',$request->id)
                                    ->paginate(4);
        }
        else {
            // $producto = Producto::with('tipo','medida','proveedor')->where($cri,'like',"%{$busc}%")->paginate(4);
            $producto = EspacioProducto::with('producto.medida','espacio')
                                    ->where('idespacio','like',$request->id)
                                    ->where('producto.'+$cri,'like',"%{$busc}%")
                                    ->paginate(4);
        }
        return [
            'pagination' => [
                'total'        => $producto->total(),
                'current_page' => $producto->currentPage(),
                'per_page'     => $producto->perPage(),
                'last_page'    => $producto->lastPage(),
                'from'         => $producto->firstItem(),
                'to'           => $producto->lastItem(),
            ],
            'producto' => $producto
        ];
    }

    public function obtenerProducto(Request $request){
        // if (!$request->ajax()) return redirect('/');
        // $producto = Producto::where('nombre','=',$request->nombre)->with('tipo','medida','proveedor')
        //                     ->oRwhere('alias','=',$request->nombre)->get();
        $buscar=$request->nombre;
        $producto = EspacioProducto::with(['producto.medida','producto.tipo','producto.proveedor','producto'=>function($acade) use($buscar){
            $acade->where('nombre', 'like', $buscar);
        },])->whereHas('producto',function($acade) use($buscar){
            $acade->where('nombre', 'like', $buscar);
        })->where('estado','=','1')
        ->get();

        return ['producto'=>$producto];
    }
    public function index(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar=='' && $criterio==''){
            $productos = Producto::with('tipo','medida','proveedor')
                                ->orderBy('id', 'asc')
                                ->paginate(10);
        }
        else{
            if($criterio=='nombre'){
                $productos = Producto::with('tipo','medida','proveedor')
                                ->where('nombre','like',"%{$buscar}%")
                                ->orderBy('id', 'desc')
                                ->paginate(10);
            }
            else if($criterio=='alias'){
                $productos = Producto::with('tipo','medida','proveedor')
                                ->where('alias','like',"%{$buscar}%")
                                ->orderBy('id', 'desc')
                                ->paginate(10);
            }
            else if($criterio=='tipo'){
                $productos = Producto::with(['medida','proveedor', 'tipo'=>function($acade) use($buscar){
                    $acade->where('nombre', 'like', "%{$buscar}%");
                },])->whereHas('tipo',function($acade) use($buscar){
                    $acade->where('nombre', 'like', "%{$buscar}%");
                })->paginate(10);
            }
            else if($criterio=='medida'){
                $productos = Producto::with(['tipo','proveedor', 'medida'=>function($acade) use($buscar){
                    $acade->where('medida', 'like', "%{$buscar}%");
                },])->whereHas('medida',function($acade) use($buscar){
                    $acade->where('medida', 'like', "%{$buscar}%");
                })->paginate(10);
            }
            else if($criterio=='proveedor'){
                $productos = Producto::with(['tipo','medida', 'proveedor'=>function($acade) use($buscar){
                    $acade->where('nombre', 'like', "%{$buscar}%");
                },])->whereHas('proveedor',function($acade) use($buscar){
                    $acade->where('nombre', 'like', "%{$buscar}%");
                })->paginate(10);
            }
            
        }
        

        return [
            'pagination' => [
                'total'        => $productos->total(),
                'current_page' => $productos->currentPage(),
                'per_page'     => $productos->perPage(),
                'last_page'    => $productos->lastPage(),
                'from'         => $productos->firstItem(),
                'to'           => $productos->lastItem(),
            ],
            'productos' => $productos
        ];
    } 

    public function obtenerMedida(Request $request){
        if (!$request->ajax()) return redirect('/');
        
        $medida = MedidaProducto::where('estado','=','1')
                                ->orderBy('id', 'desc')
                                ->get();
        return ['medida' => $medida];
    }
    public function obtenerPersona(Request $request){
        if (!$request->ajax()) return redirect('/');

        $persona = Persona::where('estado','=','1')
                                ->orderBy('id', 'desc')
                                ->get();
        return ['persona'=>$persona];
    }
    public function obtenerTipo(Request $request){
        if (!$request->ajax()) return redirect('/');

        $tipo = Categoria::where('estado','=','1')
                                ->orderBy('id', 'desc')
                                ->get();
        return ['tipo'=>$tipo];
        
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $producto = new Producto();
        $producto->nombre=$request->nombre;
        $producto->alias=$request->alias;
        $producto->codigo=$request->codigo;
        $producto->descripcion=$request->descripcion;
        
        $ruta = public_path().'/uploads/';
        $imagenOriginal = $request->file('foto');

        

        if($request->hasFile('foto')){
            $imagen=Image::make($imagenOriginal);
            $temp_name = $this->random_string() . '.' . $imagenOriginal->getClientOriginalExtension();
            $imagen->save($ruta . $temp_name, 100);
            $producto->foto = $temp_name;    
        }
        else{
            $producto->foto = 'producto.jpg';
        }

        $producto->precio_venta=$request->precio_venta;
        $producto->precio_mayorista=$request->precio_mayorista;
        $producto->precio_compra=$request->precio_compra;
        $producto->idpersonas=$request->idpersona;
        $producto->idtipo=$request->idtipo;
        $producto->idmedida=$request->idmedida;
        $producto->estado='1';
        $producto->save();
        
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $producto = Producto::findOrFail($request->id);
        $producto->nombre = $request->nombre;
        $producto->alias=$request->alias;
        $producto->codigo=$request->codigo;
        $producto->descripcion=$request->descripcion;

        $ruta = public_path().'/uploads/';

        if($request->hasFile('foto')){
            $imagenOriginal = $request->file('foto');
            $imagen=Image::make($imagenOriginal);
            $temp_name = $this->random_string() . '.' . $imagenOriginal->getClientOriginalExtension();
            $imagen->save($ruta . $temp_name, 100);
            $producto->foto = $temp_name;   
        }

        $producto->precio_venta=$request->precio_venta;
        $producto->precio_mayorista=$request->precio_mayorista;
        $producto->precio_compra=$request->precio_compra;
        $producto->idpersonas=$request->idpersona;
        $producto->idtipo=$request->idtipo;
        $producto->idmedida=$request->idmedida;
        $producto->estado = '1';
        $producto->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $producto = Producto::findOrFail($request->id);
        $producto->estado = '0';
        $producto->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $producto = Producto::findOrFail($request->id);
        $producto->estado = '1';
        $producto->save();
    }
}
