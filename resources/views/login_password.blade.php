<!doctype html>
{{-- Inicio de Sesión: Contraseña --}}
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión - Comelobos</title>
    {{-- Incluye el archivo CSS principal de la aplicación --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="wrap auth-wide">
    {{-- Enlace para retroceder a la pantalla de email --}}
    <a href="{{ route('login') }}" class="link-back">‹ Atrás</a>
    <div class="brand">COMELOBOS</div>
    <div class="card">
        {{-- Formulario para autenticar el usuario con su contraseña --}}
        <form method="POST" action="{{ route('autenticarLogin') }}">
            {{-- Token CSRF para proteger contra ataques de falsificación de solicitudes entre sitios --}}
            @csrf

            <label for="password">Contraseña</label>
            <div class="input">
                <span class="icon">🔒</span>
                <input id="password" name="password" type="password" placeholder="Contraseña">
            </div>

            {{-- Campo oculto que almacena el email del usuario del paso anterior --}}
            <input type="hidden" name="email" value="{{ $email ?? '' }}">

            <button class="btn" type="submit">Continuar</button>
        </form>

        <div class="muted">¿Has olvidado tu contraseña?</div>
        <div class="small">¿No tienes una cuenta? <a href="{{ route('registrarse') }}">Regístrate</a></div>
    </div>
</div>
</body>
</html>