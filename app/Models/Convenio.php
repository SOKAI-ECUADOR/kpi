<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Convenio extends Model
{
    
    protected $table="convenio";
    protected $primaryKey = "id_convenio";
    protected $fillable=['nombre_convenio', 'lista_precio_convenio', 'porcentaje_descuento_convenio', 'status_convenio', 'id_empresa'];
}