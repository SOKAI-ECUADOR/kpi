<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Productoorden extends Model
{
    //
    protected $table="producto_ordencompra";
    protected $primaryKey ='id_prodordcom';
    protected $fillable =['nombre', 'cantidad', 'precio', 'iva', 'id_orden'];
}
