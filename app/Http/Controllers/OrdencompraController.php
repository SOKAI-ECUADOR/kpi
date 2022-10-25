<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\FacturaCompra;
use Illuminate\Http\Request;
use App\Models\Ordencompra;
use App\Models\ProductoFactura;
use App\Models\Productoorden;
use App\Models\Proveedor;
use App\Models\Detalle_factura_compra;
use App\Models\GrupoProveedor;
include 'class/generarReportes.php';
use generarReportes;

include 'class/sendEmail.php';

use sendEMail;



class OrdencompraController extends Controller
{
    //
    public function indexorden(Request $request,$id){
       /*
        $buscar = $request->buscar;
        if ($buscar == '') {
            $recupera = Ordencompra::select('*')
                //->join('empresa', 'empresa.id_empresa', '=', 'factura.id_empresa')
                ->join('proveedor', 'proveedor.id_proveedor', '=', 'orden_compra.id_proveedor')
                ->where("orden_compra.id_empresa", "=", $id)
                ->orderByRaw('orden_compra.id_ordencompra DESC')->get();
        } else {
            $recupera = Ordencompra::select('*')
                //->join('empresa', 'empresa.id_empresa', '=', 'factura.id_empresa')
                //->join('proveedor', 'proveedor.id_proveedor', '=', 'orden_compra.id_proveedor')
                /*->where(function ($q) use ($buscar) {
                    $q->where('factura.ambiente', 'like', '%' . $buscar . '%')
                        ->orWhere('factura.tipo_emision', 'like', '%' . $buscar . '%');
                })

                ->where("orden_compra.id_empresa", "=", $id)
                ->orderByRaw('orden_compra.id_ordencompra DESC')->get();
        }*/
        $buscar = $request->buscar;
        if ($buscar == '') {
            $recupera = FacturaCompra::select('*')
                //->join('empresa', 'empresa.id_empresa', '=', 'factura.id_empresa')
                ->join('proveedor', 'proveedor.id_proveedor', '=', 'factura_compra.id_proveedor')
                //->where("factura_compra.id_empresa", "=", $id)
                ->where('factura_compra.estado_orden',1)
                ->where('factura_compra.id_empresa',$id)
                ->orderByRaw('factura_compra.id_factcompra DESC')->get();
        } else {
            $recupera = FacturaCompra::select('*')
                //->join('empresa', 'empresa.id_empresa', '=', 'factura.id_empresa')
                ->join('proveedor', 'proveedor.id_proveedor', '=', 'factura_compra.id_proveedor')
                /*->where(function ($q) use ($buscar) {
                    $q->where('factura.ambiente', 'like', '%' . $buscar . '%')
                        ->orWhere('factura.tipo_emision', 'like', '%' . $buscar . '%');
                })*/
                //->where("factura_compra.id_empresa", "=", $id)
                ->where('factura_compra.estado_orden',1)
                ->where('factura_compra.id_empresa',$id)
                ->where('proveedor.nombre_proveedor','like','%'.$buscar.'%')
                ->orderByRaw('factura_compra.id_factcompra DESC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {/*
        $ord=new Ordencompra();
        $ord->f_emision=$request->f_emision;
        $ord->f_validez=$request->f_validez;
        $ord->forma_pago=$request->forma_pago;
        $ord->subtotal_sinimp=$request->subtotal_sinimp;
        $ord->subtotal_iva12=$request->subtotal_iva12;
        $ord->valor_12=$request->valor_12;
        $ord->subtotal_iva0=$request->subtotal_iva0;
        $ord->sin_imp=$request->sin_imp;
        $ord->total_descuento=$request->total_descuento;
        $ord->total_orden=$request->total_orden;
        $ord->observacion=$request->observacion;
        $ord->id_proveedor=$request->id_proveedor;
        $ord->id_empresa=$request->id_empresa;
        $ord->save();
        $id= $ord->id_ordencompra;
        
        return $id;*/
        $sel = DB::select("SELECT max(orden_compra) as orden_compra FROM factura_compra where id_empresa={$request->id_empresa} and estado_orden=1");
        if($sel){
            $dato = intval($sel[0]->orden_compra);
        }else{
            $dato=0;    
        }
        
        $principal ="";
        if($dato>=1){
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
        }else{
            $principal = "001";
        }
        $ord=new FacturaCompra();
        $ord->orden_compra=$principal;
        $ord->gasto_import=0;
        $ord->fech_emision=$request->f_emision;
        $ord->fech_validez=$request->f_validez;
        $ord->subtotal_sin_impuesto=$request->subtotal_sinimp;
        $ord->subtotal_12=$request->subtotal_iva12;
        $ord->iva_12=$request->valor_12;
        $ord->subtotal_0=$request->subtotal_iva0;
        $ord->subtotal_no_obj_iva=$request->sin_imp;
        $ord->descuento=$request->total_descuento;
        $ord->total_factura=$request->total_orden;
        $ord->observacion=$request->observacion;
        $ord->modo_orden=1;
        $ord->estado_orden=1;
        $ord->id_proveedor=$request->id_proveedor;
        $ord->id_user=$request->id_user;
        $ord->id_empresa=$request->id_empresa;
        $ord->id_establecimiento=$request->id_establecimiento;
        $ord->id_punto_emision=$request->id_punto_emision;
        $ord->id_forma_pagos=$request->forma_pago;
        $ord->save();
        $id= $ord->id_factcompra;
        return $id;
    }
    public function guardarProducto(Request $request){
        $id=$request->id_orden;
        for ($a = 0; $a < count($request->productos); $a++) {
            $dfactc = new Detalle_factura_compra();
            $dfactc->nombre = $request->productos[$a]["nombre"];
            $dfactc->cantidad = $request->productos[$a]["cantidad"];
            $dfactc->precio = $request->productos[$a]["precio"];
            $dfactc->descuento = $request->productos[$a]["descuento"];
            $dfactc->p_descuento = $request->productos[$a]["p_descuento"];
            if($request->productos[$a]["p_descuento"]==1){
                $dfactc->total = ($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"])-$request->productos[$a]["descuento"];
            }else{
                $dfactc->total = ($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"])-(($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"])*$request->productos[$a]["descuento"])/100;
            }
            $dfactc->id_iva=$request->productos[$a]["iva"];
            $dfactc->id_ice=$request->productos[$a]["ice"];
            
            $dfactc->id_producto = $request->productos[$a]["id_producto"];
            $dfactc->id_factura = $id;
            $dfactc->id_proyecto = $request->productos[$a]["proyecto"];
            $dfactc->save();
        }
    }
     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generarReporte(Request $request){
        $queries = [];
        $fields = [];
        $inners = [];
        $initial = null;
        $final = null;
        $fecha_inicio=DB::select("SELECT min(fech_emision) as fecha_inicio from factura_compra where factura_compra.id_empresa=".$request->company);
        $info_date = json_decode($request->dates, true);
        if($request->dates){
            
            
            if ($request->currentDate !== "true") {
                $initial = $info_date["range"]["initial"];
                $final = $info_date["range"]["final"];
                if ($info_date["option"] == 1) {
                    array_push($queries, "date(fac.fech_emision) between date('{$info_date["range"]["initial"]}') and date('{$info_date["range"]["final"]}')\n");
                }
                if ($info_date["option"] == 2) {
                    array_push($queries, "date(fac.fech_emision) between date('{$info_date["range"]["initial"]}') and date('{$info_date["range"]["final"]}')\n");
                }
                if ($info_date["option"] == 3) {
                    array_push($queries, "date(fac.fech_emision) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')\n");
                }
            } else {
                $initial = $info_date["value"];
                $final = $info_date["value"];
                if ($info_date["option"] == 1) {
                    array_push($queries, "date(fac.fech_emision) = date('{$info_date["value"]}')\n");
                }
                if ($info_date["option"] == 2) {
                    array_push($queries, "date(fac.fech_emision) = date('{$info_date["value"]}')\n");
                }
                if ($info_date["option"] == 3) {
                    //array_push($queries, "date(fac.fech_emision) = date('{$info_date["value"]}')\n");
                    array_push($queries, "date(fac.fech_emision) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$info_date["value"]}')\n");
                }
            }
        }
        if ($request->project) {
            $info_project = json_decode($request->project, true);
            if ($info_project["id"] != 0) {
                array_push($queries, "f.id_proyecto = {$info_project["id"]}\n");
            }

        }
        if ($request->provider) {
            $info_provider = json_decode($request->provider, true);
            if ($info_provider["id"] != 0) {
                array_push($queries, "fac.id_proveedor = {$info_provider["id"]}\n");
            }

        }
        if ($request->user) {
            $info_user = json_decode($request->user, true);
            if ($info_user["id"] != 0) {
                array_push($queries, "fac.ucrea = {$info_user["id"]}\n");
            }

        }
        if ($request->wayToPay) {
            $info_payment = json_decode($request->wayToPay, true);
            if ($info_payment["id"] != 0) {
                array_push($queries, "fac.id_forma_pagos = {$info_payment["id"]}\n");
            }

        }
        
        
                $info_invoice = json_decode($request->invoice);
                if ($request->typeSearchSalesTotalCount == 1) {
                    $typeSearch = ">=";
                }
                if ($request->typeSearchSalesTotalCount == 0) {
                    $typeSearch = "=";
                }
                if ($request->typeSearchSalesTotalCount == -1) {
                    $typeSearch = "<=";
                }
                if (is_numeric($request->totalCountSales) && $request->typeSearchSalesTotalCount != 2) {
                    $request->totalCount = intval($request->totalCountSales);
                    array_push($queries, "fac.total_factura {$typeSearch} {$request->totalCount}\n");
                }
                $queries = implode(" and ", $queries);
                $inners = implode("", $inners);
                $fields = implode("", $fields);
        $query="select fac.*,form.descripcion,pro.nombre_proveedor,pro.identif_proveedor,emp.logo,emp.nombre_empresa from factura_compra as fac,empresa as emp,forma_pagos as form,proveedor as pro where {$queries} and fac.id_proveedor=pro.id_proveedor and fac.id_forma_pagos=form.id_forma_pagos and fac.id_empresa=emp.id_empresa and fac.modo_orden=1";
        //dd($query);
        $reporte=DB::select($query);
        $Reportes = new generarReportes();
        if(!$reporte){
            return response('no-data-report', 200)->header('Content-Type', 'application/json');
        }else{
            $Reportes = new generarReportes();
            $strPDF = $Reportes->orden_compra($reporte, $fecha_inicio[0]->fecha_inicio, $final);
            return response($strPDF, 200)->header('Content-Type', 'application/pdf');
        }
    }
    public function update(Request $request)
    {/*
        $ord=Ordencompra::find($request->id);
        $ord->f_emision=$request->f_emision;
        $ord->f_validez=$request->f_validez;
        $ord->forma_pago=$request->forma_pago;
        $ord->subtotal_sinimp=$request->subtotal_sinimp;
        $ord->subtotal_iva12=$request->subtotal_iva12;
        $ord->valor_12=$request->valor_12;
        $ord->subtotal_iva0=$request->subtotal_iva0;
        $ord->sin_imp=$request->sin_imp;
        $ord->total_descuento=$request->total_descuento;
        $ord->total_orden=$request->total_orden;
        $ord->observacion=$request->observacion;
        $ord->id_proveedor=$request->id_proveedor;
        $ord->id_empresa=$request->id_empresa;
        $ord->save();
        $id= $ord->id_ordencompra;
        
        return $id;*/

        $ord=FacturaCompra::find($request->id);
        $ord->gasto_import=0;
        $ord->fech_emision=$request->f_emision;
        $ord->fech_validez=$request->f_validez;
        $ord->subtotal_sin_impuesto=$request->subtotal_sinimp;
        $ord->subtotal_12=$request->subtotal_iva12;
        $ord->iva_12=$request->valor_12;
        $ord->subtotal_0=$request->subtotal_iva0;
        $ord->subtotal_no_obj_iva=$request->sin_imp;
        $ord->descuento=$request->total_descuento;
        $ord->total_factura=$request->total_orden;
        $ord->observacion=$request->observacion;
        $ord->modo_orden=1;
        $ord->estado_orden=1;
        $ord->id_proveedor=$request->id_proveedor;
        $ord->id_user=$request->id_user;
        $ord->id_empresa=$request->id_empresa;
        $ord->id_establecimiento=$request->id_establecimiento;
        $ord->id_punto_emision=$request->id_punto_emision;
        $ord->id_forma_pagos=$request->forma_pago;
        $ord->save();
        $id= $ord->id_factcompra;
        return $id;
    }
    public function actProducto(Request $request){
        $id=$request->id_orden;
        for ($a = 0; $a < count($request->productos); $a++) {
            if(!$request->productos[$a]["id_detalle_factura_compra"]){
                $dfactc = new Detalle_factura_compra();
                $dfactc->nombre = $request->productos[$a]["nombre"];
                $dfactc->cantidad = $request->productos[$a]["cantidad"];
                $dfactc->precio = $request->productos[$a]["precio"];
                $dfactc->descuento = $request->productos[$a]["descuento"];
                $dfactc->p_descuento = $request->productos[$a]["p_descuento"];
                if($request->productos[$a]["p_descuento"]==1){
                    $dfactc->total = ($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"])-$request->productos[$a]["descuento"];
                }else{
                    $dfactc->total = ($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"])-(($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"])*$request->productos[$a]["descuento"])/100;
                }
                $dfactc->id_iva=$request->productos[$a]["iva"];
                $dfactc->id_producto = $request->productos[$a]["id_producto"];
                $dfactc->id_factura = $id;
                $dfactc->id_proyecto = $request->productos[$a]["proyecto"];
                
                $dfactc->save();
            }
            $dfactc =Detalle_factura_compra::find($request->productos[$a]["id_detalle_factura_compra"]);
            $dfactc->nombre = $request->productos[$a]["nombre"];
            $dfactc->cantidad = $request->productos[$a]["cantidad"];
            $dfactc->precio = $request->productos[$a]["precio"];
            $dfactc->descuento = $request->productos[$a]["descuento"];
            $dfactc->p_descuento = $request->productos[$a]["p_descuento"];
            if($request->productos[$a]["p_descuento"]==1){
                $dfactc->total = ($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"])-$request->productos[$a]["descuento"];
            }else{
                $dfactc->total = ($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"])-(($request->productos[$a]["precio"] * $request->productos[$a]["cantidad"])*$request->productos[$a]["descuento"])/100;
            }
            $dfactc->id_iva=$request->productos[$a]["iva"];
            $dfactc->id_producto = $request->productos[$a]["id_producto"];
            $dfactc->id_factura = $id;
            $dfactc->id_proyecto = $request->productos[$a]["proyecto"];
            
            $dfactc->save();
        }

        return $dfactc;
            
    }
    public function abrir($id)
    {
        $recupera = FacturaCompra::select("*")
            ->addSelect(['nombre_prov' => Proveedor::select('nombre_proveedor')
            ->whereColumn('id_proveedor', 'factura_compra.id_proveedor')
        ])
        ->where('factura_compra.id_factcompra', '=', $id)->limit("1")->get();

        return $recupera;
    }
    public function traerProveedores(Request $request)
    {
        $id = $request->id;
        $recupera = Proveedor::select("proveedor.*", "factura_compra.id_factcompra")
            ->join("factura_compra", "factura_compra.id_proveedor", "=", "proveedor.id_proveedor")
            ->where("factura_compra.id_factcompra", "=", $id)->get();
        return $recupera;
    }
    public function traergrupoProvd(Request $request)
    {
        //$id = $request->id;
        $recupera = GrupoProveedor::all();
        return $recupera;
    }
    public function traerProductos(Request $request){
        $id=$request->id;
        $recuperap = DB::select('SELECT *,id_proyecto as proyecto,id_iva as iva FROM detalle_factura_compra INNER JOIN producto ON detalle_factura_compra.id_producto=producto.id_producto WHERE id_factura='.$id);
        return $recuperap;
    }
    public function traerFormaPago(Request $request){
        $recuperap = DB::select('SELECT * FROM forma_pagos');
        return $recuperap;
    }
    public function generarPDF(Request $request){
        $orden_compra=DB::select("SELECT e.logo,e.nombre_empresa,e.ruc_empresa,e.direccion_empresa,
        c.identif_proveedor as identificacion,f.fech_emision as fecha_emision,c.nombre_proveedor as cliente,p.cod_principal,p.cod_alterno,
        u.nombre as ciudad, c.contacto,c.direccion_prov as direccion,c.email,c.telefono_prov as telefono,p.imagen,p.nombre,p.descripcion,p.caracteristicas,p.normativa,p.uso,
        m.nombre as marca,tm.nombre as tipo_medida,um.nombre as unidad_medida,df.cantidad,df.precio,df.total as total_pro,null as tiempo_entrega,null as cpc,
        f.subtotal_sin_impuesto as subtotal, f.descuento,f.orden_compra as codigo, f.iva_12 as iva, f.total_factura as total, CONCAT(v.nombres, ' ', v.apellidos) as vendedor,
        v.email as mailvendedor,v.telefono as telefono_vendedor
        from factura_compra f 
				LEFT JOIN detalle_factura_compra df ON f.id_factcompra=df.id_factura 
				LEFT JOIN proveedor c ON c.id_proveedor=f.id_proveedor 
				LEFT JOIN empresa e ON e.id_empresa=f.id_empresa 
				LEFT JOIN ciudad u ON u.id_ciudad=e.id_ciudad 
				LEFT JOIN producto p ON df.id_producto=p.id_producto 
				LEFT JOIN marca m ON p.id_marca=m.id_marca 
				LEFT JOIN tipo_medida tm ON tm.id_tipo_medida=p.id_tipo_medida 
				LEFT JOIN unidad_medida um ON um.id_unidad_medida=p.id_unidad_medida 
				LEFT JOIN user v ON v.id = f.id_user
        where f.id_factcompra=".$request->id_orden);
         $factura_info = DB::select("SELECT
                                        e.email_empresa ,
                                        e.id_empresa,
                                        e.telefono,
                                        null as lugar_de_entrega,
                                        null as condiciones_de_pago,
                                        f.observacion,
                                        f.fech_validez as fecha_expiracion,
                                        fp.descripcion as forma_pago,
                                        est.urlweb
                                    FROM factura_compra f
                                    LEFT JOIN empresa e
                                    ON e.id_empresa = f.id_empresa
                                    LEFT JOIN establecimiento est
                                    ON est.id_establecimiento = f.id_establecimiento
                                    LEFT JOIN forma_pagos fp
                                    ON fp.id_forma_pagos = f.id_forma_pagos
                                    WHERE f.id_factcompra = " . $request->id_orden);
        if (!$orden_compra) {
            return response('no-data-report', 200)->header('Content-Type', 'application/json');
        } else {
            if($request->destinatario==null && $request->email==null){
                $reportePdf = new generarReportes();
                $strPDF = $reportePdf->orden_compraPDF($request->id_orden,$orden_compra, $orden_compra[0]->codigo, $factura_info[0]);
                return response($strPDF, 200)->header('Content-Type', 'application/pdf');
            }else{
                $ruta = constant("DATA_EMPRESA") . $request->id_empresa . '/compras/orden_compra';
                if (!file_exists($ruta)) {
                    mkdir($ruta, 0755, true);
                }
                $reportePdf = new generarReportes();
                $strPDF = $reportePdf->orden_compraPDF($request->id_orden,$orden_compra, $orden_compra[0]->codigo, $factura_info[0],$generar_en_servidor = true, $ruta);
                return response($strPDF, 200)->header('Content-Type', 'application/pdf');
            }
            
        }
    }
    public function correorden(Request $request){
        $empresa=DB::select("SELECT e.* FROM factura_compra f INNER JOIN empresa e ON e.id_empresa = f.id_empresa where f.id_factcompra=".$request->id);
        $email = new sendEmail();
        $email->enviarOrdenCompra($empresa[0], $request->id, $request->destinatario,$request->email,$request->tipo);
    }
    public function eliminar($id){
        Detalle_factura_compra::where('id_factura','=',$id)->delete();
        FacturaCompra::destroy($id);
      /*$id = $request->id;
       $eliminar = Producto::find($request->id);
        $eliminar->delete();
        */
    }
}
