<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustodioMobiliario extends Model
{
    
    protected $table="custodio_mobiliario";
    protected $primaryKey = "id_custodio";
    protected $fillable=['cedula_custodio', 'nombre_custodio', 'id_empresa'];
}