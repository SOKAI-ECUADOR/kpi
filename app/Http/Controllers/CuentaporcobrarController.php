<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cuentaporcobrar;
use App\Models\Ctas_cobrar_pagos;
use App\Models\Cliente;
use App\Models\Factura;
use Carbon\Carbon;
use App\Models\Asientos;
use App\Models\Asientos_contables_detalle;


//recupera las librerias de generar reporte y envio de email del SRI ubicado en class de controllers
include 'class/generarReportes.php';
include 'class/sendEmail.php';

use DOMDocument;
use generarReportes;
use sendEmail;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CuentasPorCobrarImport;

class CuentaporcobrarController extends Controller
{
    public function index($id)
    {
        //recupera las cuentas por cobrar por proveedor cuando el tipo sea 1 osea credito (2 es pagos) y lo llama mediante el id del cliente y que el valor del cobro sea mayor al valor pagado del mismo
        $recupera = DB::select("SELECT *, (select nombre from cliente where id_cliente = ctas_cobrar.id_cliente) as nombre, if((select clave_acceso from factura where id_factura = ctas_cobrar.id_factura) is null && ctas_cobrar.id_nota_venta is not null,(select clave_acceso from nota_venta where id_nota_venta = ctas_cobrar.id_nota_venta),(select clave_acceso from factura where id_factura = ctas_cobrar.id_factura)) as clave_acceso, referencias AS  id_factura2, CONCAT('001-001-',LPAD(referencias,9,'0')) AS  clave_acceso2 from ctas_cobrar where ctas_cobrar.id_cliente = $id and ctas_cobrar.valor_cuota > ctas_cobrar.valor_pagado AND ctas_cobrar.tipo = 1");
        return [
            'recupera' => $recupera
        ];
    }
    public function indexcliente(Request $request)
    {
        //recupera los datos de los clientes por empresa y busqueda
        $buscar = $request->buscar;
        $id = $request->id;
        $recupera = DB::select("SELECT * FROM cliente WHERE (nombre LIKE '%$buscar%' OR identificacion LIKE '%$buscar%' OR codigo LIKE '%$buscar%') AND id_empresa = $id");
        if (session()->get('usuariosesion')['filtro_list'] == 1) {
            $dat = [];
            for ($i = 0; $i < count($recupera); $i++) {
                if ($recupera[$i]->created_by == session()->get('usuariosesion')['id'] || $recupera[$i]->updated_by == session()->get('usuariosesion')['id'] || $recupera[$i]->id_vendedor == session()->get('usuariosesion')['id_vendedor']) {
                    array_push($dat, $recupera[$i]);
                }
            }
            $recupera = $dat;
        }
        return $recupera;
    }
    public function store(Request $request)
    {
        //guarda las cuentas por cobrar
        $pago = new Cuentaporcobrar();
        $pago->nro_cuota = $request->nro_cuota;

        $pago->id_forma_pagos = $request->forma_pago;

        $pago->id_banco = $request->banco;
        $pago->nro_tarjeta = $request->nro_tarjeta;
        $pago->cta_contable = $request->cta_contable;
        $pago->descuento = $request->descuento;
        $pago->fvencimiento = $request->fecha_pago;
        $pago->cuotas_totales = $request->cuotas;
        $pago->nro_cuota = 0;
        $pago->unidad_tiempo = $request->unidad_tiempo;
        $pago->monto = $request->monto;
        $pago->abono = $request->abonos;
        $pago->disponible = $request->abonos;
        //si es anticipo guarda con 3 caso contrario con 1
        if ($request->abonos > 0) {
            $pago->tipo = 3;
        } else {
            $pago->tipo = 1;
        }
        $pago->saldo = $request->monto;
        $pago->estado = 1;
        $pago->id_cliente = $request->id_cliente;
        $pago->created_by = session()->get('usuariosesion')['id'];
        $pago->updated_by = session()->get('usuariosesion')['id'];
        $pago->save();
    }
    public function recuperabono($id)
    {
        //recupera la cuenta por cobrar por id
        $datos = Cuentaporcobrar::select("*")->where("id_ctascobrar", "=", $id)->get();
        return $datos[0];
    }
    public function editarabono(Request $request)
    {
        //edita el abono existente
        $datos = Cuentaporcobrar::findOrFail($request->id_ctascobrar);
        $datos->id_cliente = $request->id_cliente;

        $datos->id_forma_pagos = $request->forma_pago;

        $datos->id_banco = $request->banco;
        $datos->nro_tarjeta = $request->nro_tarjeta;
        $datos->cta_contable = $request->cta_contable;
        $datos->abono = $request->abonos;
        $datos->disponible = $request->disponible;
        $datos->updated_by = session()->get('usuariosesion')['id'];
        $datos->save();
    }
    public function pagarletra(Request $request)
    {
        //edita la Cuentaporcobrar el saldo y n cuota
        $datos = Cuentaporcobrar::findOrFail($request->id_ctascobrar);
        $datos->saldo = $datos->saldo - $request->saldo_pagar;
        $datos->nro_cuota = $datos->nro_cuota + $request->letras_pagar;
        $datos->updated_by = session()->get('usuariosesion')['id'];
        $datos->save();
    }
    public function update(Request $request)
    {
        //edita las Cuentaporcobrar mediante id
        $pago = Cuentaporcobrar::find($request->id);
        $pago->nro_comprobante = $request->nro_comprobante;
        $pago->nro_cuota = $request->nro_cuota;

        $pago->id_forma_pagos = $request->forma_pago;

        $pago->id_banco = $request->banco;
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
        $pago->updated_by = session()->get('usuariosesion')['id'];
        $pago->save();
    }
    public function pagar(Request $request)
    {
        // edita la Cuentaporcobrar y agrega el registro mediante la fecha y agrega el valor dependiendo el tiempo agregado
        $cxc = Cuentaporcobrar::findOrFail($request->id);
        if ($request->tiempo == "Años") {
            $fecha = Carbon::parse($request->fecha)->format('Y-m-d');
            $fd = date("Y-m-d", strtotime($fecha . "+ " . $request->salto . " year"));
        } else if ($request->tiempo == "Meses") {
            $fecha = Carbon::parse($request->fecha)->format('Y-m-d');
            $fd = date("Y-m-d", strtotime($fecha . "+ " . $request->salto . " month"));
        } else if ($request->tiempo == "Semanas") {
            $fecha = Carbon::parse($request->fecha)->format('Y-m-d');
            $fd = date("Y-m-d", strtotime($fecha . "+ " . $request->salto . " week"));
        } else {
            $fecha = Carbon::parse($request->fecha)->format('Y-m-d');
            $fd = date("Y-m-d", strtotime($fecha . "+ " . $request->salto . " days"));
        }
        $cxc->nro_cuota = $cxc->nro_cuota + 1;
        $cxc->fvencimiento = $fd;
        $cxc->updated_by = session()->get('usuariosesion')['id'];
        $cxc->save();
    }
    public function abrir(Request $request)
    {
        //abre la cuenta por cobrar mediante su id
        $id = $request->id;
        $recupera = DB::select('SELECT * FROM `ctas_cobrar` WHERE id_ctascobrar=' . $id);
        return $recupera;
    }
    public function eliminar($id)
    {
        //elimina una cuenta por cobrar mediante su id
        Cuentaporcobrar::destroy($id);
    }
    public function getCliente()
    {
        //recupera los clientes e imprime como un json legible
        $data = Cliente::get();
        return response()->json($data);
    }
    public function listarsecuencia($id){
        $respuesta=DB::select("SELECT if(secuencial_recibo is null,1,secuencial_recibo) as secuencial_recibo from user INNER JOIN punto_emision on punto_emision.id_punto_emision=user.id_punto_emision where id=$id");
        $secuencial=[];
        if(count($respuesta)>0){
            $secuencial=$respuesta;
        }
        return $secuencial;
    }
    public function agregarpagos(Request $request)
    {
        
        if ($request->id_user == null) {
            return "id_user";
        }
        //guarda la fecha actual del servidor
        $hoy = Carbon::now();
        $idcliente = $request->id_cliente;
        $valor_pagar = $request->valor_real + $request->descuento_pago;
        //verifica si es anticipo n caso de ser resta el valor existente del cliente
        if ($request->pagos_por == 'Anticipo') {
            DB::update("UPDATE ctas_cobrar SET abono = abono - $valor_pagar WHERE id_cliente = $idcliente AND tipo = 3");
        }
        $for_pago=DB::select("SELECT * FROM cliente where id_cliente={$request->id_cliente}");
        $pos=DB::select("SELECT * from user INNER JOIN punto_emision on punto_emision.id_punto_emision=user.id_punto_emision where id={$request->id_user}");

        $conteo=1;
        if(count($pos)>0){
            if($pos[0]->secuencial_recibo!==null){
                $conteo=$pos[0]->secuencial_recibo;
            } 
        }
        if($request->exist_anticipos==true){
            //cuentas cobrar pagos genera la cabezera del registro
            $cxcp = new Ctas_cobrar_pagos();
            $cxcp->pagos_por = $request->pagos_por;
            $cxcp->nro_tarjeta = $request->numero_tarjeta;
            $cxcp->valor_seleccionado = $request->valor_select;
            $cxcp->descuento_porcentaje = $request->descuento_porcentaje;
            $cxcp->descuento_pago = $request->descuento_pago;
            $cxcp->valor_real_pago = $request->valor_real;
            $cxcp->id_forma_pagos = $request->forma_pago;
            $cxcp->id_banco = $request->banco;
            $cxcp->id_cliente = $request->id_cliente;
            $cxcp->fecha_pago = $hoy;
            $cxcp->fecha_registro = $request->fecha_registro;
            $cxcp->posicion = $conteo;
            $cxcp->pago_anticipo = 1;
            $cxcp->ucrea = $request->id_user;
            $cxcp->created_by = session()->get('usuariosesion')['id'];
            $cxcp->updated_by = session()->get('usuariosesion')['id'];
            $cxcp->save();
            $conteo_anterior=$cxcp->posicion;
            $conteo=1;
            $conteo=1;
            if(count($pos)>0){
                if($pos[0]->secuencial_recibo!==null){
                    $conteo=$pos[0]->secuencial_recibo+1;
                } 
            }
            if(count($pos)>0){
                DB::update("UPDATE punto_emision set secuencial_recibo={$conteo} where  id_punto_emision={$pos[0]->id_punto_emision}");
            }
            

            //recupera el id de la cuenta
            $idcxcp = $cxcp->id_ctas_cobrar_pagos;
            $referencia = null;
            // trae todos los anticipos
            for ($c = 0; $c < count($request->anticipos); $c++) {
                if (isset($request->anticipos[$c]["agregar"])) {
                    if ($request->anticipos[$c]["agregar"]) {
                        //cuando se va a hacer varios pagos o pagos parciales entrara a este if y restara el valor_cobrar con el valor
                        //caso contrario cuando el pago es menoro igual al saldo entra a else y genera el guardado y guarda directamente en el su Ctas_cobrar_pagos
                        if ($valor_pagar > ($request->anticipos[$c]["abono"])) {
                            //cuentas por cobrar recupera y edita los valores
                            $pago = Cuentaporcobrar::findOrFail($request->anticipos[$c]["id_ctascobrar"]);
                            $pago->pagos_por = $request->pagos_por;
                            $pago->id_forma_pagos = $request->forma_pago;
                            $pago->id_banco = $request->banco;
                            $pago->numero_tarjeta = $request->numero_tarjeta;
                            $pago->descuento = $pago->descuento + $request->descuento_pago;
                            $pago->valor_pagado = $pago->valor_pagado + ($request->anticipos[$c]["abono"]);
							$pago->abono = $pago->abono - ($request->anticipos[$c]["abono"]);
                            //$pago->fecha_registro = $request->fecha_registro;
                            $pago->updated_by = session()->get('usuariosesion')['id'];
                            $pago->save();
                            $valor_pagar = $valor_pagar - ($request->anticipos[$c]["abono"]);
                            $v_cuota = $request->anticipos[$c]["abono"];
                            //aqui guarda un string concatenado por clave de acceso, id_ctascobrar, valor_pagado y la id_factura de cuentaporcobrar
                            
                            $referencia .= $request->anticipos[$c]["posicion"]. ";cc:" . $request->anticipos[$c]["id_ctascobrar"] . ";" . number_format($v_cuota, 2, ".", "") . ";".$conteo_anterior.";";
                            
                        } else {
                            $pago = Cuentaporcobrar::findOrFail($request->anticipos[$c]["id_ctascobrar"]);
                            $pago->pagos_por = $request->pagos_por;
                            $pago->id_forma_pagos = $request->forma_pago;
                            $pago->id_banco = $request->banco;
                            $pago->numero_tarjeta = $request->numero_tarjeta;
                            $pago->descuento = $pago->descuento + $request->descuento_pago;
                            $pago->valor_pagado = $pago->valor_pagado + $valor_pagar;
                            $pago->abono = $pago->abono - $valor_pagar;
                            //$pago->fecha_registro = $request->fecha_registro;
                            $pago->updated_by = session()->get('usuariosesion')['id'];
                            $pago->save();
                            //aqui guarda un string concatenado por clave de acceso, id_ctascobrar, valor_pagado y la id_factura de cuentaporcobrar
							$referencia .= $request->anticipos[$c]["posicion"]. ";cc:" . $request->anticipos[$c]["id_ctascobrar"] . ";" . number_format($valor_pagar, 2, ".", "") . ";".$conteo_anterior.";";

                            //nuevamente edita los pagos de cuentas por cobrar y agrega la referencia de cuentaporcobrar separado por ;
                            $ref = substr($referencia, 0, -1);
                            $cxcp = Ctas_cobrar_pagos::findOrFail($idcxcp);
                            $cxcp->referencia = $ref;
                            $cxcp->updated_by = session()->get('usuariosesion')['id'];
                            $cxcp->save();

                            return $idcxcp;
                        }
                    }
                }
            }
        }else{
            //cuentas cobrar pagos genera la cabezera del registro
            $cxcp = new Ctas_cobrar_pagos();
            $cxcp->pagos_por = $request->pagos_por;
            $cxcp->nro_tarjeta = $request->numero_tarjeta;
            $cxcp->valor_seleccionado = $request->valor_select;
            $cxcp->descuento_porcentaje = $request->descuento_porcentaje;
            $cxcp->descuento_pago = $request->descuento_pago;
            $cxcp->valor_real_pago = $request->valor_real;
            $cxcp->id_forma_pagos = $request->forma_pago;
            $cxcp->id_banco = $request->banco;
            $cxcp->id_cliente = $request->id_cliente;
            $cxcp->fecha_pago = $hoy;
            $cxcp->fecha_registro = $request->fecha_registro;
            $cxcp->posicion = $conteo;
            $cxcp->ucrea = $request->id_user;
            $cxcp->created_by = session()->get('usuariosesion')['id'];
            $cxcp->updated_by = session()->get('usuariosesion')['id'];
            $cxcp->save();
            $conteo=1;
            $conteo=1;
            if(count($pos)>0){
                if($pos[0]->secuencial_recibo!==null){
                    $conteo=$pos[0]->secuencial_recibo+1;
                } 
            }
            if(count($pos)>0){
                DB::update("UPDATE punto_emision set secuencial_recibo={$conteo} where  id_punto_emision={$pos[0]->id_punto_emision}");
            }
            

            //recupera el id de la cuenta
            $idcxcp = $cxcp->id_ctas_cobrar_pagos;
            $referencia = null;
            
            $empresa=DB::select("SELECT empresa.* from user,empresa where user.id_empresa=empresa.id_empresa and id={$request->id_user}");
            //dd($request->tabla);
            for ($c = 0; $c < count($request->tabla); $c++) {
                if (isset($request->tabla[$c]["agregar"])) {
                    if ($request->tabla[$c]["agregar"]) {
                        //cuando se va a hacer varios pagos o pagos parciales entrara a este if y restara el valor_cobrar con el valor
                        //caso contrario cuando el pago es menoro igual al saldo entra a else y genera el guardado y guarda directamente en el su Ctas_cobrar_pagos
                        if ($valor_pagar > ($request->tabla[$c]["valor_cuota"] - $request->tabla[$c]["valor_pagado"])) {
                            //cuentas por cobrar recupera y edita los valores
                            $pago = Cuentaporcobrar::findOrFail($request->tabla[$c]["id_ctascobrar"]);
                            $pago->pagos_por = $request->pagos_por;
                            $pago->id_forma_pagos = $request->forma_pago;
                            $pago->id_banco = $request->banco;
                            $pago->numero_tarjeta = $request->numero_tarjeta;
                            $pago->descuento = $pago->descuento + $request->descuento_pago;
                            $pago->valor_pagado = $pago->valor_pagado + ($request->tabla[$c]["valor_cuota"] - $request->tabla[$c]["valor_pagado"]);
                            $pago->fecha_registro = $request->fecha_registro;
                            $pago->updated_by = session()->get('usuariosesion')['id'];
                            $pago->save();
                            $valor_pagar = $valor_pagar - ($request->tabla[$c]["valor_cuota"] - $request->tabla[$c]["valor_pagado"]);
                            $v_cuota = $request->tabla[$c]["valor_cuota"] - $request->tabla[$c]["valor_pagado"];
                            //aqui guarda un string concatenado por clave de acceso, id_ctascobrar, valor_pagado y la id_factura de cuentaporcobrar
                            if (isset($request->tabla[$c]["id_factura"])) {
                                $referencia .= substr($request->tabla[$c]["clave_acceso"], 24, 3) . "-" . substr($request->tabla[$c]["clave_acceso"], 27, 3) . "-" . substr($request->tabla[$c]["clave_acceso"], 30, 9) . ";" . $request->tabla[$c]["id_ctascobrar"] . ";" . number_format($v_cuota, 2, ".", "") . ";" . $request->tabla[$c]["id_factura"] . ";";
                            } else {
                                if (isset($request->tabla[$c]["id_nota_venta"])) {
                                    $referencia .= $request->tabla[$c]["clave_acceso2"] . ";" . $request->tabla[$c]["id_ctascobrar"] . ";" . number_format($v_cuota, 2, ".", "") . ";" . "nv:" . $request->tabla[$c]["id_nota_venta"] . ";";
                                } else {
                                    $referencia .= $request->tabla[$c]["clave_acceso2"] . ";" . $request->tabla[$c]["id_ctascobrar"] . ";" . number_format($v_cuota, 2, ".", "") . ";" . $request->tabla[$c]["id_factura2"] . ";";
                                }
                            }
                        } else {
                            $pago = Cuentaporcobrar::findOrFail($request->tabla[$c]["id_ctascobrar"]);
                            $pago->pagos_por = $request->pagos_por;
                            $pago->id_forma_pagos = $request->forma_pago;
                            $pago->id_banco = $request->banco;
                            $pago->numero_tarjeta = $request->numero_tarjeta;
                            $pago->descuento = $pago->descuento + $request->descuento_pago;
                            $pago->valor_pagado = $pago->valor_pagado + $valor_pagar;
                            $pago->fecha_registro = $request->fecha_registro;
                            $pago->updated_by = session()->get('usuariosesion')['id'];
                            $pago->save();
                            //aqui guarda un string concatenado por clave de acceso, id_ctascobrar, valor_pagado y la id_factura de cuentaporcobrar
                            if (isset($request->tabla[$c]["id_factura"])) {
                                $referencia .= substr($request->tabla[$c]["clave_acceso"], 24, 3) . "-" . substr($request->tabla[$c]["clave_acceso"], 27, 3) . "-" . substr($request->tabla[$c]["clave_acceso"], 30, 9) . ";" . $request->tabla[$c]["id_ctascobrar"] . ";" . number_format($valor_pagar, 2, ".", "") . ";" . $request->tabla[$c]["id_factura"] . ";";
                            } else {
                                if (isset($request->tabla[$c]["id_nota_venta"])) {
                                    $referencia .= substr($request->tabla[$c]["clave_acceso"], 24, 3) . "-" . substr($request->tabla[$c]["clave_acceso"], 27, 3) . "-" . substr($request->tabla[$c]["clave_acceso"], 30, 9) . ";" . $request->tabla[$c]["id_ctascobrar"] . ";" . number_format($valor_pagar, 2, ".", "") . ";" . "nv:" . $request->tabla[$c]["id_nota_venta"] . ";";
                                } else {
                                    $referencia .= $request->tabla[$c]["clave_acceso2"] . ";" . $request->tabla[$c]["id_ctascobrar"] . ";" . number_format($valor_pagar, 2, ".", "") . ";" . $request->tabla[$c]["id_factura2"] . ";";
                                }
                            }

                            //nuevamente edita los pagos de cuentas por cobrar y agrega la referencia de cuentaporcobrar separado por ;
                            $ref = substr($referencia, 0, -1);
                            $cxcp = Ctas_cobrar_pagos::findOrFail($idcxcp);
                            $cxcp->referencia = $ref;
                            $cxcp->updated_by = session()->get('usuariosesion')['id'];
                            $cxcp->save();

                            return $idcxcp;
                        }
                    }
                }
            }
            
            
            
            //nuevamente edita los pagos de cuentas por cobrar y agrega la referencia de cuentaporcobrar separado por ;
            $ref = substr($referencia, 0, -1);
            $cxcp = Ctas_cobrar_pagos::findOrFail($idcxcp);
            $cxcp->referencia = $ref;
            $cxcp->updated_by = session()->get('usuariosesion')['id'];
            $cxcp->save();
            echo("RECIBO 3");
        }

        
    }
    public function recibo_cobro($id,$id_empresa,$tipo){
        //return "RECIBO";
        $cobro=DB::select("SELECT ctas_cobrar_pagos.*,ctas_cobrar_pagos.ucrea as ucrea_cta,cliente.nombre,cliente.identificacion,(select if(descripcion like 'efectivo%','EFECTIVO',descripcion) from forma_pagos where id_forma_pagos=ctas_cobrar_pagos.id_forma_pagos limit 1) as descripcion_fp,(select nombre_banco from banco where id_banco=ctas_cobrar_pagos.id_banco limit 1) as nombre_banco,(select nombre from punto_emision where id_punto_emision=user.id_punto_emision limit 1) as nombre_punto,(select codigo from punto_emision where id_punto_emision=user.id_punto_emision limit 1) codigo_punto from ctas_cobrar_pagos INNER JOIN cliente ON cliente.id_cliente=ctas_cobrar_pagos.id_cliente LEFT JOIN user ON user.id=ctas_cobrar_pagos.ucrea where id_ctas_cobrar_pagos=$id");
        $empresa=DB::select("SELECT * from empresa where id_empresa=$id_empresa");
        $cobros=$cobro[0];
        $empresa=$empresa[0];
        //$pdf = \PDF::loadView('pdf/recibo_cobro', compact("cobros","empresa"));
        $carpeta2 = constant("DATA_EMPRESA") . "$id_empresa/recibo_cobro";
        if (!file_exists($carpeta2)) {
            mkdir($carpeta2, 0755, true);
        }
        $array_cobro=[];
        if(isset($cobro)){
            for($i=0; $i<count($cobro); $i++){
                if(strpos($cobro[$i]->referencia,";")!==false){
                    $data = explode(";", $cobro[$i]->referencia);
                    //devide los registros en 4 ya que son 4 los reg existentes e referencias
                    $registros = count($data)/4;
                    $salto = 0;
                    //recorre las referencias por 4 para obtener todos los registros existentes
                    for($f=0; $f<$registros; $f++){
                        $comp = $data[0+$salto];
                        $id_cb = $data[1+$salto];
                        $valor = $data[2+$salto];
                        $idf = $data[3+$salto];
                        if($comp){
                            array_push($array_cobro,$comp);
                        }
                        //hace el salto de 4
                        $salto = $salto + 4;
                    }
                }else{
                    array_push($array_cobro,"Anticipo");
                }
            }
        }
        if(count($array_cobro)>0){
            $array_cobro=array_unique($array_cobro);
            $array_cobro=implode(", ",$array_cobro);
        }else{
            $array_cobro="";
        }
        
        
        if($tipo=="v"){
            $Reportes = new generarReportes();
            $strPDF = $Reportes->ReciboCobro($cobros,$empresa,$array_cobro,$carpeta2);
            return response($strPDF, 200)->header('Content-Type', 'application/pdf');
        }else{
            $Reportes = new generarReportes();
            $strPDF = $Reportes->ReciboCobro($cobros,$empresa,$array_cobro);
            return response($strPDF, 200)->header('Content-Type', 'application/pdf');
        }
        
        // if ($tipo == 'd') {
        //     return $pdf->setWarnings(false)->save(constant("DATA_EMPRESA") . "$id_empresa/recibo_cobro/cobro_".$id."_{$cobros->posicion}.pdf")->download("cobro_".$id."_{$cobros->posicion}.pdf");
        // } else {
        //     return $pdf->setWarnings(false)->save(constant("DATA_EMPRESA") . "$id_empresa/recibo_cobro/cobro_".$id."_{$cobros->posicion}.pdf")->stream("cobro_".$id."_{$cobros->posicion}.pdf");
        // }
    }
    public function recibo_cobro_anticipo($id,$id_empresa,$tipo){
        //return "RECIBO";
        $cobro=DB::select("SELECT ctas_cobrar.*,ctas_cobrar.created_by as ucrea_cta,ctas_cobrar.valor_cuota as valor_real_pago,null as  descuento_pago,null as nro_tarjeta,null as id_banco,concat('Anticipo:',$id) as id_ctas_cobrar_pagos,cliente.nombre,cliente.identificacion,'Anticipo' as descripcion_fp,null as nombre_banco,(select nombre from punto_emision where id_punto_emision=user.id_punto_emision limit 1) as nombre_punto,(select codigo from punto_emision where id_punto_emision=user.id_punto_emision limit 1) codigo_punto from ctas_cobrar INNER JOIN cliente ON cliente.id_cliente=ctas_cobrar.id_cliente LEFT JOIN user ON user.id=ctas_cobrar.created_by where id_ctascobrar=$id");
        $empresa=DB::select("SELECT * from empresa where id_empresa=$id_empresa");
        $cobros=$cobro[0];
        $empresa=$empresa[0];
        //$pdf = \PDF::loadView('pdf/recibo_cobro', compact("cobros","empresa"));
        $carpeta2 = constant("DATA_EMPRESA") . "$id_empresa/recibo_cobro";
        if (!file_exists($carpeta2)) {
            mkdir($carpeta2, 0755, true);
        }
        $array_cobro=" Anticipo";

        
        
        if($tipo=="v"){
            $Reportes = new generarReportes();
            $strPDF = $Reportes->ReciboCobro($cobros,$empresa,$array_cobro,$carpeta2);
            return response($strPDF, 200)->header('Content-Type', 'application/pdf');
        }else{
            $Reportes = new generarReportes();
            $strPDF = $Reportes->ReciboCobro($cobros,$empresa,$array_cobro);
            return response($strPDF, 200)->header('Content-Type', 'application/pdf');
        }
        
        // if ($tipo == 'd') {
        //     return $pdf->setWarnings(false)->save(constant("DATA_EMPRESA") . "$id_empresa/recibo_cobro/cobro_".$id."_{$cobros->posicion}.pdf")->download("cobro_".$id."_{$cobros->posicion}.pdf");
        // } else {
        //     return $pdf->setWarnings(false)->save(constant("DATA_EMPRESA") . "$id_empresa/recibo_cobro/cobro_".$id."_{$cobros->posicion}.pdf")->stream("cobro_".$id."_{$cobros->posicion}.pdf");
        // }
    }
    function llamartablavalores(Request $rq)
    {
        //lama la cuenta por cobrar mediante su id y obtiene el id del cliente para llamar sus datos
        //dd($rq->referencia);
        $ref = $rq->referencia[1];
        $clientes = DB::select("SELECT * FROM ctas_cobrar WHERE id_ctascobrar = $ref");
        $pagos = DB::select("SELECT * from ctas_cobrar_pagos where id_ctas_cobrar_pagos={$rq->id_ctas_cobrar_pagos}");
        $array_ctas = [];
        for ($x = 0; $x < count($pagos); $x++) {
            $data = explode(";", $pagos[$x]->referencia);
            $registros = count($data) / 4;
            $salto = 0;
            for ($f = 0; $f < $registros; $f++) {
                $id_cb = $data[1 + $salto];
                $valor = $data[2 + $salto];
                $idf = $data[3 + $salto];
                if ($id_cb) {
                    //actualiza los valores de cuentas por cobrar el valor pagado
                    array_push($array_ctas, $id_cb);
                }
                //hace el salto de los 4
                $salto = $salto + 4;
            }
        }
        $array_ctas = implode(",", $array_ctas);
        $cliente = $clientes[0]->id_cliente;
        // if($array_ctas){
        //     $recupera = DB::select("SELECT *, (select nombre from cliente where id_cliente = ctas_cobrar.id_cliente) as nombre, (select clave_acceso from factura where id_factura = ctas_cobrar.id_factura) as clave_acceso, referencias AS  id_factura2, CONCAT('001-001-',LPAD(referencias,9,'0')) AS  clave_acceso2 from ctas_cobrar where ctas_cobrar.id_cliente = $cliente AND ctas_cobrar.tipo = 1 AND ctas_cobrar.id_ctascobrar in ({$array_ctas})");
        // }else{
        //     $recupera = DB::select("SELECT *, (select nombre from cliente where id_cliente = ctas_cobrar.id_cliente) as nombre, (select clave_acceso from factura where id_factura = ctas_cobrar.id_factura) as clave_acceso, referencias AS  id_factura2, CONCAT('001-001-',LPAD(referencias,9,'0')) AS  clave_acceso2 from ctas_cobrar where ctas_cobrar.id_cliente = $cliente AND ctas_cobrar.tipo = 1");
        // }
        $query = "SELECT  cta.id_ctascobrar,ctap.referencia,if(ctap.fecha_registro is null,ctap.fecha_pago,ctap.fecha_registro) as fecha_pago,cta.num_cuota,(select nombre from cliente where id_cliente = cta.id_cliente) as nombre,if((select clave_acceso from factura where id_factura = cta.id_factura) is null,(select clave_acceso from nota_venta where id_nota_venta = cta.id_nota_venta),(select clave_acceso from factura where id_factura = cta.id_factura)) as clave_acceso, referencias AS  id_factura2, CONCAT('001-001-',LPAD(referencias,9,'0')) AS  clave_acceso2 ,
        if(round(SUBSTRING(ctap.referencia,POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2,locate(';',ctap.referencia,POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2)-(POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2)),2)>ctap.valor_real_pago,ctap.valor_real_pago,round(SUBSTRING(ctap.referencia,POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2,locate(';',ctap.referencia,POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2)-(POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2)),2)) as valor_cuota,0 as valor_pagado,cta.id_factura,cta.id_nota_venta
        from ctas_cobrar_pagos as ctap,ctas_cobrar as cta where ctap.id_cliente={$cliente} and cta.id_cliente={$cliente} and ctap.id_ctas_cobrar_pagos={$rq->id_ctas_cobrar_pagos} and ctap.referencia like CONCAT('%;',cta.id_ctascobrar,';%')";
        //dd($query);
        $recupera = DB::select($query);
        return $recupera;
    }
    public function getClientes()
    {
        //recupera los clientes e imprime como json
        $data = Cliente::get();

        return response()->json($data);
    }
    public function reporteCuentasCobrar(Request $request)
    {
        $queries = [];
        $fields = [];
        $inners = [];
        $clients = [];
        $pagos = [];
        $anticipo = [];
        $anticipo_pagos = [];
        $ctas_pagos = [];
        $initial = null;
        $final = null;
        $fecha_inicio = DB::select("SELECT min(fecha_pago) as fecha_inicio from ctas_cobrar,cliente where ctas_cobrar.id_cliente=cliente.id_cliente and cliente.id_empresa=" . $request->company);
        $fecha_crea_cta = DB::select("SELECT min(ctas_cobrar.fcrea) as fecha_inicio from ctas_cobrar,cliente where ctas_cobrar.id_cliente=cliente.id_cliente and cliente.id_empresa=" . $request->company);
        $fecha_inicio_pagos = DB::select("SELECT min(ctas_cobrar.fmodifica) as fecha_inicio from ctas_cobrar,cliente where ctas_cobrar.id_cliente=cliente.id_cliente and cliente.id_empresa=" . $request->company);
        $fecha_inicio_factura = DB::select("SELECT min(fecha_emision) as fecha_inicio from factura where factura.id_empresa=" . $request->company);
        $fecha_inicio_ctas_total = DB::select("SELECT min(ctas_cobrar.fecha_factura) as fecha_inicio from ctas_cobrar,cliente where ctas_cobrar.id_cliente=cliente.id_cliente and cliente.id_empresa=" . $request->company);
        $fecha_inicio_ctas_cobrar_fecha_pagos = DB::select("SELECT min(ctas_cobrar.fecha_pago) as fecha_inicio from ctas_cobrar_pagos as ctas_cobrar,cliente where ctas_cobrar.id_cliente=cliente.id_cliente and cliente.id_empresa=" . $request->company);
        $fecha_inicio_ctas_cobrar_fecha_registro = DB::select("SELECT min(ctas_cobrar.fecha_registro) as fecha_inicio from ctas_cobrar_pagos as ctas_cobrar,cliente where ctas_cobrar.id_cliente=cliente.id_cliente and cliente.id_empresa=" . $request->company);
        $fecha_inicio_ctas_cobrar_fecha_registro_ctas = DB::select("SELECT min(ctas_cobrar.fecha_registro) as fecha_inicio from  ctas_cobrar,cliente where ctas_cobrar.id_cliente=cliente.id_cliente and cliente.id_empresa=" . $request->company);
        $fecha_inicio_nota_venta = DB::select("SELECT min(fecha_emision) as fecha_inicio from nota_venta where nota_venta.id_empresa=" . $request->company);
        $fecha_inicio_anticipo = DB::select("SELECT min(if(fecha_registro is null,fecha_pago,fecha_registro)) as fecha_inicio from ctas_cobrar INNER JOIN cliente ON cliente.id_cliente=ctas_cobrar.id_cliente where ctas_cobrar.tipo=3 and cliente.id_empresa=" . $request->company);
        $info_reporte = json_decode($request->reporte, true);
        //dd($request);
        $reporte4 = DB::select("SELECT email_empresa,password,servidor_correo,puerto_correo,seguridad_correo,id_empresa from empresa where id_empresa=" . $request->company);
        if ($info_reporte["id"] != 0) {
            if ($info_reporte["id"] == 1) {
                //dd($request);
                $info_date = json_decode($request->date, true);
                if ($request->date) {


                    if ($request->currentDate !== "true") {
                        $initial = str_replace("-010-", "-10-", $info_date["range"]["initial"]);
                        $final = str_replace("-010-", "-10-", $info_date["range"]["final"]);
                        //dd($final);
                        if ($info_date["option"] == 1) {
                            array_push($queries, "date(cta.fecha_pago) between date('{$info_date["range"]["initial"]}') and date('{$final}')\n");
                        }
                        if ($info_date["option"] == 2) {
                            array_push($queries, "date(cta.fecha_pago) between date('{$info_date["range"]["initial"]}') and date('{$final}')\n");
                        }
                        if ($info_date["option"] == 3) {
                            array_push($queries, "date(cta.fecha_factura) between date('{$fecha_inicio_ctas_total[0]->fecha_inicio}') and date('{$final}')\n");
                            array_push($pagos, "((date(ctap.fecha_pago) between date('{$fecha_inicio_ctas_cobrar_fecha_pagos[0]->fecha_inicio}') and date('{$final}')) or
                                                      (date(ctap.fecha_registro) between date('{$fecha_inicio_ctas_cobrar_fecha_registro[0]->fecha_inicio}') and date('{$final}')))\n");
                        }
                    } else {
                        $initial = str_replace("-010-", "-10-", $info_date["value"]);
                        $final = str_replace("-010-", "-10-", $info_date["value"]);
                        if ($info_date["option"] == 1) {
                            array_push($queries, "date(cta.fecha_pago) = date('{$info_date["value"]}')\n");
                        }
                        if ($info_date["option"] == 2) {
                            array_push($queries, "date(cta.fecha_pago) = date('{$info_date["value"]}')\n");
                        }
                        if ($info_date["option"] == 3) {
                            if ($request->company == 59) {
                                //array_push($queries, "date(cta.fecha_pago) = date('{$info_date["value"]}')\n");
                                array_push($queries, "cta.fecha_factura like '{$final}%'\n");
                                array_push($pagos, "((ctap.fecha_pago like'{$final}%') or
                                                         (ctap.fecha_registro like '{$final}%'))\n");
                            } else {
                                //array_push($queries, "date(cta.fecha_pago) = date('{$info_date["value"]}')\n");
                                array_push($queries, "date(cta.fecha_factura) between date('{$fecha_inicio_ctas_total[0]->fecha_inicio}') and date('{$final}')\n");
                                array_push($pagos, "((date(ctap.fecha_pago) between date('{$fecha_inicio_ctas_cobrar_fecha_pagos[0]->fecha_inicio}') and date('{$final}')) or
                                                        (date(ctap.fecha_registro) between date('{$fecha_inicio_ctas_cobrar_fecha_registro[0]->fecha_inicio}') and date('{$final}')))\n");
                            }
                        }
                    }
                }
                if ($request->selectedEstablishment) {
                    $info_establishment = json_decode($request->selectedEstablishment);
                    array_push($queries, "f.id_establecimiento = {$info_establishment->id}\n");
                }
                if ($request->selectedPointOfEmission) {
                    $info_point_emission = json_decode($request->selectedPointOfEmission);
                    array_push($queries, "f.id_punto_emision = {$info_point_emission->id}\n");
                }
                if ($request->selectedProject) {
                    $info_project = json_decode($request->selectedProject);
                    array_push($queries, "f.id_proyecto = {$info_project->id}\n");
                }
                if ($request->client) {
                    $info_client = json_decode($request->client);
                    $exists_client = true;
                    array_push($queries, "cliente.id_cliente = {$info_client->id}\n");
                    array_push($pagos, "ctap.id_cliente = {$info_client->id}\n");
                } else {
                    $exists_client = false;
                }
                $nombre_vendedor = "";
                /*if ($request->rol_user !== "2") {
                    

                    if ($request->user) {

                        $info_user = json_decode($request->user, true);
                        
                        if ($info_user["id"] != 0) {
                        
                            //array_push($queries, "(f.created_by = {$info_seller["id"]})\n");
                                    array_push($queries, "cta.created_by = {$info_user["id"]}\n");
                                    array_push($pagos, "ctap.created_by = {$info_user["id"]}\n");
                            
                        }
                         else{
                             if ($vnd) {
                                 array_push($queries, "((f.id_vendedor = {$vnd[0]->id_vendedor}) or
                                                         (nv.id_vendedor = {$vnd[0]->id_vendedor}))\n");
                                 array_push($pagos, "((f.id_vendedor = {$vnd[0]->id_vendedor}) or
                                                         (nv.id_vendedor = {$vnd[0]->id_vendedor}))\n");
                                 $nombre_vendedor=$vnd[0]->nombre_vendedor;
                             }
                         }
                    }
                } else {
                    dd("Entro al vendedor");
                    $vnd = DB::select("SELECT *,concat(nombres,' ',apellidos) as nombre_vendedor from user where id=" . $request->user);
                    if (count($vnd)>0) {

                        
                        array_push($queries, "cta.created_by = {$vnd[0]->id}\n");
                        array_push($pagos, "ctap.created_by = {$vnd[0]->id}\n");
                         
                    }
                }*/
                if($request->rol_user !== "2"){
                    if ($request->vendedor) {
                        $info_user = json_decode($request->vendedor, true);
                        
                        if ($info_user["id"] != 0) {
                            array_push($queries, "cliente.id_vendedor = {$info_user["id"]}\n");
                            array_push($pagos, "((fact.id_vendedor={$info_user["id"]}) or
                                                        (nv.id_vendedor={$info_user["id"]}))\n");
                        }
                    }
                }else{
                    if ($request->vendedor) {
                        $info_user = json_decode($request->vendedor, true);
                        
                        if ($info_user["id"] != 0) {
                            array_push($queries, "cliente.id_vendedor = {$info_user["id"]}\n");
                            array_push($pagos, "((fact.id_vendedor={$info_user["id"]}) or
                                                        (nv.id_vendedor={$info_user["id"]}))\n");
                        }
                    }
                    // $vnd = DB::select("SELECT * from vendedor where id_user=" . $request->usuario);
                    // if(count($vnd)>0){
                    //     $array_vnd=[];
                    //     foreach($vnd as $detail){
                    //         array_push($array_vnd,$detail->id_vendedor);
                    //     }
                    //     $array_vnd=implode(",",$array_vnd);
                    //     array_push($queries, "cliente.id_vendedor in ({$array_vnd})\n");
                    //     array_push($pagos, "((fact.id_vendedor in ({$array_vnd})) or
                    //                                     (nv.id_vendedor in ({$array_vnd})))\n");
                    // }
                    
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
                    array_push($queries, "cta.valor_cuota {$typeSearch} {$request->totalCount}\n");
                }
                if ($request->wayToPay) {
                    $info_payment = json_decode($request->wayToPay, true);
                    if ($info_payment["id"] != 0) {
                        array_push($queries, "cta.id_forma_pagos = {$info_payment["id"]}\n");
                        array_push($pagos, "ctap.id_forma_pagos = {$info_payment["id"]}\n");
                    }
                }
                $fields = implode("", $fields);
                $queries = implode(" AND ", $queries);
                $pagos = implode(" and ", $pagos);
                $query =
                    "SELECT cta.created_by as ctacreated_by, cta.updated_by as ctaupdated_by, cliente.id_vendedor as cid_vendedor, cliente.created_by as ccreated_by, cliente.updated_by as cupdated_by, sum(valor_cuota) as valor_cuota,cliente.id_cliente,cliente.nombre,cliente.identificacion,sum(if(valor_cuota>=valor_pagado,valor_pagado,valor_cuota)) as valor_pago,sum(valor_cuota)-sum(if(valor_cuota>=valor_pagado,valor_pagado,valor_cuota)) as descuento,
                    empresa.nombre_empresa,empresa.id_empresa,empresa.logo
                    FROM ctas_cobrar as cta,cliente,empresa
                    where {$queries} and cta.tipo=1 and cta.id_cliente=cliente.id_cliente and cliente.id_empresa=empresa.id_empresa and cliente.id_empresa={$request->company} GROUP BY cta.id_cliente Order by cliente.nombre
                ";
                $empresa = DB::select("SELECT * from empresa where id_empresa=" . $request->company);
                //dd($query);
                $reporte = DB::select($query);
                if (session()->get('usuariosesion')['filtro_list'] == 1 || session()->get('usuariosesion')['id_rol'] == 2) {
                    $dat = [];
                    foreach ($reporte as $report) {
                        if ($report->ctacreated_by == session()->get('usuariosesion')['id'] || $report->ctaupdated_by == session()->get('usuariosesion')['id'] || $report->cid_vendedor == session()->get('usuariosesion')['id_vendedor'] || $report->ccreated_by == session()->get('usuariosesion')['id'] || $report->cupdated_by == session()->get('usuariosesion')['id']) {
                            array_push($dat, $report);
                        }
                    }
                    $reporte = $dat;
                }
                $valores_pagado =
                    // "SELECT sum(if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota)) as valor_pagado,referencias,fact.id_factura,cta.id_cliente
                    // from ctas_cobrar as cta
                    // 						INNER JOIN cliente as pro
                    //                         on pro.id_cliente=cta.id_cliente
                    //                         LEFT JOIN factura as fact
                    //                         on fact.id_factura=cta.id_factura
                    // where {$pagos}  and pro.id_empresa={$request->company} and cta.tipo=1 and (referencias is not null or fact.id_factura is not null)
                    // 						GROUP BY fact.id_factura,cta.referencias,cta.id_cliente
                    //                         ORDER BY max(cta.fecha_factura) asc";
                    "SELECT sum(if(round(SUBSTRING(ctap.referencia,POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2,locate(';',ctap.referencia,POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2)-(POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2)),2)>ctap.valor_real_pago,ctap.valor_real_pago,round(SUBSTRING(ctap.referencia,POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2,locate(';',ctap.referencia,POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2)-(POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2)),2))) as  valor_pagado,cta.id_factura,cta.referencias,cta.id_cliente
                        from ctas_cobrar as cta
                        INNER JOIN ctas_cobrar_pagos as ctap
                        on ctap.id_cliente=cta.id_cliente
                        INNER JOIN forma_pagos
                        on forma_pagos.id_forma_pagos=ctap.id_forma_pagos
                        LEFT JOIN factura as fact
                        on fact.id_factura=cta.id_factura
                        LEFT JOIN nota_venta as nv
                        on nv.id_nota_venta=cta.id_nota_venta
                                                    where {$pagos}  and forma_pagos.id_empresa={$request->company} and cta.tipo=1 and ctap.referencia like concat('%;',cta.id_ctascobrar,';%') and ctap.pago_anticipo is null
                                                    GROUP BY cta.id_cliente
                                                    ORDER BY (select nombre from cliente where id_cliente=cta.id_cliente) asc";
                //dd($valores_pagado);
                $reporte_valores_pagado = DB::select($valores_pagado);
                $Reportes = new generarReportes();
                //dd($query." REPORTE VALORES PAGADO".$valores_pagado);
                if (!$reporte) {
                    return response('no-data-report', 200)->header('Content-Type', 'application/json');
                } else {
                    //$strPDF = $Reportes->cuentasPorCobrar($reporte, $request->date, $request->date);
                    if ($request->email && $request->destinatario) {
                        $Reportes = new generarReportes();
                        $ruta = constant("DATA_EMPRESA") . $request->company . '/facturacion/cuentas_cobrar';
                        if (!file_exists($ruta)) {
                            mkdir($ruta, 0755, true);
                        }
                        if ($request->company == 59) {
                            if ($request->currentDate == "true") {
                                $strPDF = $Reportes->cuentasPorCobrar($reporte, $reporte_valores_pagado, $final, $final, $empresa[0], $ruta);
                            } else {
                                $strPDF = $Reportes->cuentasPorCobrar($reporte, $reporte_valores_pagado, $fecha_inicio[0]->fecha_inicio, $final, $empresa[0], $ruta);
                            }
                        } else {
                            $strPDF = $Reportes->cuentasPorCobrar($reporte, $reporte_valores_pagado, $fecha_inicio[0]->fecha_inicio, $final, $empresa[0], $ruta);
                        }

                        $email = new sendEmail();
                        $email->enviarCtasCobrar($reporte4[0], $request->email, $request->destinatario, "Cliente");
                        $cta = $ruta . '/cuenta_por_cobrar_Cliente.pdf';
                        /*if(file_exists($cta)){
                                unlink($cta);
                            }*/
                        return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                    } else {
                        $Reportes = new generarReportes();
                        if ($request->company == 59) {
                            if ($request->currentDate == "true") {
                                $strPDF = $Reportes->cuentasPorCobrar($reporte, $reporte_valores_pagado, $final, $final, $empresa[0]);
                            } else {
                                $strPDF = $Reportes->cuentasPorCobrar($reporte, $reporte_valores_pagado, $fecha_inicio[0]->fecha_inicio, $final, $empresa[0]);
                            }
                        } else {
                            $strPDF = $Reportes->cuentasPorCobrar($reporte, $reporte_valores_pagado, $fecha_inicio[0]->fecha_inicio, $final, $empresa[0]);
                        }

                        return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                    }


                    //return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                }
            }
            if ($info_reporte["id"] == 2) {
                //dd($request);
                $info_date = json_decode($request->date, true);
                if ($request->date) {

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
                            array_push($queries, "((date(fact.fecha_emision) between date('{$fecha_inicio_factura[0]->fecha_inicio}') and date('{$final}')) or 
                                                    (date(nv.fecha_emision) between date('{$fecha_inicio_nota_venta[0]->fecha_inicio}') and date('{$final}')))\n");
                            array_push($fields, "date(cta.fcrea) between date('{$fecha_crea_cta[0]->fecha_inicio}') and date('{$final}')\n");
                            array_push($inners, "date(cta.fecha_factura) between date('{$fecha_inicio_ctas_total[0]->fecha_inicio}') and date('{$final}')\n");
                            array_push($pagos, "date(cta.fmodifica) between date('{$fecha_inicio_pagos[0]->fecha_inicio}') and date('{$final}')\n");
                            array_push($ctas_pagos, "((date(ctap.fecha_pago) between date('{$fecha_inicio_ctas_cobrar_fecha_pagos[0]->fecha_inicio}') and date('{$final}')) or
                                                      (date(ctap.fecha_registro) between date('{$fecha_inicio_ctas_cobrar_fecha_registro[0]->fecha_inicio}') and date('{$final}')))\n");
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
                            array_push($queries, "((date(fact.fecha_emision) between date('{$fecha_inicio_factura[0]->fecha_inicio}') and date('{$hoy}')) or 
                                                        (date(nv.fecha_emision) between date('{$fecha_inicio_factura[0]->fecha_inicio}') and date('{$hoy}')))\n");
                            array_push($fields, "date(cta.fcrea) between date('{$fecha_crea_cta[0]->fecha_inicio}') and date('{$hoy}')\n");
                            array_push($inners, "date(cta.fecha_factura) between date('{$fecha_inicio_ctas_total[0]->fecha_inicio}') and date('{$hoy}')\n");
                            array_push($pagos, "date(cta.fmodifica) between date('{$fecha_inicio_pagos[0]->fecha_inicio}') and date('{$hoy}')\n");
                            array_push($ctas_pagos, "((date(ctap.fecha_pago) between date('{$fecha_inicio_ctas_cobrar_fecha_pagos[0]->fecha_inicio}') and date('{$hoy}')) or
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
                // if ($request->user) {
                //     $info_user = json_decode($request->user, true);
                //     if ($info_user["id"] != 0) {
                //         array_push($queries, "cta.ucrea = {$info_user["id"]}\n");
                //         array_push($fields, "cta.ucrea = {$info_user["id"]}\n");
                //         array_push($inners, "cta.ucrea = {$info_user["id"]}\n");
                //         array_push($clients, "cta.ucrea = {$info_user["id"]}\n");
                //         array_push($pagos, "cta.ucrea = {$info_user["id"]}\n");
                //         //array_push($ctas_pagos, "cta.ucrea = {$info_user["id"]}\n");
                //     }
                // }
                $nombre_vendedor = "";
                if ($request->rol_user !== "2") {
                    

                    if ($request->user) {

                        $info_user = json_decode($request->user, true);
                        
                        if ($info_user["id"] != 0) {
                        
                            //array_push($queries, "(f.created_by = {$info_seller["id"]})\n");
                                    array_push($queries, "cta.created_by = {$info_user["id"]}\n");
                                    array_push($fields, "cta.created_by = {$info_user["id"]}\n");
                                    array_push($inners, "cta.created_by = {$info_user["id"]}\n");
                                    array_push($clients, "cta.created_by = {$info_user["id"]}\n");
                                    array_push($pagos, "cta.created_by = {$info_user["id"]}\n");
                            
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
                if($request->rol_user !== "2"){
                    if ($request->vendedor) {
                        $info_user = json_decode($request->vendedor, true);
                        
                        if ($info_user["id"] != 0) {
                            array_push($queries, "((fact.id_vendedor={$info_user["id"]}) or
                                                        (nv.id_vendedor={$info_user["id"]}))\n");
                            array_push($pagos, "((fact.id_vendedor={$info_user["id"]}) or
                                                        (nv.id_vendedor={$info_user["id"]}))\n");
                            array_push($inners, "((fact.id_vendedor={$info_user["id"]}) or
                                                        (nv.id_vendedor={$info_user["id"]}))\n");
                        }
                    }
                }else{
                    if ($request->vendedor) {
                        $info_user = json_decode($request->vendedor, true);
                        
                        if ($info_user["id"] != 0) {
                            array_push($queries, "((fact.id_vendedor={$info_user["id"]}) or
                                                        (nv.id_vendedor={$info_user["id"]}))\n");
                            array_push($pagos, "((fact.id_vendedor={$info_user["id"]}) or
                                                        (nv.id_vendedor={$info_user["id"]}))\n");
                            array_push($inners, "((fact.id_vendedor={$info_user["id"]}) or
                                                        (nv.id_vendedor={$info_user["id"]}))\n");
                        }
                    }
                    // $vnd = DB::select("SELECT * from vendedor where id_user=" . $request->usuario);
                    // if(count($vnd)>0){
                    //     $array_vnd=[];
                    //     foreach($vnd as $detail){
                    //         array_push($array_vnd,$detail->id_vendedor);
                    //     }
                    //     $array_vnd=implode(",",$array_vnd);
                    //     array_push($queries, "((fact.id_vendedor in ({$array_vnd})) or
                    //                                 (nv.id_vendedor in ({$array_vnd})))\n");
                    //     array_push($pagos, "((fact.id_vendedor in ({$array_vnd})) or
                    //                                 (nv.id_vendedor in ({$array_vnd})))\n");
                    //     array_push($inners, "((fact.id_vendedor in ({$array_vnd})) or
                    //                                 (nv.id_vendedor in ({$array_vnd})))\n");
                    // }
                    
                }
                if ($request->wayToPay) {
                    $info_payment = json_decode($request->wayToPay, true);
                    if ($info_payment["id"] != 0) {
                        array_push($queries, "cta.id_forma_pagos = {$info_payment["id"]}\n");
                        array_push($fields, "cta.id_forma_pagos = {$info_payment["id"]}\n");
                        array_push($inners, "cta.id_forma_pagos = {$info_payment["id"]}\n");
                        array_push($ctas_pagos, "ctap.id_forma_pagos = {$info_payment["id"]}\n");
                    }
                }
                if ($request->client) {
                    $info_client = json_decode($request->client);
                    $exists_client = true;
                    array_push($queries, "pro.id_cliente = {$info_client->id}\n");
                    array_push($fields, "pro.id_cliente = {$info_client->id}\n");
                    array_push($inners, "pro.id_cliente = {$info_client->id}\n");
                    array_push($clients, "pro.id_cliente = {$info_client->id}\n");
                    array_push($pagos, "cta.id_cliente = {$info_client->id}\n");
                    array_push($ctas_pagos, "ctap.id_cliente = {$info_client->id}\n");
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
                    array_push($queries, "cta.valor_cuota {$typeSearch} {$request->totalCount}\n");
                    array_push($fields, "cta.valor_cuota {$typeSearch} {$request->totalCount}\n");
                    array_push($inners, "cta.valor_cuota {$typeSearch} {$request->totalCount}\n");
                    array_push($clients, "cta.valor_cuota {$typeSearch} {$request->totalCount}\n");
                    array_push($pagos, "cta.valor_cuota {$typeSearch} {$request->totalCount}\n");
                    //array_push($ctas_pagos, "cta.valor_cuota {$typeSearch} {$request->totalCount}\n");
                }
                $queries = implode(" and ", $queries);
                $inners = implode(" and ", $inners);
                $fields = implode(" and ", $fields);
                $clients = implode(" and ", $clients);
                $pagos = implode(" and ", $pagos);
                $ctas_pagos = implode(" and ", $ctas_pagos);
                $query = "SELECT sum(valor_cuota) as valor_cuota,cta.id_factura,cta.id_nota_venta,if(fact.clave_acceso is null,nv.clave_acceso,fact.clave_acceso) as observacion,if(fact.fecha_emision is null,nv.fecha_emision,fact.fecha_emision) as fecha_emision,max(cta.fecha_pago) as fecha_pago,cta.id_cliente,emp.logo,emp.id_empresa,emp.nombre_empresa,if(now()>max(cta.fecha_pago),'si','no') as vencido
                from ctas_cobrar as cta -- ,empresa as emp,cliente as pro
								INNER JOIN cliente as pro
								on pro.id_cliente=cta.id_cliente
								INNER JOIN empresa as emp
								on emp.id_empresa=pro.id_empresa
								LEFT JOIN factura as fact
								on fact.id_factura=cta.id_factura
								LEFT JOIN nota_venta as nv
								on nv.id_nota_venta=cta.id_nota_venta
                where
                pro.id_empresa={$request->company} and
                {$queries} and cta.tipo=1 and cta.valor_cuota>=cta.valor_pagado
                
                GROUP BY cta.id_factura,cta.id_nota_venta  ORDER BY fact.clave_acceso,nv.clave_acceso asc";/*"select DISTINCT id_factura_compra,fact.observacion,fact.total_factura as valor_cuota,cta.id_proveedor,emp.nombre_empresa,emp.id_empresa,emp.logo,fact.fech_emision,
                fact.fech_validez as fecha_pago
                from ctas_pagar as cta,factura_compra as fact,empresa as emp
                where
                {$queries} and
                cta.id_factura_compra=fact.id_factcompra and
                fact.id_empresa=emp.id_empresa";*/

                $query2 = "SELECT numero_tarjeta,if(valor_cuota>=valor_pagado,valor_pagado,valor_cuota) as valor_pagado,cta.id_factura,cta.id_nota_venta,referencias,form.descripcion,cta.pagos_por,cta.fecha_pago,cta.fmodifica
                from ctas_cobrar as cta -- ,forma_pagos as form,cliente as pro,factura as fact
								INNER JOIN cliente as pro
								on pro.id_cliente=cta.id_cliente
								INNER JOIN forma_pagos as form
								on cta.id_forma_pagos=form.id_forma_pagos
								LEFT JOIN factura as fact
								on cta.id_factura=fact.id_factura
								LEFT JOIN nota_venta as nv
								on cta.id_nota_venta=nv.id_nota_venta
                where {$pagos} and valor_pagado>0 and cta.tipo=1 and pro.id_empresa={$request->company}";
                //dd($query2);
                $cta_cobrar_pago = DB::select("SELECT ctap.*
                from ctas_cobrar_pagos as ctap,cliente as pro
                where {$ctas_pagos} and pro.id_cliente=ctap.id_cliente and ctap.pagos_por<>'Anticipo' and pro.id_empresa=" . $request->company);

                //$query_forma_pago="SELECT cta.* from ctas_cobrar_pagos as ctap,ctas_cobrar as cta where {$pagos} ";
                $query_forma_pago = "SELECT DISTINCT cta.valor_cuota,cta.valor_pagado,if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota) as saldo,cta.numero_tarjeta,cta.numero_transaccion,cta.id_factura,cta.id_nota_venta,cta.referencias,forma_pagos.descripcion,ctap.fecha_pago,factura.observacion
                                from ctas_cobrar_pagos as ctap,ctas_cobrar as cta
                                LEFT JOIN forma_pagos
                                on forma_pagos.id_forma_pagos=cta.id_forma_pagos
                                LEFT JOIN factura
                                on factura.id_factura=cta.id_factura
								LEFT JOIN nota_venta as nv
								on nv.id_nota_venta=cta.id_nota_venta
                where cta.valor_pagado>0 and cta.tipo=1 and {$pagos} and (cta.id_factura is not null or cta.referencias is not null or cta.id_nota_venta is not null) ";
                //dd($query_forma_pago);
                $ctas_id = [];
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
                            //array_push($ctas_id,"cta.id_ctascobrar={$id_cb}\n");
                            array_push($ctas_id, "ctap.referencia like '%;{$id_cb};%'\n");
                            $salto = $salto + 4;
                        }
                    }
                }
                $ctas_id = implode(" or ", $ctas_id);

                //dd($ctas_id);
                if ($ctas_id) {
                    $query_forma_pago .= " and ({$ctas_id})";
                }
                //dd($query_forma_pago);
                $reporte = DB::select($query);
                $reporte2 = DB::select($query2);
                $query_pago_ref =
                    // "SELECT numero_tarjeta,if(valor_cuota>=valor_pagado,valor_pagado,valor_cuota) as valor_pagado,referencias,cta.id_factura,fact.clave_acceso as observacion,form.descripcion,cta.pagos_por,cta.fecha_pago,cta.fmodifica,cta.id_cliente
                    // from ctas_cobrar as cta
                    // INNER JOIN forma_pagos as form
                    // on form.id_forma_pagos=cta.id_forma_pagos
                    // INNER JOIN cliente as pro
                    // on pro.id_cliente=cta.id_cliente
                    // LEFT JOIN factura as fact
                    // on fact.id_factura=cta.id_factura
                    // where {$pagos} and valor_pagado>0 and cta.tipo=1 and pro.id_empresa={$request->company}";
                    ///////////////////////////////////////////////////cambio 27-11-2020
                    // SELECT cta.id_ctascobrar,ctap.id_ctas_cobrar_pagos,ctap.referencia,SUBSTRING(ctap.referencia,POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2,locate(';',ctap.referencia,POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2)-(POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2)) as valor_pagado,locate(';',ctap.referencia,POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2) as loc,
                    // POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2 as posicion_cta,
                    // cta.id_factura,if(cta.id_factura is not null,SUBSTRING(ctap.referencia,1,8),null) as serie,if(cta.id_factura is not null,SUBSTRING(ctap.referencia,9,9),cta.referencias) as documento,cta.referencias,ctap.fecha_pago,ctap.fecha_registro,forma_pagos.descripcion,ctap.pagos_por,
                    // locate(';',ctap.referencia) as calve,
                    // SUBSTRING(ctap.referencia,1,locate(';',ctap.referencia)-1) as clave_a,cta.id_cliente,cta.numero_transaccion as nro_tarjeta
                    // from ctas_cobrar as cta
                    // INNER JOIN ctas_cobrar_pagos as ctap
                    // on ctap.id_cliente=cta.id_cliente
                    // INNER JOIN forma_pagos
                    // on forma_pagos.id_forma_pagos=ctap.id_forma_pagos
                    // LEFT JOIN factura as fact
                    // on fact.id_factura=cta.id_factura
                    "SELECT cta.id_ctascobrar,ctap.posicion,ctap.id_ctas_cobrar_pagos,ctap.valor_real_pago,ctap.referencia,
                if(round(SUBSTRING(ctap.referencia,POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2,locate(';',ctap.referencia,POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2)-(POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2)),2)>ctap.valor_real_pago,ctap.valor_real_pago,round(SUBSTRING(ctap.referencia,POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2,locate(';',ctap.referencia,POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2)-(POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2)),2)) as valor_pagado,cta.valor_pagado as v_pago_cobrar,cta.valor_cuota,locate(';',ctap.referencia,POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2) as loc,
                                POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2 as posicion_cta,
                                cta.id_factura,cta.id_nota_venta,if(cta.id_factura is not null,SUBSTRING(ctap.referencia,1,8),if(cta.id_nota_venta is not null,SUBSTRING(ctap.referencia,1,8),null)) as serie,if(cta.id_factura is not null,SUBSTRING(fact.clave_acceso,31,9),if(cta.id_nota_venta is not null,SUBSTRING(nv.clave_acceso,31,9),cta.referencias)) as documento,cta.referencias,ctap.fecha_pago,ctap.fecha_registro,forma_pagos.descripcion,ctap.pagos_por,
                                locate(';',ctap.referencia) as calve,
                                SUBSTRING(ctap.referencia,1,locate(';',ctap.referencia)-1) as clave_a,cta.id_cliente,cta.numero_transaccion as nro_tarjeta
                                from ctas_cobrar as cta
                                INNER JOIN ctas_cobrar_pagos as ctap
                                on ctap.id_cliente=cta.id_cliente
                                INNER JOIN forma_pagos
                                on forma_pagos.id_forma_pagos=ctap.id_forma_pagos
                                LEFT JOIN factura as fact
                                on fact.id_factura=cta.id_factura
								LEFT JOIN nota_venta as nv
                                on nv.id_nota_venta=cta.id_nota_venta
                where {$ctas_pagos} and forma_pagos.id_empresa={$request->company} and cta.tipo=1 and ctap.referencia like concat('%;',cta.id_ctascobrar,';%') and ctap.pago_anticipo is null
                ORDER BY if(ctap.fecha_registro is null,ctap.fecha_pago,ctap.fecha_registro) asc";
                //dd($query_pago_ref);
                $reporte_pago_ref = DB::select($query_pago_ref);

                $query_referencia = "SELECT cta.*,if(now()>cta.fecha_pago,'si','no') as vencido
                from ctas_cobrar as cta,cliente as pro
                where {$fields} and pro.id_cliente=cta.id_cliente and referencias is not null  and valor_cuota>=valor_pagado and  pro.id_empresa={$request->company}";
                //dd($query_referencia);
                $reporte_referencia = DB::select($query_referencia);
                $empresa = DB::select("SELECT * from empresa where id_empresa=" . $request->company);
                if ($inners) {
                    $query3 = "SELECT distinct pro.created_by, pro.updated_by, pro.id_vendedor, pro.nombre,pro.identificacion,pro.id_cliente,pro.codigo,pro.contacto,pro.telefono
                                from ctas_cobrar as cta -- ,cliente as pro
                                                    INNER JOIN cliente AS pro
                                                    on pro.id_cliente=cta.id_cliente
                                                    LEFT JOIN factura as fact
                                                    on fact.id_factura=cta.id_factura
                                                    LEFT JOIN nota_venta as nv
                                                    on nv.id_nota_venta=cta.id_nota_venta
                                where {$inners} and  cta.valor_cuota>=cta.valor_pagado and pro.id_empresa={$request->company} and cta.tipo=1 order by pro.nombre asc";
                } else {
                    $query3 = "SELECT distinct pro.created_by, pro.updated_by, pro.id_vendedor, pro.nombre,pro.identificacion,pro.id_cliente,pro.codigo,pro.contacto,pro.telefono
                                from ctas_cobrar as cta -- ,cliente as pro
                                                    INNER JOIN cliente AS pro
                                                    on pro.id_cliente=cta.id_cliente
                                                    LEFT JOIN factura as fact
                                                    on fact.id_factura=cta.id_factura
                                                    LEFT JOIN nota_venta as nv
                                                    on nv.id_nota_venta=cta.id_nota_venta
                                where  cta.valor_cuota>=cta.valor_pagado and pro.id_empresa={$request->company} and cta.tipo=1 order by pro.nombre asc";
                }
                //dd($query3);
                $reporte3 = DB::select($query3);
                if (session()->get('usuariosesion')['filtro_list'] == 1 || session()->get('usuariosesion')['id_rol'] == 2) {
                    $dat = [];
                    foreach ($reporte3 as $report) {
                        if ($report->created_by == session()->get('usuariosesion')['id'] || $report->updated_by == session()->get('usuariosesion')['id'] || $report->id_vendedor == session()->get('usuariosesion')['id_vendedor']) {
                            array_push($dat, $report);
                        }
                    }
                    $reporte3 = $dat;
                }
                $nuevo_reporte = "SELECT sum(valor_cuota) as valor_cuota,fact.id_factura,nv.id_nota_venta,if(fact.clave_acceso is null,nv.clave_acceso,fact.clave_acceso) as observacion,cta.referencias,fact.fecha_emision,max(cta.fecha_pago) as fecha_pago,max(pro.id_cliente) as id_cliente,if(now()>max(fecha_pago),'si','no') as vencido
                        from ctas_cobrar as cta
                        INNER JOIN cliente as pro
                        on pro.id_cliente=cta.id_cliente
                        LEFT JOIN factura as fact
                        on fact.id_factura=cta.id_factura
                        LEFT JOIN nota_venta as nv
                        on nv.id_nota_venta=cta.id_nota_venta
                where {$inners} and cta.tipo=1  and pro.id_empresa={$request->company} and (cta.id_factura is not null or cta.referencias is not null or cta.id_nota_venta is not null)
                GROUP BY fact.id_factura,cta.referencias,cta.id_cliente,nv.id_nota_venta
                ORDER BY max(fecha_pago) asc";
                //dd($nuevo_reporte);
                $reporte_nuevo = DB::select($nuevo_reporte);
                $ctas_cobrar = "SELECT cop.*, cl.nombre AS nombrecliente, fp.descripcion AS descripcionsri, IF(fecha_registro IS NULL, fecha_pago, fecha_registro) as fechageneral
                FROM ctas_cobrar_pagos cop
                INNER JOIN cliente cl ON cl.id_cliente = cop.id_cliente
                LEFT JOIN forma_pagos fp ON fp.id_forma_pagos = cop.id_forma_pagos
                LEFT JOIN forma_pagos_sri fps ON fps.id_forma_pagos_sri = fp.id_forma_pagos_sri
                INNER JOIN empresa em ON em.id_empresa = cl.id_empresa
                WHERE em.id_empresa = {$request->company} and cop.pago_anticipo is null  ORDER BY fechageneral DESC";
                $valor = DB::select("SELECT cop.*, cl.nombre AS nombrecliente, fp.descripcion AS descripcionsri, IF(fecha_registro IS NULL, fecha_pago, fecha_registro) as fechageneral 
                FROM ctas_cobrar_pagos cop 
                INNER JOIN cliente cl ON cl.id_cliente = cop.id_cliente 
                LEFT JOIN forma_pagos fp ON fp.id_forma_pagos = cop.id_forma_pagos 
                LEFT JOIN forma_pagos_sri fps ON fps.id_forma_pagos_sri = fp.id_forma_pagos_sri 
                INNER JOIN empresa em ON em.id_empresa = cl.id_empresa 
                WHERE em.id_empresa = {$request->company} AND cop.pagos_por != 'Anticipo' and cop.pago_anticipo is null  
                ORDER BY cop.fecha_pago DESC, cop.id_ctas_cobrar_pagos DESC");
                //recorre las referencias de los registros de pagos y los conviertes
                $abonos = DB::select("SELECT *,contabilidad 
                FROM ctas_cobrar cb 
                INNER JOIN cliente c ON cb.id_cliente = c.id_cliente 
                INNER JOIN forma_pagos fp ON cb.id_forma_pagos = fp.id_forma_pagos 
                WHERE c.id_empresa = {$request->company} AND cb.tipo = 3 ORDER BY cb.id_ctascobrar DESC");
                $res = array_merge($valor, $abonos);
        
                usort($res, function($a, $b)
                {
                    return strcmp($b->fecha_registro,$a->fecha_registro);
                });
                //dd($ctas_cobrar);
                //$reporte_ctas=DB::select($ctas_cobrar);
                $reporte_ctas=$res;
                //$reporte4=DB::select("SELECT email_empresa,password,servidor_correo,puerto_correo,seguridad_correo,id_empresa from empresa where id_empresa=". $request->company);
                if (!$reporte && !$reporte_referencia) {
                    return response('no-data-report', 200)->header('Content-Type', 'application/json');
                } else {
                    if ($request->email && $request->destinatario) {
                        $Reportes = new generarReportes();
                        $ruta = constant("DATA_EMPRESA") . $request->company . '/facturacion/cuentas_cobrar';
                        if (!file_exists($ruta)) {
                            mkdir($ruta, 0755, true);
                        }
                        $strPDF = $Reportes->cuenta_cobrar_reporte_detalle_factura($reporte_nuevo, $reporte, $fecha_inicio[0]->fecha_inicio, $final, $reporte2, $reporte3, $reporte_referencia, $reporte_pago_ref, $empresa[0], $reporte_ctas, $ruta);
                        $email = new sendEmail();
                        $email->enviarCtasCobrar($reporte4[0], $request->email, $request->destinatario, "Detalle_Factura");
                        $cta = $ruta . '/cuenta_por_cobrar_Detalle_Factura.pdf';
                        /*if(file_exists($cta)){
                            unlink($cta);
                        }*/
                        return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                    } else {
                        $Reportes = new generarReportes();
                        $strPDF = $Reportes->cuenta_cobrar_reporte_detalle_factura($reporte_nuevo, $reporte, $fecha_inicio[0]->fecha_inicio, $final, $reporte2, $reporte3, $reporte_referencia, $reporte_pago_ref, $empresa[0], $reporte_ctas);
                        return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                    }
                }
            }
            if ($info_reporte["id"] == 3) {
                $fecha_hoy = Carbon::now();
                $info_date = json_decode($request->date, true);
                if ($request->date) {

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
                            array_push($queries, "((date(fact.fecha_emision) between date('{$fecha_inicio_factura[0]->fecha_inicio}') and date('{$final}')) or 
                                                            (date(nv.fecha_emision) between date('{$fecha_inicio_nota_venta[0]->fecha_inicio}') and date('{$final}')))\n");
                            array_push($fields, "date(cta.fcrea) between date('{$fecha_crea_cta[0]->fecha_inicio}') and date('{$final}')\n");
                            array_push($inners, "date(cta.fecha_factura) between date('{$fecha_inicio_ctas_total[0]->fecha_inicio}') and date('{$final}')\n");
                            //array_push($pagos, "date(cta.fecha_registro) between date('{$fecha_inicio_ctas_cobrar_fecha_registro_ctas[0]->fecha_inicio}') and date('{$final}')\n");
                            array_push($pagos, "((date(ctap.fecha_pago) between date('{$fecha_inicio_ctas_cobrar_fecha_pagos[0]->fecha_inicio}') and date('{$final}')) or
                                                      (date(ctap.fecha_registro) between date('{$fecha_inicio_ctas_cobrar_fecha_registro[0]->fecha_inicio}') and date('{$final}')))\n");
                        }
                    } else {
                        $hoy = date("Y-m-d");
                        $initial = str_replace("-010-", "-10-", $info_date["value"]);
                        $final = $hoy;
                        if ($info_date["option"] == 1) {
                            array_push($queries, "date(cta.fecha_pago) = date('{$info_date["value"]}')\n");
                            array_push($inners, "date(cta.fecha_factura) between date('{$fecha_inicio_ctas_total[0]->fecha_inicio}') and date('{$hoy}')\n");
                        }
                        if ($info_date["option"] == 2) {
                            array_push($queries, "date(cta.fecha_pago) = date('{$info_date["value"]}')\n");
                            array_push($inners, "date(cta.fecha_factura) between date('{$fecha_inicio_ctas_total[0]->fecha_inicio}') and date('{$hoy}')\n");
                        }
                        if ($info_date["option"] == 3) {
                            //array_push($queries, "date(cta.fecha_pago) = date('{$info_date["value"]}')\n");
                            array_push($queries, "((date(fact.fecha_emision) between date('{$fecha_inicio_factura[0]->fecha_inicio}') and date('{$hoy}')) or
                                                    (date(nv.fecha_emision) between date('{$fecha_inicio_nota_venta[0]->fecha_inicio}') and date('{$hoy}')))\n");
                            array_push($fields, "date(cta.fcrea) between date('{$fecha_crea_cta[0]->fecha_inicio}') and date('{$hoy}')\n");
                            array_push($inners, "date(cta.fecha_factura) between date('{$fecha_inicio_ctas_total[0]->fecha_inicio}') and date('{$hoy}')\n");
                            //array_push($pagos, "date(cta.fecha_registro) between date('{$fecha_inicio_ctas_cobrar_fecha_registro_ctas[0]->fecha_inicio}') and date('{$hoy}')\n");
                            array_push($pagos, "((date(ctap.fecha_pago) between date('{$fecha_inicio_ctas_cobrar_fecha_pagos[0]->fecha_inicio}') and date('{$hoy}')) or
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
                        array_push($clients, "cta.id_proveedor = {$info_provider["id"]}\n");
                    }
                }
                // if ($request->user) {
                //     $info_user = json_decode($request->user, true);
                //     if ($info_user["id"] != 0) {
                //         array_push($queries, "cta.ucrea = {$info_user["id"]}\n");
                //         array_push($fields, "cta.ucrea = {$info_user["id"]}\n");
                //         array_push($inners, "cta.ucrea = {$info_user["id"]}\n");
                //         array_push($clients, "cta.ucrea = {$info_user["id"]}\n");
                //         //array_push($pagos, "cta.ucrea = {$info_user["id"]}\n");
                //     }
                // }
                $nombre_vendedor = "";
                if ($request->rol_user !== "2") {
                    

                    if ($request->user) {

                        $info_user = json_decode($request->user, true);
                        
                        if ($info_user["id"] != 0) {
                        
                            //array_push($queries, "(f.created_by = {$info_seller["id"]})\n");
                                    array_push($queries, "cta.created_by = {$info_user["id"]}\n");
                                    array_push($fields, "cta.created_by = {$info_user["id"]}\n");
                                    array_push($inners, "cta.created_by = {$info_user["id"]}\n");
                                    array_push($clients, "cta.created_by = {$info_user["id"]}\n");
                            
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

                        
                    //     //array_push($queries, "cta.created_by = {$vnd[0]->id}\n");
                    //     array_push($queries, "cta.created_by = {$vnd[0]->id}\n");
                    //                 array_push($fields, "cta.created_by = {$vnd[0]->id}\n");
                    //                 array_push($inners, "cta.created_by = {$vnd[0]->id}\n");
                    //                 array_push($clients, "cta.created_by = {$vnd[0]->id}\n");
                    //     // 
                    // }
                }
                if($request->rol_user !== "2"){
                    if ($request->vendedor) {
                        $info_user = json_decode($request->vendedor, true);
                        
                        if ($info_user["id"] != 0) {
                            array_push($queries, "((fact.id_vendedor={$info_user["id"]}) or
                                                        (nv.id_vendedor={$info_user["id"]}))\n");
                            array_push($pagos, "((fact.id_vendedor={$info_user["id"]}) or
                                                        (nv.id_vendedor={$info_user["id"]}))\n");
                            array_push($inners, "((fact.id_vendedor={$info_user["id"]}) or
                                                        (nv.id_vendedor={$info_user["id"]}))\n");
                        }
                    }
                }else{
                    if ($request->vendedor) {
                        $info_user = json_decode($request->vendedor, true);
                        
                        if ($info_user["id"] != 0) {
                            array_push($queries, "((fact.id_vendedor={$info_user["id"]}) or
                                                        (nv.id_vendedor={$info_user["id"]}))\n");
                            array_push($pagos, "((fact.id_vendedor={$info_user["id"]}) or
                                                        (nv.id_vendedor={$info_user["id"]}))\n");
                            array_push($inners, "((fact.id_vendedor={$info_user["id"]}) or
                                                        (nv.id_vendedor={$info_user["id"]}))\n");
                        }
                    }
                    // $vnd = DB::select("SELECT * from vendedor where id_user=" . $request->usuario);
                    // if(count($vnd)>0){
                    //     $array_vnd=[];
                    //     foreach($vnd as $detail){
                    //         array_push($array_vnd,$detail->id_vendedor);
                    //     }
                    //     $array_vnd=implode(",",$array_vnd);
                    //     array_push($queries, "((fact.id_vendedor in ({$array_vnd})) or
                    //                                 (nv.id_vendedor in ({$array_vnd})))\n");
                    //     array_push($pagos, "((fact.id_vendedor in ({$array_vnd})) or
                    //                                 (nv.id_vendedor in ({$array_vnd})))\n");
                    //     array_push($inners, "((fact.id_vendedor in ({$array_vnd})) or
                    //                                 (nv.id_vendedor in ({$array_vnd})))\n");
                    // }
                    
                }
                if ($request->wayToPay) {
                    $info_payment = json_decode($request->wayToPay, true);
                    if ($info_payment["id"] != 0) {
                        array_push($queries, "cta.id_forma_pagos = {$info_payment["id"]}\n");
                        array_push($fields, "cta.id_forma_pagos = {$info_payment["id"]}\n");
                        array_push($inners, "cta.id_forma_pagos = {$info_payment["id"]}\n");
                        array_push($clients, "cta.id_forma_pagos = {$info_payment["id"]}\n");
                        array_push($pagos, "ctap.id_forma_pagos = {$info_payment["id"]}\n");
                    }
                }
                if ($request->client) {
                    $info_client = json_decode($request->client);
                    $exists_client = true;
                    array_push($queries, "cta.id_cliente = {$info_client->id}\n");
                    array_push($fields, "cta.id_cliente = {$info_client->id}\n");
                    array_push($inners, "cta.id_cliente = {$info_client->id}\n");
                    array_push($clients, "cta.id_cliente = {$info_client->id}\n");
                    array_push($pagos, "ctap.id_cliente = {$info_client->id}\n");
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
                    array_push($queries, "cta.valor_cuota {$typeSearch} {$request->totalCount}\n");
                    array_push($fields, "cta.valor_cuota {$typeSearch} {$request->totalCount}\n");
                    array_push($inners, "cta.valor_cuota {$typeSearch} {$request->totalCount}\n");
                    array_push($clients, "cta.valor_cuota {$typeSearch} {$request->totalCount}\n");
                    //array_push($pagos, "cta.valor_cuota {$typeSearch} {$request->totalCount}\n");
                }
                $queries = implode(" and ", $queries);
                $inners = implode(" and ", $inners);
                $fields = implode(" and ", $fields);
                $clients = implode(" and ", $clients);
                $pagos = implode(" and ", $pagos);
                $query = "SELECT sum(cta.valor_cuota) as valor_cuota,sum(if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota)) as valor_pagado,sum(cta.valor_cuota)-sum(if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota)) as saldo,cta.id_factura,cta.id_nota_venta,if(fact.id_cliente is null,nv.id_cliente,fact.id_cliente) as id_cliente,if(fact.fecha_emision is null,nv.fecha_emision,fact.fecha_emision) as fecha_emision,
                        max(cta.fecha_pago) as fecha_pago,if(fact.clave_acceso is null, nv.clave_acceso,fact.clave_acceso) as observacion,emp.logo,emp.id_empresa,emp.nombre_empresa
                        from ctas_cobrar as cta -- ,factura as fact,empresa as emp
												INNER JOIN cliente as pro
												on pro.id_cliente=cta.id_cliente
												INNER JOIN empresa as emp
												on pro.id_empresa=emp.id_empresa
												LEFT JOIN factura as fact
												on cta.id_factura=fact.id_factura
												LEFT JOIN nota_venta as nv
												on cta.id_nota_venta=nv.id_nota_venta
                        where
                        {$queries} 
                        and cta.tipo=1
                        and pro.id_empresa={$request->company}
                        GROUP BY cta.id_factura,cta.id_nota_venta ORDER BY if(fact.fecha_emision is null,nv.fecha_emision,fact.fecha_emision) asc"; /*"select cta.*,fact.observacion,fact.fech_emision,pro.nombre_proveedor,pro.identif_proveedor,emp.id_empresa,emp.nombre_empresa,emp.logo
                        from ctas_pagar as cta,factura_compra as fact,proveedor as pro,empresa as emp
                        where
                        {$queries} and cta.valor_cuota>cta.valor_pagado and
                        cta.id_factura_compra=fact.id_factcompra and
                        pro.id_proveedor=cta.id_proveedor and
                        pro.id_empresa=emp.id_empresa";*/
                //dd();
                $query2 = "SELECT sum(valor_cuota) as suma,sum(valor_pagado) as abono,sum(valor_cuota)-sum(valor_pagado) as saldo
                        from  ctas_cobrar as cta,cliente
                        where cliente.id_cliente=cta.id_cliente and cliente.id_empresa={$request->company} and valor_cuota>valor_pagado and cta.tipo=1";
                //dd($query);
                $reporte = DB::select($query);
                $reporte2 = DB::select($query2);
                if ($inners) {
                    $query3 = "SELECT pro.created_by, pro.updated_by, pro.id_vendedor, pro.nombre,pro.identificacion,pro.id_cliente,pro.codigo,pro.contacto,pro.telefono,sum(valor_cuota)-sum(valor_pagado) as valor_deuda
                                from ctas_cobrar as cta -- ,cliente as pro
                                INNER JOIN cliente as pro
                                on cta.id_cliente=pro.id_cliente
                                LEFT JOIN factura as fact
                                on fact.id_factura=cta.id_factura
                                LEFT JOIN nota_venta as nv
                                on nv.id_nota_venta=cta.id_nota_venta
                                where {$inners}  and pro.id_empresa={$request->company} and cta.tipo=1 
                                GROUP BY pro.id_cliente
                                order by pro.nombre asc";
                } else {
                    $query3 = "SELECT pro.created_by, pro.updated_by, pro.id_vendedor, pro.nombre,pro.identificacion,pro.id_cliente,pro.codigo,pro.contacto,pro.telefono,sum(valor_cuota)-sum(valor_pagado) as valor_deuda
                                from ctas_cobrar as cta -- ,cliente as pro
                                INNER JOIN cliente as pro
                                on cta.id_cliente=pro.id_cliente
                                LEFT JOIN factura as fact
                                on fact.id_factura=cta.id_factura
                                LEFT JOIN nota_venta as nv
                                on nv.id_nota_venta=cta.id_nota_venta
                                where  pro.id_empresa={$request->company} and cta.tipo=1 
                                GROUP BY pro.id_cliente
                                order by pro.nombre asc";
                }
                //dd($query3);
                $reporte3 = DB::select($query3);
                if (session()->get('usuariosesion')['filtro_list'] == 1 || session()->get('usuariosesion')['id_rol'] == 2) {
                    $dat = [];
                    foreach ($reporte3 as $report) {
                        if ($report->created_by == session()->get('usuariosesion')['id'] || $report->updated_by == session()->get('usuariosesion')['id'] || $report->id_vendedor == session()->get('usuariosesion')['id_vendedor']) {
                            array_push($dat, $report);
                        }
                    }
                    $reporte3 = $dat;
                }
                $query_referencia = "SELECT cta.*
                        from ctas_cobrar as cta,cliente as pro
                        where {$fields} and cta.id_cliente=pro.id_cliente and cta.tipo=1 and cta.referencias is not null and pro.id_empresa={$request->company}";
                //dd($query_referencia);
                $reporte_referencia = DB::select($query_referencia);
                $empresa = DB::select("SELECT * from empresa where id_empresa=" . $request->company);
                $nuevo_reporte = "SELECT sum(cta.valor_cuota) as valor_cuota,sum(if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota)) as valor_pagado,sum(cta.valor_cuota)-sum(if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota)) as saldo,
                        fact.id_factura,cta.id_nota_venta,max(cta.id_cliente) as id_cliente,max(cta.fecha_factura) as fecha_emision,max(cta.fecha_pago) as fecha_pago,if(fact.clave_acceso is null,nv.clave_acceso,fact.clave_acceso) as observacion,cta.referencias,if('{$fecha_hoy}'>max(cta.fecha_pago),'si','no') as vencido
                                                from ctas_cobrar as cta
                                                INNER JOIN cliente as pro
                                                on pro.id_cliente=cta.id_cliente
                                                LEFT JOIN factura as fact
                                                on fact.id_factura=cta.id_factura
                                                LEFT JOIN nota_venta as nv
                                                on nv.id_nota_venta=cta.id_nota_venta
                        where {$inners} and cta.tipo=1 and pro.id_empresa={$request->company}  and (cta.id_factura is not null or cta.referencias is not null or cta.id_nota_venta is not null)
                        GROUP BY fact.id_factura,cta.referencias,cta.id_cliente,cta.id_nota_venta
                        ORDER BY max(cta.fecha_factura) asc";
                //dd($nuevo_reporte);
                $reporte_nuevo = DB::select($nuevo_reporte);
                $valores_pagado =
                    // "SELECT sum(if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota)) as valor_pagado,referencias,fact.id_factura,cta.id_cliente
                    // from ctas_cobrar as cta
                    // 						INNER JOIN cliente as pro
                    //                         on pro.id_cliente=cta.id_cliente
                    //                         LEFT JOIN factura as fact
                    //                         on fact.id_factura=cta.id_factura
                    // where {$pagos}  and pro.id_empresa={$request->company} and cta.tipo=1 and (referencias is not null or fact.id_factura is not null)
                    // 						GROUP BY fact.id_factura,cta.referencias,cta.id_cliente
                    //                         ORDER BY max(cta.fecha_factura) asc";
                    "SELECT sum(if(round(SUBSTRING(ctap.referencia,POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2,locate(';',ctap.referencia,POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2)-(POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2)),2)>ctap.valor_real_pago,ctap.valor_real_pago,round(SUBSTRING(ctap.referencia,POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2,locate(';',ctap.referencia,POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2)-(POSITION(concat(';',cta.id_ctascobrar,';') in ctap.referencia)+LENGTH(cta.id_ctascobrar)+2)),2))) as  valor_pagado,cta.id_factura,cta.id_nota_venta,cta.referencias,cta.id_cliente
                            from ctas_cobrar as cta
                            INNER JOIN ctas_cobrar_pagos as ctap
                            on ctap.id_cliente=cta.id_cliente
                            INNER JOIN forma_pagos
                            on forma_pagos.id_forma_pagos=ctap.id_forma_pagos
                            LEFT JOIN factura as fact
                            on fact.id_factura=cta.id_factura
                            LEFT JOIN nota_venta as nv
                            on nv.id_nota_venta=cta.id_nota_venta
                                                    where {$pagos}  and forma_pagos.id_empresa={$request->company} and cta.tipo=1 and ctap.referencia like concat('%;',cta.id_ctascobrar,';%') and ctap.pago_anticipo is null
                                                    GROUP BY cta.id_factura,cta.referencias,cta.id_cliente,cta.id_nota_venta
                                                    ORDER BY max(cta.fecha_factura) asc";
                //dd($valores_pagado);
                $reporte_valores_pagado = DB::select($valores_pagado);
                //dd($nuevo_reporte);

                //$reporte4=DB::select("SELECT email_empresa,password,servidor_correo,puerto_correo,seguridad_correo,id_empresa from empresa where id_empresa=". $request->company);
                if (!$reporte && !$reporte_referencia) {
                    return response('no-data-report', 200)->header('Content-Type', 'application/json');
                } else {
                    if ($request->email && $request->destinatario) {
                        $Reportes = new generarReportes();
                        $ruta = constant("DATA_EMPRESA") . $request->company . '/facturacion/cuentas_cobrar';
                        if (!file_exists($ruta)) {
                            mkdir($ruta, 0755, true);
                        }
                        if(isset($request->vencido_reporte)){
                            $strPDF = $Reportes->cuenta_cobrar_reporte_resumen_factura_vencido($reporte_nuevo, $reporte_valores_pagado, $reporte, $fecha_inicio[0]->fecha_inicio, $final, $reporte2, $reporte3, $reporte_referencia, $empresa[0], $ruta);
                            $email = new sendEmail();
                            $email->enviarCtasCobrar($reporte4[0], $request->email, $request->destinatario, "Resumen_Factura");
                            $cta = $ruta . '/cuenta_por_cobrar_Resumen_Factura.pdf';
                            /*if(file_exists($cta)){
                                        unlink($cta);
                                    }*/
                            return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                        }else{
                            $strPDF = $Reportes->cuenta_cobrar_reporte_resumen_factura($reporte_nuevo, $reporte_valores_pagado, $reporte, $fecha_inicio[0]->fecha_inicio, $final, $reporte2, $reporte3, $reporte_referencia, $empresa[0], $ruta);
                            $email = new sendEmail();
                            $email->enviarCtasCobrar($reporte4[0], $request->email, $request->destinatario, "Resumen_Factura");
                            $cta = $ruta . '/cuenta_por_cobrar_Resumen_Factura.pdf';
                            /*if(file_exists($cta)){
                                        unlink($cta);
                                    }*/
                            return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                        }
                        
                    } else {
                        if(isset($request->vencido_reporte)){
                            $Reportes = new generarReportes();
                            $strPDF = $Reportes->cuenta_cobrar_reporte_resumen_factura_vencido($reporte_nuevo, $reporte_valores_pagado, $reporte, $fecha_inicio[0]->fecha_inicio, $final, $reporte2, $reporte3, $reporte_referencia, $empresa[0]);
                            return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                        }else{
                            $Reportes = new generarReportes();
                            $strPDF = $Reportes->cuenta_cobrar_reporte_resumen_factura($reporte_nuevo, $reporte_valores_pagado, $reporte, $fecha_inicio[0]->fecha_inicio, $final, $reporte2, $reporte3, $reporte_referencia, $empresa[0]);
                            return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                        }
                        
                    }
                }
            }
            if ($info_reporte["id"] == 4) {

                //dd($request);
                $info_date = json_decode($request->date, true);
                if ($request->date) {

                    //dd($request->date);
                    if ($request->currentDate !== "true") {
                        $initial = str_replace("-010-", "-10-", $info_date["range"]["initial"]);
                        $final = str_replace("-010-", "-10-", $info_date["range"]["final"]);
                        if ($info_date["option"] == 1) {
                            array_push($queries, "date(fact.fecha_emision) between date('{$fecha_inicio_factura[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')\n");
                            array_push($fields, "date(cta.fcrea) between date('{$fecha_crea_cta[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')\n");
                            array_push($inners, "date(cta.fecha_factura) between date('{$fecha_inicio_ctas_total[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')\n");
                        }
                        if ($info_date["option"] == 2) {
                            array_push($queries, "date(fact.fecha_emision) between date('{$fecha_inicio_factura[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')\n");
                            array_push($fields, "date(cta.fcrea) between date('{$fecha_crea_cta[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')\n");
                            array_push($inners, "date(cta.fecha_factura) between date('{$fecha_inicio_ctas_total[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')\n");
                        }
                        if ($info_date["option"] == 3) {
                            array_push($queries, "((date(fact.fecha_emision) between date('{$fecha_inicio_factura[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')) or
                                                    (date(fact.fecha_emision) between date('{$fecha_inicio_nota_venta[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')))\n");
                            array_push($fields, "date(cta.fcrea) between date('{$fecha_crea_cta[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')\n");
                            array_push($inners, "date(cta.fecha_factura) between date('{$fecha_inicio_ctas_total[0]->fecha_inicio}') and date('{$info_date["range"]["final"]}')\n");
                        }
                    } else {
                        $hoy = date("Y-m-d");
                        $initial = str_replace("-010-", "-10-", $info_date["value"]);
                        $final = $hoy;
                        if ($info_date["option"] == 1) {
                            array_push($queries, "date(fact.fecha_emision) between date('{$fecha_inicio_factura[0]->fecha_inicio}') and date('{$hoy}')\n");
                            array_push($fields, "date(cta.fcrea) between date('{$fecha_crea_cta[0]->fecha_inicio}') and date('{$hoy}')\n");
                            array_push($inners, "date(cta.fecha_factura) between date('{$fecha_inicio_ctas_total[0]->fecha_inicio}') and date('{$hoy}')\n");
                        }
                        if ($info_date["option"] == 2) {
                            array_push($queries, "date(fact.fecha_emision) between date('{$fecha_inicio_factura[0]->fecha_inicio}') and date('{$hoy}')\n");
                            array_push($fields, "date(cta.fcrea) between date('{$fecha_crea_cta[0]->fecha_inicio}') and date('{$hoy}')\n");
                            array_push($inners, "date(cta.fecha_factura) between date('{$fecha_inicio_ctas_total[0]->fecha_inicio}') and date('{$hoy}')\n");
                        }
                        if ($info_date["option"] == 3) {
                            //array_push($queries, "date(cta.fecha_pago) = date('{$hoy}')\n");
                            array_push($queries, "((date(fact.fecha_emision) between date('{$fecha_inicio_factura[0]->fecha_inicio}') and date('{$hoy}')) or 
                                                    (date(nv.fecha_emision) between date('{$fecha_inicio_nota_venta[0]->fecha_inicio}') and date('{$hoy}')))\n");
                            array_push($fields, "date(cta.fcrea) between date('{$fecha_crea_cta[0]->fecha_inicio}') and date('{$hoy}')\n");
                            array_push($inners, "date(cta.fecha_factura) between date('{$fecha_inicio_ctas_total[0]->fecha_inicio}') and date('{$hoy}')\n");
                        }
                    }
                }
                // if ($request->project) {
                //     $info_project = json_decode($request->project, true);
                //     if ($info_project["id"] != 0) {
                //         array_push($queries, "f.id_proyecto = {$info_project["id"]}\n");
                //         array_push($fields, "f.id_proyecto = {$info_project["id"]}\n");
                //     }

                // }
                if($request->rol_user !== "2"){
                    if ($request->vendedor) {
                        $info_user = json_decode($request->vendedor, true);
                        
                        if ($info_user["id"] != 0) {
                            array_push($queries, "((fact.id_vendedor={$info_user["id"]}) or
                                                        (nv.id_vendedor={$info_user["id"]}))\n");
                            array_push($inners, "((fact.id_vendedor={$info_user["id"]}) or
                                                        (nv.id_vendedor={$info_user["id"]}))\n");
                        }
                    }
                }else{
                    if ($request->vendedor) {
                        $info_user = json_decode($request->vendedor, true);
                        if ($info_user["id"] != 0) {
                            array_push($queries, "((fact.id_vendedor={$info_user["id"]}) or
                                                        (nv.id_vendedor={$info_user["id"]}))\n");
                            array_push($inners, "((fact.id_vendedor={$info_user["id"]}) or
                                                        (nv.id_vendedor={$info_user["id"]}))\n");
                        }
                    }
                    // $vnd = DB::select("SELECT * from vendedor where id_user=" . $request->usuario);
                    // if(count($vnd)>0){
                    //     $array_vnd=[];
                    //     foreach($vnd as $detail){
                    //         array_push($array_vnd,$detail->id_vendedor);
                    //     }
                    //     $array_vnd=implode(",",$array_vnd);
                    //     array_push($queries, "((fact.id_vendedor in ({$array_vnd})) or
                    //                                 (nv.id_vendedor in ({$array_vnd})))\n");
                    //     array_push($inners, "((fact.id_vendedor in ({$array_vnd})) or
                    //                                 (nv.id_vendedor in ({$array_vnd})))\n");
                    // }
                    
                    
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
                if ($request->client) {
                    $info_client = json_decode($request->client);
                    $exists_client = true;
                    array_push($queries, "cta.id_cliente = {$info_client->id}\n");
                    array_push($fields, "cta.id_cliente = {$info_client->id}\n");
                    array_push($inners, "cta.id_cliente = {$info_client->id}\n");
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
                    array_push($queries, "cta.valor_cuota {$typeSearch} {$request->totalCount}\n");
                    array_push($fields, "cta.valor_cuota {$typeSearch} {$request->totalCount}\n");
                    array_push($inners, "cta.valor_cuota {$typeSearch} {$request->totalCount}\n");
                }
                /*if($request->totalCountSales != "ALL"){
                    $type_search_sales_number = (int) $request->typeSearchSalesTotalCount;
                    $total_sales = (float)$request->totalCountSales;
                    if($type_search_sales_number == 1){
                        $type_search_sales = ">=";
                    }
                    if($type_search_sales_number == 0){
                        $type_search_sales = "=";
                    }
                    if($type_search_sales_number == -1){
                        $type_search_sales = "<=";
                    }
                    if ($exists_client) {
                        array_push($fields, "(SELECT sum(valor_cuota) FROM ctas_cobrar WHERE id_cliente = {$info_client->id}) as total_ventas,\n");
                        array_push($queries, "ctas_cobrar.valor_cuota {$type_search_sales} $total_sales\n");
                    } else {
                        array_push($fields, "(SELECT sum(valor_cuota) FROM ctas_cobrar) as total_ventas,\n");
                        array_push($queries, "ctas_cobrar.valor_cuota {$type_search_sales} $total_sales\n");
                    }
                } else {
                    if ($exists_client) {
                        array_push($fields, "(SELECT sum(valor_cuota) FROM ctas_cobrar WHERE id_cliente = {$info_client->id}) as total_ventas,\n");
                    } else {
                        array_push($fields, "(SELECT sum(valor_cuota) FROM ctas_cobrar) as total_ventas,\n");
                    }
                }*/

                $queries = implode(" and ", $queries);
                $inners = implode(" and ", $inners);
                $fields = implode(" and ", $fields);
                $query = "SELECT sum(cta.valor_cuota) as valor_cuota,sum(if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota)) as abono,sum(cta.valor_cuota)-sum(if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota)) as saldo,cta.id_factura,cta.id_nota_venta,pro.id_cliente,if(fact.fecha_emision is null,nv.fecha_emision,fact.fecha_emision) as fecha_emision,
                        cta.fecha_pago as fecha_pago,if(fact.clave_acceso is null,nv.clave_acceso,fact.clave_acceso) as observacion,emp.logo,emp.id_empresa,emp.nombre_empresa,pro.nombre,pro.identificacion,
                        sum(if(DATEDIFF(cta.fecha_pago,now())>=-30 and DATEDIFF(cta.fecha_pago,now())<=-1,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00)) as mentreninta,
                        sum(if(DATEDIFF(cta.fecha_pago,now())>=-60 and DATEDIFF(cta.fecha_pago,now())<=-31,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00)) as mensesenta,
                        sum(if(DATEDIFF(cta.fecha_pago,now())>=-90 and DATEDIFF(cta.fecha_pago,now())<=-61,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00)) as mennoventa,
                        sum(if(DATEDIFF(cta.fecha_pago,now())<=-91,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00)) as mencientoveinte,
                        sum(if(DATEDIFF(cta.fecha_pago,now())=0,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00)) as cero,
                        sum(if(DATEDIFF(cta.fecha_pago,now())>=1 and DATEDIFF(cta.fecha_pago,now())<=30,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00)) as treninta,
                        sum(if(DATEDIFF(cta.fecha_pago,now())>=31 and DATEDIFF(cta.fecha_pago,now())<=60,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00)) as sesenta,
                        sum(if(DATEDIFF(cta.fecha_pago,now())>=61 and DATEDIFF(cta.fecha_pago,now())<=90,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),.000)) as noventa,
                        sum(if(DATEDIFF(cta.fecha_pago,now())>=91,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00)) as cientoveinte
                        from ctas_cobrar as cta
												INNER JOIN cliente as pro
												on pro.id_cliente=cta.id_cliente
												INNER JOIN empresa as emp
												on emp.id_empresa=pro.id_empresa
												LEFT JOIN factura as fact
												on fact.id_factura=cta.id_factura
												LEFT JOIN nota_venta as nv
												on nv.id_nota_venta=cta.id_nota_venta
                        where
                        {$queries} 
                        and pro.id_empresa={$request->company}
                        and cta.tipo=1
                        and cta.valor_cuota>cta.valor_pagado
                        GROUP BY cta.id_factura,cta.id_nota_venta
                        ORDER BY nombre,if(fact.fecha_emision is null,nv.fecha_emision,fact.fecha_emision) asc";/*"select DISTINCT id_factura_compra,fact.observacion,fact.total_factura as valor_cuota,cta.id_proveedor,emp.nombre_empresa,emp.id_empresa,emp.logo,fact.fech_emision,
                        fact.fech_validez as fecha_pago
                        from ctas_pagar as cta,factura_compra as fact,empresa as emp
                        where
                        {$queries} and
                        cta.id_factura_compra=fact.id_factcompra and
                        fact.id_empresa=emp.id_empresa";*/

                $query2 = "select numero_tarjeta,if(valor_cuota>=valor_pagado,valor_pagado,valor_cuota) as valor_pagado,id_factura from ctas_cobrar as cta, cliente where cliente.id_cliente=cta.id_cliente and cta.tipo=1 and cliente.id_empresa={$request->company} and valor_pagado>=valor_cuota";
                //dd($query);
                $reporte = DB::select($query);
                //dd($reporte);
                $reporte2 = DB::select($query2);

                //$query3='';
                $query3 = "SELECT cta.valor_cuota as valor_cuota,if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota) as abono,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota) as saldo,cta.id_cliente,cta.fcrea,
                        cta.fecha_pago,cta.referencias as observacion,emp.logo,emp.id_empresa,emp.nombre_empresa,pro.nombre,pro.identificacion,
                        if(DATEDIFF(cta.fecha_pago,now())>=-30 and DATEDIFF(cta.fecha_pago,now())<=-1,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00) as mentreninta,
                        if(DATEDIFF(cta.fecha_pago,now())>=-60 and DATEDIFF(cta.fecha_pago,now())<=-31,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00) as mensesenta,
                        if(DATEDIFF(cta.fecha_pago,now())>=-90 and DATEDIFF(cta.fecha_pago,now())<=-61,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00) as mennoventa,
                        if(DATEDIFF(cta.fecha_pago,now())<=-91,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00) as mencientoveinte,
                        if(DATEDIFF(cta.fecha_pago,now())=0,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00) as cero,
                        if(DATEDIFF(cta.fecha_pago,now())>=1 and DATEDIFF(cta.fecha_pago,now())<=30,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00) as treninta,
                        if(DATEDIFF(cta.fecha_pago,now())>=31 and DATEDIFF(cta.fecha_pago,now())<=60,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00) as sesenta,
                        if(DATEDIFF(cta.fecha_pago,now())>=61 and DATEDIFF(cta.fecha_pago,now())<=90,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),.000) as noventa,
                        if(DATEDIFF(cta.fecha_pago,now())>=91,cta.valor_cuota,0.00) as cientoveinte
                        from ctas_cobrar as cta,empresa as emp,cliente as pro
                        where
                        {$fields}
                        and pro.id_cliente=cta.id_cliente
                        and pro.id_empresa=emp.id_empresa
                        and pro.id_empresa={$request->company}
                        and cta.referencias is not null
                        and cta.valor_cuota>cta.valor_pagado
                        ORDER BY pro.nombre,cta.fcrea asc";
                /*"select cta.fecha_pago,CURRENT_DATE,DATEDIFF(cta.fecha_pago,now()),cta.valor_cuota,if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota) as valor_pagado,cta.id_factura,
                        if(DATEDIFF(cta.fecha_pago,now())>=-59 and DATEDIFF(cta.fecha_pago,now())<=-30,cta.valor_cuota,0) as mentreninta,
                        if(DATEDIFF(cta.fecha_pago,now())>=-89 and DATEDIFF(cta.fecha_pago,now())<=-60,cta.valor_cuota,0) as mensesenta,
                        if(DATEDIFF(cta.fecha_pago,now())>=-119 and DATEDIFF(cta.fecha_pago,now())<=-90,cta.valor_cuota,0) as mennoventa,
                        if(DATEDIFF(cta.fecha_pago,now())<=-120,cta.valor_cuota,0) as mencientoveinte,
                        if(DATEDIFF(cta.fecha_pago,now())>=-29 and DATEDIFF(cta.fecha_pago,now())<=29,cta.valor_cuota,0.00) as cero,
                        if(DATEDIFF(cta.fecha_pago,now())>=30 and DATEDIFF(cta.fecha_pago,now())<=59,cta.valor_cuota,0) as treninta,
                        if(DATEDIFF(cta.fecha_pago,now())>=60 and DATEDIFF(cta.fecha_pago,now())<=89,cta.valor_cuota,0) as sesenta,
                        if(DATEDIFF(cta.fecha_pago,now())>=90 and DATEDIFF(cta.fecha_pago,now())<=119,cta.valor_cuota,0) as noventa,
                        if(DATEDIFF(cta.fecha_pago,now())>=120,cta.valor_cuota,0) as cientoveinte
                        from ctas_cobrar as cta,factura as fact,empresa as emp where {$queries} and fact.id_empresa=emp.id_empresa and cta.tipo=1 and fact.id_empresa={$request->company}";*/
                //dd($query);
                $empresa = DB::select("SELECT * from empresa where id_empresa=" . $request->company);
                $reporte3 = DB::select($query3);
                $nuevo_reporte = "SELECT pro.created_by, pro.updated_by, pro.id_vendedor,sum(if(cta.fecha_pago<now() and cta.valor_cuota>cta.valor_pagado,if(cta.valor_cuota>=cta.valor_pagado,cta.valor_cuota-cta.valor_pagado,cta.valor_cuota),0)) as total_vencido,
                        sum(cta.valor_cuota) as valor_cuota,sum(if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota)) as abono,sum(cta.valor_cuota)-sum(if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota)) as saldo,fact.id_factura,cta.id_nota_venta,cta.referencias,max(pro.id_cliente) as id_cliente,max(cta.fecha_factura) as fecha_emision,max(cta.fecha_pago) as fecha_pago,if(fact.clave_acceso is null,nv.clave_acceso,fact.clave_acceso) as observacion,max(pro.nombre) as nombre,max(pro.identificacion) as identificacion,
                        sum(if(DATEDIFF(cta.fecha_pago,now())>=-30 and DATEDIFF(cta.fecha_pago,now())<=-1,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0)) as mentreninta,
                        sum(if(DATEDIFF(cta.fecha_pago,now())>=-60 and DATEDIFF(cta.fecha_pago,now())<=-31,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00)) as mensesenta,
                        sum(if(DATEDIFF(cta.fecha_pago,now())>=-90 and DATEDIFF(cta.fecha_pago,now())<=-61,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00)) as mennoventa,
                        sum(if(DATEDIFF(cta.fecha_pago,now())<=-91,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00)) as mencientoveinte,
                        sum(if(DATEDIFF(cta.fecha_pago,now())=0,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00)) as cero,
                        sum(if(DATEDIFF(cta.fecha_pago,now())>=1 and DATEDIFF(cta.fecha_pago,now())<=30,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00)) as treninta,
                        sum(if(DATEDIFF(cta.fecha_pago,now())>=31 and DATEDIFF(cta.fecha_pago,now())<=60,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00)) as sesenta,
                        sum(if(DATEDIFF(cta.fecha_pago,now())>=61 and DATEDIFF(cta.fecha_pago,now())<=90,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),.000)) as noventa,
                        sum(if(DATEDIFF(cta.fecha_pago,now())>=91,cta.valor_cuota-if(cta.valor_cuota>=cta.valor_pagado,cta.valor_pagado,cta.valor_cuota),0.00)) as cientoveinte
                        FROM ctas_cobrar as cta
                        INNER JOIN cliente as pro
                        on pro.id_cliente=cta.id_cliente
                        LEFT JOIN factura as fact
                        on fact.id_factura=cta.id_factura
                        LEFT JOIN nota_venta as nv
                        on nv.id_nota_venta=cta.id_nota_venta
                        where {$inners} and cta.tipo=1 and cta.valor_cuota>cta.valor_pagado and pro.id_empresa={$request->company} and (cta.id_factura is not null or cta.referencias is not null or cta.id_nota_venta is not null)
                        GROUP BY fact.id_factura,cta.referencias,cta.id_cliente,cta.id_nota_venta
                        ORDER BY max(pro.nombre),max(cta.fecha_factura) asc";
                //dd($nuevo_reporte);
                $reporte_nuevo = DB::select($nuevo_reporte);
                if (session()->get('usuariosesion')['filtro_list'] == 1 || session()->get('usuariosesion')['id_rol'] == 2) {
                    $dat = [];
                    foreach ($reporte_nuevo as $report) {
                        if ($report->created_by == session()->get('usuariosesion')['id'] || $report->updated_by == session()->get('usuariosesion')['id'] || $report->id_vendedor == session()->get('usuariosesion')['id_vendedor']) {
                            array_push($dat, $report);
                        }
                    }
                    $reporte_nuevo = $dat;
                }
                //$reporte3='';
                //$reporte4=DB::select("SELECT email_empresa,password,servidor_correo,puerto_correo,seguridad_correo,id_empresa from empresa where id_empresa=". $request->company);
                //if (!$reporte && !$reporte3) {
                if (!$reporte_nuevo) {
                    return response('no-data-report', 200)->header('Content-Type', 'application/json');
                } else {
                    if ($request->email && $request->destinatario) {
                        $Reportes = new generarReportes();
                        $ruta = constant("DATA_EMPRESA") . $request->company . '/facturacion/cuentas_cobrar';
                        if (!file_exists($ruta)) {
                            mkdir($ruta, 0755, true);
                        }
                        $strPDF = $Reportes->cuenta_cobrar_reporte_dias_vencidos($reporte_nuevo, $reporte, $fecha_inicio[0]->fecha_inicio, $final, $reporte2, $reporte3, $empresa[0], $ruta);
                        $email = new sendEmail();
                        $email->enviarCtasCobrar($reporte4[0], $request->email, $request->destinatario, "Dias_Vencimiento");
                        $cta = $ruta . '/cuenta_por_cobrar_Dias_Vencimiento.pdf';
                        /*if(file_exists($cta)){
                                    unlink($cta);
                                }*/
                        return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                    } else {
                        $Reportes = new generarReportes();
                        $strPDF = $Reportes->cuenta_cobrar_reporte_dias_vencidos($reporte_nuevo, $reporte, $fecha_inicio[0]->fecha_inicio, $final, $reporte2, $reporte3, $empresa[0]);
                        return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                    }
                }
            }
            if ($info_reporte["id"] == 5) {
                $info_date = json_decode($request->date, true);
                if ($request->date) {

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
                                                        date(cta.fecha_pago) BETWEEN date('{$initial}') and date('{$final}'),
                                                        date(cta.fecha_registro) BETWEEN date('{$initial}') and date('{$final}'))\n");
                            array_push($anticipo_pagos, "date(fact.fecha_emision) between date('{$initial}') and date('{$final}')\n");
                            
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
                                                        date(cta.fecha_pago) = date('{$hoy}'),
                                                        date(cta.fecha_registro) = date('{$hoy}'))\n");
                            array_push($anticipo_pagos, "date(fact.fecha_emision) = date('{$hoy}')\n");
                            
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
                if ($request->client) {
                    $info_client = json_decode($request->client);
                    $exists_client = true;
                    array_push($anticipo, "pro.id_cliente = {$info_client->id}\n");
                    array_push($anticipo_pagos, "pro.id_cliente = {$info_client->id}\n");

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
                $anticipo_pagos = implode(" and ", $anticipo_pagos);
                
                $clientes=DB::select("SELECT pro.id_cliente,pro.nombre,pro.codigo,pro.identificacion,pro.telefono,pro.contacto 
                                            from cliente as pro 
                                            INNER JOIN ctas_cobrar as cta ON cta.id_cliente=pro.id_cliente 
                                            where cta.tipo=3 and pro.id_empresa={$request->company} and {$anticipo}
                                            GROUP BY pro.id_cliente
                                            ORDER BY pro.nombre");
                
                $anticipos=DB::select("SELECT cta.*,if(cta.fecha_registro is null,cta.fecha_pago,cta.fecha_registro) as fecha_anticipo,fp.descripcion as descripcion_fp
                                        from ctas_cobrar as cta
                                        INNER JOIN cliente as pro ON cta.id_cliente=pro.id_cliente
                                        LEFT JOIN forma_pagos as fp ON cta.id_forma_pagos=fp.id_forma_pagos
                                        where cta.tipo=3 and pro.id_empresa={$request->company} and {$anticipo}");
                $pagos_anticipo=DB::select("SELECT fact.*, facp.total as total_pago_ant
                                            from factura as fact
                                            INNER JOIN cliente as pro ON fact.id_cliente=pro.id_cliente
                                            INNER JOIN factura_pagos as facp ON fact.id_factura=facp.id_factura
                                            where fact.id_empresa={$request->company} and facp.anticipo=1 and {$anticipo_pagos}");
                $empresa=DB::select("SELECT * from empresa where id_empresa={$request->company}");
                
                if (!$anticipos && !$pagos_anticipo) {
                    return response('no-data-report', 200)->header('Content-Type', 'application/json');
                } else {
                    if ($request->email && $request->destinatario) {
                        $Reportes = new generarReportes();
                        $ruta = constant("DATA_EMPRESA") . $request->company . '/facturacion/cuentas_cobrar';
                        if (!file_exists($ruta)) {
                            mkdir($ruta, 0755, true);
                        }
                        $strPDF = $Reportes->cuenta_cobrar_reporte_anticipo($clientes, $anticipos, $initial, $final, $pagos_anticipo, $empresa[0],$ruta);
                        $email = new sendEmail();
                        $email->enviarCtasCobrar($reporte4[0], $request->email, $request->destinatario, "Anticipo");
                        $cta = $ruta . '/cuenta_por_cobrar_Anticipo.pdf';
                        /*if(file_exists($cta)){
                            unlink($cta);
                        }*/
                        return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                    } else {
                        $Reportes = new generarReportes();
                        $strPDF = $Reportes->cuenta_cobrar_reporte_anticipo($clientes, $anticipos, $initial, $final, $pagos_anticipo, $empresa[0]);
                        return response($strPDF, 200)->header('Content-Type', 'application/pdf');
                    }
                }
                

            }
        }
    }
    public function guardarpagar(Request $request)
    {
        //fecha del registro del pago
        $fecha_registro_pago = $request->fecha_registro_pago;
        $pos=DB::select("SELECT * from user INNER JOIN punto_emision on punto_emision.id_punto_emision=user.id_punto_emision where id={$request->id_user}");
        $conteo=1;
        if(count($pos)>0){
            if($pos[0]->secuencial_recibo!==null){
                $conteo=$pos[0]->secuencial_recibo;
            } 
        }
        //guarda la fecha actual del servidor
        $hoy = Carbon::now();

        //verifica si el pago es diferente de anticipo
        if (!$request->ccliente["anticipo"]) {
            $factura = $request->ccliente['factura'];
            $data = DB::select("SELECT * FROM factura WHERE clave_acceso LIKE '%$factura%' and id_empresa=$request->empresa");
            //si la factura no existe reenvia error
            if (!count($data)) {
                return 'error';
            }
            $fecha_emision = "";
            //recupera el id de factura
            if (count($data) > 0) {
                $id_factura = $data[0]->id_factura;
                $fecha_emision = $data[0]->fecha_emision;
            } else {
                $id_factura = null;
            }

            //recorre los plazos existentes y guarda los registros saltandose por plazos agregados
            for ($a = 0; $a < $request->ccliente["plazo"]; $a++) {
                $cxc = new Cuentaporcobrar();
                $cxc->num_cuota = $a + 1;
                if ($a < 1) {
                    //dependiendo del tipo de plazo añade el tiempo a la fecha y lo guarda
                    if ($request->ccliente["periodo"] == "Años") {
                        $fecharec = $hoy->addYears($request->ccliente["tiempos"]);
                        $fd = $hoy->addYears($request->ccliente["tiempos"])->format('Y-m-d');
                    } else if ($request->ccliente["periodo"] == "Meses") {
                        $fecharec = $hoy->addMonths($request->ccliente["tiempos"]);
                        $fd = $hoy->addMonths($request->ccliente["tiempos"])->format('Y-m-d');
                    } else if ($request->ccliente["periodo"] == "Semanas") {
                        $fecharec = $hoy->addWeeks($request->ccliente["tiempos"]);
                        $fd = $hoy->addWeeks($request->ccliente["tiempos"])->format('Y-m-d');
                    } else {
                        $fecharec = $hoy->addDays($request->ccliente["tiempos"]);
                        $fd = $hoy->addDays($request->ccliente["tiempos"])->format('Y-m-d');
                    }
                } else {
                    //dependiendo del tipo de plazo añade el tiempo a la fecha y lo guarda, suma de la fecha ya agregada lo suma nuevamente esto es para los plazos mayores a dos
                    if ($request->ccliente["periodo"] == "Años") {
                        $fd = $fecharec->addYears($request->ccliente["tiempos"])->format('Y-m-d');
                    } else if ($request->ccliente["periodo"] == "Meses") {
                        $fd = $fecharec->addMonths($request->ccliente["tiempos"])->format('Y-m-d');
                    } else if ($request->ccliente["periodo"] == "Semanas") {
                        $fd = $fecharec->addWeeks($request->ccliente["tiempos"])->format('Y-m-d');
                    } else {
                        $fd = $fecharec->addDays($request->ccliente["tiempos"])->format('Y-m-d');
                    }
                }
                //guarda los demas registros
                $cxc->fecha_pago = $fd;
                $cxc->fecha_factura = $fecha_emision;
                $cxc->periodo_pagos = $request->ccliente["periodo"];
                $cxc->valor_cuota = round($request->ccliente["monto"] / $request->ccliente["plazo"], 2, PHP_ROUND_HALF_UP);
                $cxc->valor_pagado = 0;
                $cxc->estado = 1;
                $cxc->tipo = 1;
                $cxc->id_factura = $id_factura;
                $cxc->id_cliente = $request->cliente["id_cliente"];
                $cxc->fecha_registro = $fecha_registro_pago;
                $cxc->created_by = session()->get('usuariosesion')['id'];
                $cxc->updated_by = session()->get('usuariosesion')['id'];
                $cxc->save();
            }

        } else {
            //recupera valores del pago si es anticipo
            $id = $request->cliente["id_cliente"];
            $monto = $request->ccliente["monto"];
            $formapago = $request->ccliente["formapago"];
            $nrocheque = "";
            if (isset($request->ccliente["nrocheque"])) {
                $nrocheque = $request->ccliente["nrocheque"];
            }


            //guarda la cuenta por cobrar como anticipo mediante tipo = 3
            $cxc = new Cuentaporcobrar();
            $cxc->num_cuota = 1;
            $cxc->fecha_pago = $hoy;
            $cxc->valor_cuota = $monto;
            $cxc->estado = 1;
            $cxc->tipo = 3;
            $cxc->abono = $monto;
            $cxc->id_forma_pagos = $formapago;
            $cxc->id_cliente = $id;
            $cxc->fecha_registro = $fecha_registro_pago;
            $cxc->ucrea = $request->id_user;
            $cxc->posicion = $conteo;
            if (isset($nrocheque)) {
                $cxc->numero_transaccion = $nrocheque;
            }
            $cxc->created_by = session()->get('usuariosesion')['id'];
            $cxc->updated_by = session()->get('usuariosesion')['id'];
            $cxc->save();
            

            //genera cuentas por cobrar pagos y guarda como anticipo para mantener registro del pago
            $cxcp = new Ctas_cobrar_pagos();
            $cxcp->pagos_por = "Anticipo";
            $cxcp->valor_seleccionado = $monto;
            $cxcp->valor_real_pago = $monto;
            $cxcp->id_forma_pagos = $formapago;
            $cxcp->fecha_pago = $fecha_registro_pago;
            $cxcp->fecha_registro = $hoy;
            $cxcp->id_cliente = $id;
            $cxcp->ucrea = $request->id_user;
            $cxcp->created_by = session()->get('usuariosesion')['id'];
            $cxcp->updated_by = session()->get('usuariosesion')['id'];
            $cxcp->posicion = $conteo;
            $cxcp->save();
            $conteo=1;
            if(count($pos)>0){
                if($pos[0]->secuencial_recibo!==null){
                    $conteo=$pos[0]->secuencial_recibo+1;
                } 
            }
            if(count($pos)>0){
                DB::update("UPDATE punto_emision set secuencial_recibo={$conteo} where  id_punto_emision={$pos[0]->id_punto_emision}");
            }
            $id_cxcp=$cxcp->id_ctas_cobrar_pagos;
            return $id_cxcp;
        }
    }
    public function anticipo(Request $request)
    {
        //recupera todos los anticipos del cliente que sea del tipo 3 y los suma para obtener el valor de anticipo total del cliente
        $id = $request->id;
        $valor = DB::select("SELECT SUM(abono) as anticipo FROM ctas_cobrar WHERE id_cliente = $id AND tipo=3");
        $res = $valor[0]->anticipo;
        return $res;
    }
    public function listarcuentaslista(Request $request)
    {
        //lista los pagos y sus conexiones ademas de hacer a lista de anticipos mediante el id de la empresa y la busqueda que se genera
        $id = $request->id;
        $buscar = str_replace(array(" "), "%", $request->buscar);
        $valor = DB::select("SELECT cop.*, cl.nombre AS nombrecliente, fp.descripcion AS descripcionsri, IF(fecha_registro IS NULL, fecha_pago, fecha_registro) as fechageneral FROM ctas_cobrar_pagos cop INNER JOIN cliente cl ON cl.id_cliente = cop.id_cliente LEFT JOIN forma_pagos fp ON fp.id_forma_pagos = cop.id_forma_pagos LEFT JOIN forma_pagos_sri fps ON fps.id_forma_pagos_sri = fp.id_forma_pagos_sri INNER JOIN empresa em ON em.id_empresa = cl.id_empresa WHERE em.id_empresa = $id AND cop.pagos_por != 'Anticipo' AND (pagos_por like '%$buscar%' OR nro_tarjeta like '%$buscar%' OR referencia like '%$buscar%' OR valor_seleccionado like '%$buscar%' OR valor_real_pago like '%$buscar%' OR cl.nombre like '%$buscar%' OR fp.descripcion like '%$buscar%') and cop.nota_credito is null ORDER BY cop.fecha_pago DESC, cop.id_ctas_cobrar_pagos DESC");
        //recorre las referencias de los registros de pagos y los conviertes
        $abonos = DB::select("SELECT *,IF(fecha_registro IS NULL, fecha_pago, fecha_registro) as fechageneral,contabilidad FROM ctas_cobrar cb INNER JOIN cliente c ON cb.id_cliente = c.id_cliente INNER JOIN forma_pagos fp ON cb.id_forma_pagos = fp.id_forma_pagos WHERE c.id_empresa = $id AND cb.tipo = 3 and cb.nota_credito is null ORDER BY cb.id_ctascobrar DESC");
        for ($i = 0; $i < count($valor); $i++) {
            $valor[$i]->referencia = explode(";", $valor[$i]->referencia);
        }
        //unifica los registros de pago y los registros de los anticipos
        $res = array_merge($valor, $abonos);
        if (session()->get('usuariosesion')['filtro_list'] == 1) {
            $dat = [];
            for ($i = 0; $i < count($res); $i++) {
                if ($res[$i]->created_by == session()->get('usuariosesion')['id'] || $res[$i]->updated_by == session()->get('usuariosesion')['id'] || $res[$i]->id_vendedor == session()->get('usuariosesion')['id_vendedor']) {
                    array_push($dat, $res[$i]);
                }
            }
            $res = $dat;
        }
        return $res;
    }

    function guardar_edicion_pago(Request $rq)
    {
        $data0 = $rq;
        if ($data0["id_user"] == null) {
            return "id_user";
        }
        // return;
        $dsco = 0;
        //verifica si existe descuento caso contrario queda en 0
        if (isset($rq->descuento_pago)) {
            $dsco = $rq->descuento_pago;
        }
        $valor_pagar = $rq->valor_real_pago + $dsco;
        /*if($rq->pagos_por =='Anticipo'){
            DB::update("UPDATE ctas_cobrar SET abono = abono - $valor_pagar WHERE id_cliente = $idcliente AND tipo = 3");
        }*/
        //guarda la fecha actual del servidor
        $hoy = Carbon::now();
        $data = $rq;
        //actualiza las cuentas por cobrar pagos
        $cxcp = Ctas_cobrar_pagos::find($data["id_ctas_cobrar_pagos"]);
        $cxcp->pagos_por = $data["pagos_por"];
        $cxcp->nro_tarjeta = $data["nro_tarjeta"];
        $cxcp->valor_seleccionado = $data["valor_seleccionado"];
        $cxcp->descuento_porcentaje = $data["descuento_porcentaje"];
        $cxcp->descuento_pago = $data["descuento_pago"];
        $cxcp->valor_real_pago = $data["valor_real_pago"];
        $cxcp->id_forma_pagos = $data["id_forma_pagos"];
        $cxcp->id_banco = $data["id_banco"];
        $cxcp->fecha_pago = $data["fecha_pago"];
        $cxcp->umodifica = $data["id_user"];
        //$cxcp->fecha_registro = $hoy;
        $cxcp->updated_by = session()->get('usuariosesion')['id'];
        $cxcp->save();

        //recupera el id del registro
        $idcxcp = $data["id_ctas_cobrar_pagos"];
        $referencia = null;
        for ($c = 0; $c < count($rq->contenido); $c++) {
            if (isset($rq->contenido[$c]["agregar"])) {
                if ($rq->contenido[$c]["agregar"]) {
                    //cuando se va a hacer varios pagos o pagos parciales entrara a este if y restara el valor_pagar con el valor
                    //caso contrario cuando el pago es menoro igual al saldo entra a else y genera el guardado y guarda directamente en el su Ctas_cobrar_pagos
                    if ($valor_pagar > ($rq->contenido[$c]["valor_cuota"] - $rq->contenido[$c]["valor_pagado"])) {
                        $pago = Cuentaporcobrar::findOrFail($rq->contenido[$c]["id_ctascobrar"]);
                        $pago->pagos_por = $rq->pagos_por;
                        $pago->id_forma_pagos = $rq->forma_pago;
                        $pago->id_banco = $rq->banco;
                        $pago->numero_tarjeta = $rq->numero_tarjeta;
                        $pago->descuento = $pago->descuento + $rq->descuento_pago;
                        //$pago->valor_pagado = $pago->valor_pagado + ($rq->contenido[$c]["valor_cuota"] - $rq->contenido[$c]["valor_pagado"]);
                        $pagado_cta = $pago->valor_pagado - ($rq->contenido[$c]["valor_cuota"] - $rq->contenido[$c]["valor_pagado"]);
                        //$pago->valor_pagado=$pago->valor_pagado+$pagado_cta;
                        if ($pago->valor_pagado < $pago->valor_cuota) {
                            $pago->valor_pagado = $pago->valor_pagado + $pagado_cta;
                        }
                        $pago->fecha_registro = $rq->fecha_registro;
                        $pago->updated_by = session()->get('usuariosesion')['id'];
                        $pago->save();
                        $valor_pagar = $valor_pagar - ($rq->contenido[$c]["valor_cuota"] - $rq->contenido[$c]["valor_pagado"]);
                        $valor_pagado = ($rq->contenido[$c]["valor_cuota"] - $rq->contenido[$c]["valor_pagado"]);
                        //aqui guarda un string concatenado por clave de acceso, id_ctascobrar, valor_pagado y la id_factura de cuentaporcobrar
                        if (isset($rq->contenido[$c]["id_factura"])) {
                            $referencia .= substr($rq->contenido[$c]["clave_acceso"], 24, 3) . "-" . substr($rq->contenido[$c]["clave_acceso"], 27, 3) . "-" . substr($rq->contenido[$c]["clave_acceso"], 30, 9) . ";" . $rq->contenido[$c]["id_ctascobrar"] . ";" . number_format($valor_pagado, 2, ".", "") . ";" . $rq->contenido[$c]["id_factura"] . ";";
                        } else {
                            if (isset($rq->contenido[$c]["id_nota_venta"])) {
                                $referencia .= substr($rq->contenido[$c]["clave_acceso"], 24, 3) . "-" . substr($rq->contenido[$c]["clave_acceso"], 27, 3) . "-" . substr($rq->contenido[$c]["clave_acceso"], 30, 9) . ";" . $rq->contenido[$c]["id_ctascobrar"] . ";" . number_format($valor_pagado, 2, ".", "") . ";" . 'nv:' . $rq->contenido[$c]["id_nota_venta"] . ";";
                            } else {
                                $referencia .= $rq->contenido[$c]["clave_acceso2"] . ";" . $rq->contenido[$c]["id_ctascobrar"] . ";" . number_format($valor_pagado, 2, ".", "") . ";" . $rq->contenido[$c]["id_factura2"] . ";";
                            }
                        }
                    } else {
                        $pago = Cuentaporcobrar::findOrFail($rq->contenido[$c]["id_ctascobrar"]);
                        $pago->pagos_por = $rq->pagos_por;
                        $pago->id_forma_pagos = $rq->forma_pago;
                        $pago->id_banco = $rq->banco;
                        $pago->numero_tarjeta = $rq->numero_tarjeta;
                        $pago->descuento = $pago->descuento + $rq->descuento_pago;
                        //$pago->valor_pagado = $pago->valor_pagado + $valor_pagar;
                        $pagado_cta = $valor_pagar - ($rq->contenido[$c]["valor_cuota"] - $rq->contenido[$c]["valor_pagado"]);
                        $pago->valor_pagado = $pago->valor_pagado + $pagado_cta;
                        $pago->fecha_registro = $rq->fecha_registro;
                        $pago->updated_by = session()->get('usuariosesion')['id'];
                        $pago->save();
                        //aqui guarda un string concatenado por clave de acceso, id_ctascobrar, valor_pagado y la id_factura de cuentaporcobrar
                        if (isset($rq->contenido[$c]["clave_acceso"])) {
                            if (isset($rq->contenido[$c]["id_nota_venta"])) {
                                $referencia .= substr($rq->contenido[$c]["clave_acceso"], 24, 3) . "-" . substr($rq->contenido[$c]["clave_acceso"], 27, 3) . "-" . substr($rq->contenido[$c]["clave_acceso"], 30, 9) . ";" . $rq->contenido[$c]["id_ctascobrar"] . ";" . number_format($valor_pagar, 2, ".", "") . ";" . "nv:" . $rq->contenido[$c]["id_nota_venta"] . ";";
                            } else {
                                $referencia .= substr($rq->contenido[$c]["clave_acceso"], 24, 3) . "-" . substr($rq->contenido[$c]["clave_acceso"], 27, 3) . "-" . substr($rq->contenido[$c]["clave_acceso"], 30, 9) . ";" . $rq->contenido[$c]["id_ctascobrar"] . ";" . number_format($valor_pagar, 2, ".", "") . ";" . $rq->contenido[$c]["id_factura"] . ";";
                            }
                        } else {
                            $referencia .= $rq->contenido[$c]["clave_acceso2"] . ";" . $rq->contenido[$c]["id_ctascobrar"] . ";" . number_format($valor_pagar, 2, ".", "") . ";" . $rq->contenido[$c]["id_factura2"] . ";";
                        }
                        //nuevamente edita los pagos de cuentas por pagar y agrega la referencia de cuentaporpagar separado por ;
                        $ref = substr($referencia, 0, -1);
                        $cxcp = Ctas_cobrar_pagos::findOrFail($idcxcp);
                        $cxcp->referencia = $ref;
                        $cxcp->updated_by = session()->get('usuariosesion')['id'];
                        $cxcp->save();
                        return;
                    }
                }
            }
        }
        //nuevamente edita los pagos de cuentas por pagar y agrega la referencia de cuentaporpagar separado por ;
        $ref = substr($referencia, 0, -1);
        $cxcp = Ctas_cobrar_pagos::findOrFail($idcxcp);
        $cxcp->referencia = $ref;
        $cxcp->updated_by = session()->get('usuariosesion')['id'];
        $cxcp->save();
        return;
    }
    public function verAsiento(Request $request, $id)
    {
        $cta_cobrar = DB::select("SELECT cop.*,cl.nombre,cl.tipo_identificacion,cl.identificacion from ctas_cobrar_pagos as cop,cliente as cl where cop.id_cliente=cl.id_cliente and cop.id_ctas_cobrar_pagos={$id}");
        $proyecto = DB::select("SELECT * from proyecto where id_empresa=" . $request->id_empresa . " limit 1");
        $nro_facturas = 0;
        $nro_ctas = 0;
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
        $array_id_factura = [];



        //dd($nro_ctas);
        $id_facturas = [];
        $id_ctas = [];
        $id_notas_venta = [];
        if (isset($cta_cobrar)) {
            for ($i = 0; $i < count($cta_cobrar); $i++) {
                $data = explode(";", $cta_cobrar[$i]->referencia);
                $registros = count($data) / 4;
                $salto = 0;
                for ($f = 0; $f < $registros; $f++) {
                    $id_cb = $data[1 + $salto];
                    if ($id_cb) {
                        array_push($id_ctas, "ctas_cobrar.id_ctascobrar={$id_cb}");
                    }
                    $valor = $data[2 + $salto];
                    $idf = $data[3 + $salto];
                    //array_push($idf);
                    if ($idf) {
                        //$array_factura .= " factura.id_factura={$idf} or";
                        $pos = strpos($idf, 'nv:');
                        if ($pos !== false) {
                            $number = substr($idf, 3);
                            array_push($id_notas_venta, "nota_venta.id_nota_venta={$number}");
                        } else {
                            array_push($id_facturas, "factura.id_factura={$idf}");
                        }
                    }
                    //array_push($array_id_factura,$cliente);
                    $salto = $salto + 4;
                }
            }
        }
        // $array_factura = substr($array_factura,0 ,-2);
        // $array_factura .= ")";
        //dd($id_notas_venta);
        $id_notas_venta = implode(" or ", $id_notas_venta);
        $id_facturas = implode(" or ", $id_facturas);
        $id_ctas = implode(" or ", $id_ctas);
        $x_factura = "";
        $y_factura = "";
        $a = "";
        $b = "";
        $x_nota_venta = "";
        $y_nota_venta = "";
        $c = "";
        $d = "";
        if ($id_facturas) {
            $y_factura = " and ";
            $a = "(";
            $b = ")";
        } else {
            $x_factura = " and factura.id_factura=0 ";
        }
        if ($id_notas_venta) {
            $y_nota_venta = " and ";
            $c = "(";
            $d = ")";
        } else {
            $x_nota_venta = " and nota_venta.id_nota_venta=0 ";
        }
        //dd($id_facturas);
        if ($id_facturas || $id_notas_venta) {
            $array_factura = "SELECT if(cliente.id_plan_cuentas is null,'no','si') as exist_plan_cuenta_cliente,cliente.id_plan_cuentas as id_plan_cuentas_cliente,
            (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(cliente.id_plan_cuentas is null,0,cliente.id_plan_cuentas) and id_empresa={$request->id_empresa}) as nombre_cuenta_cliente,grupo_cliente.id_plan_cuentas as id_plan_cuentas_grupo,
            (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(grupo_cliente.id_plan_cuentas is null,0,grupo_cliente.id_plan_cuentas) and id_empresa={$request->id_empresa}) as nombre_cuenta_grupo,ctas_cobrar_pagos.valor_real_pago as haber_talves,null as debe,proyecto.id_proyecto,
            proyecto.descripcion,factura.id_factura,round(detalle.total/factura.subtotal_sin_impuesto,2) as porcentaje,round(ctas_cobrar_pagos.valor_real_pago/{$nro_ctas}*(detalle.total/factura.subtotal_sin_impuesto),2) as haber,plan_cuentas.bansel
            from cliente
            LEFT JOIN grupo_cliente
            on grupo_cliente.id_grupo_cliente=cliente.id_grupo_cliente
            LEFT JOIN plan_cuentas
            on plan_cuentas.id_plan_cuentas=grupo_cliente.id_plan_cuentas
            INNER JOIN ctas_cobrar_pagos
            on ctas_cobrar_pagos.id_cliente=cliente.id_cliente
                    INNER JOIN factura
            on factura.id_cliente=cliente.id_cliente
                    INNER JOIN detalle
            on detalle.id_factura=factura.id_factura
                    LEFT JOIN proyecto
            on proyecto.id_proyecto=detalle.id_proyecto
            where ctas_cobrar_pagos.id_ctas_cobrar_pagos={$id} {$y_factura} {$a}$id_facturas{$b} {$x_factura}
            UNION
            SELECT if(cliente.id_plan_cuentas is null,'no','si') as exist_plan_cuenta_cliente,cliente.id_plan_cuentas as id_plan_cuentas_cliente,
                (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(cliente.id_plan_cuentas is null,0,cliente.id_plan_cuentas) and id_empresa={$request->id_empresa}) as nombre_cuenta_cliente,grupo_cliente.id_plan_cuentas as id_plan_cuentas_grupo,
                (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(grupo_cliente.id_plan_cuentas is null,0,grupo_cliente.id_plan_cuentas) and id_empresa={$request->id_empresa}) as nombre_cuenta_grupo,ctas_cobrar_pagos.valor_real_pago as haber_talves,null as debe,proyecto.id_proyecto,
                proyecto.descripcion,nota_venta.id_nota_venta,round(detalle_nota_venta.total/nota_venta.subtotal_sin_impuesto,2) as porcentaje,round(ctas_cobrar_pagos.valor_real_pago/{$nro_ctas}*(detalle_nota_venta.total/nota_venta.subtotal_sin_impuesto),2) as haber,plan_cuentas.bansel
                from cliente
                LEFT JOIN grupo_cliente
                on grupo_cliente.id_grupo_cliente=cliente.id_grupo_cliente
                LEFT JOIN plan_cuentas
                on plan_cuentas.id_plan_cuentas=grupo_cliente.id_plan_cuentas
                INNER JOIN ctas_cobrar_pagos
                on ctas_cobrar_pagos.id_cliente=cliente.id_cliente
                INNER JOIN nota_venta
                on nota_venta.id_cliente=cliente.id_cliente
                INNER JOIN detalle_nota_venta
                on detalle_nota_venta.id_nota_venta=nota_venta.id_nota_venta
                LEFT JOIN proyecto
                on proyecto.id_proyecto=detalle_nota_venta.id_proyecto
            where ctas_cobrar_pagos.id_ctas_cobrar_pagos={$id} {$y_nota_venta} {$c}$id_notas_venta{$d} {$x_nota_venta}";
        } else {

            $array_factura = "SELECT if(cliente.id_plan_cuentas is null,'no','si') as exist_plan_cuenta_cliente,cliente.id_plan_cuentas as id_plan_cuentas_cliente,
                (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(cliente.id_plan_cuentas is null,0,cliente.id_plan_cuentas) and id_empresa={$request->id_empresa}) as nombre_cuenta_cliente,grupo_cliente.id_plan_cuentas as id_plan_cuentas_grupo,
                (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(grupo_cliente.id_plan_cuentas is null,0,grupo_cliente.id_plan_cuentas) and id_empresa={$request->id_empresa}) as nombre_cuenta_grupo,ctas_cobrar_pagos.valor_real_pago as haber_talves,null as debe,proyecto.id_proyecto,
                proyecto.descripcion,factura.id_factura,round(detalle.total/factura.subtotal_sin_impuesto,2) as porcentaje,round(ctas_cobrar_pagos.valor_real_pago/{$nro_ctas}*(detalle.total/factura.subtotal_sin_impuesto),2) as haber,plan_cuentas.bansel
                from cliente
                LEFT JOIN grupo_cliente
                on grupo_cliente.id_grupo_cliente=cliente.id_grupo_cliente
                LEFT JOIN plan_cuentas
                on plan_cuentas.id_plan_cuentas=grupo_cliente.id_plan_cuentas
                INNER JOIN ctas_cobrar_pagos
                on ctas_cobrar_pagos.id_cliente=cliente.id_cliente
                        INNER JOIN factura
                on factura.id_cliente=cliente.id_cliente
                        INNER JOIN detalle
                on detalle.id_factura=factura.id_factura
                        LEFT JOIN proyecto
                on proyecto.id_proyecto=detalle.id_proyecto
                where ctas_cobrar_pagos.id_ctas_cobrar_pagos={$id} and factura.id_factura=0";
        }
        //dd($array_factura);
        $select = DB::select($array_factura);
        if ($id_ctas) {
            $array_referencia = "SELECT if(cliente.id_plan_cuentas is null,'no','si') as exist_plan_cuenta_cliente,cliente.id_plan_cuentas as id_plan_cuentas_cliente,
            (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(cliente.id_plan_cuentas is null,0,cliente.id_plan_cuentas) and id_empresa={$request->id_empresa}) as nombre_cuenta_cliente,grupo_cliente.id_plan_cuentas as id_plan_cuentas_grupo,
            (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(grupo_cliente.id_plan_cuentas is null,0,grupo_cliente.id_plan_cuentas) and id_empresa={$request->id_empresa}) as nombre_cuenta_grupo,ctas_cobrar_pagos.valor_real_pago as haber_talves,null as debe,
            (select id_proyecto from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as id_proyecto,
            (select descripcion from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as descripcion,
            null as id_factura,ctas_cobrar_pagos.valor_real_pago/{$nro_ctas} as porcentaje,round(ctas_cobrar_pagos.valor_real_pago/{$nro_ctas},2) as haber,plan_cuentas.bansel
                                    from cliente
            LEFT JOIN grupo_cliente
            on grupo_cliente.id_grupo_cliente=cliente.id_grupo_cliente
            LEFT JOIN plan_cuentas
            on plan_cuentas.id_plan_cuentas=grupo_cliente.id_plan_cuentas
            INNER JOIN ctas_cobrar_pagos
            on ctas_cobrar_pagos.id_cliente=cliente.id_cliente
            INNER JOIN ctas_cobrar
            on ctas_cobrar.id_cliente=cliente.id_cliente
            where ctas_cobrar_pagos.id_ctas_cobrar_pagos={$id} and ctas_cobrar.referencias is not null and ({$id_ctas})";
        } else {
            $array_referencia = "SELECT if(cliente.id_plan_cuentas is null,'no','si') as exist_plan_cuenta_cliente,cliente.id_plan_cuentas as id_plan_cuentas_cliente,
            (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(cliente.id_plan_cuentas is null,0,cliente.id_plan_cuentas) and id_empresa={$request->id_empresa}) as nombre_cuenta_cliente,grupo_cliente.id_plan_cuentas as id_plan_cuentas_grupo,
            (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(grupo_cliente.id_plan_cuentas is null,0,grupo_cliente.id_plan_cuentas) and id_empresa={$request->id_empresa}) as nombre_cuenta_grupo,ctas_cobrar_pagos.valor_real_pago as haber_talves,null as debe,
            (select id_proyecto from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as id_proyecto,
            (select descripcion from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as descripcion,
            null as id_factura,ctas_cobrar_pagos.valor_real_pago/{$nro_ctas} as porcentaje,round(ctas_cobrar_pagos.valor_real_pago/{$nro_ctas},2) as haber,plan_cuentas.bansel
                                    from cliente
            LEFT JOIN grupo_cliente
            on grupo_cliente.id_grupo_cliente=cliente.id_grupo_cliente
            LEFT JOIN plan_cuentas
            on plan_cuentas.id_plan_cuentas=grupo_cliente.id_plan_cuentas
            INNER JOIN ctas_cobrar_pagos
            on ctas_cobrar_pagos.id_cliente=cliente.id_cliente
            INNER JOIN ctas_cobrar
            on ctas_cobrar.id_cliente=cliente.id_cliente
            where ctas_cobrar_pagos.id_ctas_cobrar_pagos={$id} and ctas_cobrar.referencias is not null and ctas_cobrar.id_ctascobrar=0";
        }
        //dd($array_referencia);
        $select_referencia = DB::select($array_referencia);
        $select_cliente = array_merge($select, $select_referencia);
        //dd($select_cliente);
        //dd(array_values($array_id_factura));
        //dd($array_factura);
        // $cliente=DB::select("SELECT if(cliente.id_plan_cuentas is null,'no','si') as exist_plan_cuenta_cliente,cliente.id_plan_cuentas as id_plan_cuentas_cliente,
        // (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(cliente.id_plan_cuentas is null,0,cliente.id_plan_cuentas) and id_empresa={$request->id_empresa}) as nombre_cuenta_cliente,grupo_cliente.id_plan_cuentas as id_plan_cuentas_grupo,
        // (select concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) from plan_cuentas where id_plan_cuentas=if(grupo_cliente.id_plan_cuentas is null,0,grupo_cliente.id_plan_cuentas) and id_empresa={$request->id_empresa}) as nombre_cuenta_grupo,ctas_cobrar_pagos.valor_real_pago as haber,null as debe,null as id_proyecto,
        // null as descripcion
        // from cliente
        // LEFT JOIN grupo_cliente
        // on grupo_cliente.id_grupo_cliente=cliente.id_grupo_cliente
        // LEFT JOIN plan_cuentas
        // on plan_cuentas.id_plan_cuentas=grupo_cliente.id_plan_cuentas
        // INNER JOIN ctas_cobrar_pagos
        // on ctas_cobrar_pagos.id_cliente=cliente.id_cliente
        // where ctas_cobrar_pagos.id_ctas_cobrar_pagos={$id}");
        $codigo = DB::select("SELECT max(numero) as codigo FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.codigo like 'CC-%' and (asientos.estado='Activo' or asientos.estado is null) and proyecto.id_empresa=" . $request->id_empresa);
        $cod_asiento = "";
        if ($codigo) {
            $lenght = strlen($codigo[0]->codigo);
            $valor = $codigo[0]->codigo + 1;
            $cod_asiento = $codigo[0]->codigo + 1;
        } else {
            $cod_asiento = "1";
        }
        $cod_asiento_ant = "";
        if ($codigo) {
            $codigo_ant = DB::select("SELECT asientos.numero FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.id_asientos_comprobante=8 and (asientos.estado='Activo' or asientos.estado is null) and asientos.concepto not like '%Anticipo%' and asientos.codigo_rol={$id} and proyecto.id_empresa=" . $request->id_empresa . " ORDER BY asientos.codigo desc limit 1");
            if ($codigo_ant) {
                $cod_asiento_ant = $codigo_ant[0]->numero;
            }
        }

        //$query_forma_pago = substr($query_forma_pago,0 ,-2);
        //$query_forma_pago .= ")";
        if ($id_facturas || $id_notas_venta) {
            $query_forma_pago =
                "SELECT plan_cuentas.id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,ctas_cobrar_pagos.fecha_pago,ctas_cobrar_pagos.fecha_registro,forma_pagos.id_forma_pagos,ctas_cobrar_pagos.nro_tarjeta,banco.id_banco,banco.nombre_banco,forma_pagos.descripcion as nombre_pago,ctas_cobrar_pagos.valor_real_pago as debe_tal,null haber,proyecto.id_proyecto,
                proyecto.descripcion,factura.id_factura,round(detalle.total/factura.subtotal_sin_impuesto,2) as porcentaje,round(ctas_cobrar_pagos.valor_real_pago/{$nro_ctas}*(detalle.total/factura.subtotal_sin_impuesto),2) as debe,plan_cuentas.bansel 
                from forma_pagos
                LEFT JOIN plan_cuentas
                on plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas
                INNER JOIN ctas_cobrar_pagos
                on ctas_cobrar_pagos.id_forma_pagos=forma_pagos.id_forma_pagos
                LEFT JOIN banco
                on banco.id_banco=ctas_cobrar_pagos.id_banco
                INNER JOIN factura
                on factura.id_cliente=ctas_cobrar_pagos.id_cliente
                INNER JOIN detalle
                on detalle.id_factura=factura.id_factura
                LEFT JOIN proyecto
                on proyecto.id_proyecto=detalle.id_proyecto
            where ctas_cobrar_pagos.id_ctas_cobrar_pagos={$id} {$y_factura} {$a}{$id_facturas}{$b} {$x_factura}
            UNION
            SELECT plan_cuentas.id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,ctas_cobrar_pagos.fecha_pago,ctas_cobrar_pagos.fecha_registro,forma_pagos.id_forma_pagos,ctas_cobrar_pagos.nro_tarjeta,banco.id_banco,banco.nombre_banco,forma_pagos.descripcion as nombre_pago,ctas_cobrar_pagos.valor_real_pago as debe_tal,null haber,proyecto.id_proyecto,
                proyecto.descripcion,nota_venta.id_nota_venta,round(detalle_nota_venta.total/nota_venta.subtotal_sin_impuesto,2) as porcentaje,round(ctas_cobrar_pagos.valor_real_pago/{$nro_ctas}*(detalle_nota_venta.total/nota_venta.subtotal_sin_impuesto),2) as debe,plan_cuentas.bansel 
                from forma_pagos
                LEFT JOIN plan_cuentas
                on plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas
                INNER JOIN ctas_cobrar_pagos
                on ctas_cobrar_pagos.id_forma_pagos=forma_pagos.id_forma_pagos
                LEFT JOIN banco
                on banco.id_banco=ctas_cobrar_pagos.id_banco
                INNER JOIN nota_venta
                on nota_venta.id_cliente=ctas_cobrar_pagos.id_cliente
                INNER JOIN detalle_nota_venta
                on detalle_nota_venta.id_nota_venta=nota_venta.id_nota_venta
                LEFT JOIN proyecto
                on proyecto.id_proyecto=detalle_nota_venta.id_proyecto
            where ctas_cobrar_pagos.id_ctas_cobrar_pagos={$id} {$y_nota_venta} {$c}{$id_notas_venta}{$d} {$x_nota_venta}";
        } else {
            $query_forma_pago = "SELECT plan_cuentas.id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,ctas_cobrar_pagos.fecha_pago,ctas_cobrar_pagos.fecha_registro,forma_pagos.id_forma_pagos,ctas_cobrar_pagos.nro_tarjeta,banco.id_banco,banco.nombre_banco,forma_pagos.descripcion as nombre_pago,ctas_cobrar_pagos.valor_real_pago as debe_tal,null haber,proyecto.id_proyecto,
                proyecto.descripcion,factura.id_factura,round(detalle.total/factura.subtotal_sin_impuesto,2) as porcentaje,round(ctas_cobrar_pagos.valor_real_pago/{$nro_ctas}*(detalle.total/factura.subtotal_sin_impuesto),2) as debe,plan_cuentas.bansel from forma_pagos
                LEFT JOIN plan_cuentas
                on plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas
                INNER JOIN ctas_cobrar_pagos
                on ctas_cobrar_pagos.id_forma_pagos=forma_pagos.id_forma_pagos
                LEFT JOIN banco
                on banco.id_banco=ctas_cobrar_pagos.id_banco
                INNER JOIN factura
                on factura.id_cliente=ctas_cobrar_pagos.id_cliente
                INNER JOIN detalle
                on detalle.id_factura=factura.id_factura
                LEFT JOIN proyecto
                on proyecto.id_proyecto=detalle.id_proyecto
                where ctas_cobrar_pagos.id_ctas_cobrar_pagos={$id} and factura.id_factura=0";
        }
        if ($id_ctas) {
            $query_form_pago_ref = "SELECT plan_cuentas.id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,ctas_cobrar_pagos.fecha_pago,ctas_cobrar_pagos.fecha_registro,forma_pagos.id_forma_pagos,ctas_cobrar_pagos.nro_tarjeta,banco.id_banco,banco.nombre_banco,forma_pagos.descripcion as nombre_pago,ctas_cobrar_pagos.valor_real_pago as debe_tal,null haber,
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
                        where ctas_cobrar_pagos.id_ctas_cobrar_pagos={$id} and ctas_cobrar.referencias is not null and ({$id_ctas})";
        } else {
            $query_form_pago_ref = "SELECT plan_cuentas.id_plan_cuentas,concat(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta,ctas_cobrar_pagos.fecha_pago,ctas_cobrar_pagos.fecha_registro,forma_pagos.id_forma_pagos,ctas_cobrar_pagos.nro_tarjeta,banco.id_banco,banco.nombre_banco,forma_pagos.descripcion as nombre_pago,ctas_cobrar_pagos.valor_real_pago as debe_tal,null haber,
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
                        where ctas_cobrar_pagos.id_ctas_cobrar_pagos={$id} and ctas_cobrar.referencias is not null and ctas_cobrar.id_ctascobrar=0";
        }
        //dd($query_forma_pago);
        $select_pago_fact = DB::select($query_forma_pago);
        $select_pago_ref = DB::select($query_form_pago_ref);
        $select_pago = array_merge($select_pago_fact, $select_pago_ref);
        $fecha_emision = "";
        if ($cta_cobrar[0]->fecha_registro !== null) {
            $fecha_emision = $cta_cobrar[0]->fecha_registro;
        } else {
            $fecha_emision = $cta_cobrar[0]->fecha_pago;
        }
        $fecha_emision = substr($fecha_emision, 0, -3);
        $anio_emision = substr($fecha_emision, 0, 4);
        $fecha_cierre = DB::select("SELECT * 
                                    from asientos 
                                        where id_empresa={$request->id_empresa} 
                                        and cierre_contable='Cierre Mes' and periodo='{$fecha_emision}'
                                UNION
                                SELECT * 
                                    from asientos 
                                        where id_empresa={$request->id_empresa} 
                                        and cierre_contable='Estado Contable' and periodo='{$anio_emision}'");
        $asiento = "";
        if (count($fecha_cierre) > 0) {
            $asiento = "no";
        } else {
            $asiento = "si";
        }
        return [
            'ctas_cobrar' => $cta_cobrar[0],
            'asiento_permitido' => $asiento,
            'cliente' => $select_cliente,
            'codigo' => $cod_asiento,
            'codigo_anterior' => $cod_asiento_ant,
            'forma_pago' => $select_pago
        ];
    }
    public function verAsientoAnticipo(Request $request, $id)
    {
        $cta_cobrar = DB::select("SELECT null as anticipo,contabilidad,fecha_pago,fecha_registro,cliente.id_cliente,cliente.identificacion,cliente.nombre,cliente.tipo_identificacion 
        from ctas_cobrar 
        INNER JOIN cliente
        on cliente.id_cliente=ctas_cobrar.id_cliente 
        where id_ctascobrar={$id}");
        $proyecto = DB::select("SELECT * from proyecto where id_empresa=" . $request->id_empresa . " limit 1");
        $nro_facturas = 0;
        $nro_ctas = 0;

        $select_cliente = DB::select("SELECT if(plan_cuentas.id_plan_cuentas is null,'si','no') as exist_plan_cuenta_cliente,(SELECT id_plan_cuentas from plan_cuentas where id_plan_cuentas=if(cliente.id_plan_cuentas is null,0,cliente.id_plan_cuentas)) as id_plan_cuentas_cliente,(SELECT CONCAT(codcta,'-',nomcta) from plan_cuentas where id_plan_cuentas=if(cliente.id_plan_cuentas is null,0,cliente.id_plan_cuentas)) as nombre_cuenta_cliente,
        plan_cuentas.id_plan_cuentas as id_plan_cuentas_grupo,CONCAT(plan_cuentas.codcta,'-',plan_cuentas.nomcta) as nombre_cuenta_grupo,
        (select id_proyecto from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as id_proyecto,(select descripcion from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as descripcion,null as debe,valor_cuota as haber,valor_cuota as haber_talves,null as id_factura,abono as porcentaje,null as bansel 
        FROM ctas_cobrar
        INNER JOIN cliente
        on cliente.id_cliente=ctas_cobrar.id_cliente
        LEFT JOIN grupo_cliente
        on grupo_cliente.id_grupo_cliente=cliente.id_grupo_cliente
        LEFT JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=grupo_cliente.id_plan_cuentas_anticipo
        where ctas_cobrar.id_ctascobrar={$id}");
        $select_pago = DB::select("SELECT plan_cuentas.id_plan_cuentas,CONCAT(codcta,'-',nomcta) as nombre_cuenta,fecha_pago,fecha_registro,valor_cuota as debe,valor_cuota as debe_tal,(select id_proyecto from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as id_proyecto,(select descripcion from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as descripcion,null as haber,ctas_cobrar.id_banco,null as id_factura,forma_pagos.id_forma_pagos,nombre_banco,forma_pagos.descripcion as nombre_pago,ctas_cobrar.numero_transaccion as nro_tarjeta,abono as porcentaje,bansel
        from ctas_cobrar 
        INNER JOIN 	forma_pagos
        on forma_pagos.id_forma_pagos=ctas_cobrar.id_forma_pagos
        LEFT JOIN plan_cuentas
        on plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas
        LEFT JOIN banco
        on banco.id_banco=ctas_cobrar.id_banco
        where id_ctascobrar={$id}");

        $codigo = DB::select("SELECT max(numero) as codigo FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.codigo like 'CC-%' and (asientos.estado='Activo' or asientos.estado is null) and proyecto.id_empresa=" . $request->id_empresa);
        $cod_asiento = "";
        if ($codigo) {
            $lenght = strlen($codigo[0]->codigo);
            $valor = $codigo[0]->codigo + 1;
            $cod_asiento = $codigo[0]->codigo + 1;
        } else {
            $cod_asiento = "1";
        }
        $cod_asiento_ant = "";
        if ($codigo) {
            $codigo_ant = DB::select("SELECT asientos.numero FROM `asientos`,proyecto where asientos.id_proyecto=proyecto.id_proyecto and asientos.id_asientos_comprobante=8 and (asientos.estado='Activo' or asientos.estado is null) and asientos.concepto like '%Anticipo%' and asientos.codigo_rol={$id} and proyecto.id_empresa=" . $request->id_empresa . " ORDER BY asientos.codigo desc limit 1");
            if ($codigo_ant) {
                $cod_asiento_ant = $codigo_ant[0]->numero;
            }
        }

        return [
            'ctas_cobrar' => $cta_cobrar[0],
            'cliente' => $select_cliente,
            'codigo' => $cod_asiento,
            'codigo_anterior' => $cod_asiento_ant,
            'forma_pago' => $select_pago,
        ];
    }
    public function agregarAsiento(Request $request)
    {
        if ($request->anticipo == 1) {
            Cuentaporcobrar::where('id_ctascobrar', $request->cod_rol)->update(['contabilidad' => '1']);
        } else {
            Ctas_cobrar_pagos::where('id_ctas_cobrar_pagos', $request->cod_rol)->update(['contabilidad' => '1']);
        }

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
        $asientos->id_asientos_comprobante = 8;

        $asientos->save();
        return $asientos->id_asientos;
    }
    public function generarPdf(Request $request)
    {
        setlocale(LC_TIME, "spanish");
        $empresa = DB::select("SELECT * from empresa where id_empresa=" . $request->id_empresa);
        $usuario = DB::select("SELECT * from user where id=" . $request->id_user);
        $cta_cobrar_pago = DB::select("SELECT * from ctas_cobrar_pagos where id_ctas_cobrar_pagos=" . $request->id_cta_cobrar_pago);
        $cta_cobrar_pago_datos = DB::select("SELECT ctas_cobrar_pagos.*,cliente.nombre as nombre,cliente.identificacion as identificacion from ctas_cobrar_pagos INNER JOIN cliente ON cliente.id_cliente=ctas_cobrar_pagos.id_cliente where id_ctas_cobrar_pagos=" . $request->id_cta_cobrar_pago);
        $proyecto = DB::select("SELECT * from proyecto where id_empresa=" . $request->id_empresa . " limit 1");
        $nro_facturas = 0;


        $ctas_id = [];
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
                    array_push($ctas_id, "cta.id_ctascobrar={$id_cb}\n");
                    $salto = $salto + 4;
                }
            }
        }

        $ctas_id = implode(" or ", $ctas_id);
        //dd($ctas_id);
        $query_cta_cobrar = "SELECT cta.*,fact.clave_acceso,cl.nombre,cl.codigo,cl.identificacion,form.descripcion
        from ctas_cobrar as cta
            LEFT JOIN factura as fact
            on fact.id_factura=cta.id_factura
            LEFT JOIN forma_pagos as form
            on form.id_forma_pagos=cta.id_forma_pagos
            INNER JOIN cliente as cl
            on cl.id_cliente=cta.id_cliente
                where cl.id_empresa={$request->id_empresa} and ({$ctas_id})";
        //dd($query_cta_cobrar);
        $fecha = "";
        $cta_cobrar = DB::select("SELECT cop.*,cl.nombre,cl.tipo_identificacion,cl.identificacion from ctas_cobrar_pagos as cop,cliente as cl where cop.id_cliente=cl.id_cliente and cop.id_ctas_cobrar_pagos={$request->id_cta_cobrar_pago}");
        $nro_facturas = 0;
        $nro_ctas = 0;
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
        $array_ntv = [];
        if (isset($cta_cobrar)) {
            for ($i = 0; $i < count($cta_cobrar); $i++) {
                $data = explode(";", $cta_cobrar[$i]->referencia);
                $registros = count($data) / 4;
                $salto = 0;
                for ($f = 0; $f < $registros; $f++) {
                    array_push($array_nro_factura, $data[0 + $salto]);
                    $id_cb = $data[1 + $salto];
                    if ($id_cb) {
                        array_push($array_id_ctas, "ctas_cobrar.id_ctascobrar={$id_cb}");
                    }
                    $valor = $data[2 + $salto];
                    $idf = $data[3 + $salto];
                    //array_push($idf);
                    if ($idf) {
                        $pos = strpos($idf, 'nv:');
                        if ($pos !== false) {
                            $number = substr($idf, 3);
                            array_push($array_ntv, "nota_venta.id_nota_venta={$number}");
                        } else {
                            array_push($array_cta, "factura.id_factura={$idf}");
                        }
                        //array_push($array_cta,"factura.id_factura={$idf}");
                    }

                    //array_push($array_id_factura,$cliente);
                    $salto = $salto + 4;
                }
            }
        }
        $array_ntv = implode(" or ", $array_ntv);
        $array_cta = implode(" or ", $array_cta);
        $array_id_ctas = implode(" or ", $array_id_ctas);
        $array_nro_factura = implode(",", $array_nro_factura);
        // $array_factura = substr($array_factura,0 ,-2);
        // $array_factura .= ")";
        $x_factura = "";
        $y_factura = "";
        $a = "";
        $b = "";
        $x_nota_venta = "";
        $y_nota_venta = "";
        $c = "";
        $d = "";
        if ($array_ntv) {
            $y_nota_venta = " and ";
            $c = "(";
            $d = ")";
        } else {
            $x_nota_venta = " and nota_venta.id_nota_venta=0 ";
        }
        if ($array_cta) {
            $y_factura = " and ";
            $a = "(";
            $b = ")";
        } else {
            $x_factura = " and factura.id_factura=0 ";
        }
        if ($array_cta || $array_ntv) {
            $array_factura =
                "SELECT if(cliente.id_plan_cuentas is null,'no','si') as exist_plan_cuenta_cliente,cliente.id_plan_cuentas as id_plan_cuentas_cliente,
                (select plan_cuentas.nomcta from plan_cuentas where id_plan_cuentas=if(cliente.id_plan_cuentas is null,0,cliente.id_plan_cuentas) and id_empresa={$request->id_empresa}) as nombre_cuenta_cliente,
                grupo_cliente.id_plan_cuentas as id_plan_cuentas_grupo,
                (select plan_cuentas.nomcta from plan_cuentas where id_plan_cuentas=if(grupo_cliente.id_plan_cuentas is null,0,grupo_cliente.id_plan_cuentas) and id_empresa={$request->id_empresa}) as nombre_cuenta_grupo,
                ctas_cobrar_pagos.valor_real_pago as haber_talves,0 as debe,proyecto.id_proyecto,proyecto.descripcion,factura.id_factura,sum(detalle.precio) as producto_valor,round(sum(detalle.total)/factura.subtotal_sin_impuesto,2) as porcentaje,0 as haber_cantidad,round(ctas_cobrar_pagos.valor_real_pago/{$nro_ctas}*(sum(detalle.total)/sum(factura.subtotal_sin_impuesto))*count(if(cliente.id_plan_cuentas is null,grupo_cliente.id_plan_cuentas,cliente.id_plan_cuentas)),2) as haber,plan_cuentas.bansel
                from cliente
                LEFT JOIN grupo_cliente
                on grupo_cliente.id_grupo_cliente=cliente.id_grupo_cliente
                LEFT JOIN plan_cuentas
                on plan_cuentas.id_plan_cuentas=grupo_cliente.id_plan_cuentas
                INNER JOIN ctas_cobrar_pagos
                on ctas_cobrar_pagos.id_cliente=cliente.id_cliente
                        INNER JOIN factura
                on factura.id_cliente=cliente.id_cliente
                        INNER JOIN detalle
                on detalle.id_factura=factura.id_factura
                        LEFT JOIN proyecto
                on proyecto.id_proyecto=detalle.id_proyecto
            where ctas_cobrar_pagos.id_ctas_cobrar_pagos={$request->id_cta_cobrar_pago} {$y_factura} {$a}{$array_cta}{$b} {$x_factura}
            GROUP BY detalle.id_proyecto
            UNION
            SELECT if(cliente.id_plan_cuentas is null,'no','si') as exist_plan_cuenta_cliente,cliente.id_plan_cuentas as id_plan_cuentas_cliente,
                (select plan_cuentas.nomcta from plan_cuentas where id_plan_cuentas=if(cliente.id_plan_cuentas is null,0,cliente.id_plan_cuentas) and id_empresa={$request->id_empresa}) as nombre_cuenta_cliente,
                grupo_cliente.id_plan_cuentas as id_plan_cuentas_grupo,
                (select plan_cuentas.nomcta from plan_cuentas where id_plan_cuentas=if(grupo_cliente.id_plan_cuentas is null,0,grupo_cliente.id_plan_cuentas) and id_empresa={$request->id_empresa}) as nombre_cuenta_grupo,
                ctas_cobrar_pagos.valor_real_pago as haber_talves,0 as debe,proyecto.id_proyecto,proyecto.descripcion,nota_venta.id_nota_venta,sum(detalle_nota_venta.precio) as producto_valor,round(sum(detalle_nota_venta.total)/nota_venta.subtotal_sin_impuesto,2) as porcentaje,0 as haber_cantidad,round(ctas_cobrar_pagos.valor_real_pago/{$nro_ctas}*(sum(detalle_nota_venta.total)/sum(nota_venta.subtotal_sin_impuesto))*count(if(cliente.id_plan_cuentas is null,grupo_cliente.id_plan_cuentas,cliente.id_plan_cuentas)),2) as haber,plan_cuentas.bansel
                from cliente
                LEFT JOIN grupo_cliente
                on grupo_cliente.id_grupo_cliente=cliente.id_grupo_cliente
                LEFT JOIN plan_cuentas
                on plan_cuentas.id_plan_cuentas=grupo_cliente.id_plan_cuentas
                INNER JOIN ctas_cobrar_pagos
                on ctas_cobrar_pagos.id_cliente=cliente.id_cliente
                        INNER JOIN nota_venta
                on nota_venta.id_cliente=cliente.id_cliente
                        INNER JOIN detalle_nota_venta
                on detalle_nota_venta.id_nota_venta=nota_venta.id_nota_venta
                        LEFT JOIN proyecto
                on proyecto.id_proyecto=detalle_nota_venta.id_proyecto
                where ctas_cobrar_pagos.id_ctas_cobrar_pagos={$request->id_cta_cobrar_pago} {$y_nota_venta} {$c}{$array_ntv}{$d} {$x_nota_venta}
            GROUP BY detalle_nota_venta.id_proyecto";
            $query_forma_pago = "SELECT plan_cuentas.id_plan_cuentas,plan_cuentas.nomcta as nombre_cuenta,ctas_cobrar_pagos.fecha_pago,forma_pagos.id_forma_pagos,ctas_cobrar_pagos.nro_tarjeta,banco.id_banco,banco.nombre_banco,forma_pagos.descripcion as nombre_pago,ctas_cobrar_pagos.valor_real_pago as debe_tal,0 haber,proyecto.id_proyecto,
            proyecto.descripcion,factura.id_factura,round(sum(detalle.total)/factura.subtotal_sin_impuesto,2) as porcentaje,0 as debe_cantidad,round(ctas_cobrar_pagos.valor_real_pago/{$nro_ctas}*(sum(detalle.total)/sum(factura.subtotal_sin_impuesto))*count(plan_cuentas.id_plan_cuentas),2) as debe,plan_cuentas.bansel,ctas_cobrar_pagos.pagos_por
			from forma_pagos
            LEFT JOIN plan_cuentas
            on plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas
            INNER JOIN ctas_cobrar_pagos
            on ctas_cobrar_pagos.id_forma_pagos=forma_pagos.id_forma_pagos
            LEFT JOIN banco
            on banco.id_banco=ctas_cobrar_pagos.id_banco
            INNER JOIN factura
            on factura.id_cliente=ctas_cobrar_pagos.id_cliente
            INNER JOIN detalle
            on detalle.id_factura=factura.id_factura
            LEFT JOIN proyecto
            on proyecto.id_proyecto=detalle.id_proyecto
            where ctas_cobrar_pagos.id_ctas_cobrar_pagos={$request->id_cta_cobrar_pago} {$y_factura} {$a}{$array_cta}{$b} {$x_factura}
            GROUP BY detalle.id_proyecto
            UNION
            SELECT plan_cuentas.id_plan_cuentas,plan_cuentas.nomcta as nombre_cuenta,ctas_cobrar_pagos.fecha_pago,forma_pagos.id_forma_pagos,ctas_cobrar_pagos.nro_tarjeta,banco.id_banco,banco.nombre_banco,forma_pagos.descripcion as nombre_pago,ctas_cobrar_pagos.valor_real_pago as debe_tal,0 haber,proyecto.id_proyecto,
            proyecto.descripcion,nota_venta.id_nota_venta,round(sum(detalle_nota_venta.total)/nota_venta.subtotal_sin_impuesto,2) as porcentaje,0 as debe_cantidad,round(ctas_cobrar_pagos.valor_real_pago/{$nro_ctas}*(sum(detalle_nota_venta.total)/sum(nota_venta.subtotal_sin_impuesto))*count(plan_cuentas.id_plan_cuentas),2) as debe,plan_cuentas.bansel,ctas_cobrar_pagos.pagos_por
			from forma_pagos
            LEFT JOIN plan_cuentas
            on plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas
            INNER JOIN ctas_cobrar_pagos
            on ctas_cobrar_pagos.id_forma_pagos=forma_pagos.id_forma_pagos
            LEFT JOIN banco
            on banco.id_banco=ctas_cobrar_pagos.id_banco
            INNER JOIN nota_venta
            on nota_venta.id_cliente=ctas_cobrar_pagos.id_cliente
            INNER JOIN detalle_nota_venta
            on detalle_nota_venta.id_nota_venta=nota_venta.id_nota_venta
            LEFT JOIN proyecto
            on proyecto.id_proyecto=detalle_nota_venta.id_proyecto
            where ctas_cobrar_pagos.id_ctas_cobrar_pagos={$request->id_cta_cobrar_pago} {$y_nota_venta} {$c}{$array_ntv}{$d} {$x_nota_venta}
            GROUP BY detalle_nota_venta.id_proyecto";
        } else {

            $array_factura = "SELECT if(cliente.id_plan_cuentas is null,'no','si') as exist_plan_cuenta_cliente,cliente.id_plan_cuentas as id_plan_cuentas_cliente,
            (select plan_cuentas.nomcta from plan_cuentas where id_plan_cuentas=if(cliente.id_plan_cuentas is null,0,cliente.id_plan_cuentas) and id_empresa=1) as nombre_cuenta_cliente,grupo_cliente.id_plan_cuentas as id_plan_cuentas_grupo,
            (select plan_cuentas.nomcta from plan_cuentas where id_plan_cuentas=if(grupo_cliente.id_plan_cuentas is null,0,grupo_cliente.id_plan_cuentas) and id_empresa=1) as nombre_cuenta_grupo,ctas_cobrar_pagos.valor_real_pago as haber_talves,0 as debe,proyecto.id_proyecto,
            proyecto.descripcion,factura.id_factura,round(detalle.total/factura.subtotal_sin_impuesto,2) as porcentaje,0 as haber_cantidad,round(ctas_cobrar_pagos.valor_real_pago/{$nro_ctas}*(detalle.total/factura.subtotal_sin_impuesto),2) as haber,plan_cuentas.bansel,ctas_cobrar_pagos.id_forma_pagos
            from cliente
            LEFT JOIN grupo_cliente
            on grupo_cliente.id_grupo_cliente=cliente.id_grupo_cliente
            LEFT JOIN plan_cuentas
            on plan_cuentas.id_plan_cuentas=grupo_cliente.id_plan_cuentas
            INNER JOIN ctas_cobrar_pagos
            on ctas_cobrar_pagos.id_cliente=cliente.id_cliente
                    INNER JOIN factura
            on factura.id_cliente=cliente.id_cliente
                    INNER JOIN detalle
            on detalle.id_factura=factura.id_factura
                    LEFT JOIN proyecto
            on proyecto.id_proyecto=detalle.id_proyecto
            where ctas_cobrar_pagos.id_ctas_cobrar_pagos={$request->id_cta_cobrar_pago} and factura.id_factura=0
            GROUP BY detalle.id_proyecto";
            $query_forma_pago = "SELECT plan_cuentas.id_plan_cuentas,plan_cuentas.nomcta as nombre_cuenta,ctas_cobrar_pagos.fecha_pago,forma_pagos.id_forma_pagos,ctas_cobrar_pagos.nro_tarjeta,banco.id_banco,banco.nombre_banco,forma_pagos.descripcion as nombre_pago,ctas_cobrar_pagos.valor_real_pago as debe_tal,0 haber,proyecto.id_proyecto,
            proyecto.descripcion,factura.id_factura,round(sum(detalle.total)/factura.subtotal_sin_impuesto,2) as porcentaje,0 as debe_cantidad,round(ctas_cobrar_pagos.valor_real_pago/{$nro_ctas}*(sum(detalle.total)/factura.subtotal_sin_impuesto),2) as debe,plan_cuentas.bansel
			from forma_pagos
            LEFT JOIN plan_cuentas
            on plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas
            INNER JOIN ctas_cobrar_pagos
            on ctas_cobrar_pagos.id_forma_pagos=forma_pagos.id_forma_pagos
            LEFT JOIN banco
            on banco.id_banco=ctas_cobrar_pagos.id_banco
            INNER JOIN factura
            on factura.id_cliente=ctas_cobrar_pagos.id_cliente
            INNER JOIN detalle
            on detalle.id_factura=factura.id_factura
            LEFT JOIN proyecto
            on proyecto.id_proyecto=detalle.id_proyecto
            where ctas_cobrar_pagos.id_ctas_cobrar_pagos={$request->id_cta_cobrar_pago} and factura.id_factura=0
            GROUP BY detalle.id_proyecto";
        }
        $plan_cuenta = DB::select("SELECT if(cliente.id_plan_cuentas is null,'no','si') as exist_plan_cuenta_cliente from ctas_cobrar_pagos
            INNER JOIN cliente
            on cliente.id_cliente=ctas_cobrar_pagos.id_cliente
            where ctas_cobrar_pagos.id_ctas_cobrar_pagos={$request->id_cta_cobrar_pago}");
        $id_plan_cta = "";
        if ($plan_cuenta) {
            if ($plan_cuenta[0]->exist_plan_cuenta_cliente == "si") {
                $id_plan_cta = "GROUP BY cliente.id_plan_cuentas";
            } else {
                $id_plan_cta = "GROUP BY grupo_cliente.id_plan_cuentas";
            }
        }
        $total_referencia_cli = "";
        $total_referencia_ = "";
        if ($array_id_ctas) {

            $array_referencia = "SELECT if(cliente.id_plan_cuentas is null,'no','si') as exist_plan_cuenta_cliente,cliente.id_plan_cuentas as id_plan_cuentas_cliente,
            (select plan_cuentas.nomcta from plan_cuentas where id_plan_cuentas=if(cliente.id_plan_cuentas is null,0,cliente.id_plan_cuentas) and id_empresa={$request->id_empresa}) as nombre_cuenta_cliente,grupo_cliente.id_plan_cuentas as id_plan_cuentas_grupo,
            (select plan_cuentas.nomcta from plan_cuentas where id_plan_cuentas=if(grupo_cliente.id_plan_cuentas is null,0,grupo_cliente.id_plan_cuentas) and id_empresa={$request->id_empresa}) as nombre_cuenta_grupo,ctas_cobrar_pagos.valor_real_pago as haber_talves,0 as debe,
            (select id_proyecto from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as id_proyecto,
            (select descripcion from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as descripcion,
            null as id_factura,null as producto_valor,ctas_cobrar_pagos.valor_real_pago/{$nro_ctas} as porcentaje,round(ctas_cobrar_pagos.valor_real_pago/{$nro_ctas},2) as haber_cantidad,sum(round(ctas_cobrar_pagos.valor_real_pago/{$nro_ctas},2))as haber,plan_cuentas.bansel
                                    from cliente
            LEFT JOIN grupo_cliente
            on grupo_cliente.id_grupo_cliente=cliente.id_grupo_cliente
            LEFT JOIN plan_cuentas
            on plan_cuentas.id_plan_cuentas=grupo_cliente.id_plan_cuentas
            INNER JOIN ctas_cobrar_pagos
            on ctas_cobrar_pagos.id_cliente=cliente.id_cliente
            INNER JOIN ctas_cobrar
            on ctas_cobrar.id_cliente=cliente.id_cliente
            where ctas_cobrar_pagos.id_ctas_cobrar_pagos={$request->id_cta_cobrar_pago} and ctas_cobrar.referencias is not null and ({$array_id_ctas})
            {$id_plan_cta}";
            $query_form_pago_ref = "SELECT plan_cuentas.id_plan_cuentas,plan_cuentas.nomcta as nombre_cuenta,ctas_cobrar_pagos.fecha_pago,forma_pagos.id_forma_pagos,ctas_cobrar_pagos.nro_tarjeta,banco.id_banco,banco.nombre_banco,forma_pagos.descripcion as nombre_pago,ctas_cobrar_pagos.valor_real_pago as debe_tal,null haber,
            (select id_proyecto from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as id_proyecto,
            (select descripcion from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as descripcion,
                                    0 as id_factura,ctas_cobrar_pagos.valor_real_pago/{$nro_ctas} as porcentaje,round(ctas_cobrar_pagos.valor_real_pago/{$nro_ctas},2) as debe_cantidad,sum(round(ctas_cobrar_pagos.valor_real_pago/{$nro_ctas},2))as debe,plan_cuentas.bansel,ctas_cobrar_pagos.pagos_por
                                    from forma_pagos
                        LEFT JOIN plan_cuentas
                        on plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas
                        INNER JOIN ctas_cobrar_pagos
                        on ctas_cobrar_pagos.id_forma_pagos=forma_pagos.id_forma_pagos
                        LEFT JOIN banco
                        on banco.id_banco=ctas_cobrar_pagos.id_banco
                        INNER JOIN ctas_cobrar
                        on ctas_cobrar.id_cliente=ctas_cobrar_pagos.id_cliente
                        where ctas_cobrar_pagos.id_ctas_cobrar_pagos={$request->id_cta_cobrar_pago} and ctas_cobrar.referencias is not null and ({$array_id_ctas})
                        GROUP BY plan_cuentas.id_plan_cuentas";
        } else {
            $array_referencia = "SELECT if(cliente.id_plan_cuentas is null,'no','si') as exist_plan_cuenta_cliente,cliente.id_plan_cuentas as id_plan_cuentas_cliente,
            (select plan_cuentas.nomcta from plan_cuentas where id_plan_cuentas=if(cliente.id_plan_cuentas is null,0,cliente.id_plan_cuentas) and id_empresa={$request->id_empresa}) as nombre_cuenta_cliente,grupo_cliente.id_plan_cuentas as id_plan_cuentas_grupo,
            (select plan_cuentas.nomcta from plan_cuentas where id_plan_cuentas=if(grupo_cliente.id_plan_cuentas is null,0,grupo_cliente.id_plan_cuentas) and id_empresa={$request->id_empresa}) as nombre_cuenta_grupo,ctas_cobrar_pagos.valor_real_pago as haber_talves,null as debe,
            (select id_proyecto from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as id_proyecto,
            (select descripcion from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as descripcion,
            null as id_factura,ctas_cobrar_pagos.valor_real_pago/1 as porcentaje,0 as haber_cantidad,round(ctas_cobrar_pagos.valor_real_pago/1,2) as haber,plan_cuentas.bansel
                                    from cliente
            LEFT JOIN grupo_cliente
            on grupo_cliente.id_grupo_cliente=cliente.id_grupo_cliente
            LEFT JOIN plan_cuentas
            on plan_cuentas.id_plan_cuentas=grupo_cliente.id_plan_cuentas
            INNER JOIN ctas_cobrar_pagos
            on ctas_cobrar_pagos.id_cliente=cliente.id_cliente
            INNER JOIN ctas_cobrar
            on ctas_cobrar.id_cliente=cliente.id_cliente
            where ctas_cobrar_pagos.id_ctas_cobrar_pagos={$request->id_cta_cobrar_pago} and ctas_cobrar.referencias is not null and ctas_cobrar.id_ctascobrar=0";
            $query_form_pago_ref = "SELECT plan_cuentas.id_plan_cuentas,plan_cuentas.nomcta as nombre_cuenta,ctas_cobrar_pagos.fecha_pago,forma_pagos.id_forma_pagos,ctas_cobrar_pagos.nro_tarjeta,banco.id_banco,banco.nombre_banco,forma_pagos.descripcion as nombre_pago,ctas_cobrar_pagos.valor_real_pago as debe_tal,null haber,
            (select id_proyecto from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as id_proyecto,
            (select descripcion from proyecto where id_proyecto={$proyecto[0]->id_proyecto}) as descripcion,
                                    0 as id_factura,ctas_cobrar_pagos.valor_real_pago/{$nro_ctas} as porcentaje,0 as debe_cantidad,round(ctas_cobrar_pagos.valor_real_pago/{$nro_ctas},2) as debe,plan_cuentas.bansel,ctas_cobrar_pagos.pagos_por
                                    from forma_pagos
                        LEFT JOIN plan_cuentas
                        on plan_cuentas.id_plan_cuentas=forma_pagos.id_plan_cuentas
                        INNER JOIN ctas_cobrar_pagos
                        on ctas_cobrar_pagos.id_forma_pagos=forma_pagos.id_forma_pagos
                        LEFT JOIN banco
                        on banco.id_banco=ctas_cobrar_pagos.id_banco
                        INNER JOIN ctas_cobrar
                        on ctas_cobrar.id_cliente=ctas_cobrar_pagos.id_cliente
                        where ctas_cobrar_pagos.id_ctas_cobrar_pagos={$request->id_cta_cobrar_pago} and ctas_cobrar.referencias is not null and ctas_cobrar.id_ctascobrar=0";
        }
        //dd($query_forma_pago);
        $reporte_cta_fact = DB::select($array_factura);
        $reporte_cta_cobrar = DB::select($query_cta_cobrar);
        $reporte_cta_pago_fact = DB::select($query_forma_pago);
        $reporte_cta_ref = DB::select($array_referencia);
        $referencia_cliente = [];
        $referencia_id_cl = [];
        //$result = (array) $reporte_cta_ref;

        //dd($reporte_cta_ref);
        //$otro=array_unique($reporte_cta_ref);
        //dd($query_form_pago_ref);
        $reporte_cta_pago_ref = DB::select($query_form_pago_ref);
        $referencia_pago = [];
        $referencia_id_pag = [];
        // foreach($reporte_cta_pago_ref as $data){
        //         if(!in_array($data['id_plan_cuentas'],$referencia_id_pag)){
        //             $referencia_pago= $data;
        //             $referencia_id_pag[] = $data['id_plan_cuentas_cliente'];
        //         }
        //         else{
        //             $referencia_pago['debe'] += $data['debe'];
        //         }

        // }
        $reporte_cta_cliente = array_merge($reporte_cta_fact, $reporte_cta_ref);
        //dd($reporte_cta_cliente);

        $reporte_cta_pago = array_merge($reporte_cta_pago_fact, $reporte_cta_pago_ref);
        if ($reporte_cta_cobrar) {
            $fecha = $array_nro_factura;
        }
        $datos_filtrado = [];
        $datos_filtrado2 = [];
        $valor = 0;

        //dd($reporte_cta_pago);

        //dd($reporte_cta_pago);
        if (!$reporte_cta_pago && !$reporte_cta_cliente) {
            return response('no-data-report', 200)->header('Content-Type', 'application/json');
        } else {
            if ($request->destinatario !== null && $request->email !== null) {
            } else {
                $Reportes = new generarReportes();
                $strPDF = $Reportes->PDFCtaCobrar($reporte_cta_cobrar, $cta_cobrar_pago_datos, $empresa[0], $usuario[0], $request->index, $fecha, $reporte_cta_cliente, $reporte_cta_pago);
                return response($strPDF, 200)->header('Content-Type', 'application/pdf');
            }
        }
    }
    public function agregarAsientoDetalle(Request $request)
    {
        foreach ($request->productos as $haber) {
            $asiento = new Asientos_contables_detalle();
            if ($haber["haber"] > 0) {
                if ($haber["exist_plan_cuenta_cliente"] == "si") {
                    $asiento->proyecto = $haber["descripcion"];
                    $asiento->haber = $haber["haber"];
                    if ($haber["bansel"] !== null) {
                        $asiento->no_documento = $request->nro_documento;
                        $asiento->fecha_de_pago = $request->fecha_pago;
                        $asiento->id_forma_pagos = $request->id_forma_pago;
                    }
                    $asiento->ucrea = $request->ucrea;
                    $asiento->id_plan_cuentas = $haber["id_plan_cuentas_cliente"];
                    $asiento->id_asientos = $request->id_asientos;
                    $asiento->id_proyecto = $haber["id_proyecto"];
                    $asiento->save();
                } else {
                    $asiento->proyecto = $haber["descripcion"];
                    $asiento->haber = $haber["haber"];
                    if ($haber["bansel"] !== null) {
                        $asiento->no_documento = $request->nro_documento;
                        $asiento->fecha_de_pago = $request->fecha_pago;
                        $asiento->id_forma_pagos = $request->id_forma_pago;
                    }
                    $asiento->ucrea = $request->ucrea;
                    $asiento->id_plan_cuentas = $haber["id_plan_cuentas_grupo"];
                    $asiento->id_asientos = $request->id_asientos;
                    $asiento->id_proyecto = $haber["id_proyecto"];
                    $asiento->save();
                }
            }
        }
        foreach ($request->iva_12 as $debe) {
            $asiento = new Asientos_contables_detalle();
            if ($debe["debe"] > 0) {
                $asiento->proyecto = $debe["descripcion"];
                $asiento->debe = $debe["debe"];
                if ($debe["bansel"] !== null) {
                    $asiento->no_documento = $debe["nro_tarjeta"];
                    if ($debe["fecha_registro"] !== null) {
                        $asiento->fecha_de_pago = $debe["fecha_registro"];
                    } else {
                        $asiento->fecha_de_pago = $debe["fecha_pago"];
                    }
                    $asiento->id_forma_pagos = $debe["id_forma_pagos"];
                }
                $asiento->ucrea = $request->ucrea;
                $asiento->id_plan_cuentas = $debe["id_plan_cuentas"];
                $asiento->id_asientos = $request->id_asientos;
                $asiento->id_proyecto = $debe["id_proyecto"];
                $asiento->save();
            }
        }
    }
    public function eliminarcxc($id)
    {
        //selecciona los pagos de esa id
        $select = DB::select("SELECT * FROM ctas_cobrar_pagos WHERE id_ctas_cobrar_pagos = $id");
        if (isset($select)) {
            //recorre los registros
            for ($i = 0; $i < count($select); $i++) {
                //convierte en array las referencias y los divide para 4
                $data = explode(";", $select[$i]->referencia);
                $registros = count($data) / 4;
                $salto = 0;
                for ($f = 0; $f < $registros; $f++) {
                    $id_cb = $data[1 + $salto];
                    $valor = $data[2 + $salto];
                    $idf = $data[3 + $salto];
                    if ($id_cb && $valor) {
                        //actualiza los valores de cuentas por cobrar el valor pagado
                        DB::update("UPDATE ctas_cobrar SET valor_pagado = valor_pagado - $valor WHERE id_ctascobrar = $id_cb");
                    }
                    //hace el salto de los 4
                    $salto = $salto + 4;
                }
            }
        }
        //borra los registros de los pagos despues de revertir lospagos hechos de este pago
        DB::delete("DELETE FROM ctas_cobrar_pagos WHERE id_ctas_cobrar_pagos = $id");
    }
    public function importar(Request $request)
    {
        //importa los registros
        $file = $request->file('file');
        //llama a el módulo de import de CuentasPorPagarImport
        Excel::import(new CuentasPorCobrarImport, $file);
    }

    function eliminarabonos($id)
    {
        //elimina un registro de cuantas de abono por cobrar mediante su id y lo borra
        Cuentaporcobrar::where("id_ctascobrar", "=", $id)->delete();
    }

    function guardarabonos(Request $rq)
    {
        //actualiza los abonos mediante los valores recibidos
        $cpc = Cuentaporcobrar::find($rq->id);
        $cpc->fecha_registro = $rq->fecha;
        $cpc->valor_cuota = $rq->monto;
        $cpc->abono = $rq->monto;
        $cpc->numero_transaccion = $rq->nrocheque;
        $cpc->id_forma_pagos = $rq->formapago;
        $cpc->id_cliente = $rq->id_cliente;
        $cpc->updated_by = session()->get('usuariosesion')['id'];
        $cpc->save();
    }
    // finciones listar anticipos
    public function listar_anticipos($id){
        $recupera=DB::select("SELECT ctas_cobrar.*,cliente.nombre,if(ctas_cobrar.fecha_registro is null,ctas_cobrar.fecha_pago,ctas_cobrar.fecha_registro) as fecha_emision from ctas_cobrar INNER JOIN cliente ON cliente.id_cliente=ctas_cobrar.id_cliente where ctas_cobrar.id_cliente=$id and ctas_cobrar.tipo=3 and ctas_cobrar.abono>0");
        return $recupera;
    }
}
