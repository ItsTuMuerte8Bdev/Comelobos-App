<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Comelobos | Ajustes</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        <link href="{{ asset('css/index.css') }}" rel="stylesheet">
        <style>
            .back-btn{background:#48b8e6;color:white;border-radius:10px;padding:8px 10px;display:inline-flex;align-items:center;justify-content:center;margin-right:auto}
            .settings-link{display:block;margin:16px 0;color:#28d1d8;font-weight:600;text-decoration:none}
            .note{margin-top:40px;text-align:center;color:var(--color-muted)}
        </style>
    </head>
    <body>
        <div class="device" role="application">
            <main class="hero app-header app-header-small">
                <div class="header-inner">
                    <a href="/cuenta" class="back-btn"><i class="bi bi-arrow-left"></i></a>
                    <h2>Ajustes</h2>
                </div>
            </main>

            <section class="px-3 py-3 content-section">
                <div style="padding:8px 12px">
                    <a href="#" class="settings-link" id="tycLink">Terminos y condiciones</a>
                    <a href="#" class="settings-link" id="policyLink">Política de privacidad</a>
                </div>
            </section>

            @include('partials.navbar', ['activeTab' => 'cuenta'])
        </div>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

                <!-- Modal para la nota -->
                <div class="modal fade" id="noteModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body text-center">
                                <p id="modalDocBody">Son enlaces a los documentos</p>
                                <div class="d-flex justify-content-center gap-2 mt-3">
                                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Ir al documento</button>
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Regresar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    function openDocModal(text){
                        document.getElementById('modalDocBody').innerText = text;
                        var modal = new bootstrap.Modal(document.getElementById('noteModal'));
                        modal.show();
                    }

                    document.getElementById('tycLink').addEventListener('click', function(e){
                        e.preventDefault();
                        openDocModal('Vas a abrir: Términos y condiciones');
                    });

                    document.getElementById('policyLink').addEventListener('click', function(e){
                        e.preventDefault();
                        openDocModal('Vas a abrir: Política de privacidad');
                    });
                </script>
    </body>
</html>
