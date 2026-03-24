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
        <link href="{{ asset('css/index.css') }}" rel="stylesheet">
    </head>
    <body>
        <div class="device" role="application">
            <main class="hero">
                <div>
                    <h1>¡Hola {{ $nombreUsuario }}!</h1>
                    <p>¿Se te antoja algo?</p>
                    <p>Menú de hoy: {{ $menuDelDia->description }} a solo ${{ $menuDelDia->price }}</p>
                </div>
            </main>
 
            {{-- Incluir partial Blade y pasar la pestaña activa --}}
            {{-- @include('partials.navbar', ['activeTab' => 'home']) --}}
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
