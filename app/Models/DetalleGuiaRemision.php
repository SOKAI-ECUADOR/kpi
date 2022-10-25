<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleGuiaRemision extends Model
{
    protected $table="detalle_guia_remision";
    protected $primaryKey = "id_detalle_guia_remision";
    protected $fillable=['codigo_interno', 'codigo_adicional', 'descripcion', 'cantidad', 'id_producto', 'id_guia_remision'];
}
