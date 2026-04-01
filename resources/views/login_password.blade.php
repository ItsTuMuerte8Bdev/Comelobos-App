<!doctype html>
{{-- Inicio de Sesión: Contraseña --}}
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión - Comelobos</title>
    {{-- Incluye el archivo CSS principal de la aplicación --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        :root { /* Paleta de Colores */
            --primary: #113b78; /* Color Primario */
            --accent: #e22b20; /* Color del Botón */
            --input-bg: #eef1f3; /* Color de fondo de Input */
        }
        body { /* Cuerpo de la Página */
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, Arial; /* Fuentes */
            margin: 0; /* Margen */
            background: #fff; /* Color de fondo */
        }
        .wrap { /* Contenedor Principal */
            max-width: 500px; /* Ancho máximo */
            margin: 50px auto; /* Margen */
            padding: 20px; /* Relleno interno */
        }
        .brand { /* Título */
            color: var(--primary); /* Color del texto */
            text-align: center; /* Alineación del texto */
            font-weight: 700; /* Peso de la fuente */
            font-size: 44px; /* Tamaño de la fuente */
            margin: 22px 0; /* Margen */
        }
        .card { /* Tarjeta del Formulario */
            background: #fff; /* Color de fondo */
            padding: 20px; /* Relleno interno */
            border-radius: 10px; /* Radio del borde */
        }
        label { /* Etiquetas de los campos */
            display: block; /* Tipo de display */
            margin: 10px 0 6px; /* Margen */
            color: #222; /* Color del texto */
        }
        .input { /* Contenedor del Input */
            width: 95%; /* Ancho */
            padding: 12px; /* Relleno interno */
            border-radius: 6px; /* Radio del borde */
            background: var(--input-bg); /* Color de fondo */
            border: 1px solid #e6e9eb; /* Borde */
            display: flex; /* Tipo de display */
            align-items: center; /* Alineación de items */
        }
        .input .icon { /* Icono dentro del Input */
            margin-right: 10px; /* Margen derecho */
            color: #8a8f95; /* Color del texto */
        }
        .btn { /* Botón de Continuar */
            display: block; /* Tipo de display */
            width: 100%; /* Ancho */
            padding: 14px; /* Relleno interno */
            background: var(--accent); /* Color de fondo */
            color: #fff; /* Color del texto */
            border: none; /* Borde */
            border-radius: 6px; /* Radio del borde */
            font-weight: 600; /* Peso de la fuente */
            margin-top: 18px; /* Margen superior */
        }
        .muted { /* Texto Desapercibido */
            color: #777; /* Color del texto */
            text-align: center; /* Alineación del texto */
            margin-top: 14px; /* Margen superior */
        }
        .small { /* Texto Pequeño */
            font-size: 14px; /* Tamaño de la fuente */
            color: #333; /* Color del texto */
            margin-top: 18px; /* Margen superior */
            text-align: center; /* Alineación del texto */
        }
        .link-back { /* Enlace de Retroceso */
            color: #444; /* Color del texto */
            margin-bottom: 12px; /* Margen inferior */
            display: inline-block; /* Tipo de display */
        }
    </style>
</head>
<body>
<div class="wrap">
    {{-- Enlace para retroceder a la pantalla de email --}}
    <a href="/login" class="link-back">‹ Atrás</a>
    <div class="brand">COMELOBOS</div>
    <div class="card">
        {{-- Formulario para autenticar el usuario con su contraseña --}}
        <form method="POST" action="{{ route('login.attempt') }}">
            {{-- Token CSRF para proteger contra ataques de falsificación de solicitudes entre sitios --}}
            @csrf

            <label for="password">Contraseña</label>
            <div class="input">
                <span class="icon">🔒</span>
                <input id="password" name="password" type="password" placeholder="Contraseña" style="border:0;background:transparent;flex:1">
            </div>

            {{-- Campo oculto que almacena el email del usuario del paso anterior --}}
            <input type="hidden" name="email" value="{{ $email ?? '' }}">

            <button class="btn" type="submit">Continuar</button>
        </form>

        <div class="muted">¿Has olvidado tu contraseña?</div>
        <div class="small">¿No tienes una cuenta? <a href="/register">Regístrate</a></div>
    </div>
</div>
</body>
</html>