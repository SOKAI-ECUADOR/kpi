<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guiahasproducto extends Model
{
    //
    protected $table="guiahasproducto";
    protected $primaryKey = "id";
    protected $fillable=['id', 'producto_id', 'guia_id', 'cantidad', 'nombre', 'codigoProducto'];
}
