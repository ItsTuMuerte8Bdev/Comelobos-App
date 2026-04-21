<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Comelobos Admin | Reportes</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <style>
        /* 1. Reseteo ABSOLUTO para el navegador */
        html, body { 
            height: 100vh; 
            width: 100%; 
            overflow: hidden; 
            background-color: #e9ecef; 
            margin: 0 !important; 
            padding: 0 !important; 
        }
        
        /* 2. Contenedor Central con el espacio superior */
        .device { 
            height: calc(100vh - 1.5rem); 
            max-width: 1200px; 
            margin: 1.5rem auto 0 auto; 
            display: flex; 
            flex-direction: column; 
            background-color: #f8f9fa; 
            box-shadow: 0 0 25px rgba(0,0,0,0.1); 
            overflow: hidden; 
            position: relative; 
            border-radius: 15px 15px 0 0; 
        }
        
        .main-scroll-area { flex-grow: 1; overflow-y: auto; overflow-x: hidden; padding-bottom: 1rem; }
        
        /* 3. Navbar pegado al fondo */
        .navbar-fixed-bottom { flex-shrink: 0; z-index: 1000; background-color: #ffffff; box-shadow: 0 -4px 15px rgba(0,0,0,0.05); }

        .header-hero { background: #003b5c; color: white; padding: 1.5rem 1.5rem 2rem 1.5rem; border-radius: 0 0 25px 25px; margin-bottom: 0; }
        .table th { background-color: #f1f3f5; color: #495057; font-size: 0.85rem; text-transform: uppercase; border-bottom: 2px solid #dee2e6; }
    </style>
</head>
<body>
    <div class="device" role="application">
        
        <div class="main-scroll-area">
            <main class="header-hero d-flex align-items-center">
                <a href="{{ route('admin.cuenta') }}" class="text-white me-3 fs-4"><i class="bi bi-arrow-left"></i></a>
                <div>
                    <h3 class="mb-0 fw-bold">Reportes</h3>
                    <p class="mb-0 text-white-50">Historial de movimientos</p>
                </div>
            </main>

            <section class="px-3 position-relative z-1" style="margin-top: 1rem;">
                <div class="container-sm px-0">
                    
                    {{-- BÚSQUEDA (Sin botón extra) --}}
                    <div class="card shadow-sm border-0 rounded-3 mb-3">
                        <div class="card-body p-3">
                            <form method="GET" action="{{ route('admin.cuenta.reporte') }}" id="formReporte">
                                <div class="row g-2 align-items-end">
                                    {{-- El input toma más espacio (col-4) --}}
                                    <div class="col-8 col-md-9">
                                        <label class="form-label fw-bold text-dark small mb-1">Buscar Fecha</label>
                                        <input name="fecha" type="date" class="form-control bg-light py-2" value="{{ $fecha }}" onchange="document.getElementById('formReporte').submit()">
                                    </div>
                                    {{-- El botón CSV toma el resto (col-8) --}}
                                    <div class="col-4 col-md-3">
                                        <button type="button" id="exportCsv" class="btn btn-outline-secondary w-100 fw-bold py-2">CSV <i class="bi bi-download ms-1"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- RESUMEN --}}
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="card shadow-sm border-0 rounded-3 bg-white h-100">
                                <div class="card-body p-3 text-center">
                                    <h6 class="text-muted small fw-bold mb-1">Movimientos</h6>
                                    <h4 class="fw-bold text-dark mb-0">{{ $movimientos->count() }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card shadow-sm border-0 rounded-3 bg-white border-bottom border-success border-4 h-100">
                                <div class="card-body p-3 text-center">
                                    <h6 class="text-muted small fw-bold mb-1">Ingresos Caja</h6>
                                    <h4 class="fw-bold text-success mb-0">$ {{ number_format($totalIngresos, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TABLA --}}
                    <div class="card shadow-sm border-0 rounded-3 overflow-hidden mb-4">
                        <div class="table-responsive">
                            <table id="movTable" class="table table-hover mb-0 align-middle small bg-white">
                                <thead>
                                    <tr>
                                        <th class="ps-3 py-3">Hora</th>
                                        <th class="py-3">Tipo</th>
                                        <th class="py-3">Matrícula</th>
                                        <th class="py-3">Usuario</th>
                                        <th class="text-end pe-3 py-3">Monto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($movimientos as $mov)
                                        <tr>
                                            <td class="ps-3 text-muted fw-bold">{{ \Carbon\Carbon::parse($mov->created_at)->format('H:i') }}</td>
                                            <td><span class="badge {{ $mov->type == 'Depósito' ? 'bg-success' : 'bg-primary' }}">{{ $mov->type }}</span></td>
                                            <td class="fw-bold text-dark">{{ $mov->user->matriculation_number }}</td>
                                            <td class="fw-bold text-dark">{{ $mov->user->first_name }} {{ $mov->user->last_name }} {{ $mov->user->second_last_name }}</td>
                                            <td class="text-end pe-3 fw-bold {{ $mov->type == 'Depósito' ? 'text-success' : 'text-primary' }}">
                                                {{ $mov->type == 'Depósito' ? '+' : '-' }}{{ number_format($mov->amount, 2) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center py-5 text-muted">No hay movimientos registrados.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <div class="navbar-fixed-bottom">
            @include('partials.admin_navbar', ['activeTab' => 'cuenta'])
        </div>

    </div>

    <script>
        document.getElementById('exportCsv').addEventListener('click', function(){
            var rows = [];
            document.querySelectorAll('#movTable tbody tr').forEach(function(tr){
                if(tr.cells.length > 1) {
                    var cols = Array.from(tr.querySelectorAll('td')).map(td=>td.textContent.trim());
                    rows.push(cols);
                }
            });
            if(rows.length===0){ alert('No hay datos para exportar.'); return; }
            var csv = '\uFEFFHora,Tipo,Matrícula,Usuario,Monto\n' + rows.map(r=>r.join(',')).join('\n');
            var blob = new Blob([csv], {type:'text/csv;charset=utf-8;'});
            var url = URL.createObjectURL(blob);
            var a = document.createElement('a');
            a.href = url;
            a.download = 'Reporte_Comelobos_{{ $fecha }}.csv';
            document.body.appendChild(a);
            a.click();
            a.remove();
        });
    </script>
</body>
</html>