<?php
include_once 'conexion/cone.php';

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $usuario = isset($_POST["txtUsu"]) ? trim($_POST["txtUsu"]) : '';
    $contrasena = isset($_POST["txtPassword"]) ? trim($_POST["txtPassword"]) : '';

    if ($usuario === '' || $contrasena === '') {
        echo "❗ Todos los campos son obligatorios.";
        exit;
    }

    $hash = password_hash($contrasena, PASSWORD_DEFAULT);

    // Verificar si el usuario ya existe
    $stmt = $con->prepare("SELECT usu_id FROM tb_usuario WHERE usu_usuario = ?");
    $stmt->bind_param('s', $usuario);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "⚠️ El usuario ya existe.";
    } else {
        // Insertar nuevo usuario
        $insertar = $con->prepare("INSERT INTO tb_usuario (usu_usuario, usu_pass) VALUES (?, ?)");
        $insertar->bind_param("ss", $usuario, $hash);

        if ($insertar->execute()) {
            echo "✅ Usuario registrado correctamente.";
        } else {
            echo "❌ Error al registrar el usuario.";
        }

        $insertar->close();
    }

    $stmt->close();
    $con->close();
}
?>
