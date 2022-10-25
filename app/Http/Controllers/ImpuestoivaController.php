<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Impuestoiva;

class ImpuestoivaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $impuestoiva = Impuestoiva::all();
        return $impuestoiva;
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
        $impuestoiva = new Impuestoiva();
        $impuestoiva->codigoPorcentaje= $request->codigoPorcentaje;
        $impuestoiva->nombre= $request->nombre;
        $impuestoiva->tarifa= $request->tarifa;
        $impuestoiva->save();
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
        $impuestoiva = Impuestoiva::findOrFail($request->id);
        $impuestoiva->codigoPorcentaje= $request->codigoPorcentaje;
        $impuestoiva->nombre= $request->nombre;
        $impuestoiva->tarifa= $request->tarifa;
        $impuestoiva->save();
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
