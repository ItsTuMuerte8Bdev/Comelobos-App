<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Comelobos Admin | Información personal</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        <div class="device" role="application">
            <main class="hero app-header">
                <div class="header-inner">
                    {{-- Cambiamos la ruta de regreso al panel administrativo --}}
                    <a href="{{ route('admin.cuenta') }}" class="back-btn"><i class="bi bi-arrow-left"></i></a>
                    <h2>Información personal</h2>
                </div>
            </main>

            <section class="px-3 py-3 content-section">
                <div class="padded">
                    <form id="infoForm">
                        <div class="field mb-3">
                            <label class="form-label">ID o Matrícula</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->matriculation_number }}" disabled>
                        </div>
                        <div class="field mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->first_name }}" disabled>
                        </div>
                        <div class="field mb-3">
                            <label class="form-label">Apellidos</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->last_name }} {{ Auth::user()->second_last_name }}" disabled>
                        </div>
                        <div class="field mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->phone }}" disabled>
                        </div>
                        <div class="field mb-3">
                            <label class="form-label">Correo Institucional</label>
                            <input type="email" class="form-control" value="{{ Auth::user()->email }}" disabled>
                        </div>

                        <div class="field mb-3">
                            <label class="form-label">Créditos</label>
                            <input type="text" class="form-control" value="{{ number_format(Auth::user()->credits, 2) }}" disabled>
                        </div>
                    </form>

                    {{-- Mensaje de éxito al cambiar contraseña --}}
                    @if(session('success_password'))
                        <div class="alert alert-success">{{ session('success_password') }}</div>
                    @endif

                    {{-- Partial para el cambio de contraseña (ajustado el scope si tu componente lo requiere) --}}
                    @include('partials.change_password', ['scope' => 'admin'])
                </div>
            </section>

            {{-- Cambiamos el navbar por el del perfil administrativo --}}
            @include('partials.admin_navbar', ['activeTab' => 'cuenta'])
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Modales de confirmación -->
        <div class="modal fade" id="saveModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <p>¿Estas seguro de querer actualizar tus datos?</p>
                        <div class="d-flex justify-content-center gap-2 mt-3">
                            <button type="button" class="btn btn-success" data-bs-dismiss="modal">Confirmar</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Regresar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="resetModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <p>Regresaran los datos a su estado antes de modificarlos, ¿Estas de acuerdo?</p>
                        <div class="d-flex justify-content-center gap-2 mt-3">
                            <button type="button" class="btn btn-success" data-bs-dismiss="modal">Confirmar</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Regresar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Save / reset modals only
            if(document.getElementById('saveBtn')) {
                document.getElementById('saveBtn').addEventListener('click', function(){
                    var modal = new bootstrap.Modal(document.getElementById('saveModal'));
                    modal.show();
                });
            }
            if(document.getElementById('resetBtn')) {
                document.getElementById('resetBtn').addEventListener('click', function(){
                    var modal = new bootstrap.Modal(document.getElementById('resetModal'));
                    modal.show();
                });
            }
        </script>
    </body>
</html>