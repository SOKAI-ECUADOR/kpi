<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\ActaActivoNombreNivel4;
use Carbon\Carbon;


class ActaActivoNombreNivel4Controller extends Controller
{
    
    //listar actaactivonombrenivel4
    public function indexactaactivonombrenivel4(Request $request, $id){
        $buscar = $request->buscar;
        $acta_activo_nombre_nivel_3_id = $id;

        if ($buscar == '') { 

            $recupera = ActaActivoNombreNivel4::select("*")
            ->where("acta_activo_nombre_nivel_3_id", "=", $acta_activo_nombre_nivel_3_id)
            ->orderByRaw('nombre ASC')->get();

        } else {


            $recupera = ActaActivoNombreNivel4::select("*")
            ->where("acta_activo_nombre_nivel_3_id", "=", $acta_activo_nombre_nivel_3_id)
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
