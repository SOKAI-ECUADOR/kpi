<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detalleadicional extends Model
{
    //
    protected $table="detalleadicional";
    protected $primaryKey = "id";
    protected $fillable =['id', 'nombre', 'valor', 'facturaHasProducto_id', 'notaCreditoHasProducto_id'];
}
