<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles';
    protected $fillable = ['nombre', 'estado']; 
    
    
    public function users(){
        return $this->hasMany('App\User');
    }
}
