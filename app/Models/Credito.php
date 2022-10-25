<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credito extends Model
{
    protected $table="credito";
    protected $primaryKey ='id_credito';
    protected $fillable =['periodo', 'plazos', 'plazos_pagados', 'monto', 'letras', 'id_factura'];
}
