<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ActaProvincia;
use Carbon\Carbon;


class ActaProvinciaController extends Controller
{
    
    //listar actaprovincia
    public function buscaractaprovincias(Request $request){

        $recupera = ActaProvincia::select("*")
        ->where("activo", "=", 1)
        ->orderByRaw('id ASC')
        ->get();
        
        return [
            'recupera' => $recupera
        ];
    }
    
}
