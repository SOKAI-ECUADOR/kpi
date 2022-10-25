<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Proveedor;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;

class ProveedorImport implements WithMultipleSheets, ToCollection, WithHeadingRow

{

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

     
    
    public function collection(Collection $rows)
    {
         
        foreach ($rows as $row) {

            $idempre = $row['id_empresa'];
             
            $proveedor = DB::select("SELECT * FROM proveedor WHERE id_empresa = '" . $idempre . "' ORDER BY id_proveedor DESC limit 1");
            if($proveedor){
                $dato = $proveedor[0]->cod_proveedor;
                $var=0;
                for($i=strlen($dato); $i>0; $i--){
                    if($dato[$i-1] =='-'){
                        $var = $i;
                        //break;
                    }
                }
                $codigo_prov=0;
                $numero = substr($dato,$var)+1;
                $cod = substr($dato,0,$var);
                if($numero<=9){
                    $codigo_prov=$cod."00".$numero;
                    }elseif($numero >= 10){
                       $codigo_prov=$cod."0".$numero;
                    }else {
                        $codigo_prov=$cod.$numero;
                    }
                    //return $codigo_prov;
                    $totalcodi = $codigo_prov;
                    }else{
                    return "vacio";
                }
            $codi = $row['identificacion_representante'];
            $ver = DB::select("SELECT * FROM proveedor WHERE identif_proveedor = '" . $codi . "' AND id_empresa ='" . $idempre . "' ");
            $cedu = strtoupper($row['tipo_identificacion']);
            $cod = $row['identificacion_representante'];
            
            if($ver){
                return "existe";
            }
           
            if(count($ver) == 0 && $row['nombre'] != null && $row['tipo_identificacion'] != null && $row['identificacion_representante'] != null && 
            $row['contacto'] != null && $row['direccion'] != null && $row['id_provincia'] != null && $row['id_ciudad'] != null &&
            ($cedu == 'C' || $cedu == 'CED' || $cedu == 'CEDU' || $cedu == 'CEDULA') && ($this->validarCedula($row['identificacion_representante'])))
            {
                Proveedor::create([
                    'cod_proveedor'=>$totalcodi,
                    'id_grupo_proveedor'=> $row['id_grupo_proveedor'],
                    'nombre_proveedor'=> $row['nombre'],
                    'tipo_identificacion'=> "Cedula",
                    'identif_proveedor'=> $row['identificacion_representante'],
                    'contribuyente'=> $row['contribuyente_especial'],
                    'beneficiario'=> $row['beneficiario'],
                    'contacto'=> $row['contacto'],
                    'direccion_prov'=> $row['direccion'],
                    'nrcasa'=> $row['num_casa'],
                    'id_provincia'=> $row['id_provincia'],
                    'id_ciudad'=> $row['id_ciudad'],
                    'telefono_prov'=> $row['telefono'],
                    'estado_prov'=> $row['estado'],
                    'id_banco'=> $row['id_banco'],
                    'tipo_cuenta'=> $row['tipo_cuenta'],
                    'cta_banco'=> $row['cuenta_banco'],
                    'pagos'=> $row['pagos'],
                    'plazo'=> $row['plazo'],
                    'dias_pago'=> $row['dias_pago'],
                    'id_plan_cuentas'=> $row['id_plan_cuentas'],
                    'comentario'=> $row['comentario'],
                    'tip_comprob'=> $row['tipo_comprobante'],
                    'serie'=> $row['serie'],
                    'fvalidez'=> $row['fecha_validez'],
                    'rangomin'=> $row['fecha_inicial'],
                    'rangomax'=> $row['fecha_final'],
                    'nrautorizacion'=> $row['num_autorizacion'],
                    'contribuye_sri'=> $row['contribuye_sri'],
                    'tip_electronico'=> $row['tipo_facturacion'],
                    'imp_retencion'=> $row['impuesto_retencion'],
                    'codsri_imp'=> $row['codigo_sri_impuesto'],
                    'retencion_iva'=> $row['retencion_iva'],
                    'codsri_iva'=> $row['codigo_sri_iva'],
                    'cash_manager'=> $row['cash_manager'],
                    'id_empresa'=> $row['id_empresa'],
                        ]);
            }
          
            if (count($ver) == 0 && $row['nombre'] != null && $row['tipo_identificacion'] != null && $row['identificacion_representante'] != null && 
                $row['contacto'] != null && $row['direccion'] != null && $row['id_provincia'] != null && $row['id_ciudad'] != null && 
                ($cedu == 'R' || $cedu == 'RUC') && ($cod[2] >= 0 && $cod[2] < 6) && ($this->validarRucPersonaNatural($row['identificacion_representante'])) )
            {              
                Proveedor::create([
                    'cod_proveedor'=>$totalcodi,
                    'id_grupo_proveedor'=> $row['id_grupo_proveedor'],
                    'nombre_proveedor'=> $row['nombre'],
                    'tipo_identificacion'=> "Ruc",
                    'identif_proveedor'=> $row['identificacion_representante'],
                    'contribuyente'=> $row['contribuyente_especial'],
                    'beneficiario'=> $row['beneficiario'],
                    'contacto'=> $row['contacto'],
                    'direccion_prov'=> $row['direccion'],
                    'nrcasa'=> $row['num_casa'],
                    'id_provincia'=> $row['id_provincia'],
                    'id_ciudad'=> $row['id_ciudad'],
                    'telefono_prov'=> $row['telefono'],
                    'estado_prov'=> $row['estado'],
                    'id_banco'=> $row['id_banco'],
                    'tipo_cuenta'=> $row['tipo_cuenta'],
                    'cta_banco'=> $row['cuenta_banco'],
                    'pagos'=> $row['pagos'],
                    'plazo'=> $row['plazo'],
                    'dias_pago'=> $row['dias_pago'],
                    'id_plan_cuentas'=> $row['id_plan_cuentas'],
                    'comentario'=> $row['comentario'],
                    'tip_comprob'=> $row['tipo_comprobante'],
                    'serie'=> $row['serie'],
                    'fvalidez'=> $row['fecha_validez'],
                    'rangomin'=> $row['fecha_inicial'],
                    'rangomax'=> $row['fecha_final'],
                    'nrautorizacion'=> $row['num_autorizacion'],
                    'contribuye_sri'=> $row['contribuye_sri'],
                    'tip_electronico'=> $row['tipo_facturacion'],
                    'imp_retencion'=> $row['impuesto_retencion'],
                    'codsri_imp'=> $row['codigo_sri_impuesto'],
                    'retencion_iva'=> $row['retencion_iva'],
                    'codsri_iva'=> $row['codigo_sri_iva'],
                    'cash_manager'=> $row['cash_manager'],
                    'id_empresa'=> $row['id_empresa'],
                ]);
                  
            } 

            if (count($ver) == 0 && $row['nombre'] != null && $row['tipo_identificacion'] != null && $row['identificacion_representante'] != null && 
                $row['contacto'] != null && $row['direccion'] != null && $row['id_provincia'] != null && $row['id_ciudad'] != null && 
                ($cedu == 'R' || $cedu == 'RUC') && ($cod[2] == 9) && ($this->validarRucSociedadPrivada($row['identificacion_representante'])))
            {
                Proveedor::create([
                        'cod_proveedor'=>$totalcodi,
                        'id_grupo_proveedor'=> $row['id_grupo_proveedor'],
                        'nombre_proveedor'=> $row['nombre'],
                        'tipo_identificacion'=> "Ruc",
                        'identif_proveedor'=> $row['identificacion_representante'],
                        'contribuyente'=> $row['contribuyente_especial'],
                        'beneficiario'=> $row['beneficiario'],
                        'contacto'=> $row['contacto'],
                        'direccion_prov'=> $row['direccion'],
                        'nrcasa'=> $row['num_casa'],
                        'id_provincia'=> $row['id_provincia'],
                        'id_ciudad'=> $row['id_ciudad'],
                        'telefono_prov'=> $row['telefono'],
                        'estado_prov'=> $row['estado'],
                        'id_banco'=> $row['id_banco'],
                        'tipo_cuenta'=> $row['tipo_cuenta'],
                        'cta_banco'=> $row['cuenta_banco'],
                        'pagos'=> $row['pagos'],
                        'plazo'=> $row['plazo'],
                        'dias_pago'=> $row['dias_pago'],
                        'id_plan_cuentas'=> $row['id_plan_cuentas'],
                        'comentario'=> $row['comentario'],
                        'tip_comprob'=> $row['tipo_comprobante'],
                        'serie'=> $row['serie'],
                        'fvalidez'=> $row['fecha_validez'],
                        'rangomin'=> $row['fecha_inicial'],
                        'rangomax'=> $row['fecha_final'],
                        'nrautorizacion'=> $row['num_autorizacion'],
                        'contribuye_sri'=> $row['contribuye_sri'],
                        'tip_electronico'=> $row['tipo_facturacion'],
                        'imp_retencion'=> $row['impuesto_retencion'],
                        'codsri_imp'=> $row['codigo_sri_impuesto'],
                        'retencion_iva'=> $row['retencion_iva'],
                        'codsri_iva'=> $row['codigo_sri_iva'],
                        'cash_manager'=> $row['cash_manager'],
                        'id_empresa'=> $row['id_empresa'],
                ]);
            }

            if (count($ver) == 0 && $row['nombre'] != null && $row['tipo_identificacion'] != null && $row['identificacion_representante'] != null && 
                $row['contacto'] != null && $row['direccion'] != null && $row['id_provincia'] != null && $row['id_ciudad'] != null && 
                ($cedu == 'R' || $cedu == 'RUC') && ($cod[2] == 6) && ($this->validarRucSociedadPublica($row['identificacion_representante'])))
            {
                
                Proveedor::create([
                        'cod_proveedor'=>$totalcodi,
                        'id_grupo_proveedor'=> $row['id_grupo_proveedor'],
                        'nombre_proveedor'=> $row['nombre'],
                        'tipo_identificacion'=> "Ruc",
                        'identif_proveedor'=> $row['identificacion_representante'],
                        'contribuyente'=> $row['contribuyente_especial'],
                        'beneficiario'=> $row['beneficiario'],
                        'contacto'=> $row['contacto'],
                        'direccion_prov'=> $row['direccion'],
                        'nrcasa'=> $row['num_casa'],
                        'id_provincia'=> $row['id_provincia'],
                        'id_ciudad'=> $row['id_ciudad'],
                        'telefono_prov'=> $row['telefono'],
                        'estado_prov'=> $row['estado'],
                        'id_banco'=> $row['id_banco'],
                        'tipo_cuenta'=> $row['tipo_cuenta'],
                        'cta_banco'=> $row['cuenta_banco'],
                        'pagos'=> $row['pagos'],
                        'plazo'=> $row['plazo'],
                        'dias_pago'=> $row['dias_pago'],
                        'id_plan_cuentas'=> $row['id_plan_cuentas'],
                        'comentario'=> $row['comentario'],
                        'tip_comprob'=> $row['tipo_comprobante'],
                        'serie'=> $row['serie'],
                        'fvalidez'=> $row['fecha_validez'],
                        'rangomin'=> $row['fecha_inicial'],
                        'rangomax'=> $row['fecha_final'],
                        'nrautorizacion'=> $row['num_autorizacion'],
                        'contribuye_sri'=> $row['contribuye_sri'],
                        'tip_electronico'=> $row['tipo_facturacion'],
                        'imp_retencion'=> $row['impuesto_retencion'],
                        'codsri_imp'=> $row['codigo_sri_impuesto'],
                        'retencion_iva'=> $row['retencion_iva'],
                        'codsri_iva'=> $row['codigo_sri_iva'],
                        'cash_manager'=> $row['cash_manager'],
                        'id_empresa'=> $row['id_empresa'],
                ]);
            }
             
        }
    }

    /* puebas
      public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $idempre = $row['id_empresa'];
            $cod = $row['identificacion_representante'];
            $ver = DB::select("SELECT * FROM proveedor WHERE identif_proveedor = '" . $cod . "' AND id_empresa ='" . $idempre . "' ");
            if (count($ver) == 0 && $row['nombre'] != null && $row['tipo_identificacion'] != null && $row['identificacion_representante'] != null && 
            $row['contacto'] != null && $row['direccion'] != null && $row['id_provincia'] != null && $row['id_ciudad'] != null
            ){
                $cedu = strtoupper($row['tipo_identificacion']);
                //$num = $row['identificacion_representante'];
                //OTRA PRUEBA
                
                if(($cedu == 'C' || $cedu == 'CED' || $cedu == 'CEDU' || $cedu == 'CEDULA') && ($this->validarCedula($row['identificacion_representante']))){
                    Proveedor::create([
                            'cod_proveedor'=> "PRO-",
                            'id_grupo_proveedor'=> $row['id_grupo_proveedor'],
                            'nombre_proveedor'=> $row['nombre'],
                            'tipo_identificacion'=> "Cedula",
                            'identif_proveedor'=> $row['identificacion_representante'],
                            'contribuyente'=> $row['contribuyente_especial'],
                            'beneficiario'=> $row['beneficiario'],
                            'contacto'=> $row['contacto'],
                            'direccion_prov'=> $row['direccion'],
                            'nrcasa'=> $row['num_casa'],
                            'id_provincia'=> $row['id_provincia'],
                            'id_ciudad'=> $row['id_ciudad'],
                            'telefono_prov'=> $row['telefono'],
                            'estado_prov'=> $row['estado'],
                            'id_banco'=> $row['id_banco'],
                            'tipo_cuenta'=> $row['tipo_cuenta'],
                            'cta_banco'=> $row['cuenta_banco'],
                            'pagos'=> $row['pagos'],
                            'plazo'=> $row['plazo'],
                            'dias_pago'=> $row['dias_pago'],
                            'id_plan_cuentas'=> $row['id_plan_cuentas'],
                            'comentario'=> $row['comentario'],
                            'tip_comprob'=> $row['tipo_comprobante'],
                            'serie'=> $row['serie'],
                            'fvalidez'=> $row['fecha_validez'],
                            'rangomin'=> $row['fecha_inicial'],
                            'rangomax'=> $row['fecha_final'],
                            'nrautorizacion'=> $row['num_autorizacion'],
                            'contribuye_sri'=> $row['contribuye_sri'],
                            'tip_electronico'=> $row['tipo_facturacion'],
                            'imp_retencion'=> $row['impuesto_retencion'],
                            'codsri_imp'=> $row['codigo_sri_impuesto'],
                            'retencion_iva'=> $row['retencion_iva'],
                            'codsri_iva'=> $row['codigo_sri_iva'],
                            'cash_manager'=> $row['cash_manager'],
                            'id_empresa'=> $row['id_empresa'],
                        ]);
                }
                    //(($cedu == 'R' || $cedu == 'RUC') && ($this->validarRucPersonaNatural($row['identificacion_representante']))) || 
                    //(($cedu == 'R' || $cedu == 'RUC') && ($this->validarRucSociedadPrivada($row['identificacion_representante'])))
                if (($cedu == 'R' || $cedu == 'RUC') && ($this->validarRucPersonaNatural($row['identificacion_representante'])))
                {
                    Proveedor::create([
                            'cod_proveedor'=>"PRO-",
                            'id_grupo_proveedor'=> $row['id_grupo_proveedor'],
                            'nombre_proveedor'=> $row['nombre'],
                            'tipo_identificacion'=> "Ruc",
                            'identif_proveedor'=> $row['identificacion_representante'],
                            'contribuyente'=> $row['contribuyente_especial'],
                            'beneficiario'=> $row['beneficiario'],
                            'contacto'=> $row['contacto'],
                            'direccion_prov'=> $row['direccion'],
                            'nrcasa'=> $row['num_casa'],
                            'id_provincia'=> $row['id_provincia'],
                            'id_ciudad'=> $row['id_ciudad'],
                            'telefono_prov'=> $row['telefono'],
                            'estado_prov'=> $row['estado'],
                            'id_banco'=> $row['id_banco'],
                            'tipo_cuenta'=> $row['tipo_cuenta'],
                            'cta_banco'=> $row['cuenta_banco'],
                            'pagos'=> $row['pagos'],
                            'plazo'=> $row['plazo'],
                            'dias_pago'=> $row['dias_pago'],
                            'id_plan_cuentas'=> $row['id_plan_cuentas'],
                            'comentario'=> $row['comentario'],
                            'tip_comprob'=> $row['tipo_comprobante'],
                            'serie'=> $row['serie'],
                            'fvalidez'=> $row['fecha_validez'],
                            'rangomin'=> $row['fecha_inicial'],
                            'rangomax'=> $row['fecha_final'],
                            'nrautorizacion'=> $row['num_autorizacion'],
                            'contribuye_sri'=> $row['contribuye_sri'],
                            'tip_electronico'=> $row['tipo_facturacion'],
                            'imp_retencion'=> $row['impuesto_retencion'],
                            'codsri_imp'=> $row['codigo_sri_impuesto'],
                            'retencion_iva'=> $row['retencion_iva'],
                            'codsri_iva'=> $row['codigo_sri_iva'],
                            'cash_manager'=> $row['cash_manager'],
                            'id_empresa'=> $row['id_empresa'],
                        ]);
                    } 
            }
            
            else{
                return ProveedorImport;
            }

            
        }
    }
     */

    public function headingRow(): int
    {
        return 1;
    }
    public function sheets(): array
    {
        return [

            'Proveedor' => new ProveedorImport(),
           
        ];
    }

  

    protected $error = '';
    //validar cedula ruc
    public function validarCedula($numero = '')
    {
        // fuerzo parametro de entrada a string
        $numero = (string) $numero;

        // borro por si acaso errores de llamadas anteriores.
        $this->setError('');

        // validaciones
        try {
            $this->validarInicial($numero, '10');
            $this->validarCodigoProvincia(substr($numero, 0, 2));
            $this->validarTercerDigito($numero[2], 'cedula');
            $this->algoritmoModulo10(substr($numero, 0, 9), $numero[9]);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }

        return true;
    }

    /**
     * Validar RUC persona natural
     *
     * @param  string  $numero  Número de RUC persona natural
     *
     * @return Boolean
     */

    public function validarRucPersonaNatural($numero = '')
    {
        // fuerzo parametro de entrada a string
        $numero = (string) $numero;

        // borro por si acaso errores de llamadas anteriores.
        $this->setError('');

        // validaciones
        try {
            $this->validarInicial($numero, '13');
            $this->validarCodigoProvincia(substr($numero, 0, 2));
            $this->validarTercerDigito($numero[2], 'ruc_natural');
            $this->validarCodigoEstablecimiento(substr($numero, 10, 3));
            $this->algoritmoModulo10(substr($numero, 0, 9), $numero[9]);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }

        return true;
    }


    /**
     * Validar RUC sociedad privada
     *
     * @param  string  $numero  Número de RUC sociedad privada
     *
     * @return Boolean
     */
    public function validarRucSociedadPrivada($numero = '')
    {
        // fuerzo parametro de entrada a string
        $numero = (string) $numero;

        // borro por si acaso errores de llamadas anteriores.
        $this->setError('');

        // validaciones
        try {
            $this->validarInicial($numero, '13');
            $this->validarCodigoProvincia(substr($numero, 0, 2));
            $this->validarTercerDigito($numero[2], 'ruc_privada');
            $this->validarCodigoEstablecimiento(substr($numero, 10, 3));
            $this->algoritmoModulo11(substr($numero, 0, 9), $numero[9], 'ruc_privada');
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }

        return true;
    }

    /**
     * Validar RUC sociedad publica
     *
     * @param  string  $numero  Número de RUC sociedad publica
     *
     * @return Boolean
     */
    public function validarRucSociedadPublica($numero = '')
    {
        // fuerzo parametro de entrada a string
        $numero = (string) $numero;

        // borro por si acaso errores de llamadas anteriores.
        $this->setError('');

        // validaciones
        try {
            $this->validarInicial($numero, '13');
            $this->validarCodigoProvincia(substr($numero, 0, 2));
            $this->validarTercerDigito($numero[2], 'ruc_publica');
            $this->validarCodigoEstablecimiento(substr($numero, 9, 4));
            $this->algoritmoModulo11(substr($numero, 0, 8), $numero[8], 'ruc_publica');
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }

        return true;
    }

    /**
     * Validaciones iniciales para CI y RUC
     *
     * @param  string  $numero      CI o RUC
     * @param  integer $caracteres  Cantidad de caracteres requeridos
     *
     * @return Boolean
     *
     * @throws exception Cuando valor esta vacio, cuando no es dígito y
     * cuando no tiene cantidad requerida de caracteres
     */
    protected function validarInicial($numero, $caracteres)
    {
        if (empty($numero)) {
            throw new Exception('Valor no puede estar vacio');
        }

        if (!ctype_digit($numero)) {
            throw new Exception('Valor ingresado solo puede tener dígitos');
        }

        if (strlen($numero) != $caracteres) {
            throw new Exception('Valor ingresado debe tener ' . $caracteres . ' caracteres');
        }

        return true;
    }

    /**
     * Validación de código de provincia (dos primeros dígitos de CI/RUC)
     *
     * @param  string  $numero  Dos primeros dígitos de CI/RUC
     *
     * @return boolean
     *
     * @throws exception Cuando el código de provincia no esta entre 00 y 24
     */
    protected function validarCodigoProvincia($numero)
    {
        if ($numero < 0 or $numero > 24) {
            throw new Exception('Codigo de Provincia (dos primeros dígitos) no deben ser mayor a 24 ni menores a 0');
        }

        return true;
    }

    /**
     * Validación de tercer dígito
     *
     * Permite validad el tercer dígito del documento. Dependiendo
     * del campo tipo (tipo de identificación) se realizan las validaciones.
     * Los posibles valores del campo tipo son: cedula, ruc_natural, ruc_privada
     *
     * Para Cédulas y RUC de personas naturales el terder dígito debe
     * estar entre 0 y 5 (0,1,2,3,4,5)
     *
     * Para RUC de sociedades privadas el terder dígito debe ser
     * igual a 9.
     *
     * Para RUC de sociedades públicas el terder dígito debe ser 
     * igual a 6.
     *
     * @param  string $numero  tercer dígito de CI/RUC
     * @param  string $tipo  tipo de identificador
     *
     * @return boolean
     *
     * @throws exception Cuando el tercer digito no es válido. El mensaje
     * de error depende del tipo de Idenficiación.
     */
    protected function validarTercerDigito($numero, $tipo)
    {
        switch ($tipo) {
            case 'cedula':
            case 'ruc_natural':
                if ($numero < 0 or $numero > 5) {
                    throw new Exception('Tercer dígito debe ser mayor o igual a 0 y menor a 6 para cédulas y RUC de persona natural');
                }
                break;
            case 'ruc_privada':
                if ($numero != 9) {
                    throw new Exception('Tercer dígito debe ser igual a 9 para sociedades privadas');
                }
                break;

            case 'ruc_publica':
                if ($numero != 6) {
                    throw new Exception('Tercer dígito debe ser igual a 6 para sociedades públicas');
                }
                break;
            default:
                throw new Exception('Tipo de Identificación no existe.');
                break;
        }

        return true;
    }

    protected function validarTercerDigitoPrueba1($numero, $tipo)
    {
        switch ($tipo) {
            case 'cedula':
            case 'ruc_natural':
                if ($numero[2] < 0 or $numero[2] > 5) {
                    throw new Exception('Tercer dígito debe ser mayor o igual a 0 y menor a 6 para cédulas y RUC de persona natural');
                }
                break;
            default:
                throw new Exception('Tipo de Identificación no existe.');
                break;
        }

        return true;
    }

    protected function validarTercerDigitoPrueba2($numero, $tipo)
    {
        switch ($tipo) {
            case 'cedula':
            case 'ruc_privada':
                if ($numero != 9) {
                    throw new Exception('Tercer dígito debe ser igual a 9 para sociedades privadas');
                }
                break;
            default:
                throw new Exception('Tipo de Identificación no existe.');
                break;
        }

        return true;
    }

    /**
     * Validación de código de establecimiento
     *
     * @param  string $numero  tercer dígito de CI/RUC
     *
     * @return boolean
     *
     * @throws exception Cuando el establecimiento es menor a 1
     */
    protected function validarCodigoEstablecimiento($numero)
    {
        if ($numero < 1) {
            throw new Exception('Código de establecimiento no puede ser 0');
        }

        return true;
    }

    /**
     * Algoritmo Modulo10 para validar si CI y RUC de persona natural son válidos.
     *
     * Los coeficientes usados para verificar el décimo dígito de la cédula,
     * mediante el algoritmo “Módulo 10” son:  2. 1. 2. 1. 2. 1. 2. 1. 2
     *
     * Paso 1: Multiplicar cada dígito de los digitosIniciales por su respectivo
     * coeficiente.
     *
     *  Ejemplo
     *  digitosIniciales posicion 1  x 2
     *  digitosIniciales posicion 2  x 1
     *  digitosIniciales posicion 3  x 2
     *  digitosIniciales posicion 4  x 1
     *  digitosIniciales posicion 5  x 2
     *  digitosIniciales posicion 6  x 1
     *  digitosIniciales posicion 7  x 2
     *  digitosIniciales posicion 8  x 1
     *  digitosIniciales posicion 9  x 2
     *
     * Paso 2: Sí alguno de los resultados de cada multiplicación es mayor a o igual a 10,
     * se suma entre ambos dígitos de dicho resultado. Ex. 12->1+2->3
     *
     * Paso 3: Se suman los resultados y se obtiene total
     *
     * Paso 4: Divido total para 10, se guarda residuo. Se resta 10 menos el residuo.
     * El valor obtenido debe concordar con el digitoVerificador
     *
     * Nota: Cuando el residuo es cero(0) el dígito verificador debe ser 0.
     *
     * @param  string $digitosIniciales   Nueve primeros dígitos de CI/RUC
     * @param  string $digitoVerificador  Décimo dígito de CI/RUC
     *
     * @return boolean
     *
     * @throws exception Cuando los digitosIniciales no concuerdan contra
     * el código verificador.
     */
    protected function algoritmoModulo10($digitosIniciales, $digitoVerificador)
    {
        $arrayCoeficientes = array(2, 1, 2, 1, 2, 1, 2, 1, 2);

        $digitoVerificador = (int) $digitoVerificador;
        $digitosIniciales = str_split($digitosIniciales);

        $total = 0;
        foreach ($digitosIniciales as $key => $value) {

            $valorPosicion = ((int) $value * $arrayCoeficientes[$key]);

            if ($valorPosicion >= 10) {
                $valorPosicion = str_split($valorPosicion);
                $valorPosicion = array_sum($valorPosicion);
                $valorPosicion = (int) $valorPosicion;
            }

            $total = $total + $valorPosicion;
        }

        $residuo =  $total % 10;

        if ($residuo == 0) {
            $resultado = 0;
        } else {
            $resultado = 10 - $residuo;
        }

        if ($resultado != $digitoVerificador) {
            throw new Exception('Dígitos iniciales no validan contra Dígito Idenficador');
        }

        return true;
    }

    /**
     * Algoritmo Modulo11 para validar RUC de sociedades privadas y públicas
     *
     * El código verificador es el decimo digito para RUC de empresas privadas
     * y el noveno dígito para RUC de empresas públicas
     *
     * Paso 1: Multiplicar cada dígito de los digitosIniciales por su respectivo
     * coeficiente.
     *
     * Para RUC privadas el coeficiente esta definido y se multiplica con las siguientes
     * posiciones del RUC:
     *
     *  Ejemplo
     *  digitosIniciales posicion 1  x 4
     *  digitosIniciales posicion 2  x 3
     *  digitosIniciales posicion 3  x 2
     *  digitosIniciales posicion 4  x 7
     *  digitosIniciales posicion 5  x 6
     *  digitosIniciales posicion 6  x 5
     *  digitosIniciales posicion 7  x 4
     *  digitosIniciales posicion 8  x 3
     *  digitosIniciales posicion 9  x 2
     *
     * Para RUC privadas el coeficiente esta definido y se multiplica con las siguientes
     * posiciones del RUC:
     *
     *  digitosIniciales posicion 1  x 3
     *  digitosIniciales posicion 2  x 2
     *  digitosIniciales posicion 3  x 7
     *  digitosIniciales posicion 4  x 6
     *  digitosIniciales posicion 5  x 5
     *  digitosIniciales posicion 6  x 4
     *  digitosIniciales posicion 7  x 3
     *  digitosIniciales posicion 8  x 2
     *
     * Paso 2: Se suman los resultados y se obtiene total
     *
     * Paso 3: Divido total para 11, se guarda residuo. Se resta 11 menos el residuo.
     * El valor obtenido debe concordar con el digitoVerificador
     *
     * Nota: Cuando el residuo es cero(0) el dígito verificador debe ser 0.
     *
     * @param  string $digitosIniciales   Nueve primeros dígitos de RUC
     * @param  string $digitoVerificador  Décimo dígito de RUC
     * @param  string $tipo Tipo de identificador
     *
     * @return boolean
     *
     * @throws exception Cuando los digitosIniciales no concuerdan contra
     * el código verificador.
     */
    protected function algoritmoModulo11($digitosIniciales, $digitoVerificador, $tipo)
    {
        switch ($tipo) {
            case 'ruc_privada':
                $arrayCoeficientes = array(4, 3, 2, 7, 6, 5, 4, 3, 2);
                break;
            case 'ruc_publica':
                $arrayCoeficientes = array(3, 2, 7, 6, 5, 4, 3, 2);
                break;
            default:
                throw new Exception('Tipo de Identificación no existe.');
                break;
        }

        $digitoVerificador = (int) $digitoVerificador;
        $digitosIniciales = str_split($digitosIniciales);

        $total = 0;
        foreach ($digitosIniciales as $key => $value) {
            $valorPosicion = ((int) $value * $arrayCoeficientes[$key]);
            $total = $total + $valorPosicion;
        }

        $residuo =  $total % 11;

        if ($residuo == 0) {
            $resultado = 0;
        } else {
            $resultado = 11 - $residuo;
        }

        if ($resultado != $digitoVerificador) {
            throw new Exception('Dígitos iniciales no validan contra Dígito Idenficador');
        }

        return true;
    }

    /**
     * Get error
     *
     * @return string Mensaje de error
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Set error
     *
     * @param  string $newError
     * @return object $this
     */
    public function setError($newError)
    {
        $this->error = $newError;
        return $this;
    }

}
