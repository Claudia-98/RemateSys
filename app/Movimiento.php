<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    protected $table = 'movimiento';
    protected $fillable = ['no_recibo', 'idusuario', 'idespacio_1', 'idespacio_2', 'observaciones','fecha','estado'];

    public function movimientodetalle(){
        return $this->hasMany('App\DetalleMovimiento', 'idmovimiento', 'id');
    }
}
