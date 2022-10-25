<?php

namespace App\Http\Controllers;

include 'class/sendEmail.php';
use sendEmail;
use App\Models\Empresa;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CorreoController extends Controller
{
    public function correofacturaventa(Request $request){
        //Los correos recuperan los valores de empresa y le crea como objeto
        $email = new sendEmail();
        $empresas = (object) [
            'email_empresa'=>$request->empresas["email_empresa"],
            'password'=>$request->empresas["password"],
            'servidor_correo'=>$request->empresas["servidor_correo"],
            'puerto_correo'=>$request->empresas["puerto_correo"],
            'seguridad_correo'=>$request->empresas["seguridad_correo"],
        ];
        //Se requiere valores generales de la empresa y de la factura que se envia
        $email->enviarCorreo('Factura', $request->nombre, $request->claveAcceso, $request->email, $request->id_empresa, $empresas, $request->fecha_autorizacion, $request->valor_total, $request->logo,$request->nombre_empresa);
    }
    public function correonotaventa(Request $request){
        //Los correos recuperan los valores de empresa y le crea como objeto
        // $email = new sendEmail();
        // $empresas = (object) [
        //     'email_empresa'=>$request->empresas["email_empresa"],
        //     'password'=>$request->empresas["password"],
        //     'servidor_correo'=>$request->empresas["servidor_correo"],
        //     'puerto_correo'=>$request->empresas["puerto_correo"],
        //     'seguridad_correo'=>$request->empresas["seguridad_correo"],
        // ];
        // //Se requiere valores generales de la empresa y de la factura que se envia
        // $email->enviarCorreo('Factura', $request->nombre, $request->claveAcceso, $request->email, $request->id_empresa, $empresas, $request->fecha_autorizacion, $request->valor_total, $request->logo,$request->nombre_empresa);
        $email = new sendEmail();
        $nota_venta=DB::select("SELECT * from nota_venta where id_nota_venta=$request->id_nota_venta");
        $empresa=DB::select("SELECT * from empresa where id_empresa=$request->id_empresa");
        $cliente=DB::select("SELECT * from cliente where id_cliente={$nota_venta[0]->id_cliente}");
        if($request->email==null && $request->destinatario==null){
            $email->enviarNotaVenta($empresa[0],$nota_venta[0]->clave_acceso,$cliente[0]->nombre,$cliente[0]->email,1);
        }else{
            //dd($request->email);
            $email->enviarNotaVenta($empresa[0],$nota_venta[0]->clave_acceso,$request->destinatario,$request->email,2);
        }
        
    }
    public function correofacturaventa_masivo(Request $request){
        //Los correos recuperan los valores de empresa y le crea como objeto
        
        $email = new sendEmail();
        $empresas = (object) [
            'email_empresa'=>$request->empresas["email_empresa"],
            'password'=>$request->empresas["password"],
            'servidor_correo'=>$request->empresas["servidor_correo"],
            'puerto_correo'=>$request->empresas["puerto_correo"],
            'seguridad_correo'=>$request->empresas["seguridad_correo"],
        ];
        //Se requiere valores generales de la empresa y de la factura que se envia la diferencia del envio normal es el orden de los registros y el email contiene un array en vez de un string
        $email->enviarCorreo_masivo('Factura', $request->claveAcceso, $request->email, $request->id_empresa, $empresas, $request->fecha_autorizacion, $request->valor_total, $request->logo,$request->nombre_empresa);
    }
    public function correonotacredito(Request $request){
        //Los correos recuperan los valores de empresa y le crea como objeto
        $email = new sendEmail();
        $empresas = (object) [
            'email_empresa'=>$request->empresas["email_empresa"],
            'password'=>$request->empresas["password"],
            'servidor_correo'=>$request->empresas["servidor_correo"],
            'puerto_correo'=>$request->empresas["puerto_correo"],
            'seguridad_correo'=>$request->empresas["seguridad_correo"],
        ];
        //Se requiere valores generales de la empresa y de la factura que se envia
        $email->enviarCorreo('Notacredito', $request->nombre, $request->claveAcceso, $request->email, $request->id_empresa, $empresas, $request->fecha_autorizacion, $request->valor_total, $request->logo,$request->nombre_empresa);
    }
    public function correonotadebito(Request $request){
        //Los correos recuperan los valores de empresa y le crea como objeto
        $email = new sendEmail();
        $empresas = (object) [
            'email_empresa'=>$request->empresas["email_empresa"],
            'password'=>$request->empresas["password"],
            'servidor_correo'=>$request->empresas["servidor_correo"],
            'puerto_correo'=>$request->empresas["puerto_correo"],
            'seguridad_correo'=>$request->empresas["seguridad_correo"],
        ];
        //Se requiere valores generales de la empresa y de la factura que se envia
        $email->enviarCorreo('Notadebito', $request->nombre, $request->claveAcceso, $request->email, $request->id_empresa, $empresas, $request->fecha_autorizacion, $request->valor_total, $request->logo,$request->nombre_empresa);
    }
    public function correoliquidacioncompra(Request $request){
        //Los correos recuperan los valores de empresa y le crea como objeto
        $email = new sendEmail();
        $empresas = (object) [
            'email_empresa'=>$request->empresas["email_empresa"],
            'password'=>$request->empresas["password"],
            'servidor_correo'=>$request->empresas["servidor_correo"],
            'puerto_correo'=>$request->empresas["puerto_correo"],
            'seguridad_correo'=>$request->empresas["seguridad_correo"],
        ];
        //Se requiere valores generales de la empresa y de la factura que se envia
        $email->enviarCorreo_masivo('LiquidacionCompra', $request->claveAcceso, $request->email, $request->id_empresa, $empresas, $request->fecha_autorizacion, $request->valor_total, $request->logo,$request->nombre_empresa);
    }
    public function correoretencioncompra(Request $request){
        //Los correos recuperan los valores de empresa y le crea como objeto
        $email = new sendEmail();
        $empresas = (object) [
            'email_empresa'=>$request->empresas["email_empresa"],
            'password'=>$request->empresas["password"],
            'servidor_correo'=>$request->empresas["servidor_correo"],
            'puerto_correo'=>$request->empresas["puerto_correo"],
            'seguridad_correo'=>$request->empresas["seguridad_correo"],
        ];
        //dd($empresas);
        //Se requiere valores generales de la empresa y de la factura que se envia
        if($request->tipo=='Factura'){
            $email->enviarCorreo('retencion_compra', $request->nombre, $request->claveAcceso, $request->email, $request->id_empresa, $empresas, $request->fecha_autorizacion, $request->valor_total, $request->logo ,$request->nombre_empresa);
        }else{
            if($request->tipo=='Prov'){
                $proveedor=DB::select("SELECT proveedor.* from proveedor,factura_compra where factura_compra.id_proveedor=proveedor.id_proveedor and factura_compra.id_factcompra=$request->nombre");
                $email->enviarCorreo('retencion_compra', $proveedor[0]->nombre_proveedor, $request->claveAcceso, $proveedor[0]->email, $request->id_empresa, $empresas, $request->fecha_autorizacion, $request->valor_total, $request->logo ,$request->nombre_empresa);
            }else{
                if($request->tipo=='Dest_lic'){
                    $email->enviarCorreo('retencion_liquidacion_compra', $request->nombre, $request->claveAcceso, $request->email, $request->id_empresa, $empresas, $request->fecha_autorizacion, $request->valor_total, $request->logo ,$request->nombre_empresa);
                }else{
                    $proveedor=DB::select("SELECT proveedor.* from proveedor,liquidacion_compra where liquidacion_compra.id_proveedor=proveedor.id_proveedor and liquidacion_compra.id_liquidacion_compra=$request->nombre");
                    $email->enviarCorreo('retencion_liquidacion_compra', $proveedor[0]->nombre_proveedor, $request->claveAcceso, $proveedor[0]->email, $request->id_empresa, $empresas, $request->fecha_autorizacion, $request->valor_total, $request->logo ,$request->nombre_empresa);
                }
                
            }
            
        }
        
    }
    public function correoretencioncompraSolo(Request $request){
        //Los correos recuperan los valores de empresa y le crea como objeto
        $email = new sendEmail();
        $valor_retencion=DB::select("SELECT sum(cantidadiva) as cantidadiva,sum(cantidadrenta) as cantidadrenta,sum(cantidadiva)+sum(cantidadrenta) as suma_retencion from retencion_factura_comp where id_factura=".$request->id_factura);
        $empresas = (object) [
            'email_empresa'=>$request->empresas["email_empresa"],
            'password'=>$request->empresas["password"],
            'servidor_correo'=>$request->empresas["servidor_correo"],
            'puerto_correo'=>$request->empresas["puerto_correo"],
            'seguridad_correo'=>$request->empresas["seguridad_correo"],
        ];
        //Se requiere valores generales de la empresa y de la factura que se envia
        //dd($empresas);
        $email->enviarCorreoRetencion('retencion_compra', $request->nombre, $request->claveAcceso, $request->email, $request->id_empresa, $empresas, $request->fecha_autorizacion, $valor_retencion[0]->suma_retencion, $request->logo ,$request->nombre_empresa);
    }
    public function correofacturacompra(Request $request){
        //Los correos recuperan los valores de empresa y le crea como objeto
        $email = new sendEmail();
        $empresas = (object) [
            'email_empresa'=>$request->empresas["email_empresa"],
            'password'=>$request->empresas["password"],
            'servidor_correo'=>$request->empresas["servidor_correo"],
            'puerto_correo'=>$request->empresas["puerto_correo"],
            'seguridad_correo'=>$request->empresas["seguridad_correo"],
        ];
        //Se requiere valores generales de la empresa y de la factura que se envia
        $email->enviarCorreo_masivo('retencion_compra', $request->claveAcceso, $request->email, $request->id_empresa, $empresas, $request->fecha_autorizacion, $request->valor_total, $request->logo,$request->nombre_empresa);
    }
    public function correonotacreditocompra(Request $request){
        //Los correos recuperan los valores de empresa y le crea como objeto
        $email = new sendEmail();
        $empresas = (object) [
            'email_empresa'=>$request->empresas["email_empresa"],
            'password'=>$request->empresas["password"],
            'servidor_correo'=>$request->empresas["servidor_correo"],
            'puerto_correo'=>$request->empresas["puerto_correo"],
            'seguridad_correo'=>$request->empresas["seguridad_correo"],
        ];
        //Se requiere valores generales de la empresa y de la factura que se envia
        $email->enviarCorreocompra('Notacreditocompra', $request->nombre, $request->claveAcceso, $request->autorizacionfactura, $request->email, $request->id_empresa, $empresas, $request->fecha_autorizacion, $request->valor_total, $request->logo,$request->nombre_empresa);
    }
    public function correonotadebitocompra(Request $request){
        //Los correos recuperan los valores de empresa y le crea como objeto
        $email = new sendEmail();
        $empresas = (object) [
            'email_empresa'=>$request->empresas["email_empresa"],
            'password'=>$request->empresas["password"],
            'servidor_correo'=>$request->empresas["servidor_correo"],
            'puerto_correo'=>$request->empresas["puerto_correo"],
            'seguridad_correo'=>$request->empresas["seguridad_correo"],
        ];
        //Se requiere valores generales de la empresa y de la factura que se envia
        $email->enviarCorreocompra('Notadebitocompra', $request->nombre, $request->claveAcceso, $request->autorizacionfactura, $request->email, $request->id_empresa, $empresas, $request->fecha_autorizacion, $request->valor_total, $request->logo,$request->nombre_empresa);
    }
    public function correoguia(Request $request){
        //Los correos recuperan los valores de empresa y le crea como objeto
        $email = new sendEmail();
        $empresas = (object) [
            'email_empresa'=>$request->empresas["email_empresa"],
            'password'=>$request->empresas["password"],
            'servidor_correo'=>$request->empresas["servidor_correo"],
            'puerto_correo'=>$request->empresas["puerto_correo"],
            'seguridad_correo'=>$request->empresas["seguridad_correo"],
        ];
        //Se requiere valores generales de la empresa y de la factura que se envia
        $email->enviarCorreo('guia_remision_venta', $request->nombre, $request->claveAcceso, $request->email, $request->id_empresa, $empresas, $request->fecha_autorizacion, $request->valor_total, $request->logo ,$request->nombre_empresa);
    }
    public function correoguia_masivo(Request $request){
        //Los correos recuperan los valores de empresa y le crea como objeto
        $email = new sendEmail();
        $empresas = (object) [
            'email_empresa'=>$request->empresas["email_empresa"],
            'password'=>$request->empresas["password"],
            'servidor_correo'=>$request->empresas["servidor_correo"],
            'puerto_correo'=>$request->empresas["puerto_correo"],
            'seguridad_correo'=>$request->empresas["seguridad_correo"],
        ];
        //Se requiere valores generales de la empresa y de la factura que se envia la diferencia del envio normal es el orden de los registros y el email contiene un array en vez de un string
        if($request->tipo=='guia_nota_venta'){
            $email->enviarCorreo_masivo('guia_nota_venta', $request->claveAcceso, $request->email, $request->id_empresa, $empresas, $request->fecha_autorizacion, $request->valor_total, $request->logo ,$request->nombre_empresa);
        }else{
            $email->enviarCorreo_masivo('guia_remision_venta', $request->claveAcceso, $request->email, $request->id_empresa, $empresas, $request->fecha_autorizacion, $request->valor_total, $request->logo ,$request->nombre_empresa);
        }
        
    }
    public function correoliquidacion_compra_masivo(Request $request){
        //Los correos recuperan los valores de empresa y le crea como objeto
        $email = new sendEmail();
        $empresas = (object) [
            'email_empresa'=>$request->empresas["email_empresa"],
            'password'=>$request->empresas["password"],
            'servidor_correo'=>$request->empresas["servidor_correo"],
            'puerto_correo'=>$request->empresas["puerto_correo"],
            'seguridad_correo'=>$request->empresas["seguridad_correo"],
        ];
        //Se requiere valores generales de la empresa y de la factura que se envia la diferencia del envio normal es el orden de los registros y el email contiene un array en vez de un string
        
        $email->enviarCorreo_masivo('LiquidacionCompra', $request->claveAcceso, $request->email, $request->id_empresa, $empresas, $request->fecha_autorizacion, $request->valor_total, $request->logo ,$request->nombre_empresa);
        
        
    }
    public function pruebacorreo($id){
        //recupera el id de empresa
        $res = Empresa::select("*")->where("id_empresa", "=", $id)->get();
        $empresa = $res[0];
        //selecciona la empresa
        $email = new sendEmail();
        $respuesta = $email->enviarCorreopruebas($empresa);
        //hace una prueba de envio de correo
        return $respuesta;
    }
    public function pruebacorreodata(Request $request){
        //hace una prueba de envio de correo mediante un objeto de valores
        $email = new sendEmail();
        $respuesta = $email->enviarCorreopruebasdata($request);
        return $respuesta;
    }
}
