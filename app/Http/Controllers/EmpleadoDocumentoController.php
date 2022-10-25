<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Empleado_Documento;
use App\Models\Empleado;
use App\Models\Documentacion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmpleadoDocumentoController extends Controller
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
            $recupera = Empleado_Documento::orderByRaw('id_carga DESC')->paginate($cantidadp);
        } else {
            $recupera = Empleado_Documento::select('*')
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
        $docu = new Empleado_Documento();
        $docu->doc_url = $request->doc_url;
        $docu->doc_estado = $request->doc_estado;
        $docu->iddocu = $request->iddocu;
        $docu->idemple = $request->idemple;
        $docu->save();
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
        $docu = Empleado_Documento::findOrFail($request->id);
        $docu->doc_url = $request->doc_url;
        $docu->doc_estado = $request->doc_estado;
        $docu->iddocu = $request->iddocu;
        $docu->idemple = $request->idemple;
        $docu->save();
    }

    public function getEmpleado(Request $request){
        $data=Empleado::where('id_empleado','=', $request->id_empleado)->get();
        //$data=Ciudad::get();
        return response()->json($data);
    }

    public function getDocumento($id){
        $data=Documentacion::select("*")->where("id_empresa", "=", $id)->get();

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
        Empleado_Documento::destroy($id);
    }
    public function abrir(Request $request)
    {
        $id = $request->id;
        $recupera = DB::select('SELECT * FROM empleado_calendarioEmpleado_CalendarioEmpleado_Calendario WHERE id_empleado=' . $id);

        return $recupera;
    
    }
}
