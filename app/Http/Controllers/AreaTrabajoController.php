<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Departamento;
use App\Models\AreaTrabajo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AreaTrabajoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$id)
    {
        $buscar = $request->buscar;
        if ($buscar == '') {
            $recupera = AreaTrabajo::select("*")->addSelect([
                'nomb_departamento' => Departamento::select('dep_nombre')
                    ->whereColumn('id_departamento', 'area_trabajo.id_departamento')])->where("id_empresa", "=", $id)->orderByRaw('id_area DESC')->get();
        } else {
            $recupera = AreaTrabajo::select('*')->addSelect([
                'nomb_departamento' => Departamento::select('dep_nombre')
                    ->whereColumn('id_departamento', 'area_trabajo.id_departamento')])
                ->where(function ($q) use ($buscar) {
                    $q->where('are_nombre', 'like', '%' . $buscar . '%')
                    ->orWhere('are_estado', 'like', '%' . $buscar . '%');
                })
                ->where("id_empresa", "=", $id)
                ->orderByRaw('id_area DESC')->get();
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
        $area = new AreaTrabajo();
        $area->are_nombre = $request->are_nombre;
        $area->are_estado = "Activo";
        $area->id_departamento = $request->iddepa;
        $area->id_empresa = $request->id_empresa;
        $area->save();
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $area = AreaTrabajo::find($request->id);
        $area->are_nombre = $request->are_nombre;
        $area->are_estado = $request->are_estado;
        $area->id_departamento = $request->iddepa;
        $area->id_empresa = $request->id_empresa;
        $area->save();
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
        AreaTrabajo::destroy($id);
    }

    public function getDepartamento()
    {
        $data = Departamento::get();

        return response()->json($data);
    }

    public function verArea(Request $request)
    {
        $id = $request->id;
        $area = DB::select("SELECT * FROM area_trabajo WHERE id_area =" . $id);
        return $area;
    }
}
