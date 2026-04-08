@php $activeTab = 'admin_creditos'; @endphp
@extends('admin.layout')

@section('content')
  <style>
    .admin-scroll{max-height: calc(100vh - 220px); overflow-y:auto; overflow-x:hidden; padding-right:8px; box-sizing:border-box}
    .admin-scroll *{box-sizing:border-box}
  </style>

  <div class="admin-scroll">
    <div class="container">
      <div class="row">
        <div class="col-12">
        <h4>Agregar créditos</h4>

        <form method="post" action="#">
          @csrf
          <!-- ID / Matrícula con botones Buscar + Deshacer en la misma fila -->
          <div class="mb-3">
            <label class="form-label">ID o Matrícula</label>
            <input class="form-control" type="text" placeholder="2023XXXXX" aria-label="matricula">
            <div class="row g-2 mt-2">
              <div class="col-6"><button type="button" class="btn btn-danger w-100">Buscar</button></div>
              <div class="col-6"><button type="button" class="btn btn-secondary w-100">Deshacer</button></div>
            </div>
          </div>

          <!-- Nombre / Apellidos / Créditos disponibles -->
          <div class="mb-2">
            <label class="form-label">Nombre</label>
            <input class="form-control bg-light" type="text" placeholder="" disabled>
          </div>

          <div class="mb-2">
            <label class="form-label">Apellidos</label>
            <input class="form-control bg-light" type="text" placeholder="" disabled>
          </div>

          <div class="mb-2">
            <label class="form-label">Créditos disponibles</label>
            <input class="form-control bg-light" type="text" placeholder="" disabled>
          </div>

          <!-- Créditos a depositar -->
          <div class="mb-3">
            <label class="form-label">Créditos a depositar</label>
            <input class="form-control" type="number" min="0" value="250">
            <div class="row g-2 mt-2">
              <div class="col-6"><button type="button" class="btn btn-secondary w-100">Deshacer</button></div>
              <div class="col-6"><button class="btn btn-danger w-100">Agregar</button></div>
            </div>
          </div>
        </form>
        </div>
      </div>
    </div>
  </div>
@endsection
