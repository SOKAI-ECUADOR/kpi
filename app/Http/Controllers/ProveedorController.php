<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proveedor;
use App\Models\Provincia;
use App\Models\Ciudad;
use App\Models\Plancuenta;
use App\Models\Moneda;
use App\Models\Grupo;
use App\Models\Banco;
use App\Models\GrupoProveedor;
use App\Models\Impuesto;
use App\Models\Tipocomprobante;

include 'class/generarReportes.php';

use generarReportes;

class ProveedorController extends Controller
{
    //
    public function index(Request $request, $id)
    {
        $buscar = $request->buscar;
        if ($buscar == '' || $buscar == null) {
            // $recupera = Proveedor::select("*")
            //             ->where("id_empresa", "=", $id)
            //             ->orderByRaw('id_proveedor DESC')
            //             ->get(); 
            $recupera = DB::select("SELECT * from proveedor where id_empresa={$id} ORDER BY id_proveedor DESC");
        } else {
            // $recupera = Proveedor::select("*")
            //             ->where(function($q) use ($buscar){
            //                 $q->where('cod_proveedor','like','%'.$buscar.'%')
            //                 ->orWhere('nombre_proveedor','like','%'.$buscar.'%');
            //             })
            //             ->where("id_empresa", "=", $id)
            //             ->orderByRaw('id_proveedor DESC')
            //             ->get();
            $recupera = DB::select("SELECT * from proveedor where id_empresa={$id} and (cod_proveedor like '%{$buscar}%' or nombre_proveedor like'%{$buscar}%' or identif_proveedor like'%{$buscar}%' or nombre_adicional like '%{$buscar}%') ORDER BY id_proveedor DESC");
        }
        return [
            'recupera' => $recupera
        ];
    }
    public function verificarproveedor($id)
    {
        $cod = DB::select("SELECT cod_proveedor FROM proveedor  WHERE id_empresa = $id ORDER BY  id_proveedor DESC LIMIT 1;");
        $principal = "";
        if (count($cod) >= 1) {
            $tot = preg_replace('/[^0-9]/', '', $cod[0]->cod_proveedor)  + 1;
            $principal = $tot;
        } else {
            $principal = 1;
        }
        return $principal;
    }
    public function busqueda(Request $request)
    {
        /*$buscar = $request->ctacontable;
        $busqueda=Plancuenta::select('codcta','nomcta')->where('codcta','like','%'.$buscar.'%')
        ->orWhere('nomcta','like','%'.$buscar.'%');

        return ['busqueda'=> $busqueda];*/
        $buscar = $request->ctacontable;
        $select = DB::select('SELECT codcta,nomcta FROM `plan_cuentas` WHERE codcta = ' . "'$buscar'");
        if ($select) {
            return "existe";
        }
    }
    public function ProveedorFactura(Request $request)
    {
        //dd($request->id_empresa);
        //$factura = FacturaCompra::select("*")->where("descripcion", "like", '%'.$request->factura.'%')->where("id_empresa","=", $request->id_empresa)->get();
        // $factura=DB::select("SELECT * from factura_compra where descripcion like '%{$request->factura}%' and id_empresa=$request->id_empresa");
        // if(count($factura)>0){

        //     $detalle = DB::select("SELECT dfc.*, p.cod_principal FROM detalle_factura_compra dfc INNER JOIN producto p ON p.id_producto=dfc.id_producto WHERE id_factura = " . $factura[0]->id_factcompra);
        //     //$proveedor = Proveedor::select("*","concat()")->where("id_proveedor", "=", $factura[0]->id_proveedor)->get();
        //     $proveedor =DB::select("SELECT *,concat('Factura ',SUBSTR('{$factura[0]->descripcion}',1,3),'-',SUBSTR('{$factura[0]->descripcion}',4,3),'-',SUBSTR('{$factura[0]->descripcion}',7,9),' Proveedor: ',nombre_proveedor) as prov_nombre from proveedor where id_proveedor={$factura[0]->id_proveedor}");
        //     $empresa = DB::select("SELECT * FROM empresa WHERE id_empresa = " . $request->id_empresa);
        //     return [
        //         'factura' => $factura[0], 
        //         'detalle' => $detalle,
        //         'proveedor' => $proveedor,
        //         'empresa' => $empresa[0]
        //     ];
        // }else{
        //     return 'error';
        // }
        $proveedor = DB::select("SELECT factura_compra.*,nombre_proveedor,concat('Factura ',SUBSTR(factura_compra.descripcion,1,3),'-',SUBSTR(factura_compra.descripcion,4,3),'-',SUBSTR(factura_compra.descripcion,7,9),' Proveedor: ',nombre_proveedor) as prov_nombre 
        from proveedor 
        INNER JOIN factura_compra
        ON factura_compra.id_proveedor=proveedor.id_proveedor
        where proveedor.id_empresa={$request->id_empresa} and (factura_compra.descripcion like '%{$request->factura}%' or proveedor.nombre_proveedor like '%{$request->factura}%')");
        if ($proveedor) {
            return [
                'proveedor' => $proveedor
            ];
        } else {
            return 'error';
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
        /*$sel = DB::select("SELECT cod_proveedor FROM proveedor ORDER BY id_proveedor DESC LIMIT 1");
        $dato = $sel[0]->cod_proveedor;
        $principal ="";
        if($dato>=1){
            if(($dato+1) >= 100){
                $tot = $dato + 1; 
                $principal = "PR010".$tot;
            }else if(($dato+1) >= 10){
                $tot = $dato + 1;
                $principal = "PR0100".$tot;
            }else{
                $tot = $dato + 1; 
                $principal = "PR01000".$tot;
            }
        }else{
            $principal = "PR010";
        }*/
        $codigo_prov = 0;

        $cod_proveedor = DB::select("SELECT * FROM proveedor WHERE id_empresa = " . $request->id_empresa . " ORDER BY id_proveedor DESC limit 1");
        if ($cod_proveedor) {
            $codigo_prov = $request->cod_proveedor;
        } else {
            $codigo_prov = $request->cod_proveedor . "-001";
        }
        $select = DB::select('SELECT codcta from plan_cuentas where codcta=' . "'$request->cta_contable'");
        $identificacion = DB::select("SELECT identif_proveedor from proveedor where id_empresa={$request->id_empresa} and identif_proveedor='{$request->identif_proveedor}'");
        $proveedor = new Proveedor();

        $proveedor->cod_proveedor = $codigo_prov;
        $proveedor->nombre_proveedor = $request->nombre_proveedor;
        $proveedor->nombre_adicional = $request->nombre_adicional;
        $proveedor->tipo_identificacion = $request->tipo_identificacion;
        $proveedor->identif_proveedor = $request->identif_proveedor;
        //$proveedor->tipo_proveedor=$request->tipo_proveedor; 
        $proveedor->contribuyente = $request->contribuyente;
        $proveedor->beneficiario = $request->beneficiario;
        //$proveedor->identif_benefic=$request->identif_benefic; 
        $proveedor->contacto = $request->contacto;
        $emails = implode(";", $request->emails);
        $proveedor->email = $emails;
        $proveedor->direccion_prov = $request->direccion_prov;
        $proveedor->nrcasa = $request->nrcasa;
        $proveedor->telefono_prov = $request->telefono_prov;
        $proveedor->estado_prov = 1;
        $proveedor->tipo_cuenta = $request->tipo_cuenta;
        $proveedor->cta_banco = $request->cta_banco;
        $proveedor->cash_manager = $request->id;
        //$proveedor->nrcta_interbancaria=$request->nrcta_interbancaria; 
        $proveedor->pagos = $request->pagos;
        $proveedor->plazo = $request->plazo;
        $proveedor->dias_pago = $request->dias_pago;
        $proveedor->tip_comprob = $request->tip_comprob;
        $proveedor->serie = $request->serie;
        $proveedor->fvalidez = $request->fvalidez;
        $proveedor->comentario = $request->comentario;
        $proveedor->rangomax = $request->rangomax;
        $proveedor->rangomin = $request->rangomin;
        $proveedor->contribuye_sri = $request->contribuye_sri;
        $proveedor->tip_electronico = $request->tip_electronico;
        if ($request->imp_retencion != "I.R.F. Por Pagar (8%) Arriendos" && $request->codsri_imp != null) {
            $proveedor->imp_retencion = $request->imp_retencion;
        }

        $proveedor->codsri_imp = $request->codsri_imp;
        if ($request->retencion_iva != "I.V.A. Retenido por Pagar (70%)" && $request->codsri_iva != null) {
            $proveedor->imp_retencion = $request->imp_retencion;
        }

        $proveedor->codsri_iva = $request->codsri_iva;
        $proveedor->tipo_contribuyente = $request->tipo_contribuyente;
        $proveedor->id_provincia = $request->id_provincia;
        $proveedor->id_ciudad = $request->id_ciudad;
        $proveedor->id_banco = $request->id_banco;
        $proveedor->id_empresa = $request->id_empresa;
        $proveedor->id_grupo_proveedor = $request->grupo;
        $proveedor->id_plan_cuentas = $request->id_contable;

        //return $proveedor;
        // if($request->cta_contable==null){
        //     $proveedor->save();
        //     return "vacio";
        // }else{
        if (count($identificacion) > 0) {
            return "mal";
        } else {
            if ($request->factura_compra !== null) {
                $proveedor->save();
                $id = $proveedor->id_proveedor;
                return $id;
            } else {
                $proveedor->save();
                return "bien";
            }
        }
        //}

        $id = $proveedor->id_proveedor;
        return $id;
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
        /*
        $sel = DB::select("SELECT cod_proveedor FROM proveedor ORDER BY id_proveedor DESC LIMIT 1");
        $dato = $sel[0]->cod_proveedor;
        
        $principal ="";
        if($dato>=1){
            if(($dato+1) >= 100){
                $tot = $dato + 1; 
                $principal = "PR010".$tot;
            }else if(($dato+1) >= 10){
                $tot = $dato + 1;
                $principal = "PR0100".$tot;
            }else{
                $tot = $dato + 1; 
                $principal = "PR01000".$tot;
            }
        }else{
            $principal = "PR010";
        }*/
        /* $select = DB::select('SELECT * FROM `plan_cuentas` WHERE codcta = '."'$request->cta_contable'");
        if(!$select){
            return "existe";
        }*/
        $select = DB::select('SELECT codcta from plan_cuentas where codcta=' . "'$request->cta_contable'");
        $identificacion_id = DB::select("SELECT identif_proveedor from proveedor where id_proveedor={$request->id_proveedor}");
        $identificacion = DB::select("SELECT identif_proveedor from proveedor where id_empresa={$request->id_empresa} and identif_proveedor='{$request->identif_proveedor}'");
        $proveedor = Proveedor::findOrFail($request->id_proveedor);
        $proveedor->cod_proveedor = $request->cod_proveedor;
        $proveedor->nombre_proveedor = $request->nombre_proveedor;
        $proveedor->nombre_adicional = $request->nombre_adicional;
        $proveedor->tipo_identificacion = $request->tipo_identificacion;
        $proveedor->identif_proveedor = $request->identif_proveedor;
        //$proveedor->tipo_proveedor=$request->tipo_proveedor; 
        $proveedor->contribuyente = $request->contribuyente;
        $proveedor->beneficiario = $request->beneficiario;
        //$proveedor->identif_benefic=$request->identif_benefic; 
        $proveedor->contacto = $request->contacto;
        $emails = implode(";", $request->emails);
        $proveedor->email = $emails;
        $proveedor->direccion_prov = $request->direccion_prov;
        $proveedor->nrcasa = $request->nrcasa;
        $proveedor->telefono_prov = $request->telefono_prov;
        $proveedor->estado_prov = $request->estado_prov;
        $proveedor->tipo_cuenta = $request->tipo_cuenta;
        $proveedor->cta_banco = $request->cta_banco;
        $proveedor->cash_manager = $request->id;
        //$proveedor->nrcta_interbancaria=$request->nrcta_interbancaria; 
        $proveedor->pagos = $request->pagos;
        $proveedor->plazo = $request->plazo;
        $proveedor->dias_pago = $request->dias_pago;
        $proveedor->tip_comprob = $request->tip_comprob;
        $proveedor->serie = $request->serie;
        $proveedor->fvalidez = $request->fvalidez;
        $proveedor->comentario = $request->comentario;
        $proveedor->rangomax = $request->rangomax;
        $proveedor->rangomin = $request->rangomin;
        $proveedor->contribuye_sri = $request->contribuye_sri;
        $proveedor->tip_electronico = $request->tip_electronico;
        $proveedor->imp_retencion = $request->imp_retencion;
        $proveedor->codsri_imp = $request->codsri_imp;
        $proveedor->retencion_iva = $request->retencion_iva;
        $proveedor->codsri_iva = $request->codsri_iva;
        $proveedor->tipo_contribuyente = $request->tipo_contribuyente;
        $proveedor->id_provincia = $request->id_provincia;
        $proveedor->id_ciudad = $request->id_ciudad;
        $proveedor->id_banco = $request->id_banco;
        $proveedor->id_empresa = $request->id_empresa;
        $proveedor->id_grupo_proveedor = $request->grupo;
        $proveedor->id_plan_cuentas = $request->id_contable;
        if ($request->identif_proveedor == $identificacion_id[0]->identif_proveedor) {
            $proveedor->save();
            return "vacio";
        } else {
            if (count($identificacion) > 0) {
                return "mal";
            } else {
                $proveedor->save();
                return "bien";
            }
        }
    }
    public function abrir(Request $request)
    {
        /*Destination::addSelect(['last_flight' => Flight::select('name')
        ->whereColumn('destination_id', 'destinations.id')
        ->orderBy('arrived_at', 'desc')
        ->limit(1)*/

        $id = $request->id;
        //$recupera = DB::select('SELECT * FROM `empresa` WHERE id_empresa='.$id);
        $recupera = Proveedor::addSelect([
            'cuenta_resultado' => Plancuenta::select('nomcta')
                ->whereColumn('id_plan_cuentas', 'proveedor.id_plan_cuentas')
        ])
            ->where('id_proveedor', '=', $id)
            ->get();
        $emails_prov = DB::select("SELECT email from proveedor where id_proveedor=" . $id);
        $email = explode(";", $emails_prov[0]->email);
        return [
            'recupera' => $recupera,
            'emails' => $email
        ];
    }
    public function select(Request $request, $id)
    {
        $buscar = $request->buscar;
        if ($buscar == '') {
            //$impuestos = Impuesto::paginate($cantidadp); 
            $recupera = Plancuenta::select('plan_cuentas.*')
                ->addSelect([
                    'nomb_moneda' => Moneda::select('nomb_moneda')
                        ->whereColumn('id_moneda', 'plan_cuentas.id_moneda'),
                    'nomb_grupo' => Grupo::select('nomb_grupo')
                        ->whereColumn('id_grupo', 'plan_cuentas.id_grupo')
                ])
                ->where("plan_cuentas.id_empresa", "=", $id)
                ->orderByRaw('plan_cuentas.codcta ASC')->limit(2)->get();
        } else {
            $recupera = Plancuenta::select('plan_cuentas.*')
                ->addSelect([
                    'nomb_moneda' => Moneda::select('nomb_moneda')
                        ->whereColumn('id_moneda', 'plan_cuentas.id_moneda'),
                    'nomb_grupo' => Grupo::select('nomb_grupo')
                        ->whereColumn('id_grupo', 'plan_cuentas.id_grupo')
                ])
                ->where(function ($q) use ($buscar) {
                    $q->where('plan_cuentas.codcta', 'like', '%' . $buscar . '%')
                        ->orWhere('plan_cuentas.nomcta', 'like', '%' . $buscar . '%');
                })
                ->where("plan_cuentas.id_empresa", "=", $id)
                ->orderByRaw('plan_cuentas.codcta ASC')
                ->limit(2)->get();
        }
        return [
            'recupera' => $recupera
        ];
    }
    public function eliminar($id)
    {
        Proveedor::destroy($id);
        /*$id = $request->id;
       $eliminar = Producto::find($request->id);
        $eliminar->delete();
  */
    }
    public function getProvincia()
    {
        $data = Provincia::get();

        return response()->json($data);
    }
    //public function getCiudad(Request $request)
    public function getCiudad(Request $request)
    {
        //$data=Ciudad::get();
        $data = Ciudad::where('id_provincia', '=', $request->provincia)->get();
        return response()->json($data);
    }
    public function buscarprovciudad(Request $request)
    {
        //$data=Ciudad::get();
        $b1 = trim($request->valor_prov);
        $b2 = trim($request->valor_ciud);
        $data_0 = DB::select("SELECT * FROM provincia where nombre like '%{$b1}%'");
        $data_1 = DB::select("SELECT * FROM ciudad where nombre like '%{$b2}%'");
        if (count($data_0) > 0 && count($data_1) > 0) {
            return [
                "id_provincia" => $data_0[0]->id_provincia,
                "id_ciudad" => $data_1[0]->id_ciudad,
            ];
        } else {
            if (count($data_0) <= 0) {
                return "no exite provincia";
            } else {

                return "no exite ciudad";
            }
        }
    }
    public function getBanco()
    {
        $data = Banco::get();

        return response()->json($data);
    }
    public function generarPDF(Request $request)
    {
        $queries = [];
        $inners = [];
        $fields = [];
        $initial = null;
        $final = null;
        //dd($request);
        /*if ($request->dates) {
            $info_date = json_decode($request->dates, true);
            if ($request->currentDate !== "true") {
                $initial = $info_date["range"]["initial"];
                $final = $info_date["range"]["final"];
                if ($info_date["option"] == 1) {
                    array_push($queries, "date(cta.fecha_pago) between date('{$info_date["range"]["initial"]}') and date('{$info_date["range"]["final"]}')\n");
                }
                if ($info_date["option"] == 2) {
                    array_push($queries, "date(cta.fecha_pago) between date('{$info_date["range"]["initial"]}') and date('{$info_date["range"]["final"]}')\n");
                }
                if ($info_date["option"] == 3) {
                    array_push($queries, "date(cta.fecha_pago) between date('{$info_date["range"]["initial"]}') and date('{$info_date["range"]["final"]}')\n");
                }
            } else {
                $initial = $info_date["value"];
                $final = $info_date["value"];
                if ($info_date["option"] == 1) {
                    array_push($queries, "date(cta.fecha_pago) = date('{$info_date["value"]}')\n");
                }
                if ($info_date["option"] == 2) {
                    array_push($queries, "date(cta.fecha_pago) = date('{$info_date["value"]}')\n");
                }
                if ($info_date["option"] == 3) {
                    array_push($queries, "date(prov.fecha_pago) = date('{$info_date["value"]}')\n");
                }
            }
        }*/
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

        if ($request->provider) {
            $info_provider = json_decode($request->provider, true);
            if ($info_provider["id"] != 0) {
                array_push($queries, "prov.id_proveedor = {$info_provider["id"]}\n");
            }
        }

        if ($request->user) {
            $info_user = json_decode($request->user, true);
            if ($info_user["id"] != 0) {
                array_push($queries, "prov.ucrea = {$info_user["id"]}\n");
            }
        }
        if ($request->wayToPay) {
            $info_payment = json_decode($request->wayToPay, true);
            if ($info_payment["id"] != 0) {
                array_push($queries, "prov.id_grupo_proveedor = {$info_payment["id"]}\n");
            }
        }

        $queries = implode(" and ", $queries);
        $inners = implode("", $inners);
        $fields = implode("", $fields);
        if ($queries == "") {
            $query2 = "SELECT prov.*,emp.nombre_empresa,emp.logo,provi.nombre as provincia,ci.nombre as ciudad FROM proveedor as prov,empresa as emp,provincia as provi,ciudad as ci where  prov.id_empresa={$request->company} and prov.id_empresa=emp.id_empresa and prov.id_ciudad=ci.id_ciudad and prov.id_provincia=provi.id_provincia";
        } else {
            $query2 = "SELECT prov.*,emp.nombre_empresa,emp.logo,provi.nombre as provincia,ci.nombre as ciudad FROM proveedor as prov,empresa as emp,provincia as provi,ciudad as ci where {$queries} and  prov.id_empresa={$request->company} and prov.id_empresa=emp.id_empresa and prov.id_ciudad=ci.id_ciudad and prov.id_provincia=provi.id_provincia";
        }

        //dd($query2);
        $reporte = DB::select($query2);
        if (!$reporte) {
            return response('no-data-report', 200)->header('Content-Type', 'application/json');
        } else {
            $Reportes = new generarReportes();
            $strPDF = $Reportes->Proveedor($reporte, $initial, $final);
            return response($strPDF, 200)->header('Content-Type', 'application/pdf');
        }
    }
    public function getGrupo($id)
    {
        $data = GrupoProveedor::select("*")->where("id_empresa", "=", $id)->get();

        return $data;
    }
    public function getImpFuente(Request $request, $id)
    {
        $termino = $request->porcen_impret;
        $data = DB::select("SELECT * FROM `impuesto` WHERE `porcen_imp` = $termino AND `tipo_imp`='Fuente' and id_empresa=" . $id);

        return $data;
    }
    public function getImpIva(Request $request, $id)
    {
        $termino = $request->porcen_imp;
        $data = DB::select("SELECT * FROM `impuesto` WHERE `porcen_imp` = $termino AND `tipo_imp`='Iva' and id_empresa=" . $id);

        return $data;
    }
    public function getRetencionFuente($id)
    {
        $termino = "Retencion Fuente Compras";
        $data = DB::select("SELECT * FROM `retencion` WHERE `tipo_retencion` = '" . $termino . "' and id_empresa=" . $id);

        return $data;
    }
    public function getRetencionIva($id)
    {
        $termino = "Retencion IVA Compras";
        $data = DB::select("SELECT * FROM `retencion` WHERE `tipo_retencion` = '" . $termino . "' and id_empresa=" . $id);

        return $data;
    }
    public function getTipComprob($id)
    {
        $data = DB::select('SELECT * from tipo_comprobante where id_empresa=' . $id);

        return $data;
    }

    public function codigo(Request $request)
    {
        $id = $request->id;
        $proveedor = DB::select("SELECT * FROM proveedor WHERE id_empresa = " . $id . " ORDER BY id_proveedor DESC limit 1");
        if ($proveedor) {
            $dato = $proveedor[0]->cod_proveedor;
            $var = 0;
            for ($i = strlen($dato); $i > 0; $i--) {
                if ($dato[$i - 1] == '-') {
                    $var = $i;
                    break;
                }
            }
            $codigo_prov = 0;
            $numero = substr($dato, $var) + 1;
            $cod = substr($dato, 0, $var);
            if ($numero <= 9) {
                $codigo_prov = $cod . "00" . $numero;
            } elseif ($numero >= 10) {
                $codigo_prov = $cod . "0" . $numero;
            } else {
                $codigo_prov = $cod . $numero;
            }
            return $codigo_prov;
        } else {
            return "vacio";
        }
    }
    public function getprov_ident($identificacion, $id)
    {
        $prov = DB::select("SELECT * FROM  proveedor WHERE identif_proveedor = ${identificacion} AND id_empresa= ${id}");
        return $prov;
    }
}
