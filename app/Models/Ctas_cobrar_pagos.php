<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ctas_cobrar_pagos extends Model
{
    protected $table="ctas_cobrar_pagos";
    protected $primaryKey='id_ctas_cobrar_pagos';
    protected $fillable = ['pagos_por', 'nro_tarjeta', 'referencia', 'valor_seleccionado', 'descuento_porcentaje', 'descuento_pago', 'valor_real_pago', 'id_forma_pagos', 'id_banco', 'id_factura', 'id_cliente', 'created_by', 'updated_by'];
}
