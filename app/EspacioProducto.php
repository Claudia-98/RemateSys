<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EspacioProducto extends Model
{
    protected $table = 'espacioproducto';
    protected $fillable = ['idespacio','idproducto','stock','estado'];

    public function producto()
    {
        return $this->belongsTo('App\Producto', 'idproducto', 'id');
    }
    public function espacio()
    {
        return $this->belongsTo('App\Espacio', 'idespacio', 'id');
    }
}
