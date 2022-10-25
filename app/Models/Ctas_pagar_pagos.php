<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ctas_pagar_pagos extends Model
{
    protected $table="ctas_pagar_pagos";
    protected $primaryKey='id_ctas_pagar_pagos';
    protected $fillable = ['pagos_por', 'nro_tarjeta', 'referencia', 'valor_seleccionado', 'descuento_porcentaje', 'descuento_pago', 'valor_real_pago', 'id_forma_pagos', 'id_banco', 'id_factura_compra', 'id_proveedor'];
}
