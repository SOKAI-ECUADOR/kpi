<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Modulos;

class ModulosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

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
        //
        for ($d = 0; $d < count($request->checkBox1); $d++) {
            $modulo = new Modulos();
            $modulo->descripcion = $request->checkBox1[$d]["label"];
            $modulo->crear = $request->checkBox1[$d]["ver"];
            $modulo->modulo = $request->checkBox1[$d]["value"];
            $modulo->editar = $request->checkBox1[$d]["escribir"];
            $modulo->eliminar = $request->checkBox1[$d]["crear"];
            //$modulo->id_user = $request->checkBox1[$d]["iduser"];
            $modulo->save();
        }
        /* $request->merge([
            'modulo' => implode(',', (array) $request->get('checkBox1')),
           // 'descripcion' => implode(',', (array) $request->get('checkBox1')),
           // 'icono' => implode(',', (array) $request->get('checkBox1')),
            //'submenu' => implode(',', (array) $request->get('checkBox1')),
            //'id_user' => implode(',', (array) $request->get('iduser')),

        ]);

        Modulos::create($request->all());*/
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ver($id)
    {
        $modulos = DB::select("SELECT * FROM modulos WHERE id_user = $id");
        return $modulos;
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
        //
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
