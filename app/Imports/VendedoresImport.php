<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Vendedorcliente;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ValidationException;
use Illuminate\Support\Facades\DB;

//class ProductosImport implements ToModel, WithHeadingRow
class VendedoresImport implements WithMultipleSheets, ToCollection, WithHeadingRow

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
            $vendedor = DB::select("SELECT * FROM vendedor WHERE id_empresa = '".$idempre."' ORDER BY id_vendedor DESC limit 1");
            if($vendedor){
                $dato = $vendedor[0]->codigo_vendedor;
                $var=0;
                for($i=strlen($dato); $i>0; $i--){
                    if($dato[$i-1] =='-'){
                        $var = $i;
                    // break;
                    }
                }
                $codigo_vend=0;
                $numero = substr($dato,$var)+1;
                $cod = substr($dato,0,$var);
                if($numero<=9){
                    $codigo_vend=$cod."00".$numero;
                }elseif($numero >= 10){
                    $codigo_vend=$cod."0".$numero;
                }else {
                    $codigo_vend=$cod.$numero;
                 }
                //return $codigo_vend;
                $totalcodi = $codigo_vend;
                }else{
                    return "vacio";
            }
            $nomVen = $row['nombre_vendedor'];
            $ver = DB::select("SELECT * FROM vendedor WHERE nombre_vendedor = '" . $nomVen . "' AND id_empresa ='" . $idempre . "' ");
            $usuario = $row['id_usuarios'];
            $idusuarios = DB::select("SELECT id FROM user WHERE nombres = '" .$usuario. "' AND id_empresa ='" . $idempre . "' ");
            $idusuarioss = "$idusuarios";
            if(count($ver) == 0 && !filter_var($row['email_vendedor'], FILTER_VALIDATE_EMAIL) === false && $row['nombre_vendedor'] != null && $row['email_vendedor'] != null){
                Vendedorcliente::create([

                    "codigo_vendedor"=> $totalcodi,
                    "nombre_vendedor"=> $row['nombre_vendedor'],
                    "email_vendedor"=> $row['email_vendedor'],
                    "id_user" => $idusuarioss,
                    "id_empresa"=> $row['id_empresa'],  
                ]);

            }
            /*
            else{
                return [
                    
                ];
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

            'Vendedores' => new VendedoresImport(),
           
        ];
    }
}
