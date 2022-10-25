<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    //
    protected $table="banco";
    protected $primaryKey='id_banco';
    protected $fillable = ['nombre_banco', 'direccion_banco', 'estado'];
}

