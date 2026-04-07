<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>COMELOBOS - Bienvenida</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <!-- estilos movidos a app.css -->
</head>
<body class="welcome-root">
  <div class="splash" role="presentation">
    <div class="logo">COMELOBOS</div>
    <div class="overlay" aria-hidden="true"></div>
    <!-- combined contiene el texto final y se expande desde el centro hacia los lados -->
    <div class="combined">COMELOBOS <span class="sep">|</span> BUAP</div>
  </div>

  <script>
    // Redirige a la pantalla principal una vez completada la animación (5s)
    setTimeout(function(){ window.location.href = "<?php echo url('/index'); ?>"; }, 5000);
  </script>
</body>
</html>
