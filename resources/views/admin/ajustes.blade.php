<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Comelobos Admin | Ajustes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <style>
        /* La Caja Fuerte CSS (Igual que Reportes y Mi Cuenta) */
        html, body { height: 100vh; width: 100%; overflow: hidden; background-color: #e9ecef; margin: 0 !important; padding: 0 !important; }
        .device { height: calc(100vh - 1.5rem); max-width: 1200px; margin: 1.5rem auto 0 auto; display: flex; flex-direction: column; background-color: #f8f9fa; box-shadow: 0 0 25px rgba(0,0,0,0.1); overflow: hidden; position: relative; border-radius: 15px 15px 0 0; }
        .main-scroll-area { flex-grow: 1; overflow-y: auto; overflow-x: hidden; padding-bottom: 1rem; }
        .navbar-fixed-bottom { flex-shrink: 0; z-index: 1000; background-color: #ffffff; box-shadow: 0 -4px 15px rgba(0,0,0,0.05); }

        .header-hero { background: #003b5c; color: white; padding: 1.5rem 1.5rem 2rem 1.5rem; border-radius: 0 0 25px 25px; margin-bottom: 0; }
    </style>
</head>
<body>
    <div class="device" role="application">
        <div class="main-scroll-area">
            
            <main class="header-hero d-flex align-items-center">
                <a href="{{ route('admin.cuenta') }}" class="text-white me-3 fs-4"><i class="bi bi-arrow-left"></i></a>
                <div>
                    <h3 class="mb-0 fw-bold">Ajustes</h3>
                    <p class="mb-0 text-white-50">Configuración global del sistema</p>
                </div>
            </main>

            <section class="px-3 position-relative z-1" style="margin-top: 1rem;">
                <div class="container-sm px-0">

                    {{-- Alertas de Éxito --}}
                    @if(session('success'))
                        <div class="alert alert-success shadow-sm border-0 rounded-3 mb-4">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    <div class="card shadow-sm border-0 rounded-3 mb-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">Aforo de la Cafetería</h5>
                            
                            {{-- FORMULARIO UNIFICADO --}}
                            <form action="{{ route('admin.cuenta.ajustes.store') }}" method="POST">
                                @csrf
                                
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-muted small mb-1">Capacidad máxima para Desayuno</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-secondary"><i class="bi bi-sunrise"></i></span>
                                        <input type="number" name="aforo_desayuno" class="form-control py-2" value="{{ $aforoDesayuno }}" min="1" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold text-muted small mb-1">Capacidad máxima para Comida</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-secondary"><i class="bi bi-sun"></i></span>
                                        <input type="number" name="aforo_comida" class="form-control py-2" value="{{ $aforoComida }}" min="1" required>
                                    </div>
                                </div>

                                <div class="d-flex gap-2 mt-4">
                                    <a href="{{ route('admin.cuenta.ajustes') }}" class="btn btn-outline-secondary w-50 fw-bold py-2">Deshacer</a>
                                    <button type="submit" class="btn btn-dark w-50 fw-bold py-2">Guardar cambios</button>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </section>
        </div>

        <div class="navbar-fixed-bottom">
            @include('partials.admin_navbar', ['activeTab' => 'cuenta'])
        </div>

    </div>
</body>
</html>