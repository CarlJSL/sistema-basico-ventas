<div class="modal fade" id="modalAgregar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="usuario_add.php" class="modal-content" autocomplete="off">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <?php if (!empty($alerta_add)) echo $alerta_add; ?>
                
                <input type="text" name="usuario" class="form-control mb-2" placeholder="Usuario" required value="" autocomplete="off">
                
                <input type="email" name="email" class="form-control mb-2" placeholder="Correo electrónico" required value="" autocomplete="off">
                
                <input type="password" name="pass" class="form-control mb-2" placeholder="Contraseña" minlength="8" required value="" autocomplete="new-password">
                
                <select name="rol" class="form-control mb-2" required>
                    <option value="" disabled selected>Seleccione rol</option>
                    <option value="admin">Admin</option>
                    <option value="vendedor">Vendedor</option>
                </select>
                
                <select name="estado" class="form-control mb-2" required>
                    <option value="" disabled selected>Seleccione estado</option>
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>

                <input type="hidden" name="form_tipo" value="add">
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Guardar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </form>
    </div>
</div>
