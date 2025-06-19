<?php
include_once './conexion/cone.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id        = intval($_POST['id']);
    $nombres   = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $direccion = trim($_POST['direccion']);
    $telefono  = trim($_POST['telefono']);
    $correo    = trim($_POST['correo']);
    $fecha     = $_POST['fecha'];

    if ($id && $nombres && $apellidos && $direccion && $telefono && $correo && $fecha) {
        $sql = "UPDATE tb_cliente SET 
                    client_nombres=?, client_apellidos=?, client_direccion=?, 
                    client_telefono=?, client_correo=?, fecha_registro=?
                WHERE client_id=?";
        $stm = $con->prepare($sql);
        $stm->bind_param("ssssssi", $nombres, $apellidos, $direccion, $telefono, $correo, $fecha, $id);
        $stm->execute();
        header("Location: cliente.php?msg=editado");
        exit;
    }
}
header("Location: cliente.php?msg=incompleto");
