<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanSeguroDetalle extends Model
{
    protected $table="plan_seguro_detalle";
    protected $primaryKey = "id_plan_seguro_detalle";
    protected $fillable=['id_producto', 'id_plan_seguro','agregado'];
}
