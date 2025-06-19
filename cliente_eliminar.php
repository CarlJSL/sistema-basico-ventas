<?php
include_once './conexion/cone.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $con->query("DELETE FROM tb_cliente WHERE client_id = $id");
    header("Location: cliente.php?msg=eliminado");
    exit;
}
header("Location: cliente.php");
