<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guia;
use App\Models\FacturaGuiaDeRemision;
use App\Models\DetalleGuiaRemision;
use App\Models\Guia_remision;
use App\Models\Detalle;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;
include 'class/generarReportes.php';
include_once getenv("FILE_CONFIG_PHP");

use generarReportes;

class GuiaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $guia = Guia::all();
        return $guia;
    }
    public function listar(Request $request){
        $buscar = str_replace(array(" ", "_", "-"), "%", $request->buscar);
        //$rol = DB::select("SELECT user.*,rol.nombre,empresa.nombre_empresa from user,rol,empresa where user.id_empresa=empresa.id_empresa and rol.id_rol=user.id_rol and  id={$request->datos["id"]}");
        if($buscar == ''){
            $guia=DB::select("SELECT *,guia_remision.estado as estado_guia from guia_remision
                            INNER JOIN cliente
                            on cliente.id_cliente=guia_remision.id_cliente
                            INNER JOIN empresa
                            on empresa.id_empresa=guia_remision.id_empresa
                            where guia_remision.id_empresa={$request->datos['id_empresa']} and guia_remision.id_punto_emision={$request->datos['id_punto_emision']} and guia_remision.id_establecimiento={$request->datos['id_establecimiento']} order by guia_remision.clave_acceso desc");
        }else{
            $guia=DB::select("SELECT *,guia_remision.estado as estado_guia from guia_remision
                            INNER JOIN cliente
                            on cliente.id_cliente=guia_remision.id_cliente
                            INNER JOIN empresa
                            on empresa.id_empresa=guia_remision.id_empresa
                            where guia_remision.id_empresa={$request->datos['id_empresa']} and guia_remision.id_punto_emision={$request->datos['id_punto_emision']} and guia_remision.id_establecimiento={$request->datos['id_establecimiento']} and
                            (cliente.nombre like '%$buscar%' OR cliente.email like '%$buscar%' OR cliente.telefono like '%$buscar%' OR cliente.identificacion like '%$buscar%' OR guia_remision.respuesta like '%$buscar%' OR guia_remision.clave_acceso like '%$buscar%') order by guia_remision.clave_acceso desc");
        }
        return $guia;
        
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
    public function recuperar($id){
        $guia=DB::select("SELECT guia_remision.*,if(factura.codigo is not null,factura.codigo,SUBSTR(factura.clave_acceso,25,15)) as clave_acceso_doc,factura.fecha_emision FROM guia_remision LEFT JOIN factura on factura.id_factura=guia_remision.id_factura where id_guia={$id}");
        // cliente:{
                    //     tipo:false,
                    //     busqueda:'',
                    //     clientes:[],
                    //     id_cliente:null,
                    //     nombre:'',
                    //     telefono:'',
                    //     email:'',
                    //     tipo_identificacion:'',
                    //     identificacion:'',
                    //     direccion:'',
                    // },
        $cliente=DB::select("SELECT id_cliente,nombre,telefono,tipo_identificacion,identificacion,direccion,email FROM cliente where id_cliente={$guia[0]->id_cliente}");
        $producto=DB::select("SELECT *,detalle_guia_remision.id_proyecto as proyecto FROM detalle_guia_remision,producto where producto.id_producto=detalle_guia_remision.id_producto and id_guia_remision={$id}");
        return [
            "guia"=>$guia,
            "cliente"=>$cliente,
            "producto"=>$producto
        ];
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $guia = new Guia();
        $guia->claveAcceso= $request->claveAcceso;
        $guia->numeroAutorizacion= $request->numeroAutorizacion;
        $guia->fechaAutorizacion= $request->fechaAutorizacion;
        $guia->estado= $request->estado;
        $guia->ambiente= $request->ambiente;
        $guia->tipoEmision= $request->tipoEmision;
        $guia->secuencial= $request->secuencial;
        $guia->nombreArchivo= $request->nombreArchivo;
        $guia->dirPartida= $request->dirPartida;
        $guia->razonSocialTransportista= $request->razonSocialTransportista;
        $guia->rucTransportista= $request->rucTransportista;
        $guia->fechaIniTransporte= $request->fechalniTransporte;
        $guia->fechaFinTransporte= $request->fechaFinTransporte;
        $guia->placa= $request->placa;
        $guia->motivoTraslado= $request->motivoTraslado;
        $guia->firmado= $request->firmado;
        $guia->enviarSiAutorizado= $request->enviarSiAutorizado;
        $guia->save();
    }
    public function guardar_guia_clave(Request $request){
        $valor1 = "";
        $clave_acceso = $request->transportista["clave_acceso"];
        $ca = substr($clave_acceso, 24, 15);
        $res = DB::select("SELECT * FROM guia_remision WHERE clave_acceso like  '%{$ca}%' and id_empresa={$request->id_empresa} ");
        if (count($res) >= 1) {
            $valor1 = "repetido";
        }
        return [
            "guia" => $valor1
        ];
    }
    public function guardar_guia(Request $request){
        if($request->id_documento!==null){
            $exist=DB::select("SELECT * from guia_remision where id_factura={$request->id_documento}");
            if(count($exist)>0){
                return "existe guia factura";
            }
        }
        
        $transportistas = new FacturaGuiaDeRemision();
        $transportistas->clave_acceso = $request->transportista['clave_acceso'];
        $transportistas->razon_social_tr = $request->transportista['nombre_transporte'];
        $transportistas->respuesta = "ERROR";
        $transportistas->estado = 1;
        $transportistas->tipo_identificacion_tr = $request->transportista['tipo_identificacion_transporte'];
        $transportistas->identificacion_tr = $request->transportista['identificacion_transporte'];
        $transportistas->fecha_inicio_tr = $request->transportista['fecha_inicio_transporte'];
        $transportistas->fecha_fin_tr = $request->transportista['fecha_fin_transporte'];
        $transportistas->placa_tr = $request->transportista['placa_transporte'];
        $transportistas->doc_aduanero_tr = $request->transportista['documento_aduanero'];
        $transportistas->motivo_translado_tr = $request->transportista['motivo_translado'];
        $transportistas->otro_destino_tr = $request->transportista['otro_destino'];
        $transportistas->destino_tr = $request->transportista['destino'];
        $transportistas->otra_dir_partida_tr = $request->transportista['otra_dir_partida'];
        $transportistas->dir_partida_tr = $request->transportista['dir_partida'];
        $transportistas->cod_sustento_tr = 1;
        $transportistas->observacion_tr = $request->observacion;
        $transportistas->id_empresa = $request->id_empresa; // recuperar estos valores - REVISAR SI ES CORRECTO;
        $transportistas->id_factura = $request->id_documento; // recuperar estos valores - REVISAR SI ES CORRECTO;
        $transportistas->id_cliente = $request->cliente;
        $transportistas->id_user = $request->usuario;
        $transportistas->id_punto_emision = $request->id_punto_emision;
        $transportistas->id_establecimiento = $request->id_establecimiento;
        $transportistas->save();
        $idt = $transportistas->id_guia;

        //recupera el número del secuencial de la guia y agrega uno mas en punto de emision
        $s_facturasubstr = substr($request->transportista["clave_acceso"], -19, -10);
        $sf = $s_facturasubstr + 1;
        $idp = $request->id_punto_emision;
        DB::update("UPDATE punto_emision SET secuencial_guia_remision = '$sf' WHERE id_punto_emision = $idp");

        for ($a=0; $a <count($request->productos) ; $a++) { 
                
            $detguia = new DetalleGuiaRemision();
                $detguia->codigo_interno = $request->productos[$a]["id_producto"];
                $detguia->descripcion = $request->productos[$a]["nombre"];
                $detguia->cantidad = $request->productos[$a]["cantidad"];
                $detguia->id_producto = $request->productos[$a]["id_producto"];
                $detguia->id_guia_remision = $idt;
                $detguia->id_proyecto = $request->productos[$a]["proyecto"];
                $detguia->save();
        }
        return [
            "guia" => Guia_remision::select('guia_remision.*', 'empresa.*', 'cliente.*', 'establecimiento.codigo as codigoes', 'punto_emision.codigo as codigope', 'establecimiento.direccion as direccion_establecimiento')
                ->join('empresa', 'empresa.id_empresa', '=', 'guia_remision.id_empresa')
                ->join('cliente', 'cliente.id_cliente', '=', 'guia_remision.id_cliente')
                ->join('establecimiento', 'establecimiento.id_establecimiento', '=', 'guia_remision.id_establecimiento')
                ->join('punto_emision', 'punto_emision.id_punto_emision', '=', 'guia_remision.id_punto_emision')
                ->where("guia_remision.id_guia", "=", $idt)
                ->orderByRaw('guia_remision.id_guia DESC')->get()
        ];
    }

    public function editar_guia(Request $request){
        if($request->id_documento!==null){
            $exist=DB::select("SELECT * from guia_remision where id_factura={$request->id_documento} and id_guia<>{$request->id_guia}");
            if(count($exist)>0){
                return "existe guia factura";
            }
        }
        $transportistas = FacturaGuiaDeRemision::find($request->id_guia);
        $respuesta=$transportistas->respuesta;
        if($respuesta!=="Enviado"){
            $transportistas->clave_acceso = $request->transportista['clave_acceso'];
            $transportistas->razon_social_tr = $request->transportista['nombre_transporte'];
            //$transportistas->respuesta = "ERROR";
            $transportistas->tipo_identificacion_tr = $request->transportista['tipo_identificacion_transporte'];
            $transportistas->identificacion_tr = $request->transportista['identificacion_transporte'];
            $transportistas->fecha_inicio_tr = $request->transportista['fecha_inicio_transporte'];
            $transportistas->fecha_fin_tr = $request->transportista['fecha_fin_transporte'];
            $transportistas->placa_tr = $request->transportista['placa_transporte'];
            $transportistas->doc_aduanero_tr = $request->transportista['documento_aduanero'];
            $transportistas->motivo_translado_tr = $request->transportista['motivo_translado'];
            $transportistas->otro_destino_tr = $request->transportista['otro_destino'];
            $transportistas->destino_tr = $request->transportista['destino'];
            $transportistas->otra_dir_partida_tr = $request->transportista['otra_dir_partida'];
            $transportistas->dir_partida_tr = $request->transportista['dir_partida'];
            $transportistas->cod_sustento_tr = 1;
            $transportistas->observacion_tr = $request->observacion;
            $transportistas->id_empresa = $request->id_empresa; // recuperar estos valores - REVISAR SI ES CORRECTO;
            $transportistas->id_factura = $request->id_documento; // recuperar estos valores - REVISAR SI ES CORRECTO;
            $transportistas->id_cliente = $request->cliente;
            $transportistas->id_user = $request->usuario;
            $transportistas->id_punto_emision = $request->id_punto_emision;
            $transportistas->id_establecimiento = $request->id_establecimiento;
            $transportistas->save();
            $idt = $transportistas->id_guia;
        }
        
       

        //recupera el número del secuencial de la guia y agrega uno mas en punto de emision
        // $s_facturasubstr = substr($request->transportista["clave_acceso"], -19, -10);
        // $sf = $s_facturasubstr + 1;
        // $idp = $request->id_punto_emision;
        // DB::update("UPDATE punto_emision SET secuencial_guia_remision = '$sf' WHERE id_punto_emision = $idp");
        if($respuesta!=="Enviado"){
            for ($a=0; $a <count($request->productos) ; $a++) { 
                    if(isset($request->productos[$a]["id_detalle_guia_remision"])){
                        $detguia = DetalleGuiaRemision::find($request->productos[$a]["id_detalle_guia_remision"]);
                        $detguia->codigo_interno = $request->productos[$a]["id_producto"];
                        $detguia->descripcion = $request->productos[$a]["nombre"];
                        $detguia->cantidad = $request->productos[$a]["cantidad"];
                        $detguia->id_producto = $request->productos[$a]["id_producto"];
                        $detguia->id_guia_remision = $idt;
                        $detguia->id_proyecto = $request->productos[$a]["proyecto"];
                        $detguia->save();
                    }else{
                        $detguia = new DetalleGuiaRemision();
                        $detguia->codigo_interno = $request->productos[$a]["id_producto"];
                        $detguia->descripcion = $request->productos[$a]["nombre"];
                        $detguia->cantidad = $request->productos[$a]["cantidad"];
                        $detguia->id_producto = $request->productos[$a]["id_producto"];
                        $detguia->id_guia_remision = $idt;
                        $detguia->id_proyecto = $request->productos[$a]["proyecto"];
                        $detguia->save();
                    }
                    
            }
        }

        return [
            "guia" => Guia_remision::select('guia_remision.*', 'empresa.*', 'cliente.*', 'establecimiento.codigo as codigoes', 'punto_emision.codigo as codigope', 'establecimiento.direccion as direccion_establecimiento')
                ->join('empresa', 'empresa.id_empresa', '=', 'guia_remision.id_empresa')
                ->join('cliente', 'cliente.id_cliente', '=', 'guia_remision.id_cliente')
                ->join('establecimiento', 'establecimiento.id_establecimiento', '=', 'guia_remision.id_establecimiento')
                ->join('punto_emision', 'punto_emision.id_punto_emision', '=', 'guia_remision.id_punto_emision')
                ->where("guia_remision.id_guia", "=", $request->id_guia)
                ->orderByRaw('guia_remision.id_guia DESC')->get(),
            "repuesta"=>$respuesta
        ];

        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        $guia = Guia::findOrFail($request->id);
        $guia->claveAcceso= $request->claveAcceso;
        $guia->numeroAutorizacion= $request->numeroAutorizacion;
        $guia->fechaAutorizacion= $request->fechaAutorizacion;
        $guia->estado= $request->estado;
        $guia->ambiente= $request->ambiente;
        $guia->tipoEmision= $request->tipoEmision;
        $guia->secuencial= $request->secuencial;
        $guia->nombreArchivo= $request->nombreArchivo;
        $guia->dirPartida= $request->dirPartida;
        $guia->razonSocialTransportista= $request->razonSocialTransportista;
        $guia->rucTransportista= $request->rucTransportista;
        $guia->fechaIniTransporte= $request->fechalniTransporte;
        $guia->fechaFinTransporte= $request->fechaFinTransporte;
        $guia->placa= $request->placa;
        $guia->motivoTraslado= $request->motivoTraslado;
        $guia->firmado= $request->firmado;
        $guia->enviarSiAutorizado= $request->enviarSiAutorizado;
        $guia->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function eliminar(Request $request)
    {
        //
        DB::update("UPDATE guia_remision set estado=0 where id_guia={$request->datos["id_guia"]}");

    }
    public function listar_documentos(Request $request)
    {
        //lista la factura mediante la clave de acceso, busqueda e id-empresa
        $bs = $request->buscar;
        $empresa = $request->id_empresa;
        $establecimiento = $request->id_establecimiento;
        $res1 =  DB::select("SELECT * FROM factura WHERE ( codigo like '%$bs%' or concat('n',codigo) like '%$bs%') AND id_empresa = $empresa AND id_establecimiento = $establecimiento and codigo is not null ORDER BY codigo asc LIMIT 5 ");
        $res2 =  DB::select("SELECT * FROM factura WHERE (clave_acceso LIKE '%$bs%') AND id_empresa = $empresa AND id_establecimiento = $establecimiento and clave_acceso is not null ORDER BY clave_acceso asc LIMIT 10 ");
        $res=array_merge($res1,$res2);
        return $res;
    }
    public function buscardocumento(Request $request)
    {
        if($request->proforma==1){
            $factura = DB::select("SELECT * from factura where id_empresa={$request->id_empresa} and codigo={$request->factura}");
        }else{
            $factura = DB::select("SELECT * from factura where id_empresa={$request->id_empresa} and clave_acceso like '%{$request->factura}%'");
        }
        
        if (count($factura) > 0) {
            $detalle = Detalle::select("detalle.*", "producto.cod_principal", "producto.cod_alterno")->leftJoin("producto", "producto.id_producto", "=", "detalle.id_producto")->where("detalle.id_factura", "=", $factura[0]->id_factura)->get();
            $cliente = Cliente::select("*")->where("id_cliente", "=", $factura[0]->id_cliente)->get();
            return [
                'factura' => $factura[0],
                'detalle' => $detalle,
                'cliente' => $cliente[0]
            ];
        } else {
            return 'error';
        }
    }
    public function generar_reporte(Request $request)
    {
        // dd($request);
        $queries = [];
        // if ($request->rol_user == 2) {
        //     $vnd = DB::select("SELECT * from vendedor where id_user=" . $request->user);
        //     if ($vnd) {
        //         array_push($queries, "f.id_vendedor = {$vnd[0]->id_vendedor}\n");
        //     }
        // } else {
        //}
        // if ($request->rol_user == 2) {
        //     $vnd = DB::select("SELECT * from vendedor where id_user=" . $request->user);
        //     if ($vnd) {
        //         array_push($queries, "f.id_vendedor = {$vnd[0]->id_vendedor}\n");
        //     }
        // } else {
        //     if ($request->seller) {
        //         $info_seller = json_decode($request->seller, true);
        //         if ($info_seller["id"] != 0) {
        //             array_push($queries, "f.id_vendedor = {$info_seller["id"]}\n");
        //         }
        //     }
        // }
        $nombre_vendedor = "";
        // if ($request->rol_user !== "2") {


        //     if ($request->seller) {



        $nombre_producto = '';
        if ($request->products) {
            $to_array = function ($product) {

                $new_product = json_decode($product);

                return $new_product->id;
            };
            //$new_product = json_decode($request->products);
            //dd($new_product->id);
            // if($new_product->id!==0){
            //     array_push($queries, "df.id_producto in ({$info_products})\n");
            // }
            //dd($request->products);
            if (gettype($request->products) !== 'string') {
                if (count($request->products) == 1) {
                    $to_array_2 = function ($product) {
                        $new_product = json_decode($product);
                        return $new_product->nombre;
                    };
                    $info_products_2 = implode(",", array_map($to_array_2, $request->products));
                    $nombre_producto = $info_products_2;
                }

                $info_products = implode(",", array_map($to_array, $request->products));
                //dd($info_products);
                if ($info_products !== "0") {
                    array_push($queries, "df.id_producto in ({$info_products})\n");
                }
            } else {
                //dd("es string");
            }
        }
        if ($request->presentacion) {
            $info_presentacion = json_decode($request->presentacion);
            if ($info_presentacion->id != 0) {
                array_push($queries, "pr.id_presentacion={$info_presentacion->id}\n");
            }
        }
        if ($request->model) {
            $info_model = json_decode($request->model);
            if ($info_model->id != 0) {
                array_push($queries, "md.id_modelo={$info_model->id}\n");
            }
        }
        if ($request->marca) {
            $info_marca = json_decode($request->marca);
            if ($info_marca->id != 0) {
                array_push($queries, "mc.id_marca={$info_marca->id}\n");
            }
        }
        if ($request->tipo_producto) {
            $info_tipo_producto = json_decode($request->tipo_producto);
            if ($info_tipo_producto->id != 0) {
                array_push($queries, "tp.id_tipo_producto={$info_tipo_producto->id}\n");
            }
        }
        if ($request->linea_producto) {
            $info_linea_producto = json_decode($request->linea_producto);
            if ($info_linea_producto->id != 0) {
                array_push($queries, "lp.id_linea_producto={$info_linea_producto->id}\n");
            }
        }
        if ($request->grupoCliente) {
            $info_grupo_cliente = json_decode($request->grupoCliente);
            if ($info_grupo_cliente->id != 0) {
                array_push($queries, "c.id_grupo_cliente={$info_grupo_cliente->id}\n");
            }
        }
        if ($request->tipoCliente) {
            $info_tipo_cliente = json_decode($request->tipoCliente);
            if ($info_tipo_cliente->id != 0) {
                array_push($queries, "c.id_tipo_cliente={$info_tipo_cliente->id}\n");
            }
        }
        if ($request->grupoTributario) {
            $info_grupo_tributario = json_decode($request->grupoTributario);
            if ($info_grupo_tributario->id != 0) {
                array_push($queries, "c.grupo_tributario='{$info_tipo_cliente->name}'\n");
            }
        }

        if ($request->selectedEstablishment) {
            $info_establishment = json_decode($request->selectedEstablishment);
            if ($info_establishment->id != 0) {
                array_push($queries, "f.id_establecimiento = {$info_establishment->id}\n");
            }
        }
        if ($request->selectedPointOfEmission) {
            $info_pointOfEmission = json_decode($request->selectedPointOfEmission);
            if ($info_pointOfEmission->id != 0) {
                array_push($queries, "f.id_punto_emision = {$info_pointOfEmission->id}\n");
            }
        }
        if ($request->selectedProject) {
            $info_project = json_decode($request->selectedProject);
            if ($info_project->id != 0) {
                array_push($queries, "df.id_proyecto = {$info_project->id}\n");
            }
        }
        if ($request->date) {
            if (!is_null(json_decode($request->date))) {
                $request->date = json_decode($request->date);
                $date_initial = str_replace("-010-", "-10-", $request->date->initialDate);
                $date_final = str_replace("-010-", "-10-", $request->date->finalDate);
                array_push($queries, "date(f.fecha_inicio_tr) between date('{$date_initial}') and date('{$date_final}')\n");
            } else {
                $date = str_replace("-010-", "-10-", $request->date);
                array_push($queries, "date(f.fecha_inicio_tr) = date('{$date}')\n");
            }
        }
        
        // if($request->){

        // }
        $queries = implode(" and ", $queries);

        $query = "
        SELECT
        f.*,
        (select sum(cantidad) from detalle_guia_remision where detalle_guia_remision.id_guia_remision=f.id_guia) as cantidad,
        c.identificacion,
        c.nombre,
        c.direccion,
        e.id_empresa as idempresa,
        e.nombre_empresa,
        e.logo
        FROM guia_remision f
        inner join detalle_guia_remision df
        on df.id_guia_remision = f.id_guia
        inner join producto prod
        on df.id_producto = prod.id_producto
        left join linea_producto as lp
        on lp.id_linea_producto=prod.id_linea_producto
        left join tipo_producto as tp
        on tp.id_tipo_producto=prod.id_tipo_producto
        left join marca as mc
        on mc.id_marca=prod.id_marca
        left join modelo as md
        on md.id_modelo=prod.id_modelo
        left join presentacion as pr
        on pr.id_presentacion=prod.id_presentacion
        inner join cliente c
        on f.id_cliente = c.id_cliente
        inner join empresa e
        on f.id_empresa = e.id_empresa
        inner join user u
        on f.id_user = u.id
            WHERE {$queries}
            and f.id_empresa={$request->company}
            order by f.fecha_inicio_tr,f.clave_acceso asc;
            ";
        //dd($query);
        $reporte = DB::select($query);
        
        $Reportes = new generarReportes();
        if (!$reporte) {
            return response('no-data-report', 200)->header('Content-Type', 'application/json');
        } else {
            if (property_exists($request->date, 'initialDate')) {
                $strPDF = $Reportes->guia_reporte($reporte, $date_initial, $date_final, $nombre_producto);
            } else {
                $strPDF = $Reportes->guia_reporte($reporte, $request->date, $request->date, $nombre_producto);
            }
            return response($strPDF, 200)->header('Content-Type', 'application/pdf');
        }
    }
}
