<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Comelobos Admin | Roles</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <style>
        /* Magia de App Nativa (Layout Congelado) */
        /* Permitimos scroll para evitar problemas de stacking context con modales */
        html, body { height: 100%; overflow: auto; background-color: #f8f9fa; }
        .device { height: 100%; display: flex; flex-direction: column; }
        .main-scroll-area { flex-grow: 1; overflow-y: auto; overflow-x: hidden; padding-bottom: 2rem; }
        .navbar-fixed-bottom { flex-shrink: 0; z-index: 1000; background-color: #ffffff; box-shadow: 0 -4px 15px rgba(0,0,0,0.05); }

        /* Estilos visuales */
        .header-hero { background: #003b5c; color: white; padding: 2rem 1.5rem 3rem 1.5rem; border-radius: 0 0 25px 25px; margin-bottom: -1.5rem; }
    </style>
</head>
<body>
    <div class="device" role="application">
        
        <div class="main-scroll-area">
            
            {{-- HEADER AZUL --}}
            <main class="header-hero d-flex align-items-center">
                <a href="{{ route('admin.cuenta') ?? '#' }}" class="text-white me-3 fs-4"><i class="bi bi-arrow-left"></i></a>
                <h3 class="mb-0 fw-bold">Asignación de Roles</h3>
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

                    {{-- 1. FORMULARIO DE BÚSQUEDA --}}
                    <div class="card shadow-sm border-0 rounded-4 mb-4">
                        <div class="card-body p-4">
                            <form action="{{ route('admin.asignacion_roles') }}" method="GET">
                                <label class="form-label fw-bold text-dark small mb-2">Buscar Matrícula</label>
                                <div class="input-group">
                                    <input type="text" name="matricula" class="form-control bg-light border-secondary" placeholder="Ej. 202300001" value="{{ request('matricula') }}" required>
                                    <button type="submit" class="btn btn-dark px-4 fw-bold shadow-sm">Buscar</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- 2. RESULTADOS DEL USUARIO --}}
                    @if(isset($usuario))
                        <div class="card shadow border-0 rounded-4 border-top border-primary border-4 mb-4">
                            <div class="card-body p-4">
                                
                                <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">Información del Usuario</h5>
                                
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Nombre Completo</label>
                                    <input type="text" class="form-control fw-bold" value="{{ $usuario->first_name }} {{ $usuario->last_name }} {{ $usuario->second_last_name }}" readonly disabled>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Correo Institucional</label>
                                    <input type="text" class="form-control" value="{{ $usuario->email }}" readonly disabled>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label text-muted small mb-1">Teléfono</label>
                                    <input type="text" class="form-control" value="{{ $usuario->phone }}" readonly disabled>
                                </div>

                                <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">Gestión de Acceso</h5>

                                <form action="{{ route('admin.update_role', $usuario->id) }}" method="POST" id="form-rol">
                                    @csrf
                                    <label class="form-label fw-bold text-dark small mb-2">Selecciona el nuevo rol:</label>
                                    
                                    <select name="role" id="rol_select" class="form-select mb-4 py-2 border-primary fw-bold" required>
                                        <option value="cliente" {{ $usuario->role === 'cliente' ? 'selected' : '' }}>Cliente</option>
                                        <option value="administrativo" {{ $usuario->role === 'administrativo' ? 'selected' : '' }}>Administrativo</option>
                                    </select>

                                    <button type="button" class="btn btn-warning w-100 fw-bold rounded-pill py-2 shadow-sm text-white" id="btn-open-role-modal" data-bs-toggle="modal" data-bs-target="#confirmRoleModal">
                                        <i class="bi bi-person-fill-gear me-2"></i> Aplicar Cambios
                                    </button>
                                </form>
                                

                                {{-- Botón para restablecer contraseña (usa modal de confirmación) --}}
                                <div class="mt-3">
                                    <button type="button" class="btn btn-danger w-100 fw-bold rounded-pill py-2 shadow-sm" id="btn-reset-password" data-bs-toggle="modal" data-bs-target="#confirmResetModal">
                                        <i class="bi bi-arrow-counterclockwise me-2"></i> Reestablecimiento de contraseña
                                    </button>
                                </div>

                                {{-- Formulario oculto que realizará la petición POST para resetear la contraseña --}}
                                <form id="form-reset-password" action="{{ route('admin.reset_password', $usuario->id) }}" method="POST" style="display:none;">
                                    @csrf
                                </form>

                                <!-- Modales movidos al final del documento para evitar conflictos de stacking context -->

                                <script>
                                    // Usar triggers nativos de Bootstrap para abrir los modales.
                                    // Sólo necesitamos enviar los formularios cuando se confirme.
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const btnConfirmReset = document.getElementById('confirm-reset');
                                        if (btnConfirmReset) {
                                            btnConfirmReset.addEventListener('click', function() {
                                                // Enviar el formulario oculto que realiza el POST
                                                document.getElementById('form-reset-password').submit();
                                            });
                                        }
                                    });
                                </script>
                            </div>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const confirmRoleText = document.getElementById('confirmRoleText');
                                const rolSelect = document.getElementById('rol_select');
                                const nombreCompleto = "{{ $usuario->first_name }} {{ $usuario->last_name }} {{ $usuario->second_last_name }}";

                                // Actualizar texto del modal cuando se abra (via data-bs-target)
                                const btnOpen = document.getElementById('btn-open-role-modal');
                                if (btnOpen) {
                                    btnOpen.addEventListener('click', function() {
                                        const nuevoRol = rolSelect.options[rolSelect.selectedIndex].text;
                                        confirmRoleText.textContent = `¿Estás seguro de que ${nombreCompleto} ahora será ${nuevoRol}?`;
                                    });
                                }

                                // Enviar formulario cuando se confirme el cambio de rol
                                const btnConfirm = document.getElementById('confirm-role-btn');
                                if (btnConfirm) {
                                    btnConfirm.addEventListener('click', function() {
                                        document.getElementById('form-rol').submit();
                                    });
                                }
                            });
                        </script>
                    @endif

                </div>
            </section>
        </div>

        <div class="navbar-fixed-bottom">
            {{-- ⚠️ IMPORTANTE: Ajusta 'partials.admin_navbar' si el archivo de tu menú inferior de admin se llama diferente --}}
            @include('partials.admin_navbar', ['activeTab' => 'cuenta'])
        </div>

    </div>
</div>
                                <!-- Modal para confirmar cambio de rol -->
                                <div class="modal fade" id="confirmRoleModal" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirmar cambio de rol</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p id="confirmRoleText">¿Estás seguro de aplicar el cambio de rol?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="button" class="btn btn-warning text-white" id="confirm-role-btn" data-bs-dismiss="modal">Confirmar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal de confirmación -->
                                <div class="modal fade" id="confirmResetModal" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirmar restablecimiento</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>¿Deseas restablecer la contraseña de este usuario? La nueva contraseña será su número de teléfono registrado.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="button" class="btn btn-danger" id="confirm-reset" data-bs-dismiss="modal">Confirmar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>