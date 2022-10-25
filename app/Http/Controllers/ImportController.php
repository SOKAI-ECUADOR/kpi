<?php
namespace App\Http\Controllers;
use App\Imports\clienteImport;
use App\Imports\PlanCuentasImport;
use App\Imports\ProveedorImport;
use App\Imports\VendedoresImport;
use App\Imports\BodegaImport;

use App\Imports\ProductosImport;
use App\Imports\LineaProductoImport; 
use App\Imports\TipoProductosImport;
use App\Imports\MarcaProductosImport;
use App\Imports\ModelosProductosImport;
use App\Imports\PresentacionProductosImport;
use App\Imports\ProyectosImport;
use App\Imports\UsuariosImport;
use App\Imports\EstablecimientoImport;
use App\Imports\RegistroImportacionImport;
use App\Imports\CodigoImpuestoImport;
use App\Imports\TipoComprobanteImport;
use App\Imports\TipoSustentoImport;
use App\Imports\FormasPagoSriImport;
use App\Imports\RetencionesImport;
use App\Imports\PuntoEmisionImport;
use App\Models\Vendedorcliente;

use Maatwebsite\Excel\Concerns\ValidationException;

use Illuminate\Support\Facades\DB;
/***
 * Exports
 * 
 */
use App\Exports\InvoicesExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Rap2hpoutre\FastExcel\FastExcel;

class ImportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function importCodigoImpuesto(Request $request)
    {

        $file = $request->file('file');
        $id = $request->id_empresa;
        Excel::import(new CodigoImpuestoImport , $file, $id);

        
    }

    public function importTipoComprobante(Request $request)
    {

        $file = $request->file('file');
        $id = $request->id_empresa;
        Excel::import(new TipoComprobanteImport , $file, $id);

        
    }

    public function importTipoSustento(Request $request)
    {

        $file = $request->file('file');
        $id = $request->id_empresa;
        Excel::import(new TipoSustentoImport , $file, $id);

        
    }

    public function importFormasPagoSri(Request $request)
    {

        $file = $request->file('file');
        $id = $request->id_empresa;
        Excel::import(new FormasPagoSriImport , $file, $id);

        
    }
    
    public function importRetenciones(Request $request)
    {

        $file = $request->file('file');
        $id = $request->id_empresa;
        Excel::import(new RetencionesImport , $file, $id); 
    }
    
    
    public function importPuntosEmision(Request $request)
    {

        $file = $request->file('file');
        $id = $request->id_empresa;
        Excel::import(new PuntoEmisionImport , $file, $id); 
    }
   

    public function import(Request $request)
    {
        
        $file = $request->file('file');
        $id = $request->id_empresa;
        Excel::import(new clienteImport , $file, $id);

        
        
        
        /*
        try {
            $file = $request->file('file');
            Excel::import(new clienteImport, $file);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             $fallas = $e->failures();
        
             foreach ($fallas as $falla) {
                 $falla->row(); // fila en la que ocurrió el error
                 $falla->attribute(); // el número de columna o la "llave" de la columna
                 $falla->errors(); // Errores de las validaciones de laravel
                 $falla->values(); // Valores de la fila en la que ocurrió el error.
             }
        }
        */
        
    }

    public function importPlanCuentas(Request $request)
    {

        $file = $request->file('file');
        $id = $request->id_empresa;
        Excel::import(new PlanCuentasImport , $file, $id);

        
    }

    public function importProveedor(Request $request)
    {

        $file = $request->file('file');
        $id = $request->id_empresa;
        Excel::import(new ProveedorImport , $file);
    } 

    public function ImportVendedores(Request $request)
    {
        $file = $request->file('file');
        $id = $request->id_empresa;
        Excel::import(new VendedoresImport , $file);

        /*
        $file = $request->file('file');
        $id = $request->id_empresa;
        
        $collection = Excel::toCollection(new VendedoresImport , $file);
        //return $collection;
        $idempre = $$row['id_empresa'];
        $nomVen = $$row['nombre_vendedor'];
        $ver = DB::select("SELECT * FROM vendedor WHERE nombre_vendedor = '" . $nomVen . "' AND id_empresa ='" . $idempre . "' ");
        foreach ($collection["Vendedores"] as $row) {
            if(count($ver) == 0 && !filter_var($row['email_vendedor'], FILTER_VALIDATE_EMAIL) === false && $row['nombre_vendedor'] != null && $row['email_vendedor'] != null){
                return "bien";
            }else{
                $array [] .= "el vendedor". $row['nombre_vendedor'] . "esta repetido"; 
                return $array;
            }
        }
        */
    }

    public function importBodega(Request $request)
    {

        $file = $request->file('file');
        $id = $request->id_empresa;
        Excel::import(new BodegaImport , $file);
    }
     /***
     * producto
     * 
     * 
     */
    
    public function importProductos(Request $request)
    {
        $file = $request->file('file');
        //$err = $request->err;
        $id = $request->id_empresa;
        Excel::import(new ProductosImport , $file, $id);
    }
    public function importLineasProducto(Request $request)
    {

        $file = $request->file('file');
        $id = $request->id_empresa;
        Excel::import(new LineaProductoImport , $file, $id);

        
    }
    public function importTipoProducto(Request $request)
    {

        $file = $request->file('file');
        $id = $request->id_empresa;
        Excel::import(new TipoProductosImport , $file, $id);

        
    }

    public function importMarcaProducto(Request $request)
    {

        $file = $request->file('file');
        $id = $request->id_empresa;
        Excel::import(new MarcaProductosImport , $file, $id);

        
    }

    public function importModelosProducto(Request $request)
    {

        $file = $request->file('file');
        $id = $request->id_empresa;
        Excel::import(new ModelosProductosImport , $file, $id);

        
    }

    public function importPresentacionProducto(Request $request)
    {

        $file = $request->file('file');
        $id = $request->id_empresa;
        Excel::import(new PresentacionProductosImport , $file, $id);

        
    }

    public function importProyectos(Request $request)
    {

        $file = $request->file('file');
        $id = $request->id_empresa;
        Excel::import(new ProyectosImport , $file, $id);
    }
    public function importUsuarios(Request $request)
    {

        $file = $request->file('file');
        $id = $request->id_empresa;
        Excel::import(new UsuariosImport , $file, $id);
    }

    public function ImportEstablecimientos(Request $request)
    {

        $file = $request->file('file');
        $id = $request->id_empresa;
        Excel::import(new EstablecimientoImport , $file, $id);
    }

    public function ImportRegistroImportacion(Request $request)
    {

        $file = $request->file('file');
        $id = $request->id_empresa;
        Excel::import(new RegistroImportacionImport , $file, $id);
    }

    /***
     * Desde aqui comienza los export de excel
     * 
     * @var diana
     */

     public function export($id)
     {
        //  $export=new InvoicesExport;
        //  $export->query($id);
        return (new InvoicesExport)->empresa($id)->download('invoices4.xlsx');

     }
     public function exportFactura(Request $request)
     {
         dd($request);
        //  $export=new InvoicesExport;
        //  $export->query($id);
        return (new InvoicesExport)->empresa($request)->download('invoices4.xlsx');

     }
}
