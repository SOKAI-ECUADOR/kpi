<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Ptoemision;
use App\Models\Establecimiento;

class PtoemisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        $buscar = $request->buscar;
        if ($buscar == '') {
            $recupera = Ptoemision::select("punto_emision.*", "empresa.nombre_empresa")
                ->join("empresa", "empresa.id_empresa", "=", "punto_emision.id_empresa")
                ->where("punto_emision.id_empresa", "=", $id)->get();
        } else {
            $recupera = Ptoemision::select("punto_emision.*", "empresa.nombre_empresa")
                ->join("empresa", "empresa.id_empresa", "=", "punto_emision.id_empresa")
                ->where(function ($q) use ($buscar) {
                    $q->where('punto_emision.nombre', 'like', '%' . $buscar . '%')
                        ->orWhere('punto_emision.codigo', 'like', '%' . $buscar . '%');
                })
                ->where("punto_emision.id_empresa", "=", $id)->get();
        }
        return [
            'recupera' => $recupera
        ];
    }

    public function todo(Request $request, $id)
    {
        $ide = $request->id;
        $recupera = [];
        if ($ide == 0) {
            $recupera[0] = array("id_punto_emision" => 0, "nombre" => "Admin");
        } else {
            $recupera = Ptoemision::select("*")->where("id_empresa", "=", $id)->where('id_establecimiento', '=', $ide)->get();
        }


        return $recupera;
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
        $punto = new Ptoemision();
        $punto->nombre = $request->nombre;
        $punto->codigo = $request->codigo;
        $punto->secuencial_factura = $request->secuencial_factura;
        $punto->secuencial_nota_credito = $request->secuencial_nota_credito;
        $punto->secuencial_nota_debito = $request->secuencial_nota_debito;
        $punto->secuencial_guia_remision = $request->secuencial_guia;
        $punto->secuencial_retencion = $request->secuencial_retencion;
        $punto->secuencial_liquidacion_compra = $request->secuencial_l;
        $punto->secuencial_nota_venta = $request->secuencial_nota_venta;
        $punto->secuencial_recibo = $request->secuencial_recibo;
        $punto->activo = $request->radios1;
        $punto->id_establecimiento = $request->establecimientoe;
        $punto->id_empresa = $request->id_empresa;
        $punto->id_bodega = $request->id_bodega;
        //$punto->ucrea = $request->ucrea;
        $punto->save();
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
    public function actualizar(Request $request)
    {
        //
        $punto = Ptoemision::findOrFail($request->id);
        $punto->nombre = $request->nombre;
        $punto->codigo = $request->codigo;
        $punto->secuencial_factura = $request->secuencial_factura;
        $punto->secuencial_nota_credito = $request->secuencial_nota_credito;
        $punto->secuencial_nota_debito = $request->secuencial_nota_debito;
        $punto->secuencial_guia_remision = $request->secuencial_guia;
        $punto->secuencial_retencion = $request->secuencial_retencion;
        $punto->secuencial_liquidacion_compra = $request->secuencial_l;
        $punto->secuencial_nota_venta = $request->secuencial_nota_venta;
        $punto->secuencial_recibo = $request->secuencial_recibo;
        $punto->activo = $request->radios1;
        $punto->id_establecimiento = $request->establecimientoe;
        $punto->id_empresa = $request->id_empresa;
        $punto->id_bodega = $request->id_bodega;
        //$punto->umodifica = $request->umodifica;
        $punto->save();
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
        //
    }

    public function abrire($id)
    {
        $res = Establecimiento::select('*')->where("id_empresa", '=', $id)->get();
        return $res;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function eliminar($id)
    {
        //
        Ptoemision::destroy($id);
    }
    public function abrir(Request $request)
    {
        $id = $request->id;
        $recupera = DB::select('SELECT * FROM punto_emision WHERE id_punto_emision =' . $id);
        return [
            "recupera" => $recupera,

        ];
    }
}
