@php $activeTab = 'admin_cuenta'; @endphp
@extends('admin.layout')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="hero app-header">
          <div class="header-inner">
            <a href="{{ route('admin.cuenta') }}" class="back-btn"><i class="bi bi-arrow-left"></i></a>
            <h2>Reporte de movimientos — Hoy</h2>
          </div>
        </div>

        <section class="px-3 py-3 content-section">
          <div class="padded container-max">

            <div class="row mb-3">
              <div class="col-md-4">
                <label class="form-label">Fecha</label>
                <input id="fecha_reporte" type="date" class="form-control" value="{{ date('Y-m-d') }}">
              </div>
              <div class="col-md-8 d-flex align-items-end justify-content-end">
                <button id="generarReporte" class="btn btn-danger rounded-pill px-4 ms-2">Generar reporte</button>
                <button id="exportCsv" class="btn btn-outline-secondary rounded-pill px-4 ms-2">Exportar CSV</button>
              </div>
            </div>

            <div id="reportSummary" class="mb-3">
              <div class="card p-3">
                <div class="d-flex justify-content-between">
                  <div><strong>Total movimientos:</strong> <span id="total_mov">0</span></div>
                  <div><strong>Total crédito agregado:</strong> <span id="total_creditos">0</span></div>
                </div>
              </div>
            </div>

            <div class="table-responsive">
              <table id="movTable" class="table table-striped">
                <thead>
                  <tr>
                    <th>Hora</th>
                    <th>Tipo</th>
                    <th>Usuario</th>
                    <th>Detalle</th>
                    <th>Monto</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- filas generadas por JS -->
                </tbody>
              </table>
            </div>

            <p class="note">Esto es una vista simulada. Cuando integre el backend, el botón "Generar reporte" pedirá los datos reales del servidor.</p>

          </div>
        </section>

      </div>
    </div>
  </div>

  <script>
    function sampleMovements(date){
      // Simulated movements for demonstration
      return [
        {hora:'08:05', tipo:'Compra', usuario:'Juan Pérez', detalle:'Desayuno', monto:25},
        {hora:'09:12', tipo:'Depósito', usuario:'María López', detalle:'Créditos', monto:150},
        {hora:'12:03', tipo:'Compra', usuario:'Carlos Díaz', detalle:'Comida', monto:35},
        {hora:'13:20', tipo:'Depósito', usuario:'Ana Ruiz', detalle:'Créditos', monto:200}
      ];
    }

    function renderTable(rows){
      var tbody = document.querySelector('#movTable tbody');
      tbody.innerHTML = '';
      var totalMov = rows.length;
      var totalCred = 0;
      rows.forEach(function(r){
        var tr = document.createElement('tr');
        tr.innerHTML = '<td>'+r.hora+'</td><td>'+r.tipo+'</td><td>'+r.usuario+'</td><td>'+r.detalle+'</td><td>'+r.monto+'</td>';
        tbody.appendChild(tr);
        if(r.tipo === 'Depósito') totalCred += r.monto;
      });
      document.getElementById('total_mov').textContent = totalMov;
      document.getElementById('total_creditos').textContent = totalCred;
    }

    document.getElementById('generarReporte').addEventListener('click', function(){
      var date = document.getElementById('fecha_reporte').value;
      var rows = sampleMovements(date);
      renderTable(rows);
    });

    document.getElementById('exportCsv').addEventListener('click', function(){
      var rows = [];
      document.querySelectorAll('#movTable tbody tr').forEach(function(tr){
        var cols = Array.from(tr.querySelectorAll('td')).map(td=>td.textContent.trim());
        rows.push(cols);
      });
      if(rows.length===0){ alert('No hay datos para exportar'); return; }
      var csv = 'Hora,Tipo,Usuario,Detalle,Monto\n' + rows.map(r=>r.join(',')).join('\n');
      var blob = new Blob([csv], {type:'text/csv'});
      var url = URL.createObjectURL(blob);
      var a = document.createElement('a');
      a.href = url;
      a.download = 'reporte_movimientos_'+document.getElementById('fecha_reporte').value+'.csv';
      document.body.appendChild(a);
      a.click();
      a.remove();
      URL.revokeObjectURL(url);
    });
  </script>

@endsection
