<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asientos_comprobante extends Model
{
    protected $table="asientos_comprobante";
    protected $primaryKey="id_asientos_comprobante";
    protected $fillable = [
        'tipo',
        'codigo'
    ];
}
