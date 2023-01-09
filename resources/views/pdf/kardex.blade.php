<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>KARDEX</title>
    <style>
        @page {
            margin: 25px;
            font-family: Arial;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
        }

        .titulo1 {
            font-size: 16px;
            font-weight: bold;
        }

        .titulo1-1-1-1 {
            font-size: 14px;
        }

        .titulo1-1 {
            font-size: 16px;
        }

        .titulo2 {
            font-size: 11.8px;
        }

        .titulo3 {
            font-size: 11px;
        }

        .texto {
            font-size: 10px;
        }

        .text-center {
            text-align: center !important;
            width: 100%;
        }

        .mb-0 {
            margin-bottom: 3px;
        }

        .mb-1 {
            margin-bottom: 6px;
        }

        .mb-2 {
            margin-bottom: 8px;
        }

        .mb-3 {
            margin-bottom: 10px;
        }

        .mr-3 {
            margin-right: 10px;
        }

        .mr-4 {
            margin-right: 20px;
        }

        .mt-3 {
            margin-top: 10px;
        }

        .bold {
            font-weight: bold;
        }

        .nobold {
            font-weight: 10;
        }

        th,
        td {
            font-size: 9px;
            padding: 5px;
        }

        .bordeado {
            border: 1px solid #000;
            border-radius: 10px;
            padding: 10px 10px;
        }

        .bordeado1 {
            border: 1px solid #000;
        }

        tr:nth-child(even) {
            background: #d8d8d8;
        }
    </style>
    <script src="javascript.js">


    </script>
</head>

<body>
    <?php
        //ini_set('max_execution_time', 53200);
        ini_set('max_execution_time', 3000);
        ini_set('memory_limit','4000M');
    ?>
    <!--<div>{{var_dump($kardex)}}</div>-->
    @foreach ($kardex as $kardexs)
    <table BORDER CELLPADDING=10 CELLSPACING=0 style="width: 100%">

        <tr bgcolor="#ffa959">
            <th colspan="60" style="width:40%;" class="titulo1 bordeado1">
                KARDEX
            </th>
        </tr>
        <tr bgcolor="#ffc590">
            <th colspan="30" class="bordeado1" style="width:50%;">PRODUCTO: {{strtoupper($kardexs->cod_principal)}} - {{strtoupper($kardexs->nombre_producto)}}</th>
            <th colspan="30" class="bordeado1" style="width:50%;">BODEGA: {{strtoupper($kardexs->nombre_bodega)}}</th>
        </tr>
        <tr>
            <th colspan="30" class="bordeado1" style="width:50%;">EXISTENCIA MÍNIMA: {{$kardexs->existencia_minima}}</th>
            <th colspan="30" class="bordeado1" style="width:50%;">EXISTENCIA MÁXIMA: {{$kardexs->existencia_maxima}}</th>
        </tr>
        <tr bgcolor="#ffca99">
            <th colspan="20" class="bordeado1" style="width:33.33%;">CANTIDAD: {{$kardexs->cantidad}}</th>
            <th colspan="20" class="bordeado1" style="width:33.33%;">COSTO UNITARIO: {{$kardexs->costo_unitario}}</th>
            <th colspan="20" class="bordeado1" style="width:33.33%;">COSTO TOTAL: {{$kardexs->costo_total}}</th>
        </tr>
        <tr>
            <th colspan="60"></th>

        </tr>
        <thead>
            <tr bgcolor="#ffa959">
                <th colspan="24" class="bordeado1" style="width:40%;">INFORMACIÓN</th>
                <th colspan="12" class="bordeado1" style="width:20%;">INGRESOS</th>
                <th colspan="12" class="bordeado1" style="width:20%;">EGRESOS</th>
                <th colspan="12" class="bordeado1" style="width:20%;">SALDOS</th>
            </tr>
            <tr bgcolor="#ffc590" ALIGN=center>
                <th colspan="1" class="bordeado1">No.</th>
                <th colspan="4" class="bordeado1">Fecha</th>
                <th colspan="9" class="bordeado1">Transaccion</th>
                <th colspan="10" class="bordeado1">Proveedor o Cliente</th>
                <th colspan="4" class="bordeado1">Cantidad</th>
                <th colspan="4" class="bordeado1" style="font-size: 45%;">Costo Unitario</th>
                <th colspan="4" class="bordeado1">Costo Total</th>
                <th colspan="4" class="bordeado1">Cantidad</th>
                <th colspan="4" class="bordeado1" style="font-size: 45%;">Costo Unitario</th>
                <th colspan="4" class="bordeado1">Costo Total</th>
                <th colspan="4" class="bordeado1">Cantidad</th>
                <th colspan="4" class="bordeado1" style="font-size: 45%;">Costo Unitario</th>
                <th colspan="4" class="bordeado1">Costo Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kardexs->transacciones as $trans)
            <tr ALIGN=center>
                <td colspan="1" class="bordeado1">{{$trans->numero_transaccion}}</td>
                <td colspan="4" class="bordeado1">{{$trans->fecha_transaccion}}</td>
                <td colspan="9" class="bordeado1">{{$trans->documento}}</td>
                @if ($trans->agente !=null)
                <td colspan="10" class="bordeado1">{{$trans->agente}}</td>
                @else
                <td colspan="10" class="bordeado1">{{$trans->tipo_transaccion}}</td>
                @endif
                <td colspan="4" class="bordeado1">{{$trans->cantidad_ingreso}}</td>
                @if ($trans->costo_unitario_ingreso !=null)
                <td colspan="4" class="bordeado1">{{round($trans->costo_unitario_ingreso, 6)}}</td>
                @else
                <td colspan="4" class="bordeado1">{{$trans->costo_unitario_ingreso}}</td>
                @endif
                <td colspan="4" class="bordeado1">{{$trans->costo_total_ingreso}}</td>
                <td colspan="4" class="bordeado1">{{$trans->cantidad_egreso}}</td>
                @if ($trans->costo_unitario_egreso !=null)
                <td colspan="4" class="bordeado1">{{round($trans->costo_unitario_egreso, 6)}}</td>
                @else
                <td colspan="4" class="bordeado1">{{$trans->costo_unitario_egreso}}</td>
                @endif
                <td colspan="4" class="bordeado1">{{$trans->costo_total_egreso}}</td>
                <td colspan="4" class="bordeado1">{{$trans->cantidad_saldo}}</td>
                @if ($trans->costo_unitario_saldo !=null)
                <td colspan="4" class="bordeado1">{{round($trans->costo_unitario_saldo, 6) }}</td>
                @else
                <td colspan="4" class="bordeado1">{{$trans->costo_unitario_saldo}}</td>
                @endif
                <td colspan="4" class="bordeado1">{{$trans->costo_total_saldo}}</td>
            </tr>
            @endforeach
        </tbody>
    </table><br><br>
    @endforeach
</body>

</html>