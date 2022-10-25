<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Marca;
use App\Models\Modelo;
use App\Models\Plancuenta;
use App\Models\Producto_bodega;
use Illuminate\Support\Facades\Hash;
use App\Models\Campo_adicional;
use App\Models\FormulaProducto;
use App\Models\FormulaProduccion;

include 'class/generarReportes.php';

use generarReportes;

include_once getenv("FILE_CONFIG_PHP");
class ProductoController extends Controller
{
    public function index(Request $request, $id)
    {

        $buscar = str_replace(array(" "), "%", $request->buscar);
        if ($buscar == '') {
            $recupera = Producto::addSelect([
                'nombremarca' => Marca::select('nombre')
                    ->whereColumn('id_marca', 'producto.id_marca'),
                'nombremodelo' => Modelo::select('nombre')
                    ->whereColumn('id_modelo', 'producto.id_modelo')
            ])
                ->where('id_empresa', '=', $id)
                ->orderByRaw('id_producto DESC')->get();
        } else {
            $recupera = Producto::addSelect([
                'nombremarca' => Marca::select('nombre')
                    ->whereColumn('id_marca', 'producto.id_marca'),
                'nombremodelo' => Modelo::select('nombre')
                    ->whereColumn('id_modelo', 'producto.id_modelo')
            ])
                ->where(function ($q) use ($buscar) {
                    $q->where('nombre', 'like', '%' . $buscar . '%')
                        ->orWhere('cod_principal', 'like', '%' . $buscar . '%')
                        ->orWhere('cod_alterno', 'like', '%' . $buscar . '%');
                })
                ->where('id_empresa', '=', $id)
                ->orderByRaw('id_producto DESC')->get();
        }
        return [
            'recupera' => $recupera
        ];
    }

    public function listcategorias($id){
        $cat = DB::select("SELECT `categoria_producto` FROM `empresa` WHERE `id_empresa` = '" . $id . "'");
        $cat = explode(';', $cat[0]->categoria_producto);
        return $cat;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function guardar(Request $request)
    {
        $carpetanombre = constant("DATA_EMPRESA");
        $sel = DB::select("SELECT cod_principal FROM `producto` WHERE `id_empresa` = '" . $request->id_empresa . "' ORDER BY id_producto DESC LIMIT 1");
        if (!$request->file_imagen) {
            if ($request->file('file_imagen')) {
                $file_imagen = $request->file('file_imagen');
                $nombre_imagen = time() . $file_imagen->getClientOriginalName();
            } else {
                $nombre_imagen = "";
            }
        }
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
        if ($request->file_imagen) {
            $ubicacion_imagen = $carpetanombre . $request->id_empresa . "/productos/";
            //$file_imagen = $request->file('file_imagen');
            $nombre_imagen = time() . $request->file('file_imagen')->getClientOriginalName();
            $request->file('file_imagen')->move($ubicacion_imagen, $nombre_imagen);
            $agregarimagen = $nombre_imagen;
        } else {
            $agregarimagen = "";
        }
        if ($request->cta_prod != null) {
            $selc = DB::select("SELECT `id_plan_cuentas` FROM `plan_cuentas` WHERE `id_plan_cuentas` = '" . $request->cta_prod . "'");
            if (!$selc) {
                return "cuentamal";
            }
        }

        $producto = new Producto();
        //Agregar Producto 
        $producto->categoria = $request->categoria;
        $producto->cod_principal = $principal;
        $producto->cod_alterno = $request->cod_alterno;

        $producto->nombre = $request->nombre;
        $producto->codigo_barras = $request->cod_barras;
        $producto->descripcion = $request->descripcion;
        $producto->caracteristicas = $request->caracteristicas;
        $producto->normativa = $request->normativa;
        $producto->uso = $request->uso;
        // Campos adicionales
        $producto->id_plan_cuentas = $request->cta_prod;
        $producto->form_prod = $request->form_prod;
        // $producto->nombrec = $datof;
        //implode(";", $_POST["pdentales"]);
        //$producto->contenido = $request->contenido;
        //Línea de Producto: 
        //Línea de Producto: fk
        $producto->id_linea_producto = $request->linea_producto;
        //tipo_producto fk listar
        $producto->id_tipo_producto = $request->tipo_producto;
        $producto->id_marca = $request->marca;
        $producto->id_modelo = $request->modelo;
        $producto->id_presentacion = $request->presentacion;
        //$producto->id_bodega = $request->bodega;
        $producto->sector = $request->sector;
        $producto->tipo_servicio = $request->tipo_servicio;
        $producto->unidad_entrada = $request->unidad_entrada;
        $producto->unidad_salida = $request->unidad_salida;
        $producto->vencimiento = $request->vencimiento;
        $producto->existencia_maxima = $request->existencia_max;
        $producto->existencia_minima = $request->existencia_min;
        //Dimensiones del Producto:
        $producto->id_tipo_medida = $request->tipo_medida;
        $producto->id_unidad_medida = $request->unidad_medida;
        $producto->numero_unidad = $request->numero_unidad;
        $producto->grados_alcohol = $request->grados_alcohol;
        $producto->estado = $request->estado;
        //VEHICULO 
        $producto->vehiculo = $request->vehiculo;
        $producto->placa = $request->placa;
        $producto->pais_origen = $request->pais_origen;
        $producto->ano_fabricacionv = $request->ano_fabricacion;
        $producto->color = $request->color;
        $producto->carroceria = $request->carroceria;
        $producto->combustible = $request->combustible;
        $producto->motor = $request->motor;
        $producto->cilindraje = $request->cilindraje;
        $producto->chasis = $request->chasis;
        $producto->clase = $request->clase;
        $producto->subclase = $request->subclase;
        $producto->numero_pasajeros = $request->numero_pasajeros;
        //Rubros del Producto:
        $producto->iva = $request->iva;
        $producto->ice = $request->ice;
        $producto->total_ice = $request->total_ice;
        $producto->arancel_advalorem = $request->arancel_advalorem;
        $producto->arancel_especifico = $request->arancel_especifico;
        $producto->arancel_fodinfa = $request->arancel_fodinfa;
        $producto->comision = $request->comision;
        $producto->salvaguardia = $request->salvaguardia;
        $producto->descuento = $request->descuento;
        $producto->pvp_precio1 = $request->precio1;
        $producto->precio2 = $request->precio2;
        $producto->precio3 = $request->precio3;
        $producto->precio4 = $request->precio4;
        $producto->precio5 = $request->precio5;
        $producto->utilidad_precio1 = $request->utilidad_precio1;
        $producto->utilidad_precio2 = $request->utilidad_precio2;
        $producto->utilidad_precio3 = $request->utilidad_precio3;
        $producto->utilidad_precio4 = $request->utilidad_precio4;
        $producto->utilidad_precio5 = $request->utilidad_precio5;
        $producto->costo_unitario = $request->costo_unitario;
        $producto->imagen = $agregarimagen;
        $producto->existencia_total = $request->existencia_total;
        $producto->medicamento_controlado = filter_var($request->medicamento_controlado, FILTER_VALIDATE_BOOLEAN);
        $producto->psicotropicos = $request->psicotropicos;
        $producto->id_empresa = $request->id_empresa;
        $producto->id_formula_produccion = $request->id_formu_prod;
        $producto->save();
        $id = $producto->id_producto;

        if ($request->id_formu_prod) {
            $form_prod = new FormulaProducto;
            $form_prod->id_producto = $id;
            $form_prod->id_formula_produccion = $request->id_formu_prod;
            $form_prod->save();
        }
        return $id;
    }
    public function guardarimagen(Request $request)
    {
        //obtenemos el campo file definido en el formulario
        $file = $request->file('file');
        //obtenemos el nombre del archivo
        $nombre = time() . $file->getClientOriginalName();
        //indicamos que queremos guardar un nuevo archivo en el disco local
        $ubicacion = "../../dataempresa/" . $request->id_empresa . "/productos/";
        $file->move($ubicacion, $nombre);
        $producto = Producto::findOrFail($request->id);
        $producto->imagen = $ubicacion . $nombre;
        $producto->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actualizar(Request $request)
    {
        $carpetanombre = constant("DATA_EMPRESA");
        //$datal = "";
        //datos adicionales
        /*if (count($request->agregados) >= 1) {
            for ($i = 0; $i < count($request->agregados); $i++) {
                $datal .= $request->agregados[$i]['descripcion'] . "||";
            }
            $datof = substr($datal, 0, -1);
        } else {
            $datof = "";
        }*/
        if ($request->cta_prod != null) {
            $sel = DB::select("SELECT `id_plan_cuentas` FROM `plan_cuentas` WHERE `id_plan_cuentas` = '" . $request->cta_prod . "'");
            if (!$sel) {
                return "cuentamal";
            }
        }
        if (!$request->recuperaimagen) {
            if ($request->file_imagen) {
                $file_imagen = $request->file('file_imagen');
                $nombre_imagen = time() . $file_imagen->getClientOriginalName();
                $ubicacion_imagen = $carpetanombre . $request->id_empresa . "/productos/";
                $request->file('file_imagen')->move($ubicacion_imagen, $nombre_imagen);
                $agregarimagen = $nombre_imagen;
            } else {
                $agregarimagen = "";
            }
        }

        $producto = Producto::findOrFail($request->id);
        //Agregar Producto 
        $producto->categoria = $request->categoria;
        $producto->cod_principal = $request->cod_principal;
        $producto->cod_alterno = $request->cod_alterno;
        $producto->nombre = $request->nombre;
        $producto->codigo_barras = $request->cod_barras;
        $producto->descripcion = $request->descripcion;
        $producto->caracteristicas = $request->caracteristicas;
        $producto->normativa = $request->normativa;
        $producto->uso = $request->uso;
        // Campos adicionales
        $producto->id_plan_cuentas = $request->cta_prod;
        $producto->form_prod = $request->form_prod;
        //$producto->nombrec = $datof;
        //implode(";", $_POST["pdentales"]);
        //$producto->contenido = $request->contenido;
        //Línea de Producto: 
        //Línea de Producto: fk
        $producto->id_linea_producto = $request->linea_producto;
        //tipo_producto fk listar
        $producto->id_tipo_producto = $request->tipo_producto;
        $producto->id_marca = $request->marca;
        $producto->id_modelo = $request->modelo;
        $producto->id_presentacion = $request->presentacion;
        //$producto->id_bodega = $request->bodega;
        $producto->sector = $request->sector;
        $producto->tipo_servicio = $request->tipo_servicio;
        $producto->unidad_entrada = $request->unidad_entrada;
        $producto->unidad_salida = $request->unidad_salida;
        $producto->vencimiento = $request->vencimiento;
        $producto->existencia_maxima = $request->existencia_max;
        $producto->existencia_minima = $request->existencia_min;
        //Dimensiones del Producto:
        $producto->id_tipo_medida = $request->tipo_medida;
        $producto->id_unidad_medida = $request->unidad_medida;
        $producto->numero_unidad = $request->numero_unidad;
        $producto->grados_alcohol = $request->grados_alcohol;
        $producto->estado = $request->estado;
        //VEHICULO 
        $producto->vehiculo = $request->vehiculo;
        $producto->placa = $request->placa;
        $producto->pais_origen = $request->pais_origen;
        $producto->ano_fabricacionv = $request->ano_fabricacion;
        $producto->color = $request->color;
        $producto->carroceria = $request->carroceria;
        $producto->combustible = $request->combustible;
        $producto->motor = $request->motor;
        $producto->cilindraje = $request->cilindraje;
        $producto->chasis = $request->chasis;
        $producto->clase = $request->clase;
        $producto->subclase = $request->subclase;
        $producto->numero_pasajeros = $request->numero_pasajeros;
        //Rubros del Producto:
        $producto->iva = $request->iva;
        $producto->ice = $request->ice;
        $producto->total_ice = $request->total_ice;
        $producto->arancel_advalorem = $request->arancel_advalorem;
        $producto->arancel_especifico = $request->arancel_especifico;
        $producto->arancel_fodinfa = $request->arancel_fodinfa;
        $producto->comision = $request->comision;
        $producto->salvaguardia = $request->salvaguardia;
        $producto->descuento = $request->descuento;
        $producto->pvp_precio1 = $request->precio1;
        $producto->precio2 = $request->precio2;
        $producto->precio3 = $request->precio3;
        $producto->precio4 = $request->precio4;
        $producto->precio5 = $request->precio5;
        $producto->utilidad_precio1 = $request->utilidad_precio1;
        $producto->utilidad_precio2 = $request->utilidad_precio2;
        $producto->utilidad_precio3 = $request->utilidad_precio3;
        $producto->utilidad_precio4 = $request->utilidad_precio4;
        $producto->utilidad_precio5 = $request->utilidad_precio5;
        $producto->costo_unitario = $request->costo_unitario;
        $producto->existencia_total = $request->existencia_total;
        $producto->medicamento_controlado = filter_var($request->medicamento_controlado, FILTER_VALIDATE_BOOLEAN);
        $producto->psicotropicos = $request->psicotropicos;
        $producto->id_empresa = $request->id_empresa;
        $producto->id_formula_produccion = $request->id_formu_prod;
        if (!$request->recuperaimagen) {
            $producto->imagen = $agregarimagen;
        }
        $producto->save();

        //$recupera = DB::delete('DELETE FROM producto_bodega where id_producto = ' . $id);

        /*if ($request->id_rol == 1) {
            if (count($request->agregados) >= 1) {
                for ($d = 0; $d < count($request->agregados); $d++) {
                    if (!empty($request->agregados[$d]['nombre'])) {
                        $productob = new Campo_adicional();
                        $productob->nombre = $request->agregados[$d]['nombre'];
                        $productob->id_empresa = 1;
                        $productob->save();
                    }
                }
            }
        }*/
        if ($request->id_formu_prod) {
            DB::delete("DELETE FROM formula_producto where id_producto = $request->id");
            $form_prod = new FormulaProducto;
            $form_prod->id_producto = $request->id;
            $form_prod->id_formula_produccion = $request->id_formu_prod;
            $form_prod->save();
        }
        return $request->id;
    }
    public function eliminar($id)
    {
        //$recupera = DB::delete('DELETE FROM campo_adicional where id_empresa = 1');
        //  $recupera = DB::delete('DELETE FROM producto_bodega where id_producto = '. $id);
        Producto::destroy($id);
        /*$id = $request->id;
     $eliminar = Producto::find($request->id);
      $eliminar->delete();
*/
    }
    public function abrir($id)
    {

        $recupera = Producto::select("producto.*", "plan_cuentas.nomcta as cta_prod1")
            ->leftjoin("plan_cuentas", "plan_cuentas.id_plan_cuentas", "=", "producto.id_plan_cuentas")
            ->where("id_producto", "=", $id)->get();
        return $recupera[0];
    }

    public function camposadicionales(Request $request)
    {
        $id = $request->id;
        $recupera = DB::select('select * from campo_adicional where id_empresa = 1');
        return $recupera;
    }

    //traer productos para reporte
    public function ProductoReporte(Request $request)
    {
        $producto = DB::select("SELECT concat('Cod:',cod_principal,'-',nombre) as nombre_producto,id_producto from producto where id_empresa=" . $request->id_empresa);
        return $producto;
    }
    //traer productos para reporte
    public function ProductoEmpresa($id_empresa)
    {
        $empresa = DB::select("SELECT * FROM empresa WHERE id_empresa=" . $id_empresa);
        return $empresa;
    }

    public function generarReporte(Request $request)
    {
        if (!$request->id_producto) {
            $producto = DB::select("SELECT id_producto,cod_principal,nombre,imagen from producto where id_empresa=" . $request->id_empresa);
        } else {
            $producto = DB::select("SELECT id_producto,cod_principal,nombre,imagen from producto where id_producto=" . $request->id_producto);
        }
        //dd($producto);
        $empresa = DB::select("SELECT * from empresa where id_empresa=" . $request->id_empresa);
        $Reportes = new generarReportes();
        $strPDF = $Reportes->Producto($producto, $empresa[0]);
        return response($strPDF, 200)->header('Content-Type', 'application/pdf');
    }
}
