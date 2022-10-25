<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolProviciones extends Model
{
    protected $table="rol_provicion";
    protected $primaryKey = "id_rol_provicion";
    protected $fillable=['fechrolprov', 'primer_nombre', 'cantidad', 'total_ingreso', 'iess_patronal', 'decimo_tercero', 'decimo_cuarto','vacaciones','iece','total_provisiones','total_costo','id_empresa','id_empleado','id_departamento'];
}
