<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Departamento;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
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
            $recupera = Departamento::select("*")->where("id_empresa", "=", $id)->orderByRaw('id_departamento DESC')->get();
        } else {
            $recupera = Departamento::select('*')
                ->where(function ($q) use ($buscar) {
                    $q->where('dep_nombre', 'like', '%' . $buscar . '%')
                        ->orWhere('dep_estado', 'like', '%' . $buscar . '%');
                })
                ->where("id_empresa", "=", $id)
                ->orderByRaw('id_departamento DESC')->get();
        }
        return ['recupera' => $recupera];
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $departamento = new Departamento();
        $departamento->dep_nombre = $request->dep_nombre;
        $departamento->dep_estado = "Activo";
        $departamento->id_empresa = $request->id_empresa;
        $departamento->save();
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
        $departamento = Departamento::find($request->id);
        $departamento->dep_nombre = $request->dep_nombre;
        $departamento->dep_estado = $request->dep_estado;
        $departamento->id_empresa = $request->id_empresa;
        $departamento->save();
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
        Departamento::destroy($id);
    }
    public function derpemp(Request $request)
    {
        $departamento = DB::select("SELECT i.*,i.id_empresa,d.dep_nombre 
        from ingresos_egresos i
        inner join departamento d
        on i.id_departamento=d.id_departamento
        where i.id_empresa=1;");
        return $departamento;
    }
    public function verDepartamento(Request $request)
    {
        $id = $request->id;
        $departamento = DB::select("SELECT * FROM departamento WHERE id_departamento =" . $id);
        return $departamento;
    }
}
