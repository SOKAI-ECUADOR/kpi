<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    //
    protected $table="plan";
    protected $primaryKey = "id";
    protected $fillable=['id', 'cantComprobante', 'precio', 'periodo', 'observaciones', 'activo'];
}
