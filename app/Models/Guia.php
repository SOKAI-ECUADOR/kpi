<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guia extends Model
{
    protected $table="guia";
    protected $primaryKey = "id";
    protected $fillable=['id', 'cliente_id', 'emisor_id', 'establecimiento_id', 'claveAcceso', 'numeroAutorizacion', 'fechaAutorizacion', 'estado', 'ambiente', 'tipoEmision', 'secuencial', 'nombreArchivo', 'dirPartida', 'razonSocialTransportista', 'rucTransportista', 'fechaIniTransporte', 'fechaFinTransporte', 'placa', 'motivoTraslado', 'firmado', 'enviarSiAutorizado', 'createdAt', 'updatedAt', 'ptoEmision_id', 'createdBy_id', 'updatedBy_id', 'deletedBy_id'];
}
