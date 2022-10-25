<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Bodega;
use Illuminate\Http\Request;
use App\Models\BodegaEgreso;
use App\Models\BodegaEgresoDetalle;
use App\Models\ProductoBodega;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;

use App\Models\Asientos;
use App\Models\Asientos_contables_detalle;

use generarReportes;
include 'class/generarReportes.php';

class BodegaEgresoController extends Controller
{
    //lista registros de egreso de bodega
    public function index(Request $request, $idb, $ide)
    {
        $buscar = $request->buscar;
        if ($buscar == '') {
            $recupera = BodegaEgreso::select("*")->where("id_empresa", "=", $ide)->where("id_bodega", "=", $idb)->orderByRaw('id_bodega_egreso DESC')->get();
        } else {
            $recupera = BodegaEgreso::select('*')
                ->where(function ($q) use ($buscar) {
                    $q->where('num_egreso', 'like', '%' . $buscar . '%')
                        ->orWhere('fecha_egreso', 'like', '%' . $buscar . '%')
                        ->orWhere('tipo_egreso', 'like', '%' . $buscar . '%');
                })
                ->where("id_empresa", "=", $ide)->where("id_bodega", "=", $idb)->orderByRaw('id_bodega_egreso DESC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }
    //lista el codigo de egreso de bodega autoincrmentable
    public function codegres($id)
    {
        $selnum = DB::select("SELECT num_egreso FROM bodega_egreso  WHERE id_empresa = $id ORDER BY  id_bodega_egreso DESC LIMIT 1;");
        $principal = "";
        if (count($selnum) >= 1) {
            $dato = $selnum[0]->num_egreso;
            $tot = $dato + 1;
            $principal = $tot;
        } else {
            $principal = 1;
        }
        return [
            "num_egreso" => $principal
        ];
    }
    //guarda nuevo egreso de bodega
    public function store(Request $request)
    {
        //num egreso
        $selnum = DB::select("SELECT num_egreso FROM bodega_egreso  WHERE id_empresa = $request->id_empresa ORDER BY  id_bodega_egreso DESC LIMIT 1;");
        $principal = "";
        if (count($selnum) >= 1) {
            $dato = $selnum[0]->num_egreso;
            $tot = $dato + 1;
            $principal = $tot;
        } else {
            $principal = 1;
        }
        //egreso encabezado
        $egreso = new BodegaEgreso();
        $egreso->num_egreso = $principal;
        $egreso->fecha_egreso = $request->fecha_egreso;
        $egreso->tipo_egreso = $request->tipo_egreso;
        $egreso->observ_egreso = $request->observ_egreso;
        $egreso->id_proyecto = $request->id_proyecto;
        $egreso->id_bodega = $request->id_bodega;
        $egreso->id_empresa = $request->id_empresa;
        $egreso->save();

        for ($d = 0; $d < count($request->contenidostock); $d++) {
            $sel = DB::select("SELECT * FROM `producto_bodega` WHERE `id_producto` = " . $request->contenidostock[$d]["id"] . " AND `id_bodega` =" . $request->id_bodega);
            if (count($sel) == 1) {

                $prb = ProductoBodega::findOrFail($sel[0]->id_producto_bodega);
                $prb->cantidad = $prb->cantidad - $request->contenidostock[$d]["cant_egreso"];
                $prb->costo_total = $prb->costo_total - $request->contenidostock[$d]["cost_tot_egreso"];
                if ($prb->cantidad != 0) {
                    $prb->costo_unitario = $prb->costo_total / $prb->cantidad;
                } else {
                    $prb->costo_unitario = 0;
                }
                $prb->save();

                $bed = new BodegaEgresoDetalle();
                $bed->cantidad = $request->contenidostock[$d]["cant_egreso"];
                $bed->costo_unitario = $request->contenidostock[$d]["cost_unit_egreso"];
                $bed->costo_total = $request->contenidostock[$d]["cost_tot_egreso"];
                $bed->id_bodega_egreso = $egreso->id_bodega_egreso;
                $bed->id_producto = $request->contenidostock[$d]["id"];
                if (isset($request->contenidostock[$d]["proyecto"])) {
                    $bed->id_proyecto = $request->contenidostock[$d]["proyecto"];
                }
                $bed->save();


            }
        }
    }

    //lista contenido de egreso de bodega
    public function getegresobodega($id)
    {
        $egreso = DB::select('SELECT * FROM bodega_egreso WHERE id_bodega_egreso=' . $id);
        $detalle = DB::select('SELECT d.*, p.cod_principal, p.cod_alterno, p.nombre FROM bodega_egreso_detalle  d INNER JOIN producto p ON p.id_producto = d.id_producto WHERE id_bodega_egreso =' . $id);
        return [
            "egreso" => $egreso[0],
            "egreso_detalle" => $detalle
        ];
    }
    //lista stock de bodega para egreso bodega
    public function productosstockegreso(Request $request, $id)
    {
        $buscar = $request->buscar;

        if ($buscar == '') {
            $recupera = ProductoBodega::select("bodega.*", "producto.cod_principal", "producto.cod_alterno", "producto.id_producto AS idprod", "producto.nombre AS nombrep", "producto.descripcion", "producto_bodega.cantidad", "producto_bodega.costo_unitario", "producto_bodega.costo_total")
                ->join("producto", "producto.id_producto", "=", "producto_bodega.id_producto")
                ->join("bodega", "bodega.id_bodega", "=", "producto_bodega.id_bodega")
                ->where("bodega.id_bodega", "=", $id)->get();
        } else {
            $recupera = ProductoBodega::select("bodega.*", "producto.cod_principal", "producto.cod_alterno", "producto.id_producto AS idprod", "producto.nombre AS nombrep", "producto.descripcion", "producto_bodega.cantidad", "producto_bodega.costo_unitario", "producto_bodega.costo_total")
                ->join("producto", "producto.id_producto", "=", "producto_bodega.id_producto")
                ->join("bodega", "bodega.id_bodega", "=", "producto_bodega.id_bodega")
                ->where(function ($q) use ($buscar) {
                    $q->where('producto.nombre', 'like', '%' . $buscar . '%')
                        ->orWhere('producto.cod_principal', 'like', '%' . $buscar . '%')
                        ->orWhere('producto.cod_alterno', 'like', '%' . $buscar . '%')
                        ->orWhere('producto.descripcion', 'like', '%' . $buscar . '%');
                })
                ->where("bodega.id_bodega", "=", $id)->get();
        }
        return [
            "datos" => $recupera
        ];
    }
    //funciones de asiento
    public function verAsientoBodegaFactura(Request $request,$id){
        $codigo=DB::select("SELECT max(numero) as codigo FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.codigo like 'BCV-%' and (asientos.estado='Activo' or asientos.estado is null) and proyecto.id_empresa=".$request->id_empresa);
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
            $codigo_ant=DB::select("SELECT asientos.numero FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.id_asientos_comprobante=17 and (asientos.estado='Activo' or asientos.estado is null) and asientos.codigo_rol={$id} and proyecto.id_empresa=".$request->id_empresa." ORDER BY asientos.codigo desc limit 1");
            if($codigo_ant){
                $cod_asiento_ant=$codigo_ant[0]->numero;
            }
            
            
        }
        $proyecto=DB::select("SELECT id_proyecto from proyecto where id_empresa={$request->id_empresa} limit 1");
        $cta_produccion=DB::select("SELECT * from cuenta_produccion INNER JOIN plan_cuentas ON plan_cuentas.id_plan_cuentas=cuenta_produccion.id_plan_cuentas where cuenta_produccion.id_empresa={$request->id_empresa}");
        
        if(count($cta_produccion)>0){
            $query_bodega="SELECT (SELECT plan_cuentas.id_plan_cuentas from cuenta_produccion INNER JOIN plan_cuentas ON plan_cuentas.id_plan_cuentas=cuenta_produccion.id_plan_cuentas where cuenta_produccion.id_empresa={$request->id_empresa} limit 1) as id_plan_cuentas,(SELECT concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from cuenta_produccion INNER JOIN plan_cuentas ON plan_cuentas.id_plan_cuentas=cuenta_produccion.id_plan_cuentas where cuenta_produccion.id_empresa={$request->id_empresa} limit 1) as nombre_cuenta,proyecto.id_proyecto,proyecto.descripcion,null as debe, bodega_egreso_detalle.costo_total as haber,bodega_egreso_detalle.id_bodega_egreso_detalle
                                from bodega_egreso_detalle
                                LEFT JOIN proyecto
                                on proyecto.id_proyecto=bodega_egreso_detalle.id_proyecto
                                INNER JOIN bodega_egreso
                                on bodega_egreso.id_bodega_egreso=bodega_egreso_detalle.id_bodega_egreso
                                INNER JOIN bodega
                                on bodega.id_bodega=bodega_egreso.id_bodega
                                where bodega_egreso_detalle.id_bodega_egreso={$id}";
        }else{
            $query_bodega="SELECT plan_cuentas.id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,proyecto.id_proyecto,proyecto.descripcion,null as debe, bodega_egreso_detalle.costo_total as haber,bodega_egreso_detalle.id_bodega_egreso_detalle
                                from bodega_egreso_detalle
                                LEFT JOIN proyecto
                                on proyecto.id_proyecto=bodega_egreso_detalle.id_proyecto
                                INNER JOIN bodega_egreso
                                on bodega_egreso.id_bodega_egreso=bodega_egreso_detalle.id_bodega_egreso
                                INNER JOIN bodega
                                on bodega.id_bodega=bodega_egreso.id_bodega
                                LEFT JOIN plan_cuentas
                                on plan_cuentas.id_plan_cuentas=bodega.id_plan_cuentas
                                where bodega_egreso_detalle.id_bodega_egreso={$id}";
        }
        //dd($query_bodega);
        $bodega=DB::select($query_bodega);
        $info_bod_egreso=DB::select("SELECT * from bodega_egreso where id_bodega_egreso={$id}");
        $cuenta=DB::select("SELECT if(linea_producto.id_plan_cuentas_costo is null,'no','si') as exist_plan_cta,linea_producto.id_plan_cuentas_costo as id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,proyecto.id_proyecto,proyecto.descripcion,bodega_egreso_detalle.costo_total as debe,null as haber,bodega_egreso_detalle.id_bodega_egreso_detalle 
        from bodega_egreso_detalle
        LEFT JOIN proyecto
        on proyecto.id_proyecto=bodega_egreso_detalle.id_proyecto
        INNER JOIN bodega_egreso
        on bodega_egreso.id_bodega_egreso=bodega_egreso_detalle.id_bodega_egreso
        INNER JOIN bodega
        on bodega.id_bodega=bodega_egreso.id_bodega
        INNER JOIN producto
        on producto.id_producto=bodega_egreso_detalle.id_producto
        INNER JOIN linea_producto
        on linea_producto.id_linea_producto=producto.id_linea_producto
        LEFT JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=linea_producto.id_plan_cuentas_costo
        where bodega_egreso_detalle.id_bodega_egreso={$id}");
        $fecha_emision=substr($info_bod_egreso[0]->fecha_egreso,0,-3);
        $anio_emision=substr($info_bod_egreso[0]->fecha_egreso,0,4);
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
            "info_bodega"=>$info_bod_egreso[0],
            "cuenta"=>$cuenta,
            "proyecto"=>$proyecto[0]->id_proyecto
        ];
        
    }
    public function verAsientoBodega(Request $request,$id){
        $codigo=DB::select("SELECT max(numero) as codigo FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.codigo like 'BE-%' and (asientos.estado='Activo' or asientos.estado is null) and proyecto.id_empresa=".$request->id_empresa);
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
            $codigo_ant=DB::select("SELECT asientos.numero FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.id_asientos_comprobante=16 and (asientos.estado='Activo' or asientos.estado is null) and asientos.codigo_rol={$id} and proyecto.id_empresa=".$request->id_empresa." ORDER BY asientos.codigo desc limit 1");
            if($codigo_ant){
                $cod_asiento_ant=$codigo_ant[0]->numero;
            }
            
            
        }
        $proyecto=DB::select("SELECT id_proyecto from proyecto where id_empresa={$request->id_empresa} limit 1");
        $bodega=DB::select("SELECT plan_cuentas.id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,proyecto.id_proyecto,proyecto.descripcion,null as debe, bodega_egreso_detalle.costo_total as haber,bodega_egreso_detalle.id_bodega_egreso_detalle
        from bodega_egreso_detalle
        LEFT JOIN proyecto
        on proyecto.id_proyecto=bodega_egreso_detalle.id_proyecto
        INNER JOIN bodega_egreso
        on bodega_egreso.id_bodega_egreso=bodega_egreso_detalle.id_bodega_egreso
        INNER JOIN bodega
        on bodega.id_bodega=bodega_egreso.id_bodega
        LEFT JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=bodega.id_plan_cuentas
        where bodega_egreso_detalle.id_bodega_egreso={$id}");
        $info_bod_egreso=DB::select("SELECT * from bodega_egreso where id_bodega_egreso={$id}");
        /*$cuenta=DB::select("SELECT if(linea_producto.id_plan_cuentas_costo is null,'no','si') as exist_plan_cta,linea_producto.id_plan_cuentas_costo as id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,proyecto.id_proyecto,proyecto.descripcion,bodega_egreso_detalle.costo_total as debe,null as haber,bodega_egreso_detalle.id_bodega_egreso_detalle 
        from bodega_egreso_detalle
        LEFT JOIN proyecto
        on proyecto.id_proyecto=bodega_egreso_detalle.id_proyecto
        INNER JOIN bodega_egreso
        on bodega_egreso.id_bodega_egreso=bodega_egreso_detalle.id_bodega_egreso
        INNER JOIN bodega
        on bodega.id_bodega=bodega_egreso.id_bodega
        INNER JOIN producto
        on producto.id_producto=bodega_egreso_detalle.id_producto
        INNER JOIN linea_producto
        on linea_producto.id_linea_producto=producto.id_linea_producto
        LEFT JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=linea_producto.id_plan_cuentas_costo
        where bodega_egreso_detalle.id_bodega_egreso={$id}");*/
        $cuenta=DB::select("SELECT cuenta_bodega_egreso.id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,proyecto.id_proyecto,proyecto.descripcion,null as haber, bodega_egreso_detalle.costo_total as debe,bodega_egreso_detalle.id_bodega_egreso_detalle
        from bodega_egreso_detalle
        LEFT JOIN proyecto
        on proyecto.id_proyecto=bodega_egreso_detalle.id_proyecto
        INNER JOIN bodega_egreso
        on bodega_egreso.id_bodega_egreso=bodega_egreso_detalle.id_bodega_egreso
        INNER JOIN bodega
        on bodega.id_bodega=bodega_egreso.id_bodega
        INNER JOIN producto
        on producto.id_producto=bodega_egreso_detalle.id_producto
        INNER JOIN cuenta_bodega_egreso
        on cuenta_bodega_egreso.id_empresa=bodega.id_empresa
        LEFT JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=cuenta_bodega_egreso.id_plan_cuentas
        where bodega_egreso_detalle.id_bodega_egreso={$id} and cuenta_bodega_egreso.id_bodega={$info_bod_egreso[0]->id_bodega}");
        $fecha_emision=substr($info_bod_egreso[0]->fecha_egreso,0,-3);
        $anio_emision=substr($info_bod_egreso[0]->fecha_egreso,0,4);
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
            "info_bodega"=>$info_bod_egreso[0],
            "cuenta"=>$cuenta,
            "proyecto"=>$proyecto[0]->id_proyecto
        ];
        
    }
    public function agregarAsiento_Egreso(Request $request){
        BodegaEgreso::where('id_bodega_egreso',$request->cod_rol)->update(['contabilidad'=>'1']);
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
        $asientos->id_asientos_comprobante=16;

        $asientos->save();
        return $asientos->id_asientos;
    }
    public function agregarAsientoDetalle_Egreso(Request $request){
        foreach($request->bodegas as $haber){
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
        foreach($request->cuentas as $debe){
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
    }
    public function agregarAsiento_EgFact(Request $request){
        BodegaEgreso::where('id_bodega_egreso',$request->cod_rol)->update(['contabilidad'=>'1']);
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
        $asientos->id_asientos_comprobante=17;

        $asientos->save();
        return $asientos->id_asientos;
    }
    public function agregarAsientoDetalle_EgFact(Request $request){
        foreach($request->bodegas as $haber){
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
        foreach($request->cuentas as $debe){
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
    }
    public function generarPdf(Request $request)
    {
        $query_bd_ingreso = "SELECT bdi.*,bd.nombre,bd.responsable,bd.ubicacion,bd.direccion,bd.telefono,pro.descripcion,emp.nombre_empresa from bodega_egreso as bdi
        INNER JOIN bodega as bd
        on bd.id_bodega=bdi.id_bodega
        INNER JOIN empresa as emp
        on emp.id_empresa=bdi.id_empresa
        LEFT JOIN proyecto as pro
        on pro.id_proyecto=bdi.id_proyecto
        where bdi.id_bodega_egreso={$request->id_bodega_ingreso}";
        $reporte_bd_ingreso = DB::select($query_bd_ingreso);
        $query_bd_ingreso = "SELECT bdid.*,pro.cod_principal,pro.nombre,proy.descripcion as descripcion_proy from bodega_egreso_detalle as bdid
        INNER JOIN bodega_egreso as bdi
        on bdi.id_bodega_egreso=bdid.id_bodega_egreso
        INNER JOIN producto as pro
        on pro.id_producto=bdid.id_producto
        LEFT JOIN proyecto as proy
        on proy.id_proyecto=bdid.id_proyecto
        where bdi.id_bodega_egreso={$request->id_bodega_ingreso}";
        $reporte_bd_ingreso_detalle = DB::select($query_bd_ingreso);
        $empresa = DB::select("SELECT emp.*,'GRUPO SOLIS INGENIERIA ESPECIALIZADA' as nomb_empresa_ej,concat(usu.nombres,' ',usu.apellidos) as usuario from empresa as emp,user as usu where emp.id_empresa=usu.id_empresa and usu.id={$request->id_usuario}");
        //if($request->destinatario==null && $request->email==null){
        $pdf = new generarReportes();
        $strPDF = $pdf->PDFBodegaEg($reporte_bd_ingreso, $reporte_bd_ingreso_detalle, $empresa[0]);
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
}
