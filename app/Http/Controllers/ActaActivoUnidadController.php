<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\ActaActivoUnidad;
use Carbon\Carbon;


class ActaActivoUnidadController extends Controller
{
    
    //listar actaactivounidad
    public function indexactaactivounidad(Request $request){
        $buscar = $request->buscar;

        if ($buscar == '') { 

            $recupera = ActaActivoUnidad::select("*")
            ->orderByRaw('nombre ASC')->get();

        } else {


            $recupera = ActaActivoUnidad::select("*")
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
