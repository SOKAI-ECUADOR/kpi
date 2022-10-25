<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado_Calendario extends Model
{
    protected $table = "calendario_empleado";
    protected $primaryKey = "id_calen_emp";
    protected $fillable = ['fecha_inicio', 'fecha_fin', 'razon', 'i_dempleado'];
}