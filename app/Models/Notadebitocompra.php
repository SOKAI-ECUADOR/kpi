<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notadebitocompra extends Model
{
    protected $table="nota_debito_compra";
    protected $primaryKey="id_nota_debito_compra";
    protected $fillable=['respuesta', 'modo', 'ambiente', 'tipo_emision', 'fecha_emision', 'fecha_expiracion', 'estatus', 'forma_pago', 'clave_acceso', 'observacion', 'subtotal_sin_impuesto', 'subtotal_12', 'subtotal_0', 'subtotal_no_obj_iva', 'descuento', 'valor_ice', 'valor_irbpnr', 'iva_12', 'propina', 'valor_total', 'totalpropinaf', 'pp_descuento', 'fcrea', 'fmodifica', 'umodifica', 'ucrea', 'id_proveedor', 'id_user', 'id_punto_emision', 'id_empresa', 'id_establecimiento', 'autorizacionfactura'];
}
