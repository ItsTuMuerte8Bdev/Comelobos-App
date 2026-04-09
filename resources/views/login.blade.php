<!doctype html>

{{-- Inicio de Sesión: Email --}}

<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión - Comelobos</title>
    {{-- Incluye Bootstrap CSS (necesario para el modal) y el archivo CSS principal --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- estilos movidos a app.css -->
</head>
<body>
<div class="wrap">
    <div class="brand">COMELOBOS</div>
    <div class="card">
        {{-- Apuntamos directamente a tu controlador final --}}
        <form method="POST" action="{{ route('autenticarLogin') }}">
            @csrf

            <label for="email">Correo Institucional</label>
            <div class="input">
                <span class="icon">✉️</span>
                <input id="email" name="email" type="email" placeholder="correo@ejemplo.com" value="{{ old('email') }}">
                
                @error('email')
                    <div style="color: red; font-size: 12px; font-weight: normal; text-align: right; margin-top: 5px;">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <label for="password" style="margin-top: 15px; display: block;">Contraseña</label>
            <div class="input">
                <span class="icon">🔒</span>
                <input id="password" name="password" type="password" placeholder="Contraseña">
                
                @error('password')
                    <div style="color: red; font-size: 12px; font-weight: normal; text-align: right; margin-top: 5px;">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <button class="btn" type="submit" style="margin-top: 20px;">Iniciar Sesión</button>
        </form>
        
        <div class="muted">Si continúas, aceptas nuestra Política de Privacidad y Términos y Condiciones</div>
        <div class="small">¿No tienes una cuenta? <a href="{{ route('registrarse') }}" class="primary-link">Regístrate</a></div>
        <div class="text-center mt-2">
            <a href="#" id="forgot-link" class="small primary-link" data-bs-toggle="modal" data-bs-target="#forgotModal">¿Olvidaste tu contraseña?</a>
        </div>
        
                <!-- Forgot password Bootstrap modal -->
                <div class="modal fade" id="forgotModal" tabindex="-1" aria-labelledby="forgotModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color: var(--palette-primary, #0d6efd); color: #fff;">
                                <h5 class="modal-title" id="forgotModalLabel">¿Olvidaste tu contraseña?</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body">
                                Acude a las oficinas del comedor con identificación personal para un reseteo de contraseña.
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
    </div>
</div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</html>