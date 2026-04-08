@php $activeTab = 'admin_cuenta'; @endphp
@extends('admin.layout')

@section('content')
  <div class="container">
    <div class="row">
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
                  <h2>Ajustes</h2>
                </div>
              </div>

              <section class="px-3 py-3 content-section">
                <div class="padded">
                  <div class="container-sm">
                    <h4 class="mb-3">Aforo</h4>

                    <div class="mb-4">
                      <label class="form-label">Editar aforo para desayuno</label>
                      <div class="input-group mb-2">
                        <input type="number" id="aforo_desayuno" class="form-control rounded-pill" value="150" min="0">
                      </div>
                      <div class="d-flex justify-content-center gap-3 mb-3">
                        <button class="btn btn-outline-secondary rounded-pill px-3" id="deshacer_desayuno" type="button">Deshacer</button>
                        <button class="btn btn-danger rounded-pill px-4" id="aceptar_desayuno" type="button">Aceptar</button>
                      </div>
                    </div>

                    <div class="mb-4">
                      <label class="form-label">Editar aforo para comida</label>
                      <div class="input-group mb-2">
                        <input type="number" id="aforo_comida" class="form-control rounded-pill" value="150" min="0">
                      </div>
                      <div class="d-flex justify-content-center gap-3 mb-3">
                        <button class="btn btn-outline-secondary rounded-pill px-3" id="deshacer_comida" type="button">Deshacer</button>
                        <button class="btn btn-danger rounded-pill px-4" id="aceptar_comida" type="button">Aceptar</button>
                      </div>
                    </div>

                    
                  </div>
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

        @include('partials.modals_save_reset')

      @endsection
