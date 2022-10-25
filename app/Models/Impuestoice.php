<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Impuestoice extends Model
{
    //
    protected $table="impuestoice";
    protected $primaryKey = "id";
    protected $fillable=['id', 'codigoPorcentaje', 'nombre', 'createdAt', 'updatedAt', 'createdBy_id', 'updatedBy_id', 'deletedBy_id'];
}
