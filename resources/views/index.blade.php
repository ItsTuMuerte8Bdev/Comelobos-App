<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title>Comelobos | Inicio</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Bootstrap Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <style>
            /* Aplicar fondo azul solo en la página principal y centrar su contenido */
            .hero{background:var(--color-primary);color:var(--color-primary-contrast)}
            .home-hero{display:flex;justify-content:center;text-align:center}
            .home-hero h1{margin:0}
        </style>
        <!-- Fallback sin Vite: usar archivos en public/ con asset() -->
        <script src="{{ asset('js/app.js') }}" defer></script>
    </head>
    <body>
        <div class="device" role="application">
            <main class="hero home-hero">
                <div>
                    <h1>¡Hola {{ $nombreUsuario }}!</h1>
                    <p>¿Se te antoja algo?</p>
                </div>
            </main>
 
            {{-- Incluir partial Blade y pasar la pestaña activa --}}
            @include('partials.navbar', ['activeTab' => 'home'])
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Si no has generado assets con Vite, `public/js/app.js` puede no existir (404 en navegador). -->
    </body>
</html>
