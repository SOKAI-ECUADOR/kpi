<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Empleado_Cargas;
use App\Models\Empleado;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmpleadoCargasController extends Controller
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
            $recupera = Empleado_Cargas::/*join('empleado', 'cargas_empleado.idempleado','=','empleado.id_empleado')
            ->select()*/
            orderByRaw('id_carga DESC')->paginate($cantidadp);
        } else {
            $recupera = Empleado_Cargas::select('*')
                ->where('nombre', 'like', '%' . $buscar . '%')
                ->orWhere('dni', 'like', '%' . $buscar . '%')
                ->orWhere('dni', 'like', '%' . $buscar . '%')
                ->orWhere('dni', 'like', '%' . $buscar . '%')
                ->orderByRaw('id_carga DESC')->paginate($cantidadp);
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
        $carga = new Empleado_Cargas();
        $carga->car_dni = $request->car_dni;
        $carga->car_nombre = $request->car_nombre;
        $carga->car_fecha_nacimiento = $request->car_fecha_nacimiento;
        $carga->car_edad = $request->car_edad;
        $carga->car_ocupacion = $request->car_ocupacion;
        $carga->car_parentezco = $request->car_parentezco;
        $carga->car_discapacidad = $request->car_discapacidad;
        $carga->car_discap_porcentaje = $request->car_discap_porcentaje;
        $carga->idempleado = $request->idempleado;
        $carga->save();
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
        $carga = Empleado_Cargas::findOrFail($request->id);
        $carga->car_dni = $request->car_dni;
        $carga->car_nombre = $request->car_nombre;
        $carga->car_fecha_nacimiento = $request->car_fecha_nacimiento;
        $carga->car_edad = $request->car_edad;
        $carga->car_ocupacion = $request->car_ocupacion;
        $carga->car_parentezco = $request->car_parentezco;
        $carga->car_discapacidad = $request->car_discapacidad;
        $carga->car_discap_porcentaje = $request->car_discap_porcentaje;
        $carga->idempleado = $request->idempleado;
        $carga->save();
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
        Empleado_Cargas::destroy($id);
    }
    public function abrir(Request $request)
    {
        $id = $request->id;
        $recupera = DB::select('SELECT * FROM empleado_cargas WHERE id_empleado=' . $id);

        return $recupera;
    
    }
}
