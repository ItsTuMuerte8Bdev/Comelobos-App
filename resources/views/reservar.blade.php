<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Comelobos | Reservar</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Bootstrap Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        <link href="{{ asset('css/index.css') }}" rel="stylesheet">
    </head>
    <body>
        <div class="device" role="application">
            <main class="hero" style="background:transparent;color:#053f56;padding:1.25rem 1rem">
                <div style="display:flex;align-items:center;gap:.75rem">
                    <a href="{{ url('/reservas') }}" class="back-btn" style="background:#3fc0ff;padding:.4rem .55rem;border-radius:8px;color:#fff;text-decoration:none">←</a>
                    <h2 style="margin:0;font-size:1.1rem">Reservas</h2>
                </div>
            </main>

            <section class="px-3 py-3" style="flex:1 1 auto;">
                <div class="reservar-card">
                    <label class="form-label">Tipo de comida</label>
                    <select id="tipo" class="form-select mb-3">
                        <option value="desayuno">Desayuno</option>
                        <option value="comida">Comida</option>
                    </select>

                    <label class="form-label">Horario</label>
                    <p class="text-muted small">Nota: Si elige desayuno, solo se activan ciertos horarios.</p>
                    <div id="horarios" class="mb-3">
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
                            <img src="https://via.placeholder.com/90x60" alt="img" style="border-radius:4px;margin-right:.6rem;border:1px solid #eee">
                            <div>
                                <div>Huevos a la mexicana con porción de fruta y jugo</div>
                            </div>
                        </div>
                    </div>

                    <div class="menu-card mb-3">
                        <strong>Comida del día (De 13:00 a 17:00)</strong>
                        <div class="d-flex align-items-center mt-2">
                            <img src="https://via.placeholder.com/90x60" alt="img" style="border-radius:4px;margin-right:.6rem;border:1px solid #eee">
                            <div>
                                <div>Sopa de fideos con pollo en salsa verde y agua de sabor</div>
                            </div>
                        </div>
                    </div>

                    <div class="creditos-box mb-3">Créditos disponibles: <strong id="creditos">180</strong></div>

                    <div class="text-end">
                        <button id="btn-reservar" class="btn btn-reservar">Reservar</button>
                    </div>
                </div>
            </section>

            @include('partials.navbar', ['activeTab' => 'reservas'])
        </div>

        <!-- Confirm modal (dinámico dentro de esta vista) -->
        <div id="confirm-modal" style="display:none;position:fixed;inset:0;align-items:center;justify-content:center;background:rgba(0,0,0,.35);z-index:9999">
            <div style="background:#fff;padding:18px;border-radius:8px;max-width:420px;margin:0 auto;text-align:center;">
                <p id="confirm-text">¿Confirmar reserva? <br><small class="text-muted">Costo: <span id="costo">35</span></small></p>
                <div style="display:flex;gap:.6rem;justify-content:center;margin-top:12px">
                    <button id="btn-cancel" style="background:#e74c3c;border:none;color:#fff;padding:.45rem .9rem;border-radius:6px">Cancelar</button>
                    <button id="btn-confirm" style="background:#1bb86a;border:none;color:#fff;padding:.45rem .9rem;border-radius:6px">Confirmar</button>
                </div>
            </div>
        </div>

        <style>
            .reservar-card{max-width:520px;margin:18px auto;padding:18px;background:#fff;border-radius:6px;box-shadow:0 2px 6px rgba(0,0,0,.06)}
            .horario{margin:.25rem .25rem .25rem 0;border-radius:20px;background:#eee;border:1px solid #ddd;padding:.35rem .75rem}
            .horario.active{background:#054e61;color:#fff}
            .menu-card{border:1px solid #eee;padding:.6rem;border-radius:6px}
            .btn-reservar{background:#3fc0ff;border-color:#3fc0ff;color:#fff}
            .creditos-box{border:1px solid #ccc;padding:.5rem;text-align:center;border-radius:4px}

            /* animación de puntos */
            .loader{display:flex;gap:.4rem;align-items:center;justify-content:center}
            .dot{width:12px;height:12px;border-radius:50%;background:#054e61;opacity:0}
            .dot.show{opacity:1}
        </style>

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
                    <div style="text-align:center;padding:40px 10px">
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
