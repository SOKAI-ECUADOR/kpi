<?php

namespace App\Imports;

use App\Vendedorcliente;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Vendedorcliente([
            //
            "codigo_vendedor"=> $row['codigo_vendedor'],
            "nombre_vendedor"=> $row['nombre_vendedor'],
            "email_vendedor"=> $row['email_vendedor'],
            "id_user" => $row['id_usuarios'],
            "id_empresa"=> $row['id_empresa'],  
        ]);
    }
}
