<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsiganrIngresosDetalle extends Model
{
    protected $table="asignar_ingresos_detalle";
    protected $primaryKey="id_asignar_ingresos_detalle";
    protected $fillable = ['valor', 'id_asignar_ingreso', 'id_empleado', 'id_ingreso_egreso'];
}
