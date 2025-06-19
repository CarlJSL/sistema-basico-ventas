<?php
include_once './conexion/cone.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id          = intval($_POST['id']);
    $nombre      = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $modelo      = trim($_POST['modelo']);
    $marca       = trim($_POST['marca']);
    $subcategoria = intval($_POST['subcategoria']);
    $precio      = floatval($_POST['precio']);
    $stock       = intval($_POST['stock']);

    if ($id && $nombre && $descripcion && $modelo && $marca && $subcategoria && $precio > 0 && $stock >= 0) {
        $sql = "UPDATE producto SET 
                    prod_nombre = ?, prod_descripcion = ?, prod_model = ?, 
                    prod_marca = ?, idsubcategoria = ?, proc_precio = ?, prod_stock = ?
                WHERE prod_codi = ?";
        $stm = $con->prepare($sql);
        $stm->bind_param("ssssiidi", $nombre, $descripcion, $modelo, $marca, $subcategoria, $precio, $stock, $id);
        $stm->execute();
        header("Location: producto.php?msg=editado");
        exit;
    }
}

header("Location: producto.php?msg=incompleto");
