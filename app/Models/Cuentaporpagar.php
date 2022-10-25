<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuentaporpagar extends Model
{
    protected $table="ctas_pagar";
    protected $primaryKey='id_ctaspagar';
    protected $fillable = [
        'unidad_tiempo', 'nro_cuota', 'cuotas_totales', 'forma_pago', 'banco', 'nro_tarjeta', 'cta_contable', 'monto', 'abono', 'saldo', 'descuento', 'estado', 'tipo', 'comentario', 'fcrea', 'fmodifica', 'freguistro', 'fvencimiento', 'ucrea', 'umodifica', 'id_cliente', 'id_factura'
    ];
}
