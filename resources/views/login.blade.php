<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión - Comelobos</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        :root{--primary:#113b78;--accent:#e22b20;--input-bg:#eef1f3}
        body{font-family: system-ui, -apple-system, 'Segoe UI', Roboto, Arial;margin:0;background:#fff}
        .wrap{max-width:420px;margin:26px auto;padding:18px}
        .brand{color:var(--primary);text-align:center;font-weight:700;font-size:44px;margin:22px 0}
        .card{background:#fff;padding:18px;border-radius:6px}
        label{display:block;margin:12px 0 6px;color:#222}
        .input{width:100%;padding:12px;border-radius:6px;background:var(--input-bg);border:1px solid #e6e9eb;display:flex;align-items:center}
        .input .icon{margin-right:10px;color:#8a8f95}
        .btn{display:block;width:100%;padding:14px;background:var(--accent);color:#fff;border:none;border-radius:6px;font-weight:600;margin-top:18px}
        .muted{color:#777;text-align:center;margin-top:14px}
        .small{font-size:14px;color:#333;margin-top:18px;text-align:center}
    </style>
</head>
<body>
<div class="wrap">
    <div class="brand">COMELOBOS</div>
    <div class="card">
        <form method="POST" action="/login/password">
            @csrf
            <label for="email">Email</label>
            <div class="input">
                <span class="icon">✉️</span>
                <input id="email" name="email" type="email" placeholder="correo@ejemplo.com" style="border:0;background:transparent;flex:1">
            </div>

            <button class="btn" type="submit">Continuar</button>
        </form>

        <div class="muted">Si continúas, aceptas nuestra Política de Privacidad y Términos y Condiciones</div>
        <div class="small">¿No tienes una cuenta? <a href="/register">Regístrate</a></div>
    </div>
</div>
</body>
</html>