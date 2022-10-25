<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RolController extends Controller
{
    public function todos($id){
        $rol = DB::select("SELECT * FROM roles WHERE id_empresa = $id AND estado = 1 ORDER BY tipo, lugar DESC");
        $roles = [];
        for($i=0; $i<count($rol); $i++){
            $per = 0;
            for($f=0; $f<count($roles); $f++){
                if($rol[$i]->value == $roles[$f]->value){
                    $per = 1;
                }
            }
            if($per==0){
                array_push($roles, $rol[$i]);
            }
        }

        if(count($roles)<1){
            $data = \Illuminate\Support\Facades\File::get(storage_path("roles.json"));
            $data = json_decode($data, true);
            return $data;
        }
        return $roles;
    }
}
