<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
include 'class/generarReportes.php';
include_once getenv("FILE_CONFIG_PHP");

use generarReportes;

class ListaPrecioController extends Controller
{
    //
    public function generarReporte(Request $request){
        $nombre_producto='';
        $query='';
        $fields=[];
        $queries = [];
        $nombre_modelo="";
        $nombre_marca="";
        $nombre_pres="";
        $nombre_linea_prod="";
        $nombre_tipo_prod="";
        if ($request->products) {
            // $to_array = function ($product) {
            //     $new_product = json_decode($product);
            //     return $new_product->id;
            // };
            // if(count($request->products)==1){
            //     $to_array_3 = function ($product) {
            //         $new_product = json_decode($product);
            //         return $new_product->id;
            //     };
            //     $info_products_3 = implode(",", array_map($to_array_3, $request->products));

            //     if($info_products_3==0){
            //         $query="SELECT * from producto where id_empresa={$request->company}";
            //     }else{
            //         $to_array_2 = function ($product) {
            //             $new_product = json_decode($product);
            //             return $new_product->nombre;
            //         };
            //         $info_products_2 = implode(",", array_map($to_array_2, $request->products));
            //         $nombre_producto=$info_products_2;
            //         $query="SELECT * from producto where id_empresa={$request->company} and id_producto={$info_products_3}";
            //     }
            // }
            // $info_products = implode(",", array_map($to_array, $request->products));
            // //array_push($queries, "df.id_producto in ({$info_products_3})\n");
            // $query="SELECT * from producto where id_empresa={$request->company} and id_producto in ($info_products)";
            //dd($request->products);
            // $new_product = json_decode($request->products,true);
            // if($new_product["id"]!=0){
            //     $query="SELECT * from producto where id_empresa={$request->company} and id_producto={$new_product["id"]}";
            // }else{
            //     $query="SELECT * from producto where id_empresa={$request->company}";
            // }
            
        }
        if($request->linea_product){
            $new_product = json_decode($request->linea_product,true);
            if($new_product["id"]!=0){
                $nombre_linea_prod=$new_product["name"];
                array_push($queries,"producto.id_linea_producto={$new_product["id"]}\n");
                array_push($fields,"LEFT JOIN linea_producto ON linea_producto.id_linea_producto=producto.id_linea_producto\n");
            }
        }
        if($request->tipo_product){
            $new_product = json_decode($request->tipo_product,true);
            if($new_product["id"]!=0){
                $nombre_tipo_prod=$new_product["name"];
                array_push($queries,"producto.id_tipo_producto={$new_product["id"]}\n");
                array_push($fields,"LEFT JOIN tipo_producto ON tipo_producto.id_tipo_producto=producto.id_tipo_producto\n");
            }
        }
        if($request->modelo){
            $new_product = json_decode($request->modelo,true);
            if($new_product["id"]!=0){
                $nombre_modelo=$new_product["name"];
                array_push($queries,"producto.id_modelo={$new_product["id"]}\n");
                array_push($fields,"LEFT JOIN modelo ON modelo.id_modelo=producto.id_modelo\n");
            }
        }
        if($request->marca){
            $new_product = json_decode($request->marca,true);
            if($new_product["id"]!=0){
                $nombre_marca=$new_product["name"];
                array_push($queries,"producto.id_marca={$new_product["id"]}\n");
                array_push($fields,"LEFT JOIN marca ON marca.id_marca=producto.id_marca\n");
            }
        }
        if($request->presentacion){
            $new_product = json_decode($request->presentacion,true);
            if($new_product["id"]!=0){
                $nombre_pres=$new_product["name"];
                array_push($queries,"producto.id_presentacion={$new_product["id"]}\n");
                array_push($fields,"LEFT JOIN presentacion ON presentacion.id_presentacion=producto.id_presentacion\n");
            }
        }
        $queries=implode(" and ",$queries);
        $fields=implode("",$fields);
        if($queries){
            $query="SELECT producto.* from producto {$fields} where {$queries} and producto.id_empresa={$request->company}";
        }else{
            $query="SELECT producto.* from producto {$fields} where producto.id_empresa={$request->company}";
        }
        //dd($query);
        $precios=DB::select($query);
        $empresa=DB::select("SELECT * from empresa where id_empresa=".$request->company);
        $reportePdf = new generarReportes();
        if(!$precios){
            return response('no-data-report', 200)->header('Content-Type', 'application/json');
        }else{
            $strPDF = $reportePdf->ListaPrecioPdf($precios,$empresa[0],$nombre_marca,$nombre_modelo,$nombre_pres,$nombre_linea_prod,$nombre_tipo_prod);
            return response($strPDF, 200)->header('Content-Type', 'application/pdf');
        }
        
    }
}
