<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Importacion;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ToCollection;

//class ProductosImport implements ToModel, WithHeadingRow
class RegistroImportacionImport implements WithMultipleSheets, ToCollection, WithHeadingRow

{

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */



    public function collection(Collection $rows)
    {
         
        foreach ($rows as $row) {
            Importacion::create([
                "cod_importacion"=> $row['cod_importacion'],
                "estado"=> $row['estado'],
                "periodo_inicio"=> $row['periodo_inicio'],
                "periodo_fin"=> $row['periodo_fin'],
                "fech_embarque"=> $row['fech_embarque'],
                "fech_arribo"=> $row['fech_arribo'],
                "forma_liquidacion"=> $row['forma_liquidacion'],
                "fech_importacion"=> $row['fech_importacion'],
                "total_facturas"=> $row['total_facturas'],
                "total_liquidacion"=> $row['total_liquidacion'],
                "total_importacion"=> $row['total_importacion'],
                "id_proveedor"=> $row['id_proveedor'],
                "id_orden"=> $row['id_orden'],
                "id_user"=> $row['id_user'],
                "id_empresa"=> $row['id_empresa'],
                "id_punto_emision"=> $row['id_punto_emision'],
                "id_bodega"=> $row['id_bodega'], 
            ]);  
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

            'RegistroImportacion' => new RegistroImportacionImport(),
           
        ];
    }
}
