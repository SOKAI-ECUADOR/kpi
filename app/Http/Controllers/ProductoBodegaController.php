<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductoBodega;
use App\Models\Bodega;

use Illuminate\Support\Facades\DB;

use generarReportes;
include 'class/generarReportes.php';

class ProductoBodegaController extends Controller
{
    //lista encabezado de bodega gestion bodega
    public function bodega($id)
    {
        $bodega = Bodega::select("*")->where("id_bodega","=",$id)->get();
        return [
            "bodega"=>$bodega[0],
        ];
    }
    //lista stock bodega
    public function productos($id)
    {
        $recupera = ProductoBodega::select("bodega.*", "producto.cod_principal", "producto.cod_alterno", "producto.id_producto AS idprod", "producto.nombre AS nombrep", "producto.descripcion", "producto_bodega.cantidad", "producto_bodega.costo_unitario" , "producto_bodega.costo_total")
                    ->join("producto", "producto.id_producto", "=", "producto_bodega.id_producto")
                    ->join("bodega", "bodega.id_bodega", "=", "producto_bodega.id_bodega")
                    ->where("bodega.id_bodega","=",$id)->where("producto_bodega.cantidad","!=",0)->get();
        return [
            "datos"=>$recupera
        ];
    }

    public function generarPdf(Request $request){
        $productos_bodega=DB::select("SELECT producto_bodega.*,producto.cod_principal,producto.nombre AS nombrep,producto.descripcion,producto.imagen from producto_bodega 
        INNER JOIN bodega ON bodega.id_bodega=producto_bodega.id_bodega
        INNER JOIN producto ON producto.id_producto=producto_bodega.id_producto
        where producto_bodega.id_bodega={$request->id_bodega} and producto_bodega.cantidad<>0 and producto_bodega.costo_unitario<>0");
        $bodega=DB::select("SELECT * from bodega where id_bodega={$request->id_bodega}");
        $empresa=DB::select("SELECT * from empresa where id_empresa={$request->id_empresa}");
        $pdf = new generarReportes();
        $strPDF = $pdf->PDFInventario($productos_bodega,$bodega, $empresa[0]);
        return response($strPDF, 200)->header('Content-Type', 'application/pdf');
    }

}
