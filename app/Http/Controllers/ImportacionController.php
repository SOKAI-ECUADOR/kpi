<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Importacion;
use App\Models\Proveedor;
use App\Models\Producto;
use App\Models\ProductoFactura;
use App\Models\Proveedorimportacion;

include 'class/generarReportes.php';
use generarReportes;

include 'class/sendEmail.php';

use sendEMail;

class ImportacionController extends Controller
{
   //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$id)
    {
        $buscar = $request->buscar;
        if ($buscar==''){
            // $recupera = Importacion::select('importacion.*')
            // //->join('factur','producto.id_producto','=','importacion.id_producto')
            // ->where('id_punto_emision',$id)
            // ->where('cod_importacion',like)
            // ->orderByRaw('importacion.id_importacion ASC')->get();
            $recupera =DB::select("SELECT * from importacion where id_punto_emision={$id} order by id_importacion ASC");
        }else{
            // $recupera = Importacion::select('importacion.*')
            // //->join('producto','producto.id_producto','=','importacion.id_producto')
            // //->where('producto.nombre','like','%'.$buscar.'%')
            // ->where('id_punto_emision',$id)
            // ->orderByRaw('importacion.id_importacion ASC')->get();
            $recupera =DB::select("SELECT * from importacion where id_punto_emision={$id} and cod_importacion like '%{$buscar}%' order by id_importacion ASC");
        } 
        return [
            'recupera' => $recupera
        ];
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sel = DB::select("SELECT cod_importacion FROM importacion ORDER BY id_importacion DESC LIMIT 1");
        $principal = "";
        if (count($sel) >= 1) {
            $dato = $sel[0]->cod_importacion;
            if(($dato+1) >= 100){
                $tot = $dato + 1;
                $principal = $tot;
            }else if(($dato+1) >= 10){
                $tot = $dato + 1;
                $principal = "0".$tot;
            }else{
                $tot = $dato + 1;
                $principal = "00".$tot;
            }
        } else {
            $principal = "001";
        }
        // `cod_importacion`, `estado`, `periodo_inicio`, `periodo_fin`, `fech_embarque`, `fech_arribo`, `total_facturas`, `total_liquidacion`, `total_importacion`, `id_proveedor`, `id_orden`, `id_user`, `id_empresa`, `id_punto_emision`
        $import =new Importacion();
        $import->cod_importacion=$principal;
        $import->estado="Inicial";
        $import->periodo_inicio=$request->periodo_inicio;
        $import->periodo_fin=$request->periodo_fin;
        $import->fech_embarque=$request->fech_embarque;
        $import->fech_arribo=$request->fech_arribo;
        $import->forma_liquidacion=$request->forma_liquidacion;
        $import->total_importacion=$request->total_importacion;
        //$import->id_proveedor=$request->id_proveedor;
        $import->id_orden=$request->nro_orden;
        $import->id_user=$request->id_user;
        $import->id_empresa=$request->id_empresa;
        $import->id_punto_emision=$request->id_punto_emision;
        $import->save();
        $id=$import->id_importacion;
        return $id;
    }
    public function guardarProv(Request $request){
        $id=$request->id_import;
        for ($a = 0; $a < count($request->provds); $a++) {
            if($request->provds[$a]["nombre"]!=null){
                $dfactc = new Proveedorimportacion();
                $dfactc->nombre = $request->provds[$a]["nombre"];
                $dfactc->telefono = $request->provds[$a]["telefono"];
                if(isset($request->provds[$a]["grupo"])){
                    $dfactc->grupo = $request->provds[$a]["grupo"];
                }
                $dfactc->tipo_identificacion = $request->provds[$a]["tipo_identificacion"];
                $ident=trim($request->provds[$a]["identificacion"]);
                $dfactc->identificacion = $ident;
                $dfactc->direccion = $request->provds[$a]["direccion"];
                $dfactc->id_proveedor = $request->provds[$a]["id_proveedor"];
                $dfactc->id_importacion = $id;
                $dfactc->id_empresa=$request->id_empresa;
                $dfactc->save();
            }

        }
        return $dfactc;
    }
    public function guardarProd(Request $request){
        $id=$request->id_import;
        for ($a = 0; $a < count($request->productos); $a++) {
            $dfactc = new ProductoFactura();
            $dfactc->codigo = $request->productos[$a]["codigo"];
            $dfactc->nombre = $request->productos[$a]["nombre"];
            $dfactc->cantidad = $request->productos[$a]["cantidad"];
            $dfactc->precio = $request->productos[$a]["precio"];
            $dfactc->total = $request->productos[$a]["precio"]*$request->productos[$a]["cantidad"];
            $dfactc->id_importacion = $id;
            $dfactc->id_producto = $request->productos[$a]["id_producto"];
            if(isset($request->productos[$a]["id_bodega_prod"])){
                $dfactc->id_bodega = $request->productos[$a]["id_bodega_prod"];
            }else{
                if(isset($request->productos[$a]["id_bodega"])){
                    $dfactc->id_bodega = $request->productos[$a]["id_bodega"];
                }
            }
            if(isset($request->productos[$a]["proyecto"])){
                $dfactc->id_proyecto = $request->productos[$a]["proyecto"];
            }
            if(isset($request->productos[$a]["iva"])){
                $dfactc->id_iva = $request->productos[$a]["iva"];
            }
            if(isset($request->productos[$a]["ice"])){
                $dfactc->id_ice = $request->productos[$a]["ice"];
            }
            if(isset($request->productos[$a]["id_producto_bodega"])){
                $dfactc->id_producto_bodega = $request->productos[$a]["id_producto_bodega"];
            }
            $dfactc->save();
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $import =Importacion::findOrFail($request->id);
        //$import->estado=$request->estado;
        $import->periodo_inicio=$request->periodo_inicio;
        $import->periodo_fin=$request->periodo_fin;
        $import->fech_embarque=$request->fech_embarque;
        $import->fech_arribo=$request->fech_arribo;
        $import->forma_liquidacion=$request->forma_liquidacion;
        $import->total_importacion=$request->total_importacion;
        //$import->id_proveedor=$request->id_proveedor;
        $import->id_orden=$request->nro_orden;
        //$import->id_user=$request->id_user;
        //$import->id_empresa=$request->id_empresa;
        //$import->id_punto_emision=$request->id_punto_emision;
        //$import->id_producto=$request->id_producto;
        $import->save();
        $id=$import->id_importacion;
        return $id;
    }
    public function actProducto(Request $request){
        $id=$request->id_orden;
        $array_elim=[];
        for ($a = 0; $a < count($request->productos); $a++) {
            array_push($array_elim,$request->productos[$a]["id_producto"]);
            if($request->productos[$a]["id_prodimp"]==null){
                $dfactc = new ProductoFactura();
                $dfactc->codigo = $request->productos[$a]["codigo"];
                $dfactc->nombre = $request->productos[$a]["nombre"];
                $dfactc->cantidad = $request->productos[$a]["cantidad"];
                $dfactc->precio = $request->productos[$a]["precio"];
                $dfactc->total = $request->productos[$a]["precio"]*$request->productos[$a]["cantidad"];
                $dfactc->id_importacion = $id;
                //$dfactc->id_producto = $request->productos[$a]["id_producto"];
                $dfactc->id_producto = $request->productos[$a]["id_producto"];
                if(isset($request->productos[$a]["id_bodega_prod"])){
                    $dfactc->id_bodega = $request->productos[$a]["id_bodega_prod"];
                }else{
                    if(isset($request->productos[$a]["id_bodega"])){
                        $dfactc->id_bodega = $request->productos[$a]["id_bodega"];
                    }
                }
                if(isset($request->productos[$a]["proyecto"])){
                    $dfactc->id_proyecto = $request->productos[$a]["proyecto"];
                }
                if(isset($request->productos[$a]["iva"])){
                    $dfactc->id_iva = $request->productos[$a]["iva"];
                }
                if(isset($request->productos[$a]["ice"])){
                    $dfactc->id_ice = $request->productos[$a]["ice"];
                }
                if(isset($request->productos[$a]["id_producto_bodega"])){
                    $dfactc->id_producto_bodega = $request->productos[$a]["id_producto_bodega"];
                }
                $dfactc->save();
            }
            if($request->productos[$a]["id_prodimp"]!=null){
                $dfactc =ProductoFactura::find($request->productos[$a]["id_prodimp"]);
                $dfactc->codigo = $request->productos[$a]["codigo"];
                $dfactc->nombre = $request->productos[$a]["nombre"];
                $dfactc->cantidad = $request->productos[$a]["cantidad"];
                $dfactc->precio = $request->productos[$a]["precio"];
                $dfactc->total = $request->productos[$a]["precio"]*$request->productos[$a]["cantidad"];
                $dfactc->id_importacion = $id;
                $dfactc->id_producto = $request->productos[$a]["id_producto"];
                if(isset($request->productos[$a]["id_bodega_prod"])){
                    $dfactc->id_bodega = $request->productos[$a]["id_bodega_prod"];
                }else{
                    if(isset($request->productos[$a]["id_bodega"])){
                        $dfactc->id_bodega = $request->productos[$a]["id_bodega"];
                    }
                }
                if(isset($request->productos[$a]["proyecto"])){
                    $dfactc->id_proyecto = $request->productos[$a]["proyecto"];
                }
                if(isset($request->productos[$a]["iva"])){
                    $dfactc->id_iva = $request->productos[$a]["iva"];
                }
                if(isset($request->productos[$a]["ice"])){
                    $dfactc->id_ice = $request->productos[$a]["ice"];
                }
                if(isset($request->productos[$a]["id_producto_bodega"])){
                    $dfactc->id_producto_bodega = $request->productos[$a]["id_producto_bodega"];
                }
                
                $dfactc->save();  
            }
            
        }
        
        if(count($array_elim)>0){
            $array_elim=implode(" , ",$array_elim);
            $prod_exist=DB::select("SELECT * FROM producto_importacion where id_importacion={$id} and id_producto not in ($array_elim)");
            if(count($prod_exist)>0){
                foreach($prod_exist as $detail){
                    DB::delete("DELETE FROM producto_importacion where id_importacion={$id} and id_producto={$detail->id_producto}");
                }
            }
        }
        return $dfactc;
            
    }
    public function abrir(Request $request)
    {
        $id = $request->id;
        $recupera = DB::select('SELECT * FROM `importacion` WHERE id_importacion='.$id);
        return $recupera;
    }
    public function abrirProducto(Request $request)
    {
        $id=$request->id;
        $recuperap = DB::select('SELECT producto_importacion.id_prodimp,if(producto.cod_alterno is not null,producto.cod_alterno,producto.cod_principal) as  codigo,producto_importacion.nombre,producto_importacion.cantidad,producto_importacion.precio,bodega.nombre as nombrebodega,producto.sector,producto_importacion.id_iva as iva,producto_importacion.id_ice as ice,producto_importacion.id_bodega,producto_importacion.id_proyecto as proyecto,producto_importacion.id_producto_bodega,producto_importacion.id_producto,proyecto.descripcion,producto_importacion.total 
        FROM `producto_importacion` 
        LEFT JOIN bodega
        on bodega.id_bodega=producto_importacion.id_bodega
        LEFT JOIN producto
        on producto.id_producto=producto_importacion.id_producto
				LEFT JOIN proyecto
        on proyecto.id_proyecto=producto_importacion.id_proyecto
        WHERE id_importacion ='.$id);
        return $recuperap;
    }
    public function abrirProductoLiquid(Request $request)
    {
        $id=$request->id;
        $recuperap = DB::select("SELECT producto_importacion.id_prodimp,if(producto.cod_alterno is not null,producto.cod_alterno,producto.cod_principal) as  codigo,producto_importacion.nombre,producto_importacion.cantidad,producto_importacion.precio,bodega.nombre as nombrebodega,producto.sector,producto_importacion.id_iva as iva,producto_importacion.id_ice as ice,producto_importacion.id_bodega,producto_importacion.id_proyecto as proyecto,producto_importacion.id_producto_bodega,producto_importacion.id_producto,proyecto.descripcion,producto_importacion.total,producto_importacion.cantidad_liquidacion,producto_importacion.precio_liquidacion,producto_importacion.total_liquidacion,0 as nuevo_costo 
        FROM `producto_importacion` 
        LEFT JOIN bodega
        on bodega.id_bodega=producto_importacion.id_bodega
        LEFT JOIN producto
        on producto.id_producto=producto_importacion.id_producto
				LEFT JOIN proyecto
        on proyecto.id_proyecto=producto_importacion.id_proyecto
        WHERE id_importacion ={$id} order by bodega.nombre asc");
        $proveedores=DB::select("SELECT nombre from proveedor_importacion where id_importacion={$id}");
        return [
            "recupera"=>$recuperap,
            "proveedores"=>$proveedores
        ];
    }
    public function abrirProvedor(Request $request)
    {
        $id=$request->id;
        $recuperap = DB::select('SELECT provimp.*,gprov.nombre_grupoprov as nombre_grupo FROM `proveedor_importacion` as provimp
        INNER JOIN proveedor as prov
        on prov.id_proveedor = provimp.id_proveedor
        LEFT JOIN grupo_proveedor as gprov
        on gprov.id_grupoprov=prov.id_grupo_proveedor
         WHERE provimp.id_importacion ='.$id);
        return $recuperap;
    }
    public function traerProvedor($id)
    {
        
        $recuperap = DB::select('SELECT prov.*,gprov.nombre_grupoprov FROM `proveedor` as prov 
        LEFT JOIN grupo_proveedor as gprov
        on gprov.id_grupoprov=prov.id_grupo_proveedor
        WHERE prov.id_proveedor ='.$id);
        return $recuperap;
    }
    public function eliminar ($id){
        ProductoFactura::where('id_importacion',$id)->delete();
        Proveedorimportacion::where('id_importacion',$id)->delete();
        Importacion::destroy($id);
      /*$id = $request->id;
       $eliminar = Producto::find($request->id);
        $eliminar->delete();
  */
      }
      public function getProveedor($id){
        //$data=Proveedor::all();
        $data = DB::select('SELECT * FROM `proveedor` WHERE id_empresa = '.$id);
        return $data;
      }
      public function getProducto($id){
        $data = DB::select('SELECT * FROM `producto` WHERE id_empresa = '.$id);

        return $data;
      }
      public function getOrden($id){
        $data = DB::select('SELECT * FROM `factura_compra` WHERE modo_orden = 1 AND id_empresa ='.$id);

        return $data;
      }
      public function generarPDF(Request $request){
        $orden_compra=DB::select("SELECT e.logo,e.nombre_empresa,e.ruc_empresa,e.direccion_empresa,
        c.identificacion,f.fcrea as fecha_emision,c.nombre as cliente,p.cod_principal,p.cod_alterno,
        u.nombre as ciudad, ci.contacto,c.direccion,ci.email,ci.telefono_prov as telefono,p.imagen,p.nombre,p.descripcion,p.caracteristicas,p.normativa,p.uso,
        m.nombre as marca,tm.nombre as tipo_medida,um.nombre as unidad_medida,df.cantidad,df.precio,df.total as total_pro,null as tiempo_entrega,null as cpc,
        f.total_liquidacion as subtotal,null as descuento,f.cod_importacion as codigo, null as iva, f.total_liquidacion as total, CONCAT(v.nombres, ' ', v.apellidos) as vendedor,
        v.email as mailvendedor,v.telefono as telefono_vendedor
        from importacion f 
				LEFT JOIN producto_importacion df ON f.id_importacion=df.id_importacion 
				LEFT JOIN proveedor_importacion c ON c.id_importacion=f.id_importacion
				LEFT JOIN proveedor ci ON ci.id_proveedor=c.id_proveedor 	
				LEFT JOIN empresa e ON e.id_empresa=f.id_empresa 
				LEFT JOIN ciudad u ON u.id_ciudad=e.id_ciudad 
				LEFT JOIN producto p ON df.id_producto=p.id_producto 
				LEFT JOIN marca m ON p.id_marca=m.id_marca 
				LEFT JOIN tipo_medida tm ON tm.id_tipo_medida=p.id_tipo_medida 
				LEFT JOIN unidad_medida um ON um.id_unidad_medida=p.id_unidad_medida 
				LEFT JOIN user v ON v.id = f.id_user
        where f.id_importacion=".$request->id_orden);
        $factura_info = DB::select("SELECT
                                e.email_empresa ,
                                e.id_empresa,
                                e.telefono,
                                null as lugar_de_entrega,
                                null as condiciones_de_pago,
                                NULL AS observacion,
                                f.periodo_inicio as fecha_inicio,
                                f.periodo_fin as fecha_expiracion,
                                f.fech_embarque,
                                f.fech_arribo,
                                NULL as forma_pago,
                                est.urlweb
                            FROM importacion f
                            LEFT JOIN empresa e
                            ON e.id_empresa = f.id_empresa
                            LEFT JOIN establecimiento est
                            ON est.id_empresa = e.id_empresa
                            WHERE f.id_importacion = " . $request->id_orden);
        if (!$orden_compra) {
            return response('no-data-report', 200)->header('Content-Type', 'application/json');
        } else {
            if($request->destinatario==null && $request->email==null){
                $reportePdf = new generarReportes();
                $strPDF = $reportePdf->importacionPDF($request->id_orden,$orden_compra, $orden_compra[0]->codigo, $factura_info[0]);
                return response($strPDF, 200)->header('Content-Type', 'application/pdf');
            }else{
                $ruta = constant("DATA_EMPRESA") . $request->id_empresa . '/compras/orden_compra';
                if (!file_exists($ruta)) {
                    mkdir($ruta, 0755, true);
                }
                $reportePdf = new generarReportes();
                $strPDF = $reportePdf->importacionPDF($request->id_orden,$orden_compra, $orden_compra[0]->codigo, $factura_info[0],$generar_en_servidor = true, $ruta);
                return response($strPDF, 200)->header('Content-Type', 'application/pdf');
            }
            
        }
    }
}
