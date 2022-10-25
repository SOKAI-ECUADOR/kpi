<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documentacion extends Model
{
    protected $table = "documentacion";
    protected $primaryKey = "id_documento";
    protected $fillable = ['doc_descripcion', 'doc_estatus'];
}