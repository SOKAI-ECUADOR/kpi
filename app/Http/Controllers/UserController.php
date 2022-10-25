<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Usera;
use App\Models\Rol;
use App\Models\Roles;

class UserController extends Controller
{
    public function index(Request $request, $id)
    {
        //recupera los datos del usuario con su respectivo rol de usuario como subselect
        $buscar = $request->buscar;
        if ($buscar == '') {
            $recupera = Usera::addSelect([
                'nombre_rol' => Rol::select('nombre')
                    ->whereColumn('id_rol', 'user.id_rol'),
            ])->where("id_empresa", "=", $id)->orderByRaw('id DESC')->get();
        } else {
            $recupera = Usera::addSelect([
                'nombre_rol' => Rol::select('nombre')
                    ->where('nombre', 'like', '%' . $buscar . '%')
                    ->whereColumn('id_rol', 'user.id_rol'),
            ])
                ->where(function ($q) use ($buscar) {
                    $q->orWhere('nombres', 'like', '%' . $buscar . '%')
                        ->orWhere('apellidos', 'like', '%' . $buscar . '%')
                        ->orWhere('email', 'like', '%' . $buscar . '%');
                })
                ->where('id_empresa', '=', $id)
                ->orderByRaw('id DESC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }
    public function listar($id)
    {
        //recupera los datos del usuario y ademas de eso los datos de los roles del usuario de la tabla de roles
        $dato = Usera::select()->where("id", "=", $id)->get();
        $roles = Roles::select()->where("id_user", "=", $id)->get();
        return [
            "datos" => $dato[0],
            "roles" => $roles,
        ];
    }
    public function cmps(Request $request)
    {
        //si se cambia la contraseña del usuario aqui hashea el password y guarda el nuevo pass
        $dato = Usera::findOrFail($request->id);
        $dato->password = Hash::make($request->password);
        $dato->save();
    }
    public function registro(Request $request)
    {
        //aqui guarda el registro del usuario con los permisos del mismo
        //guarda los datos del usuario
        $dato = new Usera();
        $dato->password = Hash::make($request->password);
        $dato->email = $request->email;
        $dato->nombres = $request->nombre;
        $dato->apellidos = $request->apellido;
        $dato->telefono = $request->telefono;
        $dato->estado = $request->estado;
        $dato->filtro_list = $request->filtro_list;
        $dato->id_rol = 2;
        $dato->id_empresa = $request->empresa;
        if ($request->establecimeinto == 0) {
            $dato->id_establecimiento = null;
            $dato->id_punto_emision = null;
        } else {
            $dato->id_establecimiento = $request->establecimeinto;
            $dato->id_punto_emision = $request->punto_emision;
        }
        $dato->save();

        //recupera el id del usuario
        $id = $dato->id;

        //recorre los permisos de los roles de usuario y guarda los datos, en el CRUD guarda como 0 y 1 (true o false), siendo true que tiene permisos
        //el value sirve para identificar el numero de eprmiso, el tipo para saber a que lista de módulo pertenece y el lugar para saber el orden dentro de la lista del módulo
        for ($i = 0; $i < count($request->items); $i++) {
            $rol = new Roles();
            $rol->nombre = $request->items[$i]["nombre"];
            $rol->value = $request->items[$i]["value"];
            $rol->ver = $request->items[$i]["ver"];
            $rol->editar = $request->items[$i]["editar"];
            $rol->crear = $request->items[$i]["crear"];
            $rol->eliminar = $request->items[$i]["eliminar"];
            $rol->tipo = $request->items[$i]["tipo"];
            $rol->lugar = $request->items[$i]["lugar"];
            $rol->id_user = $id;
            $rol->save();
        }
    }
    public function editar(Request $request)
    {
        //recupera los datos del usuario para editar sus registros
        $id = $request->id;
        $dato = Usera::find($request->id);
        if (strlen($request->password) >= 1) {
            $dato->password = Hash::make($request->password);
        }
        $dato->email = $request->email;
        $dato->nombres = $request->nombre;
        $dato->apellidos = $request->apellido;
        $dato->estado = $request->estado;
        $dato->filtro_list = $request->filtro_list;        
        $dato->telefono = $request->telefono;
        $dato->id_rol = $request->rol;
        $dato->id_empresa = $request->empresa;
        if ($request->establecimeinto == 0) {
            $dato->id_establecimiento = null;
            $dato->id_punto_emision = null;
        } else {
            $dato->id_establecimiento = $request->establecimeinto;
            $dato->id_punto_emision = $request->punto_emision;
        }
        $dato->save();

        //elimina los permisos anteriores del usuario
        $rol = Roles::where("id_user", "=", $id)->delete();

        //recorre los permisos de los roles de usuario y guarda los datos, en el CRUD guarda como 0 y 1 (true o false), siendo true que tiene permisos
        //el value sirve para identificar el numero de eprmiso, el tipo para saber a que lista de módulo pertenece y el lugar para saber el orden dentro de la lista del módulo
        for ($i = 0; $i < count($request->items); $i++) {
            $rol = new Roles();
            $rol->nombre = $request->items[$i]["nombre"];
            $rol->value = $request->items[$i]["value"];
            $rol->ver = $request->items[$i]["ver"];
            $rol->editar = $request->items[$i]["editar"];
            $rol->crear = $request->items[$i]["crear"];
            $rol->eliminar = $request->items[$i]["eliminar"];
            $rol->tipo = $request->items[$i]["tipo"];
            $rol->lugar = $request->items[$i]["lugar"];
            $rol->id_user = $request->id;
            $rol->save();
        }
    }
    public function delete($id)
    {
        //elimina el registro del usuario mediante su id
        Usera::destroy($id);
    }
    public function versesion($id)
    {
        $sel = DB::select("SELECT u.estado, e.estado AS estado_empresa FROM user u INNER JOIN empresa e ON u.id_empresa = e.id_empresa WHERE u.id=$id");
        return $sel;
    }
    public function getusersesion(Request $request)
    {
        //$user = $request->user;
        $user = Usera::select("*")->where("id", "=", $request->user['id'])->get();
        session()->put('usuariosesion', $user[0]);
    }
}
