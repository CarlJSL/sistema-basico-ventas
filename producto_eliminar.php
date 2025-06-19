<?php
include_once './conexion/cone.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $con->query("DELETE FROM producto WHERE prod_codi = $id");
    header("Location: producto.php?msg=eliminado");
    exit;
}
header("Location: producto.php");
