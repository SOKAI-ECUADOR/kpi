<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Caja;
use App\Models\Plancuenta;
use App\Models\Moneda;
use App\Models\Grupo;

class CajaController extends Controller
{
    //
    public function index(Request $request,$id){
        $buscar = $request->buscar;
        if ($buscar==''){
            //$recupera = Caja::where('caja.id_empresa','=',$id); 
            $recupera=Caja::select("caja.*", "plan_cuentas.codcta as cuenta_contable","moneda.nomb_moneda as moneda")
            ->join("plan_cuentas", "plan_cuentas.id_plan_cuentas", "=", "caja.id_plan_cuentas")
            ->join("moneda", "moneda.id_moneda", "=", "caja.id_moneda")
            ->where("caja.id_empresa", "=", $id)->get();
            
        }else{
            $recupera = Caja::select("caja.*", "plan_cuentas.codcta as cuenta_contable","moneda.nomb_moneda as moneda")
            ->join("plan_cuentas", "plan_cuentas.id_plan_cuentas", "=", "caja.id_plan_cuentas")
            ->join("moneda", "moneda.id_moneda", "=", "caja.id_moneda")
            ->where(function($q) use ($buscar){
                $q->where('caja.descrip_caja','like','%'.$buscar.'%') 
                ->orWhere('plan_cuentas.codcta','like','%'.$buscar.'%');
            })
            ->where("caja.id_empresa", "=", $id)->get();

        } 
        return [
            'recupera' => $recupera
        ];
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $select=DB::select('SELECT descrip_caja from caja where descrip_caja='.'"'.$request->descrip_caja.'"'.' and id_empresa='.$request->id_empresa);
        $var=count($select);
        
        $caja= new Caja();
        $caja->descrip_caja=$request->descrip_caja;
        $caja->id_moneda=$request->id_moneda;
        $caja->id_empresa=$request->id_empresa;
        $caja->id_plan_cuentas=$request->cod_cuenta;
        if($var>=1){
            return "existe";
        }else{
            $caja->save();
        }
        //$caja->save();
        /*if($request->cuenta_contable==null){
            $caja->save();
            return "vacio";
        }else{
            if(!$select){
             return "mal";
            }else{
             $caja->save();
             return "bien";
            }
        }*/
        
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $select1=DB::select('SELECT descrip_caja from caja where id_caja='.$request->id);
        $select2=DB::select('SELECT descrip_caja from caja where descrip_caja='.'"'.$request->descrip_caja.'"'.' and id_empresa='.$request->id_empresa);
        $var=count($select2);
        $caja=Caja::find($request->id);
        $caja->descrip_caja=$request->descrip_caja;
        $caja->id_moneda=$request->id_moneda;
        $caja->id_plan_cuentas=$request->cod_cuenta;
        //$caja->save();
        if($select1[0]->descrip_caja===$request->descrip_caja){
            $caja->save();
        }else{
            if($var>=1){
                return "existe";
            }else{
                $caja->save();
                
            }
        }
        /*if($request->cuenta_contable==null){
            
            return "vacio";
        }else{
            if(!$select){
             return "mal";
            }else{
             $caja->save();
             return "bien";
            }
        }*/
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function abrir(Request $request)
    {
        $id = $request->id;
        $recupera = DB::select('SELECT caja.*,plan_cuentas.nomcta as cuenta_contable FROM `caja`,plan_cuentas WHERE caja.id_plan_cuentas=plan_cuentas.id_plan_cuentas and caja.id_caja='.$id);
        return $recupera;
    }
    public function select(Request $request, $id)
    {
        $buscar = $request->buscar;
        if ($buscar == '') {
            //$impuestos = Impuesto::paginate($cantidadp); 
            $recupera = Plancuenta::select('plan_cuentas.*')
                ->addSelect([
                    'nomb_moneda' => Moneda::select('nomb_moneda')
                        ->whereColumn('id_moneda', 'plan_cuentas.id_moneda'),
                    'nomb_grupo' => Grupo::select('nomb_grupo')
                        ->whereColumn('id_grupo', 'plan_cuentas.id_grupo')
                ])
                ->where("plan_cuentas.id_empresa", "=", $id)
                ->orderByRaw('plan_cuentas.codcta ASC')->limit(2)->get();
        } else {
            $recupera = Plancuenta::select('plan_cuentas.*')
                ->addSelect([
                    'nomb_moneda' => Moneda::select('nomb_moneda')
                        ->whereColumn('id_moneda', 'plan_cuentas.id_moneda'),
                    'nomb_grupo' => Grupo::select('nomb_grupo')
                        ->whereColumn('id_grupo', 'plan_cuentas.id_grupo')
                ])
                ->where(function ($q) use ($buscar) {
                    $q->where('plan_cuentas.codcta', 'like', '%' . $buscar . '%')
                        ->orWhere('plan_cuentas.nomcta', 'like', '%' . $buscar . '%');
                })
                ->where("plan_cuentas.id_empresa", "=", $id)
                ->orderByRaw('plan_cuentas.codcta ASC')
                ->limit(2)->get();
        }
        return [
            'recupera' => $recupera
        ];
    }

    public function eliminar ($id){
       Caja::destroy($id);
      /*$id = $request->id;
       $eliminar = Producto::find($request->id);
        $eliminar->delete();
  */
      }
}
