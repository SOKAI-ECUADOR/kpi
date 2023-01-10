<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProcesoProduccion;
use App\Models\ProcesoProducto;
use App\Models\ProcesoIngrediente;

use App\Models\Asientos;
use App\Models\Asientos_contables_detalle;

use App\Models\BodegaEgreso;
use App\Models\BodegaEgresoDetalle;

use App\Models\BodegaIngreso;
use App\Models\BodegaIngresoDetalle;

use Carbon\Carbon;


class ProcesoProduccionController extends Controller
{

    public function index(Request $request, $id, $ide)
    { {
            $buscar = $request->buscar;
            if ($buscar == '') {
                //$impuestos = Impuesto::paginate($cantidadp); 
                $recupera = ProcesoProduccion::select("*")
                    ->where("proceso_produccion.id_empresa", "=", $id)
                    ->where("proceso_produccion.id_establecimiento", "=", $ide)
                    ->orderByRaw('proceso_produccion.id_proceso_produccion DESC')->get();
            } else {
                $recupera = ProcesoProduccion::select("*")
                    ->where(function ($q) use ($buscar) {
                        $q->where('num_orden', 'like', '%' . $buscar . '%')
                            ->orWhere('detalle', 'like', '%' . $buscar . '%');
                    })
                    ->where("proceso_produccion.id_empresa", "=", $id)
                    ->where("proceso_produccion.id_establecimiento", "=", $ide)
                    ->orderByRaw('proceso_produccion.id_proceso_produccion DESC')->get();
            }
            return [
                'recupera' => $recupera
            ];
        }
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }

    public function verAsiento(Request $request,$id){
        $prod=DB::select("SELECT * from proceso_produccion where id_proceso_produccion=$id");
        $codigo=DB::select("SELECT max(numero) as codigo FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.codigo like 'PD-%' and (asientos.estado='Activo' or asientos.estado is null) and proyecto.id_empresa=".$request->id_empresa);
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
            $codigo_ant=DB::select("SELECT asientos.numero FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.id_asientos_comprobante=21 and (asientos.estado='Activo' or asientos.estado is null) and asientos.codigo_rol={$id} and proyecto.id_empresa=".$request->id_empresa." ORDER BY asientos.codigo desc limit 1");
            if($codigo_ant){
                $cod_asiento_ant=$codigo_ant[0]->numero;
            }
        }
        $proyecto=DB::select("SELECT * from proyecto where id_proyecto={$prod[0]->id_proyecto}");
        $empresa=DB::select("SELECT * from empresa where id_empresa={$prod[0]->id_empresa}");
        $query="SELECT proceso_producto.costo_total,if(producto.iva=2,'doce','cero') as iva,if(producto.sector=1,'producto','servicio')  as sector,producto.id_linea_producto,
        {$prod[0]->id_proyecto} as id_proyecto,
        '{$proyecto[0]->descripcion}' as descripcion,
        if(producto.sector=1,linea_producto.id_plan_cuentas_ventas_iva,null) as id_plan_cuentas_iva_12,
        if(producto.sector=1,linea_producto.id_plan_cuentas_ventas_iva_0,null) as id_plan_cuentas_iva_0,
        (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(linea_producto.id_plan_cuentas_ventas_iva is null,0,linea_producto.id_plan_cuentas_ventas_iva) and id_empresa={$prod[0]->id_empresa}) as nombre_cuenta_12,
        (select id_grupo from plan_cuentas where id_plan_cuentas=if(linea_producto.id_plan_cuentas_ventas_iva is null,0,linea_producto.id_plan_cuentas_ventas_iva) and id_empresa={$prod[0]->id_empresa}) as grupo_cuenta_12,
        (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(linea_producto.id_plan_cuentas_ventas_iva_0 is null,0,linea_producto.id_plan_cuentas_ventas_iva_0) and id_empresa={$prod[0]->id_empresa}) as nombre_cuenta_0,
        (select id_grupo from plan_cuentas where id_plan_cuentas=if(linea_producto.id_plan_cuentas_ventas_iva_0 is null,0,linea_producto.id_plan_cuentas_ventas_iva_0) and id_empresa={$prod[0]->id_empresa}) as grupo_cuenta_0,
        plan_cuentas.id_plan_cuentas as id_plan_cuentas_servicio,
        concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,
        plan_cuentas.id_grupo as grupo_cuenta_servicio,
        null as debe,proceso_producto.costo_total as haber
        from proceso_producto
        INNER JOIN producto
        ON producto.id_producto=proceso_producto.id_producto
        INNER JOIN bodega
        ON bodega.id_bodega=proceso_producto.id_bodega
        LEFT JOIN plan_cuentas
        ON producto.id_plan_cuentas=plan_cuentas.id_plan_cuentas
        LEFT JOIN linea_producto
        on linea_producto.id_linea_producto=producto.id_linea_producto
        where proceso_producto.id_proceso_produccion={$id}";
        //dd($query);
        $productos=DB::select($query);
        // trae los productos y servicios de produccion
        $query2="SELECT 
                (select CONCAT(codcta,'-',nomcta) from bodega LEFT JOIN plan_cuentas ON plan_cuentas.id_plan_cuentas=bodega.id_plan_cuentas where bodega.
                id_bodega=proceso_ingrediente.id_bodega limit 1) as nombre_cuenta,
                (select plan_cuentas.id_plan_cuentas from bodega LEFT JOIN plan_cuentas ON plan_cuentas.id_plan_cuentas=bodega.id_plan_cuentas where id_bodega=
                proceso_ingrediente.id_bodega limit 1) as id_plan_cuentas,
                if(proceso_ingrediente.id_proyecto is null,(select id_proyecto from proyecto where id_proyecto={$prod[0]->id_proyecto} limit 1),proceso_ingrediente.id_proyecto) as id_proyecto,
                if(proceso_ingrediente.id_proyecto is null,(select descripcion from proyecto where id_proyecto={$prod[0]->id_proyecto} limit 1),(select descripcion from proyecto where id_proyecto=proceso_ingrediente.id_proyecto limit 1)) as descripcion,
                null as debe,
                sum(cantidad_liquidacion*costo_unitario_liquidacion) as haber 
                FROM proceso_ingrediente 
                where id_proceso_produccion={$id} and id_bodega is not null
                GROUP BY proceso_ingrediente.id_bodega
                UNION
				SELECT 
				(select CONCAT(codcta,'-',nomcta) from producto LEFT JOIN plan_cuentas ON plan_cuentas.id_plan_cuentas=producto.id_plan_cuentas where producto.id_producto=proceso_ingrediente.id_producto limit 1) as nombre_cuenta,
				(select plan_cuentas.id_plan_cuentas from producto LEFT JOIN plan_cuentas ON plan_cuentas.id_plan_cuentas=producto.id_plan_cuentas where producto.id_producto=proceso_ingrediente.id_producto limit 1) as id_plan_cuentas,	
				if(proceso_ingrediente.id_proyecto is null,(select id_proyecto from proyecto where id_proyecto={$prod[0]->id_proyecto} limit 1),proceso_ingrediente.id_proyecto) as id_proyecto,
                if(proceso_ingrediente.id_proyecto is null,(select descripcion from proyecto where id_proyecto={$prod[0]->id_proyecto} limit 1),(select descripcion from proyecto where id_proyecto=proceso_ingrediente.id_proyecto limit 1)) as descripcion,
                null as debe,
                sum(cantidad_liquidacion*costo_unitario_liquidacion) as haber 
				FROM proceso_ingrediente 
				LEFT JOIN producto
				ON producto.id_producto=proceso_ingrediente.id_producto
				where id_proceso_produccion={$id} and id_bodega is null
				GROUP BY proceso_ingrediente.id_producto";
        $ingredientes=DB::select($query2);
        $query3="SELECT plan_cuentas.id_plan_cuentas,CONCAT(codcta,'-',nomcta) as nombre_cuenta,null as debe,null as haber,
        {$prod[0]->id_proyecto} as id_proyecto,
        '{$proyecto[0]->descripcion}' as descripcion 
        from proceso_produccion
        INNER JOIN cuenta_produccion
        on cuenta_produccion.id_empresa=proceso_produccion.id_empresa
        INNER JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=cuenta_produccion.id_plan_cuentas
        where proceso_produccion.id_proceso_produccion={$id}";
        $bodega_inf=[];
        //$prod[0]->id_proyecto
        $bodega=DB::select($query3);
        return [
            'produccion'=>$prod[0],
            'empresa'=>$empresa[0],
            'codigo'=>$cod_asiento,
            'codigo_anterior'=>$cod_asiento_ant,
            'productos'=>[],
            'ingredientes'=>$ingredientes,
            'bodega'=>$bodega
        ];
    }
    public function agregarAsiento(Request $request)
    {
        ProcesoProduccion::where('id_proceso_produccion', $request->cod_rol)->update(['contabilidad' => '1']);
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
        $asientos->id_asientos_comprobante = 21;

        $asientos->save();
        return $asientos->id_asientos;
    }
    public function agregarAsientoDetalle(Request $request)
    {

        foreach ($request->ingredientes as $haber) {
            $asiento = new Asientos_contables_detalle();
            if (count($haber) > 0) {
                $asiento->proyecto = $haber["descripcion"];
                $asiento->haber = $haber["haber"];
                $asiento->ucrea = $request->ucrea;
                $asiento->id_plan_cuentas = $haber["id_plan_cuentas"];
                $asiento->id_asientos = $request->id_asientos;
                $asiento->id_proyecto = $haber["id_proyecto"];
            }
            $asiento->save();
        }

        foreach ($request->bodega as $debe) {
            $asiento = new Asientos_contables_detalle();
            if (count($debe) > 0) {
                $asiento->proyecto = $debe["descripcion"];
                $asiento->debe = $debe["debe"];
                $asiento->ucrea = $request->ucrea;
                $asiento->id_plan_cuentas = $debe["id_plan_cuentas"];
                $asiento->id_asientos = $request->id_asientos;
                $asiento->id_proyecto = $debe["id_proyecto"];
                $asiento->save();
            }
        }

    }
    public function eliminarproceso_produccion(Request $request)
    {
        ini_set('max_execution_time', 1000);
        $hoy = Carbon::now();
        $id_proceso=$request->datos;
        $bodega_egreso=DB::select("SELECT * from bodega_egreso where id_proceso_produccion={$id_proceso} and observ_egreso like '%Orden:%'");
        $bodega_egreso_proceso=DB::select("SELECT * from bodega_egreso where id_proceso_produccion={$id_proceso} and observ_egreso like '%Proceso:%'");
        $bodega_ingreso=DB::select("SELECT * from bodega_ingreso where id_proceso_produccion={$id_proceso}");
        if(isset($bodega_egreso[0]->id_bodega_egreso) || isset($bodega_egreso_proceso[0]->id_bodega_egreso) || isset($bodega_ingreso[0]->id_bodega_ingreso)){
            self::CabeceraBodega($id_proceso,[],$hoy);
        }else{
            DB::update("UPDATE proceso_produccion set estado_produccion='Inactivo' where id_proceso_produccion={$id_proceso}");
        }
        //if(count($bodega_egreso)>0){
            //////////////////////////////////////////// Cancelacion Bodega Egreso Orden Produccion
            // $detalle_egreso=DB::select("SELECT * from bodega_egreso_detalle where id_bodega_egreso={$bodega_egreso[0]->id_bodega_egreso}");
            // $numingre = DB::select("SELECT num_ingreso FROM bodega_ingreso  WHERE id_empresa = {$bodega_egreso[0]->id_empresa} ORDER BY  num_ingreso DESC LIMIT 1;");
            // $numeroingreso = "";
            // //si existe la bodega_ingreso cuenta el ultimo num_ingreso del registro caso contrario comienza el contador desde 1
            // if (count($numingre) == 1) {
            //     $dato = $numingre[0]->num_ingreso;
            //     $tot = $dato + 1;
            //     $numeroingreso = $tot;
            // } else {
            //     $numeroingreso = 1;
            // }
            // $savebode = 0;
            // $id_bodega_ingreso = "";
            // if (count($detalle_egreso) > 0) {
            //     for ($i = 0; $i < count($detalle_egreso); $i++) {
            //         DB::update("UPDATE producto_bodega SET cantidad = cantidad + {$detalle_egreso[$i]->cantidad}, costo_total = if(cantidad is null or cantidad=0,0,costo_total+{$detalle_egreso[$i]->costo_total}),costo_unitario=if(costo_total=0 or costo_total is null,0,costo_total/cantidad) WHERE id_producto = {$detalle_egreso[$i]->id_producto} and id_bodega={$bodega_egreso[0]->id_bodega}");
            //         if ($savebode == 0) {
            //             $egresos = new BodegaIngreso();
            //             $egresos->num_ingreso = $numeroingreso;
            //             $egresos->fecha_ingreso = $hoy;
            //             $egresos->tipo_ingreso = "Proceso de Producción";
            //             $egresos->observ_ingreso = 'Cancelacion Orden Produccion: ' . $produccion[0]->num_orden;
            //             $egresos->id_proyecto = $proyecto[0]->id_proyecto;
            //             //if (isset($egreso[$i]->id_bodega)) {
            //             $egresos->id_bodega = $bodega_egreso[0]->id_bodega;
            //             //}
            //             $egresos->id_empresa = $bodega_egreso[0]->id_empresa;
            //             //$egreso->id_proyecto = $request->productos[$a]["proyecto"];
            //             $egresos->id_proceso_produccion = $id_proceso;
            //             $egresos->save();
            //             $id_bodega_ingreso = $egresos->id_bodega_ingreso;
            //             $savebode++;
            //         }
            //         $bed = new BodegaIngresoDetalle();
            //         $bed->cantidad = $detalle_egreso[$i]->cantidad;
            //         $bed->costo_unitario = $detalle_egreso[$i]->costo_unitario;
            //         $bed->costo_total = $detalle_egreso[$i]->costo_total;
            //         $bed->id_bodega_ingreso = $id_bodega_ingreso;
            //         $bed->id_producto = $detalle_egreso[$i]->id_producto;
            //         $bed->id_proyecto = $detalle_egreso[$i]->id_proyecto;
            //         //$bed->id_detalle = $id_detalle;
            //         $bed->save();
            //     }
            // }
            //////////////////////////////////////////////
            
        //}else{
            ////////////////////////////////// Por si no existe Bodega Egreso Orden Produccion
            
            /////////////////////////////
        //}
        
        // if(count($bodega_egreso_proceso)>0){
        /////////////////////////////////////// Si Existe Proceso Produccion Bodega Egreso para su respectiva cancela
        //     $detalle_egreso_proceso=DB::select("SELECT * from bodega_egreso_detalle where id_bodega_egreso={$bodega_egreso_proceso[0]->id_bodega_egreso}");
        //     $numingre = DB::select("SELECT num_ingreso FROM bodega_ingreso  WHERE id_empresa = {$bodega_egreso_proceso[0]->id_empresa} ORDER BY  num_ingreso DESC LIMIT 1;");
        //     $numeroingreso = "";
        //     //si existe la bodega_ingreso cuenta el ultimo num_ingreso del registro caso contrario comienza el contador desde 1
        //     if (count($numingre) == 1) {
        //         $dato = $numingre[0]->num_ingreso;
        //         $tot = $dato + 1;
        //         $numeroingreso = $tot;
        //     } else {
        //         $numeroingreso = 1;
        //     }
        //     $savebode = 0;
        //     $id_bodega_ingreso = "";
        //     if (count($detalle_egreso_proceso) > 0) {
        //         for ($i = 0; $i < count($detalle_egreso_proceso); $i++) {
        //             DB::update("UPDATE producto_bodega SET cantidad = cantidad + {$detalle_egreso_proceso[$i]->cantidad}, costo_total = if(cantidad is null or cantidad=0,0,costo_total+{$detalle_egreso_proceso[$i]->costo_total}),costo_unitario=if(costo_total=0 or costo_total is null,0,costo_total/cantidad) WHERE id_producto = {$detalle_egreso_proceso[$i]->id_producto} and id_bodega={$bodega_egreso_proceso[0]->id_bodega}");
        //             if ($savebode == 0) {
        //                 $egresos = new BodegaIngreso();
        //                 $egresos->num_ingreso = $numeroingreso;
        //                 $egresos->fecha_ingreso = $hoy;
        //                 $egresos->tipo_ingreso = "Proceso de Producción";
        //                 $egresos->observ_ingreso = 'Cancelacion Proceso Produccion: ' . $produccion[0]->num_orden;
        //                 $egresos->id_proyecto = $proyecto[0]->id_proyecto;
        //                 //if (isset($egreso[$i]->id_bodega)) {
        //                 $egresos->id_bodega = $bodega_egreso_proceso[0]->id_bodega;
        //                 //}
        //                 $egresos->id_empresa = $bodega_egreso_proceso[0]->id_empresa;
        //                 //$egreso->id_proyecto = $request->productos[$a]["proyecto"];
        //                 $egresos->id_proceso_produccion = $id_proceso;
        //                 $egresos->save();
        //                 $id_bodega_ingreso = $egresos->id_bodega_ingreso;
        //                 $savebode++;
        //             }
        //             $bed = new BodegaIngresoDetalle();
        //             $bed->cantidad = $detalle_egreso_proceso[$i]->cantidad;
        //             $bed->costo_unitario = $detalle_egreso_proceso[$i]->costo_unitario;
        //             $bed->costo_total = $detalle_egreso_proceso[$i]->costo_total;
        //             $bed->id_bodega_ingreso = $id_bodega_ingreso;
        //             $bed->id_producto = $detalle_egreso_proceso[$i]->id_producto;
        //             $bed->id_proyecto = $detalle_egreso_proceso[$i]->id_proyecto;
        //             //$bed->id_detalle = $id_detalle;
        //             $bed->save();
        //         }
        //     }
        ///////////////////////////////////////////////////
        // }
        // if(count($bodega_ingreso)>0){
            ///////////////////////////////////////////// Si existe Liquidacion Produccion para su respectiva cancelacion
        //     $detalle_ingreso=DB::select("SELECT * from bodega_ingreso_detalle where id_bodega_ingreso={$bodega_ingreso[0]->id_bodega_ingreso}");
        //     $numegre = DB::select("SELECT num_egreso FROM bodega_egreso  WHERE id_empresa = {$bodega_ingreso[0]->id_empresa} ORDER BY  num_egreso DESC LIMIT 1;");
        //     $numeroegreso = "";
        //     //si existe la bodega_ingreso cuenta el ultimo num_ingreso del registro caso contrario comienza el contador desde 1
        //     if (count($numegre) == 1) {
        //         $dato = $numegre[0]->num_egreso;
        //         $tot = $dato + 1;
        //         $numeroegreso = $tot;
        //     } else {
        //         $numeroegreso = 1;
        //     }
        //     $savebode = 0;
        //     $id_bodega_egreso = "";
        //     if (count($detalle_ingreso) > 0) {
        //         for ($i = 0; $i < count($detalle_ingreso); $i++) {
        //             DB::update("UPDATE producto_bodega SET cantidad = cantidad - {$detalle_ingreso[$i]->cantidad}, costo_total = if(cantidad is null or cantidad=0,0,costo_total-{$detalle_ingreso[$i]->costo_total}),costo_unitario=if(costo_total=0 or costo_total is null,0,costo_total/cantidad) WHERE id_producto = {$detalle_ingreso[$i]->id_producto} and id_bodega={$bodega_ingreso[0]->id_bodega}");
        //             if ($savebode == 0) {
        //                 $egresos = new BodegaEgreso();
        //                 $egresos->num_egreso = $numeroegreso;
        //                 $egresos->fecha_egreso = $hoy;
        //                 $egresos->tipo_egreso = "Proceso de Producción";
        //                 $egresos->observ_egreso = 'Cancelacion Liquidacion Produccion: ' . $produccion[0]->num_orden;
        //                 $egresos->id_proyecto = $proyecto[0]->id_proyecto;
        //                 //if (isset($egreso[$i]->id_bodega)) {
        //                 $egresos->id_bodega = $bodega_ingreso[0]->id_bodega;
        //                 //}
        //                 $egresos->id_empresa = $bodega_ingreso[0]->id_empresa;
        //                 //$egreso->id_proyecto = $request->productos[$a]["proyecto"];
        //                 $egresos->id_proceso_produccion = $id_proceso;
        //                 $egresos->save();
        //                 $id_bodega_egreso = $egresos->id_bodega_egreso;
        //                 $savebode++;
        //             }
        //             $bed = new BodegaEgresoDetalle();
        //             $bed->cantidad = $detalle_ingreso[$i]->cantidad;
        //             $bed->costo_unitario = $detalle_ingreso[$i]->costo_unitario;
        //             $bed->costo_total = $detalle_ingreso[$i]->costo_total;
        //             $bed->id_bodega_egreso = $id_bodega_egreso;
        //             $bed->id_producto = $detalle_ingreso[$i]->id_producto;
        //             $bed->id_proyecto = $detalle_ingreso[$i]->id_proyecto;
        //             //$bed->id_detalle = $id_detalle;
        //             $bed->save();
        //         }
        //     }
        // }
        //////////////////////////////////////////
        //DB::update("UPDATE proceso_produccion set estado_produccion='Inactivo' where id_proceso_produccion={$id_proceso}");
    }
    public function CabeceraBodega($id,$lotes,$hoy){
        ini_set('max_execution_time', 1000);
        $produccion=DB::select("SELECT * from proceso_produccion where id_proceso_produccion={$id}");
        $proyecto=DB::select("SELECT * from proyecto where id_empresa={$produccion[0]->id_empresa} limit 1");
        $bodega_egreso=DB::select("SELECT * from bodega_egreso where id_proceso_produccion={$id} and observ_egreso like '%Orden:%'");
        $bodega_egreso_proceso=DB::select("SELECT * from bodega_egreso where id_proceso_produccion={$id} and observ_egreso like '%Proceso:%'");
        $bodega_ingreso=DB::select("SELECT * from bodega_ingreso where id_proceso_produccion={$id}");
        if(isset($bodega_egreso[0]->id_bodega_egreso)){
            for($i = 0; $i < count($bodega_egreso); $i++){
                $numingre = DB::select("SELECT num_ingreso FROM bodega_ingreso  WHERE id_empresa = {$bodega_egreso[$i]->id_empresa} ORDER BY  num_ingreso DESC LIMIT 1;");
                $numeroingreso = "";
                //si existe la bodega_ingreso cuenta el ultimo num_ingreso del registro caso contrario comienza el contador desde 1
                if (count($numingre) == 1) {
                    $dato = $numingre[0]->num_ingreso;
                    $tot = $dato + 1;
                    $numeroingreso = $tot;
                } else {
                    $numeroingreso = 1;
                }
                $egresos = new BodegaIngreso();
                $egresos->num_ingreso = $numeroingreso;
                $egresos->fecha_ingreso = $hoy;
                $egresos->tipo_ingreso = "Proceso de Producción";
                $egresos->observ_ingreso = 'Cancelacion Orden Produccion: ' . $produccion[0]->num_orden;
                $egresos->id_proyecto = $proyecto[0]->id_proyecto;
                //if (isset($egreso[$i]->id_bodega)) {
                $egresos->id_bodega = $bodega_egreso[$i]->id_bodega;
                //}
                $egresos->id_empresa = $bodega_egreso[$i]->id_empresa;
                //$egreso->id_proyecto = $request->productos[$a]["proyecto"];
                $egresos->id_proceso_produccion = $id;
                $egresos->save();
            }
            
        }
        if(isset($bodega_egreso_proceso[0]->id_bodega_egreso)){
            for($i = 0; $i < count($bodega_egreso_proceso); $i++){
                $numingre = DB::select("SELECT num_ingreso FROM bodega_ingreso  WHERE id_empresa = {$bodega_egreso_proceso[$i]->id_empresa} ORDER BY  num_ingreso DESC LIMIT 1;");
                $numeroingreso = "";
                //si existe la bodega_ingreso cuenta el ultimo num_ingreso del registro caso contrario comienza el contador desde 1
                if (count($numingre) == 1) {
                    $dato = $numingre[0]->num_ingreso;
                    $tot = $dato + 1;
                    $numeroingreso = $tot;
                } else {
                    $numeroingreso = 1;
                }
                $egresos = new BodegaIngreso();
                $egresos->num_ingreso = $numeroingreso;
                $egresos->fecha_ingreso = $hoy;
                $egresos->tipo_ingreso = "Proceso de Producción";
                $egresos->observ_ingreso = 'Cancelacion Proceso Produccion: ' . $produccion[0]->num_orden;
                $egresos->id_proyecto = $proyecto[0]->id_proyecto;
                //if (isset($egreso[$i]->id_bodega)) {
                $egresos->id_bodega = $bodega_egreso_proceso[$i]->id_bodega;
                //}
                $egresos->id_empresa = $bodega_egreso_proceso[$i]->id_empresa;
                //$egreso->id_proyecto = $request->productos[$a]["proyecto"];
                $egresos->id_proceso_produccion = $id;
                $egresos->save();
            }
        }
        if(isset($bodega_ingreso[0]->id_bodega_ingreso)){
            for ($i = 0; $i < count($bodega_ingreso); $i++) {
                $numegre = DB::select("SELECT num_egreso FROM bodega_egreso  WHERE id_empresa = {$bodega_ingreso[$i]->id_empresa} ORDER BY  num_egreso DESC LIMIT 1;");
                $numeroegreso = "";
                //si existe la bodega_ingreso cuenta el ultimo num_ingreso del registro caso contrario comienza el contador desde 1
                if (count($numegre) == 1) {
                    $dato = $numegre[0]->num_egreso;
                    $tot = $dato + 1;
                    $numeroegreso = $tot;
                } else {
                    $numeroegreso = 1;
                }
                $egresos = new BodegaEgreso();
                $egresos->num_egreso = $numeroegreso;
                $egresos->fecha_egreso = $hoy;
                $egresos->tipo_egreso = "Proceso de Producción";
                $egresos->observ_egreso = 'Cancelacion Liquidacion Produccion: ' . $produccion[0]->num_orden;
                $egresos->id_proyecto = $proyecto[0]->id_proyecto;
                //if (isset($egreso[$i]->id_bodega)) {
                $egresos->id_bodega = $bodega_ingreso[$i]->id_bodega;
                //}
                $egresos->id_empresa = $bodega_ingreso[$i]->id_empresa;
                //$egreso->id_proyecto = $request->productos[$a]["proyecto"];
                $egresos->id_proceso_produccion = $id;
                $egresos->save();
            }  
        }
        if(isset($bodega_egreso[0]->id_bodega_egreso) || isset($bodega_egreso_proceso[0]->id_bodega_egreso) || isset($bodega_ingreso[0]->id_bodega_ingreso)){
            self::ProductoBodega($id, $hoy, "",[]);
        }
    }
    public function ProductoBodega($id, $fecha_ingreso, $nro_factura,$productos){
        ini_set('max_execution_time', 1000);
        $produccion=DB::select("SELECT * from proceso_produccion where id_proceso_produccion={$id}");
        $proyecto=DB::select("SELECT * from proyecto where id_empresa={$produccion[0]->id_empresa} limit 1");
        $bodega_egreso=DB::select("SELECT * from bodega_egreso where id_proceso_produccion={$id} and observ_egreso like '%Orden:%'");
        $bodega_egreso_proceso=DB::select("SELECT * from bodega_egreso where id_proceso_produccion={$id} and observ_egreso like '%Proceso:%'");
        $bodega_ingreso=DB::select("SELECT * from bodega_ingreso where id_proceso_produccion={$id}");
        if(isset($bodega_egreso[0]->id_bodega_egreso)){
            $detalle_egreso = DB::select("SELECT * FROM bodega_egreso_detalle,bodega_egreso where bodega_egreso.id_bodega_egreso=bodega_egreso_detalle.id_bodega_egreso and id_proceso_produccion=$id and observ_egreso like '%Orden:%'");
            for ($i = 0; $i < count($detalle_egreso); $i++) {
                $cantidad = $detalle_egreso[$i]->cantidad;
                $precio = $detalle_egreso[$i]->costo_unitario;
                $total= $cantidad*$precio;
                $id_producto_bodega = $detalle_egreso[$i]->id_bodega;
                if (isset($id_producto_bodega) && $id_producto_bodega) {
                    DB::update("UPDATE producto_bodega SET cantidad = cantidad + {$detalle_egreso[$i]->cantidad}, costo_total = if(cantidad is null or cantidad=0,0,costo_total+{$detalle_egreso[$i]->costo_total}),costo_unitario=if(costo_total=0 or costo_total is null,0,costo_total/cantidad) WHERE id_producto = {$detalle_egreso[$i]->id_producto} and id_bodega={$id_producto_bodega}");
                }
            }
        }
        if(isset($bodega_egreso_proceso[0]->id_bodega_egreso)){
            $detalle_egreso_proceso = DB::select("SELECT * FROM bodega_egreso_detalle,bodega_egreso where bodega_egreso.id_bodega_egreso=bodega_egreso_detalle.id_bodega_egreso and id_proceso_produccion=$id and observ_egreso like '%Proceso:%'");
            for ($i = 0; $i < count($detalle_egreso_proceso); $i++) {
                $cantidad = $detalle_egreso_proceso[$i]->cantidad;
                $precio = $detalle_egreso_proceso[$i]->costo_unitario;
                $total= $cantidad*$precio;
                $id_producto_bodega = $detalle_egreso_proceso[$i]->id_bodega;
                if (isset($id_producto_bodega) && $id_producto_bodega) {
                    DB::update("UPDATE producto_bodega SET cantidad = cantidad + {$cantidad}, costo_total = if(cantidad is null or cantidad=0,0,costo_total+{$detalle_egreso_proceso[$i]->costo_total}),costo_unitario=if(costo_total=0 or costo_total is null,0,costo_total/cantidad) WHERE id_producto = {$detalle_egreso_proceso[$i]->id_producto} and id_bodega={$id_producto_bodega}");
                }
            }
        }
        if(isset($bodega_ingreso[0]->id_bodega_ingreso)){
            $detalle_ingreso = DB::select("SELECT * FROM bodega_ingreso_detalle,bodega_ingreso where bodega_ingreso.id_bodega_ingreso=bodega_ingreso_detalle.id_bodega_ingreso and id_proceso_produccion=$id");
            for ($i = 0; $i < count($detalle_ingreso); $i++) {
                $cantidad = $detalle_ingreso[$i]->cantidad;
                $precio = $detalle_ingreso[$i]->costo_unitario;
                $total= $cantidad*$precio;
                $id_producto_bodega = $detalle_ingreso[$i]->id_bodega;
                if (isset($id_producto_bodega) && $id_producto_bodega) {
                    DB::update("UPDATE producto_bodega SET cantidad = cantidad - {$detalle_ingreso[$i]->cantidad}, costo_total = if(cantidad is null or cantidad=0,0,costo_total-{$detalle_ingreso[$i]->costo_total}),costo_unitario=if(costo_total=0 or costo_total is null,0,costo_total/cantidad) WHERE id_producto = {$detalle_ingreso[$i]->id_producto} and id_bodega={$id_producto_bodega}");
                }
            }
        }
        if(isset($bodega_egreso[0]->id_bodega_egreso) || isset($bodega_egreso_proceso[0]->id_bodega_egreso) || isset($bodega_ingreso[0]->id_bodega_ingreso)){
            self::DetalleBodega($id, $fecha_ingreso, "",[]);
        }
    }
    public function DetalleBodega($id, $fecha, $nro_factura,$productos){
        ini_set('max_execution_time', 1000);
        $detalle_egreso = DB::select("SELECT bodega_egreso_detalle.*,bodega_egreso.id_bodega FROM bodega_egreso_detalle,bodega_egreso where bodega_egreso.id_bodega_egreso=bodega_egreso_detalle.id_bodega_egreso and id_proceso_produccion=$id and observ_egreso like '%Orden:%'");
        $detalle_egreso_proceso = DB::select("SELECT bodega_egreso_detalle.*,bodega_egreso.id_bodega FROM bodega_egreso_detalle,bodega_egreso where bodega_egreso.id_bodega_egreso=bodega_egreso_detalle.id_bodega_egreso and id_proceso_produccion=$id and observ_egreso like '%Proceso:%'");
        $detalle_ingreso = DB::select("SELECT bodega_ingreso_detalle.*,bodega_ingreso.id_bodega FROM bodega_ingreso_detalle,bodega_ingreso where bodega_ingreso.id_bodega_ingreso=bodega_ingreso_detalle.id_bodega_ingreso and id_proceso_produccion=$id");
        if(isset($detalle_egreso[0]->id_bodega_egreso_detalle)){
            for($i=0;$i<count($detalle_egreso);$i++){
                $id_bodega_ingreso=DB::select("SELECT * from bodega_ingreso where id_bodega={$detalle_egreso[$i]->id_bodega} and id_proceso_produccion=$id and observ_ingreso like '%Orden Produccion:%'");
                $bed = new BodegaIngresoDetalle();
                $bed->cantidad = $detalle_egreso[$i]->cantidad;
                $bed->costo_unitario = $detalle_egreso[$i]->costo_unitario;
                $bed->costo_total = $detalle_egreso[$i]->costo_total;
                $bed->id_bodega_ingreso = $id_bodega_ingreso[0]->id_bodega_ingreso;
                $bed->id_producto = $detalle_egreso[$i]->id_producto;
                $bed->id_proyecto = $detalle_egreso[$i]->id_proyecto;
                //$bed->id_detalle = $id_detalle;
                $bed->save();
            }
            
        }
        if(isset($detalle_egreso_proceso[0]->id_bodega_egreso_detalle)){
            for($i=0;$i<count($detalle_egreso_proceso);$i++){
                $id_bodega_ingreso=DB::select("SELECT * from bodega_ingreso where id_bodega={$detalle_egreso_proceso[$i]->id_bodega} and id_proceso_produccion=$id and observ_ingreso like '%Proceso Produccion:%'");
                $bed = new BodegaIngresoDetalle();
                $bed->cantidad = $detalle_egreso_proceso[$i]->cantidad;
                $bed->costo_unitario = $detalle_egreso_proceso[$i]->costo_unitario;
                $bed->costo_total = $detalle_egreso_proceso[$i]->costo_total;
                $bed->id_bodega_ingreso = $id_bodega_ingreso[0]->id_bodega_ingreso;
                $bed->id_producto = $detalle_egreso_proceso[$i]->id_producto;
                $bed->id_proyecto = $detalle_egreso_proceso[$i]->id_proyecto;
                //$bed->id_detalle = $id_detalle;
                $bed->save();
            }
        }
        if(isset($detalle_ingreso[0]->id_bodega_ingreso_detalle)){
            for($i=0;$i<count($detalle_ingreso);$i++){
                $id_bodega_egreso=DB::select("SELECT * from bodega_egreso where id_bodega={$detalle_ingreso[$a]->id_bodega} and id_proceso_produccion=$id and observ_egreso like '%Liquidacion Produccion:%'");
                $bed = new BodegaIngresoDetalle();
                $bed->cantidad = $detalle_ingreso[$i]->cantidad;
                $bed->costo_unitario = $detalle_ingreso[$i]->costo_unitario;
                $bed->costo_total = $detalle_ingreso[$i]->costo_total;
                $bed->id_bodega_ingreso = $id_bodega_egreso[0]->id_bodega_egreso;
                $bed->id_producto = $detalle_ingreso[$i]->id_producto;
                $bed->id_proyecto = $detalle_ingreso[$i]->id_proyecto;
                //$bed->id_detalle = $id_detalle;
                $bed->save();
            }
            
        }
        DB::update("UPDATE proceso_produccion set estado_produccion='Inactivo' where id_proceso_produccion={$id}");
    }
}
