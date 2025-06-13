<?php
include_once './conexion/cone.php';

$alert = "";
$id = intval($_GET['id'] ?? 0);
$bloquearForm = false;

$sqlCat = "SELECT * FROM categoria";
$categorias = mysqli_query($con, $sqlCat);

$sqlSub = "SELECT * FROM subcategoria";
$subcategorias = mysqli_query($con, $sqlSub);

// Obtener datos del producto
$sqlProd = "SELECT p.*, s.idcategoria 
            FROM producto p 
            INNER JOIN subcategoria s ON p.idsubcategoria = s.idsubcategoria 
            WHERE p.prod_codi = ?";
$stmt = $con->prepare($sqlProd);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$producto = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"] ?? '';
    $descripcion = $_POST["descripcion"] ?? '';
    $modelo = $_POST["modelo"] ?? '';
    $marca = $_POST["marca"] ?? '';
    $idcategoria = intval($_POST["categoria"] ?? 0);
    $idsubcategoria = intval($_POST["subcategoria"] ?? 0);
    $precio = floatval($_POST["precio"] ?? 0);
    $stock = intval($_POST["stock"] ?? 0);

    if ($nombre && $descripcion && $modelo && $marca && $idcategoria && $idsubcategoria && $precio > 0 && $stock > 0) {
        $sqlUpdate = "UPDATE producto SET prod_nombre = ?, prod_descripcion = ?, prod_model = ?, prod_marca = ?, idsubcategoria = ?, proc_precio = ?, prod_stock = ? WHERE prod_codi = ?";
        $stmt = $con->prepare($sqlUpdate);
        $stmt->bind_param("ssssidii", $nombre, $descripcion, $modelo, $marca, $idsubcategoria, $precio, $stock, $id);

        if ($stmt->execute()) {
            $alert = '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                Producto actualizado correctamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                onclick="window.location.href=\'producto.php\'"></button>
            </div>';
            $bloquearForm = true;
        } else {
            $alert = '<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                Error al actualizar producto: ' . $stmt->error . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                onclick="window.location.href=\'producto.php\'"></button>
            </div>';
        }
        $stmt->close();

        // Volver a cargar los datos actualizados
        $stmt = $con->prepare($sqlProd);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $producto = $result->fetch_assoc();
        $stmt->close();
    } else {
        $alert = '<div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
            Por favor completa todos los campos correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
            onclick="window.location.href=\'producto_edit.php?id=' . $id . '\'"></button>
        </div>';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<?php include_once './includes/head.php'; ?>

<body>
<?php include_once './includes/header.php'; ?>

<div class="container mt-4">
    <h2>Editar Producto</h2>

    <?= $alert ?>

    <?php if (!empty($producto)) : ?>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($producto['prod_nombre']) ?>" <?= $bloquearForm ? 'disabled' : '' ?>>
        </div>
        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <input type="text" class="form-control" name="descripcion" value="<?= htmlspecialchars($producto['prod_descripcion']) ?>" <?= $bloquearForm ? 'disabled' : '' ?>>
        </div>
        <div class="mb-3">
            <label class="form-label">Modelo</label>
            <input type="text" class="form-control" name="modelo" value="<?= htmlspecialchars($producto['prod_model']) ?>" <?= $bloquearForm ? 'disabled' : '' ?>>
        </div>
        <div class="mb-3">
            <label class="form-label">Marca</label>
            <input type="text" class="form-control" name="marca" value="<?= htmlspecialchars($producto['prod_marca']) ?>" <?= $bloquearForm ? 'disabled' : '' ?>>
        </div>
        <div class="mb-3">
            <label class="form-label">Categoría</label>
            <select class="form-select" name="categoria" <?= $bloquearForm ? 'disabled' : '' ?>>
                <option value="">-- Selecciona Categoría --</option>
                <?php while ($cat = mysqli_fetch_assoc($categorias)) : ?>
                    <option value="<?= $cat['idcategoria'] ?>" <?= ($cat['idcategoria'] == $producto['idcategoria']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nombre_categoria']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Subcategoría</label>
            <select class="form-select" name="subcategoria" <?= $bloquearForm ? 'disabled' : '' ?>>
                <option value="">-- Selecciona Subcategoría --</option>
                <?php mysqli_data_seek($subcategorias, 0); ?>
                <?php while ($sub = mysqli_fetch_assoc($subcategorias)) : ?>
                    <option value="<?= $sub['idsubcategoria'] ?>" <?= ($sub['idsubcategoria'] == $producto['idsubcategoria']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($sub['nombre_subcategoria']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Precio</label>
            <input type="number" class="form-control" name="precio" step="0.01" value="<?= htmlspecialchars($producto['proc_precio']) ?>" <?= $bloquearForm ? 'disabled' : '' ?>>
        </div>
        <div class="mb-3">
            <label class="form-label">Stock</label>
            <input type="number" class="form-control" name="stock" value="<?= htmlspecialchars($producto['prod_stock']) ?>" <?= $bloquearForm ? 'disabled' : '' ?>>
        </div>

        <?php if (!$bloquearForm): ?>
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="producto.php" class="btn btn-secondary">Cancelar</a>
        <?php endif; ?>
    </form>
    <?php else: ?>
        <div class="alert alert-danger mt-4">Producto no encontrado.</div>
    <?php endif; ?>
</div>

<?php include_once './includes/footer.php'; ?>
</body>
</html>
