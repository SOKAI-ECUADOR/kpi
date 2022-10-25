<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Bodega;
use Illuminate\Http\Request;
use App\Models\BodegaIngreso;
use App\Models\BodegaIngresoDetalle;
use App\Models\ProductoBodega;
use App\Models\Producto;
use App\Models\Marca;
use App\Models\Modelo;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;
use generarReportes;

use App\Models\Asientos;
use App\Models\Asientos_contables_detalle;

include 'class/generarReportes.php';

class BodegaIngresoController extends Controller
{

    public function index(Request $request, $idb, $ide)
    {
        $buscar = $request->buscar;
        if ($buscar == '') {
            //$impuestos = Impuesto::paginate($cantidadp); 
            $recupera = BodegaIngreso::select("*")->where("id_empresa", "=", $ide)->where("id_bodega", "=", $idb)->orderByRaw('id_bodega_ingreso DESC')->get();
        } else {
            $recupera = BodegaIngreso::select('*')
                ->where(function ($q) use ($buscar) {
                    $q->where('num_ingreso', 'like', '%' . $buscar . '%')
                        ->orWhere('fecha_ingreso', 'like', '%' . $buscar . '%')
                        ->orWhere('tipo_ingreso', 'like', '%' . $buscar . '%');
                })
                ->where("id_empresa", "=", $ide)->where("id_bodega", "=", $idb)->orderByRaw('id_bodega_ingreso DESC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }
    public function codingres($id)
    {
        $selnum = DB::select("SELECT num_ingreso FROM bodega_ingreso  WHERE id_empresa = $id ORDER BY  id_bodega_ingreso DESC LIMIT 1;");
        $principal = "";
        if (count($selnum) >= 1) {
            $dato = $selnum[0]->num_ingreso;
            $tot = $dato + 1;
            $principal = $tot;
        } else {
            $principal = 1;
        }
        return [
            "num_ingreso" => $principal
        ];
    }

    public function store(Request $request)
    {
        //num ingreso
        $selnum = DB::select("SELECT num_ingreso FROM bodega_ingreso  WHERE id_empresa = $request->id_empresa ORDER BY  id_bodega_ingreso DESC LIMIT 1;");
        $prin = "";
        if (count($selnum) >= 1) {
            $dato = $selnum[0]->num_ingreso;
            $tot = $dato + 1;
            $prin = $tot;
        } else {
            $prin = 1;
        }
        //ingreso encabezado
        $ingreso = new BodegaIngreso();
        $ingreso->num_ingreso = $prin;
        $ingreso->fecha_ingreso = $request->fecha_ingreso;
        $ingreso->tipo_ingreso = $request->tipo_ingreso;
        $ingreso->observ_ingreso = $request->observ_ingreso;
        $ingreso->id_proyecto = $request->id_proyecto;
        $ingreso->id_bodega = $request->id_bodega;
        $ingreso->id_empresa = $request->id_empresa;
        $ingreso->save();

        for ($d = 0; $d < count($request->contenidopr); $d++) {
            $sel = DB::select("SELECT * FROM `producto_bodega` WHERE `id_producto` = " . $request->contenidopr[$d]["id"] . " AND `id_bodega` =" . $request->id_bodega);
            if (count($sel) <= 0) {

                $prb = new ProductoBodega();
                $prb->cantidad = $request->contenidopr[$d]["cant_ingreso"];
                $prb->costo_unitario = $request->contenidopr[$d]["cost_unit_ingreso"];
                $prb->costo_total = $request->contenidopr[$d]["cost_tot_ingreso"];
                $prb->id_producto = $request->contenidopr[$d]["id"];
                $prb->id_bodega = $request->id_bodega;
                $prb->id_empresa = $request->id_empresa;
                $prb->save();

                $bid = new BodegaIngresoDetalle();
                $bid->cantidad = $request->contenidopr[$d]["cant_ingreso"];
                $bid->costo_unitario = $request->contenidopr[$d]["cost_unit_ingreso"];
                $bid->costo_total = $request->contenidopr[$d]["cost_tot_ingreso"];
                $bid->id_bodega_ingreso = $ingreso->id_bodega_ingreso;
                $bid->id_producto = $request->contenidopr[$d]["id"];
                if (isset($request->contenidopr[$d]["proyecto"])) {
                    $bid->id_proyecto = $request->contenidopr[$d]["proyecto"];
                }
                $bid->save();
            } else {

                $prb = ProductoBodega::findOrFail($sel[0]->id_producto_bodega);
                $prb->cantidad = $prb->cantidad + $request->contenidopr[$d]["cant_ingreso"];
                $prb->costo_total = $request->contenidopr[$d]["cost_tot_ingreso"] + $prb->costo_total;
                if ($prb->cantidad != 0) {
                    $prb->costo_unitario = $prb->costo_total / $prb->cantidad;
                } else {
                    $prb->costo_unitario = 0;
                }
                $prb->save();

                $bid = new BodegaIngresoDetalle();
                $bid->cantidad = $request->contenidopr[$d]["cant_ingreso"];
                $bid->costo_unitario = $request->contenidopr[$d]["cost_unit_ingreso"];
                $bid->costo_total = $request->contenidopr[$d]["cost_tot_ingreso"];
                $bid->id_bodega_ingreso = $ingreso->id_bodega_ingreso;
                $bid->id_producto = $request->contenidopr[$d]["id"];
                if (isset($request->contenidopr[$d]["proyecto"])) {
                    $bid->id_proyecto = $request->contenidopr[$d]["proyecto"];
                }
                $bid->save();
            }
        }
    }
    // lista productos para ingresar a bodega
    public function productoingreso(Request $request, $id)
    {

        $buscar = $request->buscar;
        if ($buscar == '') {
            $recupera = Producto::addSelect([
                'nombremarca' => Marca::select('nombre')
                    ->whereColumn('id_marca', 'producto.id_marca'),
                'nombremodelo' => Modelo::select('nombre')
                    ->whereColumn('id_modelo', 'producto.id_modelo')
            ])
                ->where('id_empresa', '=', $id)
                ->where('sector', '=', 1)
                ->orderByRaw('id_producto DESC')->get();
        } else {
            $recupera = Producto::addSelect([
                'nombremarca' => Marca::select('nombre')
                    ->whereColumn('id_marca', 'producto.id_marca'),
                'nombremodelo' => Modelo::select('nombre')
                    ->whereColumn('id_modelo', 'producto.id_modelo')
            ])
                ->where(function ($q) use ($buscar) {
                    $q->where('nombre', 'like', '%' . $buscar . '%')
                        ->orWhere('cod_principal', 'like', '%' . $buscar . '%')
                        ->orWhere('cod_alterno', 'like', '%' . $buscar . '%');
                })
                ->where('id_empresa', '=', $id)
                ->where('sector', '=', 1)
                ->orderByRaw('id_producto DESC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }
    //lista contenido de ingreso de bodega
    public function getingresobodega($id)
    {
        $ingreso = DB::select('SELECT * FROM bodega_ingreso WHERE id_bodega_ingreso=' . $id);
        $detalle = DB::select('SELECT d.*, p.cod_principal, p.cod_alterno, p.nombre FROM bodega_ingreso_detalle  d INNER JOIN producto p ON p.id_producto = d.id_producto WHERE id_bodega_ingreso =' . $id);
        return [
            "ingreso" => $ingreso[0],
            "ingreso_detalle" => $detalle
        ];
    }
    public function generarPdf(Request $request)
    {
        $query_bd_ingreso = "SELECT bdi.*,bd.nombre,bd.responsable,bd.ubicacion,bd.direccion,bd.telefono,pro.descripcion,emp.nombre_empresa,(select nombre_proveedor from factura_compra,proveedor where proveedor.id_proveedor=factura_compra.id_proveedor and factura_compra.id_factcompra=bdi.id_factura_compra limit 1) as nombre_proveedor
        from bodega_ingreso as bdi
        INNER JOIN bodega as bd
        on bd.id_bodega=bdi.id_bodega
        INNER JOIN empresa as emp
        on emp.id_empresa=bdi.id_empresa
        LEFT JOIN proyecto as pro
        on pro.id_proyecto=bdi.id_proyecto
        where bdi.id_bodega_ingreso={$request->id_bodega_ingreso}";
        $reporte_bd_ingreso = DB::select($query_bd_ingreso);
        $query_bd_ingreso = "SELECT bdid.*,pro.cod_principal,pro.nombre,proy.descripcion as descripcion_proy from bodega_ingreso_detalle as bdid
        INNER JOIN bodega_ingreso as bdi
        on bdi.id_bodega_ingreso=bdid.id_bodega_ingreso
        INNER JOIN producto as pro
        on pro.id_producto=bdid.id_producto
        LEFT JOIN proyecto as proy
        on proy.id_proyecto=bdid.id_proyecto
        where bdi.id_bodega_ingreso={$request->id_bodega_ingreso}";
        $reporte_bd_ingreso_detalle = DB::select($query_bd_ingreso);
        $empresa = DB::select("SELECT emp.*,'GRUPO SOLIS INGENIERIA ESPECIALIZADA' as nomb_empresa_ej,concat(usu.nombres,' ',usu.apellidos) as usuario from empresa as emp,user as usu where emp.id_empresa=usu.id_empresa and usu.id={$request->id_usuario}");
        //if($request->destinatario==null && $request->email==null){
        $pdf = new generarReportes();
        $strPDF = $pdf->PDFBodegaIng($reporte_bd_ingreso, $reporte_bd_ingreso_detalle, $empresa[0]);
        return response($strPDF, 200)->header('Content-Type', 'application/pdf');
        //}
        // }else{
        //     $carpetanombre2 = constant("DATA_EMPRESA").'/'.$empresa[0]->id_empresa;
        //     $carpeta2 = $carpetanombre2."/Bodega/".$select[0]->id_plan_cuentas."/".$select[0]->fecha_conciliacion;
        //     if (!file_exists($carpeta2)) {
        //         mkdir($carpeta2, 0755,true);
        //     }
        //     $pdf=new generarReportes();
        //     $strPDF =$pdf->PDFAsientos($select,$empresa,$select[0]->nomcta,$usuario[0]->nombre,$carpeta2);
        // }

    }
    public function verAsiento(Request $request, $id)
    {
        $codigo = DB::select("SELECT max(numero) as codigo FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.codigo like 'BI-%' and (asientos.estado='Activo' or asientos.estado is null) and proyecto.id_empresa=" . $request->id_empresa);
        $cod_asiento = "";
        if ($codigo) {
            $lenght = strlen($codigo[0]->codigo);
            $valor = $codigo[0]->codigo + 1;
            $cod_asiento = $codigo[0]->codigo + 1;
        } else {
            $cod_asiento = "1";
        }
        $cod_asiento_ant = "";
        if ($codigo) {
            $codigo_ant = DB::select("SELECT asientos.numero FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.id_asientos_comprobante=15 and (asientos.estado='Activo' or asientos.estado is null) and asientos.codigo_rol={$id} and proyecto.id_empresa=" . $request->id_empresa . " ORDER BY asientos.codigo desc limit 1");
            if ($codigo_ant) {
                $cod_asiento_ant = $codigo_ant[0]->numero;
            }
        }
        $proyecto = DB::select("SELECT id_proyecto from proyecto where id_empresa={$request->id_empresa} limit 1");
        $bodega = DB::select("SELECT plan_cuentas.id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,proyecto.id_proyecto,proyecto.descripcion,null as haber, bodega_ingreso_detalle.costo_total as debe,bodega_ingreso_detalle.id_bodega_ingreso_detalle
        from bodega_ingreso_detalle
        LEFT JOIN proyecto
        on proyecto.id_proyecto=bodega_ingreso_detalle.id_proyecto
        INNER JOIN bodega_ingreso
        on bodega_ingreso.id_bodega_ingreso=bodega_ingreso_detalle.id_bodega_ingreso
        INNER JOIN bodega
        on bodega.id_bodega=bodega_ingreso.id_bodega
        LEFT JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=bodega.id_plan_cuentas
        where bodega_ingreso_detalle.id_bodega_ingreso={$id}");
        $info_bod_ingreso = DB::select("SELECT * from bodega_ingreso where id_bodega_ingreso={$id}");
        $cuenta = DB::select("SELECT cuenta_bodega_ingreso.id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,proyecto.id_proyecto,proyecto.descripcion,null as debe, bodega_ingreso_detalle.costo_total as haber,bodega_ingreso_detalle.id_bodega_ingreso_detalle
        from bodega_ingreso_detalle
        LEFT JOIN proyecto
        on proyecto.id_proyecto=bodega_ingreso_detalle.id_proyecto
        INNER JOIN bodega_ingreso
        on bodega_ingreso.id_bodega_ingreso=bodega_ingreso_detalle.id_bodega_ingreso
        INNER JOIN bodega
        on bodega.id_bodega=bodega_ingreso.id_bodega
        INNER JOIN producto
        on producto.id_producto=bodega_ingreso_detalle.id_producto
        INNER JOIN cuenta_bodega_ingreso
        on cuenta_bodega_ingreso.id_empresa=bodega.id_empresa
        LEFT JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=cuenta_bodega_ingreso.id_plan_cuentas
        where bodega_ingreso_detalle.id_bodega_ingreso={$id} and cuenta_bodega_ingreso.id_bodega={$info_bod_ingreso[0]->id_bodega}");
        $fecha_emision=substr($info_bod_ingreso[0]->fecha_ingreso,0,-3);
        $anio_emision=substr($info_bod_ingreso[0]->fecha_ingreso,0,4);
        $fecha_cierre=DB::select("SELECT * 
                                    from asientos 
                                        where id_empresa={$request->id_empresa} 
                                        and cierre_contable='Cierre Mes' and periodo='{$fecha_emision}'
                                UNION
                                SELECT * 
                                    from asientos 
                                        where id_empresa={$request->id_empresa} 
                                        and cierre_contable='Estado Contable' and periodo='{$anio_emision}'");
        $asiento="";
        if(count($fecha_cierre)>0){
            $asiento="no";
        }else{
            $asiento="si";
        }
        return [
            "codigo" => $cod_asiento,
            'asiento_permitido'=>$asiento,
            "codigo_anterior" => $cod_asiento_ant,
            "bodega" => $bodega,
            "info_bodega" => $info_bod_ingreso[0],
            "cuenta" => $cuenta,
            "proyecto" => $proyecto[0]->id_proyecto
        ];
    }
    public function agregarAsiento_Ingreso(Request $request)
    {
        BodegaIngreso::where('id_bodega_ingreso', $request->cod_rol)->update(['contabilidad' => '1']);
        $asientos = new Asientos();
        $asientos->automatico = 0;
        $asientos->numero = $request->numero;
        $asientos->codigo = $request->codigo;
        $asientos->codigo_rol = $request->cod_rol;
        $asientos->fecha = $request->fecha;
        $asientos->razon_social = $request->razon_social;
        $asientos->concepto = $request->concepto;
        $asientos->ucrea = $request->ucrea;
        $asientos->id_proyecto = $request->id_proyecto;
        $asientos->id_asientos_comprobante = 15;

        $asientos->save();
        return $asientos->id_asientos;
    }
    public function agregarAsientoDetalle_Ingreso(Request $request)
    {
        foreach ($request->bodegas as $debe) {
            $asiento = new Asientos_contables_detalle();
            if ($debe["debe"] > 0) {
                $asiento->proyecto = $debe["descripcion"];
                $asiento->debe = $debe["debe"];
                $asiento->ucrea = $request->ucrea;
                $asiento->id_plan_cuentas = $debe["id_plan_cuentas"];
                $asiento->id_asientos = $request->id_asientos;
                $asiento->id_proyecto = $debe["id_proyecto"];
                $asiento->save();
            }
        }
        foreach ($request->cuentas as $haber) {
            $asiento = new Asientos_contables_detalle();
            if ($haber["haber"] > 0) {
                $asiento->proyecto = $haber["descripcion"];
                $asiento->haber = $haber["haber"];
                $asiento->ucrea = $request->ucrea;
                $asiento->id_plan_cuentas = $haber["id_plan_cuentas"];
                $asiento->id_asientos = $request->id_asientos;
                $asiento->id_proyecto = $haber["id_proyecto"];
                $asiento->save();
            }
        }
    }
}
