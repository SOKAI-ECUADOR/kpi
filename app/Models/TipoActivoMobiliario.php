<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoActivoMobiliario extends Model
{
    
    protected $table="tipo_activo_mobiliario";
    protected $primaryKey = "id_tipo_activo_mobiliario";
    protected $fillable=['descripcion_tipo_activo', 'cuenta_contable_tipo_activo', 'id_empresa'];
}