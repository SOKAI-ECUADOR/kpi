<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Creditocompra extends Model
{
    //
    protected $table="credito_compras";
    protected $primaryKey='id_credcompra';
    protected $fillable = ['periodo', 'plazo', 'plazos_pagado', 'monto', 'letras','id_factcompra'];
}
