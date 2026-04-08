<!doctype html>
{{-- Formulario de Registro de Nuevos Usuarios --}}
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrarse - Comelobos</title>
    {{-- Incluye el archivo CSS principal de la aplicación --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        :root { /* Paleta de Colores */
            --primary: var(--color-primary, #113b78); /* Color Primario */
            --accent: var(--color-accent, #e22b20); /* Color del Botón */
            --muted: #f1f3f4; /* Color Desapercibido */
            --input-bg: #eef1f3; /* Color de fondo de Input */
        }
        body { /* Cuerpo de la Página */
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; /* Fuentes */
            margin: 0; /* Margen */
            padding: 0; /* Relleno */
            background: #fff; /* Color de fondo */
        }
        .wrap { /* Contenedor Principal */
            max-width: 520px; /* Ancho máximo */
            margin: 30px auto; /* Margen */
            padding: 28px; /* Relleno interno */
        }
        .brand { /* Título */
            color: var(--primary); /* Color del texto */
            text-align: center; /* Alineación del texto */
            font-weight: 700; /* Peso de la fuente */
            font-size: 44px; /* Tamaño de la fuente */
            margin: 18px 0; /* Margen */
        }
        .card { /* Tarjeta del Formulario */
            background: #fff; /* Color de fondo */
            padding: 18px; /* Relleno interno */
            border-radius: 6px; /* Radio del borde */
        }
        label { /* Etiquetas de los campos */
            display: block; /* Tipo de display */
            margin: 12px 0 6px; /* Margen */
            color: #222; /* Color del texto */
        }
        input[type="text"], input[type="email"], input[type="tel"], input[type="password"] { /* Inputs de Texto */
            box-sizing: border-box;
            width: 100%; /* Ancho */
            padding: 12px; /* Relleno interno */
            border-radius: 8px; /* Radio del borde */
            background: var(--input-bg); /* Color de fondo */
            border: 1px solid #e6e9eb; /* Borde */
            max-width: 100%;
        }
        .btn { /* Botón de Registrarse */
            display: block; /* Tipo de display */
            width: 100%; /* Ancho */
            padding: 14px; /* Relleno interno */
            background: var(--accent); /* Color de fondo */
            color: #fff; /* Color del texto */
            border: none; /* Borde */
            border-radius: 6px; /* Radio del borde */
            font-weight: 600; /* Peso de la fuente */
            margin-top: 18px; /* Margen superior */
            box-sizing: border-box;
        }
        .muted { /* Texto Desapercibido */
            color: #777; /* Color del texto */
            text-align: center; /* Alineación del texto */
            margin-top: 14px; /* Margen superior */
        }
    </style>
</head>
<body>
<div class="wrap">
    <a href="{{ route('login') }}" class="link-back">‹ Atrás</a>
    <div class="brand">COMELOBOS</div>

    <div class="card">
        {{-- Formulario para registrar nuevos usuarios --}}
        <form method="POST" action="{{ route('autenticarRegistro') }}">
            {{-- Token CSRF para proteger contra ataques de falsificación de solicitudes entre sitios --}}
            @csrf

            <label for="matriculation_number">Matrícula</label>
            <input id="matriculation_number" name="matriculation_number" type="text" placeholder="Matrícula">
                @error('matriculation_number')
                    <div style="color: red; font-size: 12px; font-weight: normal; text-align: right;">
                        {{ $message }}
                    </div>
                @enderror

            <label for="first_name">Nombre(s)</label>
            <input id="first_name" name="first_name" type="text" placeholder="Nombre(s)">
                @error('first_name')
                    <div style="color: red; font-size: 12px; font-weight: normal; text-align: right;">
                        {{ $message }}
                    </div>
                @enderror
                
            <label for="last_name">Primer Apellido (Apellido Paterno)</label>
            <input id="last_name" name="last_name" type="text" placeholder="Apellido Paterno">
                @error('last_name')
                    <div style="color: red; font-size: 12px; font-weight: normal; text-align: right;">
                        {{ $message }}
                    </div>
                @enderror

            <label for="second_last_name">Segundo Apellido (Apellido Materno)</label>
            <input id="second_last_name" name="second_last_name" type="text" placeholder="Apellido Materno">
                @error('second_last_name')
                    <div style="color: red; font-size: 12px; font-weight: normal; text-align: right;">
                        {{ $message }}
                    </div>
                @enderror
 
            <label for="phone">Teléfono</label>
            <input id="phone" name="phone" type="tel" placeholder="(555) 123-4567">
                @error('phone')
                    <div style="color: red; font-size: 12px; font-weight: normal; text-align: right;">
                        {{ $message }}
                    </div>
                @enderror
 
            <label for="email">Correo Institucional</label>
            <input id="email" name="email" type="email" placeholder="correo@ejemplo.com">
                @error('email')
                    <div style="color: red; font-size: 12px; font-weight: normal; text-align: right;">
                        {{ $message }}
                    </div>
                @enderror
 
            <label for="password">Contraseña</label>
            <input id="password" name="password" type="password" placeholder="Contraseña">
                @error('password')
                    <div style="color: red; font-size: 12px; font-weight: normal; text-align: right;">
                        {{ $message }}
                    </div>
                @enderror
 
            <button class="btn" type="submit">Registrarse</button>
        </form>

        <div class="muted">Al registrarte aceptas nuestra Política de Privacidad y Términos y Condiciones</div>
    </div>
</div>
</body>
</html>