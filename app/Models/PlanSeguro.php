<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanSeguro extends Model
{
    protected $table="plan_seguro";
    protected $primaryKey = "id_plan_seguro";
    protected $fillable=['nombre', 'descuento','estado', 'fcrea', 'fmodifica', 'ucrea', 'umodifica', 'id_seguro'];
}
