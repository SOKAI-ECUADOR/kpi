<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Proyecto;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;

//class ProductosImport implements ToModel, WithHeadingRow
class ProyectosImport implements WithMultipleSheets, ToCollection, WithHeadingRow

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
            $select= DB::select("SELECT  codigo from proyecto where id_empresa='".$idempre."' order by id_proyecto DESC limit 1");
            $codigo=0;
            if($select[0]->codigo<10){
                $r=$select[0]->codigo+1;
                $codigo="0".$r;
            }else{
                $r=$select[0]->codigo+1;
                $codigo=$r;
            }
            $descrip = $row['descripcion'];
            $ver = DB::select("SELECT * FROM proyecto WHERE descripcion = '" . $descrip . "' AND id_empresa ='" . $idempre . "' ");
            
            if(count($ver) == 0 && $row['descripcion'] != null && $row['ubicacion'] != null){
                Proyecto::create([
                    "codigo"=> $codigo,
                    "descripcion"=> $row['descripcion'],
                    "ubicacion"=> $row['ubicacion'],
                    "id_empresa"=> $row['id_empresa'],
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

            'Proyectos' => new ProyectosImport(),
           
        ];
    }
}
