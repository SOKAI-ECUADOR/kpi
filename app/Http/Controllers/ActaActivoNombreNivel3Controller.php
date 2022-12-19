<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\ActaActivoNombreNivel3;
use Carbon\Carbon;


class ActaActivoNombreNivel3Controller extends Controller
{
    
    //listar actaactivonombrenivel3
    public function indexactaactivonombrenivel3(Request $request, $id){
        $buscar = $request->buscar;
        $acta_activo_nombre_nivel_2_id = $id;

        if ($buscar == '') { 

            $recupera = ActaActivoNombreNivel3::select("*")
            ->where("acta_activo_nombre_nivel_2_id", "=", $acta_activo_nombre_nivel_2_id)
            ->orderByRaw('nombre ASC')->get();

        } else {


            $recupera = ActaActivoNombreNivel3::select("*")
            ->where("acta_activo_nombre_nivel_2_id", "=", $acta_activo_nombre_nivel_2_id)
            ->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', '%'.$buscar.'%');
            })
            ->orderByRaw('nombre ASC')->get(); 

        }
        return [
            'recupera' => $recupera
        ];
    }
    
    
}
