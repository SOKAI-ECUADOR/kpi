<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Retencion_factura extends Model
{
    protected $table="retencion_factura";
    protected $primaryKey = 'id_retencion_factura';
    protected $fillable=['id_factura', 'id_retencion_iva', 'id_retencion_renta', 'fcrea', 'fmodifica'];
}
