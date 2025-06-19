<?php
include_once './conexion/cone.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombres   = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $direccion = trim($_POST['direccion']);
    $telefono  = trim($_POST['telefono']);
    $correo    = trim($_POST['correo']);
    $fecha     = $_POST['fecha'];

    if ($nombres && $apellidos && $direccion && $telefono && $correo && $fecha) {
        $sql = "INSERT INTO tb_cliente (client_nombres, client_apellidos, client_direccion, client_telefono, client_correo, fecha_registro)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stm = $con->prepare($sql);
        $stm->bind_param("ssssss", $nombres, $apellidos, $direccion, $telefono, $correo, $fecha);
        $stm->execute();
        header("Location: cliente.php?msg=agregado");
        exit;
    }
}
header("Location: cliente.php?msg=incompleto");
