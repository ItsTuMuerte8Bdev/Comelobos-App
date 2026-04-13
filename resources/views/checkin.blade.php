<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Comelobos | Check-In</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <style>
        /* Magia de App Nativa */
        html, body { height: 100%; overflow: hidden; background-color: #f8f9fa; }
        .device { height: 100%; display: flex; flex-direction: column; }
        .main-scroll-area { flex-grow: 1; overflow-y: auto; overflow-x: hidden; padding-bottom: 2rem; }
        .navbar-fixed-bottom { flex-shrink: 0; z-index: 1000; background-color: #ffffff; box-shadow: 0 -4px 15px rgba(0,0,0,0.05); }

        /* Estilos visuales */
        .header-hero { background: #003b5c; color: white; padding: 2rem 1.5rem 3rem 1.5rem; border-radius: 0 0 25px 25px; margin-bottom: -1.5rem; }
        .menu-thumbnail { width: 90px; height: 90px; object-fit: cover; border-radius: 10px; }
        .qr-image { width: 200px; height: 200px; }
        
        /* Forzar el color verde del botón SIEMPRE */
        .btn-qr-green {
            background-color: #198754 !important; /* Verde Bootstrap */
            border-color: #198754 !important;
            color: #ffffff !important;
        }
        .btn-qr-green:hover {
            background-color: #157347 !important; /* Verde un poco más oscuro al pasar el mouse */
        }
    </style>
</head>
<body>
    <div class="device" role="application">
        
        <div class="main-scroll-area">
            
            <main class="header-hero text-center">
                <h2 class="fw-bold mb-1">Check-In</h2>
                <p class="mb-0 text-white-50">Tus pases de comida para hoy</p>
            </main>

            <section class="px-3 position-relative z-1 mt-4">
                
                @if($reservas->isEmpty())
                    {{-- ESTADO VACÍO: NO HAY RESERVAS --}}
                    <div class="card shadow-sm border-0 rounded-4 text-center py-5 mt-4">
                        <svg width="120" height="120" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="mx-auto mb-3 opacity-50">
                            <path d="M10 2v12" stroke="#111" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M14 2v12" stroke="#111" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M4 18h16" stroke="#111" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <h5 class="fw-bold text-dark">Sin Reservaciones</h5>
                        <p class="text-muted small px-4">Aún no has reservado ningún menú o ya consumiste tus platillos de hoy.</p>
                        <div class="mt-2">
                            <a href="{{ route('inicio') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">Ver Menú del Día</a>
                        </div>
                    </div>
                @else
                    {{-- LISTA DE RESERVAS --}}
                    @foreach($reservas as $reserva)
                        <div class="card shadow-sm border-0 rounded-4 mb-3">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center mb-3">
                                    {{-- Miniatura de la comida --}}
                                    @if($reserva->menu->image_path)
                                        <img src="{{ $reserva->menu->image_path }}" class="menu-thumbnail shadow-sm me-3" alt="Platillo">
                                    @else
                                        <div class="menu-thumbnail bg-secondary d-flex align-items-center justify-content-center text-white me-3">
                                            <i class="bi bi-camera fs-3"></i>
                                        </div>
                                    @endif
                                    
                                    {{-- Info Textual --}}
                                    <div>
                                        <span class="badge {{ $reserva->menu->type == 'desayuno' ? 'bg-primary' : 'bg-warning text-dark' }} mb-1">
                                            {{ ucfirst($reserva->menu->type) }}
                                        </span>
                                        <h6 class="fw-bold mb-1 text-dark lh-sm">{{ $reserva->menu->platillo_principal }}</h6>
                                        <p class="small text-muted mb-0" style="line-height: 1.2;">
                                            <i class="bi bi-check2 text-success"></i> {{ $reserva->menu->entrada }}<br>
                                            <i class="bi bi-cup-straw text-info"></i> {{ $reserva->menu->bebida }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Botón Verde Fijo --}}
                                <button type="button" class="btn btn-qr-green w-100 fw-bold rounded-pill shadow-sm py-2" data-bs-toggle="modal" data-bs-target="#qrModal{{ $reserva->id }}">
                                    <i class="bi bi-qr-code-scan me-2"></i> Generar QR
                                </button>
                            </div>
                        </div>
                    @endforeach
                @endif

            </section>
        </div>

        <div class="navbar-fixed-bottom">
            @include('partials.navbar', ['activeTab' => 'checkin'])
        </div>

    </div> {{-- ========================================================= --}}
    {{-- LOS MODALS (Deber ir afuera de todo para que no se traben) --}}
    {{-- ========================================================= --}}
    @foreach($reservas as $reserva)
        <div class="modal fade" id="qrModal{{ $reserva->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold text-dark w-100 text-center">Pase de {{ ucfirst($reserva->menu->type) }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center pt-2 pb-4">
                        <p class="text-muted small mb-4">Presenta este código en la cafetería. <br> Horario: <strong>{{ \Carbon\Carbon::parse($reserva->shift->start_time)->format('H:i') }} a {{ \Carbon\Carbon::parse($reserva->shift->end_time)->format('H:i') }}</strong></p>
                        
                        {{-- Consumo de API para generar la imagen del QR --}}
                        <div class="bg-white p-3 rounded-4 shadow-sm d-inline-block border">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ $reserva->qr_code }}" class="qr-image" alt="Código QR">
                        </div>
                        
                        <p class="mt-3 mb-0 text-secondary fw-bold font-monospace small">{{ $reserva->folio }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>