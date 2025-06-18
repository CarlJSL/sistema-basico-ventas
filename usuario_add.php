<?php
session_start();
include_once './conexion/cone.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_tipo'] ?? '') === 'add') {
    $usuario = trim($_POST['usuario'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['pass'] ?? '';
    $rol = $_POST['rol'] ?? '';
    $estado = $_POST['estado'] ?? '';

    $_SESSION['abrir_modal'] = 'modalAgregar';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['alerta_add'] = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            Correo no válido.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>';
        header("Location: usuario.php");
        exit();
    }

    if (strlen($pass) < 8) {
        $_SESSION['alerta_add'] = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            La contraseña debe tener al menos 8 caracteres.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>';
        header("Location: usuario.php");
        exit();
    }

    // Verifica si el usuario o email ya existen
    $stmt = $con->prepare("SELECT 1 FROM tb_usuario WHERE usu_usuario = ? OR email = ?");
    $stmt->bind_param("ss", $usuario, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['alerta_add'] = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            El usuario o correo ya está registrado.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>';
    } else {
        $clave_hash = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = $con->prepare("INSERT INTO tb_usuario (usu_usuario, usu_pass, email, rol, estado, fecha_creacion, fecha_actualizacion) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->bind_param("ssssi", $usuario, $clave_hash, $email, $rol, $estado);

        if ($stmt->execute()) {
            $_SESSION['alerta_success'] = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                Usuario registrado correctamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>';
            unset($_SESSION['abrir_modal']);
        } else {
            $_SESSION['alerta_add'] = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                Error al registrar el usuario.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>';
        }
    }

    $stmt->close();
    header("Location: usuario.php");
    exit();
}
?>
