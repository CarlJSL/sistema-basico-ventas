<?php
session_start();
include_once './cone.php';

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $usuario = trim($_POST["txtUsu"] ?? '');
    $contrasena = trim($_POST["txtPassword"] ?? '');

    if ($usuario === '' || $contrasena === '') {
        $_SESSION['login_error'] = 'Todos los campos son obligatorios.';
        header('Location: ../index.php');
        exit;
    }

    // Agregamos usu_usuario al SELECT para guardar en sesión
    $stmt = $con->prepare("SELECT usu_id, usu_usuario, usu_pass, estado FROM tb_usuario WHERE usu_usuario = ?");
    $stmt->bind_param('s', $usuario);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        // Ahora hacemos bind de usu_usuario también
        $stmt->bind_result($id, $usu_usuario, $hash, $estado);
        $stmt->fetch();

        if (password_verify($contrasena, $hash)) {
            if ($estado == '1') {
                $_SESSION['usuario'] = $usu_usuario; // Aquí se guarda correctamente
                $_SESSION['id'] = $id;

                header("Location: ../admin.php");
                exit;
            } else {
                $_SESSION['login_error'] = 'El usuario está inactivo.';
            }
        } else {
            $_SESSION['login_error'] = 'Contraseña incorrecta.';
        }
    } else {
        $_SESSION['login_error'] = 'El usuario no existe.';
    }

    $stmt->close();
    $con->close();
    header('Location: ../index.php');
    exit;
}
?>
