<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Asignar_ingresos;
use App\Models\AsiganrIngresosDetalle;
use Carbon\Carbon;

class AsignaringresosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        ini_set('max_execution_time', 53200);
        $hoy = Carbon::now();
        //
        $select=DB::select("SELECT cod_asignar_ingresos from asignar_ingresos  ORDER BY id_asignar_ingresos desc limit 1");
        //$codigo=$select[0]->cod_asignar_ingresos+1;
        //dd($request->contenidopr);
        if(!$select){
            $codigo=1;
        }else{
            $codigo=$select[0]->cod_asignar_ingresos+1;
        }
        //return $codigo;
        $total=0;
        for ($a = 0; $a < count($request->contenidopr); $a++) {
            $total +=$request->contenidopr[$a]["valor"];
            if(isset($request->contenidopr[$a]["id_ineg_0"])){
                $aingd=new AsiganrIngresosDetalle();
                $aingd->valor=$request->contenidopr[$a]["valor_ineg_0"];
                $aingd->fecha_asignar=$request->fecha_asignar;
                $aingd->fcrea=$hoy;
                $aingd->ucrea=$request->usuario;
                $aingd->id_empleado=$request->contenidopr[$a]["id_empleado"];
                $aingd->cod_asignar_ingresos=$codigo;
                $aingd->id_ingreso_egreso=$request->contenidopr[$a]["id_ineg_0"];
                $aingd->save();
            }
            if(isset($request->contenidopr[$a]["id_ineg_1"])){
                $aingd=new AsiganrIngresosDetalle();
                $aingd->valor=$request->contenidopr[$a]["valor_ineg_1"];
                $aingd->fecha_asignar=$request->fecha_asignar;
                $aingd->fcrea=$hoy;
                $aingd->ucrea=$request->usuario;
                $aingd->id_empleado=$request->contenidopr[$a]["id_empleado"];
                $aingd->cod_asignar_ingresos=$codigo;
                $aingd->id_ingreso_egreso=$request->contenidopr[$a]["id_ineg_1"];
                $aingd->save();
            }
            if(isset($request->contenidopr[$a]["id_ineg_2"])){
                $aingd=new AsiganrIngresosDetalle();
                $aingd->valor=$request->contenidopr[$a]["valor_ineg_2"];
                $aingd->fecha_asignar=$request->fecha_asignar;
                $aingd->fcrea=$hoy;
                $aingd->ucrea=$request->usuario;
                $aingd->id_empleado=$request->contenidopr[$a]["id_empleado"];
                $aingd->cod_asignar_ingresos=$codigo;
                $aingd->id_ingreso_egreso=$request->contenidopr[$a]["id_ineg_2"];
                $aingd->save();
            }
            if(isset($request->contenidopr[$a]["id_ineg_3"])){
                $aingd=new AsiganrIngresosDetalle();
                $aingd->valor=$request->contenidopr[$a]["valor_ineg_3"];
                $aingd->fecha_asignar=$request->fecha_asignar;
                $aingd->fcrea=$hoy;
                $aingd->ucrea=$request->usuario;
                $aingd->id_empleado=$request->contenidopr[$a]["id_empleado"];
                $aingd->cod_asignar_ingresos=$codigo;
                $aingd->id_ingreso_egreso=$request->contenidopr[$a]["id_ineg_3"];
                $aingd->save();
            }
            if(isset($request->contenidopr[$a]["id_ineg_4"])){
                $aingd=new AsiganrIngresosDetalle();
                $aingd->valor=$request->contenidopr[$a]["valor_ineg_4"];
                $aingd->fecha_asignar=$request->fecha_asignar;
                $aingd->fcrea=$hoy;
                $aingd->ucrea=$request->usuario;
                $aingd->id_empleado=$request->contenidopr[$a]["id_empleado"];
                $aingd->cod_asignar_ingresos=$codigo;
                $aingd->id_ingreso_egreso=$request->contenidopr[$a]["id_ineg_4"];
                $aingd->save();
            }
            if(isset($request->contenidopr[$a]["id_ineg_5"])){
                $aingd=new AsiganrIngresosDetalle();
                $aingd->valor=$request->contenidopr[$a]["valor_ineg_5"];
                $aingd->fecha_asignar=$request->fecha_asignar;
                $aingd->fcrea=$hoy;
                $aingd->ucrea=$request->usuario;
                $aingd->id_empleado=$request->contenidopr[$a]["id_empleado"];
                $aingd->cod_asignar_ingresos=$codigo;
                $aingd->id_ingreso_egreso=$request->contenidopr[$a]["id_ineg_5"];
                $aingd->save();
            }
            if(isset($request->contenidopr[$a]["id_ineg_6"])){
                $aingd=new AsiganrIngresosDetalle();
                $aingd->valor=$request->contenidopr[$a]["valor_ineg_6"];
                $aingd->fecha_asignar=$request->fecha_asignar;
                $aingd->ucrea=$request->usuario;
                $aingd->id_empleado=$request->contenidopr[$a]["id_empleado"];
                $aingd->cod_asignar_ingresos=$codigo;
                $aingd->id_ingreso_egreso=$request->contenidopr[$a]["id_ineg_6"];
                $aingd->save();
            }
            if(isset($request->contenidopr[$a]["id_ineg_7"])){
                $aingd=new AsiganrIngresosDetalle();
                $aingd->valor=$request->contenidopr[$a]["valor_ineg_7"];
                $aingd->fecha_asignar=$request->fecha_asignar;
                $aingd->ucrea=$request->usuario;
                $aingd->id_empleado=$request->contenidopr[$a]["id_empleado"];
                $aingd->cod_asignar_ingresos=$codigo;
                $aingd->id_ingreso_egreso=$request->contenidopr[$a]["id_ineg_7"];
                $aingd->save();
            }
            if(isset($request->contenidopr[$a]["id_ineg_8"])){
                $aingd=new AsiganrIngresosDetalle();
                $aingd->valor=$request->contenidopr[$a]["valor_ineg_8"];
                $aingd->fecha_asignar=$request->fecha_asignar;
                $aingd->ucrea=$request->usuario;
                $aingd->id_empleado=$request->contenidopr[$a]["id_empleado"];
                $aingd->cod_asignar_ingresos=$codigo;
                $aingd->id_ingreso_egreso=$request->contenidopr[$a]["id_ineg_8"];
                $aingd->save();
            }
            if(isset($request->contenidopr[$a]["id_ineg_9"])){
                $aingd=new AsiganrIngresosDetalle();
                $aingd->valor=$request->contenidopr[$a]["valor_ineg_9"];
                $aingd->fecha_asignar=$request->fecha_asignar;
                $aingd->ucrea=$request->usuario;
                $aingd->id_empleado=$request->contenidopr[$a]["id_empleado"];
                $aingd->cod_asignar_ingresos=$codigo;
                $aingd->id_ingreso_egreso=$request->contenidopr[$a]["id_ineg_9"];
                $aingd->save();
            }
            if(isset($request->contenidopr[$a]["id_ineg_10"])){
                $aingd=new AsiganrIngresosDetalle();
                $aingd->valor=$request->contenidopr[$a]["valor_ineg_10"];
                $aingd->fecha_asignar=$request->fecha_asignar;
                $aingd->ucrea=$request->usuario;
                $aingd->id_empleado=$request->contenidopr[$a]["id_empleado"];
                $aingd->cod_asignar_ingresos=$codigo;
                $aingd->id_ingreso_egreso=$request->contenidopr[$a]["id_ineg_10"];
                $aingd->save();
            }
            if(isset($request->contenidopr[$a]["id_ineg_11"])){
                $aingd=new AsiganrIngresosDetalle();
                $aingd->valor=$request->contenidopr[$a]["valor_ineg_11"];
                $aingd->fecha_asignar=$request->fecha_asignar;
                $aingd->ucrea=$request->usuario;
                $aingd->id_empleado=$request->contenidopr[$a]["id_empleado"];
                $aingd->cod_asignar_ingresos=$codigo;
                $aingd->id_ingreso_egreso=$request->contenidopr[$a]["id_ineg_11"];
                $aingd->save();
            }
        }
        
        
        for ($a = 0; $a < count($request->contenidopr); $a++) {
            
            $ingresos = new Asignar_ingresos();
            $ingresos->cod_asignar_ingresos = $codigo;
            $ingresos->fecha_asignar = $request->fecha_asignar;
            $ingresos->valor = $request->contenidopr[$a]["valor"];
            
            $ingresos->total_valor=$total;
            $ingresos->id_ineg = $request->idtipoie;
            $ingresos->id_empleado = $request->contenidopr[$a]["id_empleado"];
            $ingresos->id_empresa = $request->id_empresa;
            $ingresos->id_departamento = $request->id_departamento;
            $ingresos->tiene_detalle = 1;
            $ingresos->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function listar(Request $request)
    {
        //
        $id = $request->id;
        //$asignaringresos = DB::select("SELECT id_asignar_ingresos,cod_asignar_ingresos,(select dep_nombre from departamento where departamento.id_departamento=asignar_ingresos.id_departamento) as decripcion,(select tipo from ingresos_egresos where ingresos_egresos.id_ineg=asignar_ingresos.id_ineg) as tipo,total_valor as valor,(select decripcion from ingresos_egresos where ingresos_egresos.id_ineg=asignar_ingresos.id_ineg) as ingreso from asignar_ingresos where id_empresa=".$id);
        $asignaringresos = DB::select("SELECT distinct cod_asignar_ingresos,fecha_asignar,(select dep_nombre from departamento where departamento.id_departamento=asignar_ingresos.id_departamento) as decripcion,(select tipo from ingresos_egresos where ingresos_egresos.id_ineg=asignar_ingresos.id_ineg) as tipo,total_valor as valor,(select decripcion from ingresos_egresos where ingresos_egresos.id_ineg=asignar_ingresos.id_ineg) as ingreso,tiene_detalle from asignar_ingresos where id_empresa=".$id." and tiene_detalle<>1
            UNION
        select cod_asignar_ingresos,max(fecha_asignar) as fecha_asignar,(select dep_nombre from departamento where departamento.id_departamento=empleado.id_departamento) as descripcion,0 as tipo,sum(valor) as valor,null as ingreso,1 as tiene_detalle
        from asignar_ingresos_detalle
        INNER JOIN empleado
        on asignar_ingresos_detalle.id_empleado=empleado.id_empleado
        where empleado.id_empresa={$id}
        GROUP BY empleado.id_departamento,asignar_ingresos_detalle.cod_asignar_ingresos
        ORDER BY fecha_asignar desc");
        return ['recupera' => $asignaringresos];
    }
    public function listarEmpleados($id)
    {
        //
        $id = $request->id;
        //$asignaringresos = DB::select("SELECT id_asignar_ingresos,cod_asignar_ingresos,(select dep_nombre from departamento where departamento.id_departamento=asignar_ingresos.id_departamento) as decripcion,(select tipo from ingresos_egresos where ingresos_egresos.id_ineg=asignar_ingresos.id_ineg) as tipo,total_valor as valor,(select decripcion from ingresos_egresos where ingresos_egresos.id_ineg=asignar_ingresos.id_ineg) as ingreso from asignar_ingresos where id_empresa=".$id);
        $asignaringresos = DB::select("SELECT empleado.primer_nombre,empleado.apellido_paterno ");
        return ['recupera' => $asignaringresos];
    }
    public function getEmpleado(Request $request){
        //dd($request->ingresos[0]);
        $mes_asignar=substr($request->fecha_asignar,0,7);
        if(isset($request->ingresos)){
            $valores=[];
            
            for ($i=0; $i < count($request->ingresos); $i++) { 
                //dd($ingresos[$i]);
                array_push($valores,"(select id_ineg  from ingresos_egresos where ingresos_egresos.id_ineg={$request->ingresos[$i]} ) as id_ineg_{$i},0.00 as valor_ineg_{$i} ");
            }
            $valores=implode(",",$valores);
            $recupera = DB::select("SELECT *,0.00 as valor,{$valores} from empleado where estado=".'"Activo"'. " and id_departamento=".$request->id." and '{$request->fecha}'>=fecha_ingreso and '{$mes_asignar}-01'<=if(fecha_salida is not null,fecha_salida,'2030-01-01') Order by empleado.primer_nombre");
            //dd("SELECT *,0.00 as valor,{$valores} from empleado where estado=".'"Activo"'. " and id_departamento=".$id);
        }else{
            $recupera = DB::select("SELECT *,0.00 as valor from empleado where estado=".'"Activo"'. " and id_departamento=".$request->id." and id_departamento=".$request->id." and '{$request->fecha}'>=fecha_ingreso and '{$mes_asignar}-01'<=if(fecha_salida is not null,fecha_salida,'2030-01-01')  Order by empleado.primer_nombre");
        }
        
        
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
        $recupera=DB::select("SELECT a.*,e.primer_nombre,e.apellido_paterno,i.decripcion as tipo3,d.dep_nombre from asignar_ingresos as a,empleado as e,ingresos_egresos as i,departamento as d where a.id_ineg=i.id_ineg and a.id_empleado=e.id_empleado and a.id_departamento=d.id_departamento and a.cod_asignar_ingresos=".$id);
        return $recupera;
    }
    public function edit_nuevo($id)
    {
        $ingresos=DB::select("SELECT distinct asignar_ingresos_detalle.id_ingreso_egreso,ingresos_egresos.decripcion  from asignar_ingresos_detalle,ingresos_egresos where ingresos_egresos.id_ineg=asignar_ingresos_detalle.id_ingreso_egreso and cod_asignar_ingresos={$id}");
        $valores=[];
        for ($i=0; $i < count($ingresos); $i++) { 
            array_push($valores,"(select id_ingreso_egreso from asignar_ingresos_detalle where asignar_ingresos_detalle.id_ingreso_egreso={$ingresos[$i]->id_ingreso_egreso} and asignar_ingresos_detalle.id_empleado=e.id_empleado and asignar_ingresos_detalle.cod_asignar_ingresos={$id}) as id_ineg_{$i},(select valor from asignar_ingresos_detalle where asignar_ingresos_detalle.id_ingreso_egreso={$ingresos[$i]->id_ingreso_egreso} and asignar_ingresos_detalle.id_empleado=e.id_empleado and asignar_ingresos_detalle.cod_asignar_ingresos={$id}) as valor_ineg_{$i} ");
        }
        
        $valores=implode(",",$valores);
        // dd("SELECT e.id_empleado,e.primer_nombre,e.apellido_paterno,d.dep_nombre,max(fecha_asignar) as fecha_asignar,{$valores} from asignar_ingresos_detalle as a,empleado as e,ingresos_egresos as i,departamento as d where a.id_ingreso_egreso=i.id_ineg and a.id_empleado=e.id_empleado and e.id_departamento=d.id_departamento and a.cod_asignar_ingresos={$id}
        // GROUP BY e.id_empleado");
        $recupera=DB::select("SELECT e.id_empleado,e.primer_nombre,e.apellido_paterno,d.dep_nombre,max(fecha_asignar) as fecha_asignar,{$valores} from asignar_ingresos_detalle as a,empleado as e,ingresos_egresos as i,departamento as d where a.id_ingreso_egreso=i.id_ineg and a.id_empleado=e.id_empleado and e.id_departamento=d.id_departamento and a.cod_asignar_ingresos={$id}
        GROUP BY e.id_empleado");
        
        return [
           "recupera"=> $recupera,
           "cabecera"=> $ingresos
        ];
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
        //
        $total=0;
        for ($a = 0; $a < count($request->contenidopr); $a++) {
            $total +=$request->contenidopr[$a]["valor"];
        }
        
        $select1=DB::select("SELECT id_ineg from asignar_ingresos where id_empleado=".$request->contenidopr[0]["id_empleado"]);  
        $select2=DB::select("SELECT id_ineg from asignar_ingresos where id_empleado=".$request->contenidopr[0]["id_empleado"]." and id_ineg=".$request->idtipoie." and fecha_asignar=".'"'.$request->fecha_asignar.'"');
        if($select2[0]->id_ineg!=$request->idtipoie){
                return "existe";
        }
        for ($a = 0; $a < count($request->contenidopr); $a++) {
            $ingresos = Asignar_ingresos::find($request->contenidopr[$a]["id_asignar_ingresos"]);
            $ingresos->fecha_asignar = $request->fecha_asignar;
            $ingresos->valor = $request->contenidopr[$a]["valor"];
            $ingresos->total_valor=$total;
            $ingresos->id_ineg = $request->idtipoie;
            $ingresos->tiene_detalle = 0;
            //$ingresos->id_empleado = $request->contenidopr[$a]["id_empleado"];
            //$ingresos->id_empresa = $request->id_empresa;
            //$ingresos->id_departamento = $request->id_departamento;
            $ingresos->save();
        }
    }
    public function update_nuevo(Request $request)
    {
        ini_set('max_execution_time', 53200);
        
        //dd($request->contenidopr[0]["id_ineg_0"]);
        $total=0;
        for ($a = 0; $a < count($request->contenidopr); $a++) {
            if(isset($request->contenidopr[$a]["id_ineg_0"])){
                $id_asie=DB::select("SELECT * from asignar_ingresos_detalle where id_empleado={$request->contenidopr[$a]["id_empleado"]} and id_ingreso_egreso={$request->contenidopr[$a]["id_ineg_0"]}");
                $aingd=AsiganrIngresosDetalle::find($id_asie[0]->id_asignar_ingresos_detalle);
                $aingd->valor=$request->contenidopr[$a]["valor_ineg_0"];
                $aingd->umodifica=$request->usuario;
                $aingd->fecha_asignar=$request->fecha_asignar;
                $aingd->save();
            }
            if(isset($request->contenidopr[$a]["id_ineg_1"])){
                $id_asie=DB::select("SELECT * from asignar_ingresos_detalle where id_empleado={$request->contenidopr[$a]["id_empleado"]} and id_ingreso_egreso={$request->contenidopr[$a]["id_ineg_1"]}");
                $aingd=AsiganrIngresosDetalle::find($id_asie[0]->id_asignar_ingresos_detalle);
                $aingd->valor=$request->contenidopr[$a]["valor_ineg_1"];
                $aingd->umodifica=$request->usuario;
                $aingd->fecha_asignar=$request->fecha_asignar;
                $aingd->save();
            }
            if(isset($request->contenidopr[$a]["id_ineg_2"])){
                $id_asie=DB::select("SELECT * from asignar_ingresos_detalle where id_empleado={$request->contenidopr[$a]["id_empleado"]} and id_ingreso_egreso={$request->contenidopr[$a]["id_ineg_2"]}");
                $aingd=AsiganrIngresosDetalle::find($id_asie[0]->id_asignar_ingresos_detalle);
                $aingd->valor=$request->contenidopr[$a]["valor_ineg_2"];
                $aingd->umodifica=$request->usuario;
                $aingd->fecha_asignar=$request->fecha_asignar;
                $aingd->save();
            }
            if(isset($request->contenidopr[$a]["id_ineg_3"])){
                $id_asie=DB::select("SELECT * from asignar_ingresos_detalle where id_empleado={$request->contenidopr[$a]["id_empleado"]} and id_ingreso_egreso={$request->contenidopr[$a]["id_ineg_3"]}");
                $aingd=AsiganrIngresosDetalle::find($id_asie[0]->id_asignar_ingresos_detalle);
                $aingd->valor=$request->contenidopr[$a]["valor_ineg_3"];
                $aingd->umodifica=$request->usuario;
                $aingd->fecha_asignar=$request->fecha_asignar;
                $aingd->save();
            }
            if(isset($request->contenidopr[$a]["id_ineg_4"])){
                $id_asie=DB::select("SELECT * from asignar_ingresos_detalle where id_empleado={$request->contenidopr[$a]["id_empleado"]} and id_ingreso_egreso={$request->contenidopr[$a]["id_ineg_4"]}");
                $aingd=AsiganrIngresosDetalle::find($id_asie[0]->id_asignar_ingresos_detalle);
                $aingd->valor=$request->contenidopr[$a]["valor_ineg_4"];
                $aingd->umodifica=$request->usuario;
                $aingd->fecha_asignar=$request->fecha_asignar;
                $aingd->save();
            }
            if(isset($request->contenidopr[$a]["id_ineg_5"])){
                $id_asie=DB::select("SELECT * from asignar_ingresos_detalle where id_empleado={$request->contenidopr[$a]["id_empleado"]} and id_ingreso_egreso={$request->contenidopr[$a]["id_ineg_5"]}");
                $aingd=AsiganrIngresosDetalle::find($id_asie[0]->id_asignar_ingresos_detalle);
                $aingd->valor=$request->contenidopr[$a]["valor_ineg_5"];
                $aingd->umodifica=$request->usuario;
                $aingd->fecha_asignar=$request->fecha_asignar;
                $aingd->save();
            }
            if(isset($request->contenidopr[$a]["id_ineg_6"])){
                $id_asie=DB::select("SELECT * from asignar_ingresos_detalle where id_empleado={$request->contenidopr[$a]["id_empleado"]} and id_ingreso_egreso={$request->contenidopr[$a]["id_ineg_6"]}");
                $aingd=AsiganrIngresosDetalle::find($id_asie[0]->id_asignar_ingresos_detalle);
                $aingd->valor=$request->contenidopr[$a]["valor_ineg_6"];
                $aingd->umodifica=$request->usuario;
                $aingd->fecha_asignar=$request->fecha_asignar;
                $aingd->save();
            }
            if(isset($request->contenidopr[$a]["id_ineg_7"])){
                $id_asie=DB::select("SELECT * from asignar_ingresos_detalle where id_empleado={$request->contenidopr[$a]["id_empleado"]} and id_ingreso_egreso={$request->contenidopr[$a]["id_ineg_7"]}");
                $aingd=AsiganrIngresosDetalle::find($id_asie[0]->id_asignar_ingresos_detalle);
                $aingd->valor=$request->contenidopr[$a]["valor_ineg_7"];
                $aingd->umodifica=$request->usuario;
                $aingd->fecha_asignar=$request->fecha_asignar;
                $aingd->save();
            }
            if(isset($request->contenidopr[$a]["id_ineg_8"])){
                $id_asie=DB::select("SELECT * from asignar_ingresos_detalle where id_empleado={$request->contenidopr[$a]["id_empleado"]} and id_ingreso_egreso={$request->contenidopr[$a]["id_ineg_8"]}");
                $aingd=AsiganrIngresosDetalle::find($id_asie[0]->id_asignar_ingresos_detalle);
                $aingd->valor=$request->contenidopr[$a]["valor_ineg_8"];
                $aingd->umodifica=$request->usuario;
                $aingd->fecha_asignar=$request->fecha_asignar;
                $aingd->save();
            }
            if(isset($request->contenidopr[$a]["id_ineg_9"])){
                $id_asie=DB::select("SELECT * from asignar_ingresos_detalle where id_empleado={$request->contenidopr[$a]["id_empleado"]} and id_ingreso_egreso={$request->contenidopr[$a]["id_ineg_9"]}");
                $aingd=AsiganrIngresosDetalle::find($id_asie[0]->id_asignar_ingresos_detalle);
                $aingd->valor=$request->contenidopr[$a]["valor_ineg_9"];
                $aingd->umodifica=$request->usuario;
                $aingd->fecha_asignar=$request->fecha_asignar;
                $aingd->save();
            }
            if(isset($request->contenidopr[$a]["id_ineg_10"])){
                $id_asie=DB::select("SELECT * from asignar_ingresos_detalle where id_empleado={$request->contenidopr[$a]["id_empleado"]} and id_ingreso_egreso={$request->contenidopr[$a]["id_ineg_10"]}");
                $aingd=AsiganrIngresosDetalle::find($id_asie[0]->id_asignar_ingresos_detalle);
                $aingd->valor=$request->contenidopr[$a]["valor_ineg_10"];
                $aingd->umodifica=$request->usuario;
                $aingd->fecha_asignar=$request->fecha_asignar;
                $aingd->save();
            }
            if(isset($request->contenidopr[$a]["id_ineg_11"])){
                $id_asie=DB::select("SELECT * from asignar_ingresos_detalle where id_empleado={$request->contenidopr[$a]["id_empleado"]} and id_ingreso_egreso={$request->contenidopr[$a]["id_ineg_11"]}");
                $aingd=AsiganrIngresosDetalle::find($id_asie[0]->id_asignar_ingresos_detalle);
                $aingd->valor=$request->contenidopr[$a]["valor_ineg_11"];
                $aingd->umodifica=$request->usuario;
                $aingd->fecha_asignar=$request->fecha_asignar;
                $aingd->save();
            }
           
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
        Asignar_ingresos::where('cod_asignar_ingresos',$id)->delete();
    }
    public function destroy_nuevo($id)
    {
        AsiganrIngresosDetalle::where('cod_asignar_ingresos',$id)->delete();
        Asignar_ingresos::where('cod_asignar_ingresos',$id)->delete();
    }
}
