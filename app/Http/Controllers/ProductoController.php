<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\MedidaProducto;
use App\Categoria;
use App\Persona;
use App\Producto;
use Image;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar=='' && $criterio==''){
            $productos = Producto::with('tipo','medida','proveedor')
                                ->orderBy('id', 'desc')
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

        $producto->precio_venta=$request->precio_venta;
        $producto->precio_mayorista=$request->precio_mayorista;
        $producto->precio_compra=$request->precio_compra;
        $producto->idpersonas=$request->idproveedor;
        $producto->idtipo=$request->idtipo;
        $producto->idmedida=$request->medida;
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
        $producto->idpersonas=$request->idproveedor;
        $producto->idtipo=$request->idtipo;
        $producto->idmedida=$request->medida;
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
