<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LibroMobiliario extends Model
{
    
    protected $table="libro_mobiliario";
    protected $primaryKey = "id_libro";
    protected $fillable=['id_tipo_activo', 'codigo_identificacion_libro', 'codigo_anterior_libro', 'titulo_anterior_libro', 'titulo_actualizado_libro', 'editorial_libro', 'id_conservacion', 'id_mantenimiento', 'fechacompra_libro', 'costoadquisicion_libro', 'id_ubicaciongeneral', 'id_ubicacionespecifica', 'id_custodio', 'cuentacontable_libro', 'observaciones_libro', 'id_empresa'];
}