<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Tiposustento;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;

//class ProductosImport implements ToModel, WithHeadingRow
class TipoSustentoImport implements WithMultipleSheets, ToCollection, WithHeadingRow

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
            $codTipComp = $row['codigo_sri'];
            $ver = DB::select("SELECT * FROM tipo_sustento WHERE cod_sustento = '" . $codTipComp . "' AND id_empresa ='" . $idempre . "' ");
            
            if(count($ver) == 0 && $row['codigo_sri'] != null && $row['descripcion'] != null){
                Tiposustento::create([

                    "cod_sustento"=> $row['codigo_sri'],
                    "descrip_sustento"=> $row['descripcion'],
                    "id_empresa"=> $row['id_empresa'],  
                ]);
            }else
            {
                return 'errores';
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

            'TipoSustento' => new TipoSustentoImport(),
           
        ];
    }
}
