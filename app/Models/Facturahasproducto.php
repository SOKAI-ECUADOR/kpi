<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facturahasproducto extends Model
{
    //
    protected $table="facturahasproducto";
    protected $primaryKey = "id";
    protected $fillable=['id', 'producto_id', 'factura_id', 'cantidad', 'nombre', 'codigoProducto', 'precioUnitario', 'descuento', 'valorTotal'];
}
