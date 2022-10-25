<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proforma</title>
    <link href="./public/css/grid/grid.min.css" rel="stylesheet" type="text/css" media="screen">
    <style type="text/css">
        html {
            margin: 0.5cm;
        }

        body {
            font-family: sans-serif;
            font-size: 7;
        }

        table {
            /* table-layout: fixed; */
            width: 100%;
            border-collapse: collapse;
            border: 1px black;
        }

        thead th:nth-child(1) {
            width: 10%;
        }

        thead th:nth-child(2) {
            width: 8%;
        }

        thead th:nth-child(3) {
            width: 6%;
        }

        thead th:nth-child(4) {
            width: 4%;
        }

        td {
            font-size: 5;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                HOLA
            </div>
            <div class="col-sm-6">
                MUNDO
            </div>
        </div>

    </div>

    <table>
        <tr>

            <td></td>
            <td><b>
                    <table>
                        <tr>
                            <td>
                                <font size=1>RUC: {{$proformas[0]->ruc_empresa}}</font>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <font size=1 align=center>{{$proformas[0]->direccion_empresa}}</font>
                            </td>
                        </tr>
                    </table>
                </b>
            </td>
        </tr>
        <tr>
            <td>
                <h4><b>COTIZACIÓN DE PRECIOS</b></h4>
            </td>
        </tr>
        <tr>
            <td>
                <p>N° {{$proformas[0]->contador}}</p>
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <td><b>RUC:</b></td>
            <td>{{$proformas[0]->identificacion}}</td>
            <td><b>FECHA:</b></td>
            <td>{{$proformas[0]->fecha_emision}}</td>
        </tr>
        <tr>
            <td><b>CLIENTE:</b></td>
            <td>{{$proformas[0]->cliente}}</td>
            <td><b>CIUDAD:</b></td>
            <td>{{$proformas[0]->ciudad}}</td>
        </tr>
        <tr>
            <td><b>ATENCIÓN:</b></td>
            <td>{{$proformas[0]->contacto}}</td>
            <td><b>DIRECCIÓN:</b></td>
            <td>{{$proformas[0]->direccion}}</td>
        </tr>
        <tr>
            <td><b>E-MAIL:</b></td>
            <td>{{$proformas[0]->email}}</td>
            <td><b>TELÉFONO:</b></td>
            <td>{{$proformas[0]->telefono}}</td>
        </tr>
    </table>
    <h4><b>APRECIADO CLIENTE:</b></h4>
    De acuerdo a su solicitud nos permitimos enviar nuestra propuesta comercial:
    <br></br>
    <table border="1">
        <thead bgcolor="#6CB26B">
            <tr>TU SEGURIDAD NOS IMPORTA EN GRUPO SOLIS</tr>
            <tr>
                <th align="center" colspan="1">ITEM</th>
                <th align="center" colspan="1">PRODUCTO</th>
                <th align="center" colspan="1">CARACTERÍSTICAS</th>
                <th align="center" colspan="1">NORMATIVA</th>
                <th align="center" colspan="1">USO</th>
                <th align="center" width="20">MARCA</th>
                <th align="center" width="10">UNIDAD</th>
                <th align="center" width="10">TALLA</th>
                <th align="center" width="10">CANTIDAD</th>
                <th align="center" width="15">VALOR UNITARIO</th>
                <th align="center" width="20">VALOR TOTAL</th>
                <th align="center" width="15">TIEMPO DE ENTREGA</th>
            </tr>
        </thead>
        <tbody>
            @foreach($proformas as $proforma)
            <tr>
                <td align="justify" colspan="1">{{$proforma->imagen}}</td>
                <td align="justify" colspan="1">{{$proforma->nombre}}</td>
                <td align="justify" colspan="1">{{$proforma->caracteristicas}}</td>
                <td align="justify" colspan="1">{{$proforma->normativa}}</td>
                <td align="justify" colspan="1">{{$proforma->uso}}</td>
                <td align="justify" width="20">{{$proforma->marca}}</td>
                <td align="justify" width="10">{{$proforma->tipo_medida}}</td>
                <td align="justify" width="10">{{$proforma->unidad_medida}}</td>
                <td align="justify" width="10">{{$proforma->cantidad}}</td>
                <td align="justify" width="15">{{$proforma->precio}}</td>
                <td align="justify" width="20">{{$proforma->total_pro}}</td>
                <td align="justify" width="15">15 DÍAS HÁBILES</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>