<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'personas';
    protected $fillable = ['nombre','direccion','telefono','email','estado','idtipo'];

    public function user(){
        return $this->hasOne('App\User');
    }
}
