<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PDF;

use App\Models\Factura;

class PdfController extends Controller{
    public function factura_venta(Request $request){
        $rs = Factura::all();
        $hola = DB::select("select factura.*,cliente.nombre from factura,cliente where factura.id_cliente=cliente.id_cliente");
        $adios = Factura::all();
        $carpetanombre = constant("DATA_EMPRESA").$request->id_empresa."/comprobantes/factura/";
        $carpeta1 = $carpetanombre.$request->claveAcceso.".xml";
        $dataComprobante = simplexml_load_file($carpeta1);
        $clave=$request->claveAcceso;
        $empresa=$request->id_empresa;
        $logo=$request->imagen;
        $respuesta="Factura_Venta.pdf";
        $pdf =  PDF::loadView('pdf/factura_venta', compact('rs','hola','adios','dataComprobante','clave','empresa','logo'));
        return $pdf->save($respuesta);
    }
    public function factura_compra(){
        $rs = Factura::all();
        $hola = "hola mundo";
        $adios = Factura::all();
        $pdf = PDF::loadView('pdf/factura_venta', compact('rs','hola','adios'));
        return $pdf->setPaper('a4')->stream('Factura_Venta.pdf');
    } 
    public function diario(Request $request){
        $rs = Factura::all();
        $hola = $request->company;
        $adios = Factura::all();
        $queries = [];
        $inners = [];
        $fields = [];
        $initial = null;
        $final = null;
        $fecha_inicio=DB::select("SELECT min(fecha) as fecha_inicio from asientos,proyecto where proyecto.id_empresa=".$request->company);
        if ($request->dates) {
            $info_date = json_decode($request->dates, true);
            if ($request->currentDate !== "true") {
                $initial = $info_date["range"]["initial"];
                $final = $info_date["range"]["final"];
                if ($info_date["option"] == 1) {
                    array_push($queries, "date(asi.fecha) between date('{$info_date["range"]["initial"]}') and date('{$info_date["range"]["final"]}')\n");
                }
                if ($info_date["option"] == 2) {
                    array_push($queries, "date(asi.fecha) between date('{$info_date["range"]["initial"]}') and date('{$info_date["range"]["final"]}')\n");
                }
                if ($info_date["option"] == 3) {
                    array_push($queries, "date(asi.fecha) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')\n");
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
                    //array_push($queries, "date(asi.fecha_pago) = date('{$info_date["value"]}')\n");
                    array_push($queries, "date(asi.fecha) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$info_date["value"]}')\n");
                }
            }
        }
        $queries=implode("and",$queries);
        $query="SELECT asi.concepto,plc.codcta,plc.nomcta,asidet.* from asientos as asi,asientos_detalle as asidet,plan_cuentas as plc where {$queries} and asidet.id_asientos=asi.id_asientos and asidet.id_plan_cuentas=plc.id_plan_cuentas";
        $reporte = DB::select($query);
        $pdf = PDF::loadView('pdf/diario_general', compact('rs','hola','adios','reporte'));
        return $pdf->setPaper('a4')->stream('Factura_Venta.pdf');
    } 
}
