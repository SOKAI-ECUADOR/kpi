<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    //
    protected $table="user";
    protected $primaryKey = "id";
    protected $fillable=['nombres', 'apellidos', 'password', 'email', 'estado', 'entrada', 'id_rol', 'id_empresa', 'id_establecimiento', 'id_punto_emision'];
}
