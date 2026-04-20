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
        /* Fondo gris oscuro detrás de tu app */
        html, body { height: 100vh; width: 100%; overflow: hidden; background-color: #e9ecef; }
        
        /* Contenedor central (Ancho de Laptop) */
        .device { height: 100%; display: flex; flex-direction: column; background-color: #f8f9fa; box-shadow: 0 0 25px rgba(0,0,0,0.1); overflow: hidden; position: relative; }
        
        .main-scroll-area { flex-grow: 1; overflow-y: auto; overflow-x: hidden; padding-bottom: 1rem; }
        .navbar-fixed-bottom { flex-shrink: 0; z-index: 1000; background-color: #ffffff; box-shadow: 0 -4px 15px rgba(0,0,0,0.05); }

        /* CORRECCIÓN: Se aumentó el padding inferior y se eliminó el margin-bottom negativo */
        .header-hero { background: #003b5c; color: white; padding: 1rem 1.5rem 1.5rem 1.5rem; border-radius: 0 0 25px 25px; margin-bottom: 0; }
        
        /* Botones más delgados para ahorrar espacio */
        .account-btn { background-color: #003b5c; color: white; font-weight: 600; padding: 10px 12px; border-radius: 8px; transition: all 0.2s; border: none; text-align: left; font-size: 0.95rem; }
        .account-btn:hover { background-color: #002133; color: white; }
        
        .logout-btn { background-color: #900000; color: white; font-weight: 600; padding: 10px 12px; border-radius: 8px; transition: all 0.2s; border: none; text-align: center; font-size: 0.95rem; }
        .logout-btn:hover { background-color: #700000; color: white; }
    </style>
</head>
<body>
    <div class="device" role="application">
        
        <div class="main-scroll-area">
            <main class="header-hero d-flex align-items-center">
                <div>
                    <h3 class="mb-0 fw-bold">Mi Cuenta</h3>
                    <p class="mb-0 text-white-50">Panel de Administrador</p>
                </div>
            </main>

            {{-- Tarjeta blanca subida sobre el área azul --}}
            <section class="px-3 position-relative z-1" style="margin-top: 1rem;">
                <div class="container-sm px-0">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-body p-0">
                            
                            {{-- Cambiamos a gap-1 para juntar los botones --}}
                            <div class="d-flex flex-column gap-1">
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

        <div class="navbar-fixed-bottom">
            @include('partials.admin_navbar', ['activeTab' => 'cuenta'])
        </div>

    </div>
</body>
</html>