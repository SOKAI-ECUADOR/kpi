<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facturahasproducto;

class FacturahasproductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $facturahasproducto = Facturahasproducto::all();
        return $facturahasproducto;
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
        $facturahasproducto = new Facturahasproducto();
        $facturahasproducto->cantidad= $request->cantidad;
        $facturahasproducto->nombre= $request->nombre;
        $facturahasproducto->codigoProducto= $request->codigoProducto;
        $facturahasproducto->precioUnitario= $request->precioUnitario;
        $facturahasproducto->descuento= $request->descuento;
        $facturahasproducto->valorTotal= $request->valorTotal;
        $facturahasproducto->save();
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
        $facturahasproducto = Facturahasproducto::findOrFail($request->id);
        $facturahasproducto->cantidad= $request->cantidad;
        $facturahasproducto->nombre= $request->nombre;
        $facturahasproducto->codigoProducto= $request->codigoProducto;
        $facturahasproducto->precioUnitario= $request->precioUnitario;
        $facturahasproducto->descuento= $request->descuento;
        $facturahasproducto->valorTotal= $request->valorTotal;
        $facturahasproducto->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
