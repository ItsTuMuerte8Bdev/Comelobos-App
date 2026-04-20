<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Comelobos Admin | Inicio</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <style>
        /* Magia de App Nativa */
        html, body { height: 100%; overflow: hidden; background-color: #f8f9fa; }
        .device { height: 100%; display: flex; flex-direction: column; }
        .main-scroll-area { flex-grow: 1; overflow-y: auto; overflow-x: hidden; padding-bottom: 2rem; }
        .navbar-fixed-bottom { flex-shrink: 0; z-index: 1000; background-color: #ffffff; box-shadow: 0 -4px 15px rgba(0,0,0,0.05); }

        /* Estilos visuales del Header */
        .home-hero { background: #003b5c; color: white; padding: 2rem 1.5rem 2rem 1.5rem; border-radius: 0 0 25px 25px; margin-bottom: 1rem; }
        
        /* Estilos del Publicador de Menú */
        .menu-card { background: #fff; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: none; overflow: hidden; }
        .menu-card-header { background: #003b5c; color: white; padding: 12px; text-align: center; font-weight: bold; font-size: 1.1rem; }
        .menu-card-header.comida { background: #002133; } 
        .form-label-custom { font-size: 0.85rem; font-weight: 600; color: #333; margin-bottom: 0.3rem; }
        .btn-guardar { background-color: #900000; color: white; border: none; padding: 12px; font-weight: bold; transition: background-color 0.2s; border-radius: 8px;}
        .btn-guardar:hover { background-color: #700000; color: white; }

        .btn-check-custom + .btn-pill {
            border-radius: 8px; 
            font-weight: 600; font-size: 0.85rem; padding: 0.4rem 1rem;
            border: 2px solid #003b5c; color: #003b5c; background-color: #ffffff;
            transition: all 0.2s ease-in-out;
            flex: 1 1 calc(50% - 8px); text-align: center;
        }
        .btn-check-custom:checked + .btn-pill { background-color: #003b5c; color: #ffffff; border-color: #003b5c; }
        .btn-check-custom:not(:checked) + .btn-pill:hover { background-color: rgba(0, 59, 92, 0.05); }
    </style>
</head>
<body>
    <div class="device" role="application">
        
        <div class="main-scroll-area">
            
            {{-- HEADER PRINCIPAL --}}
            <main class="home-hero d-flex justify-content-between align-items-start">
                <div>
                    <h2 class="fw-bold mb-1">¡Bienvenido, {{ Auth::user()->first_name }}!</h2>
                    <p class="mb-0 text-white-50">Panel de Control y Menú</p>
                </div>
            </main>

            <section class="px-3 position-relative z-1">
                
                {{-- Alertas Globales --}}
                @if(session('success'))
                    <div class="alert alert-success shadow-sm border-0 rounded-3 mb-4 mt-3">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    </div>
                @endif

                {{-- ===================================== --}}
                {{-- ESTADO ACTUAL DEL DÍA                 --}}
                {{-- ===================================== --}}
                <h5 class="fw-bold text-dark mt-4 mb-3"><i class="bi bi-broadcast me-2"></i>Activos Hoy</h5>
                <div class="row g-3 mb-4">
                    {{-- Tarjeta Desayuno --}}
                    <div class="col-md-6">
                        @if($menuDesayuno)
                            <div class="card border-0 shadow-sm rounded-4 h-100">
                                <div class="card-body d-flex align-items-center p-3">
                                    @if($menuDesayuno->image_path)
                                        <img src="{{ $menuDesayuno->image_path }}" alt="Desayuno" class="rounded shadow-sm me-3" style="width: 80px; height: 80px; object-fit: cover;">
                                    @endif
                                    <div>
                                        <span class="badge bg-primary mb-1">Desayuno</span>
                                        <h6 class="fw-bold mb-1 text-dark lh-sm">{{ $menuDesayuno->platillo_principal }}</h6>
                                        <p class="small mb-0 text-muted fw-bold">Porciones: {{ $menuDesayuno->available_portions }}</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="card border-0 shadow-sm rounded-4 h-100 bg-light border-dashed">
                                <div class="card-body text-center text-muted d-flex flex-column justify-content-center p-3">
                                    <small><i class="bi bi-exclamation-circle me-1"></i> Sin desayuno programado</small>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Tarjeta Comida --}}
                    <div class="col-md-6">
                        @if($menuComida)
                            <div class="card border-0 shadow-sm rounded-4 h-100">
                                <div class="card-body d-flex align-items-center p-3">
                                    @if($menuComida->image_path)
                                        <img src="{{ $menuComida->image_path }}" alt="Comida" class="rounded shadow-sm me-3" style="width: 80px; height: 80px; object-fit: cover;">
                                    @endif
                                    <div>
                                        <span class="badge bg-warning text-dark mb-1">Comida</span>
                                        <h6 class="fw-bold mb-1 text-dark lh-sm">{{ $menuComida->platillo_principal }}</h6>
                                        <p class="small mb-0 text-muted fw-bold">Porciones: {{ $menuComida->available_portions }}</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="card border-0 shadow-sm rounded-4 h-100 bg-light border-dashed">
                                <div class="card-body text-center text-muted d-flex flex-column justify-content-center p-3">
                                    <small><i class="bi bi-exclamation-circle me-1"></i> Sin comida programada</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <hr class="text-secondary mb-4">

                {{-- ===================================== --}}
                {{-- PUBLICADOR DE MENÚ                    --}}
                {{-- ===================================== --}}
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-pencil-square me-2"></i>Publicar Menú</h5>
                
                <div class="row">
                    {{-- FORMULARIO: DESAYUNO --}}
                    <div class="col-md-6 mb-4">
                        <div class="menu-card border">
                            <div class="menu-card-header">Desayuno</div>
                            <div class="card-body p-4">
                                <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="type" value="desayuno">

                                    <div class="row g-2 mb-4">
                                        <div class="col-8">
                                            <label class="form-label-custom">¿Para qué día es?</label>
                                            <input type="date" name="menu_date" class="form-control border-secondary bg-light" value="{{ old('type') == 'desayuno' ? old('menu_date') : $fechaHoy }}" required>
                                            @error('menu_date','desayuno') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label-custom">Porciones</label>
                                            <input type="number" name="available_portions" class="form-control border-secondary bg-light" value="{{ \Illuminate\Support\Facades\Cache::get('aforo_desayuno', 150) }}">
                                            @error('available_portions','desayuno') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label-custom text-dark mb-2">Horarios Habilitados <span class="text-danger">*</span></label>
                                        <div class="d-flex flex-wrap gap-2"> 
                                            <input type="checkbox" class="btn-check btn-check-custom" name="shifts[]" value="09:00-10:00" id="d_shift1" checked>
                                            <label class="btn btn-pill shadow-sm" for="d_shift1">9:00 - 10:00</label>

                                            <input type="checkbox" class="btn-check btn-check-custom" name="shifts[]" value="10:00-11:00" id="d_shift2" checked>
                                            <label class="btn btn-pill shadow-sm" for="d_shift2">10:00 - 11:00</label>

                                            <input type="checkbox" class="btn-check btn-check-custom" name="shifts[]" value="11:00-12:00" id="d_shift3" checked>
                                            <label class="btn btn-pill shadow-sm" for="d_shift3">11:00 - 12:00</label>

                                            <input type="checkbox" class="btn-check btn-check-custom" name="shifts[]" value="12:00-13:00" id="d_shift4" checked>
                                            <label class="btn btn-pill shadow-sm" for="d_shift4">12:00 - 13:00</label>
                                        </div>
                                        @error('shifts', 'desayuno') <small class="text-danger fw-bold d-block mt-1">Selecciona al menos uno.</small> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label-custom text-dark">Entrada</label>
                                        <input type="text" name="entrada" class="form-control" placeholder="Ej. Fruta con yogurt" value="{{ old('type') == 'desayuno' ? old('entrada') : '' }}" required>
                                        @error('entrada','desayuno') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label-custom text-dark">Platillo Principal</label>
                                        <input type="text" name="platillo_principal" class="form-control" placeholder="Ej. Chilaquiles verdes" value="{{ old('type') == 'desayuno' ? old('platillo_principal') : '' }}" required>
                                        @error('platillo_principal','desayuno') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label-custom text-dark">Bebida</label>
                                        <input type="text" name="bebida" class="form-control" placeholder="Ej. Jugo de naranja" value="{{ old('type') == 'desayuno' ? old('bebida') : '' }}" required>
                                        @error('bebida','desayuno') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label-custom text-dark">Detalles / Alergias</label>
                                        <textarea name="description" class="form-control" rows="2" placeholder="Ej. Contiene lácteos" required>{{ old('type') == 'desayuno' ? old('description') : '' }}</textarea>
                                        @error('description','desayuno') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label-custom text-dark">Fotografía</label>
                                        <input type="file" name="image" class="form-control" accept="image/*" required>
                                    </div>

                                    <button type="submit" class="btn btn-guardar w-100 shadow-sm">Publicar Desayuno</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- FORMULARIO: COMIDA --}}
                    <div class="col-md-6 mb-4">
                        <div class="menu-card border">
                            <div class="menu-card-header comida">Comida</div>
                            <div class="card-body p-4">
                                <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="type" value="comida">

                                    <div class="row g-2 mb-4">
                                        <div class="col-8">
                                            <label class="form-label-custom">¿Para qué día es?</label>
                                            <input type="date" name="menu_date" class="form-control border-secondary bg-light" value="{{ old('type') == 'comida' ? old('menu_date') : $fechaHoy }}" required>
                                            @error('menu_date','comida') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label-custom">Porciones</label>
                                            <input type="number" name="available_portions" class="form-control border-secondary bg-light" value="{{ \Illuminate\Support\Facades\Cache::get('aforo_comida', 10) }}">
                                            @error('available_portions','comida') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label-custom text-dark mb-2">Horarios Habilitados <span class="text-danger">*</span></label>
                                        <div class="d-flex flex-wrap gap-2">
                                            <input type="checkbox" class="btn-check btn-check-custom" name="shifts[]" value="13:00-14:00" id="c_shift1" checked>
                                            <label class="btn btn-pill shadow-sm" for="c_shift1">13:00 - 14:00</label>

                                            <input type="checkbox" class="btn-check btn-check-custom" name="shifts[]" value="14:00-15:00" id="c_shift2" checked>
                                            <label class="btn btn-pill shadow-sm" for="c_shift2">14:00 - 15:00</label>

                                            <input type="checkbox" class="btn-check btn-check-custom" name="shifts[]" value="15:00-16:00" id="c_shift3" checked>
                                            <label class="btn btn-pill shadow-sm" for="c_shift3">15:00 - 16:00</label>

                                            <input type="checkbox" class="btn-check btn-check-custom" name="shifts[]" value="16:00-17:00" id="c_shift4" checked>
                                            <label class="btn btn-pill shadow-sm" for="c_shift4">16:00 - 17:00</label>
                                        </div>
                                        @error('shifts', 'comida') <small class="text-danger fw-bold d-block mt-1">Selecciona al menos uno.</small> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label-custom text-dark">Entrada</label>
                                        <input type="text" name="entrada" class="form-control" placeholder="Ej. Sopa de Lentejas" value="{{ old('type') == 'comida' ? old('entrada') : '' }}" required>
                                        @error('entrada','comida') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label-custom text-dark">Platillo Principal</label>
                                        <input type="text" name="platillo_principal" class="form-control" placeholder="Ej. Milanesa de Res" value="{{ old('type') == 'comida' ? old('platillo_principal') : '' }}" required>
                                        @error('platillo_principal', 'comida') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label-custom text-dark">Bebida</label>
                                        <input type="text" name="bebida" class="form-control" placeholder="Ej. Agua de Jamaica" value="{{ old('type') == 'comida' ? old('bebida') : '' }}" required>
                                        @error('bebida', 'comida') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label-custom text-dark">Detalles / Alergias</label>
                                        <textarea name="description" class="form-control" rows="2" placeholder="Ej. Contiene gluten" required>{{ old('type') == 'comida' ? old('description') : '' }}</textarea>
                                        @error('description', 'comida') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label-custom text-dark">Fotografía</label>
                                        <input type="file" name="image" class="form-control" accept="image/*" required>
                                    </div>

                                    <button type="submit" class="btn btn-guardar w-100 shadow-sm">Publicar Comida</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
        </div>

        <div class="navbar-fixed-bottom">
            @include('partials.admin_navbar', ['activeTab' => 'home'])
        </div>

    </div>
</body>
</html>