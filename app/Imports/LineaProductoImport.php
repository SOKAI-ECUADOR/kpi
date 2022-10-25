<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Lineaproducto;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;

//class ProductosImport implements ToModel, WithHeadingRow
class LineaProductoImport implements WithMultipleSheets, ToCollection, WithHeadingRow

{

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */



    public function collection(Collection $rows)
    {
        
        //linea de productos
        foreach ($rows as $row) {
            $idempre = $row['id_empresa'];
            $nomLinea = $row['nombre'];
            $codLinea = $row['codigo'];
            $ver = DB::select("SELECT * FROM linea_producto WHERE nombre = '" . $nomLinea . "'AND codigo = '" . $codLinea . "' AND id_empresa ='" . $idempre . "' ");
            
            if (count($ver) == 0 && $row['nombre'] != null){
                Lineaproducto::create([
                    'codigo'=> $row['codigo'],
                    'nombre'=> $row['nombre'],
                    'id_plan_cuentas_compras_iva'=> $row['id_plan_cuentas_compras_iva'],
                    'id_plan_cuentas_compras_iva_0'=> $row['id_plan_cuentas_compras_iva_0'],
                    'id_plan_cuentas_ventas_iva'=> $row['id_plan_cuentas_ventas_iva'],
                    'id_plan_cuentas_ventas_iva_0'=> $row['id_plan_cuentas_ventas_iva_0'],
                    'id_plan_cuentas_costo'=> $row['id_plan_cuentas_costo'],
                    'id_empresa' => $row['id_empresa'],
                ]);
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

           
            'Linea de producto' => new LineaProductoImport(),
        ];
    }
}
