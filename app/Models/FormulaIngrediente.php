<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormulaIngrediente extends Model
{
    protected $table = "formula_ingrediente";
    protected $primaryKey = "id_formula_ingrediente";
    protected $fillable = ['id_producto', 'id_formula_produccion', 'cant_unit_prod'];
}
