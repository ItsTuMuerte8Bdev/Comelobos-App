<!DOCTYPE html>
<html lang="es">
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Comelobos | Reservar</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Bootstrap Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        <div class="device" role="application">
            <main class="hero hero--sm">
                <div class="header-inner">
                    <a href="{{ url('/reservas') }}" class="back-btn back-btn--blue">←</a>
                    <h2 class="page-title">Reservas</h2>
                </div>
            </main>

            <section class="px-3 py-3 flex-auto">
                <div class="reservar-card">
                    <label class="form-label">Tipo de comida</label>
                    <select id="tipo" class="form-select mb-3">
                        <option value="desayuno">Desayuno</option>
                        <option value="comida">Comida</option>
                    </select>

                    <label class="form-label">Horario</label>
                    <p class="text-muted small">Nota: Si elige desayuno, solo se activan ciertos horarios.</p>
                    <div id="horarios" class="mb-3 color-black">
                        <button class="horario btn">8:00 - 9:00</button>
                        <button class="horario btn">9:00 - 10:00</button>
                        <button class="horario btn">10:00 - 11:00</button>
                        <button class="horario btn">11:00 - 12:00</button>
                        <button class="horario btn">12:00 - 13:00</button>
                        <button class="horario btn">13:00 - 14:00</button>
                        <button class="horario btn">14:00 - 15:00</button>
                    </div>

                    <label class="form-label">Menú</label>
                    <div class="menu-card mb-3">
                        <strong>Desayuno del día (De 9:00 a 13:00)</strong>
                        <div class="d-flex align-items-center mt-2">
                            <img src="https://via.placeholder.com/90x60" alt="img" class="thumb-rounded">
                            <div>
                                <div>Huevos a la mexicana con porción de fruta y jugo</div>
                            </div>
                        </div>
                    </div>

                    <div class="menu-card mb-3">
                        <strong>Comida del día (De 13:00 a 17:00)</strong>
                        <div class="d-flex align-items-center mt-2">
                            <img src="https://via.placeholder.com/90x60" alt="img" class="thumb-rounded">
                            <div>
                                <div>Sopa de fideos con pollo en salsa verde y agua de sabor</div>
                            </div>
                        </div>
                    </div>

                    <div class="creditos-box mb-3">Créditos disponibles: <strong id="creditos">{{ Auth::user()->credits }}</strong></div>

                    <div class="text-end">
                        <button id="btn-reservar" class="btn btn-reservar">Reservar</button>
                    </div>
                </div>
            </section>

            @include('partials.navbar', ['activeTab' => 'reservas'])
        </div>

        <!-- Confirm modal (dinámico dentro de esta vista) -->
        <div id="confirm-modal" class="modal-overlay">
            <div class="confirm-box">
                <p id="confirm-text">¿Confirmar reserva? <br><small class="text-muted">Costo: <span id="costo">35</span></small></p>
                <div class="confirm-actions">
                    <button id="btn-cancel" class="btn-cancel">Cancelar</button>
                    <button id="btn-confirm" class="btn-confirm">Confirmar</button>
                </div>
            </div>
        </div>

        <!-- estilos movidos a app.css -->

        <script>
            // manejo selección horarios
            document.querySelectorAll('.horario').forEach(btn=>{btn.addEventListener('click',()=>{document.querySelectorAll('.horario').forEach(b=>b.classList.remove('active'));btn.classList.add('active')})})

            // abrir modal de confirmación
            document.getElementById('btn-reservar').addEventListener('click',()=>{document.getElementById('confirm-modal').style.display='flex'})
            document.getElementById('btn-cancel').addEventListener('click',()=>{document.getElementById('confirm-modal').style.display='none'})

            // al confirmar, iniciar animación y redirigir luego de 3s
            document.getElementById('btn-confirm').addEventListener('click',()=>{
                const modal = document.getElementById('confirm-modal')
                modal.style.display='none'
                // replace contenido por animación
                const container = document.querySelector('.reservar-card')
                container.innerHTML = `
                    <div class="confirm-inner">
                        <svg width="160" height="160" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 2v2a2 2 0 0 1-2 2H4v12h16V6h-2a2 2 0 0 1-2-2V2H8z" stroke="#111" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <h4 class="mt-3">Reservando...</h4>
                        <div class="loader mt-3" id="loader-dots">
                            <div class="dot" id="d1"></div>
                            <div class="dot" id="d2"></div>
                            <div class="dot" id="d3"></div>
                        </div>
                    </div>`

                // mostrar puntos uno a uno durante 3 segundos
                const d1 = document.getElementById('d1')
                const d2 = document.getElementById('d2')
                const d3 = document.getElementById('d3')
                setTimeout(()=>d1.classList.add('show'),300)
                setTimeout(()=>d2.classList.add('show'),900)
                setTimeout(()=>d3.classList.add('show'),1500)

                // redirigir tras 3s a la lista y mostrar modal éxito
                setTimeout(()=>{window.location.href = '{{ url('/reservas') }}?success=1'},3000)
            })
        </script>
    </body>
</html>
