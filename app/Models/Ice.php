<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ice extends Model
{
    protected $table = "ice";
    protected $primaryKey = "id_ice";
    protected $fillable = ['codigo', 'nombre', 'valor', 'observacion', 'fcrea', 'fmodifica', 'ucrea', 'umodifica', 'id_ice_formula', 'id_plan_cuentas','id_empresa'];
}
