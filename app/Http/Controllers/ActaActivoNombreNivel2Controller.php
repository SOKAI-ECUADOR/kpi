<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\ActaActivoNombreNivel2;
use Carbon\Carbon;


class ActaActivoNombreNivel2Controller extends Controller
{
    
    //listar actaactivonombrenivel2
    public function indexactaactivonombrenivel2(Request $request, $id){
        $buscar = $request->buscar;
        $acta_tipo_id = $id;

        if ($buscar == '') { 

            $recupera = ActaActivoNombreNivel2::select("*")
            ->where("acta_activo_tipo_id", "=", $acta_tipo_id)
            ->orderByRaw('nombre ASC')->get();

        } else {


            $recupera = ActaActivoNombreNivel2::select("*")
            ->where("acta_activo_tipo_id", "=", $acta_tipo_id)
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
