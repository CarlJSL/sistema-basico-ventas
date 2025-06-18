<?php
session_start();
include_once './conexion/cone.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $usuario = trim($_POST['usuario'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['pass'] ?? '';
    $rol = $_POST['rol'] ?? '';
    $estado = $_POST['estado'] ?? '';

    $_SESSION['abrir_modal'] = 'modalEditar';

    if (empty($id) || empty($usuario) || empty($email) || empty($rol) || $estado === '') {
        $_SESSION['alerta_edit'] = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            Todos los campos obligatorios deben completarse.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['alerta_edit'] = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            Correo electrónico inválido.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>';
    } elseif (!empty($pass) && strlen($pass) < 8) {
        $_SESSION['alerta_edit'] = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            La nueva contraseña debe tener al menos 8 caracteres.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>';
    } else {
        // Verifica si otro usuario ya tiene ese usuario o email
        $stmt = $con->prepare("SELECT 1 FROM tb_usuario WHERE (usu_usuario = ? OR email = ?) AND usu_id != ?");
        $stmt->bind_param("ssi", $usuario, $email, $id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $_SESSION['alerta_edit'] = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                El usuario o correo ya están registrados por otro usuario.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>';
        } else {
            if (!empty($pass)) {
                $clave_hash = password_hash($pass, PASSWORD_DEFAULT);
                $stmt = $con->prepare("UPDATE tb_usuario SET usu_usuario=?, usu_pass=?, email=?, rol=?, estado=?, fecha_actualizacion=NOW() WHERE usu_id=?");
                $stmt->bind_param("ssssii", $usuario, $clave_hash, $email, $rol, $estado, $id);
            } else {
                $stmt = $con->prepare("UPDATE tb_usuario SET usu_usuario=?, email=?, rol=?, estado=?, fecha_actualizacion=NOW() WHERE usu_id=?");
                $stmt->bind_param("sssii", $usuario, $email, $rol, $estado, $id);
            }

            if ($stmt->execute()) {
                $_SESSION['alerta_success'] = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    Usuario actualizado correctamente.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>';
                unset($_SESSION['abrir_modal']);
            } else {
                $_SESSION['alerta_edit'] = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Error al actualizar el usuario.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>';
            }
        }

        $stmt->close();
    }

    header("Location: usuario.php");
    exit();
}
?>
