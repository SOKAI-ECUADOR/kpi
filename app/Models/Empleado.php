<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = "empleado";
    protected $primaryKey = "id_empleado";
    protected $fillable = ['tipo_dni', 'dni', 'primer_nombre','segundo_nombre', 'apellido_paterno','apellido_materno',
    'fecha_nacimiento', 'edad', 'foto', 'estado_civil', 'sexo', 'direccion_residencia','telefono','celular','email', 
    'tipo_sangre','profesion','discapacidad','discap_porcentaje','tipo_iden_discap','num_iden_discap','tipo_cuenta', 
    'num_cuenta', 'num_cargas', 'carga','estado', 'observaciones','fecha_ingreso','fecha_salida','tipo_horario','tipo_contrato',
    'sueldo','aporte_iess','fondo_reserva','decimo_tercero','decimo_cuarto','observacion_cargo','lugar_residencia',
    'id_nacionalidad', 'id_banco','id_empresa','id_departamento','id_provincia','id_ciudad','id_parroquia','id_grupo',
    'id_cargo','id_plan_cuentas','id_area_trabajo'   ];
}
