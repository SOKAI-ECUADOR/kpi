<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campo_adicional extends Model
{
    protected $table = "campo_adicional";
    protected $primaryKey = 'id_campo_adicional';
    protected $fillable = ['nombre', 'id_empresa'];
    
}
    