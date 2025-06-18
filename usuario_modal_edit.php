<?php // usuario_modal_edit.php ?>
<div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="usuario_edit.php" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <?php if (!empty($alerta_edit)) echo $alerta_edit; ?>
                <input type="hidden" name="id" id="edit-id">
                <input type="text" name="usuario" id="edit-usuario" class="form-control mb-2" required>
                <input type="email" name="email" id="edit-email" class="form-control mb-2" required>
                <input type="password" name="pass" id="edit-pass" class="form-control mb-2" placeholder="Nueva contraseña (opcional)">
                <select name="rol" id="edit-rol" class="form-control mb-2" required>
                    <option value="admin">Admin</option>
                    <option value="vendedor">Vendedor</option>
                </select>
                <select name="estado" id="edit-estado" class="form-control mb-2" required>
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
    const modalEditar = document.getElementById('modalEditar');
    modalEditar.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        document.getElementById('edit-id').value = button.getAttribute('data-id');
        document.getElementById('edit-usuario').value = button.getAttribute('data-usuario');
        document.getElementById('edit-email').value = button.getAttribute('data-email');
        document.getElementById('edit-rol').value = button.getAttribute('data-rol');
        document.getElementById('edit-estado').value = button.getAttribute('data-estado');

        document.getElementById('edit-pass').value = '';

    });
</script>