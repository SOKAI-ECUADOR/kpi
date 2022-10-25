<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendedorcliente;
use App\Models\Usuario;
use App\Usera;
use Illuminate\Support\Facades\DB;

class VendedorclienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /*
    public fid_vendedorunction index(Request $request)
    {
        //
        $buscar = $request->buscar;
        $cantidadp = $request->cantidadp;
        if ($cantidadp < 1) {
            $cantidadp = 10;
        }
        if ($buscar == '') {
            //$impuestos = Impuesto::paginate($cantidadp); 
            $recupera = Vendedorcliente::paginate($cantidadp);
        } else {
            $recupera = Vendedorcliente::orderByRaw('id_vendedor DESC')->select('*')
                ->where('codigo_vendedor', 'like', '%' . $buscar . '%')
                ->orwhere('nombre_vendedor','like','%'.$buscar.'%')
                ->orwhere('email_vendedor','like','%'.$buscar.'%')
                ->orderByRaw(' DESC')->paginate($cantidadp);
        }
        return [
            'pagination' => [
                'total'        => $recupera->total(),
                'current_page' => $recupera->currentPage(),
                'per_page'     => $recupera->perPage(),
                'last_page'    => $recupera->lastPage(),
                'from'         => $recupera->firstItem(),
                'to'           => $recupera->lastItem(),
                'count'        => ceil($recupera->total() / $cantidadp),
            ],
            'recupera' => $recupera
        ];
    }
    */

    public function index(Request $request, $id)
    {
        $buscar = $request->buscar;
        if ($buscar==''){
            $recupera = Vendedorcliente::select("*")->where("id_empresa", "=", $id)->get(); 
        }else{
            $recupera = Vendedorcliente::select("*")
            ->where(function($q) use ($buscar){
                $q->where('codigo_vendedor', 'like', '%' . $buscar . '%')
                ->orWhere('nombre_vendedor', 'like', '%' . $buscar . '%')
                ->orWhere('email_vendedor', 'like', '%' . $buscar . '%');
            })
            ->where("id_empresa", "=", $id)
            ->orderByRaw('id_vendedor', 'desc')
            ->get();
        } 
        if (session()->get('usuariosesion')['filtro_list'] == 1) {
            $dat = [];
            for ($i = 0; $i < count($recupera); $i++) {
                if ($recupera[$i]->created_by == session()->get('usuariosesion')['id'] || $recupera[$i]->updated_by == session()->get('usuariosesion')['id'] || $recupera[$i]->id_vendedor == session()->get('usuariosesion')['id_vendedor']) {
                    array_push($dat, $recupera[$i]);
                }
            }
            $recupera = $dat;
        }
        return [
            'recupera' => $recupera
        ];
    }
    public function store(Request $request)
    {
        if ($request->nombre_vendedor){
        $sel =  DB::select("SELECT `nombre_vendedor` FROM `vendedor` WHERE `nombre_vendedor` = '" . $request->nombre_vendedor . "' and id_empresa={$request->empresa}");
            if ($sel) {
                return "error_nombreVendedor";
            }
        }
        $tipo = new Vendedorcliente();
        $tipo->codigo_vendedor = $request->codigo_vendedor;
        $tipo->nombre_vendedor = $request->nombre_vendedor;
        $tipo->email_vendedor = $request->email_vendedor;
        $tipo->id_user =$request->usuarios;
        $tipo->id_empresa =$request->empresa;
        $tipo->created_by = session()->get('usuariosesion')['id'];
        $tipo->updated_by = session()->get('usuariosesion')['id'];
        $tipo->save();  
        $id=$tipo->id_vendedor;

        $user = Usera::find($request->usuarios);
        $user->id_vendedor = $tipo->id_vendedor;
        $user->save();
        return $id;
    }
    public function editar(Request $request)
    {
        $select=DB::select("SELECT * from vendedor where id_vendedor={$request->id}");
        if ($select[0]->nombre_vendedor!==$request->nombre_vendedor){
            $sel =  DB::select("SELECT `nombre_vendedor` FROM `vendedor` WHERE `nombre_vendedor` = '{$request->nombre_vendedor}' and id_empresa={$request->empresa}");
            if ($sel) {
                return "error_nombreVendedor";
            }
        }
        $tipo= Vendedorcliente::find($request->id);
        $tipo->codigo_vendedor = $request->codigo_vendedor;
        $tipo->nombre_vendedor = $request->nombre_vendedor;
        $tipo->email_vendedor = $request->email_vendedor;
        $tipo->id_user =$request->usuarios;
        $tipo->id_empresa =$request->empresa;
        $tipo->updated_by = session()->get('usuariosesion')['id'];
        $tipo->save();  

        $user = Usera::find($request->usuarios);
        $user->id_vendedor = $request->id;
        $user->save();
    }
    public function eliminar ($id){ 
        
        DB::delete("DELETE FROM vendedor WHERE id_vendedor = ".$id);
        Vendedorcliente::destroy($id);
    }
    public function vervendedor(Request $request)
    {
        $id = $request->id;
        $vendedor = DB::select("SELECT * FROM vendedor WHERE id_vendedor =".$id);
        return $vendedor;
    }
    public function todo()
    {
        $tipo = Vendedorcliente::all();
        return $tipo;
    }
    public function ver(Request $request)
    {
        $id = $request->id;
        $tipo = Vendedorcliente::select("*")->where("id_vendedor", "=", $id)->get();
        return $tipo;
    }

    public function codigo(Request $request){
        $id = $request->id;
        $vendedor = DB::select("SELECT * FROM vendedor WHERE id_empresa = ".$id." ORDER BY id_vendedor DESC limit 1");
        if($vendedor){
            $dato = $vendedor[0]->codigo_vendedor;
            $var=0;
            for($i=strlen($dato); $i>0; $i--){
                if($dato[$i-1] =='-'){
                    $var = $i;
                    break;
                }
            }
            $codigo_vend=0;
            $numero = substr($dato,$var)+1;
            $cod = substr($dato,0,$var);
            if($numero<=9){
               $codigo_vend=$cod."00".$numero;
            }elseif($numero >= 10){
               $codigo_vend=$cod."0".$numero;
            }else {
                $codigo_vend=$cod.$numero;
            }
            return $codigo_vend;
        }else{
            return "vacio";
        }

    }
    public function getGrupoUser($id){
        $data=Usuario::select("*")->where("id_empresa", "=", $id)->get();
        return response()->json($data);
    }
}
