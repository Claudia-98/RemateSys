<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pruebas extends Model
{
    protected $table = 'pruebas';
    protected $fillable = ['idespacio','idproducto','stock','estado'];
}
