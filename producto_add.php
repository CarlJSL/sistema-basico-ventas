<?php
include_once './conexion/cone.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre      = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $modelo      = trim($_POST['modelo']);
    $marca       = trim($_POST['marca']);
    $subcategoria = intval($_POST['subcategoria']);
    $precio      = floatval($_POST['precio']);
    $stock       = intval($_POST['stock']);

    if ($nombre && $descripcion && $modelo && $marca && $subcategoria && $precio > 0 && $stock >= 0) {
        $sql = "INSERT INTO producto 
                (prod_nombre, prod_descripcion, prod_model, prod_marca, idsubcategoria, proc_precio, prod_stock)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stm = $con->prepare($sql);
        $stm->bind_param("ssssiid", $nombre, $descripcion, $modelo, $marca, $subcategoria, $precio, $stock);
        $stm->execute();
        header("Location: producto.php?msg=agregado");
        exit;
    }
}

header("Location: producto.php?msg=incompleto");
