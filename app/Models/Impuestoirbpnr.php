<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Impuestoirbpnr extends Model
{
    //
    protected $table="impuestoirbpnr";
    protected $primaryKey = "id";
    protected $fillable=['id', 'codigoPorcentaje', 'nombre', 'createdAt', 'updatedAt', 'createdBy_id', 'updatedBy_id', 'deletedBy_id'];

}
