<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Motivo extends Model
{
    //
    protected $table="motivo";
    protected $primaryKey = "id";
    protected $fillable=['id', 'nombre', 'valor', 'dotaDebito_id'];
}
