<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Tipoproducto;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;
//class ProductosImport implements ToModel, WithHeadingRow
class TipoProductosImport implements WithMultipleSheets, ToCollection, WithHeadingRow

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
            $nomTipo = $row['nombre'];
            $codTipo = $row['codigo'];
            $ver = DB::select("SELECT * FROM tipo_producto WHERE nombre = '" . $nomTipo . "'AND codigo = '" . $codTipo . "' AND id_empresa ='" . $idempre . "' ");
            
            if (count($ver) == 0 && $row['nombre'] != null && $row['id_linea_producto'] != null && $row['id_empresa'] != null ){
                Tipoproducto::create([
                    'codigo'=> $row['codigo'],
                    'nombre'=> $row['nombre'],
                    'utilidad'=> $row['utilidad'],
                    'id_linea_producto'=> $row['id_linea_producto'],
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

           
            'Tipo Producto' => new TipoProductosImport(),
        ];
    }
}
