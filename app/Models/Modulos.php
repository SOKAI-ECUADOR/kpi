<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modulos extends Model
{
    protected $table = "modulos";
    protected $primaryKey = "id_modulos";
    protected $fillable = ['nombre', 'value', 'ver', 'escribir', 'crear', 'id_user', 'fcrea', 'fmodifica'];
}
