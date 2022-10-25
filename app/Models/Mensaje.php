<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    //
    protected $table="mensaje";
    protected $primaryKey = "id";
    protected $fillable=['id', 'identificador', 'mensaje', 'informacionAdicional', 'tipo', 'Factura_id', 'NotaCredito_id', 'NotaDebito_id', 'Retencion_id', 'Guia_id'];
}
