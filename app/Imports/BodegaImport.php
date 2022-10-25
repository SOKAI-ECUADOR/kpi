<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Bodega;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;

//class ProductosImport implements ToModel, WithHeadingRow
class BodegaImport implements WithMultipleSheets, ToCollection, WithHeadingRow

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
            $cod = $row['codigo'];
            $nompro = $row['nombre'];
            $ver = DB::select("SELECT * FROM bodega WHERE codigo = '" . $cod . "' OR nombre = '" . $nompro . "' AND id_empresa ='" . $idempre . "' ");

            if (count($ver) == 0 && $row['codigo'] != null  && $row['nombre'] != null  && $row['ubicacion'] != null  && $row['direccion'] != null )
            {
                Bodega::create([
                
                    'codigo'=> $row['codigo'],
                    'nombre'=> $row['nombre'],
                    'responsable'=> $row['responsable_de_bodega'],
                    'ubicacion'=> $row['ubicacion'],
                    'direccion'=> $row['direccion'],
                    'telefono'=> $row['telefono'],
                    'id_establecimiento'=> $row['id_establecimiento'],
                    'id_empresa'=> $row['id_empresa'],
                ]);
            } 
            /*
            else {
                return view('error');
            }
            */
        }
    }


  

    public function headingRow(): int
    {
        return 1;
    }
    public function sheets(): array
    {
        return [

            'Bodega' => new BodegaImport(),
           
        ];
    }
}
