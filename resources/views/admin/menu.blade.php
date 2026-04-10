@php $activeTab = 'admin_menu'; @endphp
@extends('admin.layout')

@section('content')
  <div class="container">
    <div class="row mb-3">
      <div class="col-12">
        <h4 class="fw-bold text-dark text-center fs-3">Publicador de Menú</h4>
        <p class="text-muted small">Selecciona una fecha y llena los campos para publicar el menú. Si ya existe un menú para esa fecha, se actualizará automáticamente con los nuevos datos.</p>
      </div>
    </div>

    <style>
      .admin-scroll { max-height: calc(100vh - 180px); overflow-y: auto; overflow-x: hidden; padding-right: 8px; box-sizing: border-box; }
      .admin-scroll * { box-sizing: border-box; }
      .container, .row { max-width: 100%; overflow: visible; }
      
      /* Estilos personalizados para las tarjetas de menú */
      .menu-card { background: #fff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid #eaeaea; overflow: hidden; }
      .menu-card-header { background: #003b5c; color: white; padding: 12px; text-align: center; font-weight: bold; font-size: 1.1rem; }
      .menu-card-header.comida { background: #002133; } /* Un tono más oscuro para diferenciar la comida */
      .form-label-custom { font-size: 0.85rem; font-weight: 600; color: #333; margin-bottom: 0.3rem; }
      .btn-guardar { background-color: #900000; color: white; border: none; padding: 10px; font-weight: bold; transition: background-color 0.2s; }
      .btn-guardar:hover { background-color: #700000; color: white; }
    </style>

    <div class="admin-scroll">
      
      {{-- Mensaje de Éxito Global --}}
      @if(session('success'))
          <div class="alert alert-success p-3 text-center fw-bold shadow-sm mb-4">
              <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
          </div>
      @endif

      <div class="row">
        <div class="col-md-6 mb-4">
          <div class="menu-card">
            <div class="menu-card-header">
              Desayuno
            </div>
            <div class="card-body p-4">
              <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="desayuno">

                {{-- Fecha y Porciones --}}
                <div class="row g-2 mb-4">
                    <div class="col-8">
                        <label class="form-label-custom">¿Para qué día es?</label>
                        <input type="date" name="menu_date" class="form-control border-primary shadow-sm" value="{{ old('type') == 'desayuno' ? old('menu_date') : '' }}" required>
                          @error('menu_date','desayuno')
                            <div style="color: red; font-size: 12px; font-weight: normal; text-align: right;">
                              {{ $message }}
                            </div>
                          @enderror
                    
                      </div>
                    <div class="col-4">
                        <label class="form-label-custom">Porciones</label>
                        <input type="number" name="available_portions" class="form-control border-primary shadow-sm" placeholder="Ej. 50" min="1" value="{{ old('type') == 'desayuno' ? old('available_portions') : '' }}" required>
                          @error('available_portions','desayuno')
                            <div style="color: red; font-size: 12px; font-weight: normal; text-align: right;">
                              {{ $message }}
                            </div>
                          @enderror
                      </div>
                </div>

                {{-- Entradas --}}
                <div class="mb-3">
                    <label class="form-label-custom text-dark">Entrada <span class="text-danger">*</span></label>
                    <input type="text" name="entrada" class="form-control bg-light" placeholder="Escribe la entrada..." value="{{ old('type') == 'desayuno' ? old('entrada') : '' }}" required>
                      @error('entrada','desayuno')
                          <div style="color: red; font-size: 12px; font-weight: normal; text-align: right;">
                              {{ $message }}
                          </div>
                      @enderror
                  </div>

                {{-- Platillo Principal --}}
                <div class="mb-3">
                    <label class="form-label-custom text-dark">Platillo Principal <span class="text-danger">*</span></label>
                    <input type="text" name="platillo_principal" class="form-control shadow-sm" placeholder="Escribe el platillo principal..." value="{{ old('type') == 'desayuno' ? old('platillo_principal') : '' }}" required>
                      @error('platillo_principal','desayuno')
                          <div style="color: red; font-size: 12px; font-weight: normal; text-align: right;">
                              {{ $message }}
                          </div>
                      @enderror
                  </div>

                {{-- Bebida --}}
                <div class="mb-3">
                    <label class="form-label-custom text-dark">Bebida <span class="text-danger">*</span></label>
                    <input type="text" name="bebida" class="form-control bg-light" placeholder="Escribe la bebida..." value="{{ old('type') == 'desayuno' ? old('bebida') : '' }}" required>
                      @error('bebida','desayuno')
                          <div style="color: red; font-size: 12px; font-weight: normal; text-align: right;">
                              {{ $message }}
                          </div>
                      @enderror
                  </div>

                {{-- Alergias / Detalles --}}
                <div class="mb-4">
                    <textarea name="description" class="form-control bg-light" rows="2" placeholder="Ingredientes (Prevención de Alergias)" required>{{ old('type') == 'desayuno' ? old('description') : '' }}</textarea>
                      @error('description','desayuno')
                          <div style="color: red; font-size: 12px; font-weight: normal; text-align: right;">
                              {{ $message }}
                          </div>
                      @enderror
                  </div>

                {{-- Imagen --}}
                <div class="mb-4">
                    <label class="form-label-custom text-dark">Fotografía del Menú <span class="text-danger">*</span></label>
                    <input type="file" name="image" class="form-control border-dark shadow-sm" accept="image/*" required>
                </div>

                {{-- Botón Guardar --}}
                <button type="submit" class="btn btn-guardar w-100 shadow-sm">
                    Guardar Cambios
                </button>
              </form>
            </div>
          </div>
        </div>

        <div class="col-md-6 mb-4">
          <div class="menu-card">
            <div class="menu-card-header comida">
              Comida
            </div>
            <div class="card-body p-4">
              <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="comida">

                {{-- Fecha y Porciones --}}
                <div class="row g-2 mb-4">
                    <div class="col-8">
                        <label class="form-label-custom">¿Para qué día es?</label>
                        <input type="date" name="menu_date" class="form-control border-primary shadow-sm" value="{{ old('type') == 'comida' ? old('menu_date') : '' }}" required>
                          @error('menu_date','comida')
                            <div style="color: red; font-size: 12px; font-weight: normal; text-align: right;">
                              {{ $message }}
                            </div>
                          @enderror
                      </div>
                    <div class="col-4">
                        <label class="form-label-custom">Porciones</label>
                        <input type="number" name="available_portions" class="form-control border-primary shadow-sm" placeholder="Ej. 50" min="1" value="{{ old('type') == 'comida' ? old('available_portions') : '' }}" required>
                          @error('available_portions','comida')
                            <div style="color: red; font-size: 12px; font-weight: normal; text-align: right;">
                              {{ $message }}
                            </div>
                          @enderror
                      </div>
                </div>

                {{-- Entradas --}}
                <div class="mb-3">
                    <label class="form-label-custom text-dark">Entrada <span class="text-danger">*</span></label>
                    <input type="text" name="entrada" class="form-control bg-light" placeholder="Escribe la entrada..." value="{{ old('type') == 'comida' ? old('entrada') : '' }}" required>
                      @error('entrada','comida')
                        <div style="color: red; font-size: 12px; font-weight: normal; text-align: right;">
                          {{ $message }}
                        </div>
                      @enderror
                </div>

                {{-- Platillo Principal --}}
                <div class="mb-3">
                    <label class="form-label-custom text-dark">Platillo Principal <span class="text-danger">*</span></label>
                    <input type="text" name="platillo_principal" class="form-control shadow-sm" placeholder="Escribe el platillo principal..." value="{{ old('type') == 'comida' ? old('platillo_principal') : '' }}" required>
                      @error('platillo_principal', 'comida')
                        <div style="color: red; font-size: 12px; font-weight: normal; text-align: right;">
                          {{ $message }}
                        </div>
                      @enderror
                </div>

                {{-- Bebida --}}
                <div class="mb-3">
                    <label class="form-label-custom text-dark">Bebida <span class="text-danger">*</span></label>
                    <input type="text" name="bebida" class="form-control bg-light" placeholder="Escribe la bebida..." value="{{ old('type') == 'comida' ? old('bebida') : '' }}" required>
                      @error('bebida', 'comida')
                        <div style="color: red; font-size: 12px; font-weight: normal; text-align: right;">
                          {{ $message }}
                        </div>
                      @enderror
                </div>

                {{-- Alergias / Detalles --}}
                <div class="mb-4">
                    <textarea name="description" class="form-control bg-light" rows="2" placeholder="Ingredientes (Prevención de Alergias)" required>{{ old('type') == 'comida' ? old('description') : '' }}</textarea>
                      @error('description', 'comida')
                        <div style="color: red; font-size: 12px; font-weight: normal; text-align: right;">
                          {{ $message }}
                        </div>
                      @enderror
                </div>

                {{-- Imagen --}}
                <div class="mb-4">
                    <label class="form-label-custom text-dark">Fotografía del Menú <span class="text-danger">*</span></label>
                    <input type="file" name="image" class="form-control border-dark shadow-sm" accept="image/*" required>
                </div>

                {{-- Botón Guardar --}}
                <button type="submit" class="btn btn-guardar w-100 shadow-sm">
                    Guardar Cambios
                </button>
              </form>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
@endsection