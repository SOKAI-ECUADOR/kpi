<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormaDePagosSri extends Model
{
    protected $table= "forma_pagos_sri";
    protected $primaryKey ="id_forma_pagos_sri";
    protected $fillable =['codigo','descripcion', 'id_empresa'];
}
