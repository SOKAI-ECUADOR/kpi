<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FacturaCompra;
use App\Models\Importacion;
use App\Models\Detalle_factura_compra;
use App\Models\Bodega;
use App\Models\BodegaIngreso;
use App\Models\BodegaIngresoDetalle;
use App\Models\ProductoBodega;
use App\Models\ProductoFactura;

use App\Models\Asientos;
use App\Models\Asientos_contables_detalle;

use DOMDocument;
use Dompdf\Dompdf;
use Dompdf\Options;


include_once getenv("FILE_CONFIG_PHP");

class LiquidacionController extends Controller
{
    //
    public function index(Request $request,$id){
        $buscar = $request->buscar;
        if ($buscar==''){
            //$recupera = DB::select("SELECT DISTINCT importacion.*,(select sum(factura_compra.subtotal_sin_impuesto) from factura_compra where importacion.id_importacion=factura_compra.id_importacion) as totales FROM `importacion`,factura_compra where importacion.id_importacion=factura_compra.id_importacion and importacion.id_punto_emision=".$id);
            $recupera = DB::select("SELECT importacion.id_importacion,importacion.cod_importacion,if(count(Distinct proveedor_importacion.id_proveedor)>1,CONCAT(min(proveedor_importacion.nombre),',',max(proveedor_importacion.nombre)),max(proveedor_importacion.nombre)) as nombre_porveedor,importacion.fech_importacion,
            (select sum(factura_compra.subtotal_sin_impuesto) from factura_compra where importacion.id_importacion=factura_compra.id_importacion) as totales,
            importacion.estado,importacion.total_importacion,importacion.contabilidad
            from importacion
            INNER JOIN producto_importacion
            on producto_importacion.id_importacion=importacion.id_importacion
            INNER JOIN proveedor_importacion
            on proveedor_importacion.id_importacion=importacion.id_importacion
            INNER JOIN factura_compra
            on factura_compra.id_importacion=importacion.id_importacion
            where importacion.id_punto_emision={$id}
            GROUP BY importacion.id_importacion");
        }else{
            //$recupera = DB::select("SELECT DISTINCT importacion.*,(select sum(factura_compra.subtotal_sin_impuesto) from factura_compra where importacion.id_importacion=factura_compra.id_importacion) as totales FROM `importacion`,factura_compra where importacion.id_importacion=factura_compra.id_importacion");
            $recupera = DB::select("SELECT importacion.id_importacion,importacion.cod_importacion,if(count(Distinct proveedor_importacion.id_proveedor)>1,CONCAT(min(proveedor_importacion.nombre),',',max(proveedor_importacion.nombre)),max(proveedor_importacion.nombre)) as nombre_porveedor,importacion.fech_importacion,
            (select sum(factura_compra.subtotal_sin_impuesto) from factura_compra where importacion.id_importacion=factura_compra.id_importacion) as totales,
            importacion.estado,importacion.total_importacion,importacion.contabilidad
            from importacion
            INNER JOIN producto_importacion
            on producto_importacion.id_importacion=importacion.id_importacion
            INNER JOIN proveedor_importacion
            on proveedor_importacion.id_importacion=importacion.id_importacion
            INNER JOIN factura_compra
            on factura_compra.id_importacion=importacion.id_importacion
            where importacion.id_punto_emision={$id}
            GROUP BY importacion.id_importacion");
        } 
        return [
            
            'recupera' => $recupera 
        ];
    }
    public function verAsiento(Request $request,$id){
        $total_factura=DB::select("SELECT sum(subtotal_sin_impuesto) as total_factura 
        from factura_compra
        INNER JOIN importacion
        on importacion.id_importacion=factura_compra.id_importacion
        where importacion.id_importacion=".$id);
        $valor_factura=0;
        $tipo_importacion=DB::select("SELECT *  from importacion where id_importacion=".$id);
        $total_cantidad=DB::select("SELECT sum(cantidad) as cantidad_total from producto_importacion where id_importacion=".$id);
        if($tipo_importacion[0]->forma_liquidacion==1){
            $valor_factura=$total_factura[0]->total_factura/$total_cantidad[0]->cantidad_total;
        }else{
            $valor_factura=$total_factura[0]->total_factura;
        }
        $query_bodegas="";
        
            $query_bodegas="SELECT plan_cuentas.id_plan_cuentas,CONCAT(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,producto_importacion.total_liquidacion as debe,null as haber,proyecto.id_proyecto,proyecto.descripcion  
            from producto_importacion
            LEFT JOIN proyecto
            on proyecto.id_proyecto=producto_importacion.id_proyecto
            LEFT JOIN bodega
            on bodega.id_bodega=producto_importacion.id_bodega
            LEFT JOIN plan_cuentas
            on plan_cuentas.id_plan_cuentas=bodega.id_plan_cuentas
            where producto_importacion.id_importacion={$id}
            ORDER BY bodega.nombre asc";
        
        $proveedores=DB::select("SELECT * from proveedor_importacion where id_importacion=".$id);
        $bodegas=DB::select($query_bodegas);
        $cuenta=DB::select("SELECT (select count(id_plan_cuentas) from cuenta_importacion where id_empresa={$request->id_empresa}) as nro_plan_cuentas,
        plan_cuentas.id_plan_cuentas,
        CONCAT(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,null debe,producto_importacion.total_liquidacion as haber,
        proyecto.id_proyecto,
        proyecto.descripcion
        
        from producto_importacion
                                LEFT JOIN proyecto
                                on proyecto.id_proyecto=producto_importacion.id_proyecto
                    INNER JOIN importacion
                    on importacion.id_importacion=producto_importacion.id_importacion
                    LEFT JOIN cuenta_importacion
                    on cuenta_importacion.id_empresa=importacion.id_empresa
                    LEFT JOIN plan_cuentas
                    on plan_cuentas.id_plan_cuentas=cuenta_importacion.id_plan_cuentas
                    where producto_importacion.id_importacion={$id}
                                ORDER BY proyecto.id_proyecto asc");
        $codigo=DB::select("SELECT max(numero) as codigo FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.codigo like 'IP-%' and (asientos.estado='Activo' or asientos.estado is null) and proyecto.id_empresa=".$request->id_empresa);
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
            $codigo_ant=DB::select("SELECT asientos.numero FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.id_asientos_comprobante=10 and (asientos.estado='Activo' or asientos.estado is null) and asientos.codigo_rol={$id} and proyecto.id_empresa=".$request->id_empresa." ORDER BY asientos.codigo desc limit 1");
            if($codigo_ant){
                $cod_asiento_ant=$codigo_ant[0]->numero;
            }
            
            
        }
        $proyecto=DB::select("SELECT id_proyecto from proyecto where id_empresa={$request->id_empresa} limit 1");
        $fecha_emision=substr($tipo_importacion[0]->fech_importacion,0,-3);
        $anio_emision=substr($tipo_importacion[0]->fech_importacion,0,4);
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
            "importacion"=>$tipo_importacion[0],
            "proveedores"=>$proveedores,
            "bodegas"=>$bodegas,
            "cuenta"=>$cuenta,
            "proyecto"=>$proyecto[0]->id_proyecto
        ];
    }
    public function agregarAsiento(Request $request){
        Importacion::where('id_importacion',$request->cod_rol)->update(['contabilidad'=>'1']);
        $asientos=new Asientos();
        $asientos->automatico=0;
        $asientos->numero=$request->numero;
        $asientos->codigo=$request->codigo;
        $asientos->codigo_rol=$request->cod_rol;
        $asientos->fecha=$request->fecha;
        $asientos->razon_social=$request->razon_social;
        $asientos->tipo_identificacion=$request->tipo_identificacion;
        $asientos->ruc_ci=$request->ruc_ci;
        $asientos->concepto=$request->concepto;
        $asientos->ucrea=$request->ucrea;
        $asientos->id_proyecto=$request->id_proyecto;
        $asientos->id_asientos_comprobante=10;

        $asientos->save();
        return $asientos->id_asientos;
    }
    public function agregarAsientoDetalle(Request $request){
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
    public function abrir(Request $request){
        $id=$request->id;
        $recupera = Importacion::where('importacion.id_importacion', '=', $id)->get();

        return $recupera;
    }
    public function abrirFactura($id){
        $recupera = DB::select("SELECT sum(detalle_factura_compra.total) as total,detalle_factura_compra.nombre,factura_compra.id_factcompra,factura_compra.id_importacion
         from detalle_factura_compra,factura_compra
          where detalle_factura_compra.id_factura=factura_compra.id_factcompra
           and detalle_factura_compra.importacion=1  and factura_compra.id_importacion=".$id." "."GROUP BY detalle_factura_compra.id_producto");
        return $recupera;
    }
    public function abrirBodega($pto){
        
        $recupera = Bodega::where('id_establecimiento', '=', $pto)
        ->get();

        return $recupera;
    }
   
    public function liquidar(Request $request){
        ini_set('max_execution_time', 3600);
        //dd($request->total_costo);
        $import=Importacion::find($request->id);
        $import->estado="Liquidado";
        $import->fech_importacion=$request->fecha_ingreso;
        $import->total_facturas=$request->totalfac;
        $import->total_liquidacion=$request->total;
        $import->total_importacion=$request->totalfac;
        //$import->id_bodega=$request->id_bodega;
        //$import->id_proyecto=$request->id_proyecto;
        $import->save();
        $id=[];
                    $v1=$request->cantiunitario1;
                    $v2=$request->cantunitario2;
                    $v3=$request->cantunitario3;
                    $v4=$request->cantunitario4;
                    $v5=$request->cantunitario5;
                    $v6=$request->cantunitario6;
                    $v7=$request->cantunitario7;
                    $v8=$request->cantunitario8;
                    $v9=$request->cantunitario9;
                    $v10=$request->cantunitario10;
                    $v11=$request->cantunitario11;
                    $v12=$request->cantunitario12;
                    $v13=$request->cantunitario13;
                    $v14=$request->cantunitario14;
                    $v15=$request->cantunitario15;
        $nombre_proveedores=[];
        for($p=0;$p<count($request->proveedores);$p++){
            array_push($nombre_proveedores,$request->proveedores[$p]["nombre"]);
        }
        $nombre_proveedores=implode(" , ",$nombre_proveedores);
        for($a = 0; $a < count($request->bodegas); $a++){
            $select = DB::select("SELECT num_ingreso FROM bodega_ingreso where id_empresa=".$request->id_empresa." ORDER BY id_bodega_ingreso DESC LIMIT 1");
            $principal = "";
            if($select){
                if (count($select) >= 1) {
                    $dato = $select[0]->num_ingreso;
                    if(($dato+1) >= 100){
                        $tot = $dato + 1;
                        $principal = $tot;
                    }else if(($dato+1) >= 10){
                        $tot = $dato + 1;
                        $principal = "0".$tot;
                    }else{
                        $tot = $dato + 1;
                        $principal = "00".$tot;
                    }
                } else {
                    $principal = "001";
                }
            }else{
                $principal = "001";
            }
            //if($request->contenidopr[$a]["sector"]<2){
                $ingreso = new BodegaIngreso();
                $ingreso->num_ingreso = $principal;
                $ingreso->fecha_ingreso = $request->fecha_ingreso;
                $ingreso->tipo_ingreso = "Importacion";//.$import->cod_importacion;
                $ingreso->observ_ingreso= "Importacion: ".$import->cod_importacion." Proveedor: ".$nombre_proveedores;
                $ingreso->ucrea= $request->ucrea; 
                $ingreso->id_proyecto= $request->bodegas[$a]["id_proyecto"];
                $ingreso->id_bodega= $request->bodegas[$a]["id_bodega"];
                $ingreso->id_empresa = $request->id_empresa;
                $ingreso->id_importacion = $request->id;
                $ingreso->save();
                $id_bodega_ingreso=$ingreso->id_bodega_ingreso;
                array_push($id,$id_bodega_ingreso);
                
            //}
            
            
        }
        // for($b=0;$b<count($request->contenidopr);$b++){
                    
        //     if($request->tipo_calculo=='1'){
        //         $ingreso_detalle = new BodegaIngresoDetalle();
        //         $ingreso_detalle->cantidad = $request->contenidopr[$a]["cantidad"];
        //         $ingreso_detalle->costo_unitario=$v1+$v2+$v3+$v4+$v5+$v6+$v7+$v8+$v9+$v10+$v11+$v12+$v13+$v14+$v15;
        //         $ingreso_detalle->costo_total= $request->contenidopr[$a]["cantidad"]*($v1+$v2+$v3+$v4+$v5+$v6+$v7+$v8+$v9+$v10+$v11+$v12+$v13+$v14+$v15);
        //         $select_bodega_ingreso=DB::select("SELECT id_bodega_ingreso from bodega_ingreso where id_bodega={$request->contenidopr[$a]["id_bodega"]} ORDER BY id_bodega_ingreso DESC LIMIT 1");
        //         $ingreso_detalle->id_bodega_ingreso= $select_bodega_ingreso[0]->id_bodega_ingreso;
        //         $ingreso_detalle->id_producto = $request->contenidopr[$a]["id_producto"];
        //         $ingreso_detalle->id_proyecto = $request->contenidopr[$a]["proyecto"];  
        //         $ingreso_detalle->save();
        //     }else{
        //         $ingreso_detalle = new BodegaIngresoDetalle();
        //         $ingreso_detalle->cantidad = $request->contenidopr[$a]["cantidad"];
        //         //$ingreso_detalle->costo_unitario=$request->contenidopr[$a]["precio"]+$v1+$v2+$v3+$v4+$v5+$v6+$v7+$v8+$v9;
        //         //$ingreso_detalle->costo_total= $request->contenidopr[$a]["cantidad"]*($request->contenidopr[$a]["precio"]+$v1+$v2+$v3+$v4+$v5+$v6+$v7+$v8+$v9);   
        //         $ingreso_detalle->costo_unitario=//$request->contenidopr[$a]["precio"]+
        //         ($v1*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total)/$request->contenidopr[$a]["cantidad"])+
        //         ($v2*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total)/$request->contenidopr[$a]["cantidad"])+
        //         ($v3*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total)/$request->contenidopr[$a]["cantidad"])+
        //         ($v4*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total)/$request->contenidopr[$a]["cantidad"])+
        //         ($v5*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total)/$request->contenidopr[$a]["cantidad"])+
        //         ($v6*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total)/$request->contenidopr[$a]["cantidad"])+
        //         ($v7*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total)/$request->contenidopr[$a]["cantidad"])+
        //         ($v8*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total)/$request->contenidopr[$a]["cantidad"])+
        //         ($v9*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total)/$request->contenidopr[$a]["cantidad"])+
        //         ($v10*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total)/$request->contenidopr[$a]["cantidad"])+
        //         ($v11*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total)/$request->contenidopr[$a]["cantidad"])+
        //         ($v12*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total)/$request->contenidopr[$a]["cantidad"])+
        //         ($v13*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total)/$request->contenidopr[$a]["cantidad"])+
        //         ($v14*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total)/$request->contenidopr[$a]["cantidad"])+
        //         ($v15*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total)/$request->contenidopr[$a]["cantidad"]);
        //         $ingreso_detalle->costo_total= $request->contenidopr[$a]["cantidad"]*(//$request->contenidopr[$a]["precio"]+
        //         ($v1*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total)/$request->contenidopr[$a]["cantidad"])+
        //         ($v2*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total)/$request->contenidopr[$a]["cantidad"])+
        //         ($v3*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total)/$request->contenidopr[$a]["cantidad"])+
        //         ($v4*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total)/$request->contenidopr[$a]["cantidad"])+
        //         ($v5*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total)/$request->contenidopr[$a]["cantidad"])+
        //         ($v6*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total)/$request->contenidopr[$a]["cantidad"])+
        //         ($v7*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total)/$request->contenidopr[$a]["cantidad"])+
        //         ($v8*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total)/$request->contenidopr[$a]["cantidad"])+
        //         ($v9*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total)/$request->contenidopr[$a]["cantidad"])+
        //         ($v10*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total)/$request->contenidopr[$a]["cantidad"])+
        //         ($v11*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total)/$request->contenidopr[$a]["cantidad"])+
        //         ($v12*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total)/$request->contenidopr[$a]["cantidad"])+
        //         ($v13*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total)/$request->contenidopr[$a]["cantidad"])+
        //         ($v14*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total)/$request->contenidopr[$a]["cantidad"])+
        //         ($v15*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total)/$request->contenidopr[$a]["cantidad"])
        //         ); 
        //         $select_bodega_ingreso=DB::select("SELECT id_bodega_ingreso from bodega_ingreso where id_bodega={$request->contenidopr[$a]["id_bodega"]} ORDER BY id_bodega_ingreso DESC LIMIT 1");
        //         $ingreso_detalle->id_bodega_ingreso= $select_bodega_ingreso[0]->id_bodega_ingreso;
        //         $ingreso_detalle->id_producto = $request->contenidopr[$a]["id_producto"]; 
        //         $ingreso_detalle->id_proyecto = $request->contenidopr[$a]["proyecto"];
        //         $ingreso_detalle->save();
        //     }
        
        // }
        
        return $id;
    }
    public function guardarBodegaIngreso(Request $request){
        ini_set('max_execution_time', 3600);
        $id=$request->id_bodega_ingreso;
        $id_bodega_ingreso=implode(";",$id);
        for ($a = 0; $a < count($request->contenidopr); $a++) {
            $sel = DB::select("SELECT * FROM `producto_bodega` WHERE `id_producto` = ". $request->contenidopr[$a]["id_producto"] ." AND `id_bodega` =". $request->contenidopr[$a]["id_bodega"]);
            
                if($request->contenidopr[$a]["sector"]<2){
                    if(count($sel)<=0){  
                        $v1=$request->cantiunitario1;
                        $v2=$request->cantunitario2;
                        $v3=$request->cantunitario3;
                        $v4=$request->cantunitario4;
                        $v5=$request->cantunitario5;
                        $v6=$request->cantunitario6;
                        $v7=$request->cantunitario7;
                        $v8=$request->cantunitario8;
                        $v9=$request->cantunitario9;
                        $v10=$request->cantunitario10;
                        $v11=$request->cantunitario11;
                        $v12=$request->cantunitario12;
                        $v13=$request->cantunitario13;
                        $v14=$request->cantunitario14;
                        $v15=$request->cantunitario15;
                        $v16=$request->cantunitario16;
                        $v17=$request->cantunitario17;
                        $v18=$request->cantunitario18;
                        $v19=$request->cantunitario19;
                        $v20=$request->cantunitario20;
                        
        
                        if($request->tipo_calculo=='1'){ 
                            //Guarda en la tabla producto_bodega en el inventario de bodega
                            $prb = new ProductoBodega();
                            $prb->cantidad = $request->contenidopr[$a]["cantidad"];
                            $prb->costo_unitario=$v1+$v2+$v3+$v4+$v5+$v6+$v7+$v8+$v9+$v10+$v11+$v12+$v13+$v14+$v15+$v16+$v17+$v18+$v19+$v20;
                            $prb->costo_total= $request->contenidopr[$a]["cantidad"]*($v1+$v2+$v3+$v4+$v5+$v6+$v7+$v8+$v9+$v10+$v11+$v12+$v13+$v14+$v15+$v16+$v17+$v18+$v19+$v20);
                            $prb->id_producto = $request->contenidopr[$a]["id_producto"];
                            $prb->id_bodega = $request->contenidopr[$a]["id_bodega"];
                            $prb->id_empresa = $request->id_empresa;
                            $prb->save();
            
                            $idproducto = $prb->id_producto_bodega;
    
    
                                    $ingreso_detalle = new BodegaIngresoDetalle();
                                    $ingreso_detalle->cantidad = $request->contenidopr[$a]["cantidad"];
                                    $ingreso_detalle->costo_unitario=$v1+$v2+$v3+$v4+$v5+$v6+$v7+$v8+$v9+$v10+$v11+$v12+$v13+$v14+$v15+$v16+$v17+$v18+$v19+$v20;
                                    $ingreso_detalle->costo_total= $request->contenidopr[$a]["cantidad"]*($v1+$v2+$v3+$v4+$v5+$v6+$v7+$v8+$v9+$v10+$v11+$v12+$v13+$v14+$v15+$v16+$v17+$v18+$v19+$v20);
                                    $select_bodega_ingreso=DB::select("SELECT id_bodega_ingreso from bodega_ingreso where id_bodega={$request->contenidopr[$a]["id_bodega"]} and observ_ingreso like '%Importacion: {$request->codigo_import}%' ORDER BY id_bodega_ingreso DESC LIMIT 1");
                                    $ingreso_detalle->id_bodega_ingreso= $select_bodega_ingreso[0]->id_bodega_ingreso;
                                    $ingreso_detalle->id_producto = $request->contenidopr[$a]["id_producto"];
                                    $ingreso_detalle->id_proyecto = $request->contenidopr[$a]["proyecto"];  
                                    $ingreso_detalle->id_producto_importacion = $request->contenidopr[$a]["id_prodimp"];  
                                    $ingreso_detalle->save();
        
                            $prodimpor=ProductoFactura::find($request->contenidopr[$a]["id_prodimp"]);
                            $prodimpor->cantidad_liquidacion = $request->contenidopr[$a]["cantidad"];
                            $prodimpor->precio_liquidacion = $v1+$v2+$v3+$v4+$v5+$v6+$v7+$v8+$v9+$v10+$v11+$v12+$v13+$v14+$v15+$v16+$v17+$v18+$v19+$v20;
                            $prodimpor->total_liquidacion = $request->contenidopr[$a]["cantidad"]*($v1+$v2+$v3+$v4+$v5+$v6+$v7+$v8+$v9+$v10+$v11+$v12+$v13+$v14+$v15+$v16+$v17+$v18+$v19+$v20);
                            $prodimpor->save();
                            //return $prodimpor."4";
                        }else{  
                            //Guarda en la tabla producto_bodega en el inventario de bodega
                            $prb = new ProductoBodega();
                            $cant=$request->contenidopr[$a]["cantidad"];
                            $costo_tot=($request->contenidopr[$a]["cantidad"]*(//$request->contenidopr[$a]["precio"]+
                            // ($v1*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            // ($v2*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            // ($v3*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            // ($v4*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            // ($v5*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            // ($v6*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            // ($v7*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            // ($v8*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            // ($v9*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            // ($v10*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            // ($v11*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            // ($v12*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            // ($v13*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            // ($v14*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            // ($v15*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])
                            ($v1*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v2*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v3*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v4*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v5*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v6*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v7*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v8*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v9*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v10*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v11*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v12*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v13*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v14*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v15*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v16*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v17*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v18*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v19*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v20*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])
                        ));
                            
                            $costo_uni=$costo_tot/$cant;
                            $prb->cantidad =$cant;
                            $prb->costo_unitario=$costo_uni;
                            $prb->costo_total=$costo_tot;
                            $prb->id_producto = $request->contenidopr[$a]["id_producto"];
                            $prb->id_bodega = $request->contenidopr[$a]["id_bodega"];
                            $prb->id_empresa = $request->id_empresa;
                            $prb->save();
            
                            $idproducto = $prb->id_producto_bodega;
    
                            /*
                            $ingreso->costo_unitario=$request->contenidopr[$a]["precio"]+
                            ($v1*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            ($v2*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            ($v3*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            ($v4*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            ($v5*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            ($v6*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            ($v7*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            ($v8*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            ($v9*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"]);
                            $ingreso->costo_total= $request->contenidopr[$a]["cantidad"]*($request->contenidopr[$a]["precio"]+
                            ($v1*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            ($v2*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            ($v3*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            ($v4*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            ($v5*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            ($v6*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            ($v7*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            ($v8*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            ($v9*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"]));
                            */
                                    $ingreso_detalle = new BodegaIngresoDetalle();
                                    $ingreso_detalle->cantidad = $request->contenidopr[$a]["cantidad"];
                                    //$ingreso_detalle->costo_unitario=$request->contenidopr[$a]["precio"]+$v1+$v2+$v3+$v4+$v5+$v6+$v7+$v8+$v9;
                                    //$ingreso_detalle->costo_total= $request->contenidopr[$a]["cantidad"]*($request->contenidopr[$a]["precio"]+$v1+$v2+$v3+$v4+$v5+$v6+$v7+$v8+$v9);   
                                    $ingreso_detalle->costo_unitario=//$request->contenidopr[$a]["precio"]+
                                    ($v1*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v2*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v3*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v4*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v5*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v6*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v7*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v8*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v9*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v10*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v11*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v12*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v13*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v14*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v15*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v16*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v17*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v18*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v19*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v20*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"]);
                                    $ingreso_detalle->costo_total= $request->contenidopr[$a]["cantidad"]*(//$request->contenidopr[$a]["precio"]+
                                    ($v1*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v2*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v3*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v4*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v5*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v6*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v7*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v8*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v9*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v10*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v11*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v12*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v13*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v14*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v15*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v16*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v17*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v18*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v19*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v20*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])
                                    ); 
                                    $select_bodega_ingreso=DB::select("SELECT id_bodega_ingreso from bodega_ingreso where id_bodega={$request->contenidopr[$a]["id_bodega"]} and observ_ingreso like '%Importacion: {$request->codigo_import}%' ORDER BY id_bodega_ingreso DESC LIMIT 1");
                                    $ingreso_detalle->id_bodega_ingreso= $select_bodega_ingreso[0]->id_bodega_ingreso;
                                    $ingreso_detalle->id_producto = $request->contenidopr[$a]["id_producto"]; 
                                    $ingreso_detalle->id_proyecto = $request->contenidopr[$a]["proyecto"];
                                    $ingreso_detalle->id_producto_importacion = $request->contenidopr[$a]["id_prodimp"];
                                    $ingreso_detalle->save();
                            
                            
                            $prodimpor=ProductoFactura::find($request->contenidopr[$a]["id_prodimp"]);
                            $prodimpor->cantidad_liquidacion = $request->contenidopr[$a]["cantidad"];
                            $prodimpor->precio_liquidacion = //$request->contenidopr[$a]["precio"]+
                            ($v1*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v2*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v3*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v4*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v5*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v6*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v7*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v8*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v9*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v10*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v11*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v12*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v13*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v14*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v15*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v16*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v17*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v18*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v19*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v20*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"]);
                            $prodimpor->total_liquidacion = $request->contenidopr[$a]["cantidad"]*
                            (//$request->contenidopr[$a]["precio"]+
                            ($v1*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v2*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v3*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v4*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v5*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v6*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v7*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v8*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v9*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v10*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v11*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v12*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v13*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v14*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v15*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v16*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v17*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v18*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v19*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v20*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])
                        );
                            $prodimpor->save();
                            //return $prodimpor."5";
                        }
                        
                    }else{
                        $v1=$request->cantiunitario1;
                        $v2=$request->cantunitario2;
                        $v3=$request->cantunitario3;
                        $v4=$request->cantunitario4;
                        $v5=$request->cantunitario5;
                        $v6=$request->cantunitario6;
                        $v7=$request->cantunitario7;
                        $v8=$request->cantunitario8;
                        $v9=$request->cantunitario9;
                        $v10=$request->cantunitario10;
                        $v11=$request->cantunitario11;
                        $v12=$request->cantunitario12;
                        $v13=$request->cantunitario13;
                        $v14=$request->cantunitario14;
                        $v15=$request->cantunitario15;
                        $v16=$request->cantunitario16;
                        $v17=$request->cantunitario17;
                        $v18=$request->cantunitario18;
                        $v19=$request->cantunitario19;
                        $v20=$request->cantunitario20;
                        
        
                        
        
                        if($request->tipo_calculo=='1'){ 
                            $prb = ProductoBodega::find($sel[0]->id_producto_bodega);
                            //echo $sel[0]->id_producto_bodega;
                            $cant=$prb->cantidad + $request->contenidopr[$a]["cantidad"];
                            $costo_tot=$prb->costo_total+($request->contenidopr[$a]["cantidad"]*($v1+$v2+$v3+$v4+$v5+$v6+$v7+$v8+$v9+$v10+$v11+$v12+$v13+$v14+$v15+$v16+$v17+$v18+$v19+$v20));
                            $costo_uni=$costo_tot/$cant;
                            $prb->cantidad =$cant;
                            $prb->costo_unitario=$costo_uni;
                            $prb->costo_total=$costo_tot;
                            $prb->save();
                            $idproducto = $prb->id_producto_bodega;
    
                                    $ingreso_detalle = new BodegaIngresoDetalle();
                                    $ingreso_detalle->cantidad = $request->contenidopr[$a]["cantidad"];
                                    $ingreso_detalle->costo_unitario=$v1+$v2+$v3+$v4+$v5+$v6+$v7+$v8+$v9+$v10+$v11+$v12+$v13+$v14+$v15+$v16+$v17+$v18+$v19+$v20;
                                    $ingreso_detalle->costo_total= $request->contenidopr[$a]["cantidad"]*($v1+$v2+$v3+$v4+$v5+$v6+$v7+$v8+$v9+$v10+$v11+$v12+$v13+$v14+$v15+$v16+$v17+$v18+$v19+$v20);
                                    $select_bodega_ingreso=DB::select("SELECT id_bodega_ingreso from bodega_ingreso where id_bodega={$request->contenidopr[$a]["id_bodega"]} and observ_ingreso like '%Importacion: {$request->codigo_import}%' ORDER BY id_bodega_ingreso DESC LIMIT 1");
                                    $ingreso_detalle->id_bodega_ingreso= $select_bodega_ingreso[0]->id_bodega_ingreso;
                                    $ingreso_detalle->id_producto = $request->contenidopr[$a]["id_producto"];
                                    $ingreso_detalle->id_proyecto = $request->contenidopr[$a]["proyecto"]; 
                                    $ingreso_detalle->id_producto_importacion = $request->contenidopr[$a]["id_prodimp"]; 
                                    $ingreso_detalle->save();
        
                            $prodimpor=ProductoFactura::find($request->contenidopr[$a]["id_prodimp"]);
                            $prodimpor->cantidad_liquidacion = $request->contenidopr[$a]["cantidad"];
                            $prodimpor->precio_liquidacion =$v1+$v2+$v3+$v4+$v5+$v6+$v7+$v8+$v9+$v10+$v11+$v12+$v13+$v14+$v15+$v16+$v17+$v18+$v19+$v20;
                            $prodimpor->total_liquidacion = $request->contenidopr[$a]["cantidad"]*($v1+$v2+$v3+$v4+$v5+$v6+$v7+$v8+$v9+$v10+$v11+$v12+$v13+$v14+$v15+$v16+$v17+$v18+$v19+$v20);
                            $prodimpor->save();
                            //return $prodimpor."6";
                        }else{  
                            $prb = ProductoBodega::find($sel[0]->id_producto_bodega);
                            //echo $sel[0]->id_producto_bodega;
                            $cant=$prb->cantidad + $request->contenidopr[$a]["cantidad"];
                            $costo_tot=$prb->costo_total+($request->contenidopr[$a]["cantidad"]*(//$request->contenidopr[$a]["precio"]+
                            // ($v1*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            // ($v2*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            // ($v3*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            // ($v4*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            // ($v5*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            // ($v6*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            // ($v7*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            // ($v8*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            // ($v9*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            // ($v10*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            // ($v11*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            // ($v12*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            // ($v13*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            // ($v14*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            // ($v15*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])
                            ($v1*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v2*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v3*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v4*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v5*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v6*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v7*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v8*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v9*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v10*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v11*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v12*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v13*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v14*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v15*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v16*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v17*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v18*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v19*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v20*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])
                        ));
                            $costo_uni=$costo_tot/$cant;
                            $prb->cantidad =$cant;
                            $prb->costo_unitario=$costo_uni;
                            $prb->costo_total=$costo_tot;
                            $prb->save();
                            $idproducto = $prb->id_producto_bodega;
    
                            
                            /*
                            $ingreso->costo_unitario=$request->contenidopr[$a]["precio"]+
                            ($v1*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            ($v2*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            ($v3*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            ($v4*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            ($v5*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            ($v6*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            ($v7*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            ($v8*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            ($v9*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"]);
                            $ingreso->costo_total= $request->contenidopr[$a]["cantidad"]*($request->contenidopr[$a]["precio"]+
                            ($v1*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            ($v2*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            ($v3*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            ($v4*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            ($v5*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            ($v6*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            ($v7*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            ($v8*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                            ($v9*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"]));
                            */
                                    $ingreso_detalle = new BodegaIngresoDetalle();
                                    $ingreso_detalle->cantidad = $request->contenidopr[$a]["cantidad"];
                                    //$ingreso_detalle->costo_unitario=$request->contenidopr[$a]["precio"]+$v1+$v2+$v3+$v4+$v5+$v6+$v7+$v8+$v9;
                                    //$ingreso_detalle->costo_total= $request->contenidopr[$a]["cantidad"]*($request->contenidopr[$a]["precio"]+$v1+$v2+$v3+$v4+$v5+$v6+$v7+$v8+$v9);   
                                    $ingreso_detalle->costo_unitario=//$request->contenidopr[$a]["precio"]+
                                    ($v1*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v2*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v3*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v4*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v5*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v6*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v7*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v8*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v9*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v10*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v11*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v12*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v13*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v14*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v15*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v16*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v17*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v18*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v19*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v20*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"]);
                                    $ingreso_detalle->costo_total= $request->contenidopr[$a]["cantidad"]*(//$request->contenidopr[$a]["precio"]+
                                    ($v1*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v2*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v3*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v4*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v5*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v6*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v7*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v8*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v9*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v10*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v11*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v12*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v13*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v14*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v15*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v16*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v17*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v18*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v19*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                                    ($v20*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])
                                    ); 
                                    $select_bodega_ingreso=DB::select("SELECT id_bodega_ingreso from bodega_ingreso where id_bodega={$request->contenidopr[$a]["id_bodega"]} and observ_ingreso like '%Importacion: {$request->codigo_import}%' ORDER BY id_bodega_ingreso DESC LIMIT 1");
                                    $ingreso_detalle->id_bodega_ingreso= $select_bodega_ingreso[0]->id_bodega_ingreso;
                                    $ingreso_detalle->id_producto = $request->contenidopr[$a]["id_producto"]; 
                                    $ingreso_detalle->id_proyecto = $request->contenidopr[$a]["proyecto"];
                                    $ingreso_detalle->id_producto_importacion = $request->contenidopr[$a]["id_prodimp"];
                                    $ingreso_detalle->save();
                            
                            
                            $prodimpor=ProductoFactura::find($request->contenidopr[$a]["id_prodimp"]);
                            $prodimpor->cantidad_liquidacion = $request->contenidopr[$a]["cantidad"];
                            $prodimpor->precio_liquidacion = //$request->contenidopr[$a]["precio"]+
                            ($v1*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v2*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v3*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v4*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v5*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v6*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v7*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v8*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v9*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v10*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v11*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v12*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v13*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v14*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v15*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v16*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v17*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v18*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v19*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v20*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"]);
                            $prodimpor->total_liquidacion = $request->contenidopr[$a]["cantidad"]*
                            (//$request->contenidopr[$a]["precio"]+
                            ($v1*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v2*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v3*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v4*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v5*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v6*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v7*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v8*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v9*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v10*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v11*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v12*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v13*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v14*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v15*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v16*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v17*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v18*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v19*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                            ($v20*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])
                        );
                            $prodimpor->save();
                            //return $prodimpor."7";
                        }
                    }
                }
            /*}else{
                $v1=$request->cantiunitario1;
                $v2=$request->cantunitario2;
                $v3=$request->cantunitario3;
                $v4=$request->cantunitario4;
                $v5=$request->cantunitario5;
                $v6=$request->cantunitario6;
                $v7=$request->cantunitario7;
                $v8=$request->cantunitario8;
                $v9=$request->cantunitario9;
                //Guarda en la tabla producto_bodega en el inventario de bodega
                $prb = new ProductoBodega();
                $prb->cantidad = $request->contenidopr[$a]["cantidad"];
                $prb->costo_unitario=number_format($request->contenidopr[$a]["precio"]+$v1+$v2+$v3+$v4+$v5+$v6+$v7+$v8+$v9,2,",",".");
                $prb->costo_total=number_format( $request->contenidopr[$a]["cantidad"]*($request->contenidopr[$a]["precio"]+$v1+$v2+$v3+$v4+$v5+$v6+$v7+$v8+$v9),2,",",".");
                $prb->id_producto = $request->contenidopr[$a]["id_producto"];
                $prb->id_bodega = $request->id_bodega;
                $prb->id_empresa = $request->id_empresa;
                $prb->save();

                $idproducto = $prb->id_producto_bodega;

                if($request->tipo_calculo=='1'){ 
                    $ingreso = new BodegaIngreso();
                    $ingreso->num_ingreso = $principal;
                    $ingreso->fecha_ingreso = $request->fecha_ingreso;
                    $ingreso->tipo_ingreso = "Importacion";
                    $ingreso->observ_ingreso= "Productos Importacion";
                    $ingreso->ucrea= $request->ucrea; 
                    $ingreso->id_proyecto= $request->id_proyecto;
                    $ingreso->id_bodega= $request->id_bodega;
                    $ingreso->id_empresa = $request->id_empresa;
                    $ingreso->save();
                    $id_bodega_ingreso=$ingreso->id_bodega_ingreso;

                    $ingreso_detalle = new BodegaIngresoDetalle();
                    $ingreso_detalle->cantidad = $request->contenidopr[$a]["cantidad"];
                    $ingreso_detalle->costo_unitario=number_format($request->contenidopr[$a]["precio"]+$v1+$v2+$v3+$v4+$v5+$v6+$v7+$v8+$v9,2,",",".");
                    $ingreso_detalle->costo_total= number_format($request->contenidopr[$a]["cantidad"]*($request->contenidopr[$a]["precio"]+$v1+$v2+$v3+$v4+$v5+$v6+$v7+$v8+$v9),2,",",".");
                    $ingreso_detalle->id_bodega_ingreso= $id_bodega_ingreso;
                    $ingreso_detalle->id_producto = $request->contenidopr[$a]["id_producto"]; 
                    $ingreso_detalle->save();

                    $prodimpor=ProductoFactura::find($request->contenidopr[$a]["id_prodimp"]);
                    $prodimpor->cantidad = $request->contenidopr[$a]["cantidad"];
                    $prodimpor->precio = $request->contenidopr[$a]["precio"]+$v1+$v2+$v3+$v4+$v5+$v6+$v7+$v8+$v9;
                    $prodimpor->total = $request->contenidopr[$a]["cantidad"]*($request->contenidopr[$a]["precio"]+$v1+$v2+$v3+$v4+$v5+$v6+$v7+$v8+$v9);
                    $prodimpor->save();
                    return $prodimpor."8";
                }else{  
                        $ingreso = new BodegaIngreso();
                        $ingreso->num_ingreso = $principal;
                        $ingreso->fecha_ingreso = $request->fecha_ingreso;
                        $ingreso->tipo_ingreso = "Importacion";
                        $ingreso->observ_ingreso= "Productos Importacion";
                        $ingreso->ucrea= $request->ucrea; 
                        $ingreso->id_proyecto= $request->id_proyecto;
                        $ingreso->id_bodega= $request->id_bodega;
                        
                        $ingreso->id_empresa = $request->id_empresa;
                        $ingreso->save();
                        $id_bodega_ingreso=$ingreso->id_bodega_ingreso;
                        
                        //$ingreso->costo_unitario=$request->contenidopr[$a]["precio"]+
                        //($v1*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                        //($v2*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                        //($v3*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                        //($v4*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                        //($v5*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                        //($v6*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                        //($v7*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                        //($v8*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                        //($v9*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"]);
                        //$ingreso->costo_total= $request->contenidopr[$a]["cantidad"]*($request->contenidopr[$a]["precio"]+
                        //($v1*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                        //($v2*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                        //($v3*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                        //($v4*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                        //($v5*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                        //($v6*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                        //($v7*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                        //($v8*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"])+
                        //($v9*($request->contenidopr[$a]["precio"]/$request->costounitario)/$request->contenidopr[$a]["cantidad"]));
                        
                        $ingreso_detalle = new BodegaIngresoDetalle();
                        $ingreso_detalle->cantidad = $request->contenidopr[$a]["cantidad"];
                        $ingreso_detalle->costo_unitario=$request->contenidopr[$a]["precio"]+$v1+$v2+$v3+$v4+$v5+$v6+$v7+$v8+$v9;
                        $ingreso_detalle->costo_total= $request->contenidopr[$a]["cantidad"]*($request->contenidopr[$a]["precio"]+$v1+$v2+$v3+$v4+$v5+$v6+$v7+$v8+$v9);   
                        $ingreso_detalle->costo_unitario=number_format($request->contenidopr[$a]["precio"]+
                        ($v1*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                        ($v2*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                        ($v3*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                        ($v4*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                        ($v5*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                        ($v6*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                        ($v7*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                        ($v8*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                        ($v9*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"]),2,",",".");
                        $ingreso_detalle->costo_total= number_format($request->contenidopr[$a]["cantidad"]*($request->contenidopr[$a]["precio"]+
                        ($v1*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                        ($v2*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                        ($v3*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                        ($v4*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                        ($v5*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                        ($v6*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                        ($v7*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                        ($v8*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                        ($v9*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])),2,",","."); 
                        $ingreso_detalle->id_bodega_ingreso= $id_bodega_ingreso;
                        $ingreso_detalle->id_producto = $request->contenidopr[$a]["id_producto"]; 
                        $ingreso_detalle->save();
                        
                        
                        $prodimpor=ProductoFactura::find($request->contenidopr[$a]["id_prodimp"]);
                        $prodimpor->cantidad = $request->contenidopr[$a]["cantidad"];
                        $prodimpor->precio = $request->contenidopr[$a]["precio"]+
                        ($v1*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                        ($v2*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                        ($v3*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                        ($v4*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                        ($v5*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                        ($v6*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                        ($v7*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                        ($v8*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                        ($v9*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"]);
                        $prodimpor->total = $request->contenidopr[$a]["cantidad"]*
                        ($request->contenidopr[$a]["precio"]+
                        ($v1*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                        ($v2*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                        ($v3*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                        ($v4*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                        ($v5*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                        ($v6*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                        ($v7*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                        ($v8*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])+
                        ($v9*(($request->contenidopr[$a]["precio"]*$request->contenidopr[$a]["cantidad"])/$request->total_costo)/$request->contenidopr[$a]["cantidad"])
                    );
                        $prodimpor->save();
                        return $prodimpor."9";
                }
            }*/
            
        }
       
        

        //return $request->totalfac+$request->total_costo;
        
    }
    


}
