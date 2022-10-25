<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupocliente extends Model
{
    //
    protected $table="grupo_cliente";
    protected $primaryKey ="id_grupo_cliente";
    protected $fillable =['codigo', 'nombre_grupo' , 'fcrea', 'fmodifica', 'ucrea', 'umodifica', 'id_plan_cuentas', 'id_plan_cuentas_descuento', 'id_plan_cuentas_anticipo', 'id_plan_cuentas_servicio','id_empresa'];

}
