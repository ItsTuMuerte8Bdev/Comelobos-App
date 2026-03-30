<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Comelobos | Información personal</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        <link href="{{ asset('css/index.css') }}" rel="stylesheet">
        <style>
            .back-btn{background:#48b8e6;color:white;border-radius:10px;padding:8px 10px;display:inline-flex;align-items:center;justify-content:center;margin-right:auto}
            .field{margin:10px 0}
            .placeholder{background:#bdbdbd;height:36px;border-radius:3px}
            .field a{color:#05bdb6;text-decoration:none;font-weight:600}
            .note{margin-top:16px;color:var(--color-muted)}
        </style>
    </head>
    <body>
        <div class="device" role="application">
            <main style="padding-top: 16px;" class="hero app-header">
                <div class="header-inner">
                    <a href="/account" class="back-btn"><i class="bi bi-arrow-left"></i></a>
                    <h2>Información personal</h2>
                </div>
            </main>

            <section class="px-3 py-3 content-section">
                <div style="padding:8px 12px">
                    <form>
                        <div class="field mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" value="" disabled>
                        </div>
                        <div class="field mb-3">
                            <label class="form-label">Apellidos</label>
                            <input type="text" class="form-control" value="" disabled>
                        </div>
                        <div class="field mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="" disabled>
                        </div>
                        <div class="field mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" class="form-control" value="" disabled>
                        </div>
                        <div class="field mb-3">
                            <label class="form-label">Escuela, Facultad o Dependencia</label>
                            <input type="text" class="form-control" value="" disabled>
                        </div>
                        <div class="field mb-3">
                            <label class="form-label">ID o Matrícula</label>
                            <input type="text" class="form-control" value="" disabled>
                        </div>
                        <div class="field mb-3">
                            <label class="form-label">Puesto</label>
                            <input type="text" class="form-control" value="" disabled>
                        </div>

                        <p class="note">Nota: Debe rellenarse con la información de registro y no puede cambiar nada el usuario</p>

                        <div class="d-flex justify-content-center gap-3 mt-4">
                            <button type="button" id="saveBtn" class="btn btn-success">Guardar Cambios</button>
                            <button type="button" id="resetBtn" class="btn btn-danger">Anular cambios</button>
                        </div>
                    </form>
                </div>
            </section>

            @include('partials.navbar', ['activeTab' => 'cuenta'])
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
                        document.getElementById('saveBtn').addEventListener('click', function(){
                                var modal = new bootstrap.Modal(document.getElementById('saveModal'));
                                modal.show();
                        });
                        document.getElementById('resetBtn').addEventListener('click', function(){
                                var modal = new bootstrap.Modal(document.getElementById('resetModal'));
                                modal.show();
                        });
                </script>
    </body>
</html>
