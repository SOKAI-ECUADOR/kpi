<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Empleado;
use App\Models\Empleado_Cargo;
use App\Models\Empleado_Cargas;
use App\Models\Empleado_Documento;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Provincia;
use App\Models\Ciudad;
use App\Models\Parroquia;
use App\Models\Banco;
use App\Models\Nacionalidad;
use App\Models\Cargo;
include 'class/generarReportes.php';

use generarReportes;

include_once getenv("FILE_CONFIG_PHP");

class EmpleadoController extends Controller
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
            $recupera = Empleado::select("*")->where("id_empresa", "=", $id)->orderByRaw('id_empleado DESC')->get();
        } else {
            $recupera = Empleado::select('*')
                ->where(function ($q) use ($buscar) {
                    $q->where('primer_nombre', 'like', '%' . $buscar . '%')
                        ->orWhere('apellido_paterno', 'like', '%' . $buscar . '%')
                        ->orWhere('segundo_nombre', 'like', '%' . $buscar . '%')
                        ->orWhere('apellido_materno', 'like', '%' . $buscar . '%')
                        ->orWhere('dni', 'like', '%' . $buscar . '%');
                })
                ->where("id_empresa", "=", $id)
                ->orderByRaw('id_empleado DESC')->get();
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
    public function empleadosDepartamento(Request $request){
        if($request->id_departamento){
            $reporte=DB::select("SELECT id_empleado,concat(primer_nombre,' ',segundo_nombre,' ',apellido_paterno) as nombre_empleado from empleado where estado='Activo' and id_departamento={$request->id_departamento}");
        }else{
            $reporte=DB::select("SELECT id_empleado,concat(primer_nombre,' ',segundo_nombre,' ',apellido_paterno) as nombre_empleado from empleado where estado='Activo' and id_departamento=0");
        }
        
        return $reporte;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //se escoge la carpeta donde se guardara las imagenes
        $dni_empl=DB::select("SELECT * from empleado where id_empresa={$request->id_empresa} and dni like '%{$request->dni}%'");
        if(count($dni_empl)>0){
            return "existe dni";
        }

        $nombre_imagen="";


        $empleado = new Empleado();

        $empleado->tipo_dni = $request->tipo_dni;
        $empleado->dni = $request->dni;
        $empleado->primer_nombre = $request->primer_nombre;
        $empleado->segundo_nombre = $request->segundo_nombre;
        $empleado->apellido_paterno = $request->apellido_paterno;
        $empleado->apellido_materno = $request->apellido_materno;
        $empleado->fecha_nacimiento = $request->fecha_nacimiento;
        $empleado->edad = $request->edad;
        //$empleado->foto = $agregarimagen;
        //$empleado->lugar_nacimiento = $request->lugar_nacimiento;
        $empleado->id_nacionalidad = $request->nacionalidad;
        $empleado->estado_civil = $request->estado_civil;
        $empleado->sexo = $request->sexo;
        $empleado->direccion_residencia = $request->direccion_residencia;
        $empleado->telefono = $request->telefono;
        $empleado->celular = $request->celular;
        $empleado->email = $request->email;
        $empleado->tipo_sangre = $request->tipo_sangre;
        $empleado->profesion = $request->profesion;
        $empleado->discapacidad = $request->discapacidad;
        if(isset($request->otra_discap)){
            $empleado->tipo_discapacidad = $request->otra_discap;
        }
        $empleado->discap_porcentaje = $request->discap_porcentaje;
        $empleado->tipo_iden_discap = $request->tipo_iden_discap;
        $empleado->num_iden_discap = $request->num_iden_discap;
        //$empleado->num_iess = $request->num_iess;
        //$empleado->num_libreta_militar = $request->num_libreta_militar;
        $empleado->id_banco = $request->banco;
        $empleado->tipo_cuenta = $request->tipo_cuenta;
        $empleado->num_cuenta = $request->num_cuenta;
        $empleado->carga=$request->carga;
        $empleado->num_cargas = $request->num_cargas;
        $empleado->estado = "Activo";
        $empleado->observaciones = $request->observaciones_empl;

        $empleado->contacto_nombre = $request->contacto_nombre;
        $empleado->contacto_parentezco = $request->contacto_parentezco;
        $empleado->contacto_telefono = $request->contacto_telefono;

        $empleado->fecha_ingreso = $request->fecha_ingreso;
        $empleado->fecha_salida = $request->fecha_salida;
        $empleado->tipo_horario = $request->tipo_horario;
        $empleado->tipo_contrato = $request->tipo_contrato;
        $empleado->sueldo = $request->bonos;
        $empleado->aporte_iess = $request->aporte_iess;
        $empleado->fondo_reserva = $request->fondo_reserva;
        $empleado->decimo_tercero = $request->decimo_tercero;
        $empleado->decimo_cuarto = $request->decimo_cuarto;
        $empleado->observacion_cargo = $request->observaciones_dos;
        $empleado->lugar_residencia = $request->lugar_residencia;
        $empleado->ucrea = $request->ucrea;
        $empleado->id_empresa = $request->id_empresa;
        $empleado->id_departamento = $request->departamento;
        $empleado->id_provincia = $request->id_provincia;
        $empleado->id_ciudad = $request->id_canton;
        $empleado->id_parroquia = $request->lugar_nacimiento;
        $empleado->id_grupo = $request->id_grupo;
        $empleado->id_cargo = $request->id_cargo;
        $empleado->id_plan_cuentas = $request->cuenta_contable;
        $empleado->id_area_trabajo = $request->id_area;
        /*$contador=DB::select("SELECT count(dni) as contador_dni FROM empleado where dni=".'"'.$request->dni.'"');
        $cantidad=$contador[0]->contador_dni;
        if($cantidad>1){
            return "invalido"
        }*/
        $empleado->save();
        $empleado->id_empleado;
        $id=$empleado->id_empleado;
        $carpetanombre = constant("DATA_EMPRESA").$request->id_empresa;
        $carpeta1 = $carpetanombre."/empleados/".$id."/imagenes/";
            if (!file_exists($carpeta1)) {
                mkdir($carpeta1, 0777,true);
            }
        $carpetanombre2 = constant("DATA_EMPRESA").$request->id_empresa;
        $carpeta2 = $carpetanombre2."/empleados/".$id."/documentos/";
                if (!file_exists($carpeta2)) {
                    mkdir($carpeta2, 0777,true);
                }
        if (!$request->recuperaimagen) {
            if ($request->file_imagen) {
                $file_imagen = $request->file('file_imagen');
                $nombre_imagen = time() . $file_imagen->getClientOriginalName();
                $ubicacion_imagen = $carpeta1;
                $request->file('file_imagen')->move($ubicacion_imagen, $nombre_imagen);
                $agregarimagen = $nombre_imagen;//$ubicacion_imagen ."/".
               //$agregarimagen = "/empleado/imagenes/".$nombre_imagen;
            } else {
                $agregarimagen = "";

            }
        }
        
        
        if (!$request->recuperaimagen) {
            $empleado = Empleado::findOrFail($id);
            $empleado->foto = $agregarimagen;
            $empleado->save();
        }
        
        return $id;
        

    }
    public function guardarCarga(Request $request){
        /*
        id_carga:null,
                        car_dni: "",
                        car_nombre: "",
                        car_fecha_nacimiento: "",
                        car_edad: "",
                        car_ocupacion: "",
                        car_parentezco: "",
                        car_discapacidad: "",
                        car_discap_porcentaje: "",
        */
        $id=$request->id_empleado;
        if(count($request->provds)>=1){
            for ($a = 0; $a < count($request->provds); $a++) {
                if($request->provds[$a]["car_nombre"]!=null){
                    $dfactc = new Empleado_Cargas();
                    $dfactc->tipo_car_dni = $request->provds[$a]["tipo_car_dni"];
                    $dfactc->car_dni = $request->provds[$a]["car_dni"];
                    $dfactc->car_nombre = $request->provds[$a]["car_nombre"];
                    $dfactc->car_fecha_nacimiento = $request->provds[$a]["car_fecha_nacimiento"];
                    $dfactc->car_edad = $request->provds[$a]["car_edad"];
                    $dfactc->car_ocupacion = $request->provds[$a]["car_ocupacion"];
                    $dfactc->car_parentezco = $request->provds[$a]["car_parentezco"];
                    $dfactc->car_discapacidad = $request->provds[$a]["car_discapacidad"];
                    if(isset($request->provds[$a]["car_tipo_discapacidad"])){
                        $dfactc->car_tipo_discapacidad = $request->provds[$a]["car_tipo_discapacidad"];
                    }
                    $dfactc->car_discap_porcentaje = $request->provds[$a]["car_discap_porcentaje"];
                    $dfactc->car_documento_validez = $request->provds[$a]["car_documento_validez"];
                    $dfactc->id_empleado = $id;
                    $dfactc->save();
                }
            }  
        }else{
            return "No hay";
        }
        
        return $id;
    }
    public function guardarDocumentos(Request $request){
        /*
                doc_url: "",
                doc_estado: "",
                id_documento: "",
        */
        $id=$request->id_empleado;
        
        //return $request;
        for ($a = 0; $a < count($request->provds); $a++) {
            if($request->provds[$a]["id_documento"]!=null){
                $dfactc = new Empleado_Documento();
                $dfactc->doc_url = $request->provds[$a]["doc_url"];
                $dfactc->doc_estado =  $request->provds[$a]["doc_estado"];
                $dfactc->id_documento =  $request->provds[$a]["id_documento"];
                $dfactc->id_empleado = $id;
                
                $dfactc->save();
            }
           
        }
        return $id;
    }
    public function GuardarArchivos(Request $request){
        for($a = 0; $a <= $request->cantidad; $a++){
            
            if($request->file('documento'.$a)){
                $file_imagen = $request->file('documento'.$a);
                $nombre_imagen = time() . $file_imagen->getClientOriginalName();
                $ubicacion_imagen = constant("DATA_EMPRESA").$request->id_empresa."/empleados/".$request->id."/documentos/";
                //$ubicacion_imagen="empleado/archivos";
                $file_imagen->move($ubicacion_imagen, $nombre_imagen);
            }
        }
               
    }
    public function GuardarDocumetoCarga(Request $request){
        
        for($a = 0; $a <= $request->cantidad; $a++){
            if($request->file('documento_carga'.$a)){
                $file_imagen = $request->file('documento_carga'.$a);
                $nombre_imagen = time() . $file_imagen->getClientOriginalName();
                $ubicacion_imagen = constant("DATA_EMPRESA").$request->id_empresa."/empleados/".$request->id."/documentos/";
                //$ubicacion_imagen="empleado/archivos";
                $file_imagen->move($ubicacion_imagen, $nombre_imagen);
            }
        }
                 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    { }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        /*$carpetanombre = "../../../dataempresa/".$request->id_empresa;
        $carpeta1 = $carpetanombre."/empleados/".$request->id;
            if (!file_exists($carpeta1)) {
                mkdir($carpeta1, 0777,true);
            }*/

        $dni_empleado=DB::select("SELECT * from empleado where id_empleado={$request->id}");
        
        if($request->dni!==$dni_empleado[0]->dni){
            $dni_empleado2=DB::select("SELECT * from empleado where id_empleado<>{$request->id} and id_empresa={$request->id_empresa}");
            if(count($dni_empleado2)>0){
                return "existe dni";
            } 
        }

        $carpetanombre = constant("DATA_EMPRESA").$request->id_empresa;
        $carpeta1 = $carpetanombre."/empleados/".$request->id."/imagenes/";
            if (!file_exists($carpeta1)) {
                mkdir($carpeta1, 0777,true);
            }
        $carpetanombre2 = constant("DATA_EMPRESA").$request->id_empresa;
        $carpeta2 = $carpetanombre2."/empleados/".$request->id."/documentos/";
                if (!file_exists($carpeta2)) {
                    mkdir($carpeta2, 0777,true);
                }
        if (!$request->recuperaimagen) {
            if ($request->file_imagen) {
                $file_imagen = $request->file('file_imagen');
                $nombre_imagen = time() . $file_imagen->getClientOriginalName();
                $ubicacion_imagen = $carpeta1;
                $request->file('file_imagen')->move($ubicacion_imagen, $nombre_imagen);
               // $request->file('file_imagen')->move('empleado/imagenes',$nombre_imagen); 

                $agregarimagen = $nombre_imagen;//$ubicacion_imagen."/".
                //$agregarimagen="/empleado/imagenes/".$nombre_imagen;
            } else {
                $agregarimagen = "";

            }
        }
        //return $agregarimagen;

        $empleado = Empleado::findOrFail($request->id);
        $empleado->tipo_dni = $request->tipo_dni;
        $empleado->dni = $request->dni;
        $empleado->primer_nombre = $request->primer_nombre;
        $empleado->segundo_nombre = $request->segundo_nombre;
        $empleado->apellido_paterno = $request->apellido_paterno;
        $empleado->apellido_materno = $request->apellido_materno;
        $empleado->fecha_nacimiento = $request->fecha_nacimiento;
        $empleado->edad = $request->edad;
        if (!$request->recuperaimagen) {
            $empleado->foto = $agregarimagen;
        }
        //$empleado->foto = $request->foto;
        //$empleado->lugar_nacimiento = $request->lugar_nacimiento;
        $empleado->id_nacionalidad = $request->nacionalidad;
        $empleado->estado_civil = $request->estado_civil;
        $empleado->sexo = $request->sexo;
        $empleado->direccion_residencia = $request->direccion_residencia;
        $empleado->telefono = $request->telefono;
        $empleado->celular = $request->celular;
        $empleado->email = $request->email;
        $empleado->tipo_sangre = $request->tipo_sangre;
        $empleado->profesion = $request->profesion;
        $empleado->discapacidad = $request->discapacidad;
        if(isset($request->otra_discap)){
            $empleado->tipo_discapacidad = $request->otra_discap;
        }
        $empleado->discap_porcentaje = $request->discap_porcentaje;
        $empleado->tipo_iden_discap = $request->tipo_iden_discap;
        $empleado->num_iden_discap = $request->num_iden_discap;
        //$empleado->num_iess = $request->num_iess;
        //$empleado->num_libreta_militar = $request->num_libreta_militar;
        $empleado->id_banco = $request->banco;
        $empleado->tipo_cuenta = $request->tipo_cuenta;
        $empleado->num_cuenta = $request->num_cuenta;
        $empleado->carga=$request->carga;
        $empleado->num_cargas = $request->num_cargas;
        $empleado->estado = $request->estado;
        $empleado->observaciones = $request->observaciones_empl;

        $empleado->contacto_nombre = $request->contacto_nombre;
        $empleado->contacto_parentezco = $request->contacto_parentezco;
        $empleado->contacto_telefono = $request->contacto_telefono;

        $empleado->fecha_ingreso = $request->fecha_ingreso;
        $empleado->fecha_salida = $request->fecha_salida;
        $empleado->tipo_horario = $request->tipo_horario;
        $empleado->tipo_contrato = $request->tipo_contrato;
        $empleado->sueldo = $request->bonos;
        $empleado->aporte_iess = $request->aporte_iess;
        $empleado->fondo_reserva = $request->fondo_reserva;
        $empleado->decimo_tercero = $request->decimo_tercero;
        $empleado->decimo_cuarto = $request->decimo_cuarto;
        $empleado->observacion_cargo = $request->observaciones_dos;
        $empleado->lugar_residencia = $request->lugar_residencia;
        $empleado->umodifica = $request->umodifica;
        $empleado->id_empresa = $request->id_empresa;
        $empleado->id_departamento = $request->departamento;
        $empleado->id_provincia = $request->id_provincia;
        $empleado->id_ciudad = $request->id_canton;
        $empleado->id_parroquia = $request->lugar_nacimiento;
        $empleado->id_area_trabajo = $request->id_area;
        $empleado->id_grupo = $request->id_grupo;
        $empleado->id_cargo = $request->id_cargo;
        $empleado->id_plan_cuentas = $request->cuenta_contable;
        $empleado->id_area_trabajo = $request->id_area;
        $empleado->save();
        $empleado->id_empleado;
        $id=$empleado->id_empleado;
        return $id;
    }

    public function actCargas(Request $request){
        $id=$request->id_empleado;
        if(count($request->provds)>=1){
            for ($a = 0; $a < count($request->provds); $a++) {
                if($request->provds[$a]["id_carga"]===null){
                    $dfactc = new Empleado_Cargas();
                    $dfactc->tipo_car_dni = $request->provds[$a]["tipo_car_dni"];
                    $dfactc->car_dni = $request->provds[$a]["car_dni"];
                    $dfactc->car_nombre = $request->provds[$a]["car_nombre"];
                    $dfactc->car_fecha_nacimiento = $request->provds[$a]["car_fecha_nacimiento"];
                    $dfactc->car_edad = $request->provds[$a]["car_edad"];
                    $dfactc->car_ocupacion = $request->provds[$a]["car_ocupacion"];
                    $dfactc->car_parentezco = $request->provds[$a]["car_parentezco"];
                    $dfactc->car_discapacidad = $request->provds[$a]["car_discapacidad"];
                    $dfactc->car_discap_porcentaje = $request->provds[$a]["car_discap_porcentaje"];
                    $dfactc->car_documento_validez = $request->provds[$a]["car_documento_validez"];
                    $dfactc->id_empleado = $id;
                    $dfactc->save();
                }
                if($request->provds[$a]["id_carga"]!=null){
                    $dfactc =Empleado_Cargas::find($request->provds[$a]["id_carga"]);
                    $dfactc->tipo_car_dni = $request->provds[$a]["tipo_car_dni"];
                    $dfactc->car_dni = $request->provds[$a]["car_dni"];
                    $dfactc->car_nombre = $request->provds[$a]["car_nombre"];
                    $dfactc->car_fecha_nacimiento = $request->provds[$a]["car_fecha_nacimiento"];
                    $dfactc->car_edad = $request->provds[$a]["car_edad"];
                    $dfactc->car_ocupacion = $request->provds[$a]["car_ocupacion"];
                    $dfactc->car_parentezco = $request->provds[$a]["car_parentezco"];
                    $dfactc->car_discapacidad = $request->provds[$a]["car_discapacidad"];
                    $dfactc->car_discap_porcentaje = $request->provds[$a]["car_discap_porcentaje"];
                    $dfactc->car_documento_validez = $request->provds[$a]["car_documento_validez"];
                    $dfactc->id_empleado = $id;
                    $dfactc->save();
                }    
                    
            }
        }
        return $id;
    }
    public function actDocumentos(Request $request){
        $id=$request->id_empleado;
        for ($a = 0; $a < count($request->provds); $a++) {
            if($request->provds[$a]["id_docu_emp"]===null){
                $dfactc = new Empleado_Documento();
                $dfactc->doc_url = $request->provds[$a]["doc_url"];
                $dfactc->doc_estado =  $request->provds[$a]["doc_estado"];
                $dfactc->id_documento =  $request->provds[$a]["id_documento"];
                $dfactc->id_empleado = $id;
                $dfactc->save();
            }
            if($request->provds[$a]["id_docu_emp"]!=null){
                $dfactc = Empleado_Documento::find($request->provds[$a]["id_docu_emp"]);
                $dfactc->doc_url = $request->provds[$a]["doc_url"];
                $dfactc->doc_estado =  $request->provds[$a]["doc_estado"];
                $dfactc->id_documento =  $request->provds[$a]["id_documento"];
                $dfactc->id_empleado = $id;
                $dfactc->save();
            }
        }

        return $id;
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
        // DB::select('DELETE FROM empleado WHERE id_empleado =' . $id);
        Empleado_Documento::where('id_empleado',$id)->delete();
        Empleado_Cargas::where('id_empleado',$id)->delete();
        Empleado::destroy($id);
    }
    public function getProvincia()
    {
        $data = Provincia::get();
        return response()->json($data);
    }
    public function getCiudad(Request $request)
    {
        $data = Ciudad::where('id_provincia', '=', $request->id_provincia)->get();
        //$data=Cuidad::get();
        return response()->json($data);
    }
    public function getParroquia(Request $request)
    {
        $data = Parroquia::where('id_ciudad', '=', $request->id_ciudad)->get();
        //$data=Cuidad::get();
        return response()->json($data);
    }
    public function getBanco()
    {
        $data = Banco::get();
        return response()->json($data);
    }
    public function getNacionalidad()
    {
        $data = Nacionalidad::get();

        return response()->json($data);
    }
    public function verEmpleado(Request $request)
    {
        /*
        $id = $request->id;

        $empleado = DB::select('SELECT * FROM empleado WHERE id_empleado =' . $id);
        return $empleado;
      */
        /* $id = $request->id;
        $id_empresa = $request->id_empresa;
        $empleado = DB::table('empleado')
            ->join('empleado_cargo', 'empleado.id_empleado', '=', 'empleado_cargo.id_empleado')
            ->join('cargas_empleado', 'empleado.id_empleado', '=', 'cargas_empleado.idempleado')
            ->join('docu_empleado', 'empleado.id_empleado', '=', 'docu_empleado.idemple')
            ->where('empleado.id_empleado', '=', $id)
            ->where('empleado.id_empresa', '=', $id_empresa)
            ->get();
        return $empleado;  
        */
        $empleado = DB::select("SELECT *,(select plan_cuentas.nomcta from plan_cuentas,empleado where plan_cuentas.id_plan_cuentas=empleado.id_plan_cuentas and empleado.id_empleado=".$request->id.") as cuenta_resultado FROM empleado  WHERE  empleado.id_empleado  =".$request->id); 
        return $empleado;
    }
    public function listarCargas($id){
        $empleado = DB::select("SELECT * FROM cargas_empleado  WHERE  id_empleado  =".$id); 
        return $empleado;
    }
    public function listarDocumentos($id){
        $empleado = DB::select("SELECT * FROM docu_empleado  WHERE  id_empleado  =".$id); 
        return $empleado;
    }
    public function listarInfoEmp(Request $request)
    {
        $info = DB::table('empleado')
            ->join('empleado_cargo', 'empleado.id_empleado', '=', 'empleado_cargo.id_empleado')
            //->join('cargas_empleado', 'empleado.id_empleado', '=', 'cargas_empleado.idempleado')
            ->select('empleado.*', 'empleado_cargo.*')
            ->where('empleado.id_empleado', '=', $request->id)
            ->get();
        return $info;
    }
    public function getCargoEmpleadoArea(Request $request){
        $id=$request->id_area;
        $data = Cargo::where('id_area', '=', $request->id_area)->get();
        //$data=Cuidad::get();
        return response()->json($data);
    }
    public function getCargoEmpleadoId(){
        $data = Cargo::select("*")->get();
        //$data=Cuidad::get();
        return response()->json($data);

    }
    public function getSueldoCargoEmpleado($id){
        $data = Cargo::select("car_sueldo")->where("id_cargo",'=',$id)->get();
        //$data=Cuidad::get();
        return response()->json($data);

    }
    /**
     * subir imagane  
     *@var guardarimagen
     *return Guadar img
     */
    public function guardarimagen(Request $request)
    {
        //obtenemos el campo file definido en el formulario
        // dd($request->file('file'));
        $file = $request->file('file');
        // //obtenemos el nombre del archivo
        $nombre = time().$file->getClientOriginalName();
        // //indicamos que queremos guardar un nuevo archivo en el disco local
        $ubicacion = "../../dataempresa/" . $request->id_empresa . '/imagenes-empleados/';
        $file->move($ubicacion, $nombre);
        $empleado = Empleado::find($request->id);
        $empleado->foto = $ubicacion . $nombre;
        $empleado->save();
         
        //return $request->file('file')->getClientOriginalName();
    }

    public function generarReporte(Request $request){
        setlocale(LC_ALL,"es_ES");
        $queries = [];
        
        $fech_entrada="";        //$request->date = json_decode($request->date);
        if($request->dates){
            array_push($queries, "date(e.fecha_ingreso) between date('{$request->dates}') and date(now())");
            $fech_entrada=$request->dates;
        } else{
            $fech_entrada=date("Y-m-d");
        }                   
        if ($request->department) {
            $info_establishment = json_decode($request->department, true);
            if ($info_establishment["id"] != 0) {
                array_push($queries, "e.id_departamento = {$info_establishment["id"]}\n");
            }

        }  
        if ($request->cargo) {
            $info_establishment = json_decode($request->cargo, true);
            if ($info_establishment["id"] != 0) {
                array_push($queries, "e.id_cargo = {$info_establishment["id"]}\n");
            }

        } 
        if ($request->area) {
            $info_establishment = json_decode($request->area, true);
            if ($info_establishment["id"] != 0) {
                array_push($queries, "e.id_area_trabajo = {$info_establishment["id"]}\n");
            }

        }   
        
        
        $queries = implode(" and ", $queries);
        
        if($queries!==""){
            $query = "Select e.*,d.dep_nombre, c.car_nombre,emp.nombre_empresa,emp.logo 
            from empleado e,departamento d,cargo c,empresa emp
             where  
             {$queries} and
             e.id_departamento=d.id_departamento and
             e.id_cargo=c.id_cargo and 
             e.id_empresa=emp.id_empresa and
             e.id_empresa={$request->company} 
             ";
        }else{
            $query = "Select e.*,d.dep_nombre, c.car_nombre,emp.nombre_empresa,emp.logo 
            from empleado e,departamento d,cargo c,empresa emp
             where  
             e.id_departamento=d.id_departamento and
             e.id_cargo=c.id_cargo and 
             e.id_empresa=emp.id_empresa and
             e.id_empresa={$request->company} 
             ";
        }
        
         $reporte = DB::select($query);
         
        $Reportes = new generarReportes();
        if (!$reporte) {
            return response('no-data-report', 200)->header('Content-Type', 'application/json');
        } else {
            $strPDF = $Reportes->empleado($reporte, $request->dates);
            return response($strPDF, 200)->header('Content-Type', 'application/pdf');
        }
    }

}
