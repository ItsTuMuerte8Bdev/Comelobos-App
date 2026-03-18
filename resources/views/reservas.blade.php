<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Comelobos | Reservas</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Bootstrap Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        <link href="{{ asset('css/index.css') }}" rel="stylesheet">
    </head>
    <body>
        <div class="device" role="application">
            <main class="hero" style="background:transparent;color:#053f56;padding:1.25rem 1rem">
                <div style="width:100%">
                    <h2 style="margin:0 0 .25rem;font-size:1.1rem">Reservas</h2>
                </div>
            </main>

            <section class="px-3 py-3" style="flex:1 1 auto;">
                <div class="reservas-card">
                    <div class="tabs text-center mb-4">
                        <button id="tab-activas" class="tab active">Activas</button>
                        <button id="tab-proximas" class="tab">Próximas</button>
                    </div>

                    <div id="reservas-empty" class="empty-state text-center">
                        <!-- ticket SVG -->
                        <svg width="160" height="120" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 8v8a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1h-3a1 1 0 0 1-1-1V4a1 1 0 0 0-1-1H6a1 1 0 0 0-1 1v2a1 1 0 0 1-1 1H3a1 1 0 0 0-1 1z" stroke="#111" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <h4 class="mt-3">No hay reservas</h4>
                        <div class="mt-3">
                            <a href="{{ url('/reservar') }}" class="btn btn-primary btn-reservar">Reservar</a>
                        </div>
                    </div>

                    <div id="reservas-list" class="d-none">
                        <!-- aquí se listarán reservas activas/proximas -->
                    </div>
                </div>
            </section>

            @include('partials.navbar', ['activeTab' => 'reservas'])
        </div>

        <style>
            .reservas-card{max-width:520px;margin:18px auto;padding:18px;background:#fff;border-radius:6px;box-shadow:0 2px 6px rgba(0,0,0,.06)}
            .tabs .tab{border:none;background:transparent;padding:.4rem .8rem;border-radius:20px;margin:0 .25rem;color:#053f56}
            .tabs .tab.active{background:#054e61;color:#fff}
            .empty-state svg{opacity:.95}
            .btn-reservar{background:#3fc0ff;border-color:#3fc0ff;color:#fff}
            .d-none{display:none}
        </style>

        <script>
            // Muestra modal de éxito si viene ?success=1
            (function(){
                function qs(name){return new URLSearchParams(window.location.search).get(name)}
                if(qs('success')==='1'){
                    const modal = document.createElement('div')
                    modal.innerHTML = `
                        <div class="modal-backdrop" style="position:fixed;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.35);z-index:9999">
                            <div style="background:#fff;padding:18px;border-radius:8px;max-width:420px;text-align:center;">
                                <h5>Reserva confirmada</h5>
                                <p>Tu reserva se realizó correctamente.</p>
                                <button id="aceptar-success" style="background:#1bb86a;border:none;color:#fff;padding:.5rem 1rem;border-radius:6px">Aceptar</button>
                            </div>
                        </div>`;
                    document.body.appendChild(modal)
                    document.getElementById('aceptar-success').addEventListener('click',()=>{modal.remove();history.replaceState(null,'',window.location.pathname)})
                }
            })()
        </script>
    </body>
</html>
