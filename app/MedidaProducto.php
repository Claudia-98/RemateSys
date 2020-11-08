<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedidaProducto extends Model
{
    protected $table = 'medidaproducto';
    protected $fillable = ['medida','estado'];
}
