<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = "empresa";
    protected $primaryKey = "id_empresa";
    protected $fillable = ['codigo_empresa', 'periodo_empresa', 'nombre_empresa', 'razon_social', 'ruc_empresa', 'direccion_empresa', 'telefono', 'email_empresa', 'nro_comprobantes', 'password', 'servidor_correo', 'puerto_correo', 'seguridad_correo', 'tipo_identidicacion_empresa', 'obligado_contabilidad', 'ruc_contador', 'nombre_contador', 'fecha', 'identificaion_rep', 'nomb_representante', 'clave_duracion', 'periodo_inicio', 'periodo_fin', 'mascara_empresa', 'recalculo', 'balance', 'empresa_asociada', 'noresolucion', 'nocontribuyente', 'codigo_entidad', 'contribuyente', 'tipo_ctas', 'logo', 'firma', 'pass_firma', 'actualizacion_firma', 'fecha_firma', 'fecha_expiracion_firma', 'fcierre', 'fresolucion', 'tipo_emision', 'ambiente', 'negativo', 'fmodifica', 'fcrea', 'ucrea', 'umodifica', 'id_moneda', 'id_provincia', 'id_ciudad', 'id_plan_cuentas_resultado', 'id_plan_cuentas_ingreso', 'id_plan_cuentas_costo', 'id_plan_cuentas_activo', 'id_plan_cuentas_pasivo', 'id_plan_cuentas_patrimonio', 'id_plan_cuentas_gasto', 'id_plan_cuentas_orden', 'migo', 'compra', 'leyenda', 'email_facturacion', 'estado', 'xml_factura_compra'];
}
