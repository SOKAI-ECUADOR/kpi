<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Seguro;





class SeguroController extends Controller
{
    public function index(Request $request, $id)
    {
        $buscar = $request->buscar;
        if ($buscar == '') { 
            $recupera = Seguro::select("*")
             ->where("id_empresa", "=", $id)
             ->where("estado", "=", "Activo")
            ->orderByRaw('id_seguro DESC')->get();
        } else {
            $recupera = Seguro::select('*')
                ->where(function ($q) use ($buscar) {
                    $q->where('nombre', 'like', '%' . $buscar . '%');
                })
                 ->where("id_empresa", "=", $id)
                 ->where("estado", "=", "Activo")
                ->orderByRaw('id_seguro DESC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }
    public function store(Request $request){
        $cont0=DB::select("SELECT max(numero) as numero from seguro where id_empresa={$request->id_empresa}");
        $cont1=1;
        if(count($cont0)>0){
            $cont1=$cont0[0]->numero+1;
        }
        $seg=new Seguro();
        $seg->numero=$cont1;
        $seg->nombre=$request->nombre;
        $seg->estado='Activo';
        $seg->ucrea=$request->ucrea;
        $seg->id_empresa=$request->id_empresa;
        $seg->save();
    }
    public function update(Request $request){
        $seg=Seguro::find($request->id_seguro);
        $seg->nombre=$request->nombre;
        $seg->estado='Activo';
        $seg->umodifica=$request->umodifica;
        $seg->id_empresa=$request->id_empresa;
        $seg->save();
    }
    public function delete($id){
        $seg=Seguro::find($id);
        $seg->estado='Inactivo';
        $seg->save();
    }
}
