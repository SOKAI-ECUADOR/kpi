<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TablaInteres extends Model
{
    
    protected $table="tabla_interes";
    protected $primaryKey = "id_tabla_interes";
    protected $fillable=['codigo_periodo', 'interes', 'delete'];
}
