<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Impuesto;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;

//class ProductosImport implements ToModel, WithHeadingRow
class CodigoImpuestoImport implements WithMultipleSheets, ToCollection, WithHeadingRow

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
            $codImp = $row['codigo'];
            $ver = DB::select("SELECT * FROM impuesto WHERE cod_imp = '" . $codImp . "' AND id_empresa ='" . $idempre . "' ");
            
            if(count($ver) == 0 && $row['codigo'] != null && $row['descripcion'] != null && $row['tipo'] != null && $row['porcentaje'] != null){
                Impuesto::create([
                    "cod_imp"=> $row['codigo'],
                    "descrip_imp"=> $row['descripcion'],
                    "tipo_imp"=> $row['tipo'],
                    "porcen_imp"=> $row['porcentaje'],
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

            'Impuestos' => new CodigoImpuestoImport(),
           
        ];
    }
}
