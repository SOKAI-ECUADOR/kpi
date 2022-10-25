<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Establecimiento;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;

//class ProductosImport implements ToModel, WithHeadingRow
class EstablecimientoImport implements WithMultipleSheets, ToCollection, WithHeadingRow

{

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */



    public function collection(Collection $rows)
    {
         
        foreach ($rows as $row) {

            $nomEsta = $row['nombre'];
            $idempre = $row['id_empresa'];
            $ver = DB::select("SELECT * FROM establecimiento WHERE nombre = '" . $nomEsta . "' AND id_empresa ='" . $idempre . "' ");
            
            if(count($ver) == 0 && $row['nombre'] != null && $row['nombre_comercial'] != null && $row['direccion'] != null){
                Establecimiento::create([
                    "nombre"=> $row['nombre'],
                    "codigo"=> $row['codigo'],
                    "urlweb"=> $row['urlweb'],
                    "nombre_comercial"=> $row['nombre_comercial'],
                    "direccion"=> $row['direccion'],
                    "estado"=> $row['estado'],
                    "id_empresa"=> $row['id_empresa'],   
                ]);
            } 
        }
    }

    //"password"=> Hash::make($row['password']),


    public function headingRow(): int
    {
        return 1;
    }
    public function sheets(): array
    {
        return [

            'Establecimientos' => new EstablecimientoImport(),
           
        ];
    }
}
