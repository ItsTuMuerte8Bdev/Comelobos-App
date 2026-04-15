<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Comelobos Admin | Cuenta</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <style>
        /* Magia de App Nativa - A Prueba de Fallos */
        html, body { height: 100vh; width: 100%; margin: 0; padding: 0; overflow: hidden; background-color: #f8f9fa; }
        .device { display: flex; flex-direction: column; height: 100%; }
        
        /* El área principal toma solo el espacio sobrante y scrollea si la pantalla es enana */
        .main-fixed-area { flex: 1; overflow-y: auto; overflow-x: hidden; padding-bottom: 2rem; }
        
        /* LÍNEA DEL NAVBAR - Inamovible */
        .navbar-fixed-bottom { flex-shrink: 0; background-color: #ffffff; border-top: 1px solid #dee2e6; z-index: 1000; }

        /* Header más compacto para ahorrar espacio */
        .header-hero { background: #003b5c; color: white; padding: 1.5rem 1.5rem 1.5rem 1.5rem; border-radius: 0 0 25px 25px; margin-bottom: 0; }
        
        /* Botones limpios */
        .account-btn { background-color: #003b5c; color: white; font-weight: 600; padding: 12px; border-radius: 8px; transition: all 0.2s; border: none; text-align: left; font-size: 0.95rem; }
        .account-btn:hover { background-color: #002133; color: white; }
        
        .logout-btn { background-color: #900000; color: white; font-weight: 600; padding: 12px; border-radius: 8px; transition: all 0.2s; border: none; text-align: center; font-size: 0.95rem; }
        .logout-btn:hover { background-color: #700000; color: white; }
    </style>
</head>
<body>
    <div class="device" role="application">
        
        <div class="main-fixed-area">
            
            {{-- HEADER AZUL --}}
            <main class="header-hero d-flex align-items-center">
                <div>
                    <h3 class="mb-0 fw-bold">Mi Cuenta</h3>
                    <p class="mb-0 text-white-50 small">Panel de Administrador</p>
                </div>
            </main>

            {{-- MARGEN NEGATIVO AQUÍ para superponer la tarjeta --}}
            <section class="px-3 position-relative z-1" style="margin-top: 0rem;">
                <div class="container-sm px-0">

                    <div class="card shadow-sm border-0 rounded-4">
                        {{-- Redujimos el padding a p-3 para ahorrar espacio --}}
                        <div class="card-body p-3 pt-0">
                            
                            {{-- Agrupamos los botones directamente (Se eliminó el título) --}}
                            <div class="d-flex flex-column gap-2">
                                <a href="{{ route('admin.cuenta.informacion') }}" class="btn account-btn w-100 shadow-sm">
                                    <i class="bi bi-person-lines-fill me-2"></i> Información personal
                                </a>
                                
                                <a href="{{ route('admin.cuenta.ajustes') }}" class="btn account-btn w-100 shadow-sm">
                                    <i class="bi bi-gear-fill me-2"></i> Ajustes
                                </a>
                                
                                <a href="{{ route('admin.asignacion_roles') }}" class="btn account-btn w-100 shadow-sm">
                                    <i class="bi bi-people-fill me-2"></i> Asignación de roles
                                </a>
                                
                                <a href="{{ route('admin.cuenta.reporte') }}" class="btn account-btn w-100 shadow-sm">
                                    <i class="bi bi-file-earmark-bar-graph-fill me-2"></i> Reporte de movimientos
                                </a>

                                <hr class="text-secondary my-2 opacity-25">

                                <form method="POST" action="{{ route('logout') }}" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn logout-btn w-100 shadow-sm">
                                        <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>

                </div>
            </section>

        </div>

        {{-- NAVBAR ESTÁTICO --}}
        <div class="navbar-fixed-bottom">
            @include('partials.admin_navbar', ['activeTab' => 'cuenta'])
        </div>

    </div>
</body>
</html>