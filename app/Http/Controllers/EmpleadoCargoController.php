<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Empleado_Cargo;
use App\Models\Empleado;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Departamento;
use App\Models\AreaTrabajo;
use App\Models\Cargo;
use App\Models\GrupoOcupacional;

class EmpleadoCargoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $cantidadp = $request->cantidadp;
        if ($cantidadp < 1) {
            $cantidadp = 10;
        }
        if ($buscar == '') {
            // $recupera = Empleado::paginate($cantidadp);
            $recupera = Empleado_Cargo::orderByRaw('id_emp_cargo DESC')->paginate($cantidadp);
        } else {
            $recupera = Empleado_Cargo::select('*')
                ->where('primer_nombre', 'like', '%' . $buscar . '%')
                ->orWhere('apellido_paterno', 'like', '%' . $buscar . '%')
                ->orWhere('dni', 'like', '%' . $buscar . '%')
                ->orWhere('dni', 'like', '%' . $buscar . '%')
                ->orWhere('dni', 'like', '%' . $buscar . '%')
                ->orderByRaw('id_emp_cargo DESC')->paginate($cantidadp);
            /*$recupera = Empleado::where($criterio, 'like', '%' . $buscar . '%')
                ->orderByRaw('id', 'desc')
                ->paginate($cantidadp);*/
        }
        return [
            'pagination' => [
                'total'        => $recupera->total(),
                'current_page' => $recupera->currentPage(),
                'per_page'     => $recupera->perPage(),
                'last_page'    => $recupera->lastPage(),
                'from'         => $recupera->firstItem(),
                'to'           => $recupera->lastItem(),
                'count'        => round($recupera->total() / $cantidadp),
            ],
            'recupera' => $recupera
        ];
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
        $cargo = new Empleado_Cargo();
        $cargo->fecha_ingreso = $request->fecha_ingreso;
        $cargo->fecha_salida = $request->fecha_salida;
        $cargo->tipo_horario = $request->tipo_horario;
        $cargo->tipo_contrato = $request->tipo_contrato;
        $cargo->bonos = $request->bonos;
        $cargo->aporte_iess = $request->aporte_iess;
        $cargo->fondo_reserva = $request->fondo_reserva;
        $cargo->decimo_tercero = $request->decimo_tercero;
        $cargo->decimo_cuarto = $request->decimo_cuarto;
        $cargo->cuenta_contable = $request->cuenta_contable;
        $cargo->observaciones = $request->observaciones;
        $cargo->id_empleado = $request->id_empleado;
        $cargo->id_cargo = $request->idcargo;
        $cargo->idOGrupoOcupacional = $request->idgrupo;
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
        $cargo = Empleado_Cargo::find($request->id);
        $cargo->fecha_ingreso = $request->fecha_ingreso;
        $cargo->fecha_salida = $request->fecha_salida;
        $cargo->tipo_horario = $request->tipo_horario;
        $cargo->tipo_contrato = $request->tipo_contrato;
        $cargo->bonos = $request->bonos;
        $cargo->aporte_iess = $request->aporte_iess;
        $cargo->fondo_reserva = $request->fondo_reserva;
        $cargo->decimo_tercero = $request->decimo_tercero;
        $cargo->decimo_cuarto = $request->decimo_cuarto;
        $cargo->cuenta_contable = $request->cuenta_contable;
        $cargo->observaciones = $request->observaciones;
        $cargo->id_empleado = $request->id_empleado;
        $cargo->id_cargo = $request->idcargo;
        $cargo->idgrupo = $request->idgrupo;
        $cargo->save();
    }

    public function getEmpleado(Request $request)
    {
        $data = Empleado::where('id_empleado', '=', $request->id_empleado)->get();
        //$data=AreaTrabajo::get();
        return response()->json($data);
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
        Empleado_Cargo::destroy($id);
    }
    
    public function getGrupoOcu($id)
    {
        $data = GrupoOcupacional::select("*")->where("id_empresa", "=", $id)->get();

        return response()->json($data);
    }

    public function getDepartamento($id)
    {
        $data = Departamento::select("*")->where("id_empresa", "=", $id)->get();

        return response()->json($data);
    }

    public function getArea($id)
    {
        $data = AreaTrabajo::select("*")->where("id_empresa", "=", $id)->get();
        //$data=Cuidad::get();
        return response()->json($data);
    }

    public function getCargo(Request $request)
    {
        $data = Cargo::where('id_area', '=', $request->id_area)->get();
        //$data=Cuidad::get();
        return response()->json($data);
    }

    public function abrir(Request $request)
    {
        $id = $request->id;
        $recupera = DB::select('SELECT * FROM empleado_cargo WHERE id_empleado=' . $id);

        return $recupera;
    }
}
