@php $activeTab = 'admin_menu'; @endphp
@extends('admin.layout')

@section('content')
  <div class="container">
    <div class="row mb-3">
      <div class="col-12">
        <h4>Administración - Menú</h4>
      </div>
    </div>

    <ul class="nav nav-tabs mb-3" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="existentes-tab" data-bs-toggle="tab" data-bs-target="#existentes" type="button" role="tab">Existentes</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="editar-tab" data-bs-toggle="tab" data-bs-target="#editar" type="button" role="tab">Editar</button>
      </li>
    </ul>

    <style>
      .admin-scroll{max-height: calc(100vh - 220px); overflow-y: auto; overflow-x: hidden; padding-right: 8px; box-sizing: border-box;}
      .admin-scroll *{box-sizing: border-box}
      .admin-action-btns .btn{height:44px}
      /* ensure body/container don't create horizontal overflow */
      .container, .row {max-width: 100%; overflow: visible}
    </style>

    <div class="admin-scroll">
      <div class="tab-content">
      <div class="tab-pane fade show active" id="existentes" role="tabpanel" aria-labelledby="existentes-tab">
        <div class="row">
          <!-- Left: Desayuno -->
          <div class="col-md-6">
            <div class="card mb-3">
              <div class="card-header">Existentes — Desayuno</div>
              <div class="card-body">
                <!-- Agregar -->

                <div class="mb-3">
                  <label class="form-label text-primary fw-bold">Programar Nuevo Menú</label>
                  
                  {{-- 1. Iniciamos el formulario apuntando a nuestra nueva ruta --}}
                  <form action="{{ route('admin.menu.store') }}" method="POST">
                    @csrf
                    
                    {{-- 2. Campo Oculto para decirle al sistema que es Desayuno --}}
                    <input type="hidden" name="type" value="desayuno">

                    <div class="input-group mb-2">
                      <input type="text" name="description" class="form-control" placeholder="Ej. Chilaquiles Verdes con Pollo" required>
                    </div>

                    {{-- 3. LOS DOS CAMPOS NUEVOS OBLIGATORIOS PARA LA BASE DE DATOS --}}
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="small text-muted">Fecha del Menú</label>
                            <input type="date" name="menu_date" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="small text-muted">Porciones Totales</label>
                            <input type="number" name="available_portions" class="form-control" placeholder="Ej. 50" min="1" required>
                        </div>
                    </div>

                    <div class="row g-2 mt-3">
                      <div class="col-12">
                          <button type="submit" class="btn btn-danger w-100 fw-bold">Guardar en el Calendario</button>
                      </div>
                    </div>
                  </form>
                  
                  {{-- 4. Etiqueta mágica para mostrar mensajes de éxito --}}
                  @if(session('success'))
                      <div class="alert alert-success mt-3 p-2 text-center small fw-bold">
                          {{ session('success') }}
                      </div>
                  @endif
                </div>

                <!-- Editar -->
                <div class="mt-4 mb-3">
                  <label class="form-label">Editar</label>
                  <select class="form-select mb-2"><option>Entrada, Comida o Bebida</option></select>
                  <select class="form-select mb-2"><option>Se escoge entre la lista existente correspondiente</option></select>
                  <div class="input-group mb-2">
                    <input class="form-control" placeholder="Se edita">
                  </div>
                  <div class="row g-2">
                    <div class="col-6"><button class="btn btn-warning w-100">Editar</button></div>
                    <div class="col-6"><button class="btn btn-outline-secondary w-100">Cancelar</button></div>
                  </div>
                </div>

                <!-- Eliminar -->
                <div class="mt-4">
                  <label class="form-label">Eliminar</label>
                  <div class="row g-2 align-items-center">
                    <div class="col-8">
                      <select class="form-select"><option>Huevo a la mexicana</option></select>
                    </div>
                    <div class="col-4"><button class="btn btn-danger w-100">Eliminar</button></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Right: Comida -->
          <div class="col-md-6">
            <div class="card mb-3">
              <div class="card-header">Existentes — Comida</div>
              <div class="card-body">
                <!-- Agregar -->
                <div class="mb-3">
                  <label class="form-label">Agregar</label>
                  <select class="form-select mb-2"><option>Entrada, Comida o Bebida</option></select>
                  <div class="input-group mb-2">
                    <input class="form-control" placeholder="Huevo a la mexicana">
                  </div>
                  <div class="row g-2">
                    <div class="col-6"><button class="btn btn-danger w-100">Agregar</button></div>
                    <div class="col-6"><button class="btn btn-secondary w-100">Deshacer</button></div>
                  </div>
                </div>

                <!-- Editar -->
                <div class="mt-4 mb-3">
                  <label class="form-label">Editar</label>
                  <select class="form-select mb-2"><option>Entrada, Comida o Bebida</option></select>
                  <select class="form-select mb-2"><option>Se escoge entre la lista existente correspondiente</option></select>
                  <div class="input-group mb-2">
                    <input class="form-control" placeholder="Se edita">
                  </div>
                  <div class="row g-2">
                    <div class="col-6"><button class="btn btn-warning w-100">Editar</button></div>
                    <div class="col-6"><button class="btn btn-outline-secondary w-100">Cancelar</button></div>
                  </div>
                </div>

                <!-- Eliminar -->
                <div class="mt-4">
                  <label class="form-label">Eliminar</label>
                  <div class="row g-2 align-items-center">
                    <div class="col-8">
                      <select class="form-select"><option>Huevo a la mexicana</option></select>
                    </div>
                    <div class="col-4"><button class="btn btn-danger w-100">Eliminar</button></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="tab-pane fade" id="editar" role="tabpanel" aria-labelledby="editar-tab">
        <div class="row">
          <!-- Left: Editar Desayuno -->
          <div class="col-md-6">
            <div class="card mb-3">
              <div class="card-header">Editar — Desayuno</div>
              <div class="card-body">
                <!-- Agregar -->
                <div class="mb-3">
                  <label class="form-label">Agregar</label>
                  <select class="form-select mb-2"><option>Entrada, Comida o Bebida</option></select>
                  <div class="input-group mb-2">
                    <input class="form-control" placeholder="Huevo a la mexicana">
                    <button class="btn btn-outline-secondary">✖</button>
                  </div>
                  <div class="row g-2">
                    <div class="col-6"><button class="btn btn-danger w-100">Agregar</button></div>
                    <div class="col-6"><button class="btn btn-secondary w-100">Deshacer</button></div>
                  </div>
                </div>

                <!-- Editar -->
                <div class="mt-4 mb-3">
                  <label class="form-label">Editar</label>
                  <select class="form-select mb-2"><option>Entrada, Comida o Bebida</option></select>
                  <select class="form-select mb-2"><option>Se escoge entre la lista existente correspondiente</option></select>
                  <div class="input-group mb-2">
                    <input class="form-control" placeholder="Se edita">
                    <button class="btn btn-outline-secondary">✖</button>
                  </div>
                  <div class="row g-2">
                    <div class="col-6"><button class="btn btn-warning w-100">Editar</button></div>
                    <div class="col-6"><button class="btn btn-outline-secondary w-100">Cancelar</button></div>
                  </div>
                </div>

                <!-- Eliminar -->
                <div class="mt-4">
                  <label class="form-label">Eliminar</label>
                  <div class="row g-2 align-items-center">
                    <div class="col-8">
                      <select class="form-select"><option>Huevo a la mexicana</option></select>
                    </div>
                    <div class="col-4"><button class="btn btn-danger w-100">Eliminar</button></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Right: Editar Comida -->
          <div class="col-md-6">
            <div class="card mb-3">
              <div class="card-header">Editar — Comida</div>
              <div class="card-body">
                <!-- Agregar -->
                <div class="mb-3">
                  <label class="form-label">Agregar</label>
                  <select class="form-select mb-2"><option>Entrada, Comida o Bebida</option></select>
                  <div class="input-group mb-2">
                    <input class="form-control" placeholder="Huevo a la mexicana">
                    <button class="btn btn-outline-secondary">✖</button>
                  </div>
                  <div class="row g-2">
                    <div class="col-6"><button class="btn btn-danger w-100">Agregar</button></div>
                    <div class="col-6"><button class="btn btn-secondary w-100">Deshacer</button></div>
                  </div>
                </div>

                <!-- Editar -->
                <div class="mt-4 mb-3">
                  <label class="form-label">Editar</label>
                  <select class="form-select mb-2"><option>Entrada, Comida o Bebida</option></select>
                  <select class="form-select mb-2"><option>Se escoge entre la lista existente correspondiente</option></select>
                  <div class="input-group mb-2">
                    <input class="form-control" placeholder="Se edita">
                    <button class="btn btn-outline-secondary">✖</button>
                  </div>
                  <div class="row g-2">
                    <div class="col-6"><button class="btn btn-warning w-100">Editar</button></div>
                    <div class="col-6"><button class="btn btn-outline-secondary w-100">Cancelar</button></div>
                  </div>
                </div>

                <!-- Eliminar -->
                <div class="mt-4">
                  <label class="form-label">Eliminar</label>
                  <div class="row g-2 align-items-center">
                    <div class="col-8">
                      <select class="form-select"><option>Huevo a la mexicana</option></select>
                    </div>
                    <div class="col-4"><button class="btn btn-danger w-100">Eliminar</button></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
