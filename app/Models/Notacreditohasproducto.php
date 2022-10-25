<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notacreditohasproducto extends Model
{
    //
    protected $table="notacreditohasproducto";
    protected $primaryKey = "id";
    protected $fillable=['id', 'producto_id', 'cantidad', 'nombre', 'codigoProducto', 'precioUnitario', 'descuento', 'valorTotal', 'notaCredito_id'];
}
