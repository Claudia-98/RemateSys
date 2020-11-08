<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompraModelo extends Model
{
    protected $table = 'compra';
    protected $fillable = ['fecha','estado','total','observaciones','idpersona','idusuario'];
}
