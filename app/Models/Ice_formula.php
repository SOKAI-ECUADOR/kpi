<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ice_formula extends Model
{
    protected $table = "ice_formula";
    protected $primaryKey = "id_ice_formula";
    protected $fillable = ['codigo','nombre', 'formula', 'fcrea', 'fmodifica', 'ucrea', 'umodifica', 'id_empresa'];
}
