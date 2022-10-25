<?php

namespace App\Imports;

use App\Models\Cuentas_cobrar_import;
use App\Models\ProductoBodega;
use App\Models\BodegaEgreso;
use App\Models\BodegaEgresoDetalle;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\{Importable, ToModel, WithHeadingRow};

//class ProductosImport implements ToModel, WithHeadingRow
class CuentasPorCobrarImport implements ToModel, WithHeadingRow{
    use Importable;
    public function model(array  $row){
        ini_set('max_execution_time', 900);
        //dd($row);
        $arrays=[];
        $idempresa = $row["id_empresa"];
        //linea de productos
        $id_factura = $row["id_factura"];
        $id_cliente = $row["id_cliente"];
        $id_nota_debito = $row["id_nota_debito"];
        $buscar = "";
        if($id_nota_debito){
            $buscar = "codigo = '$id_nota_debito' OR";
        }
        $fecha_emision="";
        if(isset($row['fecha_emision'])){
            $fecha_emision=$row['fecha_emision'];
        }else{
            $fecha_emision=$row['fecha_pago'];
        }
        //printf($row['fecha_pago']);
        array_push($arrays,"SELECT * FROM cliente WHERE ($buscar identificacion = $id_cliente) AND id_empresa= $idempresa");
        //var_dump($arrays);
        if(strlen($id_factura)>0){
            $cliente = DB::select("SELECT * FROM cliente WHERE ($buscar identificacion = $id_cliente) AND id_empresa= $idempresa");
            if($cliente){
                $cliente_id = $cliente[0]->id_cliente;
                return new Cuentas_cobrar_import([
                    'num_cuota'=> $row['numero_cuota'],
                    'fecha_pago'=>  \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['fecha_pago']),
                    'fecha_factura'=>  \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($fecha_emision),
                    'periodo_pagos'=> $row['periodo_pago'],
                    'valor_cuota'=> $row['valor_cuota'],
                    'descuento'=> $row['descuento'],
                    'valor_pagado'=> '0.00',
                    'estado'=> 1,
                    'tipo'=> 1,
                    'referencias'=> $id_factura,
                    'id_factura' => null,
                    'id_cliente' => $cliente_id,
                    'id_empresa' => $idempresa
                ]);
            } 
        }
        

        
    }
}
