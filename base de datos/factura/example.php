<?php

include ('ejecutar.php');

$firmaFactura='';
$validarContraseña='';
$validarVigencia='';

if(!empty($_POST['respuestaFirmarFactura'])){
	
	
$firmaFactura = $_POST['respuestaFirmarFactura'];

/*
echo "firmaFactura: ".$firmaFactura[0]."";
echo "<br>";

echo "firmaFactura: ".$firmaFactura[1]."";
echo "<br>";
*/

}


if(!empty($_POST['respuestaValidarContraseña'])){
$validarContraseña = $_POST['respuestaValidarContraseña'];
/*
echo "validarContraseña: ".$validarContraseña[0]."";
echo "<br>";
*/
}


if(!empty($_POST['respuestaValidarVigencia'])){

$validarVigencia = $_POST['respuestaValidarVigencia'];

/*
echo "validarVigencia: ".$validarVigencia[0]."";
echo "<br>";
*/

}







if(!empty($validarContraseña)){
      //Put Code
    
    $file = fopen("recibe.txt", "a+");
    fwrite($file, $validarContraseña .PHP_EOL);
    var_dump($validarContraseña);
    
      
}
if(!empty($validarVigencia)){

  //Put Code
    
    $file = fopen("recibe.txt", "w+");
    fwrite($file,$validarVigencia[0] .PHP_EOL);
    fwrite($file, $validarVigencia[1] .PHP_EOL);
    fwrite($file, $validarVigencia[2].PHP_EOL);
    var_dump($validarVigencia);
  
  //Put Code  
}

if (!empty($firmaFactura)) {
    
    $validarComprobante = $firmaFactura[0];
    $autorizacionComprobante = $firmaFactura[1];
    
    var_dump($validarComprobante);
    var_dump($autorizacionComprobante);
    
    //Put Code
    
}


$comp = '2411202001131224783400110010010000001151234567813';


/*$ruta_factura= 'http://localhost/libreria/factura.xml';
$ruta_certificado= 'http://localhost/libreria/angel_martin_pisculla_coque.p12';
$contraseña= 'Alejandro2014';
$ruta_respuesta= 'http://localhost/libreria/example.php';*/

// Parametros de entrada

// Firma consejo de la judicatura
/*$ruta_factura= 'http://localhost/libreria/2102202101179143520600110010010000090200000002511.xml';
$ruta_certificado= 'http://localhost/libreria/P0000053411.p12';
$contraseña= '35rxE3CdYz';
$ruta_respuesta= 'http://localhost/libreria/example.php';*/


$ruta_factura= 'http://localhost/libreria/2103202101120700828300110010010000090280000002714.xml';
$ruta_certificado= 'http://localhost/libreria/GABRIEL ISRAEL VEGA ROMERO .p12';
$contraseña= 'Gabriel_2021';
$ruta_respuesta= 'http://localhost/libreria/example.php';


$ejecutar = new ejecutar();
$domain_dir = $_SERVER['SERVER_NAME'];

//Validar Contraseña del certificado
    //$ejecutar->validarContraseña($ruta_certificado,$contraseña,$ruta_respuesta);


//Validar Vigencia del certificado
    //$ejecutar->validarVigencia($ruta_certificado,$contraseña,$ruta_respuesta);


//Firmar Factura y enviar a SRI
    $ejecutar->firmarFactura($ruta_factura,$ruta_certificado,$contraseña,$ruta_respuesta);





