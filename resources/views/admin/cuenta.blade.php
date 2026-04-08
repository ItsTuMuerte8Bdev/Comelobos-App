@php $activeTab = 'admin_cuenta'; @endphp
@extends('admin.layout')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-12">
        <h4>Cuenta (Administrativo)</h4>
        <p>Acciones de cuenta y perfil para usuarios administrativos.</p>

        <div class="card mb-3">
          <div class="card-body">
            <div class="d-flex flex-column gap-3">
              <a href="{{ route('admin.cuenta.informacion') }}" class="btn account-btn w-100">Información personal</a>
              <a href="{{ route('admin.cuenta.ajustes') }}" class="btn account-btn w-100">Ajustes</a>
              <a href="{{ route('admin.cuenta.asignacion') }}" class="btn account-btn w-100">Asignación de roles</a>
              <a href="{{ route('admin.cuenta.reporte') }}" class="btn account-btn w-100">Reporte de movimientos</a>

              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn account-btn w-100">Cerrar sesión</button>
              </form>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
@endsection
