<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Impuestocomprobanteretencion extends Model
{
    //
    protected $table="impuestocomprobanteretencion";
    protected $primaryKey = "id";
    protected $fillable=['id', 'retencion_id', 'codigo', 'codigoRetencion', 'baseImponible', 'porcentajeRetener', 'valorRetenido', 'codDocSustento', 'numDocSustento', 'fechaEmisionDocSustento'];
}
