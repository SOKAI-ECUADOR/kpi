<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Impuestocomprobanteretencion;

class ImpuestocomprobanteretencionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $impuestocomprobanteretencion = Impuestocomprobanteretencion::all();
        return $impuestocomprobanteretencion;
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
       $impuestocomprobanteretencion = new Impuestocomprobanteretencion();
       $impuestocomprobanteretencion->codigo= $request->codigo;
       $impuestocomprobanteretencion->codigoRetencion= $request->codigoRetencion;
       $impuestocomprobanteretencion->baseImponible= $request->baseImponible;
       $impuestocomprobanteretencion->porcentajeRetener= $request->porcentajeRetener;
       $impuestocomprobanteretencion->valorRetenido= $request->valorRetenido;
       $impuestocomprobanteretencion->codDocSustento= $request->codDocSustento;
       $impuestocomprobanteretencion->numDocSustento= $request->numDocSustento;
       $impuestocomprobanteretencion->fechaEmisionDocSustento= $request->fechaEmisionDocSustento;
       $impuestocomprobanteretencion->save();
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
       $impuestocomprobanteretencion = Impuestocomprobanteretencion::findOrFail($request->id);
       $impuestocomprobanteretencion->codigo= $request->codigo;
       $impuestocomprobanteretencion->codigoRetencion= $request->codigoRetencion;
       $impuestocomprobanteretencion->baseImponible= $request->baseImponible;
       $impuestocomprobanteretencion->porcentajeRetener= $request->porcentajeRetener;
       $impuestocomprobanteretencion->valorRetenido= $request->valorRetenido;
       $impuestocomprobanteretencion->codDocSustento= $request->codDocSustento;
       $impuestocomprobanteretencion->numDocSustento= $request->numDocSustento;
       $impuestocomprobanteretencion->fechaEmisionDocSustento= $request->fechaEmisionDocSustento;
       $impuestocomprobanteretencion->save();
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
