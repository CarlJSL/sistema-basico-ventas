<?php

session_start();
include_once './cone.php';

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $usuario = isset($_POST["txtUsu"]) ? trim($_POST["txtUsu"]) : '';
    $contrasena = isset($_POST["txtPassword"]) ? trim($_POST["txtPassword"]) : '';

    if ($usuario === '' || $contrasena === '') {
        echo "❗ Todos los campos son obligatorios.";
        exit;
    }



    // Verificar si el usuario ya existe
    $stmt = $con->prepare("SELECT usu_id, usu_pass FROM tb_usuario WHERE usu_usuario = ?");

    $stmt->bind_param('s', $usuario);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $hash);
        $stmt->fetch();

        if (password_verify($contrasena, $hash)) {
            $_SESSION['Uusuario'] =  $usuario;
            $_SESSION['id'] = $id;

            header("location:../admin.php");
        }else{
            echo 'Contraseña Incorrecta';
        }
    }else{
        echo "El usuario no existe";
    }
    $stmt -> close();
    $con -> close();
}
