<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RetencionFactura extends Model
{
    //
    protected $table="retencion_factura_comp";
    protected $primaryKey='id_retfactcompra';
    protected $fillable = ['id_factura', 'id_retencion_iva', 'id_retencion_renta', 'porcentajeiva', 'cantidadiva', 'baserenta', 'porcentajerenta', 'cantidadrenta', 'fcrea', 'fmodifica'];
}
