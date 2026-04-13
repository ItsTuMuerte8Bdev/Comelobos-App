<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Comelobos | Mis Reservas</title>
    
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
        .menu-thumbnail { width: 80px; height: 80px; object-fit: cover; border-radius: 10px; }
        
        /* Efecto para tarjetas canceladas */
        .card-cancelled { opacity: 0.7; filter: grayscale(50%); }
    </style>
</head>
<body>
    <div class="device" role="application">
        
        <div class="main-scroll-area">
            
            <main class="header-hero text-center">
                <h2 class="fw-bold mb-1">Mis Reservas</h2>
                <p class="mb-0 text-white-50">Historial de tus movimientos</p>
            </main>

            <section class="px-3 position-relative z-1 mt-4">
                
                @if($reservas->isEmpty())
                    {{-- ESTADO VACÍO --}}
                    <div class="card shadow-sm border-0 rounded-4 text-center py-5 mt-4">
                        <svg width="120" height="120" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="mx-auto mb-3 opacity-50">
                            <path d="M2 8v8a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1h-3a1 1 0 0 1-1-1V4a1 1 0 0 0-1-1H6a1 1 0 0 0-1 1v2a1 1 0 0 1-1 1H3a1 1 0 0 0-1 1z" stroke="#111" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <h5 class="fw-bold text-dark">No hay reservas</h5>
                        <p class="text-muted small px-4">Aún no has realizado ninguna reservación en el sistema.</p>
                        <div class="mt-2">
                            <a href="{{ route('inicio') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">Ir al Menú</a>
                        </div>
                    </div>
                @else
                    {{-- LISTA DE HISTORIAL --}}
                    @foreach($reservas as $reserva)
                        <div class="card shadow-sm border-0 rounded-4 mb-3 {{ $reserva->status === 'cancelled' ? 'card-cancelled' : '' }}">
                            <div class="card-body p-3">
                                
                                <div class="d-flex align-items-center mb-3">
                                    {{-- Miniatura --}}
                                    @if($reserva->menu->image_path)
                                        <img src="{{ $reserva->menu->image_path }}" class="menu-thumbnail shadow-sm me-3" alt="Platillo">
                                    @else
                                        <div class="menu-thumbnail bg-secondary d-flex align-items-center justify-content-center text-white me-3">
                                            <i class="bi bi-camera fs-3"></i>
                                        </div>
                                    @endif
                                    
                                    {{-- Datos del Menú --}}
                                    <div>
                                        <span class="badge {{ $reserva->menu->type == 'desayuno' ? 'bg-primary' : 'bg-warning text-dark' }} mb-1">
                                            {{ ucfirst($reserva->menu->type) }}
                                        </span>
                                        <h6 class="fw-bold mb-1 text-dark lh-sm">{{ $reserva->menu->platillo_principal }}</h6>
                                        <p class="small text-muted mb-0" style="line-height: 1.3;">
                                            <i class="bi bi-calendar-event me-1"></i> {{ \Carbon\Carbon::parse($reserva->reservation_date)->format('d/m/Y') }}<br>
                                            <i class="bi bi-clock me-1"></i> {{ \Carbon\Carbon::parse($reserva->shift->start_time)->format('H:i') }} hrs.
                                        </p>
                                    </div>
                                </div>

                                {{-- BOTONES DE ESTADO --}}
                                @if($reserva->status === 'paid')
                                    <button class="btn btn-warning w-100 fw-bold rounded-pill shadow-sm text-dark btn-sm py-2" disabled style="opacity: 1;">
                                        <i class="bi bi-clock-history me-1"></i> Reservado (Pendiente)
                                    </button>
                                
                                @elseif($reserva->status === 'consumed')
                                    <button class="btn btn-success w-100 fw-bold rounded-pill shadow-sm text-white btn-sm py-2" disabled style="opacity: 1;">
                                        <i class="bi bi-check2-all me-1"></i> ¡Buen Provecho! (Consumido)
                                    </button>
                                
                                @elseif($reserva->status === 'cancelled')
                                    <button class="btn btn-secondary w-100 fw-bold rounded-pill shadow-sm text-white btn-sm py-2" disabled style="opacity: 1;">
                                        <i class="bi bi-x-circle me-1"></i> Cancelado
                                    </button>
                                @endif

                            </div>
                        </div>
                    @endforeach
                @endif

            </section>
        </div>

        <div class="navbar-fixed-bottom">
            @include('partials.navbar', ['activeTab' => 'reservas'])
        </div>

    </div>
</body>
</html>