<?php
session_start();
include_once './conexion/cone.php';

if (!$con)
    die("Error de Conexión: " . mysqli_connect_error());

$sql = "SELECT * FROM tb_usuario";
$result = mysqli_query($con, $sql);

// Recupera alertas y control de modal desde la sesión
$alerta_add = $_SESSION['alerta_add'] ?? "";
$alerta_edit = $_SESSION['alerta_edit'] ?? "";
$alerta_success = $_SESSION['alerta_success'] ?? "";
$abrir_modal = $_SESSION['abrir_modal'] ?? "";

// Limpia variables de sesión
unset($_SESSION['alerta_add'], $_SESSION['alerta_edit'], $_SESSION['alerta_success'], $_SESSION['abrir_modal']);
?>

<!DOCTYPE html>
<html lang="es">

<?php include_once './includes/head.php'; ?>

<body>
    <?php include_once './includes/header.php'; ?>

    <div class="content">
        <div class="container-fluid text-center mt-6">
            <div class="d-flex justify-content-between align-items-center" style="padding-top: 30px;">
                <h3 class="mb-0">Listado de Usuarios</h3>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregar">Agregar</button>
            </div>

            <br>

            <!-- MOSTRAR ALERTA ÉXITO GENERAL -->
            <?php if (!empty($alerta_success)) echo $alerta_success; ?>

            <table class="table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Fecha Creación</th>
                        <th>Última Actualización</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?= $row["usu_id"] ?></td>
                            <td><?= $row["usu_usuario"] ?></td>
                            <td><?= $row["email"] ?></td>
                            <td><?= ucfirst($row["rol"]) ?></td>
                            <td>
                                <?php if ($row["estado"] == 1): ?>
                                    <span class="badge bg-success">Activo</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $row["fecha_creacion"] ?></td>
                            <td><?= $row["fecha_actualizacion"] ?></td>
                            <td>
                                <button class="btn btn-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditar"
                                    data-id="<?= $row['usu_id'] ?>"
                                    data-usuario="<?= $row['usu_usuario'] ?>"
                                    data-email="<?= $row['email'] ?>"
                                    data-rol="<?= $row['rol'] ?>"
                                    data-estado="<?= $row['estado'] ?>">
                                    <i class="fa fa-pencil"></i> Editar
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODALES -->
    <?php include './usuario_modal_add.php'; ?>
    <?php include './usuario_modal_edit.php'; ?>
    <?php include_once './includes/footer.php'; ?>

    <!-- REABRIR MODAL AUTOMÁTICAMENTE -->
    <?php if (!empty($abrir_modal)): ?>
        <script>
            window.onload = () => {
                const modal = new bootstrap.Modal(document.getElementById('<?= $abrir_modal ?>'));
                modal.show();
            };
        </script>
    <?php endif; ?>
</body>

</html>
