<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormulaProduccion extends Model
{
    protected $table = "formula_produccion";
    protected $primaryKey = "id_formula_produccion";
    protected $fillable = ['codigo_produccion', 'nombre_form', 'id_producto_produ', 'fcrea', 'fmodifica', 'ucrea', 'umodifica'];
}
