<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Comelobos Admin | Información</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <style>
        /* 1. Reseteo ABSOLUTO */
        html, body { height: 100vh; width: 100%; overflow: hidden; background-color: #e9ecef; margin: 0 !important; padding: 0 !important; }
        
        /* 2. Contenedor Central Caja Fuerte */
        .device { height: calc(100vh - 1.5rem); max-width: 1200px; margin: 1.5rem auto 0 auto; display: flex; flex-direction: column; background-color: #f8f9fa; box-shadow: 0 0 25px rgba(0,0,0,0.1); overflow: hidden; position: relative; border-radius: 15px 15px 0 0; }
        .main-scroll-area { flex-grow: 1; overflow-y: auto; overflow-x: hidden; padding-bottom: 1rem; }
        
        /* 3. Navbar y Header */
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
                    <h3 class="mb-0 fw-bold">Información</h3>
                    <p class="mb-0 text-white-50">Tus datos personales</p>
                </div>
            </main>

            <section class="px-3 position-relative z-1" style="margin-top: 1rem;">
                <div class="container-sm px-0">
                    <div class="card shadow-sm border-0 rounded-3 mb-4">
                        <div class="card-body p-4">
                            
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold mb-1">ID o Matrícula</label>
                                <input type="text" class="form-control bg-light" value="{{ Auth::user()->matriculation_number }}" readonly disabled>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold mb-1">Nombre</label>
                                <input type="text" class="form-control bg-light" value="{{ Auth::user()->first_name }}" readonly disabled>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold mb-1">Apellidos</label>
                                <input type="text" class="form-control bg-light" value="{{ Auth::user()->last_name }} {{ Auth::user()->second_last_name }}" readonly disabled>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold mb-1">Teléfono</label>
                                <input type="text" class="form-control bg-light" value="{{ Auth::user()->phone }}" readonly disabled>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold mb-1">Correo Institucional</label>
                                <input type="text" class="form-control bg-light" value="{{ Auth::user()->email }}" readonly disabled>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label text-muted small fw-bold mb-1">Créditos</label>
                                <input type="text" class="form-control bg-light" value="{{ number_format(Auth::user()->credits, 2) }}" readonly disabled>
                            </div>

                            {{-- Botón para cambiar contraseña --}}
                            <button type="button" class="btn btn-dark w-100 fw-bold py-2" data-bs-toggle="modal" data-bs-target="#passwordModal">
                                Cambiar Contraseña
                            </button>

                        </div>
                    </div>
                </div>
            </section>
        </div>

        <div class="navbar-fixed-bottom">
            {{-- AQUÍ ESTÁ LA MAGIA: Forzamos el navbar del administrador --}}
            @include('partials.admin_navbar', ['activeTab' => 'cuenta'])
        </div>

    </div>
</body>
</html>