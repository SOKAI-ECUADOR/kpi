<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orden_compra extends Model
{
    protected $table = "orden_compra";
    protected $primaryKey = 'id_orden_compra';
    protected $fillable = ['fecha_emision', 'fecha_expiracion', 'observacion', 'estado', 'id_empresa', 'ucrea', 'umodifica', 'id_proveedor'];
}
