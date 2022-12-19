<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ColorMobiliario;
use App\Models\ConservacionMobiliario;
use App\Models\CustodioMobiliario;
use App\Models\DimensionMobiliario;
use App\Models\MantenimientoMobiliario;
use App\Models\MarcaMobiliario;
use App\Models\MaterialMobiliario;
use App\Models\ModeloMobiliario;
use App\Models\TipoActivoMobiliario;
use App\Models\UbicacionEspecificaMobiliario;
use App\Models\UbicacionGeneralMobiliario;
use App\Models\IdentificadorMobiliario;
use App\Models\TipoMobiliario;
use App\Models\VehiculoMobiliario;
use App\Models\MaquinaMobiliario;
use App\Models\EnseresMobiliario;
use App\Models\LibroMobiliario;


use App\Models\ActaAsignacionActivo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ActaAsignacionActivoController extends Controller
{
    
    //listar  usuarios responsables
    public function indexuser(Request $request, $id){
        $buscar = $request->buscar;
        $empresa = $id;
        if ($buscar == '') { 
            $recupera = User::select("*")
             ->where("id_empresa", "=", $empresa)
            ->orderByRaw('nombres ASC')->get();
        } else {
            $recupera = User::select('*')
                ->where(function ($q) use ($buscar) {
                    $q->where('nombres', 'like', '%'.$buscar.'%');
                })
                 ->where("id_empresa", "=", $empresa)
                ->orderByRaw('nombres ASC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }


    //listar  actas asignacion activos
    public function indexactasasignacionactivos(Request $request, $id){
        $empresa = $id;        

            $recupera = DB::table('acta_asignacion_activo')
            ->join('acta_agencia', 'acta_agencia.id', '=', 'acta_asignacion_activo.acta_agencia_id')
            ->join('acta_estado', 'acta_estado.id', '=', 'acta_asignacion_activo.acta_estado_id')
            ->where("acta_asignacion_activo.empresa_id", "=", $empresa)
            ->select(
                'acta_asignacion_activo.id as acta_asignacion_activo_id',
                'acta_agencia.nombre as acta_agencia_nombre',
                'acta_asignacion_activo.periodo as periodo',
                'acta_estado.nombre as acta_estado_nombre',
                'acta_asignacion_activo.fcrea as acta_fecha_creacion'
                )
                ->orderByRaw('acta_asignacion_activo.fcrea ASC')->get(); 

        return [
            'recupera' => $recupera
        ];
    }

    //guardar acta asignacion de activos
    public function storeactaasignacionactivos(Request $request){

        $periodo = date("Y");
        $user_id = $request->user_id;
        $empresa_id = $request->empresa_id;
        $agencia_id = $request->agencia_id;
        $responsable_acta_id = $request->responsable_acta_id;
        
        $acta_asignacion_activo = new ActaAsignacionActivo();
        $acta_asignacion_activo -> empresa_id = $empresa_id;
        $acta_asignacion_activo -> periodo = $periodo;
        $acta_asignacion_activo -> user_id = $user_id;
        $acta_asignacion_activo -> acta_estado_id = 1; // Acta Estado Inicial - Pendiente
        $acta_asignacion_activo -> acta_agencia_id = $agencia_id;
        $acta_asignacion_activo -> acta_responsable_id = $responsable_acta_id;
        
        $acta_asignacion_activo->save();

        return [
            'acta_id' => $acta_asignacion_activo ->id
        ];

    }

    // Busqueda de acta de asignacion de activos por id
    public function buscaractaasignacionactivo($id){

        $recupera = ActaAsignacionActivo::find($id)->first();
        
        return [
            'recupera' => $recupera
        ];

    }













    //actualizar color
    public function updatecolor(Request $request){
        $color=ColorMobiliario::find($request->id);
        $color->nombre_color=$request->nombre_color;
        $color->save();
    }

    //eliminar color
    public function deletecolor($id){
        ColorMobiliario::where("id_color","=",$id)->delete();
    }

    //listar conservacion
    public function indexconservacion(Request $request, $id){
        $buscar = $request->buscar;
        $empresa = $id;
        $tipo = $request->tipo;
        if ($buscar == '') { 
            $recupera = ConservacionMobiliario::select("*")
             ->where("id_empresa", "=", $empresa)
            ->orderByRaw('descripcion_conservacion ASC')->get();
        } else {
            $recupera = ConservacionMobiliario::select('*')
                ->where(function ($q) use ($buscar) {
                    $q->where('descripcion_conservacion', 'like', '%'.$buscar.'%');
                })
                 ->where("id_empresa", "=", $empresa)
                ->orderByRaw('descripcion_conservacion ASC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }

    //guardar conservacion
    public function storeconservacion(Request $request){
        $cod_cons=DB::select("SELECT max(id_conservacion) as id_conservacion from conservacion_mobiliario");
        $cod=1;
        if(count($cod_cons)>0){
            $cod=$cod_cons[0]->id_conservacion+1;
        }
        $cons=new ConservacionMobiliario();
        $cons->id_conservacion=$cod;
        $cons->descripcion_conservacion=$request->descripcion_conservacion;
        $cons->id_empresa=$request->id_empresa;
        $cons->save();
    }

    //actualizar conservacion
    public function updateconservacion(Request $request){
        $cons=ConservacionMobiliario::find($request->id);
        $cons->descripcion_conservacion=$request->descripcion_conservacion;
        $cons->save();
    }

    //eliminar conservacion
    public function deleteconservacion($id){
        ConservacionMobiliario::where("id_conservacion","=",$id)->delete();
    }

    //listar tipo activo
    public function indextipoactivo(Request $request, $id){
        $buscar = $request->buscar;
        $empresa = $id;
        if ($buscar == '') { 
            $recupera = TipoActivoMobiliario::select("*")
             ->where("id_empresa", "=", $empresa)
            ->orderByRaw('descripcion_tipo_activo ASC')->get();
        } else {
            $recupera = TipoActivoMobiliario::select('*')
                ->where(function ($q) use ($buscar) {
                    $q->where('descripcion_tipo_activo', 'like', '%'.$buscar.'%');
                })
                 ->where("id_empresa", "=", $empresa)
                ->orderByRaw('descripcion_tipo_activo ASC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }

    //guardar tipo activo
    public function storetipoactivo(Request $request){
        $cod_tipoactivo=DB::select("SELECT max(id_tipo_activo_mobiliario) as id_tipo_activo_mobiliario from tipo_activo_mobiliario");
        $cod=1;
        if(count($cod_tipoactivo)>0){
            $cod=$cod_tipoactivo[0]->id_tipo_activo_mobiliario+1;
        }
        $tipoactivo=new TipoActivoMobiliario();
        $tipoactivo->id_tipo_activo_mobiliario=$cod;
        $tipoactivo->descripcion_tipo_activo=$request->descripcion_tipoactivo;
        $tipoactivo->cuenta_contable_tipo_activo=$request->cuenta_contable_tipoactivo;
        $tipoactivo->id_empresa=$request->id_empresa;
        $tipoactivo->save();
    }

    //actualizar tipo activo
    public function updatetipoactivo(Request $request){
        $tipoactivo=TipoActivoMobiliario::find($request->id);
        $tipoactivo->descripcion_tipo_activo=$request->descripcion_tipoactivo;
        $tipoactivo->cuenta_contable_tipo_activo=$request->cuenta_contable_tipoactivo;
        $tipoactivo->save();
    }

    //eliminar tipo activo
    public function deletetipoactivo($id){
        TipoActivoMobiliario::where("id_tipo_activo_mobiliario","=",$id)->delete();
    }

    //listar custodio
    public function indexcustodio(Request $request, $id){
        $buscar = $request->buscar;
        $empresa = $id;
        if ($buscar == '') { 
            $recupera = CustodioMobiliario::select("*")
             ->where("id_empresa", "=", $empresa)
            ->orderByRaw('cedula_custodio ASC')->get();
        } else {
            $recupera = CustodioMobiliario::select('*')
                ->where(function ($q) use ($buscar) {
                    $q->where('cedula_custodio', 'like', '%'.$buscar.'%');
                    $q->orWhere('nombre_custodio', 'like', '%'.$buscar.'%');
                })
                 ->where("id_empresa", "=", $empresa)
                ->orderByRaw('cedula_custodio ASC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }

    //guardar custodio
    public function storecustodio(Request $request){
        $cod_custodio=DB::select("SELECT max(id_custodio) as id_custodio from custodio_mobiliario");
        $cod=1;
        if(count($cod_custodio)>0){
            $cod=$cod_custodio[0]->id_custodio+1;
        }
        $custodio=new CustodioMobiliario();
        $custodio->id_custodio=$cod;
        $custodio->cedula_custodio=$request->cedula_custodio;
        $custodio->nombre_custodio=$request->nombre_custodio;
        $custodio->id_empresa=$request->id_empresa;
        $custodio->save();
    }

    //actualizar custodio
    public function updatecustodio(Request $request){
        $custodio=CustodioMobiliario::find($request->id);
        $custodio->cedula_custodio=$request->cedula_custodio;
        $custodio->nombre_custodio=$request->nombre_custodio;
        $custodio->save();
    }

    //eliminar custodio
    public function deletecustodio($id){
        CustodioMobiliario::where("id_custodio","=",$id)->delete();
    }

    //listar dimension
    public function indexdimension(Request $request, $id){
        $buscar = $request->buscar;
        $empresa = $id;
        if ($buscar == '') { 
            $recupera = DimensionMobiliario::select("*")
             ->where("id_empresa", "=", $empresa)
            ->orderByRaw('descripcion_dimension ASC')->get();
        } else {
            $recupera = DimensionMobiliario::select('*')
                ->where(function ($q) use ($buscar) {
                    $q->where('descripcion_dimension', 'like', '%'.$buscar.'%');
                })
                 ->where("id_empresa", "=", $empresa)
                ->orderByRaw('descripcion_dimension ASC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }

    //guardar dimesion
    public function storedimension(Request $request){
        $cod_dimension=DB::select("SELECT max(id_dimension) as id_dimension from dimension_mobiliario");
        $cod=1;
        if(count($cod_dimension)>0){
            $cod=$cod_dimension[0]->id_dimension+1;
        }
        $dimension=new DimensionMobiliario();
        $dimension->id_dimension=$cod;
        $dimension->descripcion_dimension=$request->descripcion_dimension;
        $dimension->id_empresa=$request->id_empresa;
        $dimension->save();
    }

    //actualizar dimension
    public function updatedimension(Request $request){
        $dimension=DimensionMobiliario::find($request->id);
        $dimension->descripcion_dimension=$request->descripcion_dimension;
        $dimension->save();
    }

    //eliminar dimension
    public function deletedimension($id){
        DimensionMobiliario::where("id_dimension","=",$id)->delete();
    }

    //listar mantenimiento
    public function indexmantenimiento(Request $request, $id){
        $buscar = $request->buscar;
        $empresa = $id;
        if ($buscar == '') { 
            $recupera = MantenimientoMobiliario::select("*")
             ->where("id_empresa", "=", $empresa)
            ->orderByRaw('descripcion_mantenimiento ASC')->get();
        } else {
            $recupera = MantenimientoMobiliario::select('*')
                ->where(function ($q) use ($buscar) {
                    $q->where('descripcion_mantenimiento', 'like', '%'.$buscar.'%');
                })
                 ->where("id_empresa", "=", $empresa)
                ->orderByRaw('descripcion_mantenimiento ASC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }

    //guardar mantenimiento
    public function storemantenimiento(Request $request){
        $cod_mantenimiento=DB::select("SELECT max(id_mantenimiento) as id_mantenimiento from mantenimiento_mobiliario");
        $cod=1;
        if(count($cod_mantenimiento)>0){
            $cod=$cod_mantenimiento[0]->id_mantenimiento+1;
        }
        $mantenimiento=new MantenimientoMobiliario();
        $mantenimiento->id_mantenimiento=$cod;
        $mantenimiento->descripcion_mantenimiento=$request->descripcion_mantenimiento;
        $mantenimiento->id_empresa=$request->id_empresa;
        $mantenimiento->save();
    }

    //actualizar mantenimiento
    public function updatemantenimiento(Request $request){
        $mantenimiento=MantenimientoMobiliario::find($request->id);
        $mantenimiento->descripcion_mantenimiento=$request->descripcion_mantenimiento;
        $mantenimiento->save();
    }

    //eliminar mantenimiento
    public function deletemantenimiento($id){
        MantenimientoMobiliario::where("id_mantenimiento","=",$id)->delete();
    }

    //listar marca
    public function indexmarca(Request $request, $id){
        $buscar = $request->buscar;
        $empresa = $id;
        if ($buscar == '') { 
            $recupera = MarcaMobiliario::select("*")
             ->where("id_empresa", "=", $empresa)
            ->orderByRaw('nombre_marca ASC')->get();
        } else {
            $recupera = MarcaMobiliario::select('*')
                ->where(function ($q) use ($buscar) {
                    $q->where('nombre_marca', 'like', '%'.$buscar.'%');
                })
                 ->where("id_empresa", "=", $empresa)
                ->orderByRaw('nombre_marca ASC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }

    //guardar marca
    public function storemarca(Request $request){
        $cod_marca=DB::select("SELECT max(id_marca) as id_marca from marca_mobiliario");
        $cod=1;
        if(count($cod_marca)>0){
            $cod=$cod_marca[0]->id_marca+1;
        }
        $marca=new MarcaMobiliario();
        $marca->id_marca=$cod;
        $marca->nombre_marca=$request->nombre_marca;
        $marca->id_empresa=$request->id_empresa;
        $marca->save();
    }

    //actualizar marca
    public function updatemarca(Request $request){
        $marca=MarcaMobiliario::find($request->id);
        $marca->nombre_marca=$request->nombre_marca;
        $marca->save();
    }

    //eliminar marca
    public function deletemarca($id){
        MarcaMobiliario::where("id_marca","=",$id)->delete();
    }

    //listar material
    public function indexmaterial(Request $request, $id){
        $buscar = $request->buscar;
        $empresa = $id;
        if ($buscar == '') { 
            $recupera = MaterialMobiliario::select("*")
             ->where("id_empresa", "=", $empresa)
            ->orderByRaw('descripcion_material ASC')->get();
        } else {
            $recupera = MaterialMobiliario::select('*')
                ->where(function ($q) use ($buscar) {
                    $q->where('descripcion_material', 'like', '%'.$buscar.'%');
                })
                 ->where("id_empresa", "=", $empresa)
                ->orderByRaw('descripcion_material ASC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }

    //guardar material
    public function storematerial(Request $request){
        $cod_material=DB::select("SELECT max(id_material) as id_material from material_mobiliario");
        $cod=1;
        if(count($cod_material)>0){
            $cod=$cod_material[0]->id_material+1;
        }
        $material=new MaterialMobiliario();
        $material->id_material=$cod;
        $material->descripcion_material=$request->descripcion_material;
        $material->id_empresa=$request->id_empresa;
        $material->save();
    }

    //actualizar material
    public function updatematerial(Request $request){
        $material=MaterialMobiliario::find($request->id);
        $material->descripcion_material=$request->descripcion_material;
        $material->save();
    }

    //eliminar material
    public function deletematerial($id){
        MaterialMobiliario::where("id_material","=",$id)->delete();
    }

    //listar modelo
    public function indexmodelo(Request $request, $id){
        $buscar = $request->buscar;
        $empresa = $id;
        if ($buscar == '') { 
            $recupera = ModeloMobiliario::select("*")
             ->where("id_empresa", "=", $empresa)
            ->orderByRaw('nombre_modelo ASC')->get();
        } else {
            $recupera = ModeloMobiliario::select('*')
                ->where(function ($q) use ($buscar) {
                    $q->where('nombre_modelo', 'like', '%'.$buscar.'%');
                })
                 ->where("id_empresa", "=", $empresa)
                ->orderByRaw('nombre_modelo ASC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }

    //guardar modelo
    public function storemodelo(Request $request){
        $cod_modelo=DB::select("SELECT max(id_modelo) as id_modelo from modelo_mobiliario");
        $cod=1;
        if(count($cod_modelo)>0){
            $cod=$cod_modelo[0]->id_modelo+1;
        }
        $modelo=new ModeloMobiliario();
        $modelo->id_modelo=$cod;
        $modelo->nombre_modelo=$request->nombre_modelo;
        $modelo->id_empresa=$request->id_empresa;
        $modelo->save();
    }

    //actualizar modelo
    public function updatemodelo(Request $request){
        $modelo=ModeloMobiliario::find($request->id);
        $modelo->nombre_modelo=$request->nombre_modelo;
        $modelo->save();
    }

    //eliminar modelo
    public function deletemodelo($id){
        ModeloMobiliario::where("id_modelo","=",$id)->delete();
    }

    //listar ubicacion general
    public function indexubicaciongeneral(Request $request, $id){
        $buscar = $request->buscar;
        $empresa = $id;
        if ($buscar == '') { 
            $recupera = UbicacionGeneralMobiliario::select("*")
             ->where("id_empresa", "=", $empresa)
            ->orderByRaw('descripcion_ubicacion_general ASC')->get();
        } else {
            $recupera = UbicacionGeneralMobiliario::select('*')
                ->where(function ($q) use ($buscar) {
                    $q->where('descripcion_ubicacion_general', 'like', '%'.$buscar.'%');
                })
                 ->where("id_empresa", "=", $empresa)
                ->orderByRaw('descripcion_ubicacion_general ASC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }

    //guardar ubicacion general
    public function storeubicaciongeneral(Request $request){
        $cod_ubicacion_general=DB::select("SELECT max(id_ubicacion_general) as id_ubicacion_general from ubicacion_general_mobiliario");
        $cod=1;
        if(count($cod_ubicacion_general)>0){
            $cod=$cod_ubicacion_general[0]->id_ubicacion_general+1;
        }
        $ubicacion=new UbicacionGeneralMobiliario();
        $ubicacion->id_ubicacion_general=$cod;
        $ubicacion->descripcion_ubicacion_general=$request->descripcion_ubicaciongeneral;
        $ubicacion->id_empresa=$request->id_empresa;
        $ubicacion->save();
    }

    //actualizar ubicacion general
    public function updateubicaciongeneral(Request $request){
        $ubicacion=UbicacionGeneralMobiliario::find($request->id);
        $ubicacion->descripcion_ubicacion_general=$request->descripcion_ubicaciongeneral;
        $ubicacion->save();
    }

    //eliminar ubicacion general
    public function deleteubicaciongeneral($id){
        UbicacionGeneralMobiliario::where("id_ubicacion_general","=",$id)->delete();
    }

    //listar ubicacion especifica
    public function indexubicacionespecifica(Request $request, $id){
        $buscar = $request->buscar;
        $empresa = $id;
        if ($buscar == '') { 
            $recupera = UbicacionEspecificaMobiliario::select("*")
                ->join('ubicacion_general_mobiliario', 'ubicacion_especifica_mobiliario.id_ubicacion_general', '=', 'ubicacion_general_mobiliario.id_ubicacion_general')
                ->where("ubicacion_especifica_mobiliario.id_empresa", "=", $empresa)
                ->orderByRaw('descripcion_ubicacion_especifica ASC')->get();
        } else {
            $recupera = UbicacionEspecificaMobiliario::select('*')
                ->join('ubicacion_general_mobiliario', 'ubicacion_especifica_mobiliario.id_ubicacion_general', '=', 'ubicacion_general_mobiliario.id_ubicacion_general')
                ->where(function ($q) use ($buscar) {
                    $q->where('descripcion_ubicacion_especifica', 'like', '%'.$buscar.'%');
                })
                ->where("ubicacion_especifica_mobiliario.id_empresa", "=", $empresa)
                ->orderByRaw('descripcion_ubicacion_especifica ASC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }

    //listar ubicacion especifica por ubicacion general
    public function listarubicacionespecifica(Request $request, $id){
        $buscar = $request->buscar;
        $ubicaciongeneral = $request->ubicaciongeneral;
        $empresa = $id;
        if ($buscar == '') { 
            $recupera = UbicacionEspecificaMobiliario::select("*")
             ->where("id_empresa", "=", $empresa)
             ->where("id_ubicacion_general", "=", $ubicaciongeneral)
            ->orderByRaw('descripcion_ubicacion_especifica ASC')->get();
        } else {
            $recupera = UbicacionEspecificaMobiliario::select('*')
                ->where(function ($q) use ($buscar) {
                    $q->where('descripcion_ubicacion_especifica', 'like', '%'.$buscar.'%');
                })
                 ->where("id_empresa", "=", $empresa)
                 ->where("id_ubicacion_general", "=", $ubicaciongeneral)
                ->orderByRaw('descripcion_ubicacion_especifica ASC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }

    //guardar ubicacion especifica
    public function storeubicacionespecifica(Request $request){
        $cod_ubicacion_especifica=DB::select("SELECT max(id_ubicacion_especifica) as id_ubicacion_especifica from ubicacion_especifica_mobiliario");
        $cod=1;
        if(count($cod_ubicacion_especifica)>0){
            $cod=$cod_ubicacion_especifica[0]->id_ubicacion_especifica+1;
        }
        $ubicacion=new UbicacionEspecificaMobiliario();
        $ubicacion->id_ubicacion_especifica=$cod;
        $ubicacion->descripcion_ubicacion_especifica=$request->descripcion_ubicacionespecifica;
        $ubicacion->id_ubicacion_general=$request->ubicaciongeneral;
        $ubicacion->id_empresa=$request->id_empresa;
        $ubicacion->save();
    }

    //actualizar ubicacion especifica
    public function updateubicacionespecifica(Request $request){
        $ubicacion=UbicacionEspecificaMobiliario::find($request->id);
        $ubicacion->descripcion_ubicacion_especifica=$request->descripcion_ubicacionespecifica;
        $ubicacion->id_ubicacion_general=$request->ubicaciongeneral;
        $ubicacion->save();
    }

    //eliminar ubicacion especifica
    public function deleteubicacionespecifica($id){
        UbicacionEspecificaMobiliario::where("id_ubicacion_especifica","=",$id)->delete();
    }

    //listar identificador
    public function indexidentificador(Request $request, $id){
        $buscar = $request->buscar;
        $empresa = $id;
        if ($buscar == '') { 
            $recupera = IdentificadorMobiliario::select("*")
             ->where("id_empresa", "=", $empresa)
            ->orderByRaw('descripcion_identificador ASC')->get();
        } else {
            $recupera = IdentificadorMobiliario::select('*')
                ->where(function ($q) use ($buscar) {
                    $q->where('descripcion_identificador', 'like', '%'.$buscar.'%');
                })
                 ->where("id_empresa", "=", $empresa)
                ->orderByRaw('descripcion_identificador ASC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }

    //guardar identificador
    public function storeidentificador(Request $request){
        $cod_identificador=DB::select("SELECT max(id_identificador) as id_identificador from identificador_mobiliario");
        $cod=1;
        if(count($cod_identificador)>0){
            $cod=$cod_identificador[0]->id_identificador+1;
        }
        $identificador=new IdentificadorMobiliario();
        $identificador->id_identificador=$cod;
        $identificador->descripcion_identificador=$request->descripcion_identificador;
        $identificador->id_empresa=$request->id_empresa;
        $identificador->save();
    }

    //actualizar identificador
    public function updateidentificador(Request $request){
        $identificador=IdentificadorMobiliario::find($request->id);
        $identificador->descripcion_identificador=$request->descripcion_identificador;
        $identificador->save();
    }

    //eliminar identificador
    public function deleteidentificador($id){
        IdentificadorMobiliario::where("id_identificador","=",$id)->delete();
    }

    //listar tipo
    public function indextipo(Request $request, $id){
        $buscar = $request->buscar;
        $empresa = $id;
        if ($buscar == '') { 
            $recupera = TipoMobiliario::select("*")
             ->where("id_empresa", "=", $empresa)
            ->orderByRaw('descripcion_tipo ASC')->get();
        } else {
            $recupera = TipoMobiliario::select('*')
                ->where(function ($q) use ($buscar) {
                    $q->where('descripcion_tipo', 'like', '%'.$buscar.'%');
                })
                 ->where("id_empresa", "=", $empresa)
                ->orderByRaw('descripcion_tipo ASC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }

    //guardar tipo
    public function storetipo(Request $request){
        $cod_tipo=DB::select("SELECT max(id_tipo) as id_tipo from tipo_mobiliario");
        $cod=1;
        if(count($cod_tipo)>0){
            $cod=$cod_tipo[0]->id_tipo+1;
        }
        $tipo=new TipoMobiliario();
        $tipo->id_tipo=$cod;
        $tipo->descripcion_tipo=$request->descripcion_tipo;
        $tipo->id_empresa=$request->id_empresa;
        $tipo->save();
    }

    //actualizar tipo
    public function updatetipo(Request $request){
        $tipo=TipoMobiliario::find($request->id);
        $tipo->descripcion_tipo=$request->descripcion_tipo;
        $tipo->save();
    }

    //eliminar tipo
    public function deletetipo($id){
        TipoMobiliario::where("id_tipo","=",$id)->delete();
    }

    //Listar vehiculo
    public function indexvehiculo(Request $request, $id){
        $buscar = $request->buscar;
        $empresa = $id;
        if ($buscar == '') { 
            /*$recupera = VehiculoMobiliario::select("*")
                ->leftJoin('marca_mobiliario', 'vehiculo_mobiliario.id_marca', '=', 'marca_mobiliario.id_marca')
                ->leftJoin('modelo_mobiliario', 'vehiculo_mobiliario.id_modelo', '=', 'modelo_mobiliario.id_modelo')
                ->leftJoin('tipo_activo_mobiliario', 'vehiculo_mobiliario.id_tipo_activo', '=', 'tipo_activo_mobiliario.id_tipo_activo_mobiliario')
                ->leftJoin('color_mobiliario', 'vehiculo_mobiliario.id_color', '=', 'color_mobiliario.id_color')
                ->leftJoin('color_mobiliario', 'vehiculo_mobiliario.id_color_secundario', '=', 'color_mobiliario.id_color')
                //->leftJoin('material_mobiliario', 'vehiculo_mobiliario.id_material', '=', 'material_mobiliario.id_material')
                ->leftJoin('conservacion_mobiliario', 'vehiculo_mobiliario.id_conservacion', '=', 'conservacion_mobiliario.id_conservacion')
                ->leftJoin('mantenimiento_mobiliario', 'vehiculo_mobiliario.id_mantenimiento', '=', 'mantenimiento_mobiliario.id_mantenimiento')
                ->leftJoin('ubicacion_general_mobiliario', 'vehiculo_mobiliario.id_ubicaciongeneral', '=', 'ubicacion_general_mobiliario.id_ubicacion_general')
                ->leftJoin('ubicacion_especifica_mobiliario', 'vehiculo_mobiliario.id_ubicacionespecifica', '=', 'ubicacion_especifica_mobiliario.id_ubicacion_especifica')
                ->leftJoin('custodio_mobiliario', 'vehiculo_mobiliario.id_custodio', '=', 'custodio_mobiliario.id_custodio')
                 ->where("vehiculo_mobiliario.id_empresa", "=", $empresa)
                ->orderByRaw('codigo_identificacion_vehiculo ASC')->get();*/
            $recupera = DB::select("select *, cs.nombre_color as 'nombre_color_secundario', cp.nombre_color as 'nombre_color' from `vehiculo_mobiliario` left join `marca_mobiliario` on `vehiculo_mobiliario`.`id_marca` = `marca_mobiliario`.`id_marca` left join `modelo_mobiliario` on `vehiculo_mobiliario`.`id_modelo` = `modelo_mobiliario`.`id_modelo` left join `tipo_activo_mobiliario` on `vehiculo_mobiliario`.`id_tipo_activo` = `tipo_activo_mobiliario`.`id_tipo_activo_mobiliario` left join `color_mobiliario` cp on `vehiculo_mobiliario`.`id_color` = `cp`.`id_color` left join `color_mobiliario` cs on `vehiculo_mobiliario`.`id_color_secundario` = `cs`.`id_color` left join `tipo_mobiliario` on `vehiculo_mobiliario`.`id_tipo` = `tipo_mobiliario`.`id_tipo` left join `conservacion_mobiliario` on `vehiculo_mobiliario`.`id_conservacion` = `conservacion_mobiliario`.`id_conservacion` left join `mantenimiento_mobiliario` on `vehiculo_mobiliario`.`id_mantenimiento` = `mantenimiento_mobiliario`.`id_mantenimiento` left join `ubicacion_general_mobiliario` on `vehiculo_mobiliario`.`id_ubicaciongeneral` = `ubicacion_general_mobiliario`.`id_ubicacion_general` left join `ubicacion_especifica_mobiliario` on `vehiculo_mobiliario`.`id_ubicacionespecifica` = `ubicacion_especifica_mobiliario`.`id_ubicacion_especifica` left join `custodio_mobiliario` on `vehiculo_mobiliario`.`id_custodio` = `custodio_mobiliario`.`id_custodio` where `vehiculo_mobiliario`.`id_empresa` = '".$empresa."' order by codigo_identificacion_vehiculo ASC");
        } else {
            /*$recupera = vehiculoMobiliario::select('*')
                ->leftJoin('marca_mobiliario', 'vehiculo_mobiliario.id_marca', '=', 'marca_mobiliario.id_marca')
                ->leftJoin('modelo_mobiliario', 'vehiculo_mobiliario.id_modelo', '=', 'modelo_mobiliario.id_modelo')
                ->leftJoin('tipo_activo_mobiliario', 'vehiculo_mobiliario.id_tipo_activo', '=', 'tipo_activo_mobiliario.id_tipo_activo_mobiliario')
                ->leftJoin('color_mobiliario', 'vehiculo_mobiliario.id_color', '=', 'color_mobiliario.id_color')
                ->leftJoin('color_mobiliario', 'vehiculo_mobiliario.id_color_secundario', '=', 'color_mobiliario.id_color')
                //->leftJoin('material_mobiliario', 'vehiculo_mobiliario.id_material', '=', 'material_mobiliario.id_material')
                ->leftJoin('conservacion_mobiliario', 'vehiculo_mobiliario.id_conservacion', '=', 'conservacion_mobiliario.id_conservacion')
                ->leftJoin('mantenimiento_mobiliario', 'vehiculo_mobiliario.id_mantenimiento', '=', 'mantenimiento_mobiliario.id_mantenimiento')
                ->leftJoin('ubicacion_general_mobiliario', 'vehiculo_mobiliario.id_ubicaciongeneral', '=', 'ubicacion_general_mobiliario.id_ubicacion_general')
                ->leftJoin('ubicacion_especifica_mobiliario', 'vehiculo_mobiliario.id_ubicacionespecifica', '=', 'ubicacion_especifica_mobiliario.id_ubicacion_especifica')
                ->leftJoin('custodio_mobiliario', 'vehiculo_mobiliario.id_custodio', '=', 'custodio_mobiliario.id_custodio')
                ->where(function ($q) use ($buscar) {
                    $q->where('codigo_identificacion_vehiculo', 'like', '%'.$buscar.'%');
                    $q->orWhere('descripcion_vehiculo', 'like', '%'.$buscar.'%');
                    $q->orWhere('nombre_marca', 'like', '%'.$buscar.'%');
                    $q->orWhere('nombre_modelo', 'like', '%'.$buscar.'%');
                    $q->orWhere('placa_vehiculo', 'like', '%'.$buscar.'%');
                    $q->orWhere('cedula_custodio', 'like', '%'.$buscar.'%');
                    $q->orWhere('nombre_custodio', 'like', '%'.$buscar.'%');
                })
                 ->where("vehiculo_mobiliario.id_empresa", "=", $empresa)
                ->orderByRaw('codigo_identificacion_vehiculo ASC')->get();*/
            $recupera = DB::select("select *, cs.nombre_color as 'nombre_color_secundario', cp.nombre_color as 'nombre_color' from `vehiculo_mobiliario` left join `marca_mobiliario` on `vehiculo_mobiliario`.`id_marca` = `marca_mobiliario`.`id_marca` left join `modelo_mobiliario` on `vehiculo_mobiliario`.`id_modelo` = `modelo_mobiliario`.`id_modelo` left join `tipo_activo_mobiliario` on `vehiculo_mobiliario`.`id_tipo_activo` = `tipo_activo_mobiliario`.`id_tipo_activo_mobiliario` left join `color_mobiliario` cp on `vehiculo_mobiliario`.`id_color` = `cp`.`id_color` left join `color_mobiliario` cs on `vehiculo_mobiliario`.`id_color_secundario` = `cs`.`id_color` left join `tipo_mobiliario` on `vehiculo_mobiliario`.`id_tipo` = `tipo_mobiliario`.`id_tipo` left join `conservacion_mobiliario` on `vehiculo_mobiliario`.`id_conservacion` = `conservacion_mobiliario`.`id_conservacion` left join `mantenimiento_mobiliario` on `vehiculo_mobiliario`.`id_mantenimiento` = `mantenimiento_mobiliario`.`id_mantenimiento` left join `ubicacion_general_mobiliario` on `vehiculo_mobiliario`.`id_ubicaciongeneral` = `ubicacion_general_mobiliario`.`id_ubicacion_general` left join `ubicacion_especifica_mobiliario` on `vehiculo_mobiliario`.`id_ubicacionespecifica` = `ubicacion_especifica_mobiliario`.`id_ubicacion_especifica` left join `custodio_mobiliario` on `vehiculo_mobiliario`.`id_custodio` = `custodio_mobiliario`.`id_custodio` where (`codigo_identificacion_vehiculo` like '%".$buscar."%' or `descripcion_vehiculo` like '%".$buscar."%' or `nombre_marca` like '%".$buscar."%' or `nombre_modelo` like '%".$buscar."%' or `placa_vehiculo` like '%".$buscar."%' or `cedula_custodio` like '%".$buscar."%' or `nombre_custodio` like '%".$buscar."%') and `vehiculo_mobiliario`.`id_empresa` = '".$empresa."' order by codigo_identificacion_vehiculo ASC");
        }
        return [
            'recupera' => $recupera
        ];
    }

    //Buscar vehiculo por id
    public function buscarvehiculo($id){
        /*$recupera = VehiculoMobiliario::select("*")
            ->leftJoin('marca_mobiliario', 'vehiculo_mobiliario.id_marca', '=', 'marca_mobiliario.id_marca')
            ->leftJoin('modelo_mobiliario', 'vehiculo_mobiliario.id_modelo', '=', 'modelo_mobiliario.id_modelo')
            ->leftJoin('tipo_activo_mobiliario', 'vehiculo_mobiliario.id_tipo_activo', '=', 'tipo_activo_mobiliario.id_tipo_activo_mobiliario')
            ->leftJoin('color_mobiliario', 'vehiculo_mobiliario.id_color', '=', 'color_mobiliario.id_color')
            ->leftJoin('color_mobiliario', 'vehiculo_mobiliario.id_color_secundario', '=', 'color_mobiliario.id_color')
            //->leftJoin('material_mobiliario', 'vehiculo_mobiliario.id_material', '=', 'material_mobiliario.id_material')
            ->leftJoin('conservacion_mobiliario', 'vehiculo_mobiliario.id_conservacion', '=', 'conservacion_mobiliario.id_conservacion')
            ->leftJoin('mantenimiento_mobiliario', 'vehiculo_mobiliario.id_mantenimiento', '=', 'mantenimiento_mobiliario.id_mantenimiento')
            ->leftJoin('ubicacion_general_mobiliario', 'vehiculo_mobiliario.id_ubicaciongeneral', '=', 'ubicacion_general_mobiliario.id_ubicacion_general')
            ->leftJoin('ubicacion_especifica_mobiliario', 'vehiculo_mobiliario.id_ubicacionespecifica', '=', 'ubicacion_especifica_mobiliario.id_ubicacion_especifica')
            ->leftJoin('custodio_mobiliario', 'vehiculo_mobiliario.id_custodio', '=', 'custodio_mobiliario.id_custodio')
             ->where("vehiculo_mobiliario.id_vehiculo", "=", $id)
            ->orderByRaw('codigo_identificacion_vehiculo ASC')->get();*/
        $recupera = DB::select("select *, cs.nombre_color as 'nombre_color_secundario', cp.nombre_color as 'nombre_color' from `vehiculo_mobiliario` left join `marca_mobiliario` on `vehiculo_mobiliario`.`id_marca` = `marca_mobiliario`.`id_marca` left join `modelo_mobiliario` on `vehiculo_mobiliario`.`id_modelo` = `modelo_mobiliario`.`id_modelo` left join `tipo_activo_mobiliario` on `vehiculo_mobiliario`.`id_tipo_activo` = `tipo_activo_mobiliario`.`id_tipo_activo_mobiliario` left join `color_mobiliario` cp on `vehiculo_mobiliario`.`id_color` = `cp`.`id_color` left join `color_mobiliario` cs on `vehiculo_mobiliario`.`id_color_secundario` = `cs`.`id_color` left join `tipo_mobiliario` on `vehiculo_mobiliario`.`id_tipo` = `tipo_mobiliario`.`id_tipo` left join `conservacion_mobiliario` on `vehiculo_mobiliario`.`id_conservacion` = `conservacion_mobiliario`.`id_conservacion` left join `mantenimiento_mobiliario` on `vehiculo_mobiliario`.`id_mantenimiento` = `mantenimiento_mobiliario`.`id_mantenimiento` left join `ubicacion_general_mobiliario` on `vehiculo_mobiliario`.`id_ubicaciongeneral` = `ubicacion_general_mobiliario`.`id_ubicacion_general` left join `ubicacion_especifica_mobiliario` on `vehiculo_mobiliario`.`id_ubicacionespecifica` = `ubicacion_especifica_mobiliario`.`id_ubicacion_especifica` left join `custodio_mobiliario` on `vehiculo_mobiliario`.`id_custodio` = `custodio_mobiliario`.`id_custodio` where `vehiculo_mobiliario`.`id_vehiculo` = '".$id."'");
        
        return [
            'recupera' => $recupera
        ];
    }

    //guardar vehiculo
    public function storevehiculo(Request $request){
        ini_set('max_execution_time', 53200);

        $carpetanombre = constant("DATA_EMPRESA");
        $now = Carbon::now();

        if ($request->file('file_imagen')) {
            $file_imagen = $request->file('file_imagen');
            $nombre_imagen = time() . $file_imagen->getClientOriginalName();
        }

        $cod_vehiculo=DB::select("SELECT max(id_vehiculo) as id_vehiculo from vehiculo_mobiliario");
        $cod=1;
        if(count($cod_vehiculo)>0){
            $cod=$cod_vehiculo[0]->id_vehiculo+1;
        }
        $vehiculo=new VehiculoMobiliario();
        $vehiculo->id_vehiculo=$cod;

        $vehiculo->id_tipo_activo=$request->tipo_activo;
        $vehiculo->id_identificador=$request->identificador;
        $vehiculo->codigo_identificacion_vehiculo=$request->codigo_identificacion;
        $vehiculo->codigo_anterior_vehiculo=$request->codigo_anterior;
        $vehiculo->nombre_bien_vehiculo=$request->nombre_bien;
        $vehiculo->descripcion_vehiculo=$request->descripcion;
        $vehiculo->id_marca=$request->marca;
        $vehiculo->id_modelo=$request->modelo;
        $vehiculo->id_color=$request->color;
        $vehiculo->id_color_secundario=$request->color_secundario;
        $vehiculo->id_tipo=$request->tipo;
        //$vehiculo->id_material=$request->material;
        $vehiculo->ano_fabricacion_vehiculo=$request->ano_fabricacion;
        $vehiculo->id_conservacion=$request->conservacion;
        $vehiculo->id_mantenimiento=$request->mantenimiento;
        $vehiculo->fechacompra_vehiculo=$request->fechacompra;
        $vehiculo->costoadquisicion_vehiculo=$request->costoadquisicion;
        $vehiculo->combustible_vehiculo=$request->combustible;
        $vehiculo->motor_vehiculo=$request->motor;
        $vehiculo->placa_vehiculo=$request->placa;
        $vehiculo->chasis_vehiculo=$request->chasis;
        $vehiculo->kilometraje_vehiculo=$request->kilometraje;
        $vehiculo->vehiculo_vehiculo=$request->vehiculo;
        $vehiculo->id_ubicaciongeneral=$request->ubicaciongeneral;
        $vehiculo->id_ubicacionespecifica=$request->ubicacionespecifica;
        $vehiculo->id_custodio=$request->custodio;
        $vehiculo->cuentacontable_vehiculo=$request->cuentacontable;
        $vehiculo->observaciones_vehiculo=$request->observaciones;

        $vehiculo->id_empresa=$request->id_empresa;
        $vehiculo->save();
        $id = $cod;
        $id_empresa=$request->id_empresa;

        if ($request->file('file_imagen')) {
            $recupera_ubicacion_imagen = $nombre_imagen;
        } else {
            $recupera_ubicacion_imagen = "";
        }

        $vehiculof = VehiculoMobiliario::findOrFail($id);
        $vehiculof->imagen1 = $recupera_ubicacion_imagen;
        $vehiculof->save();

        $carpetaprincipal = $carpetanombre;
        if (!file_exists($carpetaprincipal)) {
            if (!mkdir($carpetaprincipal, 0777, true)) {
                DB::delete("DELETE FROM vehiculo_mobiliario WHERE id_vehiculo = $id");
            }
        }

        //carpetas
        if (!file_exists($carpetaprincipal)) {
            mkdir($carpetaprincipal, 0777, true);
        }
        $carpetaprincipal = $carpetanombre;
        if (!file_exists($carpetaprincipal)) {
            mkdir($carpetaprincipal, 0777, true);
        }
        //archivos
        $carpeta7 = $carpetanombre . $id_empresa . "/imagen";
        if (!file_exists($carpeta7)) {
            mkdir($carpeta7, 0777, true);
        }
        //fincarpetas

        if ($request->file('file_imagen')) {
            $ubicacion_imagen = $carpetanombre . $id_empresa . "/imagen/";
            $request->file('file_imagen')->move($ubicacion_imagen, $nombre_imagen);
            //copy($ubicacion_imagen . $nombre_imagen, storage_path('mobiliario/') . $nombre_imagen);
            //$request->file('file_imagen')->move(storage_path('logos'), $nombre_imagen);
        }
    }

    //actualizar vehiculo
    public function updatevehiculo(Request $request){
        $carpetanombre = constant("DATA_EMPRESA");
        $now = Carbon::now();
        if (!$request->recuperaimagen) {
            if ($request->file('file_imagen')) {
                $file_imagen = $request->file('file_imagen');
                $nombre_imagen = time() . $file_imagen->getClientOriginalName();
            } else {
                $nombre_imagen = "";
            }
        }

        $vehiculo=VehiculoMobiliario::find($request->id_vehiculo);
        $vehiculo->id_tipo_activo=$request->tipo_activo;
        $vehiculo->id_identificador=$request->identificador;
        $vehiculo->codigo_identificacion_vehiculo=$request->codigo_identificacion;
        $vehiculo->codigo_anterior_vehiculo=$request->codigo_anterior;
        $vehiculo->nombre_bien_vehiculo=$request->nombre_bien;
        $vehiculo->descripcion_vehiculo=$request->descripcion;
        $vehiculo->id_marca=$request->marca;
        $vehiculo->id_modelo=$request->modelo;
        $vehiculo->id_color=$request->color;
        $vehiculo->id_color_secundario=$request->color_secundario;
        $vehiculo->id_tipo=$request->tipo;
        //$vehiculo->id_material=$request->material;
        $vehiculo->ano_fabricacion_vehiculo=$request->ano_fabricacion;
        $vehiculo->id_conservacion=$request->conservacion;
        $vehiculo->id_mantenimiento=$request->mantenimiento;
        $vehiculo->fechacompra_vehiculo=$request->fechacompra;
        $vehiculo->costoadquisicion_vehiculo=$request->costoadquisicion;
        $vehiculo->combustible_vehiculo=$request->combustible;
        $vehiculo->motor_vehiculo=$request->motor;
        $vehiculo->placa_vehiculo=$request->placa;
        $vehiculo->chasis_vehiculo=$request->chasis;
        $vehiculo->kilometraje_vehiculo=$request->kilometraje;
        $vehiculo->vehiculo_vehiculo=$request->vehiculo;
        $vehiculo->id_ubicaciongeneral=$request->ubicaciongeneral;
        $vehiculo->id_ubicacionespecifica=$request->ubicacionespecifica;
        $vehiculo->id_custodio=$request->custodio;
        $vehiculo->cuentacontable_vehiculo=$request->cuentacontable;
        $vehiculo->observaciones_vehiculo=$request->observaciones;
        if (!$request->recuperaimagen) {
            $vehiculo->imagen1 = $nombre_imagen;
        }
        $vehiculo->save();
        $id_empresa=$request->id_empresa;

        if (!$request->recuperaimagen) {
            if ($request->file('file_imagen')) {
                $ubicacion_imagen = $carpetanombre . $id_empresa . "/imagen/";
                $request->file('file_imagen')->move($ubicacion_imagen, $nombre_imagen);
                //copy($ubicacion_imagen . $nombre_imagen, storage_path('logos/') . $nombre_imagen);
            }
        }
    }

    //eliminar vehiculo
    public function deletevehiculo($id){
        VehiculoMobiliario::where("id_vehiculo","=",$id)->delete();
    }

    //Listar maquina
    public function indexmaquina(Request $request, $id){
        $buscar = $request->buscar;
        $empresa = $id;
        if ($buscar == '') { 
            $recupera = MaquinaMobiliario::select("*")
                ->leftJoin('marca_mobiliario', 'maquina_mobiliario.id_marca', '=', 'marca_mobiliario.id_marca')
                ->leftJoin('modelo_mobiliario', 'maquina_mobiliario.id_modelo', '=', 'modelo_mobiliario.id_modelo')
                ->leftJoin('tipo_activo_mobiliario', 'maquina_mobiliario.id_tipo_activo', '=', 'tipo_activo_mobiliario.id_tipo_activo_mobiliario')
                ->leftJoin('color_mobiliario', 'maquina_mobiliario.id_color', '=', 'color_mobiliario.id_color')
                ->leftJoin('material_mobiliario', 'maquina_mobiliario.id_material', '=', 'material_mobiliario.id_material')
                ->leftJoin('conservacion_mobiliario', 'maquina_mobiliario.id_conservacion', '=', 'conservacion_mobiliario.id_conservacion')
                ->leftJoin('mantenimiento_mobiliario', 'maquina_mobiliario.id_mantenimiento', '=', 'mantenimiento_mobiliario.id_mantenimiento')
                ->leftJoin('ubicacion_general_mobiliario', 'maquina_mobiliario.id_ubicaciongeneral', '=', 'ubicacion_general_mobiliario.id_ubicacion_general')
                ->leftJoin('ubicacion_especifica_mobiliario', 'maquina_mobiliario.id_ubicacionespecifica', '=', 'ubicacion_especifica_mobiliario.id_ubicacion_especifica')
                ->leftJoin('custodio_mobiliario', 'maquina_mobiliario.id_custodio', '=', 'custodio_mobiliario.id_custodio')
                ->where("maquina_mobiliario.id_empresa", "=", $empresa)
                ->orderByRaw('codigo_identificacion_maquina ASC')->get();
        } else {
            $recupera = MaquinaMobiliario::select('*')
                ->leftJoin('marca_mobiliario', 'maquina_mobiliario.id_marca', '=', 'marca_mobiliario.id_marca')
                ->leftJoin('modelo_mobiliario', 'maquina_mobiliario.id_modelo', '=', 'modelo_mobiliario.id_modelo')
                ->leftJoin('tipo_activo_mobiliario', 'maquina_mobiliario.id_tipo_activo', '=', 'tipo_activo_mobiliario.id_tipo_activo_mobiliario')
                ->leftJoin('color_mobiliario', 'maquina_mobiliario.id_color', '=', 'color_mobiliario.id_color')
                ->leftJoin('material_mobiliario', 'maquina_mobiliario.id_material', '=', 'material_mobiliario.id_material')
                ->leftJoin('conservacion_mobiliario', 'maquina_mobiliario.id_conservacion', '=', 'conservacion_mobiliario.id_conservacion')
                ->leftJoin('mantenimiento_mobiliario', 'maquina_mobiliario.id_mantenimiento', '=', 'mantenimiento_mobiliario.id_mantenimiento')
                ->leftJoin('ubicacion_general_mobiliario', 'maquina_mobiliario.id_ubicaciongeneral', '=', 'ubicacion_general_mobiliario.id_ubicacion_general')
                ->leftJoin('ubicacion_especifica_mobiliario', 'maquina_mobiliario.id_ubicacionespecifica', '=', 'ubicacion_especifica_mobiliario.id_ubicacion_especifica')
                ->leftJoin('custodio_mobiliario', 'maquina_mobiliario.id_custodio', '=', 'custodio_mobiliario.id_custodio')
                ->where(function ($q) use ($buscar) {
                    $q->where('codigo_identificacion_maquina', 'like', '%'.$buscar.'%');
                    $q->orWhere('descripcion_actualizada_maquina', 'like', '%'.$buscar.'%');
                    $q->orWhere('nombre_marca', 'like', '%'.$buscar.'%');
                    $q->orWhere('nombre_modelo', 'like', '%'.$buscar.'%');
                    $q->orWhere('serie_maquina', 'like', '%'.$buscar.'%');
                    $q->orWhere('cedula_custodio', 'like', '%'.$buscar.'%');
                    $q->orWhere('nombre_custodio', 'like', '%'.$buscar.'%');
                })
                ->where("maquina_mobiliario.id_empresa", "=", $empresa)
                ->orderByRaw('codigo_identificacion_maquina ASC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }

    //Buscar maquina por id
    public function buscarmaquina($id){
        $recupera = MaquinaMobiliario::select("*")
            ->leftJoin('marca_mobiliario', 'maquina_mobiliario.id_marca', '=', 'marca_mobiliario.id_marca')
            ->leftJoin('modelo_mobiliario', 'maquina_mobiliario.id_modelo', '=', 'modelo_mobiliario.id_modelo')
            ->leftJoin('tipo_activo_mobiliario', 'maquina_mobiliario.id_tipo_activo', '=', 'tipo_activo_mobiliario.id_tipo_activo_mobiliario')
            ->leftJoin('color_mobiliario', 'maquina_mobiliario.id_color', '=', 'color_mobiliario.id_color')
            ->leftJoin('material_mobiliario', 'maquina_mobiliario.id_material', '=', 'material_mobiliario.id_material')
            ->leftJoin('conservacion_mobiliario', 'maquina_mobiliario.id_conservacion', '=', 'conservacion_mobiliario.id_conservacion')
            ->leftJoin('mantenimiento_mobiliario', 'maquina_mobiliario.id_mantenimiento', '=', 'mantenimiento_mobiliario.id_mantenimiento')
            ->leftJoin('ubicacion_general_mobiliario', 'maquina_mobiliario.id_ubicaciongeneral', '=', 'ubicacion_general_mobiliario.id_ubicacion_general')
            ->leftJoin('ubicacion_especifica_mobiliario', 'maquina_mobiliario.id_ubicacionespecifica', '=', 'ubicacion_especifica_mobiliario.id_ubicacion_especifica')
            ->leftJoin('custodio_mobiliario', 'maquina_mobiliario.id_custodio', '=', 'custodio_mobiliario.id_custodio')
             ->where("maquina_mobiliario.id_maquina", "=", $id)
            ->orderByRaw('codigo_identificacion_maquina ASC')->get();
        
        return [
            'recupera' => $recupera
        ];
    }

    //guardar maquina
    public function storemaquina(Request $request){
        $cod_maquina=DB::select("SELECT max(id_maquina) as id_maquina from maquina_mobiliario");
        $cod=1;
        if(count($cod_maquina)>0){
            $cod=$cod_maquina[0]->id_maquina+1;
        }
        $maquina=new MaquinaMobiliario();
        $maquina->id_maquina=$cod;

        $maquina->id_tipo_activo=$request->tipo_activo;
        $maquina->codigo_identificacion_maquina=$request->codigo_identificacion;
        $maquina->codigo_anterior_maquina=$request->codigo_anterior;
        $maquina->descripcion_anterior_maquina=$request->descripcion_anterior;
        $maquina->descripcion_actualizada_maquina=$request->descripcion_actualizada;
        $maquina->id_marca=$request->marca;
        $maquina->id_modelo=$request->modelo;
        $maquina->serie_maquina=$request->serie;
        $maquina->id_color=$request->color;
        $maquina->id_material=$request->material;
        $maquina->id_conservacion=$request->conservacion;
        $maquina->id_mantenimiento=$request->mantenimiento;
        $maquina->fechacompra_maquina=$request->fechacompra;
        $maquina->costoadquisicion_maquina=$request->costoadquisicion;
        $maquina->id_ubicaciongeneral=$request->ubicaciongeneral;
        $maquina->id_ubicacionespecifica=$request->ubicacionespecifica;
        $maquina->id_custodio=$request->custodio;
        $maquina->cuentacontable_maquina=$request->cuentacontable;
        $maquina->observaciones_maquina=$request->observaciones;

        $maquina->id_empresa=$request->id_empresa;
        $maquina->save();
    }

    //actualizar maquina
    public function updatemaquina(Request $request){
        $maquina=MaquinaMobiliario::find($request->id_maquina);
        $maquina->id_tipo_activo=$request->tipo_activo;
        $maquina->codigo_identificacion_maquina=$request->codigo_identificacion;
        $maquina->codigo_anterior_maquina=$request->codigo_anterior;
        $maquina->descripcion_anterior_maquina=$request->descripcion_anterior;
        $maquina->descripcion_actualizada_maquina=$request->descripcion_actualizada;
        $maquina->id_marca=$request->marca;
        $maquina->id_modelo=$request->modelo;
        $maquina->serie_maquina=$request->serie;
        $maquina->id_color=$request->color;
        $maquina->id_material=$request->material;
        $maquina->id_conservacion=$request->conservacion;
        $maquina->id_mantenimiento=$request->mantenimiento;
        $maquina->fechacompra_maquina=$request->fechacompra;
        $maquina->costoadquisicion_maquina=$request->costoadquisicion;
        $maquina->id_ubicaciongeneral=$request->ubicaciongeneral;
        $maquina->id_ubicacionespecifica=$request->ubicacionespecifica;
        $maquina->id_custodio=$request->custodio;
        $maquina->cuentacontable_maquina=$request->cuentacontable;
        $maquina->observaciones_maquina=$request->observaciones;
        $maquina->save();
    }

    //eliminar maquina
    public function deletemaquina($id){
        MaquinaMobiliario::where("id_maquina","=",$id)->delete();
    }

    //Listar enseres
    public function indexenseres(Request $request, $id){
        $buscar = $request->buscar;
        $empresa = $id;
        if ($buscar == '') { 
            $recupera = EnseresMobiliario::select("*")
                ->leftJoin('marca_mobiliario', 'enseres_mobiliario.id_marca', '=', 'marca_mobiliario.id_marca')
                ->leftJoin('dimension_mobiliario', 'enseres_mobiliario.id_dimension', '=', 'dimension_mobiliario.id_dimension')
                ->leftJoin('tipo_activo_mobiliario', 'enseres_mobiliario.id_tipo_activo', '=', 'tipo_activo_mobiliario.id_tipo_activo_mobiliario')
                ->leftJoin('color_mobiliario', 'enseres_mobiliario.id_color', '=', 'color_mobiliario.id_color')
                ->leftJoin('material_mobiliario', 'enseres_mobiliario.id_material', '=', 'material_mobiliario.id_material')
                ->leftJoin('conservacion_mobiliario', 'enseres_mobiliario.id_conservacion', '=', 'conservacion_mobiliario.id_conservacion')
                ->leftJoin('mantenimiento_mobiliario', 'enseres_mobiliario.id_mantenimiento', '=', 'mantenimiento_mobiliario.id_mantenimiento')
                ->leftJoin('ubicacion_general_mobiliario', 'enseres_mobiliario.id_ubicaciongeneral', '=', 'ubicacion_general_mobiliario.id_ubicacion_general')
                ->leftJoin('ubicacion_especifica_mobiliario', 'enseres_mobiliario.id_ubicacionespecifica', '=', 'ubicacion_especifica_mobiliario.id_ubicacion_especifica')
                ->leftJoin('custodio_mobiliario', 'enseres_mobiliario.id_custodio', '=', 'custodio_mobiliario.id_custodio')
                ->where("enseres_mobiliario.id_empresa", "=", $empresa)
                ->orderByRaw('codigo_identificacion_enseres ASC')->get();
        } else {
            $recupera = EnseresMobiliario::select('*')
                ->leftJoin('marca_mobiliario', 'enseres_mobiliario.id_marca', '=', 'marca_mobiliario.id_marca')
                ->leftJoin('dimension_mobiliario', 'enseres_mobiliario.id_dimension', '=', 'dimension_mobiliario.id_dimension')
                ->leftJoin('tipo_activo_mobiliario', 'enseres_mobiliario.id_tipo_activo', '=', 'tipo_activo_mobiliario.id_tipo_activo_mobiliario')
                ->leftJoin('color_mobiliario', 'enseres_mobiliario.id_color', '=', 'color_mobiliario.id_color')
                ->leftJoin('material_mobiliario', 'enseres_mobiliario.id_material', '=', 'material_mobiliario.id_material')
                ->leftJoin('conservacion_mobiliario', 'enseres_mobiliario.id_conservacion', '=', 'conservacion_mobiliario.id_conservacion')
                ->leftJoin('mantenimiento_mobiliario', 'enseres_mobiliario.id_mantenimiento', '=', 'mantenimiento_mobiliario.id_mantenimiento')
                ->leftJoin('ubicacion_general_mobiliario', 'enseres_mobiliario.id_ubicaciongeneral', '=', 'ubicacion_general_mobiliario.id_ubicacion_general')
                ->leftJoin('ubicacion_especifica_mobiliario', 'enseres_mobiliario.id_ubicacionespecifica', '=', 'ubicacion_especifica_mobiliario.id_ubicacion_especifica')
                ->leftJoin('custodio_mobiliario', 'enseres_mobiliario.id_custodio', '=', 'custodio_mobiliario.id_custodio')
                ->where(function ($q) use ($buscar) {
                    $q->where('codigo_identificacion_enseres', 'like', '%'.$buscar.'%');
                    $q->orWhere('descripcion_actualizada_enseres', 'like', '%'.$buscar.'%');
                    $q->orWhere('nombre_marca', 'like', '%'.$buscar.'%');
                    $q->orWhere('cedula_custodio', 'like', '%'.$buscar.'%');
                    $q->orWhere('nombre_custodio', 'like', '%'.$buscar.'%');
                })
                ->where("enseres_mobiliario.id_empresa", "=", $empresa)
                ->orderByRaw('codigo_identificacion_enseres ASC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }

    //Buscar enseres por id
    public function buscarenseres($id){
        $recupera = EnseresMobiliario::select("*")
                ->leftJoin('marca_mobiliario', 'enseres_mobiliario.id_marca', '=', 'marca_mobiliario.id_marca')
                ->leftJoin('dimension_mobiliario', 'enseres_mobiliario.id_dimension', '=', 'dimension_mobiliario.id_dimension')
                ->leftJoin('tipo_activo_mobiliario', 'enseres_mobiliario.id_tipo_activo', '=', 'tipo_activo_mobiliario.id_tipo_activo_mobiliario')
                ->leftJoin('color_mobiliario', 'enseres_mobiliario.id_color', '=', 'color_mobiliario.id_color')
                ->leftJoin('material_mobiliario', 'enseres_mobiliario.id_material', '=', 'material_mobiliario.id_material')
                ->leftJoin('conservacion_mobiliario', 'enseres_mobiliario.id_conservacion', '=', 'conservacion_mobiliario.id_conservacion')
                ->leftJoin('mantenimiento_mobiliario', 'enseres_mobiliario.id_mantenimiento', '=', 'mantenimiento_mobiliario.id_mantenimiento')
                ->leftJoin('ubicacion_general_mobiliario', 'enseres_mobiliario.id_ubicaciongeneral', '=', 'ubicacion_general_mobiliario.id_ubicacion_general')
                ->leftJoin('ubicacion_especifica_mobiliario', 'enseres_mobiliario.id_ubicacionespecifica', '=', 'ubicacion_especifica_mobiliario.id_ubicacion_especifica')
                ->leftJoin('custodio_mobiliario', 'enseres_mobiliario.id_custodio', '=', 'custodio_mobiliario.id_custodio')
             ->where("enseres_mobiliario.id_enseres", "=", $id)
            ->orderByRaw('codigo_identificacion_enseres ASC')->get();
        
        return [
            'recupera' => $recupera
        ];
    }

    //guardar enseres
    public function storeenseres(Request $request){
        $cod_enseres=DB::select("SELECT max(id_enseres) as id_enseres from enseres_mobiliario");
        $cod=1;
        if(count($cod_enseres)>0){
            $cod=$cod_enseres[0]->id_enseres+1;
        }
        $enseres=new EnseresMobiliario();
        $enseres->id_enseres=$cod;

        $enseres->id_tipo_activo=$request->tipo_activo;
        $enseres->codigo_identificacion_enseres=$request->codigo_identificacion;
        $enseres->codigo_anterior_enseres=$request->codigo_anterior;
        $enseres->descripcion_anterior_enseres=$request->descripcion_anterior;
        $enseres->descripcion_actualizada_enseres=$request->descripcion_actualizada;
        $enseres->id_marca=$request->marca;
        $enseres->id_dimension=$request->dimension;
        $enseres->id_color=$request->color;
        $enseres->id_material=$request->material;
        $enseres->id_conservacion=$request->conservacion;
        $enseres->id_mantenimiento=$request->mantenimiento;
        $enseres->fechacompra_enseres=$request->fechacompra;
        $enseres->costoadquisicion_enseres=$request->costoadquisicion;
        $enseres->id_ubicaciongeneral=$request->ubicaciongeneral;
        $enseres->id_ubicacionespecifica=$request->ubicacionespecifica;
        $enseres->id_custodio=$request->custodio;
        $enseres->cuentacontable_enseres=$request->cuentacontable;
        $enseres->observaciones_enseres=$request->observaciones;

        $enseres->id_empresa=$request->id_empresa;
        $enseres->save();
    }

    //actualizar enseres
    public function updateenseres(Request $request){
        $enseres=EnseresMobiliario::find($request->id_enseres);
        $enseres->id_tipo_activo=$request->tipo_activo;
        $enseres->codigo_identificacion_enseres=$request->codigo_identificacion;
        $enseres->codigo_anterior_enseres=$request->codigo_anterior;
        $enseres->descripcion_anterior_enseres=$request->descripcion_anterior;
        $enseres->descripcion_actualizada_enseres=$request->descripcion_actualizada;
        $enseres->id_marca=$request->marca;
        $enseres->id_dimension=$request->dimension;
        $enseres->id_color=$request->color;
        $enseres->id_material=$request->material;
        $enseres->id_conservacion=$request->conservacion;
        $enseres->id_mantenimiento=$request->mantenimiento;
        $enseres->fechacompra_enseres=$request->fechacompra;
        $enseres->costoadquisicion_enseres=$request->costoadquisicion;
        $enseres->id_ubicaciongeneral=$request->ubicaciongeneral;
        $enseres->id_ubicacionespecifica=$request->ubicacionespecifica;
        $enseres->id_custodio=$request->custodio;
        $enseres->cuentacontable_enseres=$request->cuentacontable;
        $enseres->observaciones_enseres=$request->observaciones;
        $enseres->save();
    }

    //eliminar enseres
    public function deleteenseres($id){
        EnseresMobiliario::where("id_enseres","=",$id)->delete();
    }

    //Listar libro
    public function indexlibro(Request $request, $id){
        $buscar = $request->buscar;
        $empresa = $id;
        if ($buscar == '') { 
            $recupera = LibroMobiliario::select("*")
                ->leftJoin('tipo_activo_mobiliario', 'libro_mobiliario.id_tipo_activo', '=', 'tipo_activo_mobiliario.id_tipo_activo_mobiliario')
                ->leftJoin('conservacion_mobiliario', 'libro_mobiliario.id_conservacion', '=', 'conservacion_mobiliario.id_conservacion')
                ->leftJoin('mantenimiento_mobiliario', 'libro_mobiliario.id_mantenimiento', '=', 'mantenimiento_mobiliario.id_mantenimiento')
                ->leftJoin('ubicacion_general_mobiliario', 'libro_mobiliario.id_ubicaciongeneral', '=', 'ubicacion_general_mobiliario.id_ubicacion_general')
                ->leftJoin('ubicacion_especifica_mobiliario', 'libro_mobiliario.id_ubicacionespecifica', '=', 'ubicacion_especifica_mobiliario.id_ubicacion_especifica')
                ->leftJoin('custodio_mobiliario', 'libro_mobiliario.id_custodio', '=', 'custodio_mobiliario.id_custodio')
                ->where("libro_mobiliario.id_empresa", "=", $empresa)
                ->orderByRaw('codigo_identificacion_libro ASC')->get();
        } else {
            $recupera = LibroMobiliario::select('*')
                ->leftJoin('tipo_activo_mobiliario', 'libro_mobiliario.id_tipo_activo', '=', 'tipo_activo_mobiliario.id_tipo_activo_mobiliario')
                ->leftJoin('conservacion_mobiliario', 'libro_mobiliario.id_conservacion', '=', 'conservacion_mobiliario.id_conservacion')
                ->leftJoin('mantenimiento_mobiliario', 'libro_mobiliario.id_mantenimiento', '=', 'mantenimiento_mobiliario.id_mantenimiento')
                ->leftJoin('ubicacion_general_mobiliario', 'libro_mobiliario.id_ubicaciongeneral', '=', 'ubicacion_general_mobiliario.id_ubicacion_general')
                ->leftJoin('ubicacion_especifica_mobiliario', 'libro_mobiliario.id_ubicacionespecifica', '=', 'ubicacion_especifica_mobiliario.id_ubicacion_especifica')
                ->leftJoin('custodio_mobiliario', 'libro_mobiliario.id_custodio', '=', 'custodio_mobiliario.id_custodio')
                ->where(function ($q) use ($buscar) {
                    $q->where('codigo_identificacion_libro', 'like', '%'.$buscar.'%');
                    $q->orWhere('titulo_actualizado_libro', 'like', '%'.$buscar.'%');
                    $q->orWhere('editorial_libro', 'like', '%'.$buscar.'%');
                    $q->orWhere('cedula_custodio', 'like', '%'.$buscar.'%');
                    $q->orWhere('nombre_custodio', 'like', '%'.$buscar.'%');
                })
                ->where("libro_mobiliario.id_empresa", "=", $empresa)
                ->orderByRaw('codigo_identificacion_libro ASC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }

    //Buscar libro por id
    public function buscarlibro($id){
        $recupera = LibroMobiliario::select("*")
                ->leftJoin('tipo_activo_mobiliario', 'libro_mobiliario.id_tipo_activo', '=', 'tipo_activo_mobiliario.id_tipo_activo_mobiliario')
                ->leftJoin('conservacion_mobiliario', 'libro_mobiliario.id_conservacion', '=', 'conservacion_mobiliario.id_conservacion')
                ->leftJoin('mantenimiento_mobiliario', 'libro_mobiliario.id_mantenimiento', '=', 'mantenimiento_mobiliario.id_mantenimiento')
                ->leftJoin('ubicacion_general_mobiliario', 'libro_mobiliario.id_ubicaciongeneral', '=', 'ubicacion_general_mobiliario.id_ubicacion_general')
                ->leftJoin('ubicacion_especifica_mobiliario', 'libro_mobiliario.id_ubicacionespecifica', '=', 'ubicacion_especifica_mobiliario.id_ubicacion_especifica')
                ->leftJoin('custodio_mobiliario', 'libro_mobiliario.id_custodio', '=', 'custodio_mobiliario.id_custodio')
             ->where("libro_mobiliario.id_libro", "=", $id)
            ->orderByRaw('codigo_identificacion_libro ASC')->get();
        
        return [
            'recupera' => $recupera
        ];
    }

    //guardar libro
    public function storelibro(Request $request){
        $cod_libro=DB::select("SELECT max(id_libro) as id_libro from libro_mobiliario");
        $cod=1;
        if(count($cod_libro)>0){
            $cod=$cod_libro[0]->id_libro+1;
        }
        $libro=new LibroMobiliario();
        $libro->id_libro=$cod;

        $libro->id_tipo_activo=$request->tipo_activo;
        $libro->codigo_identificacion_libro=$request->codigo_identificacion;
        $libro->codigo_anterior_libro=$request->codigo_anterior;
        $libro->titulo_anterior_libro=$request->titulo_anterior;
        $libro->titulo_actualizado_libro=$request->titulo_actualizado;
        $libro->editorial_libro=$request->editorial;
        $libro->id_conservacion=$request->conservacion;
        $libro->id_mantenimiento=$request->mantenimiento;
        $libro->fechacompra_libro=$request->fechacompra;
        $libro->costoadquisicion_libro=$request->costoadquisicion;
        $libro->id_ubicaciongeneral=$request->ubicaciongeneral;
        $libro->id_ubicacionespecifica=$request->ubicacionespecifica;
        $libro->id_custodio=$request->custodio;
        $libro->cuentacontable_libro=$request->cuentacontable;
        $libro->observaciones_libro=$request->observaciones;

        $libro->id_empresa=$request->id_empresa;
        $libro->save();
    }

    //actualizar libro
    public function updatelibro(Request $request){
        $libro=LibroMobiliario::find($request->id_libro);
        $libro->id_tipo_activo=$request->tipo_activo;
        $libro->codigo_identificacion_libro=$request->codigo_identificacion;
        $libro->codigo_anterior_libro=$request->codigo_anterior;
        $libro->titulo_anterior_libro=$request->titulo_anterior;
        $libro->titulo_actualizado_libro=$request->titulo_actualizado;
        $libro->editorial_libro=$request->editorial;
        $libro->id_conservacion=$request->conservacion;
        $libro->id_mantenimiento=$request->mantenimiento;
        $libro->fechacompra_libro=$request->fechacompra;
        $libro->costoadquisicion_libro=$request->costoadquisicion;
        $libro->id_ubicaciongeneral=$request->ubicaciongeneral;
        $libro->id_ubicacionespecifica=$request->ubicacionespecifica;
        $libro->id_custodio=$request->custodio;
        $libro->cuentacontable_libro=$request->cuentacontable;
        $libro->observaciones_libro=$request->observaciones;
        $libro->save();
    }

    //eliminar libro
    public function deletelibro($id){
        LibroMobiliario::where("id_libro","=",$id)->delete();
    }




}
