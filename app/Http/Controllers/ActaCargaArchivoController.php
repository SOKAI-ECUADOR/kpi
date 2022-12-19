<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ActaCargaArchivo;
use Carbon\Carbon;


class ActaCargaArchivoController extends Controller
{
    
    //listar actacargaarchivo
    public function indexactascargaarchivo(Request $request,$id){
        $empresa_id= $id;
 
            
        $recupera = DB::table('acta_carga_archivo')
                ->join('acta_estado', 'acta_estado.id', '=', 'acta_carga_archivo.acta_estado_id')
                ->join('user', 'user.id', '=', 'acta_carga_archivo.user_id')
                ->where("acta_carga_archivo.empresa_id", "=", $empresa_id)
                ->select(
                    'acta_carga_archivo.id as id',
                    'acta_carga_archivo.nombre_archivo as nombre_archivo',
                    'acta_estado.nombre as estado',
                    'user.nombres as nombres',
                    'user.apellidos as apellidos',
                    'acta_carga_archivo.fcrea as fecha_subida'
                    )
                    ->orderByRaw('acta_carga_archivo.id ASC')->get();
       
        return [
            'recupera' => $recupera
        ];
    
    }
    
}
