@php $activeTab = 'admin_cuenta'; @endphp
@extends('admin.layout')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="hero app-header">
          <div class="header-inner">
            <a href="{{ route('admin.cuenta') }}" class="back-btn"><i class="bi bi-arrow-left"></i></a>
            <h2>Usuarios</h2>
          </div>
        </div>

        <section class="px-3 py-3 content-section">
          <div class="padded container-sm">

            <label class="form-label">ID o Matrícula</label>
            <div class="input-group mb-3">
              <input id="buscar_id" type="text" class="form-control rounded-pill" placeholder="2023XXXXX">
              <button id="buscarBtn" class="btn btn-danger rounded-pill ms-2">Buscar</button>
            </div>

            <div id="usuario_info">
              <div class="mb-2">
                <label class="form-label">Nombre</label>
                <input id="nombre" type="text" class="form-control readonly-field" value="" readonly>
              </div>
              <div class="mb-2">
                <label class="form-label">Apellidos</label>
                <input id="apellidos" type="text" class="form-control readonly-field" value="" readonly>
              </div>
              <div class="mb-2">
                <label class="form-label">Correo institucional</label>
                <input id="correo" type="text" class="form-control readonly-field" value="" readonly>
              </div>
              <div class="mb-2">
                <label class="form-label">Teléfono</label>
                <input id="telefono" type="text" class="form-control readonly-field" value="" readonly>
              </div>
            </div>

            <div class="mt-3">
              <label class="form-label">Rol asignado</label>
              <select id="rol_select" class="form-select mb-3">
                <option value="estudiante">Estudiante</option>
                <option value="docente">Docente</option>
                <option value="administrativo">Administrativo</option>
                <option value="invitado">Invitado</option>
              </select>

              <div class="d-flex justify-content-between">
                <div>
                  <button id="deshacerRol" class="btn btn-outline-secondary rounded-pill px-3">Deshacer</button>
                </div>
                <div>
                  <button id="confirmarRol" class="btn btn-danger rounded-pill px-4">Confirmar</button>
                </div>
              </div>
            </div>

          </div>
        </section>

      </div>
    </div>
  </div>

  <script>
    // Cliente: buscar y rellenar campos de solo lectura (simulado)
    document.getElementById('buscarBtn').addEventListener('click', function(){
      var id = document.getElementById('buscar_id').value || '';
      // limpiar campos
      document.getElementById('nombre').value = '';
      document.getElementById('apellidos').value = '';
      document.getElementById('correo').value = '';
      document.getElementById('telefono').value = '';

      if (!id) return; // sin búsqueda, campos quedan vacíos

      // Simulación: rellenar con datos de ejemplo — en integración reemplazaremos por respuesta del servidor
      document.getElementById('nombre').value = 'Juan';
      document.getElementById('apellidos').value = 'Pérez';
      document.getElementById('correo').value = 'juan@example.com';
      document.getElementById('telefono').value = '555-1234';
    });

    document.getElementById('deshacerRol').addEventListener('click', function(){
      document.getElementById('rol_select').selectedIndex = 0;
    });
    document.getElementById('confirmarRol').addEventListener('click', function(){
      alert('Rol actualizado (simulado): '+ document.getElementById('rol_select').value);
    });
  </script>

@endsection
