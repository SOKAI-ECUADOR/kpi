<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notacreditohasproducto;

class NotacreditohasproductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notacreditohasproducto = Notacreditohasproducto::all();
        return $notacreditohasproducto;
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
        $notacreditohasproducto = new Notacreditohasproducto();
        $notacreditohasproducto->cantidad= $request->cantidad;
        $notacreditohasproducto->nombre= $request->nombre;
        $notacreditohasproducto->codigoProducto= $request->codigoProducto;
        $notacreditohasproducto->precioUnitario= $request->precioUnitario;
        $notacreditohasproducto->descuento= $request->descuento;
        $notacreditohasproducto->valorTotal= $request->valorTotal;
        $notacreditohasproducto->save();
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
        $notacreditohasproducto = Notacreditohasproducto::findOrFail($request->id);
        $notacreditohasproducto->cantidad= $request->cantidad;
        $notacreditohasproducto->nombre= $request->nombre;
        $notacreditohasproducto->codigoProducto= $request->codigoProducto;
        $notacreditohasproducto->precioUnitario= $request->precioUnitario;
        $notacreditohasproducto->descuento= $request->descuento;
        $notacreditohasproducto->valorTotal= $request->valorTotal;
        $notacreditohasproducto->save();
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
