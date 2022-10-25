<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotaVenta extends Model
{
    protected $table = "nota_venta";
    protected $primaryKey = 'id_nota_venta';
    protected $fillable = [
        'codigo',
        'respuesta',
        'modo',
        'estado',
        'ambiente',
        'tipo_emision',
        'fecha_emision',
        'fecha_expiracion',
        'estatus',
        'clave_acceso',
        'observacion',
        'subtotal_sin_impuesto',
        'subtotal_12',
        'subtotal_0',
        'subtotal_no_obj_iva',
        'descuento',
        'valor_ice',
        'valor_irbpnr',
        'iva_12',
        'propina',
        'valor_total',
        'pp_descuento',
        'totalpropinaf',
        'orden_compra',
        'migo',
        'fcrea',
        'fmodifica',
        'umodifica',
        'ucrea',
        'id_cliente',
        'id_user',
        'id_punto_emision',
        'id_empresa',
        'id_establecimiento',
        'id_proyectos',
        'id_forma_pagos',
        'condiciones_de_pago',
        'lugar_de_entrega', 
        'created_by', 
        'updated_by'
    ];
}
