<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asignar_ingresos extends Model
{
    //
    protected $table="asignar_ingresos";
    protected $primaryKey="id_asignar_ingresos";
    protected $fillable = ['cod_asignar_ingresos','valor', 'tipo', 'id_empleado', 'id_ineg'];

}
