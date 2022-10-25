<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivoGrupo extends Model
{
    //
    protected $table="activo_fijo_grupo";
    protected $primaryKey ='id_activo_fijo_grupo';
    protected $fillable =['codigo','nombre','anios','valor_residual','porcentaje','id_empresa','id_plan_cuenta_debito','id_plan_cuenta_credito'];
}
