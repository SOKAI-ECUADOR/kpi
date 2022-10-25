<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seguro extends Model
{
    protected $table="seguro";
    protected $primaryKey = "id_seguro";
    protected $fillable=['nombre', 'estado', 'fcrea', 'fmodifica', 'ucrea', 'umodifica'];
}
