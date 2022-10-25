<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parametrizacion extends Model
{
    protected $table="parametrizacion";
    protected $primaryKey ='id_parametrizacion';
    protected $fillable =['descripcion', 'valor_decimo_cuarto', 'id_plan_cuentas_1','id_plan_cuentas_2','id_departamento' ];
}
