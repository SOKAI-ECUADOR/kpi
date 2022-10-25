<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Impuestoiva extends Model
{
    //
    protected $table="impuestoiva";
    protected $primaryKey = "id";
    protected $fillable=['id', 'codigoPorcentaje', 'nombre', 'tarifa', 'createdAt', 'updatedAt', 'createdBy_id', 'updatedBy_id', 'deletedBy_id'];
}
