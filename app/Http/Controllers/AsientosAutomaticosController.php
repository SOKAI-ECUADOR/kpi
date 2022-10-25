<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Asientos;
use App\Models\Asientos_comprobante;
use App\Models\Asientos_comprobante_automaticos;
use App\Models\Asientos_contables_detalle;
use App\Models\Plancuenta;
use App\Models\Producto;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

define("MODULO_FACTURA_VENTA", 'FV');

define('RAZON_SOCIAL_FACTURA_DE_VENTA', 'FACTURA DE VENTA');

class AsientosAutomaticosController extends Controller
{
    /**
     * Guarda asientos contables automaticos de facturas de venta
     * @author Gabriel Costta
     *
     */
    public function AsientoContableFacturaVenta($request, $tipo)
    {
        $haber = [];
        DB::transaction(function () use ($request, $tipo, $haber) {
            $ultimoNumero = $this->obtenerUltimoNumeroDeAsientoContableAutomatico($tipo);
            $asientos_cabecera = new Asientos();
            $asientos_cabecera->numero = $ultimoNumero;
            $asientos_cabecera->fecha = $request->fecha_emision;
            $asientos_cabecera->razon_social = RAZON_SOCIAL_FACTURA_DE_VENTA;
            $asientos_cabecera->ruc_ci = $request->cabecera["ruc_ci"];
            $asientos_cabecera->concepto = RAZON_SOCIAL_FACTURA_DE_VENTA;
            $asientos_cabecera->id_proyecto = $request->proyecto;
            $asientos_comprobante = Asientos_comprobante_automaticos::select("asientos_comprobante_automaticos.*")
                ->where("codigo", "=", $tipo)
                ->get();
            $asientos_cabecera->id_asientos_comprobante_automaticos = $asientos_comprobante[0]->id_asientos_comprobante_automaticos;
            $asientos_cabecera->automatico = 1;
            $asientos_cabecera->codigo = $asientos_comprobante[0]->codigo . "-" . $ultimoNumero;
            $asientos_cabecera->save();
            $haber[143] = floatval($request->iva_12);
            foreach ($request->productos as $producto) {
                $backendProducto = Producto::find($producto["id_producto"]);
                $cuenta = Plancuenta::find($backendProducto->id_plan_cuentas);
                if (!array_key_exists($cuenta["id_plan_cuentas"], $haber)) {
                    $haber[$cuenta["id_plan_cuentas"]] = floatval($producto["precio"]) * intval($producto["cantidad"]);
                } else {
                    $haber[$cuenta["id_plan_cuentas"]] += floatval($producto["precio"]) * intval($producto["cantidad"]);
                }
            }
            $proyecto = Proyecto::find($request->proyecto);
            foreach ($haber as $key => $asientos) {
                $asientoContableAutomatico = new Asientos_contables_detalle();
                $asientoContableAutomatico->proyecto = $proyecto->descripcion;
                $asientoContableAutomatico->haber = $haber[$key];
                $asientoContableAutomatico->id_plan_cuentas = $key;
                $asientoContableAutomatico->id_asientos = $asientos_cabecera->id_asientos;
                $asientoContableAutomatico->save();
            }
        });
        return $request;
    }
}
