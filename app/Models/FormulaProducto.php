<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormulaProducto extends Model
{
    protected $table = "formula_producto";
    protected $primaryKey = "id_formula_producto";
    protected $fillable = ['id_producto', 'id_formula_produccion'];
}
