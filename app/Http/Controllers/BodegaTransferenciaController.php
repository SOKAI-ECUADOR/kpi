<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Bodega;
use App\Models\BodegaIngreso;
use App\Models\BodegaEgreso;
use App\Models\BodegaIngresoDetalle;
use App\Models\BodegaEgresoDetalle;
use App\Models\BodegaTransferencia;
use App\Models\ProductoBodega;
use App\Models\BodegaTransferenciaDetalle;

use App\Models\Asientos;
use App\Models\Asientos_contables_detalle;

use phpDocumentor\Reflection\DocBlock\Tags\Return_;

use generarReportes;
include 'class/generarReportes.php';

class BodegaTransferenciaController extends Controller
{
    /* -------------------------------------------------------        Funciones Envio de Transferencia          -----------------------------------------------------------------*/
    //funcion lista trasnferencias enviadas en envio de transferecnias
    public function indexenvio(Request $request, $idb, $ide)
    {
        $buscar = $request->buscar;
        if ($buscar == '') {
            $recupera = BodegaTransferencia::select("bodega_transferencia.*", "bodega.nombre as bodega_receptor")
                ->leftjoin("bodega", "bodega.id_bodega", "=", "bodega_transferencia.receptor_trans")
                ->where("bodega_transferencia.id_empresa", "=", $ide)->where("bodega_transferencia.emisor_trans", "=", $idb)
                ->orderByRaw('bodega_transferencia.id_bodega_transferencia DESC')->get();
        } else {
            $recupera = BodegaTransferencia::select("bodega_transferencia.*", "bodega.nombre as bodega_receptor")
                ->leftjoin("bodega", "bodega.id_bodega", "=", "bodega_transferencia.receptor_trans")
                ->where(function ($q) use ($buscar) {
                    $q->where('bodega_transferencia.num_trans', 'like', '%' . $buscar . '%')
                        ->orWhere('bodega_transferencia.f_iniciacion', 'like', '%' . $buscar . '%')
                        ->orWhere('bodega_transferencia.f_finalizacion', 'like', '%' . $buscar . '%')
                        ->orWhere('bodega_transferencia.estado', 'like', '%' . $buscar . '%')
                        ->orWhere('bodega_transferencia.motivo_trans', 'like', '%' . $buscar . '%')
                        ->orWhere('bodega.nombre', 'like', '%' . $buscar . '%');
                })
                ->where("bodega_transferencia.id_empresa", "=", $ide)->where("bodega_transferencia.emisor_trans", "=", $idb)
                ->orderByRaw('bodega_transferencia.id_bodega_transferencia DESC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }
    //lista el el ultimo valor de codigo de trasnferencias autoincrementable
    public function codtrans($id)
    {
        $selnum = DB::select("SELECT num_trans FROM bodega_transferencia  WHERE id_empresa = $id ORDER BY  id_bodega_transferencia DESC LIMIT 1;");
        $principal = "";
        if (count($selnum) >= 1) {
            $dato = $selnum[0]->num_trans;
            $tot = $dato + 1;
            $principal = $tot;
        } else {
            $principal = 1;
        }
        return [
            "num_transe" => $principal
        ];
    }
    // lista bodegas disponibles para enviar trasnferencia select
    public function indexbodegastranse($idb, $ide)
    {
        $tipo = Bodega::select("bodega.id_bodega", "bodega.nombre")->where("id_empresa", "=", $ide)->where("id_bodega", "!=", $idb)->get();
        return $tipo;
    }
    //lista stock de bodega para trasnferencias envio
    public function productosstocktranse(Request $request, $id)
    {
        $buscar = $request->buscar;

        if ($buscar == '') {
            $recupera = ProductoBodega::select("bodega.*", "producto.cod_principal", "producto.id_producto AS idprod", "producto.nombre AS nombrep", "producto.descripcion", "producto_bodega.cantidad", "producto_bodega.costo_unitario", "producto_bodega.costo_total")
                ->join("producto", "producto.id_producto", "=", "producto_bodega.id_producto")
                ->join("bodega", "bodega.id_bodega", "=", "producto_bodega.id_bodega")
                ->where("bodega.id_bodega", "=", $id)->get();
        } else {
            $recupera = ProductoBodega::select("bodega.*", "producto.cod_principal", "producto.id_producto AS idprod", "producto.nombre AS nombrep", "producto.descripcion", "producto_bodega.cantidad", "producto_bodega.costo_unitario", "producto_bodega.costo_total")
                ->join("producto", "producto.id_producto", "=", "producto_bodega.id_producto")
                ->join("bodega", "bodega.id_bodega", "=", "producto_bodega.id_bodega")
                ->where(function ($q) use ($buscar) {
                    $q->where('producto.nombre', 'like', '%' . $buscar . '%')
                        ->orWhere('producto.cod_principal', 'like', '%' . $buscar . '%')
                        ->orWhere('producto.descripcion', 'like', '%' . $buscar . '%');
                })
                ->where("bodega.id_bodega", "=", $id)->get();
        }
        return [
            "datos" => $recupera
        ];
    }
    //funcion para almacenar envio de trasnferencia
    public function storetranse(Request $request)
    {
        //codigo autincrementable trasnferencia
        $selnum = DB::select("SELECT num_trans FROM bodega_transferencia  WHERE id_empresa = $request->id_empresa ORDER BY  id_bodega_transferencia DESC LIMIT 1;");
        $principal = "";
        if (count($selnum) >= 1) {
            $dato = $selnum[0]->num_trans;
            $tot = $dato + 1;
            $principal = $tot;
        } else {
            $principal = 1;
        }
        //encabezado y guia remision
        $trans = new BodegaTransferencia();
        $trans->num_trans = $principal;
        $trans->estado = "Enviada";
        $trans->f_iniciacion = $request->f_ini_transe;
        $trans->f_finalizacion = $request->f_fin_transe;
        $trans->motivo_trans = $request->motivo_transe;
        $trans->observ_trans = $request->observ_transe;
        $trans->transporte = $request->transport_transe;
        $trans->marcav = $request->marca_transe;
        $trans->placasv = $request->placa_transe;
        $trans->colorv = $request->color_transe;
        $trans->transportista = $request->sr_transe;
        $trans->emisor_trans = $request->emisor_transe;
        $trans->receptor_trans = $request->receptor_transe;
        $trans->id_proyecto = $request->id_proyecto;
        $trans->id_empresa = $request->id_empresa;
        $trans->save();
        $id = $trans->id_bodega_transferencia;

        //codigo autincrementable egreso
        $numegreso = DB::select("SELECT num_egreso FROM bodega_egreso  WHERE id_empresa = $request->id_empresa ORDER BY  id_bodega_egreso DESC LIMIT 1;");
        $codegreso = "";
        if (count($numegreso) >= 1) {
            $dato = $numegreso[0]->num_egreso;
            $tot = $dato + 1;
            $codegreso = $tot;
        } else {
            $codegreso = 1;
        }
        //egreso encabezado
        $egreso = new BodegaEgreso();
        $egreso->num_egreso = $codegreso;
        $egreso->fecha_egreso = $request->f_ini_transe;
        $egreso->tipo_egreso = "Egreso Transferencia";
        $egreso->observ_egreso = "Envio de Transferencia: " . $principal;
        $egreso->id_bodega = $request->emisor_transe;
        $egreso->id_empresa = $request->id_empresa;
        $egreso->id_bodega_transferencia = $id;
        $egreso->save();

        for ($d = 0; $d < count($request->prodtranse); $d++) {
            $sel = DB::select("SELECT * FROM `producto_bodega` WHERE `id_producto` = " . $request->prodtranse[$d]["id"] . " AND `id_bodega` =" . $request->id_bodega);
            if (count($sel) == 1) {
                //stock bodega
                $prb = ProductoBodega::findOrFail($sel[0]->id_producto_bodega);
                $prb->cantidad = $prb->cantidad - $request->prodtranse[$d]["cant_enviadae"];
                $prb->costo_total = $prb->costo_total - $request->prodtranse[$d]["cost_totale"];
                if ($prb->cantidad != 0) {
                    $prb->costo_unitario = $prb->costo_total / $prb->cantidad;
                } else {
                    $prb->costo_unitario = 0;
                }
                $prb->save();
                //detalle trasnferencia
                $pbt = new BodegaTransferenciaDetalle();
                $pbt->id_producto = $request->prodtranse[$d]["id"];
                $pbt->cant_env = $request->prodtranse[$d]["cant_enviadae"];
                $pbt->costo_unitario = $request->prodtranse[$d]["cost_unitarioe"];
                $pbt->costo_total = $request->prodtranse[$d]["cost_totale"];
                $pbt->id_bodega_transferencia = $id;
                if (isset($request->prodtranse[$d]["proyecto"])) {
                    $pbt->id_proyecto = $request->prodtranse[$d]["proyecto"];
                }
                $pbt->save();
                //egreso bodega detalle
                $pbed = new BodegaEgresoDetalle();
                $pbed->cantidad = $request->prodtranse[$d]["cant_enviadae"];
                $pbed->costo_unitario = $request->prodtranse[$d]["cost_unitarioe"];
                $pbed->costo_total = $request->prodtranse[$d]["cost_totale"];
                $pbed->id_bodega_egreso = $egreso->id_bodega_egreso;
                $pbed->id_producto = $request->prodtranse[$d]["id"];
                if (isset($request->prodtranse[$d]["proyecto"])) {
                    $pbed->id_proyecto = $request->prodtranse[$d]["proyecto"];
                }
                $pbed->id_bodega_transferencia_detalle = $pbt->id_bodega_transferencia_detalle;
                $pbed->save();
            }
        }
    }
    //lista contenido de trasnferencia envio de bodega
    public function gettransebodega($id)
    {
        $transe = DB::select('SELECT * FROM bodega_transferencia WHERE id_bodega_transferencia=' . $id);
        $detalle = DB::select('SELECT d.*, p.cod_principal, p.cod_alterno, p.nombre FROM bodega_transferencia_detalle  d INNER JOIN producto p ON p.id_producto = d.id_producto WHERE id_bodega_transferencia =' . $id);
        return [
            "transe" => $transe[0],
            "transe_detalle" => $detalle
        ];
    }
    /*--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
    /* -------------------------------------------------------        Funciones Recepcion de Transferencia          -----------------------------------------------------------------*/
    //funcion lista trasnferencias recividas en recepcion de transferecnias
    public function indexrecepcion(Request $request, $idb, $ide)
    {
        //actualzia el estado de trasnferencia a finalizado si este ha expirado
        //DB::update('UPDATE `bodega_transferencia` SET `estado`= "Finalizada" WHERE DATEDIFF(CURDATE(),`f_finalizacion`) > 1 AND receptor_trans =' . $idb);

        $buscar = $request->buscar;
        if ($buscar == '') {
            $recupera = BodegaTransferencia::select("bodega_transferencia.*", "bodega.nombre as bodega_emisor")
                ->leftjoin("bodega", "bodega.id_bodega", "=", "bodega_transferencia.emisor_trans")
                ->where("bodega_transferencia.id_empresa", "=", $ide)->where("bodega_transferencia.receptor_trans", "=", $idb)
                ->orderByRaw('bodega_transferencia.id_bodega_transferencia DESC')->get();
        } else {
            $recupera = BodegaTransferencia::select("bodega_transferencia.*", "bodega.nombre as bodega_emisor")
                ->leftjoin("bodega", "bodega.id_bodega", "=", "bodega_transferencia.emisor_trans")
                ->where(function ($q) use ($buscar) {
                    $q->where('bodega_transferencia.num_trans', 'like', '%' . $buscar . '%')
                        ->orWhere('bodega_transferencia.f_iniciacion', 'like', '%' . $buscar . '%')
                        ->orWhere('bodega_transferencia.f_finalizacion', 'like', '%' . $buscar . '%')
                        ->orWhere('bodega_transferencia.estado', 'like', '%' . $buscar . '%')
                        ->orWhere('bodega_transferencia.motivo_trans', 'like', '%' . $buscar . '%')
                        ->orWhere('bodega.nombre', 'like', '%' . $buscar . '%');
                })
                ->where("bodega_transferencia.id_empresa", "=", $ide)->where("bodega_transferencia.receptor_trans", "=", $idb)
                ->orderByRaw('bodega_transferencia.id_bodega_transferencia DESC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }
    //lista contenido de trasnferencia recepcion de bodega
    public function gettransrbodega($id)
    {
        $transr = DB::select('SELECT * FROM bodega_transferencia WHERE id_bodega_transferencia=' . $id);
        $detalle = DB::select('SELECT d.*, p.cod_principal, p.cod_alterno, p.nombre FROM bodega_transferencia_detalle  d INNER JOIN producto p ON p.id_producto = d.id_producto WHERE id_bodega_transferencia =' . $id);
        return [
            "transr" => $transr[0],
            "transr_detalle" => $detalle
        ];
    }

    public function storetransr(Request $request)
    {
        $receptada = 0;

        //num ingreso
        $numingreso = DB::select("SELECT num_ingreso FROM bodega_ingreso  WHERE id_empresa = $request->id_empresa ORDER BY  id_bodega_ingreso DESC LIMIT 1;");
        $codingres = "";
        if (count($numingreso) >= 1) {
            $dato = $numingreso[0]->num_ingreso;
            $tot = $dato + 1;
            $codingres = $tot;
        } else {
            $codingres = 1;
        }
        //ingreso de bodega
        $ingreso = new BodegaIngreso();
        $ingreso->num_ingreso = $codingres;
        $ingreso->fecha_ingreso = Carbon::now();
        $ingreso->tipo_ingreso = "Ingreso Transferencia";
        $ingreso->observ_ingreso = "Recepción de Transferencia: " . $request->num_trans;
        $ingreso->id_bodega = $request->id_bodega;
        $ingreso->id_empresa = $request->id_empresa;
        $ingreso->id_bodega_transferencia = $request->id_bodega_transferencia;
        $ingreso->save();

        for ($d = 0; $d < count($request->contenidoproductostr); $d++) {
            $sel = DB::select("SELECT * FROM `producto_bodega` WHERE `id_producto` = " . $request->contenidoproductostr[$d]["id_producto"] . " AND `id_bodega` =" . $request->id_bodega);
            if (count($sel) >= 1) {
                if (!empty($request->contenidoproductostr[$d]["cant_new"])) {
                    //stock bodega
                    $prb = ProductoBodega::findOrFail($sel[0]->id_producto_bodega);
                    $prb->cantidad = $prb->cantidad + $request->contenidoproductostr[$d]["cant_new"];
                    $prb->costo_total = ($request->contenidoproductostr[$d]["cant_new"] * $request->contenidoproductostr[$d]["cost_unitarior"]) + $prb->costo_total;
                    $prb->costo_unitario = $prb->costo_total / $prb->cantidad;
                    $prb->save();
                    //detalle trasnferencia
                    $pbt =  BodegaTransferenciaDetalle::findOrFail($request->contenidoproductostr[$d]["id_bodega_transferencia_detalle"]);
                    $pbt->cant_recib = $request->contenidoproductostr[$d]["cant_new"] + $pbt->cant_recib;
                    //calculo para receptar trasnferencia
                    $receptada += ($pbt->cant_env - $pbt->cant_recib);
                    $pbt->save();

                    //detalle ingreso de bodega
                    $boid = new BodegaIngresoDetalle();
                    $boid->cantidad = $request->contenidoproductostr[$d]["cant_new"];
                    $boid->costo_unitario =  $request->contenidoproductostr[$d]["cost_unitarior"];
                    $boid->costo_total = $request->contenidoproductostr[$d]["cant_new"] * $request->contenidoproductostr[$d]["cost_unitarior"];
                    $boid->id_bodega_ingreso = $ingreso->id_bodega_ingreso;
                    $boid->id_producto = $request->contenidoproductostr[$d]["id_producto"];
                    if (isset($request->contenidoproductostr[$d]["proyecto"])) {
                        $boid->id_proyecto = $request->contenidoproductostr[$d]["proyecto"];
                    }
                    $boid->id_bodega_transferencia_detalle = $pbt->id_bodega_transferencia_detalle;
                    $boid->save();
                }
            } else {
                if (!empty($request->contenidoproductostr[$d]["cant_new"])) {
                    $prb = new ProductoBodega();
                    $prb->cantidad = $request->contenidoproductostr[$d]["cant_new"];
                    $prb->costo_unitario = $request->contenidoproductostr[$d]["cost_unitarior"];
                    $prb->costo_total = ($request->contenidoproductostr[$d]["cant_new"] * $request->contenidoproductostr[$d]["cost_unitarior"]);
                    $prb->id_producto = $request->contenidoproductostr[$d]["id_producto"];
                    $prb->id_bodega = $request->id_bodega;
                    $prb->id_empresa = $request->id_empresa;
                    $prb->save();

                    //detalle trasnferencia
                    $pbt =  BodegaTransferenciaDetalle::findOrFail($request->contenidoproductostr[$d]["id_bodega_transferencia_detalle"]);
                    $pbt->cant_recib = $request->contenidoproductostr[$d]["cant_new"] + $pbt->cant_recib;
                    //calculo para receptar trasnferencia
                    $receptada += ($pbt->cant_env - $pbt->cant_recib);
                    $pbt->save();

                    //detalle ingreso de bodega
                    $boid = new BodegaIngresoDetalle();
                    $boid->cantidad = $request->contenidoproductostr[$d]["cant_new"];
                    $boid->costo_unitario =  $request->contenidoproductostr[$d]["cost_unitarior"];
                    $boid->costo_total = $request->contenidoproductostr[$d]["cant_new"] * $request->contenidoproductostr[$d]["cost_unitarior"];
                    $boid->id_bodega_ingreso = $ingreso->id_bodega_ingreso;
                    $boid->id_producto = $request->contenidoproductostr[$d]["id_producto"];
                    if (isset($request->contenidoproductostr[$d]["proyecto"])) {
                        $boid->id_proyecto = $request->contenidoproductostr[$d]["proyecto"];
                    }
                    $boid->id_bodega_transferencia_detalle = $pbt->id_bodega_transferencia_detalle;
                    $boid->save();
                }
            }
            if ($receptada == 0) {
                $transr = BodegaTransferencia::findorFail($request->id_bodega_transferencia);
                $transr->estado = "Receptada";
                $transr->save();
            } else {
                $transr = BodegaTransferencia::findorFail($request->id_bodega_transferencia);
                $transr->estado = "Parcial";
                $transr->save();
            }
        }
    }
    public function verAsiento(Request $request,$id){
        $codigo=DB::select("SELECT max(numero) as codigo FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.codigo like 'BT-%' and (asientos.estado='Activo' or asientos.estado is null) and proyecto.id_empresa=".$request->id_empresa);
        $cod_asiento="";
        if($codigo){
            $lenght=strlen($codigo[0]->codigo);
            $valor=$codigo[0]->codigo+1;
            $cod_asiento=$codigo[0]->codigo+1;
        }else{
            $cod_asiento="1";
        }
        $cod_asiento_ant="";
        if($codigo){
            $codigo_ant=DB::select("SELECT asientos.numero FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.id_asientos_comprobante=18 and (asientos.estado='Activo' or asientos.estado is null) and asientos.codigo_rol={$id} and proyecto.id_empresa=".$request->id_empresa." ORDER BY asientos.codigo desc limit 1");
            if($codigo_ant){
                $cod_asiento_ant=$codigo_ant[0]->numero;
            }
            
            
        }
        $proyecto=DB::select("SELECT id_proyecto from proyecto where id_empresa={$request->id_empresa} limit 1");
        $bodega=DB::select("SELECT plan_cuentas.id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,proyecto.id_proyecto,proyecto.descripcion,null as haber, bodega_transferencia_detalle.costo_total as debe,bodega_transferencia_detalle.id_bodega_transferencia_detalle
        from bodega_transferencia_detalle
        LEFT JOIN proyecto
        on proyecto.id_proyecto=bodega_transferencia_detalle.id_proyecto
        INNER JOIN bodega_transferencia
        on bodega_transferencia.id_bodega_transferencia=bodega_transferencia_detalle.id_bodega_transferencia
        INNER JOIN bodega
        on bodega.id_bodega=bodega_transferencia.emisor_trans
        LEFT JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=bodega.id_plan_cuentas
        where bodega_transferencia_detalle.id_bodega_transferencia={$id}");
        $info_bod_transf=DB::select("SELECT * from bodega_transferencia where id_bodega_transferencia={$id}");
        $cuenta=DB::select("SELECT cuenta_bodega_transferencia.id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,proyecto.id_proyecto,proyecto.descripcion,null as debe, bodega_transferencia_detalle.costo_total as haber,bodega_transferencia_detalle.id_bodega_transferencia_detalle
        from bodega_transferencia_detalle
        LEFT JOIN proyecto
        on proyecto.id_proyecto=bodega_transferencia_detalle.id_proyecto
        INNER JOIN bodega_transferencia
        on bodega_transferencia.id_bodega_transferencia=bodega_transferencia_detalle.id_bodega_transferencia
        INNER JOIN bodega
        on bodega.id_bodega=bodega_transferencia.emisor_trans
        INNER JOIN producto
        on producto.id_producto=bodega_transferencia_detalle.id_producto
        INNER JOIN cuenta_bodega_transferencia
        on cuenta_bodega_transferencia.id_empresa=bodega.id_empresa
        LEFT JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=cuenta_bodega_transferencia.id_plan_cuentas
        where bodega_transferencia_detalle.id_bodega_transferencia={$id} and cuenta_bodega_transferencia.id_bodega={$info_bod_transf[0]->emisor_trans}");
        $fecha_emision=substr($info_bod_transf[0]->f_iniciacion,0,-3);
        $anio_emision=substr($info_bod_transf[0]->f_iniciacion,0,4);
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
            "codigo"=>$cod_asiento,
            'asiento_permitido'=>$asiento,
            "codigo_anterior"=>$cod_asiento_ant,
            "bodega"=>$bodega,
            "info_bodega"=>$info_bod_transf[0],
            "cuenta"=>$cuenta,
            "proyecto"=>$proyecto[0]->id_proyecto
        ];
    }
    public function verAsiento_Receptor(Request $request,$id){
        $codigo=DB::select("SELECT max(numero) as codigo FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.codigo like 'BT-%' and (asientos.estado='Activo' or asientos.estado is null) and proyecto.id_empresa=".$request->id_empresa);
        $cod_asiento="";
        if($codigo){
            $lenght=strlen($codigo[0]->codigo);
            $valor=$codigo[0]->codigo+1;
            $cod_asiento=$codigo[0]->codigo+1;
        }else{
            $cod_asiento="1";
        }
        $cod_asiento_ant="";
        if($codigo){
            $codigo_ant=DB::select("SELECT asientos.numero FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.id_asientos_comprobante=18 and (asientos.estado='Activo' or asientos.estado is null) and asientos.codigo_rol={$id} and proyecto.id_empresa=".$request->id_empresa." ORDER BY asientos.codigo desc limit 1");
            if($codigo_ant){
                $cod_asiento_ant=$codigo_ant[0]->numero;
            }
            
            
        }
        $proyecto=DB::select("SELECT id_proyecto from proyecto where id_empresa={$request->id_empresa} limit 1");
        $bodega=DB::select("SELECT plan_cuentas.id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,proyecto.id_proyecto,proyecto.descripcion,null as haber, bodega_transferencia_detalle.costo_total as debe,bodega_transferencia_detalle.id_bodega_transferencia_detalle
        from bodega_transferencia_detalle
        LEFT JOIN proyecto
        on proyecto.id_proyecto=bodega_transferencia_detalle.id_proyecto
        INNER JOIN bodega_transferencia
        on bodega_transferencia.id_bodega_transferencia=bodega_transferencia_detalle.id_bodega_transferencia
        INNER JOIN bodega
        on bodega.id_bodega=bodega_transferencia.receptor_trans
        LEFT JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=bodega.id_plan_cuentas
        where bodega_transferencia_detalle.id_bodega_transferencia={$id}");
        $info_bod_transf=DB::select("SELECT * from bodega_transferencia where id_bodega_transferencia={$id}");
        $cuenta=DB::select("SELECT cuenta_bodega_transferencia.id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,proyecto.id_proyecto,proyecto.descripcion,null as debe, bodega_transferencia_detalle.costo_total as haber,bodega_transferencia_detalle.id_bodega_transferencia_detalle
        from bodega_transferencia_detalle
        LEFT JOIN proyecto
        on proyecto.id_proyecto=bodega_transferencia_detalle.id_proyecto
        INNER JOIN bodega_transferencia
        on bodega_transferencia.id_bodega_transferencia=bodega_transferencia_detalle.id_bodega_transferencia
        INNER JOIN bodega
        on bodega.id_bodega=bodega_transferencia.receptor_trans
        INNER JOIN producto
        on producto.id_producto=bodega_transferencia_detalle.id_producto
        INNER JOIN cuenta_bodega_transferencia
        on cuenta_bodega_transferencia.id_empresa=bodega.id_empresa
        LEFT JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=cuenta_bodega_transferencia.id_plan_cuentas
        where bodega_transferencia_detalle.id_bodega_transferencia={$id} and cuenta_bodega_transferencia.id_bodega={$info_bod_transf[0]->receptor_trans}");
        $fecha_emision=substr($info_bod_transf[0]->f_finalizacion,0,-3);
        $anio_emision=substr($info_bod_transf[0]->f_finalizacion,0,4);
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
            "codigo"=>$cod_asiento,
            'asiento_permitido'=>$asiento,
            "codigo_anterior"=>$cod_asiento_ant,
            "bodega"=>$bodega,
            "info_bodega"=>$info_bod_transf[0],
            "cuenta"=>$cuenta,
            "proyecto"=>$proyecto[0]->id_proyecto
        ];
    }
    public function generarPdf(Request $request)
    {
        if($request->tipo=="emisor"){
            $query_bd_ingreso = "SELECT bdi.*,bd.nombre,bd.responsable,bd.ubicacion,bd.direccion,bd.telefono,pro.descripcion,emp.nombre_empresa,(select nombre from bodega where bodega.id_bodega=bdi.receptor_trans) as nombre_bodega 
            from bodega_transferencia as bdi
            INNER JOIN bodega as bd
            on bd.id_bodega=bdi.emisor_trans
            INNER JOIN empresa as emp
            on emp.id_empresa=bdi.id_empresa
            LEFT JOIN proyecto as pro
            on pro.id_proyecto=bdi.id_proyecto
            where bdi.id_bodega_transferencia={$request->id_bodega_ingreso}";
            
            $query_bd_ingreso_detalle = "SELECT bdid.*,bdi.f_iniciacion,bdi.f_finalizacion,pro.cod_principal,pro.nombre,proy.descripcion as descripcion_proy from bodega_transferencia_detalle as bdid
            INNER JOIN bodega_transferencia as bdi
            on bdi.id_bodega_transferencia=bdid.id_bodega_transferencia
            INNER JOIN producto as pro
            on pro.id_producto=bdid.id_producto
            LEFT JOIN proyecto as proy
            on proy.id_proyecto=bdid.id_proyecto
            where bdi.id_bodega_transferencia={$request->id_bodega_ingreso}";
            
        }else{
            $query_bd_ingreso = "SELECT bdi.*,bd.nombre,bd.responsable,bd.ubicacion,bd.direccion,bd.telefono,pro.descripcion,emp.nombre_empresa,(select nombre from bodega where bodega.id_bodega=bdi.emisor_trans) as nombre_bodega  
            from bodega_transferencia as bdi
                        INNER JOIN bodega as bd
                        on bd.id_bodega=bdi.receptor_trans
                        INNER JOIN empresa as emp
                        on emp.id_empresa=bdi.id_empresa
                        LEFT JOIN proyecto as pro
                        on pro.id_proyecto=bdi.id_proyecto
                        where bdi.id_bodega_transferencia={$request->id_bodega_ingreso}";
            
            $query_bd_ingreso_detalle = "SELECT bdid.*,bdi.f_iniciacion,bdi.f_finalizacion,pro.cod_principal,pro.nombre,proy.descripcion as descripcion_proy from bodega_transferencia_detalle as bdid
            INNER JOIN bodega_transferencia as bdi
            on bdi.id_bodega_transferencia=bdid.id_bodega_transferencia
            INNER JOIN producto as pro
            on pro.id_producto=bdid.id_producto
            LEFT JOIN proyecto as proy
            on proy.id_proyecto=bdid.id_proyecto
            where bdi.id_bodega_transferencia={$request->id_bodega_ingreso}";
        }
        $reporte_bd_ingreso = DB::select($query_bd_ingreso);
        $reporte_bd_ingreso_detalle = DB::select($query_bd_ingreso_detalle);
        $empresa = DB::select("SELECT emp.*,'GRUPO SOLIS INGENIERIA ESPECIALIZADA' as nomb_empresa_ej,concat(usu.nombres,' ',usu.apellidos) as usuario from empresa as emp,user as usu where emp.id_empresa=usu.id_empresa and usu.id={$request->id_usuario}");
        //if($request->destinatario==null && $request->email==null){
        $pdf = new generarReportes();
        $strPDF = $pdf->PDFBodegaTransf($reporte_bd_ingreso,$request->tipo, $reporte_bd_ingreso_detalle, $empresa[0]);
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
    public function agregarAsiento_Trans(Request $request){
        BodegaTransferencia::where('id_bodega_transferencia',$request->cod_rol)->update(['contabilidad'=>'1']);
        $asientos=new Asientos();
        $asientos->automatico=0;
        $asientos->numero=$request->numero;
        $asientos->codigo=$request->codigo;
        $asientos->codigo_rol=$request->cod_rol;
        $asientos->fecha=$request->fecha;
        $asientos->razon_social=$request->razon_social;
        $asientos->concepto=$request->concepto;
        $asientos->ucrea=$request->ucrea;
        $asientos->id_proyecto=$request->id_proyecto;
        $asientos->id_asientos_comprobante=18;

        $asientos->save();
        return $asientos->id_asientos;
    }
    public function agregarAsientoDetalle_Trans(Request $request){
        foreach($request->bodegas as $debe){
            $asiento=new Asientos_contables_detalle();
            if($debe["debe"]>0){
                    $asiento->proyecto=$debe["descripcion"];
                    $asiento->debe=$debe["debe"];
                    $asiento->ucrea=$request->ucrea;
                    $asiento->id_plan_cuentas=$debe["id_plan_cuentas"];
                    $asiento->id_asientos=$request->id_asientos;
                    $asiento->id_proyecto=$debe["id_proyecto"];
                    $asiento->save();
            }
        }
        foreach($request->cuentas as $haber){
            $asiento=new Asientos_contables_detalle();
            if($haber["haber"]>0){
                $asiento->proyecto=$haber["descripcion"];
                    $asiento->haber=$haber["haber"];
                    $asiento->ucrea=$request->ucrea;
                    $asiento->id_plan_cuentas=$haber["id_plan_cuentas"];
                    $asiento->id_asientos=$request->id_asientos;
                    $asiento->id_proyecto=$haber["id_proyecto"];
                    $asiento->save();
            }
        }
    }
}
