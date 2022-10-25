<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedorimportacion extends Model
{
    protected $table="proveedor_importacion";
    protected $primaryKey = "id_provimport";
    protected $fillable=['nombre', 'telefono', 'grupo', 'tipo_identificacion', 'identificacion', 'direccion', 'id_proveedor', 'id_importacion', 'id_empresa'];
}
