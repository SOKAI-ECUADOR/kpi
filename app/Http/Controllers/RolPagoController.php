<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Rol;
use App\Models\RolPagoIngreso;
use App\Models\RolPagoEgreso;
use App\Models\RolPago;
use App\Models\Asientos;
use App\Models\Asientos_contables_detalle;
include 'class/generarPDF.php';

use generarPDF;

include 'class/RolesEmail.php';
use sendEmailRoles;

include 'class/RolGeneralEmail.php';
use sendEmailRolGeneral;

include_once getenv("FILE_CONFIG_PHP");

class RolPagoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        /*$recupera = DB::select('SELECT distinct departamento.id_departamento,departamento.dep_nombre,(select sum(rol_pago_ingreso.total_ingreso) from rol_pago_ingreso where rol_pago_ingreso.id_departamento=departamento.id_departamento) as total_ingreso,(select sum(rol_pago_egreso.total_egreso) from rol_pago_egreso where rol_pago_egreso.id_departamento=departamento.id_departamento) as total_egreso
        FROM departamento,ingresos_egresos,rol_pago_ingreso,rol_pago_egreso
        where rol_pago_egreso.id_departamento=departamento.id_departamento
        and rol_pago_ingreso.id_departamento=departamento.id_departamento');*/
        $recupera = DB::select('SELECT if(sum(contabilidad)>=1,1,0) as cont,max(departamento.dep_nombre) as dep_nombre,max(fechrol) as fechrol,max(rol_pago.id_departamento) as id_departamento,cod_rol_pago  from rol_pago,departamento where rol_pago.id_departamento=departamento.id_departamento and departamento.id_empresa='.$id.'  GROUP BY cod_rol_pago ORDER BY max(fechrol) desc');
        return $recupera;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    public function RolPagoEmpleado(Request $request){
        $recupera=DB::select("SELECT rol_pago.*,proyecto.descripcion,null as valor_pagar from rol_pago 
        INNER JOIN proyecto
        ON proyecto.id_proyecto=rol_pago.id_proyecto
        where id_departamento={$request->id_departamento} and fechrol='{$request->fecha}'");
        if($recupera){
            return $recupera;
        }else{
            return "vacio";
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        setlocale(LC_TIME, "spanish");
        $recupera = DB::select('SELECT id_departamento FROM rol_pago where fechrol='.'"'.$request->fechrol.'"'.' and id_departamento='.$request->id_departamento);
        $select=DB::select('SELECT cod_rol_pago from rol_pago ORDER BY id_rol_pago desc limit 1');
        $codigo=0;
        if($select){
            $codigo=$select[0]->cod_rol_pago+1;
        }else{
            $codigo=1;
        }
        $fecha_papeleta=ucwords(strftime("%B %Y", strtotime($request->fechrol)));
        $carpetanombre = constant("DATA_EMPRESA").$request->id_empresa;
        $carpeta1 = $carpetanombre."/papeletas/".$fecha_papeleta."/".$request->id_departamento."/";
        if (!file_exists($carpeta1)) {
            mkdir($carpeta1, 0755,true);
        }
        $carpetanombre2 = constant("DATA_EMPRESA").$request->id_empresa;
        $carpeta2 = $carpetanombre2."/rol_general/".$fecha_papeleta."/".$request->id_departamento."/";
        if (!file_exists($carpeta2)) {
            mkdir($carpeta2, 0755,true);
        }
        for ($a = 0; $a < count($request->productos); $a++) {
            
            /*if($request->nrocolum=="1"){
                
                $dfactc->primer_nombre = $request->productos[$a]["primer_nombre"];
                $dfactc->cantidad = $request->productos[$a]["cantidad"];
                $dfactc->sueldo   = $request->productos[$a]["sueldo"];
                $dfactc->ingreso1 = $request->productos[$a]["ingreso1"];
                $dfactc->total_ingreso =$request->productos[$a]["sueldo"]+
                                        $request->productos[$a]["ingreso1"];
                $dfactc->id_departamento = $request->id_departamento;
                $dfactc->save();
            }
            if($request->nrocolum=="2"){
                $dfactc->primer_nombre = $request->productos[$a]["primer_nombre"]." "." ".$request->productos[$a]["apellido_paterno"];
                $dfactc->cantidad = $request->productos[$a]["cantidad"];
                $dfactc->sueldo   = $request->productos[$a]["sueldo"];
                $dfactc->ingreso1 = $request->productos[$a]["ingreso1"];
                $dfactc->ingreso2 = $request->productos[$a]["ingreso2"];
                $dfactc->total_ingreso =$request->productos[$a]["sueldo"]+
                                        $request->productos[$a]["ingreso1"]+
                                        $request->productos[$a]["ingreso2"];
                $dfactc->id_departamento = $request->id_departamento;
                $dfactc->save();
            }
            if($request->nrocolum=="3"){
                $dfactc->primer_nombre = $request->productos[$a]["primer_nombre"];
                $dfactc->cantidad = $request->productos[$a]["cantidad"];
                $dfactc->sueldo   = $request->productos[$a]["sueldo"];
                $dfactc->ingreso1 = $request->productos[$a]["ingreso1"];
                $dfactc->ingreso2 = $request->productos[$a]["ingreso2"];
                $dfactc->ingreso3 = $request->productos[$a]["ingreso3"];
                $dfactc->total_ingreso =$request->productos[$a]["sueldo"]+
                                        $request->productos[$a]["ingreso1"]+
                                        $request->productos[$a]["ingreso2"]+
                                        $request->productos[$a]["ingreso3"];
                $dfactc->id_departamento = $request->id_departamento;
                $dfactc->save();
            }
            if($request->nrocolum=="4"){
                $dfactc->primer_nombre = $request->productos[$a]["primer_nombre"];
                $dfactc->cantidad = $request->productos[$a]["cantidad"];
                $dfactc->sueldo   = $request->productos[$a]["sueldo"];
                $dfactc->ingreso1 = $request->productos[$a]["ingreso1"];
                $dfactc->ingreso2 = $request->productos[$a]["ingreso2"];
                $dfactc->ingreso3 = $request->productos[$a]["ingreso3"];
                $dfactc->ingreso4 = $request->productos[$a]["ingreso4"];
                $dfactc->total_ingreso =$request->productos[$a]["sueldo"]+
                                        $request->productos[$a]["ingreso1"]+
                                        $request->productos[$a]["ingreso2"]+
                                        $request->productos[$a]["ingreso3"]+
                                        $request->productos[$a]["ingreso4"];
                $dfactc->id_departamento = $request->id_departamento;
                $dfactc->save();
            }
            if($request->nrocolum=="5"){
                $dfactc->primer_nombre = $request->productos[$a]["primer_nombre"];
                $dfactc->cantidad = $request->productos[$a]["cantidad"];
                $dfactc->sueldo   = $request->productos[$a]["sueldo"];
                $dfactc->ingreso1 = $request->productos[$a]["ingreso1"];
                $dfactc->ingreso2 = $request->productos[$a]["ingreso2"];
                $dfactc->ingreso3 = $request->productos[$a]["ingreso3"];
                $dfactc->ingreso4 = $request->productos[$a]["ingreso4"];
                $dfactc->ingreso5 = $request->productos[$a]["ingreso5"];
                $dfactc->total_ingreso =$request->productos[$a]["sueldo"]+
                                        $request->productos[$a]["ingreso1"]+
                                        $request->productos[$a]["ingreso2"]+
                                        $request->productos[$a]["ingreso3"]+
                                        $request->productos[$a]["ingreso4"]+
                                        $request->productos[$a]["ingreso5"];
                $dfactc->id_departamento = $request->id_departamento;
                $dfactc->save();
            }*/
            //if($request->nrocolum=="6"){
                if($recupera){
                    return "existe";
                }else{
                    $dfactc = new RolPago();
                    $dfactc->primer_nombre = $request->productos[$a]["primer_nombre"]." ".$request->productos[$a]["apellido_paterno"];
                    $dfactc->cod_rol_pago = $codigo;
                    $dfactc->cantidad = $request->productos[$a]["cantidad"];
                    $dfactc->sueldo   = $request->productos[$a]["sueldo"]/30*$request->productos[$a]["cantidad"];
                    $dfactc->ingreso1 = $request->productos[$a]["ingreso1"];
                    $dfactc->ingreso2 = $request->productos[$a]["ingreso2"];
                    $dfactc->ingreso3 = $request->productos[$a]["ingreso3"];
                    $dfactc->ingreso4 = $request->productos[$a]["ingreso4"];
                    $dfactc->ingreso5 = $request->productos[$a]["ingreso5"];
                    $dfactc->ingreso6 = $request->productos[$a]["ingreso6"];
                    $dfactc->valor_ingreso1 = $request->productos[$a]["valor_ingreso1"];
                    $dfactc->valor_ingreso2 = $request->productos[$a]["valor_ingreso2"];
                    $dfactc->valor_ingreso3 = $request->productos[$a]["valor_ingreso3"];
                    $dfactc->valor_ingreso4 = $request->productos[$a]["valor_ingreso4"];
                    $dfactc->valor_ingreso5 = $request->productos[$a]["valor_ingreso5"];
                    $dfactc->valor_ingreso6 = $request->productos[$a]["valor_ingreso6"];
                    $dfactc->total_ingreso =($request->productos[$a]["sueldo"]/30*$request->productos[$a]["cantidad"])+
                                            $request->productos[$a]["ingreso1"]+
                                            $request->productos[$a]["ingreso2"]+
                                            $request->productos[$a]["ingreso3"]+
                                            $request->productos[$a]["ingreso4"]+
                                            $request->productos[$a]["ingreso5"]+
                                            $request->productos[$a]["ingreso6"]+
                                            $request->productos[$a]["decimo_tercero"]+
                                            $request->productos[$a]["decimo_cuarto"]+
                                            $request->productos[$a]["fondo_reserva"];
                    $dfactc->egreso1 = $request->productos[$a]["egreso1"];
                    $dfactc->egreso2 = $request->productos[$a]["egreso2"];
                    $dfactc->egreso3 = $request->productos[$a]["egreso3"];
                    $dfactc->egreso4 = $request->productos[$a]["egreso4"];
                    $dfactc->egreso5 = $request->productos[$a]["egreso5"];
                    $dfactc->egreso6 = $request->productos[$a]["egreso6"];
                    $dfactc->iess = (($request->productos[$a]["sueldo"]/30*$request->productos[$a]["cantidad"])+
                                    $request->productos[$a]["valor_ingreso1"]+
                                    $request->productos[$a]["valor_ingreso2"]+
                                    $request->productos[$a]["valor_ingreso3"]+
                                    $request->productos[$a]["valor_ingreso4"]+
                                    $request->productos[$a]["valor_ingreso5"]+
                                    $request->productos[$a]["valor_ingreso6"])*9.45/100;
                    $dfactc->total_egreso =(($request->productos[$a]["sueldo"]/30*$request->productos[$a]["cantidad"])+
                                                $request->productos[$a]["valor_ingreso1"]+
                                                $request->productos[$a]["valor_ingreso2"]+
                                                $request->productos[$a]["valor_ingreso3"]+
                                                $request->productos[$a]["valor_ingreso4"]+
                                                $request->productos[$a]["valor_ingreso5"]+
                                                $request->productos[$a]["valor_ingreso6"])*9.45/100+
                                                $request->productos[$a]["egreso1"]+
                                                $request->productos[$a]["egreso2"]+
                                                $request->productos[$a]["egreso3"]+
                                                $request->productos[$a]["egreso4"]+
                                                $request->productos[$a]["egreso5"]+
                                                $request->productos[$a]["egreso6"];
                    $dfactc->valor_recibir =(($request->productos[$a]["sueldo"]/30*$request->productos[$a]["cantidad"])+
                                            $request->productos[$a]["ingreso1"]+
                                            $request->productos[$a]["ingreso2"]+
                                            $request->productos[$a]["ingreso3"]+
                                            $request->productos[$a]["ingreso4"]+
                                            $request->productos[$a]["ingreso5"]+
                                            $request->productos[$a]["ingreso6"]+
                                            $request->productos[$a]["decimo_tercero"]+
                                            $request->productos[$a]["decimo_cuarto"]+
                                            $request->productos[$a]["fondo_reserva"])-
                                                ((($request->productos[$a]["sueldo"]/30*$request->productos[$a]["cantidad"])+
                                                $request->productos[$a]["valor_ingreso1"]+
                                                $request->productos[$a]["valor_ingreso2"]+
                                                $request->productos[$a]["valor_ingreso3"]+
                                                $request->productos[$a]["valor_ingreso4"]+
                                                $request->productos[$a]["valor_ingreso5"]+
                                                $request->productos[$a]["valor_ingreso6"])*9.45/100+
                                                    $request->productos[$a]["egreso1"]+
                                                    $request->productos[$a]["egreso2"]+
                                                    $request->productos[$a]["egreso3"]+
                                                    $request->productos[$a]["egreso4"]+
                                                    $request->productos[$a]["egreso5"]+
                                                    $request->productos[$a]["egreso6"]);
                    $dfactc->fechrol = $request->fechrol;
                    $dfactc->decimo_tercero = $request->productos[$a]["decimo_tercero"];
                    $dfactc->fondo_reserva = $request->productos[$a]["fondo_reserva"];
                    $dfactc->decimo_cuarto = $request->productos[$a]["decimo_cuarto"];
                    //proyecto
                    $dfactc->ucrea = $request->ucrea;
                    $dfactc->id_proyecto = $request->productos[$a]["id_proyecto"];
                    $dfactc->id_departamento = $request->id_departamento;
                    $dfactc->id_empleado = $request->productos[$a]["id_empleado"];
                    $dfactc->id_empresa = $request->id_empresa;
                    $dfactc->save();
                } 
               
            //}
            
        }
        
        
    }
    public function getEmpleadoAsiento(Request $request,$id){
        $recupera=DB::select("SELECT distinct pro.descripcion,pro.id_proyecto,rp.cod_rol_pago,rp.contabilidad FROM proyecto as pro,rol_pago as rp where rp.id_proyecto=pro.id_proyecto and rp.cod_rol_pago=".$id." and rp.id_empresa=".$request->id_empresa);
        //$recupera=DB::select("SELECT distinct CONCAT(pro.primer_nombre,' ',pro.apellido_paterno) as nombre,pro.id_empleado,rp.cod_rol_pago,rp.id_departamento,rp.fechrol FROM empleado as pro,rol_pago as rp where rp.id_empleado=pro.id_empleado and rp.cod_rol_pago=".$id);
        return $recupera;
    }
    public function getProyectoAsiento(Request $request,$id){
        //$recupera=DB::select("SELECT distinct pro.descripcion,pro.id_proyecto,rp.cod_rol_pago FROM proyecto as pro,rol_pago as rp where rp.id_proyecto=pro.id_proyecto and rp.cod_rol_pago=".$id);
        $recupera=DB::select("SELECT DISTINCT pro.descripcion ,rp.fechrol,emp.ruc_empresa,emp.razon_social,dp.dep_nombre,rp.id_departamento from proyecto as pro,rol_pago as rp,empresa as emp,departamento as dp  where pro.id_proyecto=rp.id_proyecto and rp.id_empresa=emp.id_empresa and rp.id_departamento=dp.id_departamento and  rp.cod_rol_pago=".$id."  and rp.id_empresa=".$request->id_empresa);
        $codigo=DB::select("SELECT max(numero) as codigo FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.codigo like 'RP-%' and (asientos.estado='Activo' or asientos.estado is null) and proyecto.id_empresa=".$request->id_empresa);
        $cod_asiento="";
        if($codigo){
            $lenght=strlen($codigo[0]->codigo);
            $cod_asiento=$codigo[0]->codigo+1;
        }else{
            $cod_asiento="1";
        }
        $cod_asiento_ant="";
        if($codigo){
            $codigo_anterior=DB::select("SELECT numero as codigo FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.codigo_rol={$id} and asientos.id_asientos_comprobante=4 and (asientos.estado='Activo' or asientos.estado is null) and proyecto.id_empresa=".$request->id_empresa);
            //$lenght=strlen($codigo[0]->codigo);
            if($codigo_anterior){
                $cod_asiento_ant=$codigo_anterior[0]->codigo;
            }
            
        }
        //dd($cod_asiento);
        return ['recupera'=>$recupera[0],'codigo'=>$cod_asiento,'codigo_anterior'=>$cod_asiento_ant];
    }
    public function getDetalleAsiento(Request $request,$id){
        //$recupera=DB::select("SELECT distinct pro.descripcion,pro.id_proyecto,rp.cod_rol_pago FROM proyecto as pro,rol_pago as rp where rp.id_proyecto=pro.id_proyecto and rp.cod_rol_pago=".$id);
       
        $proyecto=DB::select("SELECT proyecto.* from proyecto
        INNER JOIN departamento
        on departamento.id_empresa=proyecto.id_empresa
        where departamento.id_departamento={$id}");
        $debe=DB::select("SELECT sum(asig.valor) as valor_debe,asig.id_ineg,CONCAT(pl.codcta,'-',pl.nomcta) as debe,pl.id_plan_cuentas,pro.id_proyecto,pro.descripcion
        from asignar_ingresos as asig,ingresos_egresos as ie,plan_cuentas as pl,empleado as emp,rol_pago as rp,proyecto  as pro 
        where ie.id_ineg=asig.id_ineg and ie.id_plan_cuentas_1=pl.id_plan_cuentas  and asig.id_departamento={$id} and asig.fecha_asignar='{$request->fecha}' 
        and asig.id_empleado=emp.id_empleado  and rp.id_empleado=emp.id_empleado and rp.cod_rol_pago={$request->cod}  and rp.id_proyecto=pro.id_proyecto and asig.tiene_detalle=0
        GROUP BY ie.id_ineg,rp.id_proyecto
        UNION
        SELECT sum(asig.valor) as valor_debe,ie.id_ineg,CONCAT(pl.codcta,'-',pl.nomcta) as debe,pl.id_plan_cuentas,pro.id_proyecto,pro.descripcion
        from asignar_ingresos_detalle as asig,ingresos_egresos as ie,plan_cuentas as pl,empleado as emp,rol_pago as rp,proyecto  as pro 
        where ie.id_ineg=asig.id_ingreso_egreso and asig.id_empleado=emp.id_empleado and ie.id_plan_cuentas_1=pl.id_plan_cuentas  and emp.id_departamento={$id} and 
				asig.fecha_asignar='{$request->fecha}' and rp.id_empleado=emp.id_empleado and rp.cod_rol_pago={$request->cod}  and rp.id_proyecto=pro.id_proyecto
				GROUP BY ie.id_ineg,rp.id_proyecto");
        // dd("SELECT sum(asig.valor) as valor_debe,asig.id_ineg,CONCAT(pl.codcta,'-',pl.nomcta) as debe,pl.id_plan_cuentas,pro.id_proyecto,pro.descripcion
        // from asignar_ingresos as asig,ingresos_egresos as ie,plan_cuentas as pl,empleado as emp,rol_pago as rp,proyecto  as pro 
        // where ie.id_ineg=asig.id_ineg and ie.id_plan_cuentas_1=pl.id_plan_cuentas  and asig.id_departamento={$id} and asig.fecha_asignar='{$request->fecha}' 
        // and asig.id_empleado=emp.id_empleado  and rp.id_empleado=emp.id_empleado and rp.cod_rol_pago={$request->cod}  and rp.id_proyecto=pro.id_proyecto 
        // GROUP BY ie.id_ineg,rp.id_proyecto");
        $haber=DB::select("SELECT sum(asig.valor) as valor_debe,asig.id_ineg,CONCAT(pl.codcta,'-',pl.nomcta) as debe,pl.id_plan_cuentas,pro.id_proyecto,pro.descripcion
        from asignar_ingresos as asig,ingresos_egresos as ie,plan_cuentas as pl,empleado as emp,rol_pago as rp,proyecto  as pro 
        where ie.id_ineg=asig.id_ineg and ie.id_plan_cuentas_2=pl.id_plan_cuentas  and asig.id_departamento={$id} and asig.fecha_asignar='{$request->fecha}' 
        and asig.id_empleado=emp.id_empleado  and rp.id_empleado=emp.id_empleado and rp.cod_rol_pago={$request->cod}  and rp.id_proyecto=pro.id_proyecto  and tiene_detalle=0
        GROUP BY ie.id_ineg,rp.id_proyecto
        UNION
        SELECT sum(asig.valor) as valor_debe,ie.id_ineg,CONCAT(pl.codcta,'-',pl.nomcta) as debe,pl.id_plan_cuentas,pro.id_proyecto,pro.descripcion
        from asignar_ingresos_detalle as asig,ingresos_egresos as ie,plan_cuentas as pl,empleado as emp,rol_pago as rp,proyecto  as pro 
        where ie.id_ineg=asig.id_ingreso_egreso and asig.id_empleado=emp.id_empleado and ie.id_plan_cuentas_2=pl.id_plan_cuentas  and emp.id_departamento={$id} and 
				asig.fecha_asignar='{$request->fecha}' and rp.id_empleado=emp.id_empleado and rp.cod_rol_pago={$request->cod}  and rp.id_proyecto=pro.id_proyecto
				GROUP BY ie.id_ineg,rp.id_proyecto");
        // dd("SELECT sum(asig.valor) as valor_debe,asig.id_ineg,CONCAT(pl.codcta,'-',pl.nomcta) as debe,pl.id_plan_cuentas,pro.id_proyecto,pro.descripcion
        // from asignar_ingresos as asig,ingresos_egresos as ie,plan_cuentas as pl,empleado as emp,rol_pago as rp,proyecto  as pro 
        // where ie.id_ineg=asig.id_ineg and ie.id_plan_cuentas_2=pl.id_plan_cuentas  and asig.id_departamento={$id} and asig.fecha_asignar='{$request->fecha}' 
        // and asig.id_empleado=emp.id_empleado  and rp.id_empleado=emp.id_empleado and rp.cod_rol_pago={$request->cod}  and rp.id_proyecto=pro.id_proyecto 
        // GROUP BY ie.id_ineg,rp.id_proyecto");
        $parametrizacion=DB::select("SELECT  max(parametrizacion.id_plan_cuentas1) as id_plan_cuentas1, concat(max(plan_cuentas.codcta),'-',max(plan_cuentas.nomcta)) as nombre_cuenta,max(parametrizacion.descripcion) as paramet,
        sum(decimo_tercero),proyecto.descripcion,if(max(parametrizacion.descripcion)='Decimo Tercero Mensual',sum(decimo_tercero),'no') as decimo_tercero,
        if(max(parametrizacion.descripcion)='Decimo Cuarto Mensual',sum(decimo_cuarto),'no') as decimo_cuarto,
        if(max(parametrizacion.descripcion)='Fondo Reserva Mensual',sum(fondo_reserva),'no') as fondo_reserva,
        if(max(parametrizacion.descripcion)='Sueldo',sum(sueldo),'no') as sueldo,
        if(max(parametrizacion.descripcion)='Aporte Personal',sum(iess),'no') as iess,
        rol_pago.id_proyecto
                from parametrizacion
                INNER JOIN plan_cuentas
                on plan_cuentas.id_plan_cuentas=parametrizacion.id_plan_cuentas1
                        INNER JOIN rol_pago
                on rol_pago.id_departamento=parametrizacion.id_departamento
                        INNER JOIN proyecto
                on rol_pago.id_proyecto=proyecto.id_proyecto
                where parametrizacion.id_departamento={$id} and rol_pago.cod_rol_pago={$request->cod} and parametrizacion.descripcion not like '%Acumulado%'
                        GROUP BY rol_pago.id_proyecto,parametrizacion.descripcion");

        $parametrizacion_haber=DB::select("SELECT  max(parametrizacion.id_plan_cuentas2) as id_plancuenta,concat(max(plan_cuentas.codcta),'-',max(plan_cuentas.nomcta)) as nombre_cuenta,parametrizacion.descripcion as parametr,
        sum(decimo_tercero),proyecto.descripcion,if(max(parametrizacion.descripcion)='Decimo Tercero Mensual',sum(decimo_tercero),'no') as decimo_tercero,
        if(max(parametrizacion.descripcion)='Decimo Cuarto Mensual',sum(decimo_cuarto),'no') as decimo_cuarto,
        if(max(parametrizacion.descripcion)='Fondo Reserva Mensual',sum(fondo_reserva),'no') as fondo_reserva,
        if(max(parametrizacion.descripcion)='Sueldo',sum(sueldo),'no') as sueldo,
        if(max(parametrizacion.descripcion)='Aporte Personal',sum(iess),'no') as iess,
        rol_pago.id_proyecto
                from parametrizacion
                INNER JOIN plan_cuentas
                on plan_cuentas.id_plan_cuentas=parametrizacion.id_plan_cuentas2
                        INNER JOIN rol_pago
                on rol_pago.id_departamento=parametrizacion.id_departamento
                        INNER JOIN proyecto
                on rol_pago.id_proyecto=proyecto.id_proyecto
                where parametrizacion.id_departamento={$id} and rol_pago.cod_rol_pago={$request->cod} 
                        GROUP BY rol_pago.id_proyecto,parametrizacion.descripcion");
        $dec_tercero=DB::select("SELECT distinct sum(decimo_tercero) as suma from rol_pago
        WHERE cod_rol_pago={$request->cod} GROUP BY id_proyecto");
        $dec_cuarto=DB::select("SELECT distinct sum(decimo_cuarto) as suma from rol_pago
        WHERE cod_rol_pago={$request->cod} GROUP BY id_proyecto");
        $fondo_reserva=DB::select("SELECT distinct sum(fondo_reserva) as suma from rol_pago
        WHERE cod_rol_pago={$request->cod} GROUP BY id_proyecto");
        $iess=DB::select("SELECT distinct sum(iess) as suma from rol_pago
        WHERE cod_rol_pago={$request->cod} GROUP BY id_proyecto");
        $sueldo=DB::select("SELECT distinct sum(sueldo) as suma from rol_pago
        WHERE cod_rol_pago={$request->cod} GROUP BY id_proyecto");
        $rol=DB::select("SELECT * from rol_pago where cod_rol_pago={$request->cod}");
        $fecha_emision=substr($rol[0]->fechrol,0,-3);
        $anio_emision=substr($rol[0]->fechrol,0,4);
        $fecha_cierre=DB::select("SELECT * 
                                    from asientos 
                                        where id_empresa={$proyecto[0]->id_empresa} 
                                        and cierre_contable='Cierre Mes' and periodo='{$fecha_emision}'
                                UNION
                                SELECT * 
                                    from asientos 
                                        where id_empresa={$proyecto[0]->id_empresa} 
                                        and cierre_contable='Estado Contable' and periodo='{$anio_emision}'");
        $asiento="";
        if(count($fecha_cierre)>0){
            $asiento="no";
        }else{
            $asiento="si";
        }
        return [
            'debe'=>$debe,
            'asiento_permitido'=>$asiento,
            'haber'=>$haber,
            'cuentas'=>$parametrizacion,
            'cuentas_haber'=>$parametrizacion_haber,
            'id_proyecto'=>$proyecto[0]->id_proyecto
        ];
    }
    public function agregarAsiento(Request $request){
        RolPago::where('cod_rol_pago',$request->cod_rol)->update(['contabilidad'=>'1']);
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
        $asientos->id_asientos_comprobante=4;
        $asientos->save();
        return $asientos->id_asientos;
    }
    public function agregarAsientoDetalle(Request $request){
        //RolPago::where('cod_rol_pago',$request->cod_rol)->update(['contabilidad'=>'Si']);

            
            foreach($request->ingresos_debe as $ingresos_debe){
                if(count($request->ingresos_debe)>0){
                    $asiento=new Asientos_contables_detalle();
                    $asiento->proyecto=$ingresos_debe["descripcion"];
                    $asiento->debe=$ingresos_debe["valor_debe"];
                    $asiento->ucrea=$request->ucrea;
                    $asiento->id_plan_cuentas=$ingresos_debe["id_plan_cuentas"];
                    $asiento->id_asientos=$request->id_asientos;
                    $asiento->id_proyecto=$ingresos_debe["id_proyecto"];
                    $asiento->save();
                }
            }
            foreach($request->ingresos_haber as $ingresos_debe){
                if(count($request->ingresos_haber)>0){
                    $asiento=new Asientos_contables_detalle();
                    $asiento->proyecto=$ingresos_debe["descripcion"];
                    $asiento->haber=$ingresos_debe["valor_debe"];
                    $asiento->ucrea=$request->ucrea;
                    $asiento->id_plan_cuentas=$ingresos_debe["id_plan_cuentas"];
                    $asiento->id_asientos=$request->id_asientos;
                    $asiento->id_proyecto=$ingresos_debe["id_proyecto"];
                    $asiento->save();
                }
            }
            foreach($request->parametrizacion_debe as $ingresos_debe){
               
                    $asiento=new Asientos_contables_detalle();
                    
                    if($ingresos_debe["paramet"]=="Aporte Personal" && $ingresos_debe["iess"]>0){
                        $asiento->proyecto=$ingresos_debe["descripcion"];
                        $asiento->debe=$ingresos_debe["iess"];
                        $asiento->ucrea=$request->ucrea;
                        $asiento->id_plan_cuentas=$ingresos_debe["id_plan_cuentas1"];
                        $asiento->id_asientos=$request->id_asientos;
                        $asiento->id_proyecto=$ingresos_debe["id_proyecto"];
                    }else{
                        if($ingresos_debe["paramet"]=="Decimo Cuarto Mensual" && $ingresos_debe["decimo_cuarto"]>0){
                            $asiento->proyecto=$ingresos_debe["descripcion"];
                            $asiento->debe=$ingresos_debe["decimo_cuarto"];
                            $asiento->ucrea=$request->ucrea;
                            $asiento->id_plan_cuentas=$ingresos_debe["id_plan_cuentas1"];
                            $asiento->id_asientos=$request->id_asientos;
                            $asiento->id_proyecto=$ingresos_debe["id_proyecto"];
                        }else{
                            if($ingresos_debe["paramet"]=="Sueldo" && $ingresos_debe["sueldo"]>0){
                                $asiento->proyecto=$ingresos_debe["descripcion"];
                                $asiento->debe=$ingresos_debe["sueldo"];
                                $asiento->ucrea=$request->ucrea;
                                $asiento->id_plan_cuentas=$ingresos_debe["id_plan_cuentas1"];
                                $asiento->id_asientos=$request->id_asientos;
                                $asiento->id_proyecto=$ingresos_debe["id_proyecto"];
                            }else{
                                if($ingresos_debe["paramet"]=="Decimo Tercero Mensual" && $ingresos_debe["decimo_tercero"]>0){
                                    $asiento->proyecto=$ingresos_debe["descripcion"];
                                    $asiento->debe=$ingresos_debe["decimo_tercero"];
                                    $asiento->ucrea=$request->ucrea;
                                    $asiento->id_plan_cuentas=$ingresos_debe["id_plan_cuentas1"];
                                    $asiento->id_asientos=$request->id_asientos;
                                    $asiento->id_proyecto=$ingresos_debe["id_proyecto"];
                                }else{
                                    if($ingresos_debe["paramet"]=="Fondo Reserva Mensual" && $ingresos_debe["fondo_reserva"]>0){
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
                    if($ingresos_debe["parametr"]=="Aporte Personal" && $ingresos_debe["iess"]>0){
                        $asiento->proyecto=$ingresos_debe["descripcion"];
                        $asiento->haber=$ingresos_debe["iess"];
                        $asiento->ucrea=$request->ucrea;
                        $asiento->id_plan_cuentas=$ingresos_debe["id_plancuenta"];
                        $asiento->id_asientos=$request->id_asientos;
                        $asiento->id_proyecto=$ingresos_debe["id_proyecto"];
                    }else{
                        if($ingresos_debe["parametr"]=="Decimo Cuarto Mensual" && $ingresos_debe["decimo_cuarto"]>0){
                            $asiento->proyecto=$ingresos_debe["descripcion"];
                            $asiento->haber=$ingresos_debe["decimo_cuarto"];
                            $asiento->ucrea=$request->ucrea;
                            $asiento->id_plan_cuentas=$ingresos_debe["id_plancuenta"];
                            $asiento->id_asientos=$request->id_asientos;
                            $asiento->id_proyecto=$ingresos_debe["id_proyecto"];
                        }else{
                            if($ingresos_debe["parametr"]=="Sueldo" && $ingresos_debe["sueldo"]>0){
                                $asiento->proyecto=$ingresos_debe["descripcion"];
                                $asiento->haber=$ingresos_debe["sueldo"];
                                $asiento->ucrea=$request->ucrea;
                                $asiento->id_plan_cuentas=$ingresos_debe["id_plancuenta"];
                                $asiento->id_asientos=$request->id_asientos;
                                $asiento->id_proyecto=$ingresos_debe["id_proyecto"];
                            }else{
                                if($ingresos_debe["parametr"]=="Decimo Tercero Mensual" && $ingresos_debe["decimo_tercero"]>0){
                                    $asiento->proyecto=$ingresos_debe["descripcion"];
                                    $asiento->haber=$ingresos_debe["decimo_tercero"];
                                    $asiento->ucrea=$request->ucrea;
                                    $asiento->id_plan_cuentas=$ingresos_debe["id_plancuenta"];
                                    $asiento->id_asientos=$request->id_asientos;
                                    $asiento->id_proyecto=$ingresos_debe["id_proyecto"];
                                }else{
                                    if($ingresos_debe["parametr"]=="Fondo Reserva Mensual" && $ingresos_debe["fondo_reserva"]>0){
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
    public function EnviarCorreo(Request $request){
        
        
        $datos=DB::select("SELECT empresa.*,empleado.primer_nombre,empleado.apellido_paterno,empleado.email,rol_pago.fechrol,rol_pago.primer_nombre,rol_pago.id_departamento from empresa,empleado,rol_pago where rol_pago.id_empleado=empleado.id_empleado and empleado.id_empresa=empresa.id_empresa and empleado.estado=".'"Activo"'." and empresa.id_empresa=".$request->empresa." and rol_pago.fechrol=".'"'.$request->date.'"'." and rol_pago.id_departamento=".$request->id);
        //$rol=DB::select("SELECT primer_nombre,fechrol from rol_pago where fechrol=".'"'.$request->date.'"'." and id_departamento=".$request->id);
        for ($a = 0; $a < count($datos); $a++) {
            $email = new sendEmailRoles(); 
            $email->Papeleta_Individual($datos[$a]);
        }
        

    }
    public function PDFRolGeneral(Request $request){
        setlocale(LC_TIME, "spanish");
        $id=$request->id_empleado;
        $ingr1=$request->ing1;
        $ingr2=$request->ing2;
        $ingr3=$request->ing3;
        $ingr4=$request->ing4;
        $ingr5=$request->ing5;
        $ingr6=$request->ing6;
        $egr1=$request->egr1;
        $egr2=$request->egr2;
        $egr3=$request->egr3;
        $egr4=$request->egr4;
        $egr5=$request->egr5;
        $egr6=$request->egr6;
        $nombre_proyecto="";
        if($request->id_proyecto>0){
            $sel_proyecto=DB::select("SELECT * from proyecto where id_proyecto={$request->id_proyecto}");
            if(count($sel_proyecto)>0){
                $nombre_proyecto=$sel_proyecto[0]->descripcion;
            }
        }else{
            $nombre_proyecto="Todos";
        }


        $recupera2=DB::select("SELECT user.*,empresa.logo,empresa.id_empresa,empresa.nombre_contador,empresa.nomb_representante from user,empresa where user.id_empresa=empresa.id_empresa and user.id=".$request->id_user);
        if($request->id_proyecto>0){
            $recupera=DB::select("SELECT rol_pago.*,empresa.nombre_empresa,empleado.dni,departamento.dep_nombre,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing1." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'.")as id_ingreso1,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing2." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'.")as id_ingreso2,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing3." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'.")as id_ingreso3,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing4." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'.")as id_ingreso4,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing5." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'.")as id_ingreso5,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing6." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'.")as id_ingreso6,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr1." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'.")as id_egreso1,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr2." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'.")as id_egreso2,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr3." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'.")as id_egreso3,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr4." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'.")as id_egreso4,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr5." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'.")as id_egreso5,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr6." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'.")as id_egreso6
            FROM rol_pago,empresa,empleado,departamento where rol_pago.fechrol=".'"'.$request->date.'"'." and rol_pago.id_empresa=empresa.id_empresa and rol_pago.id_empleado=empleado.id_empleado and rol_pago.id_departamento=departamento.id_departamento and rol_pago.id_departamento=".$id." and rol_pago.id_proyecto={$request->id_proyecto}");
        }else{
            $recupera=DB::select("SELECT rol_pago.*,empresa.nombre_empresa,empleado.dni,departamento.dep_nombre,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing1." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'.")as id_ingreso1,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing2." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'.")as id_ingreso2,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing3." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'.")as id_ingreso3,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing4." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'.")as id_ingreso4,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing5." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'.")as id_ingreso5,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing6." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'.")as id_ingreso6,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr1." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'.")as id_egreso1,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr2." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'.")as id_egreso2,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr3." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'.")as id_egreso3,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr4." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'.")as id_egreso4,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr5." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'.")as id_egreso5,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr6." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'.")as id_egreso6
            FROM rol_pago,empresa,empleado,departamento where rol_pago.fechrol=".'"'.$request->date.'"'." and rol_pago.id_empresa=empresa.id_empresa and rol_pago.id_empleado=empleado.id_empleado and rol_pago.id_departamento=departamento.id_departamento and rol_pago.id_departamento=".$id);
        }
        $Reportes = new generarPDF();
        $fecha_papeleta=ucwords(strftime("%B %Y", strtotime($request->date)));
        $documento="Rol_Pago_General_".$fecha_papeleta.".pdf";
        if (!$recupera) {
            return response('no-data-report', 200)->header('Content-Type', 'application/json');
        } else {
            $carpetanombre = constant("DATA_EMPRESA").$recupera2[0]->id_empresa;
            $carpeta1 = $carpetanombre."/rol_general/".$fecha_papeleta."/".$id.'/'.$documento;
            $carpetanombre2 = constant("DATA_EMPRESA").$recupera2[0]->id_empresa;
            $carpeta2 = $carpetanombre2."/rol_general/".$fecha_papeleta."/".$id."/";
            if (!file_exists($carpeta2)) {
                mkdir($carpeta2, 0755,true);
            }
            if(file_exists($carpeta1)){
                unlink($carpeta1);
                $strPDF = $Reportes->PdfRolGeneral($recupera, $request->date,$recupera2,$nombre_proyecto);
            }
            if(!file_exists($carpeta1)){
                $strPDF = $Reportes->PdfRolGeneral($recupera, $request->date,$recupera2,$nombre_proyecto);
            }
        }
        
    }
    public function EnviarRolGeneralCorreo(Request $request){
        
        
        $datos=DB::select("SELECT empresa.*,empleado.primer_nombre,empleado.apellido_paterno,empleado.email,rol_pago.fechrol,rol_pago.primer_nombre,rol_pago.id_departamento from empresa,empleado,rol_pago where rol_pago.id_empleado=empleado.id_empleado and empleado.id_empresa=empresa.id_empresa and empleado.estado=".'"Activo"'." and empresa.id_empresa=".$request->empresa." and rol_pago.fechrol=".'"'.$request->date.'"'." and rol_pago.id_departamento=".$request->id);
        //$rol=DB::select("SELECT primer_nombre,fechrol from rol_pago where fechrol=".'"'.$request->date.'"'." and id_departamento=".$request->id);
        //for ($a = 0; $a < count($datos); $a++) {
            $email = new sendEmailRolGeneral(); 
            $email->Rol_General($datos[0],$request->email);
        //}

    }
    
    public function guardarEgreso(Request $request){
        for ($a = 0; $a < count($request->productos); $a++) {
            if($request->productos[$a]["id_rol_pago"]!=null){
            //$dfactc =RolPago::find($request->productos[$a]["id_rol_pago"]);
            /*if($request->nrocolum=="1"){
                $dfactc->primer_nombre = $request->productos[$a]["primer_nombre"];
                $dfactc->cantidad = $request->productos[$a]["cantidad"];
                $dfactc->egreso1 = $request->productos[$a]["egreso1"];
                $dfactc->iess = $request->productos[$a]["total_ingreso"]*9.45/100;
                $dfactc->total_egreso = $request->productos[$a]["total_ingreso"]*9.45/100+
                                        $request->productos[$a]["egreso1"];
                $dfactc->valor_recibir =$request->productos[$a]["total_ingreso"]-
                                        ($request->productos[$a]["total_ingreso"]*9.45/100+
                                        $request->productos[$a]["egreso1"]);
                $dfactc->id_departamento = $request->id_departamento;
                $dfactc->save();
            }
            if($request->nrocolum=="2"){
                $dfactc->primer_nombre = $request->productos[$a]["primer_nombre"];
                $dfactc->cantidad = $request->productos[$a]["cantidad"];
                $dfactc->egreso1 = $request->productos[$a]["egreso1"];
                $dfactc->egreso2 = $request->productos[$a]["egreso2"];
                $dfactc->iess = $request->productos[$a]["total_ingreso"]*9.45/100;
                $dfactc->total_egreso = $request->productos[$a]["total_ingreso"]*9.45/100+
                                        $request->productos[$a]["egreso1"]+
                                        $request->productos[$a]["egreso2"];
                $dfactc->valor_recibir =$request->productos[$a]["total_ingreso"]-
                                        ($request->productos[$a]["total_ingreso"]*9.45/100+
                                        $request->productos[$a]["egreso1"]+
                                        $request->productos[$a]["egreso2"]);
                $dfactc->id_departamento = $request->id_departamento;
                $dfactc->save();
            }
            if($request->nrocolum=="3"){
                $dfactc->primer_nombre = $request->productos[$a]["primer_nombre"];
                $dfactc->cantidad = $request->productos[$a]["cantidad"];
                $dfactc->egreso1 = $request->productos[$a]["egreso1"];
                $dfactc->egreso2 = $request->productos[$a]["egreso2"];
                $dfactc->egreso3 = $request->productos[$a]["egreso3"];
                $dfactc->iess = $request->productos[$a]["total_ingreso"]*9.45/100;
                $dfactc->total_egreso =$request->productos[$a]["total_ingreso"]*9.45/100+
                                        $request->productos[$a]["egreso1"]+
                                        $request->productos[$a]["egreso2"]+
                                        $request->productos[$a]["egreso3"];
                $dfactc->valor_recibir =$request->productos[$a]["total_ingreso"]-
                                        ($request->productos[$a]["total_ingreso"]*9.45/100+
                                        $request->productos[$a]["egreso1"]+
                                        $request->productos[$a]["egreso2"]+
                                        $request->productos[$a]["egreso3"]);
                $dfactc->id_departamento = $request->id_departamento;
                $dfactc->save();
            }
            if($request->nrocolum=="4"){
                $dfactc->primer_nombre = $request->productos[$a]["primer_nombre"];
                $dfactc->cantidad = $request->productos[$a]["cantidad"];
                $dfactc->egreso1 = $request->productos[$a]["egreso1"];
                $dfactc->egreso2 = $request->productos[$a]["egreso2"];
                $dfactc->egreso3 = $request->productos[$a]["egreso3"];
                $dfactc->egreso4 = $request->productos[$a]["egreso4"];
                $dfactc->iess = $request->productos[$a]["total_ingreso"]*9.45/100;
                $dfactc->total_egreso =$request->productos[$a]["total_ingreso"]*9.45/100+
                                        $request->productos[$a]["egreso1"]+
                                        $request->productos[$a]["egreso2"]+
                                        $request->productos[$a]["egreso3"]+
                                        $request->productos[$a]["egreso4"];
                $dfactc->valor_recibir =$request->productos[$a]["total_ingreso"]-
                                        ($request->productos[$a]["total_ingreso"]*9.45/100+
                                        $request->productos[$a]["egreso1"]+
                                        $request->productos[$a]["egreso2"]+
                                        $request->productos[$a]["egreso3"]+
                                        $request->productos[$a]["egreso4"]);
                $dfactc->id_departamento = $request->id_departamento;
                $dfactc->save();
            }
            if($request->nrocolum=="5"){
                $dfactc->primer_nombre = $request->productos[$a]["primer_nombre"];
                $dfactc->cantidad = $request->productos[$a]["cantidad"];
                $dfactc->egreso1 = $request->productos[$a]["egreso1"];
                $dfactc->egreso2 = $request->productos[$a]["egreso2"];
                $dfactc->egreso3 = $request->productos[$a]["egreso3"];
                $dfactc->egreso4 = $request->productos[$a]["egreso4"];
                $dfactc->egreso5 = $request->productos[$a]["egreso5"];
                $dfactc->iess = $request->productos[$a]["total_ingreso"]*9.45/100;
                $dfactc->total_egreso =$request->productos[$a]["total_ingreso"]*9.45/100+
                                        $request->productos[$a]["egreso1"]+
                                        $request->productos[$a]["egreso2"]+
                                        $request->productos[$a]["egreso3"]+
                                        $request->productos[$a]["egreso4"]+
                                        $request->productos[$a]["egreso5"];
                $dfactc->valor_recibir =$request->productos[$a]["total_ingreso"]-
                                        ($request->productos[$a]["total_ingreso"]*9.45/100+
                                        $request->productos[$a]["egreso1"]+
                                        $request->productos[$a]["egreso2"]+
                                        $request->productos[$a]["egreso3"]+
                                        $request->productos[$a]["egreso4"]+
                                        $request->productos[$a]["egreso5"]);
                $dfactc->id_departamento = $request->id_departamento;
                $dfactc->save();
            }*/
            //if($request->nrocolum=="6"){
                $dfactc->primer_nombre = $request->productos[$a]["primer_nombre"];
                $dfactc->cantidad = $request->productos[$a]["cantidad"];
                $dfactc->sueldo   = $request->productos[$a]["sueldo"];
                $dfactc->ingreso1 = $request->productos[$a]["ingreso1"];
                $dfactc->ingreso2 = $request->productos[$a]["ingreso2"];
                $dfactc->ingreso3 = $request->productos[$a]["ingreso3"];
                $dfactc->ingreso4 = $request->productos[$a]["ingreso4"];
                $dfactc->ingreso5 = $request->productos[$a]["ingreso5"];
                $dfactc->ingreso6 = $request->productos[$a]["ingreso6"];
                $dfactc->total_ingreso =$request->productos[$a]["sueldo"]+
                                        $request->productos[$a]["ingreso1"]+
                                        $request->productos[$a]["ingreso2"]+
                                        $request->productos[$a]["ingreso3"]+
                                        $request->productos[$a]["ingreso4"]+
                                        $request->productos[$a]["ingreso5"]+
                                        $request->productos[$a]["ingreso6"];
                
                $dfactc->id_departamento = $request->id_departamento;
                $dfactc->save();
            //}
        }
            
        }
        return $dfactc; 
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function abrirIngresos($id)
    {
        $recupera = DB::select('SELECT rol_pago.*,departamento.dep_nombre as departamento FROM rol_pago,departamento where rol_pago.id_departamento=departamento.id_departamento and rol_pago.cod_rol_pago='.$id);
        return $recupera;
    }
    public function abrirIngresosEditar($id)
    {
        
        $recupera = DB::select('SELECT rol_pago.*,empleado.sueldo as sueldo_empleado,departamento.dep_nombre as departamento FROM rol_pago,departamento,empleado where rol_pago.id_departamento=departamento.id_departamento and rol_pago.id_empleado=empleado.id_empleado and rol_pago.cod_rol_pago='.$id);
        return $recupera;
    }
    public function abrirEgresos($id)
    {
        $recupera = DB::select('SELECT * FROM rol_pago where id_departamento='.$id);
        return $recupera;
    }
    public function abrirDepartamento($id)
    {
        $recupera = DB::select('SELECT departamento.* FROM departamento,rol_pago where rol_pago.id_departamento=departamento.id_departamento and rol_pago.cod_rol_pago='.$id.' order by departamento.id_departamento desc limit 1');
        return $recupera;
    }
    public function abrirArea($id)
    {
        $recupera = DB::select('SELECT distinct car_sueldo as sueldo from cargo,area_trabajo,departamento
        where area_trabajo.id_area=cargo.id_area and area_trabajo.id_departamento='.$id);
        return $recupera;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        for ($a = 0; $a < count($request->productos); $a++) {
            $dfactc =RolPago::find($request->productos[$a]["id_rol_pago"]);
            $dfactc->primer_nombre = $request->productos[$a]["primer_nombre"];
            //$dfactc->primer_nombre = $request->productos[$a]["primer_nombre"]." ".$request->productos[$a]["apellido_paterno"];
            //$dfactc->cod_rol_pago = $codigo;
            $dfactc->cantidad = $request->productos[$a]["cantidad"];
                    $dfactc->sueldo   = $request->productos[$a]["sueldo"]/30*$request->productos[$a]["cantidad"];
                    $dfactc->ingreso1 = $request->productos[$a]["ingreso1"];
                    $dfactc->ingreso2 = $request->productos[$a]["ingreso2"];
                    $dfactc->ingreso3 = $request->productos[$a]["ingreso3"];
                    $dfactc->ingreso4 = $request->productos[$a]["ingreso4"];
                    $dfactc->ingreso5 = $request->productos[$a]["ingreso5"];
                    $dfactc->ingreso6 = $request->productos[$a]["ingreso6"];
                    $dfactc->valor_ingreso1 = $request->productos[$a]["valor_ingreso1"];
                    $dfactc->valor_ingreso2 = $request->productos[$a]["valor_ingreso2"];
                    $dfactc->valor_ingreso3 = $request->productos[$a]["valor_ingreso3"];
                    $dfactc->valor_ingreso4 = $request->productos[$a]["valor_ingreso4"];
                    $dfactc->valor_ingreso5 = $request->productos[$a]["valor_ingreso5"];
                    $dfactc->valor_ingreso6 = $request->productos[$a]["valor_ingreso6"];
                    $dfactc->total_ingreso =($request->productos[$a]["sueldo"]/30*$request->productos[$a]["cantidad"])+
                                            $request->productos[$a]["ingreso1"]+
                                            $request->productos[$a]["ingreso2"]+
                                            $request->productos[$a]["ingreso3"]+
                                            $request->productos[$a]["ingreso4"]+
                                            $request->productos[$a]["ingreso5"]+
                                            $request->productos[$a]["ingreso6"]+
                                            $request->productos[$a]["decimo_tercero"]+
                                            $request->productos[$a]["decimo_cuarto"]+
                                            $request->productos[$a]["fondo_reserva"];
                    $dfactc->egreso1 = $request->productos[$a]["egreso1"];
                    $dfactc->egreso2 = $request->productos[$a]["egreso2"];
                    $dfactc->egreso3 = $request->productos[$a]["egreso3"];
                    $dfactc->egreso4 = $request->productos[$a]["egreso4"];
                    $dfactc->egreso5 = $request->productos[$a]["egreso5"];
                    $dfactc->egreso6 = $request->productos[$a]["egreso6"];
                    $dfactc->iess = (($request->productos[$a]["sueldo"]/30*$request->productos[$a]["cantidad"])+
                                    $request->productos[$a]["valor_ingreso1"]+
                                    $request->productos[$a]["valor_ingreso2"]+
                                    $request->productos[$a]["valor_ingreso3"]+
                                    $request->productos[$a]["valor_ingreso4"]+
                                    $request->productos[$a]["valor_ingreso5"]+
                                    $request->productos[$a]["valor_ingreso6"])*9.45/100;
                    $dfactc->total_egreso =(($request->productos[$a]["sueldo"]/30*$request->productos[$a]["cantidad"])+
                                                $request->productos[$a]["valor_ingreso1"]+
                                                $request->productos[$a]["valor_ingreso2"]+
                                                $request->productos[$a]["valor_ingreso3"]+
                                                $request->productos[$a]["valor_ingreso4"]+
                                                $request->productos[$a]["valor_ingreso5"]+
                                                $request->productos[$a]["valor_ingreso6"])*9.45/100+
                                                $request->productos[$a]["egreso1"]+
                                                $request->productos[$a]["egreso2"]+
                                                $request->productos[$a]["egreso3"]+
                                                $request->productos[$a]["egreso4"]+
                                                $request->productos[$a]["egreso5"]+
                                                $request->productos[$a]["egreso6"];
                    $dfactc->valor_recibir =(($request->productos[$a]["sueldo"]/30*$request->productos[$a]["cantidad"])+
                                            $request->productos[$a]["ingreso1"]+
                                            $request->productos[$a]["ingreso2"]+
                                            $request->productos[$a]["ingreso3"]+
                                            $request->productos[$a]["ingreso4"]+
                                            $request->productos[$a]["ingreso5"]+
                                            $request->productos[$a]["ingreso6"]+
                                            $request->productos[$a]["decimo_tercero"]+
                                            $request->productos[$a]["decimo_cuarto"]+
                                            $request->productos[$a]["fondo_reserva"])-
                                                ((($request->productos[$a]["sueldo"]/30*$request->productos[$a]["cantidad"])+
                                                $request->productos[$a]["valor_ingreso1"]+
                                                $request->productos[$a]["valor_ingreso2"]+
                                                $request->productos[$a]["valor_ingreso3"]+
                                                $request->productos[$a]["valor_ingreso4"]+
                                                $request->productos[$a]["valor_ingreso5"]+
                                                $request->productos[$a]["valor_ingreso6"])*9.45/100+
                                                    $request->productos[$a]["egreso1"]+
                                                    $request->productos[$a]["egreso2"]+
                                                    $request->productos[$a]["egreso3"]+
                                                    $request->productos[$a]["egreso4"]+
                                                    $request->productos[$a]["egreso5"]+
                                                    $request->productos[$a]["egreso6"]);
                    $dfactc->fechrol = $request->fechrol;
                    $dfactc->decimo_tercero = $request->productos[$a]["decimo_tercero"];
                    $dfactc->fondo_reserva = $request->productos[$a]["fondo_reserva"];
                    $dfactc->decimo_cuarto = $request->productos[$a]["decimo_cuarto"];
                    //proyecto
                    $dfactc->umodifica= $request->umodifica;
                    $dfactc->id_proyecto = $request->productos[$a]["id_proyecto"];
                    $dfactc->id_departamento = $request->id_departamento;
                    $dfactc->id_empleado = $request->productos[$a]["id_empleado"];
                    
                $dfactc->save();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        RolPago::where('cod_rol_pago','=',$id)->delete();

    }
    public function getEmpleados(Request $request)
    {
        $id=$request->id_empleado;
        $ingr1=$request->ing1;
        $ingr2=$request->ing2;
        $ingr3=$request->ing3;
        $ingr4=$request->ing4;
        $ingr5=$request->ing5;
        $ingr6=$request->ing6;
        $egr1=$request->egr1;
        $egr2=$request->egr2;
        $egr3=$request->egr3;
        $egr4=$request->egr4;
        $egr5=$request->egr5;
        $egr6=$request->egr6;
        $valor_dc_cuarto=0;

        $mes_asignar=substr($request->fecha_asignar,0,7);

        $recuperaDC=DB::select('SELECT if(valor_decimo_cuarto is null,0,valor_decimo_cuarto) as valor from parametrizacion where id_departamento="'.$id.'" and descripcion="Decimo Cuarto Mensual"');
        if($recuperaDC){
            $valor_dc_cuarto=$recuperaDC[0]->valor;
        }else{
            $valor_dc_cuarto=0;
        }
        $query='SELECT empleado.id_empleado,empleado.sueldo,empleado.estado,empleado.fecha_ingreso,DATE_ADD(empleado.fecha_ingreso,INTERVAL 1 YEAR)as fecha_validez_param,if( now()>=DATE_ADD(empleado.fecha_ingreso,INTERVAL 1 YEAR),truncate(sueldo/12,2),0) as sueldo_validez_param,if(empleado.decimo_tercero="Si",truncate(sueldo/12,2),0) as decimo_tercero,if(empleado.fondo_reserva="Si" and now()>=DATE_ADD(empleado.fecha_ingreso,INTERVAL 1 YEAR),truncate(sueldo/12,2),0) as fondo_reserva,if(empleado.decimo_cuarto="Si",'.$valor_dc_cuarto.',0) as decimo_cuarto,if(empleado.decimo_cuarto="Si",'.$valor_dc_cuarto.',0) as decimo_cuarto_base,empleado.primer_nombre,empleado.apellido_paterno,30 as cantidad,
        (select proyecto.id_proyecto  from proyecto where proyecto.id_empresa='.$request->empresa.' ORDER BY proyecto.id_proyecto asc limit 1) as id_proyecto,
        (select asignar_ingresos.valor from asignar_ingresos where  asignar_ingresos.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos.id_ineg="'.$ingr1.'" and asignar_ingresos.id_empleado=empleado.id_empleado and tiene_detalle=0 UNION select asignar_ingresos_detalle.valor from asignar_ingresos_detalle where  asignar_ingresos_detalle.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos_detalle.id_ingreso_egreso="'.$ingr1.'" and asignar_ingresos_detalle.id_empleado=empleado.id_empleado) as ingreso1,
        (select if(ingresos_egresos.iess=1,asignar_ingresos.valor,0) from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg="'.$ingr1.'" and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.fecha_asignar="'.$request->fecha_asignar.'" and  asignar_ingresos.id_empleado=empleado.id_empleado and tiene_detalle=0 UNION select if(ingresos_egresos.iess=1,asignar_ingresos_detalle.valor,0) from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso="'.$ingr1.'" and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.fecha_asignar="'.$request->fecha_asignar.'" and  asignar_ingresos_detalle.id_empleado=empleado.id_empleado) as valor_ingreso1,
        (select asignar_ingresos.valor from asignar_ingresos where  asignar_ingresos.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos.id_ineg="'.$ingr2.'" and asignar_ingresos.id_empleado=empleado.id_empleado and tiene_detalle=0 UNION select asignar_ingresos_detalle.valor from asignar_ingresos_detalle where  asignar_ingresos_detalle.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos_detalle.id_ingreso_egreso="'.$ingr2.'" and asignar_ingresos_detalle.id_empleado=empleado.id_empleado) as ingreso2,
        (select if(ingresos_egresos.iess=1,asignar_ingresos.valor,0) from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg="'.$ingr2.'" and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos.id_empleado=empleado.id_empleado and tiene_detalle=0 UNION select if(ingresos_egresos.iess=1,asignar_ingresos_detalle.valor,0) from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso="'.$ingr2.'" and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.fecha_asignar="'.$request->fecha_asignar.'" and  asignar_ingresos_detalle.id_empleado=empleado.id_empleado) as valor_ingreso2,
        (select asignar_ingresos.valor from asignar_ingresos where  asignar_ingresos.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos.id_ineg="'.$ingr3.'" and asignar_ingresos.id_empleado=empleado.id_empleado and tiene_detalle=0 UNION select asignar_ingresos_detalle.valor from asignar_ingresos_detalle where  asignar_ingresos_detalle.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos_detalle.id_ingreso_egreso="'.$ingr3.'" and asignar_ingresos_detalle.id_empleado=empleado.id_empleado) as ingreso3,
        (select if(ingresos_egresos.iess=1,asignar_ingresos.valor,0) from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg="'.$ingr3.'" and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos.id_empleado=empleado.id_empleado and tiene_detalle=0 UNION select if(ingresos_egresos.iess=1,asignar_ingresos_detalle.valor,0) from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso="'.$ingr3.'" and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.fecha_asignar="'.$request->fecha_asignar.'" and  asignar_ingresos_detalle.id_empleado=empleado.id_empleado) as valor_ingreso3,
        (select asignar_ingresos.valor from asignar_ingresos where  asignar_ingresos.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos.id_ineg="'.$ingr4.'" and asignar_ingresos.id_empleado=empleado.id_empleado and tiene_detalle=0 UNION select asignar_ingresos_detalle.valor from asignar_ingresos_detalle where  asignar_ingresos_detalle.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos_detalle.id_ingreso_egreso="'.$ingr4.'" and asignar_ingresos_detalle.id_empleado=empleado.id_empleado) as ingreso4,
        (select if(ingresos_egresos.iess=1,asignar_ingresos.valor,0) from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg="'.$ingr4.'" and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos.id_empleado=empleado.id_empleado and tiene_detalle=0 UNION select if(ingresos_egresos.iess=1,asignar_ingresos_detalle.valor,0) from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso="'.$ingr4.'" and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.fecha_asignar="'.$request->fecha_asignar.'" and  asignar_ingresos_detalle.id_empleado=empleado.id_empleado) as valor_ingreso4,
        (select asignar_ingresos.valor from asignar_ingresos where  asignar_ingresos.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos.id_ineg="'.$ingr5.'" and asignar_ingresos.id_empleado=empleado.id_empleado and tiene_detalle=0 UNION select asignar_ingresos_detalle.valor from asignar_ingresos_detalle where  asignar_ingresos_detalle.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos_detalle.id_ingreso_egreso="'.$ingr5.'" and asignar_ingresos_detalle.id_empleado=empleado.id_empleado) as ingreso5,
        (select if(ingresos_egresos.iess=1,asignar_ingresos.valor,0) from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg="'.$ingr5.'" and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos.id_empleado=empleado.id_empleado and tiene_detalle=0 UNION select if(ingresos_egresos.iess=1,asignar_ingresos_detalle.valor,0) from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso="'.$ingr5.'" and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.fecha_asignar="'.$request->fecha_asignar.'" and  asignar_ingresos_detalle.id_empleado=empleado.id_empleado) as valor_ingreso5,
        (select asignar_ingresos.valor from asignar_ingresos where  asignar_ingresos.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos.id_ineg="'.$ingr6.'" and asignar_ingresos.id_empleado=empleado.id_empleado and tiene_detalle=0 UNION select asignar_ingresos_detalle.valor from asignar_ingresos_detalle where  asignar_ingresos_detalle.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos_detalle.id_ingreso_egreso="'.$ingr6.'" and asignar_ingresos_detalle.id_empleado=empleado.id_empleado) as ingreso6,
        (select if(ingresos_egresos.iess=1,asignar_ingresos.valor,0) from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg="'.$ingr6.'" and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos.id_empleado=empleado.id_empleado and tiene_detalle=0 UNION select if(ingresos_egresos.iess=1,asignar_ingresos_detalle.valor,0) from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso="'.$ingr6.'" and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.fecha_asignar="'.$request->fecha_asignar.'" and  asignar_ingresos_detalle.id_empleado=empleado.id_empleado) as valor_ingreso6,
        (select asignar_ingresos.valor from asignar_ingresos where  asignar_ingresos.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos.id_ineg="'.$egr1.'" and asignar_ingresos.id_empleado=empleado.id_empleado and tiene_detalle=0 UNION select asignar_ingresos_detalle.valor from asignar_ingresos_detalle where  asignar_ingresos_detalle.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos_detalle.id_ingreso_egreso="'.$egr1.'" and asignar_ingresos_detalle.id_empleado=empleado.id_empleado) as egreso1,
        (select asignar_ingresos.valor from asignar_ingresos where  asignar_ingresos.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos.id_ineg="'.$egr2.'" and asignar_ingresos.id_empleado=empleado.id_empleado and tiene_detalle=0 UNION select asignar_ingresos_detalle.valor from asignar_ingresos_detalle where  asignar_ingresos_detalle.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos_detalle.id_ingreso_egreso="'.$egr2.'" and asignar_ingresos_detalle.id_empleado=empleado.id_empleado) as egreso2,
        (select asignar_ingresos.valor from asignar_ingresos where  asignar_ingresos.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos.id_ineg="'.$egr3.'" and asignar_ingresos.id_empleado=empleado.id_empleado and tiene_detalle=0 UNION select asignar_ingresos_detalle.valor from asignar_ingresos_detalle where  asignar_ingresos_detalle.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos_detalle.id_ingreso_egreso="'.$egr3.'" and asignar_ingresos_detalle.id_empleado=empleado.id_empleado) as egreso3,
        (select asignar_ingresos.valor from asignar_ingresos where  asignar_ingresos.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos.id_ineg="'.$egr4.'" and asignar_ingresos.id_empleado=empleado.id_empleado and tiene_detalle=0 UNION select asignar_ingresos_detalle.valor from asignar_ingresos_detalle where  asignar_ingresos_detalle.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos_detalle.id_ingreso_egreso="'.$egr4.'" and asignar_ingresos_detalle.id_empleado=empleado.id_empleado) as egreso4,
        (select asignar_ingresos.valor from asignar_ingresos where  asignar_ingresos.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos.id_ineg="'.$egr5.'" and asignar_ingresos.id_empleado=empleado.id_empleado and tiene_detalle=0 UNION select asignar_ingresos_detalle.valor from asignar_ingresos_detalle where  asignar_ingresos_detalle.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos_detalle.id_ingreso_egreso="'.$egr5.'" and asignar_ingresos_detalle.id_empleado=empleado.id_empleado) as egreso5,
        (select asignar_ingresos.valor from asignar_ingresos where  asignar_ingresos.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos.id_ineg="'.$egr6.'" and asignar_ingresos.id_empleado=empleado.id_empleado and tiene_detalle=0 UNION select asignar_ingresos_detalle.valor from asignar_ingresos_detalle where  asignar_ingresos_detalle.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos_detalle.id_ingreso_egreso="'.$egr6.'" and asignar_ingresos_detalle.id_empleado=empleado.id_empleado) as egreso6

         from empleado  WHERE empleado.estado='.'"Activo"'.' and empleado.id_departamento='.$id." and '{$request->fecha_asignar}'>=empleado.fecha_ingreso and '{$mes_asignar}-01'<=if(empleado.fecha_salida is not null,empleado.fecha_salida,'2030-01-01')
         Order By empleado.primer_nombre asc";
        //dd($query);
        $recupera = DB::select('SELECT empleado.id_empleado,empleado.sueldo,empleado.estado,empleado.fecha_ingreso,DATE_ADD(empleado.fecha_ingreso,INTERVAL 1 YEAR)as fecha_validez_param,if( now()>=DATE_ADD(empleado.fecha_ingreso,INTERVAL 1 YEAR),truncate(sueldo/12,2),0) as sueldo_validez_param,if(empleado.decimo_tercero="Si",truncate(sueldo/12,2),0) as decimo_tercero,if(empleado.fondo_reserva="Si" and now()>=DATE_ADD(empleado.fecha_ingreso,INTERVAL 1 YEAR),truncate(sueldo/12,2),0) as fondo_reserva,if(empleado.decimo_cuarto="Si",'.$valor_dc_cuarto.',0) as decimo_cuarto,if(empleado.decimo_cuarto="Si",'.$valor_dc_cuarto.',0) as decimo_cuarto_base,empleado.primer_nombre,empleado.apellido_paterno,30 as cantidad,
        (select proyecto.id_proyecto  from proyecto where proyecto.id_empresa='.$request->empresa.' ORDER BY proyecto.id_proyecto asc limit 1) as id_proyecto,
        (select asignar_ingresos.valor from asignar_ingresos where  asignar_ingresos.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos.id_ineg="'.$ingr1.'" and asignar_ingresos.id_empleado=empleado.id_empleado and tiene_detalle=0 UNION select asignar_ingresos_detalle.valor from asignar_ingresos_detalle where  asignar_ingresos_detalle.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos_detalle.id_ingreso_egreso="'.$ingr1.'" and asignar_ingresos_detalle.id_empleado=empleado.id_empleado) as ingreso1,
        (select if(ingresos_egresos.iess=1,asignar_ingresos.valor,0) from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg="'.$ingr1.'" and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.fecha_asignar="'.$request->fecha_asignar.'" and  asignar_ingresos.id_empleado=empleado.id_empleado and tiene_detalle=0 UNION select if(ingresos_egresos.iess=1,asignar_ingresos_detalle.valor,0) from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso="'.$ingr1.'" and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.fecha_asignar="'.$request->fecha_asignar.'" and  asignar_ingresos_detalle.id_empleado=empleado.id_empleado) as valor_ingreso1,
        (select asignar_ingresos.valor from asignar_ingresos where  asignar_ingresos.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos.id_ineg="'.$ingr2.'" and asignar_ingresos.id_empleado=empleado.id_empleado and tiene_detalle=0 UNION select asignar_ingresos_detalle.valor from asignar_ingresos_detalle where  asignar_ingresos_detalle.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos_detalle.id_ingreso_egreso="'.$ingr2.'" and asignar_ingresos_detalle.id_empleado=empleado.id_empleado) as ingreso2,
        (select if(ingresos_egresos.iess=1,asignar_ingresos.valor,0) from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg="'.$ingr2.'" and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos.id_empleado=empleado.id_empleado and tiene_detalle=0 UNION select if(ingresos_egresos.iess=1,asignar_ingresos_detalle.valor,0) from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso="'.$ingr2.'" and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.fecha_asignar="'.$request->fecha_asignar.'" and  asignar_ingresos_detalle.id_empleado=empleado.id_empleado) as valor_ingreso2,
        (select asignar_ingresos.valor from asignar_ingresos where  asignar_ingresos.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos.id_ineg="'.$ingr3.'" and asignar_ingresos.id_empleado=empleado.id_empleado and tiene_detalle=0 UNION select asignar_ingresos_detalle.valor from asignar_ingresos_detalle where  asignar_ingresos_detalle.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos_detalle.id_ingreso_egreso="'.$ingr3.'" and asignar_ingresos_detalle.id_empleado=empleado.id_empleado) as ingreso3,
        (select if(ingresos_egresos.iess=1,asignar_ingresos.valor,0) from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg="'.$ingr3.'" and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos.id_empleado=empleado.id_empleado and tiene_detalle=0 UNION select if(ingresos_egresos.iess=1,asignar_ingresos_detalle.valor,0) from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso="'.$ingr3.'" and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.fecha_asignar="'.$request->fecha_asignar.'" and  asignar_ingresos_detalle.id_empleado=empleado.id_empleado) as valor_ingreso3,
        (select asignar_ingresos.valor from asignar_ingresos where  asignar_ingresos.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos.id_ineg="'.$ingr4.'" and asignar_ingresos.id_empleado=empleado.id_empleado and tiene_detalle=0 UNION select asignar_ingresos_detalle.valor from asignar_ingresos_detalle where  asignar_ingresos_detalle.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos_detalle.id_ingreso_egreso="'.$ingr4.'" and asignar_ingresos_detalle.id_empleado=empleado.id_empleado) as ingreso4,
        (select if(ingresos_egresos.iess=1,asignar_ingresos.valor,0) from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg="'.$ingr4.'" and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos.id_empleado=empleado.id_empleado and tiene_detalle=0 UNION select if(ingresos_egresos.iess=1,asignar_ingresos_detalle.valor,0) from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso="'.$ingr4.'" and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.fecha_asignar="'.$request->fecha_asignar.'" and  asignar_ingresos_detalle.id_empleado=empleado.id_empleado) as valor_ingreso4,
        (select asignar_ingresos.valor from asignar_ingresos where  asignar_ingresos.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos.id_ineg="'.$ingr5.'" and asignar_ingresos.id_empleado=empleado.id_empleado and tiene_detalle=0 UNION select asignar_ingresos_detalle.valor from asignar_ingresos_detalle where  asignar_ingresos_detalle.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos_detalle.id_ingreso_egreso="'.$ingr5.'" and asignar_ingresos_detalle.id_empleado=empleado.id_empleado) as ingreso5,
        (select if(ingresos_egresos.iess=1,asignar_ingresos.valor,0) from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg="'.$ingr5.'" and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos.id_empleado=empleado.id_empleado and tiene_detalle=0 UNION select if(ingresos_egresos.iess=1,asignar_ingresos_detalle.valor,0) from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso="'.$ingr5.'" and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.fecha_asignar="'.$request->fecha_asignar.'" and  asignar_ingresos_detalle.id_empleado=empleado.id_empleado) as valor_ingreso5,
        (select asignar_ingresos.valor from asignar_ingresos where  asignar_ingresos.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos.id_ineg="'.$ingr6.'" and asignar_ingresos.id_empleado=empleado.id_empleado and tiene_detalle=0 UNION select asignar_ingresos_detalle.valor from asignar_ingresos_detalle where  asignar_ingresos_detalle.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos_detalle.id_ingreso_egreso="'.$ingr6.'" and asignar_ingresos_detalle.id_empleado=empleado.id_empleado) as ingreso6,
        (select if(ingresos_egresos.iess=1,asignar_ingresos.valor,0) from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg="'.$ingr6.'" and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos.id_empleado=empleado.id_empleado and tiene_detalle=0 UNION select if(ingresos_egresos.iess=1,asignar_ingresos_detalle.valor,0) from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso="'.$ingr6.'" and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.fecha_asignar="'.$request->fecha_asignar.'" and  asignar_ingresos_detalle.id_empleado=empleado.id_empleado) as valor_ingreso6,
        (select asignar_ingresos.valor from asignar_ingresos where  asignar_ingresos.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos.id_ineg="'.$egr1.'" and asignar_ingresos.id_empleado=empleado.id_empleado and tiene_detalle=0 UNION select asignar_ingresos_detalle.valor from asignar_ingresos_detalle where  asignar_ingresos_detalle.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos_detalle.id_ingreso_egreso="'.$egr1.'" and asignar_ingresos_detalle.id_empleado=empleado.id_empleado) as egreso1,
        (select asignar_ingresos.valor from asignar_ingresos where  asignar_ingresos.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos.id_ineg="'.$egr2.'" and asignar_ingresos.id_empleado=empleado.id_empleado and tiene_detalle=0 UNION select asignar_ingresos_detalle.valor from asignar_ingresos_detalle where  asignar_ingresos_detalle.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos_detalle.id_ingreso_egreso="'.$egr2.'" and asignar_ingresos_detalle.id_empleado=empleado.id_empleado) as egreso2,
        (select asignar_ingresos.valor from asignar_ingresos where  asignar_ingresos.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos.id_ineg="'.$egr3.'" and asignar_ingresos.id_empleado=empleado.id_empleado and tiene_detalle=0 UNION select asignar_ingresos_detalle.valor from asignar_ingresos_detalle where  asignar_ingresos_detalle.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos_detalle.id_ingreso_egreso="'.$egr3.'" and asignar_ingresos_detalle.id_empleado=empleado.id_empleado) as egreso3,
        (select asignar_ingresos.valor from asignar_ingresos where  asignar_ingresos.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos.id_ineg="'.$egr4.'" and asignar_ingresos.id_empleado=empleado.id_empleado and tiene_detalle=0 UNION select asignar_ingresos_detalle.valor from asignar_ingresos_detalle where  asignar_ingresos_detalle.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos_detalle.id_ingreso_egreso="'.$egr4.'" and asignar_ingresos_detalle.id_empleado=empleado.id_empleado) as egreso4,
        (select asignar_ingresos.valor from asignar_ingresos where  asignar_ingresos.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos.id_ineg="'.$egr5.'" and asignar_ingresos.id_empleado=empleado.id_empleado and tiene_detalle=0 UNION select asignar_ingresos_detalle.valor from asignar_ingresos_detalle where  asignar_ingresos_detalle.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos_detalle.id_ingreso_egreso="'.$egr5.'" and asignar_ingresos_detalle.id_empleado=empleado.id_empleado) as egreso5,
        (select asignar_ingresos.valor from asignar_ingresos where  asignar_ingresos.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos.id_ineg="'.$egr6.'" and asignar_ingresos.id_empleado=empleado.id_empleado and tiene_detalle=0 UNION select asignar_ingresos_detalle.valor from asignar_ingresos_detalle where  asignar_ingresos_detalle.fecha_asignar="'.$request->fecha_asignar.'" and asignar_ingresos_detalle.id_ingreso_egreso="'.$egr6.'" and asignar_ingresos_detalle.id_empleado=empleado.id_empleado) as egreso6

         from empleado  WHERE empleado.estado='.'"Activo"'.' and empleado.id_departamento='.$id." and '{$request->fecha_asignar}'>=empleado.fecha_ingreso and '{$mes_asignar}-01'<=if(empleado.fecha_salida is not null,empleado.fecha_salida,'2030-01-01')
         Order By empleado.primer_nombre asc");
         
        return $recupera;
        /*
        $recupera = DB::select('SELECT empleado.id_empleado,empleado.sueldo,if(empleado.decimo_tercero="Si",truncate(sueldo/12,2),0) as decimo_tercero_emp,if(empleado.fondo_reserva="Si",truncate(sueldo/12,2),0) as fondo_reserva_emp,if(empleado.decimo_cuarto="Si",truncate(400/12,2),0) as decimo_cuarto_emp,empleado.primer_nombre,empleado.apellido_paterno,30 as cantidad,
        (select asignar_ingresos.valor from asignar_ingresos where asignar_ingresos.id_ineg="'.$ingr1.'" and asignar_ingresos.id_empleado=empleado.id_empleado)as ingreso1,
        (select if(ingresos_egresos.iess=1,asignar_ingresos.valor,0) from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg="'.$ingr1.'" and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=empleado.id_empleado)as valor_ingreso1,
        (select asignar_ingresos.valor from asignar_ingresos where asignar_ingresos.id_ineg="'.$ingr2.'" and asignar_ingresos.id_empleado=empleado.id_empleado)as ingreso2,
        (select if(ingresos_egresos.iess=1,asignar_ingresos.valor,0) from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg="'.$ingr2.'" and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=empleado.id_empleado)as valor_ingreso2,
        (select asignar_ingresos.valor from asignar_ingresos where asignar_ingresos.id_ineg="'.$ingr3.'" and asignar_ingresos.id_empleado=empleado.id_empleado)as ingreso3,
        (select if(ingresos_egresos.iess=1,asignar_ingresos.valor,0) from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg="'.$ingr3.'" and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=empleado.id_empleado)as valor_ingreso3,
        (select asignar_ingresos.valor from asignar_ingresos where asignar_ingresos.id_ineg="'.$ingr4.'" and asignar_ingresos.id_empleado=empleado.id_empleado)as ingreso4,
        (select if(ingresos_egresos.iess=1,asignar_ingresos.valor,0) from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg="'.$ingr4.'" and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=empleado.id_empleado)as valor_ingreso4,
        (select asignar_ingresos.valor from asignar_ingresos where asignar_ingresos.id_ineg="'.$ingr5.'" and asignar_ingresos.id_empleado=empleado.id_empleado)as ingreso5,
        (select if(ingresos_egresos.iess=1,asignar_ingresos.valor,0) from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg="'.$ingr5.'" and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=empleado.id_empleado)as valor_ingreso5,
        (select asignar_ingresos.valor from asignar_ingresos where asignar_ingresos.id_ineg="'.$ingr6.'" and asignar_ingresos.id_empleado=empleado.id_empleado)as ingreso6,
        (select if(ingresos_egresos.iess=1,asignar_ingresos.valor,0) from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg="'.$ingr6.'" and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=empleado.id_empleado)as valor_ingreso6,
        (select asignar_ingresos.valor from asignar_ingresos where asignar_ingresos.id_ineg="'.$egr1.'" and asignar_ingresos.id_empleado=empleado.id_empleado)as egreso1,
        (select if(ingresos_egresos.iess=1,asignar_ingresos.valor,0) from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg="'.$egr1.'" and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=empleado.id_empleado)as valor_egreso1,
        (select asignar_ingresos.valor from asignar_ingresos where asignar_ingresos.id_ineg="'.$egr2.'" and asignar_ingresos.id_empleado=empleado.id_empleado)as egreso2,
        (select if(ingresos_egresos.iess=1,asignar_ingresos.valor,0) from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg="'.$egr2.'" and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=empleado.id_empleado)as valor_egreso2,
        (select asignar_ingresos.valor from asignar_ingresos where asignar_ingresos.id_ineg="'.$egr3.'" and asignar_ingresos.id_empleado=empleado.id_empleado)as egreso3,
        (select if(ingresos_egresos.iess=1,asignar_ingresos.valor,0) from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg="'.$egr3.'" and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=empleado.id_empleado)as valor_egreso3,
        (select asignar_ingresos.valor from asignar_ingresos where asignar_ingresos.id_ineg="'.$egr4.'" and asignar_ingresos.id_empleado=empleado.id_empleado)as egreso4,
        (select if(ingresos_egresos.iess=1,asignar_ingresos.valor,0) from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg="'.$egr4.'" and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=empleado.id_empleado)as valor_egreso4,
        (select asignar_ingresos.valor from asignar_ingresos where asignar_ingresos.id_ineg="'.$egr5.'" and asignar_ingresos.id_empleado=empleado.id_empleado)as egreso5,
        (select if(ingresos_egresos.iess=1,asignar_ingresos.valor,0) from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg="'.$egr5.'" and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=empleado.id_empleado)as valor_egreso5,
        (select asignar_ingresos.valor from asignar_ingresos where asignar_ingresos.id_ineg="'.$egr6.'" and asignar_ingresos.id_empleado=empleado.id_empleado)as egreso6,
        (select if(ingresos_egresos.iess=1,asignar_ingresos.valor,0) from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg="'.$egr6.'" and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=empleado.id_empleado)as valor_egreso6
         from empleado  WHERE empleado.id_departamento='.$id);
        return $recupera;
        */
    }
    public function RolPagoGeneral(Request $request){
        $id=$request->id_empleado;
        $ingr1=$request->ing1;
        $ingr2=$request->ing2;
        $ingr3=$request->ing3;
        $ingr4=$request->ing4;
        $ingr5=$request->ing5;
        $ingr6=$request->ing6;
        $egr1=$request->egr1;
        $egr2=$request->egr2;
        $egr3=$request->egr3;
        $egr4=$request->egr4;
        $egr5=$request->egr5;
        $egr6=$request->egr6;
        $recupera = DB::select('SELECT empleado.id_empleado,empleado.sueldo,
        (select rol_pago.decimo_tercero from rol_pago where rol_pago.id_empleado=empleado.id_empleado) as decimo_tercero,
        (select rol_pago.decimo_cuarto from rol_pago where rol_pago.id_empleado=empleado.id_empleado) as decimo_cuarto,
        (select rol_pago.fondo_reserva from rol_pago where rol_pago.id_empleado=empleado.id_empleado) as fondo_reserva,
        empleado.primer_nombre,empleado.apellido_paterno,30 as cantidad,
        (select asignar_ingresos.valor from asignar_ingresos where asignar_ingresos.id_ineg="'.$ingr1.'" and asignar_ingresos.id_empleado=empleado.id_empleado)as ingreso1,
        (select if(ingresos_egresos.iess=1,asignar_ingresos.valor,0) from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg="'.$ingr1.'" and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=empleado.id_empleado)as valor_ingreso1,
        (select asignar_ingresos.valor from asignar_ingresos where asignar_ingresos.id_ineg="'.$ingr2.'" and asignar_ingresos.id_empleado=empleado.id_empleado)as ingreso2,
        (select if(ingresos_egresos.iess=1,asignar_ingresos.valor,0) from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg="'.$ingr2.'" and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=empleado.id_empleado)as valor_ingreso2,
        (select asignar_ingresos.valor from asignar_ingresos where asignar_ingresos.id_ineg="'.$ingr3.'" and asignar_ingresos.id_empleado=empleado.id_empleado)as ingreso3,
        (select if(ingresos_egresos.iess=1,asignar_ingresos.valor,0) from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg="'.$ingr3.'" and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=empleado.id_empleado)as valor_ingreso3,
        (select asignar_ingresos.valor from asignar_ingresos where asignar_ingresos.id_ineg="'.$ingr4.'" and asignar_ingresos.id_empleado=empleado.id_empleado)as ingreso4,
        (select if(ingresos_egresos.iess=1,asignar_ingresos.valor,0) from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg="'.$ingr4.'" and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=empleado.id_empleado)as valor_ingreso4,
        (select asignar_ingresos.valor from asignar_ingresos where asignar_ingresos.id_ineg="'.$ingr5.'" and asignar_ingresos.id_empleado=empleado.id_empleado)as ingreso5,
        (select if(ingresos_egresos.iess=1,asignar_ingresos.valor,0) from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg="'.$ingr5.'" and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=empleado.id_empleado)as valor_ingreso5,
        (select asignar_ingresos.valor from asignar_ingresos where asignar_ingresos.id_ineg="'.$ingr6.'" and asignar_ingresos.id_empleado=empleado.id_empleado)as ingreso6,
        (select if(ingresos_egresos.iess=1,asignar_ingresos.valor,0) from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg="'.$ingr6.'" and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=empleado.id_empleado)as valor_ingreso6,
        (select asignar_ingresos.valor from asignar_ingresos where asignar_ingresos.id_ineg="'.$egr1.'" and asignar_ingresos.id_empleado=empleado.id_empleado)as egreso1,
        (select asignar_ingresos.valor from asignar_ingresos where asignar_ingresos.id_ineg="'.$egr2.'" and asignar_ingresos.id_empleado=empleado.id_empleado)as egreso2,
        (select asignar_ingresos.valor from asignar_ingresos where asignar_ingresos.id_ineg="'.$egr3.'" and asignar_ingresos.id_empleado=empleado.id_empleado)as egreso3,
        (select asignar_ingresos.valor from asignar_ingresos where asignar_ingresos.id_ineg="'.$egr4.'" and asignar_ingresos.id_empleado=empleado.id_empleado)as egreso4,
        (select asignar_ingresos.valor from asignar_ingresos where asignar_ingresos.id_ineg="'.$egr5.'" and asignar_ingresos.id_empleado=empleado.id_empleado)as egreso5,
        (select asignar_ingresos.valor from asignar_ingresos where asignar_ingresos.id_ineg="'.$egr6.'" and asignar_ingresos.id_empleado=empleado.id_empleado)as egreso6

         from empleado  WHERE empleado.id_departamento='.$id);
        return $recupera;
    }
    public function generarReporte(Request $request){
        $recupera2=DB::select("SELECT user.*,empresa.logo,empresa.id_empresa,empresa.nombre_contador,empresa.nomb_representante from user,empresa where user.id_empresa=empresa.id_empresa and user.id=".$request->id_user);
        if($request->id_empleado!==null){

                
                $recupera=DB::select("SELECT rol_pago.*,empresa.nombre_empresa,empleado.dni,empresa.logo,departamento.dep_nombre,
                (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing1." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->ing1." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_ingreso1,
                (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing2." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->ing2." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_ingreso2,
                (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing3." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->ing3." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_ingreso3,
                (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing4." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->ing4." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_ingreso4,
                (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing5." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->ing5." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_ingreso5,
                (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing6." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->ing6." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_ingreso6,
                (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr1." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->egr1." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_egreso1,
                (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr2." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->egr2." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_egreso2,
                (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr3." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->egr3." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_egreso3,
                (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr4." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->egr4." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_egreso4,
                (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr5." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->egr5." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_egreso5,
                (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr6." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->egr6." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_egreso6
                FROM rol_pago,empresa,empleado,departamento where rol_pago.fechrol=".'"'.$request->date.'"'." and rol_pago.id_empresa=empresa.id_empresa and rol_pago.id_empleado=empleado.id_empleado and rol_pago.id_departamento=departamento.id_departamento  and rol_pago.id_empleado=".$request->id_empleado." and rol_pago.id_departamento=".$request->id_departamento);
            
                // dd("SELECT rol_pago.*,empresa.nombre_empresa,empleado.dni,empresa.logo,departamento.dep_nombre,
                // (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing1." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->ing1." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_ingreso1,
                // (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing2." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->ing2." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_ingreso2,
                // (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing3." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->ing3." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_ingreso3,
                // (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing4." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->ing4." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_ingreso4,
                // (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing5." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->ing5." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_ingreso5,
                // (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing6." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->ing6." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_ingreso6,
                // (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr1." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->egr1." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_egreso1,
                // (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr2." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->egr2." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_egreso2,
                // (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr3." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->egr3." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_egreso3,
                // (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr4." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->egr4." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_egreso4,
                // (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr5." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->egr5." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_egreso5,
                // (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr6." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->egr6." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_egreso6
                // FROM rol_pago,empresa,empleado,departamento where rol_pago.fechrol=".'"'.$request->date.'"'." and rol_pago.id_empresa=empresa.id_empresa and rol_pago.id_empleado=empleado.id_empleado and rol_pago.id_departamento=departamento.id_departamento  and rol_pago.id_empleado=".$request->id_empleado." and rol_pago.id_departamento=".$request->id_departamento);
            
            $Reportes = new generarPDF();
                if (!$recupera) {
                    return response('no-data-report', 200)->header('Content-Type', 'application/json');
                } else {
                    $strPDF = $Reportes->RolPagoGeneralIndividual($recupera, $request->date,$recupera2);
                    return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                }
        }else{
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
                $recupera=DB::select("SELECT rol_pago.*,empresa.nombre_empresa,empleado.dni,departamento.dep_nombre,
                (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing1." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->ing1." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_ingreso1,
                (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing2." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->ing2." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_ingreso2,
                (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing3." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->ing3." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_ingreso3,
                (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing4." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->ing4." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_ingreso4,
                (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing5." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->ing5." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_ingreso5,
                (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing6." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->ing6." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_ingreso6,
                (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr1." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->egr1." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_egreso1,
                (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr2." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->egr2." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_egreso2,
                (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr3." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->egr3." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_egreso3,
                (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr4." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->egr4." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_egreso4,
                (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr5." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->egr5." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_egreso5,
                (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr6." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->egr6." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_egreso6
                FROM rol_pago,empresa,empleado,departamento where {$queries} and rol_pago.fechrol=".'"'.$request->date.'"'." and rol_pago.id_empresa=empresa.id_empresa and rol_pago.id_empleado=empleado.id_empleado and rol_pago.id_departamento=departamento.id_departamento and empleado.estado=".'"Activo"'. " and rol_pago.id_departamento=".$request->id_departamento);
            }else{
                $recupera=DB::select("SELECT rol_pago.*,empresa.nombre_empresa,empleado.dni,departamento.dep_nombre,
                (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing1." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->ing1." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_ingreso1,
                (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing2." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->ing2." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_ingreso2,
                (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing3." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->ing3." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_ingreso3,
                (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing4." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->ing4." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_ingreso4,
                (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing5." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->ing5." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_ingreso5,
                (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing6." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->ing6." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_ingreso6,
                (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr1." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->egr1." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_egreso1,
                (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr2." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->egr2." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_egreso2,
                (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr3." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->egr3." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_egreso3,
                (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr4." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->egr4." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_egreso4,
                (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr5." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->egr5." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_egreso5,
                (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr6." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado and tiene_detalle=0 UNION select ingresos_egresos.decripcion from asignar_ingresos_detalle,ingresos_egresos where asignar_ingresos_detalle.id_ingreso_egreso=".$request->egr6." and asignar_ingresos_detalle.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and asignar_ingresos_detalle.id_empleado=rol_pago.id_empleado)as id_egreso6
                FROM rol_pago,empresa,empleado,departamento where rol_pago.fechrol=".'"'.$request->date.'"'." and rol_pago.id_empresa=empresa.id_empresa and rol_pago.id_empleado=empleado.id_empleado and rol_pago.id_departamento=departamento.id_departamento  and rol_pago.id_departamento=".$request->id_departamento);
            }
            
            // dd("SELECT rol_pago.*,empresa.nombre_empresa,empleado.dni,departamento.dep_nombre,
            // (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing1." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado)as id_ingreso1,
            // (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing2." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado)as id_ingreso2,
            // (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing3." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado)as id_ingreso3,
            // (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing4." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado)as id_ingreso4,
            // (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing5." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado)as id_ingreso5,
            // (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing6." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado)as id_ingreso6,
            // (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr1." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado)as id_egreso1,
            // (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr2." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado)as id_egreso2,
            // (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr3." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado)as id_egreso3,
            // (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr4." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado)as id_egreso4,
            // (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr5." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado)as id_egreso5,
            // (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr6." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado)as id_egreso6
            // FROM rol_pago,empresa,empleado,departamento where rol_pago.fechrol=".'"'.$request->date.'"'." and rol_pago.id_empresa=empresa.id_empresa and rol_pago.id_empleado=empleado.id_empleado and rol_pago.id_departamento=departamento.id_departamento  and rol_pago.id_departamento=".$request->id_departamento);
            $Reportes = new generarPDF();
            if (!$recupera) {
                return response('no-data-report', 200)->header('Content-Type', 'application/json');
            } else {
                $strPDF = $Reportes->RolPagoGeneral($recupera, $request->date,$recupera2,$proyecto);
                return response($strPDF, 200)->header('Content-Type', 'application/pdf');
            }
        }
        
    }
    public function Papeletas(Request $request){
        setlocale(LC_TIME, "spanish");
        $id=$request->id_empleado;
        $ingr1=$request->ing1;
        $ingr2=$request->ing2;
        $ingr3=$request->ing3;
        $ingr4=$request->ing4;
        $ingr5=$request->ing5;
        $ingr6=$request->ing6;
        $egr1=$request->egr1;
        $egr2=$request->egr2;
        $egr3=$request->egr3;
        $egr4=$request->egr4;
        $egr5=$request->egr5;
        $egr6=$request->egr6;
        $recupera2=DB::select("SELECT user.*,empresa.logo,empresa.nombre_empresa as nombre_empresa_user,empresa.id_empresa,empresa.nombre_contador,empresa.nomb_representante from user,empresa where user.id_empresa=empresa.id_empresa and user.id=".$request->id_user);
       
            //}
        //}else{
           
            $recupera=DB::select("SELECT rol_pago.*,empresa.nombre_empresa,empleado.dni,empresa.logo,departamento.dep_nombre,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing1." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado)as id_ingreso1,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing2." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado)as id_ingreso2,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing3." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado)as id_ingreso3,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing4." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado)as id_ingreso4,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing5." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado)as id_ingreso5,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing6." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado)as id_ingreso6,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr1." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado)as id_egreso1,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr2." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado)as id_egreso2,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr3." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado)as id_egreso3,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr4." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado)as id_egreso4,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr5." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado)as id_egreso5,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr6." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado)as id_egreso6
            FROM rol_pago,empresa,empleado,departamento where rol_pago.fechrol=".'"'.$request->date.'"'." and rol_pago.id_empresa=empresa.id_empresa and rol_pago.id_empleado=empleado.id_empleado and rol_pago.id_departamento=departamento.id_departamento and empleado.estado=".'"Activo"'." and rol_pago.id_departamento=".$id);
            $Reportes = new generarPDF();
            for ($a = 0; $a < count($recupera); $a++) {
                $fecha_papeleta=ucwords(strftime("%B %Y", strtotime($recupera[$a]->fechrol)));
                $documento="Rol_Pago_".$recupera[$a]->primer_nombre."_".$fecha_papeleta.".pdf";
                if (!$recupera) {
                    return response('no-data-report', 200)->header('Content-Type', 'application/json');
                } else {
                    $carpetanombre = constant("DATA_EMPRESA").$recupera2[0]->id_empresa;
                    $carpeta1 = $carpetanombre."/papeletas/".$fecha_papeleta."/".$id.'/'.$documento;
                    $carpetanombre2 = constant("DATA_EMPRESA").$recupera2[0]->id_empresa;
                    $carpeta2 = $carpetanombre2."/papeletas/".$fecha_papeleta."/".$id."/";
                    if (!file_exists($carpeta2)) {
                        mkdir($carpeta2, 0755,true);
                    }
                    $carpetanombre3 = constant("DATA_EMPRESA").$recupera2[0]->id_empresa;
                    $carpeta3 = $carpetanombre3."/rol_general/".$fecha_papeleta."/".$id."/";
                    if (!file_exists($carpeta3)) {
                        mkdir($carpeta3, 0755,true);
                    }
                    if(file_exists($carpeta1)){
                        unlink($carpeta1);
                        $strPDF = $Reportes->PapeletasIndividual($recupera[$a], $request->date,$recupera2);
                    }
                    if(!file_exists($carpeta1)){
                        $strPDF = $Reportes->PapeletasIndividual($recupera[$a], $request->date,$recupera2);
                    }
                    //$strPDF = $Reportes->PapeletasIndividual($recupera[$a], $request->date,$recupera2);
                //  return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                }
            }
       // }
    }
    public function getProyecto($id){
        $request=DB::select("SELECT * from proyecto where id_empresa=".$id);
        return $request;
    }
    public function PapeletaIndividual(Request $request){
        setlocale(LC_TIME, "spanish");
        $id=$request->id_empleado;
        $ingr1=$request->ing1;
        $ingr2=$request->ing2;
        $ingr3=$request->ing3;
        $ingr4=$request->ing4;
        $ingr5=$request->ing5;
        $ingr6=$request->ing6;
        $egr1=$request->egr1;
        $egr2=$request->egr2;
        $egr3=$request->egr3;
        $egr4=$request->egr4;
        $egr5=$request->egr5;
        $egr6=$request->egr6;
        $recupera2=DB::select("SELECT user.*,empresa.logo,empresa.nombre_empresa  as nombre_empresa_user,empresa.id_empresa,empresa.nombre_contador,empresa.nomb_representante from user,empresa where user.id_empresa=empresa.id_empresa and user.id=".$request->id_user);
        
            $recupera=DB::select("SELECT rol_pago.*,empresa.nombre_empresa,empleado.dni,empresa.logo,departamento.dep_nombre,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing1." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado)as id_ingreso1,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing2." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado)as id_ingreso2,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing3." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado)as id_ingreso3,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing4." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado)as id_ingreso4,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing5." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado)as id_ingreso5,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->ing6." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado)as id_ingreso6,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr1." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado)as id_egreso1,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr2." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado)as id_egreso2,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr3." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado)as id_egreso3,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr4." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado)as id_egreso4,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr5." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado)as id_egreso5,
            (select ingresos_egresos.decripcion from asignar_ingresos,ingresos_egresos where asignar_ingresos.id_ineg=".$request->egr6." and asignar_ingresos.fecha_asignar=".'"'.$request->date.'"'." and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and asignar_ingresos.id_empleado=rol_pago.id_empleado)as id_egreso6
            FROM rol_pago,empresa,empleado,departamento where rol_pago.fechrol=".'"'.$request->date.'"'." and rol_pago.id_empresa=empresa.id_empresa and rol_pago.id_empleado=empleado.id_empleado and rol_pago.id_departamento=departamento.id_departamento and rol_pago.id_empleado=".$request->empleado." and rol_pago.id_departamento=".$id);
            $Reportes = new generarPDF();
            //for ($a = 0; $a < count($recupera); $a++) {
                $fecha_papeleta=ucwords(strftime("%B %Y", strtotime($recupera[0]->fechrol)));
                $documento="Rol_Pago_".$recupera[0]->primer_nombre."_".$fecha_papeleta.".pdf";
                if (!$recupera) {
                    return response('no-data-report', 200)->header('Content-Type', 'application/json');
                } else {
                    $carpetanombre = constant("DATA_EMPRESA").$recupera2[0]->id_empresa;
                    $carpeta1 = $carpetanombre."/papeletas/".$fecha_papeleta."/".$id.'/'.$documento;
                    $carpetanombre2 = constant("DATA_EMPRESA").$recupera2[0]->id_empresa;
                    $carpeta2 = $carpetanombre2."/papeletas/".$fecha_papeleta."/".$id."/";
                    if (!file_exists($carpeta2)) {
                        mkdir($carpeta2, 0755,true);
                    }
                    if(file_exists($carpeta1)){
                        unlink($carpeta1);
                        $strPDF = $Reportes->PapeletasIndividual($recupera[0], $request->date,$recupera2);
                    }
                    if(!file_exists($carpeta1)){
                        $strPDF = $Reportes->PapeletasIndividual($recupera[0], $request->date,$recupera2);
                    }
                    //$strPDF = $Reportes->PapeletasIndividual($recupera[0], $request->date,$recupera2);
                //  return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                }
    }
    public function getEmpleadosRolPago(Request $request){
        $recupera=DB::select('SELECT rol_pago.*,empleado.email from empleado,rol_pago where rol_pago.id_empleado=empleado.id_empleado and  rol_pago.fechrol='.'"'.$request->fecha.'"'.' and rol_pago.id_departamento='.$request->id_departamento);
        return $recupera;
    }

    public function getIngreso(Request $request,$id)
    {
        /*$recupera = DB::select('SELECT asignar_ingresos.valor,ingresos_egresos.decripcion,ingresos_egresos.tipo,asignar_ingresos.id_empleado
        FROM asignar_ingresos,empleado,ingresos_egresos
        where asignar_ingresos.id_empleado=empleado.id_empleado
        and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg
        and ingresos_egresos.tipo="Ingreso"
        and empleado.id_departamento='.$id);*/
        $recupera=DB::select('SELECT distinct ingresos_egresos.id_ineg,ingresos_egresos.decripcion FROM asignar_ingresos,ingresos_egresos,empleado where asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and ingresos_egresos.tipo="Ingreso" and empleado.id_empleado=asignar_ingresos.id_empleado and asignar_ingresos.fecha_asignar='.'"'.$request->fecha.'"'.' and empleado.id_departamento='.$id.
        " and asignar_ingresos.tiene_detalle=0 UNION
        SELECT distinct ingresos_egresos.id_ineg,ingresos_egresos.decripcion FROM asignar_ingresos_detalle,ingresos_egresos,empleado where asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and ingresos_egresos.tipo='Ingreso' and empleado.id_empleado=asignar_ingresos_detalle.id_empleado and asignar_ingresos_detalle.fecha_asignar='{$request->fecha}' and empleado.id_departamento={$id}");
        // dd('SELECT distinct ingresos_egresos.id_ineg,ingresos_egresos.decripcion FROM asignar_ingresos,ingresos_egresos,empleado where asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and ingresos_egresos.tipo="Ingreso" and empleado.id_empleado=asignar_ingresos.id_empleado and asignar_ingresos.fecha_asignar='.'"'.$request->fecha.'"'.' and empleado.id_departamento='.$id.
        // " and asignar_ingresos.tiene_detalle=0 UNION
        // SELECT distinct ingresos_egresos.id_ineg,ingresos_egresos.decripcion FROM asignar_ingresos_detalle,ingresos_egresos,empleado where asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and ingresos_egresos.tipo='Ingreso' and empleado.id_empleado=asignar_ingresos_detalle.id_empleado and asignar_ingresos_detalle.fecha_asignar='{$request->fecha}' and empleado.id_departamento={$id}");
        if($recupera){
            return $recupera;
        }else{
            return "vacio";
        }
        
    }
    public function getVerIngreso(Request $request,$id)
    {
        /*$recupera = DB::select('SELECT asignar_ingresos.valor,ingresos_egresos.decripcion,ingresos_egresos.tipo,asignar_ingresos.id_empleado
        FROM asignar_ingresos,empleado,ingresos_egresos
        where asignar_ingresos.id_empleado=empleado.id_empleado
        and asignar_ingresos.id_ineg=ingresos_egresos.id_ineg
        and ingresos_egresos.tipo="Ingreso"
        and empleado.id_departamento='.$id);*/
        $recupera=DB::select('SELECT distinct ingresos_egresos.id_ineg,ingresos_egresos.decripcion FROM asignar_ingresos,ingresos_egresos,empleado where asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and ingresos_egresos.tipo="Ingreso" and empleado.id_empleado=asignar_ingresos.id_empleado and asignar_ingresos.fecha_asignar='.'"'.$request->fecha.'"'.' and empleado.id_departamento='.$id. " and asignar_ingresos.tiene_detalle=0 UNION
        SELECT distinct ingresos_egresos.id_ineg,ingresos_egresos.decripcion FROM asignar_ingresos_detalle,ingresos_egresos,empleado where asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and ingresos_egresos.tipo='Ingreso' and empleado.id_empleado=asignar_ingresos_detalle.id_empleado and asignar_ingresos_detalle.fecha_asignar='{$request->fecha}' and empleado.id_departamento={$id}");
        if($recupera){
            return $recupera;
        }else{
            return "vacio";
        }
        
    }
    public function getValoresIngreso(Request $request,$id)
    {

        $recupera=DB::select('SELECT asignar_ingresos.*,ingresos_egresos.decripcion FROM asignar_ingresos,ingresos_egresos,empleado where asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and ingresos_egresos.tipo="Ingreso" and empleado.id_empleado=asignar_ingresos.id_empleado and asignar_ingresos.fecha_asignar='.'"'.$request->fecha.'"'.' and empleado.id_departamento='.$id);
        if($recupera){
            return $recupera;
        }else{
            return "vacio";
        }
        
    }
    public function getEgreso(Request $request,$id)
    {
        $recupera=DB::select('SELECT distinct ingresos_egresos.id_ineg,ingresos_egresos.decripcion FROM asignar_ingresos,ingresos_egresos,empleado where asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and ingresos_egresos.tipo="Egreso" and empleado.id_empleado=asignar_ingresos.id_empleado and asignar_ingresos.fecha_asignar='.'"'.$request->fecha.'"'.' and empleado.id_departamento='.$id." and asignar_ingresos.tiene_detalle=0 UNION
        SELECT distinct ingresos_egresos.id_ineg,ingresos_egresos.decripcion FROM asignar_ingresos_detalle,ingresos_egresos,empleado where asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and ingresos_egresos.tipo='Egreso' and empleado.id_empleado=asignar_ingresos_detalle.id_empleado and asignar_ingresos_detalle.fecha_asignar='{$request->fecha}' and empleado.id_departamento={$id}");
        if($recupera){
            return $recupera;
        }else{
            return "vacio";
        }
    }
    public function getVerEgreso(Request $request,$id)
    {
        $recupera=DB::select('SELECT distinct ingresos_egresos.id_ineg,ingresos_egresos.decripcion FROM asignar_ingresos,ingresos_egresos,empleado where asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and ingresos_egresos.tipo="Egreso" and empleado.id_empleado=asignar_ingresos.id_empleado and asignar_ingresos.fecha_asignar='.'"'.$request->fecha.'"'.' and empleado.id_departamento='.$id." and asignar_ingresos.tiene_detalle=0 UNION
        SELECT distinct ingresos_egresos.id_ineg,ingresos_egresos.decripcion FROM asignar_ingresos_detalle,ingresos_egresos,empleado where asignar_ingresos_detalle.id_ingreso_egreso=ingresos_egresos.id_ineg and ingresos_egresos.tipo='Egreso' and empleado.id_empleado=asignar_ingresos_detalle.id_empleado and asignar_ingresos_detalle.fecha_asignar='{$request->fecha}' and empleado.id_departamento={$id}");
        if($recupera){
            return $recupera;
        }else{
            return "vacio";
        }
    }
    public function getValoresEgreso(Request $request,$id)
    {
        $recupera=DB::select('SELECT asignar_ingresos.*,ingresos_egresos.decripcion FROM asignar_ingresos,ingresos_egresos,empleado where asignar_ingresos.id_ineg=ingresos_egresos.id_ineg and ingresos_egresos.tipo="Egreso" and empleado.id_empleado=asignar_ingresos.id_empleado and asignar_ingresos.fecha_asignar='.'"'.$request->fecha.'"'.' and empleado.id_departamento='.$id);
        if($recupera){
            return $recupera;
        }else{
            return "vacio";
        }
    }
}
