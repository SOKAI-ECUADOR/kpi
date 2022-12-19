<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\ActaActivoTipo;
use Carbon\Carbon;


class ActaActivoTipoController extends Controller
{
    
    //listar actaactivotipo
    public function indexactaactivotipo(Request $request, $id){
        $buscar = $request->buscar;

        if ($buscar == '') { 

            $recupera = ActaActivoTipo::select("*")
            ->orderByRaw('nombre ASC')->get();

        } else {


            $recupera = ActaActivoTipo::select("*")
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
