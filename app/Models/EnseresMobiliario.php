<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnseresMobiliario extends Model
{
    
    protected $table="enseres_mobiliario";
    protected $primaryKey = "id_enseres";
    protected $fillable=['id_tipo_activo', 'codigo_identificacion_enseres', 'codigo_anterior_enseres', 'descripcion_anterior_enseres', 'descripcion_actualizada_enseres', 'id_marca', 'id_dimension', 'id_color', 'id_material', 'id_conservacion', 'id_mantenimiento', 'fechacompra_enseres', 'costoadquisicion_enseres', 'id_ubicaciongeneral', 'id_ubicacionespecifica', 'id_custodio', 'cuentacontable_enseres', 'observaciones_enseres', 'id_empresa'];
}