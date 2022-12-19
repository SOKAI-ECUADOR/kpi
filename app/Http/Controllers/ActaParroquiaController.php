<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ActaParroquia;
use Carbon\Carbon;


class ActaParroquiaController extends Controller
{
    
    //listar actaparroquia por canton
    public function buscaractaparroquiaxcanton(Request $request, $canton_id){
        
        $recupera = ActaParroquia::select("*")
        ->where("acta_canton_id", "=", $canton_id)
        ->orderByRaw('id ASC')
        ->get();
        
        return [
            'recupera' => $recupera
        ];
    }
    
}
