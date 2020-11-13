<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Espacio extends Model
{
    protected $table = 'espacio';
    protected $fillable = ['nombre','direccion','telefono','estado'];

    public function espacioproducto(){
        return $this->hasMany('App\EspacioProducto', 'idespacio', 'id');
    }
    
}
