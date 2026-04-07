<!doctype html>

{{-- Inicio de Sesión: Email --}}

<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión - Comelobos</title>
    {{-- Incluye el archivo CSS principal de la aplicación --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- estilos movidos a app.css -->
</head>
<body>
<div class="wrap">
    <div class="brand">COMELOBOS</div>
    <div class="card">
        {{-- Formulario para enviar el email del usuario para el proceso de login --}}
        <form method="POST" action="/login/password">
            {{-- Token CSRF para proteger contra ataques de falsificación de solicitudes entre sitios --}}
            @csrf
            <label for="email">Email</label>
            <div class="input">
                <span class="icon">✉️</span>
                <input id="email" name="email" type="email" placeholder="correo@ejemplo.com">
            </div>

            <button class="btn" type="submit">Continuar</button>
        </form>

        <div class="muted">Si continúas, aceptas nuestra Política de Privacidad y Términos y Condiciones</div>
        <div class="small">¿No tienes una cuenta? <a href="/register">Regístrate</a></div>
    </div>
</div>
</body>
</html>