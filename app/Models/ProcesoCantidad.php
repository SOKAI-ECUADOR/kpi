<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcesoCantidad extends Model
{
    protected $table = "proceso_cantidad";
    protected $primaryKey = 'id_proceso_cantidad';
    protected $fillable = ['cantidad', 'costo_unitario', 'costo_total', 'id_proceso_produccion', 'id_proceso_producto', 'id_proceso_ingrediente'];
}
