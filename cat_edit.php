<?php
include_once './conexion/cone.php';

$alert = '';
$id = intval($_GET['id'] ?? 0);
$tipo = $_GET['tipo'] ?? '';
$editado = false; // Indicador de edición completada
$nombre = '';

// Obtener datos actuales
if ($id > 0 && in_array($tipo, ['categoria', 'subcategoria'])) {
    if ($tipo === 'categoria') {
        $query = $con->prepare("SELECT nombre_categoria AS nombre FROM categoria WHERE idcategoria = ?");
    } else {
        $query = $con->prepare("SELECT nombre_subcategoria AS nombre FROM subcategoria WHERE idsubcategoria = ?");
    }
    $query->bind_param("i", $id);
    $query->execute();
    $query->bind_result($nombre);
    $query->fetch();
    $query->close();
}

// Procesar actualización
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nuevo_nombre = trim($_POST['nombre'] ?? '');

    if ($nuevo_nombre !== '' && $id > 0 && in_array($tipo, ['categoria', 'subcategoria'])) {
        if ($tipo === 'categoria') {
            $stmt = $con->prepare("UPDATE categoria SET nombre_categoria = ? WHERE idcategoria = ?");
        } else {
            $stmt = $con->prepare("UPDATE subcategoria SET nombre_subcategoria = ? WHERE idsubcategoria = ?");
        }
        $stmt->bind_param("si", $nuevo_nombre, $id);
        if ($stmt->execute()) {
            $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Actualizado correctamente.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar" onclick="window.location.href=\'opciones-prod.php\'"></button>
                      </div>';
            $nombre = $nuevo_nombre;
            $editado = true; // Ya no permitir editar
        } else {
            $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Error al actualizar.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar" onclick="window.location.href=\'opciones-prod.php\'"></button>
                      </div>';
        }
        $stmt->close();
    } else {
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    El nombre no puede estar vacío.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                  </div>';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<?php include_once './includes/head.php'; ?>

<body>
    <?php include_once './includes/header.php'; ?>
    <div class="content">
        <div class="container mt-5">
        <h2>Editar <?= $tipo === 'categoria' ? 'Categoría' : 'Subcategoría' ?></h2>

        <?php if (!empty($alert)) echo $alert; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nuevo nombre</label>
                <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($nombre) ?>" required <?= $editado ? 'readonly disabled' : '' ?>>
            </div>
            <button type="submit" class="btn btn-success" <?= $editado ? 'disabled' : '' ?>>Actualizar</button>
            <a href="listado.php" class="btn btn-secondary">Volver</a>
        </form>
    </div>
    </div>
    </div>

    <?php include_once './includes/footer.php'; ?>
</body>
</html>
