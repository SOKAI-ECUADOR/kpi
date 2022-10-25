<?php

namespace App\Http\Controllers;

use App\Models\Plancuenta;
use App\Models\FormaDePagos;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Forma_pagosController extends Controller
{
    public function listar(Request $request){
        $buscar = $request->buscar;
        $id_empresa = $request->empresa;
        $lista = DB::select("SELECT fp.*, fps.codigo as codigofps, fps.descripcion as descripcionfps, pc.codcta, pc.nomcta AS nom_cta FROM forma_pagos fp INNER JOIN forma_pagos_sri fps ON fp.id_forma_pagos_sri=fps.id_forma_pagos_sri LEFT JOIN plan_cuentas pc ON pc.id_plan_cuentas = fp.id_plan_cuentas WHERE (fps.codigo LIKE '%$buscar%' OR fps.descripcion LIKE '%$buscar%' OR fp.descripcion LIKE '%$buscar%') AND fps.id_empresa =" . $id_empresa . " ORDER BY fp.id_forma_pagos DESC");
        return $lista;
    }
    public function listarFormasPagosAsientos(Request $request){
        //$id_empresa = $request->empresa;
        if($request->id_plc!==null){
            $lista = DB::select("SELECT fp.*, fps.codigo as codigofps, fps.descripcion as descripcionfps, pc.codcta, pc.nomcta AS nom_cta FROM forma_pagos fp INNER JOIN forma_pagos_sri fps ON fp.id_forma_pagos_sri=fps.id_forma_pagos_sri LEFT JOIN plan_cuentas pc ON pc.id_plan_cuentas = fp.id_plan_cuentas WHERE pc.bansel is not null and fps.id_empresa =" . $request->id_empresa . " and fp.tipo_forma_pago is not null and pc.id_plan_cuentas={$request->id_plc} ORDER BY fp.id_forma_pagos DESC");
        }else{
            $lista = DB::select("SELECT fp.*, fps.codigo as codigofps, fps.descripcion as descripcionfps, pc.codcta, pc.nomcta AS nom_cta FROM forma_pagos fp INNER JOIN forma_pagos_sri fps ON fp.id_forma_pagos_sri=fps.id_forma_pagos_sri LEFT JOIN plan_cuentas pc ON pc.id_plan_cuentas = fp.id_plan_cuentas WHERE pc.bansel is not null and fps.id_empresa =" . $request->id_empresa . " and fp.tipo_forma_pago is not null ORDER BY fp.id_forma_pagos DESC");
        }
        
        return $lista;
    }
    public function listarFormasPagosAsientosIndex(Request $request){
        //$id_empresa = $request->empresa;
        $lista = DB::select("SELECT fp.*, fps.codigo as codigofps, fps.descripcion as descripcionfps, pc.codcta, pc.nomcta AS nom_cta FROM forma_pagos fp INNER JOIN forma_pagos_sri fps ON fp.id_forma_pagos_sri=fps.id_forma_pagos_sri LEFT JOIN plan_cuentas pc ON pc.id_plan_cuentas = fp.id_plan_cuentas WHERE pc.bansel is not null and fps.id_empresa =" . $request->id_empresa . " and fp.id_forma_pagos=".$request->id_forma_pago." and fp.tipo_forma_pago is not null ORDER BY fp.id_forma_pagos DESC");
        if(count($lista)<=0){
            return "vacio";
        }else{
            return $lista;
        }

    }
    public function guardar(Request $request){
        $descripcion = $request->tabla["descripcion"];
        $forma_pagos_sri = $request->tabla["forma_pagos_sri"];
        $empresa = $request->empresa;
        $cta = $request->cta;
        $nom_cta = $request->nom_cta;
        if(strlen($nom_cta)>0 && !isset($cta)){
            $recupera = DB::select("SELECT * FROM plan_cuentas WHERE codcta = '$nom_cta' and id_empresa = $empresa");
            if(isset($recupera[0]->id_plan_cuentas)){
                $cta = $recupera[0]->id_plan_cuentas;
            }else{
                return 'errorcuenta';
            }

        }

        $pc = new FormaDePagos();
        $pc->descripcion = $descripcion;
        $pc->tipo_forma_pago = $request->tipo_forma_pago;
        $pc->id_empresa = $empresa;
        $pc->id_forma_pagos_sri = $forma_pagos_sri;
        if(strlen($cta)>=1){
            $pc->id_plan_cuentas = $cta;
        }
        $pc->save();
    }
    public function editar(Request $request){
        $id = $request->tabla["id"];
        $descripcion = $request->tabla["descripcion"];
        $empresa = $request->empresa;
        $forma_pagos_sri = $request->tabla["forma_pagos_sri"];
        $cta = $request->cta;
        $nom_cta = $request->nom_cta;
        $cod_cta = $request->cod_cta;
        if(strlen($nom_cta)>0){
            if(!isset($cta)){
                $recupera = DB::select("SELECT * FROM plan_cuentas WHERE codcta = '$cod_cta' and id_empresa = $empresa");
                if(isset($recupera[0]->id_plan_cuentas)){
                    $cta = $recupera[0]->id_plan_cuentas;
                }else{
                    return 'errorcuenta';
                }
            }
        }

        $pc = FormaDePagos::find($id);
        $pc->descripcion = $descripcion;
        $pc->tipo_forma_pago = $request->tipo_forma_pago;
        $pc->id_forma_pagos_sri = $forma_pagos_sri;
        if(strlen($cta)>=1){
            $pc->id_plan_cuentas = $cta;
        }
        $pc->save();
    }
    public function eliminar($id){
        $delete = DB::select("DELETE FROM forma_pagos WHERE id_forma_pagos = " . $id);
        return $delete;
    }
    public function cuenta_contable(Request $request){
        $empresa = $request->empresa;
        $buscar = $request->buscar;
        $sel = DB::select("SELECT * FROM plan_cuentas WHERE (nomcta LIKE '%$buscar%' OR codcta LIKE '%$buscar%') AND id_empresa = $empresa");
        return $sel;
    }
}

/*
INSERT INTO `sokai-devops`.`forma_pagos` (`codigo`, `descripcion`, `id_empresa`, `id_forma_pagos_sri`) VALUES ('01', 'SIN UTILIZACION DEL SISTEMA FINANCIERO', '1', '1');
INSERT INTO `sokai-devops`.`forma_pagos` (`codigo`, `descripcion`, `id_empresa`, `id_forma_pagos_sri`) VALUES ('15', 'COMPENSACIÓN DE DEUDAS', '1', '2');
INSERT INTO `sokai-devops`.`forma_pagos` (`codigo`, `descripcion`, `id_empresa`, `id_forma_pagos_sri`) VALUES ('16', 'TARJETA DE DÉBITO', '1', '3');
INSERT INTO `sokai-devops`.`forma_pagos` (`codigo`, `descripcion`, `id_empresa`, `id_forma_pagos_sri`) VALUES ('17', 'DINERO ELECTRÓNICO', '1', '4');
INSERT INTO `sokai-devops`.`forma_pagos` (`codigo`, `descripcion`, `id_empresa`, `id_forma_pagos_sri`) VALUES ('18', 'TARJETA PREPAGO', '1', '5');
INSERT INTO `sokai-devops`.`forma_pagos` (`codigo`, `descripcion`, `id_empresa`, `id_forma_pagos_sri`) VALUES ('19', 'TARJETA DE CRÉDITO', '1', '6');
INSERT INTO `sokai-devops`.`forma_pagos` (`codigo`, `descripcion`, `id_empresa`, `id_forma_pagos_sri`) VALUES ('20', 'OTROS CON UTILIZACION DEL SISTEMA', '1', '7');
INSERT INTO `sokai-devops`.`forma_pagos` (`codigo`, `descripcion`, `id_empresa`, `id_forma_pagos_sri`) VALUES ('21', 'ENDOSO DE TÍTULOS', '1', '8');
*/
