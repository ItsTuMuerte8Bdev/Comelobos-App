@php
  $scope = $scope ?? 'user';
  $s = $scope === 'admin' ? 'Admin' : '';
@endphp

{{-- Reusable change-password panel, modal and client JS. Use: @include('partials.change_password', ['scope' => 'user'|'admin']) --}}
<div>
  <div class="d-flex justify-content-center mt-2">
    <button type="button" id="changePasswordBtn{{ $s }}" class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#passwordCollapse{{ $s }}" aria-expanded="false">{{ $scope === 'admin' ? 'Cambiar Contraseña (Admin)' : 'Cambiar Contraseña' }}</button>
  </div>

  <div class="collapse mt-3" id="passwordCollapse{{ $s }}">
    <form id="passwordForm{{ $s }}" method="POST" action="{{ route('informacion.password.update') }}">
    @csrf
    <div class="card p-3 mb-3">
      <h5>Cambiar contraseña</h5>
      <div class="mb-3">
        <label class="form-label">Contraseña actual</label>
        <input type="password" name="current_password" id="current_password{{ $s }}" class="form-control" value="{{ old('current_password') }}">
        <div class="text-danger small d-none" id="err_current_password{{ $s }}"></div>
        @error('current_password') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>
      <div class="mb-3">
        <label class="form-label">Nueva contraseña</label>
        <input type="password" name="password" id="password{{ $s }}" class="form-control">
        <div class="text-danger small d-none" id="err_password{{ $s }}"></div>
        @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>
      <div class="mb-3">
        <label class="form-label">Confirmar nueva contraseña</label>
        <input type="password" name="password_confirmation" id="password_confirmation{{ $s }}" class="form-control">
        <div class="text-danger small d-none" id="err_password_confirmation{{ $s }}"></div>
      </div>
      <div class="d-flex justify-content-center mt-2">
        <button type="button" id="openPasswordConfirmBtn{{ $s }}" class="btn btn-primary">Continuar</button>
      </div>
    </div>
  </form>
  </div>

  <!-- Confirmation modal -->
  <div class="modal fade" id="changePasswordModal{{ $s }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body text-center">
          <p>¿Deseas cambiar tu contraseña?</p>
          <div class="d-flex justify-content-center gap-2 mt-3">
            <button type="button" id="confirmChangePasswordBtn{{ $s }}" class="btn btn-success">Confirmar</button>
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Regresar</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function(){
      var s = '{{ $s }}';
      console.log('change_password init', s);
      var changeBtn = document.getElementById('changePasswordBtn' + s);
      var passwordForm = document.getElementById('passwordForm' + s);
      var passwordCollapseEl = document.getElementById('passwordCollapse' + s);
      var openConfirm = document.getElementById('openPasswordConfirmBtn' + s);
      var confirmBtn = document.getElementById('confirmChangePasswordBtn' + s);
      var errCurrent = document.getElementById('err_current_password' + s);
      var errPassword = document.getElementById('err_password' + s);
      var errPasswordConf = document.getElementById('err_password_confirmation' + s);

      if (!changeBtn || !passwordForm) return;

      // Use Bootstrap collapse instance to control show/hide and sync button text
      var bsCollapse = null;
      function initCollapse(){
        if (!passwordCollapseEl) return false;
        if (typeof window.bootstrap === 'undefined') return false;
        try{
          bsCollapse = new bootstrap.Collapse(passwordCollapseEl, { toggle: false });
          passwordCollapseEl.addEventListener('show.bs.collapse', function(){ changeBtn.textContent = 'Ocultar panel'; changeBtn.setAttribute('aria-expanded', 'true'); });
          passwordCollapseEl.addEventListener('hide.bs.collapse', function(){ changeBtn.textContent = (s === 'Admin') ? 'Cambiar Contraseña (Admin)' : 'Cambiar Contraseña'; changeBtn.setAttribute('aria-expanded', 'false'); });
          return true;
        } catch(e){ console.warn('collapse init failed', e); return false; }
      }

      if (!initCollapse()){
        var _tries = 0;
        var _t = setInterval(function(){
          _tries++; if (initCollapse() || _tries > 40) clearInterval(_t);
        }, 50);
      }

      // click handled by Bootstrap collapse via data attributes; keep console log for debug
      changeBtn.addEventListener('click', function(e){
        console.log('changeBtn clicked', s);
        // let bootstrap handle the collapse toggle
      });

      function clearErrors(){
        [errCurrent, errPassword, errPasswordConf].forEach(function(el){ if(el){ el.classList.add('d-none'); el.textContent = ''; } });
      }

      if (openConfirm) {
        openConfirm.addEventListener('click', function(){
          console.log('openConfirm clicked', s);
          clearErrors();
          var current = document.getElementById('current_password' + s).value.trim();
          var pw = document.getElementById('password' + s).value;
          var pwc = document.getElementById('password_confirmation' + s).value;
          var ok = true;

          if (!current) {
            if (errCurrent) { errCurrent.classList.remove('d-none'); errCurrent.textContent = 'Debe ingresar su contraseña actual.'; }
            ok = false;
          }
          if (!pw || pw.length < 8) {
            if (errPassword) { errPassword.classList.remove('d-none'); errPassword.textContent = 'La contraseña debe tener al menos 8 caracteres.'; }
            ok = false;
          }
          if (pw !== pwc) {
            if (errPasswordConf) { errPasswordConf.classList.remove('d-none'); errPasswordConf.textContent = 'La nueva contraseña no coincide.'; }
            ok = false;
          }

          if (ok) {
            console.log('validation ok, show modal', s);
            var modalEl = document.getElementById('changePasswordModal' + s);
            if (typeof window.bootstrap !== 'undefined' && modalEl) {
              try{ var modal = new bootstrap.Modal(modalEl); modal.show(); }
              catch(e){ console.warn('modal show failed', e); modalEl.classList.add('show'); modalEl.style.display = 'block'; }
            } else if (modalEl) {
              modalEl.classList.add('show'); modalEl.style.display = 'block';
            } else {
              alert('Confirmación: formulario listo para enviar (sin modal)');
            }
          }
        });
      }

      if (confirmBtn) {
        confirmBtn.addEventListener('click', function(){
          console.log('confirmChangePassword clicked', s);
          var form = document.getElementById('passwordForm' + s);
          if (form) form.submit();
        });
      }

      // If server returned errors, expand panel on load using Collapse API
      @if($errors->has('current_password') || $errors->has('password') || $errors->has('password_confirmation'))
        if (bsCollapse) bsCollapse.show();
        else {
          var pf = document.getElementById('passwordForm' + s);
          var cb = document.getElementById('changePasswordBtn' + s);
          if (pf) pf.classList.remove('d-none');
          if (cb) cb.textContent = 'Ocultar panel';
        }
      @endif
    });
  </script>
</div>
