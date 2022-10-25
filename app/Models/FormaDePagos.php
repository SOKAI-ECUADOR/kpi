<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormaDePagos extends Model
{
    protected $table= "forma_pagos";
    protected $primaryKey ="id_forma_pagos";
    protected $fillable =['codigo','descripcion', 'id_empresa','id_forma_pagos_sri'];
}
