<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaquinaMobiliario extends Model
{
    
    protected $table="maquina_mobiliario";
    protected $primaryKey = "id_maquina";
    protected $fillable=['id_tipo_activo', 'codigo_identificacion_maquina', 'codigo_anterior_maquina', 'descripcion_anterior_maquina', 'descripcion_actualizada_maquina', 'id_marca', 'id_modelo', 'serie_maquina', 'id_color', 'id_material', 'id_conservacion', 'id_mantenimiento', 'fechacompra_maquina', 'costoadquisicion_maquina', 'id_ubicaciongeneral', 'id_ubicacionespecifica', 'id_custodio', 'cuentacontable_maquina', 'observaciones_maquina', 'id_empresa'];
}