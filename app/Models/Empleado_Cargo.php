<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado_Cargo extends Model
{
    protected $table = "empleado_cargo";
    protected $primaryKey = "id_emp_cargo";
    protected $fillable = ['fecha_ingreso', 'fecha_salida', 'tipo_horario', 'tipo_contrato', 'bonos', 
    'aporte_iess', 'fondo_reserva', 'decimo_tercero', 'decimo_cuarto', 'cuenta_contable', 
    'observaciones', 'id_empleado', 'idcargo', 'idgrupo'];
}
