<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Cuentaporpagar;
use App\Models\Detalle_factura_compra;
use App\Models\Factura;
use App\Models\FacturaCompra;
use App\Models\Factura_compra_pagos;
use App\Models\Importacion;
use App\Models\Moneda;
use App\Models\Pagocompra;
use App\Models\ProductoFactura;
use App\Models\Proveedor;
use App\Models\Provincia;
use App\Models\Ptoemision;
use App\Models\RetencionFactura;
use App\Models\Tiposustento;
use App\Models\ProductoBodega;
use Carbon\Carbon;
use generarReportes;
use Illuminate\Http\Request;
use App\Http\Controllers\Response;

include 'class/generarPDF.php';

use DOMDocument;
use generarPDF;

use App\Models\BodegaEgreso;
use App\Models\BodegaEgresoDetalle;

use App\Models\BodegaIngreso;
use App\Models\BodegaIngresoDetalle;

use App\Models\ProductoBodegaLotes;

use App\Models\Asientos;
use App\Models\Asientos_contables_detalle;

include 'class/generarReportes.php';

use Illuminate\Support\Facades\DB;

include_once getenv("FILE_CONFIG_PHP");

class FacturacompraController extends Controller
{
    public function index(Request $request, $id)
    {
        $buscar = str_replace(array(" ", "_", "-"), "%", $request->buscar);
        if ($buscar == '') {
            $recupera = DB::select("SELECT fc.*, pr.nombre_proveedor, pr.id_provincia, (SELECT id_retfactcompra FROM retencion_factura_comp WHERE id_factura = fc.id_factcompra LIMIT 1) AS id_retfactcompra, enp.*,(select direccion from establecimiento where establecimiento.id_empresa=enp.id_empresa limit 1) as direccion_establecimiento FROM factura_compra fc LEFT JOIN proveedor pr ON pr.id_proveedor=fc.id_proveedor LEFT JOIN empresa enp ON enp.id_empresa = fc.id_empresa WHERE fc.id_empresa = $id AND fc.modo_orden = 0 ORDER BY fc.fech_emision DESC, fc.id_factcompra DESC");
        } else {
            $recupera = DB::select("SELECT fc.*, pr.nombre_proveedor, pr.id_provincia, (SELECT id_retfactcompra FROM retencion_factura_comp WHERE id_factura = fc.id_factcompra LIMIT 1) AS id_retfactcompra, enp.*,(select direccion from establecimiento where establecimiento.id_empresa=enp.id_empresa limit 1) as direccion_establecimiento FROM factura_compra fc LEFT JOIN proveedor pr ON pr.id_proveedor=fc.id_proveedor LEFT JOIN empresa enp ON enp.id_empresa = fc.id_empresa WHERE (pr.nombre_proveedor LIKE '%$buscar%' OR fc.descripcion LIKE '%$buscar%' OR id_factcompra LIKE '%$buscar%') AND fc.id_empresa = $id AND fc.modo_orden = 0 ORDER BY fc.fech_emision DESC, fc.id_factcompra DESC");
        }
        return [
            'recupera' => $recupera,
        ];
    }

    public function ajustarfecharetenciones(){
        $datos = DB::select("select * from retencion_factura_comp inner join factura_compra on retencion_factura_comp.id_factura=factura_compra.id_factcompra where retencion_factura_comp.fcrea is null");

        for($i=0; $i<count($datos); $i++){

            DB::update("UPDATE retencion_factura_comp set fcrea=".$datos[$i]->fech_emision." where id_retfactcompra='".$datos[$i]->id_retfactcompra."'");

        }

        echo "<h1>Fechas Ajustadas Correctamente.</h1>";

    }

    public function store(Request $request)
    {
        $hoy = Carbon::now();
        $f_pagos = DB::select("SELECT * from forma_pagos");

        $factc = new FacturaCompra();
        $factc->destino_pago = $request->destino_pago;
        $factc->gasto_import = $request->gasto_import;
        $factc->documento_tributario = $request->documento_tributario;
        $factc->id_importacion = $request->nro_importacion;
        $factc->orden_compra = $request->orden_compra;
        $factc->descripcion = $request->descripcion;
        $factc->fech_emision = $request->fech_emision;
        $factc->fech_validez = $request->fech_validez;
        $factc->nro_autorizacion = $request->nro_autorizacion;

        $factc->subtotal_sin_impuesto = $request->subtotal_sin_impuesto;
        $factc->subtotal_12 = $request->subtotal_12;
        $factc->subtotal_0 = $request->subtotal_0;
        $factc->subtotal_no_obj_iva = $request->subtotal_no_obj_iva;
        $factc->descuento = $request->descuento;
        $factc->valor_ice = $request->valor_ice;
        $factc->valor_irbpnr = $request->valor_irbpnr;
        $factc->iva_12 = $request->iva_12;
        $factc->total_factura = $request->total_factura;
        $factc->modo_orden = 0;
        $factc->facturado_orden = 1;
        $factc->id_sustento = $request->id_sustento;
        $factc->id_proveedor = $request->id_proveedor;
        $factc->id_cliente_asoc = $request->id_cliente_asoc;
        $factc->id_importacion = $request->id_importacion;
        $factc->id_user = $request->id;
        $factc->id_empresa = $request->id_empresa;
        $factc->id_establecimiento = $request->id_establecimiento;
        $factc->id_punto_emision = $request->id_punto_emision;
        $factc->observacion = $request->claveacceso;
        $factc->id_forma_pagos = $f_pagos[0]->id_forma_pagos;
        $factc->save();

        $id = $factc->id_factcompra;

        for ($a = 0; $a < count($request->productos); $a++) {
            $dfactc = new Detalle_factura_compra();
            $dfactc->nombre = $request->productos[$a]["nombre"];
            $dfactc->cantidad = $request->productos[$a]["cantidad"];
            $dfactc->precio = $request->productos[$a]["precio"];
            $dfactc->descuento = $request->productos[$a]["descuento"];
            $dfactc->p_descuento = $request->productos[$a]["p_descuento"];
            if ($request->productos[$a]["p_descuento"] == 1) {
                $dfactc->total = ($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"]) - $request->productos[$a]["descuento"];
            } else {
                $dfactc->total = ($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"]) - (($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"]) * $request->productos[$a]["descuento"]) / 100;
            }
            $dfactc->id_iva = $request->productos[$a]["iva"];
            $dfactc->id_ice = $request->productos[$a]["ice"];
            $dfactc->irbpnr = 0;
            $dfactc->id_producto = $request->productos[$a]["id_producto"];
            $dfactc->id_factura = $id;
            $dfactc->save();
        }

        if ($request->verpagos) {
            for ($a = 0; $a < count($request->pagos); $a++) {
                if ($request->pagos[$a]["metodo_pago"] != null && $request->pagos[$a]["cantidad_pago"] != 0) {
                    $pag = new Factura_compra_pagos();
                    $pag->id_forma_pagos = $request->pagos[$a]["metodo_pago"];
                    $pag->plazo = 1;
                    $pag->unidad_tiempo = 'Días';
                    $pag->estado = 1;
                    $pag->fecha = $hoy;
                    $pag->id_factura_compra = $id;
                    if ($request->pagos[$a]["metodo_pago"] == 'Anticipo') {
                        $pag->anticipo = 1;
                    }
                    $pag->save();

                    $cxc = new Cuentaporpagar();
                    $cxc->num_cuota = 1;
                    $cxc->fecha_pago = $hoy;
                    $cxc->periodo_pagos = "Dias";
                    $cxc->valor_cuota = $request->pagos[$a]["cantidad_pago"];
                    $cxc->id_forma_pagos = $request->pagos[$a]["metodo_pago"];
                    if (isset($request->pagos[$a]["banco_pago"])) {
                        $cxc->banco = $request->pagos[$a]["banco_pago"];
                    }
                    if (isset($request->pagos[$a]["tarjeta"])) {
                        $cxc->numero_tarjeta = $request->pagos[$a]["tarjeta"];
                    }
                    if (isset($request->pagos[$a]["cuenta"])) {
                        $cxc->cuenta_contable = $request->pagos[$a]["cuenta"];
                    }
                    $cxc->valor_pagado = $request->pagos[$a]["cantidad_pago"];
                    $cxc->estado = 1;
                    $cxc->tipo = 2;
                    $cxc->id_factura_compra = $id;
                    $cxc->id_proveedor = $request->id_proveedor;
                    $cxc->save();
                }
            }
        }
        $fecharec = "";
        if ($request->vercreditos) {

            $pag = new Factura_compra_pagos();
            $pag->id_forma_pagos = $f_pagos[0]->id_forma_pagos;
            $pag->total = $request->monto;
            $pag->plazo = $request->plazos;
            $pag->unidad_tiempo = $request->periodo;
            $pag->estado = 1;
            $pag->fecha = $hoy;
            $pag->id_factura_compra = $id;
            $pag->save();

            $fd = "";
            for ($a = 0; $a < $request->plazos; $a++) {
                $cxc = new Cuentaporpagar();
                $cxc->num_cuota = $request->$a + 1;
                if ($a < 1) {
                    if ($request->periodo == "Años") {
                        $fecharec = $hoy->addYears($request->saltos_pagos);
                        $fd = $hoy->addYears($request->saltos_pagos)->format('Y-m-d');
                    } else if ($request->periodo == "Meses") {
                        $fecharec = $hoy->addMonths($request->saltos_pagos);
                        $fd = $hoy->addMonths($request->saltos_pagos)->format('Y-m-d');
                    } else if ($request->periodo == "Semanas") {
                        $fecharec = $hoy->addWeeks($request->saltos_pagos);
                        $fd = $hoy->addWeeks($request->saltos_pagos)->format('Y-m-d');
                    } else {
                        $fecharec = $hoy->addDays($request->saltos_pagos);
                        $fd = $hoy->addDays($request->saltos_pagos)->format('Y-m-d');
                    }
                } else {
                    if ($request->periodo == "Años") {
                        $fd = $fecharec->addYears($request->saltos_pagos)->format('Y-m-d');
                    } else if ($request->periodo == "Meses") {
                        $fd = $fecharec->addMonths($request->saltos_pagos)->format('Y-m-d');
                    } else if ($request->periodo == "Semanas") {
                        $fd = $fecharec->addWeeks($request->saltos_pagos)->format('Y-m-d');
                    } else {
                        $fd = $fecharec->addDays($request->saltos_pagos)->format('Y-m-d');
                    }
                }
                $cxc->fecha_pago = $fd;
                $cxc->periodo_pagos = $request->periodo;
                $cxc->valor_cuota = round($request->monto / $request->plazos, 2, PHP_ROUND_HALF_UP);
                $cxc->valor_pagado = 0;
                $cxc->estado = 1;
                $cxc->tipo = 1;
                $cxc->id_factura_compra = $id;
                $cxc->id_proveedor = $request->id_proveedor;
                $cxc->save();
            }
        }
        if ($request->verretencion) {
            for ($i = 0; $i < count($request->retenciones); $i++) {
                if ($request->retenciones[$i]["iva"] != null || $request->retenciones[$i]["renta"] != null) {
                    $ret = new RetencionFactura();
                    $ret->id_factura = $id;

                    $ret->id_retencion_iva = $request->retenciones[$i]["iva"]["id_retencion"];
                    $ret->id_retencion_renta = $request->retenciones[$i]["renta"]["id_retencion"];

                    $ret->porcentajeiva = $request->retenciones[$i]["porcentajeiva"];
                    $ret->cantidadiva = $request->retenciones[$i]["cantidadiva"];
                    $ret->baserenta = $request->retenciones[$i]["baserenta"];
                    $ret->porcentajerenta = $request->retenciones[$i]["porcentajerenta"];
                    $ret->cantidadrenta = $request->retenciones[$i]["cantidadrenta"];
                    $ret->save();
                }
            }
        }
        return FacturaCompra::select('factura_compra.*', 'empresa.*', 'proveedor.*', 'moneda.nomb_moneda as moneda', 'factura_compra.descuento as descuentototal', 'establecimiento.codigo as codigoes', 'punto_emision.codigo as codigope', 'establecimiento.direccion as direccion_establecimiento')
            ->join('empresa', 'empresa.id_empresa', '=', 'factura_compra.id_empresa')
            ->join('proveedor', 'proveedor.id_proveedor', '=', 'factura_compra.id_proveedor')
            ->join('establecimiento', 'establecimiento.id_empresa', '=', 'empresa.id_empresa')
            ->join('punto_emision', 'punto_emision.id_establecimiento', '=', 'establecimiento.id_establecimiento')
            ->join('moneda', 'moneda.id_moneda', '=', 'empresa.id_moneda')
            ->where("factura_compra.id_factcompra", "=", $id)
            ->orderByRaw('factura_compra.id_factcompra DESC')->get();
    }
    public function llamado_retencion($id)
    {
        return FacturaCompra::select('factura_compra.*', 'empresa.*', 'proveedor.*', 'moneda.nomb_moneda as moneda', 'factura_compra.descuento as descuentototal', 'establecimiento.codigo as codigoes', 'punto_emision.codigo as codigope', 'establecimiento.direccion as direccion_establecimiento')
            ->join('empresa', 'empresa.id_empresa', '=', 'factura_compra.id_empresa')
            ->join('proveedor', 'proveedor.id_proveedor', '=', 'factura_compra.id_proveedor')
            ->join('establecimiento', 'establecimiento.id_empresa', '=', 'empresa.id_empresa')
            ->join('punto_emision', 'punto_emision.id_establecimiento', '=', 'establecimiento.id_establecimiento')
            ->join('moneda', 'moneda.id_moneda', '=', 'empresa.id_moneda')
            ->where("factura_compra.id_factcompra", "=", $id)
            ->orderByRaw('factura_compra.id_factcompra DESC')->get();
    }
    public function guardarProducto(Request $request)
    {
        $idfact = $request->id_factura;
        for ($d = 0; $d < count($request->contenidopr); $d++) {
            $prod = new ProductoFactura();
            $prod->nombre = $request->contenidopr[$d]["nombre"];
            $prod->cantidad = $request->contenidopr[$d]["cantidad"];
            $prod->precio = $request->contenidopr[$d]["precio"];
            $prod->descuento = $request->contenidopr[$d]["descuento"];
            $prod->subtotal = $request->subtotal;
            $prod->id_factcompra = $idfact;
            $prod->id_producto = $request->contenidopr[$d]["id_producto"];
            $prod->iva = $request->contenidopr[$d]["iva"];
            $prod->save();
        }
    }
    public function guardarPago(Request $request)
    {
        $idfact = $request->id_factura;
        for ($a = 0; $a < count($request->valorpagos); $a++) {
            if ($request->valorpagos[$a]["metodo_pago"] != null && $request->valorpagos[$a]["cantidad_pago"] != 0) {
                $pagos = new Pagocompra();
                //, `id_ctacontable`
                $pagos->metodo_pago = $request->valorpagos[$a]["metodo_pago"];
                //$pagos->banco = $request->valorpagos[$a]["banco"];
                $pagos->tarjeta = $request->valorpagos[$a]["tarjeta"];
                //$pagos->cuenta_contable = $request->valorpagos[$a]["cuenta"];
                $pagos->cantidad_pago = $request->valorpagos[$a]["cantidad_pago"];
                $pagos->comentario_pago = $request->valorpagos[$a]["comentario_pago"];
                $pagos->id_factcompra = $idfact;
                $pagos->banco_pago = $request->valorpagos[$a]["banco_pago"];
                $pagos->id_empresa = $request->empresa;
                //$pagos->id_ctacontable=$request->valorpagos[$a]["cuenta"];
                $pagos->save();
            }
        }
    }
    public function guardarRetencion(Request $request)
    {
        /*
        $idfact=$request->id_factura;
        for($a=0; $a < count($request->valorretencion); $a++){
        $pagos = new RetencionFactura();
        //`retencion`, , ``, ``, `id_impuesto`, `id_factcompra`, `id_empresa`,
        $pagos->base = $request->valorretencion[$a]["base"];
        $pagos->porcentaje_fuente = $request->valorretencion[$a]["porcentaje_fuente"];
        $pagos->total_fuente = $request->valorretencion[$a]["total_fuente"];
        $pagos->id_retencion_fuente = $request->valorretencion[$a]["id_retencion_fuente"];
        //retencion iva
        /*$pagos->retencion = $request->valorretencion[$a]["base_iva"];
        $pagos->porcentaje_iva = $request->valorretencion[$a]["porcentaje_iva"];
        $pagos->total_iva = $request->valorretencion[$a]["total_iva"];
        $pagos->id_retencion_iva = $request->valorretencion[$a]["id_retencion_iva"];*/
        /*$pagos->id_factcompra = $idfact;
        $pagos->id_empresa = $request->empresa;
        $pagos->save();
        }*/
        //`retencion`, `base`, `porcentaje_iva`, `porcentaje_fuente`, `total_fuente`, `total_iva`, `id_retencion_fuente`, `id_retencion_iva`, `id_impuesto`, `id_factcompra`, `id_empresa`
        $id = $request->id_factura;
        for ($i = 0; $i < count($request->retencion); $i++) {
            if ($request->retencion[$i]["iva"] != null && $request->retencion[$i]["renta"] != null) {
                $ret = new RetencionFactura();
                $ret->base = $request->retencion[$i]["baserenta"];
                $ret->porcentaje_fuente = $request->retencion[$i]["porcentajerenta"];
                $ret->total_fuente = $request->retencion[$i]["cantidadrenta"];
                $ret->id_retencion_fuente = $request->retencion[$i]["renta"]["id_retencion"];
                //retencion iva
                //$ret->retencion = $request->retencion[$i]["base_iva"];
                $ret->porcentaje_iva = $request->retencion[$i]["porcentajeiva"];
                $ret->total_iva = $request->retencion[$i]["cantidadiva"];
                $ret->id_retencion_iva = $request->retencion[$i]["iva"]["id_retencion"];
                $ret->id_factcompra = $id;
                $ret->id_empresa = $request->empresa;
                $ret->save();
            }
        }
    }
    public function abrir(Request $request)
    {
        /*$id = $request->id;
        $recupera = DB::select('SELECT * FROM `factura_compra` WHERE id_factcompra='.$id);
        return $recupera;*/
        $id = $request->id;
        $recupera = FacturaCompra::addSelect([
            'nombre' => Proveedor::select('nombre_proveedor')
                ->whereColumn('id_proveedor', 'factura_compra.id_proveedor'),
            'provincias' => Proveedor::select('id_provincia')
                ->whereColumn('id_proveedor', 'factura_compra.id_proveedor'),
        ])
            ->where('id_factcompra', '=', $id)
            ->get();

        return $recupera;
    }
    public function abrirCredito($id)
    {
        $data = DB::select("SELECT * FROM `credito_compras` WHERE `id_factcompra` = " . $id);
        return $data;
    }
    public function update(Request $request)
    {
        $factcompra = FacturaCompra::find($request->id);
        $factcompra->nro_factprov = $request->nro_factprov;
        $factcompra->destino_pago = $request->destino_pago;
        $factcompra->fech_factcompra = $request->fech_factcompra;
        //$factcompra->especial=$request->especial;
        $factcompra->gasto_import = $request->gasto_import;
        $factcompra->documento_tributario = $request->documento_tributario;
        $factcompra->cotizacion = $request->cotizacion;
        $factcompra->descripcion = $request->descripcion;
        //$factcompra->tipo_iva=$request->tipo_iva;
        //$factcompra->unid_destino=$request->unid_destino;
        $factcompra->fech_emision = $request->fech_emision;
        $factcompra->fech_validez = $request->fech_validez;
        $factcompra->serie = $request->serie;
        $factcompra->nro_autorizacion = $request->nro_autorizacion;
        $factcompra->tipo_pago = $request->tipo_pago;
        $factcompra->pago_ant = $request->pago_ant;
        $factcompra->metodo_pago = $request->metodo_pago;
        $factcompra->efectivo = $request->efectivo;
        $factcompra->cant_efectivo = $request->cant_efectivo;
        $factcompra->caja_chica = $request->caja_chica;
        $factcompra->cant_caja = $request->cant_caja;
        $factcompra->nro_caja = $request->nro_caja;
        $factcompra->transaccion = $request->transaccion;
        $factcompra->tipo_transaccion = $request->tipo_transaccion;
        $factcompra->nro_transacion = $request->nro_transacion;
        $factcompra->fech_transacion = $request->fech_transacion;
        $factcompra->tarjeta = $request->tarjeta;
        $factcompra->nro_tarjeta = $request->nro_tarjeta;
        $factcompra->cant_tarjeta = $request->cant_tarjeta;
        $factcompra->nro_pago = $request->nro_pago;
        $factcompra->plazo_pago = $request->plazo_pago;
        $factcompra->dias_pago_empresa = $request->dias_pago_empresa;
        $factcompra->total_neto = $request->total_neto;
        $factcompra->base_12 = $request->base_12;
        $factcompra->base_0 = $request->base_0;
        $factcompra->tarifa_no_graba = $request->tarifa_no_graba;
        $factcompra->otro_imp = $request->otro_imp;
        $factcompra->iva = $request->iva;
        $factcompra->total_factura = $request->total_factura;
        $factcompra->modo_orden = 0;
        $factcompra->facturado_orden = 1;
        //$factcompra->compensacion=$request->compensacion;
        $factcompra->id_sustento = $request->id_sustento;
        $factcompra->id_proveedor = $request->id_proveedor;
        $factcompra->id_moneda = $request->id_moneda;
        $factcompra->id_provincia = $request->id_provincia;
        $factcompra->id_cliente_asoc = $request->id_cliente_asoc;
        //$factcompra->id_producto_fact=$request->id_producto_fact;
        $factcompra->id_retiva = $request->id_retiva;
        $factcompra->id_retfuente = $request->id_retfuente;
        $factcompra->id_caja = $request->id_caja;
        $factcompra->id_banco = $request->id_banco;
        $factcompra->id_importacion = $request->id_importacion;
        $factcompra->save();

        $idfact = $factcompra->id_factcompra;
        return $idfact;
    }
    public function actProducto(Request $request)
    {
        $idfact = $request->id_factura;
        //$prod=ProductoFactura::where('id_factcompra','=',$idfact)->get();

        for ($d = 0; $d < count($request->contenidopr); $d++) {
            //$prod=ProductoFactura::fill($request->all());
            $prod = ProductoFactura::updateOrCreate(
                ['nombre' => $request->contenidopr[$d]["nombre"]],
                ['cantidad' => $request->contenidopr[$d]["cantidad"]],
                ['precio' => $request->contenidopr[$d]["precio"]],
                ['descuento' => $request->contenidopr[$d]["descuento"]],
                ['id_producto' => $request->contenidopr[$d]["id_producto"]]
            );
            /*$prod->nombre=$request->contenidopr[$d]["nombre"];
            $prod->cantidad=$request->contenidopr[$d]["cantidad"];
            $prod->precio=$request->contenidopr[$d]["precio"];
            $prod->descuento=$request->contenidopr[$d]["descuento"];
            $prod->subtotal=$request->subtotal;
            $prod->id_factcompra=$idfact;
            $prod->id_producto=$request->contenidopr[$d]["id_producto"];*/

            //$prod->save();
            /*DB::table('producto_factura')
        ->where('id_factcompra', $idfact)
        ->update(array( 'nombre' => $request->contenidopr[$d]["nombre"],
        'cantidad'   => $request->contenidopr[$d]["cantidad"],
        'precio'     => $request->contenidopr[$d]["precio"],
        'descuento'  => $request->contenidopr[$d]["descuento"],
        'id_producto'=> $request->contenidopr[$d]["id_producto"]
        ));*/
        }
        return $prod;
    }
    public function actPago(Request $request)
    {
        $idfact = $request->id_factura;
        //$prod=ProductoFactura::where('id_factcompra','=',$idfact)->get();
        for ($d = 0; $d < count($request->contenidopr); $d++) {
            //$prod=ProductoFactura::fill($request->all());
            $prod = Pagocompra::updateOrCreate(
                ['metodo_pago' => $request->contenidopr[$d]["metodo_pago"]],
                ['cantidad_pago' => $request->contenidopr[$d]["cantidad"]],
                ['descuento' => $request->contenidopr[$d]["descuento"]],
                ['id_producto' => $request->contenidopr[$d]["id_producto"]]
            );
        }
    }
    public function listarProduct(Request $request, $id)
    {
        //$produc=ProductoFactura::where('id_factcompra','=',3);
        //return $produc;
        $idfactura = $request->idfactura;
        $buscar = $request->buscar;
        $cantidadp = $request->cantidadp;
        if ($cantidadp < 1) {
            $cantidadp = 10;
        }
        if ($buscar == '') {
            //$impuestos = Impuesto::paginate($cantidadp);
            $recupera = Detalle_factura_compra::select('*')
                ->where('id_factura', '=', $id)
                ->orderByRaw('id_detalle_factura_compra ASC')->paginate($cantidadp);
        } else {
            $recupera = Detalle_factura_compra::select('*')
                ->where(function ($q) use ($buscar) {
                    $q->where('nombre', '=', $buscar);
                })
                ->where('id_factura', '=', $idfactura)
                ->orderByRaw('id_detalle_factura_compra ASC')->paginate($cantidadp);
        }

        return [
            'pagination' => [
                'total' => $recupera->total(),
                'current_page' => $recupera->currentPage(),
                'per_page' => $recupera->perPage(),
                'last_page' => $recupera->lastPage(),
                'from' => $recupera->firstItem(),
                'to' => $recupera->lastItem(),
                'count' => ceil($recupera->total() / $cantidadp),
            ],
            'recupera' => $recupera,
        ];
    }
    public function listPagos(Request $request, $id)
    {
        $idfactura = $request->idfactura;
        $buscar = $request->buscar;
        $cantidadp = $request->cantidadp;
        if ($cantidadp < 1) {
            $cantidadp = 10;
        }
        if ($buscar == '') {
            //$impuestos = Impuesto::paginate($cantidadp);
            $recupera = Pagocompra::select('*')
                ->where('id_factcompra', '=', $id)
                ->orderByRaw('id_pagfactcompra ASC')->paginate($cantidadp);
        } else {
            $recupera = Pagocompra::select('*')
                ->where(function ($q) use ($buscar) {
                    $q->where('metodo_pago', '=', $buscar);
                })
                ->where('id_factcompra', '=', $idfactura)
                ->orderByRaw('id_pagfactcompra ASC')->paginate($cantidadp);
        }
        return [
            'pagination' => [
                'total' => $recupera->total(),
                'current_page' => $recupera->currentPage(),
                'per_page' => $recupera->perPage(),
                'last_page' => $recupera->lastPage(),
                'from' => $recupera->firstItem(),
                'to' => $recupera->lastItem(),
                'count' => ceil($recupera->total() / $cantidadp),
            ],
            'recupera' => $recupera,
        ];
    }
    public function eliminar(Request $rq)
    {
        $factura = $rq->datos["id_factcompra"];
        $id_empresa=$rq->datos["id_empresa"];
        $nro_factura=$rq->datos["descripcion"];

        $bodega_ingreso= DB::select("SELECT * FROM bodega_ingreso WHERE id_factura_compra = $factura");
        $productos=[];
        if(count($bodega_ingreso)>0){
            $productos= DB::select("SELECT bodega_ingreso_detalle.*,bodega_ingreso.id_bodega from bodega_ingreso_detalle INNER JOIN bodega_ingreso ON bodega_ingreso.id_bodega_ingreso=bodega_ingreso_detalle.id_bodega_ingreso where bodega_ingreso_detalle.id_bodega_ingreso= {$bodega_ingreso[0]->id_bodega_ingreso}");
        }
        if(count($productos)>0){
            self::CabeceraBodegaEgreso($factura,$nro_factura,[]);
            // $numegre = DB::select("SELECT num_egreso FROM bodega_egreso  WHERE id_empresa = $id_empresa ORDER BY  num_egreso DESC LIMIT 1;");
            // $numeroegreso = "";
            // //si existe la bodega_ingreso cuenta el ultimo num_ingreso del registro caso contrario comienza el contador desde 1
            // if (count($numegre) == 1) {
            //     $dato = $numegre[0]->num_egreso;
            //     $tot = $dato + 1;
            //     $numeroegreso = $tot;
            // } else {
            //     $numeroegreso = 1;
            // }
            // for ($i = 0; $i < count($productos); $i++) {
            //     $cantidad = $productos[$i]->cantidad;
            //     $precio = $productos[$i]->costo_unitario;
            //     $total= $productos[$i]->costo_total;
            //     $id_producto_bodega = $productos[$i]->id_bodega;
            //     if (isset($id_producto_bodega) && $id_producto_bodega) {
            //         DB::update("UPDATE producto_bodega SET cantidad = cantidad - $cantidad,costo_total=costo_total-$total,costo_unitario=costo_total/cantidad WHERE id_producto = {$productos[$i]->id_producto} and id_bodega={$id_producto_bodega}");
            //     }
            // }
        }
        
        //DB::delete("DELETE FROM bodega_ingreso WHERE id_factura_compra = $factura");
        Detalle_factura_compra::where('id_factura', '=', $factura)->delete();
        Cuentaporpagar::where('id_factura_compra', '=', $factura)->delete();
        RetencionFactura::where('id_factura', '=', $factura)->delete();
        FacturaCompra::destroy($factura);


        $res = DB::select("SELECT * FROM ctas_pagar_pagos WHERE referencia LIKE '%$factura%'");
        $datos = new \ArrayObject();
        for ($f = 0; $f < count($res); $f++) {
            $ref = explode(";", $res[$f]->referencia);
            for ($i = 0; $i < count($ref); $i++) {
                if ($i % 4 == 3) {
                    if ($ref[$i] == 790) {
                        $val3 = $ref[$i - 3];
                        $val2 = $ref[$i - 2];
                        $val1 = $ref[$i - 1];
                        $val = $ref[$i];
                        $datos->append("$val3;$val2;$val1;$val");
                    }
                }
            }
        }
        foreach ($datos as $rs) {
            $revisarid = DB::select("SELECT * FROM ctas_pagar_pagos WHERE referencia like '%$rs%'");
            $id = $revisarid[0]->id_ctas_pagar_pagos;

            DB::update("UPDATE ctas_pagar_pagos SET referencia = replace(referencia, '$rs', '') WHERE referencia like '%$rs%'");

            $revisarids = DB::select("SELECT * FROM ctas_pagar_pagos WHERE id_ctas_pagar_pagos = $id");
            $reff = $revisarids[0]->referencia;

            if ($reff == "") {
                DB::delete("DELETE FROM ctas_pagar_pagos WHERE id_ctas_pagar_pagos = $id");
            }
        }
    }
    public function CabeceraBodegaEgreso($id,$nro,$lotes){
        $hoy = Carbon::now();
        $factura = DB::select("SELECT * from factura_compra where id_factcompra=$id");
        $detalle = DB::select("SELECT * FROM bodega_ingreso where id_factura_compra=$id");
        $proyecto = DB::select("SELECT * from proyecto where id_empresa={$factura[0]->id_empresa}");
        if (count($detalle) > 0) {
            for ($a = 0; $a < count($detalle); $a++) {
                $numegre = DB::select("SELECT num_egreso FROM bodega_egreso  WHERE id_empresa = {$factura[0]->id_empresa} ORDER BY  num_egreso DESC LIMIT 1");
                $numeroingreso = "";
                if (count($numegre) == 1) {
                    $dato = $numegre[0]->num_egreso;
                    $tot = $dato + 1;
                    $numeroingreso = $tot;
                } else {
                    $numeroingreso = 1;
                }
                $egreso = new BodegaEgreso();
                $egreso->num_egreso = $numeroingreso;
                $egreso->fecha_egreso = $hoy;
                $egreso->tipo_egreso = 'Egreso de Factura Compra';
                $egreso->observ_egreso = 'Eliminacion Factura Compra: ' . $factura[0]->descripcion;
                $egreso->id_empresa = $factura[0]->id_empresa;
                $egreso->id_bodega = $detalle[$a]->id_bodega;
                $egreso->id_factura_compra = $id;
                $egreso->save();
            }
        }
        if (count($detalle) > 0) {
            //self::DetalleBodegaIngreso($id, $ingreso->fecha_ingreso, $nro_factura,$productos);
            self::ProductoBodegaEgreso($id, $egreso->fecha_egreso, $factura[0]->descripcion,[]);
        }
    }
    public function ProductoBodegaEgreso($id, $fecha_ingreso, $nro_factura,$productos){

        $bodega_ingreso= DB::select("SELECT * FROM bodega_ingreso WHERE id_factura_compra = $id");
        $productos=[];
        if(count($bodega_ingreso)>0){
            $productos= DB::select("SELECT bodega_ingreso_detalle.*,bodega_ingreso.id_bodega from bodega_ingreso_detalle INNER JOIN bodega_ingreso ON bodega_ingreso.id_bodega_ingreso=bodega_ingreso_detalle.id_bodega_ingreso where bodega_ingreso_detalle.id_bodega_ingreso= {$bodega_ingreso[0]->id_bodega_ingreso}");
        }
        if(count($productos)>0){
            for ($i = 0; $i < count($productos); $i++) {
                $cantidad = $productos[$i]->cantidad;
                $precio = $productos[$i]->costo_unitario;
                $total= $productos[$i]->costo_total;
                $id_producto_bodega = $productos[$i]->id_bodega;
                if (isset($id_producto_bodega) && $id_producto_bodega) {
                    DB::update("UPDATE producto_bodega SET cantidad = cantidad - $cantidad,costo_total=costo_total-$total,costo_unitario=costo_total/cantidad WHERE id_producto = {$productos[$i]->id_producto} and id_bodega={$id_producto_bodega}");
                }
            }
        }

        if(count($productos) > 0){
            self::DetalleBodegaEgreso($id, $fecha_ingreso, $nro_factura,$productos);
        }
    }
    public function DetalleBodegaEgreso($id, $fecha, $nro_factura,$productos)
    {
        $hoy = Carbon::now();
        $detalle = DB::select("SELECT * FROM bodega_ingreso_detalle,bodega_ingreso where bodega_ingreso.id_bodega_ingreso=bodega_ingreso_detalle.id_bodega_ingreso and id_factura_compra=$id");
        if (count($detalle) > 0) {
            for ($a = 0; $a < count($detalle); $a++) {
                //if($detalle[$a]->sector==1){
                    //$observ= 'Factura Compra: ' . $nro_factura;
                    $bodega_ingreso = DB::select("SELECT * from bodega_egreso where id_bodega={$detalle[$a]->id_bodega} and id_factura_compra=$id");
                    
                    $costo_unit=$detalle[$a]->costo_unitario;
                    
                    $bed = new BodegaEgresoDetalle();
                    $bed->cantidad = $detalle[$a]->cantidad;
                    $bed->costo_unitario = $costo_unit;
                    $bed->costo_total = $bed->cantidad * $bed->costo_unitario;

                    $bed->id_bodega_egreso = $bodega_ingreso[0]->id_bodega_egreso;
                    $bed->id_producto = $detalle[$a]->id_producto;
                    $bed->id_detalle_factura_compra = $detalle[$a]->id_detalle_factura_compra;
                    $bed->id_proyecto = $detalle[$a]->id_proyecto;
                    $bed->save();
                    //ingreso ProductoBodegaLotes
                    // $id_bi=$bodega_ingreso[0]->id_bodega_ingreso;
                    // $id_bid=$bed->id_bodega_ingreso_detalle;
                    // $id_producto=$detalle[$a]->id_producto;
                    // $id_producto_bodega=$detalle[$a]->id_producto_bodega;
                    // for ($i = 0; $i < count($productos); $i++) {
                    //     if($productos[$i]["id_producto"]==$detalle[$a]->id_producto && $productos[$i]["id_proyecto"]==$detalle[$a]->id_proyecto && $productos[$i]["id_bodega"]==$detalle[$a]->id_bodega){
                    //         $pbl = new ProductoBodegaLotes();
                    //         $pbl->nombre = $productos[$i]["lote"];
                    //         $pbl->cantidad_original = $productos[$i]["cantidad"];
                    //         $pbl->cantidad_real = $productos[$i]["cantidad"];
                    //         $pbl->fecha_fabricacion = $productos[$i]["fecha_fabricacion"];
                    //         $pbl->fecha_vencimiento = $productos[$i]["fecha_vencimiento"];
                    //         $pbl->id_producto=$detalle[$a]->id_producto;
                    //         $pbl->id_producto_bodega=$detalle[$a]->id_producto_bodega;
                    //         $pbl->id_bodega_ingreso=$bodega_ingreso[0]->id_bodega_ingreso;
                    //         $pbl->id_bodega_ingreso_detalle=$id_bid;
                    //         $pbl->save();
                    //     }
                        
                    // }
                    
            }
            
            
        }

        //DB::delete("DELETE FROM bodega_ingreso WHERE id_factura_compra = $id");

    }
    
    public function getSustento(Request $request)
    {
        $data = Tiposustento::select("*")->where("id_empresa", "=", $request->empresa)->get();
        return $data;
    }
    public function getProvincia()
    {
        $data = Provincia::all();
        return $data;
    }
    public function getImportacion(Request $request)
    {
        //$request->empresa
        $data = DB::select("SELECT *,(select max(nombre) from proveedor_importacion where id_importacion=importacion.id_importacion limit 1) as nombre_proveedor from importacion where id_empresa={$request->empresa} and estado<>'Liquidado'");
        return $data;
    }
    public function getImportacionEditar(Request $request)
    {
        //$request->empresa
        $data = DB::select("SELECT *,(select max(nombre) from proveedor_importacion where id_importacion=importacion.id_importacion limit 1) as nombre_proveedor from importacion where id_empresa={$request->empresa}");
        return $data;
    }
    public function getPtoemision()
    {
        $data = Ptoemision::all();
        return $data;
    }
    public function getCliente()
    {
        $data = Cliente::all();
        return $data;
    }
    public function getMoneda()
    {
        $data = Moneda::all();
        return $data;
    }
    public function getRetencionFuente()
    {
        $termino = "Retencion Fuente Compras";
        $data = DB::select("SELECT * FROM `retencion` WHERE `tipo_retencion` = '" . $termino . "'");

        return $data;
    }
    public function getRetencionIva()
    {
        $termino = "Retencion IVA Compras";
        $data = DB::select("SELECT * FROM `retencion` WHERE `tipo_retencion` = '" . $termino . "'");

        return $data;
    }
    public function getPorcentaje(Request $request)
    {
        $id = $request->id_retfuente;
        $data = DB::select("SELECT `porcen_retencion` FROM `retencion` WHERE `id_retencion` = " . $id);

        return $data;
    }
    public function getPorcentajeIva(Request $request)
    {
        $id = $request->id_retiva;
        $data = DB::select("SELECT `porcen_retencion` FROM `retencion` WHERE `id_retencion` = " . $id);

        return $data;
    }
    public function getCaja()
    {
        // $id=$request->id_retiva;
        $data = DB::select("SELECT * FROM `caja` WHERE `id_empresa` = 1");

        return $data;
    }
    public function getBanco()
    {
        $data = DB::select("SELECT * FROM `banco`");
        return $data;
    }
    public function getProveedores()
    {
        $data = Proveedor::get();

        return response()->json($data);
    }
    public function getRucProveedores()
    {
        $data = Proveedor::get();

        return response()->json($data);
    }
    public function generarPdf(Request $request)
    {
        $empresa = DB::select("SELECT * FROM empresa where id_empresa=" . $request->id_empresa);
        $fact_compra = DB::select("SELECT factura_compra.*,proveedor.nombre_proveedor,proveedor.direccion_prov,proveedor.identif_proveedor,proveedor.tipo_identificacion,proveedor.email,proveedor.telefono_prov FROM factura_compra,proveedor where factura_compra.id_proveedor=proveedor.id_proveedor and id_factcompra=" . $request->id_fact);
        $detalle_factura = DB::select("SELECT * from retencion_factura_comp,factura_compra where factura_compra.id_factcompra=retencion_factura_comp.id_factura and id_factura=" . $request->id_fact);
        $Reportes = new generarReportes();
        if ($request->destinatario == null && $request->email == null) {
            $strPDF = $Reportes->PdfRetencionFacturaCompra($empresa[0], $fact_compra[0], $detalle_factura);
            return response($strPDF, 200)->header('Content-Type', 'application/pdf');
        } else {
            $carpetanombre2 = constant("DATA_EMPRESA") . $request->id_empresa;
            $carpeta1 = $carpetanombre2 . "/comprobantes/retencioncompra";
            if (!file_exists($carpeta1)) {
                mkdir($carpeta1, 0755, true);
            }
            $strPDF = $Reportes->PdfRetencionFacturaCompra($empresa[0], $fact_compra[0], $detalle_factura, $carpeta1);
            return response($strPDF, 200)->header('Content-Type', 'application/pdf');
        }
    }
    public function comprastotales(Request $request)
    {
        $queries = [];
        $inners = [];
        $fields = [];
        $queret = [];
        $initial = null;
        $final = null;
        if ($request->dates) {
            $info_date = json_decode($request->dates, true);
            if ($request->currentDate !== "true") {
                $initial = str_replace("-010-", "-10-", $info_date["range"]["initial"]);
                $final = str_replace("-010-", "-10-", $info_date["range"]["final"]);
                if ($info_date["option"] == 1) {
                    array_push($queries, "date(f.fech_emision) between date('{$initial}') and date('{$final}')\n");
                    array_push($queret, "date(f.fech_emision) between date('{$initial}') and date('{$final}')\n");
                }
                if ($info_date["option"] == 2) {
                    array_push($queries, "date(f.fech_validez) between date('{$initial}') and date('{$final}')\n");
                    array_push($queret, "date(f.fech_validez) between date('{$initial}') and date('{$final}')\n");
                }
                if ($info_date["option"] == 3) {
                    array_push($queries, "date(f.fech_emision) between date('{$initial}') and date('{$final}')\n");
                    array_push($queret, "date(f.fech_emision) between date('{$initial}') and date('{$final}')\n");
                }
            } else {
                $initial = str_replace("-010-", "-10-", $info_date["value"]);
                $final = str_replace("-010-", "-10-", $info_date["value"]);
                if ($info_date["option"] == 1) {
                    array_push($queries, "date(f.fech_emision) = date('{$final}')\n");
                    array_push($queret, "date(f.fech_emision) = date('{$final}')\n");
                }
                if ($info_date["option"] == 2) {
                    array_push($queries, "date(f.fech_emision) = date('{$final}')\n");
                    array_push($queret, "date(f.fech_emision) = date('{$final}')\n");
                }
                if ($info_date["option"] == 3) {
                    array_push($queries, "date(f.fech_emision) = date('{$final}')\n");
                    array_push($queret, "date(f.fech_emision) = date('{$final}')\n");
                }
            }
        }
        if ($request->establishment) {
            $info_establishment = json_decode($request->establishment, true);
            if ($info_establishment["id"] != 0) {
                array_push($queries, "f.id_establecimiento = {$info_establishment["id"]}\n");
                array_push($queret, "f.id_establecimiento = {$info_establishment["id"]}\n");
            }
        }
        if ($request->pointOfEmission) {
            $info_point_emission = json_decode($request->pointOfEmission, true);
            if ($info_point_emission["id"] != 0) {
                array_push($queries, "f.id_punto_emision = {$info_point_emission["id"]}\n");
                array_push($queret, "f.id_punto_emision = {$info_point_emission["id"]}\n");
            }
        }
        if ($request->project) {
            $info_project = json_decode($request->project, true);
            if ($info_project["id"] != 0) {
                array_push($queries, "df.id_proyecto = {$info_project["id"]}\n");
            }
        }
        if ($request->provider) {
            $info_provider = json_decode($request->provider, true);
            if ($info_provider["id"] != 0) {
                array_push($queries, "f.id_proveedor = {$info_provider["id"]}\n");
                array_push($queret, "f.id_proveedor = {$info_provider["id"]}\n");
            }
        }
        if ($request->rol_user == 2) {
            array_push($queries, "f.id_user = {$request->id_user}\n");
            array_push($queret, "f.id_user = {$request->id_user}\n");
        } else {
            if ($request->user) {
                $info_user = json_decode($request->user, true);
                if ($info_user["id"] != 0) {
                    array_push($queries, "f.id_user = {$info_user["id"]}\n");
                    array_push($queret, "f.id_user = {$info_user["id"]}\n");
                }
            }
        }
        $nombre_producto = '';
        if ($request->products) {
            $to_array = function ($product) {

                $new_product = json_decode($product);

                return $new_product->id;
            };
            //$new_product = json_decode($request->products);
            //dd($new_product->id);
            // if($new_product->id!==0){
            //     array_push($queries, "df.id_producto in ({$info_products})\n");
            // }
            //dd($request->products);
            if (gettype($request->products) !== 'string') {
                if (count($request->products) == 1) {
                    $to_array_2 = function ($product) {
                        $new_product = json_decode($product);
                        return $new_product->nombre;
                    };
                    $info_products_2 = implode(",", array_map($to_array_2, $request->products));
                    $nombre_producto = $info_products_2;
                }

                $info_products = implode(",", array_map($to_array, $request->products));
                //dd($info_products);
                if ($info_products !== "0") {
                    array_push($queries, "df.id_producto in ({$info_products})\n");
                }
            } else {
                //dd("es string");
            }
        }
        if ($request->presentacion) {
            $info_presentacion = json_decode($request->presentacion);
            if ($info_presentacion->id != 0) {
                array_push($queries, "pr.id_presentacion={$info_presentacion->id}\n");
            }
        }
        if ($request->model) {
            $info_model = json_decode($request->model);
            if ($info_model->id != 0) {
                array_push($queries, "md.id_modelo={$info_model->id}\n");
            }
        }
        if ($request->marca) {
            $info_marca = json_decode($request->marca);
            if ($info_marca->id != 0) {
                array_push($queries, "mc.id_marca={$info_marca->id}\n");
            }
        }
        if ($request->tipo_producto) {
            $info_tipo_producto = json_decode($request->tipo_producto);
            if ($info_tipo_producto->id != 0) {
                array_push($queries, "tp.id_tipo_producto={$info_tipo_producto->id}\n");
            }
        }
        if ($request->linea_producto) {
            $info_linea_producto = json_decode($request->linea_producto);
            if ($info_linea_producto->id != 0) {
                array_push($queries, "lp.id_linea_producto={$info_linea_producto->id}\n");
            }
        }
        if ($request->wayToPay) {
            $info_payment = json_decode($request->wayToPay, true);
            if ($info_payment["id"] != 0) {
                array_push($queries, "f.id_forma_pagos = {$info_payment["id"]}\n");
                array_push($queret, "f.id_forma_pagos = {$info_payment["id"]}\n");
            }
        }
        if ($request->invoice) {
            $info_invoice = json_decode($request->invoice);
            if ($info_invoice->all == false) {
                if ($info_invoice->retention) {
                    array_push($inners, "INNER JOIN retencion_factura_comp r ON r.id_factura = f.id_factcompra\n");
                    array_push($fields, "r.cantidadiva,\n");
                    array_push($fields, "r.cantidadrenta,\n");
                    array_push($fields, "if(r.id_factura,'si','no') as retencion,\n");
                } else {
                    array_push($inners, "LEFT JOIN retencion_factura_comp r ON r.id_factura = f.id_factcompra\n");
                    array_push($fields, "r.cantidadiva,\n");
                    array_push($fields, "r.cantidadrenta,\n");
                    array_push($fields, "if(r.id_factura,'si','no') as retencion,\n");
                }
                if ($info_invoice->credit) {
                    array_push($inners, "INNER JOIN factura_compra_pagos cr ON f.id_factcompra = cr.id_factura_compra\n");
                } else {
                    array_push($inners, "LEFT JOIN factura_compra_pagos cr ON f.id_factcompra = cr.id_factura_compra\n");
                }
            } else {
                array_push($inners, "LEFT JOIN factura_compra_pagos cr ON f.id_factcompra = cr.id_factura_compra\n");
                array_push($inners, "LEFT JOIN retencion_factura_comp r ON r.id_factura = f.id_factcompra\n");
                array_push($fields, "r.cantidadiva,\n");
                array_push($fields, "r.cantidadrenta,\n");
                array_push($fields, "if(r.id_factura,'si','no') as retencion,\n");
            }
            if ($info_invoice->typeSearch == 1) {
                $typeSearch = ">=";
            }
            if ($info_invoice->typeSearch == 0) {
                $typeSearch = "=";
            }
            if ($info_invoice->typeSearch == -1) {
                $typeSearch = "<=";
            }
            if (is_numeric($info_invoice->totalCount) && $info_invoice->typeSearch != 2) {
                $info_invoice->totalCount = intval($info_invoice->totalCount);
                array_push($queries, "f.total_factura {$typeSearch} {$info_invoice->totalCount}\n");
                array_push($queret, "f.total_factura {$typeSearch} {$info_invoice->totalCount}\n");
            }
            if (!$info_invoice->allType) {
                if ($info_invoice->taxDocument) {
                    array_push($queries, "f.documento_tributario = 1\n");
                    array_push($queret, "f.documento_tributario = 1\n");
                } else {
                    array_push($queries, "f.documento_tributario = 0\n");
                    array_push($queret, "f.documento_tributario = 0\n");
                }
                if ($info_invoice->importCosts) {
                    array_push($queries, "f.gasto_import = 1\n");
                    array_push($queret, "f.gasto_import = 1\n");
                } else {
                    array_push($queries, "f.gasto_import = 0\n");
                    array_push($queret, "f.gasto_import = 0\n");
                }
            }
        }
        $queries = implode(" and ", $queries);
        $queret = implode(" and ", $queret);
        $inners = implode("", $inners);
        $fields = implode("", $fields);
        $query = "
        SELECT
            f.id_factcompra,
            f.fech_emision,
            f.documento_tributario,
            f.observacion,
            f.respuesta,
            f.subtotal_0,
            f.subtotal_12,
            f.subtotal_no_obj_iva,
            f.descuento,
            f.iva_12,
            f.total_factura,
            f.descripcion,
            tc.descrip_tipcomprob,
            {$fields}
            p.identif_proveedor,

            p.nombre_proveedor,
            e.id_empresa,
            e.nombre_empresa,
            e.logo
        FROM factura_compra f
        INNER JOIN empresa e
            ON e.id_empresa = f.id_empresa
        INNER JOIN proveedor p
            ON p.id_proveedor = f.id_proveedor
        INNER JOIN detalle_factura_compra df
            on df.id_factura = f.id_factcompra
        inner join producto prod
        on df.id_producto = prod.id_producto
        left join linea_producto as lp
        on lp.id_linea_producto=prod.id_linea_producto
        left join tipo_producto as tp
        on tp.id_tipo_producto=prod.id_tipo_producto
        left join marca as mc
        on mc.id_marca=prod.id_marca
        left join modelo as md
        on md.id_modelo=prod.id_modelo
        left join presentacion as pr
        on pr.id_presentacion=prod.id_presentacion
        LEFT JOIN tipo_comprobante tc
            ON tc.id_tipcomprobante = f.id_tipo_comprobante
        {$inners}
        WHERE f.id_empresa = {$request->company} and f.modo_orden<=0 and
        {$queries} ORDER BY f.fech_emision asc;
        ";
        $retenciones = DB::select("SELECT sum(cantidadiva) as cantidad_iva,sum(cantidadrenta) as cantidad_irf,f.id_factcompra 
        from retencion_factura_comp 
		INNER JOIN factura_compra as f
        on retencion_factura_comp.id_factura=f.id_factcompra
        WHERE f.id_empresa = {$request->company} and
        {$queret}
        GROUP BY f.id_factcompra");

        //dd($query);
        $reporte = DB::select($query);

        if (!$reporte) {
            return response('no-data-report', 200)->header('Content-Type', 'application/json');
        } else {
            $Reportes = new generarReportes();
            $strPDF = $Reportes->factura_compra_reporte($reporte, $initial, $final, $retenciones);
            return response($strPDF, 200)->header('Content-Type', 'application/pdf');
        }
    }
    public function facturaCompraContabilizar(Request $request, $id)
    {
        $empresa = DB::select("SELECT empresa.*,proveedor.nombre_proveedor as nombre,proveedor.identif_proveedor as identificacion,proveedor.tipo_identificacion from empresa,factura_compra,proveedor where proveedor.id_empresa=empresa.id_empresa and factura_compra.id_proveedor=proveedor.id_proveedor and factura_compra.id_empresa=empresa.id_empresa and factura_compra.id_factcompra=" . $id);
        $proyecto = DB::select("SELECT * from proyecto where id_empresa={$request->id_empresa}");
        $factura = DB::select("SELECT f.*, pr.descripcion as des_proyecto FROM factura_compra f LEFT JOIN proyecto pr ON f.id_proyecto=pr.id_proyecto WHERE f.id_factcompra =" . $id);
        $renta_retencion_asiento = DB::select("SELECT retencion.id_plan_cuentas,detalle_factura_compra.total,round(detalle_factura_compra.total/factura_compra.subtotal_sin_impuesto,2) as porcentaje,retencion_factura_comp.baserenta,
        retencion_factura_comp.porcentajerenta,retencion_factura_comp.cantidadrenta,round(retencion_factura_comp.cantidadrenta*(detalle_factura_compra.total/factura_compra.subtotal_sin_impuesto),2) as haber,null as debe,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,detalle_factura_compra.id_proyecto,proyecto.descripcion,(select sum(cantidadrenta) from retencion_factura_comp where id_factura={$id}) as total_renta,detalle_factura_compra.id_detalle_factura_compra as id_detalle
                FROM retencion_factura_comp
                INNER JOIN retencion
                ON retencion.id_retencion=retencion_factura_comp.id_retencion_renta
                INNER JOIN factura_compra
                on factura_compra.id_factcompra=retencion_factura_comp.id_factura
                INNER JOIN detalle_factura_compra
                on detalle_factura_compra.id_factura=factura_compra.id_factcompra
                left JOIN plan_cuentas
                on plan_cuentas.id_plan_cuentas=retencion.id_plan_cuentas
                LEFT JOIN proyecto
                on proyecto.id_proyecto=detalle_factura_compra.id_proyecto
                where factura_compra.id_factcompra={$id} and factura_compra.id_empresa={$request->id_empresa}
        ORDER BY detalle_factura_compra.id_proyecto");
        $iva_retencion_asiento = DB::select(
            "SELECT retencion.id_plan_cuentas,detalle_factura_compra.total,round(detalle_factura_compra.total/factura_compra.subtotal_sin_impuesto,2) as porcentaje,
                            retencion_factura_comp.porcentajeiva,retencion_factura_comp.cantidadiva,round((retencion_factura_comp.cantidadiva)*(detalle_factura_compra.total/factura_compra.subtotal_sin_impuesto),2) as haber,null as debe,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,detalle_factura_compra.id_proyecto,proyecto.descripcion,(select sum(cantidadiva) from retencion_factura_comp where id_factura={$id}) as total_iva,0 as acumula,detalle_factura_compra.id_detalle_factura_compra as id_detalle
                                    FROM retencion_factura_comp
                                    INNER JOIN retencion
                                    ON retencion.id_retencion=retencion_factura_comp.id_retencion_iva
                                    INNER JOIN factura_compra
                                    on factura_compra.id_factcompra=retencion_factura_comp.id_factura
                                    INNER JOIN detalle_factura_compra
                                    on detalle_factura_compra.id_factura=factura_compra.id_factcompra
                                    left JOIN plan_cuentas
                                    on plan_cuentas.id_plan_cuentas=retencion.id_plan_cuentas
                                    LEFT JOIN proyecto
                                    on proyecto.id_proyecto=detalle_factura_compra.id_proyecto
                                    where factura_compra.id_factcompra={$id} and factura_compra.id_empresa={$request->id_empresa}
                            ORDER BY detalle_factura_compra.id_proyecto"
        );
        $query = "SELECT round(sum(factura_compra_pagos.total)/count(factura_compra_pagos.id_factura_compra_pagos),2) as total,round(sum(detalle_factura_compra.total)/max(factura_compra.subtotal_sin_impuesto),2) as porcentaje,sum(detalle_factura_compra.total) as valor_producto,round((sum(detalle_factura_compra.total)/max(factura_compra.subtotal_sin_impuesto)*(sum(factura_compra_pagos.total)/count(factura_compra_pagos.id_factura_compra_pagos))),2) as haber,null as debe,detalle_factura_compra.id_proyecto,proyecto.descripcion,plan_cuentas.id_plan_cuentas as id_plan_cuenta_grupo_prov,concat(max(plan_cuentas.codcta),'-',max(plan_cuentas.nomcta)) as nombre_cuenta_grupo_prov,if(proveedor.id_plan_cuentas is null,'no','si') as exist_plan_cuenta_prov,(select id_plan_cuentas from plan_cuentas where id_plan_cuentas=if(proveedor.id_plan_cuentas is null,0,proveedor.id_plan_cuentas)) as id_plan_cuenta_prov,(select concat(codcta,'-',nomcta) from plan_cuentas where id_plan_cuentas=if(proveedor.id_plan_cuentas is null,0,proveedor.id_plan_cuentas)) as nombre_cuenta_prov,max(factura_compra_pagos.total) as total_pago
        from factura_compra_pagos
                INNER JOIN factura_compra
                ON factura_compra.id_factcompra=factura_compra_pagos.id_factura_compra
                INNER JOIN proveedor
                ON proveedor.id_proveedor=factura_compra.id_proveedor
                Left JOIN grupo_proveedor
                ON grupo_proveedor.id_grupoprov=proveedor.id_grupo_proveedor
                Left JOIN plan_cuentas
                ON plan_cuentas.id_plan_cuentas=grupo_proveedor.id_plan_cuentas
                INNER JOIN detalle_factura_compra
                ON detalle_factura_compra.id_factura=factura_compra.id_factcompra
                LEFT JOIN proyecto
                on proyecto.id_proyecto=detalle_factura_compra.id_proyecto
        where factura_compra_pagos.id_factura_compra={$id} and factura_compra_pagos.estado=2
        GROUP BY detalle_factura_compra.id_proyecto
        ORDER BY detalle_factura_compra.id_proyecto asc";
        $creditos = DB::select("SELECT * from factura_compra_pagos where id_factura_compra={$id} and estado=2");
        if (count($creditos) > 0) {
            $cliente = DB::select($query);
        } else {
            $cliente = [];
        }
        $codigo = DB::select("SELECT max(numero) as codigo FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.codigo like 'FC-%' and (asientos.estado='Activo' or asientos.estado is null) and proyecto.id_empresa=" . $request->id_empresa);
        $cod_asiento = "";
        if ($codigo) {
            $lenght = strlen($codigo[0]->codigo);
            $cod_asiento = $codigo[0]->codigo + 1;
        } else {
            $cod_asiento = "1";
        }
        $cod_asiento_ant = "";
        if ($codigo) {
            $codigo_ant = DB::select("SELECT asientos.numero FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.id_asientos_comprobante=7 and (asientos.estado='Activo' or asientos.estado is null) and asientos.codigo_rol={$id} and proyecto.id_empresa=" . $request->id_empresa . " ORDER BY asientos.codigo desc limit 1");
            if ($codigo_ant) {
                $lenght = strlen($codigo[0]->codigo);
                $cod_asiento_ant = $codigo_ant[0]->numero;
            }
        }
        $producto_asiento = DB::select("SELECT detalle_factura_compra.total,if(producto.iva=2,'doce','cero') as iva,if(producto.sector=1,'producto','servicio')  as sector,producto.id_linea_producto,proyecto.id_proyecto,proyecto.descripcion,
        if(producto.sector=1,linea_producto.id_plan_cuentas_compras_iva,null) as id_plan_cuentas_iva_12,
        if(producto.sector=1,linea_producto.id_plan_cuentas_compras_iva_0,null) as id_plan_cuentas_iva_0,
        (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(linea_producto.id_plan_cuentas_compras_iva is null,0,linea_producto.id_plan_cuentas_compras_iva) and id_empresa={$request->id_empresa}) as nombre_cuenta_12,
        (select id_grupo from plan_cuentas where id_plan_cuentas=if(linea_producto.id_plan_cuentas_compras_iva is null,0,linea_producto.id_plan_cuentas_compras_iva) and id_empresa={$request->id_empresa}) as grupo_cuenta_12,
        (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(linea_producto.id_plan_cuentas_compras_iva_0 is null,0,linea_producto.id_plan_cuentas_compras_iva_0) and id_empresa={$request->id_empresa}) as nombre_cuenta_0,
        (select id_grupo from plan_cuentas where id_plan_cuentas=if(linea_producto.id_plan_cuentas_compras_iva_0 is null,0,linea_producto.id_plan_cuentas_compras_iva_0) and id_empresa={$request->id_empresa}) as grupo_cuenta_0,
        plan_cuentas.id_plan_cuentas as id_plan_cuentas_servicio,
        concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,
        plan_cuentas.id_grupo as grupo_cuenta_servicio,
        null as haber,detalle_factura_compra.total as debe
        from detalle_factura_compra
        INNER JOIN producto
        ON producto.id_producto=detalle_factura_compra.id_producto
        INNER JOIN factura_compra
        ON factura_compra.id_factcompra=detalle_factura_compra.id_factura
        LEFT JOIN plan_cuentas
        ON producto.id_plan_cuentas=plan_cuentas.id_plan_cuentas
        LEFT JOIN proyecto
        on proyecto.id_proyecto=detalle_factura_compra.id_proyecto
        LEFT JOIN linea_producto
        on linea_producto.id_linea_producto=producto.id_linea_producto
        where detalle_factura_compra.id_factura={$id}");
        $iva_asiento = DB::select("SELECT detalle_factura_compra.total,proyecto.descripcion,proyecto.id_proyecto,(select CONCAT(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from retencion left  join plan_cuentas on plan_cuentas.id_plan_cuentas=retencion.id_plan_cuentas where retencion.descrip_retencion='IVA. en Compras' and retencion.id_empresa={$request->id_empresa} limit 1) as nombre_cuenta,(select plan_cuentas.id_plan_cuentas from retencion left  join plan_cuentas on plan_cuentas.id_plan_cuentas=retencion.id_plan_cuentas where retencion.descrip_retencion='IVA. en Compras' and retencion.id_empresa={$request->id_empresa} limit 1) as id_plan_cuentas,null as haber,round(if(detalle_factura_compra.id_iva=2,detalle_factura_compra.total*(12/100),0),2) as debe,factura_compra.iva_12
        from factura_compra,detalle_factura_compra

                       LEFT JOIN proyecto
                       on proyecto.id_proyecto=detalle_factura_compra.id_proyecto
                       INNER JOIN producto
                       on producto.id_producto=detalle_factura_compra.id_producto
											 
                       where factura_compra.id_factcompra={$id} and factura_compra.id_factcompra=detalle_factura_compra.id_factura 
                                       ORDER BY detalle_factura_compra.id_detalle_factura_compra asc");
        $forma_pagos_sin_plc = DB::select("SELECT factura_compra_pagos.total,round(detalle_factura_compra.total/factura_compra.subtotal_sin_impuesto,2) as porcentaje,round(detalle_factura_compra.total/factura_compra.subtotal_sin_impuesto*	factura_compra_pagos.total,2) as haber,null as debe,forma_pagos.id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,plan_cuentas.bansel,detalle_factura_compra.id_proyecto,proyecto.descripcion,factura_compra_pagos.id_forma_pagos,factura_compra_pagos.fecha_pago,factura_compra_pagos.numero_transaccion,forma_pagos.descripcion as nombre_pago
                                        from factura_compra_pagos
                                        INNER JOIN forma_pagos
                                        on forma_pagos.id_forma_pagos=factura_compra_pagos.id_forma_pagos
                                        LEFT JOIN plan_cuentas
                                        on plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas
                                        INNER JOIN factura_compra
                                        on factura_compra.id_factcompra=factura_compra_pagos.id_factura_compra
                                        INNER JOIN detalle_factura_compra
                                        on detalle_factura_compra.id_factura=factura_compra.id_factcompra
                                        left JOIN proyecto
                                        on proyecto.id_proyecto=detalle_factura_compra.id_proyecto
                                        where factura_compra_pagos.estado=1 and factura_compra_pagos.id_factura_compra={$id} and factura_compra_pagos.anticipo is null and factura_compra_pagos.id_plan_cuentas is null
                                        ORDER BY detalle_factura_compra.id_proyecto asc");
        $pagos_sin_plc = DB::select("SELECT sum(factura_compra_pagos.total) as total_pago from factura_compra_pagos  where factura_compra_pagos.estado=1 and factura_compra_pagos.id_factura_compra={$id} and factura_compra_pagos.anticipo is null and factura_compra_pagos.id_plan_cuentas is null");
        $forma_pagos_con_plc = DB::select("SELECT factura_compra_pagos.total,round(detalle_factura_compra.total/factura_compra.subtotal_sin_impuesto,2) as porcentaje,round(detalle_factura_compra.total/factura_compra.subtotal_sin_impuesto*	factura_compra_pagos.total,2) as haber,null as debe,factura_compra_pagos.id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,plan_cuentas.bansel,detalle_factura_compra.id_proyecto,proyecto.descripcion,factura_compra_pagos.id_forma_pagos,factura_compra_pagos.fecha_pago,factura_compra_pagos.numero_transaccion,forma_pagos.descripcion as nombre_pago
                                        from factura_compra_pagos
                                        INNER JOIN forma_pagos
                                        on forma_pagos.id_forma_pagos=factura_compra_pagos.id_forma_pagos
                                        LEFT JOIN plan_cuentas
                                        on plan_cuentas.id_plan_cuentas=factura_compra_pagos.id_plan_cuentas
                                        INNER JOIN factura_compra
                                        on factura_compra.id_factcompra=factura_compra_pagos.id_factura_compra
                                        INNER JOIN detalle_factura_compra
                                        on detalle_factura_compra.id_factura=factura_compra.id_factcompra
                                        left JOIN proyecto
                                        on proyecto.id_proyecto=detalle_factura_compra.id_proyecto
                                        where factura_compra_pagos.estado=1 and factura_compra_pagos.id_factura_compra={$id} and factura_compra_pagos.anticipo is null and factura_compra_pagos.id_plan_cuentas is not null
                                        ORDER BY detalle_factura_compra.id_proyecto asc");
        $pagos_con_plc = DB::select("SELECT sum(factura_compra_pagos.total) as total_pago from factura_compra_pagos  where factura_compra_pagos.estado=1 and factura_compra_pagos.id_factura_compra={$id} and factura_compra_pagos.anticipo is null and factura_compra_pagos.id_plan_cuentas is not null");
        $forma_pagos_anticipo = DB::select("SELECT factura_compra_pagos.total,round(detalle_factura_compra.total/factura_compra.subtotal_sin_impuesto,2) as porcentaje,round(detalle_factura_compra.total/factura_compra.subtotal_sin_impuesto*factura_compra_pagos.total,2) as haber,null as debe,grupo_proveedor.id_plan_cuentas_anticipo as id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,plan_cuentas.bansel,detalle_factura_compra.id_proyecto,proyecto.descripcion,factura_compra_pagos.id_forma_pagos,factura_compra_pagos.fecha_pago,factura_compra_pagos.numero_transaccion,null as nombre_pago
                                        from factura_compra_pagos
                                        INNER JOIN factura_compra
                                        on factura_compra.id_factcompra=factura_compra_pagos.id_factura_compra
                                        INNER JOIN proveedor
                                        on proveedor.id_proveedor=factura_compra.id_proveedor
                                        LEFT JOIN grupo_proveedor
                                        on grupo_proveedor.id_grupoprov=proveedor.id_grupo_proveedor
                                        LEFT JOIN plan_cuentas
                                        on plan_cuentas.id_plan_cuentas=grupo_proveedor.id_plan_cuentas_anticipo
                                        INNER JOIN detalle_factura_compra
                                        on detalle_factura_compra.id_factura=factura_compra.id_factcompra
                                        left JOIN proyecto
                                        on proyecto.id_proyecto=detalle_factura_compra.id_proyecto
                                        where factura_compra_pagos.estado=1 and factura_compra_pagos.id_factura_compra={$id} and factura_compra_pagos.anticipo is not null and factura_compra_pagos.id_plan_cuentas is null
                                        ORDER BY detalle_factura_compra.id_proyecto asc");
        $pagos_anticipo = DB::select("SELECT sum(factura_compra_pagos.total) as total_pago from factura_compra_pagos  where factura_compra_pagos.estado=1 and factura_compra_pagos.id_factura_compra={$id} and factura_compra_pagos.anticipo is not null and factura_compra_pagos.id_plan_cuentas is null");
        $ice_fact = DB::select("SELECT ice.valor as total,proyecto.descripcion,proyecto.id_proyecto,CONCAT(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,plan_cuentas.id_plan_cuentas,ice.valor as debe,null as haber,detalle_factura_compra.id_detalle_factura_compra as id_detalle from detalle_factura_compra
        INNER JOIN factura_compra
        on factura_compra.id_factcompra=detalle_factura_compra.id_factura
        INNER JOIN ice
        on ice.id_ice=detalle_factura_compra.id_ice
        LEFT JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=ice.id_plan_cuentas
        LEFT JOIN proyecto
        on proyecto.id_proyecto=detalle_factura_compra.id_proyecto
        where factura_compra.id_factcompra={$id} and ice.valor>0
        ORDER BY detalle_factura_compra.id_detalle_factura_compra asc");
        $fecha_emision = substr($factura[0]->fech_emision, 0, -3);
        $anio_emision = substr($factura[0]->fech_emision, 0, 4);
        $fecha_cierre = DB::select("SELECT * 
                                    from asientos 
                                        where id_empresa={$request->id_empresa} 
                                        and cierre_contable='Cierre Mes' and periodo='{$fecha_emision}'
                                UNION
                                SELECT * 
                                    from asientos 
                                        where id_empresa={$request->id_empresa} 
                                        and cierre_contable='Estado Contable' and periodo='{$anio_emision}'");
        $total_retencion = DB::select("SELECT sum(cantidadiva) as cantidad_iva,sum(cantidadrenta) as cantidad_renta  from retencion_factura_comp where retencion_factura_comp.id_factura={$id}");
        $asiento = "";
        if (count($fecha_cierre) > 0) {
            $asiento = "no";
        } else {
            $asiento = "si";
        }
        $total_pagos_sin_plc = 0;
        $total_pagos_con_plc = 0;
        $total_pagos_anticipo = 0;
        $total_retencion_iva = 0;
        $total_retencion_renta = 0;
        if (count($pagos_sin_plc) > 0) {
            $total_pagos_sin_plc = $pagos_sin_plc[0]->total_pago;
        }
        if (count($pagos_con_plc) > 0) {
            $total_pagos_con_plc = $pagos_con_plc[0]->total_pago;
        }
        if (count($pagos_anticipo) > 0) {
            $total_pagos_anticipo = $pagos_anticipo[0]->total_pago;
        }
        if (count($total_retencion) > 0) {
            $total_retencion_iva = $total_retencion[0]->cantidad_iva;
            $total_retencion_renta = $total_retencion[0]->cantidad_renta;
        }
        return [
            'factura' => $factura[0],
            'asiento_permitido' => $asiento,
            'cliente' => $cliente,
            'empresa' => $empresa[0],
            'codigo' => $cod_asiento,
            'codigo_anterior' => $cod_asiento_ant,
            'producto_asientos' => $producto_asiento,
            'doce_iva_asiento' => $iva_asiento,
            'retencion_asiento' => $renta_retencion_asiento,
            'iva_retencion_asiento' => $iva_retencion_asiento,
            'pagos_asientos_sin_plc' => $forma_pagos_sin_plc,
            'pagos_asientos_con_plc' => $forma_pagos_con_plc,
            'pagos_asientos_anticipo' => $forma_pagos_anticipo,
            'id_proyecto' => $proyecto[0]->id_proyecto,
            'ice' => $ice_fact,
            'total_pagos_sin_plc' => $total_pagos_sin_plc,
            'total_pagos_con_plc' => $total_pagos_con_plc,
            'total_pagos_anticipo' => $total_pagos_anticipo,
            'total_retencion_iva' => $total_retencion_iva,
            'total_retencion_renta' => $total_retencion_renta
        ];
    }
    public function sustento_compra($id)
    {
        ini_set('max_execution_time', 53200);
        $facturas = DB::select("SELECT factura_compra.*,tipo_sustento.cod_sustento,tipo_sustento.descrip_sustento from factura_compra INNER JOIN tipo_sustento on tipo_sustento.id_sustento=factura_compra.id_sustento
        where factura_compra.id_empresa=$id and tipo_sustento.id_empresa<>$id");
        $empresa = DB::select("SELECT * from empresa where id_empresa={$id}");
        $data = "Sustentos Cambiados Exitosamente " . $empresa[0]->nombre_empresa;
        for ($z = 0; $z < count($facturas); $z++) {
            $id_factura = $facturas[$z]->id_factcompra;
            $codigo = $facturas[$z]->cod_sustento;
            $sustento = DB::select("SELECT * from tipo_sustento where id_empresa=$id and cod_sustento='{$codigo}'");
            $fact = FacturaCompra::find($id_factura);
            $fact->id_sustento = $sustento[0]->id_sustento;
            $fact->save();
        }
        return $data;
    }
    public function fecha_cta_pagar($id)
    {
        ini_set('max_execution_time', 53200);
        $empresa = DB::select("SELECT * from empresa where id_empresa={$id}");
        $data = "Fecha Venc Cambiados Exitosamente " . $empresa[0]->nombre_empresa;
        $factura_pagos = DB::select("SELECT * from factura_compra_pagos
                                    INNER JOIN factura_compra
                                    on factura_compra.id_factcompra=factura_compra_pagos.id_factura_compra
                                    where factura_compra.id_empresa={$id} and factura_compra_pagos.estado=2 and factura_compra_pagos.plazo<=1");

        for ($z = 0; $z < count($factura_pagos); $z++) {
            $fecha_pago = [];
            switch ($factura_pagos[$z]->unidad_tiempo) {
                case "Días" || "Dias":
                    $fecha_pago = DB::select("SELECT '{$factura_pagos[$z]->fech_emision}' as dia,DATE_ADD('{$factura_pagos[$z]->fech_emision}', INTERVAL {$factura_pagos[$z]->tiempos_pagos} DAY) as dia_pago");
                    break;
                case "Meses":
                    $fecha_pago = DB::select("SELECT '{$factura_pagos[$z]->fech_emision}' as dia,DATE_ADD('{$factura_pagos[$z]->fech_emision}', INTERVAL {$factura_pagos[$z]->tiempos_pagos} MONTH) as dia_pago");
                    break;
                case "Años":
                    $fecha_pago = DB::select("SELECT '{$factura_pagos[$z]->fech_emision}' as dia,DATE_ADD('{$factura_pagos[$z]->fech_emision}', INTERVAL {$factura_pagos[$z]->tiempos_pagos} YEAR) as dia_pago");
                    break;
            }

            if (count($fecha_pago) > 0) {
                DB::update("UPDATE ctas_pagar set fecha_pago='{$fecha_pago[0]->dia_pago}'  where id_factura_compra={$factura_pagos[$z]->id_factura_compra}");
            }
        }
        return $data;
    }
    public function agregarAsiento(Request $request)
    {
        FacturaCompra::where('id_factcompra', $request->cod_rol)->update(['contabilidad' => '1']);
        $asientos = new Asientos();
        $asientos->automatico = 0;
        $asientos->numero = $request->numero;
        $asientos->codigo = $request->codigo;
        $asientos->codigo_rol = $request->cod_rol;
        $asientos->fecha = $request->fecha;
        $asientos->razon_social = $request->razon_social;
        $asientos->tipo_identificacion = $request->tipo_identificacion;
        $asientos->ruc_ci = $request->ruc_ci;
        $asientos->concepto = $request->concepto;
        $asientos->ucrea = $request->ucrea;
        $asientos->id_proyecto = $request->id_proyecto;
        $asientos->id_asientos_comprobante = 7;

        $asientos->save();
        return $asientos->id_asientos;
    }
    public function agregarAsientoDetalle(Request $request)
    {
        foreach ($request->productos as $haber) {
            $asiento = new Asientos_contables_detalle();
            if ($haber["sector"] == "producto" && $haber["iva"] == "doce") {
                $asiento->proyecto = $haber["descripcion"];
                $asiento->debe = $haber["debe"];
                $asiento->ucrea = $request->ucrea;
                $asiento->id_plan_cuentas = $haber["id_plan_cuentas_iva_12"];
                $asiento->id_asientos = $request->id_asientos;
                $asiento->id_proyecto = $haber["id_proyecto"];
            }
            if ($haber["sector"] == "producto" && $haber["iva"] == "cero") {
                $asiento->proyecto = $haber["descripcion"];
                $asiento->debe = $haber["debe"];
                $asiento->ucrea = $request->ucrea;
                $asiento->id_plan_cuentas = $haber["id_plan_cuentas_iva_0"];
                $asiento->id_asientos = $request->id_asientos;
                $asiento->id_proyecto = $haber["id_proyecto"];
            }
            if ($haber["sector"] == "servicio") {
                $asiento->proyecto = $haber["descripcion"];
                $asiento->debe = $haber["debe"];
                $asiento->ucrea = $request->ucrea;
                $asiento->id_plan_cuentas = $haber["id_plan_cuentas_servicio"];
                $asiento->id_asientos = $request->id_asientos;
                $asiento->id_proyecto = $haber["id_proyecto"];
            }
            $asiento->save();
        }
        foreach ($request->iva_12 as $haber) {
            $asiento = new Asientos_contables_detalle();
            if ($haber["debe"] > 0) {
                $asiento->proyecto = $haber["descripcion"];
                $asiento->debe = $haber["debe"];
                $asiento->ucrea = $request->ucrea;
                $asiento->id_plan_cuentas = $haber["id_plan_cuentas"];
                $asiento->id_asientos = $request->id_asientos;
                $asiento->id_proyecto = $haber["id_proyecto"];
            }
            $asiento->save();
        }

        foreach ($request->pagos_sin_plc as $debe) {
            $asiento = new Asientos_contables_detalle();
            if (count($debe) > 0) {
                if ($debe["haber"] > 0) {
                    $asiento->proyecto = $debe["descripcion"];
                    $asiento->haber = $debe["haber"];
                    if ($debe["bansel"] !== null) {
                        $asiento->no_documento = $debe["numero_transaccion"];
                        $asiento->fecha_de_pago = $debe["fecha_pago"];
                        $asiento->id_forma_pagos = $debe["id_forma_pagos"];
                    }
                    $asiento->ucrea = $request->ucrea;
                    $asiento->id_plan_cuentas = $debe["id_plan_cuentas"];
                    $asiento->id_asientos = $request->id_asientos;
                    $asiento->id_proyecto = $debe["id_proyecto"];
                    $asiento->save();
                }
            }
        }
        foreach ($request->pagos_con_plc as $debe) {
            $asiento = new Asientos_contables_detalle();
            if (count($debe) > 0) {
                if ($debe["haber"] > 0) {
                    $asiento->proyecto = $debe["descripcion"];
                    $asiento->haber = $debe["haber"];
                    if ($debe["bansel"] !== null) {
                        $asiento->no_documento = $debe["numero_transaccion"];
                        $asiento->fecha_de_pago = $debe["fecha_pago"];
                        $asiento->id_forma_pagos = $debe["id_forma_pagos"];
                    }
                    $asiento->ucrea = $request->ucrea;
                    $asiento->id_plan_cuentas = $debe["id_plan_cuentas"];
                    $asiento->id_asientos = $request->id_asientos;
                    $asiento->id_proyecto = $debe["id_proyecto"];
                    $asiento->save();
                }
            }
        }
        foreach ($request->pagos_anticipo as $debe) {
            $asiento = new Asientos_contables_detalle();
            if (count($debe) > 0) {
                if ($debe["haber"] > 0) {
                    $asiento->proyecto = $debe["descripcion"];
                    $asiento->haber = $debe["haber"];
                    if ($debe["bansel"] !== null) {
                        $asiento->no_documento = $debe["numero_transaccion"];
                        $asiento->fecha_de_pago = $debe["fecha_pago"];
                        $asiento->id_forma_pagos = $debe["id_forma_pagos"];
                    }
                    $asiento->ucrea = $request->ucrea;
                    $asiento->id_plan_cuentas = $debe["id_plan_cuentas"];
                    $asiento->id_asientos = $request->id_asientos;
                    $asiento->id_proyecto = $debe["id_proyecto"];
                    $asiento->save();
                }
            }
        }

        foreach ($request->creditos as $debe) {
            $asiento = new Asientos_contables_detalle();
            if (count($debe) > 0) {
                if ($debe["exist_plan_cuenta_prov"] == "si") {
                    $asiento->proyecto = $debe["descripcion"];
                    $asiento->haber = $debe["haber"];
                    $asiento->ucrea = $request->ucrea;
                    $asiento->id_plan_cuentas = $debe["id_plan_cuenta_prov"];
                    $asiento->id_asientos = $request->id_asientos;
                    $asiento->id_proyecto = $debe["id_proyecto"];
                } else {
                    $asiento->proyecto = $debe["descripcion"];
                    $asiento->haber = $debe["haber"];
                    $asiento->ucrea = $request->ucrea;
                    $asiento->id_plan_cuentas = $debe["id_plan_cuenta_grupo_prov"];
                    $asiento->id_asientos = $request->id_asientos;
                    $asiento->id_proyecto = $debe["id_proyecto"];
                }

                $asiento->save();
            }
        }

        foreach ($request->retencion_iva as $debe) {
            $asiento = new Asientos_contables_detalle();
            if (count($debe) > 0) {
                $asiento->proyecto = $debe["descripcion"];
                $asiento->haber = $debe["haber"];
                $asiento->ucrea = $request->ucrea;
                $asiento->id_plan_cuentas = $debe["id_plan_cuentas"];
                $asiento->id_asientos = $request->id_asientos;
                $asiento->id_proyecto = $debe["id_proyecto"];
                $asiento->save();
            }
        }
        foreach ($request->retencion_renta as $debe) {
            $asiento = new Asientos_contables_detalle();
            if (count($debe) > 0) {
                $asiento->proyecto = $debe["descripcion"];
                $asiento->haber = $debe["haber"];
                $asiento->ucrea = $request->ucrea;
                $asiento->id_plan_cuentas = $debe["id_plan_cuentas"];
                $asiento->id_asientos = $request->id_asientos;
                $asiento->id_proyecto = $debe["id_proyecto"];
                $asiento->save();
            }
        }
    }
    public function factura_compra_pdf($id, $tipo)
    {
        //Selecciona la factura que va enviar
        $facturas = DB::select("SELECT *,(select id_retfactcompra from retencion_factura_comp where id_factura={$id}) as exist_retencion FROM factura_compra WHERE id_factcompra = $id");
        $id_cliente = $facturas[0]->id_proveedor;
        $id_empresa = $facturas[0]->id_empresa;
        $id_punto_emision = $facturas[0]->id_punto_emision;
        $id_establecimiento = $facturas[0]->id_establecimiento;
        $clave_acceso = $facturas[0]->descripcion;

        //selecciona los clientes, empresa, los productos, pagos y clientes para crear en pdf
        $clientes = DB::select("SELECT * FROM proveedor WHERE id_proveedor = $id_cliente");
        $empresas = DB::select("SELECT em.*, es.urlweb FROM empresa em INNER JOIN establecimiento es ON es.id_establecimiento = $id_establecimiento INNER JOIN punto_emision pe ON pe.id_punto_emision = $id_punto_emision WHERE em.id_empresa = $id_empresa");
        $detalles = DB::select("SELECT det.*, pr.cod_principal, pr.cod_alterno, pr.total_ice as total_ice_pr FROM detalle_factura_compra as det INNER JOIN producto pr ON det.id_producto=pr.id_producto WHERE det.id_factura = $id");
        $pagos = DB::select("SELECT fp.*, fps.descripcion, fpag.descripcion AS descripcionfp FROM factura_compra_pagos fp LEFT JOIN forma_pagos fpag ON fp.id_forma_pagos=fpag.id_forma_pagos LEFT JOIN forma_pagos_sri fps ON fps.id_forma_pagos_sri=fpag.id_forma_pagos_sri WHERE fp.id_factura_compra=$id");
        $cliente = $clientes[0];
        $empresa = $empresas[0];
        $factura = $facturas[0];
        //envia a la vista de factura_venta los datos almacenados en las variables  mdiante compact
        $pdf = \PDF::loadView('pdf/factura_compra', compact("factura", "cliente", "empresa", "detalles", "clave_acceso", "pagos"));
        $carpeta2 = constant("DATA_EMPRESA") . "$id_empresa/comprobantes/factura_compra_pdf";
        if (!file_exists($carpeta2)) {
            mkdir($carpeta2, 0755, true);
        }
        //si la url tiene d va a descargar caso contrario solo va a ser una vista
        if ($tipo == 'd') {
            return $pdf->setWarnings(false)->save(constant("DATA_EMPRESA") . "$id_empresa/comprobantes/factura_compra_pdf/$factura->id_factcompra.pdf")->download("$clave_acceso.pdf");
        } else {
            return $pdf->setWarnings(false)->save(constant("DATA_EMPRESA") . "$id_empresa/comprobantes/factura_compra_pdf/$factura->id_factcompra.pdf")->stream("$clave_acceso.pdf");
        }
        //envia a la vista de factura_venta los datos almacenados en las variables  mdiante compact

    }
    public function facturaCompraContabilizarAuto(Request $request, $id)
    {
        $empresa = DB::select("SELECT empresa.*,proveedor.nombre_proveedor as nombre,proveedor.identif_proveedor as identificacion,proveedor.tipo_identificacion from empresa,factura_compra,proveedor where proveedor.id_empresa=empresa.id_empresa and proveedor.id_proveedor=" . $request->cliente);
        $proyecto = DB::select("SELECT * from proyecto where id_empresa={$request->usuario['id_empresa']}");
        //$factura = DB::select("SELECT f.*, pr.descripcion as des_proyecto FROM factura_compra f LEFT JOIN proyecto pr ON f.id_proyecto=pr.id_proyecto WHERE f.id_factcompra =".$id);

        // $renta_retencion_asiento=DB::select("SELECT retencion.id_plan_cuentas,detalle_factura_compra.total,round(detalle_factura_compra.total/factura_compra.subtotal_sin_impuesto,2) as porcentaje,retencion_factura_comp.baserenta,
        // retencion_factura_comp.porcentajerenta,retencion_factura_comp.cantidadrenta,round(retencion_factura_comp.cantidadrenta*(detalle_factura_compra.total/factura_compra.subtotal_sin_impuesto),2) as haber,null as debe,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,detalle_factura_compra.id_proyecto,proyecto.descripcion,(select sum(cantidadrenta) from retencion_factura_comp where id_factura={$id}) as total_renta,detalle_factura_compra.id_detalle_factura_compra as id_detalle
        //         FROM retencion_factura_comp
        //         INNER JOIN retencion
        //         ON retencion.id_retencion=retencion_factura_comp.id_retencion_renta
        //         INNER JOIN factura_compra
        //         on factura_compra.id_factcompra=retencion_factura_comp.id_factura
        //         INNER JOIN detalle_factura_compra
        //         on detalle_factura_compra.id_factura=factura_compra.id_factcompra
        //         left JOIN plan_cuentas
        //         on plan_cuentas.id_plan_cuentas=retencion.id_plan_cuentas
        //         LEFT JOIN proyecto
        //         on proyecto.id_proyecto=detalle_factura_compra.id_proyecto
        //         where factura_compra.id_factcompra={$id} and factura_compra.id_empresa={$request->id_empresa}
        // ORDER BY detalle_factura_compra.id_proyecto");
        // $iva_retencion_asiento=DB::select(
        //                     "SELECT retencion.id_plan_cuentas,detalle_factura_compra.total,round(detalle_factura_compra.total/factura_compra.subtotal_sin_impuesto,2) as porcentaje,
        //                     retencion_factura_comp.porcentajeiva,retencion_factura_comp.cantidadiva,round((retencion_factura_comp.cantidadiva)*(detalle_factura_compra.total/factura_compra.subtotal_sin_impuesto),2) as haber,null as debe,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,detalle_factura_compra.id_proyecto,proyecto.descripcion,(select sum(cantidadiva) from retencion_factura_comp where id_factura={$id}) as total_iva,0 as acumula,detalle_factura_compra.id_detalle_factura_compra as id_detalle
        //                             FROM retencion_factura_comp
        //                             INNER JOIN retencion
        //                             ON retencion.id_retencion=retencion_factura_comp.id_retencion_iva
        //                             INNER JOIN factura_compra
        //                             on factura_compra.id_factcompra=retencion_factura_comp.id_factura
        //                             INNER JOIN detalle_factura_compra
        //                             on detalle_factura_compra.id_factura=factura_compra.id_factcompra
        //                             left JOIN plan_cuentas
        //                             on plan_cuentas.id_plan_cuentas=retencion.id_plan_cuentas
        //                             LEFT JOIN proyecto
        //                             on proyecto.id_proyecto=detalle_factura_compra.id_proyecto
        //                             where factura_compra.id_factcompra={$id} and factura_compra.id_empresa={$request->id_empresa}
        //                     ORDER BY detalle_factura_compra.id_proyecto");

        $query = "SELECT round(sum(factura_compra_pagos.total)/count(factura_compra_pagos.id_factura_compra_pagos),2) as total,round(sum(detalle_factura_compra.total)/max(factura_compra.subtotal_sin_impuesto),2) as porcentaje,sum(detalle_factura_compra.total) as valor_producto,round((sum(detalle_factura_compra.total)/max(factura_compra.subtotal_sin_impuesto)*(sum(factura_compra_pagos.total)/count(factura_compra_pagos.id_factura_compra_pagos))),2) as haber,null as debe,detalle_factura_compra.id_proyecto,proyecto.descripcion,plan_cuentas.id_plan_cuentas as id_plan_cuenta_grupo_prov,concat(max(plan_cuentas.codcta),'-',max(plan_cuentas.nomcta)) as nombre_cuenta_grupo_prov,if(proveedor.id_plan_cuentas is null,'no','si') as exist_plan_cuenta_prov,(select id_plan_cuentas from plan_cuentas where id_plan_cuentas=if(proveedor.id_plan_cuentas is null,0,proveedor.id_plan_cuentas)) as id_plan_cuenta_prov,(select concat(codcta,'-',nomcta) from plan_cuentas where id_plan_cuentas=if(proveedor.id_plan_cuentas is null,0,proveedor.id_plan_cuentas)) as nombre_cuenta_prov
        from factura_compra_pagos
                INNER JOIN factura_compra
                ON factura_compra.id_factcompra=factura_compra_pagos.id_factura_compra
                INNER JOIN proveedor
                ON proveedor.id_proveedor=factura_compra.id_proveedor
                Left JOIN grupo_proveedor
                ON grupo_proveedor.id_grupoprov=proveedor.id_grupo_proveedor
                Left JOIN plan_cuentas
                ON plan_cuentas.id_plan_cuentas=grupo_proveedor.id_plan_cuentas
                INNER JOIN detalle_factura_compra
                ON detalle_factura_compra.id_factura=factura_compra.id_factcompra
                LEFT JOIN proyecto
                on proyecto.id_proyecto=detalle_factura_compra.id_proyecto
        where factura_compra_pagos.id_factura_compra={$id} and factura_compra_pagos.estado=2
        GROUP BY detalle_factura_compra.id_proyecto
        ORDER BY detalle_factura_compra.id_proyecto asc";
        $creditos = DB::select("SELECT * from factura_compra_pagos where id_factura_compra={$id} and estado=2");
        if (count($creditos) > 0) {
            $cliente = DB::select($query);
        } else {
            $cliente = [];
        }
        $codigo = DB::select("SELECT max(numero) as codigo FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.codigo like 'FC-%' and (asientos.estado='Activo' or asientos.estado is null) and proyecto.id_empresa=" . $request->id_empresa);
        $cod_asiento = "";
        if ($codigo) {
            $lenght = strlen($codigo[0]->codigo);
            $cod_asiento = $codigo[0]->codigo + 1;
        } else {
            $cod_asiento = "1";
        }
        $cod_asiento_ant = "";
        if ($codigo) {
            $codigo_ant = DB::select("SELECT asientos.numero FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.id_asientos_comprobante=7 and (asientos.estado='Activo' or asientos.estado is null) and asientos.codigo_rol={$id} and proyecto.id_empresa=" . $request->id_empresa . " ORDER BY asientos.codigo desc limit 1");
            if ($codigo_ant) {
                $lenght = strlen($codigo[0]->codigo);
                $cod_asiento_ant = $codigo_ant[0]->numero;
            }
        }
        $producto_asiento = DB::select("SELECT detalle_factura_compra.total,if(producto.iva=2,'doce','cero') as iva,if(producto.sector=1,'producto','servicio')  as sector,producto.id_linea_producto,proyecto.id_proyecto,proyecto.descripcion,
        if(producto.sector=1,linea_producto.id_plan_cuentas_compras_iva,null) as id_plan_cuentas_iva_12,
        if(producto.sector=1,linea_producto.id_plan_cuentas_compras_iva_0,null) as id_plan_cuentas_iva_0,
        (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(linea_producto.id_plan_cuentas_compras_iva is null,0,linea_producto.id_plan_cuentas_compras_iva) and id_empresa={$request->id_empresa}) as nombre_cuenta_12,
        (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(linea_producto.id_plan_cuentas_compras_iva_0 is null,0,linea_producto.id_plan_cuentas_compras_iva_0) and id_empresa={$request->id_empresa}) as nombre_cuenta_0,
        plan_cuentas.id_plan_cuentas as id_plan_cuentas_servicio,
        concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,
        null as haber,detalle_factura_compra.total as debe
        from detalle_factura_compra
        INNER JOIN producto
        ON producto.id_producto=detalle_factura_compra.id_producto
        INNER JOIN factura_compra
        ON factura_compra.id_factcompra=detalle_factura_compra.id_factura
        LEFT JOIN plan_cuentas
        ON producto.id_plan_cuentas=plan_cuentas.id_plan_cuentas
        LEFT JOIN proyecto
        on proyecto.id_proyecto=detalle_factura_compra.id_proyecto
        LEFT JOIN linea_producto
        on linea_producto.id_linea_producto=producto.id_linea_producto
        where detalle_factura_compra.id_factura={$id}");
        $iva_asiento = DB::select("SELECT detalle_factura_compra.total,proyecto.descripcion,proyecto.id_proyecto,CONCAT(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,plan_cuentas.id_plan_cuentas,null as haber,round(if(detalle_factura_compra.id_iva=2,detalle_factura_compra.total*(12/100),0),2) as debe
        from factura_compra,retencion,plan_cuentas,detalle_factura_compra
                       LEFT JOIN proyecto
                       on proyecto.id_proyecto=detalle_factura_compra.id_proyecto
                       INNER JOIN producto
                       on producto.id_producto=detalle_factura_compra.id_producto
                       where factura_compra.id_factcompra={$id} and factura_compra.id_factcompra=detalle_factura_compra.id_factura and retencion.descrip_retencion='IVA. en Compras' and retencion.id_empresa={$request->id_empresa} and plan_cuentas.id_plan_cuentas=retencion.id_plan_cuentas
                                       ORDER BY detalle_factura_compra.id_detalle_factura_compra asc");
        $forma_pagos_sin_plc = DB::select("SELECT factura_compra_pagos.total,round(detalle_factura_compra.total/factura_compra.subtotal_sin_impuesto,2) as porcentaje,round(detalle_factura_compra.total/factura_compra.subtotal_sin_impuesto*	factura_compra_pagos.total,2) as haber,null as debe,forma_pagos.id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,detalle_factura_compra.id_proyecto,proyecto.descripcion,factura_compra_pagos.id_forma_pagos,factura_compra_pagos.fecha_pago,factura_compra_pagos.numero_transaccion,forma_pagos.descripcion as nombre_pago
                                        from factura_compra_pagos
                                        INNER JOIN forma_pagos
                                        on forma_pagos.id_forma_pagos=factura_compra_pagos.id_forma_pagos
                                        LEFT JOIN plan_cuentas
                                        on plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas
                                        INNER JOIN factura_compra
                                        on factura_compra.id_factcompra=factura_compra_pagos.id_factura_compra
                                        INNER JOIN detalle_factura_compra
                                        on detalle_factura_compra.id_factura=factura_compra.id_factcompra
                                        left JOIN proyecto
                                        on proyecto.id_proyecto=detalle_factura_compra.id_proyecto
                                        where factura_compra_pagos.estado=1 and factura_compra_pagos.id_factura_compra={$id} and factura_compra_pagos.anticipo is null and factura_compra_pagos.id_plan_cuentas is null
                                        ORDER BY detalle_factura_compra.id_proyecto asc");
        $forma_pagos_con_plc = DB::select("SELECT factura_compra_pagos.total,round(detalle_factura_compra.total/factura_compra.subtotal_sin_impuesto,2) as porcentaje,round(detalle_factura_compra.total/factura_compra.subtotal_sin_impuesto*	factura_compra_pagos.total,2) as haber,null as debe,factura_compra_pagos.id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,detalle_factura_compra.id_proyecto,proyecto.descripcion,factura_compra_pagos.id_forma_pagos,factura_compra_pagos.fecha_pago,factura_compra_pagos.numero_transaccion,forma_pagos.descripcion as nombre_pago
                                        from factura_compra_pagos
                                        INNER JOIN forma_pagos
                                        on forma_pagos.id_forma_pagos=factura_compra_pagos.id_forma_pagos
                                        LEFT JOIN plan_cuentas
                                        on plan_cuentas.id_plan_cuentas=factura_compra_pagos.id_plan_cuentas
                                        INNER JOIN factura_compra
                                        on factura_compra.id_factcompra=factura_compra_pagos.id_factura_compra
                                        INNER JOIN detalle_factura_compra
                                        on detalle_factura_compra.id_factura=factura_compra.id_factcompra
                                        left JOIN proyecto
                                        on proyecto.id_proyecto=detalle_factura_compra.id_proyecto
                                        where factura_compra_pagos.estado=1 and factura_compra_pagos.id_factura_compra={$id} and factura_compra_pagos.anticipo is null and factura_compra_pagos.id_plan_cuentas is not null
                                        ORDER BY detalle_factura_compra.id_proyecto asc");
        $forma_pagos_anticipo = DB::select("SELECT factura_compra_pagos.total,round(detalle_factura_compra.total/factura_compra.subtotal_sin_impuesto,2) as porcentaje,round(detalle_factura_compra.total/factura_compra.subtotal_sin_impuesto*factura_compra_pagos.total,2) as haber,null as debe,grupo_proveedor.id_plan_cuentas_anticipo as id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,detalle_factura_compra.id_proyecto,proyecto.descripcion,factura_compra_pagos.id_forma_pagos,factura_compra_pagos.fecha_pago,factura_compra_pagos.numero_transaccion,null as nombre_pago
                                        from factura_compra_pagos
                                        INNER JOIN factura_compra
                                        on factura_compra.id_factcompra=factura_compra_pagos.id_factura_compra
                                        INNER JOIN proveedor
                                        on proveedor.id_proveedor=factura_compra.id_proveedor
                                        LEFT JOIN grupo_proveedor
                                        on grupo_proveedor.id_grupoprov=proveedor.id_grupo_proveedor
                                        LEFT JOIN plan_cuentas
                                        on plan_cuentas.id_plan_cuentas=grupo_proveedor.id_plan_cuentas_anticipo
                                        INNER JOIN detalle_factura_compra
                                        on detalle_factura_compra.id_factura=factura_compra.id_factcompra
                                        left JOIN proyecto
                                        on proyecto.id_proyecto=detalle_factura_compra.id_proyecto
                                        where factura_compra_pagos.estado=1 and factura_compra_pagos.id_factura_compra={$id} and factura_compra_pagos.anticipo is not null and factura_compra_pagos.id_plan_cuentas is null
                                        ORDER BY detalle_factura_compra.id_proyecto asc");
        $ice_fact = DB::select("SELECT ice.valor as total,proyecto.descripcion,proyecto.id_proyecto,CONCAT(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,plan_cuentas.id_plan_cuentas,ice.valor as debe,null as haber,detalle_factura_compra.id_detalle_factura_compra as id_detalle from detalle_factura_compra
        INNER JOIN factura_compra
        on factura_compra.id_factcompra=detalle_factura_compra.id_factura
        INNER JOIN ice
        on ice.id_ice=detalle_factura_compra.id_ice
        LEFT JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=ice.id_plan_cuentas
        LEFT JOIN proyecto
        on proyecto.id_proyecto=detalle_factura_compra.id_proyecto
        where factura_compra.id_factcompra={$id} and ice.valor>0
        ORDER BY detalle_factura_compra.id_detalle_factura_compra asc");
        return [
            'factura' => $factura[0],
            'cliente' => $cliente,
            'empresa' => $empresa[0],
            'codigo' => $cod_asiento,
            'codigo_anterior' => $cod_asiento_ant,
            'producto_asientos' => $producto_asiento,
            'doce_iva_asiento' => $iva_asiento,
            'retencion_asiento' => $renta_retencion_asiento,
            'iva_retencion_asiento' => $iva_retencion_asiento,
            'pagos_asientos_sin_plc' => $forma_pagos_sin_plc,
            'pagos_asientos_con_plc' => $forma_pagos_con_plc,
            'pagos_asientos_anticipo' => $forma_pagos_anticipo,
            'id_proyecto' => $proyecto[0]->id_proyecto,
            'ice' => $ice_fact
        ];
    }
    public function guardar_factura_clave(Request $request)
    {
        $clave_acceso = $request->factura["clave_acceso"];
        $numero9 =  substr($clave_acceso, 30, 9);
        $res = DB::select("SELECT * FROM factura_compra 
        INNER JOIN retencion_factura_comp
        on retencion_factura_comp.id_factura=factura_compra.id_factcompra
        WHERE (factura_compra.observacion like '%{$numero9}%') and id_empresa={$request->id_empresa}");
        if (count($res) >= 1) {
            return "repetido";
        }
    }
    public function guardar_factura(Request $request)
    {
        ini_set('max_execution_time', 950);
        
        $numero = $request->factura["nfactura"];
        $autorizacion = $request->factura["autorizacion"];
        $verificacion = DB::select("SELECT * FROM factura_compra WHERE descripcion like '%$numero%' and id_empresa={$request->usuario["id_empresa"]} and id_proveedor={$request->cliente}");
        if (count($verificacion) >= 1) {
            return "error numero";
        }

        if (isset($request->factura["id_orden"])) {
            $id_orden = $request->factura["id_orden"];
            DB::update("UPDATE orden_compra SET estado = 2 WHERE id_orden_compra = $id_orden");
        }
        if (isset($request->factura["id_orden"])) {
            $id_orden = $request->factura["id_orden"];
            DB::update("UPDATE orden_compra SET estado = 2 WHERE id_orden_compra = $id_orden");
        }
        if ($request->retencion_estado) {
            //si la retencionexiste ingresa a la condicion
            if ($request->valorretenciones[0]["iva"] != null || $request->valorretenciones[0]["renta"] != null) {

                $numero9 =  substr($request->factura["clave_acceso"], 24, 15);
                $res_ret = DB::select("SELECT * FROM factura_compra 
                 INNER JOIN retencion_factura_comp
                 on retencion_factura_comp.id_factura=factura_compra.id_factcompra
                WHERE (factura_compra.observacion like '%{$numero9}%') and id_empresa={$request->usuario["id_empresa"]}");
                if (count($res_ret) >= 1) {
                    return "repetido clave retencion";
                }
            }
        }
        $hoy = Carbon::now();

        $factc = new FacturaCompra();
        if ($request->retencion_estado) {
            if ($request->valorretenciones[0]["iva"] != null || $request->valorretenciones[0]["renta"] != null) {
                $factc->respuesta = "ERROR";
                $factc->observacion = $request->factura["clave_acceso"];
            }
        }
        $for_pago_emp = DB::select("SELECT * FROM forma_pagos where id_empresa={$request->usuario["id_empresa"]}");
        $factc->destino_pago = $request->factura["destino_pago"];
        $factc->gasto_import = intval(preg_replace('/[^0-9]+/', '', $request->factura["gastos"]), 10);
        $gato_import_fact=$factc->gasto_import;
        $factc->documento_tributario = intval(preg_replace('/[^0-9]+/', '', $request->factura["docutributario"]), 10);
        if($factc->gasto_import>0){
            $factc->id_importacion = $request->factura["importacion"];
        }
        $factc->orden_compra = $request->factura["orden_compra"];
        $factc->descripcion = $request->factura["nfactura"];
        //$factc->descripcion = $request->factura["observacion"];
        $factc->fech_emision = $request->factura["fecha_emision"];
        $factc->fech_validez = $request->factura["fecha_validez"];
        $factc->nro_autorizacion = $request->factura["autorizacion"];

        $factc->subtotal_sin_impuesto = $request->subtotal;
        $factc->subtotal_12 = $request->subtotal12;
        $factc->subtotal_0 = $request->subtotal0;
        $factc->subtotal_no_obj_iva = $request->no_impuesto;
        $factc->descuento = $request->descuento;
        $factc->valor_ice = 0;
        $factc->valor_irbpnr = 0;
        $factc->iva_12 = $request->valor12;
        $factc->total_factura = $request->total;
        $factc->modo_orden = 0;
        $factc->facturado_orden = 1;

        $factc->id_sustento = $request->factura["tipo_sustento"];
        $factc->id_proveedor = $request->cliente;
        //$factc->id_cliente_asoc = $request->id_cliente_asoc;
        $factc->id_user = $request->usuario["id"];
        $factc->id_empresa = $request->usuario["id_empresa"];
        $factc->id_establecimiento = $request->usuario["id_establecimiento"];
        $factc->id_punto_emision = $request->usuario["id_punto_emision"];
        //$factc->observacion = $request->factura["clave_acceso"];
        if (count($for_pago_emp) > 0) {
            $factc->id_forma_pagos = $for_pago_emp[0]->id_forma_pagos;
        }
        $factc->id_tipo_comprobante = $request->factura["tipo_comprobante"];
        $factc->save();

        $id = $factc->id_factcompra;
        $id_bodega_ingreso = "";
        $array_productos=[];
        for ($a = 0; $a < count($request->productos); $a++) {
            $dfactc = new Detalle_factura_compra();
            $dfactc->nombre = $request->productos[$a]["nombre"];
            if (isset($request->productos[$a]["nomb"])) {
                $dfactc->nomb = $request->productos[$a]["nomb"];
            }
            $dfactc->cantidad = $request->productos[$a]["cantidad"];
            $dfactc->precio = $request->productos[$a]["precio"];
            $dfactc->descuento = $request->productos[$a]["descuento"];
            $dfactc->p_descuento = $request->productos[$a]["p_descuento"];
            if($gato_import_fact>0 && $request->factura["importacion"]!==null){
                if($request->productos[$a]["importacion"]==true){
                    $dfactc->importacion = 1;
                }else{
                    $dfactc->importacion = 0;
                }  
            }
            if ($request->productos[$a]["p_descuento"] == 1) {
                $dfactc->total = ($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"]) - $request->productos[$a]["descuento"];
            } else {
                $dfactc->total = ($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"]) - (($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"]) * $request->productos[$a]["descuento"]) / 100;
            }
            $dfactc->id_iva = $request->productos[$a]["iva"];
            //$dfactc->id_ice = $request->productos[$a]["ice"];
            $dfactc->irbpnr = 0;
            $dfactc->id_producto = $request->productos[$a]["id_producto"];
            $dfactc->id_factura = $id;
            if (isset($request->productos[$a]["id_bodega"])) {
                $dfactc->id_bodega_detalle = $request->productos[$a]["id_bodega"];
            }
            
            if(isset($request->productos[$a]["id_bodega"])){
                    $v_producto = $request->productos[$a]["id_producto"];
                    $v_bodega = $request->productos[$a]["id_bodega"];
                    $v_empresa = $request->usuario["id_empresa"];
                    $v_res = DB::select("SELECT * FROM producto_bodega WHERE id_producto = $v_producto AND id_bodega = $v_bodega AND id_empresa = $v_empresa");
                    if (count($v_res) > 0) {
                        $dfactc->id_producto_bodega = $v_res[0]->id_producto_bodega;
                    }
            }
                
            
            if (isset($request->productos[$a]["proyecto"])) {
                $dfactc->id_proyecto = $request->productos[$a]["proyecto"];
            }
            $dfactc->save();
            $factcompradet = $dfactc->id_detalle_factura_compra;
            // if (isset($request->productos[$a]["id_bodega"])) {
            //     $v_producto = $request->productos[$a]["id_producto"];
            //     $v_bodega = $request->productos[$a]["id_bodega"];
            //     $v_empresa = $request->usuario["id_empresa"];
            //     $v_res = DB::select("SELECT * FROM producto_bodega WHERE id_producto = $v_producto AND id_bodega = $v_bodega AND id_empresa = $v_empresa");
                
            //     if (count($v_res) > 0) {
            //         array_push($array_productos,"Entro producto_bodega id_producto: $v_producto and id_bodega: $v_bodega");
            //         $cant = $request->productos[$a]["cantidad"];
            //         if ($request->productos[$a]["p_descuento"] == 1) {
            //             $total_ingreso = round(($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"]) - $request->productos[$a]["descuento"], 2);
            //         } else {
            //             $total_ingreso = round(($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"]) - (($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"]) * $request->productos[$a]["descuento"]) / 100, 2);
            //         }

            //         $idpb = $v_res[0]->id_producto_bodega;
            //         $cant_bodega = $v_res[0]->cantidad;
            //         $total_bodega = $v_res[0]->costo_total;

            //         $resultado_costo_unitario = ($total_bodega + $total_ingreso) / ($cant_bodega + $cant);

            //         DB::update("UPDATE producto_bodega SET cantidad = cantidad + $cant, costo_unitario = $resultado_costo_unitario, costo_total = cantidad * $resultado_costo_unitario WHERE id_producto_bodega = $idpb");

            //         $idempresa = $request->usuario["id_empresa"];
            //         //registro de egreso

            //         // $numegre = DB::select("SELECT num_ingreso FROM bodega_ingreso  WHERE id_empresa = $idempresa ORDER BY  num_ingreso DESC LIMIT 1");
            //         // $numeroingreso = "";
            //         // if (count($numegre) == 1) {
            //         //     $dato = $numegre[0]->num_ingreso;
            //         //     $tot = $dato + 1;
            //         //     $numeroingreso = $tot;
            //         // } else {
            //         //     $numeroingreso = 1;
            //         // }

            //         // $idbodega = $v_res[0]->id_bodega;
            //         // $savebode = 0;
            //         // if($savebode == 0){
            //         //     $ingreso = new BodegaIngreso();
            //         //     $ingreso->num_ingreso = $numeroingreso;
            //         //     $ingreso->fecha_ingreso = $hoy;
            //         //     $ingreso->tipo_ingreso = "Ingreso de Factura";
            //         //     $ingreso->observ_ingreso = 'Factura Compra: ' . $request->factura["nfactura"];
            //         //     $ingreso->id_proyecto = $request->productos[$a]["proyecto"];
            //         //     $ingreso->id_bodega = $idbodega;
            //         //     $ingreso->id_empresa = $request->usuario["id_empresa"];
            //         //     $ingreso->id_factura_compra = $id;
            //         //     $ingreso->save();
            //         //     $id_bodega_ingreso = $ingreso->id_bodega_ingreso;
            //         //     $savebode++;
            //         // }

            //         // $bed = new BodegaIngresoDetalle();
            //         // $bed->cantidad = $request->productos[$a]["cantidad"];
            //         // $bed->costo_unitario = $request->productos[$a]["precio"];
            //         // $bed->costo_total = $request->productos[$a]["cantidad"]*$request->productos[$a]["precio"];
            //         // $bed->id_bodega_ingreso = $id_bodega_ingreso;
            //         // $bed->id_producto = $request->productos[$a]["id_producto"];
            //         // $bed->id_detalle_factura_compra = $factcompradet;
            //         // $bed->id_proyecto = $request->productos[$a]["proyecto"];
            //         // $bed->save();
            //     } else {
            //         array_push($array_productos," NO Entro producto_bodega id_producto: $v_producto and id_bodega: $v_bodega");
            //         $prdb = new ProductoBodega();
            //         $prdb->cantidad = $request->productos[$a]["cantidad"];
            //         $prdb->costo_unitario = $request->productos[$a]["precio"];
            //         $prdb->costo_total = $request->productos[$a]["cantidad"] * $request->productos[$a]["precio"];
            //         $prdb->id_producto = $request->productos[$a]["id_producto"];
            //         $prdb->id_bodega = $request->productos[$a]["id_bodega"];
            //         $prdb->id_empresa = $request->usuario["id_empresa"];
            //         $prdb->save();

            //         $idpbn = $prdb->id_producto_bodega;

            //         $idempresa = $request->usuario["id_empresa"];

            //         $idpb = $prdb->id_producto_bodega;
            //         DB::update("UPDATE detalle_factura_compra SET id_producto_bodega = $idpbn WHERE id_detalle_factura_compra = $factcompradet");

            //         //registro de egreso

            //         // $numegre = DB::select("SELECT num_ingreso FROM bodega_ingreso  WHERE id_empresa = $idempresa ORDER BY  num_ingreso DESC LIMIT 1");
            //         // $numeroingreso = "";
            //         // if (count($numegre) == 1) {
            //         //     $dato = $numegre[0]->num_ingreso;
            //         //     $tot = $dato + 1;
            //         //     $numeroingreso = $tot;
            //         // } else {
            //         //     $numeroingreso = 1;
            //         // }

            //         // $reses = DB::select("SELECT * FROM producto_bodega WHERE id_producto_bodega = ".$prdb->id_producto_bodega);
            //         // $idbodega = $reses[0]->id_bodega;
            //         // $savebode = 0;
            //         // if($savebode == 0){
            //         //     $ingreso = new BodegaIngreso();
            //         //     $ingreso->num_ingreso = $numeroingreso;
            //         //     $ingreso->fecha_ingreso = $hoy;
            //         //     $ingreso->tipo_ingreso = "Ingreso de Factura";
            //         //     $ingreso->observ_ingreso = 'Factura Compra: ' . $request->factura["nfactura"];
            //         //     $ingreso->id_proyecto = $request->productos[$a]["proyecto"];
            //         //     $ingreso->id_bodega = $idbodega;
            //         //     $ingreso->id_empresa = $request->usuario["id_empresa"];
            //         //     $ingreso->id_factura_compra = $id;
            //         //     $ingreso->save();

            //         //     $id_bodega_ingreso = $ingreso->id_bodega_ingreso;
            //         //     $savebode++;
            //         // }

            //         // $bed = new BodegaIngresoDetalle();
            //         // $bed->cantidad = $request->productos[$a]["cantidad"];
            //         // $bed->costo_unitario = $request->productos[$a]["precio"];
            //         // $bed->costo_total = $request->productos[$a]["cantidad"]*$request->productos[$a]["precio"];
            //         // $bed->id_bodega_ingreso = $id_bodega_ingreso;
            //         // $bed->id_producto = $request->productos[$a]["id_producto"];
            //         // $bed->id_detalle_factura_compra = $factcompradet;
            //         // $bed->id_proyecto = $request->productos[$a]["proyecto"];
            //         // $bed->save();
            //     }
            // }
        }

        if ($request->pagos["estado"]) {
            for ($a = 0; $a < count($request->pagos["datos"]); $a++) {
                if ($request->pagos["datos"][$a]["metodo_pago"] == 'Anticipo') {
                    $pag = new Factura_compra_pagos();
                    $pag->id_forma_pagos = null;
                    $pag->total = $request->pagos["datos"][$a]["cantidad_pago"];
                    // $pag->numero_transaccion = $request->pagos["datos"][$a]["nro_trans"];
                    // $pag->id_banco = $request->pagos["datos"][$a]["banco_pago"];
                    // $pag->fecha_pago = $request->pagos["datos"][$a]["fecha_pago"];
                    $pag->plazo = 1;
                    $pag->unidad_tiempo = 'Dias';
                    $pag->estado = 1;
                    $pag->fecha = $hoy;
                    $pag->id_factura_compra = $id;
                    $pag->tiempos_pagos = 1;
                    $pag->anticipo = 1;
                    $pag->save();

                    $cpago = $request->pagos["datos"][$a]["cantidad_pago"];
                    //DB::update("UPDATE ctas_pagar SET abono = abono - $cpago WHERE id_proveedor = $request->cliente AND tipo = 3");
                    //$cliente = $request->cliente;
                    $abono = DB::select("SELECT * FROM ctas_pagar WHERE id_proveedor = $request->cliente AND tipo=3 ORDER BY id_ctaspagar ASC");
                    for ($i = 0; $i < count($abono); $i++) {
                        $id_ctascobrar = $abono[$i]->id_ctaspagar;
                        $pagado = $abono[$i]->abono;

                        if ($cpago > $pagado) {
                            $cpc = Cuentaporpagar::find($id_ctascobrar);
                            $cpc->valor_pagado = $cpc->valor_pagado + $pagado;
                            $cpc->abono = 0;
                            $cpc->save();

                            $cpago = $cpago - $pagado;
                        } else {
                            $cpc = Cuentaporpagar::find($id_ctascobrar);
                            $cpc->valor_pagado = $cpc->valor_pagado + $cpago;
                            $cpc->abono = $cpc->abono - $cpago;
                            $cpc->save();

                            $cpago = 0;
                        }
                    }

                    /*$resct = DB::select("SELECT * FROM ctas_cobrar WHERE id_cliente = $request->cliente AND tipo = 3");
                    if(count($resct)>=1){

                        DB::update("UPDATE ctas_cobrar SET abono = abono - $cpago WHERE id_cliente = $request->cliente AND tipo = 3");
                    }else{
                        $cpago = $request->pagos["datos"][$a]["cantidad_pago"];
                        DB::insert("INSERT ctas_cobrar SET abono = $cpago WHERE id_cliente = $request->cliente AND tipo = 3");
                    }*/
                } else {
                    if ($request->pagos["datos"][$a]["metodo_pago"] != null && $request->pagos["datos"][$a]["cantidad_pago"] != 0) {
                        $pag = new Factura_compra_pagos();
                        $pag->id_forma_pagos = $request->pagos["datos"][$a]["metodo_pago"];
                        $pag->total = $request->pagos["datos"][$a]["cantidad_pago"];
                        $pag->numero_transaccion = $request->pagos["datos"][$a]["nro_trans"];
                        $pag->id_banco = $request->pagos["datos"][$a]["banco_pago"];
                        $pag->fecha_pago = $request->pagos["datos"][$a]["fecha_pago"];
                        $pag->plazo = 1;
                        $pag->unidad_tiempo = 'Días';
                        $pag->estado = 1;
                        $pag->fecha = $hoy;
                        if (isset($request->pagos["datos"][$a]["plan_cuenta"])) {
                            $pag->id_plan_cuentas = $request->pagos["datos"][$a]["plan_cuenta"];
                        }
                        $pag->id_factura_compra = $id;
                        $pag->anticipo = null;
                        $pag->save();

                        $cxc = new Cuentaporpagar();
                        $cxc->num_cuota = 1;
                        $cxc->fecha_pago = $hoy;
                        $cxc->periodo_pagos = "Dias";
                        $cxc->valor_cuota = $request->pagos["datos"][$a]["cantidad_pago"];
                        $cxc->id_forma_pagos = $request->pagos["datos"][$a]["metodo_pago"];
                        if (isset($request->pagos["datos"][$a]["banco_pago"])) {
                            $cxc->id_banco = $request->pagos["datos"][$a]["banco_pago"];
                        }
                        if (isset($request->pagos["datos"][$a]["tarjeta"])) {
                            $cxc->numero_tarjeta = $request->pagos["datos"][$a]["nro_trans"];
                        }
                        if (isset($request->pagos["datos"][$a]["plan_cuenta"])) {
                            $cxc->id_plan_cuentas = $request->pagos["datos"][$a]["plan_cuenta"];
                        }
                        $cxc->fecha_factura = $request->factura["fecha_emision"];
                        $cxc->valor_pagado = 0;
                        $cxc->estado = 1;
                        $cxc->tipo = 2;
                        $cxc->id_factura_compra = $id;
                        $cxc->id_proveedor = $request->cliente;
                        $cxc->id_empresa = $request->usuario["id_empresa"];
                        $cxc->save();
                    }
                }
            }
        }
        $fecharec = "";
        if ($request->creditos["estado"]) {
            $pag = new Factura_compra_pagos();
            $pag->id_forma_pagos = 9;
            $pag->total = $request->creditos["monto"];
            $pag->plazo = $request->creditos["plazos"];
            $pag->unidad_tiempo = $request->creditos["periodo"];
            $pag->tiempos_pagos = $request->creditos["tiempo"];
            $pag->estado = 2;
            $pag->fecha = $hoy;
            $pag->id_factura_compra = $id;
            $pag->save();

            $hoy3 = Carbon::parse($request->factura["fecha_emision"]);
            $fd = "";
            for ($a = 0; $a < $request->creditos["plazos"]; $a++) {
                $cxc = new Cuentaporpagar();
                $cxc->num_cuota = $a + 1;
                if ($a < 1) {
                    if ($request->creditos["periodo"] == "Años") {
                        $fecharec = $hoy3->addYears($request->creditos["tiempo"]);
                        //$fd = $hoy3->addYears($request->creditos["tiempo"])->format('Y-m-d');
                        $fd = DB::select("SELECT DATE_ADD('{$request->factura['fecha_emision']}',INTERVAL {$request->creditos['tiempo']} YEAR) as dia_pago");
                    } else if ($request->creditos["periodo"] == "Meses") {
                        $fecharec = $hoy3->addMonths($request->creditos["tiempo"]);
                        //$fd = $hoy3->addMonths($request->creditos["tiempo"])->format('Y-m-d');
                        $fd = DB::select("SELECT DATE_ADD('{$request->factura['fecha_emision']}',INTERVAL {$request->creditos['tiempo']} MONTH ) as dia_pago");
                    } else if ($request->creditos["periodo"] == "Semanas") {
                        $fecharec = $hoy3->addWeeks($request->creditos["tiempo"]);
                        $fd = $hoy3->addWeeks($request->creditos["tiempo"])->format('Y-m-d');
                    } else {
                        $fecharec = $hoy3->addDays($request->creditos["tiempo"]);
                        //$fd = $hoy3->addDays($request->creditos["tiempo"])->format('Y-m-d');
                        $fd = DB::select("SELECT DATE_ADD('{$request->factura['fecha_emision']}',INTERVAL {$request->creditos['tiempo']} DAY ) as dia_pago");
                    }
                } else {
                    if ($request->creditos["periodo"] == "Años") {
                        $fd = $fecharec->addYears($request->creditos["tiempo"])->format('Y-m-d');
                    } else if ($request->creditos["periodo"] == "Meses") {
                        $fd = $fecharec->addMonths($request->creditos["tiempo"])->format('Y-m-d');
                    } else if ($request->creditos["periodo"] == "Semanas") {
                        $fd = $fecharec->addWeeks($request->creditos["tiempo"])->format('Y-m-d');
                    } else {
                        $fd = $fecharec->addDays($request->creditos["tiempo"])->format('Y-m-d');
                    }
                }
                $cxc->fecha_factura = $request->factura["fecha_emision"];
                if (gettype($fd) == 'array') {
                    $cxc->fecha_pago = $fd[0]->dia_pago;
                } else {
                    $cxc->fecha_pago = $fd;
                }

                $cxc->periodo_pagos = $request->creditos["periodo"];
                $cxc->valor_cuota = round($request->creditos["monto"] / $request->creditos["plazos"], 2, PHP_ROUND_HALF_UP);
                $cxc->valor_pagado = 0;
                $cxc->estado = 1;
                $cxc->tipo = 1;
                $cxc->id_factura_compra = $id;
                $cxc->id_proveedor = $request->cliente;
                $cxc->id_empresa = $request->usuario["id_empresa"];
                $cxc->save();
            }
        }

        //guardado de factura de retencion
        if ($request->retencion_estado) {
            //si la retencionexiste ingresa a la condicion
            if ($request->valorretenciones[0]["iva"] != null || $request->valorretenciones[0]["renta"] != null) {

                //si existe, suma +1 a la secuencial de retencion de punto de emision
                $s_facturasubstr = substr($request->factura["clave_acceso"], -19, -10);
                $sf = $s_facturasubstr + 1;
                $idp = $request->usuario["id_punto_emision"];
                DB::update("UPDATE punto_emision SET secuencial_retencion = '$sf' WHERE id_punto_emision = $idp");

                //recorre las retenciones existentes
                for ($i = 0; $i < count($request->valorretenciones); $i++) {
                    if ($request->valorretenciones[$i]["iva"] != null || $request->valorretenciones[$i]["renta"] != null) {
                        $ret = new RetencionFactura();
                        $ret->id_factura = $id;
                        //verifica si es retencion de iva
                        if (isset($request->valorretenciones[$i]["iva"]["id_retencion"])) {
                            $ret->id_retencion_iva = $request->valorretenciones[$i]["iva"]["id_retencion"];
                            $ret->baseiva = $request->valorretenciones[$i]["baseiva"];
                            $ret->porcentajeiva = $request->valorretenciones[$i]["porcentajeiva"];
                            $ret->cantidadiva = $request->valorretenciones[$i]["cantidadiva"];
                        }

                        //verifica si es retención de renta
                        if (isset($request->valorretenciones[$i]["renta"]["id_retencion"])) {
                            $ret->id_retencion_renta = $request->valorretenciones[$i]["renta"]["id_retencion"];
                            $ret->baserenta = $request->valorretenciones[$i]["baserenta"];
                            $ret->porcentajerenta = $request->valorretenciones[$i]["porcentajerenta"];
                            $ret->cantidadrenta = $request->valorretenciones[$i]["cantidadrenta"];
                        }


                        $ret->save();
                    }
                }
            }
        }

        //si guarda exitosamente genera la factura del pdf
        $facturaPDF = new generarPDF();
        $facturaPDF->Facturacompra($request);
        
        self::CabeceraBodegaIngreso($id, $request->factura["nfactura"],$request->sololotes);

        return FacturaCompra::select('factura_compra.*', 'empresa.*', 'proveedor.*', 'proveedor.email as emailpr', 'moneda.nomb_moneda as moneda', 'factura_compra.descuento as descuentototal', 'establecimiento.codigo as codigoes', 'punto_emision.codigo as codigope', 'establecimiento.direccion as direccion_establecimiento')
            ->join('empresa', 'empresa.id_empresa', '=', 'factura_compra.id_empresa')
            ->join('proveedor', 'proveedor.id_proveedor', '=', 'factura_compra.id_proveedor')
            ->join('establecimiento', 'establecimiento.id_empresa', '=', 'empresa.id_empresa')
            ->join('punto_emision', 'punto_emision.id_establecimiento', '=', 'establecimiento.id_establecimiento')
            ->join('moneda', 'moneda.id_moneda', '=', 'empresa.id_moneda')
            ->where("factura_compra.id_factcompra", "=", $id)
            ->orderByRaw('factura_compra.id_factcompra DESC')->get();
    }


    //funcion por revisar para guardar archivos con factura compra
//     public function guardar_factura(Request $request)
//     {
//         ini_set('max_execution_time', 900);
//         // $hoy3 = Carbon::parse($request->factura["fecha_emision"]);
//         // $exist_a="";
//         // $exist_b="";
//         // for ($a = 0; $a < $request->creditos["plazos"]; $a++) {
//         //     if ($a < 1) {
//         //         if ($request->creditos["periodo"] == "Años") {
//         //             $exist_a="Año";
//         //             $fecharec = $hoy3->addYears($request->creditos["tiempo"]);
//         //             $fd=DB::select("SELECT DATE_ADD('$request->factura['fecha_emision']',INTERVAL {$request->creditos['tiempo']} YEAR) as dia_pago");
//         //         } else if ($request->creditos["periodo"] == "Meses") {
//         //             $exist_a="Mes";
//         //             $fecharec = $hoy3->addMonths($request->creditos["tiempo"]);
//         //             $fd=DB::select("SELECT DATE_ADD('$request->factura['fecha_emision']',INTERVAL {$request->creditos['tiempo']} MONTH) as dia_pago");
//         //         } else if ($request->creditos["periodo"] == "Semanas") {
//         //             $exist_a="Semana";
//         //             $fecharec = $hoy3->addWeeks($request->creditos["tiempo"]);
//         //             //$fd = $hoy3->addWeeks($request->creditos["tiempo"])->format('Y-m-d');
//         //         } else {
//         //             $exist_a="Dia";
//         //             $fecharec = $hoy3->addDays($request->creditos["tiempo"]);

//         //             //dd("SELECT DATE_ADD('{$request->factura['fecha_emision']}',INTERVAL {$request->creditos['tiempo']} DAY) as dia_pago");
//         //             $fd=DB::select("SELECT DATE_ADD('{$request->factura['fecha_emision']}',INTERVAL {$request->creditos['tiempo']} DAY) as dia_pago");
//         //         }
//         //     } else {
//         //         if ($request->creditos["periodo"] == "Años") {
//         //             $exist_b="Año";
//         //             $fd = $fecharec->addYears($request->creditos["tiempo"])->format('Y-m-d');
//         //         } else if ($request->creditos["periodo"] == "Meses") {
//         //             $exist_b="Mes";
//         //             $fd = $fecharec->addMonths($request->creditos["tiempo"])->format('Y-m-d');
//         //         } else if ($request->creditos["periodo"] == "Semanas") {
//         //             $exist_b="Semana";
//         //             $fd = $fecharec->addWeeks($request->creditos["tiempo"])->format('Y-m-d');
//         //         } else {
//         //             $exist_b="Dias";
//         //             $fd = $fecharec->addDays($request->creditos["tiempo"])->format('Y-m-d');
//         //         }
//         //     }
//         // }

//         // if(gettype($fd)=='array'){
//         //     dd("existe a".$exist_a." existe b ". $exist_b." ".$fd[0]->dia_pago);
//         // }else{
//         //     dd("existe a".$exist_a." existe b ". $exist_b." ".$fd);
//         // }

//         $request->factura = json_decode($request->factura, true);
//         $request->productos = json_decode($request->productos, true);
//         $request->empresa = json_decode($request->empresa, true);
//         $request->usuario = json_decode($request->usuario, true);
//         $request->cliente = json_decode($request->cliente, true);
//         $request->subtotal = json_decode($request->subtotal, true);
//         $request->subtotal12 = json_decode($request->subtotal12, true);
//         $request->valor12 = json_decode($request->valor12, true);
//         $request->subtotal0 = json_decode($request->subtotal0, true);
//         $request->valor0 = json_decode($request->valor0, true);
//         $request->no_impuesto = json_decode($request->no_impuesto, true);
//         $request->exento = json_decode($request->exento, true);
//         $request->descuento = json_decode($request->descuento, true);
//         $request->total = json_decode($request->total, true);
//         $request->total_pendiente = json_decode($request->total_pendiente, true);
//         $request->total_pagado = json_decode($request->total_pagado, true);
//         $request->propinapr = json_decode($request->propinapr, true);
//         $request->pp_descuento = json_decode($request->pp_descuento, true);
//         $request->creditos = json_decode($request->creditos, true);
//         $request->retencion_estado = json_decode($request->retencion_estado, true);
//         $request->valorretenciones = json_decode($request->valorretenciones, true);
//         $request->pagos = json_decode($request->pagos, true);
//         $request->proveedor = json_decode($request->proveedor, true);
// //return dd(var_dump( !empty($request->factura["id_orden"])));
//         $numero = $request->factura["nfactura"];
//         $autorizacion = $request->factura["autorizacion"];
//         $verificacion = DB::select("SELECT * FROM factura_compra WHERE descripcion like '%$numero%' and id_empresa={$request->usuario["id_empresa"]} and id_proveedor={$request->cliente}");
//         if (count($verificacion) >= 1) {
//             return "error numero";
//         }

//         if (isset($request->factura["id_orden"]) && empty($request->factura["id_orden"])==false) {
//             $id_orden = $request->factura["id_orden"];
//             DB::update("UPDATE orden_compra SET estado = 2 WHERE id_orden_compra = $id_orden");
//         }
//         if (isset($request->factura["id_orden"]) && empty($request->factura["id_orden"])==false) {
//             $id_orden = $request->factura["id_orden"];
//             DB::update("UPDATE orden_compra SET estado = 2 WHERE id_orden_compra = $id_orden");
//         }
//         if ($request->retencion_estado) {
//             //si la retencionexiste ingresa a la condicion
//             if ($request->valorretenciones[0]["iva"] != null || $request->valorretenciones[0]["renta"] != null) {

//                 $numero9 =  substr($request->factura["clave_acceso"], 24, 15);
//                 $res_ret = DB::select("SELECT * FROM factura_compra 
//                  INNER JOIN retencion_factura_comp
//                  on retencion_factura_comp.id_factura=factura_compra.id_factcompra
//                 WHERE (factura_compra.observacion like '%{$numero9}%') and id_empresa={$request->usuario["id_empresa"]}");
//                 if (count($res_ret) >= 1) {
//                     return "repetido clave retencion";
//                 }
//             }
//         }
//         $hoy = Carbon::now();

//         $factc = new FacturaCompra();
//         if ($request->retencion_estado) {
//             if ($request->valorretenciones[0]["iva"] != null || $request->valorretenciones[0]["renta"] != null) {
//                 $factc->respuesta = "ERROR";
//                 $factc->observacion = $request->factura["clave_acceso"];
//             }
//         }
//         $for_pago_emp = DB::select("SELECT * FROM forma_pagos where id_empresa={$request->usuario["id_empresa"]}");
//         $factc->destino_pago = $request->factura["destino_pago"];
//         $factc->gasto_import = intval(preg_replace('/[^0-9]+/', '', $request->factura["gastos"]), 10);
//         $factc->documento_tributario = intval(preg_replace('/[^0-9]+/', '', $request->factura["docutributario"]), 10);
//         $factc->id_importacion = $request->factura["importacion"];
//         $factc->orden_compra = $request->factura["orden_compra"];
//         $factc->descripcion = $request->factura["nfactura"];
//         //$factc->descripcion = $request->factura["observacion"];
//         $factc->fech_emision = $request->factura["fecha_emision"];
//         $factc->fech_validez = $request->factura["fecha_validez"];
//         $factc->nro_autorizacion = $request->factura["autorizacion"];

//         $factc->subtotal_sin_impuesto = $request->subtotal;
//         $factc->subtotal_12 = $request->subtotal12;
//         $factc->subtotal_0 = $request->subtotal0;
//         $factc->subtotal_no_obj_iva = $request->no_impuesto;
//         $factc->descuento = $request->descuento;
//         $factc->valor_ice = 0;
//         $factc->valor_irbpnr = 0;
//         $factc->iva_12 = $request->valor12;
//         $factc->total_factura = $request->total;
//         $factc->modo_orden = 0;
//         $factc->facturado_orden = 1;

//         $factc->id_sustento = $request->factura["tipo_sustento"];
//         $factc->id_proveedor = $request->cliente;
//         //$factc->id_cliente_asoc = $request->id_cliente_asoc;
//         $factc->id_user = $request->usuario["id"];
//         $factc->id_empresa = $request->usuario["id_empresa"];
//         $factc->id_establecimiento = $request->usuario["id_establecimiento"];
//         $factc->id_punto_emision = $request->usuario["id_punto_emision"];
//         //$factc->observacion = $request->factura["clave_acceso"];
//         if (count($for_pago_emp) > 0) {
//             $factc->id_forma_pagos = $for_pago_emp[0]->id_forma_pagos;
//         }
//         $factc->id_tipo_comprobante = $request->factura["tipo_comprobante"];
//         $factc->save();
//         $id = $factc->id_factcompra;
//         $id_bodega_ingreso = "";

//         //guardar archivos xml y pdf de factura compra 
//         $directoryarch = constant("DATA_EMPRESA");
//         if (isset($request->filexml)) {
//             $dir_xml = $directoryarch . $request->usuario["id_empresa"] . "/comprobantes/facturacompra/factura_compra_xml";
//             $nombrexml = $factc->id_factcompra . ".xml";
//             $request->file('filexml')->move($dir_xml, $nombrexml);
//         }
//         if (isset($request->filepdf)) {
//             $dir_pdf = $directoryarch . $request->usuario["id_empresa"] . "/comprobantes/facturacompra/factura_compra_pdf";
//             $nombrepdf = $factc->id_factcompra . ".pdf";
//             $request->file('filepdf')->move($dir_pdf, $nombrepdf);
//         }


//         for ($a = 0; $a < count($request->productos); $a++) {
//             $dfactc = new Detalle_factura_compra();
//             $dfactc->nombre = $request->productos[$a]["nombre"];
//             if (isset($request->productos[$a]["nomb"]) && empty($request->productos[$a]["nomb"])==false) {
//                 $dfactc->nomb = $request->productos[$a]["nomb"];
//             }
//             $dfactc->cantidad = $request->productos[$a]["cantidad"];
//             $dfactc->precio = round($request->productos[$a]["precio"], 2);
//             $dfactc->descuento = $request->productos[$a]["descuento"];
//             $dfactc->p_descuento = $request->productos[$a]["p_descuento"];
//             if ($request->productos[$a]["p_descuento"] == 1) {
//                 $dfactc->total = round(($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"]) - $request->productos[$a]["descuento"], 2);
//             } else {
//                 $dfactc->total = round(($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"]) - (($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"]) * $request->productos[$a]["descuento"]) / 100, 2);
//             }
//             $dfactc->id_iva = $request->productos[$a]["iva"];
//             //$dfactc->id_ice = $request->productos[$a]["ice"];
//             $dfactc->irbpnr = 0;
//             $dfactc->id_producto = $request->productos[$a]["id_producto"];
//             $dfactc->id_factura = $id;
//             if (isset($request->productos[$a]["id_producto_bodega"]) && empty($request->productos[$a]["id_producto_bodega"])==false) {
//                 $dfactc->id_producto_bodega = $request->productos[$a]["id_producto_bodega"];
//             }
//             if (isset($request->productos[$a]["proyecto"]) && empty($request->productos[$a]["proyecto"])==false) {
//                 $dfactc->id_proyecto = $request->productos[$a]["proyecto"];
//             }
//             $dfactc->save();
//             $factcompradet = $dfactc->id_detalle_factura_compra;
//             if (isset($request->productos[$a]["id_bodega"]) && empty($request->productos[$a]["id_bodega"])==false) {
//                 $v_producto = $request->productos[$a]["id_producto"];
//                 $v_bodega = $request->productos[$a]["id_bodega"];
//                 $v_empresa = $request->usuario["id_empresa"];
//                 $v_res = DB::select("SELECT * FROM producto_bodega WHERE id_producto = $v_producto AND id_bodega = $v_bodega AND id_empresa = $v_empresa");
//                 if (count($v_res) >= 1) {
//                     $cant = $request->productos[$a]["cantidad"];
//                     if ($request->productos[$a]["p_descuento"] == 1) {
//                         $total_ingreso = round(($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"]) - $request->productos[$a]["descuento"], 2);
//                     } else {
//                         $total_ingreso = round(($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"]) - (($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"]) * $request->productos[$a]["descuento"]) / 100, 2);
//                     }

//                     $idpb = $v_res[0]->id_producto_bodega;
//                     $cant_bodega = $v_res[0]->cantidad;
//                     $total_bodega = $v_res[0]->costo_total;

//                     $resultado_costo_unitario = ($total_bodega + $total_ingreso) / ($cant_bodega + $cant);

//                     DB::update("UPDATE producto_bodega SET cantidad = cantidad + $cant, costo_unitario = $resultado_costo_unitario, costo_total = cantidad * $resultado_costo_unitario WHERE id_producto_bodega = $idpb");

//                     $idempresa = $request->usuario["id_empresa"];
//                     //registro de egreso

//                     // $numegre = DB::select("SELECT num_ingreso FROM bodega_ingreso  WHERE id_empresa = $idempresa ORDER BY  num_ingreso DESC LIMIT 1");
//                     // $numeroingreso = "";
//                     // if (count($numegre) == 1) {
//                     //     $dato = $numegre[0]->num_ingreso;
//                     //     $tot = $dato + 1;
//                     //     $numeroingreso = $tot;
//                     // } else {
//                     //     $numeroingreso = 1;
//                     // }

//                     // $idbodega = $v_res[0]->id_bodega;
//                     // $savebode = 0;
//                     // if($savebode == 0){
//                     //     $ingreso = new BodegaIngreso();
//                     //     $ingreso->num_ingreso = $numeroingreso;
//                     //     $ingreso->fecha_ingreso = $hoy;
//                     //     $ingreso->tipo_ingreso = "Ingreso de Factura";
//                     //     $ingreso->observ_ingreso = 'Factura Compra: ' . $request->factura["nfactura"];
//                     //     $ingreso->id_proyecto = $request->productos[$a]["proyecto"];
//                     //     $ingreso->id_bodega = $idbodega;
//                     //     $ingreso->id_empresa = $request->usuario["id_empresa"];
//                     //     $ingreso->id_factura_compra = $id;
//                     //     $ingreso->save();
//                     //     $id_bodega_ingreso = $ingreso->id_bodega_ingreso;
//                     //     $savebode++;
//                     // }

//                     // $bed = new BodegaIngresoDetalle();
//                     // $bed->cantidad = $request->productos[$a]["cantidad"];
//                     // $bed->costo_unitario = $request->productos[$a]["precio"];
//                     // $bed->costo_total = $request->productos[$a]["cantidad"]*$request->productos[$a]["precio"];
//                     // $bed->id_bodega_ingreso = $id_bodega_ingreso;
//                     // $bed->id_producto = $request->productos[$a]["id_producto"];
//                     // $bed->id_detalle_factura_compra = $factcompradet;
//                     // $bed->id_proyecto = $request->productos[$a]["proyecto"];
//                     // $bed->save();
//                 } else {
//                     $prdb = new ProductoBodega();
//                     $prdb->cantidad = $request->productos[$a]["cantidad"];
//                     $prdb->costo_unitario = $request->productos[$a]["precio"];
//                     $prdb->costo_total = $request->productos[$a]["cantidad"] * $request->productos[$a]["precio"];
//                     $prdb->id_producto = $request->productos[$a]["id_producto"];
//                     $prdb->id_bodega = $request->productos[$a]["id_bodega"];
//                     $prdb->id_empresa = $request->usuario["id_empresa"];
//                     $prdb->save();

//                     $idpbn = $prdb->id_producto_bodega;

//                     $idempresa = $request->usuario["id_empresa"];

//                     $idpb = $prdb->id_producto_bodega;
//                     DB::update("UPDATE detalle_factura_compra SET id_producto_bodega = $idpbn WHERE id_detalle_factura_compra = $factcompradet");

//                     //registro de egreso

//                     // $numegre = DB::select("SELECT num_ingreso FROM bodega_ingreso  WHERE id_empresa = $idempresa ORDER BY  num_ingreso DESC LIMIT 1");
//                     // $numeroingreso = "";
//                     // if (count($numegre) == 1) {
//                     //     $dato = $numegre[0]->num_ingreso;
//                     //     $tot = $dato + 1;
//                     //     $numeroingreso = $tot;
//                     // } else {
//                     //     $numeroingreso = 1;
//                     // }

//                     // $reses = DB::select("SELECT * FROM producto_bodega WHERE id_producto_bodega = ".$prdb->id_producto_bodega);
//                     // $idbodega = $reses[0]->id_bodega;
//                     // $savebode = 0;
//                     // if($savebode == 0){
//                     //     $ingreso = new BodegaIngreso();
//                     //     $ingreso->num_ingreso = $numeroingreso;
//                     //     $ingreso->fecha_ingreso = $hoy;
//                     //     $ingreso->tipo_ingreso = "Ingreso de Factura";
//                     //     $ingreso->observ_ingreso = 'Factura Compra: ' . $request->factura["nfactura"];
//                     //     $ingreso->id_proyecto = $request->productos[$a]["proyecto"];
//                     //     $ingreso->id_bodega = $idbodega;
//                     //     $ingreso->id_empresa = $request->usuario["id_empresa"];
//                     //     $ingreso->id_factura_compra = $id;
//                     //     $ingreso->save();

//                     //     $id_bodega_ingreso = $ingreso->id_bodega_ingreso;
//                     //     $savebode++;
//                     // }

//                     // $bed = new BodegaIngresoDetalle();
//                     // $bed->cantidad = $request->productos[$a]["cantidad"];
//                     // $bed->costo_unitario = $request->productos[$a]["precio"];
//                     // $bed->costo_total = $request->productos[$a]["cantidad"]*$request->productos[$a]["precio"];
//                     // $bed->id_bodega_ingreso = $id_bodega_ingreso;
//                     // $bed->id_producto = $request->productos[$a]["id_producto"];
//                     // $bed->id_detalle_factura_compra = $factcompradet;
//                     // $bed->id_proyecto = $request->productos[$a]["proyecto"];
//                     // $bed->save();
//                 }
//             }
//         }

//         if ($request->pagos["estado"]) {
//             for ($a = 0; $a < count($request->pagos["datos"]); $a++) {
//                 if ($request->pagos["datos"][$a]["metodo_pago"] == 'Anticipo') {
//                     $pag = new Factura_compra_pagos();
//                     $pag->id_forma_pagos = null;
//                     $pag->total = $request->pagos["datos"][$a]["cantidad_pago"];
//                     // $pag->numero_transaccion = $request->pagos["datos"][$a]["nro_trans"];
//                     // $pag->id_banco = $request->pagos["datos"][$a]["banco_pago"];
//                     // $pag->fecha_pago = $request->pagos["datos"][$a]["fecha_pago"];
//                     $pag->plazo = 1;
//                     $pag->unidad_tiempo = 'Dias';
//                     $pag->estado = 1;
//                     $pag->fecha = $hoy;
//                     $pag->id_factura_compra = $id;
//                     $pag->tiempos_pagos = 1;
//                     $pag->anticipo = 1;
//                     $pag->save();

//                     $cpago = $request->pagos["datos"][$a]["cantidad_pago"];
//                     //DB::update("UPDATE ctas_pagar SET abono = abono - $cpago WHERE id_proveedor = $request->cliente AND tipo = 3");
//                     //$cliente = $request->cliente;
//                     $abono = DB::select("SELECT * FROM ctas_pagar WHERE id_proveedor = $request->cliente AND tipo=3 ORDER BY id_ctaspagar ASC");
//                     for ($i = 0; $i < count($abono); $i++) {
//                         $id_ctascobrar = $abono[$i]->id_ctaspagar;
//                         $pagado = $abono[$i]->abono;

//                         if ($cpago > $pagado) {
//                             $cpc = Cuentaporpagar::find($id_ctascobrar);
//                             $cpc->valor_pagado = $cpc->valor_pagado + $pagado;
//                             $cpc->abono = 0;
//                             $cpc->save();

//                             $cpago = $cpago - $pagado;
//                         } else {
//                             $cpc = Cuentaporpagar::find($id_ctascobrar);
//                             $cpc->valor_pagado = $cpc->valor_pagado + $cpago;
//                             $cpc->abono = $cpc->abono - $cpago;
//                             $cpc->save();

//                             $cpago = 0;
//                         }
//                     }

//                     /*$resct = DB::select("SELECT * FROM ctas_cobrar WHERE id_cliente = $request->cliente AND tipo = 3");
//                     if(count($resct)>=1){

//                         DB::update("UPDATE ctas_cobrar SET abono = abono - $cpago WHERE id_cliente = $request->cliente AND tipo = 3");
//                     }else{
//                         $cpago = $request->pagos["datos"][$a]["cantidad_pago"];
//                         DB::insert("INSERT ctas_cobrar SET abono = $cpago WHERE id_cliente = $request->cliente AND tipo = 3");
//                     }*/
//                 } else {
//                     if ($request->pagos["datos"][$a]["metodo_pago"] != null && $request->pagos["datos"][$a]["cantidad_pago"] != 0) {
//                         $pag = new Factura_compra_pagos();
//                         $pag->id_forma_pagos = $request->pagos["datos"][$a]["metodo_pago"];
//                         $pag->total = $request->pagos["datos"][$a]["cantidad_pago"];
//                         $pag->numero_transaccion = $request->pagos["datos"][$a]["nro_trans"];
//                         $pag->id_banco = $request->pagos["datos"][$a]["banco_pago"];
//                         $pag->fecha_pago = $request->pagos["datos"][$a]["fecha_pago"];
//                         $pag->plazo = 1;
//                         $pag->unidad_tiempo = 'Días';
//                         $pag->estado = 1;
//                         $pag->fecha = $hoy;
//                         if (isset($request->pagos["datos"][$a]["plan_cuenta"]) && empty($request->pagos["datos"][$a]["plan_cuenta"])==false) {
//                             $pag->id_plan_cuentas = $request->pagos["datos"][$a]["plan_cuenta"];
//                         }
//                         $pag->id_factura_compra = $id;
//                         $pag->anticipo = null;
//                         $pag->save();

//                         $cxc = new Cuentaporpagar();
//                         $cxc->num_cuota = 1;
//                         $cxc->fecha_pago = $hoy;
//                         $cxc->periodo_pagos = "Dias";
//                         $cxc->valor_cuota = $request->pagos["datos"][$a]["cantidad_pago"];
//                         $cxc->id_forma_pagos = $request->pagos["datos"][$a]["metodo_pago"];
//                         if (isset($request->pagos["datos"][$a]["banco_pago"]) && empty($request->pagos["datos"][$a]["banco_pago"])==false) {
//                             $cxc->id_banco = $request->pagos["datos"][$a]["banco_pago"];
//                         }
//                         if (isset($request->pagos["datos"][$a]["tarjeta"]) && empty($request->pagos["datos"][$a]["tarjeta"])==false) {
//                             $cxc->numero_tarjeta = $request->pagos["datos"][$a]["nro_trans"];
//                         }
//                         if (isset($request->pagos["datos"][$a]["plan_cuenta"]) && empty($request->pagos["datos"][$a]["plan_cuenta"])==false) {
//                             $cxc->id_plan_cuentas = $request->pagos["datos"][$a]["plan_cuenta"];
//                         }
//                         $cxc->fecha_factura = $request->factura["fecha_emision"];
//                         $cxc->valor_pagado = 0;
//                         $cxc->estado = 1;
//                         $cxc->tipo = 2;
//                         $cxc->id_factura_compra = $id;
//                         $cxc->id_proveedor = $request->cliente;
//                         $cxc->id_empresa = $request->usuario["id_empresa"];
//                         $cxc->save();
//                     }
//                 }
//             }
//         }
//         $fecharec = "";
//         if ($request->creditos["estado"]) {
//             $pag = new Factura_compra_pagos();
//             $pag->id_forma_pagos = 9;
//             $pag->total = $request->creditos["monto"];
//             $pag->plazo = $request->creditos["plazos"];
//             $pag->unidad_tiempo = $request->creditos["periodo"];
//             $pag->tiempos_pagos = $request->creditos["tiempo"];
//             $pag->estado = 2;
//             $pag->fecha = $hoy;
//             $pag->id_factura_compra = $id;
//             $pag->save();

//             $hoy3 = Carbon::parse($request->factura["fecha_emision"]);
//             $fd = "";
//             for ($a = 0; $a < $request->creditos["plazos"]; $a++) {
//                 $cxc = new Cuentaporpagar();
//                 $cxc->num_cuota = $a + 1;
//                 if ($a < 1) {
//                     if ($request->creditos["periodo"] == "Años") {
//                         $fecharec = $hoy3->addYears($request->creditos["tiempo"]);
//                         //$fd = $hoy3->addYears($request->creditos["tiempo"])->format('Y-m-d');
//                         $fd = DB::select("SELECT DATE_ADD('{$request->factura['fecha_emision']}',INTERVAL {$request->creditos['tiempo']} YEAR) as dia_pago");
//                     } else if ($request->creditos["periodo"] == "Meses") {
//                         $fecharec = $hoy3->addMonths($request->creditos["tiempo"]);
//                         //$fd = $hoy3->addMonths($request->creditos["tiempo"])->format('Y-m-d');
//                         $fd = DB::select("SELECT DATE_ADD('{$request->factura['fecha_emision']}',INTERVAL {$request->creditos['tiempo']} MONTH ) as dia_pago");
//                     } else if ($request->creditos["periodo"] == "Semanas") {
//                         $fecharec = $hoy3->addWeeks($request->creditos["tiempo"]);
//                         $fd = $hoy3->addWeeks($request->creditos["tiempo"])->format('Y-m-d');
//                     } else {
//                         $fecharec = $hoy3->addDays($request->creditos["tiempo"]);
//                         //$fd = $hoy3->addDays($request->creditos["tiempo"])->format('Y-m-d');
//                         $fd = DB::select("SELECT DATE_ADD('{$request->factura['fecha_emision']}',INTERVAL {$request->creditos['tiempo']} DAY ) as dia_pago");
//                     }
//                 } else {
//                     if ($request->creditos["periodo"] == "Años") {
//                         $fd = $fecharec->addYears($request->creditos["tiempo"])->format('Y-m-d');
//                     } else if ($request->creditos["periodo"] == "Meses") {
//                         $fd = $fecharec->addMonths($request->creditos["tiempo"])->format('Y-m-d');
//                     } else if ($request->creditos["periodo"] == "Semanas") {
//                         $fd = $fecharec->addWeeks($request->creditos["tiempo"])->format('Y-m-d');
//                     } else {
//                         $fd = $fecharec->addDays($request->creditos["tiempo"])->format('Y-m-d');
//                     }
//                 }
//                 $cxc->fecha_factura = $request->factura["fecha_emision"];
//                 if (gettype($fd) == 'array') {
//                     $cxc->fecha_pago = $fd[0]->dia_pago;
//                 } else {
//                     $cxc->fecha_pago = $fd;
//                 }

//                 $cxc->periodo_pagos = $request->creditos["periodo"];
//                 $cxc->valor_cuota = round($request->creditos["monto"] / $request->creditos["plazos"], 2, PHP_ROUND_HALF_UP);
//                 $cxc->valor_pagado = 0;
//                 $cxc->estado = 1;
//                 $cxc->tipo = 1;
//                 $cxc->id_factura_compra = $id;
//                 $cxc->id_proveedor = $request->cliente;
//                 $cxc->id_empresa = $request->usuario["id_empresa"];
//                 $cxc->save();
//             }
//         }

//         //guardado de factura de retencion
//         if ($request->retencion_estado) {
//             //si la retencionexiste ingresa a la condicion
//             if ($request->valorretenciones[0]["iva"] != null || $request->valorretenciones[0]["renta"] != null) {

//                 //si existe, suma +1 a la secuencial de retencion de punto de emision
//                 $s_facturasubstr = substr($request->factura["clave_acceso"], -19, -10);
//                 $sf = $s_facturasubstr + 1;
//                 $idp = $request->usuario["id_punto_emision"];
//                 DB::update("UPDATE punto_emision SET secuencial_retencion = '$sf' WHERE id_punto_emision = $idp");

//                 //recorre las retenciones existentes
//                 for ($i = 0; $i < count($request->valorretenciones); $i++) {
//                     if ($request->valorretenciones[$i]["iva"] != null || $request->valorretenciones[$i]["renta"] != null) {
//                         $ret = new RetencionFactura();
//                         $ret->id_factura = $id;
//                         //verifica si es retencion de iva
//                         if (isset($request->valorretenciones[$i]["iva"]["id_retencion"]) && empty($request->valorretenciones[$i]["iva"]["id_retencion"])==false) {
//                             $ret->id_retencion_iva = $request->valorretenciones[$i]["iva"]["id_retencion"];
//                             $ret->baseiva = $request->valorretenciones[$i]["baseiva"];
//                             $ret->porcentajeiva = $request->valorretenciones[$i]["porcentajeiva"];
//                             $ret->cantidadiva = $request->valorretenciones[$i]["cantidadiva"];
//                         }

//                         //verifica si es retención de renta
//                         if (isset($request->valorretenciones[$i]["renta"]["id_retencion"]) && empty($request->valorretenciones[$i]["renta"]["id_retencion"])==false) {
//                             $ret->id_retencion_renta = $request->valorretenciones[$i]["renta"]["id_retencion"];
//                             $ret->baserenta = $request->valorretenciones[$i]["baserenta"];
//                             $ret->porcentajerenta = $request->valorretenciones[$i]["porcentajerenta"];
//                             $ret->cantidadrenta = $request->valorretenciones[$i]["cantidadrenta"];
//                         }


//                         $ret->save();
//                     }
//                 }
//             }
//         }

//         //si guarda exitosamente genera la factura del pdf
//         $facturaPDF = new generarPDF();
//         $facturaPDF->Facturacompra($request);

//         self::CabeceraBodegaIngreso($id, $request->factura["nfactura"]);

//         return FacturaCompra::select('factura_compra.*', 'empresa.*', 'proveedor.*', 'proveedor.email as emailpr', 'moneda.nomb_moneda as moneda', 'factura_compra.descuento as descuentototal', 'establecimiento.codigo as codigoes', 'punto_emision.codigo as codigope', 'establecimiento.direccion as direccion_establecimiento')
//             ->join('empresa', 'empresa.id_empresa', '=', 'factura_compra.id_empresa')
//             ->join('proveedor', 'proveedor.id_proveedor', '=', 'factura_compra.id_proveedor')
//             ->join('establecimiento', 'establecimiento.id_empresa', '=', 'empresa.id_empresa')
//             ->join('punto_emision', 'punto_emision.id_establecimiento', '=', 'establecimiento.id_establecimiento')
//             ->join('moneda', 'moneda.id_moneda', '=', 'empresa.id_moneda')
//             ->where("factura_compra.id_factcompra", "=", $id)
//             ->orderByRaw('factura_compra.id_factcompra DESC')->get();
//     }
    public function CabeceraBodegaIngreso($id, $nro_factura,$productos)
    {
        $hoy = Carbon::now();
        $factura = DB::select("SELECT * from factura_compra where id_factcompra=$id");
        $detalle = DB::select("SELECT distinct bodega.id_bodega from detalle_factura_compra 
                                INNER JOIN producto ON producto.id_producto=detalle_factura_compra.id_producto 
                                LEFT JOIN bodega ON bodega.id_bodega=detalle_factura_compra.id_bodega_detalle 
                                where producto.sector=1 and id_factura=$id");
        $proyecto = DB::select("SELECT * from proyecto where id_empresa={$factura[0]->id_empresa}");
        if (count($detalle) > 0) {
            for ($a = 0; $a < count($detalle); $a++) {
                $numegre = DB::select("SELECT num_ingreso FROM bodega_ingreso  WHERE id_empresa = {$factura[0]->id_empresa} ORDER BY  num_ingreso DESC LIMIT 1");
                $numeroingreso = "";
                if (count($numegre) == 1) {
                    $dato = $numegre[0]->num_ingreso;
                    $tot = $dato + 1;
                    $numeroingreso = $tot;
                } else {
                    $numeroingreso = 1;
                }
                $ingreso = new BodegaIngreso();
                $ingreso->num_ingreso = $numeroingreso;
                $ingreso->fecha_ingreso = $hoy;
                $ingreso->tipo_ingreso = "Ingreso de Factura";
                $ingreso->observ_ingreso = 'Factura Compra: ' . $nro_factura;
                $ingreso->id_proyecto = $proyecto[0]->id_proyecto;
                $ingreso->id_bodega = $detalle[$a]->id_bodega;
                $ingreso->id_empresa = $factura[0]->id_empresa;
                $ingreso->id_factura_compra = $id;
                $ingreso->save();
            }
        }
        if (count($detalle) > 0) {
            //self::DetalleBodegaIngreso($id, $ingreso->fecha_ingreso, $nro_factura,$productos);
            self::ProductoBodegaIngreso($id, $ingreso->fecha_ingreso, $nro_factura,$productos);
        }
    }
    public function ProductoBodegaIngreso($id, $fecha_ingreso, $nro_factura,$productos){

        $detalle = DB::select("SELECT detalle_factura_compra.cantidad,detalle_factura_compra.precio,detalle_factura_compra.p_descuento,detalle_factura_compra.descuento,detalle_factura_compra.id_producto,detalle_factura_compra.id_detalle_factura_compra,detalle_factura_compra.id_proyecto,bodega.id_bodega,producto.sector from detalle_factura_compra 
                                INNER JOIN producto ON producto.id_producto=detalle_factura_compra.id_producto 
                                INNER JOIN bodega ON bodega.id_bodega=detalle_factura_compra.id_bodega_detalle 
                                where producto.sector=1 and id_factura=$id");
        $factura=DB::select("SELECT * from factura_compra where id_factcompra=$id");
        
            for($i=0;$i<count($detalle);$i++){
                //if(isset($detalle[$i]->id_bodega) && $detalle[$i]->id_bodega!==null){
                    $v_res = DB::select("SELECT * FROM producto_bodega WHERE id_producto = {$detalle[$i]->id_producto} AND id_bodega = {$detalle[$i]->id_bodega}");
                    if (count($v_res)>0) {
                        $cant = $detalle[$i]->cantidad;
                        if ($detalle[$i]->p_descuento == 1) {
                            //$total_ingreso = round(($detalle[$i]->precio * $detalle[$i]->cantidad), 2);
                            $total_ingreso = $detalle[$i]->cantidad*($detalle[$i]->precio-($detalle[$i]->descuento/$detalle[$i]->cantidad));
                        } else {
                            $total_ingreso = $detalle[$i]->cantidad*($detalle[$i]->precio-(($detalle[$i]->precio*$detalle[$i]->descuento)/100));
                            //$total_ingreso = round(($detalle[$i]->precio * $detalle[$i]->cantidad) - (($detalle[$i]->precio * $detalle[$i]->cantidad) * $detalle[$i]->descuento) / 100, 2);
                        }
                        
                        $idpb = $v_res[0]->id_producto_bodega;
                        $cant_bodega = $v_res[0]->cantidad;
                        $total_bodega = $v_res[0]->costo_total;
                        //el (total del desc / cantidad) - cot unit 
                        $resultado_costo_unitario = ($total_bodega + $total_ingreso) / ($cant_bodega + $cant);

                        DB::update("UPDATE producto_bodega SET cantidad = cantidad + $cant, costo_unitario = $resultado_costo_unitario, costo_total = cantidad * $resultado_costo_unitario WHERE id_producto_bodega = $idpb");

                        $idempresa = $factura[0]->id_empresa;
                    } else {
                        // $costo_unit=0;
                        if ($detalle[$i]->p_descuento == 1) {
                            $costo_unit=$detalle[$i]->precio-($detalle[$i]->descuento/$detalle[$i]->cantidad);
                        }else{
                            $costo_unit=$detalle[$i]->precio-(($detalle[$i]->precio*$detalle[$i]->descuento)/100);
                        }
                        //precio-(precio*desc/100)
                        $prdb = new ProductoBodega();
                        $prdb->cantidad = $detalle[$i]->cantidad;
                        $prdb->costo_unitario = $costo_unit;
                        $prdb->costo_total = $detalle[$i]->cantidad * $prdb->costo_unitario;
                        $prdb->id_producto = $detalle[$i]->id_producto;
                        $prdb->id_bodega = $detalle[$i]->id_bodega;
                        $prdb->id_empresa = $factura[0]->id_empresa;
                        $prdb->save();

                        $idpbn = $prdb->id_producto_bodega;

                        $idempresa = $factura[0]->id_empresa;

                        $idpb = $prdb->id_producto_bodega;
                        //if(isset($idpbn) && $idpbn!==null){
                            DB::update("UPDATE detalle_factura_compra SET id_producto_bodega = $idpbn WHERE id_detalle_factura_compra = {$detalle[$i]->id_detalle_factura_compra}");
                        //}
                       

                    }
                //}
            }
        
        $detalle1 = DB::select("SELECT detalle_factura_compra.cantidad,detalle_factura_compra.precio,detalle_factura_compra.id_producto,detalle_factura_compra.id_detalle_factura_compra,detalle_factura_compra.id_proyecto,producto_bodega.id_bodega,detalle_factura_compra.id_producto_bodega,producto.sector from detalle_factura_compra 
                                INNER JOIN producto ON producto.id_producto=detalle_factura_compra.id_producto 
                                INNER JOIN producto_bodega ON producto_bodega.id_producto_bodega=detalle_factura_compra.id_producto_bodega 
                                where producto.sector=1 and id_factura=$id");
        if(count($detalle1) > 0){
            self::DetalleBodegaIngreso($id, $fecha_ingreso, $nro_factura,$productos);
        }
    }
    public function DetalleBodegaIngreso($id, $fecha, $nro_factura,$productos)
    {
        $hoy = Carbon::now();
        $detalle = DB::select("SELECT detalle_factura_compra.cantidad,detalle_factura_compra.precio,detalle_factura_compra.p_descuento,detalle_factura_compra.descuento,detalle_factura_compra.id_producto,detalle_factura_compra.id_detalle_factura_compra,detalle_factura_compra.id_proyecto,producto_bodega.id_bodega,detalle_factura_compra.id_producto_bodega,producto.sector from detalle_factura_compra 
                                INNER JOIN producto ON producto.id_producto=detalle_factura_compra.id_producto 
                                INNER JOIN producto_bodega ON producto_bodega.id_producto_bodega=detalle_factura_compra.id_producto_bodega 
                                where producto.sector=1 and id_factura=$id");
                                $conteo_det=0;
        if (count($detalle) > 0) {
            for ($a = 0; $a < count($detalle); $a++) {
                //if($detalle[$a]->sector==1){
                    //$observ= 'Factura Compra: ' . $nro_factura;
                    $conteo_det++;
                    $bodega_ingreso = DB::select("SELECT * from bodega_ingreso where id_bodega={$detalle[$a]->id_bodega} and id_factura_compra=$id");
                    if ($detalle[$a]->p_descuento == 1) {
                        $costo_unit=$detalle[$a]->precio-($detalle[$a]->descuento/$detalle[$a]->cantidad);
                    }else{
                        $costo_unit=$detalle[$a]->precio-(($detalle[$a]->precio*$detalle[$a]->descuento)/100);
                    }
                    $bed = new BodegaIngresoDetalle();
                    $bed->cantidad = $detalle[$a]->cantidad;
                    $bed->costo_unitario = $costo_unit;
                    $bed->costo_total = $bed->cantidad * $bed->costo_unitario;

                    $bed->id_bodega_ingreso = $bodega_ingreso[0]->id_bodega_ingreso;
                    $bed->id_producto = $detalle[$a]->id_producto;
                    $bed->id_detalle_factura_compra = $detalle[$a]->id_detalle_factura_compra;
                    $bed->id_proyecto = $detalle[$a]->id_proyecto;
                    $bed->save();
                    //ingreso ProductoBodegaLotes
                    $id_bi=$bodega_ingreso[0]->id_bodega_ingreso;
                    $id_bid=$bed->id_bodega_ingreso_detalle;
                    $id_producto=$detalle[$a]->id_producto;
                    $id_producto_bodega=$detalle[$a]->id_producto_bodega;
                    if($productos == null){
                        $productos = [];
                    }
                    if(gettype($productos) !== "string"){
                        if(count($productos)>0){
                            for ($i = 0; $i < count($productos); $i++) {
                                if($productos[$i]["id_producto"]==$detalle[$a]->id_producto && $productos[$i]["id_proyecto"]==$detalle[$a]->id_proyecto && $productos[$i]["id_bodega"]==$detalle[$a]->id_bodega && $conteo_det==$productos[$i]["index"]){
                                    $pbl = new ProductoBodegaLotes();
                                    $pbl->nombre = $productos[$i]["lote"];
                                    $pbl->cantidad_original = $productos[$i]["cantidad"];
                                    $pbl->cantidad_real = $productos[$i]["cantidad"];
                                    $pbl->fecha_fabricacion = $productos[$i]["fecha_fabricacion"];
                                    $pbl->fecha_vencimiento = $productos[$i]["fecha_vencimiento"];
                                    $pbl->id_producto=$detalle[$a]->id_producto;
                                    $pbl->id_producto_bodega=$detalle[$a]->id_producto_bodega;
                                    $pbl->id_bodega_ingreso=$bodega_ingreso[0]->id_bodega_ingreso;
                                    $pbl->id_bodega_ingreso_detalle=$id_bid;
                                    $pbl->save();
                                }
                                
                            }
                        }
                    }
                    
                    // $pl= new ProductoBodegaLotes();
                    // $pl->cantidad_original=$detalle[$a]->cantidad;
                    // $pl->cantidad_real=$detalle[$a]->cantidad;
                    // $pl->fecha_fabricacion=$hoy;
                    // $pl->fecha_vencimiento=$hoy;
                    // $pl->id_producto=$detalle[$a]->id_producto;
                    // $pl->id_producto_bodega=$detalle[$a]->id_producto_bodega;
                    // $pl->id_bodega_ingreso=$bodega_ingreso[0]->id_bodega_ingreso;
                    // $pl->id_bodega_ingreso_detalle=$id_bid;
                    // $pl->save();
                    //dd($productos[0]["lotes"][0]["cantidad"]);
                    
                        
                    
                    
                //}
            }
            
            
        }
        // for ($i = 0; $i < count($productos); $i++) {
        //     $pbl = new ProductoBodegaLotes();
        //     $pbl->nombre = $productos[$i]["lote"];
        //     $pbl->cantidad_original = $productos[$i]["cantidad"];
        //     $pbl->cantidad_real = $productos[$i]["cantidad"];
        //     $pbl->fecha_fabricacion = $productos[$i]["fecha_fabricacion"];
        //     $pbl->fecha_vencimiento = $productos[$i]["fecha_vencimiento"];
        //     for ($a = 0; $a < count($detalle); $a++) {
        //         $bodega_ingreso = DB::select("SELECT * from bodega_ingreso where id_bodega={$detalle[$a]->id_bodega} and id_factura_compra=$id");
        //         $pbl->id_producto = $detalle[$a]->id_producto;
        //         $pbl->id_producto_bodega = $detalle[$a]->id_producto_bodega;
        //         $pbl->id_bodega_ingreso = $bodega_ingreso[0]->id_bodega_ingreso;
        //         $pbl->id_bodega_ingreso_detalle = $id_bid;
        //     }
        //     $pbl->save();
        // }
        

    }
    public function IngresoLote($id,$productos){
        $hoy = Carbon::now();
        $detalle = DB::select("SELECT detalle_factura_compra.cantidad,detalle_factura_compra.precio,detalle_factura_compra.p_descuento,detalle_factura_compra.descuento,detalle_factura_compra.id_producto,detalle_factura_compra.id_detalle_factura_compra,detalle_factura_compra.id_proyecto,producto_bodega.id_bodega,detalle_factura_compra.id_producto_bodega,producto.sector, bodega_ingreso.id_bodega_ingreso,bodega_ingreso_detalle.id_bodega_ingreso_detalle
                                        from detalle_factura_compra 
                                        INNER JOIN producto ON producto.id_producto=detalle_factura_compra.id_producto 
                                        INNER JOIN producto_bodega ON producto_bodega.id_producto_bodega=detalle_factura_compra.id_producto_bodega 
                                        INNER JOIN bodega_ingreso_detalle ON bodega_ingreso_detalle.id_detalle_factura_compra=detalle_factura_compra.id_detalle_factura_compra 
                                        INNER JOIN bodega_ingreso ON bodega_ingreso_detalle.id_bodega_ingreso=bodega_ingreso.id_bodega_ingreso 
                                        where producto.sector=1 and detalle_factura_compra.id_factura=$id");
        $conteo_det=0;
        //dd($id);
        if (count($detalle) > 0) {
            
            for ($a = 0; $a < count($detalle); $a++) {
                $conteo_det++;
                if(count($productos)>0){
                    
                    for ($i = 0; $i < count($productos); $i++) {
                        //printf("Entro Productos Lote 1");
                        if($productos[$i]["id_producto"]==$detalle[$a]->id_producto && $productos[$i]["id_proyecto"]==$detalle[$a]->id_proyecto && $productos[$i]["id_bodega"]==$detalle[$a]->id_bodega && $conteo_det==$productos[$i]["index"]){
                            //printf("Entro Productos Lote 2");
                            if($productos[$i]["id_producto_bodega_lotes"]!==null){
                                //printf("Entro Productos Lote 3");
                                $pbl = ProductoBodegaLotes::findOrFail($productos[$i]["id_producto_bodega_lotes"]);
                                $pbl->nombre = $productos[$i]["lote"];
                                $pbl->cantidad_original = $productos[$i]["cantidad"];
                                
                                $pbl->fecha_fabricacion = $productos[$i]["fecha_fabricacion"];
                                $pbl->fecha_vencimiento = $productos[$i]["fecha_vencimiento"];
                                
                                $pbl->save();
                                //printf("Entro Productos Lote 3.2");
                            }
                             else{
                                //printf("Entro Productos Lote 4");
                                $pbl = new ProductoBodegaLotes();
                                $pbl->nombre = $productos[$i]["lote"];
                                $pbl->cantidad_original = $productos[$i]["cantidad"];
                                $pbl->cantidad_real = $productos[$i]["cantidad"];
                                $pbl->fecha_fabricacion = $productos[$i]["fecha_fabricacion"];
                                $pbl->fecha_vencimiento = $productos[$i]["fecha_vencimiento"];
                                $pbl->id_producto=$detalle[$a]->id_producto;
                                $pbl->id_producto_bodega=$detalle[$a]->id_producto_bodega;
                                $pbl->id_bodega_ingreso=$detalle[$a]->id_bodega_ingreso;
                                $pbl->id_bodega_ingreso_detalle=$detalle[$a]->id_bodega_ingreso_detalle;
                                $pbl->save();
                                //printf("Entro Productos Lote 4.2");
                            }
                            
                        }
                        
                    }
                }
            }
        }
    }
    public function verpagoproveedor(Request $request)
    {
        $factura = DB::select("SELECT * from factura_compra where id_factcompra={$request->id}");
        //solo traera el credito
        $pago_factura = DB::select("SELECT * from factura_compra_pagos where id_factura_compra={$request->id} and estado=2");
        $respuesta = "no";
        //si existe credito
        if (count($pago_factura) > 0) {
            $numero_monto = number_format($request->monto, 2, ".", "");
            if ($pago_factura[0]->total !== $numero_monto) {
                $cta_prov_total = DB::select("SELECT sum(valor_pagado) as total from ctas_pagar where id_factura_compra={$request->id} and tipo=1");
                if (count($cta_prov_total) > 0) {
                    if ($pago_factura[0]->total > $cta_prov_total[0]->total) {
                        $monto_pagar=number_format($pago_factura[0]->total-$cta_prov_total[0]->total,2,".","");
                        if($numero_monto>$monto_pagar){
                            $respuesta = "si";  
                        }
                    }else{
                        $respuesta = "si";
                    }
                }
            }
        }
        return $respuesta;
    }
    public function validar_clave_retencion(Request $request)
    {
        $valorcompra = DB::select("SELECT * FROM factura_compra WHERE id_factcompra = " . $request->factura["id_factcompra"]);
        if ($valorcompra[0]->respuesta != "Enviado") {
            if ($request->retencion_estado) {
                $s_facturasubstr_0 = substr($request->factura["clave_acceso"], -19, -10);
                $res_0 = DB::select("SELECT observacion FROM factura_compra WHERE observacion like '%{$s_facturasubstr_0}%' and id_empresa={$request->usuario["id_empresa"]} and id_factcompra<>{$request->factura["id_factcompra"]}");
                //dd("SELECT observacion FROM factura_compra WHERE observacion like '%{$s_facturasubstr_0}%' and id_empresa={$request->usuario["id_empresa"]}");
                if (count($res_0) > 0) {
                    return "repetido";
                }
            }
        }
    }
    public function editar_factura(Request $request)
    {
        ini_set('max_execution_time', 1050);
        $valorcompra = DB::select("SELECT * FROM factura_compra WHERE id_factcompra = " . $request->factura["id_factcompra"]);
        $for_pago_emp = DB::select("SELECT * FROM forma_pagos where id_empresa={$request->usuario["id_empresa"]}");
        $verificacion = DB::select("SELECT * FROM factura_compra WHERE descripcion like '%{$request->factura["nfactura"]}%' and id_factcompra={$request->factura["id_factcompra"]}");
        $ctas_fact0=DB::select("SELECT * from ctas_pagar WHERE id_factura_compra = {$request->factura["id_factcompra"]} AND tipo = 1");
        $ctas_fact_pagado0=DB::select("SELECT sum(valor_pagado) as valor_pagado from ctas_pagar WHERE id_factura_compra = {$request->factura["id_factcompra"]} AND tipo = 1");
        if (count($verificacion) < 1) {
            $verificacion_2 = DB::select("SELECT * FROM factura_compra WHERE descripcion like '%{$request->factura["nfactura"]}%' and id_empresa={$request->usuario["id_empresa"]} and id_proveedor={$request->cliente}");
            if (count($verificacion_2) >= 1) {
                return "error numero";
            }
        }
        if(count($ctas_fact0)>0){
            if ($request->creditos["estado"]) {
                if($ctas_fact_pagado0[0]->valor_pagado>0){
                    if(count($ctas_fact0)>$request->creditos["plazos"] || count($ctas_fact0)<$request->creditos["plazos"]){
                        return "error ctas_pagar";
                    }
                }
            }
        }
        if ($valorcompra[0]->respuesta != "Enviado") {
            //si la retencion ya fue enviada al sri no ingresara a esta condicional
            if ($request->retencion_estado) {
                //verifica si existe iva o renta en la retención
                if ($request->valorretenciones[0]["iva"] != null || $request->valorretenciones[0]["renta"] != null) {
                    $numero9 =  substr($request->factura["clave_acceso"], 30, 9);
                    $res_ret = DB::select("SELECT * FROM factura_compra WHERE (SUBSTRING(observacion, 31, 9) = '{$numero9}' or observacion='{$request->factura["clave_acceso"]}') and id_empresa={$request->usuario["id_empresa"]} and id_factcompra<>{$request->factura["id_factcompra"]}");
                    if (count($res_ret) >= 1) {
                        return "repetido clave retencion";
                    }
                }
            }
        }


        $hoy = Carbon::now();
        $exist_bodega_egre=0;
        //verifica si esta enviado al sri o no
        if ($valorcompra[0]->respuesta != "Enviado") {
            $factc = FacturaCompra::findOrFail($request->factura["id_factcompra"]);
            $factc->destino_pago = $request->factura["destino_pago"];
            $factc->gasto_import = intval(preg_replace('/[^0-9]+/', '', $request->factura["gastos"]), 10);
            $gato_import_fact=$factc->gasto_import;
            $factc->documento_tributario = intval(preg_replace('/[^0-9]+/', '', $request->factura["docutributario"]), 10);
            if($factc->gasto_import>0){
                $factc->id_importacion = $request->factura["importacion"];
            }
            //$factc->id_importacion = $request->factura["importacion"];
            $factc->orden_compra = $request->factura["orden_compra"];
            $factc->descripcion = $request->factura["nfactura"];
            //$factc->descripcion = $request->factura["observacion"];
            $factc->fech_emision = $request->factura["fecha_emision"];
            $factc->fech_validez = $request->factura["fecha_validez"];
            $factc->nro_autorizacion = $request->factura["autorizacion"];

            $factc->subtotal_sin_impuesto = $request->subtotal;
            $factc->subtotal_12 = $request->subtotal12;
            $factc->subtotal_0 = $request->subtotal0;
            $factc->subtotal_no_obj_iva = $request->no_impuesto;
            $factc->descuento = $request->descuento;
            $factc->valor_ice = 0;
            $factc->valor_irbpnr = 0;
            $factc->iva_12 = $request->valor12;
            $factc->total_factura = $request->total;
            $factc->modo_orden = 0;
            $factc->facturado_orden = 1;

            $factc->id_sustento = $request->factura["tipo_sustento"];
            $factc->id_proveedor = $request->cliente;
            //$factc->id_cliente_asoc = $request->id_cliente_asoc;
            $factc->id_user = $request->usuario["id"];
            $factc->id_empresa = $request->usuario["id_empresa"];
            $factc->id_establecimiento = $request->usuario["id_establecimiento"];
            $factc->id_punto_emision = $request->usuario["id_punto_emision"];
            $factc->observacion = $request->factura["clave_acceso"];
            $factc->id_proyecto = $request->factura["proyectos"];
            if (count($for_pago_emp) > 0) {
                $factc->id_forma_pagos = $for_pago_emp[0]->id_forma_pagos;
            }
            $factc->id_tipo_comprobante = $request->factura["tipo_comprobante"];
            $factc->save();

            $id = $request->factura["id_factcompra"];
            $savebode = 0;
            $id_bodega_ingreso = "";
            $detalles_existentes = [];
            for ($a = 0; $a < count($request->productos); $a++) {
                if (isset($request->productos[$a]["id_detalle_factura_compra"])) {
                    $rees = DB::select("SELECT * FROM detalle_factura_compra WHERE id_detalle_factura_compra = " . $request->productos[$a]["id_detalle_factura_compra"]);

                    $dfactc = Detalle_factura_compra::findOrFail($request->productos[$a]["id_detalle_factura_compra"]);
                    $dfactc->nombre = $request->productos[$a]["nombre"];
                    if (isset($request->productos[$a]["nomb"])) {
                        $dfactc->nomb = $request->productos[$a]["nomb"];
                    }
                    $dfactc->cantidad = $request->productos[$a]["cantidad"];
                    $dfactc->precio = $request->productos[$a]["precio"];
                    $dfactc->descuento = $request->productos[$a]["descuento"];
                    $dfactc->p_descuento = $request->productos[$a]["p_descuento"];
                    if($gato_import_fact>0 && $request->factura["importacion"]!==null){
                        if($request->productos[$a]["importacion"]==true){
                            $dfactc->importacion = 1;
                        }else{
                            $dfactc->importacion = 0;
                        }  
                    }
                    if ($request->productos[$a]["p_descuento"] == 1) {
                        $dfactc->total = ($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"]) - $request->productos[$a]["descuento"];
                    } else {
                        $dfactc->total = ($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"]) - (($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"]) * $request->productos[$a]["descuento"]) / 100;
                    }
                    $dfactc->id_iva = $request->productos[$a]["iva"];
                    $dfactc->id_ice = $request->productos[$a]["ice"];
                    $dfactc->irbpnr = 0;
                    $dfactc->id_producto = $request->productos[$a]["id_producto"];
                    $dfactc->id_factura = $id;
                    if (isset($request->productos[$a]["id_bodega"])) {
                        $dfactc->id_bodega_detalle = $request->productos[$a]["id_bodega"];
                    }
                    
                    if(isset($request->productos[$a]["id_bodega"]) && $request->exist_orden_compra==false){
                        $exist_bodega_egre++;
                            $v_producto = $request->productos[$a]["id_producto"];
                            $v_bodega = $request->productos[$a]["id_bodega"];
                            $v_empresa = $request->usuario["id_empresa"];
                            $v_res = DB::select("SELECT * FROM producto_bodega WHERE id_producto = $v_producto AND id_bodega = $v_bodega AND id_empresa = $v_empresa");
                            if (count($v_res) > 0) {
                                $dfactc->id_producto_bodega = $v_res[0]->id_producto_bodega;
                            }
                    }
                    // if (isset($request->productos[$a]["id_bodega"]) && $request->exist_orden_compra==false) {
                    //     $dfactc->id_producto_bodega = $request->productos[$a]["id_producto_bodega"];
                    // }
                    if (isset($request->productos[$a]["proyecto"])) {
                        $dfactc->id_proyecto = $request->productos[$a]["proyecto"];
                    }
                    $dfactc->save();

                    array_push($detalles_existentes, $request->productos[$a]["id_detalle_factura_compra"]);

                    $factcompradet = $request->productos[$a]["id_detalle_factura_compra"];

                    // if (isset($request->productos[$a]["id_producto_bodega"])) {
                    //     $valer = $rees[0]->cantidad;
                    //     $valerprecio = $rees[0]->precio;

                    //     $valorreal = ($request->productos[$a]["cantidad"]) - ($valer);
                    //     $valorrealprecio = ($request->productos[$a]["precio"]) - ($valerprecio);

                    //     if ($valorreal != 0 || $valorrealprecio != 0) {
                    //         $idpb = $request->productos[$a]["id_producto_bodega"];
                    //         //valores producto inical
                    //         $reses = DB::select("SELECT * FROM producto_bodega WHERE id_producto_bodega = " . $request->productos[$a]["id_producto_bodega"]);
                    //         $cantidad_bodega_i = $reses[0]->cantidad;
                    //         $total_bodega_i = $reses[0]->costo_total;
                    //         //valores de ingreso antes de cambio
                    //         $cantidad_ing_p = $rees[0]->cantidad;
                    //         if ($rees[0]->p_descuento == 1) {
                    //             $total_ing_p = round(($rees[0]->precio * $rees[0]->cantidad) - $rees[0]->descuento, 2);
                    //         } else {
                    //             $total_ing_p = round(($rees[0]->precio * $rees[0]->cantidad) - (($rees[0]->precio * $rees[0]->cantidad) * $rees[0]->descuento) / 100, 2);
                    //         }
                    //         $costo_total_i = $total_bodega_i - $total_ing_p;
                    //         $cantidad_total_i = $cantidad_bodega_i - $cantidad_ing_p;
                    //         $costo_unitario_i = $costo_total_i / $cantidad_total_i;
                    //         //DB::update("UPDATE producto_bodega SET cantidad = cantidad - $cantidad_total_i, costo_unitario = $costo_unitario_i, costo_total = $costo_total_i WHERE id_producto_bodega = $idpb");

                    //         //valores producto final
                    //         // $reses_f = DB::select("SELECT * FROM producto_bodega WHERE id_producto_bodega = " . $request->productos[$a]["id_producto_bodega"]);
                    //         // $cantidad_bodega_f = $reses_f[0]->cantidad;
                    //         // $total_bodega_f = $reses_f[0]->costo_total;
                    //         //valores del ingreso despues del cambio
                    //         $cantidad_f = $request->productos[$a]["cantidad"];
                    //         if ($request->productos[$a]["p_descuento"] == 1) {
                    //             $total_ing_f = round(($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"]) - $request->productos[$a]["descuento"], 2);
                    //         } else {
                    //             $total_ing_f = round(($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"]) - (($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"]) * $request->productos[$a]["descuento"]) / 100, 2);
                    //         }
                    //         $costo_total_f = $total_ing_f + $total_bodega_f;
                    //         $cantidad_total_f = $cantidad_bodega_f + $cantidad_f;
                    //         $costo_unitario_f = $costo_total_f / $cantidad_total_f;
                    //         //DB::update("UPDATE producto_bodega SET cantidad = cantidad + ($cantidad_f), costo_unitario = $costo_unitario_f, costo_total = $costo_total_f  WHERE id_producto_bodega = $idpb");

                    //         $costo_unitario = $request->productos[$a]["precio"];
                    //         $cantidad_nueva = $request->productos[$a]["cantidad"];
                    //         $total_nueva = $request->productos[$a]["precio"] * $request->productos[$a]["cantidad"];
                    //         //DB::update("UPDATE bodega_ingreso_detalle SET cantidad = $cantidad_nueva, costo_unitario = $costo_unitario, costo_total = $total_nueva WHERE id_detalle_factura_compra = " . $request->productos[$a]["id_detalle_factura_compra"]);
                    //     }
                    // } else if (isset($request->productos[$a]["id_bodega"])) {
                    //     $prdb = new ProductoBodega();
                    //     $prdb->cantidad = $request->productos[$a]["cantidad"];
                    //     $prdb->costo_unitario = $request->productos[$a]["precio"];
                    //     $prdb->costo_total = $request->productos[$a]["cantidad"] * $request->productos[$a]["precio"];
                    //     $prdb->id_producto = $request->productos[$a]["id_producto"];
                    //     $prdb->id_bodega = $request->productos[$a]["id_bodega"];
                    //     $prdb->id_empresa = $request->usuario["id_empresa"];
                    //     $prdb->save();

                    //     $idpbn = $prdb->id_producto_bodega;

                    //     $idempresa = $request->usuario["id_empresa"];

                    //     $idpb = $prdb->id_producto_bodega;
                    //     DB::update("UPDATE detalle_factura_compra SET id_producto_bodega = $idpbn WHERE id_detalle_factura_compra = $factcompradet");

                    //     //registro de egreso
                    //     $numegre = DB::select("SELECT num_ingreso FROM bodega_ingreso  WHERE id_empresa = $idempresa ORDER BY  num_ingreso DESC LIMIT 1");
                    //     $numeroingreso = "";
                    //     if (count($numegre) == 1) {
                    //         $dato = $numegre[0]->num_ingreso;
                    //         $tot = $dato + 1;
                    //         $numeroingreso = $tot;
                    //     } else {
                    //         $numeroingreso = 1;
                    //     }
                    //     $idbodega = $request->productos[$a]["id_bodega"];
                    //     if ($savebode == 0) {
                    //         $ingreso = new BodegaIngreso();
                    //         $ingreso->num_ingreso = $numeroingreso;
                    //         $ingreso->fecha_ingreso = $hoy;
                    //         $ingreso->tipo_ingreso = "ingreso de Factura";
                    //         $ingreso->observ_ingreso = 'Factura Compra';
                    //         $ingreso->id_proyecto = $request->factura["proyectos"];
                    //         $ingreso->id_bodega = $idbodega;
                    //         $ingreso->id_empresa = $request->usuario["id_empresa"];
                    //         $ingreso->id_factura_compra = $id;
                    //         $ingreso->save();

                    //         $id_bodega_ingreso = $ingreso->id_bodega_ingreso;
                    //         $savebode++;
                    //     }

                    //     $bed = new BodegaIngresoDetalle();
                    //     $bed->cantidad = $request->productos[$a]["cantidad"];
                    //     $bed->costo_unitario = $request->productos[$a]["precio"];
                    //     $bed->costo_total = $request->productos[$a]["cantidad"] * $request->productos[$a]["precio"];
                    //     $bed->id_bodega_ingreso = $id_bodega_ingreso;
                    //     $bed->id_producto = $request->productos[$a]["id_producto"];
                    //     $bed->id_detalle_factura_compra = $factcompradet;
                    //     $bed->id_proyecto = $request->productos[$a]["proyecto"];
                    //     $bed->save();
                    // }
                } else {
                    $dfactc = new Detalle_factura_compra();
                    $dfactc->nombre = $request->productos[$a]["nombre"];
                    $dfactc->cantidad = $request->productos[$a]["cantidad"];
                    $dfactc->precio = $request->productos[$a]["precio"];
                    $dfactc->descuento = $request->productos[$a]["descuento"];
                    $dfactc->p_descuento = $request->productos[$a]["p_descuento"];
                    if($gato_import_fact>0 && $request->factura["importacion"]!==null){
                        if($request->productos[$a]["importacion"]==true){
                            $dfactc->importacion = 1;
                        }else{
                            $dfactc->importacion = 0;
                        }  
                    }
                    if ($request->productos[$a]["p_descuento"] == 1) {
                        $dfactc->total = round(($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"]) - $request->productos[$a]["descuento"], 2);
                    } else {
                        $dfactc->total = round(($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"]) - (($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"]) * $request->productos[$a]["descuento"]) / 100, 2);
                    }
                    $dfactc->id_iva = $request->productos[$a]["iva"];
                    $dfactc->id_ice = $request->productos[$a]["ice"];
                    $dfactc->irbpnr = 0;
                    $dfactc->id_producto = $request->productos[$a]["id_producto"];
                    $dfactc->id_factura = $id;
                    if (isset($request->productos[$a]["id_bodega"])) {
                        $dfactc->id_bodega_detalle = $request->productos[$a]["id_bodega"];
                    }
                    
                    if(isset($request->productos[$a]["id_bodega"]) && $request->exist_orden_compra==false){
                            $v_producto = $request->productos[$a]["id_producto"];
                            $v_bodega = $request->productos[$a]["id_bodega"];
                            $v_empresa = $request->usuario["id_empresa"];
                            $v_res = DB::select("SELECT * FROM producto_bodega WHERE id_producto = $v_producto AND id_bodega = $v_bodega AND id_empresa = $v_empresa");
                            if (count($v_res) > 0) {
                                $dfactc->id_producto_bodega = $v_res[0]->id_producto_bodega;
                            }
                    }
                    if (isset($request->productos[$a]["proyecto"])) {
                        $dfactc->id_proyecto = $request->productos[$a]["proyecto"];
                    }
                    $dfactc->save();
                    $factcompradet = $dfactc->id_detalle_factura_compra;

                    array_push($detalles_existentes, $dfactc->id_detalle_factura_compra);

                    // if (isset($request->productos[$a]["id_producto_bodega"])) {
                    //     $reses = DB::select("SELECT * FROM producto_bodega WHERE id_producto_bodega = " . $request->productos[$a]["id_producto_bodega"]);
                    //     $idbodega = $reses[0]->id_bodega;
                    //     $cant = $request->productos[$a]["cantidad"];
                    //     $idpb = $request->productos[$a]["id_producto_bodega"];
                    //     if ($request->productos[$a]["p_descuento"] == 1) {
                    //         $total_ingreso = round(($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"]) - $request->productos[$a]["descuento"], 2);
                    //     } else {
                    //         $total_ingreso = round(($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"]) - (($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"]) * $request->productos[$a]["descuento"]) / 100, 2);
                    //     }

                    //     $cant_bodega = $reses[0]->cantidad;
                    //     $total_bodega = $reses[0]->costo_total;

                    //     $resultado_costo_unitario = ($total_bodega + $total_ingreso) / ($cant_bodega + $cant);

                    //     DB::update("UPDATE producto_bodega SET cantidad = cantidad + $cant, costo_unitario = $resultado_costo_unitario, costo_total = cantidad * $resultado_costo_unitario WHERE id_producto_bodega = $idpb");

                    //     $idempresa = $request->usuario["id_empresa"];
                    //     //registro de egreso
                    //     $numegre = DB::select("SELECT num_ingreso FROM bodega_ingreso  WHERE id_empresa = $idempresa ORDER BY  num_ingreso DESC LIMIT 1");
                    //     $numeroingreso = "";
                    //     if (count($numegre) == 1) {
                    //         $dato = $numegre[0]->num_ingreso;
                    //         $tot = $dato + 1;
                    //         $numeroingreso = $tot;
                    //     } else {
                    //         $numeroingreso = 1;
                    //     }

                    //     if ($savebode == 0) {
                    //         $ingreso = new BodegaIngreso();
                    //         $ingreso->num_ingreso = $numeroingreso;
                    //         $ingreso->fecha_ingreso = $hoy;
                    //         $ingreso->tipo_ingreso = "ingreso de Factura de " . $request->factura["nfactura"];
                    //         $ingreso->observ_ingreso = 'Factura Compra';
                    //         $ingreso->id_proyecto = $request->factura["proyectos"];
                    //         $ingreso->id_bodega = $idbodega;
                    //         $ingreso->id_empresa = $request->usuario["id_empresa"];
                    //         $ingreso->id_factura_compra = $id;
                    //         $ingreso->save();
                    //         $id_bodega_ingreso = $ingreso->id_bodega_ingreso;
                    //         $savebode++;
                    //     }

                    //     $bed = new BodegaIngresoDetalle();
                    //     $bed->cantidad = $request->productos[$a]["cantidad"];
                    //     $bed->costo_unitario = $request->productos[$a]["precio"];
                    //     $bed->costo_total = $request->productos[$a]["cantidad"] * $request->productos[$a]["precio"];
                    //     $bed->id_bodega_ingreso = $id_bodega_ingreso;
                    //     $bed->id_producto = $request->productos[$a]["id_producto"];
                    //     $bed->id_detalle_factura_compra = $factcompradet;
                    //     $bed->id_proyecto = $request->productos[$a]["proyecto"];
                    //     $bed->save();
                    // } else if (isset($request->productos[$a]["id_bodega"])) {
                    //     $prdb = new ProductoBodega();
                    //     $prdb->cantidad = $request->productos[$a]["cantidad"];
                    //     $prdb->costo_unitario = $request->productos[$a]["precio"];
                    //     $prdb->costo_total = $request->productos[$a]["cantidad"] * $request->productos[$a]["precio"];
                    //     $prdb->id_producto = $request->productos[$a]["id_producto"];
                    //     $prdb->id_bodega = $request->productos[$a]["id_bodega"];
                    //     $prdb->id_empresa = $request->usuario["id_empresa"];
                    //     $prdb->save();

                    //     $idpbn = $prdb->id_producto_bodega;

                    //     $idempresa = $request->usuario["id_empresa"];

                    //     $idpb = $prdb->id_producto_bodega;
                    //     DB::update("UPDATE detalle_factura_compra SET id_producto_bodega = $idpbn WHERE id_detalle_factura_compra = $factcompradet");

                    //     //registro de egreso
                    //     $numegre = DB::select("SELECT num_ingreso FROM bodega_ingreso  WHERE id_empresa = $idempresa ORDER BY  num_ingreso DESC LIMIT 1");
                    //     $numeroingreso = "";
                    //     if (count($numegre) == 1) {
                    //         $dato = $numegre[0]->num_ingreso;
                    //         $tot = $dato + 1;
                    //         $numeroingreso = $tot;
                    //     } else {
                    //         $numeroingreso = 1;
                    //     }

                    //     $reses = DB::select("SELECT * FROM producto_bodega WHERE id_producto_bodega = " . $prdb->id_producto_bodega);
                    //     $idbodega = $reses[0]->id_bodega;

                    //     if ($savebode == 0) {
                    //         $ingreso = new BodegaIngreso();
                    //         $ingreso->num_ingreso = $numeroingreso;
                    //         $ingreso->fecha_ingreso = $hoy;
                    //         $ingreso->tipo_ingreso = "ingreso de Factura de " . $request->factura["nfactura"];
                    //         $ingreso->observ_ingreso = 'Factura Compra';
                    //         $ingreso->id_proyecto = $request->factura["proyectos"];
                    //         $ingreso->id_bodega = $idbodega;
                    //         $ingreso->id_empresa = $request->usuario["id_empresa"];
                    //         $ingreso->id_factura_compra = $id;
                    //         $ingreso->save();

                    //         $id_bodega_ingreso = $ingreso->id_bodega_ingreso;
                    //         $savebode++;
                    //     }

                    //     $bed = new BodegaIngresoDetalle();
                    //     $bed->cantidad = $request->productos[$a]["cantidad"];
                    //     $bed->costo_unitario = $request->productos[$a]["precio"];
                    //     $bed->costo_total = $request->productos[$a]["cantidad"] * $request->productos[$a]["precio"];
                    //     $bed->id_bodega_ingreso = $id_bodega_ingreso;
                    //     $bed->id_producto = $request->productos[$a]["id_producto"];
                    //     $bed->id_detalle_factura_compra = $factcompradet;
                    //     $bed->id_proyecto = $request->productos[$a]["proyecto"];
                    //     $bed->save();
                    // }
                }
            }
            //borrado de detalles eliminados
            if (count($detalles_existentes) >= 1) {
                $bsbs = "SELECT * FROM detalle_factura_compra WHERE id_factura = $id AND ";
                foreach ($detalles_existentes as $dt_id) {
                    $bsbs .= "id_detalle_factura_compra != $dt_id AND ";
                }
                $res_bsbs = substr($bsbs, 0, -4);
                $seldbs = DB::select($res_bsbs);
                if ($seldbs) {
                    for ($i = 0; $i < count($seldbs); $i++) {
                        if (isset($seldbs[$i]->id_producto_bodega)) {
                            $rescuse_id_r = $seldbs[$i]->id_producto_bodega;
                            $rescuse_id_c = $seldbs[$i]->cantidad;
                            DB::update("UPDATE producto_bodega SET cantidad = cantidad - $rescuse_id_c, costo_total = (cantidad - $rescuse_id_c) * costo_unitario WHERE id_producto_bodega = $rescuse_id_r");
                        }
                        DB::delete("DELETE FROM bodega_ingreso_detalle WHERE id_detalle_factura_compra = " . $seldbs[$i]->id_detalle_factura_compra);
                        DB::delete("DELETE FROM detalle_factura_compra WHERE id_detalle_factura_compra = " . $seldbs[$i]->id_detalle_factura_compra);
                    }
                }
            }
        } else {
            $factc = FacturaCompra::findOrFail($request->factura["id_factcompra"]);
            $factc->destino_pago = $request->factura["destino_pago"];
            $factc->gasto_import = intval(preg_replace('/[^0-9]+/', '', $request->factura["gastos"]), 10);
            $gato_import_fact=$factc->gasto_import;
            $factc->documento_tributario = intval(preg_replace('/[^0-9]+/', '', $request->factura["docutributario"]), 10);
            if($factc->gasto_import>0){
                $factc->id_importacion = $request->factura["importacion"];
            }
            //$factc->id_importacion = $request->factura["importacion"];
            $factc->orden_compra = $request->factura["orden_compra"];
            $factc->descripcion = $request->factura["nfactura"];
            $factc->nro_autorizacion = $request->factura["autorizacion"];
            if (count($for_pago_emp) > 0) {
                $factc->id_forma_pagos = $for_pago_emp[0]->id_forma_pagos;
            }
            //$factc->id_forma_pagos = 1;
            $factc->modo_orden = 0;
            $factc->facturado_orden = 1;
            $factc->id_sustento = $request->factura["tipo_sustento"];
            $factc->id_tipo_comprobante = $request->factura["tipo_comprobante"];
            $factc->save();
            $id = $request->factura["id_factcompra"];

            $id_bodega_ingreso = "";
            for ($a = 0; $a < count($request->productos); $a++) {
                
                if (isset($request->productos[$a]["id_detalle_factura_compra"])) {
                    $dfactc = Detalle_factura_compra::findOrFail($request->productos[$a]["id_detalle_factura_compra"]);
                    if($gato_import_fact>0 && $request->factura["importacion"]!==null){
                        if($request->productos[$a]["importacion"]==true){
                            $dfactc->importacion = 1;
                        }else{
                            $dfactc->importacion = 0;
                        }  
                    }
                    $dfactc->save();
                }
                
                $factcompradet = $request->productos[$a]["id_detalle_factura_compra"];
                $verificabodega = DB::select("SELECT * FROM detalle_factura_compra WHERE id_detalle_factura_compra = $factcompradet");
                $valor_pbodega = $verificabodega[0]->id_producto_bodega;
                // if (!isset($valor_pbodega)) {
                //     if (isset($request->productos[$a]["id_bodega"])) {
                //         $v_producto = $request->productos[$a]["id_producto"];
                //         $v_bodega = $request->productos[$a]["id_bodega"];
                //         $v_empresa = $request->usuario["id_empresa"];
                //         $v_res = DB::select("SELECT * FROM producto_bodega WHERE id_producto = $v_producto AND id_bodega = $v_bodega AND id_empresa = $v_empresa");
                //         if (count($v_res) >= 1) {
                //             $cant = $request->productos[$a]["cantidad"];
                //             if ($request->productos[$a]["p_descuento"] == 1) {
                //                 $total_ingreso = round(($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"]) - $request->productos[$a]["descuento"], 2);
                //             } else {
                //                 $total_ingreso = round(($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"]) - (($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"]) * $request->productos[$a]["descuento"]) / 100, 2);
                //             }

                //             $idpb = $v_res[0]->id_producto_bodega;
                //             $cant_bodega = $v_res[0]->cantidad;
                //             $total_bodega = $v_res[0]->costo_total;

                //             $resultado_costo_unitario = ($total_bodega + $total_ingreso) / ($cant_bodega + $cant);

                //             DB::update("UPDATE producto_bodega SET cantidad = cantidad + $cant, costo_unitario = $resultado_costo_unitario, costo_total = (cantidad + $cant) * $resultado_costo_unitario WHERE id_producto_bodega = $idpb");

                //             $idempresa = $request->usuario["id_empresa"];
                //             //registro de egreso
                //             $bdg = $request->productos[$a]["id_bodega"];
                //             if (isset($bdg)) {
                //                 DB::update("UPDATE bodega_ingreso SET id_bodega = $bdg WHERE id_factura_compra = " . $request->factura["id_factcompra"]);
                //             }
                //         } else {
                //             $prdb = new ProductoBodega();
                //             $prdb->cantidad = $request->productos[$a]["cantidad"];
                //             $prdb->costo_unitario = $request->productos[$a]["precio"];
                //             $prdb->costo_total = $request->productos[$a]["cantidad"] * $request->productos[$a]["precio"];
                //             $prdb->id_producto = $request->productos[$a]["id_producto"];
                //             $prdb->id_bodega = $request->productos[$a]["id_bodega"];
                //             $prdb->id_empresa = $request->usuario["id_empresa"];
                //             $prdb->save();

                //             $idpbn = $prdb->id_producto_bodega;

                //             $idempresa = $request->usuario["id_empresa"];

                //             $idpb = $prdb->id_producto_bodega;
                //             DB::update("UPDATE detalle_factura_compra SET id_producto_bodega = $idpbn WHERE id_detalle_factura_compra = $factcompradet");

                //             //registro de egreso
                //             if (isset($request->productos[$a]["id_bodega"])) {
                //                 $bdg = $request->productos[$a]["id_bodega"];
                //                 DB::update("UPDATE bodega_ingreso SET id_bodega = $bdg WHERE id_factura_compra = " . $request->factura["id_factcompra"]);
                //             }
                //         }
                //     }
                // }
            }
        }
        DB::delete("DELETE FROM factura_compra_pagos WHERE id_factura_compra = $id AND estado = 1");
        DB::delete("DELETE FROM ctas_pagar WHERE id_factura_compra = $id AND tipo = 2");

        $ctas = DB::select("SELECT * FROM ctas_pagar WHERE id_factura_compra = $id AND tipo = 1");
        $datos = [];
        for ($g = 0; $g < count($ctas); $g++) {
            $id_cta = $ctas[$g]->id_ctaspagar;
            $res = DB::select("SELECT * FROM ctas_pagar_pagos,proveedor WHERE proveedor.id_proveedor=ctas_pagar_pagos.id_proveedor and proveedor.id_empresa={$request->usuario['id_empresa']} and referencia LIKE '%;{$id_cta};%'");
            for ($f = 0; $f < count($res); $f++) {
                $ref = explode(";", $res[$f]->referencia);
                for ($i = 0; $i < count($ref); $i++) {
                    if ($i % 4 == 1) {
                        array_push($datos, $ref[$i]);
                    }
                }
            }
        }
        if (count($datos) == 0) {
            DB::delete("DELETE FROM factura_compra_pagos WHERE id_factura_compra = $id AND estado = 2");
            DB::delete("DELETE FROM ctas_pagar WHERE id_factura_compra = $id AND tipo = 1");
            $fecharec = "";
            if ($request->creditos["estado"]) {
                $pag = new Factura_compra_pagos();
                if (count($for_pago_emp) > 0) {
                    $pag->id_forma_pagos = $for_pago_emp[0]->id_forma_pagos;
                } else {
                    $pag->id_forma_pagos = 9;
                }
                $pag->total = $request->creditos["monto"];
                $pag->plazo = $request->creditos["plazos"];
                $pag->unidad_tiempo = $request->creditos["periodo"];
                $pag->tiempos_pagos = $request->creditos["tiempo"];
                $pag->estado = 2;
                $pag->fecha = $hoy;
                $pag->id_factura_compra = $id;
                $pag->save();

                $hoy3 = Carbon::parse($request->factura["fecha_emision"]);
                $fd = "";
                for ($a = 0; $a < $request->creditos["plazos"]; $a++) {
                    $cxc = new Cuentaporpagar();
                    $cxc->num_cuota = $a + 1;
                    if ($a < 1) {
                        // if ($request->creditos["periodo"] == "Años") {
                        //     $fecharec = $hoy->addYears($request->creditos["tiempo"]);
                        //     $fd = $hoy->addYears($request->creditos["tiempo"])->format('Y-m-d');
                        // } else if ($request->creditos["periodo"] == "Meses") {
                        //     $fecharec = $hoy->addMonths($request->creditos["tiempo"]);
                        //     $fd = $hoy->addMonths($request->creditos["tiempo"])->format('Y-m-d');
                        // } else if ($request->creditos["periodo"] == "Semanas") {
                        //     $fecharec = $hoy->addWeeks($request->creditos["tiempo"]);
                        //     $fd = $hoy->addWeeks($request->creditos["tiempo"])->format('Y-m-d');
                        // } else {
                        //     $fecharec = $hoy->addDays($request->creditos["tiempo"]);
                        //     $fd = $hoy->addDays($request->creditos["tiempo"])->format('Y-m-d');
                        // }
                        if ($request->creditos["periodo"] == "Años") {
                            $fecharec = $hoy3->addYears($request->creditos["tiempo"]);
                            //$fd = $hoy3->addYears($request->creditos["tiempo"])->format('Y-m-d');
                            $fd = DB::select("SELECT DATE_ADD('{$request->factura['fecha_emision']}',INTERVAL {$request->creditos['tiempo']} YEAR) as dia_pago");
                        } else if ($request->creditos["periodo"] == "Meses") {
                            $fecharec = $hoy3->addMonths($request->creditos["tiempo"]);
                            //$fd = $hoy3->addMonths($request->creditos["tiempo"])->format('Y-m-d');
                            $fd = DB::select("SELECT DATE_ADD('{$request->factura['fecha_emision']}',INTERVAL {$request->creditos['tiempo']} MONTH ) as dia_pago");
                        } else if ($request->creditos["periodo"] == "Semanas") {
                            $fecharec = $hoy3->addWeeks($request->creditos["tiempo"]);
                            $fd = $hoy3->addWeeks($request->creditos["tiempo"])->format('Y-m-d');
                        } else {
                            $fecharec = $hoy3->addDays($request->creditos["tiempo"]);
                            //$fd = $hoy3->addDays($request->creditos["tiempo"])->format('Y-m-d');
                            $fd = DB::select("SELECT DATE_ADD('{$request->factura['fecha_emision']}',INTERVAL {$request->creditos['tiempo']} DAY ) as dia_pago");
                        }
                    } else {
                        if ($request->creditos["periodo"] == "Años") {
                            $fd = $fecharec->addYears($request->creditos["tiempo"])->format('Y-m-d');
                        } else if ($request->creditos["periodo"] == "Meses") {
                            $fd = $fecharec->addMonths($request->creditos["tiempo"])->format('Y-m-d');
                        } else if ($request->creditos["periodo"] == "Semanas") {
                            $fd = $fecharec->addWeeks($request->creditos["tiempo"])->format('Y-m-d');
                        } else {
                            $fd = $fecharec->addDays($request->creditos["tiempo"])->format('Y-m-d');
                        }
                    }
                    $cxc->fecha_factura = $request->factura["fecha_emision"];
                    if (gettype($fd) == 'array') {
                        $cxc->fecha_pago = $fd[0]->dia_pago;
                    } else {
                        $cxc->fecha_pago = $fd;
                    }

                    $cxc->periodo_pagos = $request->creditos["periodo"];
                    $cxc->valor_cuota = round($request->creditos["monto"] / $request->creditos["plazos"], 2, PHP_ROUND_HALF_UP);
                    $cxc->valor_pagado = 0;
                    $cxc->estado = 1;
                    $cxc->tipo = 1;
                    $cxc->id_factura_compra = $id;
                    $cxc->id_proveedor = $request->cliente;
                    $cxc->save();
                }
            }
        }else{
            $pagos_fact=DB::select("SELECT * from factura_compra_pagos WHERE id_factura_compra = $id AND estado = 2");
            $ctas_fact=DB::select("SELECT * from ctas_pagar WHERE id_factura_compra = $id AND tipo = 1");
            if ($request->creditos["estado"]) {
                if(count($pagos_fact)>0){
                    $pag =Factura_compra_pagos::find($pagos_fact[0]->id_factura_compra_pagos);
                    if (count($for_pago_emp) > 0) {
                        $pag->id_forma_pagos = $for_pago_emp[0]->id_forma_pagos;
                    } else {
                        $pag->id_forma_pagos = 9;
                    }
                    $pag->total = $request->creditos["monto"];
                    $pag->plazo = $request->creditos["plazos"];
                    $pag->unidad_tiempo = $request->creditos["periodo"];
                    $pag->tiempos_pagos = $request->creditos["tiempo"];
                    $pag->estado = 2;
                    $pag->fecha = $hoy;
                    //$pag->id_factura_compra = $id;
                    $pag->save();
                }
                $hoy3 = Carbon::parse($request->factura["fecha_emision"]);
                if(count($ctas_fact)>0){
                    if(count($ctas_fact)==$request->creditos["plazos"]){
                        for($a = 0; $a < count($ctas_fact); $a++){
                            $cxc = Cuentaporpagar::find($ctas_fact[$a]->id_ctaspagar);
                            //$cxc->num_cuota = $a + 1;
                            if ($a < 1) {
                                if ($request->creditos["periodo"] == "Años") {
                                    $fecharec = $hoy3->addYears($request->creditos["tiempo"]);
                                    //$fd = $hoy3->addYears($request->creditos["tiempo"])->format('Y-m-d');
                                    $fd = DB::select("SELECT DATE_ADD('{$request->factura['fecha_emision']}',INTERVAL {$request->creditos['tiempo']} YEAR) as dia_pago");
                                } else if ($request->creditos["periodo"] == "Meses") {
                                    $fecharec = $hoy3->addMonths($request->creditos["tiempo"]);
                                    //$fd = $hoy3->addMonths($request->creditos["tiempo"])->format('Y-m-d');
                                    $fd = DB::select("SELECT DATE_ADD('{$request->factura['fecha_emision']}',INTERVAL {$request->creditos['tiempo']} MONTH ) as dia_pago");
                                } else if ($request->creditos["periodo"] == "Semanas") {
                                    $fecharec = $hoy3->addWeeks($request->creditos["tiempo"]);
                                    $fd = $hoy3->addWeeks($request->creditos["tiempo"])->format('Y-m-d');
                                } else {
                                    $fecharec = $hoy3->addDays($request->creditos["tiempo"]);
                                    //$fd = $hoy3->addDays($request->creditos["tiempo"])->format('Y-m-d');
                                    $fd = DB::select("SELECT DATE_ADD('{$request->factura['fecha_emision']}',INTERVAL {$request->creditos['tiempo']} DAY ) as dia_pago");
                                }
                            } else {
                                if ($request->creditos["periodo"] == "Años") {
                                    $fd = $fecharec->addYears($request->creditos["tiempo"])->format('Y-m-d');
                                } else if ($request->creditos["periodo"] == "Meses") {
                                    $fd = $fecharec->addMonths($request->creditos["tiempo"])->format('Y-m-d');
                                } else if ($request->creditos["periodo"] == "Semanas") {
                                    $fd = $fecharec->addWeeks($request->creditos["tiempo"])->format('Y-m-d');
                                } else {
                                    $fd = $fecharec->addDays($request->creditos["tiempo"])->format('Y-m-d');
                                }
                            }
                            $cxc->fecha_factura = $request->factura["fecha_emision"];
                            if (gettype($fd) == 'array') {
                                $cxc->fecha_pago = $fd[0]->dia_pago;
                            } else {
                                $cxc->fecha_pago = $fd;
                            }

                            $cxc->periodo_pagos = $request->creditos["periodo"];
                            $cxc->valor_cuota = round($request->creditos["monto"] / $request->creditos["plazos"], 2, PHP_ROUND_HALF_UP);
                            //$cxc->valor_pagado = 0;
                            $cxc->estado = 1;
                            $cxc->tipo = 1;
                            //$cxc->id_factura_compra = $id;
                            //$cxc->id_proveedor = $request->cliente;
                            $cxc->save();
                        }
                    }else{
                        if(count($ctas_fact)<$request->creditos["plazos"]){
                            for ($a = 0; $a < $request->creditos["plazos"]; $a++) {
                                if(isset($ctas_fact[$a]->id_ctaspagar)){
                                    if($ctas_fact[$a]->num_cuota==$request->creditos["plazos"]){
                                       // for($a = 0; $a < count($ctas_fact); $a++){
                                            $cxc = Cuentaporpagar::find($ctas_fact[$a]->id_ctaspagar);
                                            //$cxc->num_cuota = $a + 1;
                                            if ($a < 1) {
                                                if ($request->creditos["periodo"] == "Años") {
                                                    $fecharec = $hoy3->addYears($request->creditos["tiempo"]);
                                                    //$fd = $hoy3->addYears($request->creditos["tiempo"])->format('Y-m-d');
                                                    $fd = DB::select("SELECT DATE_ADD('{$request->factura['fecha_emision']}',INTERVAL {$request->creditos['tiempo']} YEAR) as dia_pago");
                                                } else if ($request->creditos["periodo"] == "Meses") {
                                                    $fecharec = $hoy3->addMonths($request->creditos["tiempo"]);
                                                    //$fd = $hoy3->addMonths($request->creditos["tiempo"])->format('Y-m-d');
                                                    $fd = DB::select("SELECT DATE_ADD('{$request->factura['fecha_emision']}',INTERVAL {$request->creditos['tiempo']} MONTH ) as dia_pago");
                                                } else if ($request->creditos["periodo"] == "Semanas") {
                                                    $fecharec = $hoy3->addWeeks($request->creditos["tiempo"]);
                                                    $fd = $hoy3->addWeeks($request->creditos["tiempo"])->format('Y-m-d');
                                                } else {
                                                    $fecharec = $hoy3->addDays($request->creditos["tiempo"]);
                                                    //$fd = $hoy3->addDays($request->creditos["tiempo"])->format('Y-m-d');
                                                    $fd = DB::select("SELECT DATE_ADD('{$request->factura['fecha_emision']}',INTERVAL {$request->creditos['tiempo']} DAY ) as dia_pago");
                                                }
                                            } else {
                                                if ($request->creditos["periodo"] == "Años") {
                                                    $fd = $fecharec->addYears($request->creditos["tiempo"])->format('Y-m-d');
                                                } else if ($request->creditos["periodo"] == "Meses") {
                                                    $fd = $fecharec->addMonths($request->creditos["tiempo"])->format('Y-m-d');
                                                } else if ($request->creditos["periodo"] == "Semanas") {
                                                    $fd = $fecharec->addWeeks($request->creditos["tiempo"])->format('Y-m-d');
                                                } else {
                                                    $fd = $fecharec->addDays($request->creditos["tiempo"])->format('Y-m-d');
                                                }
                                            }
                                            $cxc->fecha_factura = $request->factura["fecha_emision"];
                                            if (gettype($fd) == 'array') {
                                                $cxc->fecha_pago = $fd[0]->dia_pago;
                                            } else {
                                                $cxc->fecha_pago = $fd;
                                            }
                
                                            $cxc->periodo_pagos = $request->creditos["periodo"];
                                            $cxc->valor_cuota = round($request->creditos["monto"] / $request->creditos["plazos"], 2, PHP_ROUND_HALF_UP);
                                            //$cxc->valor_pagado = 0;
                                            $cxc->estado = 1;
                                            $cxc->tipo = 1;
                                            //$cxc->id_factura_compra = $id;
                                            //$cxc->id_proveedor = $request->cliente;
                                            $cxc->save();
                                        //}
                                    }
                                }else{
                                    $cxc = new Cuentaporpagar();
                                    $cxc->num_cuota = $a + 1;
                                    if ($a < 1) {
                                        // if ($request->creditos["periodo"] == "Años") {
                                        //     $fecharec = $hoy->addYears($request->creditos["tiempo"]);
                                        //     $fd = $hoy->addYears($request->creditos["tiempo"])->format('Y-m-d');
                                        // } else if ($request->creditos["periodo"] == "Meses") {
                                        //     $fecharec = $hoy->addMonths($request->creditos["tiempo"]);
                                        //     $fd = $hoy->addMonths($request->creditos["tiempo"])->format('Y-m-d');
                                        // } else if ($request->creditos["periodo"] == "Semanas") {
                                        //     $fecharec = $hoy->addWeeks($request->creditos["tiempo"]);
                                        //     $fd = $hoy->addWeeks($request->creditos["tiempo"])->format('Y-m-d');
                                        // } else {
                                        //     $fecharec = $hoy->addDays($request->creditos["tiempo"]);
                                        //     $fd = $hoy->addDays($request->creditos["tiempo"])->format('Y-m-d');
                                        // }
                                        if ($request->creditos["periodo"] == "Años") {
                                            $fecharec = $hoy3->addYears($request->creditos["tiempo"]);
                                            //$fd = $hoy3->addYears($request->creditos["tiempo"])->format('Y-m-d');
                                            $fd = DB::select("SELECT DATE_ADD('{$request->factura['fecha_emision']}',INTERVAL {$request->creditos['tiempo']} YEAR) as dia_pago");
                                        } else if ($request->creditos["periodo"] == "Meses") {
                                            $fecharec = $hoy3->addMonths($request->creditos["tiempo"]);
                                            //$fd = $hoy3->addMonths($request->creditos["tiempo"])->format('Y-m-d');
                                            $fd = DB::select("SELECT DATE_ADD('{$request->factura['fecha_emision']}',INTERVAL {$request->creditos['tiempo']} MONTH ) as dia_pago");
                                        } else if ($request->creditos["periodo"] == "Semanas") {
                                            $fecharec = $hoy3->addWeeks($request->creditos["tiempo"]);
                                            $fd = $hoy3->addWeeks($request->creditos["tiempo"])->format('Y-m-d');
                                        } else {
                                            $fecharec = $hoy3->addDays($request->creditos["tiempo"]);
                                            //$fd = $hoy3->addDays($request->creditos["tiempo"])->format('Y-m-d');
                                            $fd = DB::select("SELECT DATE_ADD('{$request->factura['fecha_emision']}',INTERVAL {$request->creditos['tiempo']} DAY ) as dia_pago");
                                        }
                                    } else {
                                        if ($request->creditos["periodo"] == "Años") {
                                            $fd = $fecharec->addYears($request->creditos["tiempo"])->format('Y-m-d');
                                        } else if ($request->creditos["periodo"] == "Meses") {
                                            $fd = $fecharec->addMonths($request->creditos["tiempo"])->format('Y-m-d');
                                        } else if ($request->creditos["periodo"] == "Semanas") {
                                            $fd = $fecharec->addWeeks($request->creditos["tiempo"])->format('Y-m-d');
                                        } else {
                                            $fd = $fecharec->addDays($request->creditos["tiempo"])->format('Y-m-d');
                                        }
                                    }
                                    $cxc->fecha_factura = $request->factura["fecha_emision"];
                                    if (gettype($fd) == 'array') {
                                        $cxc->fecha_pago = $fd[0]->dia_pago;
                                    } else {
                                        $cxc->fecha_pago = $fd;
                                    }

                                    $cxc->periodo_pagos = $request->creditos["periodo"];
                                    $cxc->valor_cuota = round($request->creditos["monto"] / $request->creditos["plazos"], 2, PHP_ROUND_HALF_UP);
                                    $cxc->valor_pagado = 0;
                                    $cxc->estado = 1;
                                    $cxc->tipo = 1;
                                    $cxc->id_factura_compra = $id;
                                    $cxc->id_proveedor = $request->cliente;
                                    $cxc->save();
                                }
                            }
                        }
                    }
                    
                }
            }
            
            
        }
        if (isset($request->pagos)) {
            if (isset($request->pagos["estado"])) {
                if ($request->pagos["estado"]) {
                    $anticipo_creado = $request->anticipo_creado;
                    serialize($anticipo_creado);
                    if (isset($anticipo_creado)) {
                        if ($anticipo_creado > 0) {
                            //$cliente = $request->cliente;
                            $abono = DB::select("SELECT * FROM ctas_pagar WHERE id_proveedor = $request->cliente AND tipo=3 ORDER BY id_ctaspagar DESC");
                            //recorre los anticipos y actualiza si es anticipo parcial resta del anticipo creado caso contrario guarda el anticipo y el anticipo creado queda en 0
                            for ($i = 0; $i < count($abono); $i++) {
                                $id_ctascobrar = $abono[$i]->id_ctaspagar;
                                if ($anticipo_creado > $abono[$i]->valor_pagado) {
                                    $pagado = $abono[$i]->abono;

                                    $cpc = Cuentaporpagar::find($id_ctascobrar);
                                    $cpc->valor_pagado = 0;
                                    $cpc->abono = $cpc->abono + $abono[$i]->valor_pagado;
                                    $cpc->save();

                                    $anticipo_creado = $anticipo_creado - $abono[$i]->valor_pagado;
                                } else {
                                    $cpc = Cuentaporpagar::find($id_ctascobrar);
                                    $cpc->valor_pagado = $cpc->valor_pagado - $anticipo_creado;
                                    $cpc->abono = $cpc->abono + $anticipo_creado;
                                    $cpc->save();

                                    $anticipo_creado = 0;
                                }
                            }
                        }
                    }
                    for ($a = 0; $a < count($request->pagos["datos"]); $a++) {
                        if ($request->pagos["datos"][$a]["metodo_pago"] == 'Anticipo') {
                            $pag = new Factura_compra_pagos();
                            $pag->id_forma_pagos = null;
                            $pag->total = $request->pagos["datos"][$a]["cantidad_pago"];
                            $pag->plazo = 1;
                            $pag->unidad_tiempo = 'Dias';
                            // $pag->numero_transaccion = $request->pagos["datos"][$a]["nro_trans"];
                            // $pag->id_banco = $request->pagos["datos"][$a]["banco_pago"];
                            // $pag->fecha_pago = $request->pagos["datos"][$a]["fecha_pago"];
                            $pag->estado = 1;
                            $pag->fecha = $hoy;
                            $pag->id_factura_compra = $id;
                            $pag->tiempos_pagos = 1;
                            $pag->anticipo = 1;
                            if (isset($request->pagos["datos"][$a]["plan_cuenta"])) {
                                $pag->id_plan_cuentas = $request->pagos["datos"][$a]["plan_cuenta"];
                            }
                            $pag->save();

                            $cpago = $request->pagos["datos"][$a]["cantidad_pago"];
                            //DB::update("UPDATE ctas_pagar SET abono = abono - $cpago WHERE id_proveedor = $request->cliente AND tipo = 3");
                            //$cliente = $request->cliente;
                            $abono = DB::select("SELECT * FROM ctas_pagar WHERE id_proveedor = $request->cliente AND tipo=3 ORDER BY id_ctaspagar ASC");
                            //recorre los anticipos y actualiza si es anticipo parcial resta del anticipo creado caso contrario guarda el anticipo y el anticipo creado queda en 0
                            for ($i = 0; $i < count($abono); $i++) {
                                $id_ctascobrar = $abono[$i]->id_ctaspagar;
                                if ($cpago > $abono[$i]->abono) {
                                    $pagado = $abono[$i]->abono;

                                    $cpc = Cuentaporpagar::find($id_ctascobrar);
                                    $cpc->valor_pagado = $cpc->valor_pagado + $pagado;
                                    $cpc->abono = 0;
                                    $cpc->save();

                                    $cpago = $cpago - $abono[$i]->abono;
                                } else {
                                    $cpc = Cuentaporpagar::find($id_ctascobrar);
                                    $cpc->valor_pagado = $cpc->valor_pagado + $cpago;
                                    $cpc->abono = $cpc->abono - $cpago;
                                    $cpc->save();

                                    $cpago = 0;
                                }
                            }

                            /*$cpago = $request->pagos["datos"][$a]["cantidad_pago"];
                            DB::update("UPDATE ctas_cobrar SET abono = abono - $cpago WHERE id_cliente = $request->cliente AND tipo = 3");*/
                        } else {
                            if ($request->pagos["datos"][$a]["metodo_pago"] != null && $request->pagos["datos"][$a]["cantidad_pago"] != 0) {
                                $pag = new Factura_compra_pagos();
                                $pag->id_forma_pagos = $request->pagos["datos"][$a]["metodo_pago"];
                                $pag->total = $request->pagos["datos"][$a]["cantidad_pago"];
                                $pag->numero_transaccion = $request->pagos["datos"][$a]["nro_trans"];
                                $pag->id_banco = $request->pagos["datos"][$a]["banco_pago"];
                                $pag->fecha_pago = $request->pagos["datos"][$a]["fecha_pago"];
                                $pag->plazo = 1;
                                $pag->unidad_tiempo = 'Días';
                                $pag->estado = 1;
                                $pag->fecha = $hoy;
                                if (isset($request->pagos["datos"][$a]["plan_cuenta"])) {
                                    $pag->id_plan_cuentas = $request->pagos["datos"][$a]["plan_cuenta"];
                                }
                                $pag->id_factura_compra = $id;
                                $pag->anticipo = null;
                                $pag->save();

                                $cxc = new Cuentaporpagar();
                                $cxc->num_cuota = 1;
                                $cxc->fecha_pago = $hoy;
                                $cxc->periodo_pagos = "Dias";
                                $cxc->valor_cuota = $request->pagos["datos"][$a]["cantidad_pago"];
                                $cxc->id_forma_pagos = $request->pagos["datos"][$a]["metodo_pago"];
                                if (isset($request->pagos["datos"][$a]["banco_pago"])) {
                                    $cxc->id_banco = $request->pagos["datos"][$a]["banco_pago"];
                                }
                                if (isset($request->pagos["datos"][$a]["tarjeta"])) {
                                    $cxc->numero_tarjeta = $request->pagos["datos"][$a]["nro_trans"];
                                }
                                if (isset($request->pagos["datos"][$a]["plan_cuenta"])) {
                                    $cxc->id_plan_cuentas = $request->pagos["datos"][$a]["plan_cuenta"];
                                }
                                $cxc->fecha_factura = $request->factura["fecha_emision"];
                                $cxc->valor_pagado = 0;
                                $cxc->estado = 1;
                                $cxc->tipo = 2;
                                $cxc->id_factura_compra = $id;
                                $cxc->id_proveedor = $request->cliente;
                                $cxc->save();
                            }
                        }
                    }
                }
            }
        }

        if ($valorcompra[0]->respuesta != "Enviado") {
            //si la retencion ya fue enviada al sri no ingresara a esta condicional
            if ($request->retencion_estado) {
                //verifica si existe iva o renta en la retención
                if ($request->valorretenciones[0]["iva"] != null || $request->valorretenciones[0]["renta"] != null) {
                    //Borra la retencion antigua
                    $idp = $request->usuario["id_punto_emision"];
                    DB::delete("DELETE FROM retencion_factura_comp WHERE id_factura = $id");
                    $pt_em = DB::select("SELECT * from punto_emision where id_punto_emision={$idp}");
                    $s_facturasubstr = substr($request->factura["clave_acceso"], -19, -10);
                    $valor_int = intval($s_facturasubstr);
                    if ($valor_int >= $pt_em[0]->secuencial_retencion) {
                        $sf = $s_facturasubstr + 1;

                        DB::update("UPDATE punto_emision SET secuencial_retencion = '$sf' WHERE id_punto_emision = $idp");
                    }


                    //recorre las retenciones existentes y guarda los registros
                    for ($i = 0; $i < count($request->valorretenciones); $i++) {
                        if ($request->valorretenciones[$i]["iva"] != null || $request->valorretenciones[$i]["renta"] != null) {
                            $ret = new RetencionFactura();
                            $ret->id_factura = $id;
                            if (isset($request->valorretenciones[$i]["iva"]["id_retencion"])) {
                                $ret->id_retencion_iva = $request->valorretenciones[$i]["iva"]["id_retencion"];
                                $ret->baseiva = $request->valorretenciones[$i]["baseiva"];
                                if (strpos($request->valorretenciones[$i]["porcentajeiva"], "%") !== false) {
                                    $ret->porcentajeiva = $request->valorretenciones[$i]["porcentajeiva"];
                                } else {
                                    $ret->porcentajeiva = $request->valorretenciones[$i]["porcentajeiva"] . "%";
                                }

                                $ret->cantidadiva = $request->valorretenciones[$i]["cantidadiva"];
                            }
                            if (isset($request->valorretenciones[$i]["renta"]["id_retencion"])) {
                                $ret->id_retencion_renta = $request->valorretenciones[$i]["renta"]["id_retencion"];
                                $ret->baserenta = $request->valorretenciones[$i]["baserenta"];
                                if (strpos($request->valorretenciones[$i]["porcentajerenta"], "%") !== false) {
                                    $ret->porcentajerenta = $request->valorretenciones[$i]["porcentajerenta"];
                                } else {
                                    $ret->porcentajerenta = $request->valorretenciones[$i]["porcentajerenta"] . "%";
                                }

                                $ret->cantidadrenta = $request->valorretenciones[$i]["cantidadrenta"];
                            }


                            $ret->save();
                        }
                    }
                }
            }
        }
        if($exist_bodega_egre>0 && $request->por_orden==0){
            $pro = [];
            self::CabeceraBodegaIngreso($request->factura["id_factcompra"], $request->factura["nfactura"],$request->lotes_producto);
        }
        self::IngresoLote($request->factura["id_factcompra"],$request->lotes_producto);
        /*if($request->por_orden==0){
            self::CabeceraBodegaIngreso($id, $request->factura["nfactura"],$request->sololotes);
        }*/
        //verifica si esta enviado al sri si no esta enviado al sri recupera los registros de dicha retencion y su respectiva factura
        if ($valorcompra[0]->respuesta != "Enviado") {
            return FacturaCompra::select('factura_compra.*', 'empresa.*', 'proveedor.*', 'moneda.nomb_moneda as moneda', 'factura_compra.descuento as descuentototal', 'establecimiento.codigo as codigoes', 'punto_emision.codigo as codigope', 'establecimiento.direccion as direccion_establecimiento')
                ->join('empresa', 'empresa.id_empresa', '=', 'factura_compra.id_empresa')
                ->join('proveedor', 'proveedor.id_proveedor', '=', 'factura_compra.id_proveedor')
                ->join('establecimiento', 'establecimiento.id_empresa', '=', 'empresa.id_empresa')
                ->join('punto_emision', 'punto_emision.id_establecimiento', '=', 'establecimiento.id_establecimiento')
                ->join('moneda', 'moneda.id_moneda', '=', 'empresa.id_moneda')
                ->where("factura_compra.id_factcompra", "=", $id)
                ->orderByRaw('factura_compra.id_factcompra DESC')->get();
        } else {
            return "Enviado";
        }
    }
    public function listar_proveedor(Request $request)
    {
        //lista los proveedores dependiendo de la empresa
        $bs = $request->buscar;
        $id = $request->usuario;
        if ($bs == '') {
            $res = DB::select("SELECT prov.*,gprov.nombre_grupoprov FROM proveedor as prov
            LEFT JOIN grupo_proveedor as gprov
            on gprov.id_grupoprov=prov.id_grupo_proveedor
            WHERE prov.id_empresa = $id ORDER BY prov.id_proveedor DESC");
        } else {
            $res = DB::select("SELECT prov.*,gprov.nombre_grupoprov FROM proveedor as prov
            LEFT JOIN grupo_proveedor as gprov
            on gprov.id_grupoprov=prov.id_grupo_proveedor
            WHERE (prov.cod_proveedor LIKE '%$bs%' OR prov.nombre_proveedor LIKE '%$bs%' OR prov.identif_proveedor LIKE '%$bs%' OR prov.tipo_identificacion LIKE '%$bs%' OR prov.contacto LIKE '%$bs%' or prov.nombre_adicional LIKE '%$bs%') AND prov.id_empresa = $id ORDER BY prov.id_proveedor DESC");
        }
        return $res;
    }
    public function recuperar($id)
    {
        //recupera al igual que factura venta los datos de factura compra
        $ctas = DB::select("SELECT * FROM ctas_pagar WHERE id_factura_compra = $id AND tipo = 1");
        $datos = 0;
        //las referencia se convierte de string a array
        for ($g = 0; $g < count($ctas); $g++) {
            $id_cta = $ctas[$g]->id_ctaspagar;
            $res = DB::select("SELECT * FROM ctas_pagar_pagos WHERE referencia LIKE '%$id_cta%'");
            for ($f = 0; $f < count($res); $f++) {
                $ref = explode(";", $res[$f]->referencia);
                for ($i = 0; $i < count($ref); $i++) {
                    if ($i % 4 == 1) {
                        $datos++;
                    }
                }
            }
        }
        //datos generales de la afctura de compra
        $factura = DB::select("SELECT fc.*, (SELECT id_retfactcompra FROM retencion_factura_comp WHERE id_factura = fc.id_factcompra LIMIT 1) AS id_retfactcompra FROM factura_compra fc WHERE fc.id_factcompra = " . $id);
        $detalle_factura = DB::select("SELECT dfc.*, p.cod_principal, p.cod_alterno, p.sector, bod.nombre AS nombrebodega,bod.id_bodega  FROM detalle_factura_compra dfc INNER JOIN producto p ON dfc.id_producto=p.id_producto LEFT JOIN producto_bodega pb ON pb.id_producto_bodega = dfc.id_producto_bodega LEFT JOIN bodega bod ON bod.id_bodega=pb.id_bodega WHERE dfc.id_factura = " . $id);
        $proveedor = DB::select("SELECT * FROM proveedor WHERE id_proveedor = " . $factura[0]->id_proveedor);
        $producto_lotes=DB::select("SELECT producto_bodega_lotes.*,detalle_factura_compra.id_detalle_factura_compra FROM producto_bodega_lotes
        INNER JOIN bodega_ingreso_detalle
        ON bodega_ingreso_detalle.id_bodega_ingreso_detalle=producto_bodega_lotes.id_bodega_ingreso_detalle
        INNER JOIN detalle_factura_compra
        ON detalle_factura_compra.id_detalle_factura_compra=bodega_ingreso_detalle.id_detalle_factura_compra
        where detalle_factura_compra.id_factura=$id");

        $pagos = DB::select("SELECT pc.id_plan_cuentas AS plan_cuenta, pc.codcta AS cuenta, fp.id_forma_pagos AS metodo_pago, fp.id_banco AS banco_pago, fp.total AS cantidad_pago, fp.numero_transaccion AS nro_trans, fp.fecha_pago AS fecha_pago, pc.codcta AS cuenta, fp.id_plan_cuentas AS plan_cuenta,fp.anticipo FROM factura_compra_pagos fp LEFT JOIN plan_cuentas pc ON fp.id_plan_cuentas=pc.id_plan_cuentas WHERE fp.estado = 1 AND fp.id_factura_compra = " . $id);
        $creditos = DB::select("SELECT estado AS estado, unidad_tiempo AS periodo, tiempos_pagos AS tiempo, plazo AS plazos, total AS monto FROM factura_compra_pagos WHERE estado = 2 AND id_factura_compra = " . $id);
        $iva = DB::select("SELECT r.*,rf.baseiva, rf.porcentajeiva, rf.cantidadiva FROM retencion_factura_comp rf INNER JOIN retencion r ON rf.id_retencion_iva = r.id_retencion WHERE rf.id_factura = " . $id);
        $renta = DB::select("SELECT r.*, rf.baserenta, rf.porcentajerenta FROM retencion_factura_comp rf INNER JOIN retencion r ON rf.id_retencion_renta = r.id_retencion WHERE rf.id_factura = " . $id);

        $factura_creditos = "";
        if (count($creditos)) {
            $factura_creditos = $creditos[0];
        }

        return [
            "factura" => $factura[0],
            "detalle_factura" => $detalle_factura,
            "proveedor" => $proveedor[0],
            "pagos" => $pagos,
            "creditos" => $factura_creditos,
            "iva" => $iva,
            "renta" => $renta,
            "cuentas" => $datos,
            "productos_lote"=>$producto_lotes
        ];
    }
    public function traerbodegas(Request $rq)
    {
        //recupera las bodegas de dicha empresa dividido tambien por establecimiento
        $empresa = $rq->empresa;
        $establecimiento = $rq->establecimiento;
        $factura = DB::select("SELECT * FROM bodega WHERE id_empresa = $empresa AND id_establecimiento = $establecimiento");
        return $factura;
    }
    public function listar_productoxml(Request $request)
    {
        $bs = str_replace(" ", "%", $request->buscar);
        $empresa = $request->id_empresa;
        //recupera tanto productos como servicios $res = producto, $res1 = servicio
        $res =  DB::select("SELECT p.id_producto, p.cod_principal, p.nombre, p.descuento, p.iva, p.ice, p.sector, p.pvp_precio1 AS precio, ice.nombre AS nombreice FROM producto p LEFT JOIN ice ON ice.id_ice = p.ice WHERE p.sector = 1 AND (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$bs') AND p.id_empresa = $empresa ORDER BY p.codigo_barras DESC");
        $res1 = DB::select("SELECT p.id_producto, p.cod_principal, p.nombre, p.descuento, p.iva, p.ice, p.sector, p.pvp_precio1 AS precio, ice.nombre AS nombreice FROM producto p LEFT JOIN ice ON ice.id_ice = p.ice WHERE (p.cod_principal LIKE '%$bs%' OR p.cod_alterno LIKE '%$bs%' OR p.nombre LIKE '%$bs%' OR p.descripcion LIKE '%$bs%' OR p.codigo_barras = '$bs') AND p.id_empresa = $empresa AND p.sector = 2 AND p.tipo_servicio='Compra' ORDER BY p.codigo_barras DESC");
        //concatena los dos array en uno solo y lista los productos dentro de la lista
        $res2 = array_merge($res1, $res);
        return $res2;
    }
    public function savefilesfactcompra(Request $request)
    {
        $directory = constant("DATA_EMPRESA");
        if ($request->filexml) {
            $dir_xml = $directory . $request->id_empresa . "/comprobantes/facturacompra/factura_compra_xml";
            $nombrexml = $request->id_factcomp . ".xml";
            $request->file('filexml')->move($dir_xml, $nombrexml);
        }
        if ($request->filepdf) {
            $dir_pdf = $directory . $request->id_empresa . "/comprobantes/facturacompra/factura_compra_pdf";
            $nombrepdf = $request->id_factcomp . ".pdf";
            $request->file('filepdf')->move($dir_pdf, $nombrepdf);
        }
    }
    public function downloadxmlfactcompra(Request $request)
    {
        $directory = constant("DATA_EMPRESA");
        $file = $directory . $request->id_empresa . "/comprobantes/facturacompra/factura_compra_xml/" . $request->id_factcomp . ".xml";
        return response()->download($file);
    }
    public function downloadpdffactcompra(Request $request)
    {
        $directory = constant("DATA_EMPRESA");
        $file = $directory . $request->id_empresa . "/comprobantes/facturacompra/factura_compra_pdf/" . $request->id_factcomp . ".pdf";
        return response()->download($file);
    }
}
