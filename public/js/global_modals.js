// Global modal helpers (standalone, no bundler required)
(function(){
  function getEl(id){ return document.getElementById(id); }

  function initModalHelpers(){
    window.showConfirmModal = function(message, title){
      return new Promise(function(resolve){
        var modalEl = getEl('globalConfirmModal');
        var bodyEl = getEl('globalConfirmModalBody');
        var titleEl = getEl('globalConfirmModalLabel');
        var btnOk = getEl('globalConfirmOk');
        var btnCancel = getEl('globalConfirmCancel');

        if (!modalEl || !btnOk || !btnCancel || typeof bootstrap === 'undefined'){
          resolve(window.confirm(message));
          return;
        }

        if (bodyEl) bodyEl.textContent = message || '';
        if (titleEl) titleEl.textContent = title || 'Confirmar';

        var bsModal = new bootstrap.Modal(modalEl);

        function cleanup(){ btnOk.removeEventListener('click', onOk); btnCancel.removeEventListener('click', onCancel); }
        function onOk(){ cleanup(); bsModal.hide(); resolve(true); }
        function onCancel(){ cleanup(); bsModal.hide(); resolve(false); }

        btnOk.addEventListener('click', onOk);
        btnCancel.addEventListener('click', onCancel);

        bsModal.show();
      });
    };

    window.showAlertModal = function(message, title){
      return new Promise(function(resolve){
        var modalEl = getEl('globalAlertModal');
        var bodyEl = getEl('globalAlertModalBody');
        var titleEl = getEl('globalAlertModalLabel');
        var btnOk = getEl('globalAlertOk');

        if (!modalEl || !btnOk || typeof bootstrap === 'undefined'){
          alert(message);
          resolve();
          return;
        }

        if (bodyEl) bodyEl.textContent = message || '';
        if (titleEl) titleEl.textContent = title || 'Aviso';

        var bsModal = new bootstrap.Modal(modalEl);
        function cleanup(){ btnOk.removeEventListener('click', onOk); }
        function onOk(){ cleanup(); bsModal.hide(); resolve(); }
        btnOk.addEventListener('click', onOk);
        bsModal.show();
      });
    };

    // forms with needs-confirm
    document.addEventListener('submit', function(e){
      var form = e.target;
      if (form && form.classList && form.classList.contains('needs-confirm')){
        e.preventDefault();
        var msg = form.getAttribute('data-confirm-message') || '¿Estás seguro?';
        window.showConfirmModal(msg).then(function(ok){ if (ok) form.submit(); });
      }
    }, true);
  }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initModalHelpers);
  else initModalHelpers();
})();
