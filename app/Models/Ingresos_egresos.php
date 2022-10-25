<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingresos_egresos extends Model
{
    //
    protected $table="ingresos_egresos";
    protected $primaryKey = "id_ineg";
    protected $fillable =['decripcion', 'tipo','id_empresa', 'id_departamento', 'id_departamento', 'id_plan_cuentas_1', 'id_plan_cuentas_2'];
}
