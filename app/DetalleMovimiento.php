<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleMovimiento extends Model
{
    protected $table = 'detallemovimiento';
    protected $fillable = ['idproducto', 'idespacio', 'idmovimiento','cantidad','estado'];

    // public function espacioproducto(){
    //     return $this->hasMany('App\EspacioProducto', 'idespacio', 'id');
    // }
}
