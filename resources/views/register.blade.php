<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrarse - Comelobos</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        :root {
            --primary: var(--color-primary, #113b78);
            --accent: var(--color-accent, #e22b20);
            --muted: #f1f3f4;
            --input-bg: #eef1f3;
        }
        body{font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; margin:0; padding:0; background:#fff}
        .wrap{max-width:520px;margin:30px auto;padding:28px}
        .brand{color:var(--primary);text-align:center;font-weight:700;font-size:44px;margin:18px 0}
        .card{background:#fff;padding:18px;border-radius:6px}
        label{display:block;margin:12px 0 6px;color:#222}
        input[type="text"], input[type="email"], input[type="tel"]{width:100%;padding:12px;border-radius:8px;background:var(--input-bg);border:1px solid #e6e9eb}
        .btn{display:block;width:100%;padding:14px;background:var(--accent);color:#fff;border:none;border-radius:6px;font-weight:600;margin-top:18px}
        .muted{color:#777;text-align:center;margin-top:14px}
    </style>
</head>
<body>
<div class="wrap">
    <div class="brand">COMELOBOS</div>

    <div class="card">
        <form method="POST" action="/register">
            @csrf

            <label for="nombre">Nombre</label>
            <input id="nombre" name="nombre" type="text" placeholder="Nombre">

            <label for="apellidos">Apellidos</label>
            <input id="apellidos" name="apellidos" type="text" placeholder="Apellidos">

            <label for="email">Email</label>
            <input id="email" name="email" type="email" placeholder="correo@ejemplo.com">

            <label for="telefono">Teléfono</label>
            <input id="telefono" name="telefono" type="tel" placeholder="(555) 123-4567">

            <label for="escuela">Escuela, Facultad o Dependencia</label>
            <input id="escuela" name="escuela" type="text" placeholder="Ej. Facultad de Ciencias">

            <label for="matricula">ID o Matrícula</label>
            <input id="matricula" name="matricula" type="text" placeholder="ID o matrícula">

            <label for="puesto">Puesto</label>
            <input id="puesto" name="puesto" type="text" placeholder="Puesto (opcional)">

            <button class="btn" type="submit">Registrarse</button>
        </form>

        <div class="muted">Al registrarte aceptas nuestra Política de Privacidad y Términos y Condiciones</div>
    </div>
</div>
</body>
</html>