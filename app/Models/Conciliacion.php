<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conciliacion extends Model
{
    //
    protected $table="conciliacion";
    protected $primaryKey = 'id_conciliacion';
    protected $fillable =[
        'cod_conciliacion',
        'fecha_conciliacion',
        'fecha_reguistro',
        'fecha_de_pago',
        'codigo_comprobante',
        'no_documento',
        'concepto',
        'debe',
        'haber',
        'saldo_libro',
        'saldo_cheque',
        'nuevo_saldo',
        'saldo_banco',
        'conciliación',
        'fcrea',
        'fmodifica',
        'id_plan_cuentas',
        'id_empresa'
    ];
}
