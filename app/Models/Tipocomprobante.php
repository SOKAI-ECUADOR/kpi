<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tipocomprobante extends Model
{
    //
    protected $table="tipo_comprobante";
    protected $primaryKey = 'id_tipcomprobante';
    protected $fillable =['cod_tipcomprob', 'descrip_tipcomprob', 'id_empresa'];
}
