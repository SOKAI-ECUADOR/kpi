<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado_Documento extends Model
{
    protected $table = "docu_empleado";
    protected $primaryKey = "id_docu_emp";
    protected $fillable = ['doc_url', 'doc_estado', 'id_documento', 'id_empleado'];
}
