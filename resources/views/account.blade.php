<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Comelobos | Cuenta</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        <div class="device" role="application">
            <main class="hero hero--lg">
                <div class="header-inner">
                    <h2 class="page-title">Cuenta</h2>
                </div>
            </main>

            <section class="px-3 py-4 flex-auto">
                <div class="d-flex flex-column align-items-center gap-3">
                    <a href="/informacion" class="btn account-btn w-75">Información personal</a>
                    <a href="/contactos" class="btn account-btn w-75">Contactos</a>
                    <a href="/ajustes" class="btn account-btn w-75">Ajustes</a>
                    <form method="POST" action="{{ route('logout') }}" class="w-75">
                        @csrf
                        <button type="submit" class="btn account-btn w-100">Cerrar sesión
                            
                        </button>
                    </form>
                </div>
            </section>

            @include('partials.navbar', ['activeTab' => 'cuenta'])
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
