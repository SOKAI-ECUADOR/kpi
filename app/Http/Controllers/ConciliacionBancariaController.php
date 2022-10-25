<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Asientos_contables_detalle;
use App\Models\Conciliacion;
use Carbon\Carbon;
include 'class/generarPDF.php';
include 'class/sendConciliacion.php';

use generarPDF;
use sendEmailConc;


class ConciliacionBancariaController extends Controller
{
    public function index($id){
        $select=DB::select('SELECT min(plc.nomcta) as cta,min(con.fecha_conciliacion) as fecha,sum(if(con.conciliación=1,if(con.debe is null,0,con.debe)+if(con.haber is null,0,con.haber),0)) as suma2,max(saldo_banco) as suma,cod_conciliacion,min(con.id_plan_cuentas) as id_plan_cuentas from conciliacion as con , plan_cuentas as plc where plc.id_plan_cuentas=con.id_plan_cuentas and con.id_empresa='.$id.' group by con.cod_conciliacion ORDER BY min(con.fecha_conciliacion) desc');
        return $select;
    }
    public function getPlancuentas(Request $request,$id){
      //$select=DB::select("SELECT DISTINCT plc.nomcta,plc.codcta,plc.id_plan_cuentas,plc.bansel,plc.id_banco from plan_cuentas as plc,asientos_detalle as asdt where plc.id_plan_cuentas=asdt.id_plan_cuentas and plc.bansel is not null and plc.id_empresa=".$id);
      if($request->buscar==''){
        $select=DB::select("SELECT nomcta,codcta,id_plan_cuentas,bansel,id_banco from plan_cuentas where bansel is not null and id_empresa=".$id." order by codcta asc");
      }else{
        $select=DB::select("SELECT nomcta,codcta,id_plan_cuentas,bansel,id_banco from plan_cuentas where bansel is not null and (nomcta like '%{$request->buscar}%' or codcta like '%{$request->buscar}%') and id_empresa=".$id." order by codcta asc");
      }
      
        //$select=DB::select("SELECT DISTINCT plc.nomcta,plc.codcta,plc.id_plan_cuentas,plc.bansel,plc.id_banco from plan_cuentas as plc,asientos_detalle as asdt,forma_pagos as fp where plc.id_plan_cuentas=asdt.id_plan_cuentas and fp.id_forma_pagos=asdt.id_forma_pagos and fp.descripcion like '%Cheque%' and plc.bansel is not null and plc.id_empresa=".$id);
        return $select;
    }
    public function getBanco($id){
      $select=DB::select("SELECT * from banco where id_banco=".$id);
      return $select;
    }
    public function getConciliacion(Request $request,$id){
        //dd($request);
        $fecha_inicio=DB::select("SELECT min(fecha) as fecha_inicio from asientos_detalle,asientos,proyecto where asientos_detalle.id_asientos=asientos.id_asientos and asientos_detalle.id_proyecto=proyecto.id_proyecto and proyecto.id_empresa=".$request->id_empresa." and (asientos.estado='Activo' or asientos.estado is null)");
        $actual = date("Y-m-d", strtotime($request->fecha));
        $month = date('m', strtotime($request->fecha));
        $year = date('Y', strtotime($request->fecha));
        $day = date("d", mktime(0,0,0, $month+1, 0, $year));              
        $fecha_final= date('Y-m-d', mktime(0,0,0, $month, '01', $year));
        $creado = strtotime ( '-1 day' , strtotime ( $fecha_final ) ) ;
        $anterior = date("Y-m-d", $creado);
        $anterior_2=date("Y-m-d",strtotime($anterior."- 1 month"));
        $select=DB::select("SELECT DISTINCT plc.codcta,plc.nomcta,asdt.debe,asdt.haber,asi.fecha,plc.id_plan_cuentas,asi.codigo,asi.concepto,pro.descripcion,asdt.fecha_de_pago,asdt.no_documento,asdt.id_detalle,asdt.conciliacion,fp.tipo_forma_pago as descripcion_fp,asicomp.codigo as codigo_asiento
        FROM asientos_detalle as asdt,plan_cuentas as plc,asientos as asi,proyecto as pro,forma_pagos as fp,asientos_comprobante as asicomp  
        where date(asi.fecha) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$request->fecha}') and fp.id_forma_pagos=asdt.id_forma_pagos and plc.id_plan_cuentas=asdt.id_plan_cuentas and pro.id_proyecto=asdt.id_proyecto  and pro.id_empresa={$request->id_empresa} and asi.id_asientos=asdt.id_asientos and asi.id_asientos_comprobante=asicomp.id_asientos_comprobante and asdt.conciliacion is null and plc.id_plan_cuentas=".$id." and (asi.estado='Activo' or asi.estado is null) order by asi.fecha asc");
        
        
        //$select=DB::select("SELECT DISTINCT plc.codcta,plc.nomcta,asdt.debe,asdt.haber,asi.fecha,plc.id_plan_cuentas,asi.codigo,asi.concepto,pro.descripcion,asdt.fecha_de_pago,asdt.no_documento,asdt.id_detalle,asdt.conciliacion
        //FROM asientos_detalle as asdt,plan_cuentas as plc,asientos as asi,proyecto as pro,forma_pagos as fp 
        //where date(asi.fecha) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$request->fecha}') and  plc.id_plan_cuentas=asdt.id_plan_cuentas and pro.id_proyecto=asi.id_proyecto and fp.id_forma_pagos=asdt.id_forma_pagos and fp.descripcion like '%Cheque%' and pro.id_empresa={$request->id_empresa} and asi.id_asientos=asdt.id_asientos and asdt.conciliacion is null and plc.id_plan_cuentas=".$id." order by asi.fecha asc");
        $reporte_plc=DB::select("SELECT if(sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber)) is null,0, sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber))) saldo_ant,plc.id_plan_cuentas,nomcta 
        from asientos_detalle,asientos as asi,plan_cuentas as plc,forma_pagos as fp,proyecto  as proy 
          where date(asi.fecha) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$request->fecha}') and fp.id_forma_pagos=asientos_detalle.id_forma_pagos 
          and asi.id_asientos=asientos_detalle.id_asientos and asi.id_proyecto=proy.id_proyecto and plc.id_plan_cuentas=asientos_detalle.id_plan_cuentas and plc.id_plan_cuentas={$id} and plc.id_empresa={$request->id_empresa}  and (asi.estado='Activo' or asi.estado is null) 
          GROUP BY plc.id_plan_cuentas ORDER BY plc.codcta asc");
        // dd("SELECT if(sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber)) is null,0, sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber))) saldo_ant,plc.id_plan_cuentas,nomcta 
        //   from asientos_detalle,asientos as asi,plan_cuentas as plc,forma_pagos as fp,proyecto  as proy 
        //   where date(asi.fecha) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$request->fecha}') and fp.id_forma_pagos=asientos_detalle.id_forma_pagos 
        //   and asi.id_asientos=asientos_detalle.id_asientos and asi.id_proyecto=proy.id_proyecto and plc.id_plan_cuentas=asientos_detalle.id_plan_cuentas and plc.id_plan_cuentas={$id} and plc.id_empresa={$request->id_empresa} and asientos_detalle.conciliacion is null
        //   GROUP BY plc.id_plan_cuentas ORDER BY plc.codcta asc");
          
          $saldo_ant=0;
          if($reporte_plc){
            $saldo_ant=$reporte_plc[0]->saldo_ant;
          }
          if($select){
            return ['conciliacion'=>$select,'sando_ant'=>$saldo_ant];
          }else{
            return ['conciliacion'=>[],'sando_ant'=>$saldo_ant];
          }
        
    }
    public function generarPdf(Request $request){
      $select=DB::select("SELECT conciliacion.*,asientos_detalle.id_forma_pagos,forma_pagos.tipo_forma_pago as descripcionfp,plan_cuentas.bansel,plan_cuentas.nomcta,plan_cuentas.codcta,banco.nombre_banco
      from conciliacion
      LEFT JOIN asientos_detalle
      on conciliacion.detalle_asiento=asientos_detalle.id_detalle
      LEFT JOIN forma_pagos
      on asientos_detalle.id_forma_pagos=forma_pagos.id_forma_pagos
      LEFT JOIN plan_cuentas
      on conciliacion.id_plan_cuentas=plan_cuentas.id_plan_cuentas
      LEFT JOIN banco
      on plan_cuentas.id_banco=banco.id_banco
      where conciliacion.cod_conciliacion={$request->cod_conciliacion} and conciliacion.id_empresa={$request->id_empresa}");
      $empresa=DB::select("SELECT * from empresa where id_empresa=".$request->id_empresa);
      if($request->destinatario==null && $request->email==null){
        $pdf=new generarPdf();
        $strPDF =$pdf->PDFConciliacionBancaria($select,$empresa,$select[0]->nomcta,$select[0]->codcta,$select[0]->bansel,$select[0]->nombre_banco);
        return response($strPDF, 200)->header('Content-Type', 'application/pdf');
      }else{
        $carpetanombre2 = constant("DATA_EMPRESA").$empresa[0]->id_empresa;
        $carpeta2 = $carpetanombre2."/Conciliacion/".$select[0]->id_plan_cuentas."/".$select[0]->fecha_conciliacion;
        if (!file_exists($carpeta2)) {
            mkdir($carpeta2, 0755,true);
        }
        $pdf=new generarPdf();
        $strPDF =$pdf->PDFConciliacionBancaria($select,$empresa,$select[0]->nomcta,$select[0]->codcta,$select[0]->bansel,$select[0]->nombre_banco,$carpeta2);
      }
      
    }
    public function generarEmail(Request $request){
      $datos=DB::select("SELECT empresa.*,conciliacion.fecha_conciliacion,plan_cuentas.nomcta,plan_cuentas.codcta,plan_cuentas.id_plan_cuentas from empresa,conciliacion,plan_cuentas where plan_cuentas.id_plan_cuentas=conciliacion.id_plan_cuentas and conciliacion.id_empresa=empresa.id_empresa and empresa.id_empresa=".$request->id_empresa." and conciliacion.cod_conciliacion=".$request->cod);
        //$rol=DB::select("SELECT primer_nombre,fechrol from rol_pago where fechrol=".'"'.$request->date.'"'." and id_departamento=".$request->id);
        //for ($a = 0; $a < count($datos); $a++) {
            $email = new sendEmailConc(); 
            $email->enviarConciliacion($datos[0],$request->email,$request->destinatario);
        //}

    }
    public function traerConciliacion($id){
      $select=DB::select("SELECT conciliacion.*,asientos_detalle.id_forma_pagos,forma_pagos.tipo_forma_pago as descripcion,plan_cuentas.bansel,plan_cuentas.nomcta,plan_cuentas.codcta,banco.nombre_banco,asientos_comprobante.codigo as codigo_asiento
      from conciliacion
      LEFT JOIN asientos_detalle
      on conciliacion.detalle_asiento=asientos_detalle.id_detalle
			LEFT JOIN asientos
      on asientos_detalle.id_asientos=asientos.id_asientos
			LEFT JOIN asientos_comprobante
      on asientos.id_asientos_comprobante=asientos_comprobante.id_asientos_comprobante
      LEFT JOIN forma_pagos
      on asientos_detalle.id_forma_pagos=forma_pagos.id_forma_pagos
      LEFT JOIN plan_cuentas
      on conciliacion.id_plan_cuentas=plan_cuentas.id_plan_cuentas
      LEFT JOIN banco
      on plan_cuentas.id_banco=banco.id_banco
      where conciliacion.cod_conciliacion={$id}
      ORDER BY fecha_reguistro asc");
      return $select;
    }
    public function actualizarRegistro($id){
      $hoy = Carbon::now();
      $conc=DB::select("SELECT * from conciliacion where cod_conciliacion={$id}");
      $fecha_inicio=DB::select("SELECT min(fecha) as fecha_inicio from asientos_detalle,asientos,proyecto where asientos_detalle.id_asientos=asientos.id_asientos and asientos_detalle.id_proyecto=proyecto.id_proyecto and proyecto.id_empresa=".$conc[0]->id_empresa);
      $select=DB::select("SELECT conciliacion.*,asientos_detalle.id_forma_pagos,forma_pagos.tipo_forma_pago as descripcion,plan_cuentas.bansel,plan_cuentas.nomcta,plan_cuentas.codcta,banco.nombre_banco,asientos_comprobante.codigo as codigo_asiento
      from conciliacion
      LEFT JOIN asientos_detalle
      on conciliacion.detalle_asiento=asientos_detalle.id_detalle
			LEFT JOIN asientos
      on asientos_detalle.id_asientos=asientos.id_asientos
			LEFT JOIN asientos_comprobante
      on asientos.id_asientos_comprobante=asientos_comprobante.id_asientos_comprobante
      LEFT JOIN forma_pagos
      on asientos_detalle.id_forma_pagos=forma_pagos.id_forma_pagos
      LEFT JOIN plan_cuentas
      on conciliacion.id_plan_cuentas=plan_cuentas.id_plan_cuentas
      LEFT JOIN banco
      on plan_cuentas.id_banco=banco.id_banco
      where conciliacion.cod_conciliacion={$id}
        UNION
      SELECT null as id_concilaicion,{$id} as cod_conciliacion,'{$conc[0]->fecha_conciliacion}' as fecha_conciliacion,asi.fecha as fecha_reguistro,asdt.fecha_de_pago,asi.codigo as codigo_comprobante,asdt.no_documento,asi.concepto,asdt.debe,asdt.haber,null as saldo_libro,null as saldo_cheque,null as nuevo_saldo,{$conc[0]->saldo_banco} as saldo_banco,null as conciliación,asdt.id_detalle as detalle_asiento,null as tipo_conciliacion,null as detalle_forma_pago,'{$hoy}' as fcrea,null as fmodifica,plc.id_plan_cuentas,plc.id_empresa,asdt.id_forma_pagos,fp.tipo_forma_pago as descripcion,plc.bansel,plc.nomcta,plc.codcta,banco.nombre_banco,ascp.codigo as codigo_asiento 
      from asientos_detalle as asdt
      INNER JOIN asientos as asi
      on asi.id_asientos=asdt.id_asientos
      INNER JOIN plan_cuentas as plc
      on plc.id_plan_cuentas=asdt.id_plan_cuentas
      INNER  JOIN forma_pagos as fp
      on asdt.id_forma_pagos=fp.id_forma_pagos
      INNER  JOIN asientos_comprobante as ascp
      on asi.id_asientos_comprobante=ascp.id_asientos_comprobante
      LEFT JOIN conciliacion as conc
      on asdt.id_detalle=conc.detalle_asiento
      LEFT JOIN banco
      on plc.id_banco=banco.id_banco
      where date(asi.fecha) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$conc[0]->fecha_conciliacion}') and asi.id_proyecto is not null and plc.id_empresa={$conc[0]->id_empresa} and plc.id_plan_cuentas={$conc[0]->id_plan_cuentas} and asdt.conciliacion is null and conc.id_conciliacion is null and (asi.estado='Activo' or asi.estado is null)
      order by fecha_reguistro asc");

      $reporte_plc=DB::select("SELECT if(sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber)) is null,0, sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber))) saldo_ant,plc.id_plan_cuentas,nomcta 
      from asientos_detalle,asientos as asi,plan_cuentas as plc,forma_pagos as fp,proyecto  as proy 
        where date(asi.fecha) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$conc[0]->fecha_conciliacion}') and fp.id_forma_pagos=asientos_detalle.id_forma_pagos 
        and asi.id_asientos=asientos_detalle.id_asientos and asi.id_proyecto=proy.id_proyecto and plc.id_plan_cuentas=asientos_detalle.id_plan_cuentas and plc.id_plan_cuentas={$conc[0]->id_plan_cuentas} and plc.id_empresa={$conc[0]->id_empresa} and (asi.estado='Activo' or asi.estado is null)  
        GROUP BY plc.id_plan_cuentas ORDER BY plc.codcta asc");

      $saldo_ant=0;
      if($reporte_plc){
        $saldo_ant=$reporte_plc[0]->saldo_ant;
      }
      return ['conciliacion'=>$select,'sando_ant'=>$saldo_ant];

    }
    public function updateAsientoDetalle(Request $request){

      ini_set('max_execution_time', 53200);

      $select=DB::select("SELECT max(cod_conciliacion) as cod_conciliacion from conciliacion");
      
      $codigo=0;
      if($select){
          $codigo=$select[0]->cod_conciliacion+1;
      }else{
          $codigo=1;
      }

      //dd($request->detalle[$a]["id_detalle"]);
        for($a = 0; $a < count($request->detalle); $a++){
          if(count($request->detalle)>0){
            if($request->detalle[$a]["conciliacion"]!==null){
              $detalle=Asientos_contables_detalle::find($request->detalle[$a]["id_detalle"]);
              $detalle->conciliacion=$codigo;
              $detalle->save();
            }
            //$request->detalle[$a]["conciliacion"];
            
          }
 
        }
        return $codigo;
    }
    public function store(Request $request){
      //dd($request->cheque_libro);
      /*$select=DB::select('SELECT cod_conciliacion from conciliacion ORDER BY id_conciliacion desc limit 1');
      $codigo=0;
      if($select){
          $codigo=$select[0]->cod_conciliacion+1;
      }else{
          $codigo=1;
      }*/
      ini_set('max_execution_time', 53200);
      if(count($request->detalle)>0){
        for($a = 0; $a < count($request->detalle); $a++){
          $id=0;
          $select_2=DB::select('SELECT id_conciliacion from conciliacion ORDER BY id_conciliacion desc limit 1');
          if($select_2){
            $id=$select_2[0]->id_conciliacion;
          }
          $conc= new Conciliacion();
          //$conc->id_conciliacion=$id+1;
          $conc->cod_conciliacion=$request->cod;
          $conc->fecha_conciliacion=$request->fecha_conciliacion;
          $conc->fecha_reguistro=$request->detalle[$a]["fecha"];
          $conc->fecha_de_pago=$request->detalle[$a]["fecha_de_pago"];
          $conc->codigo_comprobante=$request->detalle[$a]["codigo"];
          $conc->no_documento=$request->detalle[$a]["no_documento"];
          $conc->concepto=$request->detalle[$a]["concepto"];
          $conc->debe=$request->detalle[$a]["debe"];
          $conc->haber=$request->detalle[$a]["haber"];
          $conc->saldo_libro=$request->saldo_libro;
          $conc->saldo_cheque=$request->saldo_cheque;
          $conc->nuevo_saldo=$request->nuevo_saldo;
          $conc->saldo_banco=$request->saldo_banco;
          $conc->conciliación=$request->detalle[$a]["conciliacion"];
          $conc->detalle_asiento=$request->detalle[$a]["id_detalle"];
          $conc->id_plan_cuentas=$request->id_plan_cuentas;
          $conc->id_empresa=$request->id_empresa;
          $conc->save();
        }
        if($request->total_cheque_libro>0){
          for ($i=0; $i < count($request->cheque_libro); $i++) { 
            $conc_dos= new Conciliacion();
            //$conc_dos->id_conciliacion=$id+1;
            $conc_dos->cod_conciliacion=$request->cod;
            $conc_dos->fecha_conciliacion=$request->fecha_conciliacion;
            $conc_dos->fecha_reguistro=$request->cheque_libro[$i]["fecha_reguistro"];
            $conc_dos->fecha_de_pago=$request->cheque_libro[$i]["fecha_de_pago"];
            //$conc_dos->codigo_comprobante=$request->cheque_libro[$i]["codigo_comprobante"];
            $conc_dos->no_documento=$request->cheque_libro[$i]["no_documento"];
            $conc_dos->concepto=$request->cheque_libro[$i]["concepto"];
            //$conc_dos->debe=$request->cheque_libro[$i]["debe"];
            $conc_dos->haber=$request->cheque_libro[$i]["valor"];
            $conc_dos->saldo_libro=$request->saldo_libro;
            $conc_dos->saldo_cheque=$request->saldo_cheque;
            $conc_dos->nuevo_saldo=$request->nuevo_saldo;
            $conc_dos->saldo_banco=$request->saldo_banco;
            //$conc_dos->conciliación=$request->cheque_libro[$i]["conciliacion"];
            $conc_dos->tipo_conciliacion=$request->cheque_libro[$i]["tipo"];
            $conc_dos->id_plan_cuentas=$request->id_plan_cuentas;
            $conc_dos->id_empresa=$request->id_empresa;
            $conc_dos->save();
          }
        }
        if($request->total_transferencia_libro>0){
          for ($j=0; $j < count($request->transferecia_libro); $j++) { 
            $conc= new Conciliacion();
            //$conc->id_conciliacion=$jd+1;
            $conc->cod_conciliacion=$request->cod;
            $conc->fecha_conciliacion=$request->fecha_conciliacion;
            $conc->fecha_reguistro=$request->transferecia_libro[$j]["fecha_reguistro"];
            $conc->fecha_de_pago=$request->transferecia_libro[$j]["fecha_de_pago"];
            //$conc->codigo_comprobante=$request->transferecia_libro[$j]["codigo_comprobante"];
            $conc->no_documento=$request->transferecia_libro[$j]["no_documento"];
            $conc->concepto=$request->transferecia_libro[$j]["concepto"];
            //$conc->debe=$request->transferecia_libro[$j]["debe"];
            $conc->haber=$request->transferecia_libro[$j]["valor"];
            $conc->saldo_libro=$request->saldo_libro;
            $conc->saldo_cheque=$request->saldo_cheque;
            $conc->nuevo_saldo=$request->nuevo_saldo;
            $conc->saldo_banco=$request->saldo_banco;
            //$conc->conciliación=$request->transferecia_libro[$j]["conciliacion"];
            $conc->tipo_conciliacion=$request->transferecia_libro[$j]["tipo"];
            $conc->id_plan_cuentas=$request->id_plan_cuentas;
            $conc->id_empresa=$request->id_empresa;
            $conc->save();
          }
        }
        if($request->total_deposito_libro>0){
          for ($k=0; $k < count($request->deposito_libro); $k++) { 
            $conc= new Conciliacion();
            //$conc->id_conciliacion=$id+1;
            $conc->cod_conciliacion=$request->cod;
            $conc->fecha_conciliacion=$request->fecha_conciliacion;
            $conc->fecha_reguistro=$request->deposito_libro[$k]["fecha_reguistro"];
            $conc->fecha_de_pago=$request->deposito_libro[$k]["fecha_de_pago"];
            //$conc->codigo_comprobante=$request->deposito_libro[$k]["codigo_comprobante"];
            $conc->no_documento=$request->deposito_libro[$k]["no_documento"];
            $conc->concepto=$request->deposito_libro[$k]["concepto"];
            $conc->debe=$request->deposito_libro[$k]["valor"];
            //$conc->haber=$request->deposito_libro[$k]["valor"];
            $conc->saldo_libro=$request->saldo_libro;
            $conc->saldo_cheque=$request->saldo_cheque;
            $conc->nuevo_saldo=$request->nuevo_saldo;
            $conc->saldo_banco=$request->saldo_banco;
            //$conc->conciliación=$request->deposito_libro[$k]["conciliacion"];
            $conc->tipo_conciliacion=$request->deposito_libro[$k]["tipo"];
            $conc->id_plan_cuentas=$request->id_plan_cuentas;
            $conc->id_empresa=$request->id_empresa;
            $conc->save();
          }
        }
        return "se guardo CON detalles";
      }else{
        $conc= new Conciliacion();
        $conc->cod_conciliacion=$request->cod;
        $conc->fecha_conciliacion=$request->fecha_conciliacion;
        $conc->saldo_libro=$request->saldo_libro;
        $conc->saldo_cheque=$request->saldo_cheque;
        $conc->nuevo_saldo=$request->nuevo_saldo;
        $conc->saldo_banco=$request->saldo_banco;
        $conc->id_plan_cuentas=$request->id_plan_cuentas;
        $conc->id_empresa=$request->id_empresa;
        $conc->save();
        return "se guardo sin detalles";
      }
      
      
      
    }
    public function updateAsientoDetalleEditCon(Request $request){
      if(count($request->detalle)>0){
        for($a = 0; $a < count($request->detalle); $a++){
          $detalle=Asientos_contables_detalle::find($request->detalle[$a]["detalle_asiento"]);
          if($request->detalle[$a]["conciliación"]!==null){
            $detalle->conciliacion=$request->cod;
            $detalle->save();
          }
          //$request->detalle[$a]["conciliacion"];
          
        }
      }
      

    }
    public function update(Request $request){
      ini_set('max_execution_time', 53200);
      if(count($request->detalle)>0 || count($request->cheque_libro)>0 || count($request->transferecia_libro)>0 || count($request->deposito_libro)){
        for($a = 0; $a < count($request->detalle); $a++){
          if(isset($request->detalle[$a]["id_conciliacion"])){
              $conc=Conciliacion::find($request->detalle[$a]["id_conciliacion"]);
              //$conc->id_conciliacion=$id+1;
              //$conc->cod_conciliacion=$request->cod;
              $conc->fecha_conciliacion=$request->fecha_conciliacion;
              $conc->fecha_reguistro=$request->detalle[$a]["fecha_conciliacion"];
              $conc->fecha_de_pago=$request->detalle[$a]["fecha_de_pago"];
              //$conc->codigo_comprobante=$request->detalle[$a]["codigo_comprobante"];
              $conc->no_documento=$request->detalle[$a]["no_documento"];
              $conc->concepto=$request->detalle[$a]["concepto"];
              $conc->debe=$request->detalle[$a]["debe"];
              $conc->haber=$request->detalle[$a]["haber"];
              $conc->saldo_libro=$request->saldo_libro;
              $conc->saldo_cheque=$request->saldo_cheque;
              $conc->nuevo_saldo=$request->nuevo_saldo;
              $conc->saldo_banco=$request->saldo_banco;
              $conc->conciliación=$request->detalle[$a]["conciliación"];
              //$conc->detalle_asiento=$request->detalle[$a]["id_detalle"];
              //$conc->id_plan_cuentas=$request->id_plan_cuentas;
              //$conc->id_empresa=$request->id_empresa;
              $conc->save();
          }else{
              $conc=new Conciliacion();
              //$conc->id_conciliacion=$id+1;
              $conc->cod_conciliacion=$request->cod;
              $conc->fecha_conciliacion=$request->fecha_conciliacion;
              $conc->fecha_reguistro=$request->detalle[$a]["fecha_conciliacion"];
              $conc->fecha_de_pago=$request->detalle[$a]["fecha_de_pago"];
              $conc->codigo_comprobante=$request->detalle[$a]["codigo_comprobante"];
              $conc->no_documento=$request->detalle[$a]["no_documento"];
              $conc->concepto=$request->detalle[$a]["concepto"];
              $conc->debe=$request->detalle[$a]["debe"];
              $conc->haber=$request->detalle[$a]["haber"];
              $conc->saldo_libro=$request->saldo_libro;
              $conc->saldo_cheque=$request->saldo_cheque;
              $conc->nuevo_saldo=$request->nuevo_saldo;
              $conc->saldo_banco=$request->saldo_banco;
              $conc->conciliación=$request->detalle[$a]["conciliación"];
              $conc->detalle_asiento=$request->detalle[$a]["detalle_asiento"];
              $conc->id_plan_cuentas=$request->id_plan_cuentas;
              $conc->id_empresa=$request->id_empresa;
              $conc->save();
          }
          
        }
        if($request->total_cheque_libro>0){
          for ($i=0; $i < count($request->cheque_libro); $i++) { 
            if(isset($request->cheque_libro[$i]["id_conciliacion"])){
              $conc_dos=Conciliacion::find($request->cheque_libro[$i]["id_conciliacion"]);
              //$conc_dos->id_conciliacion=$id+1;
              //$conc_dos->cod_conciliacion=$request->cod;
              $conc_dos->fecha_conciliacion=$request->fecha_conciliacion;
              $conc_dos->fecha_reguistro=$request->cheque_libro[$i]["fecha_reguistro"];
              $conc_dos->fecha_de_pago=$request->cheque_libro[$i]["fecha_de_pago"];
              //$conc_dos->codigo_comprobante=$request->cheque_libro[$i]["codigo_comprobante"];
              $conc_dos->no_documento=$request->cheque_libro[$i]["no_documento"];
              $conc_dos->concepto=$request->cheque_libro[$i]["concepto"];
              //$conc_dos->debe=$request->cheque_libro[$i]["debe"];
              $conc_dos->haber=$request->cheque_libro[$i]["valor"];
              $conc_dos->saldo_libro=$request->saldo_libro;
              $conc_dos->saldo_cheque=$request->saldo_cheque;
              $conc_dos->nuevo_saldo=$request->nuevo_saldo;
              $conc_dos->saldo_banco=$request->saldo_banco;
              //$conc_dos->conciliación=$request->cheque_libro[$i]["conciliacion"];
              //$conc_dos->tipo_conciliacion=$request->cheque_libro[$i]["tipo"];
              //$conc_dos->id_plan_cuentas=$request->id_plan_cuentas;
              //$conc_dos->id_empresa=$request->id_empresa;
              $conc_dos->save();
            }else{
              $conc_dos= new Conciliacion();
              //$conc_dos->id_conciliacion=$id+1;
              $conc_dos->cod_conciliacion=$request->cod;
              $conc_dos->fecha_conciliacion=$request->fecha_conciliacion;
              $conc_dos->fecha_reguistro=$request->cheque_libro[$i]["fecha_reguistro"];
              $conc_dos->fecha_de_pago=$request->cheque_libro[$i]["fecha_de_pago"];
              //$conc_dos->codigo_comprobante=$request->cheque_libro[$i]["codigo_comprobante"];
              $conc_dos->no_documento=$request->cheque_libro[$i]["no_documento"];
              $conc_dos->concepto=$request->cheque_libro[$i]["concepto"];
              //$conc_dos->debe=$request->cheque_libro[$i]["debe"];
              $conc_dos->haber=$request->cheque_libro[$i]["valor"];
              $conc_dos->saldo_libro=$request->saldo_libro;
              $conc_dos->saldo_cheque=$request->saldo_cheque;
              $conc_dos->nuevo_saldo=$request->nuevo_saldo;
              $conc_dos->saldo_banco=$request->saldo_banco;
              //$conc_dos->conciliación=$request->cheque_libro[$i]["conciliacion"];
              $conc_dos->tipo_conciliacion=$request->cheque_libro[$i]["tipo"];
              $conc_dos->id_plan_cuentas=$request->id_plan_cuentas;
              $conc_dos->id_empresa=$request->id_empresa;
              $conc_dos->save();
            }
          }
        }
        if($request->total_transferencia_libro>0){
          for ($j=0; $j < count($request->transferecia_libro); $j++) { 
            if(isset($request->transferecia_libro[$j]["id_conciliacion"])){
              $conc=Conciliacion::find($request->transferecia_libro[$j]["id_conciliacion"]);
              //$conc->id_conciliacion=$jd+1;
              //$conc->cod_conciliacion=$request->cod;
              $conc->fecha_conciliacion=$request->fecha_conciliacion;
              $conc->fecha_reguistro=$request->transferecia_libro[$j]["fecha_reguistro"];
              $conc->fecha_de_pago=$request->transferecia_libro[$j]["fecha_de_pago"];
              //$conc->codigo_comprobante=$request->transferecia_libro[$j]["codigo_comprobante"];
              $conc->no_documento=$request->transferecia_libro[$j]["no_documento"];
              $conc->concepto=$request->transferecia_libro[$j]["concepto"];
              //$conc->debe=$request->transferecia_libro[$j]["debe"];
              $conc->haber=$request->transferecia_libro[$j]["valor"];
              $conc->saldo_libro=$request->saldo_libro;
              $conc->saldo_cheque=$request->saldo_cheque;
              $conc->nuevo_saldo=$request->nuevo_saldo;
              $conc->saldo_banco=$request->saldo_banco;
              //$conc->conciliación=$request->transferecia_libro[$j]["conciliacion"];
              //$conc->tipo_conciliacion=$request->transferecia_libro[$j]["tipo"];
              //$conc->id_plan_cuentas=$request->id_plan_cuentas;
              //$conc->id_empresa=$request->id_empresa;
              $conc->save();
            }else{
              $conc= new Conciliacion();
              //$conc->id_conciliacion=$jd+1;
              $conc->cod_conciliacion=$request->cod;
              $conc->fecha_conciliacion=$request->fecha_conciliacion;
              $conc->fecha_reguistro=$request->transferecia_libro[$j]["fecha_reguistro"];
              $conc->fecha_de_pago=$request->transferecia_libro[$j]["fecha_de_pago"];
              //$conc->codigo_comprobante=$request->transferecia_libro[$j]["codigo_comprobante"];
              $conc->no_documento=$request->transferecia_libro[$j]["no_documento"];
              $conc->concepto=$request->transferecia_libro[$j]["concepto"];
              //$conc->debe=$request->transferecia_libro[$j]["debe"];
              $conc->haber=$request->transferecia_libro[$j]["valor"];
              $conc->saldo_libro=$request->saldo_libro;
              $conc->saldo_cheque=$request->saldo_cheque;
              $conc->nuevo_saldo=$request->nuevo_saldo;
              $conc->saldo_banco=$request->saldo_banco;
              //$conc->conciliación=$request->transferecia_libro[$j]["conciliacion"];
              $conc->tipo_conciliacion=$request->transferecia_libro[$j]["tipo"];
              $conc->id_plan_cuentas=$request->id_plan_cuentas;
              $conc->id_empresa=$request->id_empresa;
              $conc->save();
            }
            
          }
        }
        if($request->total_deposito_libro>0){
          for ($k=0; $k < count($request->deposito_libro); $k++) { 
            if(isset($request->deposito_libro[$k]["id_conciliacion"])){
              $conc= Conciliacion::find($request->deposito_libro[$k]["id_conciliacion"]);
              //$conc->id_conciliacion=$id+1;
              //$conc->cod_conciliacion=$request->cod;
              $conc->fecha_conciliacion=$request->fecha_conciliacion;
              $conc->fecha_reguistro=$request->deposito_libro[$k]["fecha_reguistro"];
              $conc->fecha_de_pago=$request->deposito_libro[$k]["fecha_de_pago"];
              //$conc->codigo_comprobante=$request->deposito_libro[$k]["codigo_comprobante"];
              $conc->no_documento=$request->deposito_libro[$k]["no_documento"];
              $conc->concepto=$request->deposito_libro[$k]["concepto"];
              $conc->debe=$request->deposito_libro[$k]["valor"];
              //$conc->haber=$request->deposito_libro[$k]["valor"];
              $conc->saldo_libro=$request->saldo_libro;
              $conc->saldo_cheque=$request->saldo_cheque;
              $conc->nuevo_saldo=$request->nuevo_saldo;
              $conc->saldo_banco=$request->saldo_banco;
              //$conc->conciliación=$request->deposito_libro[$k]["conciliacion"];
              //$conc->tipo_conciliacion=$request->deposito_libro[$k]["tipo"];
              //$conc->id_plan_cuentas=$request->id_plan_cuentas;
              //$conc->id_empresa=$request->id_empresa;
              $conc->save();
            }else{
              $conc= new Conciliacion();
              //$conc->id_conciliacion=$id+1;
              $conc->cod_conciliacion=$request->cod;
              $conc->fecha_conciliacion=$request->fecha_conciliacion;
              $conc->fecha_reguistro=$request->deposito_libro[$k]["fecha_reguistro"];
              $conc->fecha_de_pago=$request->deposito_libro[$k]["fecha_de_pago"];
              //$conc->codigo_comprobante=$request->deposito_libro[$k]["codigo_comprobante"];
              $conc->no_documento=$request->deposito_libro[$k]["no_documento"];
              $conc->concepto=$request->deposito_libro[$k]["concepto"];
              $conc->debe=$request->deposito_libro[$k]["valor"];
              //$conc->haber=$request->deposito_libro[$k]["valor"];
              $conc->saldo_libro=$request->saldo_libro;
              $conc->saldo_cheque=$request->saldo_cheque;
              $conc->nuevo_saldo=$request->nuevo_saldo;
              $conc->saldo_banco=$request->saldo_banco;
              //$conc->conciliación=$request->deposito_libro[$k]["conciliacion"];
              $conc->tipo_conciliacion=$request->deposito_libro[$k]["tipo"];
              $conc->id_plan_cuentas=$request->id_plan_cuentas;
              $conc->id_empresa=$request->id_empresa;
              $conc->save();
            }
          }
        }
      }else{
        DB::update("UPDATE conciliacion set fecha_conciliacion='{$request->fecha_conciliacion}',saldo_libro='$request->saldo_libro',saldo_cheque='$request->saldo_cheque',nuevo_saldo='$request->nuevo_saldo',saldo_banco='$request->saldo_banco' where cod_conciliacion='$request->cod' and id_empresa={$request->id_empresa}");
      }
      
    }
    public function eliminar($id){
      Conciliacion::where('cod_conciliacion','=',$id)->delete();
      Asientos_contables_detalle::where('conciliacion',$id)->update(['conciliacion'=>null]);
    }
    public function eliminarlibro($id){
      Conciliacion::where('id_conciliacion','=',$id)->delete();
    }
}
