<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Cuenta_Importacion extends Model
{
    protected $table="cuenta_importacion";
    protected $primaryKey='id_cuenta_importacion';
    protected $fillable = ['cod_cuenta', 'nombre_cuenta', 'id_plan_cuentas', 'id_empresa'];
}
