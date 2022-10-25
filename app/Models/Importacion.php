<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Importacion extends Model
{
    //
    protected $table="importacion";
    protected $primaryKey = 'id_importacion';
    protected $fillable=['cod_importacion', 'nro_orden','estado', 'fech_inicio', 'fech_fin', 'fech_embarque', 'fech_arribo', 'liquidar', 'cantidad', 'costo_unit', 'costo_total', 'id_proveedor', 'id_producto'];
}
