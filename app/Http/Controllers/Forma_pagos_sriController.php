<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Forma_pagos_sriController extends Controller
{
    public function listar(Request $request){
        $buscar = $request->buscar;
        $id_empresa = $request->empresa;
        $lista = DB::select("SELECT * FROM forma_pagos_sri WHERE (codigo LIKE '%$buscar%' OR descripcion LIKE '%$buscar%') AND id_empresa =" . $id_empresa . " ORDER BY id_forma_pagos_sri DESC");
        return $lista;
    }
    public function guardar(Request $request){
        $codigo = $request->tabla["codigo"];
        $descripcion = $request->tabla["descripcion"];
        $empresa = $request->empresa;
        DB::select("INSERT INTO forma_pagos_sri (codigo, descripcion, id_empresa) VALUES ('$codigo', '$descripcion', $empresa)");
    }
    public function editar(Request $request){
        $id = $request->tabla["id"];
        $codigo = $request->tabla["codigo"];
        $descripcion = $request->tabla["descripcion"];
        DB::select("UPDATE forma_pagos_sri SET codigo = '$codigo', descripcion = '$descripcion' WHERE id_forma_pagos_sri = $id");
    }
    public function eliminar($id){
        $delete = DB::select("DELETE FROM forma_pagos_sri WHERE id_forma_pagos_sri = " . $id);
        return $delete;
    } 
}
