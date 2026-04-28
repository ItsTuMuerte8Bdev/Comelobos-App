<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Comelobos Admin | Créditos</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <style>
        /* Magia de App Nativa */
        html, body { height: 100%; overflow: hidden; background-color: #f8f9fa; }
        .device { height: 100%; display: flex; flex-direction: column; }
        .main-scroll-area { flex-grow: 1; overflow-y: auto; overflow-x: hidden; padding-bottom: 2rem; }
        .navbar-fixed-bottom { flex-shrink: 0; z-index: 1000; background-color: #ffffff; box-shadow: 0 -4px 15px rgba(0,0,0,0.05); }

        .header-hero { background: #003b5c; color: white; padding: 2rem 1.5rem 3rem 1.5rem; border-radius: 0 0 25px 25px; margin-bottom: -1.5rem; }
    </style>
</head>
<body>
    <div class="device" role="application">
        
        <div class="main-scroll-area">
            
            <main class="header-hero d-flex align-items-center">
                <h3 class="mb-0 fw-bold">Agregar créditos</h3>
            </main>

            <section class="px-3 position-relative z-1 mt-4">
                <div class="container-sm px-0">

                    {{-- Alertas --}}
                    @if($errors->any())
                        <div class="alert alert-danger shadow-sm border-0 rounded-3 mb-4">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $errors->first() }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success shadow-sm border-0 rounded-3 mb-4">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    {{-- 1. BÚSQUEDA --}}
                    <div class="card shadow-sm border-0 rounded-4 mb-4">
                        <div class="card-body p-4">
                            <form action="{{ route('admin.creditos') }}" method="GET">
                                <label class="form-label fw-bold text-dark small mb-2">Buscar por Matrícula</label>
                                <input type="text" name="matricula" id="input_matricula" class="form-control bg-light border-secondary mb-3" placeholder="Ej. 202300001" value="{{ request('matricula') }}" required>
                                
                                <div class="row g-2">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-dark w-100 fw-bold shadow-sm">Buscar</button>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{ route('admin.creditos') }}" class="btn btn-outline-dark w-100 fw-bold">Borrar</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- 2. RESULTADOS Y DEPÓSITO --}}
                    @if(isset($usuario))
                        <div class="card shadow border-0 rounded-4 border-top border-warning border-4 mb-4">
                            <div class="card-body p-4">
                                
                                <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">Datos del Cliente</h5>
                                
                                <div class="mb-2">
                                    <label class="form-label text-muted small mb-1">Nombre</label>
                                    <input type="text" class="form-control" value="{{ $usuario->first_name }}" readonly disabled>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Apellidos</label>
                                    <input type="text" class="form-control" value="{{ $usuario->last_name }} {{ $usuario->second_last_name }}" readonly disabled>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label text-muted small mb-1 fw-bold text-dark">Créditos Disponibles</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-warning text-dark border-warning"><i class="bi bi-coin"></i></span>
                                        <input type="text" class="form-control fw-bold border-warning" value="{{ number_format($usuario->credits, 2) }}" readonly disabled>
                                    </div>
                                </div>

                                <h5 class="fw-bold mb-3 text-dark border-bottom pb-2 mt-4">Transacción</h5>

                                <form action="{{ route('admin.creditos.add') }}" method="POST" id="form-deposito">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $usuario->id }}">

                                    <label class="form-label fw-bold text-dark small mb-2">Créditos a Depositar</label>
                                    <input type="number" name="amount" id="input_monto" class="form-control form-control-lg mb-4 border-primary" placeholder="Ej. 250" min="1" required>

                                    <div class="row g-2">
                                        <div class="col-6">
                                            <button type="button" class="btn btn-outline-secondary w-100 fw-bold" onclick="document.getElementById('input_monto').value = ''">Borrar</button>
                                        </div>
                                        <div class="col-6">
                                            <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm" onclick="return confirmarDeposito(event)">
                                                <i class="bi bi-plus-circle me-1"></i> Agregar
                                            </button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>

                        {{-- Script de Confirmación Doble --}}
                        <script>
                            function confirmarDeposito(event) {
                                const monto = document.getElementById('input_monto').value;
                                const nombre = "{{ $usuario->first_name }} {{ $usuario->last_name }} {{ $usuario->second_last_name }}";
                                if (!monto || monto <= 0) {
                                    return true; // deja que el navegador maneje la validación
                                }

                                const mensaje = `¿Estás seguro de que deseas depositar ${monto} créditos a la cuenta de ${nombre}?`;
                                event.preventDefault();
                                // Usar modal global para confirmar (con fallback si no está definido)
                                if (typeof window.showConfirmModal === 'function') {
                                    window.showConfirmModal(mensaje, 'Confirmar depósito').then(function(ok){ if (ok) event.target.closest('form').submit(); });
                                } else {
                                    if (confirm(mensaje)) event.target.closest('form').submit();
                                }
                                return false;
                            }
                        </script>
                    @endif

                </div>
            </section>
        </div>

        <div class="navbar-fixed-bottom">
            {{-- Asegúrate de que el nombre del parcial coincida con tu barra de administrador --}}
            @include('partials.admin_navbar', ['activeTab' => 'creditos'])
        </div>

    </div>
        @include('partials.confirm_modal')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="{{ asset('js/global_modals.js') }}"></script>
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
    </html>