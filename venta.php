<?php
include_once './conexion/cone.php';

// Clientes activos
$clientes = $con->query("SELECT client_id, client_nombres FROM tb_cliente ORDER BY client_nombres ASC");

// Productos con stock disponible
$productos = $con->query("SELECT prod_codi, prod_nombre, proc_precio, prod_stock FROM producto WHERE prod_stock > 0 ORDER BY prod_nombre ASC");

// Lista de ventas
$ventas = $con->query("SELECT v.*, c.client_nombres FROM ventas v JOIN tb_cliente c ON v.cliente_id = c.client_id ORDER BY v.id DESC");
?>

<!DOCTYPE html>
<html lang="es">
<?php include_once './includes/head.php'; ?>

<body>
    <?php include_once './includes/header.php'; ?>

    <div class="content">
        <div class="container mt-5">
            <div class="d-flex justify-content-between mb-3 align-items-center">
                <h3>Listado de Ventas</h3>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalVenta">
                    <i class="fa fa-plus"></i> Nueva Venta
                </button>
            </div>

            <?php if (isset($_GET['msg'])) : ?>
                <div class="alert alert-<?= $_GET['msg'] === 'incompleto' ? 'warning' : 'success' ?>">
                    <?php
                    if ($_GET['msg'] == 'registrado') echo "Venta registrada correctamente.";
                    elseif ($_GET['msg'] == 'incompleto') echo "Por favor completa todos los campos.";
                    ?>
                </div>
            <?php endif; ?>

            <table class="table table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Fecha</th>
                        <th>Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($v = $ventas->fetch_assoc()) : ?>
                        <tr>
                            <td><?= $v['id'] ?></td>
                            <td><?= htmlspecialchars($v['client_nombres']) ?></td>
                            <td>S/. <?= number_format($v['total'], 2) ?></td>
                            <td><?= $v['fecha'] ?></td>
                            <td>
                                <a href="venta_detalle.php?id=<?= $v['id'] ?>" class="btn btn-info btn-sm">
                                    Ver
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL REGISTRAR VENTA -->
    <div class="modal fade" id="modalVenta" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="venta_add.php" method="POST" class="modal-content" onsubmit="return validarVenta()">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Nueva Venta</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <!-- Cliente -->
                    <div class="mb-3">
                        <label>Cliente</label>
                        <select name="cliente_id" class="form-select" required>
                            <option value="">Seleccione</option>
                            <?php while ($c = $clientes->fetch_assoc()) : ?>
                                <option value="<?= $c['client_id'] ?>"><?= htmlspecialchars($c['client_nombres']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <!-- Productos -->
                    <div class="row g-2 align-items-end">
                        <div class="col-md-5">
                            <label>Producto</label>
                            <select id="productoSelect" class="form-select">
                                <option value="">Seleccione</option>
                                <?php while ($p = $productos->fetch_assoc()) : ?>
                                    <?php $json = htmlspecialchars(json_encode($p)); ?>
                                    <option value="<?= $p['prod_codi'] ?>" data-prod="<?= $json ?>">
                                        <?= $p['prod_nombre'] ?> - S/.<?= $p['proc_precio'] ?> (<?= $p['prod_stock'] ?> unid.)
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Cantidad</label>
                            <input type="number" class="form-control" id="cantidad" min="1" value="1">
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-success w-100" onclick="agregarProducto()">Agregar</button>
                        </div>
                    </div>

                    <!-- Detalle -->
                    <div class="table-responsive mt-3">
                        <table class="table table-bordered text-center" id="tablaDetalle">
                            <thead class="table-light">
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Subtotal</th>
                                    <th>Quitar</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <input type="hidden" name="productos" id="productosInput">
                    <div class="text-end">
                        <strong>Total: S/. <span id="totalVenta">0.00</span></strong>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Registrar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let productos = [];
        let total = 0;

        function agregarProducto() {
            const select = document.getElementById("productoSelect");
            const cantidad = parseInt(document.getElementById("cantidad").value);
            const data = select.options[select.selectedIndex].getAttribute("data-prod");

            if (!data || cantidad < 1) return;

            const prod = JSON.parse(data);

            if (productos.some(p => p.id === prod.prod_codi)) {
                alert("Producto ya agregado.");
                return;
            }

            if (cantidad > prod.prod_stock) {
                alert("Stock insuficiente.");
                return;
            }

            const subtotal = (prod.proc_precio * cantidad).toFixed(2);
            productos.push({
                id: prod.prod_codi,
                nombre: prod.prod_nombre,
                cantidad,
                precio: parseFloat(prod.proc_precio),
                subtotal
            });

            total += parseFloat(subtotal);
            actualizarTabla();
        }

        function actualizarTabla() {
            const tbody = document.querySelector("#tablaDetalle tbody");
            tbody.innerHTML = "";
            productos.forEach((p, i) => {
                tbody.innerHTML += `
            <tr>
                <td>${p.nombre}</td>
                <td>${p.cantidad}</td>
                <td>S/. ${p.precio.toFixed(2)}</td>
                <td>S/. ${p.subtotal}</td>
                <td><button class="btn btn-danger btn-sm" onclick="eliminarProducto(${i})">Quitar</button></td>
            </tr>`;
            });

            document.getElementById("totalVenta").textContent = total.toFixed(2);
            document.getElementById("productosInput").value = JSON.stringify(productos);
        }

        function eliminarProducto(index) {
            total -= parseFloat(productos[index].subtotal);
            productos.splice(index, 1);
            actualizarTabla();
        }

        function validarVenta() {
            if (productos.length === 0) {
                alert("Debe agregar al menos un producto.");
                return false;
            }
            return true;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include_once './includes/footer.php'; ?>
</body>

</html>