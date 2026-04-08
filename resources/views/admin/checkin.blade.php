@php $activeTab = 'admin_checkin'; @endphp
@extends('admin.layout')

@section('content')
  <style>
    /* Conservar espacio para header + navbar y evitar desbordes */
    .admin-scroll{max-height: calc(100vh - 140px); overflow-y:auto; overflow-x:hidden; padding-right:8px; box-sizing:border-box}
    .admin-scroll *{box-sizing:border-box}
    /* Ajustes del visor para que no provoque overflow vertical */
    #qr-container{height: min(60vh,480px); max-height:60vh; overflow:hidden}
    #qr-video{width:100%;height:100%;object-fit:cover;display:block}
  </style>

  <div class="admin-scroll">
    <div class="container">
      <div class="row">
        <div class="col-12">
        <h4>Check-In (Administrativo)</h4>
        <p>Escanea QR de compra para marcar check-in. Usa la cámara del dispositivo.</p>

        <div class="card mb-3">
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <label class="form-label">Visor de cámara (QR)</label>
                <div id="qr-container" style="border:1px solid #e9ecef;border-radius:6px;overflow:hidden;height:480px;">
                  <video id="qr-video" playsinline style="width:100%;height:100%;object-fit:cover;background:#000;display:block"></video>
                </div>
                <div class="row g-2 mt-2">
                  <div class="col-6"><button id="start-btn" type="button" class="btn btn-primary w-100">Iniciar cámara</button></div>
                  <div class="col-6"><button id="stop-btn" type="button" class="btn btn-secondary w-100">Detener</button></div>
                </div>
                <small class="text-muted">Permite usar la cámara trasera en móviles.</small>
              </div>

              <div class="col-md-6">
                <label class="form-label">Resultado QR</label>
                <input id="qr-result" class="form-control mb-2" type="text" placeholder="Resultado del QR" readonly>
                <div class="row g-2">
                  <div class="col-6"><button id="clear-btn" type="button" class="btn btn-secondary w-100">Deshacer</button></div>
                  <div class="col-6"><button id="mark-btn" type="button" class="btn btn-danger w-100">Marcar</button></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Lista de usuarios removida: el check-in se realiza únicamente por QR -->
      </div>
    </div>
  </div>

  <!-- Hidden canvas used to capture frames for decoding -->
  <canvas id="qr-canvas" style="display:none"></canvas>

  <style>
    /* Make QR container responsive: fixed height on desktop, vh on mobile */
    @media (max-width: 768px){
      #qr-container{height:50vh !important}
    }
  </style>
  <!-- jsQR from CDN -->
  <script src="https://unpkg.com/jsqr/dist/jsQR.js"></script>
  <script>
    let video = document.getElementById('qr-video');
    let canvas = document.getElementById('qr-canvas');
    let ctx = canvas.getContext('2d');
    let stream = null;
    let scanning = false;

    async function startScanner(){
      if (scanning) return;
      try{
        stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
        video.srcObject = stream;
        await video.play();
        scanning = true;
        tick();
      }catch(e){
        alert('No se pudo acceder a la cámara: ' + e.message);
      }
    }

    function stopScanner(){
      scanning = false;
      if (stream){
        stream.getTracks().forEach(t=>t.stop());
        stream = null;
      }
      video.pause();
      video.srcObject = null;
    }

    function tick(){
      if (!scanning) return;
      if (video.readyState === video.HAVE_ENOUGH_DATA){
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        ctx.drawImage(video,0,0,canvas.width,canvas.height);
        let imageData = ctx.getImageData(0,0,canvas.width,canvas.height);
        let code = jsQR(imageData.data, imageData.width, imageData.height, { inversionAttempts: 'dontInvert' });
        if (code){
          document.getElementById('qr-result').value = code.data;
          stopScanner();
          return;
        }
      }
      requestAnimationFrame(tick);
    }

    document.getElementById('start-btn').addEventListener('click', startScanner);
    document.getElementById('stop-btn').addEventListener('click', stopScanner);
    document.getElementById('clear-btn').addEventListener('click', ()=>{document.getElementById('qr-result').value='';});
    document.getElementById('mark-btn').addEventListener('click', ()=>{
      let val = document.getElementById('qr-result').value;
      if (!val){ alert('No hay QR escaneado'); return; }
      // Aquí se puede enviar val al servidor para marcar check-in
      alert('Marcado con QR: ' + val);
    });
  </script>
@endsection
