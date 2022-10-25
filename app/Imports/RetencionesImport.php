<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Retencion;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ValidationException;
use Illuminate\Support\Facades\DB;

//class ProductosImport implements ToModel, WithHeadingRow
class RetencionesImport implements WithMultipleSheets, ToCollection, WithHeadingRow

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
           
            $codReten = $row['codigo'];
            $ver = DB::select("SELECT * FROM retencion WHERE cod_retencion = '" . $codReten . "' AND id_empresa ='" . $idempre . "' ");
            
            if(count($ver) == 0 && $row['codigo'] != null && $row['descripcion'] != null && $row['tipo_retencion'] != null && $row['porcentaje'] != null && $row['tipo_iva'] != null && $row['id_moneda'] != null){
                Retencion::create([

                    "cod_retencion"=> $row['codigo'],
                    "descrip_retencion"=> $row['descripcion'],
                    "tipo_retencion"=> $row['tipo_retencion'],
                    "porcen_retencion"=> $row['porcentaje'],
                    "tipoiva_retencion"=> $row['tipo_iva'],
                    "id_plan_cuentas"=> $row['id_plan_cuentas'],
                    "id_moneda"=> $row['id_moneda'],
                    "id_impuesto"=> $row['id_impuesto_sri_codigo'],
                    //"id_proyecto"=> $row[''],
                    "id_empresa"=> $row['id_empresa'],  
                ]);
            }else{
                return [
                    'err' => 'err',
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

            'Retenciones' => new RetencionesImport(),
           
        ];
    }
}
