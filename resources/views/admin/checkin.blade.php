<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Comelobos Admin | Escáner Check-In</title>
    
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
        
        /* Estilos del Lector QR */
        #reader { width: 100%; border-radius: 15px; overflow: hidden; border: none !important; box-shadow: 0 4px 15px rgba(0,0,0,0.1); background: #000; }
        #reader video { object-fit: cover; }
    </style>
</head>
<body>
    <div class="device" role="application">
        
        <div class="main-scroll-area">
            
            <main class="header-hero text-center">
                <h3 class="mb-1 fw-bold">Escáner Check-In</h3>
                <p class="mb-0 text-white-50">Escanea el QR del alumno para entregar</p>
            </main>

            <section class="px-3 position-relative z-1 mt-4">
                <div class="container-sm px-0">

                    @if(session('success'))
                        <div class="alert alert-success shadow-sm border-0 rounded-3 mb-4 text-center">
                            <i class="bi bi-check-circle-fill fs-1 d-block mb-2 text-success"></i> 
                            <strong>{{ session('success') }}</strong>
                        </div>
                    @endif

                    <div id="error-alert" class="alert alert-danger shadow-sm border-0 rounded-3 mb-4 d-none">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> <span id="error-msg"></span>
                    </div>

                    {{-- Contenedor de la Cámara --}}
                    <div class="card shadow border-0 rounded-4 mb-4">
                        <div class="card-body p-3 text-center">
                            <div id="reader"></div>
                            <p class="text-muted small mt-3 mb-0">Apunta la cámara al código QR de la aplicación del cliente.</p>
                        </div>
                    </div>

                </div>
            </section>
        </div>

        <div class="navbar-fixed-bottom">
            @include('partials.admin_navbar', ['activeTab' => 'checkin'])
        </div>
    </div>

    {{-- ========================================================= --}}
    {{-- MODAL DE CONFIRMACIÓN (Oculto por defecto)                --}}
    {{-- ========================================================= --}}
    <div class="modal fade" id="confirmModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0 pb-0 bg-primary text-white rounded-top-4">
                    <h5 class="modal-title fw-bold w-100 text-center"><i class="bi bi-ticket-detailed me-2"></i>Validar Entrega</h5>
                </div>
                <div class="modal-body pt-4 pb-4 px-4">
                    
                    <div class="mb-3">
                        <label class="text-muted small fw-bold">Cliente:</label>
                        <h5 id="modal-nombre" class="fw-bold text-dark mb-0">--</h5>
                    </div>
                    
                    <div class="mb-3">
                        <label class="text-muted small fw-bold">Platillo Reservado:</label>
                        <h6 id="modal-menu" class="text-primary fw-bold mb-0">--</h6>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted small fw-bold">Horario de Consumo:</label>
                        <p id="modal-horario" class="mb-0 fw-bold">--</p>
                    </div>

                    <form action="{{ route('admin.checkin.consume') }}" method="POST">
                        @csrf
                        <input type="hidden" name="reservation_id" id="modal-res-id" value="">
                        
                        <div class="row g-2">
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-secondary w-100 fw-bold rounded-pill" onclick="reanudarCamara()">Cancelar</button>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn-success w-100 fw-bold rounded-pill shadow-sm">
                                    Entregar <i class="bi bi-check2 me-1"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- Librerías Necesarias --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    {{-- Librería del Lector QR --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script>
        const html5QrCode = new Html5Qrcode("reader");
        const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
        const errorAlert = document.getElementById('error-alert');
        const errorMsg = document.getElementById('error-msg');

        // Función que se ejecuta cuando la cámara detecta un QR
        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
            // 1. Pausamos la cámara para que no escanee dos veces
            html5QrCode.pause(true);
            errorAlert.classList.add('d-none');

            // 2. Vamos a la base de datos a preguntar por el QR
            fetch('{{ route("admin.checkin.info") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ qr_code: decodedText })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    // 3. Si existe, rellenamos los datos del Modal
                    const res = data.reservation;
                    document.getElementById('modal-nombre').innerText = res.user.first_name + ' ' + res.user.last_name + ' ' + (res.user.second_last_name || '');
                    document.getElementById('modal-menu').innerText = res.menu.platillo_principal;
                    
                    // Formatear la hora
                    const start = res.shift.start_time.substring(0, 5);
                    const end = res.shift.end_time.substring(0, 5);
                    document.getElementById('modal-horario').innerText = start + ' a ' + end + ' hrs.';
                    
                    document.getElementById('modal-res-id').value = res.id;
                    
                    // 4. Mostramos el modal
                    confirmModal.show();
                } else {
                    // Si el QR ya fue usado o no existe
                    errorMsg.innerText = data.message;
                    errorAlert.classList.remove('d-none');
                    // Esperamos 3 segundos y reanudamos la cámara
                    setTimeout(reanudarCamara, 3000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                reanudarCamara();
            });
        };

        function reanudarCamara() {
            confirmModal.hide();
            if (html5QrCode.getState() === 3) { // 3 = PAUSED
                html5QrCode.resume();
            }
        }

        // Configuración de la cámara (Usamos la trasera por defecto)
        const config = { 
          fps: 10, 
          qrbox: function(videoWidth, videoHeight) {
            // Calcula el 80% del lado más pequeño de la pantalla para que el cuadro siempre quepa perfecto
            let edgeSize = Math.min(videoWidth, videoHeight) * 0.8;
            return { width: edgeSize, height: edgeSize };
          }
        };
        html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback)
        .catch(err => {
            // Si el dispositivo no tiene cámara o el usuario denegó los permisos
            console.error('Error inicializando html5QrCode:', err);
            const errDetail = (err && (err.name || err.message)) ? ('<div class="small text-muted mt-2">' + (err.name ? err.name + ': ' : '') + (err.message || '') + '</div>') : '';
            document.getElementById('reader').innerHTML = '<div class="p-5 bg-light text-danger fw-bold"><i class="bi bi-camera-video-off fs-1 d-block mb-2"></i>Por favor, permite el acceso a la cámara.' + errDetail + '</div>';
        });
    </script>
</body>
</html>