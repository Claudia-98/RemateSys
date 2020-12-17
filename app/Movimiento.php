<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    protected $table = 'movimiento';
    protected $fillable = ['no_recibo', 'idusuario', 'observaciones','fecha','estado'];

    // public function espacioproducto(){
    //     return $this->hasMany('App\EspacioProducto', 'idespacio', 'id');
    // }
}
