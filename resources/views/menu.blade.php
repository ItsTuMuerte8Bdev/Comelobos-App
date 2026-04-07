<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Comelobos | Menú</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        <div class="device" role="application">
            <main class="hero hero--sm">
                <div class="header-inner">
                    <h2 class="page-title">Menú</h2>
                </div>
            </main>

            <section class="px-3 py-3 flex-auto">
                <div class="menu-list">
                    <h6 class="text-danger">Desayuno del día (De 9:00 a 13:00)</h6>
                    <div class="menu-card d-flex align-items-center mb-4">
                        <div class="menu-text">Huevo a la mexicana con porción de fruta y jugo</div>
                        <img src="https://via.placeholder.com/150x100?text=Desayuno" alt="Desayuno" class="menu-thumb">
                    </div>

                    <h6 class="text-danger">Comida del día (De 13:00 a 17:00)</h6>
                    <div class="menu-card d-flex align-items-center mb-4">
                        <div class="menu-text">Sopa de fideos con pollo en salsa verde y agua de sabor</div>
                        <img src="https://via.placeholder.com/150x100?text=Comida" alt="Comida" class="menu-thumb">
                    </div>
                </div>
            </section>

            @include('partials.navbar', ['activeTab' => 'menu'])
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
