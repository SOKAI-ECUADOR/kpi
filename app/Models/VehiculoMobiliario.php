<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehiculoMobiliario extends Model
{
    
    protected $table="vehiculo_mobiliario";
    protected $primaryKey = "id_vehiculo";
    protected $fillable=['id_tipo_activo', 'id_identificador', 'codigo_identificacion_vehiculo', 'codigo_anterior_vehiculo', 'nombre_bien_vehiculo', 'descripcion_vehiculo', 'id_marca', 'id_modelo', 'id_color', 'id_color_secundario', 'id_tipo', 'ano_fabricacion_vehiculo', 'id_conservacion', 'id_mantenimiento', 'fechacompra_vehiculo', 'costoadquisicion_vehiculo', 'combustible_vehiculo', 'motor_vehiculo', 'placa_vehiculo', 'chasis_vehiculo', 'kilometraje_vehiculo', 'vehiculo_vehiculo', 'id_ubicaciongeneral', 'id_ubicacionespecifica', 'id_custodio', 'cuentacontable_vehiculo', 'observaciones_vehiculo', 'id_empresa'];
}