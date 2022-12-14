<?php

namespace App\Imports;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Models\Cliente;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ToCollection;
use SebastianBergmann\Environment\Console;


//use Maatwebsite\Excel\Concerns\WithValidation;
//class clienteImport implements ToModel,WithHeadingRow,WithMultipleSheets
class clienteImport implements WithMultipleSheets, ToCollection, WithHeadingRow

{
    // use WithConditionalSheets;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */



    public function collection(Collection $rows)
    {

        foreach ($rows as $row) { 
           $idempre = $row['id_empresa'];
           $codo = $row['identificacion'];
           $ver = DB::select("SELECT * FROM cliente WHERE identificacion = '" . $codo . "' AND id_empresa ='" . $idempre . "' ");
           //codigo automatico
           $cliente = DB::select("SELECT * FROM cliente WHERE id_empresa = '" . $idempre . "' ORDER BY id_cliente DESC limit 1");
            if($cliente){
                $dato = $cliente[0]->codigo;
                $var=0;
                for($i=strlen($dato); $i>0; $i--){
                    if($dato[$i-1] =='-'){
                    $var = $i;
                   // break; 
                    }
                }
                $numero = substr($dato,$var)+1;
                $cod = substr($dato,0,$var);
                $codauto= $cod.$numero;
            }else{
            return "vacio";
            }
            //excel import subir imformacion sea al caso
            $cedu = strtoupper($row['tipo_identificacion']);
            $tipoidenti = strtoupper($row['grupo_tributario']);
               
                //validacion cuando es cedula 
            if (count($ver) == 0 && !filter_var($row['email'], FILTER_VALIDATE_EMAIL) === false && $row['nombre'] != null
            && $row['tipo_identificacion'] != null && $row['identificacion'] != null && $row['grupo_tributario'] != null
            && $row['direccion'] != null && $row['id_provincia'] != null && $row['id_cuidad'] != null && $row['id_parroquia'] != null
            && $row['email'] != null && $row['telefono'] != null && $row['contacto'] != null && $row['estado'] != null
            && ($cedu == 'C' || $cedu == 'CED' || $cedu == 'CEDU' || $cedu == 'CEDULA' || $cedu == 'CEDULA DE IDENTIDAD') && ($tipoidenti == 1 || $tipoidenti == 'PERSONA NATURAL') && ($this->validarCedula($row['identificacion'])))
            {
                Cliente::create([
                    'codigo' =>$codauto,
                    'nombre' => $row['nombre'],
                    'nombre_adicional' => $row['nombre_adicional'],
                    'identificacion' => $row['identificacion'],
                    'direccion' => $row['direccion'],
                    'email' => $row['email'],
                    'telefono' => $row['telefono'],
                    'contacto' => $row['contacto'],
                    'estado' => $row['estado'],
                    'id_plan_cuentas' => $row['id_plan_cuentas'],
                    'comentario' => $row['comentario'],
                    'descuento' => $row['descuento'],
                    'num_pago' => $row['num_pago'],
                    'tipo_identificacion' => "C??dula de Identidad",
                    'id_codigo_pais' => $row['id_codigo_pais'],
                    'grupo_tributario' => "Persona Natural",
                    'id_cuidad' => $row['id_cuidad'],
                    'id_parroquia' => $row['id_parroquia'],
                    'id_provincia' => $row['id_provincia'],
                    'parte_relacionada' => $row['parte_relacionada'],
                    'lista_precios' => $row['lista_precios'],
                    'limite_credito' => $row['limite_credito'],
                    'id_forma_pagos' => $row['id_forma_pagos'],
                    'id_grupo_cliente' => $row['id_grupo_cliente'],
                    'id_empresa' => $row['id_empresa'],
                    'id_tipo_cliente' => $row['id_tipo_cliente'],
                    'id_vendedor' => $row['id_vendedor'],
                ]);
            }
                //validacion de ruc natural
            if (count($ver) == 0 && !filter_var($row['email'], FILTER_VALIDATE_EMAIL) === false && $row['nombre'] != null
            && $row['tipo_identificacion'] != null && $row['identificacion'] != null && $row['grupo_tributario'] != null
            && $row['direccion'] != null && $row['id_provincia'] != null && $row['id_cuidad'] != null && $row['id_parroquia'] != null
            && $row['email'] != null && $row['telefono'] != null && $row['contacto'] != null && $row['estado'] != null
            && ($cedu == 'R' || $cedu == 'RUC') && ($tipoidenti == 1 || $tipoidenti == 'PERSONA NATURAL') && ($this->validarRucPersonaNatural($row['identificacion']))) 
            {
                Cliente::create([
                        'codigo' => $codauto,
                        'nombre' => $row['nombre'],
                        'nombre_adicional' => $row['nombre_adicional'],
                        'identificacion' => $row['identificacion'],
                        'direccion' => $row['direccion'],
                        'email' => $row['email'],
                        'telefono' => $row['telefono'],
                        'contacto' => $row['contacto'],
                        'estado' => $row['estado'],
                        'id_plan_cuentas' => $row['id_plan_cuentas'],
                        'comentario' => $row['comentario'],
                        'descuento' => $row['descuento'],
                        'num_pago' => $row['num_pago'],
                        'tipo_identificacion' => "Ruc",
                        'id_codigo_pais' => $row['id_codigo_pais'],
                        'grupo_tributario' => "Persona Natural",
                        'id_cuidad' => $row['id_cuidad'],
                        'id_parroquia' => $row['id_parroquia'],
                        'id_provincia' => $row['id_provincia'],
                        'parte_relacionada' => $row['parte_relacionada'],
                        'lista_precios' => $row['lista_precios'],
                        'limite_credito' => $row['limite_credito'],
                        'id_forma_pagos' => $row['id_forma_pagos'],
                        'id_grupo_cliente' => $row['id_grupo_cliente'],
                        'id_empresa' => $row['id_empresa'],
                        'id_tipo_cliente' => $row['id_tipo_cliente'],
                        'id_vendedor' => $row['id_vendedor'],
                    ]);
            }
                //validacion de ruc juridico
            if (count($ver) == 0 && !filter_var($row['email'], FILTER_VALIDATE_EMAIL) === false && $row['nombre'] != null
            && $row['tipo_identificacion'] != null && $row['identificacion'] != null && $row['grupo_tributario'] != null
            && $row['direccion'] != null && $row['id_provincia'] != null && $row['id_cuidad'] != null && $row['id_parroquia'] != null
            && $row['email'] != null && $row['telefono'] != null && $row['contacto'] != null && $row['estado'] != null
            && ($cedu == 'R' || $cedu == 'RUC') && ($tipoidenti == 2 || $tipoidenti == 'PERSONA JURIDICA' || $tipoidenti == 'PERSONA JURIDICA') && ($this->validarRucSociedadPrivada($row['identificacion']))) 
            {
                Cliente::create([
                    'codigo' => $codauto,
                    'nombre' => $row['nombre'],
                    'nombre_adicional' => $row['nombre_adicional'],
                    'identificacion' => $row['identificacion'],
                    'direccion' => $row['direccion'],
                    'email' => $row['email'],
                    'telefono' => $row['telefono'],
                    'contacto' => $row['contacto'],
                    'estado' => $row['estado'],
                    'id_plan_cuentas' => $row['id_plan_cuentas'],
                    'comentario' => $row['comentario'],
                    'descuento' => $row['descuento'],
                    'num_pago' => $row['num_pago'],
                    'tipo_identificacion' => "Ruc",
                    'id_codigo_pais' => $row['id_codigo_pais'],
                    'grupo_tributario' => "Persona Jur??dica",
                    'id_cuidad' => $row['id_cuidad'],
                    'id_parroquia' => $row['id_parroquia'],
                    'id_provincia' => $row['id_provincia'],
                    'parte_relacionada' => $row['parte_relacionada'],
                    'lista_precios' => $row['lista_precios'],
                    'limite_credito' => $row['limite_credito'],
                    'id_forma_pagos' => $row['id_forma_pagos'],
                    'id_grupo_cliente' => $row['id_grupo_cliente'],
                    'id_empresa' => $row['id_empresa'],
                    'id_tipo_cliente' => $row['id_tipo_cliente'],
                    'id_vendedor' => $row['id_vendedor'],
                ]);
            }
                //validacion pasaporte
            if(count($ver) == 0 && !filter_var($row['email'], FILTER_VALIDATE_EMAIL) === false && $row['nombre'] != null
            && $row['tipo_identificacion'] != null && $row['identificacion'] != null && $row['grupo_tributario'] != null
            && $row['direccion'] != null && $row['id_provincia'] != null && $row['id_cuidad'] != null && $row['id_parroquia'] != null
            && $row['email'] != null && $row['telefono'] != null && $row['contacto'] != null && $row['estado'] != null
            && ($cedu == 'P' || $cedu == 'PASAPORTE'))
            {
                Cliente::create([
                    'codigo' => $codauto,
                    'nombre' => $row['nombre'],
                    'nombre_adicional' => $row['nombre_adicional'],
                    'identificacion' => $row['identificacion'],
                    'direccion' => $row['direccion'],
                    'email' => $row['email'],
                    'telefono' => $row['telefono'],
                    'contacto' => $row['contacto'],
                    'estado' => $row['estado'],
                    'id_plan_cuentas' => $row['id_plan_cuentas'],
                    'comentario' => $row['comentario'],
                    'descuento' => $row['descuento'],
                    'num_pago' => $row['num_pago'],
                    'tipo_identificacion' => "Ruc",
                    'id_codigo_pais' => $row['id_codigo_pais'],
                    'grupo_tributario' => "Pasaporte",
                    'id_cuidad' => $row['id_cuidad'],
                    'id_parroquia' => $row['id_parroquia'],
                    'id_provincia' => $row['id_provincia'],
                    'parte_relacionada' => $row['parte_relacionada'],
                    'lista_precios' => $row['lista_precios'],
                    'limite_credito' => $row['limite_credito'],
                    'id_forma_pagos' => $row['id_forma_pagos'],
                    'id_grupo_cliente' => $row['id_grupo_cliente'],
                    'id_empresa' => $row['id_empresa'],
                    'id_tipo_cliente' => $row['id_tipo_cliente'],
                    'id_vendedor' => $row['id_vendedor'],
                ]);
            }
                //consumidor final
            if(count($ver) == 0 && !filter_var($row['email'], FILTER_VALIDATE_EMAIL) === false && $row['nombre'] != null
            && $row['tipo_identificacion'] != null && $row['identificacion'] != null && $row['grupo_tributario'] != null
            && $row['direccion'] != null && $row['id_provincia'] != null && $row['id_cuidad'] != null && $row['id_parroquia'] != null
            && $row['email'] != null && $row['telefono'] != null && $row['contacto'] != null && $row['estado'] != null
            && ($cedu == 'CF' || $cedu == 'CONSUMIDOR FINAL'))
            {
                Cliente::create([
                    'codigo' => $codauto,
                    'nombre' => $row['nombre'],
                    'nombre_adicional' => $row['nombre_adicional'],
                    'identificacion' => "999999999999",
                    'direccion' => $row['direccion'],
                    'email' => $row['email'],
                    'telefono' => $row['telefono'],
                    'contacto' => $row['contacto'],
                    'estado' => $row['estado'],
                    'id_plan_cuentas' => $row['id_plan_cuentas'],
                    'comentario' => $row['comentario'],
                    'descuento' => $row['descuento'],
                    'num_pago' => $row['num_pago'],
                    'tipo_identificacion' => "Consumidor Final",
                    'id_codigo_pais' => $row['id_codigo_pais'],
                    'grupo_tributario' => "Persona Natural",
                    'id_cuidad' => $row['id_cuidad'],
                    'id_parroquia' => $row['id_parroquia'],
                    'id_provincia' => $row['id_provincia'],
                    'parte_relacionada' => $row['parte_relacionada'],
                    'lista_precios' => $row['lista_precios'],
                    'limite_credito' => $row['limite_credito'],
                    'id_forma_pagos' => $row['id_forma_pagos'],
                    'id_grupo_cliente' => $row['id_grupo_cliente'],
                    'id_empresa' => $row['id_empresa'],
                    'id_tipo_cliente' => $row['id_tipo_cliente'],
                    'id_vendedor' => $row['id_vendedor'],
                ]);
            } 
            /*
            else {
                return view('sheets');
            }*/  
        }
    }
    
 
    public function headingRow(): int
    {
        return 1;
    }
    public function sheets(): array
    {
        return [

            'Cliente' => new clienteImport(),
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
     * @param  string  $numero  N??mero de RUC persona natural
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
     * @param  string  $numero  N??mero de RUC sociedad privada
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
     * @param  string  $numero  N??mero de RUC sociedad publica
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
     * @throws exception Cuando valor esta vacio, cuando no es d??gito y
     * cuando no tiene cantidad requerida de caracteres
     */
    protected function validarInicial($numero, $caracteres)
    {
        if (empty($numero)) {
            throw new Exception('Valor no puede estar vacio');
        }

        if (!ctype_digit($numero)) {
            throw new Exception('Valor ingresado solo puede tener d??gitos');
        }

        if (strlen($numero) != $caracteres) {
            throw new Exception('Valor ingresado debe tener ' . $caracteres . ' caracteres');
        }

        return true;
    }

    /**
     * Validaci??n de c??digo de provincia (dos primeros d??gitos de CI/RUC)
     *
     * @param  string  $numero  Dos primeros d??gitos de CI/RUC
     *
     * @return boolean
     *
     * @throws exception Cuando el c??digo de provincia no esta entre 00 y 24
     */
    protected function validarCodigoProvincia($numero)
    {
        if ($numero < 0 or $numero > 24) {
            throw new Exception('Codigo de Provincia (dos primeros d??gitos) no deben ser mayor a 24 ni menores a 0');
        }

        return true;
    }

    /**
     * Validaci??n de tercer d??gito
     *
     * Permite validad el tercer d??gito del documento. Dependiendo
     * del campo tipo (tipo de identificaci??n) se realizan las validaciones.
     * Los posibles valores del campo tipo son: cedula, ruc_natural, ruc_privada
     *
     * Para C??dulas y RUC de personas naturales el terder d??gito debe
     * estar entre 0 y 5 (0,1,2,3,4,5)
     *
     * Para RUC de sociedades privadas el terder d??gito debe ser
     * igual a 9.
     *
     * Para RUC de sociedades p??blicas el terder d??gito debe ser 
     * igual a 6.
     *
     * @param  string $numero  tercer d??gito de CI/RUC
     * @param  string $tipo  tipo de identificador
     *
     * @return boolean
     *
     * @throws exception Cuando el tercer digito no es v??lido. El mensaje
     * de error depende del tipo de Idenficiaci??n.
     */
    protected function validarTercerDigito($numero, $tipo)
    {
        switch ($tipo) {
            case 'cedula':
            case 'ruc_natural':
                if ($numero < 0 or $numero > 5) {
                    throw new Exception('Tercer d??gito debe ser mayor o igual a 0 y menor a 6 para c??dulas y RUC de persona natural');
                }
                break;
            case 'ruc_privada':
                if ($numero != 9) {
                    throw new Exception('Tercer d??gito debe ser igual a 9 para sociedades privadas');
                }
                break;

            case 'ruc_publica':
                if ($numero != 6) {
                    throw new Exception('Tercer d??gito debe ser igual a 6 para sociedades p??blicas');
                }
                break;
            default:
                throw new Exception('Tipo de Identificaci??n no existe.');
                break;
        }

        return true;
    }

    /**
     * Validaci??n de c??digo de establecimiento
     *
     * @param  string $numero  tercer d??gito de CI/RUC
     *
     * @return boolean
     *
     * @throws exception Cuando el establecimiento es menor a 1
     */
    protected function validarCodigoEstablecimiento($numero)
    {
        if ($numero < 1) {
            throw new Exception('C??digo de establecimiento no puede ser 0');
        }

        return true;
    }

    /**
     * Algoritmo Modulo10 para validar si CI y RUC de persona natural son v??lidos.
     *
     * Los coeficientes usados para verificar el d??cimo d??gito de la c??dula,
     * mediante el algoritmo ???M??dulo 10??? son:  2. 1. 2. 1. 2. 1. 2. 1. 2
     *
     * Paso 1: Multiplicar cada d??gito de los digitosIniciales por su respectivo
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
     * Paso 2: S?? alguno de los resultados de cada multiplicaci??n es mayor a o igual a 10,
     * se suma entre ambos d??gitos de dicho resultado. Ex. 12->1+2->3
     *
     * Paso 3: Se suman los resultados y se obtiene total
     *
     * Paso 4: Divido total para 10, se guarda residuo. Se resta 10 menos el residuo.
     * El valor obtenido debe concordar con el digitoVerificador
     *
     * Nota: Cuando el residuo es cero(0) el d??gito verificador debe ser 0.
     *
     * @param  string $digitosIniciales   Nueve primeros d??gitos de CI/RUC
     * @param  string $digitoVerificador  D??cimo d??gito de CI/RUC
     *
     * @return boolean
     *
     * @throws exception Cuando los digitosIniciales no concuerdan contra
     * el c??digo verificador.
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
            throw new Exception('D??gitos iniciales no validan contra D??gito Idenficador');
        }

        return true;
    }

    /**
     * Algoritmo Modulo11 para validar RUC de sociedades privadas y p??blicas
     *
     * El c??digo verificador es el decimo digito para RUC de empresas privadas
     * y el noveno d??gito para RUC de empresas p??blicas
     *
     * Paso 1: Multiplicar cada d??gito de los digitosIniciales por su respectivo
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
     * Nota: Cuando el residuo es cero(0) el d??gito verificador debe ser 0.
     *
     * @param  string $digitosIniciales   Nueve primeros d??gitos de RUC
     * @param  string $digitoVerificador  D??cimo d??gito de RUC
     * @param  string $tipo Tipo de identificador
     *
     * @return boolean
     *
     * @throws exception Cuando los digitosIniciales no concuerdan contra
     * el c??digo verificador.
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
                throw new Exception('Tipo de Identificaci??n no existe.');
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
            throw new Exception('D??gitos iniciales no validan contra D??gito Idenficador');
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
