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
        .link-back{color:#444;margin-bottom:12px;display:inline-block}
    </style>
</head>
<body>
<div class="wrap">
    <a href="/login" class="link-back">‹ Atrás</a>
    <div class="brand">COMELOBOS</div>
    <div class="card">
        <form method="POST" action="{{ route('login.attempt') }}">
            @csrf

            <label for="password">Contraseña</label>
            <div class="input">
                <span class="icon">🔒</span>
                <input id="password" name="password" type="password" placeholder="Contraseña" style="border:0;background:transparent;flex:1">
            </div>

            <input type="hidden" name="email" value="{{ $email ?? '' }}">

            <button class="btn" type="submit">Continuar</button>
        </form>

        <div class="muted">¿Has olvidado tu contraseña?</div>
        <div class="small">¿No tienes una cuenta? <a href="/register">Regístrate</a></div>
    </div>
</div>
</body>
</html>