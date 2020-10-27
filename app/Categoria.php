<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'tipoproducto';
    protected $fillable = ['nombre','descripcion','estado'];
}
