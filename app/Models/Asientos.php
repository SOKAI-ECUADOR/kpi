<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asientos extends Model
{
    //
    protected $table="asientos";
    protected $primaryKey="id_asientos";
    protected $fillable = [
        'id_asientos_comprobante', 
        'numero', 
        'fecha', 
        'razon_social', 
        'ruc_ci', 
        'concepto',
        'id_proyecto',
        'codigo'
    ];
}
