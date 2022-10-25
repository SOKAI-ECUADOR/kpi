<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiquidacionCompra extends Model
{
    protected $table="liquidacion_compra";
    protected $primaryKey = "id_liquidacion_compra";
    protected $fillable=['respuesta','estado','fecha_emision','fecha_envio','clave_acceso','observacion','campo_adicional','fcrea','fmodifica','ucrea','umodifica','id_proveedor','id_user','id_punto_emision','id_establecimiento','id_empresa'];
}
