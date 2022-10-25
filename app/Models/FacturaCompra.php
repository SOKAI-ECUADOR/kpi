<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacturaCompra extends Model
{
    //
    protected $table="factura_compra";
    protected $primaryKey = "id_factcompra";
    protected $fillable=['cod_factcompra','respuesta', 'nro_factprov', 'destino_pago', 'fech_factcompra', 'gasto_import', 'cotizacion', 'descripcion', 'tipo_iva', 'unid_destino', 'fech_emision', 'fech_validez', 'serie', 'nro_autorizacion', 'tipo_pago', 'efectivo', 'cant_efectivo', 'caja_chica', 'cant_caja', 'nro_caja', 'transaccion', 'tipo_transaccion', 'nro_transacion', 'fech_transacion', 'tarjeta', 'nro_tarjeta', 'nro_pago', 'plazo_pago', 'dias_pago_empresa', 'total_neto', 'base_12', 'base_0', 'tarifa_no_graba', 'otro_imp', 'descuento', 'iva', 'total_factura', 'compensacion', 'id_sustento', 'id_proveedor', 'id_moneda', 'id_provincia', 'id_cliente_asoc', 'id_producto', 'id_ptoemision', 'id_retiva', 'id_retfuente', 'id_caja', 'id_banco'];
}
