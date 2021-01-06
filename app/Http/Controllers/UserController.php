<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Persona;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request){
    
        // if(!$request -> ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar=='')
        {
            $usuarios = User::with('persona', 'rol')
            ->orderBy('users.id', 'desc')
            ->paginate(10);
        }   
        else{
            if($criterio == 'nombre')
            {
                $usuarios = User::with(['rol','persona'=>function($acade) use($buscar){
                    $acade->where('nombre', 'like', "%{$buscar}%");
                },])->whereHas('persona',function($acade) use($buscar){
                    $acade->where('nombre', 'like', "%{$buscar}%");
                })->paginate(10);
            }
            else if ($criterio == 'usuario'){
                    $usuarios= User::with('persona','rol')->where('usuario', 'like', '%'.$buscar.'%')->paginate(10);  
            }
        }

        return [
            'pagination' => [
                'total' => $usuarios->total(),
                'current_page' => $usuarios->currentPage(),
                'per_page' => $usuarios->perPage(),
                'last_page' => $usuarios->lastPage(),
                'from' => $usuarios->firstItem(),
                'to' => $usuarios->lastItem(),
            ],
            'usuarios' => $usuarios
        ];
    }

    
    public function store(Request $request)
    {
        if(!$request -> ajax()) return redirect('/');
        try {
            DB::beginTransaction();
            $persona = new Persona();
            $persona->nombre = $request->nombre;
            $persona->direccion = $request->direccion;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;
            $persona->estado = '1';
            $persona->save();


            // $ruta = public_path().'/uploads/';
            // $imagenOriginal = $request->file('foto');

            $usuario = new User();
            $usuario->usuario = $request->usuario;
            $usuario->password = bcrypt($request->password);
            $usuario->foto = 'avatar.png';
            $usuario->estado = '1';
            $usuario->id = $persona->id;
            $usuario->idrol = $request->idrol;

            // return $usuario;
            $usuario->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
    public function update(Request $request)
    {
        if(!$request -> ajax()) return redirect('/');
        try {
            DB::beginTransaction();
            
            $personas = Persona::findOrFail($request->id);
            $usuario = User::findOrFail($personas->id);

            $personas->nombre = $request->nombre;
            $personas->direccion = $request->direccion;
            $personas->telefono = $request->telefono;
            $personas->email = $request->email;
            $personas->estado = '1';
            $personas->save();


            $usuario->usuario = $request->usuario;
            $usuario->estado = '1';
            $usuario->idrol = $request->idrol;
            if($request->password != ''){
                $usuario->password = bcrypt($request->password);
            }
            $usuario->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
    public function desactivar(Request $request)
    {
        if(!$request -> ajax()) return redirect('/');
        $personas = Persona::findOrfail($request->id);
        $usuario = User::findOrfail($personas->id);

        $personas->estado = '0';
        $usuario->estado = '0';
        
        $personas->save();
        $usuario->save();
    }

    public function activar(Request $request)
    {
        if(!$request -> ajax()) return redirect('/');
        $personas = Persona::findOrfail($request->id);
        $usuario = User::findOrfail($personas->id);

        $personas->estado = '1';
        $usuario->estado = '1';

        $personas->save();
        $usuario->save();    
    }
    protected function random_string(){
        $key = '';
        $keys = array_merge( range('a','z'), range(0,9) );

        for($i=0; $i<10; $i++)
        {
            $key .= $keys[array_rand($keys)];
        }

        return $key;
    }
}
