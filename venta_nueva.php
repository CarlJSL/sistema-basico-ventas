<?php
include_once './conexion/cone.php';

// Obtener clientes activos
$clientes = $con->query("SELECT client_id, client_nombres FROM tb_cliente ORDER BY client_nombres ASC");

// Obtener productos con stock > 0
$productos = $con->query("SELECT prod_codi, prod_nombre, proc_precio, prod_stock 
                          FROM producto 
                          WHERE prod_stock > 0 
                          ORDER BY prod_nombre ASC");
?>

<!DOCTYPE html>
<html lang="es">
<?php include_once './includes/head.php'; ?>

<body>
    <?php include_once './includes/header.php'; ?>

    <div class="container mt-5">
        <h3>Registrar Venta</h3>
        <form action="venta_add.php" method="POST" onsubmit="return validarVenta()">
            <div class="mb-3">
                <label for="cliente" class="form-label">Cliente</label>
                <select name="cliente_id" class="form-select" required>
                    <option value="">Seleccione un cliente</option>
                    <?php while ($c = $clientes->fetch_assoc()) : ?>
                        <option value="<?= $c['client_id'] ?>"><?= htmlspecialchars($c['client_nombres']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Agregar productos -->
            <div class="row g-2 align-items-end mb-3">
                <div class="col-md-5">
                    <label>Producto</label>
                    <select class="form-select" id="productoSelect">
                        <option value="">Seleccione</option>
                        <?php while ($p = $productos->fetch_assoc()) : ?>
                            <?php $data = htmlspecialchars(json_encode($p)); ?>
                            <option value="<?= $p['prod_codi'] ?>" data-prod="<?= $data ?>">
                                <?= $p['prod_nombre'] ?> - S/.<?= $p['proc_precio'] ?> (Stock: <?= $p['prod_stock'] ?>)
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

            <!-- Tabla productos -->
            <table class="table table-bordered text-center" id="tablaDetalle">
                <thead class="table-light">
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

            <input type="hidden" name="productos" id="productosInput">
            <div class="text-end mb-3">
                <strong>Total: S/. <span id="totalVenta">0.00</span></strong>
            </div>

            <button type="submit" class="btn btn-primary">Registrar Venta</button>
            <a href="venta.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script>
        let productos = [];
        let total = 0;

        function agregarProducto() {
            const select = document.getElementById("productoSelect");
            const cantidad = parseInt(document.getElementById("cantidad").value);
            const prodData = select.options[select.selectedIndex].getAttribute("data-prod");

            if (!prodData || cantidad < 1) return;

            const producto = JSON.parse(prodData);

            if (productos.some(p => p.id === producto.prod_codi)) {
                alert("Este producto ya fue agregado.");
                return;
            }

            if (cantidad > producto.prod_stock) {
                alert("Stock insuficiente.");
                return;
            }

            const subtotal = (producto.proc_precio * cantidad).toFixed(2);
            productos.push({
                id: producto.prod_codi,
                nombre: producto.prod_nombre,
                cantidad,
                precio: parseFloat(producto.proc_precio),
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
                <td>S/. ${p.precio}</td>
                <td>S/. ${p.subtotal}</td>
                <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarProducto(${i})">Quitar</button></td>
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

    <?php include_once './includes/footer.php'; ?>
</body>

</html>