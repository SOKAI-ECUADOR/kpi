<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- <link rel="icon" href="<%= BASE_URL %>favicon.ico"> -->

    <title>SOKAI | SISTEMA CONTABLE & AUDITORÍA INTEGRADO</title>
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset(mix('css/main.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/iconfont.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/material-icons/material-icons.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/vuesax.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/prism-tomorrow.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/app.css')) }}">
    <!--<link rel="stylesheet" href="css/estilos.css">-->
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('images/logo/favicon-4.png') }}">
  </head>
  <body>
    <noscript>
      <strong>Lo sentimos, tu navegador no soporta nuestros script que son requeridos para usar eeste sistema.</strong>
    </noscript>
    <div id="app">

    </div>

    <!-- <script src="js/app.js"></script> -->
    <script src="{{ asset(mix('js/app.js')) }}"></script>
    <script src="{{ asset('js/forge.min.js') }}"></script>
    <script src="{{ asset('js/buffer.js') }}"></script>
    <script src="{{ asset('js/moment.min.js') }}"></script>
  </body>
</html>
