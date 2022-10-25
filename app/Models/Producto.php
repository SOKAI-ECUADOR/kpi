<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = "producto";
    protected $primaryKey = "id_producto";
    protected $fillable = ['cod_principal', 'categoria', 'cod_alterno', 'imagen', 'nombre', 'codigo_barras', 'form_prod', 'descripcion', 'caracteristicas', 'normativa', 'uso', 'nombrec', 'sector', 'tipo_servicio', 'ubicacion_fisica', 'unidad_entrada', 'unidad_salida', 'vencimiento', 'existencia_maxima', 'existencia_minima', 'numero_unidad', 'grados_alcohol','estado', 'vehiculo', 'placa', 'pais_origen', 'ano_fabricacionv', 'color', 'carroceria', 'combustible', 'motor', 'cilindraje', 'chasis', 'clase', 'subclase', 'numero_pasajeros', 'iva', 'ice', 'total_ice','arancel_advalorem', 'arancel_especifico', 'arancel_fodinfa', 'comision', 'salvaguardia', 'descuento', 'pvp_precio1', 'precio2', 'precio3', 'precio4', 'precio5', 'utilidad_precio1', 'utilidad_precio2', 'utilidad_precio3', 'utilidad_precio4', 'utilidad_precio5', 'costo_unitario', 'existencia_total', 'psicotropicos', 'medicamento_controlado','id_linea_producto', 'id_tipo_producto', 'id_marca', 'id_modelo', 'id_presentacion', 'id_tipo_medida', 'id_unidad_medida', 'id_empresa', 'id_formula_produccion', 'id_plan_cuentas'];
}
