<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detalle_nota_credito extends Model
{
    protected $table="detalle_nota_credito";
    protected $primaryKey = "id_detalle_nota_credito";
    protected $fillable =['nombre', 'cantidad', 'precio', 'descuento', '%descuento', 'total', 'p_descuento', 'id_iva', 'id_ice', 'irbpnr', 'fcrea', 'fmodifica', 'id_producto', 'id_nota_credito'];
}
