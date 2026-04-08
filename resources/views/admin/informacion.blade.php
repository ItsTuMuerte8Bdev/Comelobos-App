@php $activeTab = 'admin_cuenta'; @endphp
@extends('admin.layout')

@section('content')
  <style>
    /* Encabezado más compacto */
    .hero.app-header{padding:6px 0; min-height:48px; display:flex; align-items:center}
    .hero.app-header .header-inner{display:flex; align-items:center; gap:12px}
    .hero.app-header .header-inner h2{margin:0; font-size:1rem; font-weight:600}
    .back-btn{width:36px; height:36px; display:inline-flex; align-items:center; justify-content:center}
    @media (max-width:576px){
      .hero.app-header{min-height:44px}
      .hero.app-header .header-inner h2{font-size:1rem}
    }
  </style>

  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="hero app-header">
          <div class="header-inner">
            <a href="{{ route('admin.cuenta') }}" class="back-btn"><i class="bi bi-arrow-left"></i></a>
            <h2>Información personal</h2>
          </div>
        </div>

        <section class="px-3 py-3 content-section">
            <div class="padded">
                <form>
                    <div class="field mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" value="" disabled>
                    </div>
                    <div class="field mb-3">
                        <label class="form-label">Apellidos</label>
                        <input type="text" class="form-control" value="" disabled>
                    </div>
                    <div class="field mb-3">
                        <label class="form-label">Correo institucional</label>
                        <input type="email" class="form-control" value="" disabled>
                    </div>
                    <div class="field mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" class="form-control" value="" disabled>
                    </div>
                    <div class="field mb-3">
                        <label class="form-label">Escuela, Facultad o Dependencia</label>
                        <input type="text" class="form-control" value="" disabled>
                    </div>
                    <div class="field mb-3">
                        <label class="form-label">ID o Matrícula</label>
                        <input type="text" class="form-control" value="" disabled>
                    </div>

                    <p class="note">Nota: Debe rellenarse con la información de registro y no puede cambiar nada el usuario</p>

                    <div class="d-flex justify-content-center gap-3 mt-4">
                        <button type="button" id="saveBtn" class="btn btn-success">Guardar Cambios</button>
                        <button type="button" id="resetBtn" class="btn btn-danger">Anular cambios</button>
                    </div>
                </form>
            </div>
        </section>

      </div>
    </div>
  </div>

  <script>
    document.getElementById('saveBtn').addEventListener('click', function(){
      var modal = new bootstrap.Modal(document.getElementById('saveModal'));
      modal.show();
    });
    document.getElementById('resetBtn').addEventListener('click', function(){
      var modal = new bootstrap.Modal(document.getElementById('resetModal'));
      modal.show();
    });
  </script>

  <!-- Reuse modals from shared view -->
  @include('partials.modals_save_reset')

@endsection
