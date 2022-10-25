<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Ptoemision;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;

//class ProductosImport implements ToModel, WithHeadingRow
class PuntoEmisionImport implements WithMultipleSheets, ToCollection, WithHeadingRow

{

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */



    public function collection(Collection $rows)
    {
         
        foreach ($rows as $row) {
            //$idempre = $row['id_empresa'];
           // $codTipComp = $row['codigo_sri'];
           // $ver = DB::select("SELECT * FROM tipo_comprobante WHERE cod_tipcomprob = '" . $codTipComp . "' AND id_empresa ='" . $idempre . "' ");
            
            if(/*count($ver) == 0*/ $row['nombre'] != null && $row['punto_de_emision'] != null && $row['secuencial_factura'] != null && $row['secuencial_nota_credito'] != null
            && $row['secuencial_nota_debito'] != null && $row['secuencial_guia_remision'] != null && $row['secuencial_retencion'] != null 
            ){
                Ptoemision::create([
                    "nombre"=> $row['nombre'],
                    "codigo"=> $row['punto_de_emision'],
                    "secuencial_factura"=> $row['secuencial_factura'],
                    "secuencial_nota_credito"=> $row['secuencial_nota_credito'],
                    "secuencial_nota_debito"=> $row['secuencial_nota_debito'],
                    "secuencial_guia_remision"=> $row['secuencial_guia_remision'],
                    "secuencial_retencion"=> $row['secuencial_retencion'],
                    "secuencial_liquidacion_compra"=> $row['secuencial_liquidacion_compra'],
                    "activo"=> $row['activo'],
                    "id_establecimiento"=> $row['id_establecimiento'],
                    "id_empresa"=> $row['id_empresa'],  
                ]);
            }else
            {
                return 'err';
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

            'PuntoEmision' => new PuntoEmisionImport(),
           
        ];
    }
}
