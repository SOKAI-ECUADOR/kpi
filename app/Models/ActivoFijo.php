<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivoFijo extends Model
{
    //
    protected $table="activos_fijos";
    protected $primaryKey ='id_activos_fijos';
    protected $fillable =[
        'codigo',
        'codigo_barra',
        'nombre',
        'depreciacion_mensual',
        'valor_bien',
        'valor_residual',
        'valor_depreciar',
        'valor_depreciacion',
        'valor_reavaluo',
        'depreciacion_acumulada',
        'valor_actual',
        'numero_meses_depreciacion',
        'fecha_movimiento',
        'observaciones',
        'ultima_depreciacion',
        'fecha_factura',
        'nro_factura',
        'nro_autorizacion',
        'nombre_proveedor',
        'id_plan_cuentas_debito',
        'id_plan_cuentas_credito',
        'id_area_activo_fijo',
        'id_departamento',
        'id_empresa',
        'id_grupo_activo_fijo',
        'id_tipo_activo_fijo',
        'id_factura_compra',
        'id_empleado',
        'id_proveedor',
        'id_proyecto',
        'id_moneda',
    ];
}
