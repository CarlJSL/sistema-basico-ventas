<?php
include_once './conexion/cone.php';
$sql = "SELECT * FROM tb_cliente";
$res = $con->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<?php include_once './includes/head.php'; ?>

<body>
    <?php include_once './includes/header.php'; ?>
    <div class="content">
        <div class="container-fluid mt-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Listado de Clientes</h3>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregar">
                    <i class="fas fa-plus"></i> Agregar Cliente
                </button>
            </div>

            <?php if (isset($_GET['msg'])) : ?>
                <?php
                $tipo = ($_GET['msg'] === 'incompleto') ? 'warning' : 'success';
                ?>
                <div class="alert alert-<?= $tipo ?>">
                    <?php
                    if ($_GET['msg'] == 'agregado') echo "Cliente agregado correctamente.";
                    elseif ($_GET['msg'] == 'editado') echo "Cliente actualizado correctamente.";
                    elseif ($_GET['msg'] == 'eliminado') echo "Cliente eliminado correctamente.";
                    elseif ($_GET['msg'] == 'incompleto') echo "Por favor completa todos los campos.";
                    else echo "Acción no reconocida.";
                    ?>
                </div>
            <?php endif; ?>


            <table class="table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Fecha Registro</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $res->fetch_assoc()) : ?>
                        <tr>
                            <td><?= $row['client_id'] ?></td>
                            <td><?= $row['client_nombres'] ?></td>
                            <td><?= $row['client_apellidos'] ?></td>
                            <td><?= $row['client_direccion'] ?></td>
                            <td><?= $row['client_telefono'] ?></td>
                            <td><?= $row['client_correo'] ?></td>
                            <td><?= $row['fecha_registro'] ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditar" data-id="<?= $row['client_id'] ?>" data-nombres="<?= htmlspecialchars($row['client_nombres']) ?>" data-apellidos="<?= htmlspecialchars($row['client_apellidos']) ?>" data-direccion="<?= htmlspecialchars($row['client_direccion']) ?>" data-telefono="<?= $row['client_telefono'] ?>" data-correo="<?= $row['client_correo'] ?>" data-fecha="<?= $row['fecha_registro'] ?>">
                                    <i class="fa fa-pencil"></i>
                                </button>
                                <a href="cliente_eliminar.php?id=<?= $row['client_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este cliente?')">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Modal Agregar -->
        <div class="modal fade" id="modalAgregar" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="cliente_add.php" method="POST" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Cliente</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input name="nombres" class="form-control mb-2" placeholder="Nombres" required>
                        <input name="apellidos" class="form-control mb-2" placeholder="Apellidos" required>
                        <input name="direccion" class="form-control mb-2" placeholder="Dirección" required>
                        <input name="telefono" class="form-control mb-2" placeholder="Teléfono" required>
                        <input name="correo" type="email" class="form-control mb-2" placeholder="Correo" required>
                        <input name="fecha" type="date" class="form-control mb-2" placeholder="Fecha de Registro" required>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success">Guardar</button>
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Editar -->
        <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="cliente_actu.php" method="POST" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Cliente</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editId">
                        <input name="nombres" id="editNombres" class="form-control mb-2" required>
                        <input name="apellidos" id="editApellidos" class="form-control mb-2" required>
                        <input name="direccion" id="editDireccion" class="form-control mb-2" required>
                        <input name="telefono" id="editTelefono" class="form-control mb-2" required>
                        <input name="correo" id="editCorreo" type="email" class="form-control mb-2" required>
                        <input name="fecha" id="editFecha" type="date" class="form-control mb-2" required>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary">Actualizar</button>
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const editarModal = document.getElementById('modalEditar');
        editarModal.addEventListener('show.bs.modal', e => {
            const btn = e.relatedTarget;
            document.getElementById('editId').value = btn.getAttribute('data-id');
            document.getElementById('editNombres').value = btn.getAttribute('data-nombres');
            document.getElementById('editApellidos').value = btn.getAttribute('data-apellidos');
            document.getElementById('editDireccion').value = btn.getAttribute('data-direccion');
            document.getElementById('editTelefono').value = btn.getAttribute('data-telefono');
            document.getElementById('editCorreo').value = btn.getAttribute('data-correo');
            document.getElementById('editFecha').value = btn.getAttribute('data-fecha');
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include_once './includes/footer.php'; ?>
</body>

</html>