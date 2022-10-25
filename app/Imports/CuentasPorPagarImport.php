<?php

namespace App\Imports;

use App\Models\Cuentas_pagar_import;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\{Importable, ToModel, WithHeadingRow};

//class ProductosImport implements ToModel, WithHeadingRow
class CuentasPorPagarImport implements ToModel, WithHeadingRow{
    use Importable;
    public function model(array  $row){
        ini_set('max_execution_time', 300);
        $idempresa = $row['id_empresa'];
        //linea de productos
        $id_factura = $row['id_factura'];
        $id_proveedor = $row['id_proveedor'];
        $id_nota_debito = $row['id_nota_debito'];
        $buscar = "";
        if($id_nota_debito){
            $buscar = "cod_proveedor = '$id_nota_debito' OR";
        }
        $fecha_emision="";
        if(isset($row['fecha_emision'])){
            $fecha_emision=$row['fecha_emision'];
        }else{
            $fecha_emision=$row['fecha_pago'];
        }
        $proveedor = DB::select("SELECT * FROM proveedor WHERE ($buscar identif_proveedor = $id_proveedor) AND id_empresa= $idempresa");
        if($proveedor){
            $proveedor_id = $proveedor[0]->id_proveedor;
            return new Cuentas_pagar_import([
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
                'id_factura_compra' => null,
                'id_proveedor' => $proveedor_id,
                'id_empresa' => $idempresa
            ]);
        }
        
    }
}
