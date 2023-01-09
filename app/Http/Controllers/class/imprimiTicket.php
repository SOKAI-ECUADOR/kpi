<?php

require 'lib/imprimir_ticket/autoload.php'; //Nota: si renombraste la carpeta a algo diferente de "ticket" cambia el nombre en esta línea
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;

class imprimiTicket{
    // class item {
    //     private $name;
    //     private $cant;
    //     private $price;
    //     private $subt;
    //     private $dollarSign;
    
    //     public function __construct($name = '',$cant='', $price = '',$subt='', $dollarSign = false) {
    //         $this->name = $name;
    //         $this->cant = $cant;
    //         $this->price = $price;
    //         $this->dollarSign = $dollarSign;
    //     }
    
    //     public function __toString() {
    //         $rightCols = 10;
    //         $leftCols = 22;
    //         if($this->dollarSign) {
    //             $leftCols = $leftCols / 2 - $rightCols / 2;
    //         }
    //         $left = str_pad($this->name, $leftCols) ;
    
    //         $sign = ($this->dollarSign ? '$ ' : '');
    //         $right = str_pad($sign . $this->price, $rightCols, ' ', STR_PAD_LEFT);
    //         return "$left$right\n";
    //     }
    // }
    public function factura_venta_ticket($factura,$detalle,$empresa,$user,$cliente,$pagos,$nombre,$fecha,$hora){
        /*
        Este ejemplo imprime un hola mundo en una impresora de tickets
        en Windows.
        La impresora debe estar instalada como genérica y debe estar
        compartida
        */

        /*
        Conectamos con la impresora
        */

        /*
        Aquí, en lugar de "POS-58" (que es el nombre de mi impresora)
        escribe el nombre de la tuya. Recuerda que debes compartirla
        desde el panel de control
        */
        //return $nombre.":nombre_impresora";
        $nombre_impresora = $nombre;

        $connector = new NetworkPrintConnector("192.168.100.15",9100);
        $printer = new Printer($connector);
        $printer->setJustification(Printer::JUSTIFY_CENTER);

        //$logo = EscposImage::load("logo.jpg", false);
        //$printer->bitImage($logo);

        /*
        Imprimimos un mensaje. Podemos usar
        el salto de línea o llamar muchas
        veces a $printer->text()
        */
        //$printer->text("VITAL");
        $printer->setTextSize(2, 2);
        if($empresa->id_empresa==60){
            $printer->text("VITAL PHARMA\n");
        }else{
            $printer->text($empresa->nombre_empresa."\n");
        }
        

        $printer->setTextSize(2, 1);
        //$printer->feed();
        $printer->text($user->nombres." ".$user->apellidos."\n");
        $printer->setTextSize(1, 1);
        $printer->text("RUC: ".$empresa->ruc_empresa."\n");
        $printer->setTextSize(1, 1);
        if($factura->ambiente==2){
            $printer->text("Ambiente: PRODUCCION\n");
        }else{
            $printer->text("Ambiente: PRUEBAS\n");
        }
        $printer->setTextSize(1, 1);
        $printer->text("Matriz: ".$empresa->direccion_empresa."\n");
        $printer->setTextSize(1, 1);
        $printer->text("Telefono: ".$empresa->telefono."\n");
        $printer->setTextSize(1, 1);
        $printer->setEmphasis(true);
        if($empresa->id_empresa==60){
            $printer->text("SUCURSAL: VITAL PHARMA\n");
        }else{
            $printer->text("SUCURSAL: ".$empresa->nombre_empresa."\n");
        }
        $printer->setTextSize(1, 1);
        $printer->setEmphasis(false);
        $printer->text($user->direccion."\n");
        $printer->text("Autorizacion SRI\n");
        $printer->text($factura->clave_acceso."\n");
        $printer->text("Clave de Acceso\n");
        $printer->text($factura->clave_acceso."\n");
        $printer->setTextSize(2, 2);
        $printer->text("FACTURA\n");
        $printer->setTextSize(1, 1);
        $printer->text("No. : ".$factura->nro_factura."\n");
        $printer->text(" \n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("CLIENTE: ".$cliente->nombre."\n");
        $printer->text("CI:/R.U.C.: ".$cliente->identificacion."\n");
        $printer->text("DIRECCION: ".$cliente->direccion."\n");
        $printer->text("TELEFONO: ".$cliente->telefono."\n");
        $printer->text("Vendedor: ".$factura->nombre_vendedor."\n");
        $printer->text("Hora    : ".$hora." - Fecha: ".$fecha."\n");
        $printer->text("Descripcion           Cant.        P.U         Subtotal\n");
        $printer->text("-------------------------------------------------------\n");
        $printer->text("-------------------------------------------------------\n");
        $total=0;

        foreach($detalle as $detalles){
            $total=$detalles->cantidad*$detalles->precio;
            $printer->text($detalles->nombre."   ".$detalles->cantidad."   ".$detalles->precio."   ".number_format($total,2,".","")."\n");  
        }
        $printer->text("-------------------------------------------------------\n");
        $printer->text("-------------------------------------------------------\n");
        $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $printer->text("Subtotal: $ ".number_format($factura->subtotal_sin_impuesto,2,".","")."\n");
        if($factura->subtotal_0>0){
            $printer->text("Subtotal Grava IVA 0%: $ ".number_format($factura->subtotal_0,2,".","")."\n");
        }
        if($factura->subtotal_no_obj_iva>0){
            $printer->text("Subtotal No Grava IVA: $ ".number_format($factura->subtotal_no_obj_iva,2,".","")."\n");
        }
        if($factura->subtotal_12>0){
            $printer->text("Subtotal Grava IVA 12%: $ ".number_format($factura->subtotal_12,2,".","")."\n");
        }
        if($factura->iva_12>0){
            $printer->text("IVA 12%: $ ".number_format($factura->iva_12,2,".","")."\n");
        }
        $printer->text("----------------\n");
        $printer->text("A pagar: $ ".number_format($factura->valor_total,2,".","")."\n");
        if($factura->descuento>0){
            $printer->text("Su Descuento: ".number_format($factura->descuento,2,".","")."\n");
        }
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        if(count($pagos)>0){
            foreach($pagos as $pago){
                $printer->text($pago->descripcion."                            ".$pago->descripcion."\n");
            }
        }
        
        
        //$printer->setJustification(Printer::JUSTIFY_LEFT);
        /*
        Hacemos que el papel salga. Es como
        dejar muchos saltos de línea sin escribir nada
        */
        $printer->feed(15);

        /*
        Cortamos el papel. Si nuestra impresora
        no tiene soporte para ello, no generará
        ningún error
        */
        $printer->cut();

        /*
        Por medio de la impresora mandamos un pulso.
        Esto es útil cuando la tenemos conectada
        por ejemplo a un cajón
        */
        $printer->pulse();

        /*
        Para imprimir realmente, tenemos que "cerrar"
        la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
        */
        $printer->close();
    }
}


