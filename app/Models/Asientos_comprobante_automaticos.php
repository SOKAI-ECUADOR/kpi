<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asientos_comprobante_automaticos extends Model
{
    protected $table="asientos_comprobante_automaticos";
    protected $primaryKey="id_asientos_comprobante_automaticos";
    protected $fillable = [
        'tipo',
        'codigo'
    ];
}
