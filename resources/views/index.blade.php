<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Comelobos | Inicio</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <style>
        html, body { height: 100%; overflow: hidden; background-color: #f8f9fa; }
        .device { height: 100%; display: flex; flex-direction: column; }
        .main-scroll-area { flex-grow: 1; overflow-y: auto; overflow-x: hidden; padding-bottom: 2rem; }
        .navbar-fixed-bottom { flex-shrink: 0; z-index: 1000; background-color: #ffffff; box-shadow: 0 -4px 15px rgba(0,0,0,0.05); }

        .home-hero { background: #003b5c; color: white; padding: 2rem 1.5rem 4rem 1.5rem; border-radius: 0 0 25px 25px; margin-bottom: -2rem; }
        .credits-badge { background-color: #ffc107; color: #000; font-weight: bold; padding: 6px 14px; border-radius: 20px; font-size: 0.95rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .menu-image { height: 260px; object-fit: cover; border-radius: 12px 12px 0 0; width: 100%; }
        
        .times-scroll { max-height: 135px; overflow-y: auto; padding-right: 5px; }
        .times-scroll::-webkit-scrollbar { width: 5px; }
        .times-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

        .shift-radio { display: none; }
        .shift-label { border: 2px solid #e0e0e0; border-radius: 8px; padding: 8px 12px; font-size: 0.9rem; font-weight: 600; color: #444; cursor: pointer; transition: all 0.2s ease; background: white; text-align: center; flex: 1 1 calc(50% - 8px); }
        .shift-radio:checked + .shift-label { background-color: #003b5c; border-color: #003b5c; color: white; }
        .shift-radio:disabled + .shift-label { background-color: #f8f9fa; border-color: #dee2e6; color: #adb5bd; cursor: not-allowed; text-decoration: line-through; }

        /* NUEVO: Botón de Cancelación Rojo Suave */
        .btn-soft-red {
            background-color: #ffcdd2;
            color: #212529;
            border: none;
            transition: all 0.2s;
        }
        .btn-soft-red:hover {
            background-color: #ef9a9a;
            color: #000;
        }
    </style>
</head>
<body>
    <div class="device" role="application">
        
        <div class="main-scroll-area">
            
            <main class="home-hero d-flex justify-content-between align-items-start">
                <div>
                    <h2 class="fw-bold mb-1">¡Hola {{ Auth::user()->first_name }}!</h2>
                    <p class="mb-0 text-white-50">¿Qué se te antoja hoy?</p>
                @include('partials.confirm_modal')
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
                <script src="{{ asset('js/global_modals.js') }}"></script>
                <script src="{{ asset('js/app.js') }}"></script>
                </div>
                <div class="credits-badge">
                    <i class="bi bi-coin me-1"></i> {{ number_format(Auth::user()->credits, 2) }}
                </div>
            </main>

            <section class="px-3 position-relative z-1">
                
                @if($errors->any())
                    <div class="alert alert-danger shadow-sm border-0 rounded-3 mb-4 mt-3">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $errors->first() }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success shadow-sm border-0 rounded-3 mb-4 mt-3">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    </div>
                @endif

                {{-- ===================================== --}}
                {{-- SECCIÓN DESAYUNO                      --}}
                {{-- ===================================== --}}
                @php $dataDesayuno = $menuDesayuno ?? ($reservaDesayuno ? $reservaDesayuno->menu : null); @endphp
                
                @if($dataDesayuno)
                    <div class="card shadow-sm border-0 rounded-4 mb-4 mt-3">
                        @if($dataDesayuno->image_path)
                            <img src="{{ asset('storage/' . $dataDesayuno->image_path) }}" class="menu-image" alt="Desayuno">
                        @else
                            <div class="menu-image bg-secondary d-flex align-items-center justify-content-center text-white">
                                <i class="bi bi-camera fs-1"></i>
                            </div>
                        @endif
                        
                        <div class="card-body">
                            <span class="badge bg-primary mb-2">Desayuno del Día</span>
                            <h4 class="card-title fw-bold text-dark mb-1">{{ $dataDesayuno->platillo_principal }}</h4>
                            
                            <p class="text-muted small mb-2">
                                <i class="bi bi-check2-circle text-success me-1"></i> {{ $dataDesayuno->entrada ?? 'Sin entrada' }}<br>
                                <i class="bi bi-cup-straw text-info me-1"></i> {{ $dataDesayuno->bebida ?? 'Sin bebida' }}
                            </p>

                            @if($dataDesayuno->description)
                                <div class="p-2 mb-3 bg-light rounded text-secondary small">
                                    <i class="bi bi-info-circle me-1"></i> {{ $dataDesayuno->description }}
                                </div>
                            @endif

                            @if($reservaDesayuno)
                                @if($reservaDesayuno->status === 'consumed')
                                    {{-- ESTADO: CONSUMIDO --}}
                                    <div class="mt-3 text-center">
                                        <p class="small text-dark mb-2">
                                            <i class="bi bi-clock fw-bold"></i> Consumido a las: <strong>{{ \Carbon\Carbon::parse($reservaDesayuno->updated_at)->format('H:i') }}</strong>
                                        </p>
                                        <button class="btn btn-success w-100 fw-bold rounded-pill py-2 shadow-sm text-white" disabled style="opacity: 1;">
                                            <i class="bi bi-check2-all me-1"></i> ¡Buen Provecho! (Consumido)
                                        </button>
                                    </div>
                                @else
                                    {{-- ESTADO: RESERVADO --}}
                                    <div class="mt-3 text-center">
                                        <p class="small text-dark mb-2">
                                            <i class="bi bi-clock fw-bold"></i> Horario reservado: <strong>{{ \Carbon\Carbon::parse($reservaDesayuno->shift->start_time)->format('H:i') }}</strong>
                                        </p>
                                        
                                        <button class="btn btn-warning w-100 fw-bold rounded-pill py-2 shadow-sm text-dark mb-2" disabled style="opacity: 1;">
                                            <i class="bi bi-check-circle-fill me-1"></i> Reservado
                                        </button>

                                        <form action="{{ route('api.reserva.cancel', $reservaDesayuno->id) }}" method="POST" class="needs-confirm" data-confirm-message="¿Estás seguro que deseas cancelar tu reserva? Te devolveremos tus {{ number_format($reservaDesayuno->menu->price, 2) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-soft-red w-100 fw-bold rounded-pill py-2 shadow-sm">
                                                <i class="bi bi-x-circle me-1"></i> Cancelar mi reserva
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @else
                                {{-- VISTA DE COMPRA --}}
                                <form action="{{ route('api.reserva.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="menu_id" value="{{ $dataDesayuno->id }}">
                                    <input type="hidden" name="reservation_date" value="{{ $hoy }}">

                                    <label class="form-label fw-bold text-dark small mb-2 text-center d-block">Elige tu horario:</label>
                                    
                                    <div class="times-scroll d-flex flex-wrap gap-2 mb-3">
                                        @foreach($turnosDesayuno as $turno)
                                            <input type="radio" class="shift-radio" name="shift_id" value="{{ $turno->id }}" id="turno_{{ $turno->id }}" {{ $turno->available_capacity <= 0 ? 'disabled' : '' }} required>
                                            <label class="shift-label shadow-sm" for="turno_{{ $turno->id }}">
                                                {{ \Carbon\Carbon::parse($turno->start_time)->format('H:i') }}
                                            </label>
                                        @endforeach
                                    </div>

                                    <button type="submit" class="btn btn-dark w-100 fw-bold rounded-pill py-2 shadow-sm">
                                        Reservar por ${{ $dataDesayuno->price }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endif


                {{-- ===================================== --}}
                {{-- SECCIÓN COMIDA                        --}}
                {{-- ===================================== --}}
                @php $dataComida = $menuComida ?? ($reservaComida ? $reservaComida->menu : null); @endphp
                
                @if($dataComida)
                    <div class="card shadow-sm border-0 rounded-4 mb-4 mt-3">
                        @if($dataComida->image_path)
                            <img src="{{ asset('storage/' . $dataComida->image_path) }}" class="menu-image" alt="Comida">
                        @else
                            <div class="menu-image bg-secondary d-flex align-items-center justify-content-center text-white">
                                <i class="bi bi-camera fs-1"></i>
                            </div>
                        @endif
                        
                        <div class="card-body">
                            <span class="badge bg-warning text-dark mb-2">Comida del Día</span>
                            <h4 class="card-title fw-bold text-dark mb-1">{{ $dataComida->platillo_principal }}</h4>
                            
                            <p class="text-muted small mb-2">
                                <i class="bi bi-check2-circle text-success me-1"></i> {{ $dataComida->entrada ?? 'Sin entrada' }}<br>
                                <i class="bi bi-cup-straw text-info me-1"></i> {{ $dataComida->bebida ?? 'Sin bebida' }}
                            </p>

                            @if($dataComida->description)
                                <div class="p-2 mb-3 bg-light rounded text-secondary small">
                                    <i class="bi bi-info-circle me-1"></i> {{ $dataComida->description }}
                                </div>
                            @endif

                            @if($reservaComida)
                                @if($reservaComida->status === 'consumed')
                                    {{-- ESTADO: CONSUMIDO --}}
                                    <div class="mt-3 text-center">
                                        <p class="small text-dark mb-2">
                                            <i class="bi bi-clock fw-bold"></i> Consumido a las: <strong>{{ \Carbon\Carbon::parse($reservaComida->updated_at)->format('H:i') }}</strong>
                                        </p>
                                        <button class="btn btn-success w-100 fw-bold rounded-pill py-2 shadow-sm text-white" disabled style="opacity: 1;">
                                            <i class="bi bi-check2-all me-1"></i> ¡Buen Provecho! (Consumido)
                                        </button>
                                    </div>
                                @else
                                    {{-- ESTADO: RESERVADO --}}
                                    <div class="mt-3 text-center">
                                        <p class="small text-dark mb-2">
                                            <i class="bi bi-clock fw-bold"></i> Horario reservado: <strong>{{ \Carbon\Carbon::parse($reservaComida->shift->start_time)->format('H:i') }}</strong>
                                        </p>
                                        
                                        <button class="btn btn-warning w-100 fw-bold rounded-pill py-2 shadow-sm text-dark mb-2" disabled style="opacity: 1;">
                                            <i class="bi bi-check-circle-fill me-1"></i> Reservado
                                        </button>

                                        <form action="{{ route('api.reserva.cancel', $reservaComida->id) }}" method="POST" class="needs-confirm" data-confirm-message="¿Estás seguro que deseas cancelar tu reserva? Te devolveremos tus {{ number_format($reservaComida->menu->price, 0) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-soft-red w-100 fw-bold rounded-pill py-2 shadow-sm">
                                                <i class="bi bi-x-circle me-1"></i> Cancelar mi reserva
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @else
                                {{-- VISTA DE COMPRA --}}
                                <form action="{{ route('api.reserva.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="menu_id" value="{{ $dataComida->id }}">
                                    <input type="hidden" name="reservation_date" value="{{ $hoy }}">

                                    <label class="form-label fw-bold text-dark small mb-2 text-center d-block">Elige tu horario:</label>
                                    
                                    <div class="times-scroll d-flex flex-wrap gap-2 mb-3">
                                        @foreach($turnosComida as $turno)
                                            <input type="radio" class="shift-radio" name="shift_id" value="{{ $turno->id }}" id="turno_c_{{ $turno->id }}" {{ $turno->available_capacity <= 0 ? 'disabled' : '' }} required>
                                            <label class="shift-label shadow-sm" for="turno_c_{{ $turno->id }}">
                                                {{ \Carbon\Carbon::parse($turno->start_time)->format('H:i') }}
                                            </label>
                                        @endforeach
                                    </div>

                                    <button type="submit" class="btn btn-dark w-100 fw-bold rounded-pill py-2 shadow-sm">
                                        Reservar por ${{ $dataComida->price }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endif

                @if(!$dataDesayuno && !$dataComida)
                    <div class="text-center text-muted mt-5 pt-5">
                        <i class="bi bi-cup-hot fs-1 text-secondary"></i>
                        <h5 class="mt-3 fw-bold">Cafetería Cerrada</h5>
                        <p>Aún no se ha publicado el menú de hoy.</p>
                    </div>
                @endif

            </section>
        </div>

        <div class="navbar-fixed-bottom">
            @include('partials.navbar', ['activeTab' => 'home'])
        </div>

    </div>
</body>
</html>