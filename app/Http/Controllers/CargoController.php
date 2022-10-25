<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Departamento;
use App\Models\AreaTrabajo;
use App\Models\Cargo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CargoController extends Controller
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
            $recupera = Cargo::select("*")->addSelect([
                'nomb_area' => AreaTrabajo::select('are_nombre')
                    ->whereColumn('id_area', 'cargo.id_area')])->where("id_empresa", "=", $id)->orderByRaw('id_cargo DESC')->get();
        } else {
            $recupera = Cargo::select('*')->addSelect([
                'nomb_area' => AreaTrabajo::select('are_nombre')
                    ->whereColumn('id_area', 'cargo.id_area')])
                ->where(function ($q) use ($buscar) {
                    $q->where('car_nombre', 'like', '%' . $buscar . '%')
                      ->orWhere('car_estado', 'like', '%' . $buscar . '%');
                })
                ->where("id_empresa", "=", $id)
                ->orderByRaw('id_cargo DESC')->get();
        }
        return ['recupera' => $recupera];
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
        $cargo = new Cargo();
        $cargo->car_nombre = $request->car_nombre;
        $cargo->car_sueldo = $request->car_sueldo;
        $cargo->car_estado = "Activo";
        $cargo->id_area = $request->id_area;
        $cargo->id_empresa = $request->id_empresa;
        $cargo->save();
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
        $cargo = Cargo::find($request->id);
        $cargo->car_nombre = $request->car_nombre;
        $cargo->car_sueldo = $request->car_sueldo;
        $cargo->car_estado = $request->car_estado;
        $cargo->id_area = $request->id_area;
        $cargo->id_empresa = $request->id_empresa;
        $cargo->save();
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
        Cargo::destroy($id);
    }

    public function getDepartamento()
    {
        $data = Departamento::get();

        return response()->json($data);
    }

    public function getArea(Request $request){
        $data=AreaTrabajo::where('id_area','=', $request->id_area)->get();
        //$data=Cuidad::get();
        return response()->json($data);
    }

    public function verArea(Request $request)
    {
        $id = $request->id;
        $cargo = DB::select("SELECT * FROM cargo WHERE id_cargo =" . 1);
        return $cargo;
    }
}
