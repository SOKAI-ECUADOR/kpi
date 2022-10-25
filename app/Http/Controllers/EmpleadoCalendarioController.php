<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Empleado_Calendario;
use App\Models\Empleado;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmpleadoCalendarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
       /* $buscar = $request->buscar;
        if ($buscar == '') {
            $recupera = Empleado_Calendario::select("*")->where("id_empresa", "=", $id)->orderByRaw('id_carga DESC')->get();
        } else {
            $recupera = Empleado_Calendario::select('*')
                ->where(function ($q) use ($buscar) {
                    $q->where('nombre', 'like', '%' . $buscar . '%')
                    ->orWhere('dni', 'like', '%' . $buscar . '%')
                    ->orWhere('dni', 'like', '%' . $buscar . '%')
                    ->orWhere('dni', 'like', '%' . $buscar . '%');
                })
                ->where("id_empresa", "=", $id)
                ->orderByRaw('id_carga DESC')->get();
        }
        return [ 'recupera' => $recupera];*/
        $recupera = DB::select('SELECT * FROM calendario_empleado where id_empleado='.$id);
        return $recupera;
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
        $fecha = new Empleado_Calendario();
        $fecha->fecha_inicio = $request->fecha_inicio;
        $fecha->fecha_fin = $request->fecha_fin;
        $fecha->razon = $request->razon;
        $fecha->ucrea = $request->id_usuario;
        $fecha->id_empleado = $request->idemplead;
        //$fecha->id_empresa = $request->id_empresa;
        $fecha->save();
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
        $fecha = Empleado_Calendario::findOrFail($request->id);
        $fecha->fecha_inicio = $request->fecha_inicio;
        $fecha->fecha_fin = $request->fecha_fin;
        $fecha->razon = $request->razon;
        $fecha->umodifica = $request->id_usuario;
        $fecha->id_empleado = $request->idemplead;
        $fecha->save();
    }

    public function getEmpleado(Request $request){
        $data=Empleado::where('id_empleado','=', $request->id_empleado)->get();
        //$data=Ciudad::get();
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
        Empleado_Calendario::destroy($id);
    }
    public function abrir(Request $request)
    {
        $id = $request->id;
        $recupera = DB::select('SELECT * FROM empleado_calendarioEmpleado_CalendarioEmpleado_Calendario WHERE id_empleado=' . $id);

        return $recupera;
    
    }
}
