<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cuentaporpagar;
use App\Models\Ctas_pagar_pagos;
use App\Models\Creditocompra;
use App\Models\Asientos;
use App\Models\Asientos_contables_detalle;
use Carbon\Carbon;

//recupera las librerias de generar reporte y envio de email del SRI ubicado en class de controllers
include 'class/generarReportes.php';
include 'class/sendEmail.php';

use DOMDocument;
use generarReportes;
use sendEmail;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CuentasPorPagarImport;

class CuentaporpagarController extends Controller{
    public function index($id){
        //recupera la conexión de retencion factura compra, factura compra y proveedor llamado por el id de proveedor
        $recupera = DB::select("SELECT rfc.*, fc.*, p.nombre_proveedor  FROM retencion_factura_comp rfc INNER JOIN factura_compra fc on rfc.id_factura = fc.id_factcompra LEFT JOIN proveedor p ON p.id_proveedor = fc.id_proveedor WHERE p.id_proveedor = $id");
        return $recupera;
    }
    public function indexdetalle($id){
        //recupera las cuentas por pagar por proveedor cuando el tipo sea 1 osea credito (2 es pagos) y lo llama mediante el id del proveedor y que el valor del cobro sea mayor al valor pagado del mismo
        $recupera = DB::select("SELECT *, (select nombre_proveedor from proveedor where id_proveedor = ctas_pagar.id_proveedor) as nombre, if(ctas_pagar.id_factura_compra is not null,(select descripcion from factura_compra where id_factcompra = ctas_pagar.id_factura_compra),(select descripcion from liquidacion_compra where id_liquidacion_compra = ctas_pagar.id_liquidacion_compra)) as clave_acceso, referencias AS  id_factura2, CONCAT('001-001-',LPAD(referencias,9,'0')) AS  clave_acceso2 from ctas_pagar where ctas_pagar.id_proveedor = $id and ctas_pagar.valor_cuota > (if(ctas_pagar.valor_pagado is not null, ctas_pagar.valor_pagado, 0)+if(ctas_pagar.pago_favor is not null, ctas_pagar.pago_favor, 0)) and ctas_pagar.tipo = 1");
        //dd("SELECT *, (select nombre_proveedor from proveedor where id_proveedor = ctas_pagar.id_proveedor) as nombre, if(ctas_pagar.id_factura_compra is not null,(select descripcion from factura_compra where id_factcompra = ctas_pagar.id_factura_compra),(select descripcion from liquidacion_compra where id_liquidacion_compra = ctas_pagar.id_liquidacion_compra)) as clave_acceso, referencias AS  id_factura2, CONCAT('001-001-',LPAD(referencias,9,'0')) AS  clave_acceso2 from ctas_pagar where ctas_pagar.id_proveedor = $id and ctas_pagar.valor_cuota > ctas_pagar.valor_pagado and ctas_pagar.tipo = 1");
        return $recupera;
    }
    public function ajustarpagos($empresa){
        $pagos = DB::select("SELECT * FROM ctas_pagar_pagos INNER JOIN proveedor ON proveedor.id_proveedor=ctas_pagar_pagos.id_proveedor WHERE proveedor.id_empresa={$empresa}");
        $ctas_pagar = DB::select("SELECT * FROM ctas_pagar WHERE id_empresa={$empresa} ORDER BY id_ctaspagar");

        for($i=0; $i<count($pagos); $i++){

            $referencias = explode(";", $pagos[$i]->referencia);
            $j=0;

            if(is_int(count($referencias)/4)){

                while($j<count($referencias)){
                    
                    $id_ctaspagar = $referencias[$j+1];
                    $monto = $referencias[$j+2];

                    foreach($ctas_pagar as $cta){
                        if($cta->id_ctaspagar==$id_ctaspagar && ($cta->valor_pagado!=$monto && $cta->valor_pagado<$monto)){
                            echo $cta->id_ctaspagar . ": " . $cta->valor_pagado . " / " .$monto;
                            echo "<br>";
                            DB::update("UPDATE ctas_pagar set valor_pagado=".$monto." where id_ctaspagar='".$cta->id_ctaspagar."'");
                        }
                    }

                    $j=$j+4;
                }

            }

        }

        /*echo "<pre>";
        print_r($ctas_pagar);
        echo "</pre>";*/

    }
    public function agregarcobros(Request $request){
        //trae el ultimo posicion ctas
        $for_pago=DB::select("SELECT * FROM proveedor where id_proveedor={$request->id_cliente}");
        $pos0=DB::select("SELECT max(id_ctas_pagar_pagos) as posicion from ctas_pagar_pagos INNER JOIN proveedor ON proveedor.id_proveedor=ctas_pagar_pagos.id_proveedor where proveedor.id_empresa={$for_pago[0]->id_empresa}");
        if(count($pos0)>0){
            $pos=DB::select("SELECT count(id_ctas_pagar_pagos) as posicion from ctas_pagar_pagos INNER JOIN proveedor ON proveedor.id_proveedor=ctas_pagar_pagos.id_proveedor where proveedor.id_empresa={$for_pago[0]->id_empresa}");
        }else{
            $pos=[];
        }
        $conteo=1;
        if(count($pos)>0){
            $conteo=$pos[0]->posicion+1;
        }
        //registra los cobros
        $fecha_registro= $request->fecha_registro;
        //guarda la fecha actual del servidor
        $hoy = Carbon::now();
        //si se va a pagar un anticipo
        if($request->exist_anticipos==true){
            $valor_pagar = $request->valor_real + $request->descuento_pago;

            //cuentas pagar pagos genera la cabezera del registro
            $cxcp = new Ctas_pagar_pagos();
            $cxcp->pagos_por = $request->pagos_por;
            $cxcp->pago_anticipo = 1;
            $cxcp->nro_tarjeta = $request->numero_tarjeta;
            $cxcp->referencia = "Pagos";
            $cxcp->valor_seleccionado = $request->valor_select;
            $cxcp->descuento_porcentaje = $request->descuento_porcentaje;
            $cxcp->descuento_pago = $request->descuento_pago;
            $cxcp->valor_real_pago = $request->valor_real;
            $cxcp->id_forma_pagos = $request->forma_pago;
            $cxcp->id_banco = $request->banco;
            $cxcp->id_proveedor = $request->id_cliente;
            $cxcp->fecha_pago = $hoy;
            $cxcp->fecha_registro = $fecha_registro;
            $cxcp->posicion = $conteo;
            $cxcp->ucrea = $request->id_user;
            $cxcp->save();

            //recupera el id de la cuenta
            $idcxcp = $cxcp->id_ctas_pagar_pagos;
            $referencia = null;
            for ($c = 0; $c < count($request->anticipos); $c++) {
                if (isset($request->anticipos[$c]["agregar"])) {
                    if ($request->anticipos[$c]["agregar"]) {
                        //cuando se va a hacer varios pagos o pagos parciales entrara a este if y restara el valor_cobrar con el valor
                        //caso contrario cuando el pago es menoro igual al saldo entra a else y genera el guardado y guarda directamente en el su Ctas_cobrar_pagos
                        if ($valor_pagar > ($request->anticipos[$c]["abono"])) {
                            //cuentas por cobrar recupera y edita los valores
                            $pago = Cuentaporpagar::findOrFail($request->anticipos[$c]["id_ctaspagar"]);
                            $pago->pagos_por = $request->pagos_por;
                            $pago->id_forma_pagos = $request->forma_pago;
                            $pago->id_banco = $request->banco;
                            $pago->numero_tarjeta = $request->numero_tarjeta;
                            $pago->descuento = $pago->descuento + $request->descuento_pago;
                            $pago->valor_pagado = $pago->valor_pagado + ($request->anticipos[$c]["abono"]);
							$pago->abono = $pago->abono - ($request->anticipos[$c]["abono"]);
                            //$pago->fecha_registro = $request->fecha_registro;
                            $pago->umodifica = $request->id_user;
                            $pago->save();
                            $valor_pagar = $valor_pagar - ($request->anticipos[$c]["abono"]);
                            $v_cuota = $request->anticipos[$c]["abono"];
                            //aqui guarda un string concatenado por clave de acceso, id_ctascobrar, valor_pagado y la id_factura de cuentaporcobrar
                            if(isset($request->anticipos[$c]["id_nota_credito_compra"])){
                                if($request->anticipos[$c]["id_nota_credito_compra"]!==null){
                                    $ntc_compra=DB::select("SELECT id_factura_compra from nota_credito_compra where id_nota_credito_compra={$request->anticipos[$c]["id_nota_credito_compra"]}");
                                    $referencia .= $request->anticipos[$c]["posicion"]. ";pp:" . $request->anticipos[$c]["id_ctaspagar"] . ";" . number_format($v_cuota, 2, ".", "") . ";fc:".$ntc_compra[0]->id_factura_compra.";";
                                }else{
                                    $referencia .= $request->anticipos[$c]["posicion"]. ";pp:" . $request->anticipos[$c]["id_ctaspagar"] . ";" . number_format($v_cuota, 2, ".", "") . ";".$conteo.";";
                                }
                                
                            }else{
                                $referencia .= $request->anticipos[$c]["posicion"]. ";pp:" . $request->anticipos[$c]["id_ctaspagar"] . ";" . number_format($v_cuota, 2, ".", "") . ";".$conteo.";";
                            }
                            
                            
                        } else {
                            $pago = Cuentaporpagar::findOrFail($request->anticipos[$c]["id_ctaspagar"]);
                            $pago->pagos_por = $request->pagos_por;
                            $pago->id_forma_pagos = $request->forma_pago;
                            $pago->id_banco = $request->banco;
                            $pago->numero_tarjeta = $request->numero_tarjeta;
                            $pago->descuento = $pago->descuento + $request->descuento_pago;
                            $pago->valor_pagado = $pago->valor_pagado + $valor_pagar;
                            $pago->abono = $pago->abono - $valor_pagar;
                            //$pago->fecha_registro = $request->fecha_registro;
                            $pago->umodifica = $request->id_user;
                            $pago->save();
                            //aqui guarda un string concatenado por clave de acceso, id_ctascobrar, valor_pagado y la id_factura de cuentaporcobrar
                            if(isset($request->anticipos[$c]["id_nota_credito_compra"])){
                                if($request->anticipos[$c]["id_nota_credito_compra"]!==null){
                                    $ntc_compra=DB::select("SELECT id_factura_compra from nota_credito_compra where id_nota_credito_compra={$request->anticipos[$c]["id_nota_credito_compra"]}");
                                    $referencia .= $request->anticipos[$c]["posicion"]. ";pp:" . $request->anticipos[$c]["id_ctaspagar"] . ";" . number_format($valor_pagar, 2, ".", "") . ";fc:".$ntc_compra[0]->id_factura_compra.";";
                                }else{
                                    $referencia .= $request->anticipos[$c]["posicion"]. ";pp:" . $request->anticipos[$c]["id_ctaspagar"] . ";" . number_format($valor_pagar, 2, ".", "") . ";".$conteo.";";
                                }
                            }else{
                                $referencia .= $request->anticipos[$c]["posicion"]. ";pp:" . $request->anticipos[$c]["id_ctaspagar"] . ";" . number_format($valor_pagar, 2, ".", "") . ";".$conteo.";";
                            }
                            

                            //nuevamente edita los pagos de cuentas por cobrar y agrega la referencia de cuentaporcobrar separado por ;
                            $ref = substr($referencia, 0, -1);
                            //$ref =$referencia;
                            $cxcp = Ctas_pagar_pagos::findOrFail($idcxcp);
                            $cxcp->referencia = $ref;
                            //$cxcp->updated_by = session()->get('usuariosesion')['id'];
                            $cxcp->save();

                            return $idcxcp;
                        }
                    }
                }
            }
            //nuevamente edita los pagos de cuentas por pagar y agrega la referencia de cuentaporpagar separado por ;
            $ref = substr($referencia,0,-1);
            $cxcp = Ctas_pagar_pagos::findOrFail($idcxcp);
            $cxcp->referencia = $ref;
            $cxcp->save();
            return;
        }else{
            $valor_pagar = $request->valor_real + $request->descuento_pago;

            //cuentas pagar pagos genera la cabezera del registro
            $cxcp = new Ctas_pagar_pagos();
            $cxcp->pagos_por = $request->pagos_por;
            $cxcp->nro_tarjeta = $request->numero_tarjeta;
            $cxcp->referencia = "Pagos";
            $cxcp->valor_seleccionado = $request->valor_select;
            $cxcp->descuento_porcentaje = $request->descuento_porcentaje;
            $cxcp->descuento_pago = $request->descuento_pago;
            $cxcp->valor_real_pago = $request->valor_real;
            $cxcp->id_forma_pagos = $request->forma_pago;
            $cxcp->id_banco = $request->banco;
            $cxcp->id_proveedor = $request->id_cliente;
            $cxcp->fecha_pago = $hoy;
            $cxcp->fecha_registro = $fecha_registro;
            $cxcp->posicion = $conteo;
            $cxcp->ucrea = $request->id_user;
            $cxcp->save();

            //recupera el id de la cuenta
            $idcxcp = $cxcp->id_ctas_pagar_pagos;
            $referencia = null;
            for($c=0; $c<count($request->tabla); $c++){
                if(isset($request->tabla[$c]["agregar"])){
                    if($request->tabla[$c]["agregar"]){
                        //cuando se va a hacer varios pagos o pagos parciales entrara a este if y restara el valor_pagar con el valor
                        //caso contrario cuando el pago es menoro igual al saldo entra a else y genera el guardado y guarda directamente en el su Ctas_pagar_pagos
                        if($valor_pagar > ($request->tabla[$c]["valor_cuota"] - $request->tabla[$c]["valor_pagado"])){
                            //cuentas por pagar recupera y edita los valores
                            $pago = Cuentaporpagar::findOrFail($request->tabla[$c]["id_ctaspagar"]);
                            $pago->pagos_por = $request->pagos_por;
                            $pago->id_forma_pagos = $request->forma_pago;
                            $pago->id_banco = $request->banco;
                            $pago->numero_tarjeta = $request->numero_tarjeta;
                            $pago->descuento = $pago->descuento +$request->descuento_pago;
                            $pago->valor_pagado = $pago->valor_pagado + $request->tabla[$c]["valor_cuota"] - $request->tabla[$c]["valor_pagado"];
                            $pago->fecha_registro = $fecha_registro;
                            $pago->umodifica = $request->id_user;
                            $pago->save();

                            $valor_pagar = $valor_pagar - ($request->tabla[$c]["valor_cuota"] - $request->tabla[$c]["valor_pagado"]);
                            $v_cuota=$request->tabla[$c]["valor_cuota"] - $request->tabla[$c]["valor_pagado"];
                            //aqui guarda un string concatenado por clave de acceso, id_ctaspagar, valor_pagado y la id_factura de cuentaporpagar
                            if(isset($request->tabla[$c]["id_factura_compra"])){
                                $referencia .= substr($request->tabla[$c]["clave_acceso"],0,3)."-".substr($request->tabla[$c]["clave_acceso"],3,3)."-".substr($request->tabla[$c]["clave_acceso"],6,9) . ";" . $request->tabla[$c]["id_ctaspagar"] . ";" . number_format($v_cuota,2,".","") . ";" . $request->tabla[$c]["id_factura_compra"]. ";";
                            }else{
                                if(isset($request->tabla[$c]["id_liquidacion_compra"])){
                                    $referencia .= substr($request->tabla[$c]["clave_acceso"],0,3)."-".substr($request->tabla[$c]["clave_acceso"],3,3)."-".substr($request->tabla[$c]["clave_acceso"],6,9) . ";" . $request->tabla[$c]["id_ctaspagar"] . ";" . number_format($v_cuota,2,".","") . ";lc:" . $request->tabla[$c]["id_liquidacion_compra"]. ";";
                                }else{
                                    $referencia .= $request->tabla[$c]["clave_acceso2"] . ";" . $request->tabla[$c]["id_ctaspagar"] . ";" . number_format($v_cuota,2,".","") . ";" . $request->tabla[$c]["id_factura2"]. ";";
                                }
                                
                            }
                        }else{
                            $pago = Cuentaporpagar::findOrFail($request->tabla[$c]["id_ctaspagar"]);
                            $pago->pagos_por = $request->pagos_por;
                            $pago->id_forma_pagos = $request->forma_pago;
                            $pago->id_banco = $request->banco;
                            $pago->numero_tarjeta = $request->numero_tarjeta;
                            $pago->descuento = $pago->descuento + $request->descuento_pago;
                            $pago->valor_pagado = $pago->valor_pagado + $valor_pagar;
                            $pago->fecha_registro = $fecha_registro;
                            //$pago->cuenta_contable = $request->
                            $pago->umodifica = $request->id_user;
                            $pago->save();
                            //aqui guarda un string concatenado por clave de acceso, id_ctaspagar, valor_pagado y la id_factura de cuentaporpagar
                            if(isset($request->tabla[$c]["id_factura_compra"])){
                                $referencia .= substr($request->tabla[$c]["clave_acceso"],0,3)."-".substr($request->tabla[$c]["clave_acceso"],3,3)."-".substr($request->tabla[$c]["clave_acceso"],6,9) . ";" . $request->tabla[$c]["id_ctaspagar"] . ";" . number_format($valor_pagar,2,".","") . ";" . $request->tabla[$c]["id_factura_compra"]. ";";
                            }else{
                                if(isset($request->tabla[$c]["id_liquidacion_compra"])){
                                    $referencia .= substr($request->tabla[$c]["clave_acceso"],0,3)."-".substr($request->tabla[$c]["clave_acceso"],3,3)."-".substr($request->tabla[$c]["clave_acceso"],6,9) . ";" . $request->tabla[$c]["id_ctaspagar"] . ";" . number_format($valor_pagar,2,".","") . ";lc:" . $request->tabla[$c]["id_liquidacion_compra"]. ";";
                                }else{
                                    $referencia .= $request->tabla[$c]["clave_acceso2"] . ";" . $request->tabla[$c]["id_ctaspagar"] . ";" . number_format($valor_pagar,2,".","") . ";" . $request->tabla[$c]["id_factura2"]. ";";
                                }
                                
                            }
                            //nuevamente edita los pagos de cuentas por pagar y agrega la referencia de cuentaporpagar separado por ;
                            $ref = substr($referencia,0,-1);
                            $cxcp = Ctas_pagar_pagos::findOrFail($idcxcp);
                            $cxcp->referencia = $ref;
                            $cxcp->save();
                            return;
                        }
                    }
                }
            }

            //nuevamente edita los pagos de cuentas por pagar y agrega la referencia de cuentaporpagar separado por ;
            $ref = substr($referencia,0,-1);
            $cxcp = Ctas_pagar_pagos::findOrFail($idcxcp);
            $cxcp->referencia = $ref;
            $cxcp->save();
            return;
        }
        
    }
    public function store(Request $request){
        //guarda los registros de cuentas por pagar
        $pago = new Cuentaporpagar();
        $pago->nro_comprobante = $request->nro_comprobante;
        $pago->nro_cuota = $request->nro_cuota;
        $pago->forma_pago = $request->forma_pago;
        $pago->banco = $request->banco;
        $pago->nro_tarjeta = $request->nro_tarjeta;
        $pago->cta_contable = $request->cta_contable;
        $pago->monto = $request->monto;
        $pago->abono = $request->abono;
        $pago->saldo = $request->saldo;
        $pago->descuento = $request->descuento;
        $pago->estado = 1;
        $pago->freguistro = $request->freguistro;
        $pago->fvencimiento = $request->fvencimiento;
        $pago->id_cliente = $request->id_cliente;
        $pago->save();
    }
    public function update(Request $request){
        //actualiza los registros de cuentas por pagar
        $pago = Cuentaporpagar::find($request->id);
        $pago->nro_comprobante = $request->nro_comprobante;
        $pago->nro_cuota = $request->nro_cuota;
        $pago->forma_pago = $request->forma_pago;
        $pago->banco = $request->banco;
        $pago->nro_tarjeta = $request->nro_tarjeta;
        $pago->cta_contable = $request->cta_contable;
        $pago->monto = $request->monto;
        $pago->abono = $request->abono;
        $pago->saldo = $request->saldo;
        $pago->descuento = $request->descuento;
        $pago->estado = $request->estado;
        $pago->freguistro = $request->freguistro;
        $pago->fvencimiento = $request->fvencimiento;
        $pago->id_cliente = $request->id_cliente;
        $pago->save();
    }
    public function abrir(Request $request){
        //recupera el registro de cuentas por pagar mediante su id para visualizar
        $id = $request->id;
        $recupera = DB::select('SELECT * FROM `ctas_pagar` WHERE id_ctaspagar=' . $id);
        return $recupera;
    }
    public function eliminar($id){
        //elimina la cuenta
        Cuentaporpagar::destroy($id);
        /*$id = $request->id;
       $eliminar = Producto::find($request->id);
        $eliminar->delete();
        */
    }
    public function getProveedor() {
        //recupera los proveedores de la primera empresa
        $recupera = DB::select('SELECT * FROM `proveedor` Where `id_empresa`=1');
        return $recupera;
    }
    public function reporteCuentasPagar(Request $request){
        $bs = "";

        if ($request->tipo_busqueda == 1) {
            $dt = "";
            if ($request->dateinicio) {
                $dt = "cc.fecha_pago >=$request->dateinicio";
            }
            if ($request->dateinicio && $request->datefin) {
                $dt = "cc.fecha_pago BETWEEN " . $request->dateinicio . " AND " . $request->datefin;
            }
            if ($request->datefin) {
                $dt = "cc.fecha_pago <=$request->datefin";
            }
            $bs .= "WHERE " . $dt;
        } else if ($request->tipo_busqueda == 2) {
            $bs .= "WHERE c.id_cliente = " . $request->cliente_busqueda;
        } else if ($request->tipo_busqueda == 3) {
            $bs .= "WHERE f.id_user = " . $request->vendedor_busqueda;
        }
        $reporte = DB::select("SELECT e.logo, e.nombre_empresa, c.codigo, c.nombre, SUM(cc.valor_cuota) as saldo_anterior, sum(cc.valor_pagado) as abono,
        (sum(cc.valor_cuota) - sum(cc.valor_pagado)) as saldo_actual
        from cliente c inner join
        ctas_cobrar cc on cc.id_cliente = c.id_cliente inner join
        factura f on f.id_factura=cc.id_factura inner join
        empresa e on f.id_empresa=e.id_empresa " . $bs . " GROUP BY c.codigo, c.nombre, e.logo, e.nombre_empresa ");
        $Reportes = new generarReportes();
        $Reportes->cuentasPorPagar($reporte, $request->dateinicio, $request->datefin);
    }
    public function getFormaPago($id){
        $res = DB::select("SELECT fp.* FROM forma_pagos fp INNER JOIN forma_pagos_sri fpi ON fp.id_forma_pagos_sri=fpi.id_forma_pagos_sri where fp.id_empresa=".$id);
        return $res;

    }
    public function getUserAdmin($id){
        $res = DB::select("SELECT user.*,empresa.nombre_empresa FROM user INNER JOIN empresa ON empresa.id_empresa=user.id_empresa  where user.id_empresa=".$id);
        return $res;

    }
    public function getUser($id){
        $res = DB::select("SELECT * FROM user  where id_empresa=".$id);
        return $res;

    }
    public function reporte(Request $request){
        $queries = [];
        $inners = [];
        $fields = [];
        $provs = [];
        $pagos = [];
        $ctas_pagos = [];
        $anticipo = [];
        $anticipo_pagos_fact = [];
        $anticipo_pagos_lc = [];
        $cierre_anticipo=[];
        $initial = null;
        $final = null;
        $fecha_inicio=DB::select("SELECT min(fecha_pago) as fecha_inicio from ctas_pagar,proveedor where proveedor.id_empresa=".$request->company);
        $fecha_crea_cta=DB::select("SELECT min(ctas_pagar.fcrea) as fecha_inicio from ctas_pagar,proveedor where ctas_pagar.id_proveedor=proveedor.id_proveedor and proveedor.id_empresa=".$request->company);
        $fecha_inicio_pagos=DB::select("SELECT min(ctas_pagar.fmodifica) as fecha_inicio from ctas_pagar,proveedor where ctas_pagar.id_proveedor=proveedor.id_proveedor and proveedor.id_empresa=".$request->company);
        $fecha_inicio_factura=DB::select("SELECT min(fech_emision) as fecha_inicio from factura_compra where factura_compra.id_empresa=".$request->company);
        $fecha_inicio_liquidacion=DB::select("SELECT min(fecha_emision) as fecha_inicio from liquidacion_compra where liquidacion_compra.id_empresa=".$request->company);
        $fecha_inicio_ctas_total=DB::select("SELECT min(ctas_pagar.fecha_factura) as fecha_inicio from ctas_pagar,proveedor where ctas_pagar.id_proveedor=proveedor.id_proveedor and proveedor.id_empresa=".$request->company);
        $fecha_inicio_ctas_cobrar_fecha_pagos=DB::select("SELECT min(ctas_cobrar.fecha_pago) as fecha_inicio from ctas_pagar_pagos as ctas_cobrar,proveedor where ctas_cobrar.id_proveedor=proveedor.id_proveedor and proveedor.id_empresa=".$request->company);
        $fecha_inicio_ctas_cobrar_fecha_registro=DB::select("SELECT min(ctas_cobrar.fecha_registro) as fecha_inicio from ctas_pagar_pagos as ctas_cobrar,proveedor where ctas_cobrar.id_proveedor=proveedor.id_proveedor and proveedor.id_empresa=".$request->company);
        $fecha_inicio_ctas_cobrar_fecha_registro_ctas=DB::select("SELECT min(ctas_cobrar.fecha_registro) as fecha_inicio from  ctas_pagar as  ctas_cobrar,proveedor where ctas_cobrar.id_proveedor=proveedor.id_proveedor and proveedor.id_empresa=".$request->company);
        $fecha_inicio_anticipo = DB::select("SELECT min(if(fecha_registro is null,fecha_pago,fecha_registro)) as fecha_inicio from ctas_pagar INNER JOIN proveedor ON proveedor.id_proveedor=ctas_pagar.id_proveedor where ctas_pagar.tipo=3 and proveedor.id_empresa=" . $request->company);
        $info_reporte=json_decode($request->reporte, true);
        //dd($request);
        if($info_reporte["id"] != 0){
            if($info_reporte["id"] == 1){
                if ($request->dates) {
                    $info_date = json_decode($request->dates, true);
                    if ($request->currentDate !== "true") {
                        $initial = str_replace("-010-","-10-",$info_date["range"]["initial"]);
                        $final = str_replace("-010-","-10-",$info_date["range"]["final"]);
                        if ($info_date["option"] == 1) {
                            array_push($queries, "date(cta.fecha_pago) between date('{$info_date["range"]["initial"]}') and date('{$final}')\n");
                        }
                        if ($info_date["option"] == 2) {
                            array_push($queries, "date(cta.fecha_pago) between date('{$info_date["range"]["initial"]}') and date('{$final}')\n");
                        }
                        if ($info_date["option"] == 3) {
                            array_push($queries, "date(cta.fecha_factura) between date('{$fecha_inicio_ctas_total[0]->fecha_inicio}') and date('{$final}')\n");
                            array_push($pagos, "if(ctap.fecha_registro is null,(date(ctap.fecha_pago) between date('{$fecha_inicio_ctas_cobrar_fecha_pagos[0]->fecha_inicio}') and date('{$final}')),
                                                      (date(ctap.fecha_registro) between date('{$fecha_inicio_ctas_cobrar_fecha_registro[0]->fecha_inicio}') and date('{$final}')))\n");
                        }
                    } else {
                        $hoy = date("Y-m-d");
                        $initial = str_replace("-010-","-10-",$info_date["value"]);
                        $final=$hoy;
                        if ($info_date["option"] == 1) {
                            array_push($queries, "date(cta.fecha_pago) = date('{$hoy}')\n");
                        }
                        if ($info_date["option"] == 2) {
                            array_push($queries, "date(cta.fecha_pago) = date('{$hoy}')\n");
                        }
                        if ($info_date["option"] == 3) {
                            //array_push($queries, "date(cta.fecha_pago) = date('{$info_date["value"]}')\n");
                            array_push($queries, "date(cta.fecha_factura) between date('{$fecha_inicio_ctas_total[0]->fecha_inicio}') and date('{$hoy}')\n");
                            array_push($pagos, "if(ctap.fecha_registro is null,(date(ctap.fecha_pago) between date('{$fecha_inicio_ctas_cobrar_fecha_pagos[0]->fecha_inicio}') and date('{$final}')),
                                                      (date(ctap.fecha_registro) between date('{$fecha_inicio_ctas_cobrar_fecha_registro[0]->fecha_inicio}') and date('{$final}')))\n");
                        }
                    }
                }
                //dd($queries);
                /*if ($request->establishment) {
                    $info_establishment = json_decode($request->establishment, true);
                    if ($info_establishment["id"] != 0) {
                        array_push($queries, "f.id_establecimiento = {$info_establishment["id"]}\n");
                    }

                }
                if ($request->pointOfEmission) {
                    $info_point_emission = json_decode($request->pointOfEmission, true);
                    if ($info_point_emission["id"] != 0) {
                        array_push($queries, "f.id_punto_emision = {$info_point_emission["id"]}\n");
                    }

                }*/
                if ($request->project) {
                    $info_project = json_decode($request->project, true);
                    if ($info_project["id"] != 0) {
                        array_push($queries, "f.id_proyecto = {$info_project["id"]}\n");
                    }

                }
                if ($request->provider) {
                    $info_provider = json_decode($request->provider, true);
                    if ($info_provider["id"] != 0) {
                        array_push($queries, "cta.id_proveedor = {$info_provider["id"]}\n");
                        array_push($pagos, "ctap.id_proveedor = {$info_provider["id"]}\n");
                    }

                }
                if ($request->user) {
                    $info_user = json_decode($request->user, true);
                    if ($info_user["id"] != 0) {
                        array_push($queries, "cta.ucrea = {$info_user["id"]}\n");
                    }

                }
                if ($request->wayToPay) {
                    $info_payment = json_decode($request->wayToPay, true);
                    if ($info_payment["id"] != 0) {
                        array_push($queries, "cta.id_forma_pagos = {$info_payment["id"]}\n");
                        array_push($pagos, "ctap.id_forma_pagos = {$info_payment["id"]}\n");
                    }

                }
                    $info_invoice = json_decode($request->invoice);
                    if ($info_invoice->typeSearch == 1) {
                        $typeSearch = ">=";
                    }
                    if ($info_invoice->typeSearch == 0) {
                        $typeSearch = "=";
                    }
                    if ($info_invoice->typeSearch == -1) {
                        $typeSearch = "<=";
                    }
                    if (is_numeric($info_invoice->totalCount) && $info_invoice->typeSearch != 2) {
                        $info_invoice->totalCount = intval($info_invoice->totalCount);
                        array_push($queries, "cta.valor_cuota {$typeSearch} {$info_invoice->totalCount}\n");
                    }
                    $queries = implode(" and ", $queries);
                    $inners = implode("", $inners);
                    $fields = implode("", $fields);
                    $pagos = implode(" and ", $pagos);
                    $query = "SELECT sum(valor_cuota) as valor_cuota,proveedor.id_proveedor,proveedor.nombre_proveedor,proveedor.identif_proveedor,sum(if(valor_cuota>=valor_pagado,valor_pagado,valor_cuota)) as valor_pago,sum(valor_cuota)-sum(if(valor_cuota>=valor_pagado,valor_pagado,valor_cuota)) as descuento,
                    empresa.nombre_empresa,empresa.id_empresa,empresa.logo
                    FROM ctas_pagar as cta,proveedor,empresa
                    where {$queries} and cta.id_proveedor=proveedor.id_proveedor and cta.tipo=1 and proveedor.id_empresa=empresa.id_empresa and proveedor.id_empresa={$request->company}
                    GROUP BY cta.id_proveedor order by proveedor.nombre_proveedor";/*"select cta.*,emp.id_empresa,emp.nombre_empresa,emp.logo,pr.nombre_proveedor,pr.identif_proveedor,fact.observacion as clave_acceso,
                    (SELECT descripcion from forma_pagos where forma_pagos.id_forma_pagos=cta.id_forma_pagos) as descripcion,
                    if(cta.valor_pagado>=cta.valor_cuota,cta.valor_cuota,cta.valor_pagado) as valor_pago
                    from ctas_pagar as cta,proveedor as pr,empresa as emp,factura_compra as fact
                    where {$queries} and
                    cta.id_proveedor = pr.id_proveedor and
                    pr.id_empresa=emp.id_empresa and
                    pr.id_empresa={$request->company} and
                    cta.id_factura_compra=fact.id_factcompra
                    ORDER BY cta.fecha_pago asc
                    ";*/
                    //  dd($query);
                    $query2="SELECT sum(valor_cuota) as suma
                    from ctas_pagar as cta,proveedor
                    where {$queries} and now()>cta.fecha_pago and cta.tipo=1 and proveedor.id_proveedor=cta.id_proveedor and proveedor.id_empresa={$request->company}";
                    //dd($query2);
                    $reporte = DB::select($query);
                    $reporte2=DB::select($query2);
                    $reporte3=DB::select("SELECT email_empresa,password,servidor_correo,puerto_correo,seguridad_correo,id_empresa
                    from empresa
                    where id_empresa=". $request->company);
                    $valores_pagado=
                    // "SELECT sum(if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota)) as valor_pagado,referencias,fact.id_factcompra,cta.id_proveedor
                    // from ctas_pagar as cta
                    //                         INNER JOIN proveedor as pro
                    //                         on pro.id_proveedor=cta.id_proveedor
                    //                         LEFT JOIN factura_compra as fact
                    //                         on fact.id_factcompra=cta.id_factura_compra
                    // where {$pagos} and pro.id_empresa={$request->company}  and (referencias is not null or fact.id_factcompra is not null)
                    //                         GROUP BY fact.id_factcompra,cta.referencias,cta.id_proveedor
                    //                         ORDER BY max(cta.fecha_factura) asc"
                    "SELECT sum(if(round(SUBSTRING(ctap.referencia,POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2,locate(';',ctap.referencia,POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2)-(POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2)),2)>ctap.valor_real_pago,ctap.valor_real_pago,round(SUBSTRING(ctap.referencia,POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2,locate(';',ctap.referencia,POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2)-(POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2)),2))) as  valor_pagado,cta.id_factura_compra as id_factcompra,cta.referencias,cta.id_proveedor
                    from ctas_pagar as cta
                    INNER JOIN ctas_pagar_pagos as ctap
                    on ctap.id_proveedor=cta.id_proveedor
                    INNER JOIN forma_pagos
                    on forma_pagos.id_forma_pagos=ctap.id_forma_pagos
                    LEFT JOIN factura_compra as fact
                    on fact.id_factcompra=cta.id_factura_compra
                                                where {$pagos} and forma_pagos.id_empresa={$request->company} and ctap.referencia like concat('%;',cta.id_ctaspagar,';%') and cta.tipo=1 and ctap.pago_anticipo is null
                                                GROUP BY cta.id_proveedor
                                                ORDER BY cta.id_proveedor asc";
                    $reporte_valores_pagado=DB::select($valores_pagado);
                    //dd($request->destinatario);
                    if (!$reporte) {
                        return response('no-data-report', 200)->header('Content-Type', 'application/json');
                    } else {
                        if($request->email && $request->destinatario){
                            $Reportes = new generarReportes();
                            $ruta = constant("DATA_EMPRESA") . $request->company . '/compras/cuentas_pagar';
                            if(!file_exists($ruta)){
                                mkdir($ruta, 0755,true);
                            }
                            $strPDF = $Reportes->cuenta_pagar_reporte_proveedor($reporte,$reporte_valores_pagado, $fecha_inicio[0]->fecha_inicio, $final,$reporte2,$ruta);
                            $email=new sendEmail();
                            $email->enviarCtasPagar($reporte3[0],$request->email,$request->destinatario,"Proveedor");
                            $cta=$ruta.'/cuenta_por_pagar_Proveedor.pdf';
                            /*if(file_exists($cta)){
                                unlink($cta);
                            }*/
                            return response($strPDF, 200)->header('Content-Type', 'application/pdf');

                        }else{
                            $Reportes = new generarReportes();
                            $strPDF = $Reportes->cuenta_pagar_reporte_proveedor($reporte,$reporte_valores_pagado, $fecha_inicio[0]->fecha_inicio, $final,$reporte2);
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
                            array_push($queries, "date(cta.fecha_pago) between date('{$info_date["range"]["initial"]}') and date('{$info_date["range"]["final"]}')\n");
                        }
                        if ($info_date["option"] == 2) {
                            array_push($queries, "date(cta.fecha_pago) between date('{$info_date["range"]["initial"]}') and date('{$info_date["range"]["final"]}')\n");
                        }
                        if ($info_date["option"] == 3) {
                            array_push($queries, "((date(fact.fech_emision) between date('{$fecha_inicio_factura[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}'))
                                                    or (date(lc.fecha_emision) between date('{$fecha_inicio_liquidacion[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')))\n");
                            array_push($fields, "date(cta.fcrea) between date('{$fecha_crea_cta[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')\n");
                            array_push($inners, "date(cta.fecha_factura) between date('{$fecha_inicio_ctas_total[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')\n");
                            //array_push($pagos, "date(cta.fecha_registro) between date('{$fecha_inicio_ctas_cobrar_fecha_registro_ctas[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')\n");
                            array_push($pagos, "if(ctap.fecha_registro is null,(date(ctap.fecha_pago) between date('{$fecha_inicio_ctas_cobrar_fecha_pagos[0]->fecha_inicio}') and date('{$final}')),
                                                      (date(ctap.fecha_registro) between date('{$fecha_inicio_ctas_cobrar_fecha_registro[0]->fecha_inicio}') and date('{$final}')))\n");
                        }
                    } else {
                        $hoy=date("Y-m-d");
                        $initial = str_replace("-010-","-10-",$info_date["value"]);
                        $final = $hoy;
                        if ($info_date["option"] == 1) {
                            array_push($queries, "date(cta.fecha_pago) = date('{$info_date["value"]}')\n");
                        }
                        if ($info_date["option"] == 2) {
                            array_push($queries, "date(cta.fecha_pago) = date('{$info_date["value"]}')\n");
                        }
                        if ($info_date["option"] == 3) {
                            //array_push($queries, "date(cta.fecha_pago) = date('{$info_date["value"]}')\n");
                            array_push($queries, "((date(fact.fech_emision) between date('{$fecha_inicio_factura[0]->fecha_inicio}') and date('{$hoy}'))
                                                    or (date(lc.fecha_emision) between date('{$fecha_inicio_liquidacion[0]->fecha_inicio}') and date('{$hoy}')))\n");
                            array_push($fields, "date(cta.fcrea) between date('{$fecha_crea_cta[0]->fecha_inicio}') and date('{$hoy}')\n");
                            array_push($inners, "date(cta.fecha_factura) between date('{$fecha_inicio_ctas_total[0]->fecha_inicio}') and date('{$hoy}')\n");
                            //array_push($pagos, "date(cta.fecha_registro) between date('{$fecha_inicio_ctas_cobrar_fecha_registro_ctas[0]->fecha_inicio}') and date('{$hoy}')\n");
                            array_push($pagos, "if(ctap.fecha_registro is null,(date(ctap.fecha_pago) between date('{$fecha_inicio_ctas_cobrar_fecha_pagos[0]->fecha_inicio}') and date('{$hoy}')),
                                                      (date(ctap.fecha_registro) between date('{$fecha_inicio_ctas_cobrar_fecha_registro[0]->fecha_inicio}') and date('{$hoy}')))\n");
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
                        array_push($queries, "cta.id_proveedor = {$info_provider["id"]}\n");
                        array_push($inners, "cta.id_proveedor = {$info_provider["id"]}\n");
                        array_push($fields, "cta.id_proveedor = {$info_provider["id"]}\n");
                        array_push($provs, "cta.id_proveedor = {$info_provider["id"]}\n");
                        array_push($pagos, "ctap.id_proveedor = {$info_provider["id"]}\n");
                    }

                }
                if ($request->user) {
                    $info_user = json_decode($request->user, true);
                    if ($info_user["id"] != 0) {
                        array_push($queries, "cta.ucrea = {$info_user["id"]}\n");
                        array_push($fields, "cta.ucrea = {$info_user["id"]}\n");
                        array_push($inners, "cta.ucrea = {$info_user["id"]}\n");
                        array_push($provs, "cta.ucrea = {$info_user["id"]}\n");
                        //array_push($pagos, "cta.ucrea = {$info_user["id"]}\n");
                    }

                }
                if ($request->wayToPay) {
                    $info_payment = json_decode($request->wayToPay, true);
                    if ($info_payment["id"] != 0) {
                        array_push($queries, "cta.id_forma_pagos = {$info_payment["id"]}\n");
                        array_push($fields, "cta.id_forma_pagos = {$info_payment["id"]}\n");
                        array_push($inners, "cta.id_forma_pagos = {$info_payment["id"]}\n");
                        array_push($provs, "cta.id_forma_pagos = {$info_payment["id"]}\n");
                        array_push($pagos, "ctap.id_forma_pagos = {$info_payment["id"]}\n");
                    }

                }
                    $info_invoice = json_decode($request->invoice);
                    if ($info_invoice->typeSearch == 1) {
                        $typeSearch = ">=";
                    }
                    if ($info_invoice->typeSearch == 0) {
                        $typeSearch = "=";
                    }
                    if ($info_invoice->typeSearch == -1) {
                        $typeSearch = "<=";
                    }
                    if (is_numeric($info_invoice->totalCount) && $info_invoice->typeSearch != 2) {
                        $info_invoice->totalCount = intval($info_invoice->totalCount);
                        array_push($queries, "cta.valor_cuota {$typeSearch} {$request->totalCount}\n");
                        array_push($fields, "cta.valor_cuota {$typeSearch} {$request->totalCount}\n");
                        array_push($inners, "cta.valor_cuota {$typeSearch} {$request->totalCount}\n");
                        array_push($provs, "cta.valor_cuota {$typeSearch} {$request->totalCount}\n");
                        //array_push($pagos, "cta.valor_cuota {$typeSearch} {$request->totalCount}\n");
                    }
                    $queries = implode(" and ", $queries);
                    $inners = implode(" and ", $inners);
                    $fields = implode(" and ", $fields);
                    $provs = implode(" and ", $provs);
                    $pagos = implode(" and ", $pagos);
                    $query ="SELECT sum(cta.valor_cuota) as valor_cuota,sum(if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota)) as valor_pagado,sum(cta.valor_cuota)-sum(if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota)) as saldo,cta.id_factura_compra,cta.id_liquidacion_compra,fact.id_proveedor,fact.fech_emision,
                    max(cta.fecha_pago) as fecha_pago,if(fact.descripcion is null,lc.descripcion,fact.descripcion) as observacion,if(max(cta.fecha_pago)<now(),'si','no') as vencido,emp.logo,emp.id_empresa,emp.nombre_empresa
                    from ctas_pagar as cta 
                    INNER JOIN proveedor as pro
                    on pro.id_proveedor=cta.id_proveedor
                    INNER JOIN empresa as emp
					on emp.id_empresa=pro.id_empresa
                    LEFT JOIN factura_compra as fact
                    on fact.id_factcompra=cta.id_factura_compra
					LEFT JOIN liquidacion_compra as lc
                    on lc.id_liquidacion_compra=cta.id_liquidacion_compra
                    where
                    {$queries} and
                    cta.id_factura_compra=fact.id_factcompra
                    and cta.tipo=1
                    and pro.id_empresa={$request->company}
                    GROUP BY id_factura_compra,cta.id_liquidacion_compra"; /*"select cta.*,fact.observacion,fact.fech_emision,pro.nombre_proveedor,pro.identif_proveedor,emp.id_empresa,emp.nombre_empresa,emp.logo
                    from ctas_pagar as cta,factura_compra as fact,proveedor as pro,empresa as emp
                    where
                    {$queries} and cta.valor_cuota>cta.valor_pagado and
                    cta.id_factura_compra=fact.id_factcompra and
                    pro.id_proveedor=cta.id_proveedor and
                    pro.id_empresa=emp.id_empresa";*/

                    $query2="select sum(valor_cuota) as suma,sum(valor_pagado) as abono,sum(valor_cuota)-sum(valor_pagado) as saldo from  ctas_pagar as cta,proveedor where valor_cuota>valor_pagado and proveedor.id_proveedor=cta.id_proveedor and cta.tipo=1 and proveedor.id_empresa={$request->company}";
                    //dd($query);
                    $reporte = DB::select($query);
                    $reporte2=DB::select($query2);
                    if($provs){
                        $query3="select distinct pro.nombre_proveedor,pro.identif_proveedor,pro.id_proveedor,pro.cod_proveedor,pro.telefono_prov,pro.contacto
                        from ctas_pagar as cta,proveedor as pro
                        where {$provs} and cta.tipo=1  and cta.id_proveedor=pro.id_proveedor and pro.id_empresa={$request->company} ORDER BY pro.nombre_proveedor asc";
                    }else{
                        $query3="select distinct pro.nombre_proveedor,pro.identif_proveedor,pro.id_proveedor,pro.cod_proveedor,pro.telefono_prov,pro.contacto
                        from ctas_pagar as cta,proveedor as pro
                        where  cta.id_proveedor=pro.id_proveedor and cta.tipo=1 and pro.id_empresa={$request->company} ORDER BY pro.nombre_proveedor asc";
                    }

                    //dd($query3);
                    $reporte3=DB::select($query3);
                    $query_referencia="SELECT cta.valor_cuota,cta.referencias,if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota) as valor_pagado,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota) as saldo,cta.id_proveedor,cta.fcrea,cta.fecha_pago,if(cta.fecha_pago<now(),'si','no') as vencido,cta.id_empresa
                    from ctas_pagar as cta
                    where {$fields} and cta.referencias is not null and cta.id_empresa={$request->company} and cta.tipo=1";
                    //dd($query_referencia);
                    $reporte_referencia=DB::select($query_referencia);
                    $empresa=DB::select("SELECT * from empresa where id_empresa=".$request->company);
                    $reporte4=DB::select("SELECT email_empresa,password,servidor_correo,puerto_correo,seguridad_correo,id_empresa from empresa where  id_empresa=". $request->company);
                    $nuevo_reporte="SELECT sum(cta.valor_cuota) as valor_cuota,sum(if(cta.pago_favor is not null,cta.valor_pagado+cta.pago_favor,if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota))) as valor_pagado,sum(cta.valor_cuota)-sum(if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota)) as saldo,fact.id_factcompra as id_factura,lc.id_liquidacion_compra,max(cta.id_proveedor) as id_proveedor,max(cta.fecha_factura) as fecha_emision,max(cta.fecha_pago) as fecha_pago,if(fact.descripcion is null, lc.descripcion,fact.descripcion) as observacion,cta.referencias,if(now()>max(cta.fecha_pago),'si','no') as vencido
                    from ctas_pagar as cta
                    INNER JOIN proveedor as pro
                    on pro.id_proveedor=cta.id_proveedor
                    LEFT JOIN factura_compra as fact
                    on fact.id_factcompra=cta.id_factura_compra
                    LEFT JOIN liquidacion_compra as lc
                    on lc.id_liquidacion_compra=cta.id_liquidacion_compra
                    where {$inners}  and pro.id_empresa={$request->company}  and (cta.id_factura_compra is not null or cta.referencias is not null or cta.id_liquidacion_compra is not null) and cta.tipo=1
                    GROUP BY fact.id_factcompra,cta.referencias,cta.id_proveedor,cta.id_liquidacion_compra
                    ORDER BY max(cta.fecha_factura) asc";
                    //dd($nuevo_reporte);
                    $reporte_nuevo=DB::select($nuevo_reporte);
                    $valores_pagado=
                    // "SELECT sum(if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota)) as valor_pagado,referencias,fact.id_factcompra,cta.id_proveedor
                    // from ctas_pagar as cta
                    //                         INNER JOIN proveedor as pro
                    //                         on pro.id_proveedor=cta.id_proveedor
                    //                         LEFT JOIN factura_compra as fact
                    //                         on fact.id_factcompra=cta.id_factura_compra
                    // where {$pagos} and pro.id_empresa={$request->company}  and (referencias is not null or fact.id_factcompra is not null)
                    //                         GROUP BY fact.id_factcompra,cta.referencias,cta.id_proveedor
                    //                         ORDER BY max(cta.fecha_factura) asc"


                    // "SELECT sum(if(round(SUBSTRING(ctap.referencia,POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2,locate(';',ctap.referencia,POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2)-(POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2)),2)>ctap.valor_real_pago,ctap.valor_real_pago,round(SUBSTRING(ctap.referencia,POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2,locate(';',ctap.referencia,POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2)-(POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2)),2))) as  valor_pagado,cta.id_factura_compra as id_factcompra,cta.id_liquidacion_compra,cta.referencias,cta.id_proveedor
                    // from ctas_pagar as cta
                    // INNER JOIN ctas_pagar_pagos as ctap
                    // on ctap.id_proveedor=cta.id_proveedor
                    // INNER JOIN forma_pagos
                    // on forma_pagos.id_forma_pagos=ctap.id_forma_pagos
                    // LEFT JOIN factura_compra as fact
                    // on fact.id_factcompra=cta.id_factura_compra
                    // LEFT JOIN liquidacion_compra as lc
                    // on lc.id_liquidacion_compra=cta.id_liquidacion_compra
                    //                             where {$pagos} and forma_pagos.id_empresa={$request->company} and ctap.pago_anticipo is null and ctap.referencia like concat('%;',cta.id_ctaspagar,';%') and cta.tipo=1
                    //                             GROUP BY cta.id_factura_compra,cta.referencias,cta.id_proveedor,cta.id_liquidacion_compra
                    //                             ORDER BY max(cta.fecha_factura) asc";
                    "SELECT sum(if(round(SUBSTRING(ctap.referencia,POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2,locate(';',ctap.referencia,POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2)-(POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2)),2)>ctap.valor_real_pago,ctap.valor_real_pago,round(SUBSTRING(ctap.referencia,POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2,locate(';',ctap.referencia,POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2)-(POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2)),2))) as  valor_pagado,null as id_factcompra,cta.id_liquidacion_compra,cta.referencias,cta.id_proveedor
                                        from ctas_pagar as cta
                                        INNER JOIN ctas_pagar_pagos as ctap
                                        on ctap.id_proveedor=cta.id_proveedor
                                        INNER JOIN forma_pagos
                                        on forma_pagos.id_forma_pagos=ctap.id_forma_pagos
                                        LEFT JOIN factura_compra as fact
                                        on fact.id_factcompra=cta.id_factura_compra
                                        LEFT JOIN liquidacion_compra as lc
                                        on lc.id_liquidacion_compra=cta.id_liquidacion_compra
                                                                    where {$pagos}
                    and forma_pagos.id_empresa={$request->company} and ctap.pago_anticipo is null and ctap.referencia like concat('%;',cta.id_ctaspagar,';%') and cta.tipo=1 and cta.id_factura_compra is null
                    GROUP BY cta.referencias,cta.id_proveedor,cta.id_liquidacion_compra
                    UNION
                    select 	sum(valor_pagado)-sum(valor_cierre) as valor_pagado,id_factcompra,max(id_liquidacion_compra) as id_liquidacion_compra,max(referencias) as referencias,max(id_proveedor) as id_proveedor
                    from
                    (
                            select sum(0) as valor_pagado,fact.id_factcompra,null as id_liquidacion_compra,null as referencias,max(cta.id_proveedor) as id_proveedor,
                            sum(if(round(SUBSTRING(ctap.referencia,POSITION(concat(';pp:',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+5,locate(';',ctap.referencia,POSITION(concat(';pp:',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+5)-(POSITION(concat(';pp:',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+5)),2)>ctap.valor_real_pago,ctap.valor_real_pago,round(SUBSTRING(ctap.referencia,POSITION(concat(';pp:',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+5,locate(';',ctap.referencia,POSITION(concat(';pp:',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+5)-(POSITION(concat(';pp:',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+5)),2))) as valor_cierre
                                                                                                                    from ctas_pagar as cta
                                                                                                                        INNER JOIN ctas_pagar_pagos as ctap
                                                                                                                        on ctap.id_proveedor=cta.id_proveedor
                                                                                                                        INNER JOIN forma_pagos
                                                                                                                        on forma_pagos.id_forma_pagos=ctap.id_forma_pagos
                                                                                                                        INNER JOIN nota_credito_compra as ntcc
                                                                                                                        on ntcc.id_nota_credito_compra=cta.id_nota_credito_compra
                                                                                                                        INNER JOIN factura_compra as fact
                                                                                                                        on fact.id_factcompra=ntcc.id_factura_compra
                                                                                                                        where {$pagos}
                            and forma_pagos.id_empresa={$request->company} and ctap.pago_anticipo>0 and ctap.referencia like concat('%;pp:',cta.id_ctaspagar,';%') and cta.tipo=3 
                            GROUP BY fact.id_factcompra
                            UNION ALL
                            SELECT sum(if(round(SUBSTRING(ctap.referencia,POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2,locate(';',ctap.referencia,POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2)-(POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2)),2)>ctap.valor_real_pago,ctap.valor_real_pago,round(SUBSTRING(ctap.referencia,POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2,locate(';',ctap.referencia,POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2)-(POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2)),2))) as  valor_pagado,cta.id_factura_compra as id_factcompra,null as id_liquidacion_compra,null as referencias,max(cta.id_proveedor) as id_proveedor,sum(0) as valor_cierre
                                                from ctas_pagar as cta
                                                INNER JOIN ctas_pagar_pagos as ctap
                                                on ctap.id_proveedor=cta.id_proveedor
                                                INNER JOIN forma_pagos
                                                on forma_pagos.id_forma_pagos=ctap.id_forma_pagos
                                                LEFT JOIN factura_compra as fact
                                                on fact.id_factcompra=cta.id_factura_compra
                                                                            where {$pagos}
                            and forma_pagos.id_empresa={$request->company} and ctap.pago_anticipo is null and ctap.referencia like concat('%;',cta.id_ctaspagar,';%') and cta.tipo=1  and cta.referencias is null and cta.id_liquidacion_compra is null
                                                                            GROUP BY cta.id_factura_compra
                    ) t
                    GROUP BY id_factcompra
                    ORDER BY id_factcompra";
                    //dd($valores_pagado);
                    $reporte_valores_pagado=DB::select($valores_pagado);
                    if (!$reporte && !$reporte_referencia) {
                        return response('no-data-report', 200)->header('Content-Type', 'application/json');
                    } else {
                        if($request->email && $request->destinatario){
                            $Reportes = new generarReportes();
                            $ruta = constant("DATA_EMPRESA") . $request->company . '/compras/cuentas_pagar';
                            if(!file_exists($ruta)){
                                mkdir($ruta, 0755,true);
                            }
                            if(isset($request->vencido_reporte)){
                                $strPDF = $Reportes->cuenta_pagar_reporte_detalle_factura_vencido($reporte_nuevo,$reporte_valores_pagado,$reporte, $fecha_inicio[0]->fecha_inicio, $final,$reporte2,$reporte3,$reporte_referencia,$empresa[0],$ruta);
                                $email=new sendEmail();
                                $email->enviarCtasPagar($reporte4[0],$request->email,$request->destinatario,"Resumen_Factura");
                                $cta=$ruta.'/cuenta_por_pagar_Resumen_Factura.pdf';
                                /*if(file_exists($cta)){
                                    unlink($cta);
                                }*/
                                return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                            }else{
                                $strPDF = $Reportes->cuenta_pagar_reporte_detalle_factura($reporte_nuevo,$reporte_valores_pagado,$reporte, $fecha_inicio[0]->fecha_inicio, $final,$reporte2,$reporte3,$reporte_referencia,$empresa[0],$ruta);
                                $email=new sendEmail();
                                $email->enviarCtasPagar($reporte4[0],$request->email,$request->destinatario,"Resumen_Factura");
                                $cta=$ruta.'/cuenta_por_pagar_Resumen_Factura.pdf';
                                /*if(file_exists($cta)){
                                    unlink($cta);
                                }*/
                                return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                            }
                            

                        }else{
                            if(isset($request->vencido_reporte)){
                                $Reportes = new generarReportes();
                                $strPDF = $Reportes->cuenta_pagar_reporte_detalle_factura_vencido($reporte_nuevo,$reporte_valores_pagado,$reporte, $fecha_inicio[0]->fecha_inicio, $final,$reporte2,$reporte3,$reporte_referencia,$empresa[0]);
                                return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                            }else{
                                $Reportes = new generarReportes();
                                $strPDF = $Reportes->cuenta_pagar_reporte_detalle_factura($reporte_nuevo,$reporte_valores_pagado,$reporte, $fecha_inicio[0]->fecha_inicio, $final,$reporte2,$reporte3,$reporte_referencia,$empresa[0]);
                                return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                            }
                            
                        }
                    }
            }
            if($info_reporte["id"] == 2){
                if ($request->dates) {
                    $info_date = json_decode($request->dates, true);
                    if ($request->currentDate !== "true") {
                        $initial = $info_date["range"]["initial"];
                        $final = $info_date["range"]["final"];
                        if ($info_date["option"] == 1) {
                            array_push($queries, "date(fact.fech_emision) between date('{$fecha_inicio_factura[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')\n");
                            array_push($fields, "date(cta.fcrea) between date('{$fecha_crea_cta[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')\n");
                            array_push($inners, "date(cta.fecha_factura) between date('{$fecha_inicio_ctas_total[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')\n");
                        }
                        if ($info_date["option"] == 2) {
                            array_push($queries, "date(fact.fech_emision) between date('{$fecha_inicio_factura[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')\n");
                            array_push($fields, "date(cta.fcrea) between date('{$fecha_crea_cta[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')\n");
                            array_push($inners, "date(cta.fecha_factura) between date('{$fecha_inicio_ctas_total[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')\n");
                        }
                        if ($info_date["option"] == 3) {
                            array_push($queries, "((date(fact.fech_emision) between date('{$fecha_inicio_factura[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}'))
                                                    or (date(lc.fecha_emision) between date('{$fecha_inicio_liquidacion[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')))\n");
                            array_push($fields, "date(cta.fcrea) between date('{$fecha_crea_cta[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')\n");
                            array_push($inners, "date(cta.fecha_factura) between date('{$fecha_inicio_ctas_total[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')\n");
                            array_push($pagos, "date(cta.fmodifica) between date('{$fecha_inicio_pagos[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')\n");
                            array_push($ctas_pagos, "if(ctap.fecha_registro is null,(date(ctap.fecha_pago) between date('{$fecha_inicio_ctas_cobrar_fecha_pagos[0]->fecha_inicio}') and date('{$final}')),
                                                      (date(ctap.fecha_registro) between date('{$fecha_inicio_ctas_cobrar_fecha_registro[0]->fecha_inicio}') and date('{$final}')))\n");
                        }
                    } else {
                        $hoy=date("Y-m-d");
                        $initial = $info_date["value"];
                        $final = $hoy;
                        if ($info_date["option"] == 1) {
                            array_push($queries, "date(fact.fech_emision) between date('{$fecha_inicio_factura[0]->fecha_inicio}') and date('{$info_date["value"]}')\n");
                            array_push($fields, "date(cta.fcrea) between date('{$fecha_crea_cta[0]->fecha_inicio}') and date('{$info_date["value"]}')\n");
                            array_push($inners, "date(cta.fecha_factura) between date('{$fecha_inicio_ctas_total[0]->fecha_inicio}') and date('{$info_date["value"]}')\n");
                        }
                        if ($info_date["option"] == 2) {
                            array_push($queries, "date(fact.fech_emision) between date('{$fecha_inicio_factura[0]->fecha_inicio}') and date('{$info_date["value"]}')\n");
                            array_push($fields, "date(cta.fcrea) between date('{$fecha_crea_cta[0]->fecha_inicio}') and date('{$info_date["value"]}')\n");
                            array_push($inners, "date(cta.fecha_factura) between date('{$fecha_inicio_ctas_total[0]->fecha_inicio}') and date('{$info_date["value"]}')\n");
                        }
                        if ($info_date["option"] == 3) {
                            //array_push($queries, "date(cta.fecha_pago) = date('{$info_date["value"]}')\n");
                            array_push($queries, "((date(fact.fech_emision) between date('{$fecha_inicio_factura[0]->fecha_inicio}') and date('{$hoy}'))
                                                    or (date(lc.fecha_emision) between date('{$fecha_inicio_liquidacion[0]->fecha_inicio}') and date('{$hoy}')))\n");
                            array_push($fields, "date(cta.fcrea) between date('{$fecha_crea_cta[0]->fecha_inicio}') and date('{$hoy}')\n");
                            array_push($inners, "date(cta.fecha_factura) between date('{$fecha_inicio_ctas_total[0]->fecha_inicio}') and date('{$hoy}')\n");
                            array_push($pagos, "date(cta.fmodifica) between date('{$fecha_inicio_pagos[0]->fecha_inicio}') and date('{$hoy}')\n");
                            array_push($ctas_pagos, "if(ctap.fecha_registro is null,(date(ctap.fecha_pago) between date('{$fecha_inicio_ctas_cobrar_fecha_pagos[0]->fecha_inicio}') and date('{$hoy}')),
                                                      (date(ctap.fecha_registro) between date('{$fecha_inicio_ctas_cobrar_fecha_registro[0]->fecha_inicio}') and date('{$hoy}')))\n");
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
                        array_push($queries, "cta.id_proveedor = {$info_provider["id"]}\n");
                        array_push($fields, "cta.id_proveedor = {$info_provider["id"]}\n");
                        array_push($inners, "cta.id_proveedor = {$info_provider["id"]}\n");
                        array_push($provs, "cta.id_proveedor = {$info_provider["id"]}\n");
                        array_push($pagos, "cta.id_proveedor = {$info_provider["id"]}\n");
                        array_push($ctas_pagos, "ctap.id_proveedor = {$info_provider["id"]}\n");
                    }

                }
                if ($request->user) {
                    $info_user = json_decode($request->user, true);
                    if ($info_user["id"] != 0) {
                        array_push($queries, "cta.ucrea = {$info_user["id"]}\n");
                        array_push($fields, "cta.ucrea = {$info_user["id"]}\n");
                        array_push($inners, "cta.ucrea = {$info_user["id"]}\n");
                        array_push($provs, "cta.ucrea = {$info_user["id"]}\n");
                        array_push($pagos, "cta.ucrea = {$info_user["id"]}\n");
                    }

                }
                if ($request->wayToPay) {
                    $info_payment = json_decode($request->wayToPay, true);
                    if ($info_payment["id"] != 0) {
                        array_push($queries, "cta.id_forma_pagos = {$info_payment["id"]}\n");
                        array_push($fields, "cta.id_forma_pagos = {$info_payment["id"]}\n");
                        array_push($inners, "cta.id_forma_pagos = {$info_payment["id"]}\n");
                        array_push($provs, "cta.id_forma_pagos = {$info_payment["id"]}\n");
                        array_push($pagos, "cta.id_forma_pagos = {$info_payment["id"]}\n");
                        array_push($ctas_pagos, "ctap.id_forma_pagos = {$info_payment["id"]}\n");
                    }

                }
                    $info_invoice = json_decode($request->invoice);
                    if ($info_invoice->typeSearch == 1) {
                        $typeSearch = ">=";
                    }
                    if ($info_invoice->typeSearch == 0) {
                        $typeSearch = "=";
                    }
                    if ($info_invoice->typeSearch == -1) {
                        $typeSearch = "<=";
                    }
                    if (is_numeric($info_invoice->totalCount) && $info_invoice->typeSearch != 2) {
                        $info_invoice->totalCount = intval($info_invoice->totalCount);
                        array_push($queries, "cta.valor_cuota {$typeSearch} {$info_invoice->totalCount}\n");
                        array_push($fields, "cta.valor_cuota {$typeSearch} {$info_invoice->totalCount}\n");
                        array_push($inners, "cta.valor_cuota {$typeSearch} {$info_invoice->totalCount}\n");
                        array_push($provs, "cta.valor_cuota {$typeSearch} {$info_invoice->totalCount}\n");
                        array_push($pagos, "cta.valor_cuota {$typeSearch} {$info_invoice->totalCount}\n");
                    }
                    $queries = implode(" and ", $queries);
                    $inners = implode(" and ", $inners);
                    $fields = implode(" and ", $fields);
                    $provs = implode(" and ", $provs);
                    $pagos = implode(" and ", $pagos);
                    $ctas_pagos = implode(" and ", $ctas_pagos);
                    $query = "SELECT sum(valor_cuota) as valor_cuota,cta.id_factura_compra,cta.id_liquidacion_compra,if(fact.descripcion is null,lc.descripcion,fact.descripcion) as observacion,if(fact.fech_emision is null,lc.fecha_emision,fact.fech_emision) as fech_emision,max(cta.fecha_pago) as fecha_pago,cta.id_proveedor,emp.logo,emp.id_empresa,emp.nombre_empresa,if(now()>max(cta.fecha_pago),'si','no') as vencido
                    from ctas_pagar as cta
                                                            INNER JOIN proveedor as pro
                                                            on pro.id_proveedor=cta.id_proveedor
                                                            INNER JOIN empresa as emp
                                                            on emp.id_empresa=pro.id_empresa
                                                            LEFT JOIN factura_compra as fact
                                                            on fact.id_factcompra=cta.id_factura_compra
                                                            LEFT JOIN liquidacion_compra as lc
                                                            on lc.id_liquidacion_compra=cta.id_liquidacion_compra
                    where
                    {$queries} and
                    pro.id_empresa={$request->company} and cta.tipo=1
                    GROUP BY cta.id_factura_compra,cta.id_liquidacion_compra";/*"select DISTINCT id_factura_compra,fact.observacion,fact.total_factura as valor_cuota,cta.id_proveedor,emp.nombre_empresa,emp.id_empresa,emp.logo,fact.fech_emision,
                    fact.fech_validez as fecha_pago
                    from ctas_pagar as cta,factura_compra as fact,empresa as emp
                    where
                    {$queries} and
                    cta.id_factura_compra=fact.id_factcompra and
                    fact.id_empresa=emp.id_empresa";*/

                    $query2="SELECT numero_tarjeta,if(valor_cuota>=valor_pagado,valor_pagado,valor_cuota) as valor_pagado,id_factura_compra,cta.id_liquidacion_compra,referencias,form.descripcion,cta.pagos_por,cta.fecha_pago,cta.fmodifica 
                    from ctas_pagar as cta
										INNER JOIN proveedor as pro
										on pro.id_proveedor=cta.id_proveedor
										INNER JOIN forma_pagos as form
										on cta.id_forma_pagos=form.id_forma_pagos
										LEFT JOIN factura_compra as fact
										on cta.id_factura_compra=fact.id_factcompra
										LEFT JOIN liquidacion_compra as lc
										on cta.id_liquidacion_compra=lc.id_liquidacion_compra	 
                    where {$pagos} and valor_pagado>0 and cta.tipo=1 and pro.id_empresa={$request->company} ";
                    //dd($query2);
                    $reporte = DB::select($query);
                    $reporte2=DB::select($query2);
                    //dd($provs);
                    if($inners){
                        $query3="SELECT distinct pro.nombre_proveedor,pro.identif_proveedor,pro.id_proveedor,pro.cod_proveedor,pro.telefono_prov,pro.contacto
                        from ctas_pagar as cta,proveedor as pro
                        where {$inners} and cta.valor_cuota>=cta.valor_pagado and cta.tipo=1 and cta.id_proveedor=pro.id_proveedor and pro.id_empresa={$request->company} ORDER BY pro.nombre_proveedor asc";
                    }else{
                        $query3="SELECT distinct pro.nombre_proveedor,pro.identif_proveedor,pro.id_proveedor,pro.cod_proveedor,pro.telefono_prov,pro.contacto
                        from ctas_pagar as cta,proveedor as pro
                        where cta.id_proveedor=pro.id_proveedor and cta.valor_cuota>=cta.valor_pagado and pro.id_empresa={$request->company} ORDER BY pro.nombre_proveedor asc";
                    }

                    //dd($query3);
                    $reporte3=DB::select($query3);
                    $query_referencia="SELECT sum(valor_cuota) as valor_cuota,max(cta.id_proveedor) as id_proveedor,max(fecha_pago) as fecha_pago,referencias 
                    from ctas_pagar as cta,proveedor as prov
                    where {$fields} and referencias is not null and prov.id_proveedor=cta.id_proveedor and prov.id_empresa={$request->company}
                    GROUP BY referencias";
                    //dd($query_referencia);
                    $reporte_referencias=DB::select($query_referencia);
                    $query_vencido=
                    // "SELECT numero_tarjeta,if(valor_cuota>=valor_pagado,valor_pagado,valor_cuota) as valor_pagado,id_factura_compra,form.descripcion,cta.pagos_por,cta.fecha_pago,cta.id_proveedor
                    // from ctas_pagar as cta,forma_pagos as form,proveedor
                    // where {$pagos} and valor_pagado>0 and cta.id_forma_pagos=form.id_forma_pagos and proveedor.id_proveedor=cta.id_proveedor  and cta.referencias is not null and proveedor.id_empresa={$request->company}";
                    "SELECT cta.id_ctaspagar,ctap.posicion,ctap.id_ctas_pagar_pagos,ctap.referencia,if(round(SUBSTRING(ctap.referencia,POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2,locate(';',ctap.referencia,POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2)-(POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2)),2)>ctap.valor_real_pago,ctap.valor_real_pago,round(SUBSTRING(ctap.referencia,POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2,locate(';',ctap.referencia,POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2)-(POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2)),2)) as valor_pagado,cta.valor_pagado as v_pago_cta,locate(';',ctap.referencia,POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2) as loc,
                    POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2 as posicion_cta,
                    cta.id_factura_compra,cta.id_nota_credito_compra,cta.id_liquidacion_compra,if(cta.id_factura_compra is not null or cta.id_liquidacion_compra is not null,SUBSTRING(ctap.referencia,1,8),null) as serie,if(cta.id_factura_compra is not null or cta.id_liquidacion_compra is not null,SUBSTRING(ctap.referencia,9,9),cta.referencias) as documento,cta.referencias,ctap.fecha_pago,ctap.fecha_registro,forma_pagos.descripcion,ctap.pagos_por,
                    locate(';',ctap.referencia) as calve,
                    SUBSTRING(ctap.referencia,1,locate(';',ctap.referencia)-1) as clave_a,cta.id_proveedor,cta.numero_transaccion as nro_tarjeta
                    from ctas_pagar as cta
                    INNER JOIN ctas_pagar_pagos as ctap
                    on ctap.id_proveedor=cta.id_proveedor
                    INNER JOIN forma_pagos
                    on forma_pagos.id_forma_pagos=ctap.id_forma_pagos
                    LEFT JOIN factura_compra as fact
                    on fact.id_factcompra=cta.id_factura_compra
					LEFT JOIN liquidacion_compra as lc
                    on lc.id_liquidacion_compra=cta.id_liquidacion_compra
                    where {$ctas_pagos} and  forma_pagos.id_empresa={$request->company} and cta.tipo=1 and ctap.pago_anticipo is null and ctap.referencia like concat('%;',cta.id_ctaspagar,';%')
                    UNION
					SELECT cta.id_ctaspagar,cta.posicion,ctap.id_ctas_pagar_pagos,ctap.referencia,if(round(SUBSTRING(ctap.referencia,POSITION(concat(';pp:',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+5,locate(';',ctap.referencia,POSITION(concat(';pp:',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+5)-(POSITION(concat(';pp:',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+5)),2)>ctap.valor_real_pago,ctap.valor_real_pago,round(SUBSTRING(ctap.referencia,POSITION(concat(';pp:',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+5,locate(';',ctap.referencia,POSITION(concat(';pp:',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+5)-(POSITION(concat(';pp:',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+5)),2)) as valor_pagado,cta.valor_pagado as v_pago_cta,locate(';',ctap.referencia,POSITION(concat(';pp:',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2) as loc,
                    POSITION(concat(';pp:',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2 as posicion_cta,
                    (select id_factura_compra from nota_credito_compra where id_nota_credito_compra=cta.id_nota_credito_compra limit 1) as id_factura_compra,cta.id_nota_credito_compra,cta.id_liquidacion_compra,if(cta.id_factura_compra is not null or cta.id_liquidacion_compra is not null,SUBSTRING(ctap.referencia,1,8),null) as serie,if(cta.id_factura_compra is not null or cta.id_liquidacion_compra is not null,SUBSTRING(ctap.referencia,9,9),cta.referencias) as documento,cta.referencias,ctap.fecha_pago,ctap.fecha_registro,forma_pagos.descripcion,ctap.pagos_por,
                    locate(';',ctap.referencia) as calve,
                    SUBSTRING(ctap.referencia,1,locate(';',ctap.referencia)-1) as clave_a,cta.id_proveedor,cta.numero_transaccion as nro_tarjeta
					from ctas_pagar as cta
                    INNER JOIN ctas_pagar_pagos as ctap
                    on ctap.id_proveedor=cta.id_proveedor
                    INNER JOIN forma_pagos
                    on forma_pagos.id_forma_pagos=ctap.id_forma_pagos
					where {$ctas_pagos} and  forma_pagos.id_empresa={$request->company} and cta.tipo=3 and ctap.pago_anticipo>0 and ctap.referencia like concat('%;pp:',cta.id_ctaspagar,';%') and cta.id_nota_credito_compra is not null
                    ORDER BY fecha_pago,fecha_registro desc";
                    //dd($query_vencido);
                    $reporte_vencido=DB::select($query_vencido);
                    $reporte4=DB::select("SELECT email_empresa,password,servidor_correo,puerto_correo,seguridad_correo,id_empresa from empresa where id_empresa=". $request->company);
                    $empresa=DB::select("SELECT * from empresa where id_empresa=".$request->company);
                    $nuevo_reporte="SELECT sum(valor_cuota) as valor_cuota,fact.id_factcompra as id_factura,lc.id_liquidacion_compra,if(fact.descripcion is null,lc.descripcion,fact.descripcion) as observacion,cta.referencias,fact.fech_emision as fecha_emision,max(cta.fecha_pago) as fecha_pago,max(pro.id_proveedor) as id_proveedor,if(now()>max(fecha_pago),'si','no') as vencido
                    from ctas_pagar as cta
                    INNER JOIN proveedor as pro
                    on pro.id_proveedor=cta.id_proveedor
                    LEFT JOIN factura_compra as fact
                    on fact.id_factcompra=cta.id_factura_compra
					LEFT JOIN liquidacion_compra as lc
                    on lc.id_liquidacion_compra=cta.id_liquidacion_compra
                    where {$inners} and pro.id_empresa={$request->company} and cta.tipo=1 and (cta.id_factura_compra is not null or cta.referencias is not null or cta.id_liquidacion_compra is not null)
                    GROUP BY fact.id_factcompra,cta.referencias,cta.id_proveedor,lc.id_liquidacion_compra
                    ORDER BY max(fecha_pago) asc";
                    //dd($nuevo_reporte);
                    $reporte_nuevo=DB::select($nuevo_reporte);
                    $nuevo_pago="SELECT numero_tarjeta,if(valor_cuota>=valor_pagado,valor_pagado,valor_cuota) as valor_pagado,id_factura_compra as id_factura,cta.id_liquidacion_compra,if(fact.descripcion is null,lc.descripcion,fact.descripcion) as observacion,cta.referencias,form.descripcion,cta.pagos_por,cta.fecha_pago,cta.id_proveedor,cta.fmodifica
                                    from ctas_pagar as cta
                                        INNER JOIN forma_pagos as form
                                        on form.id_forma_pagos=cta.id_forma_pagos
                                        INNER JOIN proveedor
                                        on proveedor.id_proveedor=cta.id_proveedor
                                        LEFT JOIN factura_compra as fact
                                        on fact.id_factcompra=cta.id_factura_compra
																				 LEFT JOIN liquidacion_compra as lc
                                        on lc.id_liquidacion_compra=cta.id_liquidacion_compra
                                        where {$pagos} and valor_pagado>0 and cta.tipo=1 and proveedor.id_empresa={$request->company}";
                    //dd($nuevo_pago);
                    $reporte_pagos=DB::select($nuevo_pago);
                    $ctas_cobrar="SELECT cop.id_ctas_pagar_pagos AS contador, cop.*, cl.nombre_proveedor AS nombreproveedor, fp.descripcion AS descripcionsri, IF(fecha_registro IS NULL, fecha_pago, fecha_registro) as fechageneral
                    FROM ctas_pagar_pagos cop
                    INNER JOIN proveedor cl ON cl.id_proveedor = cop.id_proveedor
                    LEFT JOIN forma_pagos fp ON fp.id_forma_pagos = cop.id_forma_pagos
                    LEFT JOIN forma_pagos_sri fps ON fps.id_forma_pagos_sri = fp.id_forma_pagos_sri
                    INNER JOIN empresa em ON em.id_empresa = cl.id_empresa
                    WHERE em.id_empresa = {$request->company} and cop.pago_anticipo is null  ORDER BY fechageneral DESC";
                    $reporte_ctas=DB::select($ctas_cobrar);
                    if (!$reporte && !$reporte_referencias) {
                        return response('no-data-report', 200)->header('Content-Type', 'application/json');
                    } else {
                        if($request->email && $request->destinatario){
                            $Reportes = new generarReportes();
                            $ruta = constant("DATA_EMPRESA") . $request->company . '/compras/cuentas_pagar';
                            if(!file_exists($ruta)){
                                mkdir($ruta, 0755,true);
                            }
                            
                            $strPDF = $Reportes->cuenta_pagar_reporte_resumen_factura($reporte_nuevo,$reporte_ctas,$reporte_pagos,$reporte, $fecha_inicio[0]->fecha_inicio, $final,$reporte2,$reporte3,$reporte_referencias,$reporte_vencido,$empresa[0],$ruta);
                            $email=new sendEmail();
                            $email->enviarCtasPagar($reporte4[0],$request->email,$request->destinatario,"Detalle_Factura");
                            $cta=$ruta.'/cuenta_por_pagar_Detalle_Factura.pdf';
                            /*if(file_exists($cta)){
                                unlink($cta);
                            }*/
                            return response($strPDF, 200)->header('Content-Type', 'application/pdf');

                        }else{
                            $Reportes = new generarReportes();
                            $strPDF = $Reportes->cuenta_pagar_reporte_resumen_factura($reporte_nuevo,$reporte_ctas,$reporte_pagos,$reporte, $fecha_inicio[0]->fecha_inicio, $final,$reporte2,$reporte3,$reporte_referencias,$reporte_vencido,$empresa[0]);
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
                        if ($info_date["option"] == 1) {
                            array_push($queries, "date(cta.fecha_pago) between date('{$info_date["range"]["initial"]}') and date('{$info_date["range"]["final"]}')\n");
                        }
                        if ($info_date["option"] == 2) {
                            array_push($queries, "date(cta.fecha_pago) between date('{$info_date["range"]["initial"]}') and date('{$info_date["range"]["final"]}')\n");
                        }
                        if ($info_date["option"] == 3) {
                            array_push($queries, "((date(fact.fech_emision) between date('{$fecha_inicio_factura[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}'))
                                                    or (date(lc.fecha_emision) between date('{$fecha_inicio_liquidacion[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')))\n");
                            array_push($fields, "date(cta.fcrea) between date('{$fecha_crea_cta[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')\n");
                            array_push($inners, "date(cta.fecha_factura) between date('{$fecha_inicio_ctas_total[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')\n");
                        }
                    } else {
                        $hoy = date("Y-m-d");
                        $initial = str_replace("-010-","-10-",$info_date["value"]);
                        $final = $hoy;
                        if ($info_date["option"] == 1) {
                            array_push($queries, "date(cta.fecha_pago) = date('{$info_date["value"]}')\n");
                        }
                        if ($info_date["option"] == 2) {
                            array_push($queries, "date(cta.fecha_pago) = date('{$info_date["value"]}')\n");
                        }
                        if ($info_date["option"] == 3) {
                            //array_push($queries, "date(cta.fecha_pago) = date('{$info_date["value"]}')\n");
                            array_push($queries, "((date(fact.fech_emision) between date('{$fecha_inicio_factura[0]->fecha_inicio}') and date('{$hoy}'))
                                                    or (date(lc.fecha_emision) between date('{$fecha_inicio_liquidacion[0]->fecha_inicio}') and date('{$hoy}')))\n");
                            array_push($fields, "date(cta.fcrea) between date('{$fecha_inicio[0]->fecha_inicio}') and date('{$hoy}')\n");
                            array_push($inners, "date(cta.fecha_factura) between date('{$fecha_inicio_ctas_total[0]->fecha_inicio}') and date('{$hoy}')\n");
                        }
                    }
                }
                if ($request->project) {
                    $info_project = json_decode($request->project, true);
                    if ($info_project["id"] != 0) {
                        array_push($queries, "f.id_proyecto = {$info_project["id"]}\n");
                        array_push($fields, "f.id_proyecto = {$info_project["id"]}\n");
                        array_push($inners, "f.id_proyecto = {$info_project["id"]}\n");
                    }

                }
                if ($request->provider) {
                    $info_provider = json_decode($request->provider, true);
                    if ($info_provider["id"] != 0) {
                        array_push($queries, "cta.id_proveedor = {$info_provider["id"]}\n");
                        array_push($fields, "cta.id_proveedor = {$info_provider["id"]}\n");
                        array_push($inners, "cta.id_proveedor = {$info_provider["id"]}\n");
                    }

                }
                if ($request->user) {
                    $info_user = json_decode($request->user, true);
                    if ($info_user["id"] != 0) {
                        array_push($queries, "cta.ucrea = {$info_user["id"]}\n");
                        array_push($fields, "cta.ucrea = {$info_user["id"]}\n");
                        array_push($inners, "cta.ucrea = {$info_user["id"]}\n");
                    }

                }
                if ($request->wayToPay) {
                    $info_payment = json_decode($request->wayToPay, true);
                    if ($info_payment["id"] != 0) {
                        array_push($queries, "cta.id_forma_pagos = {$info_payment["id"]}\n");
                        array_push($fields, "cta.id_forma_pagos = {$info_payment["id"]}\n");
                        array_push($inners, "cta.id_forma_pagos = {$info_payment["id"]}\n");
                    }

                }
                    $info_invoice = json_decode($request->invoice);
                    if ($info_invoice->typeSearch == 1) {
                        $typeSearch = ">=";
                    }
                    if ($info_invoice->typeSearch == 0) {
                        $typeSearch = "=";
                    }
                    if ($info_invoice->typeSearch == -1) {
                        $typeSearch = "<=";
                    }
                    if (is_numeric($info_invoice->totalCount) && $info_invoice->typeSearch != 2) {
                        $info_invoice->totalCount = intval($info_invoice->totalCount);
                        array_push($queries, "cta.valor_cuota {$typeSearch} {$info_invoice->totalCount}\n");
                        array_push($fields, "cta.valor_cuota {$typeSearch} {$info_invoice->totalCount}\n");
                        array_push($inners, "cta.valor_cuota {$typeSearch} {$info_invoice->totalCount}\n");
                    }
                    $queries = implode(" and ", $queries);
                    $inners = implode(" and ", $inners);
                    $fields = implode(" and ", $fields);
                    // "select sum(cta.valor_cuota) as valor_cuota,sum(if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota)) as abono,sum(cta.valor_cuota)-sum(if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota)) as saldo,cta.id_factura_compra,fact.id_proveedor,fact.fech_emision,
                    // fact.fech_validez as fecha_pago,fact.descripcion as observacion,emp.logo,emp.id_empresa,emp.nombre_empresa,pro.nombre_proveedor,pro.identif_proveedor,
                    // sum(if(DATEDIFF(cta.fecha_pago,now())>=-59 and DATEDIFF(cta.fecha_pago,now())<=-30,cta.valor_cuota,0.00)) as mentreninta,
                    // sum(if(DATEDIFF(cta.fecha_pago,now())>=-89 and DATEDIFF(cta.fecha_pago,now())<=-60,cta.valor_cuota,0.00)) as mensesenta,
                    // sum(if(DATEDIFF(cta.fecha_pago,now())>=-119 and DATEDIFF(cta.fecha_pago,now())<=-90,cta.valor_cuota,0.00)) as mennoventa,
                    // sum(if(DATEDIFF(cta.fecha_pago,now())<=-120,cta.valor_cuota,0.00)) as mencientoveinte,
                    // sum(if(DATEDIFF(cta.fecha_pago,now())>=-29 and DATEDIFF(cta.fecha_pago,now())<=29,cta.valor_cuota,0.00)) as cero,
                    // sum(if(DATEDIFF(cta.fecha_pago,now())>=30 and DATEDIFF(cta.fecha_pago,now())<=59,cta.valor_cuota,0.00)) as treninta,
                    // sum(if(DATEDIFF(cta.fecha_pago,now())>=60 and DATEDIFF(cta.fecha_pago,now())<=89,cta.valor_cuota,0.00)) as sesenta,
                    // sum(if(DATEDIFF(cta.fecha_pago,now())>=90 and DATEDIFF(cta.fecha_pago,now())<=119,cta.valor_cuota,.000)) as noventa,
                    // sum(if(DATEDIFF(cta.fecha_pago,now())>=120,cta.valor_cuota,0.00)) as cientoveinte
                    // from ctas_pagar as cta,factura_compra as fact,empresa as emp,proveedor as pro
                    // where
                    // {$queries} and
                    // cta.id_factura_compra=fact.id_factcompra
                    // and fact.id_empresa=emp.id_empresa
                    // and pro.id_proveedor=fact.id_proveedor
                    // and pro.id_empresa={$request->company}
                    // GROUP BY id_factura_compra
                    // ORDER BY nombre_proveedor asc"
                    $query = "SELECT sum(cta.valor_cuota) as valor_cuota,sum(if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota)) as abono,sum(cta.valor_cuota)-sum(if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota)) as saldo,cta.id_factura_compra,cta.id_liquidacion_compra,pro.id_proveedor,if(fact.fech_emision is null,lc.fecha_emision,fact.fech_emision) as fech_emision,
                    fact.fech_validez as fecha_pago,fact.descripcion as observacion,emp.logo,emp.id_empresa,emp.nombre_empresa,pro.nombre_proveedor,pro.identif_proveedor,
                    sum(if(DATEDIFF(cta.fecha_pago,now())>=-30 and DATEDIFF(cta.fecha_pago,now())<=-1,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00)) as mentreninta,
                    sum(if(DATEDIFF(cta.fecha_pago,now())>=-60 and DATEDIFF(cta.fecha_pago,now())<=-31,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00)) as mensesenta,
                    sum(if(DATEDIFF(cta.fecha_pago,now())>=-90 and DATEDIFF(cta.fecha_pago,now())<=-61,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00)) as mennoventa,
                    sum(if(DATEDIFF(cta.fecha_pago,now())<=-91,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00)) as mencientoveinte,
                    sum(if(DATEDIFF(cta.fecha_pago,now())=0,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00)) as cero,
                    sum(if(DATEDIFF(cta.fecha_pago,now())>=1 and DATEDIFF(cta.fecha_pago,now())<=30,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00)) as treninta,
                    sum(if(DATEDIFF(cta.fecha_pago,now())>=31 and DATEDIFF(cta.fecha_pago,now())<=60,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00)) as sesenta,
                    sum(if(DATEDIFF(cta.fecha_pago,now())>=61 and DATEDIFF(cta.fecha_pago,now())<=90,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),.000)) as noventa,
                    sum(if(DATEDIFF(cta.fecha_pago,now())>=91,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00)) as cientoveinte
                    from ctas_pagar as cta-- ,factura_compra as fact,empresa as emp,proveedor as pro
										INNER JOIN proveedor as pro
										on pro.id_proveedor=cta.id_proveedor
										INNER JOIN empresa as emp
										on pro.id_empresa=emp.id_empresa
										LEFT JOIN factura_compra as fact
										on fact.id_factcompra=cta.id_factura_compra
										LEFT JOIN liquidacion_compra as lc
										on lc.id_liquidacion_compra=cta.id_liquidacion_compra
                    where
					{$queries}
                    and pro.id_empresa={$request->company}
                    and cta.tipo=1
                    and cta.valor_cuota>cta.valor_pagado
                    GROUP BY id_factura_compra,cta.id_liquidacion_compra
                    ORDER BY nombre_proveedor,if(fact.fech_emision is null,lc.fecha_emision,fact.fech_emision) asc";/*"select DISTINCT id_factura_compra,fact.observacion,fact.total_factura as valor_cuota,cta.id_proveedor,emp.nombre_empresa,emp.id_empresa,emp.logo,fact.fech_emision,
                    fact.fech_validez as fecha_pago
                    from ctas_pagar as cta,factura_compra as fact,empresa as emp
                    where
                    {$queries} and
                    cta.id_factura_compra=fact.id_factcompra and
                    fact.id_empresa=emp.id_empresa";*/

                    $query2="select numero_tarjeta,if(valor_cuota>=valor_pagado,valor_pagado,valor_cuota) as valor_pagado,id_factura_compra from ctas_pagar as cta,proveedor where  valor_pagado>=valor_cuota and cta.tipo=1 and  proveedor.id_proveedor=cta.id_proveedor and proveedor.id_empresa={$request->company}";
                    //dd($query);
                    $reporte = DB::select($query);
                    $reporte2=DB::select($query2);
                    $query3="SELECT cta.fecha_pago,CURRENT_DATE,DATEDIFF(cta.fecha_pago,now()),cta.valor_cuota,if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota) as valor_pagado,cta.id_factura_compra,cta.id_liquidacion_compra,
                    if(DATEDIFF(cta.fecha_pago,now())>=-59 and DATEDIFF(cta.fecha_pago,now())<=-30,cta.valor_cuota,0) as mentreninta,
                    if(DATEDIFF(cta.fecha_pago,now())>=-89 and DATEDIFF(cta.fecha_pago,now())<=-60,cta.valor_cuota,0) as mensesenta,
                    if(DATEDIFF(cta.fecha_pago,now())>=-119 and DATEDIFF(cta.fecha_pago,now())<=-90,cta.valor_cuota,0) as mennoventa,
                    if(DATEDIFF(cta.fecha_pago,now())<=-120,cta.valor_cuota,0) as mencientoveinte,
                    if(DATEDIFF(cta.fecha_pago,now())>=-29 and DATEDIFF(cta.fecha_pago,now())<=29,cta.valor_cuota,0.00) as cero,
                    if(DATEDIFF(cta.fecha_pago,now())>=30 and DATEDIFF(cta.fecha_pago,now())<=59,cta.valor_cuota,0) as treninta,
                    if(DATEDIFF(cta.fecha_pago,now())>=60 and DATEDIFF(cta.fecha_pago,now())<=89,cta.valor_cuota,0) as sesenta,
                    if(DATEDIFF(cta.fecha_pago,now())>=90 and DATEDIFF(cta.fecha_pago,now())<=119,cta.valor_cuota,0) as noventa,
                    if(DATEDIFF(cta.fecha_pago,now())>=120,cta.valor_cuota,0) as cientoveinte
                    from ctas_pagar as cta
										INNER JOIN proveedor as pro
										on pro.id_proveedor=cta.id_proveedor
										INNER JOIN empresa as emp
										on pro.id_empresa=emp.id_empresa
										LEFT JOIN factura_compra as fact
										on fact.id_factcompra=cta.id_factura_compra
										LEFT JOIN liquidacion_compra as lc
										on lc.id_liquidacion_compra=cta.id_liquidacion_compra
                                        where {$queries}  and cta.valor_cuota>cta.valor_pagado and cta.tipo=1 and pro.id_empresa={$request->company}";
                    //dd($query3);
                    $reporte3=DB::select($query3);
                    $query_referencia="SELECT cta.valor_cuota as valor_cuota,if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota) as abono,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota) as saldo,cta.id_proveedor,cta.fcrea,
                    cta.fecha_pago,cta.referencias as observacion,emp.logo,emp.id_empresa,emp.nombre_empresa,pro.nombre_proveedor,pro.identif_proveedor,
                    if(DATEDIFF(cta.fecha_pago,now())>=-30 and DATEDIFF(cta.fecha_pago,now())<=-1,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00) as mentreninta,
                    if(DATEDIFF(cta.fecha_pago,now())>=-60 and DATEDIFF(cta.fecha_pago,now())<=-31,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00) as mensesenta,
                    if(DATEDIFF(cta.fecha_pago,now())>=-90 and DATEDIFF(cta.fecha_pago,now())<=-61,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00) as mennoventa,
                    if(DATEDIFF(cta.fecha_pago,now())<=-91,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00) as mencientoveinte,
                    if(DATEDIFF(cta.fecha_pago,now())=0,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00) as cero,
                    if(DATEDIFF(cta.fecha_pago,now())>=1 and DATEDIFF(cta.fecha_pago,now())<=30,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00) as treninta,
                    if(DATEDIFF(cta.fecha_pago,now())>=31 and DATEDIFF(cta.fecha_pago,now())<=60,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00) as sesenta,
                    if(DATEDIFF(cta.fecha_pago,now())>=61 and DATEDIFF(cta.fecha_pago,now())<=90,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),.000) as noventa,
                    if(DATEDIFF(cta.fecha_pago,now())>=91,cta.valor_cuota,0.00) as cientoveinte
                    from ctas_pagar as cta,empresa as emp,proveedor as pro
                    where
                    {$fields}
                    and pro.id_proveedor=cta.id_proveedor
					and pro.id_empresa=emp.id_empresa
                    and pro.id_empresa={$request->company}
					and cta.referencias is not null
                    and cta.tipo=1
                    and cta.valor_cuota>cta.valor_pagado
                    ORDER BY nombre_proveedor,cta.fcrea asc";
                    //dd($query_referencia);
                    $reporte_referencia=DB::select($query_referencia);
                    $empresa=DB::select("SELECT * from empresa where id_empresa=".$request->company);
                    $reporte4=DB::select("SELECT email_empresa,password,servidor_correo,puerto_correo,seguridad_correo,id_empresa from empresa where id_empresa=". $request->company);
                    $nuevo_reporte="SELECT sum(cta.valor_cuota) as valor_cuota,sum(if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota)) as abono,sum(cta.valor_cuota)-sum(if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota)) as saldo,fact.id_factcompra,lc.id_liquidacion_compra,cta.referencias,max(pro.id_proveedor) as id_proveedor,max(cta.fecha_factura) as fecha_emision,max(cta.fecha_pago) as fecha_pago,if(fact.descripcion is null,lc.descripcion,fact.descripcion) as observacion,max(pro.nombre_proveedor) as nombre,max(pro.identif_proveedor) as identificacion,
                    sum(if(DATEDIFF(cta.fecha_pago,now())>=-30 and DATEDIFF(cta.fecha_pago,now())<=-1,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0)) as mentreninta,
                    sum(if(DATEDIFF(cta.fecha_pago,now())>=-60 and DATEDIFF(cta.fecha_pago,now())<=-31,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00)) as mensesenta,
                    sum(if(DATEDIFF(cta.fecha_pago,now())>=-90 and DATEDIFF(cta.fecha_pago,now())<=-61,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00)) as mennoventa,
                    sum(if(DATEDIFF(cta.fecha_pago,now())<=-91,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00)) as mencientoveinte,
                    sum(if(DATEDIFF(cta.fecha_pago,now())=0,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00)) as cero,
                    sum(if(DATEDIFF(cta.fecha_pago,now())>=1 and DATEDIFF(cta.fecha_pago,now())<=30,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00)) as treninta,
                    sum(if(DATEDIFF(cta.fecha_pago,now())>=31 and DATEDIFF(cta.fecha_pago,now())<=60,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00)) as sesenta,
                    sum(if(DATEDIFF(cta.fecha_pago,now())>=61 and DATEDIFF(cta.fecha_pago,now())<=90,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),.000)) as noventa,
                    sum(if(DATEDIFF(cta.fecha_pago,now())>=91,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00)) as cientoveinte
                    FROM ctas_pagar as cta
                    INNER JOIN proveedor as pro
                    on pro.id_proveedor=cta.id_proveedor
                    LEFT JOIN factura_compra as fact
                    on fact.id_factcompra=cta.id_factura_compra
                    LEFT JOIN liquidacion_compra as lc
                    on lc.id_liquidacion_compra=cta.id_liquidacion_compra
                    where {$inners} and cta.valor_cuota>cta.valor_pagado and pro.id_empresa={$request->company} and (cta.id_factura_compra is not null or cta.referencias is not null or cta.id_liquidacion_compra is not null) and cta.tipo=1
                    GROUP BY fact.id_factcompra,cta.referencias,cta.id_proveedor,cta.id_liquidacion_compra
                    ORDER BY max(pro.nombre_proveedor),max(cta.fecha_factura) asc";
                    $reporte_nuevo=DB::select($nuevo_reporte);
                    if (!$reporte_nuevo) {
                        return response('no-data-report', 200)->header('Content-Type', 'application/json');
                    } else {
                        if($request->email && $request->destinatario){
                            $Reportes = new generarReportes();
                            $ruta = constant("DATA_EMPRESA") . $request->company . '/compras/cuentas_pagar';
                            if(!file_exists($ruta)){
                                mkdir($ruta, 0755,true);
                            }
                            $strPDF = $Reportes->cuenta_pagar_reporte_dias_vencidos($reporte_nuevo,$reporte, $fecha_inicio[0]->fecha_inicio, $final,$reporte2,$reporte3,$reporte_referencia,$empresa[0],$ruta);
                            $email=new sendEmail();
                            $email->enviarCtasPagar($reporte4[0],$request->email,$request->destinatario,"Dias_Vencimiento");
                            $cta=$ruta.'/cuenta_por_pagar_Dias_Vencimiento.pdf';
                            /*if(file_exists($cta)){
                                unlink($cta);
                            }*/
                            return response($strPDF, 200)->header('Content-Type', 'application/pdf');

                        }else{
                            $Reportes = new generarReportes();
                            $strPDF = $Reportes->cuenta_pagar_reporte_dias_vencidos($reporte_nuevo,$reporte, $fecha_inicio[0]->fecha_inicio, $final,$reporte2,$reporte3,$reporte_referencia,$empresa[0]);
                            return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                        }

                    }
            }
            if($info_reporte["id"] == 5){
                $info_date = json_decode($request->dates, true);
                if ($request->dates) {

                    //dd;
                    //dd($request->date);
                    if ($request->currentDate !== "true") {
                        $initial = str_replace("-010-", "-10-", $info_date["range"]["initial"]);
                        $final = str_replace("-010-", "-10-", $info_date["range"]["final"]);
                        if ($info_date["option"] == 1) {
                            array_push($queries, "date(cta.fecha_pago) between date('{$info_date["range"]["initial"]}') and date('{$info_date["range"]["final"]}')\n");
                        }
                        if ($info_date["option"] == 2) {
                            array_push($queries, "date(cta.fecha_pago) between date('{$info_date["range"]["initial"]}') and date('{$info_date["range"]["final"]}')\n");
                        }
                        if ($info_date["option"] == 3) {
                            array_push($anticipo, "if(cta.fecha_registro is null,
                                                        date(cta.fecha_pago) BETWEEN date('{$fecha_inicio_anticipo[0]->fecha_inicio}') and date('{$final}'),
                                                        date(cta.fecha_registro) BETWEEN date('{$fecha_inicio_anticipo[0]->fecha_inicio}') and date('{$final}'))\n");
                            array_push($anticipo_pagos_fact, "date(fact.fech_emision) between date('{$fecha_inicio_factura[0]->fecha_inicio}') and date('{$final}')\n");
                            array_push($anticipo_pagos_lc, "date(lc.fecha_emision) between date('{$fecha_inicio_liquidacion[0]->fecha_inicio}') and date('{$final}')\n");
                            //dd($info_date["range"]["final"]);
                            //array_push($queries, "date(fact.fecha_emision) between date('{$fecha_inicio_factura[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')\n");
                        }
                    } else {
                        $hoy = date("Y-m-d");
                        $initial = str_replace("-010-", "-10-", $info_date["value"]);
                        $final = $hoy;
                        if ($info_date["option"] == 1) {
                            array_push($queries, "date(cta.fecha_pago) = date('{$info_date["value"]}')\n");
                        }
                        if ($info_date["option"] == 2) {
                            array_push($queries, "date(cta.fecha_pago) = date('{$info_date["value"]}')\n");
                        }
                        if ($info_date["option"] == 3) {
                            //array_push($queries, "date(cta.fecha_pago) = date('{$info_date["value"]}')\n");
                            array_push($anticipo, "if(cta.fecha_registro is null,
                                                        date(cta.fecha_pago) BETWEEN date('{$fecha_inicio_anticipo[0]->fecha_inicio}') and date('{$hoy}'),
                                                        date(cta.fecha_registro) BETWEEN date('{$fecha_inicio_anticipo[0]->fecha_inicio}') and date('{$hoy}'))\n");
                            array_push($anticipo_pagos_fact, "date(fact.fech_emision) between date('{$fecha_inicio_factura[0]->fecha_inicio}') and date('{$hoy}')\n");
                            array_push($anticipo_pagos_lc, "date(lc.fecha_emision) between date('{$fecha_inicio_liquidacion[0]->fecha_inicio}') and date('{$hoy}')\n");
                        }
                    }
                }
                if ($request->project) {
                    $info_project = json_decode($request->project, true);
                    if ($info_project["id"] != 0) {
                        array_push($queries, "f.id_proyecto = {$info_project["id"]}\n");
                    }
                }
                $nombre_vendedor = "";
                if ($request->rol_user !== "2") {
                    

                    if ($request->user) {

                        $info_user = json_decode($request->user, true);
                        
                        if ($info_user["id"] != 0) {
                        
                            //array_push($queries, "(f.created_by = {$info_seller["id"]})\n");
                                    //array_push($anticipo, "cta.created_by = {$info_user["id"]}\n");
                                    //array_push($anticipo_pagos, "cta.created_by = {$info_user["id"]}\n");
                                    
                            
                        }
                        // else{
                        //     if ($vnd) {
                        //         array_push($queries, "((f.id_vendedor = {$vnd[0]->id_vendedor}) or
                        //                                 (nv.id_vendedor = {$vnd[0]->id_vendedor}))\n");
                        //         array_push($pagos, "((f.id_vendedor = {$vnd[0]->id_vendedor}) or
                        //                                 (nv.id_vendedor = {$vnd[0]->id_vendedor}))\n");
                        //         $nombre_vendedor=$vnd[0]->nombre_vendedor;
                        //     }
                        // }
                    }
                } else {
                    //dd("Entro al vendedor");
                    // $vnd = DB::select("SELECT *,concat(nombres,' ',apellidos) as nombre_vendedor from user where id=" . $request->user);
                    // if (count($vnd)>0) {

                        
                    //     array_push($queries, "cta.created_by = {$vnd[0]->id}\n");
                    //     array_push($fields, "cta.created_by = {$vnd[0]->id}\n");
                    //     array_push($inners, "cta.created_by = {$vnd[0]->id}\n");
                    //     array_push($clients, "cta.created_by = {$vnd[0]->id}\n");
                    //     array_push($pagos, "cta.created_by = {$vnd[0]->id}\n");
                    //     // 
                    // }
                }
                if ($request->wayToPay) {
                    $info_payment = json_decode($request->wayToPay, true);
                    if ($info_payment["id"] != 0) {
                        array_push($anticipo, "cta.id_forma_pagos = {$info_payment["id"]}\n");
                        
                    }
                }
                if ($request->provider) {
                    
                    $info_provider = json_decode($request->provider);
                    if($info_provider->id>0){
                        $exists_provider = true;
                        array_push($anticipo, "pro.id_proveedor = {$info_provider->id}\n");
                        array_push($anticipo_pagos_fact, "fact.id_proveedor = {$info_provider->id}\n");
                        array_push($anticipo_pagos_lc, "lc.id_proveedor = {$info_provider->id}\n");
                    }
                    
                } else {
                    $exists_client = false;
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
                    array_push($anticipo, "cta.valor_cuota {$typeSearch} {$request->totalCount}\n");

                    //array_push($ctas_pagos, "cta.valor_cuota {$typeSearch} {$request->totalCount}\n");
                }
                $anticipo = implode(" and ", $anticipo);
                $anticipo_pagos_fact = implode(" and ", $anticipo_pagos_fact);
                $anticipo_pagos_lc = implode(" and ", $anticipo_pagos_lc);
                
                $clientes=DB::select("SELECT pro.id_proveedor as id_cliente,pro.nombre_proveedor as nombre ,pro.cod_proveedor as codigo,pro.identif_proveedor as identificacion,pro.telefono_prov as telefono,pro.contacto 
                                            from proveedor as pro 
                                            INNER JOIN ctas_pagar as cta ON cta.id_proveedor=pro.id_proveedor 
                                            where cta.tipo=3 and pro.id_empresa={$request->company} and {$anticipo}
                                            GROUP BY pro.id_proveedor
                                            ORDER BY pro.nombre_proveedor");
                
                $anticipos=DB::select("SELECT cta.*,if(cta.fecha_registro is null,cta.fecha_pago,cta.fecha_registro) as fecha_anticipo,fp.descripcion as descripcion_fp,cta.id_proveedor as id_cliente
                                        from ctas_pagar as cta
                                        INNER JOIN proveedor as pro ON cta.id_proveedor=pro.id_proveedor
                                        LEFT JOIN forma_pagos as fp ON cta.id_forma_pagos=fp.id_forma_pagos
                                        where cta.tipo=3 and pro.id_empresa={$request->company} and {$anticipo} and cta.id_nota_credito_compra is null");
                // dd("SELECT cta.*,if(cta.fecha_registro is null,cta.fecha_pago,cta.fecha_registro) as fecha_anticipo,fp.descripcion as descripcion_fp,cta.id_proveedor as id_cliente
                // from ctas_pagar as cta
                // INNER JOIN proveedor as pro ON cta.id_proveedor=pro.id_proveedor
                // LEFT JOIN forma_pagos as fp ON cta.id_forma_pagos=fp.id_forma_pagos
                // where cta.tipo=3 and pro.id_empresa={$request->company} and {$anticipo}");

                $pagos_anticipo=DB::select("SELECT fact.descripcion, facp.total as total_pago_ant,fact.fech_emision as fecha_emision,fact.id_proveedor as id_cliente,null as pago_anticipo
                                            from factura_compra as fact
                                            INNER JOIN factura_compra_pagos as facp ON fact.id_factcompra=facp.id_factura_compra
                                            where fact.id_empresa={$request->company} and facp.anticipo=1 and {$anticipo_pagos_fact}
                                            UNION
                                            SELECT lc.descripcion, lcp.total as total_pago_ant,lc.fecha_emision,lc.id_proveedor as id_cliente,null as pago_anticipo
                                            from liquidacion_compra as lc
                                            INNER JOIN liquidacion_compra_pagos as lcp ON lc.id_liquidacion_compra=lcp.id_liquidacion_compra
                                            where lc.id_empresa={$request->company} and  lcp.anticipo=1 and {$anticipo_pagos_lc}
                                            UNION
                                            SELECT cta.posicion as descripcion, cta.valor_real_pago as total_pago_ant,if(cta.fecha_registro is null,cta.fecha_pago,cta.fecha_registro) as fecha_emision,cta.id_proveedor as id_cliente,1 as pago_anticipo
                                            from ctas_pagar_pagos as cta
                                            INNER JOIN proveedor as pro ON cta.id_proveedor=pro.id_proveedor
                                            where pro.id_empresa={$request->company} and  cta.pago_anticipo is not null and {$anticipo} and cta.nota_credito is null");
                $empresa=DB::select("SELECT * from empresa where id_empresa={$request->company}");
                
                if (!$anticipos && !$pagos_anticipo) {
                    return response('no-data-report', 200)->header('Content-Type', 'application/json');
                } else {
                    if ($request->email && $request->destinatario) {
                        $Reportes = new generarReportes();
                        $ruta = constant("DATA_EMPRESA") . $request->company . '/facturacion/cuentas_pagar';
                        if (!file_exists($ruta)) {
                            mkdir($ruta, 0755, true);
                        }
                        $strPDF = $Reportes->cuenta_pagar_reporte_anticipo($clientes, $anticipos, $fecha_inicio_anticipo[0]->fecha_inicio, $final, $pagos_anticipo, $empresa[0],$ruta);
                        $email = new sendEmail();
                        $email->enviarCtasCobrar($reporte4[0], $request->email, $request->destinatario, "Anticipo");
                        $cta = $ruta . '/cuenta_por_pagar_Anticipo.pdf';
                        /*if(file_exists($cta)){
                            unlink($cta);
                        }*/
                        return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                    } else {
                        $Reportes = new generarReportes();
                        $strPDF = $Reportes->cuenta_pagar_reporte_anticipo($clientes, $anticipos, $fecha_inicio_anticipo[0]->fecha_inicio, $final, $pagos_anticipo, $empresa[0]);
                        return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                    }
                }
            }
        }
        /*


        }*/


    }
    public function indexproveedor(Request $request){
        //recupera los proveedores de la empresa que esta inrgresas y hace la busqueda m ediante campos explícitos
        $buscar = $request->buscar;
        $id = $request->id;
        $recupera = DB::select("SELECT * FROM proveedor WHERE (nombre_proveedor LIKE '%$buscar%' OR tipo_identificacion LIKE '%$buscar%' OR cod_proveedor LIKE '%$buscar%') AND id_empresa = $id");
        return $recupera;
    }
    public function listarcuentaslista(Request $request){
        //lista las cuentas de lista dependiendo de la empresa y la variable de de busqueda que se ingrese
        $id = $request->id;
        $buscar = str_replace(array(" "), "%", $request->buscar);
        //$id=50;
        /*$contador = DB::select("SELECT COUNT(*) AS contador FROM ctas_pagar_pagos cop INNER JOIN proveedor cl ON cl.id_proveedor = cop.id_proveedor LEFT JOIN forma_pagos fp ON fp.id_forma_pagos = cop.id_forma_pagos LEFT JOIN forma_pagos_sri fps ON fps.id_forma_pagos_sri = fp.id_forma_pagos_sri INNER JOIN empresa em ON em.id_empresa = cl.id_empresa WHERE em.id_empresa = $id");
        $valor_contador = $contador[0]->contador; */
        $valor = DB::select("SELECT cop.id_ctas_pagar_pagos AS contador, cop.*, cl.nombre_proveedor AS nombreproveedor, fp.descripcion AS descripcionsri,if(fp.descripcion like '%Cheque%',if(fp.descripcion like '%Pichincha%','Pichincha','Internacional'),0) as cheque, IF(fecha_registro IS NULL, fecha_pago, fecha_registro) as fechageneral 
                            FROM ctas_pagar_pagos cop 
                            INNER JOIN proveedor cl ON cl.id_proveedor = cop.id_proveedor 
                            LEFT JOIN forma_pagos fp ON fp.id_forma_pagos = cop.id_forma_pagos 
                            LEFT JOIN forma_pagos_sri fps ON fps.id_forma_pagos_sri = fp.id_forma_pagos_sri 
                            INNER JOIN empresa em ON em.id_empresa = cl.id_empresa WHERE em.id_empresa = $id AND (pagos_por like '%$buscar%' OR nro_tarjeta like '%$buscar%' OR referencia like '%$buscar%' OR valor_seleccionado like '%$buscar%' OR valor_real_pago like '%$buscar%' OR cl.nombre_proveedor like '%$buscar%' OR fp.descripcion like '%$buscar%') and cop.nota_credito is null ORDER BY fechageneral DESC");
        for($i=0; $i<count($valor); $i++){
            $valor[$i]->referencia = explode(";", $valor[$i]->referencia);
            //$valor[$i]->contador = $valor_contador--;
        }
        return $valor;
    }
    function llamartablavalores(Request $rq){
        //lama la cuenta por pagar mediante su id y obtiene el id del proveedor para llamar sus datos
        // $ref = $rq->referencia[1];
        // $provs = DB::select("SELECT * FROM ctas_pagar WHERE id_ctaspagar = $ref");
        // $prov = $provs[0]->id_proveedor;
        // $recupera = DB::select("SELECT *, (select nombre_proveedor from proveedor where id_proveedor = ctas_pagar.id_proveedor) as nombre, (select descripcion from factura_compra where id_factcompra = ctas_pagar.id_factura_compra) as clave_acceso, referencias AS  id_factura2, CONCAT('001-001-',LPAD(referencias,9,'0')) AS  clave_acceso2 from ctas_pagar where ctas_pagar.id_proveedor = $prov");
        // return $recupera;

        $ref = $rq->referencia[1];
        if(strpos($ref,'pp:')!==false){
            $ref=substr($ref,3,strlen($ref));
        }
        $proveedors = DB::select("SELECT * FROM ctas_pagar WHERE id_ctaspagar = $ref");
        $pagos=DB::select("SELECT * from ctas_pagar_pagos where id_ctas_pagar_pagos={$rq->id_ctas_pagar_pagos}");
        $array_ctas=[];
        for($x=0;$x<count($pagos);$x++){
            if(strpos($pagos[$x]->referencia,';')!==false && $pagos[$x]->pago_anticipo==null){
                $data = explode(";", $pagos[$x]->referencia);
                $registros = count($data)/4;
                $salto = 0;
                for($f=0; $f<$registros; $f++){
                    $id_cb = $data[1+$salto];
                    $valor = $data[2+$salto];
                    $idf = $data[3+$salto];
                    if($id_cb){
                        //actualiza los valores de cuentas por cobrar el valor pagado
                    array_push($array_ctas,$id_cb);
                    }
                    //hace el salto de los 4
                    $salto = $salto + 4;
                }
            }
            
        }
        $array_ctas=implode(",",$array_ctas);
        $proveedor = $proveedors[0]->id_proveedor;
        // if($array_ctas){
        //     $recupera = DB::select("SELECT *, (select nombre from proveedor where id_proveedor = ctas_pagar.id_proveedor) as nombre, (select clave_acceso from factura where id_factura = ctas_pagar.id_factura) as clave_acceso, referencias AS  id_factura2, CONCAT('001-001-',LPAD(referencias,9,'0')) AS  clave_acceso2 from ctas_pagar where ctas_pagar.id_proveedor = $proveedor AND ctas_pagar.tipo = 1 AND ctas_pagar.id_ctaspagar in ({$array_ctas})");
        // }else{
        //     $recupera = DB::select("SELECT *, (select nombre from proveedor where id_proveedor = ctas_pagar.id_proveedor) as nombre, (select clave_acceso from factura where id_factura = ctas_pagar.id_factura) as clave_acceso, referencias AS  id_factura2, CONCAT('001-001-',LPAD(referencias,9,'0')) AS  clave_acceso2 from ctas_pagar where ctas_pagar.id_proveedor = $proveedor AND ctas_pagar.tipo = 1");
        // }
        if($pagos[0]->pago_anticipo!==null){
            $query="SELECT  cta.id_ctaspagar,ctap.referencia,if(ctap.fecha_registro is null,ctap.fecha_pago,ctap.fecha_registro) as fecha_pago,cta.num_cuota,(select nombre_proveedor from proveedor where id_proveedor = cta.id_proveedor) as nombre,cta.posicion as clave_acceso,null as id_factura2,null as clave_acceso2,
            if(round(SUBSTRING(ctap.referencia,POSITION(concat(';pp:',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2,locate(';',ctap.referencia,POSITION(concat(';pp:',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2)-(POSITION(concat(';pp:',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2)),2)>ctap.valor_real_pago,ctap.valor_real_pago,round(SUBSTRING(ctap.referencia,POSITION(concat(';pp:',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2,locate(';pp:',ctap.referencia,POSITION(concat(';pp:',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2)-(POSITION(concat(';pp:',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2)),2)) as valor_cuota,0 as valor_pagado,null as id_factura_compra,ctap.pago_anticipo
                    from ctas_pagar_pagos as ctap,ctas_pagar as cta where ctap.id_proveedor={$proveedor} and cta.id_proveedor={$proveedor} and ctap.id_ctas_pagar_pagos={$rq->id_ctas_pagar_pagos} and ctap.referencia like CONCAT('%;pp:',cta.id_ctaspagar,';%')";
        }else{
            $query="SELECT  cta.id_ctaspagar,ctap.referencia,if(ctap.fecha_registro is null,ctap.fecha_pago,ctap.fecha_registro) as fecha_pago,cta.num_cuota,(select nombre_proveedor from proveedor where id_proveedor = cta.id_proveedor) as nombre,(select descripcion from factura_compra where id_factcompra = cta.id_factura_compra) as clave_acceso, referencias AS  id_factura2, CONCAT('001-001-',LPAD(referencias,9,'0')) AS  clave_acceso2 ,
            if(round(SUBSTRING(ctap.referencia,POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2,locate(';',ctap.referencia,POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2)-(POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2)),2)>ctap.valor_real_pago,ctap.valor_real_pago,round(SUBSTRING(ctap.referencia,POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2,locate(';',ctap.referencia,POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2)-(POSITION(concat(';',cta.id_ctaspagar,';') in ctap.referencia)+LENGTH(cta.id_ctaspagar)+2)),2)) as valor_cuota,0 as valor_pagado,cta.id_factura_compra,ctap.pago_anticipo
            from ctas_pagar_pagos as ctap,ctas_pagar as cta where ctap.id_proveedor={$proveedor} and cta.id_proveedor={$proveedor} and ctap.id_ctas_pagar_pagos={$rq->id_ctas_pagar_pagos} and ctap.referencia like CONCAT('%;',cta.id_ctaspagar,';%')";
        }
        
        //dd($query);
        $recupera=DB::select($query);
        return $recupera;
    }
    function guardar_edicion_pago_compra(Request $rq){
        $data = $rq;
        $fecha_registro = $rq->fecha_registro;
        $dsco = 0;
        $for_pago=DB::select("SELECT * FROM forma_pagos where id_forma_pagos={$data["id_forma_pagos"]}");
        //verifica si es un pago de anticipo o no
        if($data["exist_pago_anticipo"]==true){
            //verifica si existe un descuento del pago
            if(isset($rq->descuento_pago)){
                $dsco = $rq->descuento_pago;
            }
            $valor_pagar = $rq->valor_real_pago + $dsco;
            //guarda la fecha actual del servidor
            $hoy = Carbon::now();
            
            //edita la cuenta por pagar de pagos
            $cxcp = Ctas_pagar_pagos::find($data["id_ctas_pagar_pagos"]);
            $cxcp->pagos_por = $data["pagos_por"];
            $cxcp->nro_tarjeta = $data["nro_tarjeta"];
            $cxcp->valor_seleccionado = $data["valor_seleccionado"];
            $cxcp->descuento_porcentaje = $data["descuento_porcentaje"];
            $cxcp->descuento_pago = $data["descuento_pago"];
            $cxcp->valor_real_pago = $data["valor_real_pago"];
            $cxcp->id_forma_pagos = $data["id_forma_pagos"];
            $cxcp->id_banco = $data["id_banco"];
            $cxcp->fecha_registro = $data["fecha_pago"];
            $cxcp->umodifica = $data["id_user"];
            //$cxcp->posicion = $conteo;
            //$cxcp->fecha_registro = $data["fecha_pago"];
            $cxcp->save();

            //recupera el id de la cuenta de pagos
            $idcxcp = $data["id_ctas_pagar_pagos"];
            $referencia = null;
            // for($c=0; $c<count($rq->contenido); $c++){
            //     if(isset($rq->contenido[$c]["agregar"])){
            //         if($rq->contenido[$c]["agregar"]){
            //             //cuando se va a hacer varios pagos o pagos parciales entrara a este if y restara el valor_pagar con el valor
            //             //caso contrario cuando el pago es menoro igual al saldo entra a else y genera el guardado y guarda directamente en el su Ctas_pagar_pagos
            //             if($valor_pagar > ($rq->contenido[$c]["valor_cuota"] - $rq->contenido[$c]["valor_pagado"])){
            //                 $pago = Cuentaporpagar::findOrFail($rq->contenido[$c]["id_ctaspagar"]);
            //                 $pago->pagos_por = $rq->pagos_por;
            //                 $pago->id_forma_pagos = $rq->forma_pago;
            //                 $pago->id_banco = $rq->banco;
            //                 $pago->numero_tarjeta = $rq->numero_tarjeta;
            //                 $pago->descuento = $pago->descuento +$rq->descuento_pago;
            //                 //$pago->valor_pagado = $pago->valor_pagado + $rq->contenido[$c]["valor_cuota"] - $rq->contenido[$c]["valor_pagado"];
            //                 $pagado_cta=$pago->valor_pagado - ($rq->contenido[$c]["valor_cuota"] - $rq->contenido[$c]["valor_pagado"]);
            //                 if($pago->valor_pagado<$pago->valor_cuota){
            //                     $pago->valor_pagado=$pago->valor_pagado+$pagado_cta; 
            //                 }
                            
            //                 $pago->fecha_registro = $fecha_registro;
            //                 $pago->save();
            //                 $v_cuota=$rq->contenido[$c]["valor_cuota"] - $rq->contenido[$c]["valor_pagado"];
            //                 $valor_pagar = $valor_pagar - ($rq->contenido[$c]["valor_cuota"] - $rq->contenido[$c]["valor_pagado"]);
            //                 $valor_pagado=($rq->contenido[$c]["valor_cuota"] - $rq->contenido[$c]["valor_pagado"]);
            //                 //aqui guarda un string concatenado por clave de acceso, id_ctaspagar, valor_pagado y la id_factura de cuentaporpagar
            //                 if(isset($rq->contenido[$c]["id_factura_compra"])){
            //                     $referencia .= substr($rq->contenido[$c]["clave_acceso"],0,3)."-".substr($rq->contenido[$c]["clave_acceso"],3,3)."-".substr($rq->contenido[$c]["clave_acceso"],6,9) . ";" . $rq->contenido[$c]["id_ctaspagar"] . ";" . number_format($valor_pagado,2,".","") . ";" . $rq->contenido[$c]["id_factura_compra"]. ";";
            //                 }else{
            //                     if(isset($rq->contenido[$c]["id_liquidacion_compra"])){
            //                         $referencia .= substr($rq->contenido[$c]["clave_acceso"],0,3)."-".substr($rq->contenido[$c]["clave_acceso"],3,3)."-".substr($rq->contenido[$c]["clave_acceso"],6,9) . ";" . $rq->contenido[$c]["id_ctaspagar"] . ";" . number_format($valor_pagado,2,".","") . ";lc:" . $rq->contenido[$c]["id_liquidacion_compra"]. ";";
            //                     }else{
            //                         $referencia .= $rq->contenido[$c]["clave_acceso2"] . ";" . $rq->contenido[$c]["id_ctascobrar"] . ";" . number_format($valor_pagado,2,".","") . ";" . $rq->contenido[$c]["id_factura2"]. ";";
            //                     }
                                
            //                 }
            //             }else{
            //                 $pago = Cuentaporpagar::findOrFail($rq->contenido[$c]["id_ctaspagar"]);
            //                 $pago->pagos_por = $rq->pagos_por;
            //                 $pago->id_forma_pagos = $rq->forma_pago;
            //                 $pago->id_banco = $rq->banco;
            //                 $pago->numero_tarjeta = $rq->numero_tarjeta;
            //                 $pago->descuento = $pago->descuento + $rq->descuento_pago;
            //                 // $pago->valor_pagado = $pago->valor_pagado + $valor_pagar;
            //                 // $pago->fecha_registro = $fecha_registro;
            //                 $pagado_cta=$valor_pagar - ($rq->contenido[$c]["valor_cuota"] - $rq->contenido[$c]["valor_pagado"]);
            //                 // if($pago->valor_pagado<$pago->valor_cuota){
            //                 //     $pago->valor_pagado=$pago->valor_pagado+$pagado_cta;
            //                 // }else{
            //                 //     $pago->valor_pagado=16;
            //                 // }//
            //                 $pago->valor_pagado=$pago->valor_pagado+$pagado_cta;
            //                 $pago->fecha_registro = $rq->fecha_registro;
            //                 $pago->save();
            //                 //$valor_pagar = $valor_pagar - ($rq->contenido[$c]["valor_cuota"] - $rq->contenido[$c]["valor_pagado"]);
            //                 //aqui guarda un string concatenado por clave de acceso, id_ctaspagar, valor_pagado y la id_factura de cuentaporpagar
            //                 if(isset($rq->contenido[$c]["id_factura_compra"])){
            //                     $referencia .= substr($rq->contenido[$c]["clave_acceso"],0,3)."-".substr($rq->contenido[$c]["clave_acceso"],3,3)."-".substr($rq->contenido[$c]["clave_acceso"],6,9) . ";" . $rq->contenido[$c]["id_ctaspagar"] . ";" . number_format($valor_pagar,2,".",""). ";" . $rq->contenido[$c]["id_factura_compra"]. ";";
            //                 }else{
            //                     if(isset($rq->contenido[$c]["id_liquidacion_compra"])){
            //                         $referencia .= substr($rq->contenido[$c]["clave_acceso"],0,3)."-".substr($rq->contenido[$c]["clave_acceso"],3,3)."-".substr($rq->contenido[$c]["clave_acceso"],6,9) . ";" . $rq->contenido[$c]["id_ctaspagar"] . ";" . number_format($valor_pagar,2,".",""). ";lc:" . $rq->contenido[$c]["id_liquidacion_compra"]. ";";
            //                     }else{
            //                         $referencia .= $rq->contenido[$c]["clave_acceso2"] . ";" . $rq->contenido[$c]["id_ctascobrar"] . ";" . number_format($valor_pagar,2,".","") . ";" . $rq->contenido[$c]["id_factura2"]. ";";
            //                     }
                                
            //                 }
            //                 //nuevamente edita los pagos de cuentas por pagar y agrega la referencia de cuentaporpagar separado por ;
            //                 $ref = substr($referencia,0,-1);
            //                 $cxcp = Ctas_pagar_pagos::findOrFail($idcxcp);
            //                 $cxcp->referencia = $ref;
            //                 $cxcp->save();
            //                 return;
            //             }
            //         }
            //     }
            // }
            //nuevamente edita los pagos de cuentas por pagar y agrega la referencia de cuentaporpagar separado por ;
            // $ref = substr($referencia,0,-1);
            // $cxcp = Ctas_pagar_pagos::findOrFail($idcxcp);
            // $cxcp->referencia = $ref;
            // $cxcp->save();
        }else{
            //verifica si existe un descuento del pago
            if(isset($rq->descuento_pago)){
                $dsco = $rq->descuento_pago;
            }
            $valor_pagar = $rq->valor_real_pago + $dsco;
            //guarda la fecha actual del servidor
            $hoy = Carbon::now();
            
            //edita la cuenta por pagar de pagos
            $cxcp = Ctas_pagar_pagos::find($data["id_ctas_pagar_pagos"]);
            $cxcp->pagos_por = $data["pagos_por"];
            $cxcp->nro_tarjeta = $data["nro_tarjeta"];
            $cxcp->valor_seleccionado = $data["valor_seleccionado"];
            $cxcp->descuento_porcentaje = $data["descuento_porcentaje"];
            $cxcp->descuento_pago = $data["descuento_pago"];
            $cxcp->valor_real_pago = $data["valor_real_pago"];
            $cxcp->id_forma_pagos = $data["id_forma_pagos"];
            $cxcp->id_banco = $data["id_banco"];
            $cxcp->fecha_registro = $data["fecha_pago"];
            $cxcp->umodifica = $data["id_user"];
            //$cxcp->posicion = $conteo;
            //$cxcp->fecha_registro = $data["fecha_pago"];
            $cxcp->save();

            //recupera el id de la cuenta de pagos
            $idcxcp = $data["id_ctas_pagar_pagos"];
            $referencia = null;
            for($c=0; $c<count($rq->contenido); $c++){
                if(isset($rq->contenido[$c]["agregar"])){
                    if($rq->contenido[$c]["agregar"]){
                        //cuando se va a hacer varios pagos o pagos parciales entrara a este if y restara el valor_pagar con el valor
                        //caso contrario cuando el pago es menoro igual al saldo entra a else y genera el guardado y guarda directamente en el su Ctas_pagar_pagos
                        if($valor_pagar > ($rq->contenido[$c]["valor_cuota"] - $rq->contenido[$c]["valor_pagado"])){
                            $pago = Cuentaporpagar::findOrFail($rq->contenido[$c]["id_ctaspagar"]);
                            $pago->pagos_por = $rq->pagos_por;
                            $pago->id_forma_pagos = $rq->forma_pago;
                            $pago->id_banco = $rq->banco;
                            $pago->numero_tarjeta = $rq->numero_tarjeta;
                            $pago->descuento = $pago->descuento +$rq->descuento_pago;
                            //$pago->valor_pagado = $pago->valor_pagado + $rq->contenido[$c]["valor_cuota"] - $rq->contenido[$c]["valor_pagado"];
                            $pagado_cta=$pago->valor_pagado - ($rq->contenido[$c]["valor_cuota"] - $rq->contenido[$c]["valor_pagado"]);
                            if($pago->valor_pagado<$pago->valor_cuota){
                                $pago->valor_pagado=$pago->valor_pagado+$pagado_cta; 
                            }
                            
                            $pago->fecha_registro = $fecha_registro;
                            $pago->save();
                            $v_cuota=$rq->contenido[$c]["valor_cuota"] - $rq->contenido[$c]["valor_pagado"];
                            $valor_pagar = $valor_pagar - ($rq->contenido[$c]["valor_cuota"] - $rq->contenido[$c]["valor_pagado"]);
                            $valor_pagado=($rq->contenido[$c]["valor_cuota"] - $rq->contenido[$c]["valor_pagado"]);
                            //aqui guarda un string concatenado por clave de acceso, id_ctaspagar, valor_pagado y la id_factura de cuentaporpagar
                            if(isset($rq->contenido[$c]["id_factura_compra"])){
                                $referencia .= substr($rq->contenido[$c]["clave_acceso"],0,3)."-".substr($rq->contenido[$c]["clave_acceso"],3,3)."-".substr($rq->contenido[$c]["clave_acceso"],6,9) . ";" . $rq->contenido[$c]["id_ctaspagar"] . ";" . number_format($valor_pagado,2,".","") . ";" . $rq->contenido[$c]["id_factura_compra"]. ";";
                            }else{
                                if(isset($rq->contenido[$c]["id_liquidacion_compra"])){
                                    $referencia .= substr($rq->contenido[$c]["clave_acceso"],0,3)."-".substr($rq->contenido[$c]["clave_acceso"],3,3)."-".substr($rq->contenido[$c]["clave_acceso"],6,9) . ";" . $rq->contenido[$c]["id_ctaspagar"] . ";" . number_format($valor_pagado,2,".","") . ";lc:" . $rq->contenido[$c]["id_liquidacion_compra"]. ";";
                                }else{
                                    $referencia .= $rq->contenido[$c]["clave_acceso2"] . ";" . $rq->contenido[$c]["id_ctascobrar"] . ";" . number_format($valor_pagado,2,".","") . ";" . $rq->contenido[$c]["id_factura2"]. ";";
                                }
                                
                            }
                        }else{
                            $pago = Cuentaporpagar::findOrFail($rq->contenido[$c]["id_ctaspagar"]);
                            $pago->pagos_por = $rq->pagos_por;
                            $pago->id_forma_pagos = $rq->forma_pago;
                            $pago->id_banco = $rq->banco;
                            $pago->numero_tarjeta = $rq->numero_tarjeta;
                            $pago->descuento = $pago->descuento + $rq->descuento_pago;
                            // $pago->valor_pagado = $pago->valor_pagado + $valor_pagar;
                            // $pago->fecha_registro = $fecha_registro;
                            $pagado_cta=$valor_pagar - ($rq->contenido[$c]["valor_cuota"] - $rq->contenido[$c]["valor_pagado"]);
                            // if($pago->valor_pagado<$pago->valor_cuota){
                            //     $pago->valor_pagado=$pago->valor_pagado+$pagado_cta;
                            // }else{
                            //     $pago->valor_pagado=16;
                            // }//
                            $pago->valor_pagado=$pago->valor_pagado+$pagado_cta;
                            $pago->fecha_registro = $rq->fecha_registro;
                            $pago->save();
                            //$valor_pagar = $valor_pagar - ($rq->contenido[$c]["valor_cuota"] - $rq->contenido[$c]["valor_pagado"]);
                            //aqui guarda un string concatenado por clave de acceso, id_ctaspagar, valor_pagado y la id_factura de cuentaporpagar
                            if(isset($rq->contenido[$c]["id_factura_compra"])){
                                $referencia .= substr($rq->contenido[$c]["clave_acceso"],0,3)."-".substr($rq->contenido[$c]["clave_acceso"],3,3)."-".substr($rq->contenido[$c]["clave_acceso"],6,9) . ";" . $rq->contenido[$c]["id_ctaspagar"] . ";" . number_format($valor_pagar,2,".",""). ";" . $rq->contenido[$c]["id_factura_compra"]. ";";
                            }else{
                                if(isset($rq->contenido[$c]["id_liquidacion_compra"])){
                                    $referencia .= substr($rq->contenido[$c]["clave_acceso"],0,3)."-".substr($rq->contenido[$c]["clave_acceso"],3,3)."-".substr($rq->contenido[$c]["clave_acceso"],6,9) . ";" . $rq->contenido[$c]["id_ctaspagar"] . ";" . number_format($valor_pagar,2,".",""). ";lc:" . $rq->contenido[$c]["id_liquidacion_compra"]. ";";
                                }else{
                                    $referencia .= $rq->contenido[$c]["clave_acceso2"] . ";" . $rq->contenido[$c]["id_ctascobrar"] . ";" . number_format($valor_pagar,2,".","") . ";" . $rq->contenido[$c]["id_factura2"]. ";";
                                }
                                
                            }
                            //nuevamente edita los pagos de cuentas por pagar y agrega la referencia de cuentaporpagar separado por ;
                            $ref = substr($referencia,0,-1);
                            $cxcp = Ctas_pagar_pagos::findOrFail($idcxcp);
                            $cxcp->referencia = $ref;
                            $cxcp->save();
                            return;
                        }
                    }
                }
            }
            //nuevamente edita los pagos de cuentas por pagar y agrega la referencia de cuentaporpagar separado por ;
            $ref = substr($referencia,0,-1);
            $cxcp = Ctas_pagar_pagos::findOrFail($idcxcp);
            $cxcp->referencia = $ref;
            $cxcp->save();
        }
        //dd($rq);
        
        return;
    }
    public function eliminarcxc($id){
        //selecciona las cuentas por pagar pagos dependiendo su id
        $select = DB::select("SELECT * FROM ctas_pagar_pagos WHERE id_ctas_pagar_pagos = $id");
        if(isset($select)){
            $pago_anticipo=$select[0]->pago_anticipo;
            for($i=0; $i<count($select); $i++){
                if(strpos($select[$i]->referencia,";")!==false){
                    $pago_anticipo=$select[$i]->pago_anticipo;
                    //return "es un string {$select[$i]->referencia}";
                    //obtiene la referencia y lo conierte en array dividido de ;
                    $data = explode(";", $select[$i]->referencia);
                    //devide los registros en 4 ya que son 4 los reg existentes e referencias
                    $registros = count($data)/4;
                    $salto = 0;
                    //recorre las referencias por 4 para obtener todos los registros existentes
                    for($f=0; $f<$registros; $f++){
                        $id_cb = $data[1+$salto];
                        $valor = $data[2+$salto];
                        $idf = $data[3+$salto];
                        if($id_cb && $valor){
                            if($pago_anticipo!==null){
                                $val=substr($id_cb,3,strlen($id_cb));
                                //actualiza el valor pagado del anticipo pagado
                                DB::update("UPDATE ctas_pagar SET abono = abono + $valor,valor_pagado = valor_pagado - $valor WHERE id_ctaspagar = $val and tipo=3");
                            }else{
                                //actualiza el valor pagado de la cuenta borrada
                                DB::update("UPDATE ctas_pagar SET valor_pagado = valor_pagado - $valor WHERE id_ctaspagar = $id_cb");
                            }
                            
                        }
                        //hace el salto de 4
                        $salto = $salto + 4;
                    }
                    
                }else{
                    //borra el anticipo
                    DB::delete("DELETE FROM ctas_pagar WHERE id_ctaspagar = {$select[$i]->referencia} and tipo=3");
                }
                
            }
        }
        //return;
        //borra los registros de los pagos despues de revertir lospagos hechos de este pago
        DB::delete("DELETE FROM ctas_pagar_pagos WHERE id_ctas_pagar_pagos = $id");
    }
    public function guardarpagar(Request $request)
    {
        //dd($request);
        //guarda la fecha actual del servidor
        $fecha_registro_pago = $request->fecha_registro_pago;
        $hoy = Carbon::now();
        $for_pago=DB::select("SELECT * FROM proveedor where id_proveedor={$request->proveedor["id_proveedor"]}");
        $pos0=DB::select("SELECT max(id_ctas_pagar_pagos) as posicion from ctas_pagar_pagos INNER JOIN proveedor ON proveedor.id_proveedor=ctas_pagar_pagos.id_proveedor where proveedor.id_empresa={$for_pago[0]->id_empresa}");
        if(count($pos0)>0){
            $pos=DB::select("SELECT count(id_ctas_pagar_pagos) as posicion from ctas_pagar_pagos INNER JOIN proveedor ON proveedor.id_proveedor=ctas_pagar_pagos.id_proveedor where proveedor.id_empresa={$for_pago[0]->id_empresa}");
        }else{
            $pos=[];
        }
        $conteo=1;
        if(count($pos)>0){
            $conteo=$pos[0]->posicion+1;
        }
        //verifica si el pago es diferente de anticipo
        if (!$request->cproveedor["anticipo"]) {
            $factura = $request->cproveedor['factura'];
            $data = DB::select("SELECT * FROM factura_compra WHERE nro_autorizacion LIKE '%$factura%' where id_empresa={$request->id_empresa}");
            //si la factura no existe reenvia error
            if (!count($data)) {
                return 'error';
            }
            //recupera el id de factura
            $id_factura = $data[0]->id_factcompra;
            //recorre los plazos existentes y guarda los registros saltandose por plazos agregados
            for ($a = 0; $a < $request->cproveedor["plazo"]; $a++) {
                $cxc = new Cuentaporpagar();
                $cxc->num_cuota = $a + 1;
                if ($a < 1) {
                    //dependiendo del tipo de plazo añade el tiempo a la fecha y lo guarda
                    if ($request->cproveedor["periodo"] == "Años") {
                        $fecharec = $hoy->addYears($request->cproveedor["tiempos"]);
                        $fd = $hoy->addYears($request->cproveedor["tiempos"])->format('Y-m-d');
                    } else if ($request->cproveedor["periodo"] == "Meses") {
                        $fecharec = $hoy->addMonths($request->cproveedor["tiempos"]);
                        $fd = $hoy->addMonths($request->cproveedor["tiempos"])->format('Y-m-d');
                    } else if ($request->cproveedor["periodo"] == "Semanas") {
                        $fecharec = $hoy->addWeeks($request->cproveedor["tiempos"]);
                        $fd = $hoy->addWeeks($request->cproveedor["tiempos"])->format('Y-m-d');
                    } else {
                        $fecharec = $hoy->addDays($request->cproveedor["tiempos"]);
                        $fd = $hoy->addDays($request->cproveedor["tiempos"])->format('Y-m-d');
                    }
                } else {
                    //dependiendo del tipo de plazo añade el tiempo a la fecha y lo guarda, suma de la fecha ya agregada lo suma nuevamente esto es para los plazos mayores a dos
                    if ($request->cproveedor["periodo"] == "Años") {
                        $fd = $fecharec->addYears($request->cproveedor["tiempos"])->format('Y-m-d');
                    } else if ($request->cproveedor["periodo"] == "Meses") {
                        $fd = $fecharec->addMonths($request->cproveedor["tiempos"])->format('Y-m-d');
                    } else if ($request->cproveedor["periodo"] == "Semanas") {
                        $fd = $fecharec->addWeeks($request->cproveedor["tiempos"])->format('Y-m-d');
                    } else {
                        $fd = $fecharec->addDays($request->cproveedor["tiempos"])->format('Y-m-d');
                    }
                }
                //guarda los demas registros
                $cxc->fecha_pago = $fd;
                $cxc->periodo_pagos = $request->cproveedor["periodo"];
                $cxc->numero_transaccion = $request->cproveedor["transaccion"];
                $cxc->valor_cuota = round($request->cproveedor["monto"] / $request->cproveedor["plazo"], 2, PHP_ROUND_HALF_UP);
                $cxc->valor_pagado = 0;
                $cxc->estado = 1;
                $cxc->tipo = 1;
                $cxc->id_factura_compra = $id_factura;
                $cxc->id_proveedor = $request->proveedor["id_proveedor"];
                $cxc->save();
            }
        } else {
            // //recupera valores del pago si es anticipo
            // $id = $request->proveedor["id_proveedor"];
            // $monto = $request->cproveedor["monto"];
            // $formapago = $request->cproveedor["formapago"];
            // $nrocheque = $request->cproveedor["nrocheque"];
            // //obtiene los anticipos existentes del proveedor y del tipo = 3 que significa que es anticipo
            // $ver = DB::select("SELECT * FROM ctas_pagar WHERE id_proveedor = $id AND tipo = 3");
            // if(count($ver)>=1){
            //     ///si existe actualiza el valor del pago mediante el campo de abono
            //     DB::update("UPDATE ctas_pagar SET abono = abono + $monto, id_forma_pagos = $formapago WHERE id_proveedor = $id AND tipo = 3");
            // }else{
            //     //si no existe crea la cuenta por pagar como tipo = 3 que especifica que se trata de un anticipo
            //     $cxc = new Cuentaporpagar();
            //     $cxc->num_cuota = 1;
            //     $cxc->fecha_pago = $hoy;
            //     $cxc->valor_cuota = $monto;
            //     $cxc->estado=1;
            //     $cxc->tipo = 3;
            //     $cxc->abono = $monto;
            //     $cxc->id_forma_pagos = $formapago;
            //     $cxc->id_proveedor = $id;
            //     if(isset($nrocheque)){
            //         $cxc->numero_transaccion = $nrocheque;
            //     }
            //     $cxc->save();
            // }

            //recupera valores del pago si es anticipo
            $id = $request->proveedor["id_proveedor"];
            $monto = $request->cproveedor["monto"];
            $formapago = $request->cproveedor["formapago"];
            $nrocheque = null;
            if (isset($request->cproveedor["nrocheque"])) {
                $nrocheque = $request->cproveedor["nrocheque"];
            }

            //guarda la cuenta por cobrar como anticipo mediante tipo = 3
            $cxc = new Cuentaporpagar();
            $cxc->num_cuota = 1;
            $cxc->fecha_pago = $hoy;
            $cxc->valor_cuota = $monto;
            $cxc->estado = 1;
            $cxc->tipo = 3;
            $cxc->abono = $monto;
            $cxc->id_forma_pagos = $formapago;
            $cxc->id_proveedor = $id;
            $cxc->fecha_registro = $fecha_registro_pago;
            $cxc->posicion = $conteo;
            if (isset($nrocheque)) {
                $cxc->numero_transaccion = $nrocheque;
            }
            $cxc->save();
            $id_cxc=$cxc->id_ctaspagar;
            //genera cuentas por cobrar pagos y guarda como anticipo para mantener registro del pago
            $cxcp = new Ctas_pagar_pagos();
            $cxcp->pagos_por = "Anticipo";
            $cxcp->valor_seleccionado = $monto;
            $cxcp->valor_real_pago = $monto;
            $cxcp->id_forma_pagos = $formapago;
            $cxcp->fecha_pago = $hoy;
            $cxcp->fecha_registro = $fecha_registro_pago;
            $cxcp->id_proveedor = $id;
            $cxcp->posicion = $conteo;
            //$cxcp->anticipo = 1;
            $cxcp->referencia = $id_cxc;
            //$cxcp->ucrea = $request->id_user;
            $cxcp->save();
            
        }
    }

    public function verAsiento(Request $request,$id){
        $cta_cobrar=DB::select("SELECT cop.*,cl.nombre_proveedor as  nombre,cl.tipo_identificacion,cl.identif_proveedor as identificacion from ctas_pagar_pagos as cop,proveedor as cl where cop.id_proveedor=cl.id_proveedor and cop.id_ctas_pagar_pagos={$id}");
        $proyecto=DB::select("SELECT * from proyecto where id_empresa=".$request->id_empresa." limit 1");
        $nro_facturas=0;
        $nro_ctas=0;
        
            //dd($cta_cobrar);
            if(isset($cta_cobrar)){
                for($i=0; $i<count($cta_cobrar); $i++){
                    $data = explode(";", $cta_cobrar[$i]->referencia);
                    $registros = count($data)/4;
                    $salto = 0;
                    for($f=0; $f<$registros; $f++){
                        $id_cb = $data[1+$salto];
                        if($id_cb){
                            $nro_ctas++;
                        }
                        $valor = $data[2+$salto];
                        $idf = $data[3+$salto];
                        if($idf){
                            $nro_facturas++;
                        }

                        $salto = $salto + 4;
                    }
                }
            }
            $array_id_factura=[];

            //dd($array_id_factura);
            $id_facturas=[];
            $id_ctas=[];
            $id_notas_venta=[];
            if(isset($cta_cobrar)){
                for($i=0; $i<count($cta_cobrar); $i++){
                    $data = explode(";", $cta_cobrar[$i]->referencia);
                    $registros = count($data)/4;
                    $salto = 0;
                    for($f=0; $f<$registros; $f++){
                        $id_cb = $data[1+$salto];
                        if($id_cb){
                            array_push($id_ctas,"ctas_pagar.id_ctaspagar={$id_cb}");
                        }
                        $valor = $data[2+$salto];
                        $idf = $data[3+$salto];
                        if($idf){
                            $pos=strpos($idf,'lc:');
                            if($pos!==false){
                                $number=substr($idf,3);
                                array_push($id_notas_venta,"liquidacion_compra.id_liquidacion_compra={$number}");
                            }else{
                                array_push($id_facturas,"factura_compra.id_factcompra={$idf}");
                            }
                            //$array_factura .= " factura.id_factura={$idf} or";
                            //array_push($id_facturas,"factura_compra.id_factcompra={$idf}");
                        }

                        //array_push($array_id_factura,$cliente);
                        $salto = $salto + 4;
                    }
                }
            }
            $id_notas_venta=implode(" or ",$id_notas_venta);
            $id_facturas=implode(" or ",$id_facturas);
            $id_ctas=implode(" or ",$id_ctas);
            $x_factura="";
            $y_factura="";
            $a="";
            $b="";
            $x_nota_venta="";
            $y_nota_venta="";
            $c="";
            $d="";
            if($id_facturas){
                $y_factura=" and ";
                $a="(";
                $b=")";
            }else{
                $x_factura=" and factura_compra.id_factcompra=0 ";
            }
            if($id_notas_venta){
                $y_nota_venta=" and ";
                $c="(";
                $d=")";
            }else{
                $x_nota_venta=" and liquidacion_compra.id_liquidacion_compra=0 ";
            }
            if($id_facturas || $id_notas_venta){
                $array_factura = "SELECT if(proveedor.id_plan_cuentas is null,'no','si') as exist_plan_cuenta_proveedor,proveedor.id_plan_cuentas as id_plan_cuentas_proveedor,
                    (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(proveedor.id_plan_cuentas is null,0,proveedor.id_plan_cuentas) and id_empresa={$request->id_empresa}) as nombre_cuenta_proveedor,grupo_proveedor.id_plan_cuentas as id_plan_cuentas_grupo,
                    (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(grupo_proveedor.id_plan_cuentas is null,0,grupo_proveedor.id_plan_cuentas) and id_empresa={$request->id_empresa}) as nombre_cuenta_grupo,ctas_pagar_pagos.valor_real_pago as debe_talves,null as haber,proyecto.id_proyecto,
                    proyecto.descripcion,factura_compra.id_factcompra,round(detalle_factura_compra.total/factura_compra.subtotal_sin_impuesto,2) as porcentaje,round(ctas_pagar_pagos.valor_real_pago/{$nro_ctas}*(detalle_factura_compra.total/factura_compra.subtotal_sin_impuesto),2) as debe,plan_cuentas.bansel
                    from proveedor
                    LEFT JOIN grupo_proveedor
                    on grupo_proveedor.id_grupoprov=proveedor.id_grupo_proveedor
                    LEFT JOIN plan_cuentas
                    on plan_cuentas.id_plan_cuentas=grupo_proveedor.id_plan_cuentas
                    INNER JOIN ctas_pagar_pagos
                    on ctas_pagar_pagos.id_proveedor=proveedor.id_proveedor
                    INNER JOIN factura_compra
                    on factura_compra.id_proveedor=proveedor.id_proveedor
                    INNER JOIN detalle_factura_compra
                    on detalle_factura_compra.id_factura=factura_compra.id_factcompra
                    LEFT JOIN proyecto
                    on proyecto.id_proyecto=detalle_factura_compra.id_proyecto
                    where ctas_pagar_pagos.id_ctas_pagar_pagos={$id} {$y_factura} {$a}$id_facturas{$b} {$x_factura}
                    UNION
                    SELECT if(proveedor.id_plan_cuentas is null,'no','si') as exist_plan_cuenta_proveedor,proveedor.id_plan_cuentas as id_plan_cuentas_proveedor,
                    (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(proveedor.id_plan_cuentas is null,0,proveedor.id_plan_cuentas) and id_empresa={$request->id_empresa}) as nombre_cuenta_proveedor,grupo_proveedor.id_plan_cuentas as id_plan_cuentas_grupo,
                    (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(grupo_proveedor.id_plan_cuentas is null,0,grupo_proveedor.id_plan_cuentas) and id_empresa={$request->id_empresa}) as nombre_cuenta_grupo,ctas_pagar_pagos.valor_real_pago as debe_talves,null as haber,proyecto.id_proyecto,
                    proyecto.descripcion,liquidacion_compra.id_liquidacion_compra,round(detalle_liquidacion_compra.total/liquidacion_compra.subtotal_sin_impuesto,2) as porcentaje,round(ctas_pagar_pagos.valor_real_pago/{$nro_ctas}*(detalle_liquidacion_compra.total/liquidacion_compra.subtotal_sin_impuesto),2) as debe,plan_cuentas.bansel
                    from proveedor
                    LEFT JOIN grupo_proveedor
                    on grupo_proveedor.id_grupoprov=proveedor.id_grupo_proveedor
                    LEFT JOIN plan_cuentas
                    on plan_cuentas.id_plan_cuentas=grupo_proveedor.id_plan_cuentas
                    INNER JOIN ctas_pagar_pagos
                    on ctas_pagar_pagos.id_proveedor=proveedor.id_proveedor
                    INNER JOIN liquidacion_compra
                    on liquidacion_compra.id_proveedor=proveedor.id_proveedor
                    INNER JOIN detalle_liquidacion_compra
                    on detalle_liquidacion_compra.id_liquidacion_compra=liquidacion_compra.id_liquidacion_compra
                    LEFT JOIN proyecto
                    on proyecto.id_proyecto=detalle_liquidacion_compra.id_proyecto
                    where ctas_pagar_pagos.id_ctas_pagar_pagos={$id} {$y_nota_venta} {$c}$id_notas_venta{$d} {$x_nota_venta}";
            }else{
                $array_factura = "SELECT if(proveedor.id_plan_cuentas is null,'no','si') as exist_plan_cuenta_proveedor,proveedor.id_plan_cuentas as id_plan_cuentas_proveedor,
                    (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(proveedor.id_plan_cuentas is null,0,proveedor.id_plan_cuentas) and id_empresa=$request->id_empresa) as nombre_cuenta_proveedor,grupo_proveedor.id_plan_cuentas as id_plan_cuentas_grupo,
                    (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(grupo_proveedor.id_plan_cuentas is null,0,grupo_proveedor.id_plan_cuentas) and id_empresa=$request->id_empresa) as nombre_cuenta_grupo,ctas_pagar_pagos.valor_real_pago as debe_talves,null as haber,proyecto.id_proyecto,
                    proyecto.descripcion,factura_compra.id_factcompra,round(detalle_factura_compra.total/factura_compra.subtotal_sin_impuesto,2) as porcentaje,round(ctas_pagar_pagos.valor_real_pago/{$nro_ctas}*(detalle_factura_compra.total/factura_compra.subtotal_sin_impuesto),2) as debe,plan_cuentas.bansel
                    from proveedor
                    LEFT JOIN grupo_proveedor
                    on grupo_proveedor.id_grupoprov=proveedor.id_grupo_proveedor
                    LEFT JOIN plan_cuentas
                    on plan_cuentas.id_plan_cuentas=grupo_proveedor.id_plan_cuentas
                    INNER JOIN ctas_pagar_pagos
                    on ctas_pagar_pagos.id_proveedor=proveedor.id_proveedor
                    INNER JOIN factura_compra
                    on factura_compra.id_proveedor=proveedor.id_proveedor
                    INNER JOIN detalle_factura_compra
                    on detalle_factura_compra.id_factura=factura_compra.id_factcompra
                    LEFT JOIN proyecto
                    on proyecto.id_proyecto=detalle_factura_compra.id_proyecto
                    where ctas_pagar_pagos.id_ctas_pagar_pagos={$id} and factura_compra.id_factcompra=0";

            }





            //dd($array_factura);
            $select = DB::select($array_factura);
            if($id_ctas){
                $array_referencia="SELECT if(proveedor.id_plan_cuentas is null,'no','si') as exist_plan_cuenta_proveedor,proveedor.id_plan_cuentas as id_plan_cuentas_proveedor,
                (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(proveedor.id_plan_cuentas is null,0,proveedor.id_plan_cuentas) and id_empresa={$request->id_empresa}) as nombre_cuenta_proveedor,grupo_proveedor.id_plan_cuentas as id_plan_cuentas_grupo,
                (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(grupo_proveedor.id_plan_cuentas is null,0,grupo_proveedor.id_plan_cuentas) and id_empresa={$request->id_empresa}) as nombre_cuenta_grupo,ctas_pagar_pagos.valor_real_pago as debe_talves,null as haber,
                (select id_proyecto from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as id_proyecto,
                (select descripcion from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as descripcion,
                null as id_factcompra,ctas_pagar_pagos.valor_real_pago/{$nro_ctas} as porcentaje,round(ctas_pagar_pagos.valor_real_pago/{$nro_ctas},2) as debe,plan_cuentas.bansel
                                        from proveedor
                LEFT JOIN grupo_proveedor
                on grupo_proveedor.id_grupoprov=proveedor.id_grupo_proveedor
                LEFT JOIN plan_cuentas
                on plan_cuentas.id_plan_cuentas=grupo_proveedor.id_plan_cuentas
                INNER JOIN ctas_pagar_pagos
                on ctas_pagar_pagos.id_proveedor=proveedor.id_proveedor
                INNER JOIN ctas_pagar
                on ctas_pagar.id_proveedor=proveedor.id_proveedor
                where ctas_pagar_pagos.id_ctas_pagar_pagos={$id} and ctas_pagar.referencias is not null and ({$id_ctas})";
            }else{
                $array_referencia="SELECT if(proveedor.id_plan_cuentas is null,'no','si') as exist_plan_cuenta_proveedor,proveedor.id_plan_cuentas as id_plan_cuentas_proveedor,
                (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(proveedor.id_plan_cuentas is null,0,proveedor.id_plan_cuentas) and id_empresa={$request->id_empresa}) as nombre_cuenta_proveedor,grupo_proveedor.id_plan_cuentas as id_plan_cuentas_grupo,
                (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(grupo_proveedor.id_plan_cuentas is null,0,grupo_proveedor.id_plan_cuentas) and id_empresa={$request->id_empresa}) as nombre_cuenta_grupo,ctas_pagar_pagos.valor_real_pago as haber_talves,null as debe,
                (select id_proyecto from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as id_proyecto,
                (select descripcion from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as descripcion,
                null as id_factura,ctas_pagar_pagos.valor_real_pago/{$nro_ctas} as porcentaje,round(ctas_pagar_pagos.valor_real_pago/{$nro_ctas},2) as haber,plan_cuentas.bansel
                                        from proveedor
                LEFT JOIN grupo_proveedor
                on grupo_proveedor.id_grupoprov=proveedor.id_grupo_proveedor
                LEFT JOIN plan_cuentas
                on plan_cuentas.id_plan_cuentas=grupo_proveedor.id_plan_cuentas
                INNER JOIN ctas_pagar_pagos
                on ctas_pagar_pagos.id_proveedor=proveedor.id_proveedor
                INNER JOIN ctas_pagar
                on ctas_pagar.id_proveedor=proveedor.id_proveedor
                where ctas_pagar_pagos.id_ctas_pagar_pagos={$id} and ctas_pagar.referencias is not null and ctas_pagar.id_ctaspagar=0";
            }
            //dd($array_referencia);
            $select_referencia=DB::select($array_referencia);
            $select_cliente=array_merge($select,$select_referencia);

            $codigo=DB::select("SELECT max(numero) as codigo FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.codigo like 'PP-%' and (asientos.estado='Activo' or asientos.estado is null) and proyecto.id_empresa=".$request->id_empresa);
            $cod_asiento="";
            if($codigo){
                $lenght=strlen($codigo[0]->codigo);
                $cod_asiento=$codigo[0]->codigo+1;
            }else{
                $cod_asiento="1";
            }
            $cod_asiento_ant="";
            if($codigo){
                $codigo_ant=DB::select("SELECT asientos.numero FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.id_asientos_comprobante=9 and (asientos.estado='Activo' or asientos.estado is null) and asientos.concepto not like '%Anticipo%' and asientos.codigo_rol={$id} and proyecto.id_empresa=".$request->id_empresa." ORDER BY asientos.codigo desc limit 1");
                //$lenght=strlen($codigo[0]->codigo);
                if($codigo_ant){
                    $cod_asiento_ant=$codigo_ant[0]->numero;
                }
            }
            if($id_facturas || $id_notas_venta){
                $query_forma_pago="SELECT plan_cuentas.id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,ctas_pagar_pagos.fecha_pago,ctas_pagar_pagos.fecha_registro,forma_pagos.id_forma_pagos,ctas_pagar_pagos.nro_tarjeta,banco.id_banco,banco.nombre_banco,forma_pagos.descripcion as nombre_pago,ctas_pagar_pagos.valor_real_pago as haber_tal,null debe,proyecto.id_proyecto,
                proyecto.descripcion,factura_compra.id_factcompra,round(detalle_factura_compra.total/factura_compra.subtotal_sin_impuesto,2) as porcentaje,round(ctas_pagar_pagos.valor_real_pago/{$nro_ctas}*(detalle_factura_compra.total/factura_compra.subtotal_sin_impuesto),2) as haber,plan_cuentas.bansel,0 as id_liquidacion_compra 
                from forma_pagos
                LEFT JOIN plan_cuentas
                on plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas
                INNER JOIN ctas_pagar_pagos
                on ctas_pagar_pagos.id_forma_pagos=forma_pagos.id_forma_pagos
                LEFT JOIN banco
                on banco.id_banco=ctas_pagar_pagos.id_banco
                INNER JOIN factura_compra
                on factura_compra.id_proveedor=ctas_pagar_pagos.id_proveedor
                INNER JOIN detalle_factura_compra
                on detalle_factura_compra.id_factura=factura_compra.id_factcompra
                LEFT JOIN proyecto
                on proyecto.id_proyecto=detalle_factura_compra.id_proyecto
                where ctas_pagar_pagos.id_ctas_pagar_pagos={$id} {$y_factura} {$a}$id_facturas{$b} {$x_factura}
                UNION
                SELECT plan_cuentas.id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,ctas_pagar_pagos.fecha_pago,ctas_pagar_pagos.fecha_registro,forma_pagos.id_forma_pagos,ctas_pagar_pagos.nro_tarjeta,banco.id_banco,banco.nombre_banco,forma_pagos.descripcion as nombre_pago,ctas_pagar_pagos.valor_real_pago as haber_tal,null debe,proyecto.id_proyecto,
                proyecto.descripcion,liquidacion_compra.id_liquidacion_compra,round(detalle_liquidacion_compra.total/liquidacion_compra.subtotal_sin_impuesto,2) as porcentaje,round(ctas_pagar_pagos.valor_real_pago/{$nro_ctas}*(detalle_liquidacion_compra.total/liquidacion_compra.subtotal_sin_impuesto),2) as haber,plan_cuentas.bansel,0 as id_factcompra
                from forma_pagos
                LEFT JOIN plan_cuentas
                on plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas
                INNER JOIN ctas_pagar_pagos
                on ctas_pagar_pagos.id_forma_pagos=forma_pagos.id_forma_pagos
                LEFT JOIN banco
                on banco.id_banco=ctas_pagar_pagos.id_banco
                INNER JOIN liquidacion_compra
                on liquidacion_compra.id_proveedor=ctas_pagar_pagos.id_proveedor
                INNER JOIN detalle_liquidacion_compra
                on detalle_liquidacion_compra.id_liquidacion_compra=liquidacion_compra.id_liquidacion_compra
                LEFT JOIN proyecto
                on proyecto.id_proyecto=detalle_liquidacion_compra.id_proyecto
                where ctas_pagar_pagos.id_ctas_pagar_pagos={$id} {$y_nota_venta} {$c}$id_notas_venta{$d} {$x_nota_venta}";
            }else{
                $query_forma_pago="SELECT plan_cuentas.id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,ctas_pagar_pagos.fecha_pago,ctas_pagar_pagos.fecha_registro,forma_pagos.id_forma_pagos,ctas_pagar_pagos.nro_tarjeta,banco.id_banco,banco.nombre_banco,forma_pagos.descripcion as nombre_pago,ctas_pagar_pagos.valor_real_pago as haber_tal,null debe,proyecto.id_proyecto,
                proyecto.descripcion,factura_compra.id_factcompra,round(detalle_factura_compra.total/factura_compra.subtotal_sin_impuesto,2) as porcentaje,round(ctas_pagar_pagos.valor_real_pago/{$nro_ctas}*(detalle_factura_compra.total/factura_compra.subtotal_sin_impuesto),2) as haber,plan_cuentas.bansel
                from forma_pagos
                LEFT JOIN plan_cuentas
                on plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas
                INNER JOIN ctas_pagar_pagos
                on ctas_pagar_pagos.id_forma_pagos=forma_pagos.id_forma_pagos
                LEFT JOIN banco
                on banco.id_banco=ctas_pagar_pagos.id_banco
                INNER JOIN factura_compra
                on factura_compra.id_proveedor=ctas_pagar_pagos.id_proveedor
                INNER JOIN detalle_factura_compra
                on detalle_factura_compra.id_factura=factura_compra.id_factcompra
                LEFT JOIN proyecto
                on proyecto.id_proyecto=detalle_factura_compra.id_proyecto
                where ctas_pagar_pagos.id_ctas_pagar_pagos={$id} and factura_compra.id_factcompra=0";
            }
            if($id_ctas){
                $query_form_pago_ref="SELECT plan_cuentas.id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,ctas_pagar_pagos.fecha_pago,ctas_pagar_pagos.fecha_registro,forma_pagos.id_forma_pagos,ctas_pagar_pagos.nro_tarjeta,banco.id_banco,banco.nombre_banco,forma_pagos.descripcion as nombre_pago,ctas_pagar_pagos.valor_real_pago as haber_tal,null deve,
                (select id_proyecto from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as id_proyecto,
                (select descripcion from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as descripcion,
                                        0 as id_factcompra,ctas_pagar_pagos.valor_real_pago/{$nro_ctas} as porcentaje,round(ctas_pagar_pagos.valor_real_pago/{$nro_ctas},2) as haber,plan_cuentas.bansel
                                        from forma_pagos
                            LEFT JOIN plan_cuentas
                            on plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas
                            INNER JOIN ctas_pagar_pagos
                            on ctas_pagar_pagos.id_forma_pagos=forma_pagos.id_forma_pagos
                            LEFT JOIN banco
                            on banco.id_banco=ctas_pagar_pagos.id_banco
                            INNER JOIN ctas_pagar
                            on ctas_pagar.id_proveedor=ctas_pagar_pagos.id_proveedor
                            where ctas_pagar_pagos.id_ctas_pagar_pagos={$id} and ctas_pagar.referencias is not null and ({$id_ctas})";
            }else{
                $query_form_pago_ref="SELECT plan_cuentas.id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,ctas_cobrar_pagos.fecha_pago,ctas_cobrar_pagos.fecha_registro,forma_pagos.id_forma_pagos,ctas_cobrar_pagos.nro_tarjeta,banco.id_banco,banco.nombre_banco,forma_pagos.descripcion as nombre_pago,ctas_cobrar_pagos.valor_real_pago as debe_tal,null haber,
                (select id_proyecto from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as id_proyecto,
                (select descripcion from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as descripcion,
                                        0 as id_factura,ctas_cobrar_pagos.valor_real_pago/{$nro_ctas} as porcentaje,round(ctas_cobrar_pagos.valor_real_pago/{$nro_ctas},2) as debe,plan_cuentas.bansel
                                        from forma_pagos
                            LEFT JOIN plan_cuentas
                            on plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas
                            INNER JOIN ctas_cobrar_pagos
                            on ctas_cobrar_pagos.id_forma_pagos=forma_pagos.id_forma_pagos
                            LEFT JOIN banco
                            on banco.id_banco=ctas_cobrar_pagos.id_banco
                            INNER JOIN ctas_cobrar
                            on ctas_cobrar.id_cliente=ctas_cobrar_pagos.id_cliente
                            where ctas_cobrar_pagos.id_ctas_cobrar_pagos={$id} and ctas_cobrar.referencias is not null and ctas_pagar.id_ctaspagar=0";
            }
            //dd($query_forma_pago);
            $array_id_pago="";
            $array_pago=[];

            $select_pago_fact = DB::select($query_forma_pago);
            $select_pago_ref = DB::select($query_form_pago_ref);
            $select_pago=array_merge($select_pago_fact,$select_pago_ref);
            if($cta_cobrar[0]->fecha_registro!==null){
                $fecha_emision=$cta_cobrar[0]->fecha_registro;
            }else{
                $fecha_emision=$cta_cobrar[0]->fecha_pago;
            }
            $fecha_emision=substr($fecha_emision,0,-3);
            $anio_emision=substr($fecha_emision,0,4);
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
        
        //dd();
        return [
            'ctas_pagar'=>$cta_cobrar[0],
            'asiento_permitido'=>$asiento,
            'proveedor'=>$select_cliente,
            'codigo'=>$cod_asiento,
            'codigo_anterior'=>$cod_asiento_ant,
            'forma_pago'=>$select_pago
        ];
    }
    public function verAsientoAnticipo(Request $request,$id){
        $cta_cobrar=DB::select("SELECT null as anticipo,contabilidad,fecha_pago,fecha_registro,proveedor.id_proveedor,proveedor.identif_proveedor as identificacion,proveedor.nombre_proveedor as nombre,proveedor.tipo_identificacion 
        from ctas_pagar 
        INNER JOIN proveedor
        on proveedor.id_proveedor=ctas_pagar.id_proveedor 
        where id_ctaspagar={$id}");
        $proyecto=DB::select("SELECT * from proyecto where id_empresa=".$request->id_empresa." limit 1");
        $nro_facturas=0;
        $nro_ctas=0;
        
        $select_cliente=DB::select("SELECT if(plan_cuentas.id_plan_cuentas is null,'si','no') as exist_plan_cuenta_proveedor,(SELECT id_plan_cuentas from plan_cuentas where id_plan_cuentas=if(proveedor.id_plan_cuentas is null,0,proveedor.id_plan_cuentas)) as id_plan_cuentas_proveedor,(SELECT CONCAT(codcta,'-',nomcta) from plan_cuentas where id_plan_cuentas=if(proveedor.id_plan_cuentas is null,0,proveedor.id_plan_cuentas)) as nombre_cuenta_proveedor,
        plan_cuentas.id_plan_cuentas as id_plan_cuentas_grupo,CONCAT(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta_grupo,
        (select id_proyecto from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as id_proyecto,(select descripcion from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as descripcion,null as haber,valor_cuota as debe,valor_cuota as debe_talves,null as id_factura,abono as porcentaje,null as bansel 
        FROM ctas_pagar
        INNER JOIN proveedor
        on proveedor.id_proveedor=ctas_pagar.id_proveedor
        LEFT JOIN grupo_proveedor
        on grupo_proveedor.id_grupoprov=proveedor.id_grupo_proveedor
        LEFT JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=grupo_proveedor.id_plan_cuentas_anticipo
        where ctas_pagar.id_ctaspagar={$id}");
        //dd($select_cliente);
        $select_pago=DB::select("SELECT plan_cuentas.id_plan_cuentas,CONCAT(codcta,'-',nomcta) as nombre_cuenta,fecha_pago,fecha_registro,valor_cuota as haber,valor_cuota as haber_tal,(select id_proyecto from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as id_proyecto,(select descripcion from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as descripcion,null as debe,ctas_pagar.id_banco,null as id_factura,forma_pagos.id_forma_pagos,nombre_banco,forma_pagos.descripcion as nombre_pago,ctas_pagar.numero_transaccion as nro_tarjeta,abono as porcentaje,bansel
        from ctas_pagar 
        INNER JOIN 	forma_pagos
        on forma_pagos.id_forma_pagos=ctas_pagar.id_forma_pagos
        LEFT JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas
        LEFT JOIN banco
        on banco.id_banco=ctas_pagar.id_banco
        where id_ctaspagar={$id}");

        $codigo=DB::select("SELECT max(numero) as codigo FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.codigo like 'PP-%' and (asientos.estado='Activo' or asientos.estado is null) and proyecto.id_empresa=".$request->id_empresa);
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
            $codigo_ant=DB::select("SELECT asientos.numero FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.id_asientos_comprobante=9 and (asientos.estado='Activo' or asientos.estado is null) and asientos.concepto like '%Anticipo%' and asientos.codigo_rol={$id} and proyecto.id_empresa=".$request->id_empresa." ORDER BY asientos.codigo desc limit 1");
            if($codigo_ant){
                $cod_asiento_ant=$codigo_ant[0]->numero;
            }


        }
        
        return [
            'ctas_pagar'=>$cta_cobrar[0],
            //'asiento_permitido'=>$asiento,
            'proveedor'=>$select_cliente,
            'codigo'=>$cod_asiento,
            'codigo_anterior'=>$cod_asiento_ant,
            'forma_pago'=>$select_pago
        ];
    }
    public function verAsientoPagoAnticipo(Request $request,$id){
        $cta_cobrar=DB::select("SELECT null as anticipo,contabilidad,fecha_pago,fecha_registro,proveedor.id_proveedor,proveedor.identif_proveedor as identificacion,proveedor.nombre_proveedor as nombre,proveedor.tipo_identificacion,referencia 
        from ctas_pagar_pagos 
        INNER JOIN proveedor
        on proveedor.id_proveedor=ctas_pagar_pagos.id_proveedor 
        where id_ctas_pagar_pagos={$id}");
        $proyecto=DB::select("SELECT * from proyecto where id_empresa=".$request->id_empresa." limit 1");
        $nro_facturas=0;
        $nro_ctas=0;
        
        $select_cliente=DB::select("SELECT if(plan_cuentas.id_plan_cuentas is null,'si','no') as exist_plan_cuenta_proveedor,(SELECT id_plan_cuentas from plan_cuentas where id_plan_cuentas=if(proveedor.id_plan_cuentas is null,0,proveedor.id_plan_cuentas)) as id_plan_cuentas_proveedor,(SELECT CONCAT(codcta,'-',nomcta) from plan_cuentas where id_plan_cuentas=if(proveedor.id_plan_cuentas is null,0,proveedor.id_plan_cuentas)) as nombre_cuenta_proveedor,
        plan_cuentas.id_plan_cuentas as id_plan_cuentas_grupo,CONCAT(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta_grupo,
        (select id_proyecto from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as id_proyecto,(select descripcion from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as descripcion,valor_real_pago as haber,null as debe,null as debe_talves,null as id_factura,valor_real_pago as porcentaje,null as bansel 
        FROM ctas_pagar_pagos
        INNER JOIN proveedor
        on proveedor.id_proveedor=ctas_pagar_pagos.id_proveedor
        LEFT JOIN grupo_proveedor
        on grupo_proveedor.id_grupoprov=proveedor.id_grupo_proveedor
        LEFT JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=grupo_proveedor.id_plan_cuentas_anticipo
        where ctas_pagar_pagos.id_ctas_pagar_pagos={$id}");
        //dd($select_cliente);
        $select_pago=DB::select("SELECT plan_cuentas.id_plan_cuentas,CONCAT(codcta,'-',nomcta) as nombre_cuenta,fecha_pago,fecha_registro,null as haber,null as haber_tal,(select id_proyecto from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as id_proyecto,(select descripcion from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as descripcion,valor_real_pago as debe,ctas_pagar_pagos.id_banco,null as id_factura,forma_pagos.id_forma_pagos,nombre_banco,forma_pagos.descripcion as nombre_pago,ctas_pagar_pagos.nro_tarjeta as nro_tarjeta,valor_real_pago as porcentaje,bansel
        from ctas_pagar_pagos 
        INNER JOIN 	forma_pagos
        on forma_pagos.id_forma_pagos=ctas_pagar_pagos.id_forma_pagos
        LEFT JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas
        LEFT JOIN banco
        on banco.id_banco=ctas_pagar_pagos.id_banco
        where id_ctas_pagar_pagos={$id}");

        $codigo=DB::select("SELECT max(numero) as codigo FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.codigo like 'CAP-%' and (asientos.estado='Activo' or asientos.estado is null) and proyecto.id_empresa=".$request->id_empresa);
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
            $codigo_ant=DB::select("SELECT asientos.numero FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.id_asientos_comprobante=24 and (asientos.estado='Activo' or asientos.estado is null)  and asientos.codigo_rol={$id} and proyecto.id_empresa=".$request->id_empresa." ORDER BY asientos.codigo desc limit 1");
            if($codigo_ant){
                $cod_asiento_ant=$codigo_ant[0]->numero;
            }


        }
        
        return [
            'ctas_pagar'=>$cta_cobrar[0],
            //'asiento_permitido'=>$asiento,
            'proveedor'=>$select_cliente,
            'codigo'=>$cod_asiento,
            'codigo_anterior'=>$cod_asiento_ant,
            'forma_pago'=>$select_pago
        ];
    }
    public function generarPdf(Request $request)
    {
        setlocale(LC_TIME, "spanish");
        $empresa = DB::select("SELECT * from empresa where id_empresa=" . $request->id_empresa);
        $usuario = DB::select("SELECT * from user where id=" . $request->id_user);
        $cta_cobrar_pago = DB::select("SELECT * from ctas_pagar_pagos where id_ctas_pagar_pagos=" . $request->id_cta_cobrar_pago);
        $cta_cobrar_pago_datos = DB::select("SELECT ctas_pagar_pagos.*,proveedor.nombre_proveedor as nombre,proveedor.identif_proveedor as identificacion from ctas_pagar_pagos INNER JOIN proveedor ON proveedor.id_proveedor=ctas_pagar_pagos.id_proveedor where id_ctas_pagar_pagos=" . $request->id_cta_cobrar_pago);
        $proyecto = DB::select("SELECT * from proyecto where id_empresa=" . $request->id_empresa . " limit 1");
        $nro_facturas = 0;


        $ctas_id = [];
        if($request->pagos_por=='Anticipo'){
            return;
        }else{
                if (isset($cta_cobrar_pago)) {
                    for ($i = 0; $i < count($cta_cobrar_pago); $i++) {
                        $data = explode(";", $cta_cobrar_pago[$i]->referencia);
                        $registros = count($data) / 4;
                        $salto = 0;
                        for ($f = 0; $f < $registros; $f++) {
                            $id_cb = $data[1 + $salto];
                            $valor = $data[2 + $salto];
                            //$idf = $data[3+$salto];
                            //$query_forma_pago.=" cta.id_ctascobrar={$id_cb} or";
                            array_push($ctas_id, "cta.id_ctaspagar={$id_cb}\n");
                            $salto = $salto + 4;
                        }
                    }
                }
        
                $ctas_id = implode(" or ", $ctas_id);
                //dd($ctas_id);
                $query_cta_cobrar = "SELECT cta.*,fact.descripcion as clave_acceso,cl.nombre_proveedor as nombre,cl.cod_proveedor as codigo,cl.identif_proveedor as identificacion,form.descripcion
                    from ctas_pagar as cta
                        LEFT JOIN factura_compra as fact
                        on fact.id_factcompra=cta.id_factura_compra
                        LEFT JOIN forma_pagos as form
                        on form.id_forma_pagos=cta.id_forma_pagos
                        INNER JOIN proveedor as cl
                        on cl.id_proveedor=cta.id_proveedor
                            where cl.id_empresa={$request->id_empresa} and ({$ctas_id})";
                //dd($query_cta_cobrar);
                $fecha = "";
                $cta_cobrar = DB::select("SELECT cop.*,cl.nombre_proveedor,cl.tipo_identificacion,cl.identif_proveedor from ctas_pagar_pagos as cop,proveedor as cl where cop.id_proveedor=cl.id_proveedor and cop.id_ctas_pagar_pagos={$request->id_cta_cobrar_pago}");
                $nro_facturas = 0;
                $nro_ctas = 0;
                //dd($request->index);
                if (isset($cta_cobrar)) {
                    for ($i = 0; $i < count($cta_cobrar); $i++) {
                        $data = explode(";", $cta_cobrar[$i]->referencia);
                        $registros = count($data) / 4;
                        $salto = 0;
                        for ($f = 0; $f < $registros; $f++) {
                            $id_cb = $data[1 + $salto];
                            if ($id_cb) {
                                $nro_ctas++;
                            }
                            $valor = $data[2 + $salto];
                            $idf = $data[3 + $salto];
                            if ($idf) {
                                $nro_facturas++;
                            }
        
        
                            $salto = $salto + 4;
                        }
                    }
                }
        
        
                //dd($nro_facturas);
                $array_cta = [];
                $array_nro_factura = [];
                $array_id_ctas = [];
                if (isset($cta_cobrar)) {
                    for ($i = 0; $i < count($cta_cobrar); $i++) {
                        $data = explode(";", $cta_cobrar[$i]->referencia);
                        $registros = count($data) / 4;
                        $salto = 0;
                        for ($f = 0; $f < $registros; $f++) {
                            array_push($array_nro_factura, intval(substr($data[0 + $salto], -9)));
                            $id_cb = $data[1 + $salto];
                            if ($id_cb) {
                                array_push($array_id_ctas, "ctas_pagar.id_ctaspagar={$id_cb}");
                            }
                            $valor = $data[2 + $salto];
                            $idf = $data[3 + $salto];
                            //array_push($idf);
                            if ($idf) {
                                array_push($array_cta, "factura_compra.id_factcompra={$idf}");
                            }
        
                            //array_push($array_id_factura,$cliente);
                            $salto = $salto + 4;
                        }
                    }
                }
                $array_cta = implode(" or ", $array_cta);
                $array_id_ctas = implode(" or ", $array_id_ctas);
                $array_nro_factura = implode(" , ", $array_nro_factura);
                //dd($array_nro_factura);
                // $array_factura = substr($array_factura,0 ,-2);
                // $array_factura .= ")";
                if ($array_cta) {
                    $array_factura = "SELECT if(proveedor.id_plan_cuentas is null,'no','si') as exist_plan_cuenta_cliente,proveedor.id_plan_cuentas as id_plan_cuentas_cliente,
                    (select plan_cuentas.nomcta from plan_cuentas where id_plan_cuentas=if(proveedor.id_plan_cuentas is null,0,proveedor.id_plan_cuentas) and id_empresa={$request->id_empresa}) as nombre_cuenta_cliente,
                    grupo_proveedor.id_plan_cuentas as id_plan_cuentas_grupo,
                    (select plan_cuentas.nomcta from plan_cuentas where id_plan_cuentas=if(grupo_proveedor.id_plan_cuentas is null,0,grupo_proveedor.id_plan_cuentas) and id_empresa={$request->id_empresa}) as nombre_cuenta_grupo,
                    ctas_pagar_pagos.valor_real_pago as debe_talves,0 as haber,proyecto.id_proyecto,proyecto.descripcion,factura_compra.id_factcompra,sum(detalle_factura_compra.precio) as producto_valor,round(sum(detalle_factura_compra.total)/factura_compra.subtotal_sin_impuesto,2) as porcentaje,0 as debe_cantidad,round(ctas_pagar_pagos.valor_real_pago/{$nro_facturas}*(sum(detalle_factura_compra.total)/sum(factura_compra.subtotal_sin_impuesto))*count(if(proveedor.id_plan_cuentas is null,grupo_proveedor.id_plan_cuentas,proveedor.id_plan_cuentas)),2) as debe,plan_cuentas.bansel,count( DISTINCT detalle_factura_compra.id_proyecto) as proyectos
                    from proveedor
                    LEFT JOIN grupo_proveedor
                    on grupo_proveedor.id_grupoprov=proveedor.id_grupo_proveedor
                    LEFT JOIN plan_cuentas
                    on plan_cuentas.id_plan_cuentas=grupo_proveedor.id_plan_cuentas
                    INNER JOIN ctas_pagar_pagos
                    on ctas_pagar_pagos.id_proveedor=proveedor.id_proveedor
                            INNER JOIN factura_compra
                    on factura_compra.id_proveedor=proveedor.id_proveedor
                            INNER JOIN detalle_factura_compra
                    on detalle_factura_compra.id_factura=factura_compra.id_factcompra
                            LEFT JOIN proyecto
                    on proyecto.id_proyecto=detalle_factura_compra.id_proyecto
                    where ctas_pagar_pagos.id_ctas_pagar_pagos={$request->id_cta_cobrar_pago} and ({$array_cta})
                    GROUP BY detalle_factura_compra.id_proyecto";
                    $query_forma_pago = "SELECT plan_cuentas.id_plan_cuentas,plan_cuentas.nomcta as nombre_cuenta,ctas_pagar_pagos.fecha_pago,forma_pagos.id_forma_pagos,ctas_pagar_pagos.nro_tarjeta,banco.id_banco,banco.nombre_banco,forma_pagos.descripcion as nombre_pago,ctas_pagar_pagos.valor_real_pago as haber_tal,0 debe,proyecto.id_proyecto,
                    proyecto.descripcion,factura_compra.id_factcompra,round(sum(detalle_factura_compra.total)/factura_compra.subtotal_sin_impuesto,2) as porcentaje,0 as haber_cantidad,round(ctas_pagar_pagos.valor_real_pago/{$nro_facturas}*(sum(detalle_factura_compra.total)/sum(factura_compra.subtotal_sin_impuesto))*count(plan_cuentas.id_plan_cuentas),2) as haber,plan_cuentas.bansel,ctas_pagar_pagos.pagos_por,count( DISTINCT detalle_factura_compra.id_proyecto) as proyectos
                    from forma_pagos
                    LEFT JOIN plan_cuentas
                    on plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas
                    INNER JOIN ctas_pagar_pagos
                    on ctas_pagar_pagos.id_forma_pagos=forma_pagos.id_forma_pagos
                    LEFT JOIN banco
                    on banco.id_banco=ctas_pagar_pagos.id_banco
                    INNER JOIN factura_compra
                    on factura_compra.id_proveedor=ctas_pagar_pagos.id_proveedor
                    INNER JOIN detalle_factura_compra
                    on detalle_factura_compra.id_factura=factura_compra.id_factcompra
                    LEFT JOIN proyecto
                    on proyecto.id_proyecto=detalle_factura_compra.id_proyecto
                    where ctas_pagar_pagos.id_ctas_pagar_pagos={$request->id_cta_cobrar_pago} and ({$array_cta})
                    GROUP BY detalle_factura_compra.id_proyecto";
                } else {
                    $array_factura = "SELECT if(proveedor.id_plan_cuentas is null,'no','si') as exist_plan_cuenta_cliente,proveedor.id_plan_cuentas as id_plan_cuentas_cliente,
                    (select plan_cuentas.nomcta from plan_cuentas where id_plan_cuentas=if(proveedor.id_plan_cuentas is null,0,proveedor.id_plan_cuentas) and id_empresa={$request->id_empresa}) as nombre_cuenta_cliente,
                    grupo_proveedor.id_plan_cuentas as id_plan_cuentas_grupo,
                    (select plan_cuentas.nomcta from plan_cuentas where id_plan_cuentas=if(grupo_proveedor.id_plan_cuentas is null,0,grupo_proveedor.id_plan_cuentas) and id_empresa={$request->id_empresa}) as nombre_cuenta_grupo,
                    ctas_pagar_pagos.valor_real_pago as debe_talves,0 as haber,proyecto.id_proyecto,proyecto.descripcion,factura_compra.id_factcompra,sum(detalle_factura_compra.precio) as producto_valor,round(sum(detalle_factura_compra.total)/factura_compra.subtotal_sin_impuesto,2) as porcentaje,round(ctas_pagar_pagos.valor_real_pago/1*(sum(detalle_factura_compra.total)/factura_compra.subtotal_sin_impuesto),2) as debe,plan_cuentas.bansel,count( DISTINCT detalle_factura_compra.id_proyecto) as proyectos
                    from proveedor
                    LEFT JOIN grupo_proveedor
                    on grupo_proveedor.id_grupoprov=proveedor.id_grupo_proveedor
                    LEFT JOIN plan_cuentas
                    on plan_cuentas.id_plan_cuentas=grupo_proveedor.id_plan_cuentas
                    INNER JOIN ctas_pagar_pagos
                    on ctas_pagar_pagos.id_proveedor=proveedor.id_proveedor
                            INNER JOIN factura_compra
                    on factura_compra.id_proveedor=proveedor.id_proveedor
                            INNER JOIN detalle_factura_compra
                    on detalle_factura_compra.id_factura=factura_compra.id_factcompra
                            LEFT JOIN proyecto
                    on proyecto.id_proyecto=detalle_factura_compra.id_proyecto
                    where ctas_pagar_pagos.id_ctas_pagar_pagos={$request->id_cta_cobrar_pago} and factura_compra.id_factcompra=0
                    GROUP BY detalle_factura_compra.id_proyecto";
                    $query_forma_pago = "SELECT plan_cuentas.id_plan_cuentas,plan_cuentas.nomcta as nombre_cuenta,ctas_pagar_pagos.fecha_pago,forma_pagos.id_forma_pagos,ctas_pagar_pagos.nro_tarjeta,banco.id_banco,banco.nombre_banco,forma_pagos.descripcion as nombre_pago,ctas_pagar_pagos.valor_real_pago as haber_tal,0 debe,proyecto.id_proyecto,
                    proyecto.descripcion,factura_compra.id_factcompra,round(sum(detalle_factura_compra.total)/factura_compra.subtotal_sin_impuesto,2) as porcentaje,round(ctas_pagar_pagos.valor_real_pago/1*(sum(detalle_factura_compra.total)/factura_compra.subtotal_sin_impuesto),2) as haber,plan_cuentas.bansel,ctas_pagar_pagos.pagos_por,count( DISTINCT detalle_factura_compra.id_proyecto) as proyectos
                    from forma_pagos
                    LEFT JOIN plan_cuentas
                    on plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas
                    INNER JOIN ctas_pagar_pagos
                    on ctas_pagar_pagos.id_forma_pagos=forma_pagos.id_forma_pagos
                    LEFT JOIN banco
                    on banco.id_banco=ctas_pagar_pagos.id_banco
                    INNER JOIN factura_compra
                    on factura_compra.id_proveedor=ctas_pagar_pagos.id_proveedor
                    INNER JOIN detalle_factura_compra
                    on detalle_factura_compra.id_factura=factura_compra.id_factcompra
                    LEFT JOIN proyecto
                    on proyecto.id_proyecto=detalle_factura_compra.id_proyecto
                    where ctas_pagar_pagos.id_ctas_pagar_pagos={$request->id_cta_cobrar_pago} and factura_compra.id_factcompra=0
                    GROUP BY detalle_factura_compra.id_proyecto";
                }
                //dd($query_forma_pago);
                $plan_cuenta = DB::select("SELECT if(proveedor.id_plan_cuentas is null,'no','si') as exist_plan_cuenta_cliente from ctas_pagar_pagos
                    INNER JOIN proveedor
                    on proveedor.id_proveedor=ctas_pagar_pagos.id_proveedor
                    where ctas_pagar_pagos.id_ctas_pagar_pagos={$request->id_cta_cobrar_pago}");
                $id_plan_cta = "";
        
                if ($plan_cuenta) {
                    if ($plan_cuenta[0]->exist_plan_cuenta_cliente == "si") {
                        $id_plan_cta = "GROUP BY proveedor.id_plan_cuentas";
                    } else {
                        $id_plan_cta = "GROUP BY grupo_proveedor.id_plan_cuentas";
                    }
                }
                if ($array_id_ctas) {
                    $array_referencia = "SELECT if(proveedor.id_plan_cuentas is null,'no','si') as exist_plan_cuenta_cliente, proveedor.id_plan_cuentas as id_plan_cuentas_cliente,
                    (select concat(plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(proveedor.id_plan_cuentas is null,0,proveedor.id_plan_cuentas) and id_empresa={$request->id_empresa}) as nombre_cuenta_cliente,grupo_proveedor.id_plan_cuentas as id_plan_cuentas_grupo,
                    (select concat(plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(grupo_proveedor.id_plan_cuentas is null,0,grupo_proveedor.id_plan_cuentas) and id_empresa={$request->id_empresa}) as nombre_cuenta_grupo,ctas_pagar_pagos.valor_real_pago as debe_talves,null as haber,
                    (select id_proyecto from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as id_proyecto,
                    (select descripcion from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as descripcion,
                    null as id_factcompra,ctas_pagar_pagos.valor_real_pago/{$nro_ctas} as porcentaje,0 as debe_cantidad,sum(round(ctas_pagar_pagos.valor_real_pago/{$nro_ctas},2)) as debe,plan_cuentas.bansel,1 as proyectos
                    from proveedor
                    LEFT JOIN grupo_proveedor
                    on grupo_proveedor.id_grupoprov=proveedor.id_grupo_proveedor
                    LEFT JOIN plan_cuentas
                    on plan_cuentas.id_plan_cuentas=grupo_proveedor.id_plan_cuentas
                    INNER JOIN ctas_pagar_pagos
                    on ctas_pagar_pagos.id_proveedor=proveedor.id_proveedor
                    INNER JOIN ctas_pagar
                    on ctas_pagar.id_proveedor=proveedor.id_proveedor
                    where ctas_pagar_pagos.id_ctas_pagar_pagos={$request->id_cta_cobrar_pago} and ctas_pagar.referencias is not null and ({$array_id_ctas})
                    {$id_plan_cta}";
                    $query_form_pago_ref = "SELECT plan_cuentas.id_plan_cuentas,concat(plan_cuentas.nomcta) as nombre_cuenta,ctas_pagar_pagos.referencia as referencia,ctas_pagar_pagos.fecha_pago,forma_pagos.id_forma_pagos,ctas_pagar_pagos.nro_tarjeta,banco.id_banco,banco.nombre_banco,forma_pagos.descripcion as nombre_pago,ctas_pagar_pagos.valor_real_pago as haber_tal,null debe,
                    (select id_proyecto from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as id_proyecto,
                    (select descripcion from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as descripcion,
                                            0 as id_factcompra,ctas_pagar_pagos.valor_real_pago/{$nro_ctas} as porcentaje,round(ctas_pagar_pagos.valor_real_pago/{$nro_ctas},2) as haber_cantidad,sum(round(ctas_pagar_pagos.valor_real_pago/{$nro_ctas},2)) as haber,plan_cuentas.bansel,1 as proyectos
                                            from forma_pagos
                                LEFT JOIN plan_cuentas
                                on plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas
                                INNER JOIN ctas_pagar_pagos
                                on ctas_pagar_pagos.id_forma_pagos=forma_pagos.id_forma_pagos
                                LEFT JOIN banco
                                on banco.id_banco=ctas_pagar_pagos.id_banco
                                INNER JOIN ctas_pagar
                                on ctas_pagar.id_proveedor=ctas_pagar_pagos.id_proveedor
                                where ctas_pagar_pagos.id_ctas_pagar_pagos={$request->id_cta_cobrar_pago} and ctas_pagar.referencias is not null and ({$array_id_ctas})
                                GROUP BY plan_cuentas.id_plan_cuentas";
                } else {
                    $array_referencia = "SELECT if(proveedor.id_plan_cuentas is null,'no','si') as exist_plan_cuenta_cliente, proveedor.id_plan_cuentas as id_plan_cuentas_cliente,
                    (select concat(plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(proveedor.id_plan_cuentas is null,0,proveedor.id_plan_cuentas) and id_empresa={$request->id_empresa}) as nombre_cuenta_cliente,grupo_proveedor.id_plan_cuentas as id_plan_cuentas_grupo,
                    (select concat(plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(grupo_proveedor.id_plan_cuentas is null,0,grupo_proveedor.id_plan_cuentas) and id_empresa={$request->id_empresa}) as nombre_cuenta_grupo,ctas_pagar_pagos.valor_real_pago as debe_talves,null as haber,
                    (select id_proyecto from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as id_proyecto,
                    (select descripcion from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as descripcion,
                    null as id_factcompra,ctas_pagar_pagos.valor_real_pago/{$nro_ctas} as porcentaje,0 as debe_cantidad,sum(round(ctas_pagar_pagos.valor_real_pago/{$nro_ctas},2)) as debe,plan_cuentas.bansel
                                            from proveedor
                    LEFT JOIN grupo_proveedor
                    on grupo_proveedor.id_grupoprov=proveedor.id_grupo_proveedor
                    LEFT JOIN plan_cuentas
                    on plan_cuentas.id_plan_cuentas=grupo_proveedor.id_plan_cuentas
                    INNER JOIN ctas_pagar_pagos
                    on ctas_pagar_pagos.id_proveedor=proveedor.id_proveedor
                    INNER JOIN ctas_pagar
                    on ctas_pagar.id_proveedor=proveedor.id_proveedor
                    where ctas_pagar_pagos.id_ctas_pagar_pagos={$request->id_cta_cobrar_pago} and ctas_pagar.referencias is not null and ctas_pagar.id_ctaspagar=0
                    {$id_plan_cta}";
                    $query_form_pago_ref = "SELECT plan_cuentas.id_plan_cuentas,concat(plan_cuentas.nomcta) as nombre_cuenta,ctas_pagar_pagos.referencia as referencia,ctas_pagar_pagos.fecha_pago,forma_pagos.id_forma_pagos,ctas_pagar_pagos.nro_tarjeta,banco.id_banco,banco.nombre_banco,forma_pagos.descripcion as nombre_pago,ctas_pagar_pagos.valor_real_pago as haber_tal,null deve,
                    (select id_proyecto from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as id_proyecto,
                    (select descripcion from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as descripcion,
                                            0 as id_factcompra,ctas_pagar_pagos.valor_real_pago/{$nro_ctas} as porcentaje,round(ctas_pagar_pagos.valor_real_pago/{$nro_ctas},2) as haber_cantidad,sum(round(ctas_pagar_pagos.valor_real_pago/{$nro_ctas},2)) as haber,plan_cuentas.bansel
                                            from forma_pagos
                                LEFT JOIN plan_cuentas
                                on plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas
                                INNER JOIN ctas_pagar_pagos
                                on ctas_pagar_pagos.id_forma_pagos=forma_pagos.id_forma_pagos
                                LEFT JOIN banco
                                on banco.id_banco=ctas_pagar_pagos.id_banco
                                INNER JOIN ctas_pagar
                                on ctas_pagar.id_proveedor=ctas_pagar_pagos.id_proveedor
                                where ctas_pagar_pagos.id_ctas_pagar_pagos={$request->id_cta_cobrar_pago} and ctas_pagar.referencias is not null and ctas_pagar.id_ctaspagar=0
                                GROUP BY plan_cuentas.id_plan_cuentas";
                }
                $asiento=DB::select("SELECT * from asientos where id_asientos_comprobante=9 and codigo_rol={$request->id_cta_cobrar_pago}");
                //dd($query_forma_pago);
                $reporte_cta_fact = DB::select($array_factura);
                $reporte_cta_cobrar = DB::select($query_cta_cobrar);
                $reporte_cta_pago_fact = DB::select($query_forma_pago);
                $reporte_cta_ref = DB::select($array_referencia);
                //dd($query_form_pago_ref);
                $reporte_cta_pago_ref = DB::select($query_form_pago_ref);
                if ($reporte_cta_cobrar) {
                    $fecha = $array_nro_factura;
                }
        
                $reporte_cta_cliente = array_merge($reporte_cta_fact, $reporte_cta_ref);
                $reporte_cta_pago = array_merge($reporte_cta_pago_fact, $reporte_cta_pago_ref);
                $habers = [];
                $facturas = [];
                $facturas_id = [];
                $index_cta=0;
                if(count($asiento)>0){
                    $index_cta=$asiento[0]->numero; 
                }
                //$index=0;
        
                // foreach($reporte_cta_pago as $data){
                //     $valores=0;
                //     if($data->id_plan_cuentas==$facturas_id){
                //         $facturas[] = $data;
                //         $facturas_id[] = $data->id_plan_cuentas;
                //     }
                //     else{
                //         $valores+=$data->haber;
                //         $facturas = $data;
                //     }
                // }
                // dd($facturas);
                //dd($reporte_cta_cliente);
                if (!$reporte_cta_pago && !$reporte_cta_cliente) {
                    return response('no-data-report', 200)->header('Content-Type', 'application/json');
                } else {
                    if ($request->destinatario !== null && $request->email !== null) { } else {
                        $Reportes = new generarReportes();
                        $strPDF = $Reportes->PDFCtaPagar($cta_cobrar, $cta_cobrar_pago_datos, $reporte_cta_cobrar, $empresa[0], $usuario[0], $index_cta, $fecha, $reporte_cta_cliente, $reporte_cta_pago);
                        return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                    }
                }
        }
    }
    public function generarCheque(Request $request){
                $empresa=DB::select("SELECT empresa.*,ciudad.nombre as nombre_ciudad FROM empresa  INNER JOIN ciudad ON ciudad.id_ciudad=empresa.id_ciudad where id_empresa=".$request->id_empresa);
                $ctas=DB::select("SELECT ctas_pagar_pagos.*,proveedor.beneficiario,if(fp.descripcion like '%Cheque%',if(fp.descripcion like '%Pichincha%','Pichincha',if(fp.descripcion like '%PRODUBANCO%','Produbanco','Internacional')),0) as cheque 
                FROM ctas_pagar_pagos 
                INNER JOIN proveedor ON proveedor.id_proveedor=ctas_pagar_pagos.id_proveedor
                LEFT JOIN forma_pagos fp ON fp.id_forma_pagos = ctas_pagar_pagos.id_forma_pagos 
                LEFT JOIN forma_pagos_sri fps ON fps.id_forma_pagos_sri = fp.id_forma_pagos_sri  
                where id_ctas_pagar_pagos=".$request->id_cta_cobrar_pago);

                $Reportes = new generarReportes();
                $strPDF = $Reportes->PDFCheque($empresa[0],$ctas[0]);
                return response($strPDF, 200)->header('Content-Type', 'application/pdf');
    }
    public function agregarAsiento(Request $request){
        try {
            $cod_rol="";
            if(gettype($request->cod_rol)=="array"){
                $cod_rol=implode("",$request->cod_rol);
            }else{
                $cod_rol=$request->cod_rol;
            }
            
            
            if($request->anticipo>0){
                Cuentaporpagar::where('id_ctaspagar',$cod_rol)->update(['contabilidad'=>'1']);
                Ctas_pagar_pagos::where('id_ctas_pagar_pagos',$request->ide_ctas_pagar_pagos)->update(['contabilidad'=>'1']);
            }else{
                Ctas_pagar_pagos::where('id_ctas_pagar_pagos',$cod_rol)->update(['contabilidad'=>'1']);
            }
            $asientos=new Asientos();
            $asientos->automatico=0;
            $asientos->numero=$request->numero;
            $asientos->codigo=$request->codigo;
            $asientos->codigo_rol=$cod_rol;
            $asientos->fecha=$request->fecha;
            $asientos->razon_social=$request->razon_social;
            $asientos->tipo_identificacion=$request->tipo_identificacion;
            $asientos->ruc_ci=$request->ruc_ci;
            $asientos->concepto=$request->concepto;
            $asientos->ucrea=$request->ucrea;
            $asientos->id_proyecto=$request->id_proyecto;
            $asientos->id_asientos_comprobante=9;

            $asientos->save();
            return $asientos->id_asientos;
        } catch (\Throwable $th) {
            return "ERROR ASIENTO";
            //throw $th;
        }
    }
    public function agregarAsientoDetalle(Request $request){
        foreach($request->productos as $debe){
            $asiento=new Asientos_contables_detalle();
            if($debe["debe"]>0){
                if($debe["exist_plan_cuenta_proveedor"]=="si"){
                    $asiento->proyecto=$debe["descripcion"];
                    $asiento->debe=$debe["debe"];
                    if($debe["bansel"]!==null){
                        $asiento->no_documento=$request->nro_documento;
                        $asiento->fecha_de_pago=$request->fecha_pago;
                        $asiento->id_forma_pagos=$request->id_forma_pago;
                    }
                    $asiento->ucrea=$request->ucrea;
                    $asiento->id_plan_cuentas=$debe["id_plan_cuentas_proveedor"];
                    $asiento->id_asientos=$request->id_asientos;
                    $asiento->id_proyecto=$debe["id_proyecto"];
                    $asiento->save();
                }else{
                    $asiento->proyecto=$debe["descripcion"];
                    $asiento->debe=$debe["debe"];
                    if($debe["bansel"]!==null){
                        $asiento->no_documento=$request->nro_documento;
                        $asiento->fecha_de_pago=$request->fecha_pago;
                        $asiento->id_forma_pagos=$request->id_forma_pago;
                    }
                    $asiento->ucrea=$request->ucrea;
                    $asiento->id_plan_cuentas=$debe["id_plan_cuentas_grupo"];
                    $asiento->id_asientos=$request->id_asientos;
                    $asiento->id_proyecto=$debe["id_proyecto"];
                    $asiento->save();
                }
            }
        }
        foreach($request->iva_12 as $haber){
            $asiento=new Asientos_contables_detalle();
            if($haber["haber"]>0){
                    $asiento->proyecto=$haber["descripcion"];
                    $asiento->haber=$haber["haber"];
                    if($haber["bansel"]!==null){
                        $asiento->no_documento=$haber["nro_tarjeta"];
                        if($haber["fecha_registro"]!==null){
                            $asiento->fecha_de_pago=$haber["fecha_registro"];
                        }else{
                            $asiento->fecha_de_pago=$haber["fecha_pago"];
                        }
                        
                        $asiento->id_forma_pagos=$haber["id_forma_pagos"];
                    }
                    $asiento->ucrea=$request->ucrea;
                    $asiento->id_plan_cuentas=$haber["id_plan_cuentas"];
                    $asiento->id_asientos=$request->id_asientos;
                    $asiento->id_proyecto=$haber["id_proyecto"];
                    $asiento->save();
            }
        }
    }
    public function agregarAsientoPagoAnticipo(Request $request){
        try {
            $cod_rol="";
            $cod_rol=$request->cod_rol;
            Ctas_pagar_pagos::where('id_ctas_pagar_pagos',$cod_rol)->update(['contabilidad'=>'1']);
            $asientos=new Asientos();
            $asientos->automatico=0;
            $asientos->numero=$request->numero;
            $asientos->codigo=$request->codigo;
            $asientos->codigo_rol=$cod_rol;
            $asientos->fecha=$request->fecha;
            $asientos->razon_social=$request->razon_social;
            $asientos->tipo_identificacion=$request->tipo_identificacion;
            $asientos->ruc_ci=$request->ruc_ci;
            $asientos->concepto=$request->concepto;
            $asientos->ucrea=$request->ucrea;
            $asientos->id_proyecto=$request->id_proyecto;
            $asientos->id_asientos_comprobante=24;

            $asientos->save();
            return $asientos->id_asientos;
        } catch (\Throwable $th) {
            return "ERROR ASIENTO";
            //throw $th;
        }
    }
    public function agregarAsientoDetallePagoAnticipo(Request $request){
        foreach($request->productos as $haber){
            $asiento=new Asientos_contables_detalle();
            if($haber["haber"]>0){
                if($haber["exist_plan_cuenta_proveedor"]=="si"){
                    $asiento->proyecto=$haber["descripcion"];
                    $asiento->haber=$haber["haber"];
                    if($haber["bansel"]!==null){
                        $asiento->no_documento=$request->nro_documento;
                        $asiento->fecha_de_pago=$request->fecha_pago;
                        $asiento->id_forma_pagos=$request->id_forma_pago;
                    }
                    $asiento->ucrea=$request->ucrea;
                    $asiento->id_plan_cuentas=$haber["id_plan_cuentas_proveedor"];
                    $asiento->id_asientos=$request->id_asientos;
                    $asiento->id_proyecto=$haber["id_proyecto"];
                    $asiento->save();
                }else{
                    $asiento->proyecto=$haber["descripcion"];
                    $asiento->haber=$haber["haber"];
                    if($haber["bansel"]!==null){
                        $asiento->no_documento=$request->nro_documento;
                        $asiento->fecha_de_pago=$request->fecha_pago;
                        $asiento->id_forma_pagos=$request->id_forma_pago;
                    }
                    $asiento->ucrea=$request->ucrea;
                    $asiento->id_plan_cuentas=$haber["id_plan_cuentas_grupo"];
                    $asiento->id_asientos=$request->id_asientos;
                    $asiento->id_proyecto=$haber["id_proyecto"];
                    $asiento->save();
                }
            }
        }
        foreach($request->iva_12 as $debe){
            $asiento=new Asientos_contables_detalle();
            if($debe["debe"]>0){
                    $asiento->proyecto=$debe["descripcion"];
                    $asiento->debe=$debe["debe"];
                    if($debe["bansel"]!==null){
                        $asiento->no_documento=$debe["nro_tarjeta"];
                        if($debe["fecha_registro"]!==null){
                            $asiento->fecha_de_pago=$debe["fecha_registro"];
                        }else{
                            $asiento->fecha_de_pago=$debe["fecha_pago"];
                        }
                        
                        $asiento->id_forma_pagos=$debe["id_forma_pagos"];
                    }
                    $asiento->ucrea=$request->ucrea;
                    $asiento->id_plan_cuentas=$debe["id_plan_cuentas"];
                    $asiento->id_asientos=$request->id_asientos;
                    $asiento->id_proyecto=$debe["id_proyecto"];
                    $asiento->save();
            }
        }
    }
    public function anticipo(Request $request){
        //recupera los anticipo dependiendo el cliente del proveedor
        $id = $request->id;
        $valor = DB::select("SELECT SUM(abono) as anticipo FROM ctas_pagar WHERE id_proveedor = $id AND tipo=3");
        $res = $valor[0]->anticipo;
        return $res;
    }
    public function importar(Request $request){
        //importa los registros
        $file = $request->file('file');
        //llama a el módulo de import de CuentasPorPagarImport
        Excel::import(new CuentasPorPagarImport , $file);
    }
    // finciones listar anticipos
    public function listar_anticipos($id){
        $recupera=DB::select("SELECT ctas_pagar.*,proveedor.nombre_proveedor as nombre,if(ctas_pagar.fecha_registro is null,ctas_pagar.fecha_pago,ctas_pagar.fecha_registro) as fecha_emision from ctas_pagar INNER JOIN proveedor ON proveedor.id_proveedor=ctas_pagar.id_proveedor where ctas_pagar.id_proveedor=$id and ctas_pagar.tipo=3 and ctas_pagar.abono>0");
        return $recupera;
    }
}
