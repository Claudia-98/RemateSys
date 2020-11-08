<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'producto';
    protected $fillable = ['nombre','alias','codigo','descripcion','foto','precio_venta','precio_mayorista',
    'precio_compra','idpersonas','idtipo','idmedida','estado'];
    
}
