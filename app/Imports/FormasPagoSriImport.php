<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\FormaDePagosSri;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ValidationException;
use Illuminate\Support\Facades\DB;

//class ProductosImport implements ToModel, WithHeadingRow
class FormasPagoSriImport implements WithMultipleSheets, ToCollection, WithHeadingRow

{

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */



    public function collection(Collection $rows)
    {
         
        foreach ($rows as $row) {
            $idempre = $row['id_empresa'];
            $codFormas = $row['codigo'];
            $ver = DB::select("SELECT * FROM forma_pagos_sri WHERE codigo = '" . $codFormas . "' AND id_empresa ='" . $idempre . "' ");
            
            if(count($ver) == 0 && $row['codigo'] != null){
                FormaDePagosSri::create([
                    "codigo"=> $row['codigo'],
                    "descripcion"=> $row['descripcion'],
                    "id_empresa"=> $row['id_empresa'],  
                ]);
            }else{
                return [
                    'err' => 'Actualizacion de asientos contables exitoso',
                ];
            }
        }
    }



    public function headingRow(): int
    {
        return 1;
    }
    public function sheets(): array
    {
        return [

            'FormasPagoSri' => new FormasPagoSriImport(),
           
        ];
    }
}
