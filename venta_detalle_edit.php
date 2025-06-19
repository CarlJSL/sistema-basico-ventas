<?php
include_once './conexion/cone.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $iddetalle = isset($_POST['iddetalle']) ? intval($_POST['iddetalle']) : 0;
    $idventa   = isset($_POST['idventa']) ? intval($_POST['idventa']) : 0;
    $idproducto = isset($_POST['idproducto']) ? intval($_POST['idproducto']) : 0;
    $cantidad  = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 0;
    $precio    = isset($_POST['precio']) ? floatval($_POST['precio']) : 0.0;

    if ($iddetalle && $idventa && $idproducto && $cantidad > 0 && $precio > 0) {
        // 1. Actualizar el detalle
        $update = $con->prepare("
            UPDATE detalle_venta
            SET producto_id = ?, cantidad = ?, precio_unitario = ?
            WHERE id = ?
        ");
        $update->bind_param("iidi", $idproducto, $cantidad, $precio, $iddetalle);
        $update->execute();

        // 2. Recalcular el nuevo total de la venta
        $recalc = $con->prepare("
            SELECT SUM(cantidad * precio_unitario) AS total
            FROM detalle_venta
            WHERE venta_id = ?
        ");
        $recalc->bind_param("i", $idventa);
        $recalc->execute();
        $res = $recalc->get_result()->fetch_assoc();
        $nuevoTotal = floatval($res['total']);

        // 3. Actualizar total en venta
        $updVenta = $con->prepare("UPDATE ventas SET total = ? WHERE id = ?");
        $updVenta->bind_param("di", $nuevoTotal, $idventa);
        $updVenta->execute();

        // 4. Redireccionar con éxito
        header("Location: venta_detalle.php?id=$idventa&msg=editado");
        exit;
    } else {
        header("Location: venta_detalle.php?id=$idventa&msg=incompleto");
        exit;
    }
} else {
    header("Location: venta.php");
    exit;
}
