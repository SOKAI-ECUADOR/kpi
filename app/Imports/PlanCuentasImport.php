<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Plancuenta;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;

//class ProductosImport implements ToModel, WithHeadingRow
class PlanCuentasImport implements WithMultipleSheets, ToCollection, WithHeadingRow

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
            $cod = $row['codigo_cuenta'];
            $nomta = $row['nombre_cuenta'];
            $ctaBan = strtoupper($row['cuenta_banco']);
            //$ver = DB::select("SELECT * FROM plan_cuentas WHERE codcta = '" . $cod . "' OR nomcta = '" . $nomta . "' AND id_empresa ='" . $idempre . "' ");
            $ver = DB::select("SELECT * FROM plan_cuentas WHERE codcta = '" . $cod . "' AND id_empresa ='" . $idempre . "' ");

            //if (count($ver) == 0 && $row['cuenta_banco'] == null && $row['codigo_cuenta']!= null && $row['nombre_cuenta']!= null && $row['id_moneda']!= null && $row['id_grupo']!= null)
            if (count($ver) == 0 &&  $row['codigo_cuenta']!= null  && $row['id_moneda']!= null && $row['id_grupo']!= null)
            {
                Plancuenta::create([
                    'num_cuenta'=> $row['numero_cuenta'],
                    'id_banco'=> $row['id_banco'],
                    'codcta'=> $row['codigo_cuenta'],
                    'nomcta'=> $row['nombre_cuenta'],
                    'id_moneda'=> $row['id_moneda'],
                    'id_grupo'=> $row['id_grupo'],
                    'refcon'=> $row['refcon'],
                    'bansel'=> $row['cuenta_banco'],
                    'id_empresa'=> $row['id_empresa'],
                    
                ]);
            }
            //CUENTA CORRIENTE
            /*if (count($ver) == 0 && ($ctaBan == 'CUENTA CORRIENTE' || $ctaBan == 'CC' || $ctaBan == '1') )
            {
                if ($row['numero_cuenta']!= null && $row['id_banco']!= null && $row['codigo_cuenta']!= null && $row['nombre_cuenta']!= null && $row['id_moneda']!= null && $row['id_grupo']!= null)
                {
                Plancuenta::create([
                    'num_cuenta'=> $row['numero_cuenta'],
                    'id_banco'=> $row['id_banco'],
                    'codcta'=> $row['codigo_cuenta'],
                    'nomcta'=> $row['nombre_cuenta'],
                    'id_moneda'=> $row['id_moneda'],
                    'id_grupo'=> $row['id_grupo'],
                    'refcon'=> $row['refcon'],
                    'bansel'=> 1,
                    'id_empresa'=> $row['id_empresa'],
                    
                ]);
                 }  
            }*/
            //CUENTA AHORROS
            /*if (count($ver) == 0 && ($ctaBan =='CUENTA AHORROS' || $ctaBan == 'CA' || $ctaBan == '2') )
            {
                if ($row['numero_cuenta']!= null && $row['id_banco']!= null && $row['codigo_cuenta']!= null && $row['nombre_cuenta']!= null && $row['id_moneda']!= null && $row['id_grupo']!= null)
                {
                Plancuenta::create([
                    'bansel'=> 2,
                    'num_cuenta'=> $row['numero_cuenta'],
                    'id_banco'=> $row['id_banco'],
                    'codcta'=> $row['codigo_cuenta'],
                    'nomcta'=> $row['nombre_cuenta'],
                    'id_moneda'=> $row['id_moneda'],
                    'id_grupo'=> $row['id_grupo'],
                    'refcon'=> $row['refcon'],
                    'id_empresa'=> $row['id_empresa'],
                    
                ]);
                }  
            }*/
            /*
            else{
                return PlanCuentasImport;
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

            'PlanCuentas' => new PlanCuentasImport(),
           
        ];
    }
}
