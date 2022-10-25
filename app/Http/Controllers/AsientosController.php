<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Asientos;
use App\Models\Asientos_comprobante;
use App\Models\Asientos_comprobante_automaticos;
use App\Models\Asientos_contables_detalle;
use App\Models\Plancuenta;
use App\Models\Factura;
use App\Models\Producto;
use App\Models\Proyecto;
use App\Models\RolPago;
use App\Models\RolProviciones;
use App\Models\FacturaCompra;
use App\Models\Cuentaporcobrar;
use App\Models\Ctas_cobrar_pagos;
use App\Models\Cuentaporpagar;
use App\Models\Ctas_pagar_pagos;
use App\Models\Notacredito;
use App\Models\Notadebito;
use App\Models\NotacreditoCompra;
use App\Models\Notadebitocompra;
use App\Models\Importacion;
use App\Models\BodegaEgreso;
use App\Models\BodegaIngreso;
use App\Models\BodegaTransferencia;
use App\Models\Depreciacion;
use App\Models\Conciliacion;
use App\Models\NotaVenta;
use App\Models\LiquidacionCompra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\ProcesoProduccion;


include 'class/generarReportes.php';
include 'class/sendEmail.php';

use sendEmailConc;
use generarReportes;
use sendEmail;

class AsientosController extends Controller
{
    /**
     * Guarda asientos contables manuales
     * @author Gabriel Costta
     * @param Illuminate\Http\Request
     */
    public function guardarAsientosContablesManuales(Request $request)
    {   //dd($request);
        $hora_actual= date("H:i:s");
        $ultimoNumero="";
        //$ultimoNumero = $this->obtenerUltimoNumeroDeAsientoContableManual(null,$request->cabecera["comprobante"]);
        DB::transaction(function () use ($request, $ultimoNumero,$hora_actual) {
            $asientos_cabecera = new Asientos();
            $asientos_cabecera->id_asientos_comprobante = $request->cabecera["comprobante"];
            $asientos_cabecera->numero = $request->cabecera["numero"];
            $asientos_cabecera->automatico = 0;
            $asientos_cabecera->fecha = $request->cabecera["fecha"]." ".$hora_actual;
            $asientos_cabecera->razon_social = $request->cabecera["razon_social"];
            $asientos_cabecera->tipo_identificacion = $request->tipo_identificacion;
            $asientos_cabecera->ruc_ci = $request->cabecera["ruc_ci"];
            $asientos_cabecera->concepto = $request->cabecera["concepto"];
            $asientos_cabecera->ucrea = $request->ucrea;
            $asientos_cabecera->id_proyecto = $request->id_proyecto_asiento;
            $asientos_cabecera->id_empresa = $request->id_empresa;
            $asientos_comprobante = Asientos_comprobante::find($request->cabecera["comprobante"]);
            $asientos_cabecera->id_asientos_comprobante = $asientos_comprobante->id_asientos_comprobante;
            $asientos_cabecera->codigo = $asientos_comprobante->codigo . "-" . $request->cabecera["numero"];
            
            $asientos_cabecera->save();
           
            foreach ($request->asientosContablesManuales as $asientos) {
                $proyecto = Proyecto::find($asientos["detalle"]["id_proyecto"]);
                // dd($request->asientosContablesManuales);
                $asientosContablesManueales = new Asientos_contables_detalle();
                $asientosContablesManueales->proyecto = $proyecto->descripcion;
                $asientosContablesManueales->debe = $asientos["detalle"]["debe"];
                $asientosContablesManueales->haber = $asientos["detalle"]["haber"];
                if ($asientos["detalle"]["typeCount"] == 1) {
                    if(isset($asientos["detalle"]["no_documento"])){
                        $asientosContablesManueales->no_documento = $asientos["detalle"]["no_documento"];
                    }
                    $asientosContablesManueales->fecha_de_pago = $asientos["detalle"]["fecha"];
                    $asientosContablesManueales->fecha_creacion_asiento =$request->cabecera["fecha"];
                    if(isset($asientos["detalle"]["id_forma_pago"])){
                        $asientosContablesManueales->id_forma_pagos = $asientos["detalle"]["id_forma_pago"];
                    }
                }
                $asientosContablesManueales->ucrea = $request->ucrea;
                $asientosContablesManueales->id_plan_cuentas = $asientos["detalle"]["idCuentaContable"];
                $asientosContablesManueales->id_asientos = $asientos_cabecera->id_asientos;
                $asientosContablesManueales->id_proyecto = $asientos["detalle"]["id_proyecto"];
                $asientosContablesManueales->save();
            }
        });

        return [
            'mensaje' => 'Registro de asientos contables exitoso',
        ];
    }
    public function obtenerImpresora(){
        $ruta_powershell = 'c:\Windows\System32\WindowsPowerShell\v1.0\powershell.exe'; #Necesitamos el powershell
        $opciones_para_ejecutar_comando = "-c";#Ejecutamos el powershell y necesitamos el "-c" para decirle que ejecutaremos un comando
        $espacio = " "; #ayudante para concatenar
        $comillas = '"'; #ayudante para concatenar
        $comando = 'get-WmiObject -class Win32_printer |ft name'; #Comando de powershell para obtener lista de impresoras
        $lista_de_impresoras = array(); #Aquí pondremos las impresoras
        exec(
            $ruta_powershell
            . $espacio
            . $opciones_para_ejecutar_comando
            . $espacio
            . $comillas
            . $comando
            . $comillas,
            $resultado,
            $codigo_salida);

        if ($codigo_salida === 0) {
            if (is_array($resultado)) {
                #Omitir los primeros 3 datos del arreglo, pues son el encabezado
                for($x = 3; $x < count($resultado); $x++){
                    $impresora = trim($resultado[$x]);
                    if (strlen($impresora) > 0) # Ignorar los espacios en blanco o líneas vacías
                        array_push($lista_de_impresoras, $impresora);
                }
            }
            $impresoras=$lista_de_impresoras;
            return ['recupera'=>$impresoras];

        } else {
            return ['recupera'=>"Error al ejecutar el comando."];
        }
    }

    /**
     * Edita asientos contables manuales
     * @author Gabriel Costta
     * @param Illuminate\Http\Request
     */
    public function editarAsientosContablesManuales(Request $request)
    {
        ini_set('max_execution_time', 53200);
        DB::transaction(function () use ($request) {
            $asientos_cabecera = Asientos::find($request->cabecera["id_asientos"]);
            $asientos_cabecera->numero = $request->cabecera["numero"];
            $asientos_cabecera->fecha = $request->cabecera["fecha"];
            $asientos_cabecera->razon_social = $request->cabecera["razon_social"];
            $asientos_cabecera->ruc_ci = $request->cabecera["ruc_ci"];
            $asientos_cabecera->concepto = $request->cabecera["concepto"];
            $asientos_cabecera->umodifica = $request->umodifica;
            $asientos_cabecera->id_proyecto = $request->cabecera["idProyecto"];
            $asientos_cabecera->id_empresa = $request->id_empresa;
            // dd($asientos_cabecera);

            $asientos_comprobante = Asientos_comprobante::find($request->cabecera["comprobante"]);

            $asientos_cabecera->id_asientos_comprobante = $asientos_comprobante->id_asientos_comprobante;
            $asientos_cabecera->codigo = $asientos_comprobante->codigo . "-" . $request->cabecera["numero"];
            $asientos_cabecera->save();
            
            foreach ($request->asientosContablesManuales as $asientos) {
                $proyecto = Proyecto::find($asientos["detalle"]["id_proyecto"]);
                if ($asientos["id_detalle"] != null) {
                    $id_forma_pago=null;
                    if(isset($asientos["detalle"]["id_forma_pago"])){
                        $id_forma_pago=$asientos["detalle"]["id_forma_pago"];
                    }else{
                        if(isset($asientos["detalle"]["id_pago_sri"])){
                            $id_forma_pago=$asientos["detalle"]["id_pago_sri"];
                        }
                    }
                    Asientos_contables_detalle::where("id_detalle", "=", $asientos["id_detalle"])
                        ->update([
                            "no_documento" => $asientos["detalle"]["no_documento"],
                            "proyecto" => $proyecto->descripcion,
                            "debe" => $asientos["detalle"]["debe"],
                            "haber" => $asientos["detalle"]["haber"],
                            "fecha_de_pago" => $asientos["detalle"]["fecha"],
                            "fecha_creacion_asiento"=>$request->cabecera["fecha"],
                            "umodifica" =>$request->umodifica,
                            "id_plan_cuentas" => $asientos["detalle"]["idCuentaContable"],
                            "id_asientos" => $asientos_cabecera->id_asientos,
                            "id_forma_pagos" => $id_forma_pago,
                            "id_proyecto"=>$asientos["detalle"]["id_proyecto"]
                        ]);
                    $conciliacion=DB::select("SELECT * from conciliacion where detalle_asiento={$asientos["id_detalle"]}");
                    if(count($conciliacion)>0){
                        $conc=Conciliacion::find($conciliacion[0]->id_conciliacion);
                        $conc->fecha_reguistro=$request->cabecera["fecha"];
                        $conc->fecha_de_pago=$asientos["detalle"]["fecha"];
                        $conc->codigo_comprobante=$asientos_comprobante->codigo . "-" . $request->cabecera["numero"];
                        $conc->no_documento=$asientos["detalle"]["no_documento"];
                        $conc->concepto=$request->cabecera["concepto"];
                        $conc->debe=$asientos["detalle"]["debe"];
                        $conc->haber=$asientos["detalle"]["haber"];
                        $conc->save();
                    }
                } else {
                    $id_forma_pago=null;

                        if(isset($asientos["detalle"]["id_pago_sri"])){
                            $id_forma_pago=$asientos["detalle"]["id_pago_sri"];
                        }
                    //echo $id_forma_pago;
                    $asientosContablesManueales = new Asientos_contables_detalle();
                    $asientosContablesManueales->no_documento = $asientos["detalle"]["no_documento"];
                    $asientosContablesManueales->proyecto = $proyecto->descripcion;
                    $asientosContablesManueales->debe = $asientos["detalle"]["debe"];
                    $asientosContablesManueales->haber = $asientos["detalle"]["haber"];
                    $asientosContablesManueales->fecha_de_pago = $asientos["detalle"]["fecha"];
                    $asientosContablesManueales->umodifica = $request->umodifica;
                    $asientosContablesManueales->id_plan_cuentas = $asientos["detalle"]["idCuentaContable"];
                    $asientosContablesManueales->id_asientos = $asientos_cabecera->id_asientos;
                    $asientosContablesManueales->id_forma_pagos = $id_forma_pago;
                    $asientosContablesManueales->id_proyecto = $asientos["detalle"]["id_proyecto"];
                    $asientosContablesManueales->save();
                }
            }
            foreach ($request->asientosContablesEliminados as $asientosEliminados) {
                if ($asientosEliminados != null) {
                    Asientos_contables_detalle::where("id_detalle", "=", $asientosEliminados)->delete();
                }
            }
        });
        return [
            'mensaje' => 'Actualizacion de asientos contables exitoso',
        ];
    }
    public function getPlanCuenta(Request $request){
        if($request->buscar==''){
            $recupera=DB::select("SELECT plc.codcta,plc.nomcta,plc.id_plan_cuentas from asientos_detalle as asi,plan_cuentas as plc where plc.id_plan_cuentas=asi.id_plan_cuentas and plc.id_empresa=".$request->id." group by plc.id_plan_cuentas ORDER BY plc.codcta asc"); 
        }else{
            $recupera=DB::select("SELECT plc.codcta,plc.nomcta,plc.id_plan_cuentas from asientos_detalle as asi,plan_cuentas as plc where plc.id_plan_cuentas=asi.id_plan_cuentas and (plc.codcta like '%{$request->buscar}%' or plc.nomcta like '%{$request->buscar}%') and plc.id_empresa=".$request->id." group by plc.id_plan_cuentas ORDER BY plc.codcta asc"); 
        }
      
      return [
        'recupera' => $recupera
    ]; 
    }
    public function generarPdf(Request $request){
        $select=DB::select("SELECT asientos.codigo,asientos.ucrea,asientos_comprobante.tipo,asientos.fecha,asientos.razon_social,asientos.tipo_identificacion,asientos.ruc_ci,asientos.concepto,asientos_detalle.debe,asientos_detalle.haber,plan_cuentas.nomcta,plan_cuentas.codcta,proyecto.descripcion,forma_pagos.descripcion as descripcion_forma_p,asientos_detalle.fecha_de_pago,asientos_detalle.no_documento
        from asientos
        INNER JOIN asientos_comprobante
        on asientos_comprobante.id_asientos_comprobante=asientos.id_asientos_comprobante
        INNER JOIN asientos_detalle
        on asientos_detalle.id_asientos=asientos.id_asientos
        INNER JOIN plan_cuentas
        on asientos_detalle.id_plan_cuentas=plan_cuentas.id_plan_cuentas
        INNER JOIN proyecto
        on asientos_detalle.id_proyecto=proyecto.id_proyecto
        LEFT JOIN forma_pagos
        on asientos_detalle.id_forma_pagos=forma_pagos.id_forma_pagos
        where asientos.id_asientos=".$request->id_asientos);
        $empresa=DB::select("SELECT * from empresa where id_empresa=".$request->id_empresa);
        $usuario=DB::select("SELECT CONCAT(nombres,' ',apellidos) as nombre from user where id=".$select[0]->ucrea);
        if($request->destinatario==null && $request->email==null){
          $pdf=new generarReportes();
          $strPDF =$pdf->PDFAsientos($select,$empresa,$select[0]->nomcta,$usuario[0]->nombre);
          return response($strPDF, 200)->header('Content-Type', 'application/pdf');
        }else{
          $carpetanombre2 = constant("DATA_EMPRESA").$empresa[0]->id_empresa;
          $carpeta2 = $carpetanombre2."/Conciliacion/".$select[0]->id_plan_cuentas."/".$select[0]->fecha_conciliacion;
          if (!file_exists($carpeta2)) {
              mkdir($carpeta2, 0755,true);
          }
          $pdf=new generarReportes();
          $strPDF =$pdf->PDFAsientos($select,$empresa,$select[0]->nomcta,$usuario[0]->nombre,$carpeta2);
        }
        
      }
    public function generarCheque(Request $request){
                $empresa=DB::select("SELECT empresa.* from asientos,proyecto,empresa where proyecto.id_proyecto=asientos.id_proyecto and proyecto.id_empresa=empresa.id_empresa and asientos.id_asientos={$request->id_asientos}");
                $detalle=DB::select("SELECT asientos_detalle.*,asientos.razon_social,ciudad.nombre as ciudad,if(forma_pagos.descripcion like '%Pichincha%','Pichincha',if(forma_pagos.descripcion like '%Internacional%','Internacional','Produbanco')) as cheque from asientos_detalle 
                INNER JOIN forma_pagos
                on forma_pagos.id_forma_pagos=asientos_detalle.id_forma_pagos
                INNER JOIN asientos
                on asientos.id_asientos=asientos_detalle.id_asientos
                INNER JOIN proyecto
                on asientos.id_proyecto=proyecto.id_proyecto
                INNER JOIN empresa
                on empresa.id_empresa=proyecto.id_empresa
                INNER JOIN ciudad
                on ciudad.id_ciudad=empresa.id_ciudad
                where forma_pagos.descripcion like '%CHEQUE%' and asientos_detalle.id_asientos={$request->id_asientos}");
                if($empresa[0]->nombre_empresa=='CPM'){
                    $Reportes = new generarReportes();
                    $strPDF = $Reportes->PDFChequeAsientoCPM($detalle);
                    return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                }else{
                    $Reportes = new generarReportes();
                    $strPDF = $Reportes->PDFChequeAsiento($detalle);
                    return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                }
                
    }
    public function generarReporte(Request $request){
        //dd($request);
        $queries = [];
        $debe_haber = [];
        $inners = [];
        $innersAuxCierre = [];
        $fields = [];
        $initial = null;
        $final = null;
        $fecha_inicio=DB::select("SELECT min(fecha) as fecha_inicio from asientos,proyecto where proyecto.id_empresa=".$request->company);
        
            $info_reporte=json_decode($request->reporte, true);
            if($info_reporte["id"] == 1){
                if ($request->dates) {
                    $info_date = json_decode($request->dates, true);
                    if ($request->currentDate !== "true") {
                        $initial = str_replace("-010-","-10-",$info_date["range"]["initial"]);
                        $final = str_replace("-010-","-10-",$info_date["range"]["final"]);
                        if ($info_date["option"] == 1) {
                            array_push($queries, "date(asi.fecha) between date('{$info_date["range"]["initial"]}') and date('{$info_date["range"]["final"]}')\n");
                        }
                        if ($info_date["option"] == 2) {
                            array_push($queries, "date(asi.fecha) between date('{$info_date["range"]["initial"]}') and date('{$info_date["range"]["final"]}')\n");
                        }
                        if ($info_date["option"] == 3) {
                            //array_push($queries, "date(asi.fecha) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')\n");
                            array_push($queries, "date(asi.fecha) between date('{$info_date["range"]["initial"]}') and date('{$info_date["range"]["final"]}')\n");
                            array_push($inners, "date(asi.fecha) between date('{$info_date["range"]["initial"]}') and date('{$info_date["range"]["final"]}')\n");
                        }
                    } else {
                        $initial = $info_date["value"];
                        $final = $info_date["value"];
                        if ($info_date["option"] == 1) {
                            array_push($queries, "date(asi.fecha) = date('{$info_date["value"]}')\n");
                        }
                        if ($info_date["option"] == 2) {
                            array_push($queries, "date(asi.fecha) = date('{$info_date["value"]}')\n");
                        }
                        if ($info_date["option"] == 3) {
                            //array_push($queries, "date(asi.fecha) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$info_date["value"]}')\n");
                            array_push($queries, "date(asi.fecha) = date('{$info_date["value"]}')\n");
                            array_push($inners, "date(asi.fecha) = date('{$info_date["value"]}')\n");
                        }
                    }
                    
                }
                $proyecto="";
                //dd($inners);
                if ($request->project) {
                    $info_payment = json_decode($request->project, true);
                    if ($info_payment["id"] != 0) {
                        array_push($queries, "asi.id_proyecto = {$info_payment["id"]}\n");
                        array_push($inners, "asidet.id_proyecto = {$info_payment["id"]}\n");
                        $proyecto=$info_payment["name"];
                    }else{
                        $proyecto="Todos";
                    }
                }
                if ($request->comprobante) {
                    $info_comprobante = json_decode($request->comprobante, true);
                    if ($info_comprobante["id"] != 0) {
                        array_push($queries, "asi.id_asientos_comprobante = {$info_comprobante["id"]}\n");
                        array_push($inners, "asi.id_asientos_comprobante = {$info_comprobante["id"]}\n");
                    }
                }
                $queries = implode(" and ", $queries);
                $inners = implode(" and ", $inners);
                $query="SELECT asi.concepto,plc.codcta,plc.nomcta,asidet.*,proy.descripcion from asientos as asi,asientos_detalle as asidet,plan_cuentas as plc,proyecto as proy where {$inners} and proy.id_proyecto=asidet.id_proyecto and asi.id_proyecto is not null and asidet.id_asientos=asi.id_asientos and asidet.id_plan_cuentas=plc.id_plan_cuentas and plc.id_empresa={$request->company} and (asi.estado='Activo' or asi.estado is null) order by plc.codcta asc";
                //$query="SELECT asi.concepto,plc.codcta,plc.nomcta,asidet.*,proy.descripcion from asientos as asi,asientos_detalle as asidet,plan_cuentas as plc,proyecto as proy where {$inners} and proy.id_proyecto=asi.id_proyecto and asidet.id_asientos=asi.id_asientos and asidet.id_plan_cuentas=plc.id_plan_cuentas and plc.id_empresa={$request->company} order by plc.codcta asc";
                $reporte = DB::select($query);
                //dd($query);
                $reporte2 = DB::select("Select * from empresa where id_empresa=".$request->company);
                $reporte3= DB::select("SELECT Distinct asi.id_asientos,asi.fecha,ascomp.tipo,asi.codigo,pro.id_proyecto FROM asientos as asi,asientos_comprobante as ascomp,asientos_detalle as asidet,proyecto as pro where {$inners}  and asidet.id_proyecto=pro.id_proyecto and asi.id_proyecto is not null and asi.id_asientos=asidet.id_asientos and asi.id_asientos_comprobante=ascomp.id_asientos_comprobante and pro.id_empresa={$request->company} and (asi.estado='Activo' or asi.estado is null) ORDER BY asi.fecha asc");
                //$reporte3= DB::select("SELECT Distinct asi.id_asientos,asi.fecha,ascomp.tipo,asi.codigo,pro.id_proyecto FROM asientos as asi,asientos_comprobante as ascomp,asientos_detalle as asidet,proyecto as pro where {$inners}  and asi.id_proyecto=pro.id_proyecto and asi.id_asientos=asidet.id_asientos and asi.id_asientos_comprobante=ascomp.id_asientos_comprobante and pro.id_empresa={$request->company} ORDER BY asi.fecha asc");
                //dd("SELECT  asi.id_asientos,asi.fecha,ascomp.tipo,asi.codigo,pro.descripcion FROM asientos as asi,asientos_comprobante as ascomp,asientos_detalle as asidet,proyecto as pro where {$inners}  and asidet.id_proyecto=pro.id_proyecto and asi.id_asientos_comprobante=ascomp.id_asientos_comprobante and pro.id_empresa={$request->company} ORDER BY asi.fecha asc");
                if (!$reporte) {
                    //return response('no-data-report', 200)->header('Content-Type', 'application/json');
                    return "no-data-report";
                } else {
                    if($request->email && $request->destinatario){
                        $Reportes = new generarReportes();
                        $ruta = constant("DATA_EMPRESA") . $request->company . '/contabilidad/asientos_contables';
                        if(!file_exists($ruta)){
                            mkdir($ruta, 0755,true);
                        }
                        //$strPDF = $Reportes->diario_general($reporte, $fecha_inicio[0]->fecha_inicio, $final,$reporte2,$reporte3,$ruta);
                        $strPDF = $Reportes->diario_general($reporte, $initial, $final,$reporte2,$reporte3,$proyecto,$ruta);
                        $email=new sendEmail();
                        $email->enviarAsientos($reporte2[0],$request->email,$request->destinatario,"diario_general","Diario General");
                        $cta=$ruta.'/diario_general.pdf';
                        /*if(file_exists($cta)){
                            unlink($cta);
                        }*/
                        return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                        
                    }else{
                        $Reportes = new generarReportes();
                        //$strPDF = $Reportes->diario_general($reporte, $fecha_inicio[0]->fecha_inicio, $final,$reporte2,$reporte3);
                        $strPDF = $Reportes->diario_general($reporte, $initial, $final,$reporte2,$reporte3,$proyecto);
                        return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                    }
                }
            }
            
            if($info_reporte["id"] == 2){
                if ($request->dates) {
                    $info_date = json_decode($request->dates, true);
                    if ($request->currentDate !== "true") {
                        $initial = str_replace("-010-","-10-",$info_date["range"]["initial"]);
                        $final = str_replace("-010-","-10-",$info_date["range"]["final"]);
                        if ($info_date["option"] == 1) {
                            array_push($queries, "date(asi.fecha) between date('{$info_date["range"]["initial"]}') and date('{$info_date["range"]["final"]}')\n");
                        }
                        if ($info_date["option"] == 2) {
                            array_push($queries, "date(asi.fecha) between date('{$info_date["range"]["initial"]}') and date('{$info_date["range"]["final"]}')\n");
                        }
                        if ($info_date["option"] == 3) {
                            //array_push($queries, "date(asi.fecha) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')\n");
                            array_push($queries, "date(asi.fecha) between date('{$info_date["range"]["initial"]}') and date('{$info_date["range"]["final"]}')\n");
                            //dd($initial);
                            $actual = date("Y-m-d", strtotime($initial));
                            $creado = strtotime ( '-1 day' , strtotime ( $actual ) ) ;
                            $anterior = date("Y-m-d", $creado);
                            $month = date('m');
                            $year = date('Y');
                            $day = date("d", mktime(0,0,0, $month+1, 0, $year));
                        
                            $fecha_final= date('Y-m-d', mktime(0,0,0, $month, $day, $year));
                            array_push($innersAuxCierre, "date(asi.fecha) between date('{$anterior}') and date('{$anterior}')\n");
                            array_push($inners, "date(asi.fecha) between date(if((select COUNT(id_asientos) from asientos asi WHERE concepto like '%Cierre Balance%' and asi.id_empresa={$request->company}) > 0, (select CONCAT(SUBSTR(DATE_ADD(fecha, INTERVAL 1 DAY), 1, 10), '  00:00:00') from asientos asi WHERE concepto like '%Cierre Balance%' and asi.id_empresa={$request->company} ORDER BY asi.periodo DESC LIMIT 1), '{$fecha_inicio[0]->fecha_inicio}')) and date('{$anterior}')\n");
                            array_push($fields,"date(asi.fecha) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')\n");
                        }
                    } else {
                        $initial = $info_date["value"];
                        $final = $info_date["value"];
                        if ($info_date["option"] == 1) {
                            array_push($queries, "date(asi.fecha) = date('{$info_date["value"]}')\n");
                        }
                        if ($info_date["option"] == 2) {
                            array_push($queries, "date(asi.fecha) = date('{$info_date["value"]}')\n");
                        }
                        if ($info_date["option"] == 3) {
                            //array_push($queries, "date(asi.fecha) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$info_date["value"]}')\n");
                            array_push($queries, "date(asi.fecha) = date('{$info_date["value"]}')\n");
                            $actual = date("Y-m-d", strtotime($info_date["value"]));
                            $creado = strtotime ( '-1 day' , strtotime ( $actual ) ) ;
                            $anterior = date("Y-m-d", $creado);
                            $month = date('m');
                            $year = date('Y');
                            $day = date("d", mktime(0,0,0, $month+1, 0, $year));
                        
                            $fecha_final= date('Y-m-d', mktime(0,0,0, $month, $day, $year));

                            array_push($inners, "date(asi.fecha) between date(if((select COUNT(id_asientos) from asientos asi WHERE concepto like '%Cierre Balance%' and asi.id_empresa={$request->company}) > 0, (select CONCAT(SUBSTR(DATE_ADD(fecha, INTERVAL 1 DAY), 1, 10), '  00:00:00') from asientos asi WHERE concepto like '%Cierre Balance%' and asi.id_empresa={$request->company} ORDER BY asi.periodo DESC LIMIT 1), '{$fecha_inicio[0]->fecha_inicio}')) and date('{$anterior}')\n");
                            array_push($innersAuxCierre, "date(asi.fecha) between date('{$anterior}') and date('{$anterior}')\n");
                            array_push($fields,"date(asi.fecha) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$info_date["value"]}')\n");
                        }
                    }
                    
                }
                //dd($inners);
                $proyecto="";
                //dd($inners);
                if ($request->project) {
                    $info_payment = json_decode($request->project, true);
                    if ($info_payment["id"] != 0) {
                        array_push($queries, "asdt.id_proyecto = {$info_payment["id"]}\n");
                        array_push($inners, "asientos_detalle.id_proyecto = {$info_payment["id"]}\n");
                        array_push($fields,"asdt.id_proyecto = {$info_payment["id"]}\n");
                        $proyecto=$info_payment["name"];
                    }else{
                        $proyecto="Todos";
                    }
                }
                if ($request->comprobante) {
                    $info_comprobante = json_decode($request->comprobante, true);
                    if ($info_comprobante["id"] != 0) {
                        array_push($queries, "asi.id_asientos_comprobante = {$info_comprobante["id"]}\n");
                        array_push($inners, "asi.id_asientos_comprobante = {$info_comprobante["id"]}\n");
                        array_push($fields,"asi.id_asientos_comprobante = {$info_comprobante["id"]}\n");
                    }
                }
                if ($request->plan_cuenta && $request->plan_cuenta_2) {
                    //$info_plan_cuenta = json_decode($request->plan_cuenta, true);
                        //array_push($queries, "asdt.id_plan_cuentas Between {$request->plan_cuenta} and {$request->plan_cuenta_2}\n");
                        $cod1=DB::select("SELECT codcta from plan_cuentas where id_plan_cuentas={$request->plan_cuenta} and id_empresa=".$request->company);
                        $cod2=DB::select("SELECT codcta from plan_cuentas where id_plan_cuentas={$request->plan_cuenta_2} and id_empresa=".$request->company);
                        array_push($queries, "plc.codcta Between '{$cod1[0]->codcta}' and '{$cod2[0]->codcta}'\n");
                        array_push($inners, "plc.codcta Between '{$cod1[0]->codcta}' and '{$cod2[0]->codcta}'\n");
                        array_push($fields,"plc.codcta Between '{$cod1[0]->codcta}' and '{$cod2[0]->codcta}'\n");
                }
                //dd($queries);
                $queries = implode(" and ", $queries);
                $inners = implode(" and ", $inners);
                $innersAuxCierre = implode(" and ", $innersAuxCierre);
                $fields = implode(" and ", $fields);
                $query="SELECT  plc.codcta,plc.nomcta,asdt.debe,asdt.haber,asi.fecha,plc.id_plan_cuentas,asi.codigo,asi.concepto,pro.descripcion,asi.razon_social FROM asientos_detalle as asdt,plan_cuentas as plc,asientos as asi,proyecto as pro where {$queries} and plc.id_plan_cuentas=asdt.id_plan_cuentas and pro.id_proyecto=asdt.id_proyecto and asi.id_proyecto is not null and pro.id_empresa={$request->company} and asi.id_asientos=asdt.id_asientos and (asi.estado='Activo' or asi.estado is null) order by asi.fecha asc";
                //$query="SELECT  plc.codcta,plc.nomcta,asdt.debe,asdt.haber,asi.fecha,plc.id_plan_cuentas,asi.codigo,asi.concepto,pro.descripcion FROM asientos_detalle as asdt,plan_cuentas as plc,asientos as asi,proyecto as pro where {$queries} and plc.id_plan_cuentas=asdt.id_plan_cuentas and pro.id_proyecto=asi.id_proyecto and pro.id_empresa={$request->company} and asi.id_asientos=asdt.id_asientos order by asi.fecha asc";
                //dd($query);
                $reporte = DB::select($query);
                //dd($reporte);
                $reporte2 = DB::select("Select * from empresa where id_empresa=".$request->company);
                $reporte3= DB::select("SELECT  plc.codcta,plc.nomcta,plc.id_plan_cuentas FROM asientos_detalle as asdt,plan_cuentas as plc,asientos as asi,proyecto as proy where {$fields} and plc.id_plan_cuentas=asdt.id_plan_cuentas and asdt.id_proyecto=proy.id_proyecto and asdt.id_asientos=asi.id_asientos and asi.id_proyecto is not null  and plc.id_empresa={$request->company} and (asi.estado='Activo' or asi.estado is null) GROUP BY plc.id_plan_cuentas order by plc.codcta asc");
                //$reporte3= DB::select("SELECT  plc.codcta,plc.nomcta,plc.id_plan_cuentas FROM asientos_detalle as asdt,plan_cuentas as plc,asientos as asi,proyecto as proy where {$fields} and plc.id_plan_cuentas=asdt.id_plan_cuentas and asi.id_proyecto=proy.id_proyecto and asdt.id_asientos=asi.id_asientos  and plc.id_empresa={$request->company} GROUP BY plc.id_plan_cuentas order by plc.codcta asc");
                //dd($reporte3);

                $reporte_plc=DB::select("SELECT if((select COUNT(id_asientos) from asientos asi WHERE concepto like '%Cierre Balance%' and asi.id_empresa={$request->company} and {$innersAuxCierre}) > 0, 0, if(sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber)) is null,0, sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber)))) saldo_ant,plc.id_plan_cuentas,nomcta,plc.codcta 
                from asientos_detalle,asientos as asi,plan_cuentas as plc,proyecto as pro
                where {$inners} and asientos_detalle.id_proyecto=pro.id_proyecto and asi.id_proyecto is not null
                and asi.id_asientos=asientos_detalle.id_asientos and plc.id_plan_cuentas=asientos_detalle.id_plan_cuentas and plc.id_empresa={$request->company} and (asi.estado='Activo' or asi.estado is null)
                 GROUP BY plc.id_plan_cuentas ORDER BY plc.codcta asc");
                //$reporte_plc=DB::select("SELECT if(sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber)) is null,0, sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber))) saldo_ant,plc.id_plan_cuentas,nomcta,plc.codcta 
                // from asientos_detalle,asientos as asi,plan_cuentas as plc,proyecto as pro
                // where {$inners} and asi.id_proyecto=pro.id_proyecto
                // and asi.id_asientos=asientos_detalle.id_asientos and plc.id_plan_cuentas=asientos_detalle.id_plan_cuentas and plc.id_empresa={$request->company}
                //  GROUP BY plc.id_plan_cuentas ORDER BY plc.codcta asc");
                
                if (!$reporte_plc && !$reporte) {
                    return response('no-data-report', 200)->header('Content-Type', 'application/json');
                } else {
                    if($request->email && $request->destinatario){
                        $Reportes = new generarReportes();
                        $ruta = constant("DATA_EMPRESA") . $request->company . '/contabilidad/asientos_contables';
                        if(!file_exists($ruta)){
                            mkdir($ruta, 0755,true);
                        }
                        //$strPDF = $Reportes->mayor_general($reporte, $fecha_inicio[0]->fecha_inicio, $final,$reporte2,$reporte3,$ruta);
                        $strPDF = $Reportes->mayor_general($reporte, $initial, $final,$reporte2,$reporte3,$reporte_plc,$proyecto,$ruta);
                        $email=new sendEmail();
                        $email->enviarAsientos($reporte2[0],$request->email,$request->destinatario,"mayor_general","Mayor General");
                        $cta=$ruta.'/mayor_general.pdf';
                        /*if(file_exists($cta)){
                            unlink($cta);
                        }*/
                        return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                        
                    }else{
                        $Reportes = new generarReportes();
                        //$strPDF = $Reportes->mayor_general($reporte, $fecha_inicio[0]->fecha_inicio, $final,$reporte2,$reporte3);
                        $strPDF = $Reportes->mayor_general($reporte, $initial, $final,$reporte2,$reporte3,$reporte_plc,$proyecto);
                        return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                    }
                }
            }
            if($info_reporte["id"] == 3){
                if ($request->dates) {
                    $info_date = json_decode($request->dates, true);
                    if ($request->currentDate !== "true") {
                        $initial = str_replace("-010-","-10-",$info_date["range"]["initial"]);
                        $final = str_replace("-010-","-10-",$info_date["range"]["final"]);
                        if ($info_date["option"] == 1) {
                            array_push($queries, "date(asi.fecha) between date('{$info_date["range"]["initial"]}') and date('{$info_date["range"]["final"]}')\n");
                        }
                        if ($info_date["option"] == 2) {
                            array_push($queries, "date(asi.fecha) between date('{$info_date["range"]["initial"]}') and date('{$info_date["range"]["final"]}')\n");
                        }
                        if ($info_date["option"] == 3) {
                            //array_push($queries, "date(asi.fecha) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')\n");
                            array_push($queries, "date(asi.fecha) between date('{$info_date["range"]["initial"]}') and date('{$info_date["range"]["final"]}')\n");
                            $actual = date("Y-m-d", strtotime($initial));
                            $creado = strtotime ( '-1 day' , strtotime ( $actual ) ) ;
                            $anterior = date("Y-m-d", $creado);
                            $month = date('m');
                            $year = date('Y');
                            $day = date("d", mktime(0,0,0, $month+1, 0, $year));
                        
                            $fecha_final= date('Y-m-d', mktime(0,0,0, $month, $day, $year));
                            array_push($debe_haber, "date(asi.fecha) between date('{$info_date["range"]["initial"]}') and date('{$info_date["range"]["final"]}')\n");
                            array_push($inners, "date(asi.fecha) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$anterior}')\n");
                            array_push($fields,"date(asi.fecha) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$final}')\n");
                        }
                    } else {
                        $initial = str_replace("-010-","-10-",$info_date["value"]);
                        $final = str_replace("-010-","-10-",$info_date["value"]);
                        if ($info_date["option"] == 1) {
                            array_push($queries, "date(asi.fecha) = date('{$info_date["value"]}')\n");
                        }
                        if ($info_date["option"] == 2) {
                            array_push($queries, "date(asi.fecha) = date('{$info_date["value"]}')\n");
                        }
                        if ($info_date["option"] == 3) {
                            //array_push($queries, "date(asi.fecha) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$info_date["value"]}')\n");
                            
                            $actual = date("Y-m-d", strtotime($info_date["value"]));
                            $creado = strtotime ( '-1 day' , strtotime ( $actual ) ) ;
                            $anterior = date("Y-m-d", $creado);
                            $month = date('m');
                            $year = date('Y');
                            $day = date("d", mktime(0,0,0, $month+1, 0, $year));
                        
                            $fecha_final= date('Y-m-d', mktime(0,0,0, $month, $day, $year));
                            array_push($queries, "date(asi.fecha) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$initial}')\n");
                            array_push($debe_haber, "date(asi.fecha) = date('{$initial}')\n");
                            array_push($inners, "date(asi.fecha) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$anterior}')\n");
                            array_push($fields,"date(asi.fecha) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$final}')\n");
                        }
                    }
                    
                }

                //dd($queries);
                $proyecto="";
                //dd($inners);
                if ($request->project) {
                    $info_payment = json_decode($request->project, true);
                    if ($info_payment["id"] != 0) {
                        /*array_push($queries, "asi.id_proyecto = {$info_payment["id"]}\n");
                        array_push($inners, "asi.id_proyecto = {$info_payment["id"]}\n");
                        array_push($fields,"asi.id_proyecto = {$info_payment["id"]}\n");*/
                        array_push($queries, "asientos_detalle.id_proyecto = {$info_payment["id"]}\n");
                        array_push($debe_haber, "asientos_detalle.id_proyecto = {$info_payment["id"]}\n");
                        array_push($inners, "asientos_detalle.id_proyecto = {$info_payment["id"]}\n");
                        array_push($fields,"asdt.id_proyecto = {$info_payment["id"]}\n");
                        $proyecto=$info_payment["name"];
                    }else{
                        $proyecto="Todos";
                    }
                }
                $inners=implode(" and ",$inners);
                $queries = implode(" and ", $queries);
                $debe_haber = implode(" and ", $debe_haber);
                $fields = implode(" and ", $fields);
                $query="SELECT  plc.codcta,plc.nomcta,asdt.debe,asdt.haber,asi.fecha,plc.id_plan_cuentas,asi.codigo,asi.concepto,pro.descripcion FROM asientos_detalle as asdt,plan_cuentas as plc,asientos as asi,proyecto as pro where {$fields} and plc.id_plan_cuentas=asdt.id_plan_cuentas and pro.id_proyecto=asdt.id_proyecto and asi.id_proyecto is not null and pro.id_empresa={$request->company} and asi.id_asientos=asdt.id_asientos and (asi.estado='Activo' or asi.estado is null) order by asi.fecha asc";
                //$query="SELECT  plc.codcta,plc.nomcta,asdt.debe,asdt.haber,asi.fecha,plc.id_plan_cuentas,asi.codigo,asi.concepto,pro.descripcion FROM asientos_detalle as asdt,plan_cuentas as plc,asientos as asi,proyecto as pro where {$fields} and plc.id_plan_cuentas=asdt.id_plan_cuentas and pro.id_proyecto=asi.id_proyecto and pro.id_empresa={$request->company} and asi.id_asientos=asdt.id_asientos order by asi.fecha asc";
                //dd($query);
                $reporte = DB::select($query);
                //dd($reporte);
                $reporte2 = DB::select("Select * from empresa where id_empresa=".$request->company);
                $reporte3= DB::select("SELECT  plc.codcta,plc.nomcta,plc.id_plan_cuentas,pro.descripcion FROM asientos_detalle as asdt,plan_cuentas as plc,asientos as asi,proyecto as pro where {$fields} and plc.id_plan_cuentas=asdt.id_plan_cuentas and asdt.id_asientos=asi.id_asientos and pro.id_proyecto=asi.id_proyecto and plc.id_empresa={$request->company} and (asi.estado='Activo' or asi.estado is null) GROUP BY plc.id_plan_cuentas order by plc.codcta asc");
                //dd($reporte3);
                // dd("SELECT plan_cuentas.id_plan_cuentas,codcta,nomcta,(SELECT sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber)) from asientos_detalle as asdt,asientos as asi,proyecto as pro  where {$fields} and plan_cuentas.id_plan_cuentas=asdt.id_plan_cuentas and 
                // asi.id_asientos=asdt.id_asientos) as saldo,LENGTH(codcta) as longt from plan_cuentas where id_empresa=".$request->company." order by codcta asc");
                $reporte_plc=DB::select("SELECT plan_cuentas.id_plan_cuentas,codcta,nomcta,id_grupo,(SELECT sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber)) from asientos_detalle as asdt,asientos as asi,proyecto as pro  where {$fields} and plan_cuentas.id_plan_cuentas=asdt.id_plan_cuentas and 
                asi.id_asientos=asdt.id_asientos and (asi.estado='Activo' or asi.estado is null)) as saldo,LENGTH(codcta) as longt 
                from plan_cuentas 
                where id_empresa=".$request->company."  order by codcta asc"); 
                
                $query_plc_ant="SELECT if(sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber)) is null,0, sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber))) saldo_ant,plc.id_plan_cuentas,nomcta 
                from asientos_detalle,asientos as asi,
                plan_cuentas as plc,proyecto as pro
                where {$inners} and asientos_detalle.id_proyecto=pro.id_proyecto and asi.id_proyecto is not null
                and asi.id_asientos=asientos_detalle.id_asientos and plc.id_plan_cuentas=asientos_detalle.id_plan_cuentas and plc.id_empresa={$request->company} and (asi.estado='Activo' or asi.estado is null)
                 GROUP BY plc.id_plan_cuentas ORDER BY plc.codcta asc";
                // $query_plc_ant="SELECT if(sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber)) is null,0, sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber))) saldo_ant,plc.id_plan_cuentas,nomcta 
                // from asientos_detalle,asientos as asi,
                // plan_cuentas as plc,proyecto as pro
                // where {$inners} and asi.id_proyecto=pro.id_proyecto
                // and asi.id_asientos=asientos_detalle.id_asientos and plc.id_plan_cuentas=asientos_detalle.id_plan_cuentas and plc.id_empresa={$request->company}
                // GROUP BY plc.id_plan_cuentas ORDER BY plc.codcta asc";
                //dd($query_plc_ant);
                $reporte_debe_haber=DB::select("SELECT sum(if(debe is null,0,debe)) as debe,sum(if(haber is null,0,haber)) as haber,plc.id_plan_cuentas,nomcta
                from asientos_detalle,asientos as asi,
                plan_cuentas as plc,proyecto as pro
                where {$debe_haber}
                and asientos_detalle.id_proyecto=pro.id_proyecto and asi.id_proyecto is not null
                and asi.id_asientos=asientos_detalle.id_asientos and plc.id_plan_cuentas=asientos_detalle.id_plan_cuentas and plc.id_empresa={$request->company} and (asi.estado='Activo' or asi.estado is null)
                GROUP BY plc.id_plan_cuentas ORDER BY plc.codcta asc");
                // dd("SELECT sum(if(debe is null,0,debe)) as debe,sum(if(haber is null,0,haber)) as haber,plc.id_plan_cuentas,nomcta
                // from asientos_detalle,asientos as asi,
                // plan_cuentas as plc,proyecto as pro
                // where {$debe_haber}
                // and asientos_detalle.id_proyecto=pro.id_proyecto and asi.id_proyecto is not null
                // and asi.id_asientos=asientos_detalle.id_asientos and plc.id_plan_cuentas=asientos_detalle.id_plan_cuentas and plc.id_empresa={$request->company} and (asi.estado='Activo' or asi.estado is null)
                // GROUP BY plc.id_plan_cuentas ORDER BY plc.codcta asc");
                // dd("SELECT sum(if(debe is null,0,debe)) as debe,sum(if(haber is null,0,haber)) as haber,plc.id_plan_cuentas,nomcta
                // from asientos_detalle,asientos as asi,
                // plan_cuentas as plc,proyecto as pro
                // where {$queries}
                // and asientos_detalle.id_proyecto=pro.id_proyecto and asi.id_proyecto is not null
                // and asi.id_asientos=asientos_detalle.id_asientos and plc.id_plan_cuentas=asientos_detalle.id_plan_cuentas and plc.id_empresa={$request->company}
                // GROUP BY plc.id_plan_cuentas ORDER BY plc.codcta asc");
                // $reporte_debe_haber=DB::select("SELECT sum(if(debe is null,0,debe)) as debe,sum(if(haber is null,0,haber)) as haber,plc.id_plan_cuentas,nomcta
                // from asientos_detalle,asientos as asi,
                // plan_cuentas as plc,proyecto as pro
                // where {$queries}
                // and asi.id_proyecto=pro.id_proyecto
                // and asi.id_asientos=asientos_detalle.id_asientos and plc.id_plan_cuentas=asientos_detalle.id_plan_cuentas and plc.id_empresa={$request->company}
                // GROUP BY plc.id_plan_cuentas ORDER BY plc.codcta asc");
                // dd("SELECT if(sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber)) is null,0, sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber))) saldo_ant,plc.id_plan_cuentas,nomcta from asientos_detalle,asientos as asi,
                // plan_cuentas as plc,proyecto as pro
                // where {$inners} and asientos_detalle.id_proyecto=pro.id_proyecto
                // and asi.id_asientos=asientos_detalle.id_asientos and plc.id_plan_cuentas=asientos_detalle.id_plan_cuentas and plc.id_empresa={$request->company}
                //  GROUP BY plc.id_plan_cuentas ORDER BY plc.codcta asc");
                $reporte_plc_ant=DB::select("SELECT if(sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber)) is null,0, sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber))) saldo_ant,plc.id_plan_cuentas,nomcta 
                from asientos_detalle,asientos as asi,
                plan_cuentas as plc,proyecto as pro
                where {$inners} and asientos_detalle.id_proyecto=pro.id_proyecto and asi.id_proyecto is not null
                and asi.id_asientos=asientos_detalle.id_asientos and plc.id_plan_cuentas=asientos_detalle.id_plan_cuentas and plc.id_empresa={$request->company} and (asi.estado='Activo' or asi.estado is null)
                 GROUP BY plc.id_plan_cuentas ORDER BY plc.codcta asc");
                //  $reporte_plc_ant=DB::select("SELECT if(sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber)) is null,0, sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber))) saldo_ant,plc.id_plan_cuentas,nomcta from asientos_detalle,asientos as asi,
                //  plan_cuentas as plc,proyecto as pro
                //  where {$inners} and asi.id_proyecto=pro.id_proyecto
                //  and asi.id_asientos=asientos_detalle.id_asientos and plc.id_plan_cuentas=asientos_detalle.id_plan_cuentas and plc.id_empresa={$request->company}
                //  GROUP BY plc.id_plan_cuentas ORDER BY plc.codcta asc");
                 //dd($reporte_plc);
                //dd($query_plc_ant);
                 //dd("SELECT codcta,nomcta,(SELECT sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber)) from asientos_detalle,asientos as asi  where {$queries} and plan_cuentas.id_plan_cuentas=asientos_detalle.id_plan_cuentas and 
                 //asi.id_asientos=asientos_detalle.id_asientos) as saldo,LENGTH(codcta) as longt from plan_cuentas where id_empresa=".$request->company." order by codcta asc");
                if (!$reporte) {
                    return response('no-data-report', 200)->header('Content-Type', 'application/json');
                } else {
                    if($request->email && $request->destinatario){
                        $Reportes = new generarReportes();
                        $ruta = constant("DATA_EMPRESA") . $request->company . '/contabilidad/asientos_contables';
                        if(!file_exists($ruta)){
                            mkdir($ruta, 0755,true);
                        }
                        //$strPDF = $Reportes->balance_comprobacion($reporte, $fecha_inicio[0]->fecha_inicio, $final,$reporte2,$reporte3,$reporte_plc,$ruta);
                        $strPDF = $Reportes->balance_comprobacion($initial, $final,$reporte2,$reporte3,$reporte_plc,$reporte_plc_ant,$proyecto,$reporte_debe_haber,$ruta);
                        $email=new sendEmail();
                        $email->enviarAsientos($reporte2[0],$request->email,$request->destinatario,"balance_comprobacion","Balance Comprobacion");
                        $cta=$ruta.'/balance_comprobacion.pdf';
                        /*if(file_exists($cta)){
                            unlink($cta);
                        }*/
                        return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                        
                    }else{
                        $Reportes = new generarReportes();
                        //$strPDF = $Reportes->balance_comprobacion($reporte, $fecha_inicio[0]->fecha_inicio, $final,$reporte2,$reporte3,$reporte_plc);
                        $strPDF = $Reportes->balance_comprobacion($initial, $final,$reporte2,$reporte3,$reporte_plc,$reporte_plc_ant,$proyecto,$reporte_debe_haber);
                        return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                    }
                }
                
            }
            if($info_reporte["id"] == 4){
                if ($request->dates) {
                    $info_date = json_decode($request->dates, true);
                    if ($request->currentDate !== "true") {
                        $initial = str_replace("-010-","-10-",$info_date["range"]["initial"]);
                        $final = str_replace("-010-","-10-",$info_date["range"]["final"]);
                        //dd("fecha inicio:{$initial} y fecha final:{$final}");
                        if ($info_date["option"] == 1) {
                            array_push($queries, "date(asi.fecha) between date('{$info_date["range"]["initial"]}') and date('{$info_date["range"]["final"]}')\n");
                        }
                        if ($info_date["option"] == 2) {
                            array_push($queries, "date(asi.fecha) between date('{$info_date["range"]["initial"]}') and date('{$info_date["range"]["final"]}')\n");
                        }
                        if ($info_date["option"] == 3) {
                            //array_push($queries, "date(asi.fecha) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')\n");
                            array_push($queries, "date(asi.fecha) between date('{$info_date["range"]["initial"]}') and date('{$info_date["range"]["final"]}')\n");
                            $actual = date("Y-m-d", strtotime($initial));
                            $creado = strtotime ( '-1 day' , strtotime ( $actual ) ) ;
                            $anterior = date("Y-m-d", $creado);
                            $month = date('m');
                            $year = date('Y');
                            $day = date("d", mktime(0,0,0, $month+1, 0, $year));
                        
                            $fecha_final= date('Y-m-d', mktime(0,0,0, $month, $day, $year));
                            array_push($inners, "date(asi.fecha) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$anterior}')\n");
                            array_push($fields,"date(asi.fecha) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')\n");
                        }
                    } else {
                        $initial = $info_date["value"];
                        $final = $info_date["value"];
                        if ($info_date["option"] == 1) {
                            array_push($queries, "date(asi.fecha) = date('{$info_date["value"]}')\n");
                        }
                        if ($info_date["option"] == 2) {
                            array_push($queries, "date(asi.fecha) = date('{$info_date["value"]}')\n");
                        }
                        if ($info_date["option"] == 3) {
                            //array_push($queries, "date(asi.fecha) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$info_date["value"]}')\n");
                            array_push($queries, "date(asi.fecha) = date('{$info_date["value"]}')\n");
                            $actual = date("Y-m-d", strtotime($info_date["value"]));
                            $creado = strtotime ( '-1 day' , strtotime ( $actual ) ) ;
                            $anterior = date("Y-m-d", $creado);
                            $month = date('m');
                            $year = date('Y');
                            $day = date("d", mktime(0,0,0, $month+1, 0, $year));
                        
                            $fecha_final= date('Y-m-d', mktime(0,0,0, $month, $day, $year));
                            //array_push($queries, "date(asi.fecha) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$info_date["value"]}')\n");
                            array_push($inners, "date(asi.fecha) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$anterior}')\n");
                            array_push($fields,"date(asi.fecha) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$info_date["value"]}')\n");
                        }
                    }
                    
                }

                //dd($queries);
                $proyecto="";
                //dd($inners);
                if ($request->project) {
                    $info_payment = json_decode($request->project, true);
                    if ($info_payment["id"] != 0) {
                        /*array_push($queries, "asi.id_proyecto = {$info_payment["id"]}\n");
                        array_push($inners, "asi.id_proyecto = {$info_payment["id"]}\n");
                        array_push($fields,"asi.id_proyecto = {$info_payment["id"]}\n");*/
                        array_push($queries, "asientos_detalle.id_proyecto = {$info_payment["id"]}\n");
                        array_push($inners, "asientos_detalle.id_proyecto = {$info_payment["id"]}\n");
                        array_push($fields,"asdt.id_proyecto = {$info_payment["id"]}\n");
                        $proyecto=$info_payment["name"];
                    }else{
                        $proyecto="Todos";
                    }
                }
                $inners=implode(" and ",$inners);
                $queries = implode(" and ", $queries);
                $fields = implode(" and ", $fields);
                $query="SELECT  plc.codcta,plc.nomcta,asdt.debe,asdt.haber,asi.fecha,plc.id_plan_cuentas,asi.codigo,asi.concepto,pro.descripcion FROM asientos_detalle as asdt,plan_cuentas as plc,asientos as asi,proyecto as pro where {$fields} and plc.id_plan_cuentas=asdt.id_plan_cuentas and pro.id_proyecto=asdt.id_proyecto and asi.id_proyecto is not null and pro.id_empresa={$request->company} and asi.id_asientos=asdt.id_asientos and (asi.estado='Activo' or asi.estado is null) order by asi.fecha asc";
                //$query="SELECT  plc.codcta,plc.nomcta,asdt.debe,asdt.haber,asi.fecha,plc.id_plan_cuentas,asi.codigo,asi.concepto,pro.descripcion FROM asientos_detalle as asdt,plan_cuentas as plc,asientos as asi,proyecto as pro where {$fields} and plc.id_plan_cuentas=asdt.id_plan_cuentas and pro.id_proyecto=asi.id_proyecto and pro.id_empresa={$request->company} and asi.id_asientos=asdt.id_asientos order by asi.fecha asc";
                //dd($query);
                $reporte = DB::select($query);
                //dd($reporte);
                $reporte2 = DB::select("Select * from empresa where id_empresa=".$request->company);
                $reporte3= DB::select("SELECT  plc.codcta,plc.nomcta,plc.id_plan_cuentas,pro.descripcion FROM asientos_detalle as asdt,plan_cuentas as plc,asientos as asi,proyecto as pro where {$fields} and plc.id_plan_cuentas=asdt.id_plan_cuentas and asdt.id_asientos=asi.id_asientos and pro.id_proyecto=asi.id_proyecto and plc.id_empresa={$request->company} and (asi.estado='Activo' or asi.estado is null) GROUP BY plc.id_plan_cuentas order by plc.codcta asc");
                //dd($reporte3);
                $reporte_plc=DB::select("SELECT plan_cuentas.id_plan_cuentas,id_grupo,codcta,nomcta,(SELECT sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber)) from asientos_detalle as asdt,asientos as asi,proyecto as pro  where {$fields} and plan_cuentas.id_plan_cuentas=asdt.id_plan_cuentas and 
                asi.id_asientos=asdt.id_asientos and (asi.estado='Activo' or asi.estado is null)) as saldo,LENGTH(codcta) as longt from plan_cuentas where id_empresa=".$request->company." order by codcta asc");  
                $query_plc_ant="SELECT if(sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber)) is null,0, sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber))) saldo_ant,plc.id_plan_cuentas,nomcta from asientos_detalle,asientos as asi,
                plan_cuentas as plc,proyecto as pro
                where {$inners} and asientos_detalle.id_proyecto=pro.id_proyecto and asi.id_proyecto is not null
                and asi.id_asientos=asientos_detalle.id_asientos and plc.id_plan_cuentas=asientos_detalle.id_plan_cuentas and plc.id_empresa={$request->company} and (asi.estado='Activo' or asi.estado is null)
                 GROUP BY plc.id_plan_cuentas ORDER BY plc.codcta asc";
                //  $query_plc_ant="SELECT if(sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber)) is null,0, sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber))) saldo_ant,plc.id_plan_cuentas,nomcta from asientos_detalle,asientos as asi,
                //  plan_cuentas as plc,proyecto as pro
                //  where {$inners} and asi.id_proyecto=pro.id_proyecto
                //  and asi.id_asientos=asientos_detalle.id_asientos and plc.id_plan_cuentas=asientos_detalle.id_plan_cuentas and plc.id_empresa={$request->company}
                //   GROUP BY plc.id_plan_cuentas ORDER BY plc.codcta asc";
                // dd("SELECT if(sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber)) is null,0, sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber))) saldo_ant,plc.id_plan_cuentas,nomcta from asientos_detalle,asientos as asi,
                // plan_cuentas as plc,proyecto as pro
                // where {$inners} and asientos_detalle.id_proyecto=pro.id_proyecto and asi.id_proyecto is not null
                // and asi.id_asientos=asientos_detalle.id_asientos and plc.id_plan_cuentas=asientos_detalle.id_plan_cuentas and plc.id_empresa={$request->company}
                //  GROUP BY plc.id_plan_cuentas ORDER BY plc.codcta asc");
                $reporte_debe_haber=DB::select("SELECT sum(if(debe is null,0,debe)) as debe,sum(if(haber is null,0,haber)) as haber,plc.id_plan_cuentas,nomcta
                from asientos_detalle,asientos as asi,
                plan_cuentas as plc,proyecto as pro
                where {$queries}
                and asientos_detalle.id_proyecto=pro.id_proyecto and asi.id_proyecto is not null
                and asi.id_asientos=asientos_detalle.id_asientos and plc.id_plan_cuentas=asientos_detalle.id_plan_cuentas and plc.id_empresa={$request->company} and (asi.estado='Activo' or asi.estado is null)
                GROUP BY plc.id_plan_cuentas ORDER BY plc.codcta asc");
                // $reporte_debe_haber=DB::select("SELECT sum(if(debe is null,0,debe)) as debe,sum(if(haber is null,0,haber)) as haber,plc.id_plan_cuentas,nomcta
                // from asientos_detalle,asientos as asi,
                // plan_cuentas as plc,proyecto as pro
                // where {$queries}
                // and asi.id_proyecto=pro.id_proyecto
                // and asi.id_asientos=asientos_detalle.id_asientos and plc.id_plan_cuentas=asientos_detalle.id_plan_cuentas and plc.id_empresa={$request->company}
                // GROUP BY plc.id_plan_cuentas ORDER BY plc.codcta asc");
                $reporte_plc_ant=DB::select("SELECT if(sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber)) is null,0, sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber))) saldo_ant,plc.id_plan_cuentas,nomcta from asientos_detalle,asientos as asi,
                plan_cuentas as plc,proyecto as pro
                where {$inners} and asientos_detalle.id_proyecto=pro.id_proyecto and asi.id_proyecto is not null
                and asi.id_asientos=asientos_detalle.id_asientos and plc.id_plan_cuentas=asientos_detalle.id_plan_cuentas and plc.id_empresa={$request->company} and (asi.estado='Activo' or asi.estado is null)
                 GROUP BY plc.id_plan_cuentas ORDER BY plc.codcta asc");
                //  $reporte_plc_ant=DB::select("SELECT if(sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber)) is null,0, sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber))) saldo_ant,plc.id_plan_cuentas,nomcta from asientos_detalle,asientos as asi,
                //  plan_cuentas as plc,proyecto as pro
                //  where {$inners} and asi.id_proyecto=pro.id_proyecto
                //  and asi.id_asientos=asientos_detalle.id_asientos and plc.id_plan_cuentas=asientos_detalle.id_plan_cuentas and plc.id_empresa={$request->company}
                //   GROUP BY plc.id_plan_cuentas ORDER BY plc.codcta asc");
                /*$query="SELECT DISTINCT plc.codcta,plc.nomcta,asdt.debe,asdt.haber,asi.fecha,plc.id_plan_cuentas,asi.codigo,asi.concepto FROM asientos_detalle as asdt,plan_cuentas as plc,asientos as asi where  plc.id_plan_cuentas=asdt.id_plan_cuentas and asi.id_asientos=asdt.id_asientos order by asi.fecha asc";
                $reporte = DB::select($query);
                //$reporte2 = DB::select("Select * from empresa where id_empresa=".$request->company);
                $reporte3= DB::select("SELECT  plc.codcta,plc.nomcta,plc.id_plan_cuentas FROM asientos_detalle as asdt,plan_cuentas as plc,asientos as asi where {$queries} and  plc.id_plan_cuentas=asdt.id_plan_cuentas and asdt.id_asientos=asi.id_asientos GROUP BY plc.id_plan_cuentas order by plc.codcta asc");
                $reporte_plc=DB::select("SELECT codcta,nomcta,(SELECT sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber)) from asientos_detalle,asientos as asi  where {$queries} and plan_cuentas.id_plan_cuentas=asientos_detalle.id_plan_cuentas and 
                asi.id_asientos=asientos_detalle.id_asientos) as saldo,LENGTH(codcta) as longt from plan_cuentas where id_empresa=".$request->company." order by codcta asc");
                $empresa_activo=" SELECT codcta FROM `plan_cuentas`,empresa where empresa.id_plan_cuentas_activo=plan_cuentas.id_plan_cuentas and empresa.id_empresa={$request->company}";
                $empresa_pasivo=" SELECT codcta FROM `plan_cuentas`,empresa where empresa.id_plan_cuentas_pasivo=plan_cuentas.id_plan_cuentas and empresa.id_empresa={$request->company}";
                $empresa_patrimonio=" SELECT codcta FROM `plan_cuentas`,empresa where empresa.id_plan_cuentas_patrimonio=plan_cuentas.id_plan_cuentas and empresa.id_empresa={$request->company}";
                $reporte_empresa_activo = DB::select($empresa_activo);
                $reporte_empresa_pasivo = DB::select($empresa_pasivo);
                $reporte_empresa_patrimonio = DB::select($empresa_patrimonio);
                $query="SELECT codcta,nomcta,
                (SELECT sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber)) from asientos_detalle,asientos as asi  where {$inners} and plan_cuentas.id_plan_cuentas=asientos_detalle.id_plan_cuentas and 
                asi.id_asientos=asientos_detalle.id_asientos) as saldo,
                id_plan_cuentas
                from plan_cuentas where plan_cuentas.id_empresa={$request->company} and plan_cuentas.codcta like '{$reporte_empresa_activo[0]->codcta}%'";
                $query_prueba="SELECT codcta,nomcta,
                (SELECT sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber)) from asientos_detalle,asientos as asi  where {$inners} and plan_cuentas.id_plan_cuentas=asientos_detalle.id_plan_cuentas and 
                asi.id_asientos=asientos_detalle.id_asientos) as saldo,
								LENGTH(codcta),
								if(codcta like codcta and LENGTH(codcta)=12,'si','no') as ultimo_nivel,
								id_plan_cuentas
                from plan_cuentas where plan_cuentas.id_empresa={$request->company} and plan_cuentas.codcta like '{$reporte_empresa_activo[0]->codcta}%' GROUP BY plan_cuentas.id_plan_cuentas order by codcta desc";
                $query_2="SELECT codcta,nomcta,
                (SELECT sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber)) from asientos_detalle,asientos as asi  where {$inners} and plan_cuentas.id_plan_cuentas=asientos_detalle.id_plan_cuentas and 
                asi.id_asientos=asientos_detalle.id_asientos) as saldo
                from plan_cuentas where plan_cuentas.id_empresa={$request->company} and plan_cuentas.codcta like '{$reporte_empresa_pasivo[0]->codcta}%'";
                $query_prueba_2="SELECT codcta,nomcta,
                (SELECT sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber)) from asientos_detalle,asientos as asi  where {$inners} and plan_cuentas.id_plan_cuentas=asientos_detalle.id_plan_cuentas and 
                asi.id_asientos=asientos_detalle.id_asientos) as saldo,
								LENGTH(codcta) as longt,
								if(codcta like codcta and LENGTH(codcta)=12,'si','no') as ultimo_nivel,
								id_plan_cuentas
                from plan_cuentas where plan_cuentas.id_empresa={$request->company} and plan_cuentas.codcta like '{$reporte_empresa_pasivo[0]->codcta}%' GROUP BY plan_cuentas.id_plan_cuentas order by codcta desc";
                $query_3="SELECT codcta,nomcta,
                (SELECT sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber)) from asientos_detalle,asientos as asi  where {$inners} and plan_cuentas.id_plan_cuentas=asientos_detalle.id_plan_cuentas and 
                asi.id_asientos=asientos_detalle.id_asientos) as saldo
                from plan_cuentas where plan_cuentas.id_empresa={$request->company} and plan_cuentas.codcta like '{$reporte_empresa_patrimonio[0]->codcta}%'";
                $query_prueba_3="SELECT codcta,nomcta,
                (SELECT sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber)) from asientos_detalle,asientos as asi  where {$inners} and plan_cuentas.id_plan_cuentas=asientos_detalle.id_plan_cuentas and 
                asi.id_asientos=asientos_detalle.id_asientos) as saldo,
								LENGTH(codcta) as longt,
								if(codcta like codcta and LENGTH(codcta)=12,'si','no') as ultimo_nivel,
								id_plan_cuentas
                from plan_cuentas where plan_cuentas.id_empresa={$request->company} and plan_cuentas.codcta like '{$reporte_empresa_patrimonio[0]->codcta}%' GROUP BY plan_cuentas.id_plan_cuentas order by codcta desc";
                //dd($query_prueba_2);
                
                $reporte_activo = DB::select($query);
                $reporte_prueba = DB::select($query_prueba);
                $reporte_pasivo = DB::select($query_2);
                $reporte_prueba_2 = DB::select($query_prueba_2);
                $reporte_patrimonio = DB::select($query_3);
                $reporte_prueba_3 = DB::select($query_prueba_3);
                $reporte2 = DB::select("Select * from empresa where id_empresa=".$request->company);*/
                //$reporte3= DB::select("SELECT  plc.codcta,plc.nomcta,plc.id_plan_cuentas FROM asientos_detalle as asdt,plan_cuentas as plc where plc.id_plan_cuentas=asdt.id_plan_cuentas GROUP BY plc.id_plan_cuentas");
                $empresa_activo=" SELECT codcta FROM `plan_cuentas`,empresa where empresa.id_plan_cuentas_activo=plan_cuentas.id_plan_cuentas and empresa.id_empresa={$request->company}";
                $empresa_pasivo=" SELECT codcta FROM `plan_cuentas`,empresa where empresa.id_plan_cuentas_pasivo=plan_cuentas.id_plan_cuentas and empresa.id_empresa={$request->company}";
                $empresa_patrimonio=" SELECT codcta FROM `plan_cuentas`,empresa where empresa.id_plan_cuentas_patrimonio=plan_cuentas.id_plan_cuentas and empresa.id_empresa={$request->company}";
                $reporte_empresa_activo = DB::select($empresa_activo);
                $reporte_empresa_pasivo = DB::select($empresa_pasivo);
                $reporte_empresa_patrimonio = DB::select($empresa_patrimonio);
                $empresa_ingreso=" SELECT codcta FROM `plan_cuentas`,empresa where empresa.id_plan_cuentas_ingreso=plan_cuentas.id_plan_cuentas and empresa.id_empresa={$request->company}";
                $empresa_costo=" SELECT codcta FROM `plan_cuentas`,empresa where empresa.id_plan_cuentas_costo=plan_cuentas.id_plan_cuentas and empresa.id_empresa={$request->company}";
                $empresa_gasto="SELECT codcta FROM `plan_cuentas`,empresa where empresa.id_plan_cuentas_gasto=plan_cuentas.id_plan_cuentas and empresa.id_empresa={$request->company}";
                $reporte_empresa_ingreso = DB::select($empresa_ingreso);
                $reporte_empresa_costo = DB::select($empresa_costo);
                $reporte_empresa_gasto = DB::select($empresa_gasto);
                /*$query="SELECT plc.codcta,plc.nomcta,asdt.debe,asdt.haber,asi.fecha,plc.id_plan_cuentas,asi.codigo,asi.concepto FROM asientos_detalle as asdt,plan_cuentas as plc,asientos as asi,proyecto as pro where {$fields} and plc.id_plan_cuentas=asdt.id_plan_cuentas and pro.id_proyecto=asdt.id_proyecto  and pro.id_empresa={$request->company} and asi.id_asientos=asdt.id_asientos order by asi.fecha asc";
                //dd($query);
                $reporte = DB::select($query);
                $reporte2 = DB::select("Select * from empresa where id_empresa=".$request->company);
                $reporte3= DB::select("SELECT  plc.codcta,plc.nomcta,plc.id_plan_cuentas FROM asientos_detalle as asdt,plan_cuentas as plc,asientos as asi where {$fields} and plc.id_plan_cuentas=asdt.id_plan_cuentas and asdt.id_asientos=asi.id_asientos and plc.codcta like '{$reporte_empresa_activo[0]->codcta}%' and plc.id_empresa={$request->company} GROUP BY plc.id_plan_cuentas order by plc.codcta asc");
                $reporte_plc=DB::select("SELECT codcta,nomcta,(SELECT sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber)) from asientos_detalle as asdt,asientos as asi  where {$fields} and plan_cuentas.id_plan_cuentas=asdt.id_plan_cuentas and 
                asi.id_asientos=asdt.id_asientos) as saldo,LENGTH(codcta) as longt from plan_cuentas where id_empresa=".$request->company." and codcta like '{$reporte_empresa_activo[0]->codcta}%' order by codcta asc");
                //dd($reporte_plc);
                //pasivo;
                $reporte3_pasivo= DB::select("SELECT  plc.codcta,plc.nomcta,plc.id_plan_cuentas FROM asientos_detalle as asdt,plan_cuentas as plc,asientos as asi where {$fields} and  plc.id_plan_cuentas=asdt.id_plan_cuentas and asdt.id_asientos=asi.id_asientos and plc.codcta like '{$reporte_empresa_pasivo[0]->codcta}%' and plc.id_empresa={$request->company}   GROUP BY plc.id_plan_cuentas order by plc.codcta asc");
                $reporte_plc_pasivo=DB::select("SELECT codcta,nomcta,(SELECT sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber)) from asientos_detalle as asdt,asientos as asi  where {$fields} and plan_cuentas.id_plan_cuentas=asdt.id_plan_cuentas and 
                asi.id_asientos=asdt.id_asientos) as saldo,LENGTH(codcta) as longt from plan_cuentas where id_empresa=".$request->company." and codcta like '{$reporte_empresa_pasivo[0]->codcta}%' order by codcta asc");
                //dd($reporte_plc_pasivo);
                //patrimonio
                $reporte3_patrimonio= DB::select("SELECT  plc.codcta,plc.nomcta,plc.id_plan_cuentas FROM asientos_detalle as asdt,plan_cuentas as plc,asientos as asi where {$fields} and  plc.id_plan_cuentas=asdt.id_plan_cuentas and asdt.id_asientos=asi.id_asientos and plc.codcta like '{$reporte_empresa_patrimonio[0]->codcta}%' and plc.id_empresa={$request->company}  GROUP BY plc.id_plan_cuentas order by plc.codcta asc");
                $reporte_plc_patrimonio=DB::select("SELECT codcta,nomcta,(SELECT sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber)) from asientos_detalle as asdt,asientos as asi  where {$fields} and plan_cuentas.id_plan_cuentas=asdt.id_plan_cuentas and 
                asi.id_asientos=asdt.id_asientos) as saldo,LENGTH(codcta) as longt from plan_cuentas where id_empresa=".$request->company." and codcta like '{$reporte_empresa_patrimonio[0]->codcta}%' order by codcta asc");
                //saldo ant
                $reporte_plc_ant=DB::select("SELECT if(sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber)) is null,0, sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber))) saldo_ant,plc.id_plan_cuentas,nomcta from asientos_detalle,asientos as asi,
                plan_cuentas as plc,proyecto
                where {$inners} and asientos_detalle.id_proyecto=proyecto.id_proyecto
                and asi.id_asientos=asientos_detalle.id_asientos and plc.id_plan_cuentas=asientos_detalle.id_plan_cuentas and plc.id_empresa={$request->company}
                 GROUP BY plc.id_plan_cuentas ORDER BY plc.codcta asc");*/
                //ingreso,costo y gasto
                $reporte3_ing_cos_gas= DB::select("SELECT  plc.codcta,plc.nomcta,plc.id_plan_cuentas FROM asientos_detalle as asdt,plan_cuentas as plc,asientos as asi where {$fields} and  plc.id_plan_cuentas=asdt.id_plan_cuentas and asdt.id_asientos=asi.id_asientos and plc.id_empresa={$request->company}  and (asi.estado='Activo' or asi.estado is null)   GROUP BY plc.id_plan_cuentas order by plc.codcta asc");
                $reporte_plc_ing_cos_gas=DB::select("SELECT codcta,nomcta,(SELECT sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber)) from asientos_detalle as asdt,asientos as asi  where {$fields} and plan_cuentas.id_plan_cuentas=asdt.id_plan_cuentas and 
                asi.id_asientos=asdt.id_asientos and (asi.estado='Activo' or asi.estado is null)) as saldo,LENGTH(codcta) as longt from plan_cuentas where id_empresa=".$request->company."  order by codcta asc");
                // dd("SELECT plan_cuentas.id_plan_cuentas,codcta,nomcta,(SELECT sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber)) from asientos_detalle as asdt,asientos as asi,proyecto as pro  where {$fields} and plan_cuentas.id_plan_cuentas=asdt.id_plan_cuentas and 
                // asi.id_asientos=asdt.id_asientos) as saldo,LENGTH(codcta) as longt from plan_cuentas where id_empresa=".$request->company." order by codcta asc");
                if (!$reporte) {
                    return response('no-data-report', 200)->header('Content-Type', 'application/json');
                } else {
                    if($request->email && $request->destinatario){
                        $Reportes = new generarReportes();
                        $ruta = constant("DATA_EMPRESA") . $request->company . '/contabilidad/asientos_contables';
                        if(!file_exists($ruta)){
                            mkdir($ruta, 0755,true);
                        }
                        //$strPDF = $Reportes->estado_situacion_financiera($reporte_activo, $fecha_inicio[0]->fecha_inicio, $final,$reporte2,$reporte_pasivo,$reporte_patrimonio,$reporte_prueba,$reporte_prueba_2,$reporte_prueba_3,$ruta);
                        //$strPDF = $Reportes->estado_situacion_financiera($reporte_activo, $initial, $final,$reporte2,$reporte_pasivo,$reporte_patrimonio,$reporte,$reporte3,$ruta);
                        $strPDF = $Reportes->estado_situacion_financiera($initial, $final,$reporte2,$reporte3,$reporte_plc,$reporte_plc_ant,$proyecto,$reporte_debe_haber,$reporte_empresa_activo[0]->codcta,$reporte_empresa_pasivo[0]->codcta,$reporte_empresa_patrimonio[0]->codcta,$reporte_empresa_ingreso[0]->codcta,$reporte_empresa_costo[0]->codcta,$reporte_empresa_gasto[0]->codcta,$ruta);
                        $email=new sendEmail();
                        $email->enviarAsientos($reporte2[0],$request->email,$request->destinatario,"estado_situacion_financiera","Estado Situacion Financiera");
                        $cta=$ruta.'/estado_situacion_financiera.pdf';
                        /*if(file_exists($cta)){
                            unlink($cta);
                        }*/
                        return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                        
                    }else{
                        $Reportes = new generarReportes();
                        //$strPDF = $Reportes->estado_situacion_financiera($reporte_activo, $fecha_inicio[0]->fecha_inicio, $final,$reporte2,$reporte_pasivo,$reporte_patrimonio,$reporte_prueba,$reporte_prueba_2,$reporte_prueba_3);
                        //$strPDF = $Reportes->estado_situacion_financiera($reporte_activo, $initial, $final,$reporte2,$reporte_pasivo,$reporte_patrimonio,$reporte_prueba,$reporte_prueba_2,$reporte_prueba_3);
                        $strPDF = $Reportes->estado_situacion_financiera($initial, $final,$reporte2,$reporte3,$reporte_plc,$reporte_plc_ant,$proyecto,$reporte_debe_haber,$reporte_empresa_activo[0]->codcta,$reporte_empresa_pasivo[0]->codcta,$reporte_empresa_patrimonio[0]->codcta,$reporte_empresa_ingreso[0]->codcta,$reporte_empresa_costo[0]->codcta,$reporte_empresa_gasto[0]->codcta);
                        return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                    }
                }
            }
            if($info_reporte["id"] == 5){
                if ($request->dates) {
                    $info_date = json_decode($request->dates, true);
                    if ($request->currentDate !== "true") {
                        $initial = str_replace("-010-","-10-",$info_date["range"]["initial"]);
                        $final = str_replace("-010-","-10-",$info_date["range"]["final"]);
                        if ($info_date["option"] == 1) {
                            array_push($queries, "date(asi.fecha) between date('{$info_date["range"]["initial"]}') and date('{$info_date["range"]["final"]}')\n");
                        }
                        if ($info_date["option"] == 2) {
                            array_push($queries, "date(asi.fecha) between date('{$info_date["range"]["initial"]}') and date('{$info_date["range"]["final"]}')\n");
                        }
                        if ($info_date["option"] == 3) {
                            //array_push($queries, "date(asi.fecha) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')\n");
                            array_push($queries, "date(asi.fecha) between date('{$info_date["range"]["initial"]}') and date('{$info_date["range"]["final"]}')\n");
                            $actual = date("Y-m-d", strtotime($initial));
                            $creado = strtotime ( '-1 day' , strtotime ( $actual ) ) ;
                            $anterior = date("Y-m-d", $creado);
                            $month = date('m');
                            $year = date('Y');
                            $day = date("d", mktime(0,0,0, $month+1, 0, $year));
                        
                            $fecha_final= date('Y-m-d', mktime(0,0,0, $month, $day, $year));
                            array_push($inners, "date(asi.fecha) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$anterior}')\n");
                            array_push($fields,"date(asi.fecha) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')\n");
                        }
                    } else {
                        $initial = $info_date["value"];
                        $final = $info_date["value"];
                        if ($info_date["option"] == 1) {
                            array_push($queries, "date(asi.fecha) = date('{$info_date["value"]}')\n");
                        }
                        if ($info_date["option"] == 2) {
                            array_push($queries, "date(asi.fecha) = date('{$info_date["value"]}')\n");
                        }
                        if ($info_date["option"] == 3) {
                            //array_push($queries, "date(asi.fecha) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$info_date["value"]}')\n");
                            array_push($queries, "date(asi.fecha) = date('{$info_date["value"]}')\n");
                            $actual = date("Y-m-d", strtotime($info_date["value"]));
                            $creado = strtotime ( '-1 day' , strtotime ( $actual ) ) ;
                            $anterior = date("Y-m-d", $creado);
                            $month = date('m');
                            $year = date('Y');
                            $day = date("d", mktime(0,0,0, $month+1, 0, $year));
                        
                            $fecha_final= date('Y-m-d', mktime(0,0,0, $month, $day, $year));
                            //array_push($queries, "date(asi.fecha) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$info_date["value"]}')\n");
                            array_push($inners, "date(asi.fecha) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$anterior}')\n");
                            array_push($fields,"date(asi.fecha) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$info_date["value"]}')\n");
                        }
                    }
                    
                }

                //dd($queries);
                $proyecto="";
                //dd($inners);
                if ($request->project) {
                    $info_payment = json_decode($request->project, true);
                    if ($info_payment["id"] != 0) {
                        /*array_push($queries, "asi.id_proyecto = {$info_payment["id"]}\n");
                        array_push($inners, "asi.id_proyecto = {$info_payment["id"]}\n");
                        array_push($fields,"asi.id_proyecto = {$info_payment["id"]}\n");*/
                        array_push($queries, "asientos_detalle.id_proyecto = {$info_payment["id"]}\n");
                        array_push($inners, "asientos_detalle.id_proyecto = {$info_payment["id"]}\n");
                        array_push($fields,"asdt.id_proyecto = {$info_payment["id"]}\n");
                        $proyecto=$info_payment["name"];
                    }else{
                        $proyecto="Todos";
                    }
                }
                $inners=implode(" and ",$inners);
                $queries = implode(" and ", $queries);
                $fields = implode(" and ", $fields);
                $empresa_activo=" SELECT codcta FROM `plan_cuentas`,empresa where empresa.id_plan_cuentas_ingreso=plan_cuentas.id_plan_cuentas and empresa.id_empresa={$request->company}";
                $empresa_pasivo=" SELECT codcta FROM `plan_cuentas`,empresa where empresa.id_plan_cuentas_costo=plan_cuentas.id_plan_cuentas and empresa.id_empresa={$request->company}";
                $empresa_patrimonio="SELECT codcta FROM `plan_cuentas`,empresa where empresa.id_plan_cuentas_gasto=plan_cuentas.id_plan_cuentas and empresa.id_empresa={$request->company}";
                $reporte_empresa_activo = DB::select($empresa_activo);
                $reporte_empresa_pasivo = DB::select($empresa_pasivo);
                $reporte_empresa_patrimonio = DB::select($empresa_patrimonio);
                $query="SELECT  plc.codcta,plc.nomcta,asdt.debe,asdt.haber,asi.fecha,plc.id_plan_cuentas,asi.codigo,asi.concepto,pro.descripcion FROM asientos_detalle as asdt,plan_cuentas as plc,asientos as asi,proyecto as pro where {$fields} and plc.id_plan_cuentas=asdt.id_plan_cuentas and pro.id_proyecto=asdt.id_proyecto and asi.id_proyecto is not null and pro.id_empresa={$request->company} and (asi.estado='Activo' or asi.estado is null) and asi.id_asientos=asdt.id_asientos order by asi.fecha asc";
                //$query="SELECT  plc.codcta,plc.nomcta,asdt.debe,asdt.haber,asi.fecha,plc.id_plan_cuentas,asi.codigo,asi.concepto,pro.descripcion FROM asientos_detalle as asdt,plan_cuentas as plc,asientos as asi,proyecto as pro where {$fields} and plc.id_plan_cuentas=asdt.id_plan_cuentas and pro.id_proyecto=asi.id_proyecto and pro.id_empresa={$request->company} and asi.id_asientos=asdt.id_asientos order by asi.fecha asc";
                //dd($query);
                $reporte = DB::select($query);
                //dd($reporte);
                $reporte2 = DB::select("Select * from empresa where id_empresa=".$request->company);
                $reporte3= DB::select("SELECT  plc.codcta,plc.nomcta,plc.id_plan_cuentas,pro.descripcion FROM asientos_detalle as asdt,plan_cuentas as plc,asientos as asi,proyecto as pro where {$fields} and plc.id_plan_cuentas=asdt.id_plan_cuentas and asdt.id_asientos=asi.id_asientos and pro.id_proyecto=asi.id_proyecto and plc.id_empresa={$request->company} and (asi.estado='Activo' or asi.estado is null) GROUP BY plc.id_plan_cuentas order by plc.codcta asc");
                //dd($reporte3);
                $reporte_plc=DB::select("SELECT plan_cuentas.id_plan_cuentas,id_grupo,codcta,nomcta,(SELECT sum(if(debe is null,0,debe))-sum(if(haber is null,0,haber)) from asientos_detalle as asdt,asientos as asi,proyecto as pro  where {$fields} and plan_cuentas.id_plan_cuentas=asdt.id_plan_cuentas and 
                asi.id_asientos=asdt.id_asientos and (asi.estado='Activo' or asi.estado is null)) as saldo,LENGTH(codcta) as longt from plan_cuentas where id_empresa=".$request->company." order by codcta asc");  
                $query_plc_ant="SELECT if(sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber)) is null,0, sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber))) saldo_ant,plc.id_plan_cuentas,nomcta from asientos_detalle,asientos as asi,
                plan_cuentas as plc,proyecto as pro
                where {$inners} and asientos_detalle.id_proyecto=pro.id_proyecto and asi.id_proyecto is not null and (asi.estado='Activo' or asi.estado is null)
                and asi.id_asientos=asientos_detalle.id_asientos and plc.id_plan_cuentas=asientos_detalle.id_plan_cuentas and plc.id_empresa={$request->company}
                 GROUP BY plc.id_plan_cuentas ORDER BY plc.codcta asc";
                // $query_plc_ant="SELECT if(sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber)) is null,0, sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber))) saldo_ant,plc.id_plan_cuentas,nomcta from asientos_detalle,asientos as asi,
                // plan_cuentas as plc,proyecto as pro
                // where {$inners} and asi.id_proyecto=pro.id_proyecto
                // and asi.id_asientos=asientos_detalle.id_asientos and plc.id_plan_cuentas=asientos_detalle.id_plan_cuentas and plc.id_empresa={$request->company}
                //  GROUP BY plc.id_plan_cuentas ORDER BY plc.codcta asc";
                //dd($query_plc_ant);
                $reporte_debe_haber=DB::select("SELECT sum(if(debe is null,0,debe)) as debe,sum(if(haber is null,0,haber)) as haber,plc.id_plan_cuentas,nomcta
                from asientos_detalle,asientos as asi,
                plan_cuentas as plc,proyecto as pro
                where {$queries}
                and asientos_detalle.id_proyecto=pro.id_proyecto and asi.id_proyecto is not null
                and asi.id_asientos=asientos_detalle.id_asientos and plc.id_plan_cuentas=asientos_detalle.id_plan_cuentas and plc.id_empresa={$request->company} and (asi.estado='Activo' or asi.estado is null)
                GROUP BY plc.id_plan_cuentas ORDER BY plc.codcta asc");
                // $reporte_debe_haber=DB::select("SELECT sum(if(debe is null,0,debe)) as debe,sum(if(haber is null,0,haber)) as haber,plc.id_plan_cuentas,nomcta
                // from asientos_detalle,asientos as asi,
                // plan_cuentas as plc,proyecto as pro
                // where {$queries}
                // and asi.id_proyecto=pro.id_proyecto
                // and asi.id_asientos=asientos_detalle.id_asientos and plc.id_plan_cuentas=asientos_detalle.id_plan_cuentas and plc.id_empresa={$request->company}
                // GROUP BY plc.id_plan_cuentas ORDER BY plc.codcta asc");
                $reporte_plc_ant=DB::select("SELECT if(sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber)) is null,0, sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber))) saldo_ant,plc.id_plan_cuentas,nomcta 
                from asientos_detalle,asientos as asi,
                plan_cuentas as plc,proyecto as pro
                where {$inners} and asientos_detalle.id_proyecto=pro.id_proyecto and asi.id_proyecto is not null and (asi.estado='Activo' or asi.estado is null)
                and asi.id_asientos=asientos_detalle.id_asientos and plc.id_plan_cuentas=asientos_detalle.id_plan_cuentas and plc.id_empresa={$request->company} 
                GROUP BY plc.id_plan_cuentas ORDER BY plc.codcta asc");
                // $reporte_plc_ant=DB::select("SELECT if(sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber)) is null,0, sum(if(debe is null,0,debe))- sum(if(haber is null,0,haber))) saldo_ant,plc.id_plan_cuentas,nomcta from asientos_detalle,asientos as asi,
                // plan_cuentas as plc,proyecto as pro
                // where {$inners} and asi.id_proyecto=pro.id_proyecto
                // and asi.id_asientos=asientos_detalle.id_asientos and plc.id_plan_cuentas=asientos_detalle.id_plan_cuentas and plc.id_empresa={$request->company}
                // GROUP BY plc.id_plan_cuentas ORDER BY plc.codcta asc");
                if (!$reporte) {
                    return response('no-data-report', 200)->header('Content-Type', 'application/json');
                } else {
                    if($request->email && $request->destinatario ){
                        $Reportes = new generarReportes();
                        $ruta = constant("DATA_EMPRESA") . $request->company . '/contabilidad/asientos_contables';
                        if(!file_exists($ruta)){
                            mkdir($ruta, 0755,true);
                        }
                        //$strPDF = $Reportes->estado_resultado_integral($reporte_activo, $fecha_inicio[0]->fecha_inicio, $final,$reporte2,$reporte_pasivo,$reporte_patrimonio,$reporte_prueba,$reporte_prueba_2,$reporte_prueba_3,$ruta);
                        $strPDF = $Reportes->estado_resultado_integral($initial, $final,$reporte2,$reporte3,$reporte_plc,$reporte_plc_ant,$proyecto,$reporte_debe_haber,$reporte_empresa_activo[0]->codcta,$reporte_empresa_pasivo[0]->codcta,$reporte_empresa_patrimonio[0]->codcta,$ruta);
                        $email=new sendEmail();
                        $email->enviarAsientos($reporte2[0],$request->email,$request->destinatario,"estado_resultado_integral","Estado Resultado Integral");
                        $cta=$ruta.'/estado_resultado_integral.pdf';
                        /*if(file_exists($cta)){
                            unlink($cta);
                        }*/
                        return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                        
                    }else{
                        $Reportes = new generarReportes();
                        //$strPDF = $Reportes->estado_resultado_integral($reporte_activo, $fecha_inicio[0]->fecha_inicio, $final,$reporte2,$reporte_pasivo,$reporte_patrimonio,$reporte_prueba,$reporte_prueba_2,$reporte_prueba_3);
                        $strPDF = $Reportes->estado_resultado_integral($initial, $final,$reporte2,$reporte3,$reporte_plc,$reporte_plc_ant,$proyecto,$reporte_debe_haber,$reporte_empresa_activo[0]->codcta,$reporte_empresa_pasivo[0]->codcta,$reporte_empresa_patrimonio[0]->codcta);
                        return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                    }
                }
            }
            
        
    }

    /**
     * Retorna el ultimo numero de asiento contable manual
     * @author Gabriel Costta
     * @return int
     */
    public function obtenerUltimoNumeroDeAsientoContableManual(Request $request,$id_comprobante)
    {
        $ultimoNumero = new Asientos();
        //dd("SELECT max(asientos.numero) + 2 as numero from asientos,proyecto where asientos.id_proyecto=proyecto.id_proyecto and proyecto.id_empresa={$request->id_empresa} and asientos.id_asientos_comprobante={$id_comprobante}");
        $respuesta=DB::select("SELECT max(asientos.numero) + 1 as numero from asientos where  asientos.id_empresa={$request->id_empresa} and asientos.id_asientos_comprobante={$id_comprobante} and (estado='Activo' or estado is null)");
        //return $ultimoNumero::where("id_asientos_comprobante", $id_comprobante)->max("numero") + 1;
        if($respuesta[0]->numero<=0){
            return 1;
        }else{
            return $respuesta[0]->numero;
        }
        
    }

    /**
     * Retorna el ultimo numero de asiento contable automatico
     * @author Gabriel Costta
     * @return int
     */
    public function obtenerUltimoNumeroDeAsientoContableAutomatico($tipo)
    {
        $comprobante = Asientos_comprobante_automaticos::select("asientos_comprobante_automaticos.*")
            ->where("codigo", "=", $tipo)
            ->get();
        // $ultimoNumero = new Asientos();
        $numero = Asientos::where("id_asientos_comprobante_automaticos", $comprobante[0]["id_asientos_comprobante_automaticos"])->max("numero") + 1;
        // dd($numero);
        return $numero;
    }

    /**
     * Retorna lista de comprobantes
     * @author Gabriel Costta
     */
    public function obtenerListaDeComprobantes($automaticos = false)
    {
        $comprobantesManuales = Asientos_comprobante::select("asientos_comprobante.*")->get()->toArray();
        if ($automaticos) {
            $comprobantesAutomaticos = Asientos_comprobante_automaticos::select("asientos_comprobante_automaticos.*")->get()->toArray();
            $listaComprobantes = array_merge($comprobantesManuales, $comprobantesAutomaticos);
            return $listaComprobantes;
        }
        return $comprobantesManuales;
    }

    /**
     * Lista los asientos costables
     * @author Gabriel Costta
     * @return App\Models\Asientos
     */
    public function listarAsientosContables($id)
    {
        /*$listaDeAsientos = array();
        $asientosManuales = Asientos::select(DB::raw("asientos.*, proyecto.*, asientos_comprobante.*,if(asientos_comprobante.codigo='E',(select sum(IF(forma_pagos.descripcion='CHEQUE',1,0)) from asientos_detalle 
        INNER JOIN forma_pagos
        on forma_pagos.id_forma_pagos=asientos_detalle.id_forma_pagos
        where asientos_detalle.id_asientos=asientos.id_asientos),0) as existe_cheque"))
            ->join("proyecto", "proyecto.id_proyecto", "=", "asientos.id_proyecto")
            ->join("asientos_comprobante", "asientos_comprobante.id_asientos_comprobante", "=", "asientos.id_asientos_comprobante")
            ->where("proyecto.id_empresa","=",$id)
            ->orderBy("asientos.fecha", "DESC")
            ->get()
            ->toArray();
        $asientosAutomaticos = Asientos::select("asientos.*", "proyecto.*", "asientos_comprobante_automaticos.*")
            ->join("proyecto", "proyecto.id_proyecto", "=", "asientos.id_proyecto")
            ->join("asientos_comprobante_automaticos", "asientos_comprobante_automaticos.id_asientos_comprobante_automaticos", "=", "asientos.id_asientos_comprobante_automaticos")
            ->where("proyecto.id_empres","=",$id)
            ->orderBy("asientos.fecha", "DESC")
            ->get()
            ->toArray();
        if (count($asientosManuales) !== 0 && count($asientosAutomaticos) !== 0) {
            $listaDeAsientos = array_merge($asientosManuales, $asientosAutomaticos);
            return $listaDeAsientos;
        }
        if (count($asientosManuales) === 0) {
            return $asientosAutomaticos;
        }
        if (count($asientosAutomaticos) === 0) {
            return $asientosManuales;
        }*/
        
        $cierre_anio=DB::select("SELECT * from asientos where id_empresa={$id} and cierre_contable='Estado Contable'");
        $cierre_mes=DB::select("SELECT * from asientos where id_empresa={$id} and cierre_contable='Cierre Mes'");
        $anios_cierre=[];
        $mes_cierre=[];
        for($i=0; $i<count($cierre_anio); $i++){
            array_push($anios_cierre,$cierre_anio[$i]->periodo);
        }
        for($i=0; $i<count($cierre_mes); $i++){
            array_push($mes_cierre,"'{$cierre_mes[$i]->periodo}'");
        }
        $anios_cierre=implode(",",$anios_cierre);
        $mes_cierre=implode(",",$mes_cierre);
        //dd($anios_cierre);
        $valor_mes=0;
        $valor_anio=0;
        if($mes_cierre){
            $valor_mes=$mes_cierre;
        }
        if($anios_cierre){
            $valor_anio=$anios_cierre;
        }
        $query="SELECT asientos.*, asientos_comprobante.*,if(asientos_comprobante.codigo is not null,(select sum(IF(forma_pagos.descripcion like '%CHEQUE%',1,0)) from asientos_detalle 
                INNER JOIN forma_pagos
                on forma_pagos.id_forma_pagos=asientos_detalle.id_forma_pagos
                where asientos_detalle.id_asientos=asientos.id_asientos),0) as existe_cheque,
                if((SELECT count(*) from asientos where id_empresa={$id} and cierre_contable='Estado Contable')>0,if(SUBSTR(asientos.fecha,1,4) in ({$valor_anio}),1,0),0) as cierre_anio,
                if((SELECT count(*) from asientos where id_empresa={$id} and cierre_contable='Cierre Mes')>0,if(SUBSTR(asientos.fecha,1,7) in ({$valor_mes}),1,0),0) as cierre_mes 
                from asientos
                INNER JOIN proyecto ON proyecto.id_proyecto = asientos.id_proyecto 
                INNER JOIN empresa ON empresa.id_empresa = proyecto.id_empresa
                INNER JOIN asientos_comprobante ON asientos_comprobante.id_asientos_comprobante = asientos.id_asientos_comprobante
                where proyecto.id_empresa = {$id} and (asientos.estado='Activo' or asientos.estado is null)  ORDER BY asientos.fecha desc";
        //dd($query);
        // if(count($cierre_anio)<=0 && count($cierre_mes)<=0){
        //     $query="SELECT asientos.*, proyecto.*, asientos_comprobante.*,if(asientos_comprobante.codigo='E',(select sum(IF(forma_pagos.descripcion='CHEQUE',1,0)) from asientos_detalle 
        //     INNER JOIN forma_pagos
        //     on forma_pagos.id_forma_pagos=asientos_detalle.id_forma_pagos
        //     where asientos_detalle.id_asientos=asientos.id_asientos),0) as existe_cheque,
        //     0 as cierre_anio,
        //     0 as cierre_mes 
        //     from asientos
        //     INNER JOIN proyecto ON proyecto.id_proyecto = asientos.id_proyecto 
        //     INNER JOIN empresa ON empresa.id_empresa = proyecto.id_empresa
        //     INNER JOIN asientos_comprobante ON asientos_comprobante.id_asientos_comprobante = asientos.id_asientos_comprobante
        //     where proyecto.id_empresa = {$id} ORDER BY asientos.fecha desc";
        // }else{
        //     if(count($cierre_anio)>0 && count($cierre_mes)<=0){
        //         $query="SELECT asientos.*, proyecto.*, asientos_comprobante.*,if(asientos_comprobante.codigo='E',(select sum(IF(forma_pagos.descripcion='CHEQUE',1,0)) from asientos_detalle 
        //         INNER JOIN forma_pagos
        //         on forma_pagos.id_forma_pagos=asientos_detalle.id_forma_pagos
        //         where asientos_detalle.id_asientos=asientos.id_asientos),0) as existe_cheque,
        //         if(SUBSTR(asientos.fecha,1,4) in ({$anios_cierre}),1,0) as cierre_anio,
        //         0 as cierre_mes
        //         from asientos
        //         INNER JOIN proyecto ON proyecto.id_proyecto = asientos.id_proyecto 
        //         INNER JOIN empresa ON empresa.id_empresa = proyecto.id_empresa
        //         INNER JOIN asientos_comprobante ON asientos_comprobante.id_asientos_comprobante = asientos.id_asientos_comprobante
        //         where proyecto.id_empresa = {$id} ORDER BY asientos.fecha desc";
        //     }else{
        //         if(count($cierre_anio)<=0 && count($cierre_mes)>0){
        //             $query="SELECT asientos.*, proyecto.*, asientos_comprobante.*,if(asientos_comprobante.codigo='E',(select sum(IF(forma_pagos.descripcion='CHEQUE',1,0)) from asientos_detalle 
        //             INNER JOIN forma_pagos
        //             on forma_pagos.id_forma_pagos=asientos_detalle.id_forma_pagos
        //             where asientos_detalle.id_asientos=asientos.id_asientos),0) as existe_cheque,
        //             0 as cierre_anio,
        //             if(SUBSTR(asientos.fecha,1,7) in ({$mes_cierre}),1,0) as cierre_mes
        //             from asientos
        //             INNER JOIN proyecto ON proyecto.id_proyecto = asientos.id_proyecto 
        //             INNER JOIN empresa ON empresa.id_empresa = proyecto.id_empresa
        //             INNER JOIN asientos_comprobante ON asientos_comprobante.id_asientos_comprobante = asientos.id_asientos_comprobante
        //             where proyecto.id_empresa = {$id} ORDER BY asientos.fecha desc";
        //         }else{
        //             $query="SELECT asientos.*, proyecto.*, asientos_comprobante.*,if(asientos_comprobante.codigo='E',(select sum(IF(forma_pagos.descripcion='CHEQUE',1,0)) from asientos_detalle 
        //             INNER JOIN forma_pagos
        //             on forma_pagos.id_forma_pagos=asientos_detalle.id_forma_pagos
        //             where asientos_detalle.id_asientos=asientos.id_asientos),0) as existe_cheque,
        //             if(SUBSTR(asientos.fecha,1,4) in ({$anios_cierre}),1,0) as cierre_anio,
        //             if(SUBSTR(asientos.fecha,1,7) in ({$mes_cierre}),1,0) as cierre_mes
        //             from asientos
        //             INNER JOIN proyecto ON proyecto.id_proyecto = asientos.id_proyecto 
        //             INNER JOIN empresa ON empresa.id_empresa = proyecto.id_empresa
        //             INNER JOIN asientos_comprobante ON asientos_comprobante.id_asientos_comprobante = asientos.id_asientos_comprobante
        //             where proyecto.id_empresa = {$id} ORDER BY asientos.fecha desc";
        //         }
        //     }
            
        // }
        //dd($query);
        $asientosManuales=DB::select($query);
        return $asientosManuales;
    }

    /**
     * Obtiene un asiento contable buscado por su id
     * @author Gabriel Costta
     * @param id
     * @return App\Models\Asientos
     */
    public function ObtenerAsientoContable($id)
    {
        $asiento = Asientos::find($id);
        if ($asiento->automatico === 1) {
            $asientoContable = DB::transaction(function () use ($id) {
                $cabecera = Asientos::select("asientos.*", "proyecto.*", "asientos_comprobante_automaticos.*")
                    ->join("proyecto", "proyecto.id_proyecto", "=", "asientos.id_proyecto")
                    ->join("asientos_comprobante_automaticos", "asientos_comprobante_automaticos.id_asientos_comprobante_automaticos", "=", "asientos.id_asientos_comprobante_automaticos")
                    ->where("id_asientos", $id)
                    ->get();
                $listaDeAsientos = Asientos_contables_detalle::select("asientos_detalle.*", "plan_cuentas.*")
                    ->join("plan_cuentas", "plan_cuentas.id_plan_cuentas", "=", "asientos_detalle.id_plan_cuentas")
                    ->where("id_asientos", $id)
                    ->get();
                return [
                    "cabecera" => $cabecera[0],
                    "listaDeAsientos" => $listaDeAsientos,
                ];
            });
            return $asientoContable;
        }
        if ($asiento->automatico === 0) {
            $asientoContable = DB::transaction(function () use ($id) {
                $cabecera = Asientos::select("asientos.*", "proyecto.*", "asientos_comprobante.*")
                    ->join("proyecto", "proyecto.id_proyecto", "=", "asientos.id_proyecto")
                    ->join("asientos_comprobante", "asientos_comprobante.id_asientos_comprobante", "=", "asientos.id_asientos_comprobante")
                    ->where("id_asientos", $id)
                    ->get();
                $listaDeAsientos = Asientos_contables_detalle::select("asientos_detalle.*", "plan_cuentas.*")
                    ->join("plan_cuentas", "plan_cuentas.id_plan_cuentas", "=", "asientos_detalle.id_plan_cuentas")
                    ->where("id_asientos", $id)
                    ->get();
                return [
                    "cabecera" => $cabecera[0],
                    "listaDeAsientos" => $listaDeAsientos,
                ];
            });
            return $asientoContable;
        }
    }
    public function validarCheque(Request $request){
        $existe=0;
        
        //return $request->asientosContablesManuales[0]['detalle']["no_documento"];


            foreach($request->asientosContablesManuales as $asientos){
                //dd($asientos["detalle"]["no_documento"]);
                //$asientos = json_decode($asientos, true);
                //return $asientos['detalle']["no_documento"];
                if ( $asientos["detalle"]["no_documento"]!==null) {
                    $val=DB::select("SELECT no_documento,id_detalle from asientos_detalle,forma_pagos where asientos_detalle.id_forma_pagos=forma_pagos.id_forma_pagos and asientos_detalle.id_plan_cuentas={$asientos["detalle"]["idCuentaContable"]} and forma_pagos.tipo_forma_pago like '%Cheque%' and forma_pagos.id_empresa={$request->id_empresa} and asientos_detalle.no_documento='{$asientos["detalle"]["no_documento"]}'");
                    if($val){
                        $existe=1;
                    }
                }
            }

        
        if($existe<=0){  
            return "bien";
        }else{
            return "existe";
        }

    }
    public function validarChequeEditar(Request $request){
        $existe=0;
        
        //return $request->asientosContablesManuales[0]['detalle']["no_documento"];


            foreach($request->asientosContablesManuales as $asientos){
                //$info_date = json_decode($asientos, true);
                //return $info_date['detalle']["no_documento"];
                if ( $asientos["detalle"]["no_documento"]!==null && isset($asientos["detalle"]["id_detalle"])) {
                    $val=DB::select("SELECT no_documento,id_detalle from asientos_detalle,forma_pagos where asientos_detalle.id_forma_pagos=forma_pagos.id_forma_pagos and asientos_detalle.id_plan_cuentas={$asientos["detalle"]["idCuentaContable"]} and forma_pagos.tipo_forma_pago like '%Cheque%' and forma_pagos.id_empresa={$request->id_empresa} and asientos_detalle.no_documento='{$asientos["detalle"]["no_documento"]}'");
                    if($val){
                        $existe=1;
                    }
                }
            }

        
        if($existe<=0){  
            return "bien";
        }else{
            return "existe";
        }

    }
    /**
     * Elimina un asiento contable
     * @param Illuminate\Http\Request,
     * @author Gabriel Costta,
     */
    public function eliminarAsientocontable(Request $request)
    {
        ini_set('max_execution_time', 53200);
        $asiento=DB::select("SELECT * from asientos_detalle where id_asientos={$request->id}");
        for ($z = 0; $z < count($asiento); $z++) {
            $id_detalle=$asiento[$z]->id_detalle;
            $conc=DB::select("SELECT * from conciliacion where detalle_asiento={$id_detalle}");
            if(count($conc)>0){
                Conciliacion::where("detalle_asiento","=",$id_detalle)->delete();
            }
        }
        $asiento=DB::select("SELECT *,if(concepto like '%Anticipo%','si','no') as anticipo FROM asientos where id_asientos={$request->id}");
        Asientos::where("id_asientos", "=", $request->id)->update(['estado'=>'Inactivo','udelete'=>$request->id_user]);
        //DB::update("UPDATE asientos set ");
        switch ($request->id_comprobante) {
            case 4:
                RolPago::where('cod_rol_pago',$request->cod_rol)->update(['contabilidad'=>null]);
                break;
            case 5:
                RolProviciones::where('cod_rol_provision',$request->cod_rol)->update(['contabilidad'=>null]);
                break;
            case 6:
                Factura::where('id_factura',$request->cod_rol)->update(['contabilidad'=>null]);
                break;
            case 7:
                FacturaCompra::where('id_factcompra',$request->cod_rol)->update(['contabilidad'=>null]);
                break;
            case 8:
                $cta=DB::select("SELECT * FROM ctas_cobrar where id_ctascobrar={$asiento[0]->codigo_rol} and tipo=3");
                if(count($cta)>0 && $asiento[0]->anticipo=='si'){
                    $cta_pago=DB::select("SELECT * FROM ctas_cobrar_pagos where referencia={$asiento[0]->codigo_rol} and pagos_por='Anticipo'");
                    Cuentaporcobrar::where('id_ctascobrar',$cta[0]->id_ctascobrar)->update(['contabilidad'=>null]);
                    if(count($cta_pago)>0){
                        Ctas_cobrar_pagos::where('id_ctas_cobrar_pagos',$cta_pago[0]->id_ctas_cobrar_pagos)->update(['contabilidad'=>null]);
                        break;
                    }
                }else{  
                    Ctas_cobrar_pagos::where('id_ctas_cobrar_pagos',$request->cod_rol)->update(['contabilidad'=>null]);
                    break;
                }
                
            case 9:
                $cta=DB::select("SELECT * FROM ctas_pagar where id_ctaspagar={$asiento[0]->codigo_rol} and tipo=3");
                if(count($cta)>0 && $asiento[0]->anticipo=='si'){
                    $cta_pago=DB::select("SELECT * FROM ctas_pagar_pagos where referencia={$asiento[0]->codigo_rol} and pagos_por='Anticipo'");
                    Cuentaporpagar::where('id_ctaspagar',$cta[0]->id_ctaspagar)->update(['contabilidad'=>null]);
                    if(count($cta_pago)>0){
                        Ctas_pagar_pagos::where('id_ctas_pagar_pagos',$cta_pago[0]->id_ctas_pagar_pagos)->update(['contabilidad'=>null]);
                        break;
                    }
                    
                }else{
                    Ctas_pagar_pagos::where('id_ctas_pagar_pagos',$request->cod_rol)->update(['contabilidad'=>null]);
                    break;
                }
                
            case 10:
                Importacion::where('id_importacion',$request->cod_rol)->update(['contabilidad'=>null]);
                break;
            case 11:
                Notacredito::where('id_nota_credito',$request->cod_rol)->update(['contabilidad'=>null]);
                break;
            case 12:
                Notadebito::where('id_nota_debito',$request->cod_rol)->update(['contabilidad'=>null]);
                break;
            case 13:
                NotacreditoCompra::where('id_nota_credito_compra',$request->cod_rol)->update(['contabilidad'=>null]);
                break;
            case 14:
                Notadebitocompra::where('id_nota_debito_compra',$request->cod_rol)->update(['contabilidad'=>null]);
                break;
            case 15:
                BodegaIngreso::where('id_bodega_ingreso',$request->cod_rol)->update(['contabilidad'=>null]);
                break;
            case 16:
                BodegaEgreso::where('id_bodega_egreso',$request->cod_rol)->update(['contabilidad'=>null]);
                break;
            case 17:
                BodegaEgreso::where('id_bodega_egreso',$request->cod_rol)->update(['contabilidad'=>null]);
                break;
            case 18:
                BodegaTransferencia::where('id_bodega_transferencia',$request->cod_rol)->update(['contabilidad'=>null]);
                break;
            case 19:
                Depreciacion::where('id_depreciacion',$request->cod_rol)->update(['contabilidad'=>null]);
                break;
            case 21:
                ProcesoProduccion::where('id_proceso_produccion',$request->cod_rol)->update(['contabilidad'=>null]);
                break;
            case 22:
                NotaVenta::where('id_nota_venta',$request->cod_rol)->update(['contabilidad'=>null]);
                break;
            case 23:
                LiquidacionCompra::where('id_liquidacion_compra',$request->cod_rol)->update(['contabilidad'=>null]);
                break;
            case 24:
                Ctas_pagar_pagos::where('id_ctas_pagar_pagos',$request->cod_rol)->update(['contabilidad'=>null]);
                break;
        }
        /*if($request->id_comprobante==4){
            RolPago::where('cod_rol_pago',$request->cod_rol)->update(['contabilidad'=>null]);
        }else{
            if($request->id_comprobante==5){
                RolProviciones::where('cod_rol_provision',$request->cod_rol)->update(['contabilidad'=>null]);
            }else{
                if($request->id_comprobante==6){
                    Factura::where('id_factura',$request->cod_rol)->update(['contabilidad'=>null]);
                }else{
                    if($request->id_comprobante==7){
                        FacturaCompra::where('id_factcompra',$request->cod_rol)->update(['contabilidad'=>null]);
                    }else{
                        if($request->id_comprobante==8){
                            Ctas_cobrar_pagos::where('id_ctas_cobrar_pagos',$request->cod_rol)->update(['contabilidad'=>null]);
                        }else{
                            if($request->id_comprobante==9){
                                Ctas_pagar_pagos::where('id_ctas_pagar_pagos',$request->cod_rol)->update(['contabilidad'=>null]);
                            }else{
                                if($request->id_comprobante==10){
                                    Importacion::where('id_importacion',$request->cod_rol)->update(['contabilidad'=>null]);
                                }else{
                                    if($request->id_comprobante==11){
                                        Notacredito::where('id_nota_credito',$request->cod_rol)->update(['contabilidad'=>null]);
                                    }else{
                                        if($request->id_comprobante==12){
                                            Notadebito::where('id_nota_debito',$request->cod_rol)->update(['contabilidad'=>null]);
                                        }else{
                                            if($request->id_comprobante==13){
                                                NotacreditoCompra::where('id_nota_credito_comprar',$request->cod_rol)->update(['contabilidad'=>null]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }*/   

        return [
            "mensaje" => "Registro eliminado",
        ];
    }
    public function costo_venta($id){
        ini_set('max_execution_time', 53200);
        $bd=DB::select("SELECT * from bodega_egreso where id_factura is not null and id_empresa={$id}");
        $empresa=DB::select("SELECT * from empresa where id_empresa={$id}");                
        $data = "Registros Cambiados COSTO VENTA Asiento Exitosamente ".$empresa[0]->nombre_empresa;
        for ($z = 0; $z < count($bd); $z++) {
            $cod = $bd[$z]->id_bodega_egreso;
            $desp= $bd[$z]->observ_egreso;
            $asiento=DB::select("SELECT asientos.* from asientos INNER JOIN proyecto on proyecto.id_proyecto=asientos.id_proyecto where id_asientos_comprobante=17 and proyecto.id_empresa={$id} and asientos.concepto like '%{$desp}%'");
            if(count($asiento)>0){
                $asi=Asientos::find($asiento[0]->id_asientos);
                $asi->codigo_rol=$cod;
                $asi->save();
            }
        }

        return $data;
    }
    public function costo_venta_asiento($id){
        ini_set('max_execution_time', 53200);
        $bd=DB::select("SELECT  asientos.* from asientos INNER JOIN proyecto on proyecto.id_proyecto=asientos.id_proyecto where id_asientos_comprobante=17 and proyecto.id_empresa={$id}");
        $empresa=DB::select("SELECT * from empresa where id_empresa={$id}");                
        $data = "Registros2 Cambiados2 COSTO VENTA2 Asiento2 Exitosamente ".$empresa[0]->nombre_empresa;
        for ($z = 0; $z < count($bd); $z++) {
            $cod = $bd[$z]->codigo_rol;
            $desp= $bd[$z]->concepto;
            $asiento=DB::select("SELECT * from bodega_egreso where id_factura is not null  and id_empresa={$id} and id_bodega_egreso={$cod}");
            if(count($asiento)>0){
                // $asi=BodegaEgreso::find($asiento[0]->id_bodega_egreso);
                // $asi->contabilidad=1;
                // $asi->save();
                DB::update("UPDATE bodega_egreso set contabilidad=1 where id_bodega_egreso={$asiento[0]->id_bodega_egreso}");
            }
        }
        
        return $data;
    }

    /**
     * Busqueda de coincidencia
     * @author Gabriel Costta
     * @param Illuminate\Http\Request
     */
    public function buscarCoincidencia(Request $request)
    {
        $cierre_anio=DB::select("SELECT * from asientos where id_empresa={$request["id_empresa"]} and cierre_contable='Estado Contable'");
        $cierre_mes= DB::select("SELECT * from asientos where id_empresa={$request["id_empresa"]} and cierre_contable='Cierre Mes'");
        $anios_cierre=[];
        $mes_cierre=[];
        for($i=0; $i<count($cierre_anio); $i++){
            array_push($anios_cierre,$cierre_anio[$i]->periodo);
        }
        for($i=0; $i<count($cierre_mes); $i++){
            array_push($mes_cierre,"'{$cierre_mes[$i]->periodo}'");
        }
        $anios_cierre=implode(",",$anios_cierre);
        $mes_cierre=implode(",",$mes_cierre);
        $valor_mes=0;
        $valor_anio=0;
        if($mes_cierre){
            $valor_mes=$mes_cierre;
        }
        if($anios_cierre){
            $valor_anio=$anios_cierre;
        }
        $query="SELECT asientos.*, asientos_comprobante.*,if(asientos_comprobante.codigo is not null,(select sum(IF(forma_pagos.descripcion like '%Cheque%',1,0)) from asientos_detalle 
                INNER JOIN forma_pagos
                on forma_pagos.id_forma_pagos=asientos_detalle.id_forma_pagos
                where asientos_detalle.id_asientos=asientos.id_asientos),0) as existe_cheque,
                if((SELECT count(*) from asientos where id_empresa={$request["id_empresa"]} and cierre_contable='Estado Contable')>0,if(SUBSTR(asientos.fecha,1,4) in ({$valor_anio}),1,0),0) as cierre_anio,
                if((SELECT count(*) from asientos where id_empresa={$request["id_empresa"]} and cierre_contable='Cierre Mes')>0,if(SUBSTR(asientos.fecha,1,7) in ({$valor_mes}),1,0),0) as cierre_mes 
                from asientos
                INNER JOIN proyecto ON proyecto.id_proyecto = asientos.id_proyecto 
                INNER JOIN empresa ON empresa.id_empresa = proyecto.id_empresa
                INNER JOIN asientos_comprobante ON asientos_comprobante.id_asientos_comprobante = asientos.id_asientos_comprobante
                where proyecto.id_empresa = {$request["id_empresa"]} and (asientos.numero like '%{$request["query"]}%' or asientos.fecha like '%{$request["query"]}%' or asientos.razon_social like '%{$request["query"]}%' or asientos.codigo like '%{$request["query"]}%' or asientos.ruc_ci like '%{$request["query"]}%' or asientos_comprobante.tipo like '%{$request["query"]}%' or asientos_comprobante.codigo like '%{$request["query"]}%' or asientos.concepto like '%{$request["query"]}%') and (asientos.estado='Activo' or asientos.estado is null)  ORDER BY asientos.fecha desc";
        // if(count($cierre_anio)<=0 && count($cierre_mes)<=0){
        //     $query="SELECT asientos.*, proyecto.*, asientos_comprobante.*,if(asientos_comprobante.codigo='E',(select sum(IF(forma_pagos.descripcion='CHEQUE',1,0)) from asientos_detalle 
        //     INNER JOIN forma_pagos
        //     on forma_pagos.id_forma_pagos=asientos_detalle.id_forma_pagos
        //     where asientos_detalle.id_asientos=asientos.id_asientos),0) as existe_cheque,
        //     0 as cierre_anio,
        //     0 as cierre_mes 
        //     from asientos
        //     INNER JOIN proyecto ON proyecto.id_proyecto = asientos.id_proyecto 
        //     INNER JOIN empresa ON empresa.id_empresa = proyecto.id_empresa
        //     INNER JOIN asientos_comprobante ON asientos_comprobante.id_asientos_comprobante = asientos.id_asientos_comprobante
        //     where proyecto.id_empresa = {$request["id_empresa"]} and (asientos.numero like '%{$request["query"]}%' or asientos.fecha like '%{$request["query"]}%' or asientos.razon_social like '%{$request["query"]}%' or asientos.codigo like '%{$request["query"]}%' or asientos.ruc_ci like '%{$request["query"]}%' or asientos_comprobante.tipo like '%{$request["query"]}%' or asientos_comprobante.codigo like '%{$request["query"]}%') 
        //     ORDER BY asientos.fecha desc";
        // }else{
        //     if(count($cierre_anio)>0 && count($cierre_mes)<=0){
        //         $query="SELECT asientos.*, proyecto.*, asientos_comprobante.*,if(asientos_comprobante.codigo='E',(select sum(IF(forma_pagos.descripcion='CHEQUE',1,0)) from asientos_detalle 
        //         INNER JOIN forma_pagos
        //         on forma_pagos.id_forma_pagos=asientos_detalle.id_forma_pagos
        //         where asientos_detalle.id_asientos=asientos.id_asientos),0) as existe_cheque,
        //         if(SUBSTR(asientos.fecha,1,4) in ({$anios_cierre}),1,0) as cierre_anio,
        //         0 as cierre_mes 
        //         from asientos
        //         INNER JOIN proyecto ON proyecto.id_proyecto = asientos.id_proyecto 
        //         INNER JOIN empresa ON empresa.id_empresa = proyecto.id_empresa
        //         INNER JOIN asientos_comprobante ON asientos_comprobante.id_asientos_comprobante = asientos.id_asientos_comprobante
        //         where proyecto.id_empresa = {$request["id_empresa"]} and (asientos.numero like '%{$request["query"]}%' or asientos.fecha like '%{$request["query"]}%' or asientos.razon_social like '%{$request["query"]}%' or asientos.codigo like '%{$request["query"]}%' or asientos.ruc_ci like '%{$request["query"]}%' or asientos_comprobante.tipo like '%{$request["query"]}%' or asientos_comprobante.codigo like '%{$request["query"]}%') 
        //         ORDER BY asientos.fecha desc";
        //     }else{
        //         if(count($cierre_anio)<=0 && count($cierre_mes)>0){
        //             $query="SELECT asientos.*, proyecto.*, asientos_comprobante.*,if(asientos_comprobante.codigo='E',(select sum(IF(forma_pagos.descripcion='CHEQUE',1,0)) from asientos_detalle 
        //             INNER JOIN forma_pagos
        //             on forma_pagos.id_forma_pagos=asientos_detalle.id_forma_pagos
        //             where asientos_detalle.id_asientos=asientos.id_asientos),0) as existe_cheque,
        //             0 as cierre_anio,
        //             if(SUBSTR(asientos.fecha,1,7) in ({$mes_cierre}),1,0) as cierre_mes 
        //             from asientos
        //             INNER JOIN proyecto ON proyecto.id_proyecto = asientos.id_proyecto 
        //             INNER JOIN empresa ON empresa.id_empresa = proyecto.id_empresa
        //             INNER JOIN asientos_comprobante ON asientos_comprobante.id_asientos_comprobante = asientos.id_asientos_comprobante
        //             where proyecto.id_empresa = {$request["id_empresa"]} and (asientos.numero like '%{$request["query"]}%' or asientos.fecha like '%{$request["query"]}%' or asientos.razon_social like '%{$request["query"]}%' or asientos.codigo like '%{$request["query"]}%' or asientos.ruc_ci like '%{$request["query"]}%' or asientos_comprobante.tipo like '%{$request["query"]}%' or asientos_comprobante.codigo like '%{$request["query"]}%') 
        //             ORDER BY asientos.fecha desc";
        //         }else{
        //             $query="SELECT asientos.*, proyecto.*, asientos_comprobante.*,if(asientos_comprobante.codigo='E',(select sum(IF(forma_pagos.descripcion='CHEQUE',1,0)) from asientos_detalle 
        //             INNER JOIN forma_pagos
        //             on forma_pagos.id_forma_pagos=asientos_detalle.id_forma_pagos
        //             where asientos_detalle.id_asientos=asientos.id_asientos),0) as existe_cheque,
        //             if(SUBSTR(asientos.fecha,1,4) in ({$anios_cierre}),1,0) as cierre_anio,
        //             if(SUBSTR(asientos.fecha,1,7) in ({$mes_cierre}),1,0) as cierre_mes 
        //             from asientos
        //             INNER JOIN proyecto ON proyecto.id_proyecto = asientos.id_proyecto 
        //             INNER JOIN empresa ON empresa.id_empresa = proyecto.id_empresa
        //             INNER JOIN asientos_comprobante ON asientos_comprobante.id_asientos_comprobante = asientos.id_asientos_comprobante
        //             where proyecto.id_empresa = {$request["id_empresa"]} and (asientos.numero like '%{$request["query"]}%' or asientos.fecha like '%{$request["query"]}%' or asientos.razon_social like '%{$request["query"]}%' or asientos.codigo like '%{$request["query"]}%' or asientos.ruc_ci like '%{$request["query"]}%' or asientos_comprobante.tipo like '%{$request["query"]}%' or asientos_comprobante.codigo like '%{$request["query"]}%') 
        //             ORDER BY asientos.fecha desc";
        //         }
        //     }
            
        // }
        $asientosManuales=DB::select($query);
        return $asientosManuales;
        //return $resultados;
    }
}
