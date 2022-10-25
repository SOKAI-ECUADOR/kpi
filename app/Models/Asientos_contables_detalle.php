<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asientos_contables_detalle extends Model
{
    protected $table="asientos_detalle";
    protected $primaryKey="id_detalle";
    protected $fillable = [
        'proyecto',
        'debe',
        'haber',
        'fcrea',
        'fmodifica',
        'ucrea',
        'umodifica',
        'id_plan_cuentas',
        'id_asientos',
        'fecha_de_pago',
    ];
}
