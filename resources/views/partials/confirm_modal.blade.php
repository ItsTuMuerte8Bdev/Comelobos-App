<!-- Global confirm & alert modals reutilizables -->
<div class="modal fade" id="globalConfirmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="globalConfirmModalLabel">Confirmar</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center" id="globalConfirmModalBody">¿Estás seguro?</div>
      <div class="modal-footer d-flex flex-column gap-2">
        <button type="button" class="btn btn-primary btn-lg w-100" data-bs-dismiss="modal" id="globalConfirmCancel">Cancelar</button>
        <button type="button" class="btn btn-warning btn-lg w-100" id="globalConfirmOk">Confirmar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="globalAlertModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="globalAlertModalLabel">Aviso</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center" id="globalAlertModalBody">Información</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-lg w-100" data-bs-dismiss="modal" id="globalAlertOk">OK</button>
      </div>
    </div>
  </div>
</div>
