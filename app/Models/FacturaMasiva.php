<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacturaMasiva extends Model
{
    protected $table = "factura_masiva";
    protected $primaryKey = 'id_factura_masiva';
    protected $fillable = [
        'codigo',
        'respuesta',
        'modo',
        'modo_acumulado',
        'estado',
        'ambiente',
        'tipo_emision',
        'fecha_emision',
        'fecha_expiracion',
        'fecha_factura',
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
        'condiciones_de_pago',
        'lugar_de_entrega',
        'contabilidad',
        'fcrea',
        'fmodifica',
        'umodifica',
        'ucrea',
        'mensaje_sri',
        'informacion_sri',
        'id_grupo_cliente',
        'id_user',
        'id_punto_emision',
        'id_empresa',
        'id_establecimiento',
        'id_proyecto',
        'id_forma_pagos',
        'id_vendedor'
    ];
}
