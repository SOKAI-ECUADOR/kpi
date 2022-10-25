<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<table border="1" cellpadding="2px" cellspacing="0px" >
    
    <thead>
        <tr>
            <th>Cuenta</th>
            <th>Nombre</th>
            <th>Descripcion</th>
            <th>Debe</th>
            <th>Haber</th>
        </tr>  
    </thead>
    
    <tbody>
    
    @foreach($reporte as $res)
        <tr>
        
            <td >{{ $res->codcta }}</td>
            <td >{{ $res->nomcta }}</td>
            <td>{{ $res->concepto }}</td>
            <td>{{ $res->debe }}</td>
            <td>{{ $res->haber }}</td>
        </tr>
        @endforeach
       
    </tbody>
    </table>
</body>
</html>