<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TablaInteresAnual extends Model
{
    protected $table="tabla_interes_anual";
    protected $primaryKey = "id_tabla_interes_anual";
    protected $fillable=['interes_anual', 'periodo_pago', 'tiempo_pago','delete'];
}
