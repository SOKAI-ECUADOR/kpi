<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Provincia;
use App\Models\Ciudad;
use App\Models\Parroquia;
use App\Models\Grupocliente;
use App\Models\Vendedorcliente;
use App\Models\Tipocliente;
use App\Models\FormaDePagos;
use App\Models\Codigopais;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        //$buscar = $request->buscar;
        $buscar = str_replace(array(" "), "%", $request->buscar);
        if ($buscar == '') {
            $recupera = Cliente::select("*")->where("id_empresa", "=", $id)->orderByRaw('id_cliente', 'desc')->get();
        } else {
            $recupera = Cliente::select("*")
                ->where(function ($q) use ($buscar) {
                    $q->where('nombre', 'like', '%' . $buscar . '%')
                        ->orWhere('codigo', 'like', '%' . $buscar . '%')
                        ->orWhere('identificacion', 'like', '%' . $buscar . '%')
                        ->orWhere('estado', 'like', '%' . $buscar . '%');
                })
                ->where("id_empresa", "=", $id)
                ->orderByRaw('id_cliente', 'desc')
                ->get();
        }
        if (session()->get('usuariosesion')['filtro_list'] == 1) {
            $dat = [];
            for ($i = 0; $i < count($recupera); $i++) {
                if ($recupera[$i]->created_by == session()->get('usuariosesion')['id'] || $recupera[$i]->updated_by == session()->get('usuariosesion')['id'] || $recupera[$i]->id_vendedor == session()->get('usuariosesion')['id_vendedor']) {
                    array_push($dat, $recupera[$i]);
                }
            }
            $recupera = $dat;
        }
        return [
            'recupera' => $recupera
        ];
    }
    public function verificarcliente($id)
    {
        $cod = DB::select("SELECT codigo FROM cliente  WHERE id_empresa = $id ORDER BY  id_cliente DESC LIMIT 1;");
        $principal = "";
        if (count($cod) >= 1) {
            $tot = $cod[0]->codigo + 1;
            $principal = $tot;
        } else {
            $principal = 1;
        }
        return $principal;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->ruc_ci) {
            if ($request->tipo_identificacion != "Consumidor Final") {
                $sel = DB::select("SELECT `identificacion` FROM `cliente` WHERE `identificacion` = '" . $request->ruc_ci . "' AND id_empresa =" . $request->empresa);
                if ($sel) {
                    return "error_identificacion";
                }
            }
        }
        $pais = null;
        if ($request->codigopais) {
            $id_pais = DB::select("SELECT id_codigo_pais from codigo_pais where codigo_ISO_alpha_2='{$request->codigopais}'");
            $pais = $id_pais[0]->id_codigo_pais;
        }

        $cod = DB::select("SELECT codigo FROM cliente  WHERE id_empresa = $request->empresa ORDER BY  id_cliente DESC LIMIT 1;");
        $principal = "";
        if (count($cod) >= 1) {
            $tot = $cod[0]->codigo + 1;
            $principal = $tot;
        } else {
            $principal = 1;
        }
        //dd($pais);
        $cliente = new Cliente();
        $cliente->codigo = $principal;
        $cliente->nombre = $request->nombre;
        $cliente->nombre_adicional = $request->nombre_adicional;
        $cliente->tipo_identificacion = $request->tipo_identificacion;
        $cliente->identificacion = $request->ruc_ci;
        $cliente->direccion = $request->direccion;
        $emails = implode(";", $request->email);
        $cliente->email = $emails;
        $cliente->telefono = $request->telefono;
        $cliente->contacto = $request->contacto;
        $cliente->estado = $request->estado;
        $cliente->id_plan_cuentas = $request->id_plan_cuentas;
        $cliente->comentario = $request->comentario;
        $cliente->descuento = $request->descuento;
        $cliente->num_pago = $request->num_pago;
        $cliente->id_grupo_cliente = $request->grupo_cliente;
        $cliente->id_tipo_cliente = $request->tipo_cliente;
        $cliente->grupo_tributario = $request->grupo_tributario;
        $cliente->id_cuidad = $request->canton;
        $cliente->id_parroquia = $request->parroquia;
        $cliente->id_provincia = $request->provincia;
        $cliente->parte_relacionada = $request->radios1;
        $cliente->obligado_contabilidad = $request->radios2;
        $cliente->id_vendedor = $request->vendedor;
        $cliente->lista_precios = $request->lista_precios;
        $cliente->limite_credito = $request->limite_credito;
        $cliente->regimen_contribuyente = $request->regimen_contribuyente;
        $cliente->id_forma_pagos = $request->forma_pago;
        $cliente->id_codigo_pais = $pais;
        $cliente->id_empresa = $request->empresa;
        $cliente->id_seguro = $request->id_seguro;
        $cliente->id_plan_seguro = $request->id_plan_seguro;
        $cliente->created_by = session()->get('usuariosesion')['id'];
        $cliente->updated_by = session()->get('usuariosesion')['id'];
        $cliente->save();
        $id = $cliente->id_cliente;
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
        if ($request->ruc_ci) {
            if ($request->tipo_identificacion != "Consumidor Final") {
                $sel = DB::select("SELECT `identificacion` FROM `cliente` WHERE `identificacion` = '" . $request->ruc_ci . "' AND id_empresa = '" . $request->empresa . "' AND id_cliente !=" . $request->id);
                if ($sel) {
                    return "error_identificacion";
                }
            }
        }
        $pais = null;
        if ($request->codigopais) {
            $id_pais = DB::select("SELECT id_codigo_pais from codigo_pais where codigo_ISO_alpha_2='{$request->codigopais}'");
            $pais = $id_pais[0]->id_codigo_pais;
        }
        $cliente = Cliente::find($request->id);
        $cliente->codigo = $request->codigo;
        $cliente->nombre = $request->nombre;
        $cliente->nombre_adicional = $request->nombre_adicional;
        $cliente->tipo_identificacion = $request->tipo_identificacion;
        $cliente->identificacion = $request->ruc_ci;
        $cliente->direccion = $request->direccion;
        $emails = implode(";", $request->email);
        $cliente->email = $emails;
        $cliente->telefono = $request->telefono;
        $cliente->contacto = $request->contacto;
        $cliente->estado = $request->estado;
        $cliente->id_plan_cuentas = $request->id_plan_cuentas;
        $cliente->comentario = $request->comentario;
        $cliente->descuento = $request->descuento;
        $cliente->num_pago = $request->num_pago;
        $cliente->id_grupo_cliente = $request->grupo_cliente;
        $cliente->id_tipo_cliente = $request->tipo_cliente;
        $cliente->grupo_tributario = $request->grupo_tributario;
        $cliente->id_cuidad = $request->canton;
        $cliente->id_parroquia = $request->parroquia;
        $cliente->id_provincia = $request->provincia;
        $cliente->parte_relacionada = $request->radios1;
        $cliente->obligado_contabilidad = $request->radios2;
        $cliente->id_vendedor = $request->vendedor;
        $cliente->lista_precios = $request->lista_precios;
        $cliente->limite_credito = $request->limite_credito;
        $cliente->regimen_contribuyente = $request->regimen_contribuyente;
        $cliente->id_forma_pagos = $request->forma_pago;
        $cliente->id_codigo_pais = $pais;
        $cliente->id_empresa = $request->empresa;
        $cliente->id_seguro = $request->id_seguro;
        $cliente->id_plan_seguro = $request->id_plan_seguro;
        $cliente->updated_by = session()->get('usuariosesion')['id'];
        $cliente->save();
        return $cliente->id_cliente;
    }
    public function vercliente($id)
    {
        $cliente = Cliente::select("cliente.*", "plan_cuentas.nomcta as plan_cuentas", "codigo_pais.codigo_ISO_alpha_2 as pais")
            ->leftjoin("plan_cuentas", "plan_cuentas.id_plan_cuentas", "=", "cliente.id_plan_cuentas")
            ->leftjoin("codigo_pais", "codigo_pais.id_codigo_pais", "=", "cliente.id_codigo_pais")
            ->where("id_cliente", '=', $id)->get();
        $emails_prov = DB::select("SELECT email from cliente where id_cliente=" . $id);
        $email = explode(";", $emails_prov[0]->email);
        return [
            "cliente" => $cliente[0],
            'emails' => $email
        ];
    }
    public function traercliente($id)
    {
        $cliente = DB::select("SELECT * FROM cliente WHERE id_cliente =" . $id);
        return $cliente;
    }
    public function getProvincia()
    {
        $data = Provincia::get();

        return response()->json($data);
    }
    public function getCiudad(Request $request)
    {
        $data = Ciudad::where('id_provincia', '=', $request->id_provincia)->get();
        //$data=Cuidad::get();
        return response()->json($data);
    }
    public function getParroquia(Request $request)
    {
        $data = Parroquia::where('codigo_ciudad', '=', $request->id_ciudad)->get();
        //$data=Cuidad::get();
        return response()->json($data);
    }
    public function getGrupoClientes($id)
    {
        $data = Grupocliente::select("*")->where("id_empresa", "=", $id)->get();
        return response()->json($data);
    }
    public function getGrupoVendedor($id)
    {
        $data = Vendedorcliente::select("*")->where("id_empresa", "=", $id)->get();
        return response()->json($data);
    }

    public function getFormaPagos($id)
    {
        $data = FormaDePagos::select("*")->where("id_empresa", "=", $id)->get();
        return response()->json($data);
    }
    public function getTipoCliente($id)
    {
        $data = Tipocliente::select("*")->where("id_empresa", "=", $id)->get();
        return response()->json($data);
    }
    public function CodigoPais()
    {

        $tipo = Codigopais::all();
        return $tipo;
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Cliente::destroy($id);
    }
    public function selectclient(Request $request)
    {
        $buscar = str_replace(array(" "), "%", $request->buscar);
        if ($buscar == '') {
            $recupera = Cliente::select("id_cliente", "nombre")->where("id_empresa", "=", $request->empresa)->get();
        } else {
            $recupera = Cliente::select("id_cliente", "nombre")
                ->where(function ($q) use ($buscar) {
                    $q->where('nombre', 'like', '%' . $buscar . '%')
                        ->orWhere('codigo', 'like', '%' . $buscar . '%')
                        ->orWhere('identificacion', 'like', '%' . $buscar . '%');
                })
                ->where("id_empresa", "=", $request->empresa)
                ->take(10)->get();
        }
        return $recupera;
    }
}
