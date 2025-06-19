<?php
include_once './conexion/cone.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente_id = isset($_POST['cliente_id']) ? intval($_POST['cliente_id']) : 0;
    $productosJSON = $_POST['productos'] ?? '';
    $productos = json_decode($productosJSON, true);

    if ($cliente_id && is_array($productos) && count($productos) > 0) {
        $total = 0;
        foreach ($productos as $p) {
            $total += floatval($p['subtotal']);
        }

        // Insertar en tabla venta
        $stmt = $con->prepare("INSERT INTO ventas (cliente_id, total, fecha) VALUES (?, ?, NOW())");
        $stmt->bind_param("id", $cliente_id, $total);
        if (!$stmt->execute()) {
            die("❌ Error al insertar venta: " . $stmt->error);
        }

        $venta_id = $stmt->insert_id;

        // Insertar detalle venta
        $detStmt = $con->prepare("INSERT INTO detalle_venta (venta_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
        $updStockStmt = $con->prepare("UPDATE producto SET prod_stock = prod_stock - ? WHERE prod_codi = ?");

        foreach ($productos as $item) {
            $idProd = intval($item['id']);
            $cant = intval($item['cantidad']);
            $precio = floatval($item['precio']);

            $detStmt->bind_param("iiid", $venta_id, $idProd, $cant, $precio);
            $detStmt->execute();

            // Actualizar stock
            $updStockStmt->bind_param("ii", $cant, $idProd);
            $updStockStmt->execute();
        }

        header("Location: venta.php?msg=registrado");
        exit;
    } else {
        header("Location: venta.php?msg=incompleto");
        exit;
    }
} else {
    header("Location: venta.php");
    exit;
}
