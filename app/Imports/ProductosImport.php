<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Models\Producto;
use App\Models\Lineaproducto;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ToCollection;
use phpDocumentor\Reflection\Types\Null_;
//include_once getenv("FILE_CONFIG_PHP");

//class ProductosImport implements ToModel, WithHeadingRow
class ProductosImport implements WithMultipleSheets, ToCollection, WithHeadingRow

{

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */


    public function collection(Collection $rows)
    {
        //productos 
        foreach ($rows as $row) {
            $idempre =$row['id_empresa'];
            $cod =$row['codigo_alterno'];
            $ver = DB::select("SELECT * FROM producto WHERE cod_alterno = '" . $cod . "' AND id_empresa ='". $idempre ."' ");

            $sel = DB::select("SELECT cod_principal FROM producto WHERE id_empresa = '" . $idempre . "' ORDER BY id_producto DESC LIMIT 1");
        
            $principal = "";
            if (count($sel) >= 1) {
                $dato = $sel[0]->cod_principal;
                if (($dato + 1) >= 100) {
                    $tot = $dato + 1;
                    $principal = $tot;
                } else if (($dato + 1) >= 10) {
                    $tot = $dato + 1;
                    $principal = "0" . $tot;
                } else {
                    $tot = $dato + 1;
                    $principal = "00" . $tot;
                }
            } else {
                    $principal = "001";
            }

            if (count($ver) == 0 && $row['sector_producto_o_servicio'] == 1 && $row['nombre'] != null && $row['id_linea_producto'] != null && $row['id_tipo_producto'] != null && $row['id_marca'] != null
            && $row['id_modelo'] != null && $row['id_presentacion'] != null && $row['estado'] != null && $row['id_tipo_medida'] != null  && $row['id_unidad_medida'] != null
            && $row['numero_de_unidad'] != null && $row['unidad_salida'] != null && $row['iva'] != null &&  $row['ice'] != null
            ) {
                Producto::create([
                        'cod_principal' => $principal,
                        'cod_alterno' => $row['codigo_alterno'],
                        'imagen' => $row['imagen'],
                        'nombre' => $row['nombre'],
                        'codigo_barras' => $row['codigo_barras'],
                        'form_prod' => $row['form_prod'],
                        'descripcion' => $row['descripcion'],
                        'nombrec' => $row['nombrec'],
                        'sector' => $row['sector_producto_o_servicio'],
                        'tipo_servicio' => $row['tipo_servicio'],
                        'ubicacion_fisica' => $row['ubicacion_fisica'],
                        'unidad_entrada' => $row['unidad_entrada'],
                        'unidad_salida' => $row['unidad_salida'],
                        'vencimiento' => $row['fecha_vencimiento'],
                        'existencia_maxima' => $row['existencia_maxima'],
                        'existencia_minima' => $row['existencia_minima'],
                        'numero_unidad' => $row['numero_de_unidad'],
                        'estado' => $row['estado'],
                        'vehiculo' => $row['vehiculo'],
                        'placa' => $row['placa'],
                        'pais_origen' => $row['pais_origen'],
                        'ano_fabricacionv' => $row['ano_fabricacion'],
                        'color' => $row['color'],
                        'carroceria' => $row['carroceria'],
                        'combustible' => $row['combustible'],
                        'motor' => $row['motor'],
                        'cilindraje' => $row['cilindraje'],
                        'chasis' => $row['chasis'],
                        'clase' => $row['clase'],
                        'subclase' => $row['subclase'],
                        'numero_pasajeros' => $row['numero_pasajeros'],
                        'iva' => $row['iva'],
                        'ice' => $row['ice'],
                        'arancel_advalorem' => $row['arancel_ad_valorem'],
                        'arancel_especifico' => $row['arancel_especifico'],
                        'arancel_fodinfa' => $row['arancel_fodinfa'],
                        'comision' => $row['comision'],
                        'salvaguardia' => $row['salvaguardia'],
                        'pvp_precio1' => $row['pvp_precio1'],
                        'precio2' => $row['precio2'],
                        'precio3' => $row['precio3'],
                        'precio4' => $row['precio4'],
                        'precio5' => $row['precio5'],
                        'descuento' => $row['descuento'],
                        'utilidad' => $row['utilidad'],
                        'fecha_fabricacion' => $row['fecha_fabricacion'],
                        'ultimo_costo' => $row['ultimo_costo'],
                        'costo_promedio' => $row['costo_promedio'],
                        'costo_total' => $row['costo_total'],
                        'existencia_total' => $row['existencia_total'],
                        'caracteristicas' => $row['caracteristicas'],
                        'normativa' => $row['normativa'],
                        'uso' => $row['uso'],
                        'id_linea_producto' => $row['id_linea_producto'],
                        'id_tipo_producto' => $row['id_tipo_producto'],
                        'id_marca' => $row['id_marca'],
                        'id_modelo' => $row['id_modelo'],
                        'id_presentacion' => $row['id_presentacion'],
                        'id_tipo_medida' => $row['id_tipo_medida'],
                        'id_unidad_medida' => $row['id_unidad_medida'],
                        'id_empresa' => $row['id_empresa'],
                        'id_formula_produccion' => $row['id_formula_produccion'],
                        'id_plan_cuentas' => $row['id_plan_cuentas']
                ]);
            }
            if (count($ver) == 0 && $row['sector_producto_o_servicio'] == 2 && $row['nombre'] != null && $row['id_plan_cuentas'] != null && $row['descripcion'] != null && $row['iva'] != null) {
                Producto::create([
                        'cod_principal' => $principal,
                        'cod_alterno' => $row['codigo_alterno'],
                        'imagen' => $row['imagen'],
                        'nombre' => $row['nombre'],
                        'codigo_barras' => $row['codigo_barras'],
                        'form_prod' => $row['form_prod'],
                        'descripcion' => $row['descripcion'],
                        'nombrec' => $row['nombrec'],
                        'sector' => $row['sector_producto_o_servicio'],
                        'tipo_servicio' => $row['tipo_servicio'],
                        'ubicacion_fisica' => $row['ubicacion_fisica'],
                        'unidad_entrada' => $row['unidad_entrada'],
                        'unidad_salida' => $row['unidad_salida'],
                        'vencimiento' => $row['fecha_vencimiento'],
                        'existencia_maxima' => $row['existencia_maxima'],
                        'existencia_minima' => $row['existencia_minima'],
                        'numero_unidad' => $row['numero_de_unidad'],
                        'estado' => $row['estado'],
                        'vehiculo' => $row['vehiculo'],
                        'placa' => $row['placa'],
                        'pais_origen' => $row['pais_origen'],
                        'ano_fabricacionv' => $row['ano_fabricacion'],
                        'color' => $row['color'],
                        'carroceria' => $row['carroceria'],
                        'combustible' => $row['combustible'],
                        'motor' => $row['motor'],
                        'cilindraje' => $row['cilindraje'],
                        'chasis' => $row['chasis'],
                        'clase' => $row['clase'],
                        'subclase' => $row['subclase'],
                        'numero_pasajeros' => $row['numero_pasajeros'],
                        'iva' => $row['iva'],
                        'ice' => $row['ice'],
                        'arancel_advalorem' => $row['arancel_ad_valorem'],
                        'arancel_especifico' => $row['arancel_especifico'],
                        'arancel_fodinfa' => $row['arancel_fodinfa'],
                        'comision' => $row['comision'],
                        'salvaguardia' => $row['salvaguardia'],
                        'pvp_precio1' => $row['pvp_precio1'],
                        'precio2' => $row['precio2'],
                        'precio3' => $row['precio3'],
                        'precio4' => $row['precio4'],
                        'precio5' => $row['precio5'],
                        'descuento' => $row['descuento'],
                        'utilidad' => $row['utilidad'],
                        'fecha_fabricacion' => $row['fecha_fabricacion'],
                        'ultimo_costo' => $row['ultimo_costo'],
                        'costo_promedio' => $row['costo_promedio'],
                        'costo_total' => $row['costo_total'],
                        'existencia_total' => $row['existencia_total'],
                        'caracteristicas' => $row['caracteristicas'],
                        'normativa' => $row['normativa'],
                        'uso' => $row['uso'],
                        'id_linea_producto' => $row['id_linea_producto'],
                        'id_tipo_producto' => $row['id_tipo_producto'],
                        'id_marca' => $row['id_marca'],
                        'id_modelo' => $row['id_modelo'],
                        'id_presentacion' => $row['id_presentacion'],
                        'id_tipo_medida' => $row['id_tipo_medida'],
                        'id_unidad_medida' => $row['id_unidad_medida'],
                        'id_empresa' => $row['id_empresa'],
                        'id_formula_produccion' => $row['id_formula_produccion'],
                        'id_plan_cuentas' => $row['id_plan_cuentas']
                ]);
            }
            /*
            else {
                return view('Archivo Importado Sin Exito');
            } 
            */
            
        }
    }
    

    
   //prueba

    
    public function headingRow(): int
    {
        return 1;
    }
    public function sheets(): array
    {
        return [

            'Productos' => new ProductosImport(),
           
        ];
    }


    

}
