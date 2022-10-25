<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\RolProviciones;
use App\Models\Asientos;
use App\Models\Asientos_contables_detalle;
include 'class/generarPDF.php';

use generarPDF;

class RolProvicionController extends Controller
{
    //
    public function index($id){
        $recupera = DB::select('SELECT if(sum(contabilidad)>=1,1,0) as cont,max(departamento.dep_nombre) as dep_nombre,max(fechrolprov) as fechrolprov,max(departamento.id_departamento) as id_departamento,cod_rol_provision  from rol_provicion,departamento where rol_provicion.id_departamento=departamento.id_departamento and departamento.id_empresa='.$id.'  GROUP BY cod_rol_provision order by fechrolprov desc');
        return $recupera;
    }
    public function store(Request $request){
        $recupera = DB::select('SELECT id_departamento FROM rol_provicion where fechrolprov='.'"'.$request->fechrolprov.'"'.' and id_departamento='.$request->id_departamento);
        //$recupera = DB::select('SELECT id_departamento FROM rol_provicion where  id_departamento='.$request->id_departamento);
        $select=DB::select('SELECT cod_rol_provision from rol_provicion ORDER BY id_rol_provicion desc limit 1');
        $codigo=0;
        if($select){
            $codigo=$select[0]->cod_rol_provision+1;
        }else{
            $codigo=1;
        }
        for ($a = 0; $a < count($request->productos); $a++) {
            if($recupera){
                return "existe";
            }else{
            $rol= new RolProviciones();
            $rol->cod_rol_provision = $codigo;
            $rol->fechrolprov = $request->fechrolprov;
            $rol->primer_nombre = $request->productos[$a]["primer_nombre"];
            $rol->cantidad = $request->productos[$a]["cantidad"];
            $rol->total_ingreso =$request->productos[$a]["total_ingreso"];
            $rol->iess_patronal = $request->productos[$a]["total_ingreso"]*12.15/100;
            $rol->decimo_tercero = $request->productos[$a]["decimo_tercero"];
            $rol->decimo_cuarto =$request->productos[$a]["decimo_cuarto"]/360*$request->productos[$a]["cantidad"];//$request->productos[$a]["decimo_cuarto"];
            $rol->vacaciones =$request->productos[$a]["total_ingreso"]/24;
            $rol->total_provisiones = ($request->productos[$a]["total_ingreso"]*12.15/100)+
                                      $request->productos[$a]["decimo_cuarto"]/360*$request->productos[$a]["cantidad"]+
                                      $request->productos[$a]["decimo_tercero"]+
                                      $request->productos[$a]["fondo_reserva"]+
                                      ($request->productos[$a]["total_ingreso"]/24);
            $rol->total_costo =$request->productos[$a]["total_ingreso"]+
                                        ($request->productos[$a]["total_ingreso"]*12.15/100)+
                                        $request->productos[$a]["decimo_cuarto"]/360*$request->productos[$a]["cantidad"]+
                                        $request->productos[$a]["decimo_tercero"]+
                                        $request->productos[$a]["fondo_reserva"]+
                                        ($request->productos[$a]["total_ingreso"]/24);
            $rol->fondo_reserva = $request->productos[$a]["fondo_reserva"];
            $rol->id_proyecto = $request->productos[$a]["id_proyecto"];                            
            $rol->id_empresa = $request->id_empresa;
            $rol->id_empleado = $request->productos[$a]["id_empleado"];
            $rol->id_departamento = $request->id_departamento;
            $rol->save();
            }
        }
        //return $rol;
    }
    public function update(Request $request){
        for ($a = 0; $a < count($request->productos); $a++) {
            $rol= RolProviciones::find($request->productos[$a]["id_rol_provicion"]);
            
            $rol->fechrolprov = $request->fechrolprov;
            $rol->primer_nombre = $request->productos[$a]["primer_nombre"];
            $rol->cantidad = $request->productos[$a]["cantidad"];
            $rol->total_ingreso =$request->productos[$a]["total_ingreso"];
            $rol->iess_patronal = $request->productos[$a]["total_ingreso"]*12.15/100;
            $rol->decimo_tercero = $request->productos[$a]["decimo_tercero"];
            if($request->productos[$a]["decimo_cuarto"]=="400"){
                $decimo_c=$request->productos[$a]["decimo_cuarto"]/360*$request->productos[$a]["cantidad"];
            }else{
                $decimo_c=$request->productos[$a]["decimo_cuarto"];
            }
            $rol->decimo_cuarto =$decimo_c;//$request->productos[$a]["decimo_cuarto"];
            $rol->vacaciones =$request->productos[$a]["total_ingreso"]/24;
            $rol->total_provisiones = ($request->productos[$a]["total_ingreso"]*12.15/100)+
                                        $decimo_c+
                                      $request->productos[$a]["decimo_tercero"]+
                                      $request->productos[$a]["fondo_reserva"]+
                                      ($request->productos[$a]["total_ingreso"]/24);
            $rol->total_costo =$request->productos[$a]["total_ingreso"]+
                                        ($request->productos[$a]["total_ingreso"]*12.15/100)+
                                        $decimo_c+
                                        $request->productos[$a]["decimo_tercero"]+
                                        $request->productos[$a]["fondo_reserva"]+
                                        ($request->productos[$a]["total_ingreso"]/24);
            $rol->fondo_reserva = $request->productos[$a]["fondo_reserva"]; 
            $rol->id_proyecto = $request->productos[$a]["id_proyecto"];                            
            $rol->id_empresa = $request->id_empresa;
            $rol->id_empleado = $request->productos[$a]["id_empleado"];
            $rol->id_departamento = $request->id_departamento;
            $rol->save();
        }
    }
    public function abrir($id){
        $recupera = DB::select('SELECT rol_provicion.*,if(empleado.decimo_tercero="No",truncate(rol_provicion.total_ingreso/12,2),0) as decimo_tercero_emp,if(empleado.decimo_cuarto="No",400,0) as decimo_cuarto,if(empleado.fondo_reserva="No" and now()>=DATE_ADD(empleado.fecha_ingreso,INTERVAL 1 YEAR),truncate(rol_provicion.total_ingreso/12,2),0) as fondo_reserva_emp,departamento.dep_nombre as departamento from rol_provicion,empleado,departamento where rol_provicion.id_empleado=empleado.id_empleado and rol_provicion.id_departamento=departamento.id_departamento  and cod_rol_provision='.$id);
        return $recupera;
    }
    public function getEmpleadoAsiento($id){
        $recupera=DB::select("SELECT distinct pro.descripcion,pro.id_proyecto,rp.cod_rol_provision,rp.contabilidad FROM proyecto as pro,rol_provicion as rp where rp.id_proyecto=pro.id_proyecto and rp.cod_rol_provision=".$id);
        //$recupera=DB::select("SELECT distinct CONCAT(pro.primer_nombre,' ',pro.apellido_paterno) as nombre,pro.id_empleado,rp.cod_rol_pago,rp.id_departamento,rp.fechrol FROM empleado as pro,rol_pago as rp where rp.id_empleado=pro.id_empleado and rp.cod_rol_pago=".$id);
        return $recupera;
    }
    public function getProyectoAsiento(Request $request,$id){
        //$recupera=DB::select("SELECT distinct pro.descripcion,pro.id_proyecto,rp.cod_rol_pago FROM proyecto as pro,rol_pago as rp where rp.id_proyecto=pro.id_proyecto and rp.cod_rol_pago=".$id);
        $recupera=DB::select("SELECT DISTINCT pro.descripcion ,rp.fechrolprov,emp.ruc_empresa,emp.razon_social,dp.dep_nombre,rp.id_departamento from proyecto as pro,rol_provicion as rp,empresa as emp,departamento as dp  where pro.id_proyecto=rp.id_proyecto and rp.id_empresa=emp.id_empresa and rp.id_departamento=dp.id_departamento and  rp.cod_rol_provision=".$id."  and rp.id_empresa=".$request->id_empresa);
        $codigo=DB::select("SELECT max(numero) as codigo FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.codigo like 'RPR-%' and (asientos.estado='Activo' or asientos.estado is null) and proyecto.id_empresa=".$request->id_empresa);
        $cod_asiento="";
        $cod_asiento_ant="";
        if($codigo){
            $lenght=strlen($codigo[0]->codigo);
            $cod_asiento=$codigo[0]->codigo+1;
        }else{
            $cod_asiento="1";
        }
        if($codigo){
            //$lenght=strlen($codigo[0]->codigo);
            $codigo_anterior=DB::select("SELECT numero as codigo FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.codigo_rol={$id} and (asientos.estado='Activo' or asientos.estado is null) and asientos.id_asientos_comprobante=5 and proyecto.id_empresa=".$request->id_empresa);
            //dd("SELECT numero as codigo FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.id_asientos_comprobante={$id} and proyecto.id_empresa=".$request->id_empresa);
            if($codigo_anterior){
                $cod_asiento_ant=$codigo_anterior[0]->codigo;
            }
            
        }
        return ['recupera'=>$recupera[0],'codigo'=>$cod_asiento,'codigo_anterior'=>$cod_asiento_ant];
    }
    public function getDetalleAsiento(Request $request,$id){
        //$recupera=DB::select("SELECT distinct pro.descripcion,pro.id_proyecto,rp.cod_rol_pago FROM proyecto as pro,rol_pago as rp where rp.id_proyecto=pro.id_proyecto and rp.cod_rol_pago=".$id);
       
        
        
        $parametrizacion=DB::select("SELECT  max(parametrizacion.id_plan_cuentas1) as id_plan_cuentas1,concat(max(plan_cuentas.codcta),'-',max(plan_cuentas.nomcta)) as nombre_cuenta,max(parametrizacion.descripcion) as paramet,proyecto.descripcion,
        if(max(parametrizacion.descripcion)='Aporte Patronal',sum(iess_patronal),'no') as iess,
        if(max(parametrizacion.descripcion)='Vacaciones',sum(vacaciones),'no') as vacaciones,
        if(max(parametrizacion.descripcion)='Decimo Tercero Acumulado',sum(decimo_tercero),'no') as decimo_tercero,
        if(max(parametrizacion.descripcion)='Decimo Cuarto Acumulado',sum(decimo_cuarto),'no') as decimo_cuarto,
        if(max(parametrizacion.descripcion)='Fondo Reserva Acumulado',sum(fondo_reserva),'no') as fondo_reserva,
        rol_provicion.id_proyecto
        from parametrizacion
        INNER JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=parametrizacion.id_plan_cuentas1
                INNER JOIN rol_provicion
        on rol_provicion.id_departamento=parametrizacion.id_departamento
                INNER JOIN proyecto
        on rol_provicion.id_proyecto=proyecto.id_proyecto
        where parametrizacion.id_departamento={$id} and rol_provicion.cod_rol_provision={$request->cod} 
                GROUP BY rol_provicion.id_proyecto,parametrizacion.descripcion");

        $parametrizacion_haber=DB::select("SELECT  max(parametrizacion.id_plan_cuentas2) as id_plancuenta,concat(max(plan_cuentas.codcta),'-',max(plan_cuentas.nomcta)) as nombre_cuenta,parametrizacion.descripcion as parametr,proyecto.descripcion,
        if(max(parametrizacion.descripcion)='Aporte Patronal',sum(iess_patronal),'no') as iess,
        if(max(parametrizacion.descripcion)='Vacaciones',sum(vacaciones),'no') as vacaciones,
        if(max(parametrizacion.descripcion)='Decimo Tercero Acumulado',sum(decimo_tercero),'no') as decimo_tercero,
        if(max(parametrizacion.descripcion)='Decimo Cuarto Acumulado',sum(decimo_cuarto),'no') as decimo_cuarto,
        if(max(parametrizacion.descripcion)='Fondo Reserva Acumulado',sum(fondo_reserva),'no') as fondo_reserva,
        rol_provicion.id_proyecto
                from parametrizacion
                INNER JOIN plan_cuentas
                on plan_cuentas.id_plan_cuentas=parametrizacion.id_plan_cuentas2
                        INNER JOIN rol_provicion
                on rol_provicion.id_departamento=parametrizacion.id_departamento
                        INNER JOIN proyecto
                on rol_provicion.id_proyecto=proyecto.id_proyecto
                where parametrizacion.id_departamento={$id} and rol_provicion.cod_rol_provision={$request->cod} 
                        GROUP BY rol_provicion.id_proyecto,parametrizacion.descripcion");
        $proyecto=DB::select("SELECT id_proyecto from proyecto where id_empresa=".$request->id_empresa);
        $rol=DB::select("SELECT * from rol_provicion where cod_rol_provision={$request->cod}");
        $fecha_emision=substr($rol[0]->fechrolprov,0,-3);
        $anio_emision=substr($rol[0]->fechrolprov,0,4);
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
            'asiento_permitido'=>$asiento,
            'cuentas'=>$parametrizacion,
            'cuentas_haber'=>$parametrizacion_haber,
            'id_proyecto'=>$proyecto[0]->id_proyecto

        ];
    }
    public function agregarAsiento(Request $request){
        RolProviciones::where('cod_rol_provision',$request->cod_rol)->update(['contabilidad'=>'1']);
        $asientos=new Asientos();
        $asientos->automatico=0;
        $asientos->numero=$request->numero;
        $asientos->codigo=$request->codigo;
        $asientos->codigo_rol=$request->cod_rol;
        $asientos->fecha=$request->fecha;
        $asientos->razon_social=$request->razon_social;
        $asientos->tipo_identificacion="Ruc";
        $asientos->ruc_ci=$request->ruc_ci;
        $asientos->concepto=$request->concepto;
        $asientos->ucrea=$request->ucrea;
        $asientos->id_proyecto=$request->id_proyecto;
        $asientos->id_asientos_comprobante=5;

        $asientos->save();
        return $asientos->id_asientos;
    }
    public function agregarAsientoDetalle(Request $request){
        //RolPago::where('cod_rol_pago',$request->cod_rol)->update(['contabilidad'=>'Si']);

        foreach($request->parametrizacion_debe as $ingresos_debe){
               
            $asiento=new Asientos_contables_detalle();
            
            if($ingresos_debe["paramet"]=="Aporte Patronal" && $ingresos_debe["iess"]>0){
                $asiento->proyecto=$ingresos_debe["descripcion"];
                $asiento->debe=$ingresos_debe["iess"];
                $asiento->ucrea=$request->ucrea;
                $asiento->id_plan_cuentas=$ingresos_debe["id_plan_cuentas1"];
                $asiento->id_asientos=$request->id_asientos;
                $asiento->id_proyecto=$ingresos_debe["id_proyecto"];
            }else{
                if($ingresos_debe["paramet"]=="Decimo Cuarto Acumulado" && $ingresos_debe["decimo_cuarto"]>0){
                    $asiento->proyecto=$ingresos_debe["descripcion"];
                    $asiento->debe=$ingresos_debe["decimo_cuarto"];
                    $asiento->ucrea=$request->ucrea;
                    $asiento->id_plan_cuentas=$ingresos_debe["id_plan_cuentas1"];
                    $asiento->id_asientos=$request->id_asientos;
                    $asiento->id_proyecto=$ingresos_debe["id_proyecto"];
                }else{
                    if($ingresos_debe["paramet"]=="Vacaciones" && $ingresos_debe["vacaciones"]>0){
                        $asiento->proyecto=$ingresos_debe["descripcion"];
                        $asiento->debe=$ingresos_debe["vacaciones"];
                        $asiento->ucrea=$request->ucrea;
                        $asiento->id_plan_cuentas=$ingresos_debe["id_plan_cuentas1"];
                        $asiento->id_asientos=$request->id_asientos;
                        $asiento->id_proyecto=$ingresos_debe["id_proyecto"];
                    }else{
                        if($ingresos_debe["paramet"]=="Decimo Tercero Acumulado" && $ingresos_debe["decimo_tercero"]>0){
                            $asiento->proyecto=$ingresos_debe["descripcion"];
                            $asiento->debe=$ingresos_debe["decimo_tercero"];
                            $asiento->ucrea=$request->ucrea;
                            $asiento->id_plan_cuentas=$ingresos_debe["id_plan_cuentas1"];
                            $asiento->id_asientos=$request->id_asientos;
                            $asiento->id_proyecto=$ingresos_debe["id_proyecto"];
                        }else{
                            if($ingresos_debe["paramet"]=="Fondo Reserva Acumulado" && $ingresos_debe["fondo_reserva"]>0){
                                $asiento->proyecto=$ingresos_debe["descripcion"];
                                $asiento->debe=$ingresos_debe["fondo_reserva"];
                                $asiento->ucrea=$request->ucrea;
                                $asiento->id_plan_cuentas=$ingresos_debe["id_plan_cuentas1"];
                                $asiento->id_asientos=$request->id_asientos;
                                $asiento->id_proyecto=$ingresos_debe["id_proyecto"];
                            }
                        }
                    }
                }
            }
            $asiento->save();
        
        }
        foreach($request->parametrizacion_haber as $ingresos_debe){
            $asiento=new Asientos_contables_detalle();
            if($ingresos_debe["parametr"]=="Aporte Patronal" && $ingresos_debe["iess"]>0){
                $asiento->proyecto=$ingresos_debe["descripcion"];
                $asiento->haber=$ingresos_debe["iess"];
                $asiento->ucrea=$request->ucrea;
                $asiento->id_plan_cuentas=$ingresos_debe["id_plancuenta"];
                $asiento->id_asientos=$request->id_asientos;
                $asiento->id_proyecto=$ingresos_debe["id_proyecto"];
            }else{
                if($ingresos_debe["parametr"]=="Decimo Cuarto Acumulado" && $ingresos_debe["decimo_cuarto"]>0){
                    $asiento->proyecto=$ingresos_debe["descripcion"];
                    $asiento->haber=$ingresos_debe["decimo_cuarto"];
                    $asiento->ucrea=$request->ucrea;
                    $asiento->id_plan_cuentas=$ingresos_debe["id_plancuenta"];
                    $asiento->id_asientos=$request->id_asientos;
                    $asiento->id_proyecto=$ingresos_debe["id_proyecto"];
                }else{
                    if($ingresos_debe["parametr"]=="Vacaciones" && $ingresos_debe["vacaciones"]>0){
                        $asiento->proyecto=$ingresos_debe["descripcion"];
                        $asiento->haber=$ingresos_debe["vacaciones"];
                        $asiento->ucrea=$request->ucrea;
                        $asiento->id_plan_cuentas=$ingresos_debe["id_plancuenta"];
                        $asiento->id_asientos=$request->id_asientos;
                        $asiento->id_proyecto=$ingresos_debe["id_proyecto"];
                    }else{
                        if($ingresos_debe["parametr"]=="Decimo Tercero Acumulado" && $ingresos_debe["decimo_tercero"]>0){
                            $asiento->proyecto=$ingresos_debe["descripcion"];
                            $asiento->haber=$ingresos_debe["decimo_tercero"];
                            $asiento->ucrea=$request->ucrea;
                            $asiento->id_plan_cuentas=$ingresos_debe["id_plancuenta"];
                            $asiento->id_asientos=$request->id_asientos;
                            $asiento->id_proyecto=$ingresos_debe["id_proyecto"];
                        }else{
                            if($ingresos_debe["parametr"]=="Fondo Reserva Acumulado" && $ingresos_debe["fondo_reserva"]>0){
                                $asiento->proyecto=$ingresos_debe["descripcion"];
                                $asiento->haber=$ingresos_debe["fondo_reserva"];
                                $asiento->ucrea=$request->ucrea;
                                $asiento->id_plan_cuentas=$ingresos_debe["id_plancuenta"];
                                $asiento->id_asientos=$request->id_asientos;
                                $asiento->id_proyecto=$ingresos_debe["id_proyecto"];
                            }
                        }
                    }
                }
            }
            $asiento->save();
        
        }

        
    }
    public function abrirIngresos(Request $request,$id)
    {
        $recupera = DB::select('SELECT rol_pago.id_empleado,rol_pago.primer_nombre,rol_pago.cantidad,rol_pago.id_proyecto,rol_pago.sueldo,(rol_pago.sueldo+rol_pago.valor_ingreso1+rol_pago.valor_ingreso2+rol_pago.valor_ingreso3+rol_pago.valor_ingreso4+rol_pago.valor_ingreso5+rol_pago.valor_ingreso6) as total_ingreso,if(empleado.decimo_tercero="No",truncate(rol_pago.total_ingreso/12,2),0) as decimo_tercero,if(empleado.decimo_cuarto="No",400,0) as decimo_cuarto,if(empleado.fondo_reserva="No" and now()>=DATE_ADD(empleado.fecha_ingreso,INTERVAL 1 YEAR),truncate(rol_pago.total_ingreso/12,2),0) as fondo_reserva,DATE_ADD(empleado.fecha_ingreso,INTERVAL 1 YEAR) as fecha_fd_reserva FROM rol_pago,empleado where rol_pago.id_empleado=empleado.id_empleado and rol_pago.fechrol='.'"'.$request->fecha.'"'. 'and empleado.id_departamento='.$id);
        //$recupera = DB::select('SELECT rol_pago.id_empleado,rol_pago.primer_nombre,rol_pago.cantidad,rol_pago.sueldo,rol_pago.total_ingreso,if(empleado.decimo_tercero="No",truncate(rol_pago.total_ingreso/12,2),0) as decimo_tercero,if(empleado.decimo_cuarto="No",400,0) as decimo_cuarto,if(empleado.fondo_reserva="No",truncate(rol_pago.total_ingreso/12,2),0) as fondo_reserva FROM rol_pago,empleado where rol_pago.id_empleado=empleado.id_empleado and rol_pago.fechrol='.'"'.$request->fecha.'"'. 'and empleado.id_departamento='.$id);
        return $recupera;
    }
    public function generarReporte(Request $request){
        $queries = [];
        $proyecto="";
        //dd($inners);
        if ($request->id_proyecto) {
            if ($request->id_proyecto != 0) {
                $proy=DB::select("SELECT descripcion from proyecto where id_proyecto=".$request->id_proyecto);
                array_push($queries, "rol_pago.id_proyecto = {$request->id_proyecto}\n");
                $proyecto=$proy[0]->descripcion;
            }else{
                $proyecto="Todos";
            }
        }else{
            $proyecto="Todos";
        };
        $queries = implode(" and ", $queries);
        if($queries){
            $recupera=DB::select("SELECT rol_provicion.*,departamento.dep_nombre,empleado.dni,empleado.foto from rol_provicion,departamento,empleado where {$queries} and rol_provicion.id_departamento=departamento.id_departamento and rol_provicion.id_empleado=empleado.id_empleado and rol_provicion.fechrolprov=".'"'.$request->date.'"'."  and rol_provicion.id_departamento=".$request->id_departamento);
        }else{
            $recupera=DB::select("SELECT rol_provicion.*,departamento.dep_nombre,empleado.dni,empleado.foto from rol_provicion,departamento,empleado where rol_provicion.id_departamento=departamento.id_departamento and rol_provicion.id_empleado=empleado.id_empleado and rol_provicion.fechrolprov=".'"'.$request->date.'"'."  and rol_provicion.id_departamento=".$request->id_departamento);
        }
        $recupera2=DB::select("SELECT user.*,empresa.nombre_empresa,empresa.logo,empresa.id_empresa,empresa.nombre_contador,empresa.nomb_representante from user,empresa where user.id_empresa=empresa.id_empresa and user.id=".$request->id_user);
        $Reportes = new generarPDF();
        
        if (!$recupera) {
            return response('no-data-report', 200)->header('Content-Type', 'application/json');
        } else {
            $strPDF = $Reportes->RolProvicion($recupera, $request->date,$recupera2,$proyecto);
            return response($strPDF, 200)->header('Content-Type', 'application/pdf');
        }
    }
    public function destroy($id){
        RolProviciones::where('cod_rol_provision','=',$id)->delete();
    }
}
