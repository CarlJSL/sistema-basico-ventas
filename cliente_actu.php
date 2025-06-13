<?php
session_start();
require_once './conexion/cone.php';

// Generar token CSRF si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Validar si se recibe ID por GET
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idcliente = intval($_GET['id']);
    $sql = "SELECT * FROM tb_cliente WHERE client_id = ?";
    $stm = $con->prepare($sql);
    $stm->bind_param('i', $idcliente);
    $stm->execute();
    $result = $stm->get_result();
    $Cliente = $result->fetch_assoc();

    if (!$Cliente) {
        die("Cliente no encontrado");
    }
} else {
    die("ID de Cliente no especificado o inválido");
}

// Procesar actualización
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Token CSRF inválido");
    }

    $idcliente       = intval($_POST["client_id"]);
    $nombres         = trim($_POST["client_nombres"]);
    $apellidos       = trim($_POST["client_apellidos"]);
    $direccion       = trim($_POST["client_direccion"]);
    $telefono        = trim($_POST["client_telefono"]);
    $correo          = trim($_POST["client_correo"]);
    $fecha_registro  = trim($_POST["fecha_registro"]);

    $sql = "UPDATE tb_cliente 
            SET client_nombres=?, client_apellidos=?, client_direccion=?, client_telefono=?, client_correo=?, fecha_registro=?
            WHERE client_id=?";

    $stm = $con->prepare($sql);
    $stm->bind_param("ssssssi", $nombres, $apellidos, $direccion, $telefono, $correo, $fecha_registro, $idcliente);

    if ($stm->execute()) {
        $_SESSION['msg'] = "Datos actualizados correctamente";
        header("Location: cliente.php");
        exit();
    } else {
        $error = "Error al actualizar: " . $stm->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<?php include_once './includes/head.php'; ?>

<body>
<?php include_once './includes/header.php'; ?>

<div class="content">
    <div class="container-fluid container-form">
        <br>
        <h2 class="mb-4">Editar Datos del Cliente</h2>
        <br>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="client_id" value="<?php echo $Cliente['client_id']; ?>">

            <div class="form-group">
                <label for="client_id">ID Cliente</label>
                <input type="text" name="client_id" class="form-control" value="<?php echo htmlspecialchars($Cliente['client_id']); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="client_nombres">Nombres</label>
                <input type="text" name="client_nombres" class="form-control" value="<?php echo htmlspecialchars($Cliente['client_nombres']); ?>" required>
            </div>
            <div class="form-group">
                <label for="client_apellidos">Apellidos</label>
                <input type="text" name="client_apellidos" class="form-control" value="<?php echo htmlspecialchars($Cliente['client_apellidos']); ?>" required>
            </div>
            <div class="form-group">
                <label for="client_direccion">Dirección</label>
                <input type="text" name="client_direccion" class="form-control" value="<?php echo htmlspecialchars($Cliente['client_direccion']); ?>" required>
            </div>
            <div class="form-group">
                <label for="client_telefono">Teléfono</label>
                <input type="text" name="client_telefono" class="form-control" value="<?php echo htmlspecialchars($Cliente['client_telefono']); ?>" required>
            </div>
            <div class="form-group">
                <label for="client_correo">Correo</label>
                <input type="email" name="client_correo" class="form-control" value="<?php echo htmlspecialchars($Cliente['client_correo']); ?>" required>
            </div>
            <div class="form-group">
                <label for="fecha_registro">Fecha de Registro</label>
                <input type="date" name="fecha_registro" class="form-control" value="<?php echo htmlspecialchars($Cliente['fecha_registro']); ?>" required>
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i> Actualizar
                </button>
                <a href="cliente.php" class="btn btn-danger">
                    <i class="fa fa-ban"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php include_once './includes/footer.php'; ?>
</body>
</html>
