<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Comelobos | Reservar Menú</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <style>
        /* Estilos para las píldoras de horarios (Radio Buttons) */
        .shift-radio { display: none; }
        .shift-label {
            border: 2px solid #e0e0e0;
            border-radius: 50px;
            padding: 8px 16px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #666;
            cursor: pointer;
            transition: all 0.2s ease;
            background: white;
            text-align: center;
            flex: 1 1 calc(50% - 8px); /* Dos columnas en móvil */
        }
        .shift-radio:checked + .shift-label {
            background-color: #0d6efd; /* Azul de selección */
            border-color: #0d6efd;
            color: white;
        }
        .shift-radio:disabled + .shift-label {
            background-color: #f8f9fa;
            border-color: #dee2e6;
            color: #adb5bd;
            cursor: not-allowed;
            text-decoration: line-through;
        }
        .menu-image {
            height: 180px;
            object-fit: cover;
            border-radius: 12px 12px 0 0;
            width: 100%;
        }
        .credits-badge {
            background-color: #ffc107;
            color: #000;
            font-weight: bold;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body class="bg-light">
    <div class="device pb-5" role="application">
        
        <main class="hero hero--sm mb-3">
            <div class="header-inner d-flex justify-content-between align-items-center w-100 px-3">
                <h2 class="page-title mb-0">Menú de Hoy</h2>
                <div class="credits-badge">
                    <i class="bi bi-coin me-1"></i> {{ Auth::user()->credits ?? 0 }} pts
                </div>
            </div>
        </main>

        <section class="px-3 flex-auto">
            
            {{-- Errores de Validación (Si intenta reservar doble, etc.) --}}
            @if($errors->any())
                <div class="alert alert-danger shadow-sm border-0 rounded-3 mb-4">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $errors->first() }}
                </div>
            @endif

            {{-- ============================== --}}
            {{-- TARJETA DE DESAYUNO            --}}
            {{-- ============================== --}}
            @if($menuDesayuno)
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                @if($menuDesayuno->image_path)
                    <img src="{{ $menuDesayuno->image_path }}" class="menu-image" alt="Desayuno">
                @else
                    <div class="menu-image bg-secondary d-flex align-items-center justify-content-center text-white">
                        <i class="bi bi-camera text-white-50 fs-1"></i>
                    </div>
                @endif
                
                <div class="card-body">
                    <span class="badge bg-primary mb-2">Desayuno</span>
                    <h5 class="card-title fw-bold text-dark mb-1">{{ $menuDesayuno->platillo_principal }}</h5>
                    <p class="text-muted small mb-3">
                        <i class="bi bi-check2-circle text-success me-1"></i> {{ $menuDesayuno->entrada ?? 'Sin entrada' }}<br>
                        <i class="bi bi-cup-straw text-info me-1"></i> {{ $menuDesayuno->bebida ?? 'Sin bebida' }}
                    </p>

                    <form action="{{ route('api.reserva.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="menu_id" value="{{ $menuDesayuno->id }}">
                        <input type="hidden" name="reservation_date" value="{{ $hoy }}">

                        <label class="form-label fw-bold text-dark small mb-2">Elige tu horario de servicio:</label>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @foreach($turnosDesayuno as $turno)
                                <input type="radio" class="shift-radio" name="shift_id" value="{{ $turno->id }}" id="turno_{{ $turno->id }}" {{ $turno->available_capacity <= 0 ? 'disabled' : '' }} required>
                                <label class="shift-label shadow-sm" for="turno_{{ $turno->id }}">
                                    {{ \Carbon\Carbon::parse($turno->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($turno->end_time)->format('H:i') }}
                                </label>
                            @endforeach
                        </div>

                        <button type="submit" class="btn btn-dark w-100 fw-bold rounded-pill py-2">
                            Reservar (35 Créditos)
                        </button>
                    </form>
                </div>
            </div>
            @endif

            {{-- ============================== --}}
            {{-- TARJETA DE COMIDA              --}}
            {{-- ============================== --}}
            @if($menuComida)
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                @if($menuComida->image_path)
                    <img src="{{ $menuComida->image_path }}" class="menu-image" alt="Comida">
                @else
                    <div class="menu-image bg-secondary d-flex align-items-center justify-content-center text-white">
                        <i class="bi bi-camera text-white-50 fs-1"></i>
                    </div>
                @endif
                
                <div class="card-body">
                    <span class="badge bg-warning text-dark mb-2">Comida</span>
                    <h5 class="card-title fw-bold text-dark mb-1">{{ $menuComida->platillo_principal }}</h5>
                    <p class="text-muted small mb-3">
                        <i class="bi bi-check2-circle text-success me-1"></i> {{ $menuComida->entrada ?? 'Sin entrada' }}<br>
                        <i class="bi bi-cup-straw text-info me-1"></i> {{ $menuComida->bebida ?? 'Sin bebida' }}
                    </p>

                    <form action="{{ route('api.reserva.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="menu_id" value="{{ $menuComida->id }}">
                        <input type="hidden" name="reservation_date" value="{{ $hoy }}">

                        <label class="form-label fw-bold text-dark small mb-2">Elige tu horario de servicio:</label>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @foreach($turnosComida as $turno)
                                <input type="radio" class="shift-radio" name="shift_id" value="{{ $turno->id }}" id="turno_{{ $turno->id }}" {{ $turno->available_capacity <= 0 ? 'disabled' : '' }} required>
                                <label class="shift-label shadow-sm" for="turno_{{ $turno->id }}">
                                    {{ \Carbon\Carbon::parse($turno->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($turno->end_time)->format('H:i') }}
                                </label>
                            @endforeach
                        </div>

                        <button type="submit" class="btn btn-dark w-100 fw-bold rounded-pill py-2">
                            Reservar (35 Créditos)
                        </button>
                    </form>
                </div>
            </div>
            @endif

            @if(!$menuDesayuno && !$menuComida)
                <div class="text-center text-muted mt-5">
                    <i class="bi bi-emoji-frown fs-1"></i>
                    <p class="mt-2">Por el momento no hay menús publicados para el día de hoy.</p>
                </div>
            @endif

        </section>
        
        {{-- Aquí puedes mantener tu include del Navbar inferior --}}
        {{-- @include('partials.navbar', ['activeTab' => 'reservar']) --}}
    </div>
</body>
</html>