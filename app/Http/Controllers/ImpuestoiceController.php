<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Impuestoice;

class ImpuestoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $impuestoice = Impuestoice::all();
        return $impuestoice;
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
        $impuestoice = new Impuestoice();
        $impuestoice->codigoPorcentaje= $request->codigoPorcentaje;
        $impuestoice->nombre= $request->nombre;
        $impuestoice->save();
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
        $impuestoice = Impuestoice::findOrFail($request->id);
        $impuestoice->codigoPorcentaje= $request->codigoPorcentaje;
        $impuestoice->nombre= $request->nombre;
        $impuestoice->save();
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
