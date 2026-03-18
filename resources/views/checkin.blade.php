<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Comelobos | Check-In</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Bootstrap Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        <link href="{{ asset('css/index.css') }}" rel="stylesheet">
    </head>
    <body>
        <div class="device" role="application">
            <main class="hero" style="background:transparent;color:#053f56;padding:1.25rem 1rem">
                <div style="width:100%">
                    <h2 style="margin:0 0 .25rem;font-size:1.1rem">Check-In</h2>
                </div>
            </main>
            <section class="px-3 py-3" style="flex:1 1 auto;">
                <div class="reservas-card text-center">
                    <svg width="160" height="160" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 2v12" stroke="#111" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M14 2v12" stroke="#111" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M4 18h16" stroke="#111" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <h4 class="mt-3">Check-In</h4>
                    <p class="text-muted">Escanea tu código o selecciona una reserva para hacer check-in.</p>
                </div>
            </section>
            @include('partials.navbar', ['activeTab' => 'checkin'])
        </div>
    </body>
</html>
