<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonaModelo extends Model
{
    protected $table = 'personas';
    protected $fillable = ['nombre','direccion','telefono','email','estado'];
}
