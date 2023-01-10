<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\Input;
use App\Models\Empresa;
use App\Models\Moneda;
use App\Models\Provincia;
use App\Models\Ciudad;
use App\Models\Plancuenta;

use App\Models\Establecimiento;
use App\Models\Ptoemision;
use App\Models\FormaDePagos;
use App\Models\FormaDePagosSri;
use App\Models\Impuesto;
use App\Models\Tipocomprobante;
use App\Models\Tiposustento;
use App\Models\Retencion;
use App\Models\Roles;
use App\Usera;
use App\Models\Ice;
use App\Models\Ice_formula;

use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Mockery\Undefined;
use PhpParser\Node\Expr\Empty_;

include_once getenv("FILE_CONFIG_PHP");
class EmpresaController extends Controller
{
    public function index(Request $request)
    {
        $buscar = str_replace(array(" "), "%", $request->buscar);
        if ($buscar == '') {
            $recupera = Empresa::all();
        } else {
            $recupera = Empresa::select('*')
                ->where(function ($q) use ($buscar) {
                    $q->where('id_empresa', 'like', '%' . $buscar . '%')
                        ->orWhere('nombre_empresa', 'like', '%' . $buscar . '%')
                        ->orWhere('razon_social', 'like', '%' . $buscar . '%')
                        ->orWhere('ruc_empresa', 'like', '%' . $buscar . '%');
                })->get();
        }
        return [
            'recupera' => $recupera
        ];
    }
    public function indexUsuario()
    {
        $recupera = DB::table('empresa')
            ->where('id_user', 6)->get();
        return [$recupera];
    }
    public function store(Request $request)
    {

        ini_set('max_execution_time', 53200);
        $categoria=[];
        if(isset($request->categoria_producto)){
            $decode=json_decode($request->categoria_producto);
            for($z=0;$z<count($decode);$z++){
                array_push($categoria,$decode[$z]->value);
            }
            $categoria=implode(";",$categoria);
        
        }else{
            $categoria=null;   
        }
        $carpetanombre = constant("DATA_EMPRESA");
        $now = Carbon::now();

        if ($request->file('file_imagen')) {
            $file_imagen = $request->file('file_imagen');
            $nombre_imagen = time() . $file_imagen->getClientOriginalName();
        }

        if ($request->file('file_p12')) {
            $file_p12 = $request->file('file_p12');
            $nombre_p12 = $file_p12->getClientOriginalName();
        }

        $empresa = new Empresa();
        $empresa->periodo_empresa = $request->periodo;
        $empresa->nombre_empresa = $request->nombre_comercial;
        $empresa->razon_social = $request->razon_social;
        $empresa->ruc_empresa = $request->ruc;
        $empresa->direccion_empresa = $request->direccion;
        $empresa->telefono = $request->telefono;
        $empresa->email_empresa = $request->email_empresa;
        $empresa->nro_comprobantes = $request->nro_comprobantes;
        $empresa->password = $request->password;
        $empresa->servidor_correo = $request->servidor_correo;
        $empresa->puerto_correo = $request->puerto_correo;
        $empresa->seguridad_correo = $request->seguridad_correo;
        $empresa->tipo_identidicacion_empresa = $request->tipo_identidicacion;
        $empresa->obligado_contabilidad = $request->contabilidad;


        $empresa->compra = $request->compra;
        $empresa->migo = $request->migo;
        if ($request->ruc_contador != "null") {
            $empresa->ruc_contador = $request->ruc_contador;
        } else {
            $empresa->ruc_contador = null;
        }
        if ($request->nombre_contador != "null") {
            $empresa->nombre_contador = $request->nombre_contador;
        } else {
            $empresa->nombre_contador = null;
        }
        if ($request->identificacion_representante != "null") {
            $empresa->identificaion_rep = $request->identificacion_representante;
        } else {
            $empresa->identificaion_rep = null;
        }
        $empresa->nomb_representante = $request->nomb_representante;
        $empresa->clave_duracion = $request->fecha_duracion;
        $empresa->periodo_inicio = $request->periodo_inicio;
        $empresa->periodo_fin = $request->periodo_fin;
        $empresa->mascara_empresa = "1";
        $empresa->recalculo = $request->recalculo;
        if ($request->numero_resolucion != "null") {
            $empresa->balance = $request->balance;
        } else {
            $empresa->balance = null;
        }
        $empresa->empresa_asociada = $request->empresa_asociada;
        if ($request->numero_resolucion != "null") {
            $empresa->noresolucion = $request->numero_resolucion;
        } else {
            $empresa->noresolucion = null;
        }
        if ($request->numero_contribuyente != "null") {
            $empresa->nocontribuyente = $request->numero_contribuyente;
        } else {
            $empresa->nocontribuyente = null;
        }
        if ($request->codigo_entidad != "null") {
            $empresa->codigo_entidad = $request->codigo_entidad;
        } else {
            $empresa->codigo_entidad = null;
        }
        $empresa->contribuyente = $request->contribuyente;
        $empresa->tipo_ctas = "Ctas Niif";
        if ($request->fecha_resolucion) {
            if ($request->fecha_resolucion != '0000-00-00' || $request->fecha_resolucion != null || $request->fecha_resolucion != 'Undefined' || $request->fecha_resolucion != 'null') {
                $empresa->fresolucion = $request->fecha_resolucion;
            }
        } else {
            $empresa->fresolucion = null;
        }
        $empresa->fcierre = $request->fecha_cierre;
        if ($request->moneda) {
            $empresa->id_moneda = $request->moneda;
        } else {
            $empresa->id_moneda = 1;
        }
        $empresa->id_provincia = $request->provincia;
        $empresa->id_ciudad = $request->ciudad;
        $empresa->tipo_emision = $request->tipoemision;
        $empresa->ambiente = $request->ambiente;
        $empresa->negativo = $request->negativo;
        $empresa->pass_firma = $request->pass_firma;
        $empresa->fecha_firma = $now;
        $empresa->actualizacion_firma = $now;
        $empresa->fecha_expiracion_firma = $request->fecha_expiracion_firma;

        $empresa->id_plan_cuentas_resultado = $request->ctaResultado;
        $empresa->id_plan_cuentas_ingreso = $request->ctaIngreso;
        $empresa->id_plan_cuentas_costo = $request->ctaCosto;
        $empresa->id_plan_cuentas_activo = $request->ctaActivo;
        $empresa->id_plan_cuentas_pasivo = $request->ctaPasivo;
        $empresa->id_plan_cuentas_patrimonio = $request->ctaPatrimonio;
        $empresa->id_plan_cuentas_gasto = $request->ctaGasto;
        $empresa->id_plan_cuentas_orden = $request->ctaOrden;
        $empresa->leyenda = $request->leyenda;
        $empresa->email_facturacion = $request->email_facturacion;
        $empresa->estado = 1;
        $empresa->xml_factura_compra = filter_var($request->xml_factura_compra, FILTER_VALIDATE_BOOLEAN);
        $empresa->categoria_producto=$categoria;
        $empresa->save();
        $id = $empresa->id_empresa;

        if ($request->file('file_imagen')) {
            $recupera_ubicacion_imagen = $nombre_imagen;
        } else {
            $recupera_ubicacion_imagen = "";
        }
        if ($request->file('file_p12')) {
            $recupera_ubicacion_firma =  $nombre_p12;
        } else {
            $recupera_ubicacion_firma = "";
        }

        $empresaf = Empresa::findOrFail($id);
        $empresaf->logo = $recupera_ubicacion_imagen;
        $empresaf->firma = $recupera_ubicacion_firma;
        $empresaf->save();

        $carpetaprincipal = $carpetanombre;
        if (!file_exists($carpetaprincipal)) {
            if (!mkdir($carpetaprincipal, 0777, true)) {
                DB::delete("DELETE FROM empresa WHERE id_empresa = $id");
            }
        }

        $data = \Illuminate\Support\Facades\File::get(storage_path("formas_pago.json"));
        $products = json_decode($data, true);
        foreach ($products as $product) {
            $fp1 = new FormaDePagosSri();
            $fp1->codigo = $product["codigo"];
            $fp1->descripcion = $product["descripcion"];
            $fp1->id_empresa = $id;
            $fp1->save();
            $idfp1 = $fp1->id_forma_pagos_sri;
            $fp2 = new FormaDePagos();
            $fp2->codigo = $product["codigo"];
            $fp2->descripcion = $product["descripcion"];
            $fp2->id_empresa = $id;
            $fp2->id_forma_pagos_sri = $idfp1;
            $fp2->save();
        }
        $data_impuesto = \Illuminate\Support\Facades\File::get(storage_path("impuesto.json"));
        $products_impuesto = json_decode($data_impuesto, true);
        foreach ($products_impuesto as $product) {
            $fp1 = new Impuesto();
            $fp1->cod_imp = $product["cod_imp"];
            $fp1->descrip_imp = $product["descrip_imp"];
            $fp1->tipo_imp = $product["tipo_imp"];
            $fp1->porcen_imp = $product["porcen_imp"];
            $fp1->id_empresa = $id;
            $fp1->save();
        }
        $data_retencion = \Illuminate\Support\Facades\File::get(storage_path("retencion.json"));
        $products_retencion = json_decode($data_retencion, true);
        foreach ($products_retencion as $product) {
            $fp1 = new Retencion();
            $fp1->cod_retencion = $product["cod_retencion"];
            $fp1->descrip_retencion = $product["descrip_retencion"];
            $fp1->porcen_retencion = $product["porcen_retencion"];
            $fp1->tipo_retencion = $product["tipo_retencion"];
            $fp1->tipoiva_retencion = $product["tipoiva_retencion"];
            $fp1->id_moneda = $product["id_moneda"];
            //$fp1->id_impuesto = $product["id_impuesto"];
            $fp1->id_empresa = $id;
            $fp1->save();
        }
        $data_tipo_comprobante = \Illuminate\Support\Facades\File::get(storage_path("tipo_comprobante.json"));
        $products_tipo_comprobante = json_decode($data_tipo_comprobante, true);
        foreach ($products_tipo_comprobante as $product) {
            $fp1 = new Tipocomprobante();
            $fp1->cod_tipcomprob = $product["cod_tipcomprob"];
            $fp1->descrip_tipcomprob = $product["descrip_tipcomprob"];
            $fp1->id_empresa = $id;
            $fp1->save();
        }
        $data_tipo_sustento = \Illuminate\Support\Facades\File::get(storage_path("tipo_sustento.json"));
        $products_tipo_sustento = json_decode($data_tipo_sustento, true);
        foreach ($products_tipo_sustento as $product) {
            $fp1 = new Tiposustento();
            $fp1->cod_sustento = $product["cod_sustento"];
            $fp1->descrip_sustento = $product["descrip_sustento"];
            $fp1->id_empresa = $id;
            $fp1->save();
        }

        //carpetas
        if (!file_exists($carpetaprincipal)) {
            mkdir($carpetaprincipal, 0777, true);
        }
        $carpetaprincipal = $carpetanombre;
        if (!file_exists($carpetaprincipal)) {
            mkdir($carpetaprincipal, 0777, true);
        }
        //factura
        $carpeta = $carpetanombre . $id . "/comprobantes";
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }
        $carpeta1 = $carpetanombre . $id . "/comprobantes/factura";
        if (!file_exists($carpeta1)) {
            mkdir($carpeta1, 0777, true);
        }
        $carpeta2 = $carpetanombre . $id . "/comprobantes/factura/errores";
        if (!file_exists($carpeta2)) {
            mkdir($carpeta2, 0777, true);
        }
        $carpeta3 = $carpetanombre . $id . "/comprobantes/factura/codigosbarras";
        if (!file_exists($carpeta3)) {
            mkdir($carpeta3, 0777, true);
        }
        //guia
        $carpeta1 = $carpetanombre . $id . "/comprobantes/guia";
        if (!file_exists($carpeta1)) {
            mkdir($carpeta1, 0777, true);
        }
        $carpeta2 = $carpetanombre . $id . "/comprobantes/guia/errores";
        if (!file_exists($carpeta2)) {
            mkdir($carpeta2, 0777, true);
        }
        $carpeta3 = $carpetanombre . $id . "/comprobantes/guia/codigosbarras";
        if (!file_exists($carpeta3)) {
            mkdir($carpeta3, 0777, true);
        }
        //retencion
        $carpeta1 = $carpetanombre . $id . "/comprobantes/retencion";
        if (!file_exists($carpeta1)) {
            mkdir($carpeta1, 0777, true);
        }
        $carpeta2 = $carpetanombre . $id . "/comprobantes/retencion/errores";
        if (!file_exists($carpeta2)) {
            mkdir($carpeta2, 0777, true);
        }
        $carpeta3 = $carpetanombre . $id . "/comprobantes/retencion/codigosbarras";
        if (!file_exists($carpeta3)) {
            mkdir($carpeta3, 0777, true);
        }
        //notacredito
        $carpeta1 = $carpetanombre . $id . "/comprobantes/notacredito";
        if (!file_exists($carpeta1)) {
            mkdir($carpeta1, 0777, true);
        }
        $carpeta2 = $carpetanombre . $id . "/comprobantes/notacredito/errores";
        if (!file_exists($carpeta2)) {
            mkdir($carpeta2, 0777, true);
        }
        $carpeta3 = $carpetanombre . $id . "/comprobantes/notacredito/codigosbarras";
        if (!file_exists($carpeta3)) {
            mkdir($carpeta3, 0777, true);
        }
        //notadebito
        $carpeta1 = $carpetanombre . $id . "/comprobantes/notadebito";
        if (!file_exists($carpeta1)) {
            mkdir($carpeta1, 0777, true);
        }
        $carpeta2 = $carpetanombre . $id . "/comprobantes/notadebito/errores";
        if (!file_exists($carpeta2)) {
            mkdir($carpeta2, 0777, true);
        }
        $carpeta3 = $carpetanombre . $id . "/comprobantes/notadebito/codigosbarras";
        if (!file_exists($carpeta3)) {
            mkdir($carpeta3, 0777, true);
        }
        //liquidacion
        $carpeta1 = $carpetanombre . $id . "/comprobantes/liquidacion";
        if (!file_exists($carpeta1)) {
            mkdir($carpeta1, 0777, true);
        }
        $carpeta2 = $carpetanombre . $id . "/comprobantes/liquidacion/errores";
        if (!file_exists($carpeta2)) {
            mkdir($carpeta2, 0777, true);
        }
        $carpeta3 = $carpetanombre . $id . "/comprobantes/liquidacion/codigosbarras";
        if (!file_exists($carpeta3)) {
            mkdir($carpeta3, 0777, true);
        }
        //facturacompra
        $carpeta4 = $carpetanombre . $id . "/comprobantes/facturacompra";
        if (!file_exists($carpeta4)) {
            mkdir($carpeta4, 0777, true);
        }
        $carpeta5 = $carpetanombre . $id . "/comprobantes/facturacompra/errores";
        if (!file_exists($carpeta5)) {
            mkdir($carpeta5, 0777, true);
        }
        $carpeta6 = $carpetanombre . $id . "/comprobantes/facturacompra/codigosbarras";
        if (!file_exists($carpeta6)) {
            mkdir($carpeta6, 0777, true);
        }
        //guiacompra
        $carpeta4 = $carpetanombre . $id . "/comprobantes/guiacompra";
        if (!file_exists($carpeta4)) {
            mkdir($carpeta4, 0777, true);
        }
        $carpeta5 = $carpetanombre . $id . "/comprobantes/guiacompra/errores";
        if (!file_exists($carpeta5)) {
            mkdir($carpeta5, 0777, true);
        }
        $carpeta6 = $carpetanombre . $id . "/comprobantes/guiacompra/codigosbarras";
        if (!file_exists($carpeta6)) {
            mkdir($carpeta6, 0777, true);
        }
        //notacredito
        $carpeta4 = $carpetanombre . $id . "/comprobantes/notacreditocompra";
        if (!file_exists($carpeta4)) {
            mkdir($carpeta4, 0777, true);
        }
        $carpeta5 = $carpetanombre . $id . "/comprobantes/notacreditocompra/errores";
        if (!file_exists($carpeta5)) {
            mkdir($carpeta5, 0777, true);
        }
        $carpeta6 = $carpetanombre . $id . "/comprobantes/notacreditocompra/codigosbarras";
        if (!file_exists($carpeta6)) {
            mkdir($carpeta6, 0777, true);
        }
        //notadebito
        $carpeta4 = $carpetanombre . $id . "/comprobantes/notadebito";
        if (!file_exists($carpeta4)) {
            mkdir($carpeta4, 0777, true);
        }
        $carpeta5 = $carpetanombre . $id . "/comprobantes/notadebito/errores";
        if (!file_exists($carpeta5)) {
            mkdir($carpeta5, 0777, true);
        }
        $carpeta6 = $carpetanombre . $id . "/comprobantes/notadebito/codigosbarras";
        if (!file_exists($carpeta5)) {
            mkdir($carpeta5, 0777, true);
        }
        //retencioncompras
        $carpeta4 = $carpetanombre . $id . "/comprobantes/retencioncompra";
        if (!file_exists($carpeta4)) {
            mkdir($carpeta4, 0777, true);
        }
        $carpeta5 = $carpetanombre . $id . "/comprobantes/retencioncompra/errores";
        if (!file_exists($carpeta5)) {
            mkdir($carpeta5, 0777, true);
        }
        $carpeta6 = $carpetanombre . $id . "/comprobantes/retencioncompra/codigosbarras";
        if (!file_exists($carpeta6)) {
            mkdir($carpeta6, 0777, true);
        }
        //liquidacioncompra
        $carpeta4 = $carpetanombre . $id . "/comprobantes/liquidacioncompra";
        if (!file_exists($carpeta4)) {
            mkdir($carpeta4, 0777, true);
        }
        $carpeta5 = $carpetanombre . $id . "/comprobantes/liquidacioncompra/errores";
        if (!file_exists($carpeta5)) {
            mkdir($carpeta5, 0777, true);
        }
        $carpeta6 = $carpetanombre . $id . "/comprobantes/liquidacioncompra/codigosbarras";
        if (!file_exists($carpeta6)) {
            mkdir($carpeta6, 0777, true);
        }
        //archivos
        $carpeta7 = $carpetanombre . $id . "/imagen";
        if (!file_exists($carpeta7)) {
            mkdir($carpeta7, 0777, true);
        }
        $carpeta9 = $carpetanombre . $id . "/firma";
        if (!file_exists($carpeta9)) {
            mkdir($carpeta9, 0777, true);
        }
        $carpeta10 = $carpetanombre . $id . "/productos";
        if (!file_exists($carpeta10)) {
            mkdir($carpeta10, 0777, true);
        }
        $carpeta11 = $carpetanombre . $id . "/empleados";
        if (!file_exists($carpeta11)) {
            mkdir($carpeta11, 0777, true);
        }
        //proforma
        $carpeta12 = $carpetanombre . $id . "/comprobantes/proforma";
        if (!file_exists($carpeta12)) {
            mkdir($carpeta12, 0777, true);
        }
        //fincarpetas

        if ($request->file('file_imagen')) {
            $ubicacion_imagen = $carpetanombre . $id . "/imagen/";
            $request->file('file_imagen')->move($ubicacion_imagen, $nombre_imagen);
            copy($ubicacion_imagen . $nombre_imagen, storage_path('logos/') . $nombre_imagen);
            //$request->file('file_imagen')->move(storage_path('logos'), $nombre_imagen);
        }
        if ($request->file('file_p12')) {
            $ubicacion_firma = $carpetanombre . $id . "/firma/";
            $request->file('file_p12')->move($ubicacion_firma, $nombre_p12);
        }
        $establecimiento = new Establecimiento();
        $establecimiento->nombre = $request->razon_social;
        $establecimiento->codigo = "001";
        $establecimiento->urlweb = $request->pagina_web;
        $establecimiento->nombre_comercial = $request->nombre_comercial;
        $establecimiento->direccion = $request->direccion;
        $establecimiento->estado = "1";
        $establecimiento->id_empresa = $id;
        $establecimiento->save();

        $id_establecimeintos = $establecimiento->id_establecimiento;

        $emision = new Ptoemision();
        $emision->nombre = $request->razon_social;
        $emision->codigo = "001";
        $emision->secuencial_factura = $request->secuencial_factura;
        $emision->secuencial_nota_credito = $request->secuencial_nota_credito;
        $emision->secuencial_nota_debito = $request->secuencial_nota_debito;
        $emision->secuencial_guia_remision = $request->secuencial_guia_remision;
        $emision->secuencial_retencion = $request->secuencial_retencion;
        $emision->secuencial_liquidacion_compra = $request->secuencial_liquidacion_compra;
        $emision->activo = 1;
        $emision->id_empresa = $id;
        $emision->id_establecimiento = $id_establecimeintos;
        $emision->save();

        $id_punto_emision = $emision->id_punto_emision;

        $user = new Usera();
        $user->password = Hash::make($request->passusuario);
        $user->email = $request->emailusuario;
        $user->nombres = $request->nombreusuario;
        $user->apellidos = $request->apellidousuario;
        $user->estado = 1;
        $user->entrada = 1;
        $user->id_rol = 1;
        $user->id_empresa = $id;
        $user->id_establecimiento = $id_establecimeintos;
        $user->id_punto_emision = $id_punto_emision;
        $user->save();
        //CRUD valores ICE iniciales para productos
        $ice_form = new Ice_formula();
        $ice_form->codigo = "1";
        $ice_form->nombre = "no grava ICE";
        $ice_form->formula = "Vacio";
        $ice_form->id_empresa =  $id;
        $ice_form->save();

        $id_ice_formula = $ice_form->id_ice_formula;

        $ice = new Ice();
        $ice->codigo = "1";
        $ice->nombre = "No Grava ICE";
        $ice->valor = 0;
        $ice->observacion = "Opcion por defecto para productos sin ICE";
        $ice->id_ice_formula =  $id_ice_formula;
        $ice->id_empresa =  $id;
        $ice->save();

        return $id;
    }
    public function roles(Request $request)
    {
        ini_set('max_execution_time', 53200);
        $id = $request->id;
        for ($i = 0; $i < count($request->roles); $i++) {
            $rol = new Roles();
            $rol->nombre = $request->roles[$i]["nombre"];
            $rol->value = $request->roles[$i]["value"];
            $rol->ver = $request->roles[$i]["ver"];
            $rol->editar = $request->roles[$i]["editar"];
            $rol->crear = $request->roles[$i]["crear"];
            $rol->eliminar = $request->roles[$i]["eliminar"];
            $rol->tipo = $request->roles[$i]["tipo"];
            $rol->lugar = $request->roles[$i]["lugar"];
            if ($request->roles[$i]["ver"] == 1) {
                $rol->estado = 1;
            } else {
                $rol->estado = 0;
            }
            $rol->id_user = 1;
            $rol->id_empresa = $id;
            $rol->save();
        }
    }
    public function actualizarempresa(Request $request)
    {
        //  return $request->xml_factura_compra;
        ini_set('max_execution_time', 53200);
        
        $categoria=[];
        if(isset($request->categoria_producto)){
            $decode=json_decode($request->categoria_producto);
            for($z=0;$z<count($decode);$z++){
                array_push($categoria,$decode[$z]->value);
            }
            $categoria=implode(";",$categoria);
        
        }else{
            $categoria=null;   
        }
        $carpetanombre = constant("DATA_EMPRESA");
        $now = Carbon::now();
        if (!$request->recuperaimagen) {
            if ($request->file('file_imagen')) {
                $file_imagen = $request->file('file_imagen');
                $nombre_imagen = time() . $file_imagen->getClientOriginalName();
            } else {
                $nombre_imagen = "";
            }
        }

        if (!$request->recuperafirma) {
            if ($request->file('file_p12')) {
                $file_p12 = $request->file('file_p12');
                $nombre_p12 = $file_p12->getClientOriginalName();
            } else {
                $nombre_p12 = "";
            }
        }

        $id = $request->id_empresa;
        $id_user = $request->id_user;
        $id_punto_emision = $request->id_punto_emision;
        $id_establecimiento = $request->id_establecimiento;
        $empresa = Empresa::findOrFail($id);
        $empresa->periodo_empresa = $request->periodo;
        $empresa->nombre_empresa = $request->nombre_comercial;
        $empresa->razon_social = $request->razon_social;
        $empresa->ruc_empresa = $request->ruc;
        $empresa->direccion_empresa = $request->direccion;
        $empresa->telefono = $request->telefono;
        $empresa->email_empresa = $request->email_empresa;
        $empresa->nro_comprobantes = $request->nro_comprobantes;
        if ($request->password) {
            $empresa->password = $request->password;
        }
        $empresa->servidor_correo = $request->servidor_correo;
        $empresa->puerto_correo = $request->puerto_correo;
        $empresa->seguridad_correo = $request->seguridad_correo;
        $empresa->tipo_identidicacion_empresa = $request->tipo_identidicacion;
        $empresa->obligado_contabilidad = $request->contabilidad;

        $empresa->compra = $request->compra;
        $empresa->migo = $request->migo;

        $empresa->ruc_contador = $request->ruc_contador;
        $empresa->nombre_contador = $request->nombre_contador;
        $empresa->identificaion_rep = $request->identificacion_representante;
        $empresa->nomb_representante = $request->nomb_representante;
        $empresa->clave_duracion = $request->fecha_duracion;
        $empresa->periodo_inicio = $request->periodo_inicio;
        $empresa->periodo_fin = $request->periodo_fin;
        $empresa->mascara_empresa = "1";
        $empresa->recalculo = $request->recalculo;
        if ($request->numero_resolucion != "null") {
            $empresa->balance = $request->balance;
        } else {
            $empresa->balance = null;
        }
        $empresa->empresa_asociada = $request->empresa_asociada;


        if ($request->numero_resolucion != "null") {
            $empresa->noresolucion = $request->numero_resolucion;
        } else {
            $empresa->noresolucion = null;
        }
        if ($request->numero_contribuyente != "null") {
            $empresa->nocontribuyente = $request->numero_contribuyente;
        } else {
            $empresa->nocontribuyente = null;
        }
        if ($request->codigo_entidad != "null") {
            $empresa->codigo_entidad = $request->codigo_entidad;
        } else {
            $empresa->codigo_entidad = null;
        }
        $empresa->contribuyente = $request->contribuyente;
        $empresa->tipo_ctas = "Ctas Niif";
        if ($request->fecha_resolucion || $request->fecha_resolucion != '0000-00-00' || $request->fecha_resolucion != null || $request->fecha_resolucion != 'Undefined' || $request->fecha_resolucion != 'null') {
            $empresa->fresolucion = $request->fecha_resolucion;
        } else {
            $empresa->fresolucion = null;
        }
        $empresa->fcierre = $request->fecha_cierre;

        if ($request->moneda != 'null') {
            $empresa->id_moneda = $request->moneda;
        }
        if ($request->provincia != 'null') {
            $empresa->id_provincia = $request->provincia;
        }
        if ($request->ciudad != 'null') {
            $empresa->id_ciudad = $request->ciudad;
        }

        $empresa->tipo_emision = $request->tipoemision;
        $empresa->ambiente = $request->ambiente;
        $empresa->negativo = $request->negativo;

        if ($request->ctaActivo != 'null') {
            $empresa->id_plan_cuentas_activo = $request->ctaActivo;
        }
        if ($request->ctaPasivo != 'null') {
            $empresa->id_plan_cuentas_pasivo = $request->ctaPasivo;
        }
        if ($request->ctaPatrimonio != 'null') {
            $empresa->id_plan_cuentas_patrimonio = $request->ctaPatrimonio;
        }
        if ($request->ctaIngreso != 'null') {
            $empresa->id_plan_cuentas_ingreso = $request->ctaIngreso;
        }
        if ($request->ctaCosto != 'null') {
            $empresa->id_plan_cuentas_costo = $request->ctaCosto;
        }
        if ($request->ctaGasto != 'null') {
            $empresa->id_plan_cuentas_gasto = $request->ctaGasto;
        }
        if ($request->ctaOrden != 'null') {
            $empresa->id_plan_cuentas_orden = $request->ctaOrden;
        }
        if ($request->ctaResultado != 'null') {
            $empresa->id_plan_cuentas_resultado = $request->ctaResultado;
        }


        if (!$request->recuperaimagen) {
            $empresa->logo = $nombre_imagen;
        }
        if (!$request->recuperafirma) {
            $empresa->firma = $nombre_p12;
        }
        if ($request->pass_firma) {
            $empresa->pass_firma = $request->pass_firma;
        }
        $empresa->fecha_firma = $now;
        $empresa->actualizacion_firma = $now;
        $empresa->fecha_expiracion_firma = $request->fecha_expiracion_firma;
        $empresa->leyenda = $request->leyenda;
        $empresa->email_facturacion = $request->email_facturacion;
        $empresa->xml_factura_compra = filter_var($request->xml_factura_compra, FILTER_VALIDATE_BOOLEAN);
        $empresa->categoria_producto=$categoria;
        $empresa->save();

        if (!$request->recuperaimagen) {
            if ($request->file('file_imagen')) {
                $ubicacion_imagen = $carpetanombre . $id . "/imagen/";
                $request->file('file_imagen')->move($ubicacion_imagen, $nombre_imagen);
                copy($ubicacion_imagen . $nombre_imagen, storage_path('logos/') . $nombre_imagen);
            }
        }
        if (!$request->recuperafirma) {
            if ($request->file('file_p12')) {
                $ubicacion_firma = $carpetanombre . $id . "/firma/";
                $request->file('file_p12')->move($ubicacion_firma, $nombre_p12);
            }
        }

        // $establecimiento = Establecimiento::find($id_establecimiento);
        // $establecimiento->nombre = $request->razon_social;
        // $establecimiento->codigo = "001";
        // $establecimiento->urlweb = $request->pagina_web;
        // $establecimiento->nombre_comercial = $request->nombre_comercial;
        // $establecimiento->direccion = $request->direccion;
        // $establecimiento->estado = "1";
        // $establecimiento->id_empresa = $id;
        //$establecimiento->save();

        // $emision = Ptoemision::find($id_punto_emision);
        // $emision->nombre = $request->razon_social;
        // $emision->codigo = "001";
        // $emision->secuencial_factura = $request->secuencial_factura;
        // $emision->secuencial_nota_credito = $request->secuencial_nota_credito;
        // $emision->secuencial_nota_debito = $request->secuencial_nota_debito;
        // $emision->secuencial_guia_remision = $request->secuencial_guia_remision;
        // $emision->secuencial_retencion = $request->secuencial_retencion;
        // $emision->secuencial_liquidacion_compra = $request->secuencial_liquidacion_compra;
        // $emision->activo = $request->estado_punto_emision;
        // $emision->id_empresa = $id;
        // $emision->id_establecimiento = $id_establecimiento;
        //$emision->save();

        //carpetas
        $carpetaprincipal = $carpetanombre;
        if (!file_exists($carpetaprincipal)) {
            mkdir($carpetaprincipal, 0777, true);
        }
        //factura
        $carpeta = $carpetanombre . $id . "/comprobantes";
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }
        $carpeta1 = $carpetanombre . $id . "/comprobantes/factura";
        if (!file_exists($carpeta1)) {
            mkdir($carpeta1, 0777, true);
        }
        $carpeta2 = $carpetanombre . $id . "/comprobantes/factura/errores";
        if (!file_exists($carpeta2)) {
            mkdir($carpeta2, 0777, true);
        }
        $carpeta3 = $carpetanombre . $id . "/comprobantes/factura/codigosbarras";
        if (!file_exists($carpeta3)) {
            mkdir($carpeta3, 0777, true);
        }
        //guia
        $carpeta1 = $carpetanombre . $id . "/comprobantes/guia";
        if (!file_exists($carpeta1)) {
            mkdir($carpeta1, 0777, true);
        }
        $carpeta2 = $carpetanombre . $id . "/comprobantes/guia/errores";
        if (!file_exists($carpeta2)) {
            mkdir($carpeta2, 0777, true);
        }
        $carpeta3 = $carpetanombre . $id . "/comprobantes/guia/codigosbarras";
        if (!file_exists($carpeta3)) {
            mkdir($carpeta3, 0777, true);
        }
        //retencion
        $carpeta1 = $carpetanombre . $id . "/comprobantes/retencion";
        if (!file_exists($carpeta1)) {
            mkdir($carpeta1, 0777, true);
        }
        $carpeta2 = $carpetanombre . $id . "/comprobantes/retencion/errores";
        if (!file_exists($carpeta2)) {
            mkdir($carpeta2, 0777, true);
        }
        $carpeta3 = $carpetanombre . $id . "/comprobantes/retencion/codigosbarras";
        if (!file_exists($carpeta3)) {
            mkdir($carpeta3, 0777, true);
        }
        //notacredito
        $carpeta1 = $carpetanombre . $id . "/comprobantes/notacredito";
        if (!file_exists($carpeta1)) {
            mkdir($carpeta1, 0777, true);
        }
        $carpeta2 = $carpetanombre . $id . "/comprobantes/notacredito/errores";
        if (!file_exists($carpeta2)) {
            mkdir($carpeta2, 0777, true);
        }
        $carpeta3 = $carpetanombre . $id . "/comprobantes/notacredito/codigosbarras";
        if (!file_exists($carpeta3)) {
            mkdir($carpeta3, 0777, true);
        }
        //notadebito
        $carpeta1 = $carpetanombre . $id . "/comprobantes/notadebito";
        if (!file_exists($carpeta1)) {
            mkdir($carpeta1, 0777, true);
        }
        $carpeta2 = $carpetanombre . $id . "/comprobantes/notadebito/errores";
        if (!file_exists($carpeta2)) {
            mkdir($carpeta2, 0777, true);
        }
        $carpeta3 = $carpetanombre . $id . "/comprobantes/notadebito/codigosbarras";
        if (!file_exists($carpeta3)) {
            mkdir($carpeta3, 0777, true);
        }
        //liquidacion
        $carpeta1 = $carpetanombre . $id . "/comprobantes/liquidacion";
        if (!file_exists($carpeta1)) {
            mkdir($carpeta1, 0777, true);
        }
        $carpeta2 = $carpetanombre . $id . "/comprobantes/liquidacion/errores";
        if (!file_exists($carpeta2)) {
            mkdir($carpeta2, 0777, true);
        }
        $carpeta3 = $carpetanombre . $id . "/comprobantes/liquidacion/codigosbarras";
        if (!file_exists($carpeta3)) {
            mkdir($carpeta3, 0777, true);
        }
        //facturacompra
        $carpeta4 = $carpetanombre . $id . "/comprobantes/facturacompra";
        if (!file_exists($carpeta4)) {
            mkdir($carpeta4, 0777, true);
        }
        $carpeta5 = $carpetanombre . $id . "/comprobantes/facturacompra/errores";
        if (!file_exists($carpeta5)) {
            mkdir($carpeta5, 0777, true);
        }
        $carpeta6 = $carpetanombre . $id . "/comprobantes/facturacompra/codigosbarras";
        if (!file_exists($carpeta6)) {
            mkdir($carpeta6, 0777, true);
        }
        //guiacompra
        $carpeta4 = $carpetanombre . $id . "/comprobantes/guiacompra";
        if (!file_exists($carpeta4)) {
            mkdir($carpeta4, 0777, true);
        }
        $carpeta5 = $carpetanombre . $id . "/comprobantes/guiacompra/errores";
        if (!file_exists($carpeta5)) {
            mkdir($carpeta5, 0777, true);
        }
        $carpeta6 = $carpetanombre . $id . "/comprobantes/guiacompra/codigosbarras";
        if (!file_exists($carpeta6)) {
            mkdir($carpeta6, 0777, true);
        }
        //notacredito
        $carpeta4 = $carpetanombre . $id . "/comprobantes/notacreditocompra";
        if (!file_exists($carpeta4)) {
            mkdir($carpeta4, 0777, true);
        }
        $carpeta5 = $carpetanombre . $id . "/comprobantes/notacreditocompra/errores";
        if (!file_exists($carpeta5)) {
            mkdir($carpeta5, 0777, true);
        }
        $carpeta6 = $carpetanombre . $id . "/comprobantes/notacreditocompra/codigosbarras";
        if (!file_exists($carpeta6)) {
            mkdir($carpeta6, 0777, true);
        }
        //notadebito
        $carpeta4 = $carpetanombre . $id . "/comprobantes/notadebito";
        if (!file_exists($carpeta4)) {
            mkdir($carpeta4, 0777, true);
        }
        $carpeta5 = $carpetanombre . $id . "/comprobantes/notadebito/errores";
        if (!file_exists($carpeta5)) {
            mkdir($carpeta5, 0777, true);
        }
        $carpeta6 = $carpetanombre . $id . "/comprobantes/notadebito/codigosbarras";
        if (!file_exists($carpeta5)) {
            mkdir($carpeta5, 0777, true);
        }
        //retencioncompras
        $carpeta4 = $carpetanombre . $id . "/comprobantes/retencioncompra";
        if (!file_exists($carpeta4)) {
            mkdir($carpeta4, 0777, true);
        }
        $carpeta5 = $carpetanombre . $id . "/comprobantes/retencioncompra/errores";
        if (!file_exists($carpeta5)) {
            mkdir($carpeta5, 0777, true);
        }
        $carpeta6 = $carpetanombre . $id . "/comprobantes/retencioncompra/codigosbarras";
        if (!file_exists($carpeta6)) {
            mkdir($carpeta6, 0777, true);
        }
        //liquidacioncompra
        $carpeta4 = $carpetanombre . $id . "/comprobantes/liquidacioncompra";
        if (!file_exists($carpeta4)) {
            mkdir($carpeta4, 0777, true);
        }
        $carpeta5 = $carpetanombre . $id . "/comprobantes/liquidacioncompra/errores";
        if (!file_exists($carpeta5)) {
            mkdir($carpeta5, 0777, true);
        }
        $carpeta6 = $carpetanombre . $id . "/comprobantes/liquidacioncompra/codigosbarras";
        if (!file_exists($carpeta6)) {
            mkdir($carpeta6, 0777, true);
        }
        //archivos
        $carpeta7 = $carpetanombre . $id . "/imagen";
        if (!file_exists($carpeta7)) {
            mkdir($carpeta7, 0777, true);
        }
        $carpeta9 = $carpetanombre . $id . "/firma";
        if (!file_exists($carpeta9)) {
            mkdir($carpeta9, 0777, true);
        }
        $carpeta10 = $carpetanombre . $id . "/productos";
        if (!file_exists($carpeta10)) {
            mkdir($carpeta10, 0777, true);
        }
        $carpeta11 = $carpetanombre . $id . "/empleados";
        if (!file_exists($carpeta11)) {
            mkdir($carpeta11, 0777, true);
        }
        //proforma
        $carpeta12 = $carpetanombre . $id . "/comprobantes/proforma";
        if (!file_exists($carpeta12)) {
            mkdir($carpeta12, 0777, true);
        }
        //fincarpetas

        $user = Usera::findOrFail($id_user);
        if ($request->passusuario) {
            $user->password = Hash::make($request->passusuario);
        }
        $user->email = $request->emailusuario;
        $user->nombres = $request->nombreusuario;
        $user->apellidos = $request->apellidousuario;
        $user->estado = 1;
        $user->entrada = 1;
        $user->id_rol = 1;
        $user->id_empresa = $id;
        $user->id_establecimiento = $id_establecimiento;
        $user->id_punto_emision = $id_punto_emision;
        $user->save();
    }
    public function editarempresa($id)
    {
        $empresa = Empresa::select("empresa.*", "empresa.telefono as telefono_empresa", "establecimiento.codigo AS codigo_establecimiento", "establecimiento.urlweb", "punto_emision.*", "user.*")
            ->addSelect([
                'plan_cuentas_activo' => Plancuenta::select('codcta')
                    ->whereColumn('id_plan_cuentas', 'empresa.id_plan_cuentas_activo'),
                'plan_cuentas_pasivo' => Plancuenta::select('codcta')
                    ->whereColumn('id_plan_cuentas', 'empresa.id_plan_cuentas_pasivo'),
                'plan_cuentas_patrimonio' => Plancuenta::select('codcta')
                    ->whereColumn('id_plan_cuentas', 'empresa.id_plan_cuentas_patrimonio'),
                'plan_cuentas_ingreso' => Plancuenta::select('codcta')
                    ->whereColumn('id_plan_cuentas', 'empresa.id_plan_cuentas_ingreso'),
                'plan_cuentas_costo' => Plancuenta::select('codcta')
                    ->whereColumn('id_plan_cuentas', 'empresa.id_plan_cuentas_costo'),
                'plan_cuentas_gasto' => Plancuenta::select('codcta')
                    ->whereColumn('id_plan_cuentas', 'empresa.id_plan_cuentas_gasto'),
                'plan_cuentas_orden' => Plancuenta::select('codcta')
                    ->whereColumn('id_plan_cuentas', 'empresa.id_plan_cuentas_orden'),
                'plan_cuentas_resultado' => Plancuenta::select('codcta')
                    ->whereColumn('id_plan_cuentas', 'empresa.id_plan_cuentas_resultado')
            ])
            ->join("establecimiento", "establecimiento.id_empresa", "=", "empresa.id_empresa")
            ->join("punto_emision", "punto_emision.id_empresa", "=", "empresa.id_empresa")
            ->join("user", "user.id_empresa", "=", "empresa.id_empresa")
            ->where("user.id_rol", "=", 1)
            ->where("empresa.id_empresa", "=", $id)->limit(1)->get();
        $empresa[0]->telefono_empresa = explode(",", $empresa[0]->telefono_empresa);
        $empresa[0]->categoria_producto = explode(";", $empresa[0]->categoria_producto);
        return $empresa[0];
    }
    public function update(Request $request)
    {
        $id = $request->id;
        $id_establecimeinto = $request->id_establecimiento_id;
        $id_punto_emision = $request->id_punto_emision_id;

        $empresa = Empresa::find($request->id);
        $empresa->periodo_empresa = $request->periodo_empresa;
        $empresa->nombre_empresa = $request->nombre_empresa;
        $empresa->razon_social = $request->nombre_comercial;
        $empresa->ruc_empresa = $request->ruc_empresa;
        $empresa->direccion_empresa = $request->direccion_empresa;
        $empresa->telefono = $request->telefono;
        $empresa->email_empresa = $request->email_empresa;
        $empresa->password = $request->password;
        $empresa->servidor_correo = $request->servidor_correo;
        $empresa->puerto_correo = $request->puerto_correo;
        $empresa->seguridad_correo = $request->seguridad_correo;
        $empresa->tipo_identidicacion_empresa = $request->tipo_identidicacion_empresa;
        $empresa->ruc_contador = $request->ruc_contador;
        $empresa->nombre_contador = $request->nombre_contador;
        $empresa->identificaion_rep = $request->identificacion_repr;
        $empresa->nomb_representante = $request->nomb_representante;
        $empresa->clave_duracion = $request->clave_duracion;
        $empresa->periodo_inicio = $request->periodo_inicio;
        $empresa->periodo_fin = $request->periodo_fin;
        // $empresa->mascara_empresa=$request->mascara_empresa;
        $empresa->mascara_empresa = "1";
        $empresa->recalculo = $request->recalculo;
        $empresa->balance = $request->balance;
        $empresa->empresa_asociada = $request->empresa_asociada;
        $empresa->noresolucion = $request->noresolucion;
        $empresa->nocontribuyente = $request->nocontribuyente;
        $empresa->codigo_entidad = $request->codigo_entidad;
        $empresa->contribuyente = $request->contribuyente;
        $empresa->tipo_ctas = "Ctas Niif";
        //$empresa->logo=$request->logo;
        //$empresa->firma=$request->firma;
        $empresa->pass_firma = $request->pass_firma;
        //$empresa->ruta=$request->ruta;
        $empresa->fcierre = $request->fcierre;
        $empresa->fresolucion = $request->fresolucion;
        $empresa->id_moneda = $request->id_moneda;
        $empresa->id_establecimiento = $request->id_establecimiento;
        $empresa->id_provincia = $request->id_provincia;
        $empresa->id_ciudad = $request->id_ciudad;

        $empresa->id_plan_cuentas_activo = $request->cta_activo;
        $empresa->id_plan_cuentas_pasivo = $request->cta_pasivo;
        $empresa->id_plan_cuentas_patrimonio = $request->cta_patrimonio;
        $empresa->id_plan_cuentas_ingreso = $request->cta_ingreso;
        $empresa->id_plan_cuentas_costo = $request->cta_costo;
        $empresa->id_plan_cuentas_gasto = $request->cta_gasto;
        $empresa->id_plan_cuentas_orden = $request->cta_orden;
        $empresa->id_plan_cuentas_resultado = $request->cta_resultado;

        $empresa->tipo_emision = $request->tipoemision;
        $empresa->ambiente = $request->ambiente;
        $empresa->negativo = $request->negativo;
        $empresa->xml_factura_compra = filter_var($request->xml_factura_compra, FILTER_VALIDATE_BOOLEAN);
        $empresa->save();


        // $establecimiento = Establecimiento::find($id_establecimeinto);
        // $establecimiento->nombre = $request->nombre_empresa;
        // $establecimiento->urlweb = $request->pag_web;
        // $establecimiento->nombre_comercial = $request->nombre_comercial;
        // $establecimiento->direccion = $request->direccion_empresa;
        // $establecimiento->id_empresa = $id;
        //$establecimiento->save();

        // $emision = Ptoemision::find($id_punto_emision);
        // $emision->nombre = $request->nombre_empresa;
        // $emision->secuencial_factura = $request->secuencial_factura;
        // $emision->secuencial_nota_credito = $request->secuencial_nota_credito;
        // $emision->secuencial_nota_debito = $request->secuencial_nota_debito;
        // $emision->secuencial_guia_remision = $request->secuencial_guia_remision;
        // $emision->secuencial_retencion = $request->secuencial_retencion;
        // $emision->secuencial_liquidacion_compra = $request->secuencial_liquidacion_compra;
        // $emision->activo = $request->activo;
        // $emision->id_empresa = $id;
        // $emision->id_establecimiento = $id_establecimeinto;
        //$emision->save();

        $id_user = $request->id_user;

        $user = Usera::find($id_user);
        $user->email = $request->emailusuario;
        $user->nombres = $request->nombreusuario;
        $user->apellidos = $request->apellidousuario;
        $user->estado = 1;
        $user->entrada = 1;
        $user->id_rol = 1;
        $user->id_empresa = $id;
        $user->id_establecimiento = $id_establecimeinto;
        $user->id_punto_emision = $id_punto_emision;
        $user->save();

        return $id;
    }
    public function verempresa($id)
    {
        $recupera = Empresa::select("empresa.*", "e.urlweb", "pe.*", "m.nomb_moneda as nombre_moneda", "p.nombre as nombre_provincia", "c.nombre as nombre_ciudad", "eas.nombre_empresa AS nombre_empresa_asociada")
            ->join("establecimiento AS e", "e.id_empresa", "=", "empresa.id_empresa")
            ->join("punto_emision AS pe", "pe.id_empresa", "=", "empresa.id_empresa")
            ->join("moneda AS m", "m.id_moneda", "=", "empresa.id_moneda")
            ->join("provincia AS p", "p.id_provincia", "=", "empresa.id_provincia")
            ->join("ciudad AS c", "c.id_ciudad", "=", "empresa.id_ciudad")
            ->join("empresa AS eas", "eas.id_empresa", "=", "empresa.id_empresa")
            ->where("empresa.id_empresa", "=", $id)->get();

        return $recupera;
    }
    public function listarempresas()
    {
        $data = Empresa::all();
        return $data;
    }
    public function abrir($id)
    {
        $recupera = Empresa::select("empresa.*", "establecimiento.id_establecimiento AS id_establecimiento_id", "establecimiento.urlweb", "punto_emision.secuencial_factura", "punto_emision.secuencial_nota_credito", "punto_emision.id_punto_emision AS id_punto_emision_id", "punto_emision.secuencial_nota_debito", "punto_emision.secuencial_guia_remision", "punto_emision.secuencial_retencion", "punto_emision.secuencial_liquidacion_compra", "user.id as iduser", "user.nombres as nombresuser", "user.apellidos as apellidosuser", "user.email as emailuser")
            ->addSelect([
                'cuenta_resultado' => Plancuenta::select('codcta')
                    ->whereColumn('id_ctas', 'empresa.cta_resultado'), 'cuenta_ingreso' => Plancuenta::select('codcta')
                    ->whereColumn('id_ctas', 'empresa.cta_ingreso'),
                'cuenta_costo' => Plancuenta::select('codcta')
                    ->whereColumn('id_ctas', 'empresa.cta_costo'),
                'cuenta_activo' => Plancuenta::select('codcta')
                    ->whereColumn('id_ctas', 'empresa.cta_activo'),
                'cuenta_pasivo' => Plancuenta::select('codcta')
                    ->whereColumn('id_ctas', 'empresa.cta_pasivo'),
                'cuenta_patrimonio' => Plancuenta::select('codcta')
                    ->whereColumn('id_ctas', 'empresa.cta_patrimonio'),
                'cuenta_gasto' => Plancuenta::select('codcta')
                    ->whereColumn('id_ctas', 'empresa.cta_gasto'),
                'cuenta_orden' => Plancuenta::select('codcta')
                    ->whereColumn('id_ctas', 'empresa.cta_orden'),
            ])
            ->join("establecimiento", "establecimiento.id_empresa", "=", "empresa.id_empresa")
            ->join("punto_emision", "punto_emision.id_establecimiento", "=", "establecimiento.id_establecimiento")
            ->join("user", "user.id_empresa", "=", "empresa.id_empresa")
            ->where("establecimiento.id_empresa", "=", $id)
            ->where("user.entrada", "=", 1)
            ->where('empresa.id_empresa', '=', $id)->limit("1")->get();

        return $recupera;
    }
    public function obtenerEmpresa($id_empresa)
    {
        return Empresa::find($id_empresa);
    }
    public function eliminar($id)
    {
        Empresa::destroy($id);
    }
    public function getMoneda()
    {
        $data = Moneda::all();
        return $data;
    }
    public function getProvincia()
    {
        $data = Provincia::all();
        return $data;
    }
    public function getCiudad(Request $request)
    {
        $id = $request->id;
        $data = Ciudad::where('id_provincia', '=', $request->id_provincia)->get();
        return response()->json($data);
    }
    //llamado de tablas adicionales
    public function adicionales()
    {
        $monedas = DB::select("SELECT * FROM moneda");
        $provincias = DB::select("SELECT * FROM provincia");
        $empresas = DB::select("SELECT * FROM empresa");
        return [
            "monedas" => $monedas,
            "provincias" => $provincias,
            "empresas" => $empresas
        ];
    }
    //listado de ciudades
    public function ciudades(Request $request)
    {
        $ciudades = DB::select("SELECT * FROM ciudad WHERE id_provincia =" . $request->id);
        return $ciudades;
    }
    public function getEmpresas()
    {
        $data = Empresa::all();
        return $data;
    }
    public function empresaroles()
    {
        $data = \Illuminate\Support\Facades\File::get(storage_path("roles.json"));
        $products = json_decode($data, true);
        return $products;
    }
    public function empresarolesid($id)
    {
        $dat = DB::select("SELECT * FROM roles WHERE id_empresa = $id");
        if (count($dat) < 5) {
            $data = \Illuminate\Support\Facades\File::get(storage_path("roles.json"));
            $products = json_decode($data, true);
            return $products;
        }
        return $dat;
    }
    public function rolesid(Request $request)
    {
        $id = $request->id;
        DB::delete("DELETE FROM roles WHERE id_empresa = $id");

        $dataus = EMPRESA::select("*")->join("user", "empresa.id_empresa", "=", "user.id_empresa")->where("empresa.id_empresa", "=", $id)->get();
        if (count($dataus) >= 1) {
            for ($g = 0; $g < count($dataus); $g++) {
                $id_user = $dataus[$g]["id"];
                $data_roles_us = ROLES::select("*")->where("id_user", "=", $id_user)->get();
                if (count($data_roles_us) >= 1) {
                    for ($h = 0; $h < count($data_roles_us); $h++) {
                        for ($i = 0; $i < count($request->roles); $i++) {
                            if ($data_roles_us[$h]["value"] == $request->roles[$i]["value"]) {
                                if ($request->roles[$i]["ver"] != 1) {
                                    $id_rol = $data_roles_us[$h]["id_roles"];
                                    DB::delete("DELETE FROM roles WHERE id_roles = $id_rol");
                                }
                            }
                        }
                    }
                }
            }
        }

        for ($i = 0; $i < count($request->roles); $i++) {
            $rol = new Roles();
            $rol->nombre = $request->roles[$i]["nombre"];
            $rol->value = $request->roles[$i]["value"];
            $rol->ver = $request->roles[$i]["ver"];
            $rol->editar = $request->roles[$i]["editar"];
            $rol->crear = $request->roles[$i]["crear"];
            $rol->eliminar = $request->roles[$i]["eliminar"];
            $rol->tipo = $request->roles[$i]["tipo"];
            $rol->lugar = $request->roles[$i]["lugar"];
            if ($request->roles[$i]["ver"] == 1) {
                $rol->estado = 1;
            } else {
                $rol->estado = 0;
            }
            $rol->id_user = 1;
            $rol->id_empresa = $id;
            $rol->save();
        }
    }
    public function todosroot()
    {
        /*$res = DB::select("SELECT *, concat_ws(' ', nombres, apellidos) AS nombre_empresa FROM user us WHERE us.id_rol = 3 LIMIT 1");
        $res1 = DB::select("SELECT * FROM user us INNER JOIN empresa em ON us.id_empresa = em.id_empresa AND us.id_rol = 1");
        $todos = array_merge($res, $res1);
        return $todos;*/

        $res = DB::select("SELECT *, concat_ws(' ', nombres, apellidos) AS nombre_empresa FROM user us WHERE us.id_rol = 3 LIMIT 1");
        $emp = DB::select("SELECT * FROM user us INNER JOIN empresa em ON us.id_empresa = em.id_empresa AND us.id_rol = 1 ORDER BY em.id_empresa ASC");
        $us = DB::select("SELECT min(id) as id_usuario, id_empresa FROM user us WHERE us.id_rol = 1 GROUP BY id_empresa ORDER BY id_empresa ASC");
        $empresas = [];
        for ($i = 0; $i < count($emp); $i++) {
            for ($f = 0; $f < count($us); $f++) {
                if ($emp[$i]->id === $us[$f]->id_usuario) {
                    array_push($empresas, $emp[$i]);
                }
            }
        }
        $todos = array_merge($res, $empresas);
        return $todos;
    }
    public function listadoroot(Request $request)
    {
        $id = $request->id;
        $dato = Usera::select()->where("id", "=", $id)->get();
        $dato[0]->root = true;
        $roles = Roles::select()->where("id_user", "=", $id)->get();
        if (count($roles) < 10) {
            $data = \Illuminate\Support\Facades\File::get(storage_path("roles.json"));
            $products = json_decode($data, true);
            return [
                "datos" => $dato[0],
                "roles" => $products,
            ];
        }
        return [
            "datos" => $dato[0],
            "roles" => $roles,
        ];
    }
    function logoempresa($id, $imagen)
    {
        $filePath = constant("DATA_EMPRESA") . "$id/imagen/$imagen";
        //return File::get($filePath);
        $file = \File::get($filePath);
        $type = \File::mimeType($filePath);
        $response = \Response::make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
    }
    function firma_expiracion($id)
    {
        $empresa = Empresa::find($id);
        return $empresa;
    }
    function change_state_empresa(Request $request)
    {
        $empresa = Empresa::find($request->id);
        if ($request->estado == true) {
            $empresa->estado = 1;
        } else if ($request->estado == false) {
            $empresa->estado = 0;
        }
        $empresa->save();
    }
}
