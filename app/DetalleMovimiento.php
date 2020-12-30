<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleMovimiento extends Model
{
    protected $table = 'detallemovimiento';
    protected $fillable = ['idproducto', 'idmovimiento','cantidad','estado'];

    public function movimiento(){
        return $this->belongsTo('App\Movimiento', 'idmovimiento', 'id');
    }
}
