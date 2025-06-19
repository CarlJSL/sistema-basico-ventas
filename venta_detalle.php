<?php
include_once './conexion/cone.php';

$idventa = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Obtener venta
$sqlVenta = $con->prepare("SELECT v.*, c.client_nombres FROM ventas v JOIN tb_cliente c ON v.cliente_id = c.client_id WHERE v.id = ?");
$sqlVenta->bind_param("i", $idventa);
$sqlVenta->execute();
$resVenta = $sqlVenta->get_result()->fetch_assoc();

if (!$resVenta) {
    die("Venta no encontrada.");
}

// Obtener productos vendidos
$detalle = $con->prepare("
    SELECT d.id, p.prod_nombre, d.cantidad, d.precio_unitario, p.prod_codi
    FROM detalle_venta d
    JOIN producto p ON d.producto_id = p.prod_codi
    WHERE d.venta_id = ?");
$detalle->bind_param("i", $idventa);
$detalle->execute();
$resDetalle = $detalle->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<?php include_once './includes/head.php'; ?>

<body>
    <?php include_once './includes/header.php'; ?>

    <div class="content">
        <div class="container mt-4">
            <h3>Detalle de Venta</h3>
            <?php if (isset($_GET['msg']) && $_GET['msg'] === 'editado') : ?>
                <div class="alert alert-success">Detalle actualizado correctamente.</div>
            <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'incompleto') : ?>
                <div class="alert alert-warning">Faltan datos para actualizar.</div>
            <?php endif; ?>

            <p><strong>Cliente:</strong> <?= htmlspecialchars($resVenta['client_nombres']) ?></p>
            <p><strong>Fecha:</strong> <?= $resVenta['fecha'] ?></p>
            <p><strong>Total:</strong> S/. <?= number_format($resVenta['total'], 2) ?></p>

            <table class="table table-bordered text-center">
                <thead class="table-light">
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $resDetalle->fetch_assoc()) : ?>
                        <tr>
                            <td><?= $row['prod_nombre'] ?></td>
                            <td><?= $row['cantidad'] ?></td>
                            <td>S/. <?= number_format($row['precio_unitario'], 2) ?></td>
                            <td>S/. <?= number_format($row['cantidad'] * $row['precio_unitario'], 2) ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditar" data-id="<?= $row['id'] ?>" data-idprod="<?= $row['prod_codi'] ?>" data-nombre="<?= htmlspecialchars($row['prod_nombre']) ?>" data-cantidad="<?= $row['cantidad'] ?>" data-precio="<?= $row['precio_unitario'] ?>">
                                    <i class="fa fa-pencil"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <a href="venta.php" class="btn btn-secondary">Volver</a>
        </div>

        <!-- MODAL EDITAR PRODUCTO DE LA VENTA -->
        <!-- MODAL EDITAR DETALLE -->
        <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="venta_detalle_edit.php" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Detalle de Venta</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="iddetalle" id="editIdDetalle">
                        <input type="hidden" name="idventa" value="<?= $idventa ?>">

                        <div class="mb-3">
                            <label>Producto</label>
                            <select name="idproducto" id="editProducto" class="form-select" required>
                                <option value="">Seleccione producto</option>
                                <?php
                                $prod = $con->query("SELECT prod_codi, prod_nombre, proc_precio FROM producto");
                                while ($p = $prod->fetch_assoc()) :
                                ?>
                                    <option value="<?= $p['prod_codi'] ?>" data-precio="<?= $p['proc_precio'] ?>"> <?= htmlspecialchars($p['prod_nombre']) ?> </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Cantidad</label>
                            <input type="number" name="cantidad" id="editCantidad" class="form-control" min="1" required>
                        </div>

                        <div class="mb-3">
                            <label>Precio Unitario</label>
                            <input type="number" name="precio" id="editPrecio" step="0.01" class="form-control" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary">Guardar Cambios</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const modal = document.getElementById("modalEditar");

            modal.addEventListener("show.bs.modal", event => {
                const btn = event.relatedTarget;

                document.getElementById("editIdDetalle").value = btn.getAttribute("data-id");
                document.getElementById("editCantidad").value = btn.getAttribute("data-cantidad");
                document.getElementById("editPrecio").value = btn.getAttribute("data-precio");

                const select = document.getElementById("editProducto");
                const idProd = btn.getAttribute("data-idprod");

                // Esperar que las opciones existan antes de asignar
                setTimeout(() => {
                    select.value = idProd;
                }, 100);
            });

            // Al cambiar de producto, actualizar precio automáticamente
            document.getElementById("editProducto").addEventListener("change", function() {
                const selectedOption = this.options[this.selectedIndex];
                const nuevoPrecio = selectedOption.getAttribute("data-precio");

                if (nuevoPrecio) {
                    document.getElementById("editPrecio").value = parseFloat(nuevoPrecio).toFixed(2);
                }
            });
        });
    </script>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include_once './includes/footer.php'; ?>
</body>

</html>